<?php
class AA_GenericStructDlg extends AA_GenericWindowTemplate
{
    protected $applyActions = "";
    /**
     * @var mixed
     */
    protected  $targetForm = "";
    public function GetTargetForm()
    {
        return $this->targetForm;
    }

    public function SetApplyActions($actions = "")
    {
        $this->applyActions = $actions;
    }

    protected $applyButton = "";

    public function __construct($id = "", $title = "", $options = null, $applyActions = "", $module = "", $user = null)
    {
        parent::__construct($id, $title, $module);

        if (!($user instanceof AA_User)) $user = AA_User::GetCurrentUser();

        $this->SetWidth("800");
        $this->SetHeight("600");

        $this->applyActions = $applyActions;

        //target Form
        if ($options['targetForm'] != "") $this->targetForm = $options['targetForm'];

        /*
        if ($user->IsValid()) {
            $struct = "";
            $userStruct = $user->GetStruct();
            if (is_array($options)) {
                if (isset($options['showAll']) && $options['showAll'] == 1) {
                    if ($userStruct->GetTipo() <= 0) $struct = AA_Struct::GetStruct(0, 0, 0, $userStruct->GetTipo()); //RAS
                    else $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), 0, 0, $userStruct->GetTipo()); //Altri
                }

                if (isset($options['showAllDir']) && $options['showAllDir'] == 1) {
                    $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), 0, 0, $userStruct->GetTipo());
                }

                if (isset($options['showAllServ']) && $options['showAllServ'] == 1) {
                    $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), $userStruct->GetDirezione(true), 0, $userStruct->GetTipo());
                }
            }
            if (!($struct instanceof AA_Struct)) $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), $userStruct->GetDirezione(true), $userStruct->GetServizio(true), $userStruct->GetTipo());
        } else {
            $struct = array(array("id" => "root", "value" => "strutture"));
        }*/

        //Struttura
        $filterLevel = 4;

        if (isset($options['hideServices'])) $filterLevel = 3;

        //AA_Log::Log(__METHOD__." - options: ".print_r($_REQUEST,true),100);

        $url_params="?task=GetStructTreeData";
        foreach($options as $key=>$val)
        {
            if($key !="task") $url_params.="&".$key."=".$val;
        }

        $tree = new AA_JSON_Template_Tree($this->id . "_Tree", array(
            //"data" => $struct->toArray($options),
            "url"=>AA_Config::AA_PUBLIC_LIB_PATH."/system_ops.php".$url_params,
            "select" => true,
            "switch_suppressed_id"=>$this->id . "_Switch_Supressed",
            "search_text_id"=>$this->id . "_Search_Text",
            //"filterMode" => array("showSubItems" => false, "level" => $filterLevel, "openParents" => false),
            "template" => "{common.icon()}&nbsp;{common.folder()}&nbsp;<span>#value#</span>"
        ));

        //Filtra in base al testo
        $this->body->AddRow(new AA_JSON_Template_Search($this->id . "_Search_Text", array("placeholder" => "Digita qui per filtrare le strutture")));

        $this->body->AddRow($tree);

        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 10, "css" => array("border-top" => "1px solid #e6f2ff !important;"))));

        //Apply button
        $this->applyButton = new AA_JSON_Template_Generic($this->id . "_Button_Bar_Apply", array("view" => "button", "width" => 80, "label" => "Applica"));

        //Toolbar
        $toolbar = new AA_JSON_Template_Layout($this->id . "_Button_Bar", array("height" => 38));
        $toolbar->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 15)));

        //mostra/nascondi strutture soppresse
        $toolbar->addCol(new AA_JSON_Template_Generic($this->id . "_Switch_Supressed", array("view" => "switch", "width" => 350, "struct_params"=>$url_params, "treeView_id"=>$this->id . "_Tree","label" => "Strutture soppresse:", "labelWidth" => 150, "onLabel" => "visibili", "offLabel" => "nascoste", "tooltip" => "mostra/nascondi le strutture soppresse","eventHandlers"=>array("onChange"=>array("handler"=>"onStructDlgShowSupressedChange")))));

        $toolbar->addCol(new AA_JSON_Template_Generic());
        $toolbar->addCol($this->applyButton);
        $toolbar->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 15)));
        $this->body->AddRow($toolbar);
        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 10)));
    }

    protected function Update()
    {
        if ($this->targetForm != "") $this->applyActions .= "; if($$('" . $this->targetForm . "')) { $$('" . $this->targetForm . "').setValues({id_assessorato : AA_MainApp.ui.MainUI.structDlg.lastSelectedItem['id_assessorato'], \"id_direzione\" : AA_MainApp.ui.MainUI.structDlg.lastSelectedItem['id_direzione'], id_servizio : AA_MainApp.ui.MainUI.structDlg.lastSelectedItem['id_servizio'], struct_desc : AA_MainApp.ui.MainUI.structDlg.lastSelectedItem['value'], id_struct_tree_select: AA_MainApp.ui.MainUI.structDlg.lastSelectedItem['id']},true);}";

        $this->applyButton->SetProp("click", "try{AA_MainApp.ui.MainUI.structDlg.lastSelectedItem=$$('" . $this->id . "_Tree').getSelectedItem();" . $this->applyActions . "; $$('" . $this->id . "_Wnd').close()}catch(msg){console.error(msg)}");

        parent::Update();
    }
}
