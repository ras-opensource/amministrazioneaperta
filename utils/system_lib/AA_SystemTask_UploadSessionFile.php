<?php
class AA_SystemTask_UploadSessionFile extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("UploadSessionFile", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());

        $value = false;
        foreach ($_FILES as $id => $curFile) {
            $value = AA_SessionFileUpload::Add($_REQUEST['file_id'], $curFile);
        }

        if ($value !== false) {
            $this->sTaskLog = json_encode(array("status" => "server", "value" => $value['tmp_name']));
            return true;
        } else {
            $this->sTaskLog = json_encode(array("status" => "error", "value" => "errore nel caricamento del file."));
            return false;
        }
    }
}
