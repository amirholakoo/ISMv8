<?php
include 'connect_db.php';

if (isset($_POST['supplier_id'])) {
    $supplierID = intval($_POST['supplier_id']);

    $query = "SELECT DISTINCT MaterialType FROM RawMaterials WHERE SupplierID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $supplierID);
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
