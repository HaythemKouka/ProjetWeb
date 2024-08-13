<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer's autoloader

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_reservation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$newPassword = generateRandomPassword();
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

// Update the user's password in the database
$sql = "UPDATE users SET password='$hashedPassword' WHERE email='$email'";
if ($conn->query($sql) === TRUE) {
    // Send the new password to the user's email
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'haythemkk56@gmail.com'; // SMTP username
        $mail->Password = 'kpxepubgmvwmvjjy'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('haythemkk56@gmail.com', 'Mailer');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation du mot de passe';
        $mail->Body = "Votre nouveau mot de passe est : $newPassword";

        $mail->send();
        $message = 'Un nouveau mot de passe a été envoyé à votre adresse e-mail.';
        $messageClass = 'text-success';
    } catch (Exception $e) {
        $message = 'Le message n\'a pas pu être envoyé. Erreur: ' . $mail->ErrorInfo;
        $messageClass = 'text-danger';
    }
} else {
    $message = 'Erreur lors de la mise à jour du mot de passe: ' . $conn->error;
    $messageClass = 'text-danger';
}

$conn->close();

function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .message {
            margin-top: 20px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h2>Réinitialisation du mot de passe</h2>
        <p class="message <?php echo $messageClass; ?>"><?php echo $message; ?></p>
        <div class="mt-4">
            <a href="login.html" class="btn btn-primary">Retour à la connexion</a>
        </div>
    </div>
</body>
</html>
