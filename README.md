# HRM Module - Momagic Bangladesh Ltd. (A concern of Edison Group)

This is a Laravel 10 project for HRM Management Module.

---

## Project Requirements

- **PHP**: ^8.1  
- **Laravel Framework**: ^10.10  
- **Database**: MySQL  

---

## Installation & Setup

1. Clone the repository:

git clone https://github.com/Rajan225263/edison_hrm_rajan.git
cd edison_hrm_rajan
Install dependencies:

composer install
Copy .env file and set your environment variables:
cp .env.example .env

Generate application key:
php artisan key:generate


Run migrations:
php artisan migrate

Start the development server:

php artisan serve
Visit the application at http://127.0.0.1:8000