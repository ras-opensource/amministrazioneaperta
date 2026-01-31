<?php
class AA_GenericLogDlg extends AA_GenericWindowTemplate
{
    public function __construct($id = "", $title = "Logs", $user = null)
    {
        parent::__construct($id, $title);

        $this->SetWidth("720");
        $this->SetHeight("576");

        //Id oggetto non impostato
        if ($_REQUEST['id'] == "") {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id . "_Log_Box", array("type" => "clean", "template" => "<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>Identificativo oggetto non impostato.</div>")));
            return;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id . "_Log_Box", array("type" => "clean", "template" => "<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>Utente non valido o sessione scaduta.</div>")));
            return;
        }

        if(!isset($_REQUEST['object_class'])) $class="AA_Object_V2";
        else
        {
            if(class_exists($_REQUEST['object_class']))
            {
                $class=$_REQUEST['object_class'];
            }
            else $class="AA_Object_V2";

            AA_Log::Log(__METHOD__." - classe: ".$class,100);
        }

        $object = $class::Load($_REQUEST['id'], $user, false);

        //Invalid object
        if (!$object->IsValid()) {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id . "_Log_Box", array("type" => "clean", "template" => "<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>Oggetto non valido o permessi insufficienti.</div>")));
            return;
        }

        //permessi insufficienti
        if (($object->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) == 0) {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id . "_Log_Box", array("type" => "clean", "template" => "<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>L'utente corrente non ha i permessi per visualizzare i logs dell'oggetto.</div>")));
            return;
        }

        $logs = $object->GetLog();

        $table = new AA_JSON_Template_Generic($id . "_Table", array(
            "view" => "datatable",
            "scrollX" => false,
            "select" => false,
            "columns" => array(
                array("id" => "data", "header" => array("Data", array("content" => "textFilter")), "width" => 150, "css" => array("text-align" => "left")),
                array("id" => "user", "header" => array("<div style='text-align: center'>Utente</div>", array("content" => "selectFilter")), "width" => 120, "css" => array("text-align" => "center")),
                array("id" => "msg", "header" => array("Operazione", array("content" => "textFilter")), "fillspace" => true, "css" => array("text-align" => "left"))
            ),
            "data" => $logs->GetLog()
        ));

        //riquadro di visualizzazione preview pdf
        $this->body->AddRow($table);
        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 38)));
    }

    protected function Update()
    {
        parent::Update();
    }
}
