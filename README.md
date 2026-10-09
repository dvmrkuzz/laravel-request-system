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

## Request Data Model (Laboratory 2)

The `requests` table stores each submitted request.

| Field | Type | Constraint | Purpose |
|---|---|---|---|
| id | BIGINT UNSIGNED | Primary key, auto-increment | Unique request number |
| requester_name | VARCHAR(100) | Required | Person submitting the request |
| requester_email | VARCHAR(255) | Required | Contact address |
| item_name | VARCHAR(150) | Required | Requested item or service |
| quantity | INT UNSIGNED | Required | Requested quantity |
| purpose | TEXT | Required | Reason for the request |
| status | VARCHAR(20) | Default `pending` | Request state |
| created_at | TIMESTAMP | Auto | Creation time |
| updated_at | TIMESTAMP | Auto | Last update time |

### Creating the migration

    php artisan make:migration create_requests_table

### Verifying the table

    php artisan migrate
    php artisan migrate:status

Then open phpMyAdmin, select `laravel_request_system_db`, and inspect the
`requests` table structure. Confirm the default status by inserting a row
without a `status` value and checking that it is stored as `pending`.

### User Stories

- **Requester:** As a requester, I want to submit a request containing my name,
  email, the item I need, the quantity, and my reason for needing it, so that
  the office has a complete record of what I am asking for.
- **Staff Reviewer:** As a staff reviewer, I want every incoming request to
  arrive with a clear status and a stated purpose, so that I can tell which
  requests still need a decision.
- **Record Keeper:** As a record keeper, I want each request to carry a unique
  identifying number and automatic timestamps, so that I can trace any request
  back to when it was filed and last changed.
  
## Running the Project

    php artisan serve

Then open http://127.0.0.1:8000 in your browser.

## Security Notes

- The `.env` file is excluded from version control and must never be committed. Only `.env.example` is tracked.
- The `/vendor` and `/node_modules` folders are excluded and are rebuilt locally using `composer install` and `npm install`.
- No passwords, API keys, or access tokens are stored in this repository.

## Repository

https://github.com/dvmrkuzz/laravel-request-system

## Access Control (Laboratory 3)

### Ownership rules

- All request routes require authentication. Guests are redirected to the login page.
- Students see only requests where `user_id` matches their own account.
- A record may be viewed by its owner or by an administrator.
- Any authenticated student may create a request.
- Only an administrator may update a request status.
- Ownership is determined from `user_id`, never from `requester_name` or `requester_email`.

### Denial response

A student requesting another student's record receives **404 Not Found**, applied
consistently. A 403 would confirm the record exists, which gives an attacker useful
information while probing sequential IDs. A 404 reveals nothing. The status-update
route returns 403 instead, because there the user may legitimately see the record and
only the action is denied.

### Routes

| Verb | URI | Method | Who may use it |
|---|---|---|---|
| GET | /requests | index | Any signed-in user; results scoped by role |
| GET | /requests/create | create | Students only |
| POST | /requests | store | Students only |
| GET | /requests/{id} | show | Owner or administrator |
| PATCH | /requests/{id}/status | updateStatus | Administrator only |

### File responsibilities

| File or area | Maintainer |
|---|---|
| app/Policies/ServiceRequestPolicy.php | Mark Julius Bongalbal |
| app/Http/Controllers/ServiceRequestController.php | Mark Julius Bongalbal |
| routes/web.php | Mark Julius Bongalbal |
| resources/views/requests/ | Mark Julius Bongalbal |
| README.md | Shiela Mae Fungo |

### Testing the access controls

1. Run the migrations and seed the accounts:

       php artisan migrate
       php artisan db:seed --class=AccountSeeder

2. Start the server with `php artisan serve`.
3. Log out and open `/requests` — you should be redirected to login.
4. Log in as Student A and confirm the list shows only their own records.
5. Open another student's record ID directly — you should receive 404.
6. Log in as the administrator and confirm all records are listed and a status
   change is saved.
7. Submit a quantity of 0 or a blank item name — the server rejects the input.