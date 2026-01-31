<?php
class AA_SystemChangeCurrentUserProfileDlg extends AA_GenericWindowTemplate
{
    public function __construct($id = "", $title = "")
    {
        parent::__construct($id, $title, "");

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->SetWidth("640");
        $this->SetHeight("480");

        $user = AA_User::GetCurrentUser();
        $profili = $user->GetProfiles();
        $data = array();
        foreach ($profili as $id => $userProfile) 
        {
            if($id != $user->GetId())
            {
                $struct = $userProfile->GetStruct();
                $struttura="Nessuna";
                if($struct->GetAssessorato(true)>0) $struttura=$struct->GetAssessorato();
                if($struct->GetDirezione(true)>0) $struttura.="<br>".$struct->GetDirezione();
                if($struct->GetServizio(true)>0) $struttura.="<br>".$struct->GetServizio();
    
                $modify='AA_MainApp.utils.callHandler("doTask", {task:"ChangeCurrentUserProfile", params: [{profile: "'.$userProfile->GetId().'"}],taskManager:AA_MainApp.taskManager})';
                $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' style='font-size: 48px' title='Cambia profilo' onClick='".$modify."'><span class='mdi mdi-account-switch'></span></a></div>";
                $data[] = array("id" => $id, "ruolo" => $userProfile->GetRuolo(), "struttura" => $struttura,"ops"=>$ops);
            }
        }

        if (sizeof($profili) > 1) {
            $columns = array(
                array("id" => "ruolo", "header" => array("<div style='text-align: center'>Ruolo</div>"), "width" => 120, "sort" => "text", "css" => "ProfileUserTable_left"),
                array("id" => "struttura", "header" => array("<div style='text-align: center'>Struttura</div>"), "fillspace" => true, "css" => "ProfileUserTable_left"),
                array("id" => "ops", "header" => array("<div style='text-align: center'>Operazioni</div>"), "width" => 90, "css" => "ProfileUserTable"),
            );

            $table = new AA_JSON_Template_Generic("", array(
                "view" => "datatable",
                "scrollX" => false,
                "select" => false,
                "fixedRowHeight" => false,
                "rowHeight" => 90,
                "rowLineHeight" => 24,
                "css" => "AA_Header_DataTable",
                "hover" => "AA_DataTable_Row_Hover",
                "eventHandlers" => array("onresize" => array("handler" => "adjustRowHeight")),
                "columns" => $columns,
                "data" => $data
            )
            );

            $this->AddView($table);
        } else {
            $this->AddView(new AA_JSON_Template_Template("", array("template" => "<div style='display:flex;justify-content:center;align-items: center'>Non possiedi ulteriori profili diversi da quello corrente.</div>")));
        }
    }
}
