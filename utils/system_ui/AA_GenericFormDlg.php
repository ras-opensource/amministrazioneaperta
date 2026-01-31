<?php
class AA_GenericFormDlg extends AA_GenericWindowTemplate
{
    protected $form = "";
    public function GetForm()
    {
        return $this->form;
    }
    public function GetFormId()
    {
        if ($this->form instanceof AA_JSON_Template_Form)
            return $this->form->GetId();
    }

    protected $layout = "";
    public function GetLayout()
    {
        return $this->layout;
    }
    public function GetLayoutId()
    {
        if ($this->layout instanceof AA_JSON_Template_Generic)
            return $this->layout->GetId();
    }

    protected $curRow = null;
    public function GetCurRow()
    {
        return $this->curRow;
    }

    protected $bottomPadding = 18;
    public function SetBottomPadding($val = 18)
    {
        $this->bottomPadding = $val;
    }

    protected $validation = false;
    public function EnableValidation($bVal = true)
    {
        $this->validation = $bVal;
    }

    protected $bEnableApplyHotkey = true;
    public function EnableApplyHotkey($bVal = true)
    {
        $this->bEnableApplyHotkey = $bVal;
    }

    protected $sApplyHotkey = "enter";
    public function SetApplyHotkey($val = "enter")
    {
        $this->sApplyHotkey = $val;
    }

    protected $applyActions = "";
    public function SetApplyActions($actions = "")
    {
        $this->applyActions = $actions;
    }

    protected $applyCallbackFunction = "";
    public function SetApplyCallbackFunction($actions = "")
    {
        $this->applyCallbackFunction = $actions;
    }

    protected $saveFormDataId = "";
    public function SetSaveformDataId($id = "")
    {
        $this->saveFormDataId = $id;
    }
    public function GetSaveFormDataId()
    {
        return $this->saveFormDataId;
    }

    protected $labelWidth = 120;
    public function SetLabelWidth($val = 120)
    {
        $this->labelWidth = $val;
    }

    protected $labelAlign = "left";
    public function SetLabelAlign($val = "left")
    {
        $this->labelAlign = $val;
    }

    protected $labelPosition = "left";
    public function SetLabelPosition($val = "left")
    {
        $this->labelPosition = $val;
    }
    protected $defaultFocusedItem = "right";
    public function SetDefaultFocusedItem($sVal = "")
    {
        $this->defaultFocusedItem = $sVal;
    }

    //Gestione pulsanti
    protected $applyButton = null;
    protected $applyButtonName = "Salva";
    public function SetApplyButtonName($val = "Salva")
    {
        $this->applyButtonName = $val;
    }
    protected $resetButtonName = "Reset";
    public function SetResetButtonName($val = "Reset")
    {
        $this->resetButtonName = $val;
    }
    protected $enableReset = true;
    public function EnableResetButton($bVal = true)
    {
        $this->enableReset = $bVal;
    }
    protected $applyButtonStyle = "AA_Button_primary";
    public function SetApplybuttonStyle($sStyle = "")
    {
        $this->applyButtonStyle = $sStyle;
    }
    protected $applyButtonPosition = "right";
    public function SetApplybuttonPosition($sVal = "right")
    {
        $this->applyButtonPosition = $sVal;
    }
    #----------------------------------------------------

    //Valori form
    protected $formData = array();
    protected $resetData = array();

    public function __construct($id = "", $title = "", $module = "", $formData = array(), $resetData = array(), $applyActions = "", $save_formdata_id = "")
    {
        parent::__construct($id, $title, $module);

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->SetWidth("700");
        $this->SetHeight("400");

        $this->applyActions = $applyActions;
        $this->saveFormDataId = $save_formdata_id;
        $this->formData = $formData;
        if (sizeof($resetData) == 0)
            $resetData = $formData;
        $this->resetData = $resetData;

        $this->form = new AA_JSON_Template_Form($this->id . "_Form", array(
            "data" => $formData,
        )
        );

        $this->body->AddRow($this->form);
        $this->layout = new AA_JSON_Template_Layout($id . "_Form_Layout", array("type" => "clean"));
        $this->form->AddRow($this->layout);

        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 10, "css" => array("border-top" => "1px solid #e6f2ff !important;"))));
    }

    //File upload id
    protected $fileUploader_id = "";
    public function SetFileUploaderId($id = "")
    {
        $this->fileUploader_id = $id;
    }

    #Gestione salvataggio dati
    protected $refresh = true; //Rinfresca la view in caso di salvataggio 
    public function enableRefreshOnSuccessfulSave($bVal = true)
    {
        $this->refresh = $bVal;
    }

    protected $refreshSection = false; //Rinfresca la section view in caso di salvataggio 
    public function enableRefreshSectionOnSuccessfulSave($bVal = true)
    {
        $this->refreshSection = $bVal;
    }
    protected $refresh_obj_id = "";
    public function SetRefreshObjId($id = "")
    {
        $this->refresh_obj_id = $id;
    }
    protected $closeWnd = true;
    public function EnableCloseWndOnSuccessfulSave($bVal = true)
    {
        $this->closeWnd = $bVal;
    }
    protected $saveTask = "";
    public function SetSaveTask($task = "")
    {
        $this->saveTask = $task;
    }
    protected $saveTaskParams = array();
    public function SetSaveTaskParams($params = array())
    {
        if (is_array($params))
            $this->saveTaskParams = $params;
    }

    protected $sTaskManager = "";
    public function SetTaskManager($var = "")
    {
        $this->sTaskManager = $var;
    }
    #-----------------------------------------------------

    protected function Update()
    {
        $elementsConfig = array("labelWidth" => $this->labelWidth, "labelAlign" => $this->labelAlign, "bottomPadding" => $this->bottomPadding, "labelPosition" => $this->labelPosition);
        if ($this->validation) {
            $this->form->SetProp("validation", "validateForm");
        }

        if ($this->defaultFocusedItem != "")
            $this->form->SetProp("defaultFocusedItem", $this->id . "_Field_" . $this->defaultFocusedItem);

        $this->form->SetProp("elementsConfig", $elementsConfig);

        if ($this->applyActions == "") {
            if ($this->saveTask != "") {
                $params = "{task: '$this->saveTask'";
                if ($this->sTaskManager != "")
                    $params .= ", taskManager: " . $this->sTaskManager;
                if (sizeof($this->saveTaskParams) > 0)
                    $params .= ", taskParams: " . json_encode(array($this->saveTaskParams));
                if ($this->closeWnd)
                    $params .= ", wnd_id: '" . $this->id . "_Wnd'";
                if ($this->refresh)
                    $params .= ", refresh: true";
                if ($this->refreshSection)
                    $params .= ", refresh_section: true";
                if ($this->refresh_obj_id)
                    $params .= ", refresh_obj_id: '$this->refresh_obj_id'";
                if ($this->fileUploader_id != "")
                    $params .= ", fileUploader_id: '$this->fileUploader_id'";
                $params .= ", data: $$('" . $this->id . "_Form').getValues()}";
                if ($this->validation)
                    $validate = "if($$('" . $this->id . "_Form').validate())";
                else
                    $validate = "";
                $callback="";
                if($this->applyCallbackFunction !="")
                {
                    $callback="AA_MainApp.utils.callHandler('".$this->applyCallbackFunction."',$params,'$this->module');";
                }
                else
                {
                    $callback="AA_MainApp.utils.callHandler('saveData',$params,'$this->module');";
                }
                $buttonApply = $this->id . "_Button_Bar_Apply";
                $setTimeout = "setTimeout('if($$(\"" . $buttonApply . "\")) { $$(\"" . $buttonApply . "\").enable()};',800)";
                $this->applyActions = $validate . "{ $$('" . $buttonApply . "').disable();" .$callback.$setTimeout . "}";
            }
        }

        //Apply button
        if ($this->bEnableApplyHotkey)
            $this->applyButton = new AA_JSON_Template_Generic($this->id . "_Button_Bar_Apply", array("view" => "button", "width" => 80, "css" => "webix_primary " . $this->applyButtonStyle, "hotkey" => $this->sApplyHotkey, "label" => $this->applyButtonName));
        else
            $this->applyButton = new AA_JSON_Template_Generic($this->id . "_Button_Bar_Apply", array("view" => "button", "width" => 80, "css" => "webix_primary " . $this->applyButtonStyle, "label" => $this->applyButtonName));

        //Toolbar
        $toolbar = new AA_JSON_Template_Layout($this->id . "_Button_Bar", array("height" => 38));
        $toolbar->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 15)));

        //reset form button
        if ($this->enableReset && is_array($this->resetData)) {
            $resetAction = "if($$('" . $this->id . "_Form')) " . "{" . "$$('" . $this->id . "_Button_Bar_Apply').enable();$$('" . $this->id . "_Form').setValues(" . json_encode($this->resetData) . ")}";
            $toolbar->addCol(new AA_JSON_Template_Generic($this->id . "_Button_Bar_Reset", array("view" => "button", "width" => 80, "label" => $this->resetButtonName, "tooltip" => "Reimposta i valori di default", "css" => "AA_Button_secondary", "click" => $resetAction)));
        }

        if ($this->applyButtonPosition != "left")
            $toolbar->addCol(new AA_JSON_Template_Generic());
        $toolbar->addCol($this->applyButton);
        if ($this->applyButtonPosition != "right")
            $toolbar->addCol(new AA_JSON_Template_Generic());

        $toolbar->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 15)));
        $this->body->AddRow($toolbar);
        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 10)));
        $this->applyButton->SetProp("click", $this->applyActions);
        //AA_log::log(__METHOD__." - apply: ".$this->applyActions,100);
        parent::Update();
    }

    //Aggiungi un campo al form
    public function AddField($name = "", $label = "", $type = "text", $props = array(), $newRow = true)
    {
        if ($name != "" && $label != "") {
            $props['name'] = $name;
            $props['label'] = $label;

            if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
                $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_" . uniqid(time()));
                $this->layout->AddRow($this->curRow);
            }
            if ($type == "text")
                $this->curRow->AddCol(new AA_JSON_Template_Text($this->id . "_Field_" . $name, $props));
            if ($type == "richtext")
                $this->curRow->AddCol(new AA_JSON_Template_Richtext($this->id . "_Field_" . $name, $props));
            if ($type == "ckeditor5")
                $this->curRow->AddCol(new AA_JSON_Template_Ckeditor5_field($this->id . "_Field_" . $name, $props));
            if ($type == "textarea")
                $this->curRow->AddCol(new AA_JSON_Template_Textarea($this->id . "_Field_" . $name, $props));
            if ($type == "checkbox")
                $this->curRow->AddCol(new AA_JSON_Template_Checkbox($this->id . "_Field_" . $name, $props));
            if ($type == "select")
                $this->curRow->AddCol(new AA_JSON_Template_Select($this->id . "_Field_" . $name, $props));
            if ($type == "combo")
                $this->curRow->AddCol(new AA_JSON_Template_Combo($this->id . "_Field_" . $name, $props));
            if ($type == "switch")
                $this->curRow->AddCol(new AA_JSON_Template_Switch($this->id . "_Field_" . $name, $props));
            if ($type == "datepicker")
                $this->curRow->AddCol(new AA_JSON_Template_Datepicker($this->id . "_Field_" . $name, $props));
            if ($type == "radio")
                $this->curRow->AddCol(new AA_JSON_Template_Radio($this->id . "_Field_" . $name, $props));

            //Se il campo è invisibile aggiunge uno spacer
            if (isset($props['hidden']) && $props['hidden'] == true) {
                $this->curRow->AddCol(new AA_JSON_Template_Generic($this->id . "_Spacer_" . $name, array("view" => "spacer", "minHeight" => "0", "minWidth" => "0", "height" => 1)));
            }
        }
    }

    //Aggiungi una nuova sezione
    public function AddSection($name = "New Section", $newRow = true)
    {
        if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_" . uniqid(time()));
            $this->layout->AddRow($this->curRow);
            $this->curRow->AddCol(new AA_JSON_Template_Generic($this->id . "_Section_", array("type" => "section", "template" => $name)));
        } else {
            $this->curRow->AddCol(new AA_JSON_Template_Generic($this->id . "_Section_" . $name, array("type" => "section", "template" => $name)));
        }
    }

    //Aggiungi uno spazio
    public function AddSpacer($newRow = true)
    {
        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_" . uniqid(time()));
            $this->layout->AddRow($this->curRow);
        }

        $this->curRow->AddCol(new AA_JSON_Template_Generic($this->id . "_Field_Spacer_" . uniqid(time()), array("view" => "spacer")));
    }

    //Aggiungi un campo di testo
    public function AddTextField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "text", $props, $newRow);
    }

    //Aggiungi un campo di area di testo
    public function AddTextareaField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "textarea", $props, $newRow);
    }

    //Aggiungi un campo richtext
    public function AddRichtextField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "richtext", $props, $newRow);
    }

    //Aggiungi un campo richtext ckeditor5
    public function AddCkeditor5Field($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "ckeditor5", $props, $newRow);
    }

    //Aggiungi un checkbox
    public function AddCheckBoxField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "checkbox", $props, $newRow);
    }

    //Aggiungi un switchbox
    public function AddSwitchBoxField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "switch", $props, $newRow);
    }

    //Aggiungi una select
    public function AddSelectField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "select", $props, $newRow);
    }

    //Aggiungi una combo
    public function AddComboField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "combo", $props, $newRow);
    }


    //Aggiungi un radio control
    public function AddRadioField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "radio", $props, $newRow);
    }

    //Aggiungi un campo per la scelta delle strutture
    public function AddStructField($taskParams = array(), $params = array(), $fieldParams = array(), $newRow = true)
    {
        $onSearchScript = "try{ if($$('" . $this->form->GetId() . "')){AA_MainApp.ui.MainUI.structDlg.lastSelectedItem={id: $$('" . $this->form->GetId() . "').getValues().id_struct_tree_select};}; AA_MainApp.ui.MainUI.structDlg.show(" . json_encode($taskParams) . "," . json_encode($params) . ");}catch(msg){console.error(msg)}";

        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_" . uniqid(time()));
            $this->layout->AddRow($this->curRow);
        }

        if (!isset($fieldParams['name']) || $fieldParams['name'] == "")
            $fieldParams['name'] = "struct_desc";
        if (!isset($fieldParams['label']) || $fieldParams['label'] == "")
            $fieldParams['label'] = "Struttura";
        if (!isset($fieldParams['readonly']) || $fieldParams['readonly'] == "")
            $fieldParams['readonly'] = true;
        if (!isset($fieldParams['click']) || $fieldParams['click'] == "")
            $fieldParams['click'] = $onSearchScript;

        $this->curRow->AddCol(new AA_JSON_Template_Search($this->id . "_Field_Struct_Search", $fieldParams));
    }

    //aggiungi un campo di ricerca personalizzato
    public function AddSearchField($handler="dlg",$handlerParams=array(),$module="", $fieldParams = array(), $newRow = true)
    {
        $onSearchScript = "try{ if($$('" . $this->form->GetId() . "')){AA_MainApp.utils.callHandler('".$handler."'," . json_encode($handlerParams) . ",'".$module."');}}catch(msg){console.error(msg)}";

        if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_" . uniqid(time()));
            $this->layout->AddRow($this->curRow);
        }

        if (!isset($fieldParams['name']) || $fieldParams['name'] == "")
            $fieldParams['name'] = "search_desc";
        if (!isset($fieldParams['label']) || $fieldParams['label'] == "")
            $fieldParams['label'] = "Cerca";
        if (!isset($fieldParams['readonly']) || $fieldParams['readonly'] == "")
            $fieldParams['readonly'] = true;
        if (!isset($fieldParams['click']) || $fieldParams['click'] == "")
            $fieldParams['click'] = $onSearchScript;

        $this->curRow->AddCol(new AA_JSON_Template_Search($this->id . "_Field_Search_".$fieldParams['name'], $fieldParams));
    }
    //Aggiungi un campo per l'upload di file
    public function AddFileUploadField($name = "AA_FileUploader", $label = "Sfoglia...", $props = array(), $newRow = true)
    {
        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_" . uniqid(time()));
            $this->layout->AddRow($this->curRow);
        }

        $props['name'] = "AA_FileUploader";
        if ($label == "")
            $props['value'] = "Sfoglia...";
        else
            $props['value'] = $label;
        $props['autosend'] = false;
        if (!isset($props['multiple']) || $props['multiple'] == "")
            $props['multiple'] = false;
        $props['view'] = "uploader";
        $props['link'] = $this->id . "_FileUpload_List";
        $props['layout_id'] = $this->id . "_FileUpload_Layout";
        $props['formData'] = array("file_id" => $name);

        $this->fileUploader_id = $this->id . "_FileUpload_Field";

        $template = new AA_JSON_Template_Layout($this->id . "_FileUpload_Layout", array("type" => "clean", "borderless" => true, "autoheight" => true, ));
        $template->AddRow(new AA_JSON_Template_Generic($this->id . "_FileUpload_Field", $props));
        $template->AddRow(new AA_JSON_Template_Generic($this->id . "_FileUpload_List", array(
            "view" => "list",
            "scroll" => false,
            "autoheight" => true,
            "autoHeight" => 32,
            "type" => "uploader",
            "css" => array("background" => "transparent")
        )
        ));

        if ($props['bottomLabel']) {
            $template->AddRow(new AA_JSON_Template_Template($this->id . "_FileUpload_BottomLabel", array(
                "autoheight" => true,
                "template" => "<span style='font-size: smaller; font-style:italic'>" . $props['bottomLabel'] . "</span>",
                "css" => array("background" => "transparent")
            )
            ));
        }

        $this->curRow->AddCol($template);
    }

    //Aggiungi un campo data
    public function AddDateField($name = "", $label = "", $props = array(), $newRow = true)
    {
        $props['timepick'] = false;
        if (!isset($props['format']) || $props['format'] == "")
            $props['format'] = "%Y-%m-%d";
        $props['stringResult'] = true;
        return $this->AddField($name, $label, "datepicker", $props, $newRow);
    }

    //Aggiungi un generico oggetto
    public function AddGenericObject($obj, $newRow = true)
    {
        if ($obj instanceof AA_JSON_Template_Generic) {
            if ($newRow) {
                $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_" . uniqid(time()));
                $this->layout->AddRow($this->curRow);
            }

            $this->curRow->AddCol($obj);
        }
    }
}
