<?php
class AA_SicarModule extends AA_GenericModule
{
    const AA_UI_PREFIX = "AA_Sicar";
    
    // Id modulo
    const AA_ID_MODULE = "AA_MODULE_SICAR";
    
    // Main ui layout box
    const AA_UI_MODULE_MAIN_BOX = "AA_Sicar_module_layout";

    //ui id sezione dettaglio
    const AA_UI_DETAIL_GENERALE_BOX = "Generale_Box";

    const AA_UI_SECTION_BOZZE_NAME="Alloggi (bozze)";
    const AA_UI_SECTION_PUBBLICATE_NAME="Alloggi (pubblicate)";
    const AA_UI_SECTION_PUBBLICATE_ICON="mdi mdi-home-city";

    
    //id sezione gestione immobili
    const AA_ID_SECTION_IMMOBILI = "GestImmobili";
    const AA_UI_SECTION_IMMOBILI_BOX = "GestImmobiliBox";
    const AA_UI_SECTION_IMMOBILI_NAME = "Gestione immobili";
    const AA_UI_SECTION_IMMOBILI_ICON = "mdi mdi-office-building-marker";
    const AA_UI_SECTION_IMMOBILI_DESC = "Visualizza e gestisci gli immobili";
    const AA_UI_SECTION_IMMOBILI_TOOLTIP = "Visualizza e gestisci gli immobili";

    //id sezione gestione enti
    const AA_ID_SECTION_ENTI = "GestEnti";
    const AA_UI_SECTION_ENTI_BOX = "GestEntiBox";
    const AA_UI_SECTION_ENTI_NAME = "Gestione enti";
    const AA_UI_SECTION_ENTI_ICON = "mdi mdi-home-group";
    const AA_UI_SECTION_ENTI_DESC = "Visualizza e gestisci gli enti";
    const AA_UI_SECTION_ENTI_TOOLTIP = "Visualizza e gestisci gli enti";

    //operatori ente
    const AA_UI_WND_OPERATORI_ENTE = "SicarOperatoriEnteWnd";
    const AA_UI_TABLE_OPERATORI_ENTE = "TableOperatoriEnte";

    //id sezione gestione nuclei
    const AA_ID_SECTION_NUCLEI = "GestNuclei";
    const AA_UI_SECTION_NUCLEI_BOX = "GestNucleiBox";
    const AA_UI_SECTION_NUCLEI_NAME = "Gestione nuclei";
    const AA_UI_SECTION_NUCLEI_ICON = "mdi mdi-account-group";
    const AA_UI_SECTION_NUCLEI_DESC = "Visualizza e gestisci i nuclei familiari";
    const AA_UI_SECTION_NUCLEI_TOOLTIP = "Visualizza e gestisci i nuclei familiari";
    const AA_UI_WND_SEARCH_NUCLEI = "SicarSearchWnd";
    const AA_UI_TABLE_SEARCH_NUCLEI = "TableSearchNuclei";
    const AA_UI_WND_DETAIL_NUCLEI = "SicarDetailNucleoWnd";

    //stato occupazione wnd
    const AA_UI_WND_DETAIL_STATO_OCCUPAZIONE_ALLOGGIO = "SicarDetailStatoOccupazioneAlloggioWnd";

    //id sezione finanziamenti
    const AA_ID_SECTION_FINANZIAMENTI = "GestFinanziamenti";
    const AA_UI_SECTION_FINANZIAMENTI_BOX = "GestFinanziamentiBox";
    const AA_UI_SECTION_FINANZIAMENTI_NAME = "Gestione finanziamenti";
    const AA_UI_SECTION_FINANZIAMENTI_ICON = "mdi mdi-cash-fast";
    const AA_UI_SECTION_FINANZIAMENTI_DESC = "Visualizza e gestisci i Finanziamenti";
    const AA_UI_SECTION_FINANZIAMENTI_TOOLTIP = "Visualizza e gestisci i Finanziamenti";

    //id sezione finanziamenti
    const AA_ID_SECTION_GRADUATORIE = "GestGraduatorie";
    const AA_UI_SECTION_GRADUATORIE_BOX = "GestGraduatorieBox";
    const AA_UI_SECTION_GRADUATORIE_NAME = "Gestione graduatorie";
    const AA_UI_SECTION_GRADUATORIE_ICON = "mdi mdi-format-list-numbered";
    const AA_UI_SECTION_GRADUATORIE_DESC = "Visualizza e gestisci le graduatorie";
    const AA_UI_SECTION_GRADUATORIE_TOOLTIP = "Visualizza e gestisci le graduatorie";

    //id sezione tables
    const AA_ID_SECTION_TABLES = "GestTables";
    const AA_UI_SECTION_TABLES_BOX = "GestTablesBox";
    const AA_UI_SECTION_TABLES_NAME = "Gestione Enti,immobili e nuclei";
    const AA_UI_SECTION_TABLES_ICON = "mdi mdi-table";

    //------- Sezione cruscotto -------
    //Id sezione
    const AA_ID_SECTION_DESKTOP="sicar_desktop";

    //nome sezione
    const AA_UI_SECTION_DESKTOP_NAME="Cruscotto";

    const AA_UI_SECTION_DESKTOP_BOX="Sicar_Desktop_Content_Box";

    const AA_UI_SECTION_DESKTOP_ICON="mdi mdi-desktop-classic";
    //------------------------------

    const AA_MODULE_OBJECTS_CLASS = "AA_SicarAlloggio";
    
    // Task per la gestione dei dialoghi standard
    const AA_UI_TASK_PUBBLICATE_FILTER_DLG = "GetSicarPubblicateFilterDlg";
    const AA_UI_TASK_BOZZE_FILTER_DLG = "GetSicarBozzeFilterDlg";
    const AA_UI_TASK_REASSIGN_DLG = "GetSicarReassignDlg";
    const AA_UI_TASK_PUBLISH_DLG = "GetSicarPublishDlg";
    const AA_UI_TASK_TRASH_DLG = "GetSicarTrashDlg";
    const AA_UI_TASK_RESUME_DLG = "GetSicarResumeDlg";
    const AA_UI_TASK_DELETE_DLG = "GetSicarDeleteDlg";
    const AA_UI_TASK_ADDNEW_DLG = "GetSicarAddNewDlg";
    const AA_UI_TASK_MODIFY_DLG = "GetSicarModifyDlg";
    
    // Task specifici per SICAR
    const AA_UI_TASK_GET_TIPOLOGIE = "GetSicarTipologie";
    const AA_UI_TASK_GET_UBICAZIONI = "GetSicarUbicazioni";
    const AA_UI_TASK_GET_ZONE_URBANISTICHE = "GetSicarZoneUrbanistiche";
    const AA_UI_TASK_GET_COMUNI = "GetSicarComuni";
    const AA_UI_TASK_EXPORT_CSV = "ExportSicarCsv";

    const AA_UI_TABLE_SEARCH_IMMOBILI = "TableSearchImmobili";
    const AA_UI_TABLE_SEARCH_ENTI = "TableSearchEnti";
    
    //ricerca immobili
    const AA_UI_WND_SEARCH_IMMOBILI = "SicarSearchWnd";
    const AA_UI_WND_DETAIL_IMMOBILI = "SicarDetailImmobileWnd";

    //interventi immobili
    const AA_UI_WND_INTERVENTI_IMMOBILE = "SicarInterventiImmobileWnd";
    const AA_UI_TABLE_INTERVENTI_IMMOBILE = "SicarInterventiImmobileTable";
    
    //ricerca enti
    const AA_UI_WND_SEARCH_ENTI = "SicarSearchEntiWnd";
    const AA_UI_WND_DETAIL_ENTI = "SicarDetailEnteWnd";

    public function __construct($user = null, $bDefaultSections = true)
    {
        if (!($user instanceof AA_User)) {
            $user = AA_User::GetCurrentUser();
        }
        
        parent::__construct($user, $bDefaultSections);
        
        // Registrazione dei task
        $taskManager = $this->GetTaskManager();
        
        // Task standard per la gestione degli oggetti
        $taskManager->RegisterTask("GetSicarPubblicateFilterDlg");
        $taskManager->RegisterTask("GetSicarBozzeFilterDlg");
        $taskManager->RegisterTask("GetSicarReassignDlg");
        $taskManager->RegisterTask("GetSicarPublishDlg");
        $taskManager->RegisterTask("GetSicarTrashDlg");
        $taskManager->RegisterTask("GetSicarResumeDlg");
        $taskManager->RegisterTask("GetSicarDeleteDlg");
        $taskManager->RegisterTask("GetSicarAddNewDlg");
        $taskManager->RegisterTask("GetSicarModifyDlg");
        
        //immobili
        $taskManager->RegisterTask("GetSicarAddNewImmobileDlg");
        $taskManager->RegisterTask("AddNewImmobileSicar");
        $taskManager->RegisterTask("GetSicarModifyImmobileDlg");
        $taskManager->RegisterTask("UpdateImmobileSicar");
        $taskManager->RegisterTask("GetSicarDeleteImmobileDlg");
        $taskManager->RegisterTask("DeleteImmobileSicar");
        $taskManager->RegisterTask("GetSicarDetailImmobileDlg");
        $taskManager->RegisterTask("GetSicarInterventiImmobileDlg");
        $taskManager->RegisterTask("GetSicarAddNewInterventoImmobileDlg");
        $taskManager->RegisterTask("AddNewInterventoImmobileSicar");
        $taskManager->RegisterTask("GetSicarModifyInterventoImmobileDlg");
        $taskManager->RegisterTask("UpdateInterventoImmobileSicar");
        $taskManager->RegisterTask("GetSicarDeleteInterventoImmobileDlg");
        $taskManager->RegisterTask("DeleteInterventoImmobileSicar");
        //$taskManager->RegisterTask("GetSicarModifyStatoInterventiImmobileDlg");
        //$taskManager->RegisterTask("GetSicarDeleteStatoInterventiImmobileDlg");

        //enti
        $taskManager->RegisterTask("GetSicarAddNewEnteDlg");
        $taskManager->RegisterTask("AddNewEnteSicar");
        $taskManager->RegisterTask("GetSicarModifyEnteDlg");
        $taskManager->RegisterTask("UpdateEnteSicar");
        $taskManager->RegisterTask("GetSicarOperatoriEnteDlg");
        $taskManager->RegisterTask("GetSicarSearchEntiDlg");

        //nuclei
        $taskManager->RegisterTask("GetSicarAddNewNucleoDlg");
        $taskManager->RegisterTask("AddNewNucleoSicar");
        $taskManager->RegisterTask("GetSicarModifyNucleoDlg");
        $taskManager->RegisterTask("UpdateNucleoSicar");
        $taskManager->RegisterTask("GetSicarSearchNucleiDlg");
        $taskManager->RegisterTask("GetSicarDeleteNucleoDlg");
        $taskManager->RegisterTask("DeleteNucleoSicar");

        //stato occupazione
        $taskManager->RegisterTask("GetSicarAddNewStatoOccupazioneAlloggioDlg");
        $taskManager->RegisterTask("AddNewStatoOccupazioneAlloggioSicar");
        $taskManager->RegisterTask("GetSicarModifyStatoOccupazioneAlloggioDlg");
        $taskManager->RegisterTask("UpdateStatoOccupazioneAlloggioSicar");
        $taskManager->RegisterTask("GetSicarDeleteStatoOccupazioneAlloggioDlg");
        $taskManager->RegisterTask("DeleteStatoOccupazioneAlloggioSicar");
        $taskManager->RegisterTask("GetSicarDetailStatoOccupazioneAlloggioDlg");

        //stato interventi
        $taskManager->RegisterTask("GetSicarAddNewStatoInterventiAlloggioDlg");
        $taskManager->RegisterTask("AddNewStatoInterventiAlloggioSicar");
        $taskManager->RegisterTask("GetSicarModifyStatoInterventiAlloggioDlg");
        $taskManager->RegisterTask("UpdateStatoInterventiAlloggioSicar");
        $taskManager->RegisterTask("GetSicarDeleteStatoInterventiAlloggioDlg");
        $taskManager->RegisterTask("DeleteStatoInterventiAlloggioSicar");
        $taskManager->RegisterTask("GetSicarDetailStatoInterventiAlloggioDlg");

        // Task per le operazioni CRUD
        $taskManager->RegisterTask("AddNewAlloggioSicar");        
        $taskManager->RegisterTask("UpdateSicar");
        $taskManager->RegisterTask("DeleteSicar");
        $taskManager->RegisterTask("PublishSicar");
        $taskManager->RegisterTask("TrashSicar");
        $taskManager->RegisterTask("ResumeSicar");
        $taskManager->RegisterTask("ReassignSicar");

        // Task specifici per SICAR
        $taskManager->RegisterTask("GetSicarTipologie");
        $taskManager->RegisterTask("GetSicarUbicazioni");
        $taskManager->RegisterTask("GetSicarZoneUrbanistiche");
        $taskManager->RegisterTask("GetSicarComuni");
        $taskManager->RegisterTask("ExportSicarCsv");
        $taskManager->RegisterTask("GetSicarListaCodiciIstat");
        $taskManager->RegisterTask("GetSicarSearchImmobiliDlg");
        
        //template dettaglio
        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL,array(
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateSicarDettaglio_Generale_Tab")
        ));

        #----------------------search immobili --------------------
        $this->AddObjectTemplate(static::AA_UI_WND_SEARCH_IMMOBILI."_".static::AA_UI_TABLE_SEARCH_IMMOBILI,"Template_DatatableSearchImmobili");
        #---------------------------------------------------------------

        #----------------------lista interventi immobili --------------------
        $this->AddObjectTemplate(static::AA_UI_WND_INTERVENTI_IMMOBILE."_".static::AA_UI_TABLE_INTERVENTI_IMMOBILE,"Template_DatatableInterventiImmobile");
        #---------------------------------------------------------------

        #----------------------search enti --------------------
        $this->AddObjectTemplate(static::AA_UI_WND_SEARCH_ENTI."_".static::AA_UI_TABLE_SEARCH_ENTI,"Template_DatatableSearchEnti");
        #---------------------------------------------------------------

        #---------------------- operatori ente --------------------
        $this->AddObjectTemplate(static::AA_UI_WND_OPERATORI_ENTE."_".static::AA_UI_TABLE_OPERATORI_ENTE,"Template_DatatableOperatoriEnte");
        #---------------------------------------------------------------

        #----------------------search nuclei --------------------
        $this->AddObjectTemplate(static::AA_UI_WND_SEARCH_NUCLEI."_".static::AA_UI_TABLE_SEARCH_NUCLEI,"Template_DatatableSearchNuclei");
        #---------------------------------------------------------------

        #------------------------------- desktop -----------------------
        $desktop=new AA_GenericModuleSection(static::AA_ID_SECTION_DESKTOP,static::AA_UI_SECTION_DESKTOP_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_DESKTOP_BOX,$this->GetId(),true,true,false,true,static::AA_UI_SECTION_DESKTOP_ICON,"TemplateSection_Desktop");
        $desktop->SetNavbarTemplate($this->TemplateGenericNavbar_Void(1,true)->toArray());
        $desktop->SetIcon(static::AA_UI_SECTION_DESKTOP_ICON);
        $this->AddSection($desktop);
        #---------------------------------------------------------------
        
        #----------------------- Gest immobili -------------------------
        $gest_immobili=new AA_GenericModuleSection(static::AA_ID_SECTION_IMMOBILI,static::AA_UI_SECTION_IMMOBILI_NAME,true,static::AA_UI_PREFIX."_".static::AA_ID_SECTION_IMMOBILI,$this->GetId(),false,true,false,false,static::AA_UI_SECTION_IMMOBILI_ICON,"TemplateSection_Immobili");
        $gest_immobili->SetNavbarTemplate(array($this->TemplateGenericNavbar_Section($desktop,1)->toArray(),$this->TemplateGenericNavbar_Section($this->GetSection(static::AA_ID_SECTION_PUBBLICATE),2,true)->toArray()));
        $this->AddSection($gest_immobili);
        #---------------------------------------------------------------

        #----------------------- Gest enti -----------------------------
        $section=new AA_GenericModuleSection(static::AA_ID_SECTION_ENTI,static::AA_UI_SECTION_ENTI_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_ENTI_BOX,$this->GetId(),false,true,false,false,static::AA_UI_SECTION_ENTI_ICON,"TemplateSection_Enti");
        $section->SetNavbarTemplate(array($this->TemplateGenericNavbar_Desktop(1,true,true)->toArray()));
        $this->AddSection($section);
        #---------------------------------------------------------------

        #----------------------- Gest nuclei ---------------------------
        $section=new AA_GenericModuleSection(static::AA_ID_SECTION_NUCLEI,static::AA_UI_SECTION_NUCLEI_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_NUCLEI_BOX,$this->GetId(),false,true,false,false,static::AA_UI_SECTION_NUCLEI_ICON,"TemplateSection_Nuclei");
        $section->SetNavbarTemplate(array($this->TemplateGenericNavbar_Desktop(1,true,true)->toArray()));
        $this->AddSection($section);
        #---------------------------------------------------------------

        #----------------------- Gest finanziamenti -------------------------
        $section=new AA_GenericModuleSection(static::AA_ID_SECTION_FINANZIAMENTI,static::AA_UI_SECTION_FINANZIAMENTI_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_FINANZIAMENTI_BOX,$this->GetId(),false,true,false,false,static::AA_UI_SECTION_FINANZIAMENTI_ICON,"TemplateSection_Finanziamenti");
        $section->SetNavbarTemplate(array($this->TemplateGenericNavbar_Desktop(1,true,true)->toArray()));
        $this->AddSection($section);
        #--------------------------------------------------------------------

        #----------------------- Gest graduatorie -------------------------
        $section=new AA_GenericModuleSection(static::AA_ID_SECTION_GRADUATORIE,static::AA_UI_SECTION_GRADUATORIE_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GRADUATORIE_BOX,$this->GetId(),false,true,false,false,static::AA_UI_SECTION_FINANZIAMENTI_ICON,"TemplateSection_Graduatorie");
        $section->SetNavbarTemplate(array($this->TemplateGenericNavbar_Desktop(1,true,true)->toArray()));
        $this->AddSection($section);
        #------------------------------------------------------------------

        $bozze=$this->GetSection(static::AA_ID_SECTION_BOZZE);
        $bozze->SetNavbarTemplate(array($this->TemplateGenericNavbar_Section($desktop,1)->toArray(),$this->TemplateGenericNavbar_Pubblicate(2,true)->toArray()));

        $pubblicate=$this->GetSection(static::AA_ID_SECTION_PUBBLICATE);
        $pubblicate->SetNavbarTemplate(array($this->TemplateGenericNavbar_Section($desktop,1)->toArray(),$this->TemplateGenericNavbar_Bozze(2)->toArray(),$this->TemplateGenericNavbar_Section($gest_immobili,3,true)->toArray()));  
    }
    
    //Navbar Desktop
    protected function TemplateGenericNavbar_Desktop($level = 1, $last = false, $refresh_view = true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(
            "",
            array(
                "type" => "clean",
                "section_id" => static::AA_ID_SECTION_DESKTOP,
                "module_id" => $this->GetId(),
                "refresh_view" => $refresh_view,
                "tooltip" => "Fai click per tornare al dekstop",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" . static::AA_UI_PREFIX . "_Navbar_Link_" . static::AA_ID_SECTION_DESKTOP . "' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"".static::AA_ID_SECTION_DESKTOP."\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => static::AA_UI_SECTION_DESKTOP_NAME, "icon" => static::AA_UI_SECTION_DESKTOP_ICON, "class" => $class)
            )
        );
        return $navbar;
    }
    
    //Layout del modulo
    function TemplateLayout()
    {
        return $this->TemplateGenericLayout();
    }
    
    //Template placeholder
    public function TemplateSection_Placeholder()
    {
        return $this->TemplateGenericSection_Placeholder();
    }

    // Restituisce i dati delle bozze
    public function GetDataSectionBozze_List($params = array())
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            AA_Log::Log(__METHOD__ . " - ERRORE: l'utente corrente: " . $this->oUser->GetUserName() . " non è abilitato alla visualizzazione delle bozze.", 100);
            return array();
        }

        // Recupera immobili in stato bozza
        return $this->GetDataGenericSectionBozze_List($params,"GetDataSectionBozze_CustomFilter","GetDataSectionBozze_CustomDataTemplate");
    }

    // Personalizza il template dei dati delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomDataTemplate($data = array(), $object = null)
    {
        if ($object instanceof AA_SicarAlloggio) 
        {
            $immobile=$object->GetImmobile();
            $anno_ultima_ristrutturazione="n.d.";
            if(!empty($object->GetAnnoRistrutturazione())) $anno_ultima_ristrutturazione=$object->GetAnnoRistrutturazione();

            $data['pretitolo'] =" <span class='AA_DataView_Tag AA_Label AA_Label_Blue_Simo' title='ultima ristrutturazione'>Anno ultima ristrutturazione: ".$anno_ultima_ristrutturazione."</span>";
            if($immobile instanceof AA_SicarImmobile) $data['denominazione'] = $immobile->GetDescrizione().", ".$object->GetDisplayName()." - ".$immobile->GetIndirizzo()." (".$immobile->GetComune().")";
            else $data['denominazione'] = $object->GetDisplayName();
            $data['sottotitolo'] = "";
            
            $proprietario=$object->GetProprietario();
            if($proprietario instanceof AA_SicarEnte)
            {
                $data['sottotitolo'] .=" <span class='AA_DataView_Tag AA_Label AA_Label_LightOrange' title='Ente proprietario'>Ente proprietario: <b>".$proprietario->GetDenominazione()."</b></span>";
            }
            else $data['sottotitolo'] .= " <span class='AA_DataView_Tag AA_Label AA_Label_LightRed' title='Ente proprietario'>Nessuno</span>";

            $gestore=$object->GetGestore();
            if($gestore instanceof AA_SicarEnte)
            {
                $data['sottotitolo'] .= " <span class='AA_DataView_Tag AA_Label AA_Label_LightOrange' title='Ente gestore'>Ente gestore: <b>".$gestore->GetDenominazione()."</b></span>";
            }
            else $data['sottotitolo'] .= " <span class='AA_DataView_Tag AA_Label AA_Label_LightRed' title='Ente gestore'>Nessuno</span>";

            //$tags="<span class='AA_DataView_Tag AA_Label AA_Label_LightYellow' title='Tipo di utilizzo'>Tipologia di utilizzo: <b>".$object->GetTipologiaUtilizzo()."</b></span>";
            $tags="";
            //if(!empty($object->GetAnnoRistrutturazione())) $tags=" <span class='AA_DataView_Tag AA_Label AA_Label_LightYellow' title='Anno ultima ristrutturazione'>Anno ultima ristrutturazione: <b>".$object->GetAnnoRistrutturazione()."</b></span>";
            $data['tags']=$tags;

            //occupazione
            $occupazione=$object->GetOccupazione();
            $last_occupazione=current($occupazione);
            if(empty($occupazione))
            {

                    $data['occupazione']="<span>Nessuna informazione disponibile</span><span>&nbsp;</span>";
            }
            else
            {
                $tipo_occupazione=AA_Sicar_Const::GetListaTipologieOccupazione(true);
                
                $clickDetailOccupazione="AA_MainApp.utils.callHandler('dlg', {task:'GetSicarDetailStatoOccupazioneAlloggioDlg', params: [{id: ".$object->GetId()."},{dal: '".key($occupazione)."'}]},'$this->id')";
                
                $colors=array(0=>"LightGray",1=>"LightGreen",2=>"LightYellow",3=>"LightOrange",4=>"LightRed");
                if($last_occupazione['occupazione_tipo']>0) $data['occupazione']="<span class='AA_Label AA_Label_".$colors[$last_occupazione['occupazione_tipo']]."' style='font-size:large; padding:4px;font-weight:900' title='Stato occupazione'>".$tipo_occupazione[$last_occupazione['occupazione_tipo']]."</span>";
                else $data['occupazione']="<span class='AA_Label AA_Label_".$colors[0]."' style='font-size:large; padding:4px;font-weight:900' title='Stato occupazione'>Libero</span>";
                
                //libero
                if($last_occupazione['occupazione_tipo']==0)
                {
                    $data['occupazione'].="<div><span style='font-size:small'>dal: </span><span style='font-weight:600'>".key($occupazione)."</span></div>";
                    $data['occupazione'].="<span>&nbsp;</span>";
                    if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                    else $data['occupazione'].="<span>&nbsp;</span>";
                }

                $nucleo=new AA_SicarNucleo();
                $tipo_canone=AA_Sicar_Const::GetListaTipologieCanoneAlloggio(true);
                
                //assegnato
                if($last_occupazione['occupazione_tipo']==1)
                {
                    if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>a: </span><span style='font-weight:600'>".$nucleo->GetDescrizione()."</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'>".key($occupazione)."</span></div>";
                        $data['occupazione'].="<span>&nbsp;</span>";
                        if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                        else $data['occupazione'].="<span>&nbsp;</span>";
                    }   
                    else
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>a: </span><span style='font-weight:600'> n.d.</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'> n.d.</span></div>";
                        $data['occupazione'].="<span>&nbsp;</span>";
                        if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                        else $data['occupazione'].="<span>&nbsp;</span>";
                    }
                }

                //occupato
                if($last_occupazione['occupazione_tipo']>1)
                {
                    if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>da: </span><span style='font-weight:600'>".$nucleo->GetDescrizione()."</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'>".key($occupazione)."</span></div>";
                        if($last_occupazione['occupazione_tipo']==2) $data['occupazione'].="<div><span style='font-size:small'>tipo canone: </span><span style='font-weight:600'>".$tipo_canone[$last_occupazione['occupazione_tipo_canone']]."</span></div>";
                        else $data['occupazione'].="<span>&nbsp;</span>";
                        if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                        else $data['occupazione'].="<span>&nbsp;</span>";
                    }
                    else
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>da: </span><span style='font-weight:600'> n.d.</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'> n.d.</span></div>";
                        $data['occupazione'].="<span>&nbsp;</span>";
                        if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                        else $data['occupazione'].="<span>&nbsp;</span>";
                    }
                }
            }

            //interventi
            $interventi=$object->GetInterventi();
            $stato_lavori=AA_Sicar_Const::GetListaStatoLavori(true);
            if(empty($interventi))
            {
                $data['interventi']="<div style='flex-direction: column; display:flex; gap:4px; item-align: center; justify-content: center;height:100%;width: 98%;'><span style='text-align: center'>Nessun intervento in corso</span></div>";
            }
            else
            {
                $tipo_intervento=AA_Sicar_Const::GetListaTipologieIntervento(true);
                $data['interventi']="<div style='flex-direction: column; display:flex; gap:4px; item-align: center; justify-content: center;height:100%;width: 98%;'><div style='text-align: center;border-bottom: 1px solid #ccc;font-weight:600;font-size:smaller'><span style='display: inline-block; width: 24%;'>Tipologia</span><span style='display: inline-block; width: 24%;'>Data inizio</span><span style='display: inline-block; width: 24%;'>Stato lavori</span><span style='display: inline-block; width: 24%;'>CUP</span></div>";
                $count_interventi=0;
                $count_max=2;
                foreach($interventi as $id_intervento=>$intervento)
                {
                    if(($intervento['data_al'] == "" || $intervento['data_al'] >= date("Y-m-d")) && $count_interventi<$count_max)
                    {
                        $data['interventi'].="<div style='text-align: center;font-size: smaller'><span style='display: inline-block; width: 24%; font-weight:600'>".$tipo_intervento[$intervento['tipologia']]."</span>";
                        $data['interventi'].="<span style='display: inline-block; width: 24%;'>".$intervento['data_dal']."</span>";
                        $data['interventi'].="<span style='display: inline-block; width: 24%;'>".$stato_lavori[$intervento['stato_lavori']]."</span>";
                        if($intervento['cup']!="") $data['interventi'].="<span style='display: inline-block; width: 24%;'>".$intervento['cup']."</span>";
                        else $data['interventi'].="<span style='display: inline-block; width: 24%;'>&nbsp;</span>";
                        $data['interventi'].="</div>";
                    
                        $count_interventi++;
                    }
                }
               
                if($count_interventi==0)
                {
                    $data['interventi'].="<div style='flex-direction: column; display:flex; gap:4px; item-align: center; justify-content: center;height:100%;width: 98%;'><span style='font-weight:900'>Nessun intervento in corso</span>";
                }
                else
                {
                   
                    //$clickDetailInterventi="AA_MainApp.utils.callHandler('dlg', {task:'GetSicarDetailStatoInterventoAlloggioDlg', params: [{id: ".$object->GetId()."},{dal: '".key($interventi)."'}]},'$this->id')";
                    //$data['interventi'].='<a href="#" onClick="'.$clickDetailInterventi.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli interventi">Fai click qui per visualizzare i dettagli dell&apos;intervento</a>';
                }
                $data['interventi'].="</div>";
            }
        }
        else
        {
            AA_Log::Log(__METHOD__." - oggetto non valido: ".print_r($object,true),100);
        }

        //AA_Log::Log(__METHOD__." - oggetto: ".print_r($object,true),100);
        return $data;
    }
    
    //Funzione di filtro personalizzata per la verifica se la sezione è filtrata
    protected function CustomDataSectionIsFiltered($params = array())
    {
        if(isset($params['immobile']) && $params['immobile'] > 0) return true;
        if(!empty($params['comune'])) return true;
        if(!empty($params['indirizzo'])) return true;
        if(!empty($params['stato_conservazione']) && $params['stato_conservazione'] > 0) return true;
        return false;
    }

    //Personalizza il filtro delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomFilter($params = array())
    {
        //immobile
        if(isset($params['immobile']) && $params['immobile'] > 0)
        {
            $params['where'][]=" AND ".AA_SicarAlloggio::AA_DBTABLE_DATA.".immobile like '%".addslashes($params['immobile'])."%'";
        }

        //comune
        if(!empty($params['comune']))
        {
            $params['join'][]=" LEFT JOIN ".AA_SicarImmobile::GetDatatable()." ON ".AA_SicarAlloggio::AA_DBTABLE_DATA.".immobile=".AA_SicarImmobile::GetDatatable().".id";
            $params['where'][]=" AND ".AA_SicarImmobile::GetDatatable().".comune like '".addslashes($params['comune'])."'";
        }

        //indirizzo
        if(!empty($params['indirizzo']))
        {
            $params['join'][]=" LEFT JOIN ".AA_SicarImmobile::GetDatatable()." ON ".AA_SicarAlloggio::AA_DBTABLE_DATA.".immobile=".AA_SicarImmobile::GetDatatable().".id";
            $params['where'][]=" AND ".AA_SicarImmobile::GetDatatable().".indirizzo like '%".addslashes($params['indirizzo'])."%'";
        }

        if(!empty($params['stato_conservazione']) && $params['stato_conservazione'] > 0)
        {
            $params['where'][]=" AND ".AA_SicarAlloggio::AA_DBTABLE_DATA.".stato_conservazione like '".addslashes($params['stato_conservazione'])."'";
        }

        //ids
        if(isset($params['ids']) &&  $params['ids']!="")
        {
            $params['where'][]=" AND ".AA_SicarAlloggio::GetObjectsDbDataTable().".id in (".addslashes($params['ids']).")";
        }
    
        return $params;
    }


    // Template della sezione bozze
    public function TemplateSection_Bozze($params = array())
    {
        $bCanModify = $this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR);
        $params['enableAddNewMultiFromCsv'] = false;

        $contentBoxTemplate = "<div class='AA_DataViewSicarAlloggi'><div style='width:33%; min-width:500px' class='AA_DataView_ItemContent'>"
            . "<div>#pretitolo#</div>"
            . "<div><span class='AA_DataView_ItemTitle'>#denominazione#</span></div>"
            . "<div>#tags#</div>"
            . "<div><span class='AA_DataView_ItemSubTitle'>#sottotitolo#</span></div>"
            . "<div><span class='AA_Label AA_Label_LightBlue' title='Stato elemento'>#stato#</span>&nbsp;<span class='AA_DataView_ItemDetails'>#dettagli#</span></div>"
            . "</div>"
            . "<div class='AA_DataView_SicarAlloggiOccupazione'><span class='AA_DataView_SicarItemViewBoxLabel'>Stato occupazione</span>#occupazione#</span></div>"
            . "<div class='AA_DataView_SicarAlloggiInterventi'><span class='AA_DataView_SicarItemViewBoxLabel'>Stato interventi</span>#interventi#</div>"
            . "</div>";
        
        // Qui puoi usare AA_GenericSection_Bozze o un template simile a GECOP
        $content = $this->TemplateGenericSection_Bozze($params,null);
        $content->SetContentBoxTemplate($contentBoxTemplate);

        return $content->toObject();
    }

    // Restituisce i dati delle pubblicate
    public function GetDataSectionPubblicate_List($params = array())
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            AA_Log::Log(__METHOD__ . " - ERRORE: l'utente corrente: " . $this->oUser->GetUserName() . " non è abilitato alla visualizzazione delle pubblicate.", 100);
            return array();
        }

        // Recupera immobili in stato pubblicato
        return $this->GetDataGenericSectionPubblicate_List($params,"GetDataSectionPubblicate_CustomFilter","GetDataSectionPubblicate_CustomDataTemplate");

    }

    // Personalizza il template dei dati delle pubblicate per il modulo corrente
    protected function GetDataSectionPubblicate_CustomDataTemplate($data = array(), $object = null)
    {
        if ($object instanceof AA_SicarAlloggio) 
        {
            $immobile=$object->GetImmobile();
            $anno_ultima_ristrutturazione="n.d.";
            if(!empty($object->GetAnnoRistrutturazione())) $anno_ultima_ristrutturazione=$object->GetAnnoRistrutturazione();

            $data['pretitolo'] =" <span class='AA_DataView_Tag AA_Label AA_Label_Blue_Simo' title='ultima ristrutturazione'>Anno ultima ristrutturazione: ".$anno_ultima_ristrutturazione."</span>";
            if($immobile instanceof AA_SicarImmobile) $data['denominazione'] = $immobile->GetDescrizione().", ".$object->GetDisplayName()." - ".$immobile->GetIndirizzo()." (".$immobile->GetComune().")";
            else $data['denominazione'] = $object->GetDisplayName();
            $data['sottotitolo'] = "";
            
            $proprietario=$object->GetProprietario();
            if($proprietario instanceof AA_SicarEnte)
            {
                $data['sottotitolo'] .=" <span class='AA_DataView_Tag AA_Label AA_Label_LightOrange' title='Ente proprietario'>Ente proprietario: <b>".$proprietario->GetDenominazione()."</b></span>";
            }
            else $data['sottotitolo'] .= " <span class='AA_DataView_Tag AA_Label AA_Label_LightRed' title='Ente proprietario'>Nessuno</span>";

            $gestore=$object->GetGestore();
            if($gestore instanceof AA_SicarEnte)
            {
                $data['sottotitolo'] .= " <span class='AA_DataView_Tag AA_Label AA_Label_LightOrange' title='Ente gestore'>Ente gestore: <b>".$gestore->GetDenominazione()."</b></span>";
            }
            else $data['sottotitolo'] .= " <span class='AA_DataView_Tag AA_Label AA_Label_LightRed' title='Ente gestore'>Nessuno</span>";

            //$tags="<span class='AA_DataView_Tag AA_Label AA_Label_LightYellow' title='Tipo di utilizzo'>Tipologia di utilizzo: <b>".$object->GetTipologiaUtilizzo()."</b></span>";
            $tags="";
            //if(!empty($object->GetAnnoRistrutturazione())) $tags=" <span class='AA_DataView_Tag AA_Label AA_Label_LightYellow' title='Anno ultima ristrutturazione'>Anno ultima ristrutturazione: <b>".$object->GetAnnoRistrutturazione()."</b></span>";
            $data['tags']=$tags;

            //occupazione
            $occupazione=$object->GetOccupazione();
            $last_occupazione=current($occupazione);
            if(empty($occupazione))
            {

                    $data['occupazione']="<span>Nessuna informazione disponibile</span><span>&nbsp;</span>";
            }
            else
            {
                $tipo_occupazione=AA_Sicar_Const::GetListaTipologieOccupazione(true);
                
                $clickDetailOccupazione="AA_MainApp.utils.callHandler('dlg', {task:'GetSicarDetailStatoOccupazioneAlloggioDlg', params: [{id: ".$object->GetId()."},{dal: '".key($occupazione)."'}]},'$this->id')";
                
                $colors=array(0=>"LightGray",1=>"LightGreen",2=>"LightYellow",3=>"LightOrange",4=>"LightRed");
                if($last_occupazione['occupazione_tipo']>0) $data['occupazione']="<span class='AA_Label AA_Label_".$colors[$last_occupazione['occupazione_tipo']]."' style='font-size:large; padding:4px;font-weight:900' title='Stato occupazione'>".$tipo_occupazione[$last_occupazione['occupazione_tipo']]."</span>";
                else $data['occupazione']="<span class='AA_Label AA_Label_".$colors[0]."' style='font-size:large; padding:4px;font-weight:900' title='Stato occupazione'>Libero</span>";
                
                //libero
                if($last_occupazione['occupazione_tipo']==0)
                {
                    $data['occupazione'].="<div><span style='font-size:small'>dal: </span><span style='font-weight:600'>".key($occupazione)."</span></div>";
                    $data['occupazione'].="<span>&nbsp;</span>";
                    if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                    else $data['occupazione'].="<span>&nbsp;</span>";
                }

                $nucleo=new AA_SicarNucleo();
                $tipo_canone=AA_Sicar_Const::GetListaTipologieCanoneAlloggio(true);
                
                //assegnato
                if($last_occupazione['occupazione_tipo']==1)
                {
                    if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>a: </span><span style='font-weight:600'>".$nucleo->GetDescrizione()."</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'>".key($occupazione)."</span></div>";
                        $data['occupazione'].="<span>&nbsp;</span>";
                        if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                        else $data['occupazione'].="<span>&nbsp;</span>";
                    }   
                    else
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>a: </span><span style='font-weight:600'> n.d.</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'> n.d.</span></div>";
                        $data['occupazione'].="<span>&nbsp;</span>";
                        if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                        else $data['occupazione'].="<span>&nbsp;</span>";
                    }
                }

                //occupato
                if($last_occupazione['occupazione_tipo']>1)
                {
                    if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>da: </span><span style='font-weight:600'>".$nucleo->GetDescrizione()."</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'>".key($occupazione)."</span></div>";
                        if($last_occupazione['occupazione_tipo']==2) $data['occupazione'].="<div><span style='font-size:small'>tipo canone: </span><span style='font-weight:600'>".$tipo_canone[$last_occupazione['occupazione_tipo_canone']]."</span></div>";
                        else $data['occupazione'].="<span>&nbsp;</span>";
                        if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                        else $data['occupazione'].="<span>&nbsp;</span>";
                    }
                    else
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>da: </span><span style='font-weight:600'> n.d.</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'> n.d.</span></div>";
                        $data['occupazione'].="<span>&nbsp;</span>";
                        if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                        else $data['occupazione'].="<span>&nbsp;</span>";
                    }
                }
            }

            //interventi
            $interventi=$object->GetInterventi();
            $stato_lavori=AA_Sicar_Const::GetListaStatoLavori(true);
            if(empty($interventi))
            {
                $data['interventi']="<div style='flex-direction: column; display:flex; gap:4px; item-align: center; justify-content: center;height:100%;width: 98%;'><span style='text-align: center'>Nessun intervento in corso</span></div>";
            }
            else
            {
                $tipo_intervento=AA_Sicar_Const::GetListaTipologieIntervento(true);
                $data['interventi']="<div style='flex-direction: column; display:flex; gap:4px; item-align: center; justify-content: center;height:100%;width: 98%;'><div style='text-align: center;border-bottom: 1px solid #ccc;font-weight:600;font-size:smaller'><span style='display: inline-block; width: 24%;'>Tipologia</span><span style='display: inline-block; width: 24%;'>Data inizio</span><span style='display: inline-block; width: 24%;'>Stato lavori</span><span style='display: inline-block; width: 24%;'>CUP</span></div>";
                $count_interventi=0;
                $count_max=2;
                foreach($interventi as $id_intervento=>$intervento)
                {
                    if(($intervento['data_al'] == "" || $intervento['data_al'] >= date("Y-m-d")) && $count_interventi<$count_max)
                    {
                        $data['interventi'].="<div style='text-align: center;font-size: smaller'><span style='display: inline-block; width: 24%; font-weight:600'>".$tipo_intervento[$intervento['tipologia']]."</span>";
                        $data['interventi'].="<span style='display: inline-block; width: 24%;'>".$intervento['data_dal']."</span>";
                        $data['interventi'].="<span style='display: inline-block; width: 24%;'>".$stato_lavori[$intervento['stato_lavori']]."</span>";
                        if($intervento['cup']!="") $data['interventi'].="<span style='display: inline-block; width: 24%;'>".$intervento['cup']."</span>";
                        else $data['interventi'].="<span style='display: inline-block; width: 24%;'>&nbsp;</span>";
                        $data['interventi'].="</div>";
                    
                        $count_interventi++;
                    }
                }
               
                if($count_interventi==0)
                {
                    $data['interventi'].="<div style='flex-direction: column; display:flex; gap:4px; item-align: center; justify-content: center;height:100%;width: 98%;'><span style='font-weight:900'>Nessun intervento in corso</span>";
                }
                else
                {
                   
                    //$clickDetailInterventi="AA_MainApp.utils.callHandler('dlg', {task:'GetSicarDetailStatoInterventoAlloggioDlg', params: [{id: ".$object->GetId()."},{dal: '".key($interventi)."'}]},'$this->id')";
                    //$data['interventi'].='<a href="#" onClick="'.$clickDetailInterventi.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli interventi">Fai click qui per visualizzare i dettagli dell&apos;intervento</a>';
                }
                $data['interventi'].="</div>";
            }
        }
        else
        {
            AA_Log::Log(__METHOD__." - oggetto non valido: ".print_r($object,true),100);
        }

        //restituisce il record al template base
        return $data;
    }

    //Personalizza il filtro delle bozze per il modulo corrente
    protected function GetDataSectionPubblicate_CustomFilter($params = array())
    {
        //immobile
        if(isset($params['immobile']) && $params['immobile'] > 0)
        {
            $params['where'][]=" AND ".AA_SicarAlloggio::AA_DBTABLE_DATA.".immobile like '%".addslashes($params['immobile'])."%'";
        }

        //comune
        if(!empty($params['comune']))
        {
            $params['join'][]=" LEFT JOIN ".AA_SicarImmobile::GetDatatable()." ON ".AA_SicarAlloggio::AA_DBTABLE_DATA.".immobile=".AA_SicarImmobile::GetDatatable().".id";
            $params['where'][]=" AND ".AA_SicarImmobile::GetDatatable().".comune like '".addslashes($params['comune'])."'";
        }

        //indirizzo
        if(!empty($params['indirizzo']))
        {
            $params['join'][]=" LEFT JOIN ".AA_SicarImmobile::GetDatatable()." ON ".AA_SicarAlloggio::AA_DBTABLE_DATA.".immobile=".AA_SicarImmobile::GetDatatable().".id";
            $params['where'][]=" AND ".AA_SicarImmobile::GetDatatable().".indirizzo like '%".addslashes($params['indirizzo'])."%'";
        }

        if(!empty($params['stato_conservazione']) && $params['stato_conservazione'] > 0)
        {
            $params['where'][]=" AND ".AA_SicarAlloggio::AA_DBTABLE_DATA.".stato_conservazione like '".addslashes($params['stato_conservazione'])."'";
        }

        //ids
        if(isset($params['ids']) &&  $params['ids']!="")
        {
            $params['where'][]=" AND ".AA_SicarAlloggio::GetObjectsDbDataTable().".id in (".addslashes($params['ids']).")";
        }
    
        return $params;
    }

    //Template cruscotto content
    public function TemplateSection_Desktop()
    {
        //AA_Log::Log(__METHOD__,100);
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_DESKTOP_BOX;
        $layout = new AA_JSON_Template_Layout($id,array("type"=>"clean","name" => static::AA_UI_SECTION_DESKTOP_NAME));

        $second_row=new AA_JSON_Template_Layout("",array("type"=>"space","css"=>array("background-color"=>"transparent")));
        $second_row->AddCol($this->TemplateSection_News());
        $layout->AddRow($second_row);

        $minCountModulesToCarousel=6;

        //Moduli Row
        $modules_added=0;
        $modules=array(
            array("id_section"=>static::AA_ID_SECTION_PUBBLICATE,"icon"=>static::AA_UI_SECTION_PUBBLICATE_ICON,"label"=>"Gestione alloggi","descrizione"=>"Visualizza e gestisci gli alloggi","tooltip"=>"Visualizza e gestisci gli alloggi","visible"=>true),
            array("id_section"=>static::AA_ID_SECTION_IMMOBILI,"icon"=>static::AA_UI_SECTION_IMMOBILI_ICON,"label"=>static::AA_UI_SECTION_IMMOBILI_NAME,"descrizione"=>static::AA_UI_SECTION_IMMOBILI_DESC,"tooltip"=>static::AA_UI_SECTION_IMMOBILI_TOOLTIP),
            array("id_section"=>static::AA_ID_SECTION_ENTI,"icon"=>static::AA_UI_SECTION_ENTI_ICON,"label"=>static::AA_UI_SECTION_ENTI_NAME,"descrizione"=>static::AA_UI_SECTION_ENTI_DESC,"tooltip"=>static::AA_UI_SECTION_ENTI_TOOLTIP),
            array("id_section"=>static::AA_ID_SECTION_NUCLEI,"icon"=>static::AA_UI_SECTION_NUCLEI_ICON,"label"=>static::AA_UI_SECTION_NUCLEI_NAME,"descrizione"=>static::AA_UI_SECTION_NUCLEI_DESC,"tooltip"=>static::AA_UI_SECTION_NUCLEI_TOOLTIP),
            array("id_section"=>static::AA_ID_SECTION_FINANZIAMENTI,"icon"=>static::AA_UI_SECTION_FINANZIAMENTI_ICON,"label"=>static::AA_UI_SECTION_FINANZIAMENTI_NAME,"descrizione"=>static::AA_UI_SECTION_FINANZIAMENTI_DESC,"tooltip"=>static::AA_UI_SECTION_FINANZIAMENTI_TOOLTIP),
            array("id_section"=>static::AA_ID_SECTION_GRADUATORIE,"icon"=>static::AA_UI_SECTION_GRADUATORIE_ICON,"label"=>static::AA_UI_SECTION_GRADUATORIE_NAME,"descrizione"=>static::AA_UI_SECTION_GRADUATORIE_DESC,"tooltip"=>static::AA_UI_SECTION_GRADUATORIE_TOOLTIP)
        );
 
        $minHeightModuliItem=intval(($_REQUEST['vh']-180)/2);
        //$numModuliBoxForrow=intval(sqrt(sizeof($moduli_data)));
        $WidthModuliItem=intval(($_REQUEST['vw']-110)/4);
        //$HeightModuliItem=intval(/$numModuliBoxForrow);"css"=>"AA_DataView_Moduli_item","margin"=>10

        if(sizeof($modules) < $minCountModulesToCarousel ) 
        {
            AA_Log::Log(__METHOD__." - Aggiungo layout: ".$id."_ModuliBox" ,100);
            $moduli_box=new AA_JSON_Template_Layout($id."_ModuliBox",array("type"=>"clean","css"=>array("background-color"=>"transparent")));
        }
        else 
        {
            AA_Log::Log(__METHOD__." - Aggiungo carosello: ".$id."_ModuliBox" ,100);
            $moduli_box=new AA_JSON_Template_Carousel($id."_ModuliBox",array("type"=>"clean","css"=>array("background-color"=>"transparent")));
        }

        $riepilogo_template="<div class='AA_DataView_Moduli_item' onclick=\"#onclick#\" style='cursor: pointer; border: 1px solid; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 97%; margin:5px;'>";
        //icon
        $riepilogo_template.="<div style='display: flex; align-items: center; height: 120px; font-size: 90px;'><span class='#icon#'></span></div>";
        //name
        $riepilogo_template.="<div style='display: flex; align-items: center;justify-content: center; flex-direction: column; font-size: larger;height: 60px'>#name#</div>";
        //descr
        //$riepilogo_template.="<div style='display: flex; align-items: center;padding: 10px;height: 120px'><span>#descr#</span></div>";
        //go
        //$riepilogo_template.="<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 48px; padding: 5px'><a title='Apri il modulo' onclick=\"#onclick#\" class='AA_Button_Link'><span>Vai</span>&nbsp;<span class='mdi mdi-arrow-right-thick'></span></a></div>";
        $riepilogo_template.="</div>";

        $nSlide=0;
        $nMod=0;
        $moduli_view=null;
        foreach($modules as $curModId => $curMod)
        {
            $nMod++;
            $modules_added++;
            AA_Log::Log(__METHOD__." - Aggiungo il modulo: ".$curModId,100);
            $name="<span style='font-weight:900;font-variant-caps: all-small-caps;font-size:larger'>".$curMod['label']."</span><span>".$curMod['tooltip']."</span>";
            $onclick="AA_MainApp.utils.callHandler('setCurrentSection','".$curMod['id_section']."','".$this->GetId()."')";
            $moduli_data=array("id"=>$curMod['id_section'],"name"=>$name,'descr'=>$curMod['descrizione'],"icon"=>$curMod['icon'],"onclick"=>$onclick);
            if($moduli_view==null) $moduli_view=new AA_JSON_Template_Layout($id."_ModuliView_".$nSlide,array("type"=>"clean","css"=>array("background-color"=>"transparent")));
            $moduli_view->AddCol(new AA_JSON_Template_Template($id."_ModuleBox_".$moduli_data['id'],array("template"=>$riepilogo_template,"borderless"=>true,"data"=>array($moduli_data))));
            
            if($nMod%4==0)
            {
                
                if(sizeof($modules) < $minCountModulesToCarousel) 
                {
                    AA_Log::Log(__METHOD__." - Aggiungo box moduli: ".$id."_ModuliView_".$nSlide." - nMod: ".$nMod ,100);
                    $moduli_box->AddRow($moduli_view);
                }
                else 
                {
                    AA_Log::Log(__METHOD__." - Aggiungo la slide: ".$id."_ModuliView_".$nSlide." - nMod: ".$nMod ,100);
                    $moduli_box->AddSlide($moduli_view);
                }
                $nSlide++;
                $moduli_view=null;
                $nMod=0;
            }   
        }

        //AA_Log::Log(__METHOD__." - nMod: ".$nMod. " - %: ".$nMod%4,100);
        if($nMod%4 || $nMod < 4)
        {
            //AA_Log::Log(__METHOD__." - Aggiungo la slide: ".$id."_ModuliView_".$nSlide,100);
            $i=$nMod;
            if($nMod > 4) $i=$nMod%4;
            for($i;$i < 4;$i++)
            {
                if($moduli_view !=null) $moduli_view->addCol(new AA_JSON_Template_Generic());
            }
        }

        if($moduli_view != null)
        {
            if(sizeof($modules) < $minCountModulesToCarousel) 
            {
                AA_Log::Log(__METHOD__." - Aggiungo il box al layout: ".$id."_ModuliView_".$nSlide." - nMod: ".$nMod ,100);
                if($moduli_view !=null) $moduli_box->AddRow($moduli_view);
            }
            else 
            {
                AA_Log::Log(__METHOD__." - Aggiungo la slide: ".$id."_ModuliView_".$nSlide." - nMod: ".$nMod ,100);
                if($moduli_view !=null) $moduli_box->AddSlide($moduli_view);
            }
        }
          
        if($moduli_box)
        {
            if($modules_added==0)
            {
                $moduli_box->AddRow(new AA_JSON_Template_Template(uniqid(),array("template"=>"<div style='display: flex; justify-content: center; align-items: center; width: 100%;height:100%'><div>&nbsp;</div></div>")));
            }
            $layout->AddRow($moduli_box);
        }
        else
        {
            $layout->AddRow(new AA_JSON_Template_Template(uniqid(),array("template"=>"<div style='display: flex; justify-content: center; align-items: center; width: 100%;height:100%'><div>&nbsp;</div></div>")));
        }
        
        return $layout;
    }

    //Template news content
    public function TemplateSection_News()
    {
        $news_box=new AA_JSON_Template_Layout("",array("type"=>"space","css"=>"AA_Desktop_Section_Box"));
      
         
        $db = new AA_Database();
        
        $query="SELECT * from aa_sicar_news WHERE archivio='0' order by data DESC";
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__."() - errore: ".$db->GetErrorMessage()." nella query: ".$query,100);
        }

        $data=array();
        foreach($db->GetResultSet() as $row)
        {
            $data[]=array("id"=>$row['id'],"date"=>$row['data'],"value"=>$row['oggetto'],"details"=>$row['corpo']);
        }
        
        $news_box->AddRow(new AA_JSON_Template_Generic("",array("view"=>"label","align"=>"center","label"=>"<span class='AA_Desktop_Section_Label'>News</span>")));
        if(sizeof($data)>0)
        {
            $news_layout = new AA_JSON_Template_Generic("",
            array(
            "view"=>"timeline",
            "css"=>array("background-color"=>"transparent"),
            "type"=>array(
                "height"=>"auto",
                "width"=>800,
                "type"=>"left",
                "lineColor"=>"skyblue"
            )));
            $news_layout->setProp('data',$data);
            $news_box->AddRow($news_layout);
        }
        else
        {
            $news_box->AddRow(new AA_JSON_Template_Template("",array("template"=>"<div style='display: flex; justify-content: center; align-items: center; width: 100%;height:100%'><div>Non sono presenti news</div></div>")));
        }

        return $news_box;
    }

    // Template della sezione pubblicate
    public function TemplateSection_Pubblicate($params = array())
    {
        $bCanModify = $this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR);

        $contentBoxTemplate = "<div class='AA_DataViewSicarAlloggi'><div style='width:33%; min-width:500px' class='AA_DataView_ItemContent'>"
            . "<div>#pretitolo#</div>"
            . "<div><span class='AA_DataView_ItemTitle'>#denominazione#</span></div>"
            . "<div>#tags#</div>"
            . "<div><span class='AA_DataView_ItemSubTitle'>#sottotitolo#</span></div>"
            . "<div><span class='AA_Label AA_Label_LightBlue' title='Stato elemento'>#stato#</span>&nbsp;<span class='AA_DataView_ItemDetails'>#dettagli#</span></div>"
            . "</div>"
            . "<div class='AA_DataView_SicarAlloggiOccupazione'><span class='AA_DataView_SicarItemViewBoxLabel'>Stato occupazione</span>#occupazione#</span></div>"
            . "<div class='AA_DataView_SicarAlloggiInterventi'><span class='AA_DataView_SicarItemViewBoxLabel'>Ultimi interventi in corso</span>#interventi#</div>"
            . "</div>";

        $content=$this->TemplateGenericSection_Pubblicate($params,$bCanModify,null);
        $content->SetContentBoxTemplate($contentBoxTemplate);
        return $content->toObject();
    }

    // Template per la finestra di dialogo di aggiunta nuovo alloggio
    public function Template_GetSicarAddNewAlloggioDlg()
    {
        $id = $this->GetId() . "_AddNew_Dlg_" . uniqid();
        $form_data = array();
        $form_data['nome'] = "Nuovo alloggio";
        $form_data['tipologia_utilizzo'] = 0;
        $form_data['stato_conservazione'] = 0;
        $form_data['anno_ristrutturazione'] = "";
        $form_data['condominio_misto'] = 0;
        $form_data['superficie_utile_abitabile'] = 0;
        $form_data['superficie_non_residenziale'] = 0;
        $form_data['superficie_parcheggi'] = 0;
        $form_data['vani_abitabili'] = 0;
        $form_data['piano'] = 0;
        $form_data['ascensore'] = 0;
        $form_data['fruibile_dis'] = 0;
        $form_data['note'] = "";
        $form_data['proprieta_ente']="";
        $form_data['proprieta_ente_desc']="";
        $form_data['proprieta_dal']="";

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo alloggio", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(150);
        $wnd->SetWidth(1280);
        $wnd->SetHeight(800);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

         // Campo testuale: descrizione
        $wnd->AddTextField("nome", "Denominazione", ["required" => true, "bottomLabel" => "*Descrizione dell'alloggio"]);

        // Campo testuale: riferimento immobile
        $immobili = AA_SicarImmobile::GetListaImmobili();
        $options = array();
        foreach($immobili as $option)
        {
            $options[] = array("id" => $option->GetProp("id"), "value" => $option->GetDisplayName());
        }
        //$wnd->AddSelectField("immobile", "Immobile", ["required" => true, "bottomLabel" => "*Scegli un elemento della lista o fai click su nuovo se non e' presente l'immobile nella lista.","options" => $options]);
        $dlgImmobiliParams = array("task" => "GetSicarSearchImmobiliDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"immobile","field_desc"=>"immobile_desc"));
        $wnd->AddSearchField("dlg",$dlgImmobiliParams,$this->GetId(),["required" => true,"label"=>"Immobile","name"=>"immobile_desc", "bottomLabel" => "*Cerca un immobile gia' esistente o aggiungine uno se non e' presente."],false);        
        // Campo testuale: tipologia utilizzo
        $options = AA_Sicar_Const::GetListaTipologieUtilizzoAlloggio();
        $wnd->AddSelectField("tipologia_utilizzo", "Tipologia utilizzo", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        // Campo testuale: stato conservazione
        $options = AA_Sicar_Const::GetListaStatiConservazioneAlloggio();
        $wnd->AddSelectField("stato_conservazione", "Stato conservazione", ["required" => true,"validateFunction"=>"IsSelected","labelWidth"=>160, "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options],false);
        
        //superfici
        $superfici=new AA_FieldSet("AA_SICAR_ALLOGGI_SUPERFICI","Dati sulle superfici",$wnd->GetFormId(),1);
        // Campo numerico: superficie non residenziale
        $superfici->AddTextField("superficie_non_residenziale", "Non residenziale", ["required" => false,"labelWidth"=>120, "bottomLabel" => "Valore in metri quadri"]);
        // Campo numerico: superficie utile abitabile
        $superfici->AddTextField("superficie_utile_abitabile", "Abitabile", ["required" => true,"labelWidth"=>120, "bottomLabel" => "Superficie utile in mq"],false);
        // Campo numerico: superficie parcheggi
        $superfici->AddTextField("superficie_parcheggi", "Parcheggi", ["required" => false,"labelWidth"=>120, "bottomLabel" => "Superficie utile in mq"],false);
        $wnd->AddGenericObject($superfici);

        //altri dati
        $altro=new AA_FieldSet("AA_SICAR_ALLOGGI_ALTRO","Altri dati",$wnd->GetFormId(),1);
        // Campo numerico: anno ristrutturazione
        $altro->AddTextField("anno_ristrutturazione", "Anno ristrutturazione", ["required" => false,"labelWidth"=>150, "bottomLabel" => "Anno (se presente)"]);
        // Campo numerico: vani abitabili
        $altro->AddTextField("vani_abitabili", "Vani abitabili", ["required" => true, "labelWidth"=>120,"width"=>200,"bottomLabel" => "Numero di vani abitabili."],false);       
        // Campo numerico: piano
        $altro->AddTextField("piano", "Piano", ["required" => true,"labelWidth"=>90,"width"=>170, "bottomLabel" => "Numero del piano"],false);
        $wnd->AddGenericObject($altro,false);

        // Campo booleano: condominio misto
        //$wnd->AddCheckBoxField("condominio_misto", " ", ["required" => false, "labelWidth"=>150,"labelRight" => "Condominio misto"]);
        
        //ente proprietario
        $dlgEntiParams = array("task" => "GetSicarSearchEntiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"proprieta_ente","field_desc"=>"proprieta_ente_desc"));
        $ente=new AA_FieldSet("AA_SICAR_ENTE_PROPRIETARIO","Ente proprietario",$wnd->GetFormId(),1);
        $ente->AddSearchField("dlg",$dlgEntiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Denominazione","name"=>"proprieta_ente_desc", "bottomLabel" => "*Cerca un ente gia' esistente o aggiungine uno se non e' presente."]);
        $ente->AddDateField('proprieta_dal','Dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>80),false);
        $wnd->AddGenericObject($ente);

        //ente gestore
        $dlgEntiParams = array("task" => "GetSicarSearchEntiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"gestione_ente","field_desc"=>"gestione_ente_desc"));
        $ente=new AA_FieldSet("AA_SICAR_ENTE_GESTORE","Ente gestore",$wnd->GetFormId(),1);
        $ente->AddSearchField("dlg",$dlgEntiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Denominazione","name"=>"gestione_ente_desc", "bottomLabel" => "*Cerca un ente gia' esistente o aggiungine uno se non e' presente."]);
        $ente->AddDateField('gestione_dal','Dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>80),false);
        $wnd->AddGenericObject($ente);

        $attributi=new AA_FieldSet("AA_SICAR_ALLOGGIO_ATTRIBUTI","Caratteristiche",$wnd->GetFormId(),2);
        
        // Campo select: fruibile per disabili
        $options = AA_Sicar_Const::GetListaFruibilitaDisabile();
        $attributi->AddSelectField("fruibile_dis", "Fruibilità da disabile", ["required" => true, "labelWidth"=>160,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        
        // Campo booleano: ascensore
        $attributi->AddCheckBoxField("ascensore", " ", ["required" => false,"labelWidth"=>10,"width"=>160,"bottomPadding"=>0,"labelRight" => "Ascensore"],false);
        
        $wnd->AddGenericObject($attributi);
        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false,"bottomPadding"=>0, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("AddNewAlloggioSicar");
        return $wnd;
    }

    // Template per la finestra di dialogo di aggiunta nuovo alloggio
    public function Template_GetSicarModifyAlloggioDlg($object=null)
    {
        $id = $this->GetId() . "_Modify_Dlg_" . uniqid();
        if(!($object instanceof AA_SicarAlloggio))
        {
            $wnd = new AA_GenericWindowTemplate($id, "Modifica alloggio", $this->id);
            return $wnd;
        }
        if(!$object->IsValid()) return new AA_GenericWindowTemplate("", "Modifica alloggio", $this->id);
        if($object->GetId()==0) return new AA_GenericWindowTemplate("", "Modifica alloggio", $this->id);

        $form_data = array();
        $form_data['id'] = $object->GetId();
        $form_data['nome'] = $object->GetName();

        $immobile=$object->GetImmobile();
        $form_data["immobile"]=$immobile->GetProp("id");
        $form_data["immobile_desc"]=$immobile->GetDisplayName();
        $form_data['tipologia_utilizzo'] = $object->GetTipologiaUtilizzo(false);
        $form_data['stato_conservazione'] = $object->GetStatoConservazione(false);
        $form_data['anno_ristrutturazione'] = $object->GetAnnoRistrutturazione();
        $form_data['condominio_misto'] = $object->GetCondominioMisto();
        $form_data['superficie_non_residenziale'] = $object->GetSuperficieNonResidenziale();
        $form_data['superficie_parcheggi'] = $object->GetSuperficieParcheggi();
        $form_data['vani_abitabili'] = $object->GetVaniAbitabili();
        $form_data['superficie_utile_abitabile'] = $object->GetSuperficieUtileAbitabile();
        $form_data['piano'] = $object->GetPiano();
        $form_data['ascensore'] = $object->GetAscensore();
        $form_data['fruibile_dis'] = $object->GetFruibileDis(true);

        $form_data['note'] = $object->GetNote();

        $gestore=$object->GetGestore();
        if($gestore)
        {
            $form_data['gestione_ente']=$gestore->GetProp("id");
            $form_data['gestione_ente_desc']=$gestore->GetDisplayName();
            $form_data['gestione_dal']=$object->GetGestioneDal();
        }
        else
        {
            AA_Log::Log(__METHOD__." - Ente gestore non impostato o non trovato. (".print_r($object->GetProp("gestione"),true),100);
            $form_data['gestione_ente']="";
            $form_data['gestione_ente_desc']="";
            $form_data['gestione_dal']="";
        }

        $proprietario=$object->GetProprietario();
        if($proprietario)
        {
            $form_data['proprieta_ente']=$proprietario->GetProp("id");
            $form_data['proprieta_ente_desc']=$proprietario->GetDisplayName();
            $form_data['proprieta_dal']=$object->GetProprietaDal();
        }
        else
        {
            AA_Log::Log(__METHOD__." - Ente proprietario non impostato o non trovato. (".print_r($object->GetProp("proprieta"),true),100);
            $form_data['proprieta_ente']="";
            $form_data['proprieta_ente_desc']="";
            $form_data['proprieta_dal']="";
        }

        $wnd = new AA_GenericFormDlg($id, "Modifica alloggio", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(150);
        $wnd->SetWidth(1280);
        $wnd->SetHeight(800);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: denominazione
        $wnd->AddTextField("nome", "Denominazione", ["required" => true, "bottomLabel" => "*Descrizione dell'alloggio"]);
       
        // Campo testuale: riferimento immobile (opzionale)
        $immobili = AA_SicarImmobile::GetListaImmobili();
        $options = array();
        foreach($immobili as $option)
        {
            $options[] = array("id" => $option->GetProp("id"), "value" => $option->GetDisplayName());
        }
        //$wnd->AddSelectField("immobile", "Immobile", ["required" => true, "bottomLabel" => "*Scegli un elemento della lista o fai click su nuovo se non e' presente l'immobile nella lista.","options" => $options]);
        $dlgParams = array("task" => "GetSicarSearchImmobiliDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"immobile","field_desc"=>"immobile_desc"));
        $wnd->AddSearchField("dlg",$dlgParams,$this->GetId(),["required" => true,"label"=>"Immobile","name"=>"immobile_desc", "bottomLabel" => "*Cerca un immobile gia' esistente o aggiungine uno se non e' presente."],false);

        $addnew_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-plus",
             "label"=>"Nuovo immobile",
             "align"=>"right",
             "autowidth"=>true,
             "tooltip"=>"Aggiungi un nuovo immobile",
             "click"=>"AA_MainApp.utils.callHandler('dlg', {task:'GetSicarAddNewImmobileDlg', params: [{wnd_alloggio: '".$wnd->GetId()."'}]},'".$this->id."')"
         ));
        //$wnd->AddGenericObject($addnew_btn,false);
 
        // Campo testuale: tipologia utilizzo
        $options = AA_Sicar_Const::GetListaTipologieUtilizzoAlloggio();
        $wnd->AddSelectField("tipologia_utilizzo", "Tipologia utilizzo", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        // Campo testuale: stato conservazione
        $options = AA_Sicar_Const::GetListaStatiConservazioneAlloggio();
        $wnd->AddSelectField("stato_conservazione", "Stato conservazione", ["required" => true,"validateFunction"=>"IsSelected","labelWidth"=>160, "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options],false);
        
        //superfici
        $superfici=new AA_FieldSet("AA_SICAR_ALLOGGI_SUPERFICI","Dati sulle superfici",$wnd->GetFormId(),1);
        // Campo numerico: superficie non residenziale
        $superfici->AddTextField("superficie_non_residenziale", "Non residenziale", ["required" => false,"labelWidth"=>120, "bottomLabel" => "Valore in metri quadri"]);
        // Campo numerico: superficie utile abitabile
        $superfici->AddTextField("superficie_utile_abitabile", "Abitabile", ["required" => true,"labelWidth"=>120, "bottomLabel" => "Superficie utile in mq"],false);
        // Campo numerico: superficie parcheggi
        $superfici->AddTextField("superficie_parcheggi", "Parcheggi", ["required" => false,"labelWidth"=>120, "bottomLabel" => "Superficie utile in mq"],false);
        $wnd->AddGenericObject($superfici);

        //altri dati
        $altro=new AA_FieldSet("AA_SICAR_ALLOGGI_ALTRO","Altri dati",$wnd->GetFormId(),1);
        // Campo numerico: anno ristrutturazione
        $altro->AddTextField("anno_ristrutturazione", "Anno ristrutturazione", ["required" => false,"labelWidth"=>150, "bottomLabel" => "Anno (se presente)"]);
        // Campo numerico: vani abitabili
        $altro->AddTextField("vani_abitabili", "Vani abitabili", ["required" => true, "labelWidth"=>120,"width"=>200,"bottomLabel" => "Numero di vani abitabili."],false);       
        // Campo numerico: piano
        $altro->AddTextField("piano", "Piano", ["required" => true,"labelWidth"=>90,"width"=>170, "bottomLabel" => "Numero del piano"],false);
        $wnd->AddGenericObject($altro,false);

        //ente proprietario
        $dlgEntiParams = array("task" => "GetSicarSearchEntiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"proprieta_ente","field_desc"=>"proprieta_ente_desc"));
        $ente=new AA_FieldSet("AA_SICAR_ENTE_PROPRIETARIO","Ente proprietario",$wnd->GetFormId(),1);
        $ente->AddSearchField("dlg",$dlgEntiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Denominazione","name"=>"proprieta_ente_desc", "bottomLabel" => "*Cerca un ente gia' esistente o aggiungine uno se non e' presente."]);
        $ente->AddDateField('proprieta_dal','Dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>80),false);
        $wnd->AddGenericObject($ente);

        //ente gestore
        $dlgEntiParams = array("task" => "GetSicarSearchEntiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"gestione_ente","field_desc"=>"gestione_ente_desc"));
        $ente=new AA_FieldSet("AA_SICAR_ENTE_GESTORE","Ente gestore",$wnd->GetFormId(),1);
        $ente->AddSearchField("dlg",$dlgEntiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Denominazione","name"=>"gestione_ente_desc", "bottomLabel" => "*Cerca un ente gia' esistente o aggiungine uno se non e' presente."]);
        $ente->AddDateField('gestione_dal','Dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>80),false);
        $wnd->AddGenericObject($ente);

        $attributi=new AA_FieldSet("AA_SICAR_ALLOGGIO_ATTRIBUTI","Caratteristiche",$wnd->GetFormId(),2);
        
        // Campo select: fruibile per disabili
        $options = AA_Sicar_Const::GetListaFruibilitaDisabile();
        $attributi->AddSelectField("fruibile_dis", "Fruibilità da disabile", ["required" => true, "labelWidth"=>160,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        
        // Campo booleano: ascensore
        $attributi->AddCheckBoxField("ascensore", " ", ["required" => false,"labelWidth"=>10,"width"=>160,"bottomPadding"=>0,"labelRight" => "Ascensore"],false);
        
        $wnd->AddGenericObject($attributi);

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("UpdateSicar");
        return $wnd;
    }

    // Template per la finestra di dialogo di aggiunta nuovo immobile
    public function Template_GetSicarAddNewImmobileDlg()
    {
        $form = "";
        if(!empty($_REQUEST['form']))
        {
            $form = $_REQUEST['form'];
        }

        $field_id="";
        if(!empty($_REQUEST['field_id']))
        {
            $field_id = $_REQUEST['field_id'];
        }

        $field_desc="";
        if(!empty($_REQUEST['field_desc']))
        {
            $field_desc = $_REQUEST['field_desc'];
        }
        
        $id = $this->GetId() . "_AddNew_Dlg_" . uniqid();
        $form_data = array();

        $newImmobile = new AA_SicarImmobile();
        foreach($newImmobile->GetProps() as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }

        //attributi
        $form_data['attributi']="";
        $form_data['attributi_condominio_misto']=0;
        $form_data['attributi_alloggi']="";

        //dati catastali
        $form_data['catasto'] = "";
        $form_data['SezioneCatasto'] = 0;
        $form_data['FoglioCatasto'] = "";
        $form_data['MappaleCatasto'] = "";
        $form_data['ParticellaCatasto'] = "";
        $form_data['Subalterno'] = "";

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo Immobile", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(1280);
        $wnd->SetHeight(800);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: descrizione
        $wnd->AddTextField("descrizione", "Descrizione", ["required" => true, "bottomLabel" => "*Descrizione dell'immobile"]);
        
        // Campo testuale: tipologia
        $options = AA_Sicar_Const::GetListaTipologie();
        $wnd->AddSelectField("tipologia", "Tipologia", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Tipologia dell'immobile", "options" => $options]);
        
        // Campo testuale: comune
        $wnd->AddTextField("comune", "Comune", ["required" => true, "bottomLabel" => "*Comune dell'immobile (codice ISTAT)", "suggest"=>array("template"=>"#codice#","url"=>$this->taskManagerUrl."?task=GetSicarListaCodiciIstat")],false);
       
        // Campo testuale: ubicazione
        $options= AA_Sicar_Const::GetListaUbicazioni();
        $wnd->AddSelectField("ubicazione", "Ubicazione", ["required" => true,"validateFunction"=>"IsSelected","gravity"=>2, "bottomLabel" => "*Ubicazione dell'immobile", "options" => $options]);
        
        // Campo testuale: zona urbanistica
        $options = AA_Sicar_Const::GetListaZoneUrbanistiche();
        $wnd->AddSelectField("zona_urbanistica", "Zona urb.", ["required" => true,"gravity"=>2, "bottomLabel" => "*Zona urbanistica dell'immobile", "options" => $options],false);
        
        // Campo numerico: piani
        //$wnd->AddTextField("piani", "Piani", ["required" => true,"gravity"=>1,"validateFunction"=>"IsPositive", "bottomLabel" => "*Numero di piani dell'immobile"],false);

        // Campo testuale: indirizzo
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>1, "bottomLabel" => "*Indirizzo dell'immobile comprensivo del numero civico."]);
        
        // Campo testuale: geolocalizzazione
        $wnd->AddTextField("geolocalizzazione", "Geoloc.", ["required" => true,"gravity"=>1, "bottomLabel" => "*Geolocalizzazione dell'immobile (latitudine e longitudine) in gradi decimali separate da virgola.","placeholder"=>"es. 39.22, 9.10"],false);

        //Dati catastali
        $catasto = new AA_FieldSet("AA_SICAR_CATASTO","Dati catastali");

        //sezione catasto
        $label="Sezione";
        $options=array(
            array("id"=>0,"value"=>"Catasto urbano"),
            array("id"=>1,"value"=>"Catasto terreni")
        );
        $catasto->AddRadioField("SezioneCatasto",$label,array("options"=>$options,"bottomLabel"=>"*Indicare la sezione in cui è accatastato l'immobile.", "value"=>0,"required"=>true));
        //foglio catasto
        $label="Foglio";
        $catasto->AddTextField("FoglioCatasto",$label,array("tooltip"=>"*Inserire il numero del foglio in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."));
        
        //mappale catasto
        $label="Mappale";
        $catasto->AddTextField("MappaleCatasto",$label,array("tooltip"=>"*Inserire il numero di mappale in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //particella catasto
        $label="Particella";
        $catasto->AddTextField("ParticellaCatasto",$label,array("tooltip"=>"*Inserire il numero della particella in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //subalterno
        $label="Subalterno";
        $catasto->AddTextField("Subalterno",$label,array("required"=>true,"tooltip"=>"*Inserire il numero del sublaternose presente.", "placeholder"=>"..."),false);

        $wnd->AddGenericObject($catasto);

        //ente gestore
        $dlgEntiParams = array("task" => "GetSicarSearchEntiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"immobile_gestione_ente","field_desc"=>"immobile_gestione_ente_desc"));
        $ente=new AA_FieldSet("AA_SICAR_ENTE_GESTORE".uniqid(),"Ente gestore",$wnd->GetFormId(),3);
        $ente->AddSearchField("dlg",$dlgEntiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Denominazione","name"=>"immobile_gestione_ente_desc", "bottomLabel" => "*Cerca un ente gia' esistente o aggiungine uno se non e' presente."]);
        $ente->AddDateField('immobile_gestione_dal','Dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>80),false);
        //$wnd->AddGenericObject($ente);

        ///Attributi
        $attributi = new AA_FieldSet("AA_SICAR_ATTRIBUTI".uniqid(),"Caratteristiche",$wnd->GetFormId(),1);

        //piani
        $attributi->AddTextField("piani", "Piani", ["required" => true,"gravity"=>1,"validateFunction"=>"IsPositive", "bottomLabel" => "*Numero di piani dell'immobile"]);
        
        $label="Alloggi tot.";
        $attributi->AddTextField("attributi_alloggi",$label,array("gravity"=>1,"bottomLabel"=>"*Inserire il numero totale di alloggi (anche solo previsti).", "required"=>true,"placeholder"=>"..."),false);
        
        //condominio misto 
        //$attributi->AddCheckBoxField("attributi_condominio_misto", " ", ["required" => false,"labelWidth"=>10,"width"=>180,"labelRight"=>"Condominio misto","bottomPadding"=>36, "bottomLabel" => ""],false);
        $wnd->AddGenericObject($attributi);

        $wnd->SetSaveTask("AddNewImmobileSicar");
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false,"labelWidth"=>60, "bottomLabel" => "Note aggiuntive"]);

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di aggiunta nuovo ente
    public function Template_GetSicarAddNewEnteDlg()
    {
        $form = "";
        if(!empty($_REQUEST['form']))
        {
            $form = $_REQUEST['form'];
        }

        $field_id="";
        if(!empty($_REQUEST['field_id']))
        {
            $field_id = $_REQUEST['field_id'];
        }

        $field_desc="";
        if(!empty($_REQUEST['field_desc']))
        {
            $field_desc = $_REQUEST['field_desc'];
        }
        
        $id = $this->GetId() . "_AddNewEnte_Dlg_" . uniqid();
        $form_data = array();

        $newImmobile = new AA_SicarEnte();
        foreach($newImmobile->GetProps() as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo Ente", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(980);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: descrizione
        $wnd->AddTextField("denominazione", "Denominazione", ["required" => true, "bottomLabel" => "*Denominazione dell'ente"]);
        
        // Campo testuale: tipologia
        $options = AA_Sicar_Const::GetListaTipologieEnte();
        $wnd->AddSelectField("tipologia", "Tipologia", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Tipologia dell'ente", "options" => $options]);
        
        // Campo testuale: geolocalizzazione
        $wnd->AddTextField("geolocalizzazione", "Geoloc.", ["gravity"=>1, "bottomLabel" => "*Geolocalizzazione dell'ente (latitudine e longitudine) in gradi decimali separate da virgola.","placeholder"=>"es. 39.225048459422766, 9.102739130435575"],false);

        // Campo testuale: indirizzo
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>3, "bottomLabel" => "*Indirizzo dell'ente comprensivo del numero civico."]);
        
        // Campo testuale: web
        $wnd->AddTextField("web", "Sito web", ["required" => true,"gravity"=>1, "validateFunction"=>"IsUrl", "bottomLabel" => "*Url del sito web dell'ente","placeholder"=>"es. https://www.miosito.it"]);

        // Campo testuale: pec
        $wnd->AddTextField("pec", "PEC", ["required" => true,"gravity"=>1, "validateFunction"=>"IsEmail", "bottomLabel" => "*PEC dell'ente","placeholder"=>"es. pec@ente.it"],false);
        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("AddNewEnteSicar");
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di aggiunta nuovo nucleo
    public function Template_GetSicarAddNewNucleoDlg()
    {
        $form = "";
        if(!empty($_REQUEST['form']))
        {
            $form = $_REQUEST['form'];
        }

        $field_id="";
        if(!empty($_REQUEST['field_id']))
        {
            $field_id = $_REQUEST['field_id'];
        }

        $field_desc="";
        if(!empty($_REQUEST['field_desc']))
        {
            $field_desc = $_REQUEST['field_desc'];
        }
        
        $id = $this->GetId() . "_AddNewNucleo_Dlg_" . uniqid();
        $form_data = array();

        $newNucleo = new AA_SicarNucleo();
        foreach($newNucleo->GetProps() as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo Nucleo", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(980);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: descrizione
        $wnd->AddTextField("descrizione", "Descrizione", ["required" => true,"gravity"=>2, "bottomLabel" => "*descrizione del nucleo","placeholder"=>"famiglia Rossi"]);
        
        // Campo testuale: cf
        $wnd->AddTextField("cf", "Codice fiscale", ["required" => true, "bottomLabel" => "*codice fiscale del capofmiglia","placeholder"=>"..."],false);
        
        // Campo testuale: indirizzo
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>2, "bottomLabel" => "*Indirizzo di residenza."]);
        
        // Campo testuale: comune
        $wnd->AddTextField("comune", "Comune", ["required" => true, "bottomLabel" => "*Comune dell'immobile (codice ISTAT)", "suggest"=>array("template"=>"#value#","url"=>$this->taskManagerUrl."?task=GetSicarListaCodiciIstat")],false);
       
        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("AddNewNucleoSicar");
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di aggiunta nuovo stato occupazione dell'alloggio
    public function Template_GetSicarAddNewStatoOccupazioneAlloggioDlg($object=null)
    {
        $id = $this->GetId() . "_AddNewStatoOccupazioneAlloggio_Dlg_" . uniqid();
        if(!($object instanceof AA_SicarAlloggio) || $object===null || !$object->IsValid() || $object->GetId()==0)
        {
            $wnd = new AA_GenericWindowTemplate($id, "Aggiunta nuovo stato di occupazione", $this->id);
            $wnd->AddView(new AA_JSON_Template_Generic("",array(
                "view"=>"label",
                "label"=>"Impossibile aggiungere un nuovo stato occupazione. Prima e' necessario selezionare un alloggio valido.",
                "align"=>"center",
                "autowidth"=>true,
                "height"=>100
            )));
            return $wnd;
        }
        
        $form_data = array();
        $form_data['id']=$object->GetId();
        $form_data['data_dal'] = date("Y-m-d");
        $form_data['stato'] = 0; //"libero"
        $form_data['occupazione_tipo'] = 0;
        $form_data['occupazione_id_nucleo'] = 0;
        $form_data['occupazione_nucleo_desc'] = "";
        $form_data['occupazione_data_assegnazione'] = date("Y-m-d");
        $form_data['occupazione_tipo_canone'] = 0;
        $form_data['occupazione_residenza'] = 0;
        $form_data['note'] = "";

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo Stato occupazione", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(760);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        //campo stato alloggio
        $wnd->AddSwitchBoxField("stato", "Stato alloggio", ["onLabel"=>"assegnato/occupato","offLabel"=>"libero", "relatedView"=>$id."_AA_SICAR_DETTAGLIO_OCCUPAZIONE", "relatedAction"=>"show"]);
        
        //campo data: data dal
        $wnd->AddDateField("data_dal", "dal", ["required" => true,"validateFunction"=>"IsIsoDate", "bottomLabel" => "*Data di inizio dello stato dell'alloggio"], false);
        
        $dettaglioOccupazione = new AA_FieldSet($id."_AA_SICAR_DETTAGLIO_OCCUPAZIONE", "Dettaglio occupazione", $wnd->GetFormId(), 1,array("type"=>"clean","hidden"=>true));
        
        $options=array(
            array("id"=>1,"value"=>"Assegnato"),
            array("id"=>2,"value"=>"Occupato"),
            array("id"=>3,"value"=>"Occupato con riserva"),
            array("id"=>4,"value"=>"Occupato abusivo")
        );
        $dettaglioOccupazione->AddRadioField("occupazione_tipo", "Tipo", ["required" => true,"labelWidth"=>120,"options"=>$options, "bottomPadding"=>36, "bottomLabel" => "*Scegliere il tipo di occupazione tra quelli disponibili."]);
        
        // Campo booleano: occupazione riserva
        //$dettaglioOccupazione->AddCheckBoxField("occupazione_riserva", " ", ["required" => false,"labelWidth"=>10,"labelRight"=>"Occupazione con riserva","bottomPadding"=>36, "bottomLabel" => ""]);
        // Campo booleano: occupazione abusivo
        //$dettaglioOccupazione->AddCheckBoxField("occupazione_abusivo", " ", ["required" => false,"labelWidth"=>10,"labelRight"=>"Occupazione abusiva","bottomPadding"=>36, "bottomLabel" => ""],false);
        // Campo booleano: occupazione indirizzo di residenza
        
        //campo riferimento nucleo occupante
        $dlgNucleiParams = array("task" => "GetSicarSearchNucleiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"occupazione_id_nucleo","field_desc"=>"occupazione_nucleo_desc"));
        $dettaglioOccupazione->AddSearchField("dlg",$dlgNucleiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Nucleo","name"=>"occupazione_nucleo_desc", "bottomLabel" => "*Cerca un nucleo gia' esistente o aggiungine uno se non e' presente."]);
       
        $dettaglioOccupazione->AddCheckBoxField("occupazione_residenza", " ", ["required" => false,"labelWidth"=>1,"labelRight"=>"indirizzo di residenza","bottomPadding"=>36, "bottomLabel" => "Aggiorna l'indirizzo di residenza del nucleo occupante."],false);
        
        //campo tipo canone
        $options = AA_Sicar_Const::GetListaTipologieCanoneAlloggio();
        $dettaglioOccupazione->AddSelectField("occupazione_tipo_canone", "Tipo canone", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        
        //campo data assegnazione
        $dettaglioOccupazione->AddDateField('occupazione_data_assegnazione','Assegnato dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>150),false);

        $wnd->AddGenericObject($dettaglioOccupazione);

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Eventuali note aggiuntive"]);

        $wnd->SetSaveTask("AddNewStatoOccupazioneAlloggioSicar");

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di aggiunta nuovo stato interventi dell'alloggio
    public function Template_GetSicarAddNewStatoInterventiAlloggioDlg($object=null)
    {
        $id = $this->GetId() . "_AddNewStatoInterventiAlloggio_Dlg_" . uniqid();
        if(!($object instanceof AA_SicarAlloggio) || $object===null || !$object->IsValid() || $object->GetId()==0)
        {
            $wnd = new AA_GenericWindowTemplate($id, "Aggiunta nuovo intervento", $this->id);
            $wnd->AddView(new AA_JSON_Template_Generic("",array(
                "view"=>"label",
                "label"=>"Impossibile aggiungere un nuovo intervento. Prima e' necessario selezionare un alloggio valido.",
                "align"=>"center",
                "autowidth"=>true,
                "height"=>100
            )));
            return $wnd;
        }
        
        $form_data = array();
        $form_data['id']=$object->GetId();
        $form_data['data_dal'] = date("Y-m-d");
        $form_data['data_al'] = "";
        $form_data['tipologia'] = 0;
        $form_data['stato_intervento'] = 0;
        $form_data['importo_stimato'] = 0;
        $form_data['importo_finanziato'] = 0;
        $form_data['stato_lavori'] = 1; // "non avviati"
        $form_data['id_finanziamento'] = 0;
        $form_data['finanziamento_desc'] = 'Nessun finanziamento associato all\'intervento';
        $form_data['id_richiesta_finanziamento'] = 0;
        $form_data['richiesta_finanziamento_desc'] = "Nessuna richiesta di finanziamento associata all'intervento";
        $form_data['programma_finanziamento'] = 1; // "Nessuno"
        $form_data['cup'] = "";
        $form_data['note'] = "";

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo intervento", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(90);
        $wnd->SetWidth(760);
        $wnd->SetHeight(640);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        //campo stato
        //$wnd->AddSwitchBoxField("stato_intervento", "Stato", ["onLabel"=>"Intervento necessario","offLabel"=>"Nessun intervento necessario", "relatedView"=>$id."_AA_SICAR_DETTAGLIO_INTERVENTO", "relatedAction"=>"show"]);
        
        //campo data: data dal
        $wnd->AddDateField("data_dal", "dal", ["required" => true,"validateFunction"=>"IsIsoDate", "bottomLabel" => "*Data di inizio"], false);

        //campo data: data al
        $wnd->AddDateField("data_al", "al", ["required" => false,"validateFunction"=>"IsIsoDate", "bottomLabel" => "*Data di fine"], false);
        
        $dettaglioIntervento = new AA_FieldSet($id."_AA_SICAR_DETTAGLIO_INTERVENTO", "Dettaglio intervento", $wnd->GetFormId(), 1,array("type"=>"clean","hidden"=>false));
        
        $options=array(
            array("id"=>1,"value"=>"Manutenzione ordinaria"),
            array("id"=>2,"value"=>"Manutenzione straordinaria"),
            array("id"=>3,"value"=>"Ristrutturazione"),
            array("id"=>4,"value"=>"Nuova costruzione")
        );
        $dettaglioIntervento->AddSelectField("tipologia", "Tipologia", ["required" => true,"labelWidth"=>120,"validateFunction"=>"IsSelected","options"=>$options, "bottomPadding"=>36, "bottomLabel" => "*Scegliere il tipo di intervento tra quelli disponibili."]);
        
        //importo stimato
        $dettaglioIntervento->AddTextField("importo_stimato", "Importo stimato", ["required" => true,"labelWidth"=>130, "validateFunction"=>"IsPositive", "bottomLabel" => "*Inserire l'importo stimato per la realizzazione dell'intervento."],false);

        //stato lavori  
        $options=array(
            array("id"=>1,"value"=>"Non avviati"),
            array("id"=>2,"value"=>"Avviati")
        );
        $dettaglioIntervento->AddSelectField("stato_lavori", "Stato dei lavori", ["required" => true,"labelWidth"=>120,"options"=>$options, "bottomPadding"=>36, "bottomLabel" => "*Scegliere uno degli stati dei lavori tra quelli disponibili."]);
        
        //$dettaglioIntervento->AddSpacer(false);

        $options = AA_Sicar_Const::GetListaTipologieProgFinanziamento();
        $dettaglioIntervento->AddSelectField("programma_finanziamento", "Programma", ["required" => false,"bottomPadding"=>42,"labelWidth"=>120,"validateFunction"=>"IsSelected", "bottomLabel" => "Scegliere il programma di finanziamento a cui associare la richiesta di intervento.", "options" => $options],false);

        //richiesta di finanziamento
        $dlgParams = array("task" => "GetSicarSearchRichiesteFinanziamentoDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"id_richiesta_finanziamento","field_desc"=>"richiesta_finanziamento_desc"));
        $dettaglioIntervento->AddSearchField("dlg",$dlgParams,$this->GetId(),["required" => false,"gravity"=>2,"label"=>"Richiesta","labelWidth"=>130, "name"=>"richiesta_finanziamento_desc","bottomLabel" => "Fai click sulla lente per selezionare una richiesta di finanziamento da associare all'intervento, o lascia vuoto se vuoi generare una richiesta di finanziamento successivamente."]);

        //campo riferimento finanziamento
        //$dlgParams = array("task" => "GetSicarSearchFinanziamentiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"id_finanziamento","field_desc"=>"finanziamento_desc"));
        //$dettaglioIntervento->AddSearchField("dlg",$dlgParams,$this->GetId(),["required" => false,"gravity"=>2,"label"=>"Finanziamento","labelWidth"=>130,"name"=>"finanziamento_desc", "bottomLabel" => "*Fai click sulla lente per selezionare un finanziamento dalla lista o lascia vuoto se vuoi utilizzare la funzione di associazione automatica dalla gestione dei finanziamenti."]);
       
        //cup
        $dettaglioIntervento->AddTextField("cup", "CUP", ["required" => false,"labelWidth"=>130, "bottomLabel" => "Inserire il codice CUP associato all'intervento."]);

        $wnd->AddGenericObject($dettaglioIntervento);

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false,"bottomPadding"=>0, "bottomLabel" => "Eventuali note aggiuntive"]);

        $wnd->SetSaveTask("AddNewStatoInterventiAlloggioSicar");

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di aggiunta nuovo intervento dell'immobile
    public function Template_GetSicarAddNewInterventoImmobileDlg($object=null)
    {
        $id = $this->GetId() . "_AddNewInterventoImmobile_Dlg_" . uniqid();
        if(!($object instanceof AA_SicarImmobile))
        {
            $wnd = new AA_GenericWindowTemplate($id, "Aggiunta nuovo intervento", $this->id);
            $wnd->AddView(new AA_JSON_Template_Generic("",array(
                "view"=>"label",
                "label"=>"Impossibile aggiungere un nuovo intervento. Prima e' necessario selezionare un immobile valido.",
                "align"=>"center",
                "autowidth"=>true,
                "height"=>100
            )));
            return $wnd;
        }
        
        $form_data = array();
        $form_data['id_immobile']=$object->GetProp('id');
        $form_data['data_dal'] = date("Y-m-d");
        $form_data['data_al'] = "";
        $form_data['tipologia'] = 0;
        $form_data['stato_intervento'] = 0;
        $form_data['importo_stimato'] = 0;
        $form_data['importo_finanziato'] = 0;
        $form_data['stato_lavori'] = 1; // "non avviati"
        $form_data['id_finanziamento'] = 0;
        $form_data['finanziamento_desc'] = 'Nessun finanziamento associato all\'intervento';
        $form_data['id_richiesta_finanziamento'] = 0;
        $form_data['richiesta_finanziamento_desc'] = "Nessuna richiesta di finanziamento associata all'intervento";
        $form_data['programma_finanziamento'] = 1; // "Nessuno"
        $form_data['cup'] = "";
        $form_data['note'] = "";

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo intervento su parti comuni", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(90);
        $wnd->SetWidth(760);
        $wnd->SetHeight(640);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
    
        //campo data: data dal
        $wnd->AddDateField("data_dal", "dal", ["required" => true,"validateFunction"=>"IsIsoDate", "bottomLabel" => "*Data di inizio"], false);

        //campo data: data al
        $wnd->AddDateField("data_al", "al", ["required" => false,"validateFunction"=>"IsIsoDate", "bottomLabel" => "*Data di fine"], false);
        
        $dettaglioIntervento = new AA_FieldSet($id."_AA_SICAR_DETTAGLIO_INTERVENTO", "Dettaglio intervento", $wnd->GetFormId(), 1,array("type"=>"clean","hidden"=>false));
        
        $options=array(
            array("id"=>1,"value"=>"Manutenzione ordinaria"),
            array("id"=>2,"value"=>"Manutenzione straordinaria"),
            array("id"=>3,"value"=>"Ristrutturazione"),
            array("id"=>4,"value"=>"Nuova costruzione")
        );
        $dettaglioIntervento->AddSelectField("tipologia", "Tipologia", ["required" => true,"labelWidth"=>120,"validateFunction"=>"IsSelected","options"=>$options, "bottomPadding"=>36, "bottomLabel" => "*Scegliere il tipo di intervento tra quelli disponibili."]);
        
        //importo stimato
        $dettaglioIntervento->AddTextField("importo_stimato", "Importo stimato", ["required" => true,"labelWidth"=>130, "validateFunction"=>"IsPositive", "bottomLabel" => "*Inserire l'importo stimato per la realizzazione dell'intervento."],false);

        //stato lavori  
        $options=array(
            array("id"=>1,"value"=>"Non avviati"),
            array("id"=>2,"value"=>"Avviati")
        );
        $dettaglioIntervento->AddSelectField("stato_lavori", "Stato dei lavori", ["required" => true,"labelWidth"=>120,"options"=>$options, "bottomPadding"=>36, "bottomLabel" => "*Scegliere uno degli stati dei lavori tra quelli disponibili."]);
        
        //$dettaglioIntervento->AddSpacer(false);

        $options = AA_Sicar_Const::GetListaTipologieProgFinanziamento();
        $dettaglioIntervento->AddSelectField("programma_finanziamento", "Programma", ["required" => false,"bottomPadding"=>42,"labelWidth"=>120,"validateFunction"=>"IsSelected", "bottomLabel" => "Scegliere il programma di finanziamento a cui associare la richiesta di intervento.", "options" => $options],false);

        //richiesta di finanziamento
        $dlgParams = array("task" => "GetSicarSearchRichiesteFinanziamentoDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"id_richiesta_finanziamento","field_desc"=>"richiesta_finanziamento_desc"));
        $dettaglioIntervento->AddSearchField("dlg",$dlgParams,$this->GetId(),["required" => false,"gravity"=>2,"label"=>"Richiesta","labelWidth"=>130, "name"=>"richiesta_finanziamento_desc","bottomLabel" => "Fai click sulla lente per selezionare una richiesta di finanziamento da associare all'intervento, o lascia vuoto se vuoi generare una richiesta di finanziamento successivamente."]);

        //campo riferimento finanziamento
        //$dlgParams = array("task" => "GetSicarSearchFinanziamentiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"id_finanziamento","field_desc"=>"finanziamento_desc"));
        //$dettaglioIntervento->AddSearchField("dlg",$dlgParams,$this->GetId(),["required" => false,"gravity"=>2,"label"=>"Finanziamento","labelWidth"=>130,"name"=>"finanziamento_desc", "bottomLabel" => "*Fai click sulla lente per selezionare un finanziamento dalla lista o lascia vuoto se vuoi utilizzare la funzione di associazione automatica dalla gestione dei finanziamenti."]);
       
        //cup
        $dettaglioIntervento->AddTextField("cup", "CUP", ["required" => false,"labelWidth"=>130, "bottomLabel" => "Inserire il codice CUP associato all'intervento."]);

        $wnd->AddGenericObject($dettaglioIntervento);

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false,"bottomPadding"=>0, "bottomLabel" => "Eventuali note aggiuntive"]);

        $wnd->SetSaveTask("AddNewInterventoImmobileSicar");

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di modifica intervento dell'immobile
    public function Template_GetSicarModifyInterventoImmobileDlg($object=null,$id_intervento)
    {
        $id = $this->GetId() . "_ModifyInterventoImmobile_Dlg_" . uniqid();
        if(!($object instanceof AA_SicarImmobile))
        {
            $wnd = new AA_GenericWindowTemplate($id, "Modifica intervento", $this->id);
            $wnd->AddView(new AA_JSON_Template_Generic("",array(
                "view"=>"label",
                "label"=>"Impossibile modificare l'intervento. Prima e' necessario selezionare un immobile valido.",
                "align"=>"center",
                "autowidth"=>true,
                "height"=>100
            )));
            return $wnd;
        }

        $interventi = $object->GetInterventi();
        if(!isset($interventi[$id_intervento]))
        {
            $wnd = new AA_GenericWindowTemplate($id, "Modifica intervento", $this->id);
            $wnd->AddView(new AA_JSON_Template_Generic("",array(
                "view"=>"label",
                "label"=>"Impossibile modificare l'intervento. Prima e' necessario selezionare un intervento valido.",
                "align"=>"center",
                "autowidth"=>true,
                "height"=>100
            )));
            return $wnd;
        }
       
        $intervento = $interventi[$id_intervento];

        $form_data = array();
        $form_data['id_immobile']=$object->GetProp('id');
        $form_data['id_intervento']=$id_intervento;
        foreach($intervento as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }
        
        $form_data['importo_stimato'] = AA_Utils::number_format($intervento["importo_stimato"],2,",",".");
        $form_data['importo_finanziato'] = AA_Utils::number_format($intervento["importo_finanziato"],2,",",".");

        $wnd = new AA_GenericFormDlg($id, "Modifica intervento su parti comuni", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(90);
        $wnd->SetWidth(760);
        $wnd->SetHeight(640);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
    
        //campo data: data dal
        $wnd->AddDateField("data_dal", "dal", ["required" => true,"validateFunction"=>"IsIsoDate", "bottomLabel" => "*Data di inizio"], false);

        //campo data: data al
        $wnd->AddDateField("data_al", "al", ["required" => false,"validateFunction"=>"IsIsoDate", "bottomLabel" => "*Data di fine"], false);
        
        $dettaglioIntervento = new AA_FieldSet($id."_AA_SICAR_DETTAGLIO_INTERVENTO", "Dettaglio intervento", $wnd->GetFormId(), 1,array("type"=>"clean","hidden"=>false));
        
        $options=array(
            array("id"=>1,"value"=>"Manutenzione ordinaria"),
            array("id"=>2,"value"=>"Manutenzione straordinaria"),
            array("id"=>3,"value"=>"Ristrutturazione"),
            array("id"=>4,"value"=>"Nuova costruzione")
        );
        $dettaglioIntervento->AddSelectField("tipologia", "Tipologia", ["required" => true,"labelWidth"=>120,"validateFunction"=>"IsSelected","options"=>$options, "bottomPadding"=>36, "bottomLabel" => "*Scegliere il tipo di intervento tra quelli disponibili."]);
        
        //importo stimato
        $dettaglioIntervento->AddTextField("importo_stimato", "Importo stimato", ["required" => true,"labelWidth"=>130, "validateFunction"=>"IsPositive", "bottomLabel" => "*Inserire l'importo stimato per la realizzazione dell'intervento."],false);

        //stato lavori  
        $options=array(
            array("id"=>1,"value"=>"Non avviati"),
            array("id"=>2,"value"=>"Avviati")
        );
        $dettaglioIntervento->AddSelectField("stato_lavori", "Stato dei lavori", ["required" => true,"labelWidth"=>120,"options"=>$options, "bottomPadding"=>36, "bottomLabel" => "*Scegliere uno degli stati dei lavori tra quelli disponibili."]);
        
        //$dettaglioIntervento->AddSpacer(false);

        $options = AA_Sicar_Const::GetListaTipologieProgFinanziamento();
        $dettaglioIntervento->AddSelectField("programma_finanziamento", "Programma", ["required" => false,"bottomPadding"=>42,"labelWidth"=>120,"validateFunction"=>"IsSelected", "bottomLabel" => "Scegliere il programma di finanziamento a cui associare la richiesta di intervento.", "options" => $options],false);

        //richiesta di finanziamento
        $dlgParams = array("task" => "GetSicarSearchRichiesteFinanziamentoDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"id_richiesta_finanziamento","field_desc"=>"richiesta_finanziamento_desc"));
        $dettaglioIntervento->AddSearchField("dlg",$dlgParams,$this->GetId(),["required" => false,"gravity"=>2,"label"=>"Richiesta","labelWidth"=>130, "name"=>"richiesta_finanziamento_desc","bottomLabel" => "Fai click sulla lente per selezionare una richiesta di finanziamento da associare all'intervento, o lascia vuoto se vuoi generare una richiesta di finanziamento successivamente."]);

        //campo riferimento finanziamento
        //$dlgParams = array("task" => "GetSicarSearchFinanziamentiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"id_finanziamento","field_desc"=>"finanziamento_desc"));
        //$dettaglioIntervento->AddSearchField("dlg",$dlgParams,$this->GetId(),["required" => false,"gravity"=>2,"label"=>"Finanziamento","labelWidth"=>130,"name"=>"finanziamento_desc", "bottomLabel" => "*Fai click sulla lente per selezionare un finanziamento dalla lista o lascia vuoto se vuoi utilizzare la funzione di associazione automatica dalla gestione dei finanziamenti."]);
       
        //cup
        $dettaglioIntervento->AddTextField("cup", "CUP", ["required" => false,"labelWidth"=>130, "bottomLabel" => "Inserire il codice CUP associato all'intervento."]);

        $wnd->AddGenericObject($dettaglioIntervento);

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false,"bottomPadding"=>0, "bottomLabel" => "Eventuali note aggiuntive"]);

        $wnd->SetSaveTask("UpdateInterventoImmobileSicar");

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di modifica stato interventi dell'alloggio
    public function Template_GetSicarModifyStatoInterventiAlloggioDlg($object=null,$id_intervento=null)
    {
        $id = $this->GetId() . "_ModifyStatoInterventiAlloggio_Dlg_" . uniqid();
        if(!($object instanceof AA_SicarAlloggio) || $object===null || !$object->IsValid() || $object->GetId()==0)
        {
            $wnd = new AA_GenericWindowTemplate($id, "Modifica intervento", $this->id);
            $wnd->AddView(new AA_JSON_Template_Generic("",array(
                "view"=>"label",
                "label"=>"Impossibile modificare l'intervento. Prima e' necessario selezionare un alloggio valido.",
                "align"=>"center",
                "autowidth"=>true,
                "height"=>100
            )));
            return $wnd;
        }
        
        $interventi=$object->GetInterventi();
        if(!isset($interventi[$id_intervento]) || $interventi[$id_intervento]===null)
        {
            $wnd = new AA_GenericWindowTemplate($id, "Modifica intervento", $this->id);
            $wnd->AddView(new AA_JSON_Template_Generic("",array(
                "view"=>"label",
                "label"=>"Impossibile modificare l'intervento. Prima e' necessario selezionare un intervento valido.",
                "align"=>"center",
                "autowidth"=>true,
                "height"=>100
            )));
            return $wnd;
        }

        $curIntervento=$interventi[$id_intervento];

        $form_data = array();
        $form_data['id']=$object->GetId();
        $form_data['id_intervento']=$id_intervento;
        $form_data['data_dal'] = $curIntervento["data_dal"];
        $form_data['data_al'] = $curIntervento["data_al"];
        $form_data['cup'] = $curIntervento["cup"];
        $form_data['tipologia'] = $curIntervento["tipologia"];
        $form_data['stato_intervento'] = $curIntervento["stato_intervento"];
        $form_data['importo_stimato'] = AA_Utils::number_format($curIntervento["importo_stimato"],2,",",".");
        $form_data['importo_finanziato'] = AA_Utils::number_format($curIntervento["importo_finanziato"],2,",",".");
        $form_data['stato_lavori'] = $curIntervento["stato_lavori"];
        
        //campo riferimento finanziamento
        $form_data['id_finanziamento'] = $curIntervento["id_finanziamento"];
        $finanziamento=new AA_SicarFinanziamento();
        if(!$finanziamento->Load($form_data['id_finanziamento']))
        {
            $form_data['finanziamento_desc'] = "Nessun finanziamento associato all'intervento";
        }
        else
        {
            $form_data['finanziamento_desc'] = $finanziamento->GetName();
        }

        //campo riferimento richiesta di finanziamento
        $form_data['id_richiesta_finanziamento'] = $curIntervento["id_richiesta_finanziamento"];
        $richiestaFinanziamento=new AA_SicarRichiestaFinanziamento();
        if(!$richiestaFinanziamento->Load($form_data['id_richiesta_finanziamento']))
        {            
            $form_data['richiesta_finanziamento_desc'] = "Nessuna richiesta di finanziamento associata all'intervento";
        }
        else        
        {
            $form_data['richiesta_finanziamento_desc'] = $richiestaFinanziamento->GetName();
        }

        $form_data['programma_finanziamento'] = $curIntervento["programma_finanziamento"];
        $form_data['note'] = $curIntervento["note"];

        $wnd = new AA_GenericFormDlg($id, "Modifica intervento", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(90);
        $wnd->SetWidth(760);
        $wnd->SetHeight(640);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        //campo stato
        //$wnd->AddSwitchBoxField("stato_intervento", "Stato", ["onLabel"=>"Intervento necessario","offLabel"=>"Nessun intervento necessario", "relatedView"=>$id."_AA_SICAR_DETTAGLIO_INTERVENTO", "relatedAction"=>"show"]);
        
        //campo data: data dal
        $wnd->AddDateField("data_dal", "dal", ["required" => true,"readonly"=>false,"validateFunction"=>"IsIsoDate", "bottomLabel" => "*Data di inizio"]);
        
        //campo data: data al
        $wnd->AddDateField("data_al", "al", ["required" => false,"readonly"=>false,"validateFunction"=>"IsIsoDate", "bottomLabel" => "*Data di fine"], false);
        
        $dettaglioIntervento = new AA_FieldSet($id."_AA_SICAR_DETTAGLIO_INTERVENTO", "Dettaglio intervento", $wnd->GetFormId(), 1,array("type"=>"clean","hidden"=>false));
        
        $options=array(
            array("id"=>1,"value"=>"Manutenzione ordinaria"),
            array("id"=>2,"value"=>"Manutenzione straordinaria"),
            array("id"=>3,"value"=>"Ristrutturazione"),
            array("id"=>4,"value"=>"Nuova costruzione")
        );
        $dettaglioIntervento->AddSelectField("tipologia", "Tipologia", ["required" => true,"labelWidth"=>120,"validateFunction"=>"IsSelected","options"=>$options, "bottomPadding"=>36, "bottomLabel" => "*Scegliere il tipo di intervento tra quelli disponibili."]);
        
        //importo stimato
        $dettaglioIntervento->AddTextField("importo_stimato", "Importo stimato", ["required" => true,"labelWidth"=>130, "validateFunction"=>"IsPositive", "bottomLabel" => "*Inserire l'importo stimato per la realizzazione dell'intervento."],false);

        //stato lavori  
        $options=array(
            array("id"=>1,"value"=>"Non avviati"),
            array("id"=>2,"value"=>"Avviati")
        );
        $dettaglioIntervento->AddSelectField("stato_lavori", "Stato dei lavori", ["required" => true,"labelWidth"=>120,"options"=>$options, "bottomPadding"=>36, "bottomLabel" => "*Scegliere uno degli stati dei lavori tra quelli disponibili."]);
        
        //$dettaglioIntervento->AddSpacer(false);

        $options = AA_Sicar_Const::GetListaTipologieProgFinanziamento();
        $dettaglioIntervento->AddSelectField("programma_finanziamento", "Programma", ["required" => false,"bottomPadding"=>42,"labelWidth"=>120,"validateFunction"=>"IsSelected", "bottomLabel" => "Scegliere il programma di finanziamento a cui associare la richiesta di intervento.", "options" => $options],false);

        //richiesta di finanziamento
        $dlgParams = array("task" => "GetSicarSearchRichiesteFinanziamentoDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"id_richiesta_finanziamento","field_desc"=>"richiesta_finanziamento_desc"));
        $dettaglioIntervento->AddSearchField("dlg",$dlgParams,$this->GetId(),["required" => false,"gravity"=>2,"label"=>"Richiesta","labelWidth"=>130, "name"=>"richiesta_finanziamento_desc","bottomLabel" => "Fai click sulla lente per selezionare una richiesta di finanziamento da associare all'intervento, o lascia vuoto se vuoi generare una richiesta di finanziamento successivamente."]);

        //campo riferimento finanziamento
        //$dlgParams = array("task" => "GetSicarSearchFinanziamentiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"id_finanziamento","field_desc"=>"finanziamento_desc"));
        //$dettaglioIntervento->AddSearchField("dlg",$dlgParams,$this->GetId(),["required" => false,"gravity"=>2,"label"=>"Finanziamento","labelWidth"=>130,"name"=>"finanziamento_desc", "bottomLabel" => "*Fai click sulla lente per selezionare un finanziamento dalla lista o lascia vuoto se vuoi utilizzare la funzione di associazione automatica dalla gestione dei finanziamenti."]);
       
        //cup
        $dettaglioIntervento->AddTextField("cup", "CUP", ["required" => false,"labelWidth"=>130, "bottomLabel" => "Inserire il codice CUP associato all'intervento."]);

        $wnd->AddGenericObject($dettaglioIntervento);

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false,"bottomPadding"=>0, "bottomLabel" => "Eventuali note aggiuntive"]);

        $wnd->SetSaveTask("UpdateStatoInterventiAlloggioSicar");

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di modifica stato occupazione dell'alloggio
    public function Template_GetSicarModifyStatoOccupazioneAlloggioDlg($object=null,$dal="")
    {
        $id = $this->GetId() . "_ModifyStatoOccupazioneAlloggio_Dlg_" . uniqid();
        if(!($object instanceof AA_SicarAlloggio) || $object===null || !$object->IsValid() || $object->GetId()==0)
        {
            $wnd = new AA_GenericWindowTemplate($id, "Modifica stato di occupazione", $this->id);
            $wnd->AddView(new AA_JSON_Template_Generic("",array(
                "view"=>"label",
                "label"=>"Impossibile modificare lo stato occupazione. Prima e' necessario selezionare un alloggio valido.",
                "align"=>"center",
                "autowidth"=>true,
                "height"=>100
            )));
            return $wnd;
        }
        $occupazione=$object->GetOccupazione();
        if(!isset($occupazione[$dal]) || empty($occupazione[$dal]))
        {
            $wnd = new AA_GenericWindowTemplate($id, "Modifica stato di occupazione", $this->id);
            $wnd->AddView(new AA_JSON_Template_Generic("",array(
                "view"=>"label",
                "label"=>"Stato occupazione non trovato.",
                "align"=>"center",
                "autowidth"=>true,
                "height"=>100
            )));
            return $wnd;
        }

        $occupazione=$occupazione[$dal];
        if($occupazione['occupazione_id_nucleo']>0)
        {
            $nucleo = new AA_SicarNucleo();
            if($nucleo->Load($occupazione['occupazione_id_nucleo']))
            {
                $occupazione['occupazione_nucleo_desc']=$nucleo->GetDescrizione();
            }
            else 
            {
                $occupazione['occupazione_nucleo_desc']="Nucleo non trovato.";
            }
        }
       
        $form_data = array();
        $form_data['id']=$object->GetId();
        $form_data['data_dal'] = $dal;
        $form_data['stato'] = $occupazione['stato'];
        $form_data['occupazione_tipo'] = $occupazione['occupazione_tipo'];
        $form_data['occupazione_id_nucleo'] = $occupazione['occupazione_id_nucleo'];
        $form_data['occupazione_nucleo_desc'] = $occupazione['occupazione_nucleo_desc'];
        $form_data['occupazione_data_assegnazione'] = $occupazione['occupazione_data_assegnazione'];
        $form_data['occupazione_tipo_canone'] = $occupazione['occupazione_tipo_canone'];
        $form_data['occupazione_residenza'] = $occupazione['occupazione_residenza'];
        $form_data['note'] = $occupazione['note'];

        $wnd = new AA_GenericFormDlg($id, "Modifica Stato occupazione per data dal: ".$dal, $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(760);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        //campo stato alloggio
        $wnd->AddSwitchBoxField("stato", "Stato alloggio", ["onLabel"=>"assegnato/occupato","offLabel"=>"libero", "relatedView"=>$id."_AA_SICAR_DETTAGLIO_OCCUPAZIONE", "relatedAction"=>"show"]);
        
        $dettaglioOccupazione = new AA_FieldSet($id."_AA_SICAR_DETTAGLIO_OCCUPAZIONE", "Dettaglio occupazione", $wnd->GetFormId(), 1,array("type"=>"clean","hidden"=>true));
        $options=array(
            array("id"=>1,"value"=>"Assegnato"),
            array("id"=>2,"value"=>"Occupato"),
            array("id"=>3,"value"=>"Occupato con riserva"),
            array("id"=>4,"value"=>"Occupato abusivo")
        );
        $dettaglioOccupazione->AddRadioField("occupazione_tipo", "Tipo", ["required" => true,"labelWidth"=>120,"options"=>$options, "bottomPadding"=>36, "bottomLabel" => "*Scegliere il tipo di occupazione tra quelli disponibili."]);
           
        //campo riferimento nucleo occupante
        $dlgNucleiParams = array("task" => "GetSicarSearchNucleiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"occupazione_id_nucleo","field_desc"=>"occupazione_nucleo_desc"));
        $dettaglioOccupazione->AddSearchField("dlg",$dlgNucleiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Nucleo","name"=>"occupazione_nucleo_desc", "bottomLabel" => "*Cerca un nucleo gia' esistente o aggiungine uno se non e' presente."]);
       
        // Campo booleano: occupazione indirizzo di residenza
        $dettaglioOccupazione->AddCheckBoxField("occupazione_residenza", " ", ["required" => false,"labelWidth"=>1,"labelRight"=>"indirizzo di residenza","bottomPadding"=>36, "bottomLabel" => "Aggiorna l'indirizzo di residenza del nucleo occupante."],false);
        
        //campo tipo canone
        $options = AA_Sicar_Const::GetListaTipologieCanoneAlloggio();
        $dettaglioOccupazione->AddSelectField("occupazione_tipo_canone", "Tipo canone", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        
        //campo data assegnazione
        $dettaglioOccupazione->AddDateField('occupazione_data_assegnazione','Assegnato dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>150),false);

        $wnd->AddGenericObject($dettaglioOccupazione);

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Eventuali note aggiuntive"]);

        $wnd->SetSaveTask("UpdateStatoOccupazioneAlloggioSicar");

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di modifica nucleo
    public function Template_GetSicarModifyNucleoDlg($object)
    {
        $form = "";
        if(!empty($_REQUEST['form']))
        {
            $form = $_REQUEST['form'];
        }

        $field_id="";
        if(!empty($_REQUEST['field_id']))
        {
            $field_id = $_REQUEST['field_id'];
        }

        $field_desc="";
        if(!empty($_REQUEST['field_desc']))
        {
            $field_desc = $_REQUEST['field_desc'];
        }
        
        $id = $this->GetId() . "_ModifyNucleo_Dlg_" . uniqid();
        $form_data = array();

        foreach($object->GetProps() as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }
        $form_data['comune']=AA_Sicar_Const::GetComuneDescrFromCodiceIstat($object->GetProp('comune'))." (".$object->GetProp("comune").")";

        $wnd = new AA_GenericFormDlg($id, "Modifica Nucleo", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(980);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: descrizione
        $wnd->AddTextField("descrizione", "Descrizione", ["required" => true,"gravity"=>2, "bottomLabel" => "*descrizione del nucleo","placeholder"=>"famiglia Rossi"]);
        
        // Campo testuale: cf
        $wnd->AddTextField("cf", "Codice fiscale", ["required" => true, "bottomLabel" => "*codice fiscale del capofmiglia","placeholder"=>"..."],false);
        
        // Campo testuale: indirizzo
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>2, "bottomLabel" => "*Indirizzo di residenza."]);
        
        // Campo testuale: comune
        $wnd->AddTextField("comune", "Comune", ["required" => true, "bottomLabel" => "*Comune dell'immobile (codice ISTAT)", "suggest"=>array("template"=>"#value#","url"=>$this->taskManagerUrl."?task=GetSicarListaCodiciIstat")],false);
       
        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("UpdateNucleoSicar");
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di modifica ente
    public function Template_GetSicarModifyEnteDlg($object=null)
    {
        $form = "";
        if(!empty($_REQUEST['form']))
        {
            $form = $_REQUEST['form'];
        }

        $field_id="";
        if(!empty($_REQUEST['field_id']))
        {
            $field_id = $_REQUEST['field_id'];
        }

        $field_desc="";
        if(!empty($_REQUEST['field_desc']))
        {
            $field_desc = $_REQUEST['field_desc'];
        }
        
        $id = $this->GetId() . "_ModifyEnte_Dlg_" . uniqid();
        $form_data = array();

        if(!($object instanceof AA_SicarEnte))
        {
            AA_Log::Log(__METHOD__." - Ento0 non specificato: ".print_r($object,true),100);
            return new AA_GenericFormDlg($id, "Aggiungi nuovo Ente", $this->id, $form_data, $form_data);
        }

        $newImmobile = $object;
        $form_data['id']=$newImmobile->GetProp('id');
        foreach($newImmobile->GetProps() as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo Ente", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(980);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: descrizione
        $wnd->AddTextField("denominazione", "Denominazione", ["required" => true, "bottomLabel" => "*Denominazione dell'ente"]);
        
        // Campo testuale: tipologia
        $options = AA_Sicar_Const::GetListaTipologieEnte();
        $wnd->AddSelectField("tipologia", "Tipologia", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Tipologia dell'ente", "options" => $options]);
        
        // Campo testuale: geolocalizzazione
        $wnd->AddTextField("geolocalizzazione", "Geoloc.", ["gravity"=>1, "bottomLabel" => "*Geolocalizzazione dell'ente (latitudine e longitudine) in gradi decimali separate da virgola.","placeholder"=>"es. 39.225048459422766, 9.102739130435575"],false);

        // Campo testuale: indirizzo
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>3, "bottomLabel" => "*Indirizzo dell'ente comprensivo del numero civico."]);
        
        // Campo testuale: web
        $wnd->AddTextField("web", "Sito web", ["required" => true,"gravity"=>1, "validateFunction"=>"IsUrl", "bottomLabel" => "*Url del sito web dell'ente","placeholder"=>"es. https://www.miosito.it"]);

        // Campo testuale: pec
        $wnd->AddTextField("pec", "PEC", ["required" => true,"gravity"=>1, "validateFunction"=>"IsEmail", "bottomLabel" => "*PEC dell'ente","placeholder"=>"es. pec@ente.it"],false);
        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("AddNewEnteSicar");
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di modifica immobile esistente
    public function Template_GetSicarModifyImmobileDlg($immobile=null)
    {        
        $id = $this->GetId() . "_Modify_Dlg_" . uniqid();
        $form_data = array();

        foreach($immobile->GetProps() as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }

        $form_data['id']=$immobile->GetProp("id");

        //dati catastali
        $catasto=$immobile->GetCatasto();

        $form_data['catasto']="";
        $form_data['SezioneCatasto'] = $catasto['SezioneCatasto'];
        $form_data['FoglioCatasto'] = $catasto['FoglioCatasto'];
        $form_data['MappaleCatasto'] = $catasto['MappaleCatasto'];
        $form_data['ParticellaCatasto'] = $catasto['ParticellaCatasto'];
        $form_data['Subalterno'] = $catasto['Subalterno'];

        $attributiData=$immobile->GetAttributi();
        $form_data['attributi']="";

        $form_data['attributi_condominio_misto']="";
        $form_data['attributi_alloggi']="";
        
        $gestore=$immobile->GetGestore();
        if($gestore)
        {
            $form_data['immobile_gestione_ente']=$gestore->GetProp("id");
            $form_data['immobile_gestione_ente_desc']=$gestore->GetDisplayName();
            $form_data['immobile_gestione_dal']=$immobile->GetGestioneDal();
        }
        else
        {
            AA_Log::Log(__METHOD__." - Ente gestore non impostato o non trovato. (".print_r($immobile->GetProp("attributi"),true));
            $form_data['immobile_gestione_ente']="";
            $form_data['immobile_gestione_ente_desc']="";
            $form_data['immobile_gestione_dal']="";
        }

        if(!empty($attributiData['condominio_misto'])) $form_data['attributi_condominio_misto']=$attributiData['condominio_misto'];
        if(!empty($attributiData['alloggi'])) $form_data['attributi_alloggi']=$attributiData['alloggi'];

        $wnd = new AA_GenericFormDlg($id, "Modifica immobile", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(1280);
        $wnd->SetHeight(800);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: descrizione
        $wnd->AddTextField("descrizione", "Descrizione", ["required" => true, "bottomLabel" => "*Descrizione dell'immobile"]);
        
        // Campo testuale: tipologia
        $options = AA_Sicar_Const::GetListaTipologie();
        $wnd->AddSelectField("tipologia", "Tipologia", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Tipologia dell'immobile", "options" => $options]);
        
        // Campo testuale: comune
        $wnd->AddTextField("comune", "Comune", ["required" => true, "bottomLabel" => "*Comune dell'immobile (codice ISTAT)", "suggest"=>array("template"=>"#codice#","url"=>$this->taskManagerUrl."?task=GetSicarListaCodiciIstat")],false);
       
        // Campo testuale: ubicazione
        $options= AA_Sicar_Const::GetListaUbicazioni();
        $wnd->AddSelectField("ubicazione", "Ubicazione", ["required" => true,"validateFunction"=>"IsSelected","gravity"=>2, "bottomLabel" => "*Ubicazione dell'immobile", "options" => $options]);
        
        // Campo testuale: zona urbanistica
        $options = AA_Sicar_Const::GetListaZoneUrbanistiche();
        $wnd->AddSelectField("zona_urbanistica", "Zona urb.", ["required" => true,"gravity"=>2, "bottomLabel" => "*Zona urbanistica dell'immobile", "options" => $options],false);
        
        // Campo numerico: piani
        //$wnd->AddTextField("piani", "Piani", ["required" => true,"gravity"=>1,"validateFunction"=>"IsPositive", "bottomLabel" => "*Numero di piani dell'immobile"],false);

        // Campo testuale: indirizzo
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>1, "bottomLabel" => "*Indirizzo dell'immobile comprensivo del numero civico."]);
        
        // Campo testuale: geolocalizzazione
        $wnd->AddTextField("geolocalizzazione", "Geoloc.", ["required" => true,"gravity"=>1, "bottomLabel" => "*Geolocalizzazione dell'immobile (latitudine e longitudine) in gradi decimali separate da virgola (es.: 39.22, 9.10)","placeholder"=>"es. 39.22, 9.10"],false);

        //Dati catastali
        $catasto = new AA_FieldSet("AA_SICAR_CATASTO","Dati catastali",$wnd->GetFormId(),1,array("type"=>"clean"));

        //sezione catasto
        $label="Sezione";
        $options=array(
            array("id"=>0,"value"=>"Catasto urbano"),
            array("id"=>1,"value"=>"Catasto terreni")
        );
        $catasto->AddRadioField("SezioneCatasto",$label,array("options"=>$options,"bottomLabel"=>"*Indicare la sezione in cui è accatastato l'immobile.", "value"=>0,"required"=>true));
        //foglio catasto
        $label="Foglio";
        $catasto->AddTextField("FoglioCatasto",$label,array("tooltip"=>"*Inserire il numero del foglio in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."));
        
        //mappale catasto
        $label="Mappale";
        $catasto->AddTextField("MappaleCatasto",$label,array("tooltip"=>"*Inserire il numero di mappale in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //particella catasto
        $label="Particella";
        $catasto->AddTextField("ParticellaCatasto",$label,array("tooltip"=>"*Inserire il numero della particella in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //subalterno
        $label="Subalterno";
        $catasto->AddTextField("Subalterno",$label,array("required"=>true,"tooltip"=>"*Inserire il numero del sublaternose presente.", "placeholder"=>"..."),false);

        $wnd->AddGenericObject($catasto);

       //ente gestore
        $dlgEntiParams = array("task" => "GetSicarSearchEntiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"immobile_gestione_ente","field_desc"=>"immobile_gestione_ente_desc"));
        $ente=new AA_FieldSet("AA_SICAR_ENTE_GESTORE".uniqid(),"Ente gestore",$wnd->GetFormId(),3);
        $ente->AddSearchField("dlg",$dlgEntiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Denominazione","name"=>"immobile_gestione_ente_desc", "bottomLabel" => "*Cerca un ente gia' esistente o aggiungine uno se non e' presente."]);
        $ente->AddDateField('immobile_gestione_dal','Dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>80),false);
        //$wnd->AddGenericObject($ente);

        //Attributi
        $attributi = new AA_FieldSet("AA_SICAR_ATTRIBUTI".uniqid(),"Caratteristiche",$wnd->GetFormId(),1);

        //piani
        $attributi->AddTextField("piani", "Piani", ["required" => true,"gravity"=>1,"validateFunction"=>"IsPositive", "bottomLabel" => "*Numero di piani dell'immobile"]);
        
        $label="Alloggi tot.";
        $attributi->AddTextField("attributi_alloggi",$label,array("gravity"=>1,"bottomLabel"=>"*Inserire il numero totale di alloggi (anche solo previsti).", "required"=>true,"placeholder"=>"..."),false);
        
        
        //condominio misto 
        //$attributi->AddCheckBoxField("attributi_condominio_misto", " ", ["required" => false,"labelWidth"=>10,"width"=>180,"labelRight"=>"Condominio misto","bottomPadding"=>36, "bottomLabel" => ""],false);
        $wnd->AddGenericObject($attributi);

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false,"labelWidth"=>60, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("UpdateImmobileSicar");

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Task per la restituzione della finestra di dialogo di eliminazione immobile
    public function Task_GetSicarDeleteImmobileDlg($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per eliminare l'immobile");
            return false;
        }

        $immobile=new AA_SicarImmobile();
        if (!$immobile->Load($_REQUEST['id'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Immobile non trovato (id: ".$_REQUEST['id'].")");
            return false;
        }

        $alloggi=$immobile->GetAlloggi(false,$this->oUser);
        if($alloggi===false || sizeof($alloggi)>0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'immobile contiene uno o piu' alloggi, è possibile eliminare esclusivamene immobili senza alloggi.");
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarDeleteImmobileDlg($immobile), true);
        return true;
    }

    //Template dlg delete immobile
    public function Template_GetSicarDeleteImmobileDlg($object=null)
    {
        $id=uniqid();
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina immobile", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(380);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("descrizione"=>$object->GetDisplayName());

        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p></div>";
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente immobile verrà eliminato definitivamente, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"descrizione", "header"=>"Descrizione", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteImmobileSicar");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetProp("id")));
        
        return $wnd;
    }

    //Template dlg delete nucleo
    public function Template_GetSicarDeleteNucleoDlg($object=null)
    {
        $id=uniqid();
        
        $form = "";
        if(!empty($_REQUEST['form']))
        {
            $form = $_REQUEST['form'];
        }

        $field_id="";
        if(!empty($_REQUEST['field_id']))
        {
            $field_id = $_REQUEST['field_id'];
        }

        $field_desc="";
        if(!empty($_REQUEST['field_desc']))
        {
            $field_desc = $_REQUEST['field_desc'];
        }

        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina nucleo", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(380);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("descrizione"=>$object->GetDescrizione(),"cf"=>$object->GetProp("cf"));

        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p></div>";
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente nucleo verrà eliminato definitivamente, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"descrizione", "header"=>"Descrizione", "fillspace"=>true),
              array("id"=>"cf", "header"=>"Codice Fiscale", "width"=>160)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteNucleoSicar");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetProp("id")));
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("id"=>$object->GetProp("id"),"form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
        return $wnd;
    }

    //Template dlg delete nucleo
    public function Template_GetSicarDeleteStatoOccupazioneAlloggioDlg($object=null,$dal="")
    {
        $id=uniqid();
        
        $form = "";
        if(!empty($_REQUEST['form']))
        {
            $form = $_REQUEST['form'];
        }

        $field_id="";
        if(!empty($_REQUEST['field_id']))
        {
            $field_id = $_REQUEST['field_id'];
        }

        $field_desc="";
        if(!empty($_REQUEST['field_desc']))
        {
            $field_desc = $_REQUEST['field_desc'];
        }

        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina stato assegnazione occupazione", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(380);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");

        $nucleo=$object->GetNucleoAssegnatario($dal);
        AA_Log::Log(__METHOD__." - Nucleo assegnatario dello stato di occupazione con data dal ".$dal.": ".print_r($nucleo,true),100);

        if($nucleo)
        {
            $nucleo_desc=$nucleo->GetProp("descrizione")." (".$nucleo->GetProp("cf").")";
        }
        else $nucleo_desc="n.d.";

        $tabledata=array();
        $tabledata[]=array("data_stato_occupazione"=>$dal,"nucleo_assegnatario"=>$nucleo_desc);

        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p></div>";
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente stato di occupazione verrà eliminato definitivamente, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"data_stato_occupazione", "header"=>"Data dal", "width"=>120),
              array("id"=>"nucleo_assegnatario", "header"=>"Codice Fiscale", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteStatoOccupazioneAlloggioSicar");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"dal"=>$dal));
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"dal"=>$dal,"form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
        return $wnd;
    }

    //Template dlg delete intervento
    public function Template_GetSicarDeleteStatoInterventiAlloggioDlg($object=null,$id_intervento=null)
    {
        $id=uniqid();
        
        $form = "";
        if(!empty($_REQUEST['form']))
        {
            $form = $_REQUEST['form'];
        }

        $field_id="";
        if(!empty($_REQUEST['field_id']))
        {
            $field_id = $_REQUEST['field_id'];
        }

        $field_desc="";
        if(!empty($_REQUEST['field_desc']))
        {
            $field_desc = $_REQUEST['field_desc'];
        }

        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina intervento", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(380);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");

        $interventi=$object->GetInterventi();
        $intervento=$interventi[$id_intervento];
        $tipologiaIntervento=AA_Sicar_Const::GetListaTipologieIntervento(true);

        $tabledata=array();
        $tabledata[]=array("id_intervento"=>$id_intervento,"dal"=>$intervento['data_dal'],"tipologia"=>$tipologiaIntervento[$intervento['tipologia']],"cup"=>$intervento['cup']);

        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p></div>";
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente intervento verrà eliminato definitivamente, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"dal", "header"=>"Data dal", "width"=>120),
              array("id"=>"tipologia", "header"=>"Tipologia", "fillspace"=>true),
              array("id"=>"cup", "header"=>"CUP", "width"=>120)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteStatoInterventiAlloggioSicar");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_intervento"=>$id_intervento));
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_intervento"=>$id_intervento,"form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
        return $wnd;
    }

    //Template dlg delete intervento
    public function Template_GetSicarDeleteInterventoImmobileDlg($object=null,$id_intervento=null)
    {
        $id=uniqid();
        
        $form = "";
        if(!empty($_REQUEST['form']))
        {
            $form = $_REQUEST['form'];
        }

        $field_id="";
        if(!empty($_REQUEST['field_id']))
        {
            $field_id = $_REQUEST['field_id'];
        }

        $field_desc="";
        if(!empty($_REQUEST['field_desc']))
        {
            $field_desc = $_REQUEST['field_desc'];
        }

        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina intervento", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(380);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");

        $interventi=$object->GetInterventi();
        $intervento=$interventi[$id_intervento];
        $tipologiaIntervento=AA_Sicar_Const::GetListaTipologieIntervento(true);

        $tabledata=array();
        $tabledata[]=array("id_intervento"=>$id_intervento,"dal"=>$intervento['data_dal'],"tipologia"=>$tipologiaIntervento[$intervento['tipologia']],"cup"=>$intervento['cup']);

        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p></div>";
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente intervento verrà eliminato definitivamente, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"dal", "header"=>"Data dal", "width"=>120),
              array("id"=>"tipologia", "header"=>"Tipologia", "fillspace"=>true),
              array("id"=>"cup", "header"=>"CUP", "width"=>120)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteInterventoImmobileSicar");
        $wnd->SetSaveTaskParams(array("id_immobile"=>$object->GetProp("id"),"id_intervento"=>$id_intervento));
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("id_immobile"=>$object->GetProp("id"),"id_intervento"=>$id_intervento,"form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
        return $wnd;
    }

    // Task per la restituzione della finestra di dialogo di eliminazione immobile
    public function Task_GetSicarDeleteNucleoDlg($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per eliminare il nucleo");
            return false;
        }

        $nucleo=new AA_SicarNucleo();
        if (!$nucleo->Load($_REQUEST['id'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nucleo non trovato (id: ".$_REQUEST['id'].")");
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarDeleteNucleoDlg($nucleo), true);
        return true;
    }

    //restituisce la lista dei comuni
    public function Task_GetSicarListaCodiciIstat($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        $filter=$_REQUEST["filter"];

        $db=new AA_Database();
        $query="SELECT codice,comune FROM ".AA_Sicar_Const::AA_DBTABLE_CODICI_ISTAT;
        if($filter !="") $query.=" WHERE codice like '".addslashes($filter['value'])."%' OR comune like '".addslashes($filter['value'])."%'";
        //$query.=" LIMIT 10";

        //AA_Log::Log(__METHOD__." - query ".$query.print_r($_REQUEST,true),100);
        
        //errore nella query
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - ERRORE ".$db->GetErrorMessage(),100);
            die("[]");
        }

        //Query vuota
        if($db->GetAffectedRows() == 0)
        {
            die("[]");
        }
        
        $result=array();
        $count=1;
        foreach($db->GetResultSet() as $curRow)
        {
            $result[]=array("id"=>$count,"codice"=>$curRow['codice'],"value"=>$curRow['comune']." (".$curRow['codice'].")");
            $count++;
        }

        die(json_encode($result));
    }

    // Task per la restituzione della finestra di dialogo di aggiunta nuovo alloggio
    public function Task_GetSicarAddNewDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi alloggi", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarAddNewAlloggioDlg(), true);
        return true;
    }

    // Task per la restituzione della finestra di dialogo di filtro bozze
    public function Task_GetSicarBozzeFilterDlg($task)
    {
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->TemplateBozzeFilterDlg($_REQUEST), true);
        return true;
    }

    // Task per la restituzione della finestra di dialogo di filtro pubblicate
    public function Task_GetSicarPubblicateFilterDlg($task)
    {
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->TemplatePubblicateFilterDlg($_REQUEST), true);
        return true;
    }

    //Task search immobili
    public function Task_GetSicarSearchImmobiliDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per visualizzare gli immobili", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarSearchImmobiliDlg(),true);
        return true;
    }

    //Task list interventi
    public function Task_GetSicarInterventiImmobileDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per visualizzare gli immobili", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarInterventiImmobileDlg(),true);
        return true;
    }

    //Task search Enti
    public function Task_GetSicarSearchEntiDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per visualizzare gli enti", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarSearchEntiDlg(),true);
        return true;
    }

    //Task search Nuclei
    public function Task_GetSicarSearchNucleiDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per visualizzare i nuclei", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarSearchNucleiDlg(),true);
        return true;
    }

    //Task operatori ente
    public function Task_GetSicarOperatoriEnteDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per visualizzare gli operatori dell'ente", false);
            return false;
        }

        $ente=new AA_SicarEnte();
        if(!$ente->Load($_REQUEST['id_ente']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Ente non trovato (id: ".$_REQUEST['id_ente'].")", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarOperatoriEnteDlg($ente),true);
        return true;
    }

    //Template dlg search immobili
    public function Template_GetSicarSearchImmobiliDlg()
    {
        $id=static::AA_UI_WND_SEARCH_IMMOBILI;
        
        $wnd=new AA_GenericWindowTemplate($id, "Ricerca immobili", $this->id);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(640);
        
        $wnd->AddView($this->Template_DatatableSearchImmobili($id));
        
        return $wnd;
    }

    //Template dlg lista interventi immobili
    public function Template_GetSicarInterventiImmobileDlg()
    {
        $id=static::AA_UI_WND_INTERVENTI_IMMOBILE;
        
        $wnd=new AA_GenericWindowTemplate($id, "Interventi su parti comuni", $this->id);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(640);
        
        $wnd->AddView($this->Template_DatatableInterventiImmobile($id));
        
        return $wnd;
    }

    //Template dlg search enti
    public function Template_GetSicarSearchEntiDlg()
    {
        $id=static::AA_UI_WND_SEARCH_ENTI;
        
        $wnd=new AA_GenericWindowTemplate($id, "Ricerca Enti", $this->id);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(640);
        
        $wnd->AddView($this->Template_DatatableSearchEnti($id));
        
        return $wnd;
    }

    //Template dlg search nuclei
    public function Template_GetSicarSearchNucleiDlg()
    {
        $id=static::AA_UI_WND_SEARCH_NUCLEI;
        
        $wnd=new AA_GenericWindowTemplate($id, "Ricerca nuclei", $this->id);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(640);
        
        $wnd->AddView($this->Template_DatatableSearchNuclei($id));
        
        return $wnd;
    }

    //Template dlg operatori ente
    public function Template_GetSicarOperatoriEnteDlg($ente=null)
    {
        $id=static::AA_UI_WND_OPERATORI_ENTE;
        
        $wnd=new AA_GenericWindowTemplate($id, "Operatori ente", $this->id);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(640);
        
        $wnd->AddView($this->Template_DatatableOperatoriEnte($id,$ente));
        
        return $wnd;
    }

    //Template data table Operatori ente
    public function Template_DatatableOperatoriEnte($id="",$ente=null)
    {
        if($id=="") $id=static::AA_UI_WND_OPERATORI_ENTE;
        $id.="_".static::AA_UI_TABLE_OPERATORI_ENTE;

        if(!($ente instanceof AA_SicarEnte))
        {
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
            $layout->addRow(new AA_JSON_Template_Template("",array("template"=>"Ente non trovato.")));
            return $layout;
        }
        
        $canModify=false;
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //form di destinazione
        if(!empty($_REQUEST['form'])) $form=trim($_REQUEST['form']);

        //campo di destinazione
        if(!empty($_REQUEST['field_id'])) $field_id=trim($_REQUEST['field_id']);
        if(!empty($_REQUEST['field_desc'])) $field_desc=trim($_REQUEST['field_desc']);

        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        $filter="";

        if($filter=="") $filter="<span class='AA_Label AA_Label_LightOrange'>tutti</span>";
        
        //$toolbar->addElement(new AA_JSON_Template_Generic($id."_FilterLabel",array("view"=>"label","align"=>"left","label"=>"<div>Visualizza: ".$filter."</div>")));
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //Aggiunta
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) $filter=array("id_ente"=>$ente->GetProp('id'),"refresh"=>1,"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc);
        else $filter=array("refresh"=>1,"refresh_obj_id"=>$id,"id_ente"=>$ente->GetProp('id'));
        $modify_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-filter-cog",
             "label"=>"Aggiungi",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Aggiungi un nuovo operatore",
             "click"=>"AA_MainApp.curModule.setRuntimeValue('" . $id . "','filter_data',".json_encode($filter)."); AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewOperatoreEnteDlg\",postParams: AA_MainApp.curModule.getRuntimeValue('" . $id . "','filter_data'), module: '" . $this->id . "'},'".$this->id."')"
        ));
        $toolbar->AddElement($modify_btn);
        
        $layout->addRow($toolbar);

        #criteri----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        $operatori=$ente->GetOperatori();

        $data=[];
        //$trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopTrashComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        //$modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopModifyComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) 
        {
            $select_icon="mdi mdi-cursor-pointer";
        }
    
        foreach($operatori as $curOperatore)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if(!empty($form) && !empty($field_id) && !empty($field_desc))
            {
                $select="try{if($$('".$form."')){ AA_MainApp.utils.callHandler('SicarSelectOperatoreEnte', {form:'".$form."', values:{'".$field_id."':'".$curOperatore['cf']."','".$field_desc."':'".$curOperatore['nome']." ".$curOperatore['cognome']."'}},'".$this->GetId()."'); $$('".static::AA_UI_WND_ENTE_OPERATORI."_Wnd').close();}}catch(msg){console.error(msg)}";
                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Scegli' onClick=\"".$select."\"><span class='mdi ".$select_icon."'></span></a></div>";
                
                $data[]=array("id"=>$curOperatore['cf'],"nome"=>$curOperatore['nome']." ".$curOperatore['cognome'],"cf"=>$curOperatore['cf'],"email"=>$curOperatore['email'],"ops"=>$ops);
            }
            else
            {
                $ops="";
                if($canModify)
                {
                    $trash="AA_MainApp.utils.callHandler('dlg', {task:'GetSicarDeleteOperatoreEnteDlg', postParams: {id_ente:'".$ente->GetProp('id')."', cf:'".$curOperatore['cf']."'}, module: '".$this->id."'},'".$this->id."')";
                    $modify="AA_MainApp.utils.callHandler('dlg', {task:'GetSicarModifyOperatoreEnteDlg', postParams: {id_ente:'".$ente->GetProp('id')."', cf:'".$curOperatore['cf']."'}, module: '".$this->id."'},'".$this->id."')";
                    $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Modifica' onClick=\"".$modify."\"><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button' title='Elimina' onClick=\"".$trash."\"><span class='mdi mdi-trash-can'></span></a></div>";
                }
                $data[]=array("id"=>$curOperatore['cf'],"nome"=>$curOperatore['nome']." ".$curOperatore['cognome'],"cf"=>$curOperatore['cf'],"email"=>$curOperatore['email'],"ops"=>$ops);
            }
        }

        if(!$canModify) $template=new AA_GenericDatatableTemplate($id."_TableEnteOperatori_".uniqid(),"",3,null,array("css"=>"AA_Header_DataTable"));
        else $template=new AA_GenericDatatableTemplate($id."_TableEnteOperatori_".uniqid(),"",4,null,array("css"=>"AA_Header_DataTable"));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(false);
        $template->SetHeaderHeight(38);

        /*
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewImmobileDlg");
            if(!empty($form) && !empty($field_id) && !empty($field_desc)) $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1,"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc)));
            else $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1,"refresh_obj_id"=>$id)));
        }*/

        $template->SetColumnHeaderInfo(0,"nome","<div style='text-align: center'>Nome e cognome</div>",250,"textFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(1,"cf","<div style='text-align: center'>Cf</div>",250,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(2,"email","<div style='text-align: center'>Email</div>",250,"textFilter","text","GenericAutosizedRowTable");
       
        if($canModify) $template->SetColumnHeaderInfo(3,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"GenericAutosizedRowTable");

        $template->SetData($data);

        $layout->AddRow($template);
        return $layout;
    }

    //Template dlg Detail immobile
    public function Template_GetSicarDetailImmobileDlg($immobile=null)
    {
        $id=static::AA_UI_WND_DETAIL_IMMOBILI;
        
        $wnd=new AA_GenericWindowTemplate($id, "Dettaglio immobile", $this->id);

        $wnd->SetWidth(800);
        $wnd->SetHeight(600);
        
        if(!($immobile instanceof AA_SicarImmobile))
        {
            $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Immobile non trovato")));
            return $wnd;
        }

        $wnd->AddView($immobile->GetTemplateView());
        
        return $wnd;
    }

    //Template dlg Detail occupazione alloggio
    public function Template_GetSicarDetailStatoOccupazioneAlloggioDlg($stato=null)
    {
        $id=static::AA_UI_WND_DETAIL_STATO_OCCUPAZIONE_ALLOGGIO;
        
        $wnd=new AA_GenericWindowTemplate($id, "Dettaglio stato occupazione alloggio", $this->id);
        $wnd->SetWidth(500);
        $wnd->SetHeight(400);
        
        if(!is_array($stato))
        {
            $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Stato occupazione alloggio non valido")));
            return $wnd;
        }

        $oTemplateView=new AA_GenericTemplate_Grid();

        if($stato['stato'] > 0)
        {
            $tipo_occupazione=AA_Sicar_Const::GetListaTipologieOccupazione(true);
            $tipo_canone=AA_Sicar_Const::GetListaTipologieCanoneAlloggio(true);

            $nucleo=new AA_SicarNucleo();
            if($nucleo->Load($stato['occupazione_id_nucleo']))
            {
                $nucleo_desc=$nucleo->GetDescrizione();
            }
            else $nucleo_desc="n.d.";

            //template view props
            $aTemplateViewProps['stato']=array("label"=>"Stato occupazione","value"=>$tipo_occupazione[$stato['stato']],"visible"=>true);
            $aTemplateViewProps['nucleo']=array("label"=>"Nucleo","value"=>$nucleo_desc,"visible"=>true);
            $aTemplateViewProps['canone']=array("label"=>"Canone","value"=>$tipo_canone[$stato['occupazione_tipo_canone']],"visible"=>true);
            $aTemplateViewProps['data_assegnazione']=array("label"=>"Data assegnazione","value"=>$stato['occupazione_data_assegnazione'],"visible"=>true);
            $aTemplateViewProps['note']=array("label"=>"note","value"=>$stato['note'],"visible"=>true);
        
            $aTemplateViewProps['__areas']=array(
                array("stato", "data_assegnazione"),
                array("nucleo","canone"),
                array("note", "note")
            );
            $aTemplateViewProps['__cols']=array("2fr","1fr");
            $aTemplateViewProps['__rows']=array("1fr","1fr","2fr");
        }
        else
        {
            $aTemplateViewProps['stato']=array("label"=>"Descrizione","value"=>"libero","visible"=>true);
            $aTemplateViewProps['note']=array("label"=>"Note","value"=>$stato['note'],"visible"=>true);

            $aTemplateViewProps['__areas']=array(
                array("stato","stato"),
                array("note", "note")
            );
            $aTemplateViewProps['__cols']=array("1fr","1fr");
            $aTemplateViewProps['__rows']=array("1fr","3fr");
        }

        foreach($aTemplateViewProps as $propName=>$propConfig)
        {
            if($propConfig['visible'])
            {
                $class='';
                if(!empty($propConfig['class'])) $class=$propConfig['class'];
                else $class='aa-templateview-prop-'.$propName;

                $value="";
                if(empty($propConfig['function'])) $value = "<span class='".$class."'>" . $propConfig['value']. "</span>";
                else 
                {
                    if(method_exists($this,$propConfig['function'])) $value = "<div class='".$class."'>".$this->{$propConfig['function']}()."</div>";
                    else $value = "<span class='".$class."'>n.d.</span>";
                }

                if(!$oTemplateView->AddCellToGrid(new AA_JSON_Template_Template("", array(
                    "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
                    "data" => array("title" => "".$propConfig['label'].":", "value" => $value),
                    "css" => array("border-bottom" => "1px solid #dadee0 !important","width"=>"auto !important","height"=> "auto !important")
                )), $propName))
                {
                    AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile aggiungere la cella alla template view per la proprietà: " . $propName, 100);
                }
            }
        }

        $oTemplateView->SetTemplateAreas($aTemplateViewProps['__areas']);
        $oTemplateView->SetTemplateCols($aTemplateViewProps['__cols']);
        $oTemplateView->SetTemplateRows($aTemplateViewProps['__rows']);

        $wnd->AddView($oTemplateView);
        
        return $wnd;
    }

    //Template data table SearchImmobili
    public function Template_DatatableSearchImmobili($id="")
    {
        if($id=="") $id=static::AA_UI_WND_SEARCH_IMMOBILI;
        $id.="_".static::AA_UI_TABLE_SEARCH_IMMOBILI;
        
        //form di destinazione
        if(!empty($_REQUEST['form'])) $form=trim($_REQUEST['form']);

        //campo di destinazione
        if(!empty($_REQUEST['field_id'])) $field_id=trim($_REQUEST['field_id']);
        if(!empty($_REQUEST['field_desc'])) $field_desc=trim($_REQUEST['field_desc']);

        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        $filter="";

        if($filter=="") $filter="<span class='AA_Label AA_Label_LightOrange'>tutti</span>";
        
        //$toolbar->addElement(new AA_JSON_Template_Generic($id."_FilterLabel",array("view"=>"label","align"=>"left","label"=>"<div>Visualizza: ".$filter."</div>")));
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //Aggiunta
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) $filter=array("refresh"=>1,"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc);
        else $filter=array("refresh"=>1,"refresh_obj_id"=>$id);
        $modify_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-filter-cog",
             "label"=>"Aggiungi",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Aggiungi un nuovo immobile",
             "click"=>"AA_MainApp.curModule.setRuntimeValue('" . $id . "','filter_data',".json_encode($filter)."); AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewImmobileDlg\",postParams: AA_MainApp.curModule.getRuntimeValue('" . $id . "','filter_data'), module: '" . $this->id . "'},'".$this->id."')"
        ));
        $toolbar->AddElement($modify_btn);
        
        $layout->addRow($toolbar);

        #criteri----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        $immobili=AA_SicarImmobile::Search();
        $data=[];
        //$trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopTrashComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        //$modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopModifyComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) 
        {
            $select_icon="mdi mdi-cursor-pointer";
        }
        else
        {
            $ops="";
        }

        foreach($immobili as $curImmobile)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if(!empty($form) && !empty($field_id) && !empty($field_desc))
            {
                $select="try{if($$('".$form."')){ AA_MainApp.utils.callHandler('SicarSelectImmobile', {form:'".$form."', values:{'".$field_id."':'".$curImmobile->GetProp('id')."','".$field_desc."':'".$curImmobile->GetDisplayName()."'}},'".$this->GetId()."'); $$('".static::AA_UI_WND_SEARCH_IMMOBILI."_Wnd').close();}}catch(msg){console.error(msg)}";
                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Scegli' onClick=\"".$select."\"><span class='mdi ".$select_icon."'></span></a></div>";
                $data[]=array("id"=>$curImmobile->GetProp("id"),"descrizione"=>$curImmobile->GetDescrizione(),"indirizzo"=>$curImmobile->GetIndirizzo(),"comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curImmobile->GetProp("comune")),"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curImmobile->GetProp("id"),"descrizione"=>$curImmobile->GetDescrizione(),"indirizzo"=>$curImmobile->GetIndirizzo(),"comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curImmobile->GetProp("comune")));
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id."_TableSearch_".uniqid(),"Ricerca immobili",3,null,array("css"=>"AA_Header_DataTable"));
        else $template=new AA_GenericDatatableTemplate($id."_TableSearch_".uniqid(),"Ricerca immobili",4,null,array("css"=>"AA_Header_DataTable"));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(false);
        $template->SetHeaderHeight(38);

        /*
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewImmobileDlg");
            if(!empty($form) && !empty($field_id) && !empty($field_desc)) $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1,"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc)));
            else $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1,"refresh_obj_id"=>$id)));
        }*/

        $template->SetColumnHeaderInfo(0,"descrizione","<div style='text-align: center'>Descrizione</div>",250,"textFilter","int","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo</div>","fillspace","textFilter","text","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(2,"comune","<div style='text-align: center'>Comune</div>",250,"textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(3,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"ImmobiliTable");

        $template->SetData($data);

        $layout->AddRow($template);
        return $layout;
    }

    //Template data table Interventi partti comune
    public function Template_DatatableInterventiImmobile($id="",$object=null,$canModify=false)
    {
        if($id=="") $id=static::AA_UI_WND_INTERVENTI_IMMOBILE;
        $id.="_".static::AA_UI_TABLE_INTERVENTI_IMMOBILE;
        
        if(empty($_REQUEST['id_immobile']) && $object==null)
        {
            
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
            $layout->addRow(new AA_JSON_Template_Template($id,array("template"=>"Immobile non specificato.")));
            return $layout;
        }

        //immobile di riferimento
        if(!empty($_REQUEST['id_immobile']) && $object==null)
        {
            $id_immobile=trim($_REQUEST['id_immobile']);
            $object=new AA_SicarImmobile();
            if(!$object->Load($id_immobile))
            {
                $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
                $layout->addRow(new AA_JSON_Template_Template($id,array("template"=>"Immobile non trovato.")));
                return $layout;
            }
        }

        // Verifica che l'utente abbia i permessi per gestire gli interventi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
               $canModify=false;
        }
        else $canModify=true;

        //form di destinazione
        if(!empty($_REQUEST['form'])) $form=trim($_REQUEST['form']);

        //campo di destinazione
        if(!empty($_REQUEST['field_id'])) $field_id=trim($_REQUEST['field_id']);
        if(!empty($_REQUEST['field_desc'])) $field_desc=trim($_REQUEST['field_desc']);

        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "pippo"=>"pippo","filtered"=>true,"filter_id"=>$id));
        
        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        $filter="";

        //if($filter=="") $filter="<span class='AA_Label AA_Label_LightOrange'>tutti</span>";
        
        //$toolbar->addElement(new AA_JSON_Template_Generic($id."_FilterLabel",array("view"=>"label","align"=>"left","label"=>"<div>Visualizza: ".$filter."</div>")));
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //Aggiunta
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) $filter=array("refresh"=>1,"id_immobile"=>$object->GetProp("id"),"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc);
        else $filter=array("refresh"=>1,"refresh_obj_id"=>$id,"id_immobile"=>$object->GetProp("id"));
        
        if($canModify)
        {
             $modify_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-filter-cog",
             "label"=>"Aggiungi",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Aggiungi un nuovo immobile",
             "click"=>"AA_MainApp.curModule.setRuntimeValue('" . $id . "','filter_data',".json_encode($filter)."); AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewInterventoImmobileDlg\",postParams: AA_MainApp.curModule.getRuntimeValue('" . $id . "','filter_data'), module: '" . $this->id . "'},'".$this->id."')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        else
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        }
       
        $layout->addRow($toolbar);

        $interventi=$object->GetInterventi();
        $interventi_data=[];
        $tipologia_intervento_desc=AA_Sicar_Const::GetListaTipologieIntervento(true);
        $stato_lavori_desc=AA_Sicar_Const::GetListaStatoLavori(true);

        foreach($interventi as $id_intervento=>$curIntervento)
        {
            $finanziamento_desc="n.d.";
            if($curIntervento['id_finanziamento'] > 0)    
            {
                $finanziamento=new AA_SicarFinanziamento();
                if($finanziamento->Load($curIntervento['id_finanziamento']))
                {
                    $finanziamento_desc=$finanziamento->GetDenominazione();
                }
            }

            $richiesta_finanziamento_desc="n.d.";
            if($curIntervento['id_richiesta_finanziamento'] > 0)    
            {
                $richiesta_finanziamento=new AA_SicarRichiestaFinanziamento();
                if($richiesta_finanziamento->Load($curIntervento['id_richiesta_finanziamento']))
                {
                    $richiesta_finanziamento_desc=$richiesta_finanziamento->GetDenominazione();
                }
            }

            $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailInterventoImmobileDlg", params: [{id_immobile:"'.$object->GetProp("id").'"},{id_intervento:"'.$id_intervento.'"},{refresh:1},{refresh_section:1},{refresh_obj_id:"'.$id.'"}]},"'.$this->id.'")';
            $detail_icon="mdi mdi-eye";
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteInterventoImmobileDlg", params: [{id_immobile:"'.$object->GetProp("id").'"},{id_intervento:"'.$id_intervento.'"},{refresh:1},{refresh_section:1},{refresh_obj_id:"'.$id.'"}]},"'.$this->id.'")';
            $trash_icon="mdi mdi-trash-can";
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyInterventoImmobileDlg", params: [{id_immobile:"'.$object->GetProp("id").'"},{id_intervento:"'.$id_intervento.'"},{refresh:1},{refresh_section:1},{refresh_obj_id:"'.$id.'"}]},"'.$this->id.'")';
            $modify_icon="mdi mdi-pencil";
            $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
            $tipologia_intervento="Nessun intervento richiesto";
            $stato_lavori="n.d.";
            if(!empty($curIntervento['tipologia'])) 
            {
                $tipologia_intervento=$tipologia_intervento_desc[$curIntervento['tipologia']];
                $stato_lavori=$stato_lavori_desc[$curIntervento['stato_lavori']];
            }
        
            $intervento_data[]=array(
                "id"=>$id_intervento,
                "data_dal"=>$curIntervento['data_dal'],
                "data_al"=>$curIntervento['data_al'],
                "tipologia_desc"=>$tipologia_intervento,
                "stato_lavori"=>$stato_lavori,
                "importo_stimato"=>AA_Utils::number_format($curIntervento['importo_stimato'],2,",","."),
                "cup"=>$curIntervento['cup'],
                "ops"=>$ops
            );
        }

        $id_table=$id."_TableInterventi_".uniqid();
        if(!$canModify) $template=new AA_GenericDatatableTemplate($id_table,6,array("type"=>"clean"),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id_table));
        else $template=new AA_GenericDatatableTemplate($id_table,"",7,array("type"=>"clean"),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id_table));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(false);
        $template->SetHeaderHeight(38);
        $template->EnableAddNew(false);
        $template->SetColumnHeaderInfo(0,"tipologia_desc","<div style='text-align: center'>Tipologia</div>",230,"selectFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(1,"data_dal","<div style='text-align: center'>Dal</div>",100,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(2,"data_al","<div style='text-align: center'>Al</div>",100,"textFilter","int","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(3,"importo_stimato","<div style='text-align: center'>Costo stimato</div>",120,"textFilter","int","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(4,"stato_lavori","<div style='text-align: center'>Stato lavori</div>",130,"selectFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(5,"cup","<div style='text-align: center'>CUP</div>","fillspace","textFilter","text","GenericAutosizedRowTable");
        //$template->SetColumnHeaderInfo(6,"note","<div style='text-align: center'>Note</div>","fillspace","textFilter","text","GenericAutosizedRowTable_left");
        //$template->SetColumnHeaderInfo(4,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if($canModify) $template->SetColumnHeaderInfo(6,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"GenericAutosizedRowTable");

        $template->SetData($intervento_data);
        #--------------------------------------
        
        $layout->AddRow($template);
        return $layout;
    }

    //Template data table SearchEnti
    public function Template_DatatableSearchEnti($id="")
    {
        if($id=="") $id=static::AA_UI_WND_SEARCH_ENTI;
        $id.="_".static::AA_UI_TABLE_SEARCH_ENTI;
        
        //form di destinazione
        if(!empty($_REQUEST['form'])) $form=trim($_REQUEST['form']);

        //campo di destinazione
        if(!empty($_REQUEST['field_id'])) $field_id=trim($_REQUEST['field_id']);
        if(!empty($_REQUEST['field_desc'])) $field_desc=trim($_REQUEST['field_desc']);

        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        $filter="";

        if($filter=="") $filter="<span class='AA_Label AA_Label_LightOrange'>tutti</span>";
        
        //$toolbar->addElement(new AA_JSON_Template_Generic($id."_FilterLabel",array("view"=>"label","align"=>"left","label"=>"<div>Visualizza: ".$filter."</div>")));
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //Aggiunta
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) $filter=array("refresh"=>1,"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc);
        else $filter=array("refresh"=>1,"refresh_obj_id"=>$id);
        $modify_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-filter-cog",
             "label"=>"Aggiungi",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Aggiungi un nuovo Ente",
             "click"=>"AA_MainApp.curModule.setRuntimeValue('" . $id . "','filter_data',".json_encode($filter)."); AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewEnteDlg\",postParams: AA_MainApp.curModule.getRuntimeValue('" . $id . "','filter_data'), module: '" . $this->id . "'},'".$this->id."')"
        ));
        $toolbar->AddElement($modify_btn);
        
        $layout->addRow($toolbar);

        #criteri----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        $immobili=AA_SicarEnte::Search();
        $data=[];
        //$trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopTrashComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        //$modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopModifyComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) 
        {
            $select_icon="mdi mdi-cursor-pointer";
        }
        else
        {
            $ops="";
        }

        foreach($immobili as $curImmobile)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if(!empty($form) && !empty($field_id) && !empty($field_desc))
            {
                $select="try{if($$('".$form."')){ AA_MainApp.utils.callHandler('SicarSelectFormItem', {form:'".$form."', values:{'".$field_id."':'".$curImmobile->GetProp('id')."','".$field_desc."':'".$curImmobile->GetDisplayName()."'}},'".$this->GetId()."'); $$('".static::AA_UI_WND_SEARCH_ENTI."_Wnd').close();}}catch(msg){console.error(msg)}";
                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Scegli' onClick=\"".$select."\"><span class='mdi ".$select_icon."'></span></a></div>";
                $data[]=array("id"=>$curImmobile->GetProp("id"),"denominazione"=>$curImmobile->GetDenominazione(),"indirizzo"=>$curImmobile->GetIndirizzo(),"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curImmobile->GetProp("id"),"denominazione"=>$curImmobile->GetDenominazione(),"indirizzo"=>$curImmobile->GetIndirizzo());
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id."_TableSearch_".uniqid(),"Ricerca enti",2,null,array("css"=>"AA_Header_DataTable"));
        else $template=new AA_GenericDatatableTemplate($id."_TableSearch_".uniqid(),"Ricerca enti",3,null,array("css"=>"AA_Header_DataTable"));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(false);
        $template->SetHeaderHeight(38);

        /*
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewImmobileDlg");
            if(!empty($form) && !empty($field_id) && !empty($field_desc)) $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1,"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc)));
            else $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1,"refresh_obj_id"=>$id)));
        }*/

        $template->SetColumnHeaderInfo(0,"denominazione","<div style='text-align: center'>Denominazione</div>",250,"textFilter","int","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo</div>","fillspace","textFilter","text","ImmobiliTable_left");
        //$template->SetColumnHeaderInfo(2,"comune","<div style='text-align: center'>Comune</div>",250,"textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(2,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"ImmobiliTable");

        $template->SetData($data);

        $layout->AddRow($template);
        return $layout;
    }

    //Template data table SearchNuclei
    public function Template_DatatableSearchNuclei($id="")
    {
        if($id=="") $id=static::AA_UI_WND_SEARCH_NUCLEI;
        $id.="_".static::AA_UI_TABLE_SEARCH_NUCLEI;
        
        //form di destinazione
        if(!empty($_REQUEST['form'])) $form=trim($_REQUEST['form']);

        //campo di destinazione
        if(!empty($_REQUEST['field_id'])) $field_id=trim($_REQUEST['field_id']);
        if(!empty($_REQUEST['field_desc'])) $field_desc=trim($_REQUEST['field_desc']);

        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        $filter="";

        if($filter=="") $filter="<span class='AA_Label AA_Label_LightOrange'>tutti</span>";
        
        //$toolbar->addElement(new AA_JSON_Template_Generic($id."_FilterLabel",array("view"=>"label","align"=>"left","label"=>"<div>Visualizza: ".$filter."</div>")));
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //Aggiunta
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) $filter=array("refresh"=>1,"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc);
        else $filter=array("refresh"=>1,"refresh_obj_id"=>$id);
        $modify_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-filter-cog",
             "label"=>"Aggiungi",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Aggiungi un nuovo Nucleo",
             "click"=>"AA_MainApp.curModule.setRuntimeValue('" . $id . "','filter_data',".json_encode($filter)."); AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewNucleoDlg\",postParams: AA_MainApp.curModule.getRuntimeValue('" . $id . "','filter_data'), module: '" . $this->id . "'},'".$this->id."')"
        ));
        $toolbar->AddElement($modify_btn);
        
        $layout->addRow($toolbar);

        #criteri----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //Solo nuclei senza alloggio attuale
        $params=array();
        $params['WHERE']=array(
            array("FIELD"=>"alloggio_attuale","OPERATOR"=>"=","VALUE"=>0)
        );

        $nuclei=AA_SicarNucleo::Search();
        $data=[];
        //$trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopTrashComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        //$modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopModifyComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) 
        {
            $select_icon="mdi mdi-cursor-pointer";
        }
        else
        {
            $ops="";
        }

        foreach($nuclei as $curNucleo)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if(!empty($form) && !empty($field_id) && !empty($field_desc))
            {
                $select="try{if($$('".$form."')){ AA_MainApp.utils.callHandler('SicarSelectFormItem', {form:'".$form."', values:{'".$field_id."':'".$curNucleo->GetProp('id')."','".$field_desc."':'".$curNucleo->GetDescrizione()."'}},'".$this->GetId()."'); $$('".static::AA_UI_WND_SEARCH_NUCLEI."_Wnd').close();}}catch(msg){console.error(msg)}";
                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Scegli' onClick=\"".$select."\"><span class='mdi ".$select_icon."'></span></a></div>";
                if(!$curNucleo->HasAlloggio())
                { 
                    $alloggio_attuale_str="<span class='AA_Label AA_Label_LightRed'>Nessun alloggio assegnato</span>";
                }
                else
                {
                    $alloggio_attuale=$curNucleo->GetAlloggioAttuale();
                    $alloggio_attuale_str="<span class='AA_Label AA_Label_LightGreen'>".$alloggio_attuale->GetDescrizione()."</span>";
                    $ops="&nbsp;"; //non mostrare il pulsante di selezione per i nuclei con alloggio attuale
                }

                $data[]=array("id"=>$curNucleo->GetProp("id"),"denominazione"=>$curNucleo->GetDescrizione(),"cf"=>$curNucleo->GetCf(),"alloggio_attuale"=>$alloggio_attuale_str,"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curNucleo->GetProp("id"),"denominazione"=>$curNucleo->GetDenominazione(),"alloggio_attuale"=>$alloggio_attuale_str,"cf"=>$curNucleo->GetCf());
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id."_TableSearch_".uniqid(),"Ricerca nuclei",3,null,array("css"=>"AA_Header_DataTable"));
        else $template=new AA_GenericDatatableTemplate($id."_TableSearch_".uniqid(),"Ricerca nuclei",4,null,array("css"=>"AA_Header_DataTable"));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(false);
        $template->SetHeaderHeight(38);

        /*
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewImmobileDlg");
            if(!empty($form) && !empty($field_id) && !empty($field_desc)) $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1,"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc)));
            else $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1,"refresh_obj_id"=>$id)));
        }*/

        $template->SetColumnHeaderInfo(0,"denominazione","<div style='text-align: center'>Descrizione</div>",250,"textFilter","text","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(1,"cf","<div style='text-align: center'>Codice fiscale</div>","fillspace","textFilter","text","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(2,"alloggio_attuale","<div style='text-align: center'>Alloggio assegnato</div>","fillspace","textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(2,"comune","<div style='text-align: center'>Comune</div>",250,"textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(3,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"ImmobiliTable");

        $template->SetData($data);

        $layout->AddRow($template);
        return $layout;
    }

    //Template filtro di ricerca bozze
    public function TemplateBozzeFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("stato_conservazione"=>$params["stato_conservazione"],"indirizzo"=>$params["indirizzo"],"comune"=>$params["comune"],"immobile_desc"=>$params["immobile_desc"],"immobile"=>$params["immobile"],"ids"=>$params['ids'],"id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['nome']=="") $formData['nome']="";

        //Immobile
        if($params['immobile_desc']=="") $formData['immobile_desc']="Qualunque";
        if($params['immobile']=="") $formData['immobile']=0;
        if($params['indirizzo']=="") $formData['indirizzo']="";

        //stato conservazione
        if(empty($params['stato_conservazione'])) $formData['stato_conservazione']=-1;

        //Valori reset
        $resetData=array("stato_conservazione"=>-1,"indirizzo"=>"","comune"=>"","immobile_desc"=>"Qualunque","immobile"=>0,"ids"=>"","id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","nome"=>"","cestinate"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Bozze_Filter".uniqid(), "Parametri di ricerca per gli elementi in bozza",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
      
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura di gestione."));
    
        //titolo
        $dlg->AddTextField("nome","Descrizione",array("bottomLabel"=>"*Filtra in base alla descrizione dell'alloggio", "placeholder"=>"..."));
 
        //comune
        $dlg->AddTextField("comune", "Comune", ["bottomLabel" => "*Comune dell'immobile (codice ISTAT)", "suggest"=>array("template"=>"#codice#","url"=>$this->taskManagerUrl."?task=GetSicarListaCodiciIstat")]);
       
        //immobile
        $dlgParams = array("task" => "GetSicarSearchImmobiliDlg", "postParams" => array("form" => $dlg->GetFormId(),"field_id"=>"immobile","field_desc"=>"immobile_desc"));
        $dlg->AddSearchField("dlg",$dlgParams,$this->GetId(),["label"=>"Immobile","name"=>"immobile_desc", "bottomLabel" => "*Immobile di cui fa parte l'alloggio."]);

        //indirizzo
        $dlg->AddTextField("indirizzo","Indirizzo",array("bottomLabel"=>"*Filtra in base all'indirizzo dell'immobile di cui fa parte l'alloggio", "placeholder"=>"..."));
 
        //Stato conservazione
        $options=[0=>array("id"=>-1,"value"=>"Qualunque")];
        $options=array_merge($options,AA_Sicar_Const::GetListaStatiConservazioneAlloggio());
        //AA_Log::Log(__METHOD__." - ".print_r($options,true),100);
        $dlg->AddSelectField("stato_conservazione","Stato di conservazione",array("bottomLabel"=>"*Filtra in base allo stato di conservazione dell'alloggio", "options"=>$options));
        
        //ids
        $dlg->AddTextField("ids","Identificativi",array("bottomLabel"=>"*Filtra in base a uno o piu' identificativi (separati da virgola es. 101,105,205).", "placeholder"=>"..."));

        $dlg->SetApplyButtonName("Filtra");

        return $dlg->GetObject();
    }

    //Template filtro di ricerca bozze
    public function TemplatePubblicateFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("stato_conservazione"=>$params["stato_conservazione"],"indirizzo"=>$params["indirizzo"],"comune"=>$params["comune"],"immobile_desc"=>$params["immobile_desc"],"immobile"=>$params["immobile"],"ids"=>$params['ids'],"id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['nome']=="") $formData['nome']="";

        //Immobile
        if($params['immobile_desc']=="") $formData['immobile_desc']="Qualunque";
        if($params['immobile']=="") $formData['immobile']=0;
        if($params['indirizzo']=="") $formData['indirizzo']="";

        //stato conservazione
        if(empty($params['stato_conservazione'])) $formData['stato_conservazione']=-1;

        //Valori reset
        $resetData=array("stato_conservazione"=>-1,"indirizzo"=>"","comune"=>"","immobile_desc"=>"Qualunque","immobile"=>0,"ids"=>"","id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","nome"=>"","cestinate"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Bozze_Filter".uniqid(), "Parametri di ricerca per gli elementi in bozza",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
      
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura di gestione."));
    
        //titolo
        $dlg->AddTextField("nome","Descrizione",array("bottomLabel"=>"*Filtra in base alla descrizione dell'alloggio", "placeholder"=>"..."));
 
        //comune
        $dlg->AddTextField("comune", "Comune", ["bottomLabel" => "*Comune dell'immobile (codice ISTAT)", "suggest"=>array("template"=>"#codice#","url"=>$this->taskManagerUrl."?task=GetSicarListaCodiciIstat")]);
       
        //immobile
        $dlgParams = array("task" => "GetSicarSearchImmobiliDlg", "postParams" => array("form" => $dlg->GetFormId(),"field_id"=>"immobile","field_desc"=>"immobile_desc"));
        $dlg->AddSearchField("dlg",$dlgParams,$this->GetId(),["label"=>"Immobile","name"=>"immobile_desc", "bottomLabel" => "*Immobile di cui fa parte l'alloggio."]);

        //indirizzo
        $dlg->AddTextField("indirizzo","Indirizzo",array("bottomLabel"=>"*Filtra in base all'indirizzo dell'immobile di cui fa parte l'alloggio", "placeholder"=>"..."));
 
        //Stato conservazione
        $options=[0=>array("id"=>-1,"value"=>"Qualunque")];
        $options=array_merge($options,AA_Sicar_Const::GetListaStatiConservazioneAlloggio());
        //AA_Log::Log(__METHOD__." - ".print_r($options,true),100);
        $dlg->AddSelectField("stato_conservazione","Stato di conservazione",array("bottomLabel"=>"*Filtra in base allo stato di conservazione dell'alloggio", "options"=>$options));
        
        //ids
        $dlg->AddTextField("ids","Identificativi",array("bottomLabel"=>"*Filtra in base a uno o piu' identificativi (separati da virgola es. 101,105,205).", "placeholder"=>"..."));

        $dlg->SetApplyButtonName("Filtra");

        return $dlg->GetObject();
    }
    
    // Task per la restituzione della finestra di dialogo di aggiunta nuovo immobile
    public function Task_GetSicarAddNewImmobileDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi immobili", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarAddNewImmobileDlg(), true);
        return true;
    }

    // Task per la restituzione della finestra di dialogo di aggiunta nuovo ente
    public function Task_GetSicarAddNewEnteDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi immobili", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarAddNewEnteDlg(), true);
        return true;
    }

    // Task per la restituzione della finestra di dialogo di aggiunta nuovo nucleo
    public function Task_GetSicarAddNewNucleoDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi nuclei", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarAddNewNucleoDlg(), true);
        return true;
    }

    // Task per la finestra di modifica dati generali immobile
    public function Task_GetSicarModifyEnteDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare l'ente");
            return false;
        }

        $object = new AA_SicarEnte();
        if (!$object->Load($_REQUEST['id'],$this->oUser)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarModifyEnteDlg($object),true);
        return true;
    }

    // Task per la finestra di modifica dati generali nucleo
    public function Task_GetSicarModifyNucleoDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare il nucleo");
            return false;
        }

        $object = new AA_SicarNucleo();
        if (!$object->Load($_REQUEST['id'],$this->oUser)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarModifyNucleoDlg($object),true);
        return true;
    }

    // Task per la finestra di modifica dati generali immobile
    public function Task_GetSicarModifyImmobileDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare l'immmobile");
            return false;
        }

        $object = new AA_SicarImmobile();
        if (!$object->Load($_REQUEST['id'],$this->oUser)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarModifyImmobileDlg($object),true);
        return true;
    }
    
    // Task per la finestra di modifica dati generali alloggio
    public function Task_GetSicarModifyDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $object = new AA_SicarAlloggio($_REQUEST['id'], $this->oUser);
        if (!$object->IsValid()) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarModifyDlg($object),true);
        return true;
    }

    // Task per la finestra di aggiunta nuovo intervento
    public function Task_GetSicarAddNewStatoInterventiAlloggioDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $object = new AA_SicarAlloggio($_REQUEST['id'], $this->oUser);
        if (!$object->IsValid()) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarAddNewStatoInterventiAlloggioDlg($object),true);
        return true;
    }

    // Task per la finestra di aggiunta nuovo intervento immobile
    public function Task_GetSicarAddNewInterventoImmobileDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id_immobile']) || $_REQUEST['id_immobile'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo immobile non valido.", false);
            return false;
        }

        $object = new AA_SicarImmobile();
        if (!$object->Load($_REQUEST['id_immobile'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo immobile non valido.", false);
            return false;
        }

       // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi interventi", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarAddNewInterventoImmobileDlg($object),true);
        return true;
    }

    // Task per la finestra di modifica intervento immobile
    public function Task_GetSicarModifyInterventoImmobileDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id_immobile']) || $_REQUEST['id_immobile'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo immobile non valido.", false);
            return false;
        }

        $object = new AA_SicarImmobile();
        if (!$object->Load($_REQUEST['id_immobile'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo immobile non valido.", false);
            return false;
        }

       // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi interventi", false);
            return false;
        }

        //verifica che sia stato specificato l'id dell'intervento da modificare
        if (!isset($_REQUEST['id_intervento'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo intervento non valido.", false);
            return false;
        }

        $interventi = $object->GetInterventi();
        if(!isset($interventi[$_REQUEST['id_intervento']]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo intervento non valido.", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarModifyInterventoImmobileDlg($object, $_REQUEST['id_intervento']),true);
        return true;
    }

    // Task per la finestra di modifica stato interventoalloggio
    public function Task_GetSicarModifyStatoInterventiAlloggioDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $object = new AA_SicarAlloggio($_REQUEST['id'], $this->oUser);
        if (!$object->IsValid()) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento", false);
            return false;
        }

        if (!isset($_REQUEST['id_intervento'])) 
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non e' stata specificato l'identificativo dell'intervento", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarModifyStatoInterventiAlloggioDlg($object,$_REQUEST['id_intervento']),true);
        return true;
    }

    

    // Task per la finestra di aggiunta nuovo stato alloggio
    public function Task_GetSicarAddNewStatoOccupazioneAlloggioDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $object = new AA_SicarAlloggio($_REQUEST['id'], $this->oUser);
        if (!$object->IsValid()) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarAddNewStatoOccupazioneAlloggioDlg($object),true);
        return true;
    }

    // Task per la finestra di modifica stato alloggio
    public function Task_GetSicarModifyStatoOccupazioneAlloggioDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $object = new AA_SicarAlloggio($_REQUEST['id'], $this->oUser);
        if (!$object->IsValid()) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento", false);
            return false;
        }

         if (empty($_REQUEST['dal'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non e' stata specificato l'identificativo della data di inizio occupazione", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarModifyStatoOccupazioneAlloggioDlg($object,$_REQUEST['dal']),true);
        return true;
    }

    // Task per la finestra di eliminazione stato alloggio
    public function Task_GetSicarDeleteStatoOccupazioneAlloggioDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $object = new AA_SicarAlloggio($_REQUEST['id'], $this->oUser);
        if (!$object->IsValid()) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento", false);
            return false;
        }

         if (empty($_REQUEST['dal'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non e' stata specificato l'identificativo della data di inizio occupazione", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarDeleteStatoOccupazioneAlloggioDlg($object,$_REQUEST['dal']),true);
        return true;
    }

    // Task per la finestra di eliminazione stato interventi
    public function Task_GetSicarDeleteStatoInterventiAlloggioDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $object = new AA_SicarAlloggio($_REQUEST['id'], $this->oUser);
        if (!$object->IsValid()) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento", false);
            return false;
        }

         if (!isset($_REQUEST['id_intervento'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non e' stata specificato l'identificativo intervento", false);
            return false;
        }

        $interventi=$object->GetInterventi();
        if(!isset($interventi[$_REQUEST['id_intervento']]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Dettaglio intervento non presente (".$_REQUEST['id_intervento'].").", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarDeleteStatoInterventiAlloggioDlg($object,$_REQUEST['id_intervento']),true);
        return true;
    }

    // Task per la finestra di eliminazione intervento immobile
    public function Task_GetSicarDeleteInterventoImmobileDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id_immobile']) || $_REQUEST['id_immobile'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $object = new AA_SicarImmobile();
        if (!$object->Load($_REQUEST['id_immobile'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) == 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento", false);
            return false;
        }

         if (!isset($_REQUEST['id_intervento'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non e' stata specificato l'identificativo intervento", false);
            return false;
        }

        $interventi=$object->GetInterventi();
        if(!isset($interventi[$_REQUEST['id_intervento']]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Dettaglio intervento non presente (".$_REQUEST['id_intervento'].").", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarDeleteInterventoImmobileDlg($object,$_REQUEST['id_intervento']),true);
        return true;
    }

    // Task per la finestra visualizzazione dettaglio immobile
    public function Task_GetSicarDetailImmobileDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $object = new AA_SicarImmobile();
        if (!$object->Load($_REQUEST['id'],$this->oUser)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarDetailImmobileDlg($object),true);
        return true;
    }

    // Task per la finestra visualizzazione dettaglio ocupazione
    public function Task_GetSicarDetailStatoOccupazioneAlloggioDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $object = new AA_SicarAlloggio($_REQUEST['id'],$this->oUser);
        if (!$object->IsValid()) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $occupazione=$object->GetOccupazione();
        if(!isset($occupazione[$_REQUEST['dal']]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Dettaglio occupazione non presente (".$_REQUEST['dal'].").", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarDetailStatoOccupazioneAlloggioDlg($occupazione[$_REQUEST['dal']]),true);
        return true;
    }

    // Template per la finestra di dialogo di modifica alloggio
    public function Template_GetSicarModifyDlg($object)
    {
        return $this->Template_GetSicarModifyAlloggioDlg($object);
    }

    // Metodi specifici del modulo SICAR (Alloggi)
    public function GetAlloggio($id = 0, $user = null)
    {
        return AA_SicarAlloggio::Load($id, $user);
    }
    
    public function SearchAlloggi($params = array(), $user = null)
    {
        $params['class'] = 'AA_SicarAlloggio';
        return AA_SicarAlloggio::Search($params, $user);
    }
    
    public function AddAlloggio($alloggio = null, $user = null)
    {
        if ($alloggio instanceof AA_SicarAlloggio) {
            return AA_SicarAlloggio::AddNew($alloggio, $user, true);
        }
        return false;
    }
    
    // Task per ottenere le tipologie
    public function Task_GetSicarTipologie($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
            $options = AA_Sicar_Const::GetListaTipologie();
            if (!empty($options)) {
                $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
                $task->SetContent(json_encode($options), true);
                return true;
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nel recupero delle tipologie", false);
            return false;
    }
    
    // Task per ottenere le ubicazioni
    public function Task_GetSicarUbicazioni($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        $comune = isset($_REQUEST['comune']) ? $_REQUEST['comune'] : "";
        
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM aa_sicar_ubicazioni WHERE attivo = 1";
        if (!empty($comune)) {
            $query .= " AND comune = '" . addslashes($comune) . "'";
        }
        $query .= " ORDER BY ordine, descrizione";
        
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            $options = array();
            foreach ($rs as $row) {
                $options[] = array("id" => $row['id'], "value" => $row['descrizione']);
            }
            
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent(json_encode($options), true);
            return true;
        }
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("Errore nel recupero delle ubicazioni", false);
        return false;
    }
    
    // Task per ottenere le zone urbanistiche
    public function Task_GetSicarZoneUrbanistiche($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        $comune = isset($_REQUEST['comune']) ? $_REQUEST['comune'] : "";
        
        $db = new AA_Database();
        $query = "SELECT codice, descrizione FROM aa_sicar_zone_urbanistiche WHERE attivo = 1";
        if (!empty($comune)) {
            $query .= " AND comune = '" . addslashes($comune) . "'";
        }
        $query .= " ORDER BY ordine, descrizione";
        
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            $options = array();
            foreach ($rs as $row) {
                $options[] = array("id" => $row['codice'], "value" => $row['descrizione']);
            }
            
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent(json_encode($options), true);
            return true;
        }
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("Errore nel recupero delle zone urbanistiche", false);
        return false;
    }
    
    // Task per ottenere i comuni
    public function Task_GetSicarComuni($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // In un'implementazione reale, questa funzione dovrebbe caricare i comuni da una tabella
        // Per ora restituiamo alcuni comuni di esempio
        $comuni = array(
            array("id" => "092009", "value" => "Cagliari"),
            array("id" => "092003", "value" => "Alghero"),
            array("id" => "092015", "value" => "Carbonia"),
            array("id" => "092025", "value" => "Iglesias"),
            array("id" => "092035", "value" => "Nuoro"),
            array("id" => "092050", "value" => "Oristano"),
            array("id" => "092051", "value" => "Olbia"),
            array("id" => "092064", "value" => "Sassari")
        );
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent(json_encode($comuni), true);
        return true;
    }
    
    // Task per esportare in CSV
    public function Task_ExportSicarCsv($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        $searchParams = array(
            'class' => 'AA_SicarAlloggio',
            'status' => AA_Const::AA_STATUS_PUBBLICATA
        );
        
    $alloggi = AA_SicarAlloggio::Search($searchParams, $this->oUser);
        
        if (is_array($alloggi)) {
            $csv = "ID;Descrizione;Tipologia;Comune;Indirizzo;Piani;Stato\n";
            
            foreach ($alloggi as $alloggio) {
                $tipologia = $this->GetTipologiaDesc($alloggio->GetTipologia());
                $status = $this->GetStatusDesc($alloggio->GetStatus());
                
                $csv .= $alloggio->GetId() . ";" .
                       str_replace(";", ",", $alloggio->GetName()) . ";" .
                       str_replace(";", ",", $tipologia) . ";" .
                       $alloggio->GetComune() . ";" .
                       str_replace(";", ",", $alloggio->GetIndirizzo()) . ";" .
                       $alloggio->GetPiani() . ";" .
                       $status . "\n";
            }
            
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($csv, true);
            return true;
        }
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("Nessun dato da esportare", false);
        return false;
    }
    
    // Task per aggiungere un nuovo alloggio
    public function Task_AddNewAlloggioSicar($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi alloggi", false);
            return false;
        }
        
        if(isset($_REQUEST['superficie_utile_abitabile']) && $_REQUEST['superficie_utile_abitabile']!="")
        {
            $_REQUEST['superficie_utile_abitabile']=str_replace(",",".",str_replace(".","",$_REQUEST['superficie_utile_abitabile']));
        }
        if(isset($_REQUEST['superficie_netta']) && $_REQUEST['superficie_netta']!="")
        {
            $_REQUEST['superficie_netta']=str_replace(",",".",str_replace(".","",$_REQUEST['superficie_netta']));
        }

        if(empty($_REQUEST['gestione_ente']) || empty($_REQUEST['gestione_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare un ente gestore e una data iniziale di gestione.", false);
            return false;
        }

        $gestione=array(mb_substr($_REQUEST['gestione_dal'],0,10)=>$_REQUEST['gestione_ente']);

        $_REQUEST['gestione']=json_encode($gestione);

        if(empty($_REQUEST['proprieta_ente']) || empty($_REQUEST['proprieta_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare un ente proprietario e una data iniziale di proprieta'.", false);
            return false;
        }

        $gestione=array(mb_substr($_REQUEST['proprieta_dal'],0,10)=>$_REQUEST['proprieta_ente']);

        $_REQUEST['proprieta']=json_encode($gestione);

        // Utilizza il metodo generico della classe base
        return $this->Task_GenericAddNew($task, $_REQUEST);
    }

    // Task per aggiungere un nuovo stato alloggio
    public function Task_AddNewStatoOccupazioneAlloggioSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare lo stato di occupazione dell'alloggio", false);
            return false;
        }
        
        // Verifica che l'utente abbia i permessi di scrittura sull'oggetto specifico
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $alloggio = new AA_SicarAlloggio($id, $this->oUser);
            if ($alloggio->IsValid()) {
                if (($alloggio->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("L'utente corrente non ha i permessi di modifica dello stato di occupazione dell'alloggio", false);
                    return false;
                }
            }
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo non presente, aggiornamento non possibile.", false);
            return false;
        }

        $occupazione=$alloggio->GetOccupazione();

        if(empty($_REQUEST['data_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare la data di inizio del nuovo stato di occupazione.", false);
            return false;
        }

        $dettaglioOccupazione=array();
        $dettaglioOccupazione['stato']=0;
        $dettaglioOccupazione['note']=$_REQUEST['note'];

        if(!empty($_REQUEST['stato']) && intval($_REQUEST['stato'])>=1)
        {
            $dettaglioOccupazione['stato']=1;
            if(empty($_REQUEST['occupazione_id_nucleo']) || $_REQUEST['occupazione_id_nucleo']<=0)
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("E' necessario specificare il nucleo occupante per lo stato di occupazione selezionato.", false);
                return false;
            }
            $dettaglioOccupazione['occupazione_id_nucleo']=$_REQUEST['occupazione_id_nucleo'];

            if(empty($_REQUEST['occupazione_data_assegnazione']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("E' necessario specificare la data di assegnazione per lo stato di occupazione selezionato.", false);
                return false;
            }

            //Aggiorna il nucleo per assegnare l'alloggio al nucleo se e' l'utlimo stato di occupazione
            $update_nucleo=true;
            if(!empty($occupazione))
            {
                $last_date=current(array_keys($occupazione));
                if(strtotime($last_date)>strtotime(mb_substr($_REQUEST['data_dal'],0,10)))
                {
                    //non e' l'ultimo stato di occupazione, non aggiorna il nucleo
                    AA_Log::Log(__METHOD__." - Lo stato di occupazione inserito non e' il piu' recente, non viene aggiornato il nucleo.",100);
                    $update_nucleo=false;
                }
            }

            if($update_nucleo)
            {
                $nucleo=new AA_SicarNucleo();
                if($nucleo->Load($_REQUEST['occupazione_id_nucleo']))
                {
                    $nucleo->SetProp("alloggio_attuale",$alloggio->GetID());

                    //aggiorna l'indirizzo di residenza del nucleo se richiesto
                    if(!empty($_REQUEST['occupazione_residenza']) && $_REQUEST['occupazione_residenza']==1)
                    {
                        $immobile=$alloggio->GetImmobile();
                        $nucleo->SetProp("indirizzo",$immobile->GetIndirizzo());
                        $nucleo->SetProp("comune",$immobile->GetComune(false));
                    }
                    $nucleo->Sync($this->oUser);  
                }
                else
                {
                    AA_Log::Log(__METHOD__." - Impossibile caricare il nucleo con id: ".$_REQUEST['occupazione_id_nucleo'],100);
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("Nucleo occupante non trovato.", false);
                    return false;
                }
            }

            $dettaglioOccupazione['occupazione_data_assegnazione']=mb_substr($_REQUEST['occupazione_data_assegnazione'],0,10);

            $dettaglioOccupazione['occupazione_tipo_canone']=!empty($_REQUEST['occupazione_tipo_canone']) ? $_REQUEST['occupazione_tipo_canone'] : 0;

            $dettaglioOccupazione['occupazione_tipo']=!empty($_REQUEST['occupazione_tipo']) ? $_REQUEST['occupazione_tipo'] : 0;
            $dettaglioOccupazione['occupazione_residenza']=!empty($_REQUEST['occupazione_residenza']) ? 1 : 0;

            //rimuove l'alloggio dal nucleo a cui era assegnato in precedenza, se presente
            $last_occupazione=current($occupazione);
            if($last_occupazione['occupazione_id_nucleo']>0 && $last_occupazione['occupazione_id_nucleo']!=$dettaglioOccupazione['occupazione_id_nucleo'])
            {
                $nucleo=new AA_SicarNucleo();
                if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                {
                    $nucleo->SetProp("alloggio_attuale",0);
                    $nucleo->SetProp("indirizzo","n.d.");
                    $nucleo->SetProp("comune","");
                    $nucleo->Sync($this->oUser);
                }
                else
                {
                    AA_Log::Log(__METHOD__." - Impossibile caricare il nucleo con id: ".$last_occupazione['occupazione_id_nucleo'],100); 
                }
            }
        }
        else
        {
            $dettaglioOccupazione['occupazione_id_nucleo']=0;
            $dettaglioOccupazione['occupazione_data_assegnazione']="";
            $dettaglioOccupazione['occupazione_tipo_canone']=0;
            $dettaglioOccupazione['occupazione_tipo']=0;
            $dettaglioOccupazione['occupazione_residenza']=0;
        }

        $occupazione[mb_substr($_REQUEST['data_dal'],0,10)]=$dettaglioOccupazione;
        
        //ordina l'arrai in modo che la data piu' recente sia la prima
        krsort($occupazione, SORT_STRING);

        $_REQUEST['occupazione']=json_encode($occupazione);

        $alloggio->Parse($_REQUEST);

        $validate = $alloggio->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$alloggio->Update($this->oUser,true,"Aggiunta nuovo stato di occupazione"))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiunta del nuovo stato di occupazione.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }

    // Task per aggiungere un nuovo stato interventi
    public function Task_AddNewStatoInterventiAlloggioSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare lo stato d'interventi dell'alloggio", false);
            return false;
        }
        
        // Verifica che l'utente abbia i permessi di scrittura sull'oggetto specifico
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $alloggio = new AA_SicarAlloggio($id, $this->oUser);
            if ($alloggio->IsValid()) {
                if (($alloggio->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("L'utente corrente non ha i permessi di modifica dello stato d'interventi dell'alloggio", false);
                    return false;
                }
            }
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo non presente, aggiornamento non possibile.", false);
            return false;
        }

        $interventi=$alloggio->GetInterventi();

        if(empty($_REQUEST['data_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare la data di inizio del nuovo stato di occupazione.", false);
            return false;
        }

        $dettaglioInterventi=array();
        $dettaglioInterventi['data_dal']=date("Y-m-d");
        $dettaglioInterventi['data_al']="";
        $dettaglioInterventi['tipologia']=0;
        $dettaglioInterventi['importo_stimato']=0;
        $dettaglioInterventi['importo_finanziato']=0;
        $dettaglioInterventi['stato_lavori']=1; // "non avviati"
        $dettaglioInterventi['id_finanziamento']=0;
        $dettaglioInterventi['finanziamento_desc']="";
        $dettaglioInterventi['cup']="";
        $dettaglioInterventi['id_richiesta_finanziamento']=0;
        $dettaglioInterventi['richiesta_finanziamento_desc']="";
        $dettaglioInterventi['programma_finanziamento']=-1;

        //data dal
        if(!empty($_REQUEST['data_dal']))
        {
            $dettaglioInterventi['data_dal']=mb_substr($_REQUEST['data_dal'],0,10);
        }
        
        //data al
        if(!empty($_REQUEST['data_al']))
        {
            $dettaglioInterventi['data_al']=mb_substr($_REQUEST['data_al'],0,10);
        }

        //tipologia dell'intervento
        if(empty($_REQUEST['tipologia']) || $_REQUEST['tipologia']<=0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare la tipologia dell'intervento per lo stato d'intervento selezionato.", false);
            return false;
        }
        $dettaglioInterventi['tipologia']=$_REQUEST['tipologia'];

        if(!empty($_REQUEST['importo_stimato']) || $_REQUEST['importo_stimato']=="0")
        {
            $dettaglioInterventi['importo_stimato']=str_replace(',', '.', str_replace(".","",$_REQUEST['importo_stimato']));
            $dettaglioInterventi['importo_stimato']=AA_Utils::number_format($dettaglioInterventi['importo_stimato'],2,'.');
        }

        if(!empty($_REQUEST['importo_finanziato']) || $_REQUEST['importo_finanziato']=="0")
        {
            $dettaglioInterventi['importo_finanziato']=str_replace(',', '.', str_replace(".","",$_REQUEST['importo_finanziato']));
            $dettaglioInterventi['importo_finanziato']=AA_Utils::number_format($dettaglioInterventi['importo_finanziato'],2,'.');
        }

        if(!empty($_REQUEST['stato_lavori']) && intval($_REQUEST['stato_lavori'])>1)
        {
            $dettaglioInterventi['stato_lavori']=$_REQUEST['stato_lavori'];
        }

        //id finanziamento
        if(!empty($_REQUEST['id_finanziamento']) && $_REQUEST['id_finanziamento']>0)
        {
            $dettaglioInterventi['id_finanziamento']=$_REQUEST['id_finanziamento'];
            $finanziamento=new AA_SicarFinanziamento();
            if($finanziamento->Load($_REQUEST['id_finanziamento'],$this->oUser))
            {
                $dettaglioInterventi['finanziamento_desc']=$finanziamento->GetName();
            }
        }

        //richiesta finanziamento
        if(!empty($_REQUEST['id_richiesta_finanziamento']) && $_REQUEST['id_richiesta_finanziamento']>0)
        {
            $dettaglioInterventi['id_richiesta_finanziamento']=$_REQUEST['id_richiesta_finanziamento'];
            $richiestaFinanziamento=new AA_SicarRichiestaFinanziamento();
            if($richiestaFinanziamento->Load($_REQUEST['id_richiesta_finanziamento'],$this->oUser))
            {
                $dettaglioInterventi['richiesta_finanziamento_desc']=$richiestaFinanziamento->GetName();
            }
        }

        //programma finanziamento
        if(!empty($_REQUEST['programma_finanziamento']) && $_REQUEST['programma_finanziamento']>0)
        {
            $dettaglioInterventi['programma_finanziamento']=$_REQUEST['programma_finanziamento'];
        }

        //cup
        if(!empty($_REQUEST['cup']))
        {
            $dettaglioInterventi['cup']=trim($_REQUEST['cup']);
        }

        //aggiunge il nuovo stato di intervento all'elenco degli interventi dell'alloggio, utilizzando la data di inizio come chiave
        $interventi[uniqid()]=$dettaglioInterventi;

        //ordina l'arrai in modo che la data piu' recente sia la prima
        usort($interventi, function($a, $b) {
            return strcmp($b['data_dal'], $a['data_dal']);
        });

        $_REQUEST['interventi']=json_encode($interventi);

        $alloggio->Parse($_REQUEST);

        $validate = $alloggio->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$alloggio->Update($this->oUser,true,"Aggiunta nuovo stato interventi"))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiunta del nuovo stato interventi.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }

    // Task per aggiungere un nuovo intervento sull'immobile
    public function Task_AddNewInterventoImmobileSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi interventi sull'immobile", false);
            return false;
        }
        
        // Verifica che l'utente abbia i permessi di scrittura sull'oggetto specifico
        $id = isset($_REQUEST['id_immobile']) ? intval($_REQUEST['id_immobile']) : 0;
        if ($id > 0) {
            $immobile = new AA_SicarImmobile();
            if (!$immobile->Load($id)) {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("L'utente corrente non ha i permessi di modifica dello stato d'interventi dell'immobile", false);
                    return false;
            }
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo immobile non presente, aggiornamento non possibile.", false);
            return false;
        }

        $interventi=$immobile->GetInterventi();

        if(empty($_REQUEST['data_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare la data di inizio del nuovo stato di occupazione.", false);
            return false;
        }

        $dettaglioInterventi=array();
        $dettaglioInterventi['data_dal']=date("Y-m-d");
        $dettaglioInterventi['data_al']="";
        $dettaglioInterventi['tipologia']=0;
        $dettaglioInterventi['importo_stimato']=0;
        $dettaglioInterventi['importo_finanziato']=0;
        $dettaglioInterventi['stato_lavori']=1; // "non avviati"
        $dettaglioInterventi['id_finanziamento']=0;
        $dettaglioInterventi['finanziamento_desc']="";
        $dettaglioInterventi['cup']="";
        $dettaglioInterventi['id_richiesta_finanziamento']=0;
        $dettaglioInterventi['richiesta_finanziamento_desc']="";
        $dettaglioInterventi['programma_finanziamento']=-1;

        //data dal
        if(!empty($_REQUEST['data_dal']))
        {
            $dettaglioInterventi['data_dal']=mb_substr($_REQUEST['data_dal'],0,10);
        }
        
        //data al
        if(!empty($_REQUEST['data_al']))
        {
            $dettaglioInterventi['data_al']=mb_substr($_REQUEST['data_al'],0,10);
        }

        //tipologia dell'intervento
        if(empty($_REQUEST['tipologia']) || $_REQUEST['tipologia']<=0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare la tipologia dell'intervento per lo stato d'intervento selezionato.", false);
            return false;
        }
        $dettaglioInterventi['tipologia']=$_REQUEST['tipologia'];

        if(!empty($_REQUEST['importo_stimato']) || $_REQUEST['importo_stimato']=="0")
        {
            $dettaglioInterventi['importo_stimato']=str_replace(',', '.', str_replace(".","",$_REQUEST['importo_stimato']));
            $dettaglioInterventi['importo_stimato']=AA_Utils::number_format($dettaglioInterventi['importo_stimato'],2,'.');
        }

        if(!empty($_REQUEST['importo_finanziato']) || $_REQUEST['importo_finanziato']=="0")
        {
            $dettaglioInterventi['importo_finanziato']=str_replace(',', '.', str_replace(".","",$_REQUEST['importo_finanziato']));
            $dettaglioInterventi['importo_finanziato']=AA_Utils::number_format($dettaglioInterventi['importo_finanziato'],2,'.');
        }

        if(!empty($_REQUEST['stato_lavori']) && intval($_REQUEST['stato_lavori'])>1)
        {
            $dettaglioInterventi['stato_lavori']=$_REQUEST['stato_lavori'];
        }

        //id finanziamento
        if(!empty($_REQUEST['id_finanziamento']) && $_REQUEST['id_finanziamento']>0)
        {
            $dettaglioInterventi['id_finanziamento']=$_REQUEST['id_finanziamento'];
            $finanziamento=new AA_SicarFinanziamento();
            if($finanziamento->Load($_REQUEST['id_finanziamento'],$this->oUser))
            {
                $dettaglioInterventi['finanziamento_desc']=$finanziamento->GetName();
            }
        }

        //richiesta finanziamento
        if(!empty($_REQUEST['id_richiesta_finanziamento']) && $_REQUEST['id_richiesta_finanziamento']>0)
        {
            $dettaglioInterventi['id_richiesta_finanziamento']=$_REQUEST['id_richiesta_finanziamento'];
            $richiestaFinanziamento=new AA_SicarRichiestaFinanziamento();
            if($richiestaFinanziamento->Load($_REQUEST['id_richiesta_finanziamento'],$this->oUser))
            {
                $dettaglioInterventi['richiesta_finanziamento_desc']=$richiestaFinanziamento->GetName();
            }
        }

        //programma finanziamento
        if(!empty($_REQUEST['programma_finanziamento']) && $_REQUEST['programma_finanziamento']>0)
        {
            $dettaglioInterventi['programma_finanziamento']=$_REQUEST['programma_finanziamento'];
        }

        //cup
        if(!empty($_REQUEST['cup']))
        {
            $dettaglioInterventi['cup']=trim($_REQUEST['cup']);
        }

        //aggiunge il nuovo stato di intervento all'elenco degli interventi dell'alloggio, utilizzando la data di inizio come chiave
        $interventi[uniqid()]=$dettaglioInterventi;

        //ordina l'arrai in modo che la data piu' recente sia la prima
        usort($interventi, function($a, $b) {
            return strcmp($b['data_dal'], $a['data_dal']);
        });

        $_REQUEST['interventi']=json_encode($interventi);

        $immobile->Parse($_REQUEST);

        $validate = $immobile->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$immobile->Update(null,$this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiunta del nuovo intervento.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }

    // Task per aggiornamento intervento sull'immobile
    public function Task_UpdateInterventoImmobileSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi interventi sull'immobile", false);
            return false;
        }
        
        // Verifica che l'utente abbia i permessi di scrittura sull'oggetto specifico
        $id = isset($_REQUEST['id_immobile']) ? intval($_REQUEST['id_immobile']) : 0;
        if ($id > 0) {
            $immobile = new AA_SicarImmobile();
            if (!$immobile->Load($id)) {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("L'utente corrente non ha i permessi di modifica dello stato d'interventi dell'immobile", false);
                    return false;
            }
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo immobile non presente, aggiornamento non possibile.", false);
            return false;
        }

        $interventi=$immobile->GetInterventi();

        if(empty($_REQUEST['data_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare la data di inizio del nuovo stato di occupazione.", false);
            return false;
        }

        if(!isset($interventi[$_REQUEST['id_intervento']]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo intervento errato, aggiornamento non possibile.", false);
            return false;
        }

        $dettaglioInterventi=$interventi[$_REQUEST['id_intervento']];

        //data dal
        if(!empty($_REQUEST['data_dal']))
        {
            $dettaglioInterventi['data_dal']=mb_substr($_REQUEST['data_dal'],0,10);
        }
        
        //data al
        if(!empty($_REQUEST['data_al']))
        {
            $dettaglioInterventi['data_al']=mb_substr($_REQUEST['data_al'],0,10);
        }

        //tipologia dell'intervento
        if(empty($_REQUEST['tipologia']) || $_REQUEST['tipologia']<=0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare la tipologia dell'intervento per lo stato d'intervento selezionato.", false);
            return false;
        }
        $dettaglioInterventi['tipologia']=$_REQUEST['tipologia'];

        if(!empty($_REQUEST['importo_stimato']) || $_REQUEST['importo_stimato']=="0")
        {
            $dettaglioInterventi['importo_stimato']=str_replace(',', '.', str_replace(".","",$_REQUEST['importo_stimato']));
            $dettaglioInterventi['importo_stimato']=AA_Utils::number_format($dettaglioInterventi['importo_stimato'],2,'.');
        }

        if(!empty($_REQUEST['importo_finanziato']) || $_REQUEST['importo_finanziato']=="0")
        {
            $dettaglioInterventi['importo_finanziato']=str_replace(',', '.', str_replace(".","",$_REQUEST['importo_finanziato']));
            $dettaglioInterventi['importo_finanziato']=AA_Utils::number_format($dettaglioInterventi['importo_finanziato'],2,'.');
        }

        if(!empty($_REQUEST['stato_lavori']) && intval($_REQUEST['stato_lavori'])>1)
        {
            $dettaglioInterventi['stato_lavori']=$_REQUEST['stato_lavori'];
        }

        //id finanziamento
        if(!empty($_REQUEST['id_finanziamento']) && $_REQUEST['id_finanziamento']>0)
        {
            $dettaglioInterventi['id_finanziamento']=$_REQUEST['id_finanziamento'];
            $finanziamento=new AA_SicarFinanziamento();
            if($finanziamento->Load($_REQUEST['id_finanziamento'],$this->oUser))
            {
                $dettaglioInterventi['finanziamento_desc']=$finanziamento->GetName();
            }
        }

        //richiesta finanziamento
        if(!empty($_REQUEST['id_richiesta_finanziamento']) && $_REQUEST['id_richiesta_finanziamento']>0)
        {
            $dettaglioInterventi['id_richiesta_finanziamento']=$_REQUEST['id_richiesta_finanziamento'];
            $richiestaFinanziamento=new AA_SicarRichiestaFinanziamento();
            if($richiestaFinanziamento->Load($_REQUEST['id_richiesta_finanziamento'],$this->oUser))
            {
                $dettaglioInterventi['richiesta_finanziamento_desc']=$richiestaFinanziamento->GetName();
            }
        }

        //programma finanziamento
        if(!empty($_REQUEST['programma_finanziamento']) && $_REQUEST['programma_finanziamento']>0)
        {
            $dettaglioInterventi['programma_finanziamento']=$_REQUEST['programma_finanziamento'];
        }

        //cup
        if(!empty($_REQUEST['cup']))
        {
            $dettaglioInterventi['cup']=trim($_REQUEST['cup']);
        }

        //aggiunge il nuovo stato di intervento all'elenco degli interventi dell'alloggio, utilizzando la data di inizio come chiave
        $interventi[$_REQUEST['id_intervento']]=$dettaglioInterventi;

        //ordina l'arrai in modo che la data piu' recente sia la prima
        usort($interventi, function($a, $b) {
            return strcmp($b['data_dal'], $a['data_dal']);
        });

        $_REQUEST['interventi']=json_encode($interventi);

        $immobile->Parse($_REQUEST);

        $validate = $immobile->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$immobile->Update(null,$this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento del intervento.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }

    // Task per aggiornare uno stato interventi
    public function Task_UpdateStatoInterventiAlloggioSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare lo stato d'interventi dell'alloggio", false);
            return false;
        }
        
        // Verifica che l'utente abbia i permessi di scrittura sull'oggetto specifico
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $alloggio = new AA_SicarAlloggio($id, $this->oUser);
            if ($alloggio->IsValid()) {
                if (($alloggio->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("L'utente corrente non ha i permessi di modifica dello stato d'interventi dell'alloggio", false);
                    return false;
                }
            }
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo non presente, aggiornamento non possibile.", false);
            return false;
        }

        // Verifica che sia impostato l'identificativo dell'intervento da aggiornare
        $id_intervento = $_REQUEST['id_intervento'];
        if(!isset($_REQUEST['id_intervento']) || $_REQUEST['id_intervento']=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo intervento non presente, aggiornamento non possibile.", false);
            return false;
        }

        $interventi=$alloggio->GetInterventi();
        if(!isset($interventi[$id_intervento]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Intervento specificato (" . $id_intervento . ") non presente, aggiornamento non possibile.", false);
            return false;
        }

        if(empty($_REQUEST['data_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare la data di inizio.", false);
            return false;
        }
        else
        {
            $_REQUEST['data_dal']=mb_substr($_REQUEST['data_dal'],0,10);
        }
        
        //data al
        if(!empty($_REQUEST['data_al']))
        {
            $_REQUEST['data_al']=mb_substr($_REQUEST['data_al'],0,10);
        }

        $dettaglioInterventi=$interventi[$id_intervento];

        if(!empty($_REQUEST['data_dal']))
        {
           $dettaglioInterventi['data_dal']=$_REQUEST['data_dal'];
        }

        if(!empty($_REQUEST['data_al']))
        {
           $dettaglioInterventi['data_al']=$_REQUEST['data_al'];
        }
        else
        {
            $dettaglioInterventi['data_al']="";
        }

        //tipologia dell'intervento
        if(empty($_REQUEST['tipologia']) || $_REQUEST['tipologia']<=0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare la tipologia dell'intervento per lo stato d'intervento selezionato.", false);
            return false;
        }
        $dettaglioInterventi['tipologia']=$_REQUEST['tipologia'];

        if(!empty($_REQUEST['importo_stimato']) || $_REQUEST['importo_stimato']=="0")
        {
            $dettaglioInterventi['importo_stimato']=str_replace(',', '.', str_replace(".","",$_REQUEST['importo_stimato']));
            $dettaglioInterventi['importo_stimato']=AA_Utils::number_format($dettaglioInterventi['importo_stimato'],2,'.');
        }

        if(!empty($_REQUEST['importo_finanziato']) || $_REQUEST['importo_finanziato']=="0")
        {
            $dettaglioInterventi['importo_finanziato']=str_replace(',', '.', str_replace(".","",$_REQUEST['importo_finanziato']));
            $dettaglioInterventi['importo_finanziato']=AA_Utils::number_format($dettaglioInterventi['importo_finanziato'],2,'.');
        }

        if(!empty($_REQUEST['stato_lavori']) && intval($_REQUEST['stato_lavori'])>1)
        {
            $dettaglioInterventi['stato_lavori']=$_REQUEST['stato_lavori'];
        }

        //id finanziamento
        if(!empty($_REQUEST['id_finanziamento']) && $_REQUEST['id_finanziamento']>0)
        {
            $dettaglioInterventi['id_finanziamento']=$_REQUEST['id_finanziamento'];
            $finanziamento=new AA_SicarFinanziamento();
            if($finanziamento->Load($_REQUEST['id_finanziamento'],$this->oUser))
            {
                $dettaglioInterventi['finanziamento_desc']=$finanziamento->GetName();
            }
        }

        //richiesta finanziamento
        if(!empty($_REQUEST['id_richiesta_finanziamento']) && $_REQUEST['id_richiesta_finanziamento']>0)
        {
            $dettaglioInterventi['id_richiesta_finanziamento']=$_REQUEST['id_richiesta_finanziamento'];
            $richiestaFinanziamento=new AA_SicarRichiestaFinanziamento();
            if($richiestaFinanziamento->Load($_REQUEST['id_richiesta_finanziamento'],$this->oUser))
            {
                $dettaglioInterventi['richiesta_finanziamento_desc']=$richiestaFinanziamento->GetName();
            }
        }

        $dettaglioInterventi['programma_finanziamento']=!empty($_REQUEST['programma_finanziamento']) ? $_REQUEST['programma_finanziamento'] : 1; // "Nessuno"

        //cup
        if(!empty($_REQUEST['cup'])) $dettaglioInterventi['cup']=trim($_REQUEST['cup']);

        //aggiunge il nuovo stato di intervento all'elenco degli interventi dell'alloggio, utilizzando la data di inizio come chiave
        $interventi[$id_intervento]=$dettaglioInterventi;

       //ordina l'arrai in modo che la data piu' recente sia la prima
        usort($interventi, function($a, $b) {
            return strcmp($b['data_dal'], $a['data_dal']);
        });

        $_REQUEST['interventi']=json_encode($interventi);

        $alloggio->Parse($_REQUEST);

        $validate = $alloggio->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$alloggio->Update($this->oUser,true,"Aggiornamento intervento"))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dell'intervento.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }


    // Task per eliminare uno stato interventi
    public function Task_DeleteStatoInterventiAlloggioSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare lo stato d'interventi dell'alloggio", false);
            return false;
        }
        
        // Verifica che l'utente abbia i permessi di scrittura sull'oggetto specifico
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $alloggio = new AA_SicarAlloggio($id, $this->oUser);
            if ($alloggio->IsValid()) {
                if (($alloggio->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("L'utente corrente non ha i permessi di modifica dello stato d'interventi dell'alloggio", false);
                    return false;
                }
            }
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo non presente, eliminazione non possibile.", false);
            return false;
        }

        // Verifica che sia impostato l'identificativo dell'intervento da eliminare
        $id_intervento = $_REQUEST['id_intervento'];
        if(!isset($_REQUEST['id_intervento']) || $_REQUEST['id_intervento']=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo intervento non presente, eliminazione non possibile.", false);
            return false;
        }

        $interventi=$alloggio->GetInterventi();
        if(!isset($interventi[$id_intervento]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Intervento specificato (" . $id_intervento . ") non presente, eliminazione non possibile.", false);
            return false;
        }
        
        unset($interventi[$id_intervento]);
        
        //ordina l'arrai in modo che la data piu' recente sia la prima
        usort($interventi, function($a, $b) {
            return strcmp($b['data_dal'], $a['data_dal']);
        });

        $_REQUEST['interventi']=json_encode($interventi);

        $alloggio->Parse($_REQUEST);

        $validate = $alloggio->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$alloggio->Update($this->oUser,true,"L'intervento indicato e' stato eliminato."))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'eliminazione dell'intervento.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }

    // Task per eliminare uno stato interventi immobile
    public function Task_DeleteInterventoImmobileSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per eliminare l'immobile
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per rimuovere l'intervento dell'immobile", false);
            return false;
        }
        
        // Verifica l'immobile sia impostato
        $immobile = new AA_SicarImmobile();
        if (!$immobile->Load($_REQUEST['id_immobile'])) 
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Immobile non presente", false);
            return false;
        }
 
        // Verifica che sia impostato l'identificativo dell'intervento da eliminare
        $id_intervento = $_REQUEST['id_intervento'];
        if(!isset($_REQUEST['id_intervento']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo intervento non presente, eliminazione non possibile.", false);
            return false;
        }

        $interventi=$immobile->GetInterventi();
        if(!isset($interventi[$id_intervento]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Intervento specificato (" . $id_intervento . ") non presente, eliminazione non possibile.", false);
            return false;
        }
        
        unset($interventi[$id_intervento]);
        
        //ordina l'arrai in modo che la data piu' recente sia la prima
        usort($interventi, function($a, $b) {
            return strcmp($b['data_dal'], $a['data_dal']);
        });

        $_REQUEST['interventi']=json_encode($interventi);

        $immobile->Parse($_REQUEST);

        $validate = $immobile->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$immobile->Update(null,$this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'eliminazione dell'intervento.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }

    //Task NavBarContent
    public function Task_GetNavbarContent($task)
    {
        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR))
        {
            $_REQUEST['section']=static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX;
        }
        
        return $this->Task_GetGenericNavbarContent($task,$_REQUEST);
    }

    // Task per aggiungere un nuovo immobile
    public function Task_AddNewImmobileSicar($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi alloggi", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun dato indicato per il nuovo immobile", false);
            return false;
        }

        if(empty($_REQUEST['catasto']))
        {
            $catasto=array();
            if(empty($_REQUEST['SezioneCatasto']))
            {
                $catasto['SezioneCatasto']=0;
            }
            $catasto['SezioneCatasto']=$_REQUEST['SezioneCatasto'];

            if(empty($_REQUEST['FoglioCatasto']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare il foglio catastale", false);
                return false;
            }
            $catasto['FoglioCatasto']=$_REQUEST['FoglioCatasto'];

            if(empty($_REQUEST['MappaleCatasto']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare il mappale catastale", false);
                return false;
            }
            $catasto['MappaleCatasto']=$_REQUEST['MappaleCatasto'];

            if(empty($_REQUEST['ParticellaCatasto']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare la particella catastale", false);
                return false;
            }
            $catasto['ParticellaCatasto']=$_REQUEST['ParticellaCatasto'];

            if(empty($_REQUEST['Subalterno']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare il subalterno", false);
                return false;
            }
            $catasto['Subalterno']=$_REQUEST['Subalterno'];
           
        }
        else
        {
            $catasto=json_decode($_REQUEST['catasto'],true);
            if(empty($catasto))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("I dati catastali non sono correttamente formattati", false);
                return false;
            }
        }

        $_REQUEST['catasto']=json_encode($catasto);

        $attributi=array();
        if(empty($_REQUEST['attributi']))
        {
            $attributi['condominio_misto']=0;
            if(!empty($_REQUEST['attributi_condominio_misto'])) $attributi['condominio_misto']=1;

            //num alloggi
            $attributi['alloggi']=0;
            if(!empty($_REQUEST['attributi_alloggi'])) $attributi['alloggi']=intval($_REQUEST['attributi_alloggi']);

            //ente gestore
            $attributi['gestione']=array();
            if(!empty($_REQUEST['immobile_gestione_ente']) && !empty($_REQUEST['immobile_gestione_dal'])) 
            {
                $attributi['gestione']=array(mb_substr($_REQUEST['immobile_gestione_dal'],0,10)=>$_REQUEST['immobile_gestione_ente']);
            }
            else
            {
                //$task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                //$task->SetError("Occorre specificare un ente gestore e una data iniziale di gestione", false);
                //return false;

                $attributi['gestione']=array();
            }
        }

        $_REQUEST['attributi']=json_encode($attributi);
       
        $immobile = new AA_SicarImmobile($_REQUEST);
        $validate=$immobile->Validate();
        if(sizeof($validate)>0)
        {
            AA_Log::Log(__METHOD__." - Sono stati trovati i seguenti errori: ".print_r($validate,true),100);
            $error="Sono state riscontrate le seguenti criticita': <br>";
            foreach($validate as $curError)
            {
                $error.="<li>".$curError."</li>";
            }

            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$immobile->Sync($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiunta dell'immobile", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Immobile aggiunto con successo", false);
        return true;
    }

    // Task per aggiungere un nuovo ente
    public function Task_AddNewEnteSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi enti", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun dato indicato per il nuovo ente", false);
            return false;
        }
       
        $var = new AA_SicarEnte($_REQUEST);
        $validate=$var->Validate();
        if(sizeof($validate)>0)
        {
            AA_Log::Log(__METHOD__." - Sono stati trovati i seguenti errori: ".print_r($validate,true),100);
            $error="Sono state riscontrate le seguenti criticita': <br>";
            foreach($validate as $curError)
            {
                $error.="<li>".$curError."</li>";
            }

            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$var->Sync($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiunta dell'ente", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Ente aggiunto con successo", false);
        return true;
    }

    // Task per aggiungere un nuovo nucleo
    public function Task_AddNewNucleoSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi enti", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun dato indicato per il nuovo nucleo", false);
            return false;
        }
       
        //comune
        if(!empty($_REQUEST['comune']))
        {
            $_REQUEST['comune']=mb_substr($_REQUEST['comune'],-5,4);
        }

        $var = new AA_SicarNucleo($_REQUEST);
        $validate=$var->Validate();
        if(sizeof($validate)>0)
        {
            AA_Log::Log(__METHOD__." - Sono stati trovati i seguenti errori: ".print_r($validate,true),100);
            $error="Sono state riscontrate le seguenti criticita': <br>";
            foreach($validate as $curError)
            {
                $error.="<li>".$curError."</li>";
            }

            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$var->Sync($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiunta dell'ente", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Ente aggiunto con successo", false);
        return true;
    }

    // Task per modificare un ente esistente
    public function Task_UpdateEnteSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare enti", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun ente indicato", false);
            return false;
        }
       

        $var = new AA_SicarEnte();
        if(!$var->Load($_REQUEST['id']))
        {
            AA_Log::Log(__METHOD__." - Ente non valido.",100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo ente non specificato o non valido.", false);
            return false;
        }
        $var->Parse($_REQUEST);
        $validate=$var->Validate();
        if(sizeof($validate)>0)
        {
            AA_Log::Log(__METHOD__." - Sono stati trovati i seguenti errori: ".print_r($validate,true),100);
            $error="Sono state riscontrate le seguenti criticita': <br>";
            foreach($validate as $curError)
            {
                $error.="<li>".$curError."</li>";
            }

            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$var->Sync($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dell'ente", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Ente aggiornato con successo", false);
        return true;
    }

    // Task per modificare un nucleo esistente
    public function Task_UpdateNucleoSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare i nuclei", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun nucleo indicato", false);
            return false;
        }
       
        //comune
        if(!empty($_REQUEST['comune']))
        {
            $_REQUEST['comune']=mb_substr($_REQUEST['comune'],-5,4);
        }

        $var = new AA_SicarNucleo();
        if(!$var->Load($_REQUEST['id']))
        {
            AA_Log::Log(__METHOD__." - Nucleo non valido.",100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo nucleo non specificato o non valido.", false);
            return false;
        }
        $var->Parse($_REQUEST);
        $validate=$var->Validate();
        if(sizeof($validate)>0)
        {
            AA_Log::Log(__METHOD__." - Sono stati trovati i seguenti errori: ".print_r($validate,true),100);
            $error="Sono state riscontrate le seguenti criticita': <br>";
            foreach($validate as $curError)
            {
                $error.="<li>".$curError."</li>";
            }

            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$var->Sync($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento del nucleo", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Nucleo aggiornato con successo", false);
        return true;
    }

    // Task per aggiornare un immobile esistente
    public function Task_UpdateImmobileSicar($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare l'immobile", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun dato indicato per l'immobile", false);
            return false;
        }

        $immobile=new AA_SicarImmobile();
        if(!$immobile->Load($_REQUEST['id']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Immobile non trovato (id: ".$_REQUEST['id'].")", false);
            return false;
        }

        if(empty($_REQUEST['catasto']))
        {
            $catasto=array();
            if(empty($_REQUEST['SezioneCatasto']))
            {
                $catasto['SezioneCatasto']=0;
            }
            $catasto['SezioneCatasto']=$_REQUEST['SezioneCatasto'];

            if(empty($_REQUEST['FoglioCatasto']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare il foglio catastale", false);
                return false;
            }
            $catasto['FoglioCatasto']=$_REQUEST['FoglioCatasto'];

            if(empty($_REQUEST['MappaleCatasto']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare il mappale catastale", false);
                return false;
            }
            $catasto['MappaleCatasto']=$_REQUEST['MappaleCatasto'];

            if(empty($_REQUEST['ParticellaCatasto']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare la particella catastale", false);
                return false;
            }
            $catasto['ParticellaCatasto']=$_REQUEST['ParticellaCatasto'];

            if(empty($_REQUEST['Subalterno']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare il subalterno", false);
                return false;
            }
            $catasto['Subalterno']=$_REQUEST['Subalterno'];
            
            $_REQUEST['catasto']=json_encode($catasto);
        }
        else
        {
            $catasto=json_decode($_REQUEST['catasto'],true);
            if(empty($catasto))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("I dati catastali non sono correttamente formattati", false);
                return false;
            }
        }
       
        $attributi=$immobile->GetAttributi();
        if(empty($_REQUEST['attributi']))
        {
            $attributi['condominio_misto']=0;
            if(!empty($_REQUEST['attributi_condominio_misto'])) $attributi['condominio_misto']=1;

            //num alloggi
            $attributi['alloggi']=0;
            if(!empty($_REQUEST['attributi_alloggi'])) $attributi['alloggi']=intval($_REQUEST['attributi_alloggi']);

            //ente gestore
            $gestione=$immobile->GetGestione();
            if(!empty($_REQUEST['immobile_gestione_ente']) && !empty($_REQUEST['immobile_gestione_dal'])) 
            {
                $gestione[mb_substr($_REQUEST['immobile_gestione_dal'],0,10)]=$_REQUEST['immobile_gestione_ente'];
            }
            else
            {
                //$task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                //$task->SetError("Occorre specificare un ente gestore e una data iniziale di gestione", false);
                //return false;
            }
            $attributi['gestione']=$gestione;
        }
        else
        {
            $new_attributi=json_decode($_REQUEST['attributi'],true);
      
            if(is_array($new_attributi)) $attributi=$new_attributi;
        }       

        $_REQUEST['attributi']=json_encode($attributi);
        //AA_Log::Log(__METHOD__." - Attributi: ".print_r($attributi,true),100);

        $immobile->Parse($_REQUEST);

        $validate=$immobile->Validate();
        if(sizeof($validate)>0)
        {
            AA_Log::Log(__METHOD__." - Sono stati trovati i seguenti errori: ".print_r($validate,true),100);
            $error="Sono state riscontrate le seguenti criticita': <br>";
            foreach($validate as $curError)
            {
                $error.="<li>".$curError."</li>";
            }

            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$immobile->Sync($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dell'immobile", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Immobile aggiornato con successo", false);
        return true;
    }

    // Task per eliminare un immobile esistente
    public function Task_DeleteImmobileSicar($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per eliminare l'immobile", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun dato indicato per l'immobile", false);
            return false;
        }

        $immobile=new AA_SicarImmobile();
        if(!$immobile->Load($_REQUEST['id']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Immobile non trovato (id: ".$_REQUEST['id'].")", false);
            return false;
        }

        if(!$immobile->Delete($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'eliminazione dell'immobile", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Immobile eliminato con successo", false);
        return true;
    }
    
    // Task per eliminare un nucleo esistente
    public function Task_DeleteNucleoSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per eliminare il nucleo", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun dato indicato per nucleo", false);
            return false;
        }

        $nucleo=new AA_SicarNucleo();
        if(!$nucleo->Load($_REQUEST['id']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nucleo non trovato (id: ".$_REQUEST['id'].")", false);
            return false;
        }

        if(!$nucleo->Delete($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'eliminazione del nucleo", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Nucleo eliminato con successo", false);
        return true;
    }

    // Task per aggiornare un alloggio
    public function Task_UpdateSicar($task)
    {
        // Verifica che l'utente abbia i permessi per modificare gli alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare gli alloggi", false);
            return false;
        }
        
        // Verifica che l'utente abbia i permessi di scrittura sull'oggetto specifico
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $alloggio = new AA_SicarAlloggio($id, $this->oUser);
            if ($alloggio->IsValid()) {
                if (($alloggio->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("L'utente corrente non ha i permessi di modifica dell'alloggio", false);
                    return false;
                }
            }
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo non presente, aggiornamento non possibile.", false);
            return false;
        }
        
        if(isset($_REQUEST['superficie_utile_abitabile']) && $_REQUEST['superficie_utile_abitabile']!="")
        {
            $_REQUEST['superficie_utile_abitabile']=str_replace(",",".",str_replace(".","",$_REQUEST['superficie_utile_abitabile']));
        }
        if(isset($_REQUEST['superficie_non_residenziale']) && $_REQUEST['superficie_non_residenziale']!="")
        {
            $_REQUEST['superficie_non_residenziale']=str_replace(",",".",str_replace(".","",$_REQUEST['superficie_non_residenziale']));
        }
        if(isset($_REQUEST['superficie_parcheggi']) && $_REQUEST['superficie_parcheggi']!="")
        {
            $_REQUEST['superficie_parcheggi']=str_replace(",",".",str_replace(".","",$_REQUEST['superficie_parcheggi']));
        }
        if(isset($_REQUEST['vani_abitabili']) && $_REQUEST['vani_abitabili']!="")
        {
            $_REQUEST['vani_abitabili']=str_replace(",",".",str_replace(".","",$_REQUEST['vani_abitabili']));
        }
        
        //gestione
        if(empty($_REQUEST['gestione_ente']) || empty($_REQUEST['gestione_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare un ente gestore e una data iniziale di gestione.", false);
            return false;
        }

        $gestione=$alloggio->GetGestione();
        $gestione[mb_substr($_REQUEST['gestione_dal'],0,10)]=$_REQUEST['gestione_ente'];
        krsort($gestione);

        $_REQUEST['gestione']=json_encode($gestione);
        //------------------------

        //proprieta
        if(empty($_REQUEST['proprieta_ente']) || empty($_REQUEST['proprieta_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare un ente proprietario e una data iniziale di proprieta'.", false);
            return false;
        }

        $proprieta=$alloggio->GetProprieta();
        $proprieta[mb_substr($_REQUEST['proprieta_dal'],0,10)]=$_REQUEST['proprieta_ente'];
        krsort($proprieta);
        $proprieta=array(mb_substr($_REQUEST['proprieta_dal'],0,10)=>$_REQUEST['proprieta_ente']);

        $_REQUEST['proprieta']=json_encode($proprieta);
        //---------------------------------

        $alloggio->Parse($_REQUEST);

        $validate = $alloggio->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true)." - ".print_r($alloggio, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$alloggio->Update($this->oUser,true,"Aggiornamento dati generali"))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dei dati generali.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }

    // Task per aggiornare uno stato di occupazione di un alloggio
    public function Task_UpdateStatoOccupazioneAlloggioSicar($task)
    {
        // Verifica che l'utente abbia i permessi per modificare gli alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare gli alloggi", false);
            return false;
        }
        
        // Verifica che l'utente abbia i permessi di scrittura sull'oggetto specifico
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $alloggio = new AA_SicarAlloggio($id, $this->oUser);
            if ($alloggio->IsValid()) {
                if (($alloggio->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("L'utente corrente non ha i permessi di modifica dell'alloggio", false);
                    return false;
                }
            }
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo non presente, aggiornamento non possibile.", false);
            return false;
        }
        
        $occupazione=$alloggio->GetOccupazione();

        if(empty($_REQUEST['data_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Stato di occupazione non valido.", false);
            return false;
        }

        $dettaglioOccupazione=array();
        $dettaglioOccupazione['stato']=0;
        $dettaglioOccupazione['note']=$_REQUEST['note'];
        
        //Aggiorna il nucleo associato se e' l'ultimo stato di occupazione
        $update_nucleo=true;
        if(!empty($occupazione))
        {
            $last_date=current(array_keys($occupazione));
            if(strtotime($last_date) > strtotime(mb_substr($_REQUEST['data_dal'],0,10)))
            {
                //non e' l'ultimo stato di occupazione, non aggiorna il nucleo
                AA_Log::Log(__METHOD__." - Lo stato di occupazione inserito non e' il piu' recente, non viene aggiornato il nucleo.",100);
                $update_nucleo=false;
            }
        }

        if(!empty($_REQUEST['stato']) && intval($_REQUEST['stato'])>=1)
        {
            $dettaglioOccupazione['stato']=1;
            if(empty($_REQUEST['occupazione_id_nucleo']) || $_REQUEST['occupazione_id_nucleo']<=0)
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("E' necessario specificare il nucleo occupante per lo stato di occupazione selezionato.", false);
                return false;
            }
            
            if(empty($_REQUEST['occupazione_data_assegnazione']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("E' necessario specificare la data di assegnazione per lo stato di occupazione selezionato.", false);
                return false;
            }

            $dettaglioOccupazione['occupazione_id_nucleo']=$_REQUEST['occupazione_id_nucleo'];
            
            if($update_nucleo)
            {
                //rimuove l'alloggio dal nucleo a cui era assegnato in precedenza, se presente
                $last_occupazione=current($occupazione);
                AA_Log::Log(__METHOD__." - Last occupazione: ".print_r($last_occupazione,true),100);
                if($last_occupazione['occupazione_id_nucleo']>0 && $last_occupazione['occupazione_id_nucleo']!=$dettaglioOccupazione['occupazione_id_nucleo'])
                {
                    $nucleo=new AA_SicarNucleo();
                    if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                    {
                        $nucleo->SetProp("alloggio_attuale",0);
                        $nucleo->SetProp("indirizzo","n.d.");
                        $nucleo->SetProp("comune","");
                        $nucleo->Sync($this->oUser);
                    }
                    else
                    {
                        AA_Log::Log(__METHOD__." - Impossibile caricare il nucleo con id: ".$last_occupazione['occupazione_id_nucleo'],100); 
                    }
                }

                //Aggiorna il nucleo per assegnare l'alloggio al nucleo
                $nucleo=new AA_SicarNucleo();
                if($nucleo->Load($_REQUEST['occupazione_id_nucleo']))
                {
                    $nucleo->SetProp("alloggio_attuale",$alloggio->GetID());

                    //aggiorna l'indirizzo di residenza del nucleo se richiesto
                    if(!empty($_REQUEST['occupazione_residenza']) && $_REQUEST['occupazione_residenza']==1)
                    {
                        $immobile=$alloggio->GetImmobile();
                        $nucleo->SetProp("indirizzo",$immobile->GetIndirizzo());
                        $nucleo->SetProp("comune",$immobile->GetComune(false));
                    }
                    $nucleo->Sync($this->oUser);  
                }
                else
                {
                    AA_Log::Log(__METHOD__." - Impossibile caricare il nucleo con id: ".$_REQUEST['occupazione_id_nucleo'],100);
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("Nucleo occupante non trovato.", false);
                    return false;
                }
            }

            $dettaglioOccupazione['occupazione_data_assegnazione']=mb_substr($_REQUEST['occupazione_data_assegnazione'],0,10);

            $dettaglioOccupazione['occupazione_tipo_canone']=!empty($_REQUEST['occupazione_tipo_canone']) ? $_REQUEST['occupazione_tipo_canone'] : 0;

            $dettaglioOccupazione['occupazione_tipo']=!empty($_REQUEST['occupazione_tipo']) ? $_REQUEST['occupazione_tipo'] : 0;
            $dettaglioOccupazione['occupazione_residenza']=!empty($_REQUEST['occupazione_residenza']) ? 1 : 0;
        }
        else
        {
            if($update_nucleo)
            {
                //rimuove l'alloggio dal nucleo a cui era assegnato in precedenza, se presente
                $last_occupazione=current($occupazione);
                AA_Log::Log(__METHOD__." - Last occupazione: ".print_r($last_occupazione,true),100);
                if($last_occupazione['occupazione_id_nucleo']>0 && $last_occupazione['occupazione_id_nucleo']!=$dettaglioOccupazione['occupazione_id_nucleo'])
                {
                    $nucleo=new AA_SicarNucleo();
                    if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                    {
                        $nucleo->SetProp("alloggio_attuale",0);
                        $nucleo->SetProp("indirizzo","n.d.");
                        $nucleo->SetProp("comune","");
                        $nucleo->Sync($this->oUser);
                    }
                    else
                    {
                        AA_Log::Log(__METHOD__." - Impossibile caricare il nucleo con id: ".$last_occupazione['occupazione_id_nucleo'],100); 
                    }
                }
            }

            $dettaglioOccupazione['occupazione_id_nucleo']=0;
            $dettaglioOccupazione['occupazione_data_assegnazione']="";
            $dettaglioOccupazione['occupazione_tipo_canone']=0;
            $dettaglioOccupazione['occupazione_tipo']=0;
            $dettaglioOccupazione['occupazione_residenza']=0;
        }

        $occupazione[mb_substr($_REQUEST['data_dal'],0,10)]=$dettaglioOccupazione;
        
        //ordina l'arrai in modo che la data piu' recente sia la prima
        krsort($occupazione, SORT_STRING);

        $_REQUEST['occupazione']=json_encode($occupazione);
        
        $alloggio->Parse($_REQUEST);

        $validate = $alloggio->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$alloggio->Update($this->oUser,true,"Aggiornamento stato occupazione dal: ".mb_substr($_REQUEST['data_dal'],0,10)))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dello stato di occupazione.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }
    
    // Task per rimuovere uno stato di occupazione di un alloggio
    public function Task_DeleteStatoOccupazioneAlloggioSicar($task)
    {
        // Verifica che l'utente abbia i permessi per modificare gli alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare gli alloggi", false);
            return false;
        }
        
        // Verifica che l'utente abbia i permessi di scrittura sull'oggetto specifico
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $alloggio = new AA_SicarAlloggio($id, $this->oUser);
            if ($alloggio->IsValid()) {
                if (($alloggio->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("L'utente corrente non ha i permessi di modifica dell'alloggio", false);
                    return false;
                }
            }
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo non presente, aggiornamento non possibile.", false);
            return false;
        }
        
        $occupazione=$alloggio->GetOccupazione();
        
        //Verifica se deve agiornare il nucleo associato
        $update_nucleo=true;
        if(!empty($occupazione))
        {
            $last_date=current(array_keys($occupazione));
            if(strtotime($last_date)>strtotime(mb_substr($_REQUEST['dal'],0,10)))
            {
                //non e' l'ultimo stato di occupazione, non aggiorna il nucleo
                AA_Log::Log(__METHOD__." - Lo stato di occupazione inserito non e' il piu' recente, non viene aggiornato il nucleo.",100);
                $update_nucleo=false;
            }
        }

        if(empty($_REQUEST['dal']) || !isset($occupazione[mb_substr($_REQUEST['dal'],0,10)]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Stato di occupazione non valido o non presente.", false);
            return false;
        }

        if(!isset($occupazione[mb_substr($_REQUEST['dal'],0,10)]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Stato di occupazione non valido o non presente.", false);
            return false;
        }

        $thisOccupazione=$occupazione[mb_substr($_REQUEST['dal'],0,10)];
        if(key($occupazione)==mb_substr($_REQUEST['dal'],0,10) && $update_nucleo)
        {
            //se si sta eliminando lo stato di occupazione piu' recente con un nucleo oassociato, occorre inserire un nuovo stato di occupazione libero come stato sostitutivo
            $occupazione[mb_substr($_REQUEST['dal'],0,10)]=array(
                'stato'=>0,
                'occupazione_id_nucleo'=>0,
                'occupazione_data_assegnazione'=>'',
                'occupazione_tipo_canone'=>0,
                'occupazione_tipo'=>0,
                'occupazione_residenza'=>0,
                'note'=>''
            );
        }
        else
        {
            //rimuove lo stato di occupazione selezionato
            unset($occupazione[mb_substr($_REQUEST['dal'],0,10)]);
        }

        if($update_nucleo)
        {
            //rimuove l'alloggio dal nucleo a cui era assegnato in precedenza, se presente
            AA_Log::Log(__METHOD__." - Last occupazione: ".print_r($thisOccupazione,true),100);
            if($thisOccupazione['occupazione_id_nucleo']>0)
            {
                $nucleo=new AA_SicarNucleo();
                if($nucleo->Load($thisOccupazione['occupazione_id_nucleo']))
                {
                    $nucleo->SetProp("alloggio_attuale",0);
                    $nucleo->SetProp("indirizzo","n.d.");
                    $nucleo->SetProp("comune","");
                    $nucleo->Update($this->oUser);
                }
                else
                {
                    AA_Log::Log(__METHOD__." - Impossibile caricare il nucleo con id: ".$thisOccupazione['occupazione_id_nucleo'],100); 
                }
            }
        }
        
        //ordina l'arrai in modo che la data piu' recente sia la prima
        krsort($occupazione, SORT_STRING);
        
        $alloggio->setOccupazione($occupazione);

        $validate = $alloggio->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$alloggio->Update($this->oUser,true,"Eliminazione stato occupazione dal: ".mb_substr($_REQUEST['dal'],0,10)))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dello stato di occupazione.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }

    // Task per eliminare un alloggio
    public function Task_DeleteSicar($task)
    {
        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per eliminare l'elemento.", false);
            return false;
        }

        return $this->Task_GenericDeleteObject($task,$_REQUEST);
    }
    
    // Task per pubblicare un alloggio
    public function Task_PublishSicar($task)
    {
         return $this->Task_GenericPublishObject($task,$_REQUEST);
    }
    
    // Task per cestinare un alloggio
    public function Task_TrashSicar($task)
    {
        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR))
        {
            
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per cestinare l'elemento.", false);

            return false;
        }

        return $this->Task_GenericTrashObject($task,$_REQUEST);
    }
    
    // Task per ripristinare un alloggio
    public function Task_ResumeSicar($task)
    {
        return $this->Task_GenericResumeObject($task,$_REQUEST);
    }
    
    // Task per riassegnare un alloggio
    public function Task_ReassignSicar($task)
    {
        return $this->Task_GenericReassignObject($task,$_REQUEST);
    }

    // Task per la restituzione della finestra di dialogo di cestinazione alloggio
    public function Task_GetSicarTrashDlg($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per cestinare gli alloggi");
            return false;
        }

        if ($_REQUEST['ids'] != "") {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericObjectTrashDlg($_REQUEST, "TrashSicar"), true);
            return true;
        } else {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.", false);
            return false;
        }
    }

    //Task publish organismo
    public function Task_GetSicarPublishDlg($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per pubblicare elementi.");
            return false;
        }
        
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericPublishObjectDlg($_REQUEST,"PublishSicar"),true);
            return true;
        }    
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.",false);
            return false;
        }
    }

    //Task Riassegna
    public function Task_GetSicarReassignDlg($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per riassegnare elementi.");
            return false;
        }

        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericReassignObjectDlg($_REQUEST,"ReassignSicar"),true);
            return true;
        }    
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.",false);
            return false;
        }
    }

    // Task per la restituzione della finestra di dialogo di ripristino alloggio
    public function Task_GetSicarResumeDlg($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare gli alloggi");
            return false;
        }

        if ($_REQUEST['ids'] != "") {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericResumeObjectDlg($_REQUEST, "ResumeSicar"), true);
            return true;
        } else {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.", false);
            return false;
        }
    }

    //Task dialogo elimina
    public function Task_GetSicarDeleteDlg($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per eliminare elementi.");
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent( $this->Template_GetGenericObjectDeleteDlg($_REQUEST,"DeleteSicar"),true);
            return true;
        }    
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.",false);
            return false;
        }
    }

     //Template detail (da specializzare)
    public function TemplateSection_Detail($params)
    {
        //Gestione dei tab
        //$id=static::AA_UI_PREFIX."_Detail_Generale_Tab_".$params['id'];
        //$params['DetailOptionTab']=array(array("id"=>$id, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateGecopDettaglio_Generale_Tab"));
        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $params['readonly']=true;
        
        $params['MultiviewEventHandlers']=array("onViewChange"=>array("handler"=>"onDetailViewChange"));

        //$params['disable_SaveAsPdf']=true;
        //$params['disable_SaveAsCsv']=true;
        //$params['disable_trash']=true;
        //$params['disable_public_trash']=true;

        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $params['disable_MenuAzioni']=true;
        
        $detail = $this->TemplateGenericSection_Detail($params);

        return $detail;
    }

    // Template dettaglio generale alloggio
    public function TemplateSicarDettaglio_Generale_Tab($object = null)
    {
        $id = static::AA_UI_PREFIX . "_" . static::AA_ID_SECTION_DETAIL . "_" . static::AA_UI_DETAIL_GENERALE_BOX;
        if (!($object instanceof AA_SicarAlloggio)) {
            return new AA_JSON_Template_Template($id, array("template" => "Dati non validi"));
        }

        $rows_fixed_height=50;
        $canModify = (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0 && $this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR));

        $toolbar = new AA_JSON_Template_Toolbar("", array("height" => 32, "type" => "clean", "borderless" => true));
        $toolbar->AddElement(new AA_JSON_Template_Generic("", array("width" => 120)));
        $toolbar->AddElement(new AA_JSON_Template_Generic());

        $layout = $this->TemplateGenericDettaglio_Header_Generale_Tab($object, $id, $toolbar, $canModify);

        // stato conservazione
        $value = "<span class='AA_Label AA_Label_LightYellow'>" . $object->GetStatoConservazione(true) . "</span>";
        $stato_conservazione = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Stato conservazione:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //tipologia utilizzo
        $value = "<span class='AA_Label AA_Label_LightYellow'>" . $object->GetTipologiaUtilizzo() . "</span>";
        $tipologia_utilizzo = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Tipo utilizzo:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //ultima ristrutturazione
        $value = "<span>" . $object->GetAnnoRistrutturazione() . "</span>";
        $ultima_ristrutturazione = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Anno ultima ristrutturazione:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        // superficie
        $superficie="";
        if(!empty($object->GetSuperficieNonResidenziale())) $superficie.="<span>Non residenziale: ".$object->GetSuperficieNonResidenziale()."</span>";
        else $superficie.="<span>Non residenziale: n.d.</span>";
        if(!empty($superficie)) $superficie.=" - "; 
        if(!empty($object->GetSuperficieParcheggi())) $superficie.=" <span>parcheggi: ".$object->GetSuperficieParcheggi()."</span>";
        else $superficie.=" <span>parcheggi: n.d.</span>";
        if(!empty($object->GetSuperficieUtileAbitabile())) 
        {
            if(!empty($superficie)) $superficie.=" - "; 
            $superficie.=" <span>abitabile: ".$object->GetSuperficieUtileAbitabile()."</span>";
        }
        else 
        {
            if(!empty($superficie)) $superficie.=" - "; 
            $superficie.=" <span>abitabile: n.d.</span>";
        }

        $value =  $superficie;
        $superficie = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 2,
            "data" => array("title" => "Superfici (mq):", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //piano
        if(!empty($object->GetPiano())) $value = "<span>" . $object->GetPiano() . "</span>";
        else $value = "<span>terra</span>";
        $piano = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Piano:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //vani abitabili
        if(!empty($object->GetVaniAbitabili())) $value = "<span>" . $object->GetVaniAbitabili() . "</span>";
        else $value = "<span>n.d.</span>";
        $piano = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Vani abitabili:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //ascensore
        $val="No";
        $color="LightRed";
        if(!empty($object->GetAscensore())) 
        {
            $val="Si";
            $color="LightGreen";
        }
        $value = "<span class='AA_Label AA_Label_$color'>" . $val. "</span>";
        $ascensore = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Servito da ascensore:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //condominio misto
        $val="No";
        $color="LightRed";
        if(!empty($object->GetCondominioMisto()))
        {
            $val="Si";
            $color="LightGreen";
        }
        $value = "<span class='AA_Label AA_Label_$color'>" . $val. "</span>";
        $condominio = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Condominio misto:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));
        
        //disabile
        $val=$object->GetFruibileDis();
        $color="LightGreen";
        
        $value = "<span class='AA_Label AA_Label_$color'>" . $val. "</span>";
        $fruibile_dis = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Fruibile da disabile:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));
        
        //prima riga
        $riga=new AA_JSON_Template_Layout("",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($stato_conservazione);
        $riga->AddCol($tipologia_utilizzo);
        $riga->AddCol($ultima_ristrutturazione);
        $riga->AddCol($superficie);
        $riga->AddCol($piano);
        $riga->AddCol($condominio);
        $riga->AddCol($ascensore);
        $riga->AddCol($fruibile_dis);
        $layout->AddRow($riga);
        
        //seconda riga
        $riga=new AA_JSON_Template_Layout("",array("gravity"=>1,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        //$layout_riga=new AA_JSON_Template_Layout("",array("gravity"=>1,"type"=>"clean"));
        
        //immobile
        $immobile_obj=$object->GetImmobile();
        if($immobile_obj) 
        {
            $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailImmobileDlg", params: [{id:"'.$immobile_obj->GetProp("id").'"}]},"'.$this->id.'")';
            $immobile_data=array("title"=>"Immobile:","value"=>"<a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'>".$immobile_obj->GetDescrizione()."</a><br>".$immobile_obj->GetIndirizzo()." (".$immobile_obj->GetComune().") <a href='https://www.google.com/maps/search/?api=1&query=".$immobile_obj->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a><br><i>".$immobile_obj->GetTipologia()."</i>");
        }
        else 
        {
            $detail="";
            $immobile_data=array("title"=>"Immobile:","value"=>"Immobile non definito");
        }
        $immobile=new AA_JSON_Template_Template("",array(
            "maxHeight"=>100,
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>$immobile_data
        ));
        $riga->addCol($immobile);

        //Gestore
        $gestore_obj=$object->GetGestore();
        if($gestore_obj) 
        {
            $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailEnteGestoreDlg", params: [{id:"'.$gestore_obj->GetProp("id").'"}]},"'.$this->id.'")';
            $detail_gestore=$gestore_obj->GetDenominazione();
            $gestore=new AA_JSON_Template_Template("",array(
                "maxHeight"=>100,
                "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "data"=>array("title"=>"Ente gestore:","value"=>"<a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'>".$detail_gestore."</a><br>dal ".$object->GetGestioneDaL()."</br>")
            ));
        }
        else 
        {
            $detail="";
            $detail_gestore="Ente gestore non definito";
            $gestore=new AA_JSON_Template_Template("",array(
                "maxHeight"=>100,
                "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "data"=>array("title"=>"Ente gestore:","value"=>$detail_gestore)
            ));

        }

        //Proprietario
        $proprietario_obj=$object->GetProprietario();
        if($proprietario_obj) 
        {
            $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailEnteProprietarioDlg", params: [{id:"'.$proprietario_obj->GetProp("id").'"}]},"'.$this->id.'")';
            $detail_text=$proprietario_obj->GetDenominazione();
            $proprietario=new AA_JSON_Template_Template("",array(
                "maxHeight"=>100,
                "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "data"=>array("title"=>"Ente proprietario:","value"=>"<a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'>".$detail_text."</a><br>dal ".$object->GetProprietaDaL()."</br>")
            ));
        }
        else 
        {
            $detail_text="Ente proprietario non definito";
            $proprietario=new AA_JSON_Template_Template("",array(
                "maxHeight"=>100,
                "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "data"=>array("title"=>"Ente proprietario:","value"=>$detail_text)
            ));

        }
       
        $riga->addCol($gestore);
        $riga->addCol($proprietario);
        $layout->AddRow($riga);

        $riga=new AA_JSON_Template_Layout("",array("gravity"=>1,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        
        //note
        $value = $object->GetNote();
        $note=new AA_JSON_Template_Template("",array(
            "height"=>100,
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Note:","value"=>$value)
        ));
        $riga->addCol($note);
        $layout->AddRow($riga);

        $riga=new AA_JSON_Template_Layout("",array("type"=>"clean","css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        //occupazione
        $riga->AddCol($this->TemplateDettaglio_Occupazione($object,$id."_Occupazione",$canModify));
        $riga->AddCol($this->TemplateDettaglio_Interventi($object,$id."_Interventi",$canModify));
        $layout->AddRow($riga);
        
        return $layout;
    }

    //Template dettaglio occupazione
    public function TemplateDettaglio_Occupazione($object=null,$id="", $canModify=false)
    {
        #documenti----------------------------------
        $curId=$id."_Layout_Occupazione";
        $provvedimenti=new AA_JSON_Template_Layout($curId,array("type"=>"clean","gravity"=>4,"css"=>array("border-left"=>"1px solid gray !important;","border-top"=>"1px solid gray !important;")));

        $toolbar=new AA_JSON_Template_Toolbar($curId."_Toolbar_occupazione",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"<span style='color:#003380'>Stato occupazione</span>", "align"=>"center")));

        if($canModify)
        {
            //Pulsante di aggiunta documento
            $add_documento_btn=new AA_JSON_Template_Generic("",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-file-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi un nuovo stato occupazione",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewStatoOccupazioneAlloggioDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));

            $toolbar->AddElement($add_documento_btn);
        }
        else 
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        }

        $provvedimenti->AddRow($toolbar);

        $occupazioni=$object->GetOccupazione();
        $occupazione_data=[];
        $tipologia_occupazione_desc=AA_Sicar_Const::GetListaTipologieOccupazione(true);
        foreach($occupazioni as $dal=>$curOccupazione)
        {
            $nucleo_desc="n.d.";
            if($curOccupazione['stato'] >= 1)    
            {
                
                $nucleo=new AA_SicarNucleo();
                if($nucleo->Load($curOccupazione['occupazione_id_nucleo']))
                {
                    $nucleo_desc=$nucleo->GetDescrizione();
                }
            }

            if($curOccupazione['stato']>=1) 
            {
                $tipo_occupazione=$tipologia_occupazione_desc[$curOccupazione['occupazione_tipo']];
            }
            else $tipo_occupazione="libero";

            $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailStatoOccupazioneAlloggioDlg", params: [{id:"'.$object->GetId().'"},{dal:"'.$dal.'"}]},"'.$this->id.'")';
            $detail_icon="mdi mdi-eye";
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteStatoOccupazioneAlloggioDlg", params: [{id:"'.$object->GetId().'"},{dal:"'.$dal.'"}]},"'.$this->id.'")';
            $trash_icon="mdi mdi-trash-can";
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyStatoOccupazioneAlloggioDlg", params: [{id:"'.$object->GetId().'"},{dal:"'.$dal.'"}]},"'.$this->id.'")';
            $modify_icon="mdi mdi-pencil";
            $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
            
            $occupazione_data[]=array(
                "id"=>$dal,
                "dal"=>$dal,
                "tipologia_desc"=>$tipo_occupazione,
                "nucleo"=>$curOccupazione['occupazione_id_nucleo'],
                "nucleo_desc"=>$nucleo_desc,
                "canone"=>$curOccupazione['occupazione_tipo_canone'],
                "note"=>$curOccupazione['note'],
                "ops"=>$ops
            );
        }

        if(!$canModify) $template=new AA_GenericDatatableTemplate($id,"",3,array("type"=>"clean"),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"",4,array("type"=>"clean"),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(false);
        //$template->SetHeaderHeight(38);
        $template->EnableAddNew(false);
        $template->SetColumnHeaderInfo(0,"tipologia_desc","<div style='text-align: center'>Tipo</div>",160,"selectFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(1,"dal","<div style='text-align: center'>Dal</div>",100,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(2,"nucleo_desc","<div style='text-align: center'>Nucleo</div>","fillspace","textFilter","text","GenericAutosizedRowTable");
        //$template->SetColumnHeaderInfo(3,"canone","<div style='text-align: center'>Canone</div>",100,"textFilter","text","GenericAutosizedRowTable");
        //$template->SetColumnHeaderInfo(4,"note","<div style='text-align: center'>Note</div>",90,"","","GenericAutosizedRowTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if($canModify) $template->SetColumnHeaderInfo(3,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"GenericAutosizedRowTable");

        $template->SetData($occupazione_data);
        #--------------------------------------
        
        $provvedimenti->AddRow($template);
        return $provvedimenti;
    }

    //Template dettaglio interventi
    public function TemplateDettaglio_Interventi($object=null,$id="", $canModify=false)
    {
        #documenti----------------------------------
        $curId=$id."_Layout_Interventi";
        $provvedimenti=new AA_JSON_Template_Layout($curId,array("type"=>"clean","gravity"=>4,"css"=>array("border-left"=>"1px solid gray !important;","border-top"=>"1px solid gray !important;")));

        $toolbar=new AA_JSON_Template_Toolbar($curId."_Toolbar_interventi",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"<span style='color:#003380'>Lista interventi</span>", "align"=>"center")));

        if($canModify)
        {
            //Pulsante di aggiunta documento
            $add_documento_btn=new AA_JSON_Template_Generic("",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-file-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi un intervento",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewStatoInterventiAlloggioDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));

            $toolbar->AddElement($add_documento_btn);
        }
        else 
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        }

        $provvedimenti->AddRow($toolbar);

        $interventi=$object->GetInterventi();
        $interventi_data=[];
        $tipologia_intervento_desc=AA_Sicar_Const::GetListaTipologieIntervento(true);
        $stato_lavori_desc=AA_Sicar_Const::GetListaStatoLavori(true);

        foreach($interventi as $id_intervento=>$curIntervento)
        {
            $finanziamento_desc="n.d.";
            if($curIntervento['id_finanziamento'] > 0)    
            {
                $finanziamento=new AA_SicarFinanziamento();
                if($finanziamento->Load($curIntervento['id_finanziamento']))
                {
                    $finanziamento_desc=$finanziamento->GetDenominazione();
                }
            }

            $richiesta_finanziamento_desc="n.d.";
            if($curIntervento['id_richiesta_finanziamento'] > 0)    
            {
                $richiesta_finanziamento=new AA_SicarRichiestaFinanziamento();
                if($richiesta_finanziamento->Load($curIntervento['id_richiesta_finanziamento']))
                {
                    $richiesta_finanziamento_desc=$richiesta_finanziamento->GetDenominazione();
                }
            }

            $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailStatoInterventiAlloggioDlg", params: [{id:"'.$object->GetId().'"},{id_intervento:"'.$id_intervento.'"}]},"'.$this->id.'")';
            $detail_icon="mdi mdi-eye";
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteStatoInterventiAlloggioDlg", params: [{id:"'.$object->GetId().'"},{id_intervento:"'.$id_intervento.'"}]},"'.$this->id.'")';
            $trash_icon="mdi mdi-trash-can";
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyStatoInterventiAlloggioDlg", params: [{id:"'.$object->GetId().'"},{id_intervento:"'.$id_intervento.'"}]},"'.$this->id.'")';
            $modify_icon="mdi mdi-pencil";
            $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
            $tipologia_intervento="Nessun intervento richiesto";
            $stato_lavori="n.d.";
            if(!empty($curIntervento['tipologia'])) 
            {
                $tipologia_intervento=$tipologia_intervento_desc[$curIntervento['tipologia']];
                $stato_lavori=$stato_lavori_desc[$curIntervento['stato_lavori']];
            }
        
            $intervento_data[]=array(
                "id"=>$id_intervento,
                "data_dal"=>$curIntervento['data_dal'],
                "data_al"=>$curIntervento['data_al'],
                "tipologia_desc"=>$tipologia_intervento,
                "stato_lavori"=>$stato_lavori,
                "importo_stimato"=>AA_Utils::number_format($curIntervento['importo_stimato'],2,",","."),
                "cup"=>$curIntervento['cup'],
                "ops"=>$ops
            );
        }

        if(!$canModify) $template=new AA_GenericDatatableTemplate($id,"",6,array("type"=>"clean"),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"",7,array("type"=>"clean"),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(false);
        $template->SetHeaderHeight(38);
        $template->EnableAddNew(false);
        $template->SetColumnHeaderInfo(0,"tipologia_desc","<div style='text-align: center'>Tipologia</div>",230,"selectFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(1,"data_dal","<div style='text-align: center'>Dal</div>",100,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(2,"data_al","<div style='text-align: center'>Al</div>",100,"textFilter","int","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(3,"importo_stimato","<div style='text-align: center'>Costo stimato</div>",120,"textFilter","int","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(4,"stato_lavori","<div style='text-align: center'>Stato lavori</div>",130,"selectFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(5,"cup","<div style='text-align: center'>CUP</div>","fillspace","textFilter","text","GenericAutosizedRowTable");
        //$template->SetColumnHeaderInfo(6,"note","<div style='text-align: center'>Note</div>","fillspace","textFilter","text","GenericAutosizedRowTable_left");
        //$template->SetColumnHeaderInfo(4,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if($canModify) $template->SetColumnHeaderInfo(6,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"GenericAutosizedRowTable");

        $template->SetData($intervento_data);
        #--------------------------------------
        
        $provvedimenti->AddRow($template);
        return $provvedimenti;
    }

    //Template section immobili
    public function TemplateSection_Immobili($params=array())
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_IMMOBILI;
        $canModify=false;

        #immobili----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //$layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        //filtrare in base al comune dell'operatore comunale
        //to do
        //-------------------------------------------------

        $immobili=AA_SicarImmobile::Search($params);
        $data=[];
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) 
        {
            $select_icon="mdi mdi-cursor-pointer";
        }
        else
        {
            $ops="";
        }

        foreach($immobili as $curImmobile)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if($canModify)
            {
                $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailImmobileDlg", params: [{id:"'.$curImmobile->GetProp("id").'"}]},"'.$this->id.'")';
                $detail_icon="mdi mdi-eye";
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteImmobileDlg", params: [{id:"'.$curImmobile->GetProp("id").'"}]},"'.$this->id.'")';
                $trash_icon="mdi mdi-trash-can";
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyImmobileDlg", params: [{id:"'.$curImmobile->GetProp("id").'"}]},"'.$this->id.'")';
                $modify_icon="mdi mdi-pencil";

                //alloggi
                //$alloggi_list='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarLiastaAlloggiImmobileDlg", params: [{id:"'.$curImmobile->GetProp("id").'"}]},"'.$this->id.'")';
                $alloggi_list='try{module=AA_MainApp.curModule; if(module.isValid()) {console.log(module); module.setRuntimeValue("' . static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX . '","filter_data", '.json_encode(array("immobile"=>$curImmobile->GetProp("id"),"immobile_desc"=>$curImmobile->GetDisplayName())).');} AA_MainApp.curModule.setCurrentSection("'.static::AA_ID_SECTION_PUBBLICATE.'")}catch(msg){console.error(msg)}';
                $alloggi_list_icon="mdi mdi-home-search";

                $gestore=$curImmobile->GetGestore();
                $gestore_desc="Nessuno";
                if($gestore instanceof AA_SicarEnte)
                {
                    $gestore_desc=$gestore->GetDenominazione();
                }

                $num_alloggi=$curImmobile->GetNumeroAlloggiTot();
                $alloggi_censiti=sizeof($curImmobile->GetAlloggi());
                $condominio_misto="No";
                if($curImmobile->IsCondominioMisto()) $condominio_misto="Si";
                if($alloggi_censiti>0) $num_alloggi.=" (".sizeof($curImmobile->GetAlloggi())." <a class='AA_DataTable_Ops_Button' title='Visualizza gli alloggi censiti associati all&apos;immobile' onClick='".$alloggi_list."'><span class='".$alloggi_list_icon."'></span></a>)";
                else $num_alloggi.=" (0)";

                //interventi
                $interventi_list='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarInterventiImmobileDlg", params: [{id_immobile:"'.$curImmobile->GetProp("id").'"}]},"'.$this->id.'")';
                $interventi_list_icon="mdi mdi-table-search";
                $num_interventi=sizeof($curImmobile->GetInterventi())." <a class='AA_DataTable_Ops_Button' title='Visualizza gli interventi associati all&apos;immobile' onClick='".$interventi_list."'><span class='".$interventi_list_icon."'></span></a>";
                
                $localizzazione="<b>".$curImmobile->GetDescrizione()."</b> - ".$curImmobile->GetIndirizzo()." (".AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curImmobile->GetProp("comune")).")"; 
                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
                $data[]=array("id"=>$curImmobile->GetProp("id"),"localizzazione"=>$localizzazione,"condominio_misto"=>$condominio_misto,"alloggi"=>$num_alloggi,"interventi"=>$num_interventi,"descrizione"=>$curImmobile->GetDescrizione(),"indirizzo"=>$curImmobile->GetIndirizzo()."(".AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curImmobile->GetProp("comune")).")<a href='https://www.google.com/maps/search/?api=1&query=".$curImmobile->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a>","gestore"=>$gestore_desc,"ops"=>$ops);
            }
            else
            {
                $gestore=$curImmobile->GetGestore();
                $gestore_desc="Nessuno";
                if($gestore instanceof AA_SicarEnte)
                {
                    $gestore_desc=$gestore->GetDenominazione();
                }
                $num_alloggi=$curImmobile->GetNumeroAlloggiTot();
                $alloggi_censiti=sizeof($curImmobile->GetAlloggi());
                if($alloggi_censiti>0) $num_alloggi.=" (".sizeof($curImmobile->GetAlloggi())." <a class='AA_DataTable_Ops_Button' title='Visualizza gli alloggi censiti associati all&apos;immobile' onClick='".$alloggi_list."'><span class='".$alloggi_list_icon."'></span></a>)";
                else $num_alloggi.=" (0)";
                
                //interventi
                $interventi_list='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarInterventiImmobileDlg", params: [{id_immobile:"'.$curImmobile->GetProp("id").'"}]},"'.$this->id.'")';
                $interventi_list_icon="mdi mdi-table-search";
                $num_interventi=sizeof($curImmobile->GetInterventi())." - <a class='AA_DataTable_Ops_Button' title='Visualizza gli interventi associati all&apos;immobile' onClick='".$interventi_list."'><span class='".$interventi_list_icon."'></span></a>";
                
                $condominio_misto="No";
                if($curImmobile->IsCondominioMisto()) $condominio_misto="Si";
                $localizzazione=$curImmobile->GetDescrizione()." - ".$curImmobile->GetIndirizzo()."(".AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curImmobile->GetProp("comune")).")"; 
                $data[]=array("id"=>$curImmobile->GetProp("id"),"localizzazione"=>$localizzazione,"condominio_misto"=>$condominio_misto,"alloggi"=>$num_alloggi,"interventi"=>$num_interventi,"descrizione"=>$curImmobile->GetDescrizione(),"indirizzo"=>$curImmobile->GetIndirizzo()."(".AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curImmobile->GetProp("comune")).")<a href='https://www.google.com/maps/search/?api=1&query=".$curImmobile->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a>","gestore"=>$gestore_desc);
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id,"",3,array("type"=>"clean","name"=>static::AA_UI_SECTION_IMMOBILI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"",4,array("type"=>"clean","name"=>static::AA_UI_SECTION_IMMOBILI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(true);
        $template->SetHeaderHeight(38);

        
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewImmobileDlg");
            $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1)));
        }

        $template->SetColumnHeaderInfo(0,"localizzazione","<div style='text-align: center'>Localizzazione</div>","fillspace","textFilter","text","ImmobiliTable_left");
        //$template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo</div>","fillspace","textFilter","text","ImmobiliTable_left");
        //$template->SetColumnHeaderInfo(1,"condominio_misto","<div style='text-align: center'>Condominio Misto</div>",150,"selectFilter","text","ImmobiliTable");
        $template->SetColumnHeaderInfo(1,"alloggi","<div style='text-align: center'>Alloggi ERP</div>",130,null,null,"ImmobiliTable");
        $template->SetColumnHeaderInfo(2,"interventi","<div style='text-align: center'>Interventi</div>",130,null,null,"ImmobiliTable");
        //$template->SetColumnHeaderInfo(4,"gestore","<div style='text-align: center'>Gestore</div>",260,"textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(3,"ops","<div style='text-align: center'>Operazioni</div>",130,null,null,"ImmobiliTable");

        $template->SetData($data);

        //$layout->AddRow($template);

        return $template;
    }

    //Template section enti
    public function TemplateSection_Enti($params=array())
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_ENTI_BOX;
        $canModify=false;

        #enti----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //$layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $objs=AA_SicarEnte::Search($params);
        $data=[];

        $ops="";
        
        foreach($objs as $curObj)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if($canModify)
            {
                $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailEnteDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $detail_icon="mdi mdi-eye";
                $operatori='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarOperatoriEnteDlg", params: [{id_ente:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $operatori_icon="mdi mdi-account-multiple";
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteEnteDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $trash_icon="mdi mdi-trash-can";
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyEnteDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $modify_icon="mdi mdi-pencil";

                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Operatori' onClick='".$operatori."'><span class='mdi ".$operatori_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
                $data[]=array("id"=>$curObj->GetProp("id"),"denominazione"=>$curObj->GetDenominazione(),"indirizzo"=>$curObj->GetIndirizzo()."<a href='https://www.google.com/maps/search/?api=1&query=".$curObj->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a>","contatti"=>$curObj->GetContatti(false),"note"=>$curObj->GetNote(),"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curObj->GetProp("id"),"denominazione"=>$curObj->GetDenominazione(),"indirizzo"=>$curObj->GetIndirizzo()."<a href='https://www.google.com/maps/search/?api=1&query=".$curObj->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a>","contatti"=>$curObj->GetContatti(),"note"=>$curObj->GetNote());
            }
        }

        $nCols=4;
        if($canModify) $nCols=5;
        $template=new AA_GenericDatatableTemplate($id,"",$nCols,array("type"=>"clean","name"=>static::AA_UI_SECTION_ENTI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(true);
        $template->SetHeaderHeight(38);

        
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewEnteDlg");
            $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1)));
        }
        else
        {
            $template->EnableHeader(false);
        }

        $template->SetColumnHeaderInfo(0,"denominazione","<div style='text-align: center'>Denominazione</div>",250,"textFilter","int","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo</div>",400,"textFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(2,"contatti","<div style='text-align: center'>Contatti</div>","fillspace","textFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(3,"note","<div style='text-align: center'>Note</div>","fillspace","textFilter","text","GenericAutosizedRowTabl_left");
        if($canModify) 
        {
            $template->SetColumnHeaderInfo(4,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"GenericAutosizedRowTable");
        }

        $template->SetData($data);

        //$layout->AddRow($template);

        return $template;
    }

    //Template section nuclei
    public function TemplateSection_Nuclei($params=array())
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_NUCLEI_BOX;
        $canModify=false;

        #immobili----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //$layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $objs=AA_SicarNucleo::Search($params);
        $data=[];

        $ops="";
        
        foreach($objs as $curObj)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if($canModify)
            {
                $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailNucleoDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $detail_icon="mdi mdi-eye";
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteNucleoDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $trash_icon="mdi mdi-trash-can";
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyNucleoDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $modify_icon="mdi mdi-pencil";

                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
                
                $AlloggioAttuale=$curObj->GetAlloggioAttuale(true);
                if($AlloggioAttuale)
                {
                    $last_stato_assegnazione="<strong>".$AlloggioAttuale->GetDescrizione()."</strong>";
                }
                else
                {
                    $last_stato_assegnazione="Senza alloggio assegnato";
                }

                $indirizzo=$curObj->GetIndirizzo();
                if(!empty($curObj->GetProp("comune")))
                {
                    $indirizzo.=" (".$curObj->GetComune().")";
                }

                $data[]=array(
                    "id"=>$curObj->GetProp("id"),
                    "descrizione"=>$curObj->GetDescrizione(),
                    "indirizzo"=>$indirizzo,
                    "stato_assegnazione"=>$last_stato_assegnazione,
                    "componenti"=>sizeof($curObj->GetComponenti()),
                    "isee"=>$curObj->GetIseePreview(),
                    "cf"=>$curObj->GetCf(),
                    "ops"=>$ops
                );
            }
            else
            {
                $indirizzo=$curObj->GetIndirizzo();
                if(!empty($curObj->GetProp("comune")))
                {
                    $indirizzo.=" (".$curObj->GetComune().")";
                }

                $data[]=array(
                    "id"=>$curObj->GetProp("id"),
                    "descrizione"=>$curObj->GetDescrizione(),
                    "indirizzo"=>$indirizzo,
                    "stato_assegnazione"=>$last_stato_assegnazione,
                    "componenti"=>sizeof($curObj->GetComponenti()),
                    "isee"=>$curObj->GetIseePreview(),
                    "cf"=>$curObj->GetCf()
                );
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id,"",6,array("type"=>"clean","name"=>static::AA_UI_SECTION_NUCLEI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"",7,array("type"=>"clean","name"=>static::AA_UI_SECTION_NUCLEI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(true);
        $template->SetHeaderHeight(38);

        
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewNucleoDlg");
            $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1)));
        }

        $template->SetColumnHeaderInfo(0,"descrizione","<div style='text-align: center'>Descrizione</div>",250,"textFilter","int","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo di residenza</div>","fillspace","textFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(2,"cf","<div style='text-align: center'>Codice fiscale</div>",250,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(3,"stato_assegnazione","<div style='text-align: center'>Alloggio assegnato</div>",250,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(4,"componenti","<div style='text-align: center'>Componenti</div>",120,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(5,"isee","<div style='text-align: center'>ISEE</div>",120,"textFilter","text","GenericAutosizedRowTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(6,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"GenericAutosizedRowTable");

        $template->SetData($data);

        return $template;
    }

    //Template section finanziamenti
    public function TemplateSection_Finanziamenti($params=array())
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_FINANZIAMENTI_BOX;
        $canModify=false;

        #immobili----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //$layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $objs=AA_SicarFinanziamento::Search($params);
        $data=[];

        $ops="";
        
        foreach($objs as $curObj)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if($canModify)
            {
                $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailFinanziamentoDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $detail_icon="mdi mdi-eye";
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteFinaziamentoDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $trash_icon="mdi mdi-trash-can";
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyFinanziamentoDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $modify_icon="mdi mdi-pencil";

                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
                $data[]=array("id"=>$curObj->GetProp("id"),"descrizione"=>$curObj->GetDescrizione(),"indirizzo"=>$curObj->GetIndirizzo()."<a href='https://www.google.com/maps/search/?api=1&query=".$curObj->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a>","comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curObj->GetProp("comune")),"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curObj->GetProp("id"),"descrizione"=>$curObj->GetDescrizione(),"indirizzo"=>$curObj->GetIndirizzo(),"comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curObj->GetProp("comune")));
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id,"",3,array("type"=>"clean","name"=>static::AA_UI_SECTION_FINANZIAMENTI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"",4,array("type"=>"clean","name"=>static::AA_UI_SECTION_FINANZIAMENTI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(true);
        $template->SetHeaderHeight(38);

        
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewFinanziamentoDlg");
            $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1)));
        }

        $template->SetColumnHeaderInfo(0,"descrizione","<div style='text-align: center'>Descrizione</div>",250,"textFilter","int","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo</div>","fillspace","textFilter","text","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(2,"comune","<div style='text-align: center'>Comune</div>",250,"textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(3,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"ImmobiliTable");

        $template->SetData($data);

        //$layout->AddRow($template);

        return $template;
    }

    //Template section graduatorie
    public function TemplateSection_Graduatorie($params=array())
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GRADUATORIE_BOX;
        $canModify=false;

        #immobili----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //$layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $objs=AA_SicarGraduatoria::Search($params);
        $data=[];

        $ops="";
        
        foreach($objs as $curObj)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if($canModify)
            {
                $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailGraduatoriaDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $detail_icon="mdi mdi-eye";
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteGraduatoriaDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $trash_icon="mdi mdi-trash-can";
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifygraduatoriaDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $modify_icon="mdi mdi-pencil";

                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
                $data[]=array("id"=>$curObj->GetProp("id"),"descrizione"=>$curObj->GetDescrizione(),"indirizzo"=>$curObj->GetIndirizzo()."<a href='https://www.google.com/maps/search/?api=1&query=".$curObj->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a>","comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curObj->GetProp("comune")),"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curObj->GetProp("id"),"descrizione"=>$curObj->GetDescrizione(),"indirizzo"=>$curObj->GetIndirizzo(),"comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curObj->GetProp("comune")));
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id,"",3,array("type"=>"clean","name"=>static::AA_UI_SECTION_GRADUATORIE_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"",4,array("type"=>"clean","name"=>static::AA_UI_SECTION_GRADUATORIE_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(true);
        $template->SetHeaderHeight(38);

        
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewNucleoDlg");
            $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1)));
        }

        $template->SetColumnHeaderInfo(0,"descrizione","<div style='text-align: center'>Descrizione</div>",250,"textFilter","int","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo</div>","fillspace","textFilter","text","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(2,"comune","<div style='text-align: center'>Comune</div>",250,"textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(3,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"ImmobiliTable");

        $template->SetData($data);

        //$layout->AddRow($template);

        return $template;
    }

    // Metodi di utilità
    private function GetTipologiaDesc($tipologia_id)
    {
        $tipologie = AA_Sicar_Const::GetListaTipologie();
        foreach ($tipologie as $row) {
            if ($row['id'] == $tipologia_id) {
                return $row['value'];
            }
        }
        return "Non specificato";
    }
    
    private function GetStatusDesc($status)
    {
        if (($status & AA_Const::AA_STATUS_PUBBLICATA) > 0) {
            return "Pubblicato";
        } elseif (($status & AA_Const::AA_STATUS_BOZZA) > 0) {
            return "Bozza";
        } elseif (($status & AA_Const::AA_STATUS_CESTINATA) > 0) {
            return "Cestinato";
        } elseif (($status & AA_Const::AA_STATUS_REVISIONATA) > 0) {
            return "In revisione";
        }
        
        return "Non specificato";
    }
}

/**
 * Classe AA_SicarAlloggio - rappresenta un alloggio associato ad un immobile
 */
