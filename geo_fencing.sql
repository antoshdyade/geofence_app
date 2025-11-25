-- -----------------------------------------------------
-- 1. CREATE DATABASE
-- -----------------------------------------------------
CREATE DATABASE IF NOT EXISTS geo_fencing;
USE geo_fencing;

-- -----------------------------------------------------
-- 2. ADMIN USERS TABLE (login + roles)
-- -----------------------------------------------------
DROP TABLE IF EXISTS admin_users;
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin account
INSERT INTO admin_users (username, password, role)
VALUES ('admin', MD5('admin123'), 'admin');

-- -----------------------------------------------------
-- 3. GEOFENCES TABLE (Polygon JSON stored)
-- -----------------------------------------------------
DROP TABLE IF EXISTS geofences;
CREATE TABLE geofences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    fence_type VARCHAR(50) DEFAULT 'restricted',
    polygon_geojson JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- 4. LOCATIONS TABLE (Device GPS updates)
-- -----------------------------------------------------
DROP TABLE IF EXISTS locations;
CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL,
    latitude DOUBLE NOT NULL,
    longitude DOUBLE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- 5. GEOFENCE EVENTS TABLE (Entry/Exit tracking)
-- -----------------------------------------------------
DROP TABLE IF EXISTS geofence_events;
CREATE TABLE geofence_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL,
    geofence_id INT NOT NULL,
    event_type VARCHAR(20) NOT NULL,  -- inside, enter, exit
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (geofence_id) REFERENCES geofences(id) ON DELETE CASCADE
);

-- -----------------------------------------------------
-- 6. SAMPLE DATA (optional)
-- -----------------------------------------------------
-- INSERT INTO geofences (name, fence_type, polygon_geojson)
-- VALUES ('Test Zone', 'restricted', '{"type":"Polygon","coordinates":[[[78.1,20.1],[78.2,20.1],[78.2,20.2],[78.1,20.2],[78.1,20.1]]]}');
