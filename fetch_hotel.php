<?php
include 'db.php'; // Connexion à la base de données

if (isset($_POST['hotelId'])) {
    $hotelId = $_POST['hotelId'];
    
    // Requête pour récupérer les détails de l'hôtel spécifique
    $sql = "SELECT * FROM hotels WHERE id = '$hotelId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $hotel = $result->fetch_assoc();
        $output = '
        <h5>Détails de l\'hôtel ' . $hotel['name'] . '</h5>
        <p><strong>Description:</strong> ' . $hotel['description'] . '</p>
        <p><strong>Prix par nuit:</strong> ' . $hotel['price_per_night'] . ' €</p>
        <img src="data:image/jpeg;base64,' . base64_encode($hotel['image']) . '" class="img-fluid" alt="Image de l\'hôtel">
    ';
    echo $output;    } else {
        echo json_encode(['error' => 'Hôtel non trouvé']);
    }
} else {
    echo json_encode(['error' => 'Paramètres manquants']);
}

$conn->close();
?>
