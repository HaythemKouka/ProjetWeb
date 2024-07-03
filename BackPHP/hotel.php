<?php
require 'config.php';

// Ajouter un nouvel hôtel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addHotel'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price_per_night = $_POST['price_per_night'];
    $imagePath = ''; // Gérer le téléchargement d'image ici

    // Gérer l'upload de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imagePath = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO hotels (name, description, price_per_night, image) VALUES (?, ?, ?, ?)');
        if ($stmt->execute([$name, $description, $price_per_night, $imagePath])) {
            echo json_encode(['message' => 'Hotel added successfully']);
        } else {
            echo json_encode(['error' => 'Failed to add hotel']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to add hotel: ' . $e->getMessage()]);
    }
}

// Liste des hôtels
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query('SELECT * FROM hotels');
    $hotels = $stmt->fetchAll();
    echo json_encode($hotels);
}
?>
