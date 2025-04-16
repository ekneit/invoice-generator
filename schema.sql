CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    invoice_date DATE NOT NULL,
    invoice_due DATE,
    
    seller_name VARCHAR(100),
    seller_code VARCHAR(50),
    seller_vat VARCHAR(50),
    seller_address VARCHAR(255),
    seller_bank VARCHAR(100),
    seller_iban VARCHAR(100),

    buyer_name VARCHAR(100),
    buyer_code VARCHAR(50),
    buyer_vat VARCHAR(50),
    buyer_address VARCHAR(255),

    vat_rate DECIMAL(5,2) DEFAULT 21.00,
    subtotal DECIMAL(10,2) DEFAULT 0.00,
    vat_amount DECIMAL(10,2) DEFAULT 0.00,
    total DECIMAL(10,2) DEFAULT 0.00,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE invoice_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    title VARCHAR(255),
    quantity DECIMAL(10,2),
    unit VARCHAR(20),
    price DECIMAL(10,2),
    total DECIMAL(10,2),

    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
);
