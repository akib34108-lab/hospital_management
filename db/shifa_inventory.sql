CREATE DATABASE IF NOT EXISTS shifa_inventory
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE shifa_inventory;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS inventory_adjustments;
DROP TABLE IF EXISTS inventory_issue_items;
DROP TABLE IF EXISTS inventory_issues;
DROP TABLE IF EXISTS inventory_purchase_items;
DROP TABLE IF EXISTS inventory_purchases;
DROP TABLE IF EXISTS inventory_stock;
DROP TABLE IF EXISTS inventory_locations;
DROP TABLE IF EXISTS inventory_suppliers;
DROP TABLE IF EXISTS inventory_items;
DROP TABLE IF EXISTS inventory_categories;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. Categories
CREATE TABLE inventory_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NULL,
    status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Items
CREATE TABLE inventory_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    item_code VARCHAR(50) NOT NULL UNIQUE,
    item_name VARCHAR(150) NOT NULL,
    brand_name VARCHAR(100) NULL,
    unit VARCHAR(30) NOT NULL,
    reorder_level INT NOT NULL DEFAULT 0,
    unit_cost DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    expiry_date DATE NULL,
    description TEXT NULL,
    status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_items_category
        FOREIGN KEY (category_id)
        REFERENCES inventory_categories(category_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 3. Suppliers
CREATE TABLE inventory_suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(150) NOT NULL,
    contact_person VARCHAR(100) NULL,
    phone VARCHAR(20) NULL,
    email VARCHAR(100) NULL,
    address VARCHAR(255) NULL,
    status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 4. Locations
CREATE TABLE inventory_locations (
    location_id INT AUTO_INCREMENT PRIMARY KEY,
    location_name VARCHAR(100) NOT NULL,
    location_type ENUM(
        'Central Store',
        'Department Store',
        'Emergency Store'
    ) NOT NULL,
    address VARCHAR(255) NULL,
    status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 5. Current Stock
CREATE TABLE inventory_stock (
    stock_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    location_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    reserved_quantity INT NOT NULL DEFAULT 0,
    available_quantity INT NOT NULL DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_stock_item
        FOREIGN KEY (item_id)
        REFERENCES inventory_items(item_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_stock_location
        FOREIGN KEY (location_id)
        REFERENCES inventory_locations(location_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT uq_stock_item_location
        UNIQUE (item_id, location_id)
) ENGINE=InnoDB;

-- 6. Purchases
CREATE TABLE inventory_purchases (
    purchase_id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_no VARCHAR(50) NOT NULL UNIQUE,
    supplier_id INT NOT NULL,
    location_id INT NOT NULL,
    purchase_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    payment_status ENUM('Paid','Pending','Partial')
        NOT NULL DEFAULT 'Pending',
    status ENUM('Received','Pending','Cancelled')
        NOT NULL DEFAULT 'Pending',
    notes TEXT NULL,
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_purchase_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES inventory_suppliers(supplier_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_purchase_location
        FOREIGN KEY (location_id)
        REFERENCES inventory_locations(location_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 7. Purchase Items
CREATE TABLE inventory_purchase_items (
    purchase_item_id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_cost DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_purchase_items_purchase
        FOREIGN KEY (purchase_id)
        REFERENCES inventory_purchases(purchase_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_purchase_items_item
        FOREIGN KEY (item_id)
        REFERENCES inventory_items(item_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 8. Issues
CREATE TABLE inventory_issues (
    issue_id INT AUTO_INCREMENT PRIMARY KEY,
    issue_no VARCHAR(50) NOT NULL UNIQUE,
    department_id INT NULL,
    location_id INT NOT NULL,
    issue_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    issued_by INT NULL,
    status ENUM('Issued','Pending','Cancelled')
        NOT NULL DEFAULT 'Pending',
    notes TEXT NULL,
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_issue_location
        FOREIGN KEY (location_id)
        REFERENCES inventory_locations(location_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 9. Issue Items
CREATE TABLE inventory_issue_items (
    issue_item_id INT AUTO_INCREMENT PRIMARY KEY,
    issue_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,

    CONSTRAINT fk_issue_items_issue
        FOREIGN KEY (issue_id)
        REFERENCES inventory_issues(issue_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_issue_items_item
        FOREIGN KEY (item_id)
        REFERENCES inventory_items(item_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 10. Stock Adjustments
CREATE TABLE inventory_adjustments (
    adjustment_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    location_id INT NOT NULL,
    adjustment_type ENUM('Increase','Decrease') NOT NULL,
    quantity INT NOT NULL,
    reason VARCHAR(255) NOT NULL,
    adjusted_by INT NULL,
    adjustment_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_adjustment_item
        FOREIGN KEY (item_id)
        REFERENCES inventory_items(item_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_adjustment_location
        FOREIGN KEY (location_id)
        REFERENCES inventory_locations(location_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Optional initial data
INSERT INTO inventory_categories
(category_name, description)
VALUES
('Gloves', 'Medical gloves'),
('Surgical Items', 'Surgical and operation-related items'),
('Equipment', 'Hospital equipment');

INSERT INTO inventory_locations
(location_name, location_type, address)
VALUES
('Main Store', 'Central Store', NULL),
('OT Store', 'Department Store', NULL),
('Emergency Store', 'Emergency Store', NULL);

SET FOREIGN_KEY_CHECKS = 1;
