<?php
// Connexion à la base de données (à adapter selon votre configuration)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_reservation";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT c.nom AS chambre_nom, r.nombre_reservations
FROM chambres c
LEFT JOIN (
    SELECT id_chambre, COUNT(*) AS nombre_reservations
    FROM reservation
    GROUP BY id_chambre
) r ON c.id = r.id_chambre";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Erreur de requête SQL: " . mysqli_error($conn));
}

// Récupération des données sous forme de tableau associatif
$reservations_data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $reservations_data[] = $row;
}

// Libération des résultats de la mémoire
mysqli_free_result($result);

// Envoi des données sous forme de réponse JSON
header('Content-Type: application/json');
echo json_encode($reservations_data);
?>