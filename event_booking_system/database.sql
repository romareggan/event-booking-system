CREATE DATABASE IF NOT EXISTS event_booking
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE event_booking;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    location VARCHAR(150) NOT NULL,
    ticket_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    available_slots INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    number_of_tickets INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    booking_status ENUM('Confirmed','Cancelled') NOT NULL DEFAULT 'Confirmed',
    CONSTRAINT fk_booking_user FOREIGN KEY (user_id) REFERENCES users(user_id),
    CONSTRAINT fk_booking_event FOREIGN KEY (event_id) REFERENCES events(event_id)
);

-- Register an account normally, then promote it to admin in phpMyAdmin:
-- UPDATE users SET role='admin' WHERE email='your-email@example.com';

INSERT INTO events (event_name, description, event_date, event_time, location, ticket_price, available_slots)
VALUES
('Technology Innovation Day', 'A simple event showcasing student technology projects.', '2027-03-15', '09:00:00', 'University Main Hall', 200.00, 100),
('Music and Cultural Festival', 'A day of music, performances and cultural activities.', '2027-04-20', '14:00:00', 'University Grounds', 300.00, 150);
