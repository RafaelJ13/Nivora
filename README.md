# Nivora

> **Take control of your money, one transaction at a time.**

Nivora is a personal finance management web application that helps users
organize accounts, record income and expenses, and understand their current
financial position at a glance.

**� MIT LICENSE** — Open source software with attribution required. See the [LICENSE](LICENSE) file.

The MVP focuses on the fundamentals of personal finance management:
accounts, income, expenses, categories and balances --- without unnecessary
complexity.

## ✨ Features

### 🔐 Authentication

-   User registration
-   Login and logout
-   Secure password hashing
-   Session-based authentication

### 🏦 Financial Accounts

Create and manage multiple accounts, such as:

``` text
Millennium
€2,000

Revolut
€500

Cash
€150
```

### 🏷️ Categories

Organize transactions using categories such as:

``` text
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

``` text
INCOME
EXPENSE
```

### 📊 Dashboard

Get a quick overview of your finances:

``` text
Balance
€2,340.50

Income
€2,800

Expenses
€459.50

Recent transactions
```

The dashboard also shows the current balance of each account, the current
month's income and expenses, savings, recent transactions and expenses by
category.

## 🧱 Tech Stack

-   **PHP**
-   **CodeIgniter 4**
-   **MySQL**
-   **MVC**
-   **Composer**

The project is intentionally backend-focused in its initial stage, using
CodeIgniter 4 to build a structured and maintainable web application.

## Instalação local

### Requisitos

- PHP **8.2 ou superior**, com as extensões `intl`, `mbstring` e `mysqli`
- [Composer](https://getcomposer.org/)
- MySQL ou MariaDB em execução
- Git, se ainda não tiveres o código-fonte

Confirma a versão do PHP e as extensões carregadas:

```sh
php -v
php -m
composer --version
```

Na lista de `php -m` devem aparecer `intl`, `mbstring` e `mysqli`. Se alguma
estiver em falta, instala/ativa a extensão para a versão de PHP que o terminal
está a usar e volta a abrir o terminal.

### 1. Obter o projeto e instalar as dependências

Clona o repositório e entra na pasta:

```sh
git clone https://github.com/RafaelJ13/Nivora.git
cd Nivora
```

Instala as dependências PHP. O Composer cria a pasta `vendor/` a partir do
`composer.lock`:

```sh
composer install
```

Se já tens o projeto clonado, começa por `cd` para a respetiva pasta e executa
`composer install`.

### 2. Criar a base de dados e um utilizador MySQL

Inicia sessão no MySQL como administrador:

```sh
mysql -u root -p
```

No prompt MySQL, cria uma base de dados e um utilizador só para a aplicação.
Substitui `uma_palavra-passe-forte` por uma palavra-passe tua:

```sql
CREATE DATABASE nivora
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

CREATE USER 'nivora_app'@'localhost'
    IDENTIFIED BY 'uma_palavra-passe-forte';

GRANT ALL PRIVILEGES ON nivora.* TO 'nivora_app'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Se o servidor MySQL estiver noutra máquina ou a aplicação se ligar por TCP,
ajusta o hostname autorizado do utilizador e o `hostname` no `.env` para
corresponder à tua configuração.

### 3. Configurar o ambiente e a ligação à base de dados

Cria o ficheiro local `.env` a partir do exemplo:

```sh
cp example.env .env
```

Abre `.env`, remove o `#` das definições abaixo e preenche os valores com os
dados usados no passo anterior:

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = nivora
database.default.username = nivora_app
database.default.password = 'uma_palavra-passe-forte'
database.default.DBDriver = MySQLi
database.default.port = 3306
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_general_ci
```

Não publiques nem commits o `.env`: pode conter credenciais. O ficheiro já está
ignorado pelo Git.

Gera uma chave de encriptação para esta instalação:

```sh
php spark key:generate
```

O comando grava `encryption.key` no `.env`. Se já existir uma chave que queiras
manter, não uses `--force`.

### 4. Criar as tabelas

Aplica as migrations da aplicação e do CodeIgniter Shield:

```sh
php spark migrate --all
```

O comando cria as tabelas sem apagar dados existentes. Para confirmar o estado:

```sh
php spark migrate:status
```

### 5. Iniciar e usar a aplicação

Arranca o servidor de desenvolvimento:

```sh
php spark serve
```

Abre <http://localhost:8080>, cria uma conta através do registo e inicia sessão.
Não é necessário executar seeders para começar a usar a aplicação.

### Problemas comuns

- **`Access denied`**: confirma `username`, `password` e se o utilizador MySQL
  tem privilégios sobre a base `nivora`.
- **`Unknown database`**: confirma que criaste a base de dados e que o nome em
  `database.default.database` coincide.
- **`Class "mysqli" not found`**: ativa/instala a extensão `mysqli` no PHP usado
  pelo terminal.
- **Não encontra `vendor/autoload.php`**: executa `composer install` na pasta
  raiz do projeto.
- **Base de dados sem tabelas**: configura primeiro a ligação e volta a executar
  `php spark migrate --all`.

## Limpar e reconstruir a instalação local

Para reiniciar a base de dados configurada e apagar ficheiros runtime gerados,
executa:

```sh
./scripts/rebuild-project.sh
```

O script pede para escrever `RESET`, executa `migrate:refresh` em todas as
migrations e limpa cache, debugbar, logs e sessões. **Isto apaga os dados da
base de dados configurada**; usa-o apenas num ambiente local `development` ou
`testing`. Mantém o código-fonte, `.env`, uploads e outras bases de dados.
Confirma o que será feito sem alterações com:

```sh
./scripts/rebuild-project.sh --dry-run
```

Numa instalação nova, o script pode criar `.env` a partir de `example.env` e
instalar dependências em falta; ainda assim, tens de criar a base de dados,
preencher as credenciais em `.env` e voltar a executá-lo para aplicar as
migrations.

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

A user should only ever be able to access and manage their own financial
data.

## 💰 Money Handling

Users enter and see amounts in euros. Nivora keeps calculations precise
internally so balances and transaction totals remain reliable.

Instead, monetary values are stored as integer units:

``` text
€19.99 → 1999
€5.00  → 500
€0.50  → 50
```

This avoids common floating-point precision issues when calculating
financial values.

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
-   Transaction search and filters✅
-   Pagination✅
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


Advanced features will only be introduced when they provide a real
benefit to the application.

## 🎯 Product Principles

Nivora is built around three main goals:

**Simplicity**\
Keep the core experience easy to understand and use.

**Reliability**\
Make financial calculations and data ownership predictable and secure.

**Clarity**\
Show the information people need to understand their money without noise.

**Control**\
Keep financial records organized, private and under the user's control.

**Progressive development**\
Start with a small, useful product and evolve it based on real needs.

> **See your money clearly. Make better decisions.**

## 📸 Project Status

The Nivora MVP is complete and covers authentication, accounts, categories,
transactions and the financial overview needed for everyday tracking.

## 📄 License

MIT License — This project is open source and available under the MIT License. You are free to use, modify, and distribute this software, as long as you include the original copyright notice and license.

See the [LICENSE](LICENSE) file for complete details.
