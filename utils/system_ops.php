<?php
include_once("system_lib.php");
session_start();

$task=$_REQUEST['task'];

include_once("system_custom.php");

//recupero credenziali
if($task=="ResetPassword")
{
  if(!isset($_REQUEST['email']))
  {
    die("<status id='status'>-1</status><error id='error'>email di recupero non impostata o non valida.</error>");
  }

  if(!AA_User::ResetPassword($_REQUEST['email']))
  {
    die("<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>");
  }

  die("<status id='status'>0</status><content id='content'>Le nuove credenziali sono state inviate alla casella indicata.</content><error id='error'>Le nuove credenziali sono state inviate alla casella indicata.</error>");
}

//email auth
if($task=="MailUserAuth")
{
    $result=AA_User::MailOTPAuthChallenge($_REQUEST['email'],$_REQUEST['remember_me']);
    if(!$result)
    {
        die("<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>");
    }
    else die("<status id='status'>0</status><error id='error'>Codice di verifica inviato alla email indicata.</error>");
}

//email auth verify
if($task=="MailUserAuthChallengeVerify")
{
    $result=AA_User::MailOTPAuthChallengeVerify($_REQUEST['codice']);
    if(!$result)
    {
        die("<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>");
    }
    else die("<status id='status'>0</status><error id='error'>Codice verificato con successo.</error>");
}

//auth
if($task=="UserAuth")
{
    $user=AA_User::UserAuth("", $_REQUEST['user'],$_REQUEST['pwd'],$_REQUEST['remember_me']);
    if($user->IsGuest())
    {
        die("<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>");
    }
    else die("<status id='status'>0</status><error id='error'>Autenticazione effettuata con successo.</error>");
}
else
{
  $user=AA_User::GetCurrentUser();
}

//log out
if($task=="UserLogOut")
{
    //$user=AA_User::UserAuth("", $_REQUEST['user'],$_REQUEST['pwd']);
    if($user->IsGuest())
    {
      die("<status id='status'>0</status><error id='error'>Logout effettuato con successo.</error>");
    }

    if($user->IsValid() && $user->isCurrentUser())
    {
      $user->LogOut();
    }
    
    die("<status id='status'>0</status><error id='error'>Logout effettuato con successo.</error>");
}

//Utente non identificato o sessioen scaduta
if($user->IsGuest() && $task !="struttura-utente")
{
  //AA_Log::Log("SmartCV - token: ".$_SESSION['token']);
  die("<status id='status'>-2</status><error id='error'>Credenziali non impostate o sessione scaduta.</error>");
  exit;
}

//Task non impostato
if($task == "")
{
  die("<status id='status'>-1</status><error id='error'>parametro task non impostato.</error>");
}

$taskManager = new AA_SystemTaskManager($user);
if(!$taskManager->RunTask($task))
{
  AA_Log::Log("system_ops - task: ".$task." - ".$taskManager->GetTaskError($task),100,false,true);
}
die($taskManager->GetTaskLog($task));
?>