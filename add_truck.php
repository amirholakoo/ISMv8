<?php
include 'connet_db.php';

$message = ''; // Message to display
$success = false; // Flag to track if operation is successful

// Function to sanitize input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $licenseNumber = test_input($_POST["licenseNumber"]);
    $driverName = test_input($_POST["driverName"]);
    $phone = test_input($_POST["phone"]);

    // Check if license number exists
    $sql = "SELECT LicenseNumber FROM Trucks WHERE LicenseNumber = '$licenseNumber'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // License number exists
        $message = "Error: Truck with this License Number already exists.";
        $success = false;
    } else {
        // Insert new truck
        $sql = "INSERT INTO Trucks (LicenseNumber, DriverName, Phone, Status, Location) VALUES ('$licenseNumber', '$driverName', '$phone', 'Free', 'Entrance')";

        if ($conn->query($sql) === TRUE) {
            $message = "New truck added successfully.";
            $success = true;
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
            $success = false;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Truck</title>
    <style>
        .error {color: #FF0000;}
        .success {color: #00FF00;}
    </style>
</head>
<body>
    <h2>Add Truck</h2>
    <p><span class="<?php echo $success ? 'success' : 'error'; ?>"><?php echo $message; ?></span></p>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        License Number: 
        <input type="text" name="licenseNumber" pattern="[0-9]{2}-[ا-ی]{1}-[0-9]{3}-IR-[0-9]{2}" required>
        <br><br>
        Driver's Name: <input type="text" name="driverName" required>
        <br><br>
        Phone: <input type="text" name="phone" required>
        <br><br>
        <input type="submit" name="submit" value="Add Truck">
    </form>
</body>
</html>
