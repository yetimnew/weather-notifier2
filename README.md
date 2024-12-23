# Weather Notifier Application

## Project Overview
The Weather Notifier application alerts users about specific weather conditions, focusing on:
- **High Precipitation Levels**
- **Harmful UV Index Levels**

The application allows users to:
- Receive notifications via **email**.
- Set **custom thresholds** for weather alerts.
- Configure alerts for **multiple cities**.

This project is built using **Laravel**, powered by **Laravel Sail** for Docker support, and adheres to modern web development practices.

---

## Requirements Fulfilled

### **Core Features**
1. **User Authentication**
   - Implemented using **Laravel Jetstream**.
   - Provides user registration, login, and profile management.

2. **Weather Alerts**
   - Integrated with a weather API (e.g., OpenWeather, WeatherAPI).
   - Sends notifications via **Laravel Notifications** when thresholds are exceeded.

3. **Custom Thresholds**
   - Users can set personalized thresholds for precipitation and UV index.
   - Alerts configured for multiple cities.

4. **Database Configuration**
   - Configured MySQL for managing users, cities, thresholds, and session data.
   - Successfully migrated all required tables, including the `sessions` table for database-based sessions.

5. **One-Button Docker Setup**
   - Leveraged **Laravel Sail** for a seamless Dockerized environment.
   - `sail up` command starts all required services (e.g., MySQL, Redis).

---

## Installation Instructions

### **Prerequisites**
- **Docker** installed on your machine.
- **Composer** installed for PHP dependencies.
- Weather API key (e.g., from OpenWeather).

### **Steps**

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd weather-notifier
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Configure Environment Variables**
   - Copy the example `.env` file:
     ```bash
     cp .env.example .env
     ```
   - Set the following variables in `.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=mysql
     DB_PORT=3306
     DB_DATABASE=weather_notifier
     DB_USERNAME=sail
     DB_PASSWORD=password

     SESSION_DRIVER=database
     MAIL_MAILER=smtp
     MAIL_HOST=mailpit
     MAIL_PORT=1025
     MAIL_USERNAME=null
     MAIL_PASSWORD=null
     MAIL_ENCRYPTION=null

     WEATHER_API_KEY=<your-weather-api-key>
     ```

4. **Set Up Laravel Sail**
   - Install Sail:
     ```bash
     php artisan sail:install
     ```
   - Start Docker containers:
     ```bash
     ./vendor/bin/sail up -d
     ```

5. **Run Migrations**
   - Create the database schema:
     ```bash
     ./vendor/bin/sail artisan migrate
     ```

6. **Serve the Application**
   - Run the development server:
     ```bash
     ./vendor/bin/sail artisan serve
     ```
   - Access the application at: [http://localhost:8000](http://localhost:8000)

---

## Features Implemented

### **User Authentication**
- Powered by **Laravel Jetstream**.
- Includes registration, login, and profile management.

### **Weather Data Fetching**
- Integrated with a weather API to fetch:
  - Precipitation data.
  - UV index data.

### **Notifications**
- Sends email alerts using Laravel Notifications when thresholds are exceeded.

### **Custom User Settings**
- Users can:
  - Set personalized thresholds.
  - Add multiple cities for alerts.

### **Database Management**
- Configured MySQL with proper migrations:
  - `users`, `sessions`, `cities`, and `thresholds` tables.

### **Dockerized Setup**
- One-command Docker setup using Laravel Sail.
- Includes MySQL, Redis, and Mailpit.

---

## Future Enhancements
- Add more notification channels (e.g., SMS, Push Notifications).
- Support for additional weather anomalies.
- UI improvements for a more cohesive user experience.
- Advanced features like pausing notifications for a specified duration.

---

## Troubleshooting

### Common Issues
1. **Port Conflicts**
   - Ensure ports required by Sail services are not in use. Modify the `docker-compose.yml` file if needed.

2. **Missing `sessions` Table**
   - Run the following command to create the table:
     ```bash
     ./vendor/bin/sail artisan session:table
     ./vendor/bin/sail artisan migrate
     ```

3. **Weather API Errors**
   - Ensure your API key is valid and set in the `.env` file.

4. **Docker Permission Errors**
   - Ensure your user has permission to run Docker commands or use `sudo`.

---

## Contributors
- **Your Name**
- **Additional Contributors**

---

## License
This project is licensed under the [MIT License](LICENSE).
