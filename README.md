# Laravel Role-Based Access Control (RBAC) Application

## 🚀 Project Overview

A comprehensive Laravel application demonstrating Role-Based Access Control (RBAC) using the Spatie Permission package. 

## 📋 Table of Contents

- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Default Users](#-default-users)
- [Features](#-key-features)
- [Authorization System](#-authorization-system)
- [Permission Hierarchy](#-permission-hierarchy)
- [Development Setup](#-development-setup)
- [Testing](#-testing)
- [Security](#-security)
- [Contributing](#-contributing)
- [License](#-license)

## 💻 Requirements

- PHP 8.1+
- Composer
- Postgres
- Node.js & NPM
- Laravel 12.x

## 🔧 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/GHOST117s/RBAC_APP.git
cd rbac-app
```

### 2. Install Dependencies
```bash
# PHP Dependencies
composer install

# Frontend Assets
npm install
npm run dev
```

### 3. Environment Setup
```bash
# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

## ⚙️ Configuration

### Database Configuration
Edit the `.env` file with your database credentials:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=RBAC
DB_USERNAME=root
DB_PASSWORD=root
```

### Database Initialization
```bash
# Run migrations and seed database
php artisan migrate --seed
```

## 👥 Default Users

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | password | Super Admin |
| editor@example.com | password | Editor |
| author@example.com | password | Author |
| user@example.com | password | User |

## ✨ Key Features

### 1. User Management
- Create, edit, and delete users
- Role assignment
- User activity tracking

### 2. Role Management
- Dynamic role creation
- Permission-based role assignments
- Protected system roles

### 3. Permission Management
- Granular permission creation
- Role-permission mapping
- System permission protection

### 4. Post Management
- CRUD operations
- Permission-based access control
- Publication workflow

## 🔐 Authorization System

The application implements multi-level authorization:

1. **Policies**: Centralized permission logic
2. **Middleware**: Route-level permission checks
3. **Blade Directives**: UI-level permission controls

### Permission Categories

#### Post Permissions
- `view posts`
- `create posts`
- `edit posts`
- `delete posts`
- `publish posts`

#### User Permissions
- `view users`
- `create users`
- `edit users`
- `delete users`

#### Role Permissions
- `create roles`
- `edit roles`
- `delete roles`
- `assign roles`

#### Permission Permissions
- `create permissions`
- `edit permissions`
- `delete permissions`

## 🚀 Development Setup

### Running the Application
```bash
npm run start
```


