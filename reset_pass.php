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
        echo 'Un nouveau mot de passe a été envoyé à votre adresse e-mail.';
    } catch (Exception $e) {
        echo "Le message n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
    }
} else {
    echo "Erreur lors de la mise à jour du mot de passe: " . $conn->error;
}

$conn->close();
?>
