<?php
class AA_SystemCurrentUserProfileDlg extends AA_GenericWindowTemplate
{
    public function __construct($id = "", $title = "")
    {
        parent::__construct($id, $title, "");

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->SetWidth("600");
        $this->SetHeight("480");

        $layout_tab = new AA_JSON_Template_Layout("", array("type" => "clean", "minWidth" => 500));

        $user = AA_User::GetCurrentUser();

        //Nome
        $value = $user->GetNome();
        $nome = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Nome:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        )
        );

        //email
        $value = $user->GetEmail();
        $email = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Email:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        )
        );

        //Cognome
        $value = $user->GetCognome();
        $cognome = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Cognome:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        )
        );

        //ruolo
        $value = $user->GetRuolo();
        $ruolo = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Ruolo:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        )
        );

        //nome profilo
        $value = $user->GetUsername();
        $profilo = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Profilo corrente:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        )
        );

        //struttura
        $struttura = "";
        if (AA_Const::AA_ENABLE_LEGACY_DATA) {
            $struct = $user->GetStruct();
            $value = "Nessuna";
            if ($struct->GetAssessorato(true) > 0) {
                $value = $struct->GetAssessorato();
            }
            if ($struct->GetDirezione(true) > 0) {
                $value .= "<br>" . $struct->GetDirezione();
            }

            if ($struct->GetServizio(true) > 0) {
                $value .= "<br>" . $struct->GetServizio();
            }

            $struttura = new AA_JSON_Template_Template("", array(
                "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "gravity" => 1,
                "data" => array("title" => "Struttura:", "value" => $value),
                "css" => array("border-bottom" => "1px solid #dadee0 !important")
            )
            );
        }

        //Abilitazioni
        $value = $user->GetLabelFlags();
        $abilitazioni = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Abilitazioni:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        )
        );

        $row = new AA_JSON_Template_Layout("", array("type" => "clean", "css" => array("border-bottom" => "1px solid #dadee0 !important")));
        $row->AddCol($profilo);
        $row->AddCol($email);
        $layout_tab->addRow($row);

        $row = new AA_JSON_Template_Layout("", array("type" => "clean", "css" => array("border-bottom" => "1px solid #dadee0 !important")));
        $row->AddCol($nome);
        $row->AddCol($cognome);
        $row->AddCol($ruolo);
        $layout_tab->addRow($row);

        if (AA_Const::AA_ENABLE_LEGACY_DATA) {
            $row = new AA_JSON_Template_Layout("", array("type" => "clean", "css" => array("border-bottom" => "1px solid #dadee0 !important")));
            $row->AddRow($struttura);
            $layout_tab->addRow($row);
        }

        $row = new AA_JSON_Template_Layout("", array("type" => "clean", "css" => array("border-bottom" => "1px solid #dadee0 !important")));
        $row->AddRow($abilitazioni);
        $layout_tab->addRow($row);

        //toolbar
        $toolbar = new AA_JSON_Template_Toolbar("", array("height" => 38));

        //pwd change
        $pwd_btn = new AA_JSON_Template_Generic("", array(
            "view" => "button",
            "type" => "icon",
            "icon" => "mdi mdi-key-chain",
            "label" => "Reimposta password",
            "align" => "right",
            "css" => "webix_primary ",
            "width" => 190,
            "tooltip" => "Cambia la password di accesso al sistema",
            "click" => "AA_MainApp.utils.callHandler('dlg', {task:\"GetChangeCurrentUserPwdDlg\",taskManager: AA_MainApp.taskManager, module: \"\"},'')"
        )
        );

        //profile change
        $profile_btn = new AA_JSON_Template_Generic("", array(
            "view" => "button",
            "type" => "icon",
            "icon" => "mdi mdi-account-box-multiple",
            "label" => "Cambia profilo",
            "align" => "right",
            "css" => "webix_primary ",
            "width" => 190,
            "tooltip" => "Cambia il profilo utente corrente",
            "click" => "AA_MainApp.utils.callHandler('dlg', {task:\"GetChangeCurrentUserProfileDlg\",taskManager: AA_MainApp.taskManager, module: \"\"},'')"
        )
        );

        $toolbar->AddElement(new AA_JSON_Template_Generic());
        $toolbar->AddElement($pwd_btn);
        $toolbar->AddElement(new AA_JSON_Template_Generic());
        $toolbar->AddElement($profile_btn);
        $toolbar->AddElement(new AA_JSON_Template_Generic());

        $row = new AA_JSON_Template_Layout("", array("type" => "clean"));
        $row->AddRow($toolbar);

        $layout_tab->AddRow(new AA_JSON_Template_Generic("", array("height" => 20)));
        $layout_tab->AddRow($row);
        $layout_tab->AddRow(new AA_JSON_Template_Generic("", array("height" => 20)));

        $this->AddView($layout_tab);
    }
}
