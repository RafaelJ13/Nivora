# Nivora --- Development Guide

> **Build a small financial application. Understand every layer. Then
> make it better.**

Este documento é o guia técnico e de desenvolvimento do **Nivora**, uma
aplicação web para gestão de finanças pessoais.

O projeto começa deliberadamente pequeno. O objetivo não é construir
inicialmente uma plataforma financeira completa, mas aprender e aplicar
na prática conceitos de backend, bases de dados, autenticação,
segurança, arquitetura e testing com CodeIgniter 4.

------------------------------------------------------------------------

# 1. Objetivo do projeto

O **Nivora** será uma aplicação web para gestão de finanças pessoais.

O objetivo principal **não é construir inicialmente uma aplicação
financeira completa**.

O objetivo é começar com um MVP pequeno que permita aprender e aplicar,
na prática:

-   CodeIgniter 4
-   PHP
-   MySQL
-   MVC
-   Routing
-   Controllers
-   Models
-   Migrations
-   Seeders
-   Forms
-   Validation
-   Sessions
-   Authentication
-   Relationships
-   Queries
-   Testing

Depois do MVP estar funcional, a aplicação será evoluída
progressivamente.

A regra principal será:

> **Não implementar uma funcionalidade antes de existir uma razão para
> ela.**

------------------------------------------------------------------------

# 2. Visão final

A aplicação poderá eventualmente permitir:

``` text
Nivora
│
├── Authentication
│
├── Accounts
│   ├── Bank accounts
│   ├── Cash
│   └── Credit cards
│
├── Transactions
│   ├── Income
│   ├── Expenses
│   └── Transfers
│
├── Categories
│
├── Budgets
│
├── Recurring transactions
│
├── Financial goals
│
├── Reports
│
├── Dashboard
│
├── Notifications
│
├── REST API
│
├── Redis
│
└── Advanced features
```

**Mas isto NÃO será implementado inicialmente.**

------------------------------------------------------------------------

# 3. MVP

O MVP deve ser pequeno.

O objetivo é conseguir chegar rapidamente a:

``` text
Register
   ↓
Login
   ↓
Dashboard
   ↓
Create Account
   ↓
Create Category
   ↓
Create Transaction
   ↓
View Balance
```

## Funcionalidades do MVP

### Authentication

-   Register
-   Login
-   Logout
-   Session
-   Password hashing

### Accounts

Um utilizador pode criar contas financeiras.

Exemplos:

``` text
Millennium
€2,000

Cash
€150

Revolut
€500
```

### Categories

Exemplos:

``` text
Food
Transport
Salary
Entertainment
Rent
Shopping
```

### Transactions

Uma transação terá:

``` text
Amount
Type
Account
Category
Description
Date
```

Tipos:

``` text
INCOME
EXPENSE
```

### Dashboard

Mostrar:

``` text
Total balance
Total income
Total expenses
Recent transactions
```

------------------------------------------------------------------------

# 4. O que NÃO entra no MVP

Não implementar inicialmente:

-   Redis
-   REST API
-   Docker
-   Email
-   Notifications
-   Budgets
-   Recurring transactions
-   Financial goals
-   Attachments
-   Bank integrations
-   OAuth
-   Multi-currency
-   Cryptocurrency
-   AI
-   Complex charts
-   Microservices

Se uma funcionalidade não for necessária para o MVP, fica fora.

------------------------------------------------------------------------

# 5. Conceitos financeiros básicos

Antes de começar a arquitetura, é importante perceber alguns conceitos.

## Account

Representa onde o dinheiro está.

Exemplos:

``` text
Bank account
Cash
Savings
Credit card
```

## Transaction

Representa uma alteração financeira.

Exemplo:

``` text
€50 grocery expense
```

## Income

Dinheiro recebido.

``` text
Salary +€2,000
```

## Expense

Dinheiro gasto.

``` text
Supermarket -€50
```

## Category

Classifica uma transação.

``` text
Food
Transport
Housing
Entertainment
Salary
```

------------------------------------------------------------------------

# 6. Regras financeiras do MVP

Estas regras devem estar definidas antes de escrever código.

## Income

Aumenta o saldo da conta.

``` text
Balance = Balance + Income
```

## Expense

Diminui o saldo.

``` text
Balance = Balance - Expense
```

Exemplo:

``` text
Initial balance: €1,000

Expense: €100

New balance: €900
```

------------------------------------------------------------------------

# 7. Dinheiro

Não devemos utilizar `float` para representar dinheiro.

Evitar:

``` php
$amount = 19.99;
```

porque floating point pode causar problemas de precisão.

Para o MVP, podemos guardar os valores como unidades mínimas:

``` text
€19.99 → 1999
€5.00  → 500
€0.50  → 50
```

Na base de dados:

``` text
amount BIGINT
```

A aplicação converte para euros quando apresenta o valor.

Esta decisão deverá ser tomada antes de criar a tabela `transactions`.

------------------------------------------------------------------------

# 8. Modelo inicial

O MVP pode começar com apenas quatro entidades principais:

``` text
User
 │
 ├──────────────┐
 ▼              ▼
Account       Category
 │
 ▼
Transaction
```

## users

``` text
id
name
email
password
created_at
updated_at
```

## accounts

``` text
id
user_id
name
type
initial_balance
created_at
updated_at
```

## categories

``` text
id
user_id
name
type
created_at
updated_at
```

## transactions

``` text
id
user_id
account_id
category_id
type
amount
description
transaction_date
created_at
updated_at
```

------------------------------------------------------------------------

# 9. Relationships

## User → Accounts

Um utilizador pode ter várias contas.

``` text
User 1 ─────── N Accounts
```

## User → Categories

Um utilizador pode ter várias categorias.

``` text
User 1 ─────── N Categories
```

## Account → Transactions

Uma conta pode possuir várias transações.

``` text
Account 1 ─────── N Transactions
```

## Category → Transactions

Uma categoria pode ser utilizada em várias transações.

``` text
Category 1 ─────── N Transactions
```

------------------------------------------------------------------------

# 10. Ownership

Uma regra extremamente importante:

> **Um utilizador só pode aceder aos seus próprios dados.**

Se:

``` text
User A
Account 1
```

e:

``` text
User B
Account 2
```

User A nunca pode:

``` text
GET /accounts/2
```

e conseguir visualizar Account 2.

Esta regra deverá existir desde o MVP.

Não devemos deixar segurança para uma fase posterior.

------------------------------------------------------------------------

# 11. Authentication

O fluxo será:

``` text
Register
   ↓
Validate input
   ↓
Create user
   ↓
Hash password
   ↓
Store user
```

Login:

``` text
Email + Password
       ↓
Validate
       ↓
Verify password
       ↓
Create session
       ↓
Dashboard
```

Logout:

``` text
Logout
  ↓
Destroy session
  ↓
Login page
```

------------------------------------------------------------------------

# 12. Routing inicial

Exemplo de rotas:

``` text
GET  /
GET  /register
POST /register

GET  /login
POST /login
GET  /logout

GET  /dashboard

GET  /accounts
GET  /accounts/new
POST /accounts
GET  /accounts/{id}
GET  /accounts/{id}/edit
POST /accounts/{id}/update
POST /accounts/{id}/delete

GET  /categories
GET  /categories/new
POST /categories
POST /categories/{id}/delete

GET  /transactions
GET  /transactions/new
POST /transactions
GET  /transactions/{id}
POST /transactions/{id}/delete
```

Não é obrigatório seguir exatamente esta estrutura.

Primeiro devemos perceber como o CI4 organiza estas operações.

------------------------------------------------------------------------

# 13. MVC

A aplicação deverá seguir o padrão MVC.

``` text
Browser
   ↓
Route
   ↓
Controller
   ↓
Model
   ↓
Database
```

Depois:

``` text
Database
   ↓
Model
   ↓
Controller
   ↓
View
   ↓
Browser
```

Exemplo:

``` text
GET /transactions
        ↓
TransactionController
        ↓
TransactionModel
        ↓
MySQL
        ↓
TransactionController
        ↓
transactions/index.php
```

------------------------------------------------------------------------

# 14. Controllers

No início, não devemos criar uma arquitetura gigantesca.

Podemos ter:

``` text
AuthController
DashboardController
AccountController
CategoryController
TransactionController
```

Cada controller trata das operações relacionadas com o seu domínio.

------------------------------------------------------------------------

# 15. Models

Inicialmente:

``` text
UserModel
AccountModel
CategoryModel
TransactionModel
```

Os Models serão responsáveis principalmente por:

-   queries
-   inserts
-   updates
-   deletes
-   definição dos campos permitidos
-   regras relacionadas com persistência

Não devemos colocar toda a lógica da aplicação dentro dos Models.

------------------------------------------------------------------------

# 16. Views

Inicialmente podemos ter:

``` text
Views
│
├── layouts
│   └── main.php
│
├── auth
│   ├── login.php
│   └── register.php
│
├── dashboard
│   └── index.php
│
├── accounts
│   ├── index.php
│   ├── create.php
│   └── edit.php
│
├── categories
│   ├── index.php
│   └── create.php
│
└── transactions
    ├── index.php
    ├── create.php
    └── show.php
```

Não precisamos de React/Vue/etc.

O objetivo é aprender primeiro o funcionamento do backend CI4.

------------------------------------------------------------------------

# 17. Database migrations

A base de dados deverá ser criada através de migrations.

Não queremos depender de:

``` text
phpMyAdmin → Create table → ...
```

Queremos:

``` text
Migration
    ↓
php spark migrate
    ↓
Database
```

Isto permite que outra pessoa consiga clonar o projeto e reconstruir a
base de dados.

------------------------------------------------------------------------

# 18. Seeders

Depois das migrations teremos seeders.

Exemplo:

``` text
php spark db:seed DatabaseSeeder
```

Que poderá criar:

``` text
Demo user

Accounts:
- Bank
- Cash

Categories:
- Food
- Transport
- Salary
- Entertainment

Transactions:
- Salary
- Supermarket
- Fuel
- Restaurant
```

Assim conseguimos testar a aplicação rapidamente.

------------------------------------------------------------------------

# 19. Validation

Todas as entradas do utilizador devem ser validadas.

Exemplo:

``` text
Account name
    ↓
required
max_length[100]
```

Transaction:

``` text
amount
    ↓
required
integer
greater_than[0]

type
    ↓
required
in_list[income,expense]
```

Nunca devemos confiar nos dados enviados pelo browser.

------------------------------------------------------------------------

# 20. CSRF

Os formulários que alteram dados deverão estar protegidos contra CSRF.

Exemplo:

``` text
POST /transactions
POST /accounts
POST /categories
```

O CI4 deverá tratar da proteção CSRF de acordo com a configuração
escolhida.

------------------------------------------------------------------------

# 21. Dashboard

O dashboard do MVP deve ser simples.

``` text
-------------------------------------
Balance
€2,340.50
-------------------------------------

Income
€2,800

Expenses
€459.50

-------------------------------------

Recent transactions

Salary             +€2,800
Supermarket         -€45
Restaurant          -€20
Fuel                -€50
-------------------------------------
```

Não precisamos de gráficos inicialmente.

------------------------------------------------------------------------

# 22. Cálculo do saldo

No MVP, podemos calcular:

``` text
Account balance =
initial balance
+ income
- expenses
```

Exemplo:

``` text
Initial: €1,000

Income:  +€500
Expense: -€200
Expense: -€50

Balance: €1,250
```

É importante definir claramente esta regra antes de implementar.

------------------------------------------------------------------------

# 23. Transferências

Não entram no primeiro MVP.

Uma transferência parece simples:

``` text
Bank → Revolut
€500
```

mas implica várias decisões:

``` text
É uma ou duas transactions?

Como evitar contar €500 como income?

Como tratar cancelamento?

Como manter ambas as contas consistentes?
```

Por isso fica para uma versão posterior.

------------------------------------------------------------------------

# 24. MVP → V1

Depois do MVP estar funcional:

``` text
MVP
│
├── Authentication
├── Accounts
├── Categories
└── Transactions
```

Podemos adicionar:

``` text
V1
│
├── Transfers
├── Better dashboard
├── Search
├── Filters
├── Pagination
└── Transaction editing
```

------------------------------------------------------------------------

# 25. V2 --- Budgets

Depois:

``` text
Budget
```

Exemplo:

``` text
Food

Budget: €300

Spent: €210

Remaining: €90
```

Podemos ter:

``` text
budget
├── user_id
├── category_id
├── amount
├── month
└── year
```

------------------------------------------------------------------------

# 26. V3 --- Recurring transactions

Exemplo:

``` text
Rent
€800
Every month
Day 1
```

Ou:

``` text
Netflix
€15
Every month
Day 10
```

Mais tarde poderemos criar um command:

``` text
php spark finance:process-recurring
```

E eventualmente agendá-lo.

------------------------------------------------------------------------

# 27. V4 --- Reports

Podemos adicionar:

``` text
Monthly income
Monthly expenses
Expenses by category
Account balance history
Savings
```

Exemplo:

``` text
August

Income:   €2,800
Expenses: €1,420
Saved:    €1,380
```

------------------------------------------------------------------------

# 28. V5 --- REST API

Só depois de a aplicação web estar estável:

``` text
/api/v1/accounts
/api/v1/categories
/api/v1/transactions
/api/v1/budgets
```

A API deverá devolver JSON.

Exemplo:

``` json
{
    "id": 15,
    "amount": 4500,
    "type": "expense",
    "description": "Supermarket"
}
```

------------------------------------------------------------------------

# 29. V6 --- Redis

Redis será introduzido quando houver uma necessidade concreta.

Possíveis utilizações:

``` text
Redis
│
├── Cache
├── Sessions
└── Rate limiting
```

Por exemplo:

``` text
Dashboard
    ↓
Cache?
    ↓
Redis
    ↓
Se não existir:
    ↓
MySQL
    ↓
Guardar resultado no Redis
```

Não devemos colocar Redis no MVP só porque queremos aprender Redis.

------------------------------------------------------------------------

# 30. V7 --- Notifications

Depois:

``` text
Budget almost exceeded
Recurring transaction created
Monthly report available
```

Pode eventualmente utilizar:

``` text
Queue
   ↓
Worker
   ↓
Email
```

------------------------------------------------------------------------

# 31. V8 --- Docker

Quando a aplicação já estiver estável:

``` text
docker compose
│
├── php
├── nginx
├── mysql
└── redis
```

A configuração Docker deverá representar uma evolução do projeto, não
uma condição para começar.

------------------------------------------------------------------------

# 32. V9 --- Testing

Os testes devem começar cedo, mas não precisamos testar tudo no primeiro
dia.

Primeiros testes:

``` text
User registration
Login
Account creation
Transaction creation
```

Depois:

``` text
Authorization
Balance calculation
Transfers
Budgets
API
```

------------------------------------------------------------------------

# 33. Arquitetura inicial

A arquitetura inicial deverá ser deliberadamente simples:

``` text
Browser
   │
   ▼
Routes
   │
   ▼
Controllers
   │
   ▼
Models
   │
   ▼
MySQL
```

Com:

``` text
Views
Filters
Validation
Migrations
Seeders
```

Não começar com:

``` text
Services
Repositories
DTOs
Factories
Events
Queues
Microservices
```

a menos que uma necessidade real apareça.

------------------------------------------------------------------------

# 34. Evolução da arquitetura

A arquitetura deverá evoluir juntamente com o projeto.

### Inicialmente

``` text
Controller
    ↓
Model
```

### Quando aparecer lógica complexa

``` text
Controller
    ↓
Service
    ↓
Model
```

### Quando existir API

``` text
Web Controller ──┐
                  ├── Service → Model
API Controller ──┘
```

### Quando existirem processos assíncronos

``` text
Service
   ↓
Queue
   ↓
Worker
```

Esta evolução é intencional.

Queremos aprender **quando** uma abstração é necessária, não apenas
criar abstrações porque parecem profissionais.

------------------------------------------------------------------------

# 35. Segurança desde o início

Mesmo no MVP:

-   Password hashing
-   CSRF
-   Input validation
-   Output escaping
-   Authorization
-   User ownership
-   Mass assignment protection
-   Secure sessions
-   Não expor dados de outros utilizadores
-   Não guardar passwords
-   Não guardar secrets no Git

Segurança não será uma "feature V5".

------------------------------------------------------------------------

# 36. Git

O projeto deve ser construído através de commits pequenos.

Exemplos:

``` text
chore: initialize CodeIgniter project
feat: add user registration
feat: add login
feat: add authentication filter
feat: add account management
feat: add categories
feat: add transactions
feat: add dashboard
test: add transaction tests
fix: prevent cross-user account access
```

Evitar:

``` text
final project
update stuff
changes
everything
```

------------------------------------------------------------------------

# 37. README

Quando o MVP estiver funcional, o GitHub deverá explicar claramente:

``` text
Nivora

A personal finance management application
built with CodeIgniter 4 and MySQL.

Features
- Authentication
- Accounts
- Categories
- Transactions
- Dashboard

Tech Stack
- PHP
- CodeIgniter 4
- MySQL

Getting Started
...
```

O README será atualizado conforme a aplicação evoluir.

------------------------------------------------------------------------

# 38. O que estudar antes de começar

Não precisas de estudar outra framework.

Deves estar confortável com:

### PHP

-   Classes
-   Objects
-   Interfaces
-   Exceptions
-   Type declarations
-   Namespaces
-   Composer
-   Basic OOP

### SQL

-   SELECT
-   INSERT
-   UPDATE
-   DELETE
-   WHERE
-   ORDER BY
-   GROUP BY
-   JOIN
-   Foreign keys
-   Indexes
-   Transactions

### Web

-   HTTP
-   GET
-   POST
-   PUT/PATCH
-   DELETE
-   Cookies
-   Sessions
-   Forms
-   Status codes

### CodeIgniter

Depois dos Guides:

-   Routing
-   Controllers
-   Models
-   Database
-   Query Builder
-   Migrations
-   Seeders
-   Validation
-   Filters
-   Sessions
-   Testing

Não precisas de saber tudo perfeitamente.

O próprio projeto será parte do processo de aprendizagem.

------------------------------------------------------------------------

# 39. Ordem de implementação

Esta será a ordem recomendada.

## Fase 0 --- Planeamento

Antes de programar:

-   Definir MVP
-   Definir entidades
-   Definir relações
-   Definir regras financeiras
-   Definir rotas
-   Definir permissões
-   Criar ERD simples

------------------------------------------------------------------------

## Fase 1 --- Setup

``` text
CI4
Composer
Git
.env
MySQL
```

------------------------------------------------------------------------

## Fase 2 --- Authentication

``` text
Register
Login
Logout
Session
Auth Filter
```

------------------------------------------------------------------------

## Fase 3 --- Accounts

``` text
Create
Read
Update
Delete
Ownership
```

------------------------------------------------------------------------

## Fase 4 --- Categories

``` text
Create
Read
Update
Delete
Ownership
```

------------------------------------------------------------------------

## Fase 5 --- Transactions

``` text
Create
Read
Update
Delete
Validation
Account relation
Category relation
```

------------------------------------------------------------------------

## Fase 6 --- Dashboard

``` text
Balance
Income
Expenses
Recent transactions
```

------------------------------------------------------------------------

## Fase 7 --- MVP COMPLETE

Neste momento devemos parar.

Testar.

Corrigir.

Refatorar.

Fazer o projeto funcionar corretamente antes de adicionar
funcionalidades.

------------------------------------------------------------------------

# 40. Depois do MVP

Só depois de termos uma versão funcional:

``` text
             MVP
              │
       ┌───────┼────────┐
       ▼       ▼        ▼
  Transfers  Filters   Tests
       │       │        │
       └───────┼────────┘
               ▼
              V1
               │
       ┌───────┼─────────┐
       ▼       ▼         ▼
    Budgets  Reports  Recurring
       │       │         │
       └───────┼─────────┘
               ▼
              V2
               │
              API
               │
              V3
               │
             Redis
               │
              V4
               │
         Queues/Workers
               │
              V5
```

------------------------------------------------------------------------

# 41. Critério de sucesso do MVP

O MVP estará terminado quando um utilizador conseguir:

``` text
1. Criar conta
       ↓
2. Fazer login
       ↓
3. Criar uma conta financeira
       ↓
4. Criar categorias
       ↓
5. Adicionar rendimento
       ↓
6. Adicionar despesas
       ↓
7. Ver o saldo atualizado
       ↓
8. Consultar as transações
       ↓
9. Editar/apagar os seus dados
       ↓
10. Fazer logout
```

E, principalmente:

``` text
User A
   ✕
   └──→ não consegue aceder aos dados do User B
```

------------------------------------------------------------------------

# 42. Filosofia do projeto

Este projeto não será desenvolvido seguindo:

> "Vamos construir uma arquitetura perfeita antes de escrever código."

Será desenvolvido seguindo:

> **Build → Understand → Test → Refactor → Extend**

Cada versão deve ensinar alguma coisa.

``` text
MVP
 ↓
Problemas reais aparecem
 ↓
Entender os problemas
 ↓
Refatorar
 ↓
Adicionar feature
 ↓
Novos problemas
 ↓
Melhorar arquitetura
```

Isto é particularmente importante para aprender desenvolvimento backend.

------------------------------------------------------------------------

# 43. Estado final desejado

No final do projeto, idealmente teremos:

``` text
Nivora
│
├── PHP
├── CodeIgniter 4
├── MySQL
├── Redis
├── REST API
├── Authentication
├── Authorization
├── Validation
├── Testing
├── Background jobs
├── Docker
└── CI/CD
```

Mas isto será o **resultado de várias versões**, não o ponto de partida.

------------------------------------------------------------------------

# 44. Regra principal

Durante o desenvolvimento:

> **Se uma feature não é necessária, não a implementamos.**

> **Se uma abstração não resolve um problema, não a criamos.**

> **Se uma tecnologia não tem uma utilização concreta, não a
> adicionamos.**

O objetivo é terminar primeiro uma aplicação pequena e funcional.

Depois transformá-la gradualmente numa aplicação cada vez mais robusta.

------------------------------------------------------------------------

# 45. Definition of Done

Uma feature do Nivora não deve ser considerada terminada apenas porque
"funciona".

Sempre que fizer sentido, uma feature deverá passar por:

``` text
Implementation
    ↓
Validation
    ↓
Authorization
    ↓
Error handling
    ↓
Testing
    ↓
Refactoring
    ↓
Commit
```

Isto evita transformar o MVP numa coleção de funcionalidades frágeis.

------------------------------------------------------------------------

# 46. Princípio final

O Nivora não precisa de parecer uma aplicação enterprise no primeiro
commit.

Precisa de:

-   funcionar;
-   ser seguro;
-   ter regras claras;
-   ser compreensível;
-   ser testável;
-   e evoluir por necessidade.

> **Start simple. Earn the complexity.**
