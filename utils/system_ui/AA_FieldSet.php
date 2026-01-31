<?php
class AA_FieldSet extends AA_JSON_Template_Generic
{
    protected $formId = "";
    public function GetFormId()
    {
        return $this->formId;
    }
    public function setFormId($val = "")
    {
        $this->formId = $val;
    }

    public function __construct($id = "field_set", $label = "Generic field set", $formId = "", $gravity = 1, $props = array("type" => "clean"))
    {
        if(is_array($props))
        {
            foreach($props as $key=>$val)
            {
                $this->props[$key]=$val;
            }
        }

        $this->props['view'] = "fieldset";
        $this->props['label'] = $label;
        $this->props['id'] = $id;
        $this->props['gravity'] = $gravity;
        $this->layout = new AA_JSON_Template_Layout($id . "_FieldSet_Layout", array("type" => "clean"));
        $this->addRowToBody($this->layout);

        $this->formId = $formId;
    }

    protected $layout = null;
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

    //Aggiungi un campo al field set
    public function AddField($name = "", $label = "", $type = "text", $props = array(), $newRow = true)
    {
        if ($name != "" && $label != "") {
            $props['name'] = $name;
            $props['label'] = $label;

            if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
                $unique = uniqid(time());
                $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_" . $unique);
                $this->layout->AddRow($this->curRow);
            }

            if ($type == "text")
                $this->curRow->AddCol(new AA_JSON_Template_Text($this->GetId() . "_Field_" . $name, $props));
            if ($type == "textarea")
                $this->curRow->AddCol(new AA_JSON_Template_Textarea($this->GetId() . "_Field_" . $name, $props));
            if ($type == "richtext")
                $this->curRow->AddCol(new AA_JSON_Template_Richtext($this->Getid() . "_Field_" . $name, $props));
            if ($type == "ckeditor5")
                $this->curRow->AddCol(new AA_JSON_Template_Ckeditor5($this->GetId() . "_Field_" . $name, $props));
            if ($type == "checkbox")
                $this->curRow->AddCol(new AA_JSON_Template_Checkbox($this->GetId() . "_Field_" . $name, $props));
            if ($type == "select")
                $this->curRow->AddCol(new AA_JSON_Template_Select($this->GetId() . "_Field_" . $name, $props));
            if ($type == "combo")
                $this->curRow->AddCol(new AA_JSON_Template_Combo($this->GetId() . "_Field_" . $name, $props));
            if ($type == "switch")
                $this->curRow->AddCol(new AA_JSON_Template_Switch($this->GetId() . "_Field_" . $name, $props));
            if ($type == "datepicker")
                $this->curRow->AddCol(new AA_JSON_Template_Datepicker($this->GetId() . "_Field_" . $name, $props));
            if ($type == "radio")
                $this->curRow->AddCol(new AA_JSON_Template_Radio($this->GetId() . "_Field_" . $name, $props));
        }
    }

    //aggiungi un campo di ricerca personalizzato
    public function AddSearchField($handler="dlg",$handlerParams=array(),$module="", $fieldParams = array(), $newRow = true)
    {
         if ($this->formId != "")
            $form = $this->formId;
        else
            $form = $this->GetId() . "_Form";

        $onSearchScript = "try{ if($$('" . $form . "')){AA_MainApp.utils.callHandler('".$handler."'," . json_encode($handlerParams) . ",'".$module."');}}catch(msg){console.error(msg)}";

        if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_" . uniqid(time()));
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

        $this->curRow->AddCol(new AA_JSON_Template_Search($this->GetFormId() . "_Field_Search_".$fieldParams['name'], $fieldParams));
    }

    //Aggiungi una nuova sezione
    public function AddSection($name = "New Section", $newRow = true)
    {
        if ($newRow) {
            $unique = uniqid(time());
            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_" . $unique);
            $this->layout->AddRow($this->curRow);
            $this->curRow->AddCol(new AA_JSON_Template_Generic($this->GetId() . "_Section_", array("type" => "section", "template" => $name)));
        } else {
            $this->curRow->AddCol(new AA_JSON_Template_Generic($this->GetId() . "_Section_" . $name, array("type" => "section", "template" => $name)));
        }
    }

    //Aggiungi uno spazio
    public function AddSpacer($newRow = true)
    {
        $unique = uniqid(time());
        if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {

            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_" . $unique);
            $this->layout->AddRow($this->curRow);
        }
        $this->curRow->AddCol(new AA_JSON_Template_Generic($this->GetId() . "_Field_Spacer_" . $unique, array("view" => "spacer")));
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
        if ($this->formId != "")
            $form = $this->formId;
        else
            $form = $this->GetId() . "_Form";
        $onSearchScript = "try{ if($$('" . $form . "')){AA_MainApp.ui.MainUI.structDlg.lastSelectedItem={id: $$('" . $form . "').getValues().id_struct_tree_select};}; AA_MainApp.ui.MainUI.structDlg.show(" . json_encode($taskParams) . "," . json_encode($params) . ");}catch(msg){console.error(msg)}";

        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_" . uniqid(time()));
            $this->layout->AddRow($this->curRow);
        }

        if ($fieldParams['name'] == "")
            $fieldParams['name'] = "struct_desc";
        if ($fieldParams['label'] == "")
            $fieldParams['label'] = "Struttura";
        if ($fieldParams['readonly'] == "")
            $fieldParams['readonly'] = true;
        if ($fieldParams['click'] == "")
            $fieldParams['click'] = $onSearchScript;

        $this->curRow->AddCol(new AA_JSON_Template_Search($this->GetId() . "_Field_Struct_Search", $fieldParams));
    }

    protected $fileUploader_id = "";

    //Aggiungi un campo per l'upload di file
    public function AddFileUploadField($name = "AA_FileUploader", $label = "Sfoglia...", $props = array(), $newRow = true)
    {
        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_" . uniqid(time()));
            $this->layout->AddRow($this->curRow);
        }

        $props['name'] = "AA_FileUploader";
        if ($label == "")
            $props['value'] = "Sfoglia...";
        else
            $props['value'] = $label;
        $props['autosend'] = false;
        if ($props['multiple'] == "")
            $props['multiple'] = false;
        $props['view'] = "uploader";
        $props['link'] = $this->GetId() . "_FileUpload_List";
        $props['layout_id'] = $this->GetId() . "_FileUpload_Layout";
        $props['formData'] = array("file_id" => $name);

        $this->fileUploader_id = $this->GetId() . "_FileUpload_Field";

        $template = new AA_JSON_Template_Layout($this->GetId() . "_FileUpload_Layout", array("type" => "clean", "borderless" => true, "autoheight" => true));
        $template->AddRow(new AA_JSON_Template_Generic($this->GetId() . "_FileUpload_Field", $props));
        $template->AddRow(new AA_JSON_Template_Generic($this->GetId() . "_FileUpload_List", array(
            "view" => "list",
            "scroll" => false,
            "autoheight" => true,
            "minHeight" => 32,
            "type" => "uploader",
            "css" => array("background" => "transparent")
        )
        ));

        if ($props['bottomLabel']) {
            $template->AddRow(new AA_JSON_Template_Template($this->GetId() . "_FileUpload_BottomLabel", array(
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
        if ($props['format'] == "")
            $props['format'] = "%Y-%m-%d";
        $props['stringResult'] = true;
        return $this->AddField($name, $label, "datepicker", $props, $newRow);
    }

    //Aggiungi un generico oggetto
    public function AddGenericObject($obj, $newRow = true)
    {
        if ($obj instanceof AA_JSON_Template_Generic) {
            if ($newRow) {
                $unique = uniqid(time());
                $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_" . $unique);
                $this->layout->AddRow($this->curRow);
            }

            $this->curRow->AddCol($obj);
        }
    }
}
