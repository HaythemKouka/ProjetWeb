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

// Récupération des données du formulaire
$user_id = $_POST['userid']; // ID d'utilisateur statique
 $date_arrivee = $_POST['check-in'];
$date_depart = $_POST['check-out'];
$nombre_invites = $_POST['guests'];
$petit_dejeuner_inclus = isset($_POST['breakfast']) ? 1 : 0;
$type_chambre = $_POST['room-type'];
$preference_lit = isset($_POST['bed-type']) ? $_POST['bed-type'] : '';
$id_chambre = $_POST['chambre-id']; // Récupérez l'ID de la chambre depuis le formulaire
$cout_total = 0; // Calculer le coût total si nécessaire
$sql_chambre = "SELECT cout, pourcentage_remise FROM chambres WHERE id = $id_chambre";
$result_chambre = $conn->query($sql_chambre);

if ($result_chambre->num_rows > 0) {
    $row_chambre = $result_chambre->fetch_assoc();
    $cout_chambre = $row_chambre['cout'];
    $cout_remise = $row_chambre['pourcentage_remise'];

    // Calculer le coût total
    $cout_total = $cout_chambre - (($cout_remise *$cout_chambre)/100) * $nombre_invites;

    // Préparation de la requête SQL d'insertion
    $sql = "INSERT INTO reservation (user_id, date_arrivee, date_depart, nombre_invites, petit_dejeuner_inclus, type_chambre, preference_lit, id_chambre, cout_total)
            VALUES ('$user_id', '$date_arrivee', '$date_depart', '$nombre_invites', '$petit_dejeuner_inclus', '$type_chambre', '$preference_lit', '$id_chambre', '$cout_total')";

    if ($conn->query($sql) === TRUE) {
      header('Location: mes_reservations.html');
        exit(); // Assurez-vous de terminer le script après la redirection
       } else {
        echo "Erreur lors de la réservation: " . $conn->error;
    }
} else {
    echo "Erreur: chambre non trouvée.";
}

$conn->close();
?>