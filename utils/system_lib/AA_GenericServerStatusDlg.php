<?php
class AA_GenericServerStatusDlg extends AA_GenericWindowTemplate
{
    public function __construct($id = "", $title = "Logs", $user = null)
    {
        parent::__construct($id, $title);

        $this->SetWidth("1280");
        $this->SetHeight("720");

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if (!$user->IsSuperUser()) {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id . "_ServerStatus_Box", array("type" => "clean", "template" => "<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>Utente non autorizzato.</div>")));
            return;
        }

        $section_box=new AA_JSON_Template_Layout("ServerStatus_Box",array("type"=>"space","css"=>"AA_Desktop_Section_Box"));
        $section_box_content=new AA_JSON_Template_Generic("ServerStatus_Box_content",array("view"=>"scrollview","borderless"=>true));
        $section_box_content->AddRowToBody(new AA_JSON_Template_Template("AA_ServerStatus_Details",array("template"=>"#status#","autoheight"=>true,"url"=>"utils/system_ops.php?task=GetServerStatus")));
        $section_box->addRow($section_box_content);

        $this->body->AddRow($section_box);
    }

    protected function Update()
    {
        parent::Update();
    }
}
