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

2. Install PHP dependencies: 
composer install

3. Install JavaScript dependencies:
npm install

4. Create your environment file: 
cp .env.example .env

5. Generate application key: 
php artisan key

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
npm run build

9. Start the development server:
php artisan serve

## Deployment

This application can be deployed using Laravel Forge and services like Vultr. For SSL certificates, services like Let's Encrypt can be used with DuckDNS for domain management.

## Usage

### Tracking a Run

1. Navigate to the "Track Run" page
2. Click "Start Run" to begin tracking
3. For real GPS tracking, toggle "Use Real GPS"
4. Your route will be displayed on the map
5. Click "End Run" when finished to save your data

### Logging a Run Manually

1. Navigate to the "Log Run" page
2. Enter the date, distance, and duration
3. Add optional notes
4. Click "Save" to record your run

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Acknowledgments

- [Laravel](https://laravel.com/) - The web framework used
- [Leaflet](https://leafletjs.com/) - Used for interactive maps
- [OpenStreetMap](https://www.openstreetmap.org/) - Map data provider
