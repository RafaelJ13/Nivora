# Nivora

> **Take control of your money, one transaction at a time.**

Nivora is a personal finance management web application that helps users organize accounts, record income and expenses, and understand their current financial position at a glance.

**⚖️ MIT LICENSE** — Open source software with attribution required. See the [LICENSE](LICENSE) file.

The MVP focuses on the fundamentals of personal finance management: accounts, income, expenses, categories, and balances — without unnecessary complexity.

## ✨ Features

### 🔐 Authentication

-   User registration
-   Login and logout
-   Secure password hashing
-   Session-based authentication

### 🏦 Financial Accounts

Create and manage multiple accounts, such as:

```text
Millennium
€2,000

Revolut
€500

Cash
€150
```

### 🏷️ Categories

Organize transactions using categories such as:

```text
Food
Transport
Housing
Entertainment
Shopping
Salary
```

### 💸 Transactions

Track the money coming in and going out.

Each transaction includes:

-   Amount
-   Type
-   Account
-   Category
-   Description
-   Date

Supported transaction types:

```text
INCOME
EXPENSE
```

### 📊 Dashboard

Get a quick overview of your finances:

```text
Balance
€2,340.50

Income
€2,800

Expenses
€459.50

Recent transactions
```

The dashboard also shows the current balance of each account, the current month's income and expenses, savings, recent transactions, and expenses by category.

## 🧱 Tech Stack

-   **PHP**
-   **CodeIgniter 4**
-   **MySQL**
-   **MVC**
-   **Composer**

The project is intentionally backend-focused in its initial stage, using CodeIgniter 4 to build a structured and maintainable web application.

## Local Installation

### Requirements

-   PHP **8.2 or higher**, with the `intl`, `mbstring`, and `mysqli` extensions
-   [Composer](https://getcomposer.org/)
-   MySQL or MariaDB running
-   Git, if you don't already have the source code

Verify your PHP version and loaded extensions:

```sh
php -v
php -m
composer --version
```

In the `php -m` list, `intl`, `mbstring`, and `mysqli` must appear. If any are missing, install/enable the extension for the PHP version your terminal is using and restart the terminal.

### 1. Get the project and install dependencies

Clone the repository and enter the directory:

```sh
git clone https://github.com/RafaelJ13/Nivora.git
cd Nivora
```

Install the PHP dependencies. Composer will create the `vendor/` folder based on the `composer.lock` file:

```sh
composer install
```

If you already have the project cloned, start by using `cd` to enter the respective directory and run `composer install`.

### 2. Create the database and a MySQL user

Log in to MySQL as an administrator:

```sh
mysql -u root -p
```

At the MySQL prompt, create a database and a user just for the application. Replace `a_strong_password` with a password of your choice:

```sql
CREATE DATABASE nivora
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

CREATE USER 'nivora_app'@'localhost'
    IDENTIFIED BY 'a_strong_password';

GRANT ALL PRIVILEGES ON nivora.* TO 'nivora_app'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

If your MySQL server is on another machine or the application connects via TCP, adjust the user's authorized hostname and the `hostname` in the `.env` file to match your setup.

### 3. Configure the environment and database connection

Create the local `.env` file from the example:

```sh
cp example.env .env
```

Open `.env`, remove the `#` from the definitions below, and fill in the values with the details used in the previous step:

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = nivora
database.default.username = nivora_app
database.default.password = 'a_strong_password'
database.default.DBDriver = MySQLi
database.default.port = 3306
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_general_ci
```

Do not publish or commit the `.env` file: it may contain credentials. The file is already ignored by Git.

Generate an encryption key for this installation:

```sh
php spark key:generate
```

The command saves `encryption.key` in `.env`. If a key already exists that you want to keep, do not use `--force`.

### 4. Create the tables

Apply the migrations for the application and CodeIgniter Shield:

```sh
php spark migrate --all
```

This command creates the tables without deleting existing data. To confirm the status:

```sh
php spark migrate:status
```

### 5. Start and use the application

Start the development server:

```sh
php spark serve
```

Open <http://localhost:8080>, create an account via registration, and log in. It is not necessary to run seeders to start using the application.

### Common Issues

-   **`Access denied`**: Confirm `username`, `password`, and whether the MySQL user has privileges on the `nivora` database.
-   **`Unknown database`**: Confirm that you created the database and that the name in `database.default.database` matches.
-   **`Class "mysqli" not found`**: Enable/install the `mysqli` extension in the PHP version used by the terminal.
-   **Cannot find `vendor/autoload.php`**: Run `composer install` in the project's root folder.
-   **Database with no tables**: Configure the connection first and run `php spark migrate --all` again.

## Clean and Rebuild Local Installation

To reset the configured database and delete generated runtime files, execute:

```sh
./scripts/rebuild-project.sh
```

The script asks you to type `RESET`, runs `migrate:refresh` on all migrations, and clears the cache, debugbar, logs, and sessions. **This deletes the data from the configured database**; only use it in a local `development` or `testing` environment. It preserves the source code, `.env`, uploads, and other databases. Confirm what will be done without making changes with:

```sh
./scripts/rebuild-project.sh --dry-run
```

On a fresh installation, the script can create `.env` from `example.env` and install missing dependencies; however, you still need to create the database, fill in the credentials in `.env`, and run it again to apply the migrations.

## 🔒 Security

Nivora treats financial data as private by design.

The application includes security considerations such as:

-   Password hashing
-   CSRF protection
-   Input validation
-   Output escaping
-   Session security
-   Authorization
-   User ownership
-   Mass-assignment protection

A user should only ever be able to access and manage their own financial data.

## 💰 Money Handling

Users enter and see amounts in euros. Nivora keeps calculations precise internally so balances and transaction totals remain reliable.

Instead, monetary values are stored as integer units:

```text
€19.99 → 1999
€5.00  → 500
€0.50  → 50
```

This avoids common floating-point precision issues when calculating financial values.

## 🗺️ Roadmap

The MVP is complete and covers the core personal finance workflow.

### Completed MVP ✅

-   Authentication ✅
-   Financial accounts ✅
-   Categories ✅
-   Income and expenses ✅
-   Dashboard ✅
-   Ownership and authorization ✅
-   Account balances updated by their transactions ✅
-   Monthly dashboard summary ✅
-   Expense breakdown by category ✅

### Future

-   Transfers ✅
-   Transaction search and filters ✅
-   Pagination ✅
-   Oauth integration
-   MCP and OAuth system
-   Budgets
-   Recurring transactions
-   Financial reports
-   REST API
-   Redis
-   Notifications
-   Background jobs
-   Docker
-   CI/CD
-   Bank integration

Advanced features will only be introduced when they provide a real benefit to the application.

## 🎯 Product Principles

Nivora is built around three main goals:

**Simplicity**\
Keep the core experience easy to understand and use.

**Reliability**\
Make financial calculations and data ownership predictable and secure.

**Clarity**\
Show the information people need to understand their money without noise.

**Control**\
Keep financial records organized, private, and under the user's control.

**Progressive development**\
Start with a small, useful product and evolve it based on real needs.

> **See your money clearly. Make better decisions.**

## 📸 Project Status

The Nivora MVP is complete and covers authentication, accounts, categories, transactions, and the financial overview needed for everyday tracking.

## 📄 License

MIT License — This project is open source and available under the MIT License. You are free to use, modify, and distribute this software, as long as you include the original copyright notice and license.

See the [LICENSE](LICENSE) file for complete details.
