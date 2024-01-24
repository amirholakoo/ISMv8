// Create Shipment
if (isset($_POST['create_shipment'])) {
    $truckID = $_POST['truck_id']; // Make sure this is the TruckID
    $supplierID = $_POST['supplier_id'];
    $materialType = $_POST['material_type'];
    $materialName = $_POST['material_name'];
    $shipmentType = $_POST['shipment_type'];
    $entryTime = date("Y-m-d H:i:s");
    $location = 'Entrance';

    // Fetch LicenseNumber for the selected truck
    $truckQuery = $conn->prepare("SELECT LicenseNumber FROM Trucks WHERE TruckID = ?");
    $truckQuery->bind_param("i", $truckID);
    $truckQuery->execute();
    $truckResult = $truckQuery->get_result();
    $truckRow = $truckResult->fetch_assoc();
    $licenseNumber = $truckRow['LicenseNumber'] ?? '';
    $truckQuery->close();

    // Fetch SupplierName for the selected supplier
    $supplierQuery = $conn->prepare("SELECT SupplierName FROM Suppliers WHERE SupplierID = ?");
    $supplierQuery->bind_param("i", $supplierID);
    $supplierQuery->execute();
    $supplierResult = $supplierQuery->get_result();
    $supplierRow = $supplierResult->fetch_assoc();
    $supplierName = $supplierRow['SupplierName'] ?? '';
    $supplierQuery->close();

    // Transaction to ensure atomicity
    $conn->begin_transaction();
    try {
        $insertShipment = $conn->prepare("INSERT INTO Shipments (Status, Location, TruckID, LicenseNumber, EntryTime, SupplierName, MaterialType, MaterialName) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insertShipment->bind_param("ssisssss", $shipmentType, $location, $truckID, $licenseNumber, $entryTime, $supplierName, $materialType, $materialName);
        // ... [rest of your code]
    } catch (Exception $e) {
        // ... [error handling]
    }
}
