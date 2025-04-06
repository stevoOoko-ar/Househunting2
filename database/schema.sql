-- Create Database
CREATE DATABASE if not exists househunt;
USE househunt;

-- Create Users Table (Students, Landlords, Admins)
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    role ENUM('student', 'landlord', 'admin') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reset_code varchar(10) null
);

-- Create Houses Table (House Listings)
CREATE TABLE houses (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    landlord_id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    type ENUM('single', 'bedsitter', 'self-contained', 'shared') NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    vacant_rooms INT(11) NOT NULL,
    availability ENUM('available', 'occupied') DEFAULT 'available',
    images TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    descriptions TEXT NULL,
    FOREIGN KEY (landlord_id) REFERENCES users(id) ON DELETE CASCADE
);


-- Create Reviews Table (Student House Ratings)
CREATE TABLE reviews (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    house_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    rating INT(1) CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (house_id) REFERENCES houses(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create Applications Table (House Booking & Applications)
CREATE TABLE applications (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    house_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    visit_date DATE NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (house_id) REFERENCES houses(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create Messages Table (Landlord-Student Chat System)
CREATE TABLE messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    sender_id int(11) not null,
    name varchar(11) NULL,
    email varchar(30)  NULL,
    message TEXT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create Payments Table (Rent Payments & Deposits)
CREATE TABLE payments (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT(11) NOT NULL,
    house_id INT(11) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('MPESA', 'Bank Transfer') NOT NULL,
    transaction_id VARCHAR(255) UNIQUE,
    status ENUM('Pending', 'Confirmed') DEFAULT 'Pending',
    paid_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (house_id) REFERENCES houses(id) ON DELETE CASCADE
);

CREATE TABLE landlord_payments (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    landlord_id INT(11) NOT NULL,
    house_id INT(11) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('MPESA', 'Bank Transfer') NOT NULL,
    transaction_id VARCHAR(255) UNIQUE,
    status ENUM('Pending', 'Confirmed') DEFAULT 'Pending',
    paid_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (landlord_id) REFERENCES users(id) ON DELETE CASCADE
);




-- Create Shortlist Table (Students Save Favorite Houses)
CREATE TABLE shortlist (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    house_id INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (house_id) REFERENCES houses(id) ON DELETE CASCADE
);

-- Create Reports Table (Fraud & Security Issues)
CREATE TABLE reports (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    house_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    issue TEXT NOT NULL,
    status ENUM('pending', 'resolved') DEFAULT 'pending',
    reported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (house_id) REFERENCES houses(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create Landlord Ratings Table (Trust Scores)
CREATE TABLE landlord_ratings (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    landlord_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    rating INT(1) CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (landlord_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create Community Forum Table (Discussions)
CREATE TABLE forum (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
