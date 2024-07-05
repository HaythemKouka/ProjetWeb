<?php
header('Content-Type: application/json');

// Configuration de la base de données
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

// Récupérer les données POST
$username = $_POST['username'];
$password = $_POST['password'];
 // Préparer la requête SQL
$sql = "SELECT id, passwd, username, usersurname, email, datenaiss, lieu_naissance, file_content,photo FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($uid, $hashed_password, $db_username, $db_usersurname, $db_email, $db_datenaiss, $db_lieu_naissance, $db_file_content,$img);
$stmt->fetch();

if ($hashed_password && password_verify($password, $hashed_password)) {
    // Retourner une réponse JSON avec les données de l'utilisateur
    echo json_encode([
        'success' => true, 
        'message' => 'Connexion réussie',
        'username' => $db_username,
        'usersurname' => $db_usersurname,
        'email' => $db_email,
        'uid' => $uid,
        'photo' => $img,
        'datenaiss' => $db_datenaiss,
        'lieu_naissance' => $db_lieu_naissance,
        'file_content' => base64_encode($db_file_content) // Encodage base64 pour les données de fichier
    ]);
} else {
    // Échec de connexion
    echo json_encode(['success' => false, 'message' => 'Échec de la connexion']);
}

$stmt->close();
$conn->close();
?>
