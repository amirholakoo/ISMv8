<?php
include 'connect_db.php';

// Fetch Free Trucks for Dropdown
$trucksQuery = "SELECT TruckID, LicenseNumber FROM Trucks WHERE Status = 'Free'";
$trucksResult = $conn->query($trucksQuery);

// Fetch Suppliers for Dropdown
$suppliersQuery = "SELECT SupplierID, SupplierName FROM Suppliers";
$suppliersResult = $conn->query($suppliersQuery);

// Create Shipment
if (isset($_POST['create_shipment'])) {
    $truckID = $_POST['truck_id'];
    $supplierID = $_POST['supplier_id'];
    $materialType = $_POST['material_type'];
    $materialName = $_POST['material_name'];
    $shipmentType = $_POST['shipment_type'];
    $entryTime = date("Y-m-d H:i:s");
    $location = 'Entrance';

    // Transaction to ensure atomicity
    $conn->begin_transaction();
    try {
        // Insert into Shipments
        $insertShipment = $conn->prepare("INSERT INTO Shipments (Status, Location, TruckID, EntryTime, SupplierName, MaterialType, MaterialName) VALUES (?, ?, ?, ?, (SELECT SupplierName FROM Suppliers WHERE SupplierID = ?), ?, ?)");
        $insertShipment->bind_param("ssisss", $shipmentType, $location, $truckID, $entryTime, $supplierID, $materialType, $materialName);
        $insertShipment->execute();
        $insertShipment->close();

        // Update Truck Status
        $updateTruck = $conn->prepare("UPDATE Trucks SET Status = 'Busy' WHERE TruckID = ?");
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

// HTML Form for Creating Shipment
echo "<form method='post'>";
echo "<h2>Create Shipment</h2>";

echo "Truck (License Number): <select name='truck_id' id='truck_id'>";
while ($row = $trucksResult->fetch_assoc()) {
    echo "<option value='" . $row['TruckID'] . "'>" . $row['LicenseNumber'] . "</option>";
}
echo "</select> <br>";

echo "Supplier: <select name='supplier_id' id='supplier_id' onchange='loadMaterialTypes()'>";
echo "<option value=''>Select Supplier</option>";
while ($row = $suppliersResult->fetch_assoc()) {
    echo "<option value='" . $row['SupplierID'] . "'>" . $row['SupplierName'] . "</option>";
}
echo "</select> <br>";

echo "Material Type: <select name='material_type' id='material_type' onchange='loadMaterialNames()'>";
echo "<option value=''>Select Material Type</option>";
// Options will be loaded dynamically using JavaScript
echo "</select> <br>";

echo "Material Name: <select name='material_name' id='material_name'>";
echo "<option value=''>Select Material Name</option>";
// Options will be loaded dynamically using JavaScript
echo "</select> <br>";

echo "Shipment Type: <select name='shipment_type'>
    <option value='Incoming'>Incoming</option>
    <option value='Outgoing'>Outgoing</option>
</select> <br>";

echo "<input type='submit' name='create_shipment' value='Create Shipment'>";
echo "</form>";
echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>";
echo "<script type='text/javascript'>
    function loadMaterialTypes() {
        var supplierId = $('#supplier_id').val();
        $.ajax({
            url: 'get_material_types.php',
            type: 'POST',
            data: {supplier_id: supplierId},
            success: function(response) {
                $('#material_type').html(response);
            }
        });
    }

    function loadMaterialNames() {
        var materialType = $('#material_type').val();
        $.ajax({
            url: 'get_material_names.php',
            type: 'POST',
            data: {material_type: materialType},
            success: function(response) {
                $('#material_name').html(response);
            }
        });
    }
</script>";
echo "</body></html>";
$conn->close();
?>
