<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        $contenido = "<html>";
        $contenido .="<p><strong>Hola ". $this->nombre . "</strong> Has creado tu cuenta en Uptask, solo debes confirmarla en el siguiente enlace</p>";
        $contenido .="<p>Presiona aquí <a href='". $_ENV['APP_URL'] ."/confirmar?token=". $this->token ."'>Confirmar Cuenta</a> </p>";
        $contenido .= "<p>Si tu no creaste esta cuenta, solo ignora este mensaje</p>";
        $contenido .= "</html>";

        $this->enviar('Confirmar cuenta',$contenido);
    }

    public function enviarInstrucciones(){
        $contenido = "<html>";
        $contenido .="<p><strong>Hola ". $this->nombre . "</strong> Has solicitado reestablecer tu contraseña, solo debes confirmarla en el siguiente enlace</p>";
        $contenido .="<p>Presiona aquí <a href='". $_ENV['APP_URL'] ."/reestablecer?token=". $this->token ."'>Reestablecer Contraseña</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esto, ignora este mensaje</p>";
        $contenido .= "</html>";

        $this->enviar('Reestablecer contraseña', $contenido);
    }

    protected function enviar($subject, $contenido){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail -> set('cuentas@uptask.com');
        $mail -> addAddress('cuentas@uptask.com','uptask.com');
        $mail -> Subject = $subject;
        $mail -> isHTML(TRUE);
        $mail -> CharSet = 'UTF-8';

        $mail -> Body = $contenido;

        //Enviar el email
        $mail -> send();
    }
    
}