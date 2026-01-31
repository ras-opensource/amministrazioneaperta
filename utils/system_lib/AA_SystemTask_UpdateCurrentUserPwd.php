<?php
class AA_SystemTask_UpdateCurrentUserPwd extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("UpdateCurrentUserPwd", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content'>";

        $user=AA_User::GetCurrentUser();
        if($user->IsGuest())
        {
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Utente non valido o sessione scaduta</error>";
            $this->SetLog($sTaskLog);
            
            return false;
        }

        if(!$user->ChangePwd($_REQUEST))
        {
            $sTaskLog = "<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            $this->SetLog($sTaskLog);
            
            return false;
        }

        //Profilo aggiornato
        $sTaskLog .= "Password aggiornata con successo.</content>";

        $this->SetLog($sTaskLog);

        return true;
    }
}
