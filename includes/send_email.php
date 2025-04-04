
<?php
if (!isset($_SESSION)) {
    session_start();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Function to send email
function sendEmail($email, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'danielmaishy@gmail.com'; // Replace with your Gmail
        $mail->Password   = 'hqll ozri uuuk hazi'; // Use a Google App Password, not your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Disable SSL Certificate Verification (if needed)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Email headers
        $mail->setFrom('danielmaishy@gmail.com', 'House Hunt'); // Sender email
        $mail->addAddress($email); // Recipient email
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->isHTML(true); // Set email format to HTML

        // Send email
        if ($mail->send()) {
            return "✅ Email sent successfully!";
        } else {
            return "❌ Email sending failed: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        return "❌ Email sending failed: {$mail->ErrorInfo}";
    }
}


?>