# Nivora

> **Take control of your money, one transaction at a time.**

Nivora is a personal finance management web application designed to give
users a simple and clear way to track their money.

**� MIT LICENSE** — Open source software with attribution required. See the [LICENSE](LICENSE) file.

The project focuses on the fundamentals of personal finance management:
accounts, income, expenses, categories and balances --- without trying
to turn the first release into an unnecessarily complex financial
platform.

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

The goal is to make the most important financial information visible
without overwhelming the user.

## 🧱 Tech Stack

-   **PHP**
-   **CodeIgniter 4**
-   **MySQL**
-   **MVC**
-   **Composer**

The project is intentionally backend-focused in its initial stage, using
CodeIgniter 4 to build a structured and maintainable web application.

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

## 💰 Accurate Money Handling

Nivora does not rely on floating-point values to represent money.

Instead, monetary values are stored as integer units:

``` text
€19.99 → 1999
€5.00  → 500
€0.50  → 50
```

This avoids common floating-point precision issues when calculating
financial values.

## 🗺️ Roadmap

Nivora is being developed progressively.

### MVP

-   Authentication
-   Financial accounts
-   Categories
-   Income and expenses
-   Dashboard
-   Ownership and authorization

### Future

-   Transfers
-   Transaction search and filters
-   Pagination
-   Budgets
-   Recurring transactions
-   Financial reports
-   REST API
-   Redis
-   Notifications
-   Background jobs
-   Docker
-   CI/CD

Advanced features will only be introduced when they provide a real
benefit to the application.

## 🎯 Project Goals

Nivora is built around three main goals:

**Simplicity**\
Keep the core experience easy to understand and use.

**Reliability**\
Make financial calculations and data ownership predictable and secure.

**Progressive development**\
Start with a small, functional product and evolve it based on actual
requirements.

> **Start simple. Earn the complexity.**

## 📸 Project Status

Nivora is currently under active development.

The first milestone is a functional MVP covering authentication,
accounts, categories, transactions and financial overview.

## 📄 License

MIT License — This project is open source and available under the MIT License. You are free to use, modify, and distribute this software, as long as you include the original copyright notice and license.

See the [LICENSE](LICENSE) file for complete details.
