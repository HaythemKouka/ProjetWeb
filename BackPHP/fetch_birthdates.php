<?php
$conn = new mysqli("localhost", "root", "", "hotel_reservation");
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

$sql = "SELECT datenaiss FROM users";
$result = $conn->query($sql);

$birthdates = [];
while($row = $result->fetch_assoc()) {
    $birthdates[] = $row['datenaiss'];
}

$conn->close();

echo json_encode($birthdates);
?>
