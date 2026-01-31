<?php
class AA_SystemTask_ChangeCurrentUserProfile extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("ChangeCurrentUserProfile", $user);
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

        if(!AA_User::ChangeProfile($_REQUEST['profile']))
        {
            $sTaskLog = "<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            $this->SetLog($sTaskLog);
            
            return false;
        }

        //reload page
        $sTaskLog = "<status id='status' action='AA_RefreshApp' action_params='true'>0</status><content id='content'>";
        $sTaskLog .= "Profilo cambiato con successo.</content>";
        $this->SetLog($sTaskLog);

        return true;
    }
}
