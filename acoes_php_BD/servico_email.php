<?php
// acoes_php_BD/servico_email.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ajuste os caminhos conforme a tua pasta lib
require_once __DIR__ . '/../lib/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../lib/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/src/SMTP.php';

function enviarLinkAcesso($emailDestino, $nome, $token) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->CharSet     = 'UTF-8'; 
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mucapera8@gmail.com'; 
        $mail->Password   = 'xgwd ftzq jctu ajnz'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('mucapera8@gmail.com', 'SIGATCIUP - Sistema');
        $mail->addAddress($emailDestino, $nome);
        							
	$protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
	$dominio = $_SERVER['HTTP_HOST']; // Captura sigeatciup.com ou localhost automaticamente

	// Monta o link dinamicamente
	$link = $protocolo . "://" . $dominio . "/senha/definir_senha.php?token=" . $token;
        $mail->isHTML(true);
        $mail->Subject = 'Define a tua senha de acesso';
        $mail->Body    = "Olá $nome, <br><br>Clica no link para definires a tua senha: <a href='$link'>$link</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
