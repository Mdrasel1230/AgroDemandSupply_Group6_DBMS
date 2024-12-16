-- Database creation
CREATE DATABASE IF NOT EXISTS agriculture_db;
USE agriculture_db;

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

-- Insert sample user data
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1,	'admin',	'admin@agro.com',	'$2y$10$Z4ABhsLM4Mz3pcIXXDXEOuojSDorA4hWQq0Rg.Bt.Gs3zmRDlkaLy',	'admin',	'2024-11-30 19:27:52'),
(2,	'test',	'test@gmail.com',	'$2y$10$akv2rYaQO6A5sunLInoWAOJ8XjebbKoqv9mY0HcKa/5wWYiVFBstK',	'admin',	'2024-12-13 20:30:39'),
(3,	'test2',	'sgs@gsgs.s',	'$2y$10$2.tttmNg5MHEh4OKjZtge.3v0YluazGefxKAln1Pc0pdoGOPmi4Du',	'supplier',	'2024-12-15 21:36:11');


INSERT INTO DEMAND_ANALYSIS (productID, consumptionPattern, priceElasticity)
VALUES
(1, 'Steady increase in demand during the winter season.', 0.8),
(2, 'Demand fluctuates based on festival seasons.', 1.2),
(3, 'High demand during summer months.', 0.5),
(4, 'Demand remains consistent throughout the year.', 0.3),
(5, 'Sudden spike in demand due to recent market trends.', 1.5);


INSERT INTO ANALYTICS_DATA (year, productID, quantitySupplied, quantityDemanded, region)
VALUES
(2024, 1, 1000.00, 1200.00, 'Dhaka'),
(2024, 1, 800.00, 900.00, 'Chattogram'),
(2024, 2, 500.00, 600.00, 'Sylhet'),
(2024, 3, 200.00, 250.00, 'Khulna'),
(2024, 4, 1500.00, 1300.00, 'Barishal'),
(2024, 5, 1000.00, 1100.00, 'Rajshahi'),
(2023, 1, 950.00, 1000.00, 'Dhaka'),
(2023, 2, 450.00, 500.00, 'Sylhet'),
(2023, 3, 180.00, 200.00, 'Khulna');
