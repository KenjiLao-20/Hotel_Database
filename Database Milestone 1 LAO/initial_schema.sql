-- =============================================
-- HOTEL CONCIERGE & BILLING SYSTEM
-- Database: hotel_db
-- Milestone 1 - April 15, 2026
-- =============================================

-- Step 1: Create Database
DROP DATABASE IF EXISTS hotel_db;
CREATE DATABASE hotel_db;
USE hotel_db;

-- Step 2: Create Tables (in correct order to satisfy foreign keys)

-- TABLE 1: guests (no foreign dependencies)
CREATE TABLE guests (
    guest_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NULL,
    address TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABLE 2: rooms (no foreign dependencies)
CREATE TABLE rooms (
    room_id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    room_type ENUM('Standard', 'Deluxe', 'Suite', 'Presidential') NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL CHECK (price_per_night > 0),
    status ENUM('Available', 'Occupied', 'Maintenance', 'Reserved') DEFAULT 'Available'
);

-- TABLE 3: bookings (depends on guests and rooms)
CREATE TABLE bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    guest_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    total_nights INT GENERATED ALWAYS AS (DATEDIFF(check_out_date, check_in_date)) STORED,
    booking_status ENUM('Active', 'CheckedOut', 'Cancelled') DEFAULT 'Active',
    FOREIGN KEY (guest_id) REFERENCES guests(guest_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE CASCADE,
    CHECK (check_out_date > check_in_date)
);

-- TABLE 4: services (no foreign dependencies)
CREATE TABLE services (
    service_id INT PRIMARY KEY AUTO_INCREMENT,
    service_name VARCHAR(100) NOT NULL,
    category ENUM('Restaurant', 'Spa', 'MiniBar', 'Laundry', 'Other') NOT NULL,
    price DECIMAL(10,2) NOT NULL CHECK (price > 0)
);

-- TABLE 5: room_orders (depends on bookings and services)
CREATE TABLE room_orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    service_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    order_datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE
);

-- TABLE 6: invoices (depends on bookings - one-to-one)
CREATE TABLE invoices (
    invoice_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL UNIQUE,
    room_charges DECIMAL(10,2) DEFAULT 0,
    service_charges DECIMAL(10,2) DEFAULT 0,
    tax_amount DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) GENERATED ALWAYS AS (room_charges + service_charges + tax_amount) STORED,
    payment_status ENUM('Unpaid', 'Paid', 'PartiallyPaid') DEFAULT 'Unpaid',
    invoice_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE
);

-- Step 3: Create Indexes for Performance
CREATE INDEX idx_bookings_dates ON bookings(check_in_date, check_out_date);
CREATE INDEX idx_bookings_status ON bookings(booking_status);
CREATE INDEX idx_room_orders_datetime ON room_orders(order_datetime);
CREATE INDEX idx_invoices_status ON invoices(payment_status);

-- Step 4: Display all tables created
SHOW TABLES;

-- Step 5: Display structure of each table (for verification)
DESCRIBE guests;
DESCRIBE rooms;
DESCRIBE bookings;
DESCRIBE services;
DESCRIBE room_orders;
DESCRIBE invoices;