-- Khumbila Adventure Travel Database Setup
-- This is a basic schema to get the project running

CREATE DATABASE IF NOT EXISTS db_khumbila;
USE db_khumbila;

-- Basic tables needed for the project to run
CREATE TABLE IF NOT EXISTS tbl_slider (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) DEFAULT '',
    slide_status ENUM('0', '1') DEFAULT '1',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tbl_about (
    id INT AUTO_INCREMENT PRIMARY KEY,
    short_content TEXT,
    content LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tbl_programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    type VARCHAR(100) DEFAULT 'Tours',
    parent_id INT DEFAULT 0,
    child ENUM('0', '1') DEFAULT '0',
    display ENUM('0', '1') DEFAULT '1',
    content LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tbl_services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    display ENUM('0', '1') DEFAULT '1',
    content LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO tbl_slider (image, slide_status) VALUES 
('sample-slider-1.jpg', '1'),
('sample-slider-2.jpg', '1'),
('sample-slider-3.jpg', '1');

INSERT INTO tbl_about (short_content, content) VALUES 
('Welcome to Khumbila Adventure Travel', 'We at Khumbi-Ila welcome you to embark on a journey that will leave an indelible mark on you. Nothing can capture the mysteries and magnificence of the Himalayas except experience.');

INSERT INTO tbl_programs (title, slug, type, parent_id, child, display) VALUES 
('Everest Base Camp Trek', 'everest-base-camp-trek', 'Tours', 0, '0', '1'),
('Annapurna Circuit Trek', 'annapurna-circuit-trek', 'Tours', 0, '0', '1'),
('Langtang Valley Trek', 'langtang-valley-trek', 'Tours', 0, '0', '1');

INSERT INTO tbl_services (title, slug, display) VALUES 
('Flight Booking', 'flight-booking', '1'),
('Hotel Reservation', 'hotel-reservation', '1'),
('Car Rental', 'car-rental', '1');
