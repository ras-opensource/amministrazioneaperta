<?php
class AA_SystemChangeCurrentUserPwdDlg extends AA_GenericFormDlg
{
    public function __construct($id = "", $title = "", $formData = array(), $resetData = array(), $applyActions = "", $save_formdata_id = "")
    {
        parent::__construct($id, $title, "", $formData, $resetData, $applyActions, $save_formdata_id);

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->SetWidth("480");
        $this->SetHeight("480");

        $this->SetLabelWidth(150);
        $this->EnableValidation();

        $this->AddTextField("otp", "codice OTP", array("required" => true, "bottomPadding"=>32,"bottomLabel" => "Inserisci il codice OTP che hai ricevuto via email."));
        $this->AddTextField("new_user_pwd", "Nuova password", array("required" => true, "type" => "password", "bottomLabel" => "Inserisci la nuova password."));
        $this->AddTextField("re_new_user_pwd", "Ridigita la nuova password", array("required" => true, "type" => "password", "bottomLabel" => "Reinserisci la nuova password."));

        $this->AddSpacer();
        $this->AddGenericObject(new AA_JSON_Template_Template($id . "_ChangeUserPwdTips", array("type" => "clean", "autoheight" => "true", "template" => "<div style='display: flex; flex-direction: column;'><span>La nuova password deve contenere:</span><ul><li>almeno 12 caratteri</li><li>almeno un numero</li><li>almeno una lettera maiuscola</li><li>almeno una lettera minuscola</li><li>almeno uno dei seguenti caratteri speciali: @$!%*?&</li><li>deve essere diversa dalla vecchia password</li></ul></div>")));
        $this->AddSpacer();

        $this->SetSaveTask("UpdateCurrentUserPwd");
        $this->SetTaskManager("AA_MainApp.taskManager");
        //$this->SetApplybuttonStyle("AA_Button_primary");
        $this->enableRefreshOnSuccessfulSave(false);
        $this->EnableCloseWndOnSuccessfulSave();
    }
}
