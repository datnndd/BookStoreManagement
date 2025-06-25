# BookStore Management System

A comprehensive bookstore management system built with PHP, providing complete functionality for managing bookstore operations.

## 📁 Project Structure

```
BookStoreManagement/
├── 📄 bookstoredb.sql          # SQL Database
├── 📄 dashboard.php            # Main dashboard page
├── 📄 index.php               # System home page
├── 📁 AuthorManager/          # Author management
├── 📁 BookManager/            # Book management
├── 📁 Categories/             # Category management
├── 📁 nbproject/              # NetBeans configuration
├── 📁 Orders/                 # Order management
├── 📁 Publisher/              # Publisher management
├── 📁 Users/                  # User management
└── 📁 Verify/                 # Authentication and authorization
```

## 🚀 Key Features

### 📚 Book Management (BookManager)
- ➕ Add new books
- ✏️ Edit book information
- ❌ Delete books
- 🔍 Search books by title, author, ISBN

### 👨‍💼 Author Management (AuthorManager)
- ➕ Add new authors
- ✏️ Update author information
- ❌ Delete authors
- 🔍 Search authors by name, nationality

### 📖 Category Management (Categories)
- ➕ Create new categories
- ✏️ Edit categories
- ❌ Delete categories
- 🔍 Search categories by name

### 🏢 Publisher Management (Publisher)
- ➕ Add publishers
- ✏️ Update publisher information
- ❌ Delete publishers
- 🔍 Search publishers

### 🛒 Order Management (Orders)
- ➕ Create new orders
- ✏️ Update order status
- ❌ Cancel orders
- 🔍 Search orders by ID, customer

### 👥 User Management (Users)
- ➕ Add new users
- ✏️ Update user information
- ❌ Delete users
- 🔍 Search users by name, email

### 🔐 Authentication (Verify)
- System login
- User authorization
- Session security

## 🛠️ Technologies Used

- **Backend**: PHP
- **Database**: MySQL/MariaDB
- **Frontend**: HTML, CSS, JavaScript
- **IDE**: NetBeans (configuration in nbproject folder)

## 📋 System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB
- Apache/Nginx Web Server
- Modern web browser

## 🔧 Installation

1. **Clone the project**
   ```bash
   git clone [repository-url]
   cd BookStoreManagement
   ```

2. **Configure database**
   - Import `bookstoredb.sql` file into MySQL
   - Configure database connection in PHP files

3. **Run the application**
   - Place project folder in webroot (htdocs/www)
   - Access `http://localhost/BookStoreManagement`

## 🎯 Usage

1. **Access system**: Open `index.php` in browser
2. **Login**: Use Verify module for authentication
3. **Dashboard**: After login, access `dashboard.php`
4. **Management**: Use Manager modules to perform CRUD operations

## 📝 Notes

- All Manager modules support full CRUD operations (Create, Read, Update, Delete)
- System includes user authentication and authorization
- Data is securely stored in MySQL database
- Each management module provides search functionality for easy data retrieval

## 🔒 Security Features

- User authentication and session management
- Role-based access control
- Secure database connections
- Input validation and sanitization

## 🚀 Getting Started

1. Set up your local web server (XAMPP, WAMP, or LAMP)
2. Import the database schema from `bookstoredb.sql`
3. Configure database connection settings
4. Access the application through your web browser
5. Login with appropriate credentials to start managing your bookstore

## 📞 Support

If you encounter any issues during usage, please create an issue or contact me.
