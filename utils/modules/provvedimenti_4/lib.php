<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "config.php";
include_once "system_lib.php";

#Costanti
Class AA_Provvedimenti_Const extends AA_Const
{
    const AA_USER_FLAG_PROVVEDIMENTI="provvedimenti";
}

#Classe oggetto provvedimenti
Class AA_Provvedimenti extends AA_Object_V2
{
    //tabella dati db
    const AA_DBTABLE_DATA="aa_provvedimenti_data";

    //Funzione di cancellazione
    protected function DeleteData($idData = 0, $user = null)
    {
        if(!$this->IsValid() || $this->IsReadOnly() || $idData == 0) return false;

        if($idData != $this->nId_Data && $idData != $this->nId_Data_Rev) return false;

        return parent::DeleteData($idData,$user);
    }

    //Funzione di clonazione dei dati
    protected function CloneData($idData = 0, $user = null)
    {
        if(!$this->IsValid() || $this->IsReadOnly()) return 0;
        
        $newIdData=parent::CloneData($idData,$user);

        return $newIdData;
    }

    //Costruttore
    public function __construct($id=0, $user=null)
    {
        //data table
        $this->SetDbDataTable(static::AA_DBTABLE_DATA);

        //Db data binding
        $this->SetBind("Estremi","estremi_atto");
        $this->SetBind("AnnoRiferimento","anno_rif");
        $this->SetBind("Tipo","tipo");
        $this->SetBind("Contraente","contraente");
        $this->SetBind("Modalita","modalita");

        //Valori iniziali
        $this->SetProp("IdData",0);
        $this->SetProp("Modalita",0);

        //chiama il costruttore genitore
        parent::__construct($id,$user,false);

        //Carica i dati dell'oggetto
        if($this->bValid && $this->nId > 0)
        {
            if(!$this->LoadData($user))
            {
                $this->bValid=false;
            }
        }
    }

    //funzione di ricerca
    static public function Search($params=array(),$user=null)
    {
        //Verifica utente
        if($user instanceof AA_User)
        {
            if(!$user->isCurrentUser())
            {
                $user=AA_User::GetCurrentUser();
            }
        }
        else $user=AA_User::GetCurrentUser();

        //---------local checks-------------
        $params['class']="AA_Provvedimenti";
        //----------------------------------

        return parent::Search($params,$user);
    }

    //Funzione di verifica dei permessi
    public function GetUserCaps($user=null)
    {
        //Verifica utente
        if($user instanceof AA_User)
        {
            if(!$user->isCurrentUser())
            {
               $user=AA_User::GetCurrentUser();
            }
        }
        else $user=AA_User::GetCurrentUser();

        $perms=parent::GetUserCaps($user);

        //------------local checks---------------

        //Se l'utente non ha il flag può al massimo visualizzare la scheda
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && !$user->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $perms = AA_Const::AA_PERMS_READ;
        }
        //---------------------------------------

        //Se l'utente ha il flag e può modificare la scheda allora può fare tutto
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && $user->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $perms = AA_Const::AA_PERMS_ALL;
        }
        //---------------------------------------

        return $perms;
    }

    static public function AddNew($object=null,$user=null,$bSaveData=true)
    {
        //Verifica utente
        if($user instanceof AA_User)
        {
            if(!$user->isCurrentUser())
            {
               $user=AA_User::GetCurrentUser();
            }
        }
        else $user=AA_User::GetCurrentUser();

        //-------------local checks---------------------
        $bStandardCheck=false; //disable standard checks
        $bSaveData=true; //enable save data

        //Chi non ha il flag non può inserire nuovi elementi
        if(!$user->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            AA_Log::Log(__METHOD__." - L'utente corrente: ".$user->GetUserName()." non ha i permessi per inserire nuovi elementi.",100);
            return false;
        }

        //Verifica validità oggetto
        if(!($object instanceof AA_Provvedimenti))
        {
            AA_Log::Log(__METHOD__." - Errore: oggetto non valido (".print_r($object,true).").",100);
            return false;
        }
        //----------------------------------------------

        return parent::AddNew($object,$user,$bSaveData);
    }
}

#Classe per il modulo art23 - provvedimenti dirigenziali e accordi
Class AA_ProvvedimentiModule extends AA_GenericModule
{
    const AA_UI_PREFIX="AA_Provvedimenti";

    const AA_MODULE_OBJECTS_CLASS="AA_Provvedimenti";

    //Task per la gestione dei dialoghi standard
    const AA_UI_TASK_PUBBLICATE_FILTER_DLG="GetProvvedimentiPubblicateFilterDlg";
    const AA_UI_TASK_BOZZE_FILTER_DLG="GetProvvedimentiBozzeFilterDlg";
    const AA_UI_TASK_REASSIGN_DLG="GetProvvedimentiReassignDlg";
    const AA_UI_TASK_PUBLISH_DLG="GetProvvedimentiPublishDlg";
    const AA_UI_TASK_TRASH_DLG="GetProvvedimentiTrashDlg";
    const AA_UI_TASK_RESUME_DLG="GetProvvedimentiResumeDlg";
    const AA_UI_TASK_DELETE_DLG="GetProvvedimentiDeleteDlg";
    const AA_UI_TASK_ADDNEW_DLG="GetProvvedimentiAddNewDlg";
    const AA_UI_TASK_MODIFY_DLG="GetProvvedimentiModifyDlg";
    //------------------------------------

    public function __construct($user=null,$bDefaultSections=true)
    {
        $this->SetId("AA_MODULE_PROVVEDIMENTI");
        
        parent::__construct($user,$bDefaultSections);
        
        #--------------------------------Registrazione dei task-----------------------------
        $taskManager=$this->GetTaskManager();
        
        //Dialoghi di filtraggio
        $taskManager->RegisterTask("GetProvvedimentiPubblicateFilterDlg");
        $taskManager->RegisterTask("GetProvvedimentiBozzeFilterDlg");

        //provvedimenti
        $taskManager->RegisterTask("GetProvvedimentiModifyDlg");
        $taskManager->RegisterTask("GetProvvedimentiAddNewDlg");
        $taskManager->RegisterTask("GetProvvedimentiTrashDlg");
        $taskManager->RegisterTask("TrashProvvedimenti");
        $taskManager->RegisterTask("GetProvvedimentiDeleteDlg");
        $taskManager->RegisterTask("DeleteProvvedimenti");
        $taskManager->RegisterTask("GetProvvedimentiResumeDlg");
        $taskManager->RegisterTask("ResumeProvvedimenti");
        $taskManager->RegisterTask("GetProvvedimentiReassignDlg");
        $taskManager->RegisterTask("GetProvvedimentiPublishDlg");
        $taskManager->RegisterTask("ReassignProvvedimenti");
        $taskManager->RegisterTask("AddNewProvvedimenti");
        $taskManager->RegisterTask("UpdateProvvedimenti");
        $taskManager->RegisterTask("PublishProvvedimenti");

        //template dettaglio
        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL,array(
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_Generale_Tab", "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateProvvedimentiDettaglio_Generale_Tab"),
            //array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_Canoni_Tab", "value"=>"Canoni","tooltip"=>"Canoni legati all'immobile","template"=>"TemplateProvvedimentiDettaglio_Canoni_Tab")
        ));
    }
    
    //istanza
    protected static $oInstance=null;
    
    //Restituisce l'istanza corrente
    public static function GetInstance($user=null)
    {
        if(self::$oInstance==null)
        {
            self::$oInstance=new AA_ProvvedimentiModule($user);
        }
        
        return self::$oInstance;
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
    
    //Template pubblicate content
    public function TemplateSection_Pubblicate($params=array())
    {
        $bCanModify=false;
        if($this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $bCanModify=true;
        }

        $content=$this->TemplateGenericSection_Pubblicate($params,$bCanModify);
        return $content->toObject();
    }

    //Restituisce la lista delle schede pubblicate (dati)
    public function GetDataSectionPubblicate_List($params=array())
    {
        return $this->GetDataGenericSectionPubblicate_List($params,"GetDataSectionPubblicate_CustomFilter","GetDataSectionPubblicate_CustomDataTemplate");
    }

    //Personalizza il filtro delle schede pubblicate per il modulo corrente
    protected function GetDataSectionPubblicate_CustomFilter($params = array())
    {
        //Titolo di possesso
        if($params['Titolo'] > 0)
        {
            $params['where'][]=" AND ".AA_Provvedimenti::AA_DBTABLE_DATA.".titolo = '".addslashes($params['Titolo'])."'";
        }
        return $params;
    }

     //Personalizza il template dei dati delle schede pubblicate per il modulo corrente
     protected function GetDataSectionPubblicate_CustomDataTemplate($data = array(),$object=null)
     {
        /*
         if($object instanceof AA_Provvedimenti)
         {
             $data['pretitolo']=$object->GetTitolo();
             $data['tags']="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetSezione()."</span>";
        }*/
 
         return $data;
     }

    //Template sezione bozze (da specializzare)
    public function TemplateSection_Bozze($params=array())
    {
        $is_enabled= false;
       
        if($this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $is_enabled=true;
        }
        
        if(!$is_enabled)
        {
            $content = new AA_JSON_Template_Template(static::AA_UI_PREFIX."_".static::AA_UI_BOZZE_BOX,
                array(
                "type"=>"clean",
                "update_time"=>Date("Y-m-d H:i:s"),
                "name"=>"Schede in bozza",
                "template"=>"L'utente corrente non è abilitato alla visualizzazione della sezione."
            ));
        
            return $content;
        }

        $content=$this->TemplateGenericSection_Bozze($params,false);
        return $content->toObject();
    }
    
    //Restituisce i dati delle bozze
    public function GetDataSectionBozze_List($params=array())
    {
        if(!$this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            AA_Log::Log(__METHOD__." - ERRORE: l'utente corrente: ".$this->oUser->GetUserName()." non è abilitato alla visualizzazione delle bozze.",100);
            return array();
        }

        return $this->GetDataGenericSectionBozze_List($params,"GetDataSectionBozze_CustomFilter","GetDataSectionBozze_CustomDataTemplate");
    }

    //Personalizza il filtro delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomFilter($params = array())
    {
        //Titolo di possesso
        if($params['Titolo'] > 0)
        {
            $params['where'][]=" AND ".AA_Provvedimenti::AA_DBTABLE_DATA.".titolo = '".addslashes($params['Titolo'])."'";
        }
        return $params;
    }

    //Personalizza il template dei dati delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomDataTemplate($data = array(),$object=null)
    {
        /*
        if($object instanceof AA_Provvedimenti)
        {
            $data['pretitolo']=$object->GetTitolo();
            $data['tags']="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetSezione()."</span>";
        }
        */
        return $data;
    }
    
    //Template organismo publish dlg
    public function Template_GetProvvedimentiPublishDlg($params)
    {
        //lista organismi da ripristinare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Provvedimenti($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_PUBLISH)>0)
                {
                    $ids_final[$curId]=$organismo->GetDescr();
                    unset($organismo);
                }
            }

            $id=$this->id."_PublishDlg";

            //Esiste almeno un organismo che può essere pubblicato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $forms_data['ids']=json_encode(array_keys($ids_final));
                
                $wnd=new AA_GenericFormDlg($id, "Pubblica", $this->id, $forms_data,$forms_data);
               
                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata=array();
                foreach($ids_final as $id_org=>$desc)
                {
                    $tabledata[]=array("Denominazione"=>$desc);
                }

                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." provvedimenti/accordi verranno pubblicati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente provvedimento/accordo verrà pubblicato, vuoi procedere?")));

                $table=new AA_JSON_Template_Generic($id."_Table", array(
                    "view"=>"datatable",
                    "scrollX"=>false,
                    "autoConfig"=>true,
                    "select"=>false,
                    "data"=>$tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask('PublishProvvedimenti');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per pubblicare i provvedimenti/accordi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }
            
            return $wnd;
        }
    }
    
    //Template organismo delete dlg
    public function Template_GetProvvedimentiDeleteDlg($params)
    {
        return $this->Template_GetGenericObjectDeleteDlg($params,"DeleteProvvedimenti");
    }
        
    //Template dlg addnew provvedimenti
    public function Template_GetProvvedimentiAddNewDlg()
    {
        $id=$this->GetId()."_AddNew_Dlg";
        
        $form_data=array();
        
        $anno_fine=Date('Y');
        $form_data['AnnoRif']=$anno_fine;
        
        //Struttura
        $form_data['Tipo']=-1;
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi un nuovo provvedimento/accordo", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(920);
        $wnd->SetHeight(640);
        $wnd->EnableValidation();
              
        //tipo
        $options=array(
            array("id"=>"1","value"=>"Provvedimento di scelta del contraente"),
            array("id"=>"2","value"=>"Accordo con altre amministrazioni")
        );
        $wnd->AddSelectField("Tipo","Tipo",array("required"=>true,"validateFunction"=>"IsSelected","customInvalidMessage"=>"*Occorre selezionare il tipo di provvedimento.","bottomLabel"=>"*Indicare il tipo di provvedimento","placeholder"=>"Scegli una voce...","options"=>$options,"value"=>"1"));

        $anno_start=($anno_fine-10);

        //anno riferimento
        $options=array();
        for($i=$anno_fine; $i>=$anno_start; $i--)
        {
            $options[]=array("id"=>$i, "value"=>$i);
        }
        $wnd->AddSelectField("AnnoRif","Anno",array("required"=>true,"validateFunction"=>"IsSelected","bottomLabel"=>"*Indicare l'anno di riferimento.", "placeholder"=>"Scegli l'anno di riferimento.","options"=>$options,"value"=>Date('Y')));

        //Nome
        $wnd->AddTextField("nome","Oggetto",array("required"=>true, "bottomLabel"=>"*Inserisci l'oggetto del provvedimento.", "placeholder"=>"Oggetto del provvedimento..."));

        //Descrizione
        $label="Descrizione";
        $wnd->AddTextareaField("descrizione",$label,array("bottomLabel"=>"*Breve descrizione del provvedimento.", "required"=>true,"placeholder"=>"Inserisci qui la descrizione del provvedimento..."));

        //estremi
        $wnd->AddTextField("Estremi","Estremi",array("required"=>true, "bottomLabel"=>"*Inserisci gli estremi dell'atto.", "placeholder"=>"Estremi dell'atto..."));

        //Contraente
        $wnd->AddTextField("Contraente","Contraente",array("required"=>true, "bottomLabel"=>"*Inserisci la denominazione del contraente.", "placeholder"=>"Denominazione del contraente..."));

        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->SetSaveTask("AddNewProvvedimenti");
        
        return $wnd;
    }
    
    //Template dlg modify immobile
    public function Template_GetProvvedimentiModifyDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_TASK_MODIFY_DLG;
        if(!($object instanceof AA_Provvedimenti)) return new AA_GenericWindowTemplate($id, "Modifica i dati generali", $this->id);

        $id.="_".$object->GetId();
        $form_data['id']=$object->GetID();
        $form_data['nome']=$object->GetName();

        foreach($object->GetDbBindings() as $prop=>$field)
        {
            $form_data[$prop]=$object->GetProp($prop);
        }
        
        $wnd=new AA_GenericFormDlg($id, "Modifica i dati generali", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(920);
        $wnd->SetHeight(640);
        
        //titolo di possesso
        $options=array(
            array("id"=>"1","value"=>"di proprietà"),
            array("id"=>"2","value"=>"posseduto"),
            array("id"=>"4","value"=>"detenuto")
        );
        $wnd->AddSelectField("Titolo","Titolo",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il titolo.","bottomLabel"=>"*Indicare il titolo di possesso","placeholder"=>"Scegli una voce...","options"=>$options,"value"=>"1"));

        //Nome
        $wnd->AddTextField("nome","Denominazione",array("required"=>true, "bottomLabel"=>"*Inserisci la denominazione dell'immobile.", "placeholder"=>"inserisci qui la denominazione dell'immobile"));

        //Descrizione
        $label="Descrizione";
        $wnd->AddTextareaField("Descrizione",$label,array("bottomLabel"=>"*Breve descrizione dell'immobile.", "required"=>true,"placeholder"=>"Inserisci qui la descrizione dell'immobile"));

        //Dati catastali
        $catasto = new AA_FieldSet("AA_PROVVEDIMENTI_CATASTO","Dati catastali");

        //sezione catasto
        $label="Sezione";
        $options=array(
            array("id"=>0,"value"=>"Catasto urbano"),
            array("id"=>1,"value"=>"Catasto terreni")
        );
        $catasto->AddRadioField("SezioneCatasto",$label,array("options"=>$options,"bottomLabel"=>"*Indicare la sezione in cui è accatastato l'immobile.", "required"=>true));

        //codice comune
        $label="Cod. Comune";
        $catasto->AddTextField("CodiceComune",$label,array("bottomLabel"=>"*Codice istat del comune.", "tooltip"=>"Inserisci il nome del comune per attivare l'autocompletamento","required"=>true,"placeholder"=>"Inserisci qui il codice o il nome del comune...","suggest"=>array("template"=>"#codice#","url"=>$this->taskManagerUrl."?task=GetProvvedimentiListaCodiciIstat")));

        //classe
        $label="Classe";
        $catasto->AddTextField("ClasseCatasto",$label,array("bottomLabel"=>"*Inserisci la classe dell'immobile.", "required"=>true,"placeholder"=>"Inserisci qui la classe dell'immobile"), false); 

        //foglio catasto
        $label="Foglio";
        $catasto->AddTextField("FoglioCatasto",$label,array("tooltip"=>"*Inserire il numero del foglio in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."));

        //particella catasto
        $label="Particella";
        $catasto->AddTextField("ParticellaCatasto",$label,array("tooltip"=>"*Inserire il numero della particella in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //rendita catasto
        $label="Rendita";
        $catasto->AddTextField("RenditaCatasto",$label,array("tooltip"=>"*Inserire la rendita catatastale dell'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //consistenza catasto
        $label="Consistenza";
        $catasto->AddTextField("ConsistenzaCatasto",$label,array("tooltip"=>"*Inserire la consistenza dell'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //Indirizzo
        $label="Indirizzo";
        $catasto->AddTextField("Indirizzo",$label,array("bottomLabel"=>"*Inserire l'indirizzo dell'immobile senza indicare il comune.", "required"=>true,"placeholder"=>"es.: viale Trento 69"));

        $wnd->AddGenericObject($catasto);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateProvvedimenti");
        
        return $wnd;
    }  

    //Template detail (da specializzare)
    public function TemplateSection_Detail($params)
    {
        //Gestione dei tab
        //$id=static::AA_UI_PREFIX."_Detail_Generale_Tab_".$params['id'];
        //$params['DetailOptionTab']=array(array("id"=>$id, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateProvvedimentiDettaglio_Generale_Tab"));
        
        return $this->TemplateGenericSection_Detail($params);
    }   
    
    //Template section detail, tab generale
    public function TemplateProvvedimentiDettaglio_Generale_Tab($object=null)
    {
        $sectionTemplate=$this->GetSectionItemTemplate(static::AA_ID_SECTION_DETAIL);
        if(!is_array($sectionTemplate))
        {
            $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_Generale_Tab_".date("Y-m-d_h:i:s");
        }
        else
        {
            $id=$sectionTemplate[0]['id'];
        }

        if(!($object instanceof AA_Provvedimenti)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
        //$id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_Generale_Tab_".$object->GetId();
        $rows_fixed_height=50;

        $layout=$this->TemplateGenericDettaglio_Header_Generale_Tab($object,$id);
        
        //Descrizione
        $value=$object->GetProp("Descrizione");
        if($value=="")$value="n.d.";
        $descr=new AA_JSON_Template_Template($id."_Descrizione",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Descrizione:","value"=>$value)
        ));

        //Codice Comune
        $value= $object->GetProp("CodiceComune");
        if($value=="") $value="n.d.";
        $comune=new AA_JSON_Template_Template($id."_CodiceComune",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Codice Comune:","value"=>$value)
        ));

        //Classe
        $value= $object->GetProp("ClasseCatasto");
        if($value=="") $value="n.d.";
        $classe=new AA_JSON_Template_Template($id."_ClasseCatasto",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Classe:","value"=>$value)
        ));
        
        //foglio
        $value= $object->GetProp("FoglioCatasto");
        if($value=="") $value="n.d.";
        $foglio=new AA_JSON_Template_Template($id."_FoglioCatasto",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Foglio:","value"=>$value)
        ));
        
        //Particella
        $value= $object->GetProp("ParticellaCatasto");
        if($value=="") $value="n.d.";
        $particella=new AA_JSON_Template_Template($id."_ParticellaCatasto",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Particella:","value"=>$value)
        ));

        //Rendita
        $value= $object->GetProp("RenditaCatasto");
        if($value=="") $value="n.d.";
        $rendita=new AA_JSON_Template_Template($id."_RenditaCatasto",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Rendita:","value"=>$value)
        ));

        //Consistenza
        $value= $object->GetProp("ConsistenzaCatasto");
        if($value=="") $value="n.d.";
        $consistenza=new AA_JSON_Template_Template($id."_Consistenza",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Consistenza:","value"=>$value)
        ));
        
        //seconda riga
        $riga=new AA_JSON_Template_Layout($id."_SecondRow",array("height"=>$rows_fixed_height));
        $riga->AddCol($descr);
        $layout->AddRow($riga);
        
        //terza riga
        $riga=new AA_JSON_Template_Layout($id."_ThirdRow",array("height"=>38,"type"=>"section","css"=>array("background"=>"#dadee0 !important;")));
        $riga->AddCol(new AA_JSON_Template_Generic($id."_Catasto_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Dati catastali</span>", "align"=>"center")));
        $layout->AddRow($riga);

        //Quinta riga
        $riga=new AA_JSON_Template_Layout($id."_FiveRow",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($foglio);
        $riga->AddCol($particella);
        $riga->AddCol($rendita);
        $riga->AddCol($consistenza);
        $layout->AddRow($riga);        
        
        return $layout;
    }
     
    //Task Update Provvedimenti
    public function Task_UpdateProvvedimenti($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi di modifica dell'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        return $this->Task_GenericUpdateObject($task,$_REQUEST,true);   
    }
    
    //Task trash Provvedimenti
    public function Task_TrashProvvedimenti($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $task->SetError("L'utente corrente non ha i permessi per cestinare l'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per cestinare l'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        return $this->Task_GenericTrashObject($task,$_REQUEST);
    }
    
    //Task resume Provvedimenti
    public function Task_ResumeProvvedimenti($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericResumeObject($task,$_REQUEST);
    }
    
    //Task publish Provvedimenti
    public function Task_PublishProvvedimenti($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericPublishObject($task,$_REQUEST);
    }
    
    //Task reassign Provvedimenti
    public function Task_ReassignProvvedimenti($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        return $this->Task_GenericReassignObject($task,$_REQUEST);
    }
    
    //Task delete Provvedimenti
    public function Task_DeleteProvvedimenti($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        return $this->Task_GenericDeleteObject($task,$_REQUEST);
    }
    
    //Task Aggiungi provvedimenti
    public function Task_AddNewProvvedimenti($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per aggiungere nuovi elementi</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        return $this->Task_GenericAddNew($task,$_REQUEST);
    }

    //Task modifica provvedimento
    public function Task_GetProvvedimentiModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non può modifcare l'elemento.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $object= new AA_Provvedimenti($_REQUEST['id'],$this->oUser);
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetProvvedimentiModifyDlg($object)->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task resume organismo
    public function Task_GetProvvedimentiResumeDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per ripristinare elementi.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetGenericResumeObjectDlg($_REQUEST,"ResumeProvvedimenti")->toBase64();
            $sTaskLog.="</content>";
            $task->SetLog($sTaskLog);
        
            return true;
        }    
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativi non presenti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        return true;
    }
    
    //Task publish organismo
    public function Task_GetProvvedimentiPublishDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per pubblicare elementi.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetGenericPublishObjectDlg($_REQUEST,"PublishProvvedimenti")->toBase64();
            $sTaskLog.="</content>";
            $task->SetLog($sTaskLog);
        
            return true;
        }    
        else
        {
            // to do lista da recuperare con filtro
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativi non presenti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        return true;
    }
    
    //Task Riassegna provvedimenti
    public function Task_GetProvvedimentiReassignDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
         if(!$this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per riassegnare elementi.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetGenericReassignObjectDlg($_REQUEST,"ReassignProvvedimenti")->toBase64();
            $sTaskLog.="</content>";
            
            $task->SetLog($sTaskLog);
        
            return true;
        }    
        else
        {
            // to do lista da recuperare con filtro
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativi non presenti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        return true;
    }
    
    //Task elimina organismo
    public function Task_GetProvvedimentiTrashDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per cestinare/eliminare elementi di questo tipo.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetGenericObjectTrashDlg($_REQUEST,"TrashProvvedimenti")->toBase64();
            $sTaskLog.="</content>";
            
            $task->SetLog($sTaskLog);
        
            return true;
        }    
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativi non presenti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }

        return true;
    }
       
    //Task dialogo elimina provvedimenti
    public function Task_GetProvvedimentiDeleteDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per cestinare/eliminare organismi.</error>";
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetProvvedimentiDeleteDlg($_REQUEST)->toBase64();
            $sTaskLog.="</content>";
        }    
        else
        {
            // to do lista da recuperare con filtro
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativi non presenti.</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task aggiunta provvedimenti
    public function Task_GetProvvedimentiAddNewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per istanziare nuovi elementi.</error>";
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetProvvedimentiAddNewDlg()->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task filter dlg
    public function Task_GetProvvedimentiPubblicateFilterDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->TemplatePubblicateFilterDlg($_REQUEST);
        $sTaskLog.= base64_encode($content);
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task filter dlg
    public function Task_GetProvvedimentiBozzeFilterDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->TemplateBozzeFilterDlg($_REQUEST);
        $sTaskLog.= base64_encode($content);
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task NavBarContent
    public function Task_GetNavbarContent($task)
    {
        if(!$this->oUser->HasFlag(AA_Provvedimenti_Const::AA_USER_FLAG_PROVVEDIMENTI))
        {
            $_REQUEST['section']=static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX;
        }
        
        return $this->Task_GetGenericNavbarContent($task,$_REQUEST);
    }
    
    //Template filtro di ricerca
    public function TemplatePubblicateFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate'],"revisionate"=>$params['revisionate'], "Titolo"=>$params['Titolo']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['revisionate']=="") $formData['revisionate']=0;

        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","Titolo"=>0,"nome"=>"","cestinate"=>0,"revisionate"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Pubblicate_Filter", "Parametri di ricerca per le schede pubblicate",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
      
        //Revisionate
        //$dlg->AddSwitchBoxField("revisionate","Revisionate",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede revisionate."));
        
        //Denominazione
        $dlg->AddTextField("nome","Denominazione",array("bottomLabel"=>"*Filtra in base alla denominazione dell'immobile.", "placeholder"=>"Denominazione..."));
        
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
       //Oggetto
       $dlg->AddTextField("oggetto","Oggetto",array("bottomLabel"=>"*Filtra in base all'oggetto del provvedimento/accordo.", "placeholder"=>"Oggetto..."));

       //Estremi provvedimento
       $dlg->AddTextField("estremi","Estremi",array("bottomLabel"=>"*Filtra in base agli estremi del provvedimento/accordo.", "placeholder"=>"Estremi..."));
        
        return $dlg->GetObject();
    }
    
    //Template filtro di ricerca
    public function TemplateBozzeFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"Titolo"=>$params['Titolo'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        
        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","Titolo"=>0,"nome"=>"","cestinate"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Bozze_Filter", "Parametri di ricerca per le bozze",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
        
        //Denominazione
        $dlg->AddTextField("nome","Denominazione",array("bottomLabel"=>"*Filtra in base alla denominazione del provvedimento/accordo.", "placeholder"=>"Denominazione..."));
        
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));

        //Oggetto
        $dlg->AddTextField("oggetto","Oggetto",array("bottomLabel"=>"*Filtra in base all'oggetto del provvedimento/accordo.", "placeholder"=>"Oggetto..."));

        //Estremi provvedimento
        $dlg->AddTextField("estremi","Estremi",array("bottomLabel"=>"*Filtra in base agli estremi del provvedimenti.", "placeholder"=>"Estremi..."));

        return $dlg->GetObject();
    }
    
    //Funzione di esportazione in pdf (da specializzare)
    public function Template_PdfExport($ids=array())
    {
        return $this->Template_GenericPdfExport($ids,true,"Pubblicazione ai sensi dell'art.23 del d.lgs. 33/2013","Template_ProvvedimentiPdfExport");
    }

    //Template pdf export single
    public function Template_ProvvedimentiPdfExport($id="", $parent=null,$object=null,$user=null)
    {
        if(!($object instanceof AA_Provvedimenti))
        {
            return "";
        }
        
        if($id=="") $id="Template_ProvvedimentiPdfExport_".$object->GetId();

        return new AA_ProvvedimentiPublicReportTemplateView($id,$parent,$object,$user);
    }
}

#Classe template per la gestione del report pdf dell'oggetto
Class AA_ProvvedimentiPublicReportTemplateView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_ProvvedimentiPublicReportTemplateView",$parent=null,$object=null)
    {
        if(!($object instanceof AA_Provvedimenti))
        {
            AA_Log::Log(__METHOD__." - oggetto non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$object);
        
        $this->SetStyle("width: 99%; display:flex; flex-direction: column; align-items: center;");

        #Parte generale---------------------------------
        $generale=new AA_XML_Div_Element("AA_OrganismiPublicReportTemplateView-generale",$this);
        $generale->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; width: 100%");

        #Denominazione----------------------------------
        $denominazione=new AA_XML_Div_Element("generale-tab-denominazione",$generale);
        $denominazione->SetStyle('width:100%; border-bottom: 1px solid  gray; margin-bottom: 1em; margin-top: .2em; font-size: 20px; font-weight: bold; padding: .1em');
        $denominazione->SetText($object->GetName()."<div style='font-size: x-small; font-weight: normal; margin-top: .1em;'>tipo</div>");
        #-----------------------------------------------

        //left panel-------
        $left_panel= new AA_XML_Div_Element("generale-tab-left-panel",$generale);
        $left_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; align-self: start; width:70%; flex: 1; align-self: stretch; border: 1px solid #d7dbdd;");
        
        //Etichetta descrizione
        $descr= new AA_XML_Div_Element("generale-tab-left-panel-descrizione",$left_panel);
        $descr->SetStyle("width:100%; margin-bottom: .8em; text-align: center; background: #d7dbdd; border-bottom: 1px solid #d7dbdd;");
        $descr->SetText('<span style="font-weight:bold">Descrizione</span>');

        //Descrizione
        $val=$object->GetProp("Descrizione");
        if($val=="") $val="n.d.";
        $descr=new AA_XML_Div_Element("descr",$left_panel);
        $descr->SetStyle("display: flex; width: 100%; margin-bottom: .8em; text-align: left; align-self: stretch; flex: 1; padding: .3em");
        $descr->SetText($val);

        //Etichetta indirizzo
        $descr= new AA_XML_Div_Element("generale-tab-left-panel-indirizzo",$left_panel);
        $descr->SetStyle("width:100%; margin-bottom: .8em; text-align: center; background: #d7dbdd; border-bottom: 1px solid #d7dbdd;");
        $descr->SetText('<span style="font-weight:bold">Indirizzo</span>');

        //Indirizzo
        $val=$object->GetProp("Indirizzo");
        if($val=="") $val="n.d.";
        $descr=new AA_XML_Div_Element("indirizzo",$left_panel);
        $descr->SetStyle("width: 100%; margin-bottom: .8em; text-align: left; padding: .3em;");
        $descr->SetText($val);
        #-------------------
        
        //right panel ------
        $right_panel= new AA_XML_Div_Element("generale-tab-right-panel",$generale);
        $right_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; width:29%; border: 1px solid  #d7dbdd");
        
        $databoxStyle="display: flex; justify-content: space-between; width: 98%; margin-bottom: .8em; border-bottom: 1px solid #d7dbdd; padding: 1%;";
        $databoxStyleLastRow="display: flex; justify-content: space-between; width: 98%; margin-bottom: .8em; padding: 1%;";

        //Etichetta dati catastali
        $dati_catastali= new AA_XML_Div_Element("generale-tab-right-panel-dati_catastali",$right_panel);
        $dati_catastali->SetStyle("width: 100%; margin-bottom: .8em; text-align: center; border-bottom: 1px solid  #d7dbdd; background: #d7dbdd");
        $dati_catastali->SetText('<span style="font-weight:bold">Dati catastali</span>');

        //codice comune
        $val=$object->GetProp("CodiceComune");
        if($val=="") $val="n.d.";
        $codcomune = new AA_XML_Div_Element("generale-tab-right-panel-cod_comune",$right_panel);
        $codcomune->SetStyle($databoxStyle);
        $codcomune->SetText('<span style="font-weight:bold">Codice Comune:</span><span>'.$val.'</span>');

        //classe
        $val=$object->GetProp("ClasseCatasto");
        if($val=="") $val="n.d.";
        $classe = new AA_XML_Div_Element("generale-tab-right-panel-classe",$right_panel);
        $classe->SetStyle($databoxStyle);
        $classe->SetText('<span style="font-weight:bold">Classe:</span>'.$val.'</span>');

        //foglio
        $val=$object->GetProp("FoglioCatasto");
        if($val=="") $val="n.d.";
        $foglio = new AA_XML_Div_Element("generale-tab-right-panel-foglio",$right_panel);
        $foglio->SetStyle($databoxStyle);
        $foglio->SetText('<span style="font-weight:bold">Foglio:</span>'.$val.'</span>');

        //particella
        $val=$object->GetProp("ParticellaCatasto");
        if($val=="") $val="n.d.";
        $particella = new AA_XML_Div_Element("generale-tab-right-panel-particella",$right_panel);
        $particella->SetStyle($databoxStyle);
        $particella->SetText('<span style="font-weight:bold">Particella:</span><span>'.$val.'</span>');

        //codice comune
        $val=$object->GetProp("RenditaCatasto");
        if($val=="") $val="n.d.";
        $rendita = new AA_XML_Div_Element("generale-tab-right-panel-rendita",$right_panel);
        $rendita->SetStyle($databoxStyle);
        $rendita->SetText('<span style="font-weight:bold">Rendita:</span><span>'.$val.'</span>');

        //consistenza
        $val=$object->GetProp("ConsistenzaCatasto");
        if($val=="") $val="n.d.";
        $consistenza = new AA_XML_Div_Element("generale-tab-right-panel-consistenza",$right_panel);
        $consistenza->SetStyle($databoxStyleLastRow);
        $consistenza->SetText('<span style="font-weight:bold">Consistenza:</span><span>'.$val.'</span>');
        #-------------------

        //legenda
        $footer="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%; margin-top: 1em;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente prospetto sono state aggiornate l'ultima volta il ".$object->GetAggiornamento()."</span></div>";
        $this->SetText($footer,false);
    }
}
