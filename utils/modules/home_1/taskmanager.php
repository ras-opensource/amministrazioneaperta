<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$path = '/home/sitod/web/amministrazione_aperta/utils';
setlocale(LC_ALL, 'it_IT');
set_include_path(get_include_path().PATH_SEPARATOR.$path);

include_once("home_lib.php");

session_start();

//Utente non identificato o sessioen scaduta
$user=AA_User::GetCurrentUser();
if($user->IsGuest())
{
  die("<status id='status'>-2</status><error id='error'>Credenziali non impostate o sessione scaduta.</error>");
  exit;
}

//Task non impostato
if($_REQUEST['task'] == "")
{
  die("<status id='status'>-1</status><error id='error'>parametro task non impostato.</error>");
  exit;
}

$task=$_REQUEST['task'];

$taskManager = new AA_HomeTaskManager($user);

if($taskManager->IsManaged($task))
{
  if(!$taskManager->RunTask($task))
  {
    AA_Log::Log("organismi_ops - task: ".$task." - ".$taskManager->GetTaskError($task),100,false,true);
  }
  die($taskManager->GetTaskLog($task));
}

die("<status id='status'>-1</status><error id='error'>Task non gestito (2): ".$task.".</error>");
exit;