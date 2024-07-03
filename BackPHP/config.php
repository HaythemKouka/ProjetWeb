<?php
$servername = "localhost";   // Serveur MySQL (par défaut : localhost)
$username = "root";          // Nom d'utilisateur MySQL (par défaut : root)
$password = "";              // Mot de passe MySQL (par défaut : vide)
$database = "hotel_reservation"; // Nom de la base de données
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données";
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
