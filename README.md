# 🛍️ TradeShare

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

> ⚠️ **IMPORTANT**: This is an intentionally vulnerable web application for security training purposes. Do not deploy in production or expose to the internet.

TradeShare is a freelance services marketplace built with PHP and MySQL, designed specifically for security training and vulnerability assessment. It intentionally implements various security vulnerabilities to help developers understand and practice identifying common web application security issues.

## 🎯 Features

- 👤 User registration and authentication
- 📝 Service posting and management
- 💬 Real-time messaging system
- 👤 Profile management with image uploads
- 👨‍💼 Admin dashboard
- 🔌 RESTful API endpoints

## 🚀 Quick Start

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server (or PHP's built-in server)
- Composer (optional, for dependencies)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/tradeshare.git
   cd tradeshare
   ```

2. Create the database:
   ```sql
   CREATE DATABASE tradeshare;
   ```

3. Import the database schema:
   ```bash
   mysql -u your_username -p tradeshare < schema.sql
   ```

4. Configure the database connection:
   ```bash
   cp config.bak config.php
   # Edit config.php with your database credentials
   ```

5. Set up the web server:

   **Option 1: Using PHP's built-in server**
   ```bash
   php -S localhost:8000
   ```

   **Option 2: Using Apache**
   ```bash
   # Copy files to your web root
   sudo cp -r * /var/www/html/
   # Set permissions
   sudo chown -R www-data:www-data /var/www/html
   sudo chmod -R 755 /var/www/html
   ```

6. Create the uploads directory:
   ```bash
   mkdir uploads
   chmod 777 uploads
   ```

7. Visit the application:
   ```
   http://localhost:8000
   ```

## 🔑 Default Credentials

- **Admin User**
  - Username: `admin`
  - Password: `admin123`

- **Test User**
  - Username: `user`
  - Password: `user123`

## 🎯 Intentionally Vulnerable Features

### 1. Broken Access Control
- Service editing/deletion without ownership verification
- Admin access via URL parameter (`/admin.php?auth=1`)
- Direct access to user data without proper authorization

### 2. Cryptographic Failures
- MD5 password hashing
- Unencrypted sensitive data storage
- Predictable session IDs
- No session expiration

### 3. Injection
- SQL Injection in login, search, and user management
- No input validation or sanitization
- Direct concatenation of user input in queries

### 4. Insecure Design
- Weak password requirements
- No rate limiting
- No account lockout
- Predictable resource IDs

### 5. Security Misconfiguration
- Exposed `.git` directory
- Debug mode enabled
- Detailed error messages
- Default credentials

### 6. Vulnerable Components
- Outdated libraries
- Known vulnerable dependencies
- Unpatched security issues

### 7. Authentication Failures
- Weak session management
- No password complexity requirements
- No multi-factor authentication
- Predictable session tokens

### 8. Software and Data Integrity Failures
- Unsafe `eval()` usage
- Unsafe JSON handling
- No integrity checks
- Unsafe file uploads

### 9. Logging Failures
- No security event logging
- No audit trails
- No login attempt tracking
- No error logging

### 10. SSRF Vulnerabilities
- Unsafe URL preview feature
- No URL validation
- No allowlist/blocklist
- Follows redirects

## 🔌 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/index.php?action=login` | POST | User login |
| `/api/index.php?action=register` | POST | User registration |
| `/api/index.php?action=services` | GET | List services |
| `/api/index.php?action=create_service` | POST | Create service |
| `/api/index.php?action=update_service` | POST | Update service |
| `/api/index.php?action=delete_service` | POST | Delete service |
| `/api/index.php?action=settings` | POST | Update user settings |
| `/api/index.php?action=url_preview` | GET | URL preview (SSRF) |

## 🎓 Security Training

This application is designed for:
- Penetration testing practice
- Security vulnerability assessment
- OWASP Top 10 vulnerability study
- Web application security training

## 🤝 Contributing

Contributions are welcome! Feel free to:
1. Fork the repository
2. Create a new branch
3. Add more vulnerabilities or improve existing ones
4. Submit a pull request

## 📝 License

This project is for educational purposes only. Use at your own risk.

## ⚠️ Disclaimer

This application is intentionally vulnerable and should ONLY be used in controlled environments for security training and testing. The authors are not responsible for any misuse or damage caused by this application. 