CREATE TABLE `users` (
    `user_id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `profile_image` VARCHAR(255) DEFAULT NULL,
    `phone` VARCHAR(15) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `date_of_birth` DATE DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `otp` VARCHAR(6) DEFAULT NULL,
    `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2),
    category VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_main BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


CREATE TABLE orders (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,        
    name VARCHAR(255) NOT NULL,                  
    email VARCHAR(255) NOT NULL,
    contact VARCHAR(15) NOT NULL,                 
    address TEXT NOT NULL,                        
    size VARCHAR(10) NOT NULL,
    payment_method ENUM('online', 'cod') NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,         
    payment_status ENUM('paid', 'pending') DEFAULT 'pending', 
    order_status ENUM('processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'processing', 
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    user_id INT(11) NOT NULL,                     
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admins (username, password) VALUES ('admin', 'admin123');
