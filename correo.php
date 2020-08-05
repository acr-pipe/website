<?php

require_once '../assets/libs/swiftmail/swift_required.php';

class correo 
{
    var $mailer;
    var $message;

    function __construct($pr,$tit,$msj)
    { 
      	include_once 'mysqlDB.php';
		$base = new DBClass();
		$res = $base->ejecutar('call sp_getGeneralMail('.$_SESSION['IMPRESA'].')')->fetch_all()[0];
    // $plantilla = '<table border="0" cellpadding="0" cellspacing="0" style="max-width:600px"><tbody><tr><td><table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td align="left"><img height="32" src="https://www.logintechcr.com/resources/img/logo/logogmail.gif" style="display:block;width:92px;height:32px" width="92" class="CToWUd"></td><td align="right"><img height="32" src="https://ci4.googleusercontent.com/proxy/lAhs1ZWkhk-sePp91w_WRU8JbwObkz_f8JYQgcmDkfp7jLel0CgtX7EYABb6xsGt24NeIH4pJkCRax5hoAvA7UvbMgV1uXoptJYoWQ=s0-d-e1-ft#https://www.gstatic.com/accountalerts/email/shield.png" style="display:block;width:32px;height:32px" width="32" class="CToWUd"></td></tr></tbody></table></td></tr><tr height="16"></tr><tr><td><table bgcolor="#0080C6" border="0" cellpadding="0" cellspacing="0" style="min-width:332px;max-width:600px;border:1px solid #f0f0f0;border-bottom:0;border-top-left-radius:3px;border-top-right-radius:3px" width="100%"><tbody><tr><td colspan="3" height="72px"></td></tr><tr><td width="32px"></td><td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:24px;color:#ffffff;line-height:1.25;min-width:300px">Solicitud de cambio de contraseña</td><td width="32px"></td></tr><tr><td colspan="3" height="18px"></td></tr></tbody></table></td></tr><tr><td><table bgcolor="#FAFAFA" border="0" cellpadding="0" cellspacing="0" style="min-width:332px;max-width:600px;border:1px solid #f0f0f0;border-bottom:1px solid #c0c0c0;border-top:0;border-bottom-left-radius:3px;border-bottom-right-radius:3px" width="100%"><tbody><tr height="16px"><td rowspan="3" width="32px"></td><td></td><td rowspan="3" width="32px"></td></tr><tr><td><table border="0" cellpadding="0" cellspacing="0" style="min-width:300px"><tbody><tr><td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:13px;color:#202020;line-height:1.5;padding-bottom:4px">Hola, '.$_SESSION['EMPRESA'].':</td></tr><tr><td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:13px;color:#202020;line-height:1.5;padding:4px 0">La contraseña de tu cuenta de logintech <a>'.$pr.'</a> ha solicitado un cambio de contraseña.<br><br><b>¿No reconoces esta actividad?</b><br>Haz clic <a href="#!">aquí</a> para obtener más información sobre cómo recuperar la cuenta.</td></tr><tr><td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:13px;color:#202020;line-height:1.5;padding-top:28px">El equipo de cuentas de logintech</td></tr><tr height="16px"></tr><tr><td><table style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:12px;color:#b9b9b9;line-height:1.5"><tbody><tr><td>Esta dirección de correo electrónico no admite respuestas. Para obtener más información, visita el <a href="https://www.logintech.co.cr" style="text-decoration:none;color:#4285f4" target="_blank" data-saferedirecturl="#!">www.logintechcr.com</a>.</td></tr></tbody></table></td></tr></tbody></table></td></tr><tr height="32px"></tr></tbody></table></td></tr><tr height="16"></tr><tr><td style="max-width:600px;font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:10px;color:#bcbcbc;line-height:1.5"></td></tr><tr><td><table style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:10px;color:#666666;line-height:18px;padding-bottom:10px"><tbody><tr><td>Te hemos enviado este correo electrónico de anuncio de servicio obligatorio para informarte sobre una serie de cambios importantes que afectan a tu cuenta o producto de logintech.</td></tr><tr height="6px"></tr><tr><td><div style="direction:ltr;text-align:left">© 2018 logintech VS., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA</div><div style="display:none!important;max-height:0px;max-width:0px">1531165673661971</div></td></tr></tbody></table></td></tr></tbody></table>';
      	$transport = Swift_SmtpTransport::newInstance($res[2],$res[3])
      		->setUsername($res[1])
      		->setPassword($res[0]);
      $empresa = isset($_SESSION['EMPRESA']) ? $_SESSION['EMPRESA'] : 'Logintech';
     	$this->mailer = Swift_Mailer::newInstance($transport);
     	$this->message = Swift_Message::newInstance($tit)
     		->setFrom(array($res[1] => $empresa))
     		->setTo( explode(',',$pr) )
     		->setBody($msj,'text/html');

      if ($_SESSION['BUSS'] == 1) {
        print_r($_SESSION['CRR']);
        $this->message->setBcc(array($_SESSION['CRR']=>$_SESSION['NOM']));
      }

        //'<div style="min-height:250px;background-color: #0B3861; margin-left:15%;margin-right: 15%;color: white">'. .'</div>'
    }

    function enviar(){
	     if ($this->mailer->send($this->message)) {
	        return 1;
	     } else {
	        return 0;
	     }
    }

    function enviar_adjunto($vAdjunto){

      if ($vAdjunto == '')
        return 1;
    
      if(is_array($vAdjunto)){
        for ($i=0; $i < sizeof($vAdjunto); $i++) { 
          $this->message->attach(Swift_Attachment::fromPath('../assets/'.$vAdjunto[$i]));
        }
      }else
        $this->message->attach(Swift_Attachment::fromPath('../assets/'.$vadjunto));      

      if ($this->mailer->send($this->message)) {
          if(is_array($vAdjunto)){
            for ($i=0; $i < sizeof($vAdjunto); $i++) { 
              unlink('../assets/'.$vAdjunto[$i]);
            }
          }else
            unlink('../assets/'.$vAdjunto);
              
          return 1;
       } else {
          return 0;
       }

    }
}

?>