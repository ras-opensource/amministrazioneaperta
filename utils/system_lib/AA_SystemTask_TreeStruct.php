<?php
class AA_SystemTask_TreeStruct extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("struttura-utente", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());
        $userStruct = $this->oUser->GetStruct();
        if ($this->oUser->isGuest()) $userStruct = AA_Struct::GetStruct(1, 0, 0, 0);

        if (!isset($_REQUEST['show_all'])) $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), $userStruct->GetDirezione(true), 0, $userStruct->GetTipo());
        else {
            if ($userStruct->GetTipo() > 0) $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), 0, 0, $userStruct->GetTipo());
            else $struct = AA_Struct::GetStruct(0, 0, 0, $userStruct->GetTipo());
        }

        if ($_REQUEST['format'] != "json") $this->sTaskLog = "<status id='status'>0</status>" . $struct->toXML() . "<error id='error'></error>";
        else $this->sTaskLog = "<status id='status'>0</status><content id'content' type='json' encode='base64'>" . $struct->toJSON(true) . "</content><error id='error'></error>";
        return true;
    }
}
