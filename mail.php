<?php
// PHPMailer-Klassen laden
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer-Dateien einbinden
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

// Formular-Daten erfassen
$first_name = htmlspecialchars($_POST['first_name']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars($_POST['Betreff']);
$message = htmlspecialchars($_POST['message']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Ungültige E-Mail-Adresse!";
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'abassley.com';
    $mail->Password = '#';  
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom($email, $first_name);
    $mail->addAddress('abassley.com');

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = nl2br($message); 

    // E-Mail senden
    $mail->send();

    // Bestätigungsmail an den Absender senden
    $confirmationMail = new PHPMailer(true);
    $confirmationMail->isSMTP();
    $confirmationMail->Host = 'smtp.gmail.com';
    $confirmationMail->SMTPAuth = true;
    $confirmationMail->Username = 'abassley.com';
    $confirmationMail->Password = '#'; 
    $confirmationMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $confirmationMail->Port = 587;

    // Absender und Empfänger konfigurieren
    $confirmationMail->setFrom('no-reply@example.com', 'Ihr Umzug');
    $confirmationMail->addAddress($email);

    // Bestätigungsmail-Inhalt konfigurieren
    $confirmationMail->isHTML(true);
    $confirmationMail->Subject = 'Bestaetigung Ihrer Nachricht';
    $confirmationMail->Body = 'Sehr geehrte*r ' . $first_name . ',<br><br>'
        . 'Vielen Dank für Ihre Nachricht! Wir haben Ihre Anfrage erhalten und werden uns so schnell wie möglich bei Ihnen melden.<br><br>'
        . 'Mit freundlichem Gruß<br>Ihr Umzug';

    // Bestätigungsmail senden
    $confirmationMail->send();

    // Erfolgsnachricht anzeigen
    echo "<script type='text/javascript'>alert('Vielen Dank für Ihre Nachricht! Ich werde mich so schnell wie möglich bei Ihnen melden.'); window.location.href = 'index.html';</script>";
} catch (Exception $e) {
    echo "Nachricht konnte nicht gesendet werden. Fehler: {$mail->ErrorInfo}";
}
?>
