#  Hotel Concierge & Billing System

![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?logo=mysql)
![Python](https://img.shields.io/badge/Python-3.9-green?logo=python)
![CSS](https://img.shields.io/badge/CSS-3-purple?logo=css3)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-yellow?logo=javascript)

##  Project Overview

The **Hotel Concierge & Billing System** is a full-stack database-driven application developed as the capstone project for Database Systems 1. It manages complete hotel operations including guest registration, room booking, service ordering, and automated billing with tax calculation.

###  Key Features

- ✅ Complete CRUD Operations - Create, Read, Update, Delete for all entities
- ✅ Guest Management - Register and manage guest information
- ✅ Room Inventory - Track room types, pricing, and availability status
- ✅ Booking System - Create and manage reservations with date validation
- ✅ Service Menu - Manage hotel services (Restaurant, Spa, MiniBar, Laundry)
- ✅ Room Orders - Place service orders for active bookings
- ✅ Automated Billing - Generate invoices with 12% tax calculation
- ✅ Transaction Logic - Checkout process with rollback capability
- ✅ Python Integration - Billing engine with email reporting
- ✅ Professional UI - Modern, responsive design with dark theme

---

##  Tech Stack

| Layer | Technology | Purpose |
|-------|------------|---------|
| Database | MySQL 8.0 | Data storage with 3NF normalization |
| Backend | PHP 8.2 | CRUD operations and server-side logic |
| Automation | Python 3.9 | Billing engine and reporting |
| Frontend | HTML5, CSS3, JavaScript | User interface and form validation |
| Server | Apache (XAMPP) | Local development environment |

---

##  Database Schema (3NF)

The database contains **6 tables** normalized to Third Normal Form:

| Table | Description |
|-------|-------------|
| guests | Customer master data (name, email, phone, address) |
| rooms | Room inventory (number, type, price, status) |
| bookings | Reservation transactions (links guests to rooms) |
| services | Price list of hotel amenities |
| room_orders | Line items of services ordered per booking |
| invoices | Final billing with tax and payment status |
