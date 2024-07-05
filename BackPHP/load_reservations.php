<?php
// Connexion à votre base de données MySQL
$mysqli = new mysqli('localhost', 'root', '', 'hotel_reservation');

// Vérifier la connexion
if ($mysqli->connect_errno) {
    echo "Echec lors de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

// Récupérer l'ID utilisateur depuis la requête GET
$userId = $_GET['id'];

// Préparer la requête SQL pour récupérer les réservations de l'utilisateur
$sql = "SELECT * FROM reservation WHERE user_id = $userId";

// Exécuter la requête SQL
$result = $mysqli->query($sql);

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    $reservations = array();

    // Parcourir les résultats et stocker chaque réservation dans un tableau
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }

    // Renvoyer les réservations au format JSON
    header('Content-Type: application/json');
    echo json_encode($reservations);
} else {
    // Aucune réservation trouvée pour cet utilisateur
    echo json_encode(array()); // Renvoyer un tableau vide en JSON
}

// Fermer la connexion à la base de données
$mysqli->close();
?>
