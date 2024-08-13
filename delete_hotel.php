<?php
include 'db.php';

$hotelId = $_POST['hotelId'];

$sql = "DELETE FROM hotels WHERE id='$hotelId'";

if ($conn->query($sql) === TRUE) {
    echo "Hôtel supprimé avec succès!";
} else {
    echo "Erreur: " . $conn->error;
}

$conn->close();
?>
