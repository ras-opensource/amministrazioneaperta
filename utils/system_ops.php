<?php
include_once("system_lib.php");
session_start();

$task=$_REQUEST['task'];

//Utente non identificato o sessioen scaduta
$user=AA_User::GetCurrentUser();
if($user->IsGuest() && $task !="UserAuth" && $task !="struttura-utente")
{
  //AA_Log::Log("SmartCV - token: ".$_SESSION['token']);
  die("<status id='status'>-2</status><error id='error'>Credenziali non impostate o sessione scaduta.</error>");
  exit;
}

//Task non impostato
if($task == "")
{
  die("<status id='status'>-1</status><error id='error'>parametro task non impostato.</error>");
  exit;
}

//auth
if($task=="UserAuth")
{
    $user=AA_User::UserAuth("", $_REQUEST['user'],$_REQUEST['pwd']);
    if($user->IsGuest())
    {
        die("<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>");
    }
    else die("<status id='status'>0</status><error id='error'>Autenticazione effettuata con successo.</error>");
}

$taskManager = new AA_SystemTaskManager($user);
if(!$taskManager->RunTask($task))
{
  AA_Log::Log("system_ops - task: ".$task." - ".$taskManager->GetTaskError($task),100,false,true);
}
die($taskManager->GetTaskLog($task));
exit;
?>