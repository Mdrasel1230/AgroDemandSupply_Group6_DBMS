-- Database creation
CREATE DATABASE IF NOT EXISTS AgricultureDB;
USE AgricultureDB;

-- WAREHOUSE table
CREATE TABLE WAREHOUSE (
    warehouseID INT AUTO_INCREMENT PRIMARY KEY,
    zone VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    capacity INT NOT NULL
);

-- PRODUCT table
CREATE TABLE PRODUCT (
    productID INT AUTO_INCREMENT PRIMARY KEY,
    productName VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,
    variety VARCHAR(100),
    seasonality VARCHAR(50)
);

-- PRODUCTION_RECORD table
CREATE TABLE PRODUCTION_RECORD (
    recordID INT AUTO_INCREMENT PRIMARY KEY,
    productID INT,
    year YEAR NOT NULL,
    yield DECIMAL(10, 2) NOT NULL,
    acreage DECIMAL(10, 2) NOT NULL,
    costOfProduction DECIMAL(10, 2),
    region VARCHAR(100)
);

-- PRICE_HISTORY table
CREATE TABLE PRICE_HISTORY (
    priceID INT AUTO_INCREMENT PRIMARY KEY,
    productID INT,
    date DATE NOT NULL,
    perUnitPriceValue DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (productID) REFERENCES PRODUCT(productID) ON DELETE CASCADE
);

-- SUPPLIER table
CREATE TABLE SUPPLIER (
    supplierID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contactInfo VARCHAR(255),
    regionSupplied VARCHAR(100)
);

-- STORAGE_SUPPLY table
CREATE TABLE STORAGE_SUPPLY (
    supplyID INT AUTO_INCREMENT PRIMARY KEY,
    warehouseID INT,
    productID INT,
    quantity DECIMAL(10, 2) NOT NULL,
    unitPrice DECIMAL(10, 2),
    storageSupplyDate DATE NOT NULL,
    transportDetails VARCHAR(255),
    FOREIGN KEY (warehouseID) REFERENCES WAREHOUSE(warehouseID) ON DELETE CASCADE,
    FOREIGN KEY (productID) REFERENCES PRODUCT(productID) ON DELETE CASCADE
);

-- RETAILER table
CREATE TABLE RETAILER (
    retailerID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contactInfo VARCHAR(255),
    registeredDate DATE NOT NULL,
    region VARCHAR(100)
);

-- RETAIL_SUPPLY table
CREATE TABLE RETAIL_SUPPLY (
    retailSupplyID INT AUTO_INCREMENT PRIMARY KEY,
    retailerID INT,
    productID INT,
    retailSupplyDate DATE NOT NULL,
    quantity DECIMAL(10, 2) NOT NULL,
    unitPrice DECIMAL(10, 2),
    totalCost DECIMAL(10, 2),
    FOREIGN KEY (retailerID) REFERENCES RETAILER(retailerID) ON DELETE CASCADE,
    FOREIGN KEY (productID) REFERENCES PRODUCT(productID) ON DELETE CASCADE
);

-- INVOICE table
CREATE TABLE INVOICE (
    invoiceID INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    quantity DECIMAL(10, 2),
    unitPrice DECIMAL(10, 2),
    subTotal DECIMAL(10, 2)
);

-- DEMAND_ANALYSIS table
CREATE TABLE DEMAND_ANALYSIS (
    demandID INT AUTO_INCREMENT PRIMARY KEY,
    productID INT,
    consumptionPattern TEXT NOT NULL,
    priceElasticity DECIMAL(10, 2),
    FOREIGN KEY (productID) REFERENCES PRODUCT(productID) ON DELETE CASCADE
);

-- ANALYTICS_DATA table (to support dynamic analytics)
CREATE TABLE ANALYTICS_DATA (
    analyticsID INT AUTO_INCREMENT PRIMARY KEY,
    year YEAR NOT NULL,
    productID INT,
    quantitySupplied DECIMAL(10, 2),
    quantityDemanded DECIMAL(10, 2),
    region VARCHAR(100),
    FOREIGN KEY (productID) REFERENCES PRODUCT(productID) ON DELETE CASCADE
);

-- SUPPLY_FORECAST_DATA table
CREATE TABLE SUPPLY_FORECAST_DATA (
    forecastID INT AUTO_INCREMENT PRIMARY KEY,
    year YEAR NOT NULL,
    productID INT,
    quantitySupplied DECIMAL(10, 2),
    FOREIGN KEY (productID) REFERENCES PRODUCT(productID) ON DELETE CASCADE
);

-- Create the `users` table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'supplier', 'consumer', 'warehouse', 'retailer') NOT NULL DEFAULT 'consumer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

