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
$newPassword = $_POST['newPassword'];
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

// Update the user's password in the database
$sql = "UPDATE users SET passwd='$hashedPassword' WHERE email='$email'";
if ($conn->query($sql) === TRUE) {
    // Send the new password to the user's email
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'haythemkk56@gmail.com'; // SMTP username
        $mail->Password = 'riksfdeiojmavhws'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('haythemkk56@gmail.com', 'Mailer');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Reinitialisation du mot de passe';
        $mail->Body = "Bonjour, Votre nouveau mot de passe est du site de reservation : $newPassword";

        $mail->send();
        echo '<div class="container mt-5"><div class="alert alert-success">Un nouveau mot de passe a été envoyé à votre adresse e-mail.</div></div>';
    } catch (Exception $e) {
        echo '<div class="container mt-5"><div class="alert alert-danger">Le message n\'a pas pu être envoyé. Erreur: ' . $mail->ErrorInfo . '</div></div>';
    }
} else {
    echo '<div class="container mt-5"><div class="alert alert-danger">Erreur lors de la mise à jour du mot de passe: ' . $conn->error . '</div></div>';
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