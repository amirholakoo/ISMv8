

### 1\. Rolls Table

Purpose: Tracks details of each paper roll.

Columns:

-   ReelNumber
-   GSM
-   Grade
-   Width
-   Length
-   Breaks
-   Comments
-   CurrentLocation
-   Status
-   QRCode

### 2\. Trucks Table

Purpose: Manages information about trucks entering and exiting the premises.

Columns:

-   TruckNumber
-   DriverName
-   TruckStatus
-   TareWeight
-   GrossWeight
-   NetWeight

### 3\. Customers Table

Purpose: Stores details about customers.

Columns:

-   CustomerID
-   CustomerName
-   ContactName
-   ContactEmail
-   ContactPhone
-   Address

### 4\. Suppliers Table

Purpose: Keeps track of suppliers for raw materials.

Columns:

-   SupplierID
-   SupplierName
-   ContactName
-   ContactEmail
-   ContactPhone
-   Address

### 5\. Raw Materials Table

Purpose: Records details about raw materials received.

Columns:

-   MaterialID
-   MaterialName
-   MaterialType
-   SupplierID
-   NetWeight
-   PricePerUnit
-   PurchaseDate
-   TruckNumber
-   Status
-   CurrentLocation
-   PurchaseID

### 6\. Sales Orders Table

Purpose: Manages sales orders.

Columns:

-   OrderID
-   CustomerID
-   TruckNumber
-   Status
-   OrderDate
-   NetWeight

### 7\. OrderDetails Table

Purpose: Links rolls to specific sales orders.

Columns:

-   OrderDetailID
-   OrderID
-   ReelNumber

### 8\. Purchases Table

Purpose: Tracks purchases of supplies.

Columns:

-   PurchaseID
-   SupplierID
-   TruckNumber
-   NetWeight
-   PurchaseDate
-   Status
-   TotalCost
-
-
📦 ISMv808 Factory Management System
====================================

ISMv808 is an integrated factory management system designed for a paper packaging company. It streamlines operations by managing shipments, sales, purchases, and inventory, all running on a local LAMP server.

🚀 Features
-----------

-   Truck Management: Keep track of your fleet with real-time status updates.
-   Shipment Handling: Manage incoming and outgoing shipments, including QR code scanning for efficient tracking.
-   Sales and Purchases Processing: Easy-to-use interfaces for managing sales and purchases.
-   Inventory Management: Track products with QR codes, monitor stock levels, and handle materials efficiently.
-   Dashboard: Quick access to key metrics and live data reports.

🗂️ Database Structure
----------------------

The ISMv808 system uses a MySQL database to store and manage various operational data. Key tables include:

-   `Trucks`: Manage truck details, including status and location.
-   `Suppliers` and `Customers`: Store information about business partners.
-   `RawMaterials` and `Products`: Keep track of inventory items, including QR code data for products.
-   `Sales` and `Purchases`: Record transaction details, linked to shipments and inventory items.
-   `Shipments`: Track shipment details, linked to trucks, sales, and purchases.

Each table is carefully designed to ensure data integrity and optimal performance.

🌐 Web Interface
----------------

### Create Shipment Page

-   Functionality: Manage outgoing and incoming shipments.
-   Database Updates: Inserts into `Shipments` and updates `Trucks` and `Inventory` tables.
-   User Interaction: Dropdowns for truck and material selection, input fields for shipment details.

### Purchase Order Page

-   Functionality: Process purchase orders for incoming shipments.
-   Database Updates: Inserts into `Purchases`, updates `Shipments`, `Trucks`, and `RawMaterials`.
-   User Interaction: Auto-filled shipment details, input fields for purchase specifics.

### Sales Invoice Page

-   Functionality: Create sales invoices for outgoing shipments.
-   Database Updates: Inserts into `Sales`, updates `Shipments`, `Trucks`, and `Products`.
-   User Interaction: Dropdown to select customer, auto-filled shipment details, fields for invoice generation.

💻 Local Server Setup
---------------------

This system is designed to run on a local LAMP server:

-   Linux: Operating system hosting the server.
-   Apache: Web server software.
-   MySQL: Database management.
-   PHP: Server-side scripting.

🚧 Installation
---------------

1.  Clone the repository to your local server.
2.  Import the `ismv808.sql` file to set up the initial database structure.
3.  Configure your server settings as required.

🤝 Contributing
---------------

Contributions to ISMv808 are welcome! Feel free to fork the repository and submit pull requests.

📖 NOTES:
----------

T# ISMv8

ini_set('display_errors', 1);

error_reporting(E_ALL);

There are few places to set default value to NULL in DB

sudo apt install fonts-noto-color-emoji

wget -qO- https://raw.githubusercontent.com/Botspot/pi-apps/master/install | bash
