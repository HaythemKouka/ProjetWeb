<?php
// Inclure le fichier de configuration de la base de données
$servername = "localhost";   // Serveur MySQL (par défaut : localhost)
$username = "root";          // Nom d'utilisateur MySQL (par défaut : root)
$password = "";              // Mot de passe MySQL (par défaut : vide)
$database = "hotel_reservation"; // Nom de la base de données 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $response = [
        'message' => 'Connexion réussie à la base de données.',
        'current_time' => date('Y-m-d H:i:s')
    ];
} catch (PDOException $e) {
    $response = [
        'message' => 'Échec de la connexion à la base de données: ' . $e->getMessage()
    ];
}

echo json_encode($response);
?>