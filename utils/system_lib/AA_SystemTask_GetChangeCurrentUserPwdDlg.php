<?php
class AA_SystemTask_GetChangeCurrentUserPwdDlg extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetChangeCurrentUserPwdDlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $user=AA_User::GetCurrentUser();
        if($user->IsGuest())
        {
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Utente non valido o sessione scaduta</error>";
            $this->SetLog($sTaskLog);
            
            return false;
        }

        $user->MailOTPChangePwdChallenge();

        //Profilo aggiornato
        $dlg = new AA_SystemChangeCurrentUserPwdDlg("AA_SystemChangeCurrentUserPwdDlg","Reimposta password");
        $sTaskLog .= $dlg->toBase64()."</content>";

        $this->SetLog($sTaskLog);

        return true;
    }
}
