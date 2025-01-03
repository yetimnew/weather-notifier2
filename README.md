
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
   - Integrated with a weather API (WeatherAPI).
   - Sends notifications via **Laravel Notifications** when thresholds are exceeded.

3. **Custom Thresholds**
   - Users can set personalized thresholds for precipitation and UV index.
   - Alerts configured for multiple cities.

4. **Database Configuration**
   - Configured MySQL for managing users, cities, thresholds, and session data.
   - Database tables:
     - **`cities`**: Stores city details such as `id`, `name`, `latitude`, `longitude`, and other metadata.
     - **`city_user`**: A pivot table to manage the many-to-many relationship between users and cities, including columns like `uv_threshold`, `precipitation_threshold`, and timestamps.
   - Successfully migrated all required tables, including the `sessions` table for database-based sessions.

5. **One-Button Docker Setup**
   - Leveraged **Laravel Sail** for a seamless Dockerized environment.
   - `sail up` command starts all required services (e.g., MySQL, Redis).

---

## Installation Instructions

### **Prerequisites**
- **Docker** installed on your machine.
- **Composer** installed for PHP dependencies.
- Weather API key from WeatherAPI.

### **Steps**

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yetimnew/weather-notifier2
   cd weather-notifier2
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
     DB_DATABASE=weather_notifier2
     DB_USERNAME=root
     DB_PASSWORD=

     MAIL_MAILER=smtp
     MAIL_HOST=sandbox.smtp.mailtrap.io
     MAIL_PORT=2525
     MAIL_USERNAME=8a4a70983e2b09
     MAIL_PASSWORD=2c1c6e88501a74
     MAIL_FROM_ADDRESS="yetimnew@gmail.com"
     MAIL_FROM_NAME="${APP_NAME}"

     WEATHER_API_KEY=352d96f2df464b8bb6f122344242312
     WEATHER_API_URL=http://api.weatherapi.com/v1
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
   - Access the application at: [http://localhost](http://localhost)
   - You can also follow the Telescope route at: [http://localhost/telescope/](http://localhost/telescope/)

7. **Dispatching Events**
   - Before dispatching the event, make sure to run the following command:
     ```bash
     ./vendor/bin/sail artisan queue:work
     ```
   - For production, we will use **Supervisor** or **Cron Job** to manage the queue workers.

---

## Features Implemented

### **User Authentication**
- Powered by **Laravel Jetstream**.
- Includes registration, login, and profile management.

### **Weather Data Fetching**
- Integrated with WeatherAPI to fetch:
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
  - `users`, `sessions`, `cities`, and `city_user` tables.

### **Dockerized Setup**
- One-command Docker setup using Laravel Sail.
- Includes MySQL, Redis, and Mailpit.

---

## Common Issues
1. **Port Conflicts**
   - Ensure ports required by Sail services are not in use. Modify the `docker-compose.yml` file if needed.

2. **Weather API Errors**
   - Ensure your API key is valid and set in the `.env` file.

3. **Docker Permission Errors**
   - Ensure your user has permission to run Docker commands or use `sudo`.

---

## Contributors
- **Yetimeshet Tadesse**

---

## License
This project is licensed under the [MIT License](LICENSE).
