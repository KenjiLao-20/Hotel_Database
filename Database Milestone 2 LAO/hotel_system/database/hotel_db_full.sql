-- =====================================================
-- HOTEL CONCIERGE & BILLING SYSTEM
-- FIXED SQL SCRIPT (Correct Foreign Key Order)
-- =====================================================

DROP DATABASE IF EXISTS hotel_db;
CREATE DATABASE hotel_db;
USE hotel_db;

-- =====================================================
-- STEP 1: CREATE TABLES (No foreign key dependencies first)
-- =====================================================

-- TABLE 1: guests (no foreign keys)
CREATE TABLE guests (
    guest_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABLE 2: rooms (no foreign keys)
CREATE TABLE rooms (
    room_id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10) UNIQUE NOT NULL,
    room_type ENUM('Standard', 'Deluxe', 'Suite', 'Presidential') NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL CHECK (price_per_night > 0),
    status ENUM('Available', 'Occupied', 'Maintenance', 'Reserved') DEFAULT 'Available'
);

-- TABLE 3: services (no foreign keys)
CREATE TABLE services (
    service_id INT PRIMARY KEY AUTO_INCREMENT,
    service_name VARCHAR(100) NOT NULL,
    category ENUM('Restaurant', 'Spa', 'MiniBar', 'Laundry', 'Other') NOT NULL,
    price DECIMAL(10,2) NOT NULL CHECK (price > 0)
);

-- TABLE 4: bookings (depends on guests and rooms)
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

-- TABLE 6: invoices (depends on bookings)
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

-- =====================================================
-- STEP 2: INSERT SAMPLE DATA (In correct order)
-- =====================================================

-- Insert guests (room_id will be auto-generated: 1,2,3,4,5,6,7,8)
INSERT INTO guests (first_name, last_name, email, phone, address) VALUES
('John', 'Smith', 'john.smith@email.com', '555-0101', '123 Main St, New York, NY 10001'),
('Maria', 'Garcia', 'maria.garcia@email.com', '555-0102', '456 Oak Ave, Los Angeles, CA 90001'),
('David', 'Lee', 'david.lee@email.com', '555-0103', '789 Pine Rd, Chicago, IL 60601'),
('Sarah', 'Johnson', 'sarah.j@email.com', '555-0104', '321 Elm St, Houston, TX 77001'),
('Robert', 'Brown', 'robert.brown@email.com', '555-0105', '654 Cedar Ln, Phoenix, AZ 85001'),
('Lisa', 'Wilson', 'lisa.wilson@email.com', '555-0106', '987 Birch Dr, Philadelphia, PA 19101'),
('James', 'Martinez', 'james.m@email.com', '555-0107', '147 Maple Way, San Antonio, TX 78201'),
('Emily', 'Taylor', 'emily.taylor@email.com', '555-0108', '258 Walnut St, San Diego, CA 92101');

-- Insert rooms (room_id will be auto-generated: 1,2,3,4,5,6,7,8)
-- NOTE: room_number 101 = room_id 1, 102 = room_id 2, 103 = room_id 3, etc.
INSERT INTO rooms (room_number, room_type, price_per_night, status) VALUES
('101', 'Standard', 120.00, 'Available'),
('102', 'Standard', 120.00, 'Occupied'),
('103', 'Deluxe', 180.00, 'Available'),
('104', 'Deluxe', 180.00, 'Reserved'),
('201', 'Suite', 250.00, 'Available'),
('202', 'Suite', 250.00, 'Occupied'),
('301', 'Presidential', 500.00, 'Available'),
('302', 'Presidential', 500.00, 'Maintenance');

-- Insert services (service_id will be auto-generated: 1-10)
INSERT INTO services (service_name, category, price) VALUES
('Club Sandwich', 'Restaurant', 12.50),
('Caesar Salad', 'Restaurant', 10.00),
('Grilled Cheese', 'Restaurant', 8.50),
('Massage (60min)', 'Spa', 85.00),
('Facial Treatment', 'Spa', 65.00),
('Minibar - Soda', 'MiniBar', 4.00),
('Minibar - Beer', 'MiniBar', 6.00),
('Minibar - Wine', 'MiniBar', 12.00),
('Laundry (Shirt)', 'Laundry', 5.50),
('Laundry (Suit)', 'Laundry', 15.00);

-- Insert bookings (using actual room_id values, NOT room_number)
-- room_id 1 = Room 101, room_id 2 = Room 102, room_id 3 = Room 103, etc.
INSERT INTO bookings (guest_id, room_id, check_in_date, check_out_date, booking_status) VALUES
(1, 1, '2026-04-10', '2026-04-15', 'Active'),     -- John Smith in Room 101
(2, 3, '2026-04-11', '2026-04-14', 'Active'),     -- Maria Garcia in Room 103
(3, 5, '2026-04-12', '2026-04-18', 'Active'),     -- David Lee in Room 201
(4, 4, '2026-04-09', '2026-04-13', 'CheckedOut'), -- Sarah Johnson in Room 104
(5, 6, '2026-04-10', '2026-04-20', 'Active'),     -- Robert Brown in Room 202
(6, 2, '2026-04-15', '2026-04-18', 'Reserved');   -- Lisa Wilson in Room 102

-- Insert room_orders (using actual booking_id and service_id)
INSERT INTO room_orders (booking_id, service_id, quantity) VALUES
(1, 1, 2),  -- Booking 1 ordered 2 Club Sandwiches
(1, 6, 3),  -- Booking 1 ordered 3 Sodas
(2, 4, 1),  -- Booking 2 ordered 1 Massage
(2, 9, 2),  -- Booking 2 ordered 2 Shirt Laundry
(3, 2, 1),  -- Booking 3 ordered 1 Caesar Salad
(3, 7, 2),  -- Booking 3 ordered 2 Beers
(5, 4, 1),  -- Booking 5 ordered 1 Massage
(5, 5, 1);  -- Booking 5 ordered 1 Facial

-- Insert invoices (for checked out booking)
INSERT INTO invoices (booking_id, room_charges, service_charges, tax_amount, payment_status) VALUES
(4, 480.00, 25.00, 60.60, 'Paid');

-- =====================================================
-- STEP 3: CREATE VIEW
-- =====================================================

CREATE VIEW consolidated_charges AS
SELECT 
    b.booking_id,
    g.first_name,
    g.last_name,
    r.room_number,
    r.room_type,
    r.price_per_night,
    b.check_in_date,
    b.check_out_date,
    b.total_nights,
    b.total_nights * r.price_per_night AS room_total,
    COALESCE(SUM(ro.quantity * s.price), 0) AS service_total,
    (b.total_nights * r.price_per_night + COALESCE(SUM(ro.quantity * s.price), 0)) * 0.12 AS tax,
    (b.total_nights * r.price_per_night + COALESCE(SUM(ro.quantity * s.price), 0)) * 1.12 AS grand_total,
    b.booking_status
FROM bookings b
JOIN guests g ON b.guest_id = g.guest_id
JOIN rooms r ON b.room_id = r.room_id
LEFT JOIN room_orders ro ON b.booking_id = ro.booking_id
LEFT JOIN services s ON ro.service_id = s.service_id
GROUP BY b.booking_id;

-- =====================================================
-- STEP 4: CREATE TRANSACTION PROCEDURE
-- =====================================================

DELIMITER $$
CREATE PROCEDURE CheckoutGuest(IN p_booking_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Transaction Failed - Rollback Completed' AS Message;
    END;
    
    START TRANSACTION;
    
    INSERT INTO invoices (booking_id, room_charges, service_charges, tax_amount)
    SELECT 
        booking_id,
        room_total,
        service_total,
        tax
    FROM consolidated_charges
    WHERE booking_id = p_booking_id
    ON DUPLICATE KEY UPDATE
        room_charges = VALUES(room_charges),
        service_charges = VALUES(service_charges),
        tax_amount = VALUES(tax_amount);
    
    UPDATE bookings 
    SET booking_status = 'CheckedOut' 
    WHERE booking_id = p_booking_id;
    
    UPDATE rooms r
    JOIN bookings b ON r.room_id = b.room_id
    SET r.status = 'Available'
    WHERE b.booking_id = p_booking_id;
    
    COMMIT;
    SELECT 'Checkout Successful!' AS Message;
END$$
DELIMITER ;

-- =====================================================
-- STEP 5: VERIFY ALL DATA
-- =====================================================
SELECT 'Guests:' AS Table_Name, COUNT(*) AS Row_Count FROM guests
UNION ALL
SELECT 'Rooms:', COUNT(*) FROM rooms
UNION ALL
SELECT 'Bookings:', COUNT(*) FROM bookings
UNION ALL
SELECT 'Services:', COUNT(*) FROM services
UNION ALL
SELECT 'Room_Orders:', COUNT(*) FROM room_orders
UNION ALL
SELECT 'Invoices:', COUNT(*) FROM invoices;

-- Show all data for verification
SELECT * FROM guests;
SELECT * FROM rooms;
SELECT * FROM bookings;
SELECT * FROM services;
SELECT * FROM room_orders;
SELECT * FROM invoices;