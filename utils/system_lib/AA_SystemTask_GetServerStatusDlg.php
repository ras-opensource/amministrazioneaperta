<?php
class AA_SystemTask_GetServerStatusDlg extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetServerStatusDlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());
        $wnd = new AA_GenericServerStatusDlg("AA_SystemServerStatusDlg_" . $_REQUEST['id'], "Server status", $this->oUser);

        $this->sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>" . $wnd->toBase64() . "</content><error id='error'></error>";
        return true;
    }
}
