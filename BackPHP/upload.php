<?php
if(isset($_POST['submit'])){
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $naiss = $_POST['naiss'];
    $lieu = $_POST['lieu'];
    $email = $_POST['email'];
     $genre = $_POST['genre']; // Récupération du genre

    if ($_POST['pass'] !== $_POST['confirm-password']) {
        header("Location: ../test.html?message=Les%20mots%20de%20passe%20ne%20correspondent%20pas&type=error");
        exit();
    }
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
        }}
    if (isset($_FILES["fileToUpload"])) {
        // Récupérer le type du fichier
        $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
        
        // Vérifier si l'image est une image réelle ou une fausse image
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo json_encode(['success' => false, 'message' => 'Le fichier n\'est pas une image.']);
            exit();
        }
    
        // Limiter la taille du fichier
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo json_encode(['success' => false, 'message' => 'Désolé, votre fichier est trop volumineux.']);
            exit();
        }
    
        // Autoriser certains formats de fichiers
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            echo json_encode(['success' => false, 'message' => 'Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.']);
            exit();
        }}
        if (isset($_FILES["fileToUpload"])) {

    
        $image = file_get_contents($_FILES["fileToUpload"]["tmp_name"]);
        $image = base64_encode($image);}
    $conn = new mysqli("localhost", "root", "", "hotel_reservation");
    if ($conn->connect_error) {
        header("Location: ../test.html?message=Échec%20de%20la%20connexion%20à%20la%20base%20de%20données&type=error");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users (username, usersurname, datenaiss, lieu_naissance, email, passwd, file_content, role, photo, genre) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        header("Location: ../test.html?message=Erreur%20de%20préparation%20de%20la%20requête&type=error");
        exit();
    }

    $role = "Client";
    $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);

    $stmt->bind_param("ssssssssss", $nom, $prenom, $naiss, $lieu, $email, $password, $pdfContent, $role, $image, $genre);

    if ($stmt->execute()) {
        header("Location: ../test.html?message=Inscription%20réussie&type=success");
    } else {
        header("Location: ../test.html?message=Échec%20de%20l'insertion%20en%20base%20de%20données&type=error");
    }

    $stmt->close();
    $conn->close();
}
?>
