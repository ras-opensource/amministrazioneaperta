<?php
class AA_SystemTask_GetStructTreeData extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetStructTreeData", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog = "";
        $data=array(array("id" => "root", "parent"=>0,"value" => "strutture"));
        if ($this->oUser->IsValid()) 
        {
            $struct = "";
            $userStruct = $this->oUser->GetStruct();
            if (is_array($_REQUEST)) {
                if (isset($_REQUEST['showAll']) && $_REQUEST['showAll'] == 1) {
                    if ($userStruct->GetTipo() <= 0) $struct = AA_Struct::GetStruct(0, 0, 0, $userStruct->GetTipo()); //RAS
                    else $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), 0, 0, $userStruct->GetTipo()); //Altri
                }

                if (isset($_REQUEST['showAllDir']) && $_REQUEST['showAllDir'] == 1) {
                    $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), 0, 0, $userStruct->GetTipo());
                }

                if (isset($_REQUEST['showAllServ']) && $_REQUEST['showAllServ'] == 1) {
                    $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), $userStruct->GetDirezione(true), 0, $userStruct->GetTipo());
                }
            }
            if (!($struct instanceof AA_Struct)) $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), $userStruct->GetDirezione(true), $userStruct->GetServizio(true), $userStruct->GetTipo());

            if(empty($_REQUEST['show_suppressed'])) $_REQUEST['bHideSuppressed']=1;

            $data=$struct->toArray($_REQUEST);
        }

        $this->sTaskLog=json_encode($data);
    }
}
