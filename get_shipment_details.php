<?php
include 'connect_db.php';

if (isset($_POST['shipment_id'])) {
    $shipmentId = intval($_POST['shipment_id']);
    $query = "SELECT * FROM Shipments WHERE ShipmentID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $shipmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $shipmentDetails = $result->fetch_assoc();
    echo json_encode($shipmentDetails); // Return the details as a JSON object
    $stmt->close();
}

$conn->close();
?>
