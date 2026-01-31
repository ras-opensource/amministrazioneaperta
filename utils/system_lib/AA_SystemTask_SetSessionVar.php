<?php
class AA_SystemTask_SetSessionVar extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("SetSessionVar", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());

        $name = $_REQUEST['name'];
        if ($name != "") {
            AA_SessionVar::Set($_REQUEST['name'], $_REQUEST['value']);
            $this->sTaskLog = "<status id='status'>0</status><error id='error'>variabile impostata correttamente</error>";
            return true;
        } else {
            $this->sTaskLog = "<status id='status'>-1</status><error id='error'>variabile non impostata (nome non definito).</error>";
            return true;
        }
    }
}
