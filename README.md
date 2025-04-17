# Run Tracker

A web application for tracking and logging your runs with GPS data.

## Features

- Track runs in real-time using GPS
- Record distance, duration, and pace
- View route maps of your runs
- Track with real GPS on mobile devices
- Simulation mode for testing without actual movement
- Log manual runs with custom distance and time
- View history of all your runs

## Technologies Used

- Laravel 12.3
- JavaScript
- Leaflet.js for mapping
- MySQL/SQLite database

## Installation

1. Clone the repository: git clone https://github.com/username/run-tracker.git, cd run-tracker

2. Install PHP dependencies: composer install

3. Install JavaScript dependencies: npm install

4. Create your environment file: cp .env.example .env

5. Generate application key: php artisan key

6. Configure your database in .env file:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

7. Run migrations:

php artisan migrate

8. Build assets: