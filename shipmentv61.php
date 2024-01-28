// Create Shipment
if (isset($_POST['create_shipment'])) {
    $truckID = $_POST['truck_id'];
    $supplierID = $_POST['supplier_id'];
    $materialType = $_POST['material_type'];
    $materialName = $_POST['material_name'];
    $shipmentType = $_POST['shipment_type'];
    $location = 'Entrance';
    $entryTime = date("Y-m-d H:i:s");

    // Fetch License Number from Trucks
    $truckQuery = "SELECT LicenseNumber FROM Trucks WHERE TruckID = ?";
    $truckStmt = $conn->prepare($truckQuery);
    $truckStmt->bind_param("i", $truckID);
    $truckStmt->execute();
    $truckResult = $truckStmt->get_result();
    $truckRow = $truckResult->fetch_assoc();
    $licenseNumber = $truckRow['LicenseNumber'];
    $truckStmt->close();

    // Fetch Supplier Name from Suppliers
    $supplierQuery = "SELECT SupplierName FROM Suppliers WHERE SupplierID = ?";
    $supplierStmt = $conn->prepare($supplierQuery);
    $supplierStmt->bind_param("i", $supplierID);
    $supplierStmt->execute();
    $supplierResult = $supplierStmt->get_result();
    $supplierRow = $supplierResult->fetch_assoc();
    $supplierName = $supplierRow['SupplierName'];
    $supplierStmt->close();

    // Fetch Material ID from RawMaterials
    $materialQuery = "SELECT MaterialID FROM RawMaterials WHERE MaterialType = ? AND MaterialName = ? AND SupplierID = ?";
    $materialStmt = $conn->prepare($materialQuery);
    $materialStmt->bind_param("ssi", $materialType, $materialName, $supplierID);
    $materialStmt->execute();
    $materialResult = $materialStmt->get_result();
    $materialRow = $materialResult->fetch_assoc();
    $materialID = $materialRow['MaterialID'];
    $materialStmt->close();

    // Transaction to ensure atomicity
    $conn->begin_transaction();
    try {
        // Insert into Shipments
        $insertShipmentQuery = "INSERT INTO Shipments (Status, Location, TruckID, LicenseNumber, SupplierName, MaterialType, MaterialName, MaterialID, EntryTime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertShipment = $conn->prepare($insertShipmentQuery);
        $insertShipment->bind_param("ssissssis", $shipmentType, $location, $truckID, $licenseNumber, $supplierName, $materialType, $materialName, $materialID, $entryTime);
        $insertShipment->execute();
        $insertShipment->close();

        // Update Truck Status
        $updateTruckQuery = "UPDATE Trucks SET Status = 'Busy' WHERE TruckID = ?";
        $updateTruck = $conn->prepare($updateTruckQuery);
        $updateTruck->bind_param("i", $truckID);
        $updateTruck->execute();
        $updateTruck->close();

        $conn->commit();
        echo "<p style='color:green;'>Shipment created and truck status updated successfully!</p>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p style='color:red;'>Error creating shipment: " . $e->getMessage() . "</p>";
    }
}
