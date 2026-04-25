# 💄 Cosmetics Website

A full-featured e-commerce web application for a cosmetics store, built with PHP, MySQL, and vanilla CSS/JavaScript. The site supports customer shopping, user account management, and a complete admin panel for store operations.

---

## ✨ Features

### Customer-Facing
- Browse and search products by category
- View detailed product pages with images, descriptions, and pricing
- User registration, login, and password reset (via email token)
- Shopping cart and checkout flow
- Multiple payment methods: Credit Card, PayPal, Bank Transfer, Cash on Delivery
- Shipping address management
- Product reviews and ratings
- Wishlist
- User dashboard to track orders

### Admin Panel
- Secure admin login (role-based access)
- Manage products (add / edit / delete, with image upload)
- Manage categories
- Manage users (view / edit / delete)
- View and update order statuses
- Manage payments and issue refunds
- Site-wide settings
- Audit log for tracking admin actions

---

## 🛠️ Tech Stack

| Layer       | Technology                      |
|-------------|---------------------------------|
| Backend     | PHP 8+ (procedural / MVC-style) |
| Database    | MySQL 8+                        |
| Frontend    | HTML5, CSS3, JavaScript (ES6)   |
| Mailer      | PHPMailer (password reset)      |
| DB Access   | PDO with prepared statements    |

---

## 📁 Project Structure

```
cosmetics_website/
├── admin/                  # Admin panel pages
│   ├── index.php           # Admin dashboard
│   ├── login.php           # Admin login
│   ├── manage_products.php # Add / edit / delete products
│   ├── manage_categories.php
│   ├── manage_users.php
│   ├── manage_orders.php
│   ├── manage_payments.php
│   ├── refund_payment.php
│   ├── update_order_status.php
│   └── settings.php
│
├── controllers/            # Business logic (MVC controllers)
│   ├── product_controller.php
│   ├── user_controller.php
│   ├── order_controller.php
│   └── payment_controller.php
│
├── models/                 # Database operations (MVC models)
│   ├── product_model.php
│   ├── category_model.php
│   ├── user_model.php
│   ├── order_model.php
│   └── payment_model.php
│
├── public_html/            # Customer-facing pages
│   ├── index.php           # Homepage
│   ├── products.php        # Product listing
│   ├── product_detail.php  # Single product view
│   ├── cart.php            # Shopping cart
│   ├── checkout.php        # Checkout & payment
│   ├── user_dashboard.php  # Order history & profile
│   ├── shipping_address.php
│   ├── reviews.php
│   ├── login.php / register.php
│   ├── forgot_password.php / reset_password.php
│   ├── about.php
│   ├── contact.php
│   └── logout.php
│
├── includes/               # Shared PHP includes
│   ├── db_connection.php   # PDO database connection (singleton)
│   ├── header.php / footer.php
│   └── admin_header.php / admin_footer.php
│
├── resources/              # Static assets
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   ├── images/             # Site images & product photos
│   ├── fonts/              # Custom fonts
│   └── PHPMailer/          # PHPMailer library
│
├── sql_script/
│   └── create_tables.sql   # Full database schema
│
└── docs/
    └── setup_guide.md
```

---

## 🗄️ Database Schema

The application uses a single MySQL database (`cosmetics_db`) with the following tables:

| Table               | Description                                      |
|---------------------|--------------------------------------------------|
| `users`             | Customer & admin accounts (role-based)           |
| `categories`        | Product categories                               |
| `products`          | Product listings (name, price, stock, image)     |
| `orders`            | Customer orders with status tracking             |
| `order_items`       | Individual line items within an order            |
| `payments`          | Payment records per order                        |
| `shipping_addresses`| Saved shipping addresses per user                |
| `reviews`           | Product ratings (1–5) and comments               |
| `wishlist`          | Saved products per user                          |
| `audit_logs`        | Admin action log                                 |
| `settings`          | Key–value store for site configuration           |

---

## ⚙️ Setup & Installation

### Prerequisites
- PHP 8.0 or higher
- MySQL 8.0 or higher
- A web server: Apache (with `mod_rewrite`) or Nginx
- Composer (optional, PHPMailer is bundled)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/gagan-long/cosmetics_website.git
   cd cosmetics_website
   ```

2. **Create the database**
   ```bash
   mysql -u root -p < sql_script/create_tables.sql
   ```

3. **Configure the database connection**

   Open `includes/db_connection.php` and update the credentials:
   ```php
   $host = 'localhost';
   $db   = 'cosmetics_db';
   $user = 'your_db_username';
   $pass = 'your_db_password';
   ```

4. **Configure your web server**

   Point the document root to the `public_html/` directory (for the customer site) or the project root (to also expose `/admin`).

   Example Apache virtual host:
   ```apache
   <VirtualHost *:80>
       ServerName cosmetics.local
       DocumentRoot /path/to/cosmetics_website/public_html
   </VirtualHost>
   ```

5. **Set up PHPMailer** (for password reset emails)

   Open `public_html/forgot_password.php` and set your SMTP credentials (host, port, username, password).

6. **Set file permissions** (Linux/macOS)
   ```bash
   chmod -R 755 resources/images/
   ```

7. **Open the site** in your browser:
   - Customer site: `http://localhost/`
   - Admin panel:   `http://localhost/admin/`

   Default admin login can be inserted directly into the `users` table with `role = 'admin'`.

---

## 🔒 Security Notes

- Passwords are stored as bcrypt hashes.
- All database queries use PDO prepared statements to prevent SQL injection.
- Password reset tokens are time-limited and single-use.
- Admin routes check the user's role on every request.

---

## 📸 Screenshots

> *(Add screenshots of the homepage, product listing, and admin dashboard here.)*

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).
