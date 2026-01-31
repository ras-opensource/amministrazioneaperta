<?php
class AA_SystemTask_GetStructDlg extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetStructDlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());
        $module="";
        if(isset($_REQUEST['module'])) $module = $_REQUEST['module'];
        $wnd = new AA_GenericStructDlg("AA_SystemStructDlg_".uniqid(), "Organigramma", $_REQUEST, "", $module, $this->oUser);

        //AA_Log::Log(__METHOD__." - ".$wnd->toString(),100);

        $this->sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>" . $wnd->toBase64() . "</content><error id='error'></error>";
        return true;
    }
}
