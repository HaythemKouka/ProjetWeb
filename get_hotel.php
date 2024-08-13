<?php
include 'db.php';

$hotelId = $_GET['hotelId'];

$sql = "SELECT * FROM hotels WHERE id='$hotelId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo "Hôtel non trouvé";
}

$conn->close();
?>
