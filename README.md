# 🚀 Geofencing System (PHP + MySQL + Leaflet + Role Management)

![PHP](https://img.shields.io/badge/PHP-8.x-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange?logo=mysql)
![Leaflet](https://img.shields.io/badge/Leaflet-Maps-brightgreen?logo=leaflet)
![License](https://img.shields.io/badge/License-MIT-lightgrey)

A complete **Geofencing & Location Tracking System** built using:

- PHP  
- MySQL  
- Leaflet + OpenStreetMap  
- Leaflet-Draw  
- Session Login  
- Admin/User Role Management  

Admins can create geofences & users.  
Normal users can only view dashboard.

---

## 📁 Project Folder

geofence_app/


## 📂 Folder Structure

geofence_app/
│── dashboard.php
│── login.php
│── login_check.php
│── logout.php
│── manage_users.php
│── save_geofence.php
│── api_location_update.php
│── point_in_polygon.php
│── /uploads/
└── README.md

---

# 🗄 Database Setup

Create database:

```sql
CREATE DATABASE IF NOT EXISTS geo_fencing;
USE geo_fencing;
🔹 admin_users Table

CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admin_users (username, password, role)
VALUES ('admin', MD5('admin123'), 'admin');
🔹 geofences Table

CREATE TABLE geofences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    fence_type VARCHAR(50) DEFAULT 'restricted',
    polygon_geojson JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
🔹 locations Table

CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50),
    latitude DOUBLE,
    longitude DOUBLE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
🔹 geofence_events Table


CREATE TABLE geofence_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50),
    geofence_id INT,
    event_type VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (geofence_id) REFERENCES geofences(id) ON DELETE CASCADE
);

---

# ⚙ Installation


1️⃣ Place project under:
htdocs/geofence_app/

2️⃣ Import SQL
Using phpMyAdmin.

3️⃣ Run Application:

http://localhost/geofence_app/login.php
🔐 Default Login
Username: admin
Password: admin123
Role: admin

📍 Geofencing Features
Draw polygon / rectangle

Save geofence as GeoJSON

Load existing geofences

Uses free OpenStreetMap tiles

🛰 GPS Location API
Endpoint:

POST http://localhost/geofence_app/api_location_update.php
Request Body (JSON):
{
  "user_id": "testuser01",
  "lat": 18.5204,
  "lng": 73.8567
}


🧪 Test with cURL
curl -X POST http://localhost/geofence_app/api_location_update.php \
     -H "Content-Type: application/json" \
     -d '{
           "user_id": "testuser01",
           "lat": 18.5204,
           "lng": 73.8567
         }'
Example Response:
{
  "status": "ok",
  "inside_geofences": []
}


🔒 Security
Admin has full access

Users have limited access

Only Admin sees Add User button

Pages protected by session

Passwords are hashed (MD5 → bcrypt upgrade recommended)


🧠 Tech Stack
Component	Technology
Backend	PHP
Database	MySQL
Map	Leaflet + OSM
Drawing	Leaflet-Draw
Auth	PHP Sessions
Data	GeoJSON



🚀 Future Enhancements
Live GPS map updates

WebSocket real-time tracking

Bootstrap modern UI

Police dashboard

SMS/Email alerts

Blockchain-based identity


📜 License
MIT License © 2025
