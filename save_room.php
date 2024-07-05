<?php
include 'db.php';

$id = $_POST['roomId'];
$nom = $_POST['nom'];
$description = $_POST['description'];
$remise = isset($_POST['remise']) ? 1 : 0;
$pourcentage_remise = $_POST['pourcentage_remise'];
$cout = $_POST['cout'];
$hotel_id = $_POST['hotel_id'];

$image = NULL;
if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != "") {
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
}

if ($id) {
    // Update existing room
    $sql = "UPDATE chambres SET nom='$nom', description='$description', remise='$remise', pourcentage_remise='$pourcentage_remise', cout='$cout', hotel_id='$hotel_id'";
    if ($image) {
        $sql .= ", image='$image'";
    }
    $sql .= " WHERE id='$id'";
} else {
    // Add new room
    $sql = "INSERT INTO chambres (nom, description, image, remise, pourcentage_remise, cout, hotel_id)
            VALUES ('$nom', '$description', '$image', '$remise', '$pourcentage_remise', '$cout', '$hotel_id')";
}

if ($conn->query($sql) === TRUE) {
    echo "Opération réussie!";
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
