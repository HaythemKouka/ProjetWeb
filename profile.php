<?php
// Vérifier si l'e-mail est passé en paramètre GET
if (!isset($_GET['email'])) {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Paramètre email manquant']);
    exit;
}

$email = $_GET['email'];

// Configuration de la connexion à la base de données
$host = 'localhost';
$db = 'hotel_reservation';
$user = 'root';
$pass = '';

// Connexion à la base de données
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erreur de connexion à la base de données']);
    exit;
}

// Préparation de la requête SQL pour récupérer les données de l'utilisateur par e-mail
$sql = "SELECT id, username, email, role, usersurname, datenaiss, lieu_naissance, file_content,photo FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

// Vérifier s'il y a des résultats
if ($stmt->num_rows > 0) {
    // Liaison des résultats de la requête aux variables PHP
    $stmt->bind_result($id, $username, $email, $role, $usersurname, $datenaiss, $lieu_naissance, $file_content,$photo);
    $stmt->fetch();

    // Création d'un tableau associatif avec les données de l'utilisateur
    $userData = [
        'id' => $id,
        'username' => $username,
        'email' => $email,
        'role' => $role,
        'usersurname' => $usersurname,
        'datenaiss' => $datenaiss,
        'lieu_naissance' => $lieu_naissance,
        'photo'=> base64_encode($photo),
        'file_content' => base64_encode($file_content), // Encodage du contenu du fichier en base64
    ];

    // Conversion des données en format JSON
    $jsonData = json_encode($userData);

    // Définition de l'en-tête Content-Disposition pour indiquer un téléchargement
    header('Content-Disposition: attachment; filename="userData.json"');
    header('Content-Type: application/json');
    header('Content-Length: ' . strlen($jsonData));

    // Affichage des données JSON
    echo $jsonData;
} else {
    // Aucun utilisateur trouvé avec cet e-mail
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Aucun utilisateur trouvé avec cet e-mail']);
}

// Fermeture des statements et de la connexion à la base de données
$stmt->close();
$conn->close();
?>
