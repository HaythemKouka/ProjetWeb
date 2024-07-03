<?php
// Connexion à la base de données
$host = 'localhost';
$db = 'hotel_reservation';
$user = 'root';
$pass = '';

// Connexion à la base de données
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données']);
    exit();
}

// Vérifier si la requête est une requête POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données postées depuis le formulaire
     $username = $_POST['username'];
    $email = $_POST['email'];
    $usersurname = $_POST['usersurname'];
    $datenaiss = $_POST['datenaiss'];
    $lieu_naissance = $_POST['lieu_naissance'];

    // Préparer la requête SQL pour mettre à jour les données dans la base de données
    $sql = "UPDATE users SET 
    username = '$username', 
    usersurname = '$usersurname', 
    datenaiss = '$datenaiss', 
    lieu_naissance = '$lieu_naissance' 
    WHERE email = '$email'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Les données ont été mises à jour avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour des données : ' . $conn->error]);
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>
