-- Create food_requests table
CREATE TABLE food_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    food_item VARCHAR(255) NOT NULL,
    food_type ENUM('Fresh', 'Frozen', 'Canned', 'Packaged') NOT NULL,
    quantity INT NOT NULL,
    category ENUM('Vegetables', 'Fruits', 'Grains', 'Protein', 'Dairy', 'Other') NOT NULL,
    dietary_type ENUM('Vegetarian', 'Vegan', 'Halal', 'Kosher', 'None') NOT NULL,
    location VARCHAR(255) NOT NULL,
    required_by DATE NOT NULL,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create food_donations table
CREATE TABLE food_donations (
    donation_id INT AUTO_INCREMENT PRIMARY KEY,
    food_item VARCHAR(255) NOT NULL,
    category ENUM('Vegetables', 'Fruits', 'Grains', 'Protein', 'Dairy', 'Other') NOT NULL,
    food_type ENUM('Fresh', 'Frozen', 'Canned', 'Packaged', 'Cooked') NOT NULL,
    quantity INT NOT NULL,
    location VARCHAR(255) NOT NULL,
    cooking_time DATETIME NOT NULL,
    best_before DATETIME NOT NULL,
    donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
