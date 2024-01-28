<?php
include 'connect_db.php';

if (isset($_POST['supplier_id'])) {
    $supplierId = intval($_POST['supplier_id']);
    $query = "SELECT DISTINCT MaterialType FROM RawMaterials WHERE SupplierID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $supplierId);
    $stmt->execute();
    $result = $stmt->get_result();
    echo "<option value=''>Select Material Type</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['MaterialType'] . "'>" . $row['MaterialType'] . "</option>";
    }
    $stmt->close();
}

$conn->close();
?>
