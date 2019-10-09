<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    Class EnviarCorreo {

        public static function enviarCorreoTerminar($solicitante, $asesor, $ccemail, $asunto, $cuerpo){
            require '../php_libraries/phpMailer/Exception.php';
            require '../php_libraries/phpMailer/PHPMailer.php';
            require '../php_libraries/phpMailer/SMTP.php';
            $arrayccEmail = explode(';',$ccemail);
    
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = 2;                      
                $mail->isSMTP();                                            
                $mail->Host       = 'smtp.office365.com';                    
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = 'apoyoconectate@uniandes.edu.co';       
                $mail->Password   = 'ceintic13';                            
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          
                $mail->Port       = 587;                                    

                //Recipients
                $mail->setFrom('apoyoconectate@uniandes.edu.co', 'Conecta-Te');
                $mail->addAddress($solicitante, ''); 
                $mail->addCC('apoyoconectate@uniandes.edu.co');
                $mail->addCC($asesor);
                foreach($arrayccEmail as $item) {
                    $mail->addCC($item);
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

        public static function enviarCorreoCotizacion($solicitante, $ccemail, $asunto, $cuerpo){
            require '../php_libraries/phpMailer/Exception.php';
            require '../php_libraries/phpMailer/PHPMailer.php';
            require '../php_libraries/phpMailer/SMTP.php';
            $arrayccEmail = explode(';',$ccemail);
    
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = 2;                      
                $mail->isSMTP();                                            
                $mail->Host       = 'smtp.office365.com';                    
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = 'apoyoconectate@uniandes.edu.co';       
                $mail->Password   = 'ceintic13';                            
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          
                $mail->Port       = 587;                                    

                //Recipients
                $mail->setFrom('apoyoconectate@uniandes.edu.co', 'Conecta-Te');
                $mail->addAddress($solicitante, ''); 
                $mail->addCC('apoyoconectate@uniandes.edu.co');
                foreach($arrayccEmail as $item) {
                    $mail->addCC($item);
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
                return false;
            } 
        }

        public static function enviarCorreoAsignados($solicitante, $ccemail, $asunto, $cuerpo){
            require '../php_libraries/phpMailer/Exception.php';
            require '../php_libraries/phpMailer/PHPMailer.php';
            require '../php_libraries/phpMailer/SMTP.php';
            $arrayccEmail = explode(';',$ccemail);
    
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = 2;                      
                $mail->isSMTP();                                            
                $mail->Host       = 'smtp.office365.com';                    
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = 'apoyoconectate@uniandes.edu.co';       
                $mail->Password   = 'ceintic13';                            
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          
                $mail->Port       = 587;                                    

                //Recipients
                $mail->setFrom('apoyoconectate@uniandes.edu.co', 'Conecta-Te');
                $mail->addAddress($solicitante, ''); 
                $mail->addCC('apoyoconectate@uniandes.edu.co');
                foreach($arrayccEmail as $item) {
                    $mail->addCC($item);
                    }
                
                // Content
                $mail->isHTML(true);                                  
                $mail->Subject = $asunto;
                $mail->Body    = $cuerpo;
                $mail->CharSet = 'UTF-8';

                $mail->send();
                echo '<script>alert("Se envio correctamente el correo")</script>';
                echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php">';
            } catch (Exception $e) {
                echo '<script>alert("No se envio correctamente el correo. Error:'. $mail->ErrorInfo.'")</script>'; 
                echo '<meta http-equiv="Refresh" content="0;url=../Views/solicitudEspecifica.php">';
            } 
        }
    } 
?>