<?php
include 'db.php';

$id = $_POST['id'];

$sql = "DELETE FROM chambres WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "Chambre supprimée avec succès!";
} else {
    echo "Erreur: " . $conn->error;
}

$conn->close();
?>
