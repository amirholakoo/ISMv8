<?php
include 'connect_db.php';

if (isset($_POST['material_type']) && isset($_POST['supplier_id'])) {
    $materialType = $_POST['material_type'];
    $supplierId = intval($_POST['supplier_id']);

    $query = "SELECT MaterialName FROM RawMaterials WHERE MaterialType = ? AND SupplierID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $materialType, $supplierId);
    $stmt->execute();
    $result = $stmt->get_result();
    echo "<option value=''>Select Material Name</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['MaterialName'] . "'>" . $row['MaterialName'] . "</option>";
    }
    $stmt->close();
}

$conn->close();
?>
