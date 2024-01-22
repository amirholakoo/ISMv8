<?php
include 'connect_db.php';

// Fetch Suppliers for Dropdown
$suppliersQuery = "SELECT SupplierID, SupplierName FROM Suppliers";
$suppliersResult = $conn->query($suppliersQuery);

// Fetch Material Types for Selected Supplier
$materialTypes = [];
if (isset($_POST['selected_supplier'])) {
    $selectedSupplierID = $_POST['selected_supplier'];
    $materialTypesQuery = "SELECT DISTINCT MaterialType FROM RawMaterials WHERE SupplierID = $selectedSupplierID";
    $materialTypesResult = $conn->query($materialTypesQuery);
    while ($row = $materialTypesResult->fetch_assoc()) {
        array_push($materialTypes, $row['MaterialType']);
    }
}

// Fetch Material Names for Selected Material Type
$materialNames = [];
if (isset($_POST['selected_material_type'])) {
    $selectedMaterialType = $_POST['selected_material_type'];
    $materialNamesQuery = "SELECT MaterialName FROM RawMaterials WHERE MaterialType = '$selectedMaterialType'";
    $materialNamesResult = $conn->query($materialNamesQuery);
    while ($row = $materialNamesResult->fetch_assoc()) {
        array_push($materialNames, $row['MaterialName']);
    }
}

// Handle Shipment Creation
if (isset($_POST['create_shipment'])) {
    // Retrieve form data
    $supplierID = $_POST['selected_supplier'];
    $materialType = $_POST['selected_material_type'];
    $materialName = $_POST['selected_material_name'];
    $shipmentType = $_POST['shipment_type'];

    // Insert shipment data into database
    $insertShipmentQuery = "INSERT INTO Shipments (SupplierID, MaterialType, MaterialName, Status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertShipmentQuery);
    $stmt->bind_param("isss", $supplierID, $materialType, $materialName, $shipmentType);
    if ($stmt->execute()) {
        echo "<p style='color:green;'>Shipment created successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error creating shipment: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// HTML Form for Creating Shipment
echo "<form method='post'>";
echo "<h2>Create Shipment</h2>";

// Supplier Dropdown
echo "Supplier: <select name='selected_supplier' onchange='this.form.submit()'>";
echo "<option value=''>Select Supplier</option>";
while ($row = $suppliersResult->fetch_assoc()) {
    $selected = ($row['SupplierID'] == $_POST['selected_supplier']) ? 'selected' : '';
    echo "<option value='" . $row['SupplierID'] . "' $selected>" . $row['SupplierName'] . "</option>";
}
echo "</select> <br>";

// Material Type Dropdown
if (!empty($materialTypes)) {
    echo "Material Type: <select name='selected_material_type' onchange='this.form.submit()'>";
    foreach ($materialTypes as $type) {
        $selected = ($type == $_POST['selected_material_type']) ? 'selected' : '';
        echo "<option value='$type' $selected>$type</option>";
    }
    echo "</select> <br>";
}

// Material Name Dropdown
if (!empty($materialNames)) {
    echo "Material Name: <select name='selected_material_name'>";
    foreach ($materialNames as $name) {
        echo "<option value='$name'>$name</option>";
    }
    echo "</select> <br>";
}

// Shipment Type Dropdown
echo "Shipment Type: <select name='shipment_type'>
    <option value='Incoming'>Incoming</option>
    <option value='Outgoing'>Outgoing</option>
</select> <br>";

echo "<input type='submit' name='create_shipment' value='Create Shipment'>";
echo "</form>";
echo "</body></html>";

$conn->close();
?>
