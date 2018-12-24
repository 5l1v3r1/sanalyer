<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Hata ayıklama

$mail->isSMTP();
$mail->Host = 'smtp.yandex.com';                    // SMTP sunucusu - değiştirme
$mail->SMTPAuth = true;                               // SMTP doğrulama -değiştirme
$mail->Username = 'smtp.yandex.com.tr';                 // SMTP kullanıcısı - Bu kısımı değiştir
$mail->Password = 'smtp.yandex.com.tr';                               // SMTP şifresi - Bu kısımı değiştir
$mail->SMTPSecure = 'tls';                            // TLS şifrelemesi aktif - değiştirme
$mail->Port = 587;                                    // TLS portu

$mail->setFrom('xenforoweb@yandex.com', 'Mailer');
$mail->addAddress('webmaster@xenforo.web.tr', 'Selim Ozdemir');     // Alıcıyı ekle
$mail->addReplyTo('info@example.com', 'Information');

//$mail->isHTML(true);                                  // HTML formatında göndermek istiyorsan

$mail->Subject = 'Konu başlığınız';
$mail->Body    = 'HTML mesaj gövdesi <b>in bold!</b>';
$mail->AltBody = 'HTML olmayan alıcılarda gösterilecek sadece metin mesaj gövdesi';

if(!$mail->send()) {
    echo 'Mesaj gönderilemedi.';
    echo 'Mailer Hatası: ' . $mail->ErrorInfo;
} else {
    echo 'Mesaj gönderildi';
}