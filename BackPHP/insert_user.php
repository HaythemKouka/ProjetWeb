<?php
require 'test_config.php';

header('Content-Type: application/json');

// Désactiver l'affichage des erreurs PHP
ini_set('display_errors', 0);
error_reporting(0);

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Récupérer les données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $naiss = $_POST['naiss'];
        $lieu = $_POST['lieu'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        if ($_POST['password'] !== $_POST['confirm-password']) {
            echo json_encode(["message" => "0", "error" => "Les mots de passe ne correspondent pas."]);
            exit();
        }

        // Vérifier si un fichier a été téléchargé
        if (isset($_FILES['pdfFile']) && $_FILES['pdfFile']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['pdfFile']['tmp_name'];
            $fileName = $_FILES['pdfFile']['name'];
            $fileSize = $_FILES['pdfFile']['size'];
            $fileType = $_FILES['pdfFile']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Vérifier l'extension du fichier
            if ($fileExtension === 'pdf') {
                // Lire le contenu du fichier
                $pdfContent = file_get_contents($fileTmpPath);

                // Préparer et exécuter la requête d'insertion avec MySQLi
                $stmt = $mysqli->prepare("INSERT INTO users (username, usersurname, datenaiss, lieu_naissance, email, password,  role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param(  $nom, $prenom, $naiss, $lieu, $email, $password,  'client');

                if ($stmt->execute()) {
                    echo json_encode(["message" => "1"]);
                    exit();
                } else {
                    echo json_encode(["message" => "0", "error" => "Échec de l'insertion en base de données."]);
                    exit();
                }
            } else {
                echo json_encode(["message" => "0", "error" => "Seuls les fichiers PDF sont autorisés."]);
                exit();
            }
        } else {
            echo json_encode(["message" => "0", "error" => "Erreur lors du téléchargement du fichier."]);
            exit();
        }
    }
} catch (Exception $e) {
    echo json_encode(["message" => "0", "error" => "Erreur : " . $e->getMessage()]);
    exit();
}
?>
