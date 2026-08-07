# 📦 Smart Inventory Pro

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Smart Inventory Pro** is a comprehensive inventory management system built with Laravel 12. It provides businesses with powerful tools to manage customers, products, invoices, payments, and generate detailed analytics to make informed decisions.

## 🚀 Features

### 📊 **Dashboard & Analytics**
- Real-time business overview with key metrics
- Interactive charts and graphs
- Financial summaries and reports
- Performance indicators

### 👥 **Customer Management**
- Complete customer profiles with contact information
- Customer analytics and sales history
- Payment tracking and outstanding balances
- Monthly sales trends visualization
- Customer performance metrics

### 📦 **Inventory Management**
- Product catalog with categories and units
- Stock level monitoring
- Item pricing and tax management
- Comprehensive product search and filtering

### 🧾 **Invoice System**
- Professional invoice generation
- Multiple tax support
- Discount management (percentage and fixed)
- Invoice status tracking (Paid, Unpaid, Partial)
- PDF invoice generation
- Invoice item management

### 💰 **Payment Processing**
- Multiple payment methods support
- Payment tracking and history
- Automatic invoice status updates
- Payment reconciliation

### ⚙️ **System Management**
- Finance year management
- System settings and customization
- User management
- Application configuration

### 📈 **Advanced Analytics**
- Customer sales analytics
- Payment efficiency tracking
- Monthly trend analysis
- Performance metrics dashboard

## 🛠️ Tech Stack

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Blade Templates with Tailwind CSS 4.0
- **Database**: MySQL 8.0+ / PostgreSQL / SQLite
- **Charts**: ApexCharts.js
- **PDF Generation**: DomPDF
- **Data Tables**: Yajra DataTables
- **Image Processing**: Intervention Image
- **Testing**: PHPUnit

## 📋 Requirements

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18.x or higher
- **Database**: MySQL 8.0+ / PostgreSQL 12+ / SQLite 3.x
- **Web Server**: Apache/Nginx

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/dkpankaj1/Smart_inventory_pro.git
cd smart_inventory
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node.js Dependencies
```bash
npm install
```

### 4. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Database Configuration
Edit your `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_inventory
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed
```

### 7. Build Assets
```bash
# For development
npm run dev

# For production
npm run build
```

### 8. Start the Application
```bash
# Start Laravel development server
php artisan serve

# The application will be available at http://localhost:8000
```

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suites
```bash
# Run unit tests
php artisan test --testsuite=Unit

# Run feature tests
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

### Test Database
For testing, you can use SQLite:
```bash
# Create test database
touch database/testing.sqlite

# Run tests with specific environment
php artisan test --env=testing
```

## 📁 Project Structure

```
smart_inventory/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   ├── Datatables/          # DataTable classes
│   └── Support/             # Helper classes
├── resources/
│   ├── views/               # Blade templates
│   ├── js/                  # JavaScript files
│   └── css/                 # Stylesheets
├── database/
│   ├── migrations/          # Database migrations
│   ├── seeders/             # Database seeders
│   └── factories/           # Model factories
├── tests/
│   ├── Feature/             # Feature tests
│   └── Unit/                # Unit tests
└── public/                  # Public assets
```

## 🔧 Configuration

### Environment Variables
Key environment variables to configure:

```env
# Application
APP_NAME="Smart Inventory Pro"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_inventory

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password

# Cache (for better performance)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 📊 Key Models & Relationships

- **Customer**: Manages customer information and relationships
- **Invoice**: Handles invoice creation and management
- **InvoiceItem**: Line items for invoices
- **Payment**: Payment tracking and processing
- **Item**: Product catalog management
- **Category**: Product categorization
- **Tax**: Tax management system
- **Unit**: Measurement units for products

## 🎯 API Endpoints (if applicable)

The system includes DataTable endpoints for dynamic data loading:

- `GET /customer` - Customer listing with search/filter
- `GET /invoice` - Invoice management interface
- `GET /item` - Product catalog interface
- `GET /payment` - Payment tracking interface

## 🔐 Security Features

- **CSRF Protection**: All forms protected against CSRF attacks
- **Input Validation**: Comprehensive input validation using Form Requests
- **SQL Injection Prevention**: Using Eloquent ORM and parameterized queries
- **XSS Protection**: Blade template engine with automatic escaping
- **Authentication**: Built-in Laravel authentication system

## 🚀 Deployment

### Production Deployment Steps

1. **Server Setup**
   ```bash
   # Set proper permissions
   sudo chown -R www-data:www-data storage/
   sudo chown -R www-data:www-data bootstrap/cache/
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

2. **Optimize for Production**
   ```bash
   # Cache configuration
   php artisan config:cache
   
   # Cache routes
   php artisan route:cache
   
   # Cache views
   php artisan view:cache
   
   # Optimize autoloader
   composer install --optimize-autoloader --no-dev
   ```

3. **Web Server Configuration**
   - Point document root to `/public` directory
   - Configure URL rewriting for Laravel
   - Set up SSL certificate

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Code Standards
- Follow PSR-12 coding standards
- Use Laravel Pint for code formatting
- Write tests for new features
- Update documentation as needed

## 🐛 Issue Reporting

If you discover any bugs or have feature requests, please create an issue on GitHub with:
- Detailed description of the problem
- Steps to reproduce
- Expected vs actual behavior
- Environment details (PHP version, Laravel version, etc.)

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Author

**DK Pankaj**
- GitHub: [@dkpankaj1](https://github.com/dkpankaj1)
- Email: [your-email@example.com](mailto:your-email@example.com)

## 📞 Support

For support and questions:
- Create an issue on GitHub
- Email: [support@example.com](mailto:support@example.com)
- Documentation: [Wiki](https://github.com/dkpankaj1/Smart_inventory_pro/wiki)

---

⭐ **If you find this project helpful, please give it a star!** ⭐
