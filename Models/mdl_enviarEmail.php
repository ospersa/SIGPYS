<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    Class EnviarCorreo {

        public static function enviarCorreos($solicitante, $asesor, $ccemail, $asunto, $cuerpo){
            require '../php_libraries/phpMailer/Exception.php';
            require '../php_libraries/phpMailer/PHPMailer.php';
            require '../php_libraries/phpMailer/SMTP.php';
            $arrayccEmail = explode(';',$ccemail);
    
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = 2;                      
                $mail->isSMTP();                                            
                $mail->Host       = 'smtp.gmail.com';                    
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = 'produccionysoporte@gmail.com';       
                $mail->Password   = '12345Feapys';                            
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          
                $mail->Port       = 587;                                    

                //Recipients
                $mail->setFrom('produccionysoporte@gmail.com', 'Conecta-Te');
                if( $solicitante != null){
                    $mail->addAddress($solicitante, ''); 
                    foreach($arrayccEmail as $item) {
                        $mail->addCC($item);
                    }
                } else {
                    foreach($arrayccEmail as $item) {
                        $mail->addAddress($item);
                    }
                }
                $mail->addCC('apoyoconectate@uniandes.edu.co');
                if($asesor != null){
                    $mail->addCC($asesor);
                }               
                // Content
                $mail->isHTML(true);                                  
                $mail->Subject = $asunto;
                $mail->Body    = $cuerpo;
                $mail->CharSet = 'UTF-8';

                $mail->send();
                return true;
            } catch (Exception $e) {
                echo '<script>alert("No se envio correctamente el correo. Error:'. $mail->ErrorInfo.'")</script>'; 
                echo '<meta http-equiv="Refresh" content="0;url=../Views/terminacionServiciosProductos.php">';
                return false;
            }
        }


    } 
?>