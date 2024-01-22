<?php
include 'connect_db.php';

// Fetch Free Trucks for Dropdown
$trucksQuery = "SELECT LicenseNumber FROM Trucks WHERE Status = 'Free'";
$trucksResult = $conn->query($trucksQuery);

// Fetch Suppliers for Dropdown
$suppliersQuery = "SELECT SupplierID, SupplierName FROM Suppliers";
$suppliersResult = $conn->query($suppliersQuery);

// Fetch Material Types and Names
$materials = [];
if (isset($_POST['supplier_id'])) {
    $supplierID = $_POST['supplier_id'];
    $materialsQuery = "SELECT MaterialID, MaterialType, MaterialName FROM RawMaterials WHERE SupplierID = $supplierID";
    $materialsResult = $conn->query($materialsQuery);
    while ($row = $materialsResult->fetch_assoc()) {
        $materials[$row['MaterialType']][] = $row;
    }
}

// Create Shipment
if (isset($_POST['create_shipment'])) {
    $licenseNumber = $_POST['license_number'];
    $shipmentType = $_POST['shipment_type'];
    $materialID = $_POST['material_id'];
    $entryTime = date("Y-m-d H:i:s");
    $location = 'Entrance';

    // Transaction to ensure atomicity
    $conn->begin_transaction();
    try {
        // Insert into Shipments
        $insertShipment = $conn->prepare("INSERT INTO Shipments (Status, Location, LicenseNumber, EntryTime, MaterialID) VALUES (?, ?, ?, ?, ?)");
        $insertShipment->bind_param("ssssi", $shipmentType, $location, $licenseNumber, $entryTime, $materialID);
        $insertShipment->execute();
        $insertShipment->close();

        // Update Truck Status
        $updateTruck = $conn->prepare("UPDATE Trucks SET Status = 'Busy' WHERE LicenseNumber = ?");
        $updateTruck->bind_param("s", $licenseNumber);
        $updateTruck->execute();
        $updateTruck->close();
        
        $conn->commit();
        echo "<p style='color:green;'>Shipment created and truck status updated successfully!</p>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p style='color:red;'>Error creating shipment: " . $e->getMessage() . "</p>";
    }
}

// HTML Form for Creating Shipment
echo "<form method='post'>";
echo "<h2>Create Shipment</h2>";

// Dropdown for Trucks
echo "Truck (License Number): <select name='license_number'>";
while ($row = $trucksResult->fetch_assoc()) {
    echo "<option value='" . $row['LicenseNumber'] . "'>" . $row['LicenseNumber'] . "</option>";
}
echo "</select> <br>";

// Dropdown for Shipment Type
echo "Shipment Type: <select name='shipment_type'>
    <option value='Incoming'>Incoming</option>
    <option value='Outgoing'>Outgoing</option>
</select> <br>";

// Dropdown for Suppliers
echo "Supplier: <select name='supplier_id' onchange='this.form.submit()'>";
echo "<option value=''>Select Supplier</option>";
while ($row = $suppliersResult->fetch_assoc()) {
    $selected = (isset($_POST['supplier_id']) && $_POST['supplier_id'] == $row['SupplierID']) ? 'selected' : '';
    echo "<option value='" . $row['SupplierID'] . "' $selected>" . $row['SupplierName'] . "</option>";
}
echo "</select> <br>";

// Dropdown for Material Types and Names
if (!empty($materials)) {
    echo "Material Type: <select name='material_type' onchange='this.form.submit()'>";
    foreach ($materials as $type => $materialInfo) {
        $selected = (isset($_POST['material_type']) && $_POST['material_type'] == $type) ? 'selected' : '';
        echo "<option value='$type' $selected>$type</option>";
    }
    echo "</select> <br>";

    echo "Material Name: <select name='material_id'>";
    foreach ($materials[$_POST['material_type']] as $material) {
        echo "<option value='" . $material['MaterialID'] . "'>" . $material['MaterialName'] . "</option>";
    }
    echo "</select> <br>";
}

echo "<input type='submit' name='create_shipment' value='Create Shipment'>";
echo "</form>";

echo "</body></html>";
$conn->close();
?>
