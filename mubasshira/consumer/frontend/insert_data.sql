-- Reset AUTO_INCREMENT for PRODUCT table
ALTER TABLE PRODUCT AUTO_INCREMENT = 1;

-- Insert data into WAREHOUSE
INSERT INTO WAREHOUSE (zone, location, capacity)
VALUES
('North', 'Dhaka', 5000),
('South', 'Chittagong', 7000),
('East', 'Sylhet', 3000),
('West', 'Khulna', 4000);

-- Insert data into PRODUCT
INSERT INTO PRODUCT (productName, type, variety, seasonality)
VALUES
('Rice', 'Grain', 'Basmati', 'Kharif'),
('Wheat', 'Grain', 'Durum', 'Rabi'),
('Potato', 'Vegetable', 'Desiree', 'Kharif'),
('Tomato', 'Vegetable', 'Roma', 'Zaid');

-- Insert data into PRODUCTION_RECORD
INSERT INTO PRODUCTION_RECORD (productID, year, yield, acreage, costOfProduction, region)
VALUES
(1, 2023, 12000, 500, 200000, 'North'),
(2, 2023, 8000, 400, 150000, 'South'),
(3, 2023, 6000, 300, 100000, 'East'),
(4, 2023, 5000, 200, 90000, 'West');

-- Insert data into PRICE_HISTORY
INSERT INTO PRICE_HISTORY (productID, date, perUnitPriceValue)
VALUES
(1, '2023-11-01', 25.5),
(2, '2023-11-01', 30.2),
(3, '2023-11-01', 15.3),
(4, '2023-11-01', 10.5);

-- Insert data into SUPPLIER
INSERT INTO SUPPLIER (name, contactInfo, regionSupplied)
VALUES
('Supplier A', '0123456789', 'North'),
('Supplier B', '0987654321', 'South'),
('Supplier C', '0112233445', 'East'),
('Supplier D', '0223344556', 'West');

-- Insert data into STORAGE_SUPPLY
INSERT INTO STORAGE_SUPPLY (warehouseID, productID, quantity, unitPrice, storageSupplyDate, transportDetails)
VALUES
(1, 1, 1000, 20, '2023-11-01', 'Truck 1'),
(2, 2, 1500, 25, '2023-11-01', 'Truck 2'),
(3, 3, 800, 12, '2023-11-01', 'Truck 3'),
(4, 4, 500, 8, '2023-11-01', 'Truck 4');

-- Insert data into RETAILER
INSERT INTO RETAILER (name, contactInfo, registeredDate, region)
VALUES
('Retailer A', '0123000001', '2023-01-01', 'North'),
('Retailer B', '0123000002', '2023-01-01', 'South'),
('Retailer C', '0123000003', '2023-01-01', 'East'),
('Retailer D', '0123000004', '2023-01-01', 'West');

-- Insert data into RETAIL_SUPPLY
INSERT INTO RETAIL_SUPPLY (retailerID, productID, retailSupplyDate, quantity, unitPrice, totalCost)
VALUES
(1, 1, '2023-11-01', 500, 30, 15000),
(2, 2, '2023-11-01', 600, 35, 21000),
(3, 3, '2023-11-01', 300, 20, 6000),
(4, 4, '2023-11-01', 200, 15, 3000);

-- Insert data into INVOICE
INSERT INTO INVOICE (date, quantity, unitPrice, subTotal)
VALUES
('2023-11-01', 500, 30, 15000),
('2023-11-01', 600, 35, 21000),
('2023-11-01', 300, 20, 6000),
('2023-11-01', 200, 15, 3000);

-- Insert data into DEMAND_ANALYSIS
INSERT INTO DEMAND_ANALYSIS (productID, consumptionPattern, priceElasticity)
VALUES
(1, 'High demand during festivals', 1.2),
(2, 'Consistent demand throughout the year', 0.8),
(3, 'Seasonal demand during winter', 1.5),
(4, 'Moderate demand throughout the year', 1.1);

-- Insert data into ANALYTICS_DATA
INSERT INTO ANALYTICS_DATA (year, productID, quantitySupplied, quantityDemanded, region)
VALUES
(2023, 1, 12000, 11000, 'North'),
(2023, 2, 8000, 8500, 'South'),
(2023, 3, 6000, 6500, 'East'),
(2023, 4, 5000, 4800, 'West');

-- Insert data into SUPPLY_FORECAST_DATA
INSERT INTO SUPPLY_FORECAST_DATA (year, productID, quantitySupplied)
VALUES
(2023, 1, 12000),
(2023, 2, 8000),
(2023, 3, 6000),
(2023, 4, 5000);

-- Insert sample user data
INSERT INTO USERS (username, email, password, role)
VALUES
('admin', 'admin@agro.com', 'admin', 'admin'),
('supplier1', 'supplier1@agro.com', 'pass123', 'supplier'),
('consumer1', 'consumer1@agro.com', 'pass123', 'consumer');

-- Insert data into CATEGORIES table
INSERT INTO CATEGORIES (name, description)
VALUES
('Grains', 'Cereal crops like rice and wheat'),
('Vegetables', 'Fresh vegetables including leafy greens'),
('Fruits', 'Seasonal and tropical fruits');

-- Insert data into PRODUCTS table
INSERT INTO PRODUCTS (name, description, price, stock, category_id, supplier_id)
VALUES
('Basmati Rice', 'High-quality long-grain rice', 40.00, 1000, 1, 2),
('Roma Tomato', 'Fresh Roma variety tomatoes', 20.00, 500, 2, 2);

-- Insert data into CART table
INSERT INTO CART (user_id, product_id, quantity)
VALUES
(3, 1, 2),
(3, 2, 5);

-- Insert data into ORDERS table
INSERT INTO ORDERS (user_id, order_status, total_amount)
VALUES
(3, 'pending', 140.00);

-- Insert data into ORDER_DETAILS table
INSERT INTO ORDER_DETAILS (order_id, product_id, quantity, price)
VALUES
(1, 1, 2, 80.00),
(1, 2, 5, 60.00);

-- Insert data into WAREHOUSE_INVENTORY table
INSERT INTO WAREHOUSE_INVENTORY (warehouse_id, product_id, stock)
VALUES
(1, 1, 500),
(1, 2, 300);

-- Insert data into REVIEWS table
INSERT INTO REVIEWS (product_id, user_id, rating, review)
VALUES
(1, 3, 5, 'Excellent quality rice!'),
(2, 3, 4, 'Fresh and good taste.');

-- Insert data into PAYMENTS table
INSERT INTO PAYMENTS (order_id, payment_method, payment_status, amount)
VALUES
(1, 'Credit Card', 'success', 140.00);
