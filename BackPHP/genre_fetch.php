<?php
$conn = new mysqli("localhost", "root", "", "hotel_reservation");
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

$sql = "SELECT genre, COUNT(*) as count FROM users GROUP BY genre";
$result = $conn->query($sql);

$users = [];
while($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$conn->close();

echo json_encode($users);
?>
