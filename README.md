# TradeShare

TradeShare is a modern web platform that connects skilled tradesmen with clients, facilitating service sharing and management in a secure and efficient environment.

## 🌟 Features

- **User Authentication**
  - Secure login and registration system
  - Profile management for tradesmen and clients
  - Role-based access control

- **Service Management**
  - Create and manage service listings
  - Real-time service availability updates
  - Service categorization and search

- **Messaging System**
  - In-app messaging between tradesmen and clients
  - Real-time notifications
  - Message history and management

- **Profile Management**
  - Professional profile creation
  - Portfolio showcase
  - Review and rating system

- **Admin Dashboard**
  - User management
  - Service moderation
  - System monitoring

## 🚀 Getting Started

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (PHP package manager)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/tradeshare.git
   cd tradeshare
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Set up the database:
   ```bash
   mysql -u your_username -p your_database < schema.sql
   ```

4. Configure the application:
   - Copy `config.bak` to `config.php`
   - Update database credentials and other settings in `config.php`

5. Set up the web server:
   - Point your web server to the project directory
   - Ensure the `uploads` directory is writable
   - Configure URL rewriting if needed

### Environment Setup

Create a `.env` file in the root directory with the following variables:
```env
DB_HOST=localhost
DB_NAME=tradeshare
DB_USER=your_username
DB_PASS=your_password
APP_URL=http://localhost/tradeshare
```

## 🛠️ Development

### Project Structure
```
tradeshare/
├── api/            # API endpoints
├── css/           # Stylesheets
├── images/        # Static images
├── uploads/       # User uploads
├── vendor/        # Composer dependencies
├── .env           # Environment variables
├── config.php     # Application configuration
└── README.md      # This file
```

### Running Tests
```bash
composer test
```

## 🔒 Security

- All user inputs are sanitized
- Passwords are hashed using secure algorithms
- SQL injection prevention
- XSS protection
- CSRF protection

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📧 Support

For support, email support@tradeshare.com or open an issue in the GitHub repository.

## 🙏 Acknowledgments

- Thanks to all contributors
- Inspired by the need for better tradesman-client connections
- Built with modern web technologies 