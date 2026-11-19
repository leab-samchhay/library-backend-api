# 📚 Library Management System (LMS) - Backend RESTful API

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Sanctum-Token_Auth-red?style=for-the-badge" alt="Sanctum" />
  <img src="https://img.shields.io/badge/ABA_PayWay-KHQR-blue?style=for-the-badge" alt="ABA PayWay KHQR" />
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker" />
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License" />
</p>

<p align="center">
  A robust, secure, and modern <b>RESTful API</b> built with <b>Laravel 11 / 12</b> and <b>PHP 8.3</b> for comprehensive library management operations, featuring <b>Role-Based Access Control (RBAC)</b> and <b>ABA PayWay KHQR</b> payment integration.
</p>

---

## 📑 Table of Contents

- [About The Project](#-about-the-project)
- [Key Features](#-key-features)
- [Tech Stack](#️-tech-stack)
- [Database Security & Git Best Practices](#-database-security--git-best-practices)
- [Getting Started & Installation](#-getting-started--installation)
- [Default Seeded Accounts](#-default-seeded-accounts)
- [API Reference](#-api-reference)
  - [Authentication & Users](#1-authentication--users)
  - [Roles & Permissions (RBAC)](#2-roles--permissions-rbac)
  - [Catalog & Inventory Management](#3-catalog--inventory-management)
  - [Members & Circulation](#4-members--circulation-loans--returns)
  - [Fines & ABA PayWay KHQR Payments](#5-fines--aba-payway-khqr-payments)
  - [Reports & Analytics](#6-reports--dashboard-analytics)
- [Docker Deployment](#-docker-deployment)
- [Contributing](#-contributing)
- [License](#-license)

---

## 📖 About The Project

The **Library Management System (LMS) Backend API** is designed to power university and institutional libraries. It handles end-to-end library administration, from book cataloging and individual book copy inventory tracking to member management, automated overdue fine calculation, dynamic KHQR digital payments, and operational analytics.

---

## ✨ Key Features

### 1. 🔐 Authentication & RBAC (Role-Based Access Control)
- **Token-based Authentication**: Powered by Laravel Sanctum with Bearer token authentication.
- **Granular Permissions**: Fine-grained access control on module-level actions (`view`, `create`, `edit`, `delete`).
- **Custom Middleware**: `CheckPermission` middleware automatically protects restricted endpoints against unauthorized roles.

### 2. 📚 Book Catalog & Inventory Management
- **Complete Metadata**: Manages title, ISBN, published year, category, author, and publisher relationships.
- **Individual Copy Tracking (`Book Copies`)**: Tracks each physical copy status (`available`, `borrowed`, `damaged`, `lost`).
- **Multi-author Support**: Relational model connecting authors to books.

### 3. 🔄 Circulation: Loans, Returns & Reservations
- **Borrowing Workflows**: Creates loan records, sets due dates, and manages multiple book items per loan transaction.
- **Automated Overdue Detection**: Dedicated endpoints to quickly retrieve late returns and active overdue loans.
- **Reservations**: Queue and hold reservations for unavailable or in-demand books.
- **Audit Logs**: Transaction logging for all book and loan lifecycle events (`bookTransaction`, `loanTransaction`).

### 4. 💳 Fines & ABA PayWay KHQR Payment Gateway
- **Overdue Fine Computation**: Automatic fine creation upon overdue book returns.
- **EMVCo-Compliant KHQR Generation**: Generates standard dynamic **KHQR** payloads (Bakong & ABA Mobile compatible) for mobile banking scans.
- **Transaction Verification**: Instant callback & status checking endpoint for ABA PayWay transactions.

### 5. 📊 Reports & Dashboard Analytics
- **System Overview**: Summary statistics for total books, active members, borrowed items, and accumulated fines.
- **Monthly Borrower Trends**: Track user borrowing trends month-over-month.
- **Category Analytics**: Discover which book categories are borrowed most frequently.

---

## 🛠️ Tech Stack

| Component | Technology | Description |
| :--- | :--- | :--- |
| **Framework** | [Laravel 11 / 12](https://laravel.com/) | Modern PHP Web/API Framework |
| **Runtime** | [PHP 8.3+](https://www.php.net/) | High-performance PHP runtime with OPcache |
| **Database** | [MySQL 8.0+](https://www.mysql.com/) / SQLite | Relational database storage |
| **Authentication** | [Laravel Sanctum](https://laravel.com/docs/sanctum) | Secure API Bearer tokens |
| **Payment Gateway** | ABA PayWay / KHQR | EMVCo dynamic QR payment standard (Cambodia) |
| **Containerization** | [Docker](https://www.docker.com/) | Containerized Apache & PHP environment (Render/Cloud-ready) |
| **Testing & Quality** | PHPUnit, Laravel Pint | Unit/Feature testing & PSR-12 code style |

---

## 🔐 Database Security & Git Best Practices

> [!IMPORTANT]
> **Never commit your database credentials, password strings, or production API keys to GitHub!**

To ensure your database connection and secrets remain completely private when pushing code to GitHub:

### 1. Ensure `.env` is always in `.gitignore`
Your actual credentials reside strictly in `.env`. Ensure your [`.gitignore`](file:///d:/ICT/Full-Stake/Project/ProjectLibrary/Backend_API/.gitignore) includes:
```gitignore
.env
.env.backup
.env.production
.env.*
!.env.example
```

### 2. Keep `.env.example` Clean as a Template
Commit [`.env.example`](file:///d:/ICT/Full-Stake/Project/ProjectLibrary/Backend_API/.env.example) to GitHub so collaborators know which variables are required, but **leave sensitive values empty**:
```env
# ✅ Correct & Safe for GitHub (.env.example)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_db
DB_USERNAME=root
DB_PASSWORD=

# ❌ NEVER put real passwords or production keys here:
# DB_PASSWORD=my_actual_password_1234
```

### 3. Use `.dockerignore` to Prevent Docker Build Leaks
Avoid accidentally copying `.env` into your Docker container image by configuring [`.dockerignore`](file:///d:/ICT/Full-Stake/Project/ProjectLibrary/Backend_API/.dockerignore):
```dockerignore
.git
.env
.env.*
!.env.example
vendor
node_modules
```

### 4. How to Remove Accidentally Committed `.env` Files
If a `.env` file was previously committed to your Git repository by mistake, remove it from the Git index without deleting the local file:
```bash
git rm --cached .env
git commit -m "chore: remove .env from tracking"
git push origin <your-branch>
```
*(If credentials were leaked to a public repository, rotate your database passwords and API keys immediately).*

---

## 🚀 Getting Started & Installation

### Prerequisites
- **PHP** >= 8.3 (`pdo`, `pdo_mysql`, `mbstring`, `bcmath`, `gd`, `zip` extensions enabled)
- **Composer** >= 2.0
- **MySQL** or **MariaDB** database server

### Step-by-Step Installation

#### 1. Clone the repository
```bash
git clone https://github.com/your-username/Backend_API.git
cd Backend_API
```

#### 2. Install PHP dependencies
```bash
composer install
```

#### 3. Create your `.env` configuration file
```bash
# On Linux / macOS:
cp .env.example .env

# On Windows PowerShell:
copy .env.example .env
```

#### 4. Generate the application encryption key
```bash
php artisan key:generate
```

#### 5. Configure Database Credentials
Open your newly created `.env` file and fill in your local database connection details:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=backend_Api_Library
DB_USERNAME=root
DB_PASSWORD=your_local_password
```

#### 6. Run Migrations and Database Seeders
This creates all tables and populates default permissions, roles, and administrative users:
```bash
php artisan migrate --seed
```

#### 7. Create the Public Storage Link
```bash
php artisan storage:link
```

#### 8. Start the Local Development Server
```bash
php artisan serve
```
The API is now running at: `http://127.0.0.1:8000`

---

## 👤 Default Seeded Accounts

After running `php artisan migrate --seed`, the database is populated with the following testing credentials:

| Role | Email | Password | Permissions |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@gmail.com` | `123` | **Full Access**: `view`, `create`, `edit`, `delete` across all modules |
| **User / Staff** | `user@gmail.com` | `123` | **Read-Only Access**: `view` permissions across all modules |

---

## 📡 API Reference

> **Base URL**: `http://localhost:8000/api`  
> Protected endpoints require the header: `Authorization: Bearer <your_token>`

### 1. Authentication & Users
| Method | Endpoint | Protection / Middleware | Description |
| :--- | :--- | :--- | :--- |
| `POST` | `/login` | Public | Authenticate user & issue Bearer token |
| `POST` | `/logout` | `auth:sanctum` | Revoke current access token |
| `GET` | `/current-user` | `auth:sanctum` | Get authenticated user info & role |
| `GET` | `/users` | `auth:sanctum`, `permission` | List all system users |
| `POST` | `/users` | `auth:sanctum`, `permission` | Create a new user |
| `GET` | `/users/{id}` | `auth:sanctum`, `permission` | Get user details by ID |
| `PUT` | `/users/{id}` | `auth:sanctum`, `permission` | Update user details |
| `DELETE` | `/users/{id}` | `auth:sanctum`, `permission` | Delete user |

### 2. Roles & Permissions (RBAC)
| Method | Endpoint | Protection / Middleware | Description |
| :--- | :--- | :--- | :--- |
| `GET\|POST` | `/role` | Public / Sanctum | Manage system roles |
| `GET\|POST` | `/user-roles` | Public / Sanctum | Assign roles to users |
| `GET` | `/permissions` | `auth:sanctum` | List all registered system permissions |
| `GET` | `/users/{userId}/permissions` | `auth:sanctum` | Get permissions assigned to a specific user |
| `POST` | `/users/{userId}/permissions` | `auth:sanctum` | Assign permissions directly to a user |

### 3. Catalog & Inventory Management
| Method | Endpoint | Protection / Middleware | Description |
| :--- | :--- | :--- | :--- |
| `GET\|POST` | `/category` | Public / Sanctum | Manage book categories |
| `GET\|POST` | `/authors` | `auth:sanctum`, `permission` | Manage authors |
| `GET\|POST` | `/publishers` | `auth:sanctum`, `permission` | Manage publishers |
| `GET\|POST` | `/book` | `auth:sanctum`, `permission` | Manage book titles & metadata |
| `GET\|POST` | `/book-copies` | `auth:sanctum`, `permission` | Track physical book copies & status |
| `GET\|POST` | `/author-books` | `auth:sanctum`, `permission` | Manage author-to-book associations |

### 4. Members & Circulation (Loans & Returns)
| Method | Endpoint | Protection / Middleware | Description |
| :--- | :--- | :--- | :--- |
| `GET\|POST` | `/members` | `auth:sanctum`, `permission` | Manage library members |
| `GET\|POST` | `/loans` | `auth:sanctum`, `permission` | Create & manage loan transactions |
| `GET\|POST` | `/loan-details` | `auth:sanctum`, `permission` | Loan detail records for borrowed items |
| `GET` | `/loan-details/late-returns` | `auth:sanctum`, `permission` | List of items returned after due date |
| `GET` | `/loan-details/overdue` | `auth:sanctum`, `permission` | List of active overdue borrowed books |
| `GET\|POST` | `/book-returns` | `auth:sanctum`, `permission` | Process book return submissions |
| `GET\|POST` | `/reservations` | `auth:sanctum`, `permission` | Book hold and reservation requests |
| `GET` | `/loanTransaction` | `auth:sanctum`, `permission` | Loan transaction audit trail |
| `GET` | `/bookTransaction` | `auth:sanctum`, `permission` | Book transaction audit trail |

### 5. Fines & ABA PayWay KHQR Payments
| Method | Endpoint | Protection / Middleware | Description |
| :--- | :--- | :--- | :--- |
| `GET\|POST` | `/fines` | `auth:sanctum`, `permission` | Manage overdue fine records |
| `GET\|POST` | `/fine-payments` | `auth:sanctum`, `permission` | Record fine payments |
| `POST` | `/aba/create-payment` | `auth:sanctum`, `permission` | Generate dynamic **KHQR** string for mobile scan |
| `POST` | `/aba/check-transaction` | `auth:sanctum`, `permission` | Check payment status against ABA PayWay |

### 6. Reports & Dashboard Analytics
| Method | Endpoint | Protection / Middleware | Description |
| :--- | :--- | :--- | :--- |
| `GET` | `/report/getSummary` | `permission:dashboard.view` | Overall library stats (books, loans, fines, members) |
| `GET` | `/report/monthly-borrower` | `permission:dashboard.view` | Monthly borrower counts and trends |
| `GET` | `/report/borrow-by-category` | `permission:dashboard.view` | Distribution of borrowed books grouped by category |

---

## 🐳 Docker Deployment

The application includes a production-ready `Dockerfile` (PHP 8.3 + Apache), configured for both local container execution and cloud platforms such as **Render**, **Railway**, or VPS:

### 1. Build the Docker Image
```bash
docker build -t library-backend-api .
```

### 2. Run with Docker Compose (Recommended for Production / Server)
```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
```

Or run the automated deployment script:
```bash
chmod +x deploy.sh
./deploy.sh
```

### 3. Run the Container Manually
```bash
docker run -d -p 8000:10000 \
  --name library_api \
  --env-file .env \
  library-backend-api
```
*(Apache in this container is set to port `10000` to comply with standard cloud hosting environments such as Render).*

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the issues page or submit a pull request:
1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is open-source software licensed under the [MIT License](LICENSE).
