-- ============================================================
-- schema.sql — Full database schema for Allan Guest House
-- Run once to set up the database: mysql -u root guesthouse < schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS guesthouse CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE guesthouse;

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80)  NOT NULL UNIQUE,
    email    VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80)  NOT NULL UNIQUE,
    email    VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Rooms table
CREATE TABLE IF NOT EXISTS rooms (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    number      VARCHAR(10)  NOT NULL UNIQUE,
    type        VARCHAR(50)  NOT NULL,
    price       DECIMAL(10,2) NOT NULL,
    description TEXT,
    available   TINYINT(1) DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    phone      VARCHAR(20)  NOT NULL,
    room       VARCHAR(50)  NOT NULL,
    checkin    DATE         NOT NULL,
    checkout   DATE         NOT NULL,
    status     ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Menu items table
CREATE TABLE IF NOT EXISTS menu_items (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    price      DECIMAL(10,2) NOT NULL,
    category   VARCHAR(50)   DEFAULT 'Main',
    available  TINYINT(1)    DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    guest_name VARCHAR(100) NOT NULL,
    item_id    INT NOT NULL,
    quantity   INT NOT NULL DEFAULT 1,
    status     ENUM('pending','prepared','delivered') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES menu_items(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- Seed data
-- ============================================================

-- Default admin (password: Admin@2025)
INSERT IGNORE INTO admins (username, email, password)
VALUES ('admin', 'emoruallan@gmail.com', '$2y$12$YQm6z.kFkRzGSXJJhkH.OeYKpFqSHFEMVjJFqTJ7hZKJRBjqkRWIK');

-- Sample rooms
INSERT IGNORE INTO rooms (number, type, price, description) VALUES
('101', 'Single',  2500.00, 'A cozy single room with a comfortable bed and a view of the garden.'),
('102', 'Double',  4000.00, 'A spacious double room perfect for couples, featuring modern amenities.'),
('103', 'Suite',   6500.00, 'An elegant suite with a living area, kitchenette, and luxury bathroom.'),
('104', 'Deluxe',  5000.00, 'A premium deluxe room with high-end furnishings and a private balcony.');

-- Sample menu items
INSERT IGNORE INTO menu_items (name, price, category) VALUES
('Chicken Curry',   500.00, 'Main'),
('Beef Stew',       450.00, 'Main'),
('Vegetable Rice',  350.00, 'Main'),
('Fish Fillet',     600.00, 'Main'),
('Chapati',          50.00, 'Side'),
('Ugali',            80.00, 'Side'),
('Soda (500ml)',      60.00, 'Drinks'),
('Water (500ml)',     30.00, 'Drinks');
