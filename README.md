# Laravel Request System

A web-based request management system built with the Laravel framework. The system allows users to submit requests, and allows administrators to review, approve, and track the status of those requests. This project was developed as Laboratory Activity 1 for the Development and Operations (DevOps) and Security course.

## Student Information

- **Name:** Mark Julius Bongalbal
- **Course, Year and Section:** BSIT 4-3
- **Subject:** Development and Operations (DevOps) and Security

## Software Requirements

- PHP 8.3.30 or higher
- Composer 2.10.3
- Laravel 13.33.0
- MySQL 8.0
- Laragon 8.6.1 (Apache + MySQL + phpMyAdmin)
- Git 2.54.0
- Node.js 24.15.0 and NPM

## Installation

1. Clone the repository:

       git clone https://github.com/dvmrkuzz/laravel-request-system.git
       cd laravel-request-system

2. Install the PHP dependencies:

       composer install

3. Install the front-end dependencies:

       npm install

4. Copy the environment template and generate the application key:

       copy .env.example .env
       php artisan key:generate

## Database

**Database name:** `laravel_request_system_db`

1. Start Apache and MySQL in the Laragon control panel.
2. Open phpMyAdmin at http://localhost/phpmyadmin.
3. Create a new database named `laravel_request_system_db`.
4. Set your own database credentials inside the `.env` file:

       DB_CONNECTION=mysql
       DB_HOST=127.0.0.1
       DB_PORT=3306
       DB_DATABASE=laravel_request_system_db
       DB_USERNAME=your_username
       DB_PASSWORD=your_password

5. Build the database structure by running the migrations:

       php artisan migrate

An `.sql` export file is not required, because the migration files included in this repository rebuild the complete database structure.

## Running the Project

    php artisan serve

Then open http://127.0.0.1:8000 in your browser.

## Security Notes

- The `.env` file is excluded from version control and must never be committed. Only `.env.example` is tracked.
- The `/vendor` and `/node_modules` folders are excluded and are rebuilt locally using `composer install` and `npm install`.
- No passwords, API keys, or access tokens are stored in this repository.

## Repository

https://github.com/dvmrkuzz/laravel-request-system