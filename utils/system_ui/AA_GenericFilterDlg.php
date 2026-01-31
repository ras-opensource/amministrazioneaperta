<?php
class AA_GenericFilterDlg extends AA_GenericFormDlg
{
    protected $saveFilterId = "";
    public function SetSaveFilterId($id = "")
    {
        $this->saveFilterId = $id;
    }
    public function GetSaveFilterId()
    {
        return $this->saveFilterId;
    }

    protected $enableSessionSave = false;
    public function EnableSessionSave($bVal = true)
    {
        $this->enableSessionSave = $bVal;
    }

    public function __construct($id = "", $title = "", $module = "", $formData = array(), $resetData = array(), $applyActions = "", $save_filter_id = "")
    {
        parent::__construct($id, $title, $module, $formData, $resetData, $applyActions, $save_filter_id);

        $this->SetWidth("700");
        $this->SetHeight("400");

        $this->applyActions = $applyActions;
        $this->saveFilterId = $save_filter_id;

        /*$this->form=new AA_JSON_Template_Form($this->id."_Form",array(
            "data"=>$formData,
            "elementsConfig"=>array("labelWidth"=>180)
        ));
        
        $this->body->AddRow($this->form);
        
        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view"=>"spacer", "height"=>10, "css"=>array("border-top"=>"1px solid #e6f2ff !important;"))));
        
        //Apply button
        $this->applyButton = new AA_JSON_Template_Generic($this->id."_Button_Bar_Apply",array("view"=>"button","width"=>80, "label"=>"Applica"));
        
        //Toolbar
        $toolbar=new AA_JSON_Template_Layout($this->id."_Button_Bar",array("height"=>38));
        $toolbar->addCol(new AA_JSON_Template_Generic("spacer",array("view"=>"spacer","width"=>15)));
        
        //reset form button
        if(is_array($resetData))$resetAction="if($$('".$this->id."_Form')) $$('".$this->id."_Form').setValues(".json_encode($resetData).")";
        else $resetAction="";
        $toolbar->addCol(new AA_JSON_Template_Generic($this->id."_Button_Bar_Reset",array("view"=>"button","width"=>80,"label"=>"Reset", "tooltip"=>"Reimposta i valori di default", "click"=>$resetAction)));
        
        $toolbar->addCol(new AA_JSON_Template_Generic());
        
        $toolbar->addCol($this->applyButton);
        $toolbar->addCol(new AA_JSON_Template_Generic("spacer",array("view"=>"spacer","width"=>15)));
        $this->body->AddRow($toolbar);
        $this->body->AddRow(new AA_JSON_Template_Generic("spacer",array("view"=>"spacer","height"=>10)));*/
    }

    protected function Update()
    {
        parent::Update();

        if ($this->module == "")
            $module = "module=AA_MainApp.curModule";
        else
            $module = "module=AA_MainApp.getModule('" . $this->module . "')";

        if ($this->saveFilterId == "")
            $filter_id = "module.getActiveView()";
        else
            $filter_id = "'" . $this->saveFilterId . "'";

        if ($this->enableSessionSave) {
            $sessionSave = "AA_MainApp.setSessionVar(" . $filter_id . ", $$('" . $this->id . "_Form').getValues());";
        } else
            $sessionSave = "";

        $this->applyButton->SetProp("click", "try{" . $module . "; if(module.isValid()) {" . $sessionSave . "module.setRuntimeValue(" . $filter_id . ",'filter_data',$$('" . $this->id . "_Form').getValues());" . $this->applyActions . ";}$$('" . $this->id . "_Wnd').close()}catch(msg){console.error(msg)}");
    }

    /*
    //Aggiungi un campo al form
    public function AddField($name="", $label="", $type="text", $props=array())
    {
        if($name !="" && $label !="")
        {
            $props['name']=$name;
            $props['label']=$label;
            
            if($type=="text") $this->form->AddElement(new AA_JSON_Template_Text($this->id."_Field_".$name,$props));
            if($type=="textarea") $this->form->AddElement(new AA_JSON_Template_Textarea($this->id."_Field_".$name,$props));
            if($type=="checkbox") $this->form->AddElement(new AA_JSON_Template_Checkbox($this->id."_Field_".$name,$props));
            if($type=="select") $this->form->AddElement(new AA_JSON_Template_Select($this->id."_Field_".$name,$props));
            if($type=="switch") $this->form->AddElement(new AA_JSON_Template_Switch($this->id."_Field_".$name,$props));
        }
    }
    
    //Aggiungi un campo di testo
    public function AddTextField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"text",$props);
    }
    
    //Aggiungi un campo di area di testo
    public function AddTextareaField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"textarea",$props);
    }
    
    //Aggiungi un checkbox
    public function AddCheckBoxField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"checkbox",$props);
    }
    
    //Aggiungi un switchbox
    public function AddSwitchBoxField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"switch",$props);
    }
    
    //Aggiungi una select
    public function AddSelectField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"select",$props);
    }
    
    //Aggiungi un campo per la scelta delle strutture
    public function AddStructField($taskParams=array(),$params=array(), $fieldParams=array())
    {
        $onSearchScript="try{ if($$('".$this->id."_Form').getValues().id_struct_tree_select) AA_MainApp.ui.MainUI.structDlg.lastSelectedItem={id: $$('".$this->id."_Form').getValues().id_struct_tree_select}; AA_MainApp.ui.MainUI.structDlg.show(". json_encode($taskParams).",".json_encode($params).");}catch(msg){console.error(msg)}";
        
        if($fieldParams['name']== "") $fieldParams['name']="struct_desc";
        if($fieldParams['label']== "") $fieldParams['label']="Struttura";
        if($fieldParams['readonly']== "") $fieldParams['readonly']=true;
        if($fieldParams['click']== "") $fieldParams['click']=$onSearchScript;
        
        $this->form->AddElement(new AA_JSON_Template_Search($this->id."_Field_Struct_Search",$fieldParams));
    }*/
}
