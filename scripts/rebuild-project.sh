#!/usr/bin/env bash

set -Eeuo pipefail

ROOT_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

DRY_RUN=false

usage() {
    cat <<'EOF'
Usage: ./scripts/rebuild-project.sh [--dry-run] [--help]

Reset the configured development database to the current migrations and clear
generated CodeIgniter runtime files. Source files, .env, and uploads are kept.
EOF
}

for argument in "$@"; do
    case "$argument" in
        --dry-run) DRY_RUN=true ;;
        --help|-h) usage; exit 0 ;;
        *) printf 'Unknown option: %s\n' "$argument" >&2; usage >&2; exit 2 ;;
    esac
done

if [[ ! -f spark || ! -f composer.json || ! -f example.env ]]; then
    printf 'Run this script from a complete Nivora project checkout.\n' >&2
    exit 1
fi

if ! command -v php >/dev/null 2>&1; then
    printf 'PHP is required but was not found in PATH.\n' >&2
    exit 1
fi

if ! command -v composer >/dev/null 2>&1 \
    && { [[ ! -f vendor/autoload.php ]] || [[ ! -f vendor/codeigniter4/shield/src/Database/Migrations/2020-12-28-223112_create_auth_tables.php ]]; }; then
    printf 'Composer is required to install the project dependencies.\n' >&2
    exit 1
fi

if [[ "$DRY_RUN" == true ]]; then
    printf 'Dry run: no files or database will be changed.\n'
    [[ -f .env ]] || printf 'Would create .env from example.env and generate an encryption key.\n'
    if [[ ! -f vendor/autoload.php || ! -f vendor/codeigniter4/shield/src/Database/Migrations/2020-12-28-223112_create_auth_tables.php ]]; then
        printf 'Would run composer install, including CodeIgniter Shield.\n'
    fi
    printf 'Would verify Shield is installed and its migrations are available before running migrations.\n'
    printf 'Would run php spark migrate:refresh --all (development/testing only).\n'
    printf 'Would clear writable/cache, writable/debugbar, writable/logs, and writable/session, preserving index.html and .htaccess.\n'
    printf 'Would preserve source files, .env, database uploads, and writable/uploads.\n'
    exit 0
fi

if [[ ! -t 0 ]]; then
    printf 'This reset requires an interactive terminal for its destructive confirmation.\n' >&2
    exit 1
fi

cat <<'EOF'
WARNING: This will erase data in the database configured for this project by
rolling back and rerunning its migrations. It will also remove generated cache,
debugbar, log, and session files.

Source files, .env, uploaded files, and other databases are not removed.
EOF
printf 'Type RESET to continue: '
IFS= read -r confirmation
if [[ "$confirmation" != RESET ]]; then
    printf 'Reset cancelled.\n'
    exit 1
fi

if [[ ! -f .env ]]; then
    cp example.env .env
    printf '\nCI_ENVIRONMENT = development\n' >> .env
    printf 'Created .env from example.env. Configure the database credentials before continuing.\n'
fi

if [[ ! -f vendor/autoload.php ]] \
    || [[ ! -f vendor/codeigniter4/shield/src/Database/Migrations/2020-12-28-223112_create_auth_tables.php ]]; then
    printf 'Installing project dependencies, including CodeIgniter Shield...\n'
    composer install --no-interaction --prefer-dist
fi

if [[ ! -f vendor/codeigniter4/shield/src/Database/Migrations/2020-12-28-223112_create_auth_tables.php ]]; then
    printf 'CodeIgniter Shield is not installed correctly; refusing to run migrations.\n' >&2
    exit 1
fi

printf 'CodeIgniter Shield is installed; its auth migrations will be included.\n'

if ! grep -Eq '^[[:space:]]*database\.default\.database[[:space:]]*=[[:space:]]*[^[:space:]#]+' .env \
    && [[ -z "$(printenv 'database.default.database' || true)" ]]; then
    printf 'Configure database.default.database in .env, then run this script again.\n' >&2
    exit 1
fi

environment_info="$(php spark env 2>&1 || true)"
if ! grep -Eq 'Your environment is currently set as (development|testing)\.' <<<"$environment_info"; then
    printf 'Refusing to reset: CodeIgniter must report development or testing environment.\n' >&2
    exit 1
fi

if ! grep -Eq '^[[:space:]]*encryption\.key[[:space:]]*=[[:space:]]*[^[:space:]#]+' .env \
    && [[ -z "$(printenv 'encryption.key' || true)" ]]; then
    php spark key:generate
fi

printf 'Refreshing all migrations...\n'
migration_output=''
migration_status=0
migration_output="$(php spark migrate:refresh --all 2>&1)" || migration_status=$?
printf '%s\n' "$migration_output"

if [[ "$migration_status" -ne 0 ]] \
    || grep -Eq 'Migration failed!|CRITICAL|DatabaseException|mysqli_sql_exception' <<<"$migration_output" \
    || ! grep -Fq 'Migrations complete.' <<<"$migration_output"; then
    printf 'Reset failed while refreshing migrations; runtime files were not cleared.\n' >&2
    exit 1
fi

migration_status_output="$(php spark migrate:status 2>&1)" || {
    printf '%s\n' "$migration_status_output" >&2
    printf 'Reset failed: could not verify migration status; runtime files were not cleared.\n' >&2
    exit 1
}
if ! awk -F'|' '
    /^\|/ && $2 !~ /^[[:space:]]*$/ && $2 !~ /Namespace/ && $4 !~ /^[[:space:]]*$/ {
        if ($5 ~ /---/) failed = 1
        found = 1
    }
    END { exit (!found || failed) }
' <<<"$migration_status_output"; then
    printf '%s\n' "$migration_status_output" >&2
    printf 'Reset failed: one or more migrations are not applied; runtime files were not cleared.\n' >&2
    exit 1
fi

if ! php spark db:table users | grep -Eq '(^|[[:space:]])name([[:space:]]|$)'; then
    printf 'Reset failed: the Shield users table does not contain the required name column.\n' >&2
    printf 'Run `php spark migrate --all` and inspect the migration output.\n' >&2
    exit 1
fi

for runtime_dir in cache debugbar logs session; do
    directory="writable/$runtime_dir"
    mkdir -p "$directory"
    find "$directory" -mindepth 1 -maxdepth 1 \
        ! -name index.html \
        ! -name .htaccess \
        -exec rm -rf -- {} +
done

printf 'Project database reset and generated runtime files cleared.\n'
