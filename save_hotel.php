<?php
include 'db.php';

$hotelId = $_POST['hotelId'];
$name = $_POST['name'];
$description = $_POST['description'];
$price_per_night = $_POST['price_per_night'];

$image = NULL;
if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != "") {
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
}

if ($hotelId) {
    // Update existing hotel
    $sql = "UPDATE hotels SET name='$name', description='$description', price_per_night='$price_per_night'";
    if ($image) {
        $sql .= ", image='$image'";
    }
    $sql .= " WHERE id='$hotelId'";
} else {
    // Add new hotel
    $sql = "INSERT INTO hotels (name, description, price_per_night, image)
            VALUES ('$name', '$description', '$price_per_night', '$image')";
}

if ($conn->query($sql) === TRUE) {
    echo "Opération réussie!";
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
