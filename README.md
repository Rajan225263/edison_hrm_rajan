# Sales Module - ITWay

This is a Laravel 10 project for Sales Management Module.

---

## Project Requirements

- **PHP**: ^8.1  
- **Laravel Framework**: ^10.10  
- **Database**: MySQL  

---

## Installation & Setup

1. Clone the repository:

git clone https://github.com/Rajan225263/sales_module_itway.git
cd sales_module_itway
Install dependencies:

composer install
Copy .env file and set your environment variables:
cp .env.example .env

Generate application key:
php artisan key:generate


Run migrations:
php artisan migrate
Seed the database with sample data:


php artisan db:seed

Start the development server:

php artisan serve
Visit the application at http://127.0.0.1:8000