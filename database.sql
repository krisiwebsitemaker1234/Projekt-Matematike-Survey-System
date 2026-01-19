-- Database: survey_system

CREATE DATABASE IF NOT EXISTS survey_system;
USE survey_system;

-- Table for superadmin (only one)
CREATE TABLE IF NOT EXISTS superadmin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for users (survey collectors)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for records (surveys/themes created by superadmin)
CREATE TABLE IF NOT EXISTS records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    theme VARCHAR(255) NOT NULL,
    description TEXT,
    num_options INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for record options (the choices available in each record)
CREATE TABLE IF NOT EXISTS record_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    option_text VARCHAR(255) NOT NULL,
    option_order INT NOT NULL,
    FOREIGN KEY (record_id) REFERENCES records(id) ON DELETE CASCADE
);

-- Table for user assignments (which users can collect data for which records)
CREATE TABLE IF NOT EXISTS user_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    record_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (record_id) REFERENCES records(id) ON DELETE CASCADE,
    UNIQUE KEY unique_assignment (user_id, record_id)
);

-- Table for responses (collected survey data)
CREATE TABLE IF NOT EXISTS responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    user_id INT NOT NULL,
    respondent_name VARCHAR(100) NOT NULL,
    option_id INT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (record_id) REFERENCES records(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (option_id) REFERENCES record_options(id) ON DELETE CASCADE
);

-- Insert default superadmin (username: admin, password: admin123)
-- Password is hashed using PHP's password_hash function
INSERT INTO superadmin (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Note: This is a hashed version of 'admin123'
-- In production, change this password immediately!
