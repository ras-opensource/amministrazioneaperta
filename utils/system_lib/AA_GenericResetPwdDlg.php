<?php
class AA_GenericResetPwdDlg extends AA_GenericFormDlg
{
    public function ___construct($id = "", $title = "", $formData = array(), $resetData = array(), $applyActions = "", $save_formdata_id = "")
    {
        parent::__construct($id, $title);

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->SetWidth("340");
        $this->SetHeight("400");

        $this->applyActions = $applyActions;
        $this->saveFormDataId = $save_formdata_id;
        $this->formData = $formData;
        if (sizeof($resetData) == 0) $resetData = $formData;
        $this->resetData = $resetData;

        $this->form = new AA_JSON_Template_Form($this->id . "_Form", array(
            "data" => $formData,
        ));

        $this->body->AddRow($this->form);
        $this->layout = new AA_JSON_Template_Layout($id . "_Form_Layout", array("type" => "clean"));
        $this->form->AddRow($this->layout);

        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 10, "css" => array("border-top" => "1px solid #e6f2ff !important;"))));

        $this->AddTextField("email_verification_code","OTP",array("required"=>true,"bottomLabel"=>"Inserisci il codice OTP ricevuto via email."));
        $this->SetSaveTask("User");
    }
}
