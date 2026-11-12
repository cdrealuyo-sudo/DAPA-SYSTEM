CREATE DATABASE lgu_dapa_reservation;

USE lgu_dapa_reservation;

-- USERS TABLE
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'staff') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- RESOURCES TABLE
CREATE TABLE resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('facility', 'equipment') NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- RESERVATIONS TABLE
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    resource_id INT NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected', 'Cancelled') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (resource_id) REFERENCES resources(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- FACILITIES
INSERT INTO resources (name, type, description) VALUES
('Dapa Gymnasium', 'facility', 'LGU Dapa Gymnasium'),
('Teatro', 'facility', 'LGU Dapa Teatro'),
('Covered Court', 'facility', 'LGU Dapa Covered Court');

-- EQUIPMENT
INSERT INTO resources (name, type, description) VALUES
('Chairs', 'equipment', 'Chairs available for reservation'),
('Tables', 'equipment', 'Tables available for reservation'),
('Tents', 'equipment', 'Tents available for reservation');

-- STAFF ACCOUNT
INSERT INTO users (full_name, email, password, role)
VALUES (
    'LGU Dapa Staff',
    'staff@lgudapa.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC0oJ3yRz6pXqG8K5h7m',
    'staff'
);