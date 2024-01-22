<?php
include 'connect_db.php';

if (isset($_POST['material_type'])) {
    $materialType = $conn->real_escape_string($_POST['material_type']);

    $query = "SELECT MaterialID, MaterialName FROM RawMaterials WHERE MaterialType = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $materialType);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<option value=''>Select Material Name</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['MaterialID'] . "'>" . $row['MaterialName'] . "</option>";
    }
    $stmt->close();
}

$conn->close();
?>
