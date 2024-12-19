# AgroDemandSupply_Group6_DBMS

## Overview
This repository contains the implementation of a database management system for managing agricultural demand and supply. The system includes different roles such as admin, consumer, supplier, warehouse, and retailer, each with specific credentials and functionalities.

## Setup Instructions

### Prerequisites
1. Install a MySQL server on your system.
2. Install PHP

OR

3. Install XAMP

### Steps to Set Up the Database

1. Clone the repository:
    ```bash
    git clone https://github.com/Mdrasel1230/AgroDemandSupply_Group6_DBMS.git
    cd AgroDemandSupply_Group6_DBMS
    php -S localhost:1337
    ```

2. Open your MySQL client and log in to your MySQL server.

3. Create the database and tables:
    - Run the `AgricultureDB.sql` script to create the necessary database and tables.
      ```sql
      source path/to/AgricultureDB.sql;
      ```

4. Insert sample data:
    - Use the `insert_data.sql` script to populate the database with initial data.
      ```sql
      source path/to/insert_data.sql;
      ```
5. Modify the `db_connect.php` file to match your MySQL configuration.
   
6. Verify that the tables and data are set up correctly by executing:
    ```sql
    SHOW TABLES;
    SELECT * FROM <table_name>;
    ```

## User Credentials
The system includes the following roles and their respective login credentials:

| **Username**  | **Password** | **Role**      |
|---------------|--------------|---------------|
| admin         | admin        | Admin         |
| consumer      | consumer     | Consumer      |
| supplier      | supplier     | Supplier      |
| warehouse     | warehouse    | Warehouse     |
| retailer      | retailer     | Retailer      |

## Usage
1. Access the system using the appropriate credentials for your role.
2. Each role has specific permissions and functionalities:
   - **Admin**: Full control over the database and system operations.
   - **Consumer**: Access to consumer-specific features.
   - **Supplier**: Access to supplier-specific features.
   - **Warehouse**: Access to warehouse management features.
   - **Retailer**: Access to retail-related features.

## Notes
- Ensure that you have the correct permissions to execute the SQL scripts on your MySQL server.
- Modify the SQL scripts if required to adapt them to your environment.
