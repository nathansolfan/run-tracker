Run Tracker
A web application for tracking and logging your runs with GPS data.
Features

Track runs in real-time using GPS
Record distance, duration, and pace
View route maps of your runs
Track with real GPS on mobile devices
Simulation mode for testing without actual movement
Log manual runs with custom distance and time
View history of all your runs

Technologies Used

Laravel 12.3
JavaScript
Leaflet.js for mapping
MySQL/SQLite database

Installation

Clone the repository:
git clone https://github.com/username/run-tracker.git
cd run-tracker

Install PHP dependencies:
composer install

Install JavaScript dependencies:
npm install

Create your environment file:
cp .env.example .env

Generate application key:
php artisan key:generate

Configure your database in .env file:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

Run migrations:
php artisan migrate

Build assets:
npm run build

Start the development server:
php artisan serve


Deployment
This application can be deployed using Laravel Forge and services like Vultr. For SSL certificates, services like Let's Encrypt can be used with DuckDNS for domain management.
Usage
Tracking a Run

Navigate to the "Track Run" page
Click "Start Run" to begin tracking
For real GPS tracking, toggle "Use Real GPS"
Your route will be displayed on the map
Click "End Run" when finished to save your data

Logging a Run Manually

Navigate to the "Log Run" page
Enter the date, distance, and duration
Add optional notes
Click "Save" to record your run

License
This project is open-sourced software licensed under the MIT license.
Acknowledgments

Laravel - The web framework used
Leaflet - Used for interactive maps
OpenStreetMap - Map data provider