<?php

require_once 'config.php';
set_include_path(get_include_path().PATH_SEPARATOR.AA_Config::AA_LIB_PATH.DIRECTORY_SEPARATOR.'phpmailer');

require_once 'PHPMailerAutoload.php';

//Server settings
$mail_SMTPDebug = 0;                                                    // Enable verbose debug output
$mail_isSMTP = true;                                                    // Set mailer to use SMTP
$mail_Host = AA_Config::AA_DBHOST;                                // Specify main and backup SMTP servers
$mail_SMTPAuth = false;                                                 // Enable SMTP authentication
$mail_Username = AA_Config::AA_SMTP_USERNAME;           // SMTP username
$mail_Password = AA_Config::AA_SMTP_PWD;                                              // SMTP password
$mail_SMTPSecure = '';                                               // Enable TLS encryption, `ssl` also accepted
$mail_Port = 25;
$mail_error = "";

function SendMail($Tolist,$CClist,$subject,$body,$allegati=array(), $priority=null, $isHtml=true)
{
    $mail_error="";

    if(!is_array($Tolist))
    {
        error_log("SendMail() - Lista destinatari non corretta: ");
        return false;
    }

    if($subject == "" )
    {
        error_log("SendMail() - Soggetto vuoto.");
        return false;
    }

    if($body == "")
    {
        error_log("SendMail() - Corpo mail vuoto.");
        return false;
    }

    global $mail_SMTPAuth,$mail_SMTPDebug,$mail_Host,$mail_isSMTP,$mail_Password,$mail_Port,$mail_SMTPSecure,$mail_Username;

    $mail = new PHPMailer(true);
    
    try
    {
        $mail->SMTPDebug = $mail_SMTPDebug;                                 
        if($mail_isSMTP) $mail->isSMTP();                                   
        $mail->Host = $mail_Host;
        $mail->SMTPAuth = $mail_SMTPAuth;
        $mail->Username = $mail_Username;
        $mail->Password = $mail_Password;
        $mail->SMTPSecure = $mail_SMTPSecure;
        $mail->Port = $mail_Port;
        if($priority) $mail->Priority=$priority;
        
        //Da Amministrazione Aperta
        $mail->setFrom('amministrazioneaperta@regione.sardegna.it', 'Amministrazione Aperta');
        $mail->addReplyTo('amministrazioneaperta@regione.sardegna.it', 'Amministrazione Aperta');

        //Destinatari
        foreach($Tolist as $curTo)
        {
            $mail->addAddress($curTo);
        }

        //Destinatari CC
        foreach($CClist as $curCC)
        {
            $mail->addCC($curCC);
        }

        //Allegati
        foreach($allegati as $curAllegato)
        {
            if(is_file($curAllegato)) $mail->addAttachment($curAllegato);     
        } 

        //Content
        $mail->isHTML($isHtml);                             
        $mail->Subject = $subject;
        $mail->Body    = $body;
        
        //send email
        $mail->send();

        error_log('SendMail() - Mail inviata: '.$subject);
        return true;
    }
    catch (Exception $e) {
        error_log('SendMail() - errore invio mail: '.$mail->ErrorInfo);
        $mail_error.=$mail->ErrorInfo;
        return false;
    }
    
    return false;
}
?>