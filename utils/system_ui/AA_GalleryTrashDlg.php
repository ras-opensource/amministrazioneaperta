<?php
class AA_GalleryTrashDlg extends AA_GenericFormDlg
{
    public function __construct($id = "", $title = "", $img=null, $formData = array(), $resetData = array(), $applyActions = "", $save_formdata_id = "")
    {
        //AA_Log::Log(__METHOD__." - ".$module,100);

        $form_data=array("id"=>$img->GetProp('id'));
        $form_data["url_name"]=$img->GetProp('url_name');
        if(empty($form_data["url_name"])) $form_data["url_name"]="non condiviso";
        $form_data["categorie"]=$img->GetProp('categorie');

        parent::__construct($id, $title,'',$form_data);
        
        $this->SetWidth("640");
        $this->SetHeight("300");

        //Disattiva il pulsante di reset
        $this->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $this->SetApplyButtonName("Procedi");
        
        $tabledata=array();
        $tabledata[]=array("id_risorsa"=>$img->getProp('id'),"url_name"=>$img->getProp('url_name'),"categorie"=>$img->getProp('categorie'));

        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p></div>";
        $this->AddGenericObject(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));
        $this->AddGenericObject(new AA_JSON_Template_Template("",array("borderless"=>true,"autoheight"=>true,"template"=>"La seguente immagine <b>verra' eliminata definitivamente</b>, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"id_risorsa", "header"=>"id", "width"=>80),
              array("id"=>"url_name", "header"=>"nome condivisione", "fillspace"=>true),
              array("id"=>"categorie", "header"=>"categorie", "fillspace"=>true),
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $this->AddGenericObject($table);

        $this->EnableCloseWndOnSuccessfulSave();
        $this->enableRefreshOnSuccessfulSave(false);
        $this->SetSaveTask("TrashFromGallery");
        $this->SetTaskManager("AA_MainApp.taskManager");
        $this->SetSaveTaskParams(array("id"=>$img->GetProp("id"),"refresh"=>1,"refresh_obj_id"=>$_REQUEST['refresh_obj_id']));
    }
}
