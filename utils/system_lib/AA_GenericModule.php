<?php
class AA_GenericModule
{
    //Id default
    const AA_ID_MODULE = "AA_MODULE_GENERIC";

    //Classe per la gestione degli oggetti del modulo
    const AA_MODULE_OBJECTS_CLASS = "AA_Object_V2";

    //prefisso per la generazione degli identificativi degli oggetti dell'interfaccia
    const AA_UI_PREFIX = "AA_Generic";

    //id ui sezione pubblicate
    const AA_UI_PUBBLICATE_BOX = "Pubblicate_Content_Box";

    //id ui lista sezione pubblicate
    const AA_UI_PUBBLICATE_LISTBOX = "Pubblicate_List_Box";

    //id ui sezione bozze
    const AA_UI_BOZZE_BOX = "Bozze_Content_Box";

    //id ui lista sezione bozze
    const AA_UI_BOZZE_LISTBOX = "Bozze_List_Box";

    //id ui sezione dettaglio
    const AA_UI_DETAIL_BOX = "Detail_Content_Box";

    //id ui sezione revisionate
    const AA_UI_REVISIONATE_BOX = "Revisionate_Content_Box";

    //id ui sezione bozze
    const AA_ID_SECTION_BOZZE = "Bozze";

    //id ui sezione pubblicate
    const AA_ID_SECTION_PUBBLICATE = "Pubblicate";

    //id ui sezione dettaglio
    const AA_ID_SECTION_DETAIL = "Dettaglio";

    //dicitura interfaccia sezione dettaglio
    const AA_UI_SECTION_DETAIL_NAME = "Dettaglio";

    //dicitura interfaccia sezione bozze
    const AA_UI_SECTION_BOZZE_NAME = "Bozze";

    //dicitura interfaccia sezione bozze cestinate
    const AA_UI_SECTION_BOZZE_CESTINATE_NAME = "Bozze cestinate";

    //dicitura interfaccia sezione pubblicate
    const AA_UI_SECTION_PUBBLICATE_NAME = "Pubblicate";

    //dicitura interfaccia sezione pubblicate cestinate
    const AA_UI_SECTION_PUBBLICATE_CESTINATE_NAME = "Pubblicate cestinate";

    //Task per la gestione dei dialoghi standard
    const AA_UI_TASK_PUBBLICATE_FILTER_DLG = "GetGenericPubblicateFilterDlg";
    const AA_UI_TASK_BOZZE_FILTER_DLG = "GetGenericBozzeFilterDlg";
    const AA_UI_TASK_REASSIGN_DLG = "GetGenericReassignDlg";
    const AA_UI_TASK_PUBLISH_DLG = "GetGenericPublishDlg";
    const AA_UI_TASK_TRASH_DLG = "GetGenericTrashDlg";
    const AA_UI_TASK_RESUME_DLG = "GetGenericResumeDlg";
    const AA_UI_TASK_DELETE_DLG = "GetGenericDeleteDlg";
    const AA_UI_TASK_ADDNEW_DLG = "GetGenericAddNewDlg";
    const AA_UI_TASK_MODIFY_DLG = "GetGenericModifyDlg";
    const AA_UI_TASK_SAVEASPDF_DLG = "GetGenericSaveAsPdfDlg";
    const AA_UI_TASK_SAVEASCSV_DLG = "GetGenericSaveAsCsvDlg";
    //------------------------------------

    //Caricamento multiplo
    const AA_UI_TASK_ADDNEWMULTI_DLG = "GetGenericAddNewMultiDlg";
    //--------------------------------------

    //---------Task azioni standard-------
    const AA_UI_TASK_TRASH = "GenericTrashObject";
    const AA_UI_TASK_RESUME = "GenericResumeObject";
    const AA_UI_TASK_PUBLISH = "GenericPublishObject";
    const AA_UI_TASK_REASSIGN = "GenericReassignObject";
    const AA_UI_TASK_DELETE = "GenericDeleteObject";
    //------------------------------------
    
    protected $taskManagerUrl = "system_ops.php";
    public function GetTaskManagerUrl()
    {
        return $this->taskManagerUrl;
    }

    protected $sections = array();
    public function AddSection($section)
    {
        if ($section instanceof AA_GenericModuleSection) $this->sections[$section->GetId()] = $section;
    }
    public function GetSection($section = "AA_GENERIC_MODULE_SECTION")
    {
        if ($this->sections[$section]) return $this->sections[$section];
        else return new AA_GenericModuleSection();
    }

    //templates degli oggetti
    protected $aObjectTemplates=array();
    public function AddObjectTemplate($idObject="",$template="")
    {
        if($idObject!="")
        {
            if(method_exists($this,$template))
            {
                $this->aObjectTemplates[$idObject]=$template;
                return true;
            }
            else
            {
                AA_Log::Log(__METHOD__." - Template non trovato (".$template.") per l'oggetto: ".$idObject,100);
            }
        }
        else
        {
            AA_Log::Log(__METHOD__." - id oggetto non valido ".$idObject,100);
        }
        
        return false;
    }
    public function DelObjectTemplate($idObject="")
    {
        if($idObject !="" && isset($this->aObjectTemplates[$idObject])) unset($this->aObjectTemplates[$idObject]);
    }
    public function GetObjectTemplate($idObject="")
    {
        if($idObject !="" && isset($this->aObjectTemplates[$idObject])) return $this->aObjectTemplates[$idObject];

        return "";
    }

    //Restituisce le sezioni del modulo
    public function GetSections($format = "raw")
    {
        if ($format == "raw") return $this->sections;
        if ($format == "array") {
            $return = array();
            foreach ($this->sections as $curSection) {
                $return[] = $curSection->toArray();
            }
        }

        if ($format == "string") {
            $return = array();
            foreach ($this->sections as $curSection) {
                $return[] = $curSection->toArray();
            }

            $return = json_encode($return);
        }

        if ($format == "base64") {
            $return = array();
            foreach ($this->sections as $curSection) {
                $return[] = $curSection->toArray();
            }

            $return = base64_encode(json_encode($return));
        }

        return $return;
    }

    //Restituisce le flags collegate al modulo
    protected $flags = null;
    public function GetFlags()
    {
        if (!is_array($this->flags)) {
            if ($this->id != "AA_MODULE_GENERIC") {
                $db = new AA_Database();
                $query = "SELECT flags FROM " . AA_Const::AA_DBTABLE_MODULES;
                $query .= " WHERE id_modulo like '" . addslashes($this->id) . "' LIMIT 1";

                if (!$db->query($query)) {
                    AA_Log::Log(__METHOD__ . " - ERRORE - " . $db->GetErrorMessage(), 100);
                    $this->flags = array();
                    return array();
                }

                if ($db->GetAffectedRows() > 0) {
                    foreach ($db->GetResultSet() as $key => $curRow) {
                        $this->flags = json_decode($curRow, true);
                        if (!is_array($this->flags)) {
                            if (json_last_error() > 0) AA_Log::Log(__METHOD__ . " - module flags:" . print_r($this->flags, true) . " - error: " . json_last_error(), 100);
                            $this->flags = array();
                        }
                    }
                } else $this->flags = array();
            } else $this->flags = array();
        }

        return $this->flags;
    }

    //Item templates
    protected $aSectionItemTemplates = null;
    public function SetSectionItemTemplate($section = "", $template = "")
    {
        if (!is_array($this->aSectionItemTemplates)) {
            $this->aSectionItemTemplates = array();
        }
        $this->aSectionItemTemplates[$section] = $template;
    }
    public function GetSectionItemTemplate($section = "")
    {
        if (is_array($this->aSectionItemTemplates)) return $this->aSectionItemTemplates[$section];
        else return "";
    }

    //Task sections
    public function Task_GetSections($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $sTaskLog .= $this->GetSections("base64");
        $sTaskLog .= "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Task layout
    public function Task_GetLayout($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json'>";
        $content = $this->TemplateLayout();
        $sTaskLog .= $content;
        $sTaskLog .= "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Generic AMAAI Dlg
    public function Task_AMAAI_Start($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $module = AA_AMAAI::GetInstance();

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $sTaskLog .=  $this->Template_GenericAMAAIDlg()->toBase64();
        $sTaskLog .= "</content>";
        $task->SetLog($sTaskLog);
        return true;
    }

    public function Template_GenericAMAAIDlg()
    {
        $module = AA_AMAAI::GetInstance();
        $template = $module->TemplateLayout();
        $template->SetWidth(720);
        $template->SetHeight(580);

        $template->AddView($module->TemplateStart());

        return $template;
    }

    //Restituisce la configurazione sulla sidebar
    public function GetSideBarConfig($format = "raw")
    {

        $conf = array();
        $conf['id'] = $this->sSideBarId;
        $conf['icon'] = $this->sSideBarIcon;
        $conf['value'] = $this->sSideBarName;
        $conf['tooltip'] = $this->sSideBarTooltip;
        $conf['module'] = $this->id;

        if ($format == "string" || $format == "json") return json_encode($conf);
        if ($format == "base64") return base64_encode(json_encode($conf));

        return $conf;
    }

    //sidebar id
    protected $sSideBarId = "";
    public function GetSideBarId()
    {
        return $this->sSideBarId;
    }
    public function SetSideBarId($var = "")
    {
        $this->sSideBarId = $var;
    }

    //sidebar icon
    protected $sSideBarIcon = "mdi mdi-home";
    public function GetSideBarIcon()
    {
        return $this->sSideBarIcon;
    }
    public function SetSideBarIcon($var = "")
    {
        $this->sSideBarIcon = $var;
    }

    //sidebar name
    protected $sSideBarName = "";
    public function GetSideBarName()
    {
        return $this->sSideBarName;
    }
    public function SetSideBarName($var = "")
    {
        $this->sSideBarName = $var;
    }

    //sidebar tooltip
    protected $sSideBarTooltip = "";
    public function GetSideBarTooltip()
    {
        return $this->sSideBarTooltip;
    }
    public function SetSideBarTooltip($var = "")
    {
        $this->sSideBarTooltip = $var;
    }

    protected $oUser = null;
    public function GetUser()
    {
        return $this->oUser;
    }

    public static function RegisterPublicService()
    {
        //da specializzare nel modulo derivato

        //return AA_Platform::RegisterPublicService("<service_name>","<service_handler>");
    }

    public static function PublicServiceHandler()
    {
        //da specializzare nel modulo derivato
        return true;
    }

    public function __construct($user = null, $bDefaultSections = true)
    {

        if (!($user instanceof AA_User) || !$user->isCurrentUser()) $user = AA_User::GetCurrentUser();

        $this->oUser = $user;

        $this->SetId(static::AA_ID_MODULE);

        //Task manager url
        $platform = AA_Platform::GetInstance($user);
        $this->taskManagerUrl = $platform->GetModuleTaskManagerURL($this->id);

        //Registrazione dei task-------------------
        $taskManager = $this->GetTaskManager();

        $taskManager->RegisterTask("GetSections");
        $taskManager->RegisterTask("GetLayout");
        $taskManager->RegisterTask("GetActionMenu");
        $taskManager->RegisterTask("GetNavbarContent");
        $taskManager->RegisterTask("GetSideMenuContent");
        $taskManager->RegisterTask("GetSectionContent");
        $taskManager->RegisterTask("GetObjectContent");
        $taskManager->RegisterTask("GetObjectData");
        $taskManager->RegisterTask("GetLogDlg");
        $taskManager->RegisterTask("PdfExport");
        $taskManager->RegisterTask("CsvExport");
        $taskManager->RegisterTask("AMAAI_Start");

        if ($bDefaultSections) {
            #Sezioni default

            //Schede pubblicate
            $navbarTemplate = array($this->TemplateNavbar_Bozze(1, true)->toArray());
            $section = new AA_GenericModuleSection(static::AA_ID_SECTION_PUBBLICATE, static::AA_UI_SECTION_PUBBLICATE_NAME, true, static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX, $this->GetId(), true, true, false, true, "mdi-certificate");
            $section->SetNavbarTemplate($navbarTemplate);
            $this->AddSection($section);

            //Bozze
            $navbarTemplate = $this->TemplateNavbar_Pubblicate(1, true)->toArray();
            $section = new AA_GenericModuleSection(static::AA_ID_SECTION_BOZZE, static::AA_UI_SECTION_BOZZE_NAME, true, static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX, $this->GetId(), false, true, false, true."mdi-file-document-edit");
            $section->SetNavbarTemplate($navbarTemplate);
            $this->AddSection($section);

            //dettaglio
            $navbarTemplate = $this->TemplateNavbar_Back(1, true)->toArray();
            $section = new AA_GenericModuleSection(static::AA_ID_SECTION_DETAIL, static::AA_UI_SECTION_DETAIL_NAME, false, static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX, $this->GetId(), false, true, true, true);
            $section->SetNavbarTemplate($navbarTemplate);
            $this->AddSection($section);
            #-------------------------------------------
        }

        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL, array(array("id" => static::AA_UI_PREFIX . "_" . static::AA_ID_SECTION_DETAIL . "_Generale_Tab", "value" => "Generale", "tooltip" => "Dati generali", "template" => "TemplateGenericDettaglio_Generale_Tab")));

        return;
    }

    protected $id = "AA_MODULE_GENERIC";
    public function GetId()
    {
        return $this->id;
    }

    //Imposta l'identificativo
    protected function SetId($newId = "")
    {
        if ($newId != "") $this->id = $newId;
    }

    protected static $oTaskManager = null;

    //Restituisce il task manager del modulo
    public function GetTaskManager()
    {
        if (self::$oTaskManager == null) {
            AA_Log::Log(__METHOD__ . " - istanzio il task manager.");
            self::$oTaskManager = new AA_GenericModuleTaskManager($this, $this->GetUser());
        }

        return self::$oTaskManager;
    }

    //Restituisce la lista delle schede pubblicate 
    protected function GetDataGenericSectionPubblicate_List($params = array(), $customFilterFunction = "GetDataSectionPubblicate_CustomFilter", $customTemplateDataFunction = "GetDataSectionPubblicate_CustomDataTemplate")
    {
        $templateData = array();

        $parametri = array("status" => AA_Const::AA_STATUS_PUBBLICATA);
        if (isset ($params['cestinate']) && $params['cestinate'] == 1) {
            $parametri['status'] |= AA_Const::AA_STATUS_CESTINATA;
        }

        if (isset($params['revisionate']) && $params['revisionate'] == 1) {
            $parametri['status'] |= AA_Const::AA_STATUS_REVISIONATA;
        }

        if (isset($params['page']) && $params['page'] > 0) $parametri['from'] = ($params['page'] - 1) * $params['count'];
        if (isset($params['id_assessorato']) && $params['id_assessorato'] != "") $parametri['id_assessorato'] = $params['id_assessorato'];
        if (isset($params['id_direzione']) && $params['id_direzione'] != "") $parametri['id_direzione'] = $params['id_direzione'];
        if (isset($params['id_servizio']) && $params['id_servizio'] != "") $parametri['id_servizio'] = $params['id_servizio'];
        if (isset($params['id']) && $params['id'] != "") $parametri['id'] = $params['id'];
        if (isset($params['nome']) && $params['nome'] != "") $parametri['nome'] = $params['nome'];

        //Richiama la funzione custom per la personalizzazione dei parametri
        if (function_exists($customFilterFunction)) $parametri = array_merge($parametri, $customFilterFunction($params));
        if (method_exists($this, $customFilterFunction)) $parametri = array_merge($parametri, $this->$customFilterFunction($params));

        //Richiama la classe di gestione degli oggetti gestiti dal modulo
        $objectClass = static::AA_MODULE_OBJECTS_CLASS;
        if (class_exists($objectClass)) {
            if (method_exists($objectClass, "Search")) $data = $objectClass::Search($parametri, $this->oUser);
            else {
                AA_Log::Log(__METHOD__ . " Errore: Funzione di ricerca non definita ($objectClass::Search)", 100);
                $data[1] = array();
                $data[0] = 0;
            }
        } else {
            AA_Log::Log(__METHOD__ . " Errore: Classe di gestione dei moduli non definita ($objectClass)", 100);
            $data[1] = array();
            $data[0] = 0;
        }

        foreach ($data[1] as $id => $object) {
            $struct = $object->GetStruct();
            $userCaps = $object->GetUserCaps($this->oUser);
            $struttura_gest = $struct->GetAssessorato();
            if ($struct->GetDirezione(true) > 0) $struttura_gest .= " -> " . $struct->GetDirezione();
            if ($struct->GetServizio(true) > 0) $struttura_gest .= " -> " . $struct->GetServizio();

            #Stato
            if ($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) $status = "bozza";
            if ($object->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $status = "pubblicata";
            if ($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $status .= " revisionata";
            if ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $status .= " cestinata";

            #Dettagli
            if (($userCaps & AA_Const::AA_PERMS_PUBLISH) > 0 && $object->GetAggiornamento() != "") {
                //Aggiornamento
                $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento(true) . "</span>&nbsp;";

                //utente e log
                $lastLog = $object->GetLog()->GetLastLog();
                $details .= "<span class='AA_Label AA_Label_LightBlue' title=\"Nome dell'utente che ha compiuto l'ultima azione - Fai click per visualizzare il log delle azioni\"><span class='mdi mdi-account' onClick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetLogDlg', 'params': {id: " . $object->GetId() . ",object_class:'".get_class($object)."'}},'" . $this->GetId() . "');\">" . $lastLog['user'] . "</span>&nbsp;";

                //id
                $details .= "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
            } else {
                if ($object->GetAggiornamento() != "") $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento(true) . "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
            }

            if (($userCaps & AA_Const::AA_PERMS_WRITE) == 0) $details .= "&nbsp;<span class='AA_Label AA_Label_LightBlue' title=\" L'utente corrente non può apportare modifiche all'organismo\"><span class='mdi mdi-pencil-off'></span>&nbsp; sola lettura</span>";

            $object_name=$object->GetName();
            if(strlen($object_name) > 190) $object_name=mb_substr($object_name,0,190)."...";
            $result = array(
                "id" => $object->GetId(),
                "tags" => "",
                "aggiornamento" => $object->GetAggiornamento(),
                "denominazione" => $object_name,
                "pretitolo" => "",
                "sottotitolo" => $struttura_gest,
                "stato" => $status,
                "dettagli" => $details,
                "module_id" => $this->GetId()
            );

            if (method_exists($this, $customTemplateDataFunction)) {
                $result=$this->$customTemplateDataFunction($result, $object);
                if($result['pretitolo'] !="" && strpos($result['pretitolo'],"span") === false)
                {
                    $result['pretitolo']="<span class='AA_Label AA_Label_Blue_Simo'>".$result['pretitolo']."</span>";
                }
                $templateData[] = $result;
            } else if (function_exists($customTemplateDataFunction)) {
                $templateData[] = $customTemplateDataFunction($result, $object);
            } else {
                $templateData[] = $result;
            }
        }

        //AA_Log::Log(__METHOD__." - numcount: ".$data[0],100);
        return array($data[0], $templateData);
    }

    //Restituisce la lista delle schede pubblicate (dati - da specializzare)
    public function GetDataSectionPubblicate_List($params = array())
    {
        return $this->GetDataGenericSectionPubblicate_List($params);
    }

    //Layout del modulo
    protected function TemplateGenericLayout()
    {
        $template = new AA_JSON_Template_Multiview(static::AA_UI_PREFIX . "_module_layout", array("type" => "clean", "fitBiggest" => "true"));
        foreach ($this->GetSections() as $curSection) {
            $template->addCell(new AA_JSON_Template_Template($curSection->GetViewId(), array("name" => $curSection->GetName(), "type" => "clean", "template" => "", "initialized" => false, "refreshed" => false)));
        }

        return $template;
    }

    //Layout del modulo (da specializzare)
    public function TemplateLayout()
    {
        return $this->TemplateGenericLayout();
    }

    //Template generic section placeholder
    protected function TemplateGenericSection_Placeholder()
    {
        $content = new AA_JSON_Template_Template(
            static::AA_UI_PREFIX . "_Placeholder_Content",
            array(
                "type" => "clean",
                "template" => "placeholder"
            )
        );

        return $content;
    }

    //Template section placeholder
    public function TemplateSection_Placeholder()
    {
        return $this->TemplateGenericSection_Placeholder();
    }

    //Task Generic Action Menù
    protected function Task_GetGenericActionMenu($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $content = "";

        switch ($_REQUEST['section']) {
            case static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX:
                $content = $this->TemplateActionMenu_Bozze();
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX:
                $content = $this->TemplateActionMenu_Pubblicate();
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX:
                $content = $this->TemplateActionMenu_Detail();
                break;
            default:
                $sections=$this->GetSections();
                $content = new AA_JSON_Template_Generic();

                foreach($sections as $curSection)
                {
                    if($curSection->GetViewId()==$_REQUEST['section']) $content=$curSection->TemplateActionMenu();
                }
                break;
        }

        if ($content != "") $sTaskLog .= $content->toBase64();

        $sTaskLog .= "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Task action menù (da specializzare)
    public function Task_GetActionMenu($task)
    {
        return $this->Task_GetGenericActionMenu($task);
    }

    //Template bozze context menu
    protected function TemplateGenericActionMenu_Bozze()
    {

        $menu = new AA_JSON_Template_Generic(
            "AA_ActionMenuBozze",
            array(
                "view" => "contextmenu",
                "data" => array(array(
                    "id" => "refresh_bozze",
                    "value" => "Aggiorna",
                    "icon" => "mdi mdi-reload",
                    "module_id" => $this->GetId(),
                    "handler" => "refreshUiObject",
                    "handler_params" => array(static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX, true)
                ))
            )
        );

        return $menu;
    }

    //Task NavBarContent
    protected function Task_GetGenericNavbarContent($task, $params = array())
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $content = array();
        switch ($params['section']) {
            case static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX:
                $content[] = $this->TemplateNavbar_Pubblicate(1, true)->toArray();
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX:
                $content[] = $this->TemplateNavbar_Bozze(1, true)->toArray();
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX:
                $content[] = $this->TemplateNavbar_Back(1, true)->toArray();
                break;
            default:
                $content[] = $this->TemplateNavbar_Pubblicate(1, true)->toArray();
        }

        $spacer = new AA_JSON_Template_Generic("navbar_spacer");
        $content[] = $spacer->toArray();

        $sTaskLog .= base64_encode(json_encode($content)) . "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Task NavBarContent
    public function Task_GetNavbarContent($task)
    {
        return $this->Task_GetGenericNavbarContent($task, $_REQUEST);
    }

    //Task get side menu content
    public function Task_GetSideMenuContent($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $content = array();
        $num=1;
        foreach($this->sections as $curSection)
        {
            $content[]=array("id"=>$num,"value"=>$curSection->GetName(),"icon"=>$curSection->GetIcon(),"type"=>"section","section"=>$curSection->GetId());
            $num++;
        }

        //logout
        $content[]=array("id"=>$num,"value"=>"Esci","icon"=>"mdi mdi-logout","type"=>"task","task"=>"logout");
        
        //Codifica il contenuto in base64
        $sTaskLog .= base64_encode(json_encode($content)) . "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Task Generico di aggiunta elemento
    public function Task_GenericAddNew($task, $params = array(), $bSaveData = true)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        if (class_exists(static::AA_MODULE_OBJECTS_CLASS)) 
        {
            if (method_exists(static::AA_MODULE_OBJECTS_CLASS, "AddNew")) {
                $objectClass = static::AA_MODULE_OBJECTS_CLASS;
                $object = new $objectClass(0, $this->oUser);
                if (!$object->IsValid()) {
                    AA_Log::Log(__METHOD__ . " - ERRORE: L'utente corrente non ha i permessi per aggiungere nuovi elementi di tipo (" . static::AA_MODULE_OBJECTS_CLASS . ")", 100);
                    $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi di tipo (" . static::AA_MODULE_OBJECTS_CLASS . ")");
                    $sTaskLog = "<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per aggiugere nuovi elementi</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                }

                //Popolamento oggetto
                $object->Parse($params);
                $object->SetStruct($this->oUser->GetStruct());

                if (!$objectClass::AddNew($object, $this->oUser, $bSaveData)) {
                    $task->SetError(AA_Log::$lastErrorLog);
                    $sTaskLog = "<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (" . AA_Log::$lastErrorLog . ")</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                }

                $sTaskLog = "<status id='status' id_Rec='" . $object->GetId() . "' action='showDetailView' action_params='" . json_encode(array("id" => $object->GetId())) . "'>0</status><content id='content'>";
                $sTaskLog .= "Elemento aggiunto con successo (identificativo: " . $object->GetId() . ")";
                $sTaskLog .= "</content>";

                $task->SetLog($sTaskLog);

                return true;
            } else {
                AA_Log::Log(__METHOD__ . " - ERRORE: Metodo di aggiunta nuovi elementi (AddNew) non definito nella classe di gestione degli elementi (" . static::AA_MODULE_OBJECTS_CLASS . ")", 100);
                $task->SetError(AA_Log::$lastErrorLog);
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (" . AA_Log::$lastErrorLog . ")</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            AA_Log::Log(__METHOD__ . " - ERRORE: Classe di gestione degli elementi non trovata (" . static::AA_MODULE_OBJECTS_CLASS . ")", 100);
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (" . AA_Log::$lastErrorLog . ")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Task Generic Update object
    public function Task_GenericUpdateObject($task, $params = array(), $bSaveData = true)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;
            $object = new $objectClass($params['id'], $this->oUser);
            if (!$object->isValid()) {
                $task->SetError("Identificativo oggetto non valido: " . $params['id']);
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Identificativo oggetto non valido: " . $params['id'] . "</error>";
                $task->SetLog($sTaskLog);

                return false;
            }

            if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
                $task->SetError("L'utente corrente (" . $this->oUser->GetName() . ") non ha i privileggi per modificare l'oggetto: " . $object->GetName());
                $sTaskLog = "<status id='status'>-1</status><error id='error'>L'utente corrente (" . $this->oUser->GetName() . ") non ha i privileggi per modificare l'oggetto: " . $object->GetName() . "</error>";
                $task->SetLog($sTaskLog);

                return false;
            }

            //Aggiorna i dati
            $object->Parse($params);

            //Salva i dati
            if (!$object->Update($this->oUser, $bSaveData)) {
                $task->SetError(AA_Log::$lastErrorLog);
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (" . AA_Log::$lastErrorLog . ")</error>";
                $task->SetLog($sTaskLog);

                return false;
            }

            $sTaskLog = "<status id='status'>0</status><content id='content'>";
            $sTaskLog .= "Dati aggiornati con successo.";
            $sTaskLog .= "</content>";

            $task->SetLog($sTaskLog);

            return true;
        } else {
            AA_Log::Log(__METHOD__ . " - ERRORE: Classe di gestione degli elementi non trovata (" . static::AA_MODULE_OBJECTS_CLASS . ")", 100);
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (" . AA_Log::$lastErrorLog . ")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Task Generic Update object
    public function Task_GetLogDlg($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;
            $object = new $objectClass($_REQUEST['id'], $this->oUser);
            if (!$object->isValid()) {
                $task->SetError("Identificativo oggetto non valido: " . $_REQUEST['id']);
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Identificativo oggetto non valido: " . $_REQUEST['id'] . "</error>";
                $task->SetLog($sTaskLog);

                return false;
            }

            if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
                $task->SetError("L'utente corrente (" . $this->oUser->GetName() . ") non ha i privileggi per modificare l'oggetto: " . $object->GetName());
                $sTaskLog = "<status id='status'>-1</status><error id='error'>L'utente corrente (" . $this->oUser->GetName() . ") non ha i privileggi per modificare l'oggetto: " . $object->GetName() . "</error>";
                $task->SetLog($sTaskLog);

                return false;
            }

            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GenericLogDlg($object),true);
            return true;

        } else {
            AA_Log::Log(__METHOD__ . " - ERRORE: Classe di gestione degli elementi non trovata (" . static::AA_MODULE_OBJECTS_CLASS . ")", 100);
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (" . AA_Log::$lastErrorLog . ")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    public function Template_GenericLogDlg($object=null)
    {
        $wnd=new AA_GenericWindowTemplate(uniqid(),"Logs utente",$this->id);

        $wnd->SetWidth("720");
        $wnd->SetHeight("576");

        $logs = $object->GetLog();

        AA_Log::Log(__METHOD__." - logs:".print_r($logs,true),100);

        $table = new AA_JSON_Template_Generic(uniqid(), array(
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
        $wnd->AddView($table);
        $wnd->AddView(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 38)));

        return $wnd;
    }

    //Template object trash dlg
    public function Template_GetGenericObjectTrashDlg($params, $saveTask = "TrashObject")
    {
        $id = static::AA_UI_PREFIX . "_TrashDlg";

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>La classe di gestione degli oggetti non è stata trovata.</p>")));
            $wnd->SetWidth(380);
            $wnd->SetHeight(115);

            return $wnd;
        }

        //lista oggetti da cestinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object->GetName();
                }
            }

            //Esiste almeno un organismo che può essere cestinato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $forms_data['ids'] = json_encode(array_keys($ids_final));
                $wnd = new AA_GenericFormDlg($id, "Cestina", $this->id, $forms_data, $forms_data);

                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata = array();
                foreach ($ids_final as $id_org => $desc) {
                    $tabledata[] = array("Denominazione" => $desc);
                }

                if (sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "I seguenti " . sizeof($ids_final) . " elementi verranno cestinati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "Il seguente elemento verrà cestinato, vuoi procedere?")));

                $table = new AA_JSON_Template_Generic($id . "_Table", array(
                    "view" => "datatable",
                    "scrollX" => false,
                    "autoConfig" => true,
                    "select" => false,
                    "data" => $tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask($saveTask);
            } else {
                $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>L'utente corrente non ha i permessi per cestinare gli elementi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }

            return $wnd;
        }
    }

    //Template object trash dlg
    public function Template_GetGenericObjectDeleteDlg($params, $saveTask = "GenericDeleteObject")
    {
        $id = static::AA_UI_PREFIX . "_DeleteDlg";

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>La classe di gestione degli oggetti non è stata trovata.</p>")));
            $wnd->SetWidth(380);
            $wnd->SetHeight(115);

            return $wnd;
        }

        //lista oggetti da cestinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object->GetName();
                }
            }

            //Esiste almeno un organismo che può essere cestinato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $forms_data['ids'] = json_encode(array_keys($ids_final));
                $wnd = new AA_GenericFormDlg($id, "Elimina", $this->id, $forms_data, $forms_data);

                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata = array();
                foreach ($ids_final as $id_org => $desc) {
                    $tabledata[] = array("Denominazione" => $desc);
                }

                if (sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "I seguenti " . sizeof($ids_final) . " elementi verranno eliminati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "Il seguente elemento verrà eliminato, vuoi procedere?")));

                $table = new AA_JSON_Template_Generic($id . "_Table", array(
                    "view" => "datatable",
                    "scrollX" => false,
                    "autoConfig" => true,
                    "select" => false,
                    "data" => $tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask($saveTask);
            } else {
                $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>L'utente corrente non ha i permessi per cestinare gli elementi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }

            return $wnd;
        }
    }

    //Template generic resume dlg
    public function Template_GetGenericResumeObjectDlg($params = array(), $saveTask = "ResumeObject")
    {
        $id = static::AA_UI_PREFIX . "_ResumeDlg";

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>La classe di gestione degli oggetti non è stata trovata.</p>")));
            $wnd->SetWidth(380);
            $wnd->SetHeight(115);

            return $wnd;
        }

        //lista elementi da ripristinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object->GetName();
                }
            }

            //Esiste almeno un organismo che può essere ripristinato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $forms_data['ids'] = json_encode(array_keys($ids_final));

                $wnd = new AA_GenericFormDlg($id, "Ripristina", $this->id, $forms_data, $forms_data);

                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata = array();
                foreach ($ids_final as $id_org => $desc) {
                    $tabledata[] = array("Denominazione" => $desc);
                }

                if (sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "I seguenti " . sizeof($ids_final) . " elementi verranno ripristinati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "Il seguente elemento verrà ripristinato, vuoi procedere?")));

                $table = new AA_JSON_Template_Generic($id . "_Table", array(
                    "view" => "datatable",
                    "scrollX" => false,
                    "autoConfig" => true,
                    "select" => false,
                    "data" => $tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask($saveTask);
            } else {
                $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>L'utente corrente non ha i permessi per ripristinare gli elementi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }

            return $wnd;
        }
    }

    //Task generic resume object
    public function Task_GenericResumeObject($task, $params = array(), $bStandardCheck = true)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $objectClass = static::AA_MODULE_OBJECTS_CLASS;

        //lista elementi da ripristinare
        if ($params['ids']) {
            $ids = json_decode($_REQUEST['ids']);

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object;
                }
            }

            //Esiste almeno un elemento che può essere ripristinato dall'utente corrente
            $result_error=array();
            if (sizeof($ids_final) > 0) {
                $count = 0;
                foreach ($ids_final as $id => $object) {

                    if (!$object->Resume($this->oUser, $bStandardCheck)) {
                        $count++;
                        $result_error["$object->GetName()"] = AA_Log::$lastErrorLog;
                    }
                }

                if (sizeof($result_error) > 0) {
                    $id = static::AA_UI_PREFIX . "_ResumeDlg";
                    $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template($id . "_ResumeReport", array("template" => "Sono stati ripristinati " . (sizeof($ids) - sizeof($result_error)) . " elementi.<br>I seguenti non sono stati ripristinati:")));

                    $tabledata = array();
                    foreach ($result_error as $org => $desc) {
                        $tabledata[] = array("Denominazione" => $org, "Errore" => $desc);
                    }
                    $table = new AA_JSON_Template_Generic($id . "_Table", array(
                        "view" => "datatable",
                        "scrollX" => false,
                        "autoConfig" => true,
                        "select" => false,
                        "data" => $tabledata
                    ));
                    $wnd->AddView($table);

                    $sTaskLog = "<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog .= $wnd->toBase64();
                    $sTaskLog .= "</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                } else {
                    $sTaskLog = "<status id='status'>0</status><content id='content'>";
                    $sTaskLog .= "Sono stati ripristinati " . sizeof($ids_final) . " elementi.";
                    $sTaskLog .= "</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            } else {
                $task->SetError("Nella selezione non sono presenti elementi ripristinabili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi ripristinabili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            $task->SetError("Non sono stati selezionati elementi.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Non sono stati selezionati elementi.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Template generic reassign dlg
    public function Template_GetGenericReassignObjectDlg($params = array(), $saveTask = "GenericReassignObject")
    {
        $id = static::AA_UI_PREFIX . "_ReassignDlg";

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>La classe di gestione degli oggetti non è stata trovata.</p>")));
            $wnd->SetWidth(380);
            $wnd->SetHeight(115);

            return $wnd;
        }

        //lista elementi da ripristinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object->GetName();
                }
            }

            //Esiste almeno un organismo che può essere ripristinato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $forms_data['ids'] = json_encode(array_keys($ids_final));

                $wnd = new AA_GenericFormDlg($id, "Riassegna", $this->id, $forms_data, $forms_data);
                $wnd->EnableValidation();

                //Aggiunge il campo per la struttura di riassegnazione
                $wnd->AddStructField(array("targetForm" => $wnd->GetFormId()), array("select" => true), array("required" => true, "bottomLabel" => "*Seleziona la struttura di riassegnazione.", "placeholder" => "Scegli..."));

                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata = array();
                foreach ($ids_final as $id_org => $desc) {
                    $tabledata[] = array("Denominazione" => $desc);
                }

                if (sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "I seguenti " . sizeof($ids_final) . " elementi verranno riassegnati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "Il seguente elemento verrà riassegnato, vuoi procedere?")));

                $table = new AA_JSON_Template_Generic($id . "_Table", array(
                    "view" => "datatable",
                    "scrollX" => false,
                    "autoConfig" => true,
                    "select" => false,
                    "data" => $tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask($saveTask);
            } else {
                $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>L'utente corrente non ha i permessi per ripristinare gli elementi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }

            return $wnd;
        }
    }

    //Task generic reassign object
    public function Task_GenericReassignObject($task, $params = array(), $bStandardCheck = true)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $objectClass = static::AA_MODULE_OBJECTS_CLASS;

        //Verifica che l'utente possa riassegnare alla struttura indicata
        $struct = AA_Struct::GetStruct($params['id_assessorato'], $params['id_direzione'], $params['id_servizio']);
        if (!$struct->isValid()) {
            $task->SetError("Struttura indicata non valida.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Struttura indicata non valida.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        //lista elementi da ripristinare
        if ($params['ids']) {
            $ids = json_decode($_REQUEST['ids']);

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0) {
                    $ids_final[$curId] = $object;
                }
            }

            //Esiste almeno un elemento che può essere riassegnato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $count = 0;
                foreach ($ids_final as $id => $object) {

                    if (!$object->Reassign($struct, $this->oUser, $bStandardCheck)) {
                        $count++;
                        $result_error["$object->GetName()"] = AA_Log::$lastErrorLog;
                    }
                }

                if (is_array($result_error) && sizeof($result_error) > 0) {
                    $id = static::AA_UI_PREFIX . "_ReassignDlg";
                    $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template($id . "_ReassignReport", array("template" => "Sono stati riassegnati " . (sizeof($ids) - sizeof($result_error)) . " elementi.<br>I seguenti non sono stati riassegnati:")));

                    $tabledata = array();
                    foreach ($result_error as $org => $desc) {
                        $tabledata[] = array("Denominazione" => $org, "Errore" => $desc);
                    }
                    $table = new AA_JSON_Template_Generic($id . "_Table", array(
                        "view" => "datatable",
                        "scrollX" => false,
                        "autoConfig" => true,
                        "select" => false,
                        "data" => $tabledata
                    ));
                    $wnd->AddView($table);

                    $sTaskLog = "<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog .= $wnd->toBase64();
                    $sTaskLog .= "</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                } else {
                    $sTaskLog = "<status id='status'>0</status><content id='content'>";
                    $sTaskLog .= "Sono stati riassegnati " . sizeof($ids_final) . " elementi.";
                    $sTaskLog .= "</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            } else {
                $task->SetError("Nella selezione non sono presenti elementi riassegnabili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi riassegnabili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            $task->SetError("Non sono stati selezionati elementi.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Non sono stati selezionati elementi.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Task generic trash object
    public function Task_GenericTrashObject($task, $params = array(), $bStandardCheck = true)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $objectClass = static::AA_MODULE_OBJECTS_CLASS;

        //lista oggetti da cestinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object;
                }
            }

            //Esiste almeno un organismo che può essere cestinato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $count = 0;
                $result_error=array();
                foreach ($ids_final as $id => $object) {

                    if (!$object->Trash($this->oUser, $bStandardCheck, false)) {
                        $count++;
                        $result_error[$object->GetName()] = AA_Log::$lastErrorLog;
                    }
                }

                if (sizeof($result_error) > 0) {
                    $id = static::AA_UI_PREFIX . "_Trash";
                    $wnd = new AA_GenericWindowTemplate(static::AA_UI_PREFIX . "_Trash", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("", array("template" => "Sono stati cestinati " . (sizeof($ids) - sizeof($result_error)) . " elementi.<br>I seguenti non sono stati cestinati:")));

                    $tabledata = array();
                    foreach ($result_error as $org => $desc) {
                        $tabledata[] = array("Denominazione" => $org, "Errore" => $desc);
                    }
                    $table = new AA_JSON_Template_Generic($id . "_Table", array(
                        "view" => "datatable",
                        "scrollX" => false,
                        "autoConfig" => true,
                        "select" => false,
                        "data" => $tabledata
                    ));
                    $wnd->AddView($table);

                    $sTaskLog = "<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog .= $wnd->toBase64();
                    $sTaskLog .= "</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                } else {
                    $sTaskLog = "<status id='status'>0</status><content id='content'>";
                    $sTaskLog .= "Sono stati cestinati " . sizeof($ids_final) . " elementi.";
                    $sTaskLog .= "</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            } else {
                $task->SetError("Nella selezione non sono presenti elementi cestinabili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi cestinabili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            $task->SetError("Non sono stati selezionati elementi.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Non sono stati selezionati elementi.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Task generic trash object
    public function Task_GenericDeleteObject($task, $params = array(), $bStandardCheck = true)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $objectClass = static::AA_MODULE_OBJECTS_CLASS;

        //lista oggetti da cestinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object;
                }
            }

            //Esiste almeno un organismo che può essere eliminato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $count = 0;
                $result_error=array();
                foreach ($ids_final as $id => $object) {

                    if (!$object->Delete($this->oUser, $bStandardCheck, false)) {
                        $count++;
                        $result_error[$object->GetName()] = AA_Log::$lastErrorLog;
                    }
                }

                if (sizeof($result_error) > 0) {
                    $id = static::AA_UI_PREFIX . "_Trash";
                    $wnd = new AA_GenericWindowTemplate(static::AA_UI_PREFIX . "_Trash", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("", array("template" => "Sono stati eliminati " . (sizeof($ids) - sizeof($result_error)) . " elementi.<br>I seguenti non sono stati eliminati:")));

                    $tabledata = array();
                    foreach ($result_error as $org => $desc) {
                        $tabledata[] = array("Denominazione" => $org, "Errore" => $desc);
                    }
                    $table = new AA_JSON_Template_Generic($id . "_Table", array(
                        "view" => "datatable",
                        "scrollX" => false,
                        "autoConfig" => true,
                        "select" => false,
                        "data" => $tabledata
                    ));
                    $wnd->AddView($table);

                    $sTaskLog = "<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog .= $wnd->toBase64();
                    $sTaskLog .= "</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                } else {
                    $sTaskLog = "<status id='status' action='goBack' action_params='" . json_encode(array()) . "'>0</status><content id='content'>";
                    $sTaskLog .= "Sono stati eliminati " . sizeof($ids_final) . " elementi.";
                    $sTaskLog .= "</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            } else {
                $task->SetError("Nella selezione non sono presenti elementi eliminabili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi eliminabili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            $task->SetError("Non sono stati selezionati elementi.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Non sono stati selezionati elementi.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Template generic publish dlg
    public function Template_GetGenericPublishObjectDlg($params = array(), $saveTask = "GenericPublishObject")
    {
        $id = static::AA_UI_PREFIX . "_PublishDlg";

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>La classe di gestione degli oggetti non è stata trovata.</p>")));
            $wnd->SetWidth(380);
            $wnd->SetHeight(115);

            return $wnd;
        }

        //lista elementi da ripristinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_PUBLISH) > 0) {
                    $ids_final[$curId] = $object->GetName();
                }
            }

            //Esiste almeno un organismo che può essere pubblicato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $forms_data['ids'] = json_encode(array_keys($ids_final));

                $wnd = new AA_GenericFormDlg($id, "Pubblica", $this->id, $forms_data, $forms_data);

                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata = array();
                foreach ($ids_final as $id_org => $desc) {
                    $tabledata[] = array("Denominazione" => $desc);
                }

                if (sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "I seguenti " . sizeof($ids_final) . " elementi verranno pubblicati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "Il seguente elemento verrà pubblicato, vuoi procedere?")));

                $table = new AA_JSON_Template_Generic($id . "_Table", array(
                    "view" => "datatable",
                    "scrollX" => false,
                    "autoConfig" => true,
                    "select" => false,
                    "data" => $tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask($saveTask);
            } else {
                $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>L'utente corrente non ha i permessi per ripristinare gli elementi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }

            return $wnd;
        }
    }

    //Task generic trash object
    public function Task_GenericPublishObject($task, $params = array(), $bStandardCheck = true)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $objectClass = static::AA_MODULE_OBJECTS_CLASS;

        //lista oggetti da pubblicare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_PUBLISH > 0)) {
                    $ids_final[$curId] = $object;
                }
            }

            //Esiste almeno un organismo che può essere pubblicato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $count = 0;
                foreach ($ids_final as $id => $object) {

                    if (!$object->Publish($this->oUser, $bStandardCheck, false)) {
                        $count++;
                        $result_error[$object->GetName()] = AA_Log::$lastErrorLog;
                    }
                }

                if (is_array($result_error) && sizeof($result_error) > 0) {
                    $id = static::AA_UI_PREFIX . "_Trash";
                    $wnd = new AA_GenericWindowTemplate(static::AA_UI_PREFIX . "_Publish", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("", array("autoheight"=>true,"template" => "Sono stati pubblicati " . (sizeof($ids) - sizeof($result_error)) . " elementi.<br>I seguenti non sono stati pubblicati:")));

                    $columns=array(
                        array("id"=>"Denominazione","header"=>array("<div style='text-align: center'>Denominazione</div>",array("content"=>"textFilter")),"fillspace"=>true,"sort"=>"text","css"=>"PraticheTable_left"),
                        array("id"=>"Errore","header"=>array("<div style='text-align: center'>Errore</div>",array("content"=>"textFilter")),"fillspace"=>true,"sort"=>"text","css"=>"PraticheTable_left"),
                    );
                    $tabledata = array();
                    foreach ($result_error as $org => $desc) {
                        $tabledata[] = array("Denominazione" => $org, "Errore" => $desc);
                    }
                    $table = new AA_JSON_Template_Generic("", array(
                        "view" => "datatable",
                        "scrollX" => false,
                        "columns" => $columns,
                        "select" => false,
                        "data" => $tabledata
                    ));
                    $wnd->AddView($table);

                    $sTaskLog = "<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog .= $wnd->toBase64();
                    $sTaskLog .= "</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                } else {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
                    $task->SetContent("Sono stati pubblicati " . sizeof($ids_final) . " elementi.",false);
                    if(isset($_REQUEST['goBack']) && $_REQUEST['goBack'] == 1) $task->SetStatusAction("goBack");

                    return true;
                }
            } else {
                $task->SetError("Nella selezione non sono presenti elementi pubblicabili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi pubblicabili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            $task->SetError("Non sono stati selezionati elementi.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Non sono stati selezionati elementi.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Task generic export pdf 
    public function Task_PdfExport($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        //Verifica della classe degli oggetti
        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $sessVar = AA_SessionVar::Get("SaveAsPdf_ids");
        $sessParams = AA_SessionVar::Get("SaveAsPdf_params");
        $objectClass = static::AA_MODULE_OBJECTS_CLASS;

        //lista elementi da esportare
        if ($sessVar->IsValid() && !isset($_REQUEST['fromParams'])) {
            $ids = $sessVar->GetValue();

            if (is_array($ids)) {
                foreach ($ids as $curId) {
                    $object = new $objectClass($curId, $this->oUser);
                    if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_READ) > 0) {
                        $ids_final[$curId] = $object;
                    }
                }
            }

            //Esiste almeno un organismo che può essere letto dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $this->Template_PdfExport($ids_final);
            } else {
                $task->SetError("Nella selezione non sono presenti dati leggibili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi leggibili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            if ($sessParams->isValid()) {
                $params = (array) $sessParams->GetValue();

                //Verifica della sezione 
                if ($params['section'] == static::AA_ID_SECTION_BOZZE) {
                    $params["status"] = AA_Const::AA_STATUS_BOZZA;
                } else {
                    $params["status"] = AA_Const::AA_STATUS_PUBBLICATA;
                }

                if ($params['cestinate'] == 1) {
                    $params['status'] |= AA_Const::AA_STATUS_CESTINATA;
                }

                if ($objectClass == "AA_Object") $objects = $objectClass::Search($params, false, $this->oUser);
                else $objects = $objectClass::Search($params, $this->oUser);

                if ($objects[0] == 0) {
                    $task->SetError("Non è stata individuata nessuna corrispondenza in base ai parametri indicati.");
                    $sTaskLog = "<status id='status'>-1</status><error id='error'>Non è stata individuata nessa corrispondenza in base ai parametri indicati.</error>";
                    $task->SetLog($sTaskLog);
                    return false;
                } else {
                    $this->Template_PdfExport($objects[1]);
                }
            }
        }
    }

    //Funzione di esportazione in pdf (da specializzare)
    public function Template_PdfExport($objects = array())
    {
        return $this->Template_GenericPdfExport($objects);
    }

    //Task generic export csv 
    public function Task_CsvExport($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        //Verifica della classe degli oggetti
        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $sessVar = AA_SessionVar::Get("SaveAsCsv_ids");
        $sessParams = AA_SessionVar::Get("SaveAsCsv_params");
        $objectClass = static::AA_MODULE_OBJECTS_CLASS;
        $ids_final=array();

        //lista elementi da esportare
        if ($sessVar->IsValid() && !isset($_REQUEST['fromParams'])) {
            $ids = $sessVar->GetValue();

            if (is_array($ids)) {
                foreach ($ids as $curId) {
                    $object = new $objectClass($curId, $this->oUser);
                    if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_READ) > 0) {
                        $ids_final[$curId] = $object;
                    }
                }
            }

            //Esiste almeno un organismo che può essere letto dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $this->Template_CsvExport($ids_final);
            } else {
                $task->SetError("Nella selezione non sono presenti dati leggibili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi leggibili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            if ($sessParams->isValid()) {
                $params = (array) $sessParams->GetValue();
                AA_Log::Log(__METHOD__." - params: ".print_r($params,true),100);

                //Verifica della sezione 
                if ($params['section'] == static::AA_ID_SECTION_BOZZE) {
                    $params["status"] = AA_Const::AA_STATUS_BOZZA;
                } else {
                    $params["status"] = AA_Const::AA_STATUS_PUBBLICATA;
                }

                if ($params['cestinate'] == 1) {
                    $params['status'] |= AA_Const::AA_STATUS_CESTINATA;
                }

                if ($objectClass == "AA_Object") $objects = $objectClass::Search($params, false, $this->oUser);
                else $objects = $objectClass::Search($params, $this->oUser);

                if ($objects[0] == 0) {
                    $task->SetError("Non è stata individuata nessuna corrispondenza in base ai parametri indicati.");
                    $sTaskLog = "<status id='status'>-1</status><error id='error'>Non è stata individuata nessuna corrispondenza in base ai parametri indicati.</error>";
                    $task->SetLog($sTaskLog);
                    return false;
                } else {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
                    $task->SetContent("Exportazione effettuata con successo.",false);

                    $this->Template_CsvExport($objects[1],$params);

                    return true;
                }
            }
        }
    }

    //Funzione di esportazione in csv (da specializzare)
    public function Template_CsvExport($objects = array(),$params=null)
    {
        return $this->Template_GenericCsvExport($objects, $params);
    }

    public function Template_GenericCsvExport($objects=array(), $params=null)
    {
        $separator="|";
        $showHeader=true;
        $showDetails=true;
        $toBrowser=true;
        if(is_array($params) && !empty($params['separator'])) $separator=$params['separator'];
        if(is_array($params) && !empty($params['showHeader'])) $showHeader=$params['showHeader'];
        if(is_array($params) && !empty($params['showDetails'])) $showDetails=$params['showDetails'];
        if(is_array($params) && !empty($params['toBrowser'])) $toBrowser=$params['toBrowser'];

        $bFirst=true;
        $csv="";
        foreach($objects as $curObj)
        {
            if($bFirst)
            {
                if($params['showHeader']) $csv .=$curObj->ToCsv($separator, $showHeader, $showDetails,false);
                else $csv .=$curObj->ToCsv($separator, $showHeader, $showDetails,false);
                $bFirst=false;
            }
            else $csv .="\n".$curObj->ToCsv($separator,false, $showDetails,false);
        }

        if($toBrowser)
        {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="export.csv"');
            die($csv);
        }

        return $csv;
    }

    //Template pdf export generic
    protected function Template_GenericPdfExport($objects = array(), $bToBrowser = true, $title = "Esportazione in pdf", $pageTemplateFunc = "Template_GenericObjectPdfExport",$rowsForPage=1, $index=true, $subTitle="",$layout_class="AA_PDF_RAS_TEMPLATE_A4_PORTRAIT",$maxRowHeight=0,$headerTemplateFunct=null)
    {
        include_once "pdf_lib.php";

        if (!is_array($objects)) return "";
        if (sizeof($objects) == 0) return "";

        //recupero elementi
        //$objectClass="AA_Object_V2";
        //if(class_exists(static::AA_MODULE_OBJECTS_CLASS))
        //{
        //    $objectClass=static::AA_MODULE_OBJECTS_CLASS;
        //}

        //$objects=$objectClass::Search(array("id"=>implode(",",$ids)),false,$this->oUser);
        //$count = sizeof($objects);
        #--------------------------------------------

        $count = sizeof($objects);
        if($rowsForPage <=0) $rowsForPage=1;

        //nome file
        $filename = "pdf_export";
        $filename .= "-" . date("YmdHis");
        $doc = new $layout_class($filename);

        $doc->SetDocumentStyle("font-family: sans-serif; font-size: 3mm;");
        $doc->SetPageCorpoStyle("display: flex; flex-direction: column; justify-content: space-between; padding:0;");
        $curRow = 0;
        $rowForPage = $rowsForPage;
        $lastRow = $rowForPage - 1;
        $curPage = null;
        $curPage_row = "";
        $curNumPage = 0;
        if($rowsForPage > 0) $maxItemHeight="max-height: ".intval(100/$rowsForPage)."%";
        if($maxRowHeight > 0) $maxItemHeight="max-height: ".$maxRowHeight."%";
        //$columns_width=array("titolare"=>"10%","incarico"=>"8%","atto"=>"10%","struttura"=>"28%","curriculum"=>"10%","art20"=>"12%","altri_incarichi"=>"10%","1-ter"=>"10%","emolumenti"=>"10%");
        //$columns_width=array("dal"=>"10%","al"=>"10%","inconf"=>"10%","incomp"=>"10%","anno"=>"25%","titolare"=>"50%","tipo_incarico"=>"10%","atto_nomina"=>"10%","struttura"=>"40%","curriculum"=>"25%","altri_incarichi"=>"25%","1-ter"=>"25%","emolumenti"=>"10%");
        $rowContentWidth = "width: 99.8%;";

        if ($count > 1) {
            //pagina di intestazione (senza titolo)
            $curPage = $doc->AddPage();
            $curPage->SetCorpoStyle("display: flex; flex-direction: column; justify-content: center; align-items: center; padding:0;");
            $curPage->SetFooterStyle("border-top:.2mm solid black");
            $curPage->ShowPageNumber(false);

            //Intestazione
            $intestazione = "<div style='width: 100%; text-align: center; font-size: 24; font-weight: bold'>$title</div>";
            if($subTitle !="")
            {
                $intestazione .= "<div style='width: 100%; text-align: center; font-weight: normal; margin-top: 1em;'>".$subTitle."</div>";
            }
            $intestazione .= "<div style='width: 100%; text-align: center; font-size: x-small; font-weight: normal;margin-top: 3em;'>documento generato il " . date("Y-m-d") . "</div>";

            $curPage->SetContent($intestazione);
            $curNumPage++;

            if($index)
            {
                //pagine indice (50 nominativi per pagina)
                $indiceNumVociPerPagina = 50;
                for ($i = 0; $i < $count / $indiceNumVociPerPagina; $i++) {
                    $curPage = $doc->AddPage();
                    $curPage->SetCorpoStyle("display: flex; flex-direction: column; padding:0;");
                    $curNumPage++;
                }
            }
            $curPage=null;
            #---------------------------------------
        }

        //Imposta il titolo per le pagine successive
        if($subTitle =="") $doc->SetTitle("$title - report generato il " . date("Y-m-d"));
        else $doc->SetTitle("$subTitle - report generato il " . date("Y-m-d"));

        $indice = array();
        $lastPage = $count / $rowForPage + $curNumPage;

        //Rendering pagine
        foreach ($objects as $id => $curObject) {

            if (method_exists($this, $pageTemplateFunc)) $template = $this->$pageTemplateFunc("report_object_pdf_" . $curObject->GetId(), null, $curObject, $this->oUser);
            else $template = "";

            if(is_object($template) && method_exists($template,"GetRowCount"))
            {
                if($curRow+$template->GetRowCount() > $rowForPage) $curRow=$rowForPage;
            }
            //inizia una nuova pagina (intestazione)
            if ($curRow >= $rowForPage) $curRow = 0;
            if ($curRow == 0) {
                $border = "";
                if ($curPage != null) 
                {
                    if($curPage_row !="")
                    {
                        $curPage->SetContent($curPage_row);
                        $curPage = $doc->AddPage();
                        $curNumPage++;
                    }
                }
                else 
                {
                    $curPage = $doc->AddPage();
                    $curNumPage++;
                }
                
                $curPage->SetCorpoStyle("display: flex; flex-direction: column;  justify-content: flex-start; padding:0;");
                $curPage_row = "";
                if(method_exists($this, $headerTemplateFunct)) $curPage_row = $this->$headerTemplateFunct($curObject);
            }

            $indice[$curObject->GetID()] = $curNumPage . "|" . mb_substr($curObject->GetName(),0,90);
            $curPage_row .= "<div id='" . $curObject->GetID() . "' style='display:flex;  flex-direction: column; width: 99.8%; align-items: center; text-align: center; padding: 0mm; margin-top: 2mm; min-height: 9mm; ".$maxItemHeight."; overflow: hidden;'>";

            //AA_Log::Log($template,100,false,true);

            $curPage_row .= $template;
            $curPage_row .= "</div>";
            if(is_object($template) && method_exists($template,"GetRowCount")) $curRow+=$template->GetRowCount();
            else $curRow++;
        }
        if ($curPage != null) $curPage->SetContent($curPage_row);
        #-----------------------------------------

        if ($count > 1 && $index) {
            //Aggiornamento indice
            $curNumPage = 1;
            $curPage = $doc->GetPage($curNumPage);
            $vociCount = 0;
            $curRow = 0;
            $bgColor = "";
            $curPage_row = "";

            foreach ($indice as $id => $data) {
                if ($curNumPage != (int)($vociCount / $indiceNumVociPerPagina) + 1) {
                    $curPage->SetContent($curPage_row);
                    $curNumPage = (int)($vociCount / $indiceNumVociPerPagina) + 1;
                    $curPage = $doc->GetPage($curNumPage);
                    $curRow = 0;
                    $bgColor = "";
                }

                if ($curPage instanceof AA_PDF_Page) {
                    if ($vociCount % 2 > 0) {
                        $dati = explode("|", $data);
                        $curPage_row .= "<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#" . $id . "'>" . $dati['1'] . "</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#" . $id . "'>pag. " . $dati[0] . "</a></div>";
                        $curPage_row .= "</div>";
                        if ($vociCount == (sizeof($indice) - 1)) $curPage->SetContent($curPage_row);
                        $curRow++;
                    } else {
                        //Intestazione
                        if ($curRow == 0) $curPage_row = "<div style='width:100%;text-align: center; font-size: 18px; font-weight: bold; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .3em;'>Indice</div>";

                        if ($curRow % 2) $bgColor = "background-color: #f5f5f5;";
                        else $bgColor = "";
                        $curPage_row .= "<div style='display:flex; " . $rowContentWidth . " align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;" . $bgColor . "'>";
                        $dati = explode("|", $data);
                        $curPage_row .= "<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#" . $id . "'>" . $dati['1'] . "</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#" . $id . "'>pag. " . $dati[0] . "</a></div>";

                        //ultima voce
                        if ($vociCount == (sizeof($indice) - 1)) {
                            $curPage_row .= "<div style='width:40%;text-align: left;padding-left: 10mm'>&nbsp; </div><div style='width:9%;text-align: right;padding-left: 10mm'>&nbsp; </div></div>";
                            $curPage->SetContent($curPage_row);
                        }
                    }
                }

                $vociCount++;
            }
        }

        if ($bToBrowser) $doc->Render();
        else {
            $doc->Render(false);
            return $doc->GetFilePath();
        }
    }

    //Template navbar bozze
    protected function TemplateGenericNavbar_Bozze($level = 1, $last = false, $refresh_view = true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(
            static::AA_UI_PREFIX . "_Navbar_Link_" . static::AA_UI_BOZZE_BOX,
            array(
                "type" => "clean",
                "section_id" => static::AA_ID_SECTION_BOZZE,
                "module_id" => $this->GetId(),
                "refresh_view" => $refresh_view,
                "tooltip" => "Fai click per visualizzare le schede in bozza",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" . static::AA_UI_PREFIX . "_Navbar_Link_" . static::AA_UI_BOZZE_BOX . "' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"Bozze\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => static::AA_UI_SECTION_BOZZE_NAME, "icon" => "mdi mdi-file-document-edit", "class" => $class)
            )
        );
        return $navbar;
    }

    protected function TemplateGenericNavbar_Section($section=null, $level = 1, $last = false, $refresh_view = true)
    {
        if($section instanceof AA_GenericModuleSection)
        {
            $class = "n" . $level;
            if ($last) $class .= " AA_navbar_terminator_left";
            $navbar =  new AA_JSON_Template_Template(
                "",
                array(
                    "type" => "clean",
                    "section_id" => $section->GetId(),
                    "css" => array("width"=>"fit-content !important"),
                    "module_id" => $this->GetId(),
                    "refresh_view" => $refresh_view,
                    "tooltip" => "Fai click per visualizzare la sezione relativa a ".$section->GetName(),
                    "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" . $section->GetViewId() . "' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"".$section->GetId()."\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                    "data" => array("label" => $section->GetName(), "icon" => "mdi ".$section->GetIcon(), "class" => $class)
                )
            );
        }
        else
        {
            $navbar=$this->TemplateGenericNavbar_Void($level,$last);
        }
       
        return $navbar;
    }

    //Task section object content
    protected function Task_GetGenericObjectContent($task, $params = array(), $param = "object")
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        //object templates
        foreach($this->aObjectTemplates as $idObject=>$curTemplate)
        {
            if($params['object']==$idObject && is_string($curTemplate) && method_exists($this,$curTemplate))
            {
                $content = array("id" => $idObject, "content" => $this->{$curTemplate}()->toArray());
                
                //AA_Log::Log(__METHOD__." - content ".print_r($content,true),100);

                //Codifica il contenuto in base64
                $sTaskLog .= base64_encode(json_encode($content)) . "</content>";
                $task->SetLog($sTaskLog);

                return true;
            }
        }

        //Verifica se il contenuto richiesto è una sezione
        foreach($this->sections as $idSection=>$curSection)
        {
            if($idSection==$params[$param] || $idSection==$params['section'])
            {
                if($curSection->GetTemplate() != "" && method_exists($this,$curSection->GetTemplate()))
                {
                    $content = array("id" => $curSection->GetViewId(), "content" => $this->{$curSection->GetTemplate()}($params)->toArray());
                     
                    //Codifica il contenuto in base64
                     $sTaskLog .= base64_encode(json_encode($content)) . "</content>";
                     $task->SetLog($sTaskLog);

                     //AA_Log::Log(__METHOD__." - tasklog: ".$sTaskLog,100);
                     return true;
                }

                if(is_string($this->aSectionItemTemplates[$idSection]) && method_exists($this,$this->aSectionItemTemplates[$idSection]))
                {
                    $content = array("id" => $curSection->GetViewId(), "content" => $this->{$this->aSectionItemTemplates[$idSection]}($params)->toArray());
                     
                    //Codifica il contenuto in base64
                     $sTaskLog .= base64_encode(json_encode($content)) . "</content>";
                     $task->SetLog($sTaskLog);

                     //AA_Log::Log(__METHOD__." - tasklog: ".$sTaskLog,100);
                     return true;
                }

                if(is_array($this->aSectionItemTemplates[$idSection]))
                {
                    foreach($this->aSectionItemTemplates[$idSection] as $key=>$val)
                    {
                        if($params['object']==$val['id'] && method_exists($this, $val['template']))
                        {
                            //AA_Log::Log(__METHOD__." - object id: ".$_REQUEST['object'],100);
                            $object=null;
                            if(isset($params['id']) && AA_Platform::IsRegistered($this->GetId(),$this->oUser))
                            {
                                $platform=AA_Platform::GetInstance();
                                $module=$platform->GetModule($this->GetId());
                                if(class_exists($module['class']))
                                {
                                    $object=new $module['class']($params['id']);
                                }
                                else
                                {
                                    AA_Log::Log(__METHOD__." - classe non trovata: ".$module['class'],100);
                                }
                            }
                            
                            if($object !=null)
                            {
                                $content = array("id" =>$val['id'], "content" => $this->{$val['template']}($object)->toArray());
                                $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
                                $task->SetContent(json_encode($content),true);
                                return true;    
                            }
                        }
                    }
                }
            }
        }

        switch ($params[$param]) 
        {
            case "Bozze":
            case static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX:
                $template = $this->TemplateSection_Bozze($params);
                $content = array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX, "content" => $template->toArray());
                break;

            case "Pubblicate":
            case static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX:
                $template = $this->TemplateSection_Pubblicate($params);
                $content = array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX, "content" => $template->toArray());
                break;

            case "Dettaglio":
            case static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX:
                $template = $this->TemplateSection_Detail($params);
                $content = array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX, "content" => $template->toArray());
                break;
            default:
                $content = array(
                    array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX, "content" => $this->TemplateSection_Placeholder()->toArray()),
                    array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX, "content" => $this->TemplateSection_Placeholder()->toArray()),
                    array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX, "content" => $this->TemplateSection_Placeholder()->toArray())
                );
        }

        //AA_Log::Log(__METHOD__." content: ".print_r($content,true),100);

        //Codifica il contenuto in base64
        $sTaskLog .= base64_encode(json_encode($content)) . "</content>";

        //AA_Log::Log(__METHOD__." - params: ".print_r($params,true)." - param: ".$param." - object content: ".$sTaskLog,100);

        $task->SetLog($sTaskLog);

        return true;
    }

    //Task object content (da specializzare)
    public function Task_GetObjectContent($task)
    {
        return $this->Task_GetGenericObjectContent($task, $_REQUEST);
    }

    //Task section layout (da specializzare)
    public function Task_GetSectionContent($task)
    {
        /*//verifica se è presente un template per la sezione impostata
        if($_REQUEST['section'] !="" && isset($this->aSectionItemTemplates[$_REQUEST['section']]))
        {
            $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

            if(method_exists($this,$this->aSectionItemTemplates[$_REQUEST['section']]))
            {
                $content=$this->{$this->aSectionItemTemplates[$_REQUEST['section']]}($_REQUEST);
                if($content instanceof AA_JSON_Template_Generic)
                {
                    $sTaskLog.=$content->toBase64()."</content>";
                    $task->SetLog($sTaskLog);

                    return true;
                }
            }
        }*/

        return $this->Task_GetGenericObjectContent($task, $_REQUEST, "section");
    }

    //Task object data
    protected function Task_GetGenericObjectData($task, $params = array())
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $objectData = array(array());

        switch ($params['object']) {
            case static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_LISTBOX:
                $params['count'] = 10;
                $data = $this->GetDataSectionPubblicate_List($params);
                if ($data[0] > 0) $objectData = $data[1];
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_LISTBOX:
                $params['count'] = 10;
                $data = $this->GetDataSectionBozze_List($params);
                if ($data[0] > 0) $objectData = $data[1];
                break;
            default:
                $objectData = array();
        }

        $sTaskLog .= base64_encode(json_encode($objectData));
        $sTaskLog .= "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Task object data (da specializzare)
    public function Task_GetObjectData($task)
    {
        return $this->Task_GetGenericObjectData($task, $_REQUEST);
    }

    //Template detail (da specializzare)
    public function TemplateSection_Detail($params)
    {
        return $this->TemplateGenericSection_Detail($params);
    }

    //Template navbar bozze (da specializzare)
    public function TemplateNavbar_Bozze($level = 1, $last = false, $refresh_view = true)
    {
        return $this->TemplateGenericNavbar_Bozze($level, $last, $refresh_view);
    }

    //Template navbar pubblicate
    protected function TemplateGenericNavbar_Pubblicate($level = 1, $last = false, $refresh_view = true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(
            static::AA_UI_PREFIX . "_Navbar_Link_" . static::AA_UI_PUBBLICATE_BOX,
            array(
                "type" => "clean",
                "section_id" => static::AA_ID_SECTION_PUBBLICATE,
                "module_id" => $this->GetId(),
                "refresh_view" => $refresh_view,
                "tooltip" => "Fai click per visualizzare le schede pubblicate",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" . static::AA_UI_PREFIX . "_Navbar_Link_" . static::AA_UI_PUBBLICATE_BOX . "' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"Pubblicate\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => static::AA_UI_SECTION_PUBBLICATE_NAME, "icon" => "mdi mdi-certificate", "class" => $class)
            )
        );
        return $navbar;
    }

    //Template navbar pubblicate (da specializzare)
    public function TemplateNavbar_Pubblicate($level = 1, $last = false, $refresh_view = true)
    {
        return $this->TemplateGenericNavbar_Pubblicate($level, $last, $refresh_view);
    }

    //Template navbar indietro
    protected function TemplateGenericNavbar_Back($level = 1, $last = false, $refresh_view = false)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $id=static::AA_UI_PREFIX . "_Navbar_Link_Back_Content_Box_".uniqid(time());
        $navbar =  new AA_JSON_Template_Template(
            $id,
            array(
                "type" => "clean",
                "css" => "AA_NavbarEventListener",
                "module_id" => $this->GetId(),
                "refresh_view" => $refresh_view,
                "tooltip" => "Fai click per tornare alla lista",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" .$id."' onClick='AA_MainApp.utils.callHandler(\"goBack\",null,\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => "Indietro", "icon" => "mdi mdi-keyboard-backspace", "class" => $class)
            )
        );
        return $navbar;
    }

    //Template navbar void
    protected function TemplateGenericNavbar_Void($level = 1, $last = false)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $id=static::AA_UI_PREFIX . "_Navbar_Link_Back_Content_Box_".uniqid(time());
        $navbar =  new AA_JSON_Template_Template(
            $id,
            array(
                "type" => "clean",
                "css" => "AA_NavbarEventListener",
                "module_id" => $this->GetId(),
                "tooltip" => "",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" .$id."'><span>#label#</span></a></div>",
                "data" => array("label" => "&nbsp;", "class" => $class)
            )
        );
        return $navbar;
    }

    //Template navbar indietro (da specializzare)
    public function TemplateNavbar_Back($level = 1, $last = false, $refresh_view = true)
    {
        return $this->TemplateGenericNavbar_Back($level, $last, $refresh_view);
    }

    //Template bozze context menu (da specializzare)
    public function TemplateActionMenu_Bozze()
    {
        return $this->TemplateGenericActionMenu_Bozze();
    }

    //Template pubblicate context menu
    protected function TemplateGenericActionMenu_Pubblicate()
    {

        $menu = new AA_JSON_Template_Generic(
            "AA_ActionMenuPubblicate",
            array(
                "view" => "contextmenu",
                "data" => array(
                    array(
                        "id" => "refresh_pubblicate",
                        "value" => "Aggiorna",
                        "icon" => "mdi mdi-reload",
                        "module_id" => $this->GetId(),
                        "handler" => "refreshUiObject",
                        "handler_params" => array(static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX, true)
                    )
                )
            )
        );

        return $menu;
    }

    //Template pubblicate context menu (da specializzare)
    public function TemplateActionMenu_Pubblicate()
    {
        return $this->TemplateGenericActionMenu_Pubblicate();
    }

    //Template detail context menu
    protected function TemplateGenericActionMenu_Detail()
    {

        $menu = new AA_JSON_Template_Generic(
            "AA_ActionMenuDetail",
            array(
                "view" => "contextmenu",
                "data" => array(array(
                    "id" => "refresh_detail",
                    "value" => "Aggiorna",
                    "icon" => "mdi mdi-reload",
                    "panel_id" => "back",
                    "section_id" => "Dettaglio",
                    "module_id" => $this->GetId(),
                    "handler" => "refreshUiObject",
                    "handler_params" => array(static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX, true)
                ))
            )
        );

        return $menu;
    }

    //Template detail context menu (da specializzare)
    public function TemplateActionMenu_Detail()
    {
        return $this->TemplateGenericActionMenu_Detail();
    }

    //Template sezione pubblicate
    protected function TemplateGenericSection_Pubblicate($params = array(), $bCanModify = false, $contentData = null)
    {
        $content = new AA_GenericPagedSectionTemplate(static::AA_UI_PREFIX . "_" . static::AA_ID_SECTION_PUBBLICATE, $this->GetId());

        //custom items templates
        if (is_array($this->aSectionItemTemplates)) {
            if (isset($this->aSectionItemTemplates[static::AA_ID_SECTION_PUBBLICATE]) && $this->aSectionItemTemplates[static::AA_ID_SECTION_PUBBLICATE] != "") {
                $content->SetContentBoxTemplate($this->aSectionItemTemplates[static::AA_ID_SECTION_PUBBLICATE]);
            }
        }

        $content->EnablePager();
        $content->EnablePaging();
        $content->SetPagerItemForPage(10);
        $content->EnableFiltering();
        $content->SetFilterDlgTask(static::AA_UI_TASK_PUBBLICATE_FILTER_DLG);
        $content->ViewExportFunctions();

        $sectionName = static::AA_UI_SECTION_PUBBLICATE_NAME;

        $content->ViewDetail();

        if ($bCanModify) {
            $content->ViewReassign();
            $content->SetReassignHandlerParams(array("task" => static::AA_UI_TASK_REASSIGN_DLG));

            if (isset($params['revisionate']) && $params['revisionate'] == 1) {
                $sectionName .= " revisionate";
                $content->HideReassign();
                $content->ViewPublish();
                $content->SetPublishHandlerParams(array("task" => static::AA_UI_TASK_PUBLISH_DLG));
            }

            if (!isset($params['cestinate']) || $params['cestinate'] == 0) {
                $content->ViewTrash();
                $content->SetTrashHandlerParams(array("task" => static::AA_UI_TASK_TRASH_DLG));
            } else {
                $sectionName = static::AA_UI_SECTION_PUBBLICATE_CESTINATE_NAME;
                $content->HideReassign();
                $content->HidePublish();
                $content->ViewResume();
                $content->SetResumeHandlerParams(array("task" => static::AA_UI_TASK_RESUME_DLG));
                $content->ViewDelete();
                $content->SetDeleteHandlerParams(array("task" => static::AA_UI_TASK_DELETE_DLG));
            }
        }

        $content->SetSectionName($sectionName);

        if($this->DataSectionIsFiltered($params))
        {
            AA_Log::Log(__METHOD__." - sezione pubblicate filtrata",100);
            $content->SetPagerFiltered(true);
        }

        if ($contentData == null) {
            $params['count'] = 10;
            $contentData = $this->GetDataSectionPubblicate_List($params);
        }

        $content->SetContentBoxData($contentData[1]);
        $content->SetPagerItemCount($contentData[0]);
        $content->EnableMultiSelect();
        $content->EnableSelect();

        return $content;
    }

    //Verifica se la sezione è filtrata
    protected function DataSectionIsFiltered($params = array())
    {
        if($this->CustomDataSectionIsFiltered($params)) return true;

        if(isset($params['id_assessorato']) && $params['id_assessorato'] > 0) return true;
        if(isset($params['id_direzione']) && $params['id_direzione'] > 0) return true;
        if(isset($params['id_servizio']) && $params['id_servizio'] > 0) return true;
        if(isset($params['struct_descr']) && $params['struct_descr'] != "Qualunque") return true;
        if(isset($params['nome']) && $params['nome'] != "") return true;
        if(isset($params['ids']) && $params['ids'] != "") return true;

        return false;
    }
    //Funzione di filtro personalizzata per la verifica se la sezione è filtrata
    protected function CustomDataSectionIsFiltered($params = array())
    {
        return false;
    }

    //Template sezione pubblicate (da specializzare)
    public function TemplateSection_Pubblicate($params = array())
    {
        $content = $this->TemplateGenericSection_Pubblicate($params, false);
        return $content->toObject();
    }

    //Restituisce la lista delle schede pubblicate 
    protected function GetDataGenericSectionBozze_List($params = array(), $customFilterFunction = "GetDataSectionBozze_CustomFilter", $customTemplateDataFunction = "GetDataSectionBozze_CustomDataTemplate")
    {
        $templateData = array();

        $parametri = array("status" => AA_Const::AA_STATUS_BOZZA);
        if (isset($params['cestinate']) && $params['cestinate'] == 1) {
            $parametri['status'] |= AA_Const::AA_STATUS_CESTINATA;
        }

        if (isset($params['page']) && $params['page'] > 0) $parametri['from'] = ($params['page'] - 1) * $params['count'];
        if (isset($params['id_assessorato']) && $params['id_assessorato'] != "") $parametri['id_assessorato'] = $params['id_assessorato'];
        if (isset($params['id_direzione']) && $params['id_direzione'] != "") $parametri['id_direzione'] = $params['id_direzione'];
        if (isset($params['id_servizio']) && $params['id_servizio'] != "") $parametri['id_servizio'] = $params['id_servizio'];
        if (isset($params['id']) && $params['id'] != "") $parametri['id'] = $params['id'];
        if (isset($params['nome']) && $params['nome'] != "") $parametri['nome'] = $params['nome'];

        //Richiama la funzione custom per la personalizzazione dei parametri
        if (function_exists($customFilterFunction)) $parametri = array_merge($parametri, $customFilterFunction($params));
        if (method_exists($this, $customFilterFunction)) $parametri = array_merge($parametri, $this->$customFilterFunction($params));

        //Richiama la classe di gestione degli oggetti gestiti dal modulo
        $objectClass = static::AA_MODULE_OBJECTS_CLASS;
        if (class_exists($objectClass)) {
            if (method_exists($objectClass, "Search")) $data = $objectClass::Search($parametri, $this->oUser);
            else {
                AA_Log::Log(__METHOD__ . " Errore: Funzione di ricerca non definita ($objectClass::Search)", 100);
                $data[1] = array();
                $data[0] = 0;
            }
        } else {
            AA_Log::Log(__METHOD__ . " Errore: Classe di gestione dei moduli non definita ($objectClass)", 100);
            $data[1] = array();
            $data[0] = 0;
        }

        foreach ($data[1] as $id => $object) {
            $struct = $object->GetStruct();
            $struttura_gest = $struct->GetAssessorato();
            if ($struct->GetDirezione(true) > 0) $struttura_gest .= " -> " . $struct->GetDirezione();
            if ($struct->GetServizio(true) > 0) $struttura_gest .= " -> " . $struct->GetServizio();

            #Stato
            if ($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) $status = "bozza";
            if ($object->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $status = "pubblicata";
            if ($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $status .= " revisionata";
            if ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $status .= " cestinata";

            #Dettagli
            if ($this->oUser->IsSuperUser() && $object->GetAggiornamento() != "") {
                //Aggiornamento
                $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento(true) . "</span>&nbsp;";

                //utente e log
                $lastLog = $object->GetLog()->GetLastLog();
                $details .= "<span class='AA_Label AA_Label_LightBlue' title=\"Nome dell'utente che ha compiuto l'ultima azione - Fai click per visualizzare il log delle azioni\"><span class='mdi mdi-account' onClick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetLogDlg', 'params': {id: " . $object->GetId() . ",object_class:'".get_class($object)."'}},'" . $this->GetId() . "');\">" . $lastLog['user'] . "</span>&nbsp;";

                //id
                $details .= "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
            } else {
                if ($object->GetAggiornamento() != "") $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento(true) . "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
            }

            if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) $details .= "&nbsp;<span class='AA_Label AA_Label_LightBlue' title=\" L'utente corrente non può apportare modifiche all'organismo\"><span class='mdi mdi-pencil-off'></span>&nbsp; sola lettura</span>";

            $object_name=$object->GetName();
            if(strlen($object_name) > 190) $object_name=mb_substr($object_name,0,190)."...";
            $newData = array(
                "id" => $object->GetId(),
                "tags" => "",
                "aggiornamento" => $object->GetAggiornamento(),
                "denominazione" => $object_name,
                "pretitolo" => "",
                "sottotitolo" => $struttura_gest,
                "stato" => $status,
                "dettagli" => $details,
                "module_id" => $this->GetId()
            );

            if (method_exists($this, $customTemplateDataFunction)) 
            {
                $result=$this->$customTemplateDataFunction($newData, $object);
                if($result['pretitolo'] !="" && strpos($result['pretitolo'],"span") === false)
                {
                    $result['pretitolo']="<span class='AA_Label AA_Label_Blue_Simo'>".$result['pretitolo']."</span>";
                }
                $templateData[] = $result;
            } else if (function_exists($customTemplateDataFunction)) {
                $templateData[] = $customTemplateDataFunction($newData, $object);
            } else {
                $templateData[] = $newData;
            }
        }

        return array($data[0], $templateData);
    }

    //Restituisce la lista delle bozze (dati - da specializzare)
    public function GetDataSectionBozze_List($params = array())
    {
        return $this->GetDataGenericSectionBozze_List($params);
    }

    //Template section bozze content
    protected function TemplateGenericSection_Bozze($params, $contentData = null)
    {
        $content = new AA_GenericPagedSectionTemplate(static::AA_UI_PREFIX . "_" . static::AA_ID_SECTION_BOZZE, $this->GetId());

        //custom items templates
        if (is_array($this->aSectionItemTemplates)) {
            if (isset($this->aSectionItemTemplates[static::AA_ID_SECTION_BOZZE]) && $this->aSectionItemTemplates[static::AA_ID_SECTION_BOZZE] != "") {
                $content->SetContentBoxTemplate($this->aSectionItemTemplates[static::AA_ID_SECTION_BOZZE]);
            }
        }

        $content->EnablePager();
        $content->EnablePaging();
        $content->SetPagerItemForPage(10);
        $content->EnableFiltering();
        $content->EnableAddNew();
        if(isset($params['enableAddNewMultiFromCsv']) && $params['enableAddNewMultiFromCsv'] == true)
        {
            $content->EnableAddNewMulti();
        }
        if(isset($params['enableAddNewMultiFromCsvDlgTask']) && $params['enableAddNewMultiFromCsvDlgTask'] != "")
        {
            $content->SetAddNewMultiDlgTask($params['enableAddNewMultiFromCsvDlgTask']);
        }
        else
        {
            $content->SetAddNewMultiDlgTask(static::AA_UI_TASK_ADDNEWMULTI_DLG);
        }
        

        $content->SetAddNewDlgTask(static::AA_UI_TASK_ADDNEW_DLG);
        $content->SetFilterDlgTask(static::AA_UI_TASK_BOZZE_FILTER_DLG);
        $content->ViewExportFunctions();

        $content->SetSectionName(static::AA_UI_SECTION_BOZZE_NAME);

        $content->ViewDetail();

        if (!isset($params['cestinate']) || $params['cestinate'] == 0) {
            $content->ViewTrash();
            $content->SetTrashHandlerParams(array("task" => static::AA_UI_TASK_TRASH_DLG));
            $content->ViewPublish();
            $content->SetPublishHandlerParams(array("task" => static::AA_UI_TASK_PUBLISH_DLG));
            $content->ViewReassign();
            $content->SetReassignHandlerParams(array("task" => static::AA_UI_TASK_REASSIGN_DLG));
        } else {
            $content->SetSectionName(static::AA_UI_SECTION_BOZZE_CESTINATE_NAME);
            $content->ViewResume();
            $content->SetResumeHandlerParams(array("task" => static::AA_UI_TASK_RESUME_DLG));
            $content->ViewDelete();
            $content->SetDeleteHandlerParams(array("task" => static::AA_UI_TASK_DELETE_DLG));
            $content->HidePublish();
            $content->HideReassign();
            $content->EnableAddNew(false);
        }

        if($this->DataSectionIsFiltered($params))
        {
            $content->SetPagerFiltered(true);
        }

        if ($contentData == null) {
            $params['count'] = 10;
            $contentData = $this->GetDataSectionBozze_List($params);
        }

        //AA_Log::Log(__METHOD__ . " - oggetti trovati: ".$contentData[0]." - oggetti nell'array: ".sizeof($contentData[1]), 100);

        $content->SetContentBoxData($contentData[1]);
        $content->SetPagerItemCount($contentData[0]);
        $content->EnableMultiSelect();
        $content->EnableSelect();

        return $content;
    }

    //Template sezione bozze (da specializzare)
    public function TemplateSection_Bozze($params = array())
    {
        $content = $this->TemplateGenericSection_Bozze($params);
        return $content->toObject();
    }

    //Template Detail
    public function TemplateGenericSection_Detail($params)
    {
        $id = static::AA_UI_PREFIX . "_" . static::AA_ID_SECTION_DETAIL . "_";
        $objectClass = static::AA_MODULE_OBJECTS_CLASS;
        if (class_exists($objectClass)) {
            $object = new $objectClass($params['id'], $this->oUser);
        } else {
            return new AA_JSON_Template_Template(
                static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX,
                array(
                    "update_time" => Date("Y-m-d H:i:s"),
                    "name" => static::AA_UI_SECTION_DETAIL_NAME,
                    "type" => "clean", "template" => "Tipo di elemento non gestito."
                )
            );
        }

        #Stato
        if ($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) $status = "bozza";
        if ($object->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $status = "pubblicata";
        if ($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $status .= " revisionata";
        if ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $status .= " cestinata";
        $status = "<span class='AA_Label AA_Label_LightBlue' title='Stato scheda'>" . $status . "</span>";

        #Dettagli
        if ($this->oUser->IsSuperUser() && $object->GetAggiornamento() != "") {
            //Aggiornamento
            $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento() . "</span>&nbsp;";

            //utente
            $lastLog = $object->GetLog()->GetLastLog();
            //AA_Log::Log(__METHOD__." - ".print_r($lastLog,true),100);
            $details .= "<span class='AA_Label AA_Label_LightBlue' title=\"Nome dell'utente che ha compiuto l'ultima azione - Fai click per visualizzare il log delle azioni\"><span class='mdi mdi-account' onClick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetLogDlg', 'params': {id: " . $object->GetId() . ",object_class:'".get_class($object)."'}},'" . $this->GetId() . "');\">" . $lastLog['user'] . "</span>&nbsp;";
            $details .= "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
        } else {
            if ($object->GetAggiornamento() != "") $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento() . "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
        }

        $perms = $object->GetUserCaps($this->oUser);
        if($params['readonly']) $perms=AA_Const::AA_PERMS_READ;
        
        $id_org = $object->GetID();

        if (($perms & AA_Const::AA_PERMS_WRITE) == 0) $details .= "&nbsp;<span class='AA_Label AA_Label_LightBlue' title=\" L'utente corrente non può apportare modifiche all'oggetto\"><span class='mdi mdi-pencil-off'></span>&nbsp; sola lettura</span>";

        $header = new AA_JSON_Template_Layout($id . "Header" . "_$id_org", array("type" => "clean", "height" => 38, "css" => "AA_SectionContentHeader"));

        //Scheda generale di default
        if (!is_array($this->aSectionItemTemplates)) {
            if ($this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL] == "") {
                $this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL] = array(array("id" => $id . "Generale_Tab_" . $id_org, "value" => "Generale", "tooltip" => "Dati generali", "template" => "TemplateGenericDettaglio_Generale_Tab"));
            }
        }

        //AA_Log::Log(__METHOD__." - ".print_r($this->aSectionItemTemplates,true),100);

        $layout_tab=new AA_JSON_Template_Layout($id . "Layout_TabBar" . "_$id_org",array("type"=>"clean","minWidth"=>500));
        
        $gravity_tabbar=sizeof($this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL]);
        //if(sizeof($this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL]) > 1) $gravity_tabbar=sizeof($this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL]);

        $layout_tab->addCol(new AA_JSON_Template_Generic($id . "TabBar" . "_$id_org", array(
            "view" => "tabbar",
            "gravity"=>$gravity_tabbar,
            "borderless" => true,
            "value" => $this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL][0]['id'],
            "css" => "AA_Header_TabBar",
            "multiview" => true,
            "view_id" => $id . "Multiview" . "_$id_org",
            "options" => $this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL]
        )));
        $layout_tab->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer","gravity"=>2/$gravity_tabbar)));
        $header->addCol($layout_tab);

        $header->addCol(new AA_JSON_Template_Generic($id . "Detail" . "_$id_org", array(
            "view" => "template",
            "borderless" => true,
            "css" => "AA_SectionContentHeader",
            "minWidth" => 500,
            "template" => "<div style='display: flex; width:100%; height: 100%; justify-content: center; align-items: center;'>#status#<span>&nbsp;&nbsp;</span><span>#detail#</span></div>",
            "data" => array("detail" => $details, "status" => $status)
        )));

        $layout_toolbar=new AA_JSON_Template_Layout($id . "Layout_ToolBar" . "_$id_org",array("type"=>"clean","minWidth"=>500));

        $toolbar = new AA_JSON_Template_Toolbar($id . "_Toolbar" . "_$id_org", array(
            "type" => "clean",
            "css" => array("background" => "#ebf0fa", "border-color" => "transparent")
        ));

        //Inserisce il pulsante di pubblicazione
        if (($perms & AA_Const::AA_PERMS_PUBLISH) > 0 && ($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) > 0 && ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) == 0) {
            $menu_data[] = array(
                "id" => $this->id . "_Publish" . "_$id_org",
                "value" => "Pubblica",
                "tooltip" => "Pubblica l'elemento",
                "icon" => "mdi mdi-certificate",
                "module_id" => $this->id,
                "handler" => "sectionActionMenu.publish",
                "handler_params" => array("task" => static::AA_UI_TASK_PUBLISH_DLG, "object_id" => $object->GetID())
            );
        }

        //Inserisce il pulsante di riassegnazione ripristino
        if (($perms & AA_Const::AA_PERMS_WRITE) > 0) {
            if (($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) == 0 && !isset($params['disable_reassign'])) {
                //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
                //$menu_spacer=true;
                $menu_data[] = array(
                    "id" => $this->id . "_Reassign" . "_$id_org",
                    "value" => "Riassegna",
                    "tooltip" => "Riassegna l'elemento",
                    "icon" => "mdi mdi-share-all",
                    "module_id" => $this->id,
                    "handler" => "sectionActionMenu.reassign",
                    "handler_params" => array("task" => static::AA_UI_TASK_REASSIGN_DLG, "object_id" => $object->GetID())
                );
            }
            if (($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) > 0) {
                $menu_data[] = array(
                    "id" => $id . "_Resume" . "_$id_org",
                    "value" => "Ripristina",
                    "tooltip" => "Ripristina gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                    "icon" => "mdi mdi-recycle",
                    "module_id" => $this->id,
                    "handler" => "sectionActionMenu.resume",
                    "handler_params" => array("task" => static::AA_UI_TASK_RESUME_DLG, "object_id" => $object->GetID())
                );
            }
        }

        //Inserisce le voci di esportazione
        //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
        //$menu_spacer=true;
        if(!isset($params['disable_SaveAsPdf']))
        {
            $menu_data[] = array(
                "id" => $id . "_SaveAsPdf" . "_$id_org",
                "value" => "Esporta in pdf",
                "tooltip" => "Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file pdf",
                "icon" => "mdi mdi-file-pdf-box",
                "module_id" => $this->id,
                "handler" => "sectionActionMenu.saveAsPdf",
                "handler_params" => array("task" => static::AA_UI_TASK_SAVEASPDF_DLG, "object_id" => $object->GetID())
            );    
        }
        if(!isset($params['disable_SaveAsCsv']))
        {
            $menu_data[] = array(
                "id" => $id . "_SaveAsCsv" . "_$id_org",
                "value" => "Esporta in csv",
                "tooltip" => "Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file csv",
                "icon" => "mdi mdi-file-table",
                "module_id" => $this->id,
                "handler" => "sectionActionMenu.saveAsCsv",
                "handler_params" => array("task" => static::AA_UI_TASK_SAVEASCSV_DLG, "object_id" => $object->GetID())
            );
        }
        #-------------------------------------

        //Inserisce la voce di eliminazione
        if (($perms & AA_Const::AA_PERMS_DELETE) > 0 && !isset($params['disable_trash']) && !(isset($params['disable_public_trash']) && $object->GetStatus()&AA_Const::AA_STATUS_PUBBLICATA)) {
            if (($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) == 0) {
                //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
                //$menu_spacer=true;

                $menu_data[] = array(
                    "id" => $id . "_Trash" . "_$id_org",
                    "value" => "Cestina",
                    "css" => "AA_Menu_Red",
                    "tooltip" => "Cestina l'elemento",
                    "icon" => "mdi mdi-trash-can",
                    "module_id" => $this->id,
                    "handler" => "sectionActionMenu.trash",
                    "handler_params" => array("task" => static::AA_UI_TASK_TRASH_DLG, "object_id" => $object->GetID())
                );
            } else {

                $menu_data[] = array(
                    "id" => $id . "_Delete" . "_$id_org",
                    "value" => "Elimina",
                    "css" => "AA_Menu_Red",
                    "tooltip" => "Elimina definitivamente l'elemento",
                    "icon" => "mdi mdi-trash-can",
                    "module_id" => $this->id,
                    "handler" => "sectionActionMenu.delete",
                    "handler_params" => array("task" => static::AA_UI_TASK_DELETE_DLG, "object_id" => $object->GetID())
                );
            }
        }

        if(!isset($params['disable_MenuAzioni']))
        {
            //Azioni
            $scriptAzioni = "try{"
                . "let azioni_btn=$$('" . $id . "_Azioni_btn_$id_org');"
                . "if(azioni_btn){"
                . "let azioni_menu=webix.ui(azioni_btn.config.menu_data);"
                . "if(azioni_menu){"
                . "azioni_menu.setContext(azioni_btn);"
                . "azioni_menu.show(azioni_btn.\$view);"
                . "}"
                . "}"
                . "}catch(msg){console.error('" . $id . "_Azioni_btn_$id_org',this,msg);AA_MainApp.ui.alert(msg);}";
            $azioni_btn = new AA_JSON_Template_Generic($id . "_Azioni_btn" . "_$id_org", array(
                "view" => "button",
                "type" => "icon",
                "icon" => "mdi mdi-dots-vertical",
                "label" => "Azioni",
                "align" => "right",
                "autowidth" => true,
                "menu_data" => new AA_JSON_Template_Generic($id . "_ActionMenu" . "_$id_org", array("view" => "contextmenu", "data" => $menu_data, "module_id" => $this->GetId(), "on" => array("onItemClick" => "AA_MainApp.utils.getEventHandler('onDetailMenuItemClick','" . $this->GetId() . "')"))),
                "tooltip" => "Visualizza le azioni disponibili",
                "click" => $scriptAzioni
            ));

            $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer")));
            $toolbar->addElement($azioni_btn);
        }
        else $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer")));

        $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 15)));

        $layout_toolbar->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer")));
        $layout_toolbar->addCol($toolbar);

        $header->addCol($layout_toolbar);

        //Content box
        $name=$object->GetName();
        if(strlen($name)>225) $name=mb_substr($name,0,217)."...";

        $content = new AA_JSON_Template_Layout(
            static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX,
            array(
                "type" => "clean",
                "name" => $name,
                "filtered" => true
            )
        );
        $content->AddRow($header);

        $multiview = new AA_JSON_Template_Multiview($id . "Multiview" . "_$id_org", array(
            "type" => "clean",
            "css" => "AA_Detail_Content"
        ));

        $bDefaultChangeEventAdded=false;
        foreach ($this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL] as $curTab) 
        {
            if(isset($curTab['enable_preview']))
            {
                if(isset($curTab['preview_template']))
                {
                    
                    if (method_exists($this, $curTab['preview_template']) && $curTab['preview_template'] != "" && is_string($curTab['preview_template'])) 
                    {
                        $multiview->addCell($this->{$curTab['preview_template']}($object));
                    } 
                    else
                    {
                        $multiview->addCell(new AA_JSON_Template_Template($curTab['id'],array("filtered"=>true,"preview"=>true,"template"=>"<div style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; font-size: larger; font-weight: 600; color: rgb(0, 102, 153);' class='blinking'>Caricamento in corso...</div>")));    
                    }  
                }
                else
                {
                    $multiview->addCell(new AA_JSON_Template_Template($curTab['id'],array("filtered"=>true,"preview"=>true,"template"=>"<div style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; font-size: larger; font-weight: 600; color: rgb(0, 102, 153);' class='blinking'>Caricamento in corso...</div>")));
                }

                if(!$bDefaultChangeEventAdded)
                {
                    $multiview->AddEventHandler("onViewChange","onDetailViewChange",null,$this->GetId());
                    $bDefaultChangeEventAdded=true;
                }
            }
            else
            {
                if(isset($curTab['template']))
                {
                    if (method_exists($this, $curTab['template']) && $curTab['template'] != "" && is_string($curTab['template'])) {
                        $multiview->addCell($this->{$curTab['template']}($object));
                    }    
                }    
            }
        }
        $content->AddRow($multiview);

        return $content;
    }

    //Template Generic Tabbed Section
    public function TemplateGenericTabbedSection($id_section="",$object=null,$params=null)
    {
        $section=null;
        if(isset($this->sections[$id_section]))
        {
            $section=$this->sections[$id_section];
            $id=$section->GetViewId();
        }
        else
        {
            AA_Log::Log(__METHOD__." - sezione non trovata: ".$id_section,100);
            return new AA_JSON_Template_Template("GenericVoidTemplate_".uniqid(),array("type"=>"clean","template"=>"<div style='display:flex; justify-content: center;align-items: center;width:100%;height:100%'>&nbsp;</div>"));
        }

        if($object==null && is_array($params) && isset($params['id']))
        {
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;
            if (class_exists($objectClass)) {
                $object = new $objectClass($params['id'], $this->oUser);
            }
        }

        if(!is_array($this->aSectionItemTemplates[$id_section]))
        {
            if(method_exists($this,$this->aSectionItemTemplates[$id_section]))
            {
                return $this->{(string)$this->aSectionItemTemplates[$id_section]}($object);
            }
            else
            {
                AA_Log::Log(__METHOD__." - templates di sezione non impostati come array, utilizzare la funzione SetSectionItemTemplates passando un array di array come secondo parametro. ".print_r($this->aSectionItemTemplates[$id_section],true),100);
                return new AA_JSON_Template_Template($id,array("type"=>"clean","template"=>"<div style='display:flex; justify-content: center;align-items: center;width:100%;height:100%'>".__METHOD__." - templates di sezione non impostati come array, utilizzare la funzione SetSectionItemTemplates passando un array di array come secondo parametro. </div>"));
            }
        }

        $id_org=uniqid();
        $details="";
        if(isset($params['details'])) $details=$params['details'];

        $status="";
        if(isset($params['status'])) $details=$params['status'];

        if($object != null && $object instanceof AA_Object_V2)
        {
            $id_org = $object->GetID();

            if(!isset($params['status']))
            {
                #Stato
                if ($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) $status = "bozza";
                if ($object->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $status = "pubblicata";
                if ($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $status .= " revisionata";
                if ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $status .= " cestinata";
                $status = "<span class='AA_Label AA_Label_LightBlue' title='Stato oggetto'>" . $status . "</span>";
            }

            if(!isset($params['details']))
            {
                #Dettagli
                if ($this->oUser->IsSuperUser() && $object->GetAggiornamento() != "") {
                    //Aggiornamento
                    $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento() . "</span>&nbsp;";

                    //utente
                    $lastLog = $object->GetLog()->GetLastLog();
                    //AA_Log::Log(__METHOD__." - ".print_r($lastLog,true),100);
                    $details .= "<span class='AA_Label AA_Label_LightBlue' title=\"Nome dell'utente che ha compiuto l'ultima azione - Fai click per visualizzare il log delle azioni\"><span class='mdi mdi-account' onClick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetLogDlg', 'params': {id: " . $object->GetId() . ", object_class:'".get_class($object)."'}},'" . $this->GetId() . "');\">" . $lastLog['user'] . "</span>&nbsp;";
                    $details .= "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
                } else {
                    if ($object->GetAggiornamento() != "") $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento() . "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
                }

                $perms = $object->GetUserCaps($this->oUser);
                if($params['readonly']) $perms=AA_Const::AA_PERMS_READ;

                if (($perms & AA_Const::AA_PERMS_WRITE) == 0) $details .= "&nbsp;<span class='AA_Label AA_Label_LightBlue' title=\" L'utente corrente non può apportare modifiche all'oggetto\"><span class='mdi mdi-pencil-off'></span>&nbsp; sola lettura</span>";
            }
        }

        $header = new AA_JSON_Template_Layout($id . "Header" . "_$id_org", array("type" => "clean", "height" => 38, "css" => "AA_SectionContentHeader"));

        $layout_tab=new AA_JSON_Template_Layout($id . "Layout_TabBar" . "_$id_org",array("type"=>"clean","minWidth"=>500));
        
        $gravity_tabbar=sizeof($this->aSectionItemTemplates[$id_section]);

        $layout_tab->addCol(new AA_JSON_Template_Generic($id . "TabBar" . "_$id_org", array(
            "view" => "tabbar",
            "gravity"=>$gravity_tabbar,
            "borderless" => true,
            "value" => $this->aSectionItemTemplates[$id_section][0]['id'],
            "css" => "AA_Header_TabBar",
            "multiview" => true,
            "view_id" => $id . "Multiview" . "_$id_org",
            "options" => $this->aSectionItemTemplates[$id_section]
        )));

        $layout_tab->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer","gravity"=>2/$gravity_tabbar)));
        $header->addCol($layout_tab);

        $header->addCol(new AA_JSON_Template_Generic($id . "Detail" . "_$id_org", array(
            "view" => "template",
            "borderless" => true,
            "css" => "AA_SectionContentHeader",
            "minWidth" => 500,
            "template" => "<div style='display: flex; width:100%; height: 100%; justify-content: center; align-items: center;'>#status#<span>&nbsp;&nbsp;</span><span>#detail#</span></div>",
            "data" => array("detail" => $details, "status" => $status)
        )));

        $layout_toolbar=new AA_JSON_Template_Layout($id . "Layout_ToolBar" . "_$id_org",array("type"=>"clean","minWidth"=>500));

        $toolbar = new AA_JSON_Template_Toolbar($id . "_Toolbar" . "_$id_org", array(
            "type" => "clean",
            "css" => array("background" => "#ebf0fa", "border-color" => "transparent")
        ));

        if($object != null && $object instanceof AA_Object_V2 && isset($params['common_ctrls']))
        {
            $show_action_menu=false;
            if(isset($params['common_ctrls']['publish']))
            {
                //Inserisce il pulsante di pubblicazione
                if (($perms & AA_Const::AA_PERMS_PUBLISH) > 0 && ($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) > 0 && ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) == 0) {
                    $menu_data[] = array(
                        "id" => $this->id . "_Publish" . "_$id_org",
                        "value" => "Pubblica",
                        "tooltip" => "Pubblica l'elemento",
                        "icon" => "mdi mdi-certificate",
                        "module_id" => $this->id,
                        "handler" => "sectionActionMenu.publish",
                        "handler_params" => array("task" => static::AA_UI_TASK_PUBLISH_DLG, "object_id" => $object->GetID())
                    );
                    $show_action_menu=true;
                }
            }

            if(isset($params['common_ctrls']['reassign']))
            {
                //Inserisce il pulsante di riassegnazione ripristino
                if (($perms & AA_Const::AA_PERMS_WRITE) > 0 && ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) == 0)
                {
                    //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
                    //$menu_spacer=true;
                    $menu_data[] = array(
                        "id" => $this->id . "_Reassign" . "_$id_org",
                        "value" => "Riassegna",
                        "tooltip" => "Riassegna l'elemento",
                        "icon" => "mdi mdi-share-all",
                        "module_id" => $this->id,
                        "handler" => "sectionActionMenu.reassign",
                        "handler_params" => array("task" => static::AA_UI_TASK_REASSIGN_DLG, "object_id" => $object->GetID())
                    );
                    $show_action_menu=true;
                }
            }

            if(isset($params['common_ctrls']['resume']) && ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) > 0)
            {
                $menu_data[] = array(
                    "id" => $id . "_Resume" . "_$id_org",
                    "value" => "Ripristina",
                    "tooltip" => "Ripristina gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                    "icon" => "mdi mdi-recycle",
                    "module_id" => $this->id,
                    "handler" => "sectionActionMenu.resume",
                    "handler_params" => array("task" => static::AA_UI_TASK_RESUME_DLG, "object_id" => $object->GetID())
                );
                $show_action_menu=true;
            }

            //Inserisce le voci di esportazione
            //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
            //$menu_spacer=true;
            if(isset($params['common_ctrls']['save_as_pdf']))
            {
                $menu_data[] = array(
                    "id" => $id . "_SaveAsPdf" . "_$id_org",
                    "value" => "Esporta in pdf",
                    "tooltip" => "Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file pdf",
                    "icon" => "mdi mdi-file-pdf-box",
                    "module_id" => $this->id,
                    "handler" => "sectionActionMenu.saveAsPdf",
                    "handler_params" => array("task" => static::AA_UI_TASK_SAVEASPDF_DLG, "object_id" => $object->GetID())
                );
                $show_action_menu=true;
            }

            if(isset($params['common_ctrls']['save_as_csv']))
            {
                $menu_data[] = array(
                    "id" => $id . "_SaveAsCsv" . "_$id_org",
                    "value" => "Esporta in csv",
                    "tooltip" => "Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file csv",
                    "icon" => "mdi mdi-file-table",
                    "module_id" => $this->id,
                    "handler" => "sectionActionMenu.saveAsCsv",
                    "handler_params" => array("task" => static::AA_UI_TASK_SAVEASCSV_DLG, "object_id" => $object->GetID())
                );
                $show_action_menu=true;
            }
            #-------------------------------------

            //Inserisce la voce di eliminazione
            if (isset($params['common_ctrls']['trash']) && ($perms & AA_Const::AA_PERMS_DELETE) > 0) 
            {
                if (($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) == 0) {
                    //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
                    //$menu_spacer=true;

                    $menu_data[] = array(
                        "id" => $id . "_Trash" . "_$id_org",
                        "value" => "Cestina",
                        "css" => "AA_Menu_Red",
                        "tooltip" => "Cestina l'elemento",
                        "icon" => "mdi mdi-trash-can",
                        "module_id" => $this->id,
                        "handler" => "sectionActionMenu.trash",
                        "handler_params" => array("task" => static::AA_UI_TASK_TRASH_DLG, "object_id" => $object->GetID())
                    );
                } else {

                    $menu_data[] = array(
                        "id" => $id . "_Delete" . "_$id_org",
                        "value" => "Elimina",
                        "css" => "AA_Menu_Red",
                        "tooltip" => "Elimina definitivamente l'elemento",
                        "icon" => "mdi mdi-trash-can",
                        "module_id" => $this->id,
                        "handler" => "sectionActionMenu.delete",
                        "handler_params" => array("task" => static::AA_UI_TASK_DELETE_DLG, "object_id" => $object->GetID())
                    );
                }
                $show_action_menu=true;
            }

            if($show_action_menu)
            {
                //Azioni
                $scriptAzioni = "try{"
                    . "let azioni_btn=$$('" . $id . "_Azioni_btn_$id_org');"
                    . "if(azioni_btn){"
                    . "let azioni_menu=webix.ui(azioni_btn.config.menu_data);"
                    . "if(azioni_menu){"
                    . "azioni_menu.setContext(azioni_btn);"
                    . "azioni_menu.show(azioni_btn.\$view);"
                    . "}"
                    . "}"
                    . "}catch(msg){console.error('" . $id . "_Azioni_btn_$id_org',this,msg);AA_MainApp.ui.alert(msg);}";
                $azioni_btn = new AA_JSON_Template_Generic($id . "_Azioni_btn" . "_$id_org", array(
                    "view" => "button",
                    "type" => "icon",
                    "icon" => "mdi mdi-dots-vertical",
                    "label" => "Azioni",
                    "align" => "right",
                    "autowidth" => true,
                    "menu_data" => new AA_JSON_Template_Generic($id . "_ActionMenu" . "_$id_org", array("view" => "contextmenu", "data" => $menu_data, "module_id" => $this->GetId(), "on" => array("onItemClick" => "AA_MainApp.utils.getEventHandler('onDetailMenuItemClick','" . $this->GetId() . "')"))),
                    "tooltip" => "Visualizza le azioni disponibili",
                    "click" => $scriptAzioni
                ));

                $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer")));
                $toolbar->addElement($azioni_btn);
                $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 15)));
            }
        }

        $layout_toolbar->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer")));
        $layout_toolbar->addCol($toolbar);

        $header->addCol($layout_toolbar);

        //Content box
        $content = new AA_JSON_Template_Layout(
            $id,
            array(
                "type" => "clean",
                "name" => $object->GetName(),
                "filtered" => true
            )
        );
        $content->AddRow($header);

        $multiview = new AA_JSON_Template_Multiview($id . "_Multiview" . "_$id_org", array(
            "type" => "clean",
            "css" => "AA_Detail_Content"
        ));

        $bDefaultChangeEventAdded=false;
        $ChangeViewHandler="onGenericSectionViewChange";
        if(isset($params['change_view_handler'])) $ChangeViewHandler=$params['change_view_handler'];
        foreach ($this->aSectionItemTemplates[$id_section] as $curTab) 
        {
            if(isset($curTab['enable_preview']))
            {
                if(isset($curTab['preview_template']))
                {
                    
                    if (method_exists($this, $curTab['preview_template']) && $curTab['preview_template'] != "" && is_string($curTab['preview_template'])) 
                    {
                        $multiview->addCell($this->{$curTab['preview_template']}($object));
                    } 
                    else
                    {
                        $multiview->addCell(new AA_JSON_Template_Template($curTab['id'],array("filtered"=>true,"preview"=>true,"template"=>"<div style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; font-size: larger; font-weight: 600; color: rgb(0, 102, 153);' class='blinking'>Caricamento in corso...</div>")));    
                    }  
                }
                else
                {
                    $multiview->addCell(new AA_JSON_Template_Template($curTab['id'],array("filtered"=>true,"preview"=>true,"template"=>"<div style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; font-size: larger; font-weight: 600; color: rgb(0, 102, 153);' class='blinking'>Caricamento in corso...</div>")));
                }

                if(!$bDefaultChangeEventAdded)
                {
                    $multiview->AddEventHandler("onViewChange",$ChangeViewHandler,null,$this->GetId());
                    $bDefaultChangeEventAdded=true;
                }
            }
            else
            {
                if(isset($curTab['template']))
                {
                    if (method_exists($this, $curTab['template']) && $curTab['template'] != "" && is_string($curTab['template'])) {
                        $multiview->addCell($this->{$curTab['template']}($object));
                    }    
                }    
            }
        }
        $content->AddRow($multiview);

        return $content;
    }

    //Template generic section detail, tab generale
    public function TemplateGenericDettaglio_Generale_Tab($object = null)
    {
        $sectionTemplate = $this->GetSectionItemTemplate(static::AA_ID_SECTION_DETAIL);
        if (!is_array($sectionTemplate)) {
            $id = static::AA_UI_PREFIX . "_" . static::AA_ID_SECTION_DETAIL . "_Generale_Tab_" . date("Y-m-d_h:i:s");
        } else {
            $id = $sectionTemplate[0]['id'];
        }

        if (!($object instanceof AA_Object_V2)) return new AA_JSON_Template_Template($id, array("template" => "Dati non validi"));

        //$id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_Generale_Tab_".$object->GetId();

        $layout = $this->TemplateGenericDettaglio_Header_Generale_Tab($object, $id);

        $rows_fixed_height = 50;

        //Nome
        $value = $object->GetName();
        if ($value == "") $value = "n.d.";
        $nome = new AA_JSON_Template_Template($id . "_Denominazione", array(
            "template" => "<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data" => array("title" => "Denominazione:", "value" => $value)
        ));

        //Descrizione
        $value = $object->GetDescr();
        if ($value == "") $value = "n.d.";
        $descr = new AA_JSON_Template_Template($id . "_Descrizione", array(
            "template" => "<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data" => array("title" => "Descrizione:", "value" => $value)
        ));

        //Prima riga
        $riga = new AA_JSON_Template_Layout($id . "_FirstRow", array("height" => $rows_fixed_height));
        $riga->AddCol($nome);
        $layout->AddRow($riga);

        //seconda riga
        $riga = new AA_JSON_Template_Layout($id . "_SecondRow");
        $riga->AddCol($descr);
        $layout->AddRow($riga);

        return $layout;
    }

    //Template generic section detail, tab generale header
    public function TemplateGenericDettaglio_Header_Generale_Tab($object = null, $id = "",$header_content=null,$bModify=null)
    {
        if (!($object instanceof AA_Object_V2)) return new AA_JSON_Template_Template($id, array("template" => "Dati non validi"));

        $layout = new AA_JSON_Template_Layout($id, array("type" => "clean"));

        $toolbar = new AA_JSON_Template_Toolbar($id . "_Toolbar", array("height" => 38, "css" => array("border-bottom" => "1px solid #dadee0 !important")));

        $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 120)));
        //$toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer")));
        if($header_content)
        {
            if($header_content instanceof AA_JSON_Template_Generic) $toolbar->addElement($header_content);
            if(is_string($header_content))
            {
                $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer")));
                $toolbar->addElement(new AA_JSON_Template_Template($id."_header_content",array("type"=>"clean","template"=>$header_content)));
                $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer")));
            } 
        }
        else
        {
            $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer")));
        }

        //Pulsante di modifica
        if(!isset($bModify))
        {
            $canModify = false;
            if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0) $canModify = true;    
        }
        else $canModify=$bModify;
        if ($canModify) {
            $modify_btn = new AA_JSON_Template_Generic($id . "_Modify_btn", array(
                "view" => "button",
                "type" => "icon",
                "icon" => "mdi mdi-pencil",
                "label" => "Modifica",
                "align" => "right",
                "css"=>"webix_primary",
                "width" => 120,
                "tooltip" => "Modifica le informazioni generali",
                "click" => "AA_MainApp.utils.callHandler('dlg', {task:\"" . static::AA_UI_TASK_MODIFY_DLG . "\", params: [{id: " . $object->GetId() . "}]},'$this->id')"
            ));
            $toolbar->AddElement($modify_btn);
        }

        $layout->addRow($toolbar);

        return $layout;
    }
}
