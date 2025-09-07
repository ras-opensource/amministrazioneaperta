<?php
/**
 * Task Manager per il modulo SICAR
 * Gestione delle operazioni sugli immobili
 */

 include_once("lib.php");

 session_start();
 
 $user=AA_User::GetCurrentUser();
 
 if($user->IsGuest())
 {
   die("<status id='status'>-2</status><error id='error'>Credenziali non impostate o sessione scaduta.</error>"); 
 }
 
 //Utente non identificato o sessioen scaduta
 
 //Task non impostato
 if($_REQUEST['task'] == "")
 {
   die("<status id='status'>-1</status><error id='error'>parametro task non impostato.</error>");
 }
 
 $task=$_REQUEST['task'];
 
 $module= new AA_SicarModule($user);
 
 $taskManager = $module->GetTaskManager();
 
 if($taskManager->IsManaged($task))
 {
   if(!$taskManager->RunTask($task))
   {
     AA_Log::Log("task manager - task: ".$task." - ".$taskManager->GetTaskError($task),100,false,true);
   }
   die($taskManager->GetTaskLog($task));
 }
 
 die("<status id='status'>-1</status><error id='error'>Task non gestito: ".$task.".</error>");
 
