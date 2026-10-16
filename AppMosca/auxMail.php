<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../phpmailer/src/Exception.php';
require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/src/SMTP.php';

class auxMail{

    public function enviarCorreo($mailDestinatario, $nombreDestinatario, $asunto, $mensaje){
        try {
            $mail = new PHPMailer();
            //Configuración del servidor
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;         //Habilitar los mensajes de depuración
            $mail->isSMTP();                               //Enviar usando SMTP
            $mail->Host       = 'smtp.gmail.com';            //Configurar el servidor SMTP
            $mail->SMTPAuth   = true;                        //Habilitar autenticación SMTP
            $mail->Username   = 'pruebasappdwes@gmail.com';  //Nombre de usuario SMTP
            $mail->Password   = getenv('SMTP_PASSWORD');     //Contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Habilitar el cifrado TLS
            $mail->Port       = 465;                         //Puerto TCP al que conectarse; use 587 si config...

            //Emisor
            $mail->setFrom(getenv('EMAIL'), getenv('NOMBREDESTINATARIO'));

            //Destinatarios
            $mail->addAddress($mailDestinatario, $nombreDestinatario); //Añadir un destinatario, el...

            //Destinatarios opcionales
            //$mail->addReplyTo('info@example.com', 'Information'); //Responder a
            //$mail->addCC('cc@example.com');                       //Copia pública
            //$mail->addBCC('bcc@example.com');                      //Copia oculta

            //Archivos adjuntos
            //$mail->addAttachment('files/comunicado.pdf', 'comunicado'); //Agregar archivos adjuntos, no...

            //Nombre opcional

        //Nombre opcional
            $mail->isHTML(true);                //Establecer el formato de correo electrónico en HTML
            $mail->Subject = $asunto;
            $mail->Body    = $mensaje;

            $mail->send();
            echo 'El mensaje ha sido enviado';
        } catch (Exception $e) {
            echo "No se pudo enviar el mensaje. Error de correo: {$mail->ErrorInfo}";
        }
    }

}
