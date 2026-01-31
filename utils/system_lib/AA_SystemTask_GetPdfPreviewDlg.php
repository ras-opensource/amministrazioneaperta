<?php
class AA_SystemTask_GetPdfPreviewDlg extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetPdfPreviewDlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());
        $wnd = new AA_GenericPdfPreviewDlg();

        //AA_Log::Log(__METHOD__." - ".$wnd->toString(),100);

        $this->sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>" . $wnd->toBase64() . "</content><error id='error'></error>";
        return true;
    }
}
