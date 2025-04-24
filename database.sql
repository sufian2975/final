CREATE DATABASE wanderlust;

USE wanderlust;

-- Users table for sign-up and login
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Trips table for storing trip overview details
CREATE TABLE trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    start_date DATE,
    end_date DATE,
    duration VARCHAR(50),
    people INT,
    user_id INT,  -- Reference to the user who created the trip
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- To-Do table for storing trip-related tasks
CREATE TABLE todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task VARCHAR(255),
    description TEXT,
    deadline DATETIME,
    trip_id INT,  -- Reference to the trip this task is related to
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trip_id) REFERENCES trips(id) ON DELETE CASCADE
);

-- Expenses table for tracking expenses related to trips
CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_id INT,  -- Reference to the trip the expense is related to
    category VARCHAR(100),
    amount DECIMAL(10, 2),
    date DATE,
    description TEXT,
    FOREIGN KEY (trip_id) REFERENCES trips(id) ON DELETE CASCADE
);

-- Optional: Notifications table to handle user notifications
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,  -- Reference to the user who gets the notification
    message TEXT,
    seen BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);