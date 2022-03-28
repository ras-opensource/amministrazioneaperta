<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "config.php";
include_once "system_lib.php";

#Costanti
Class AA_Patrimonio_Const extends AA_Const
{
    const AA_USER_FLAG_PATRIMONIO="patrimonio";

    //Risorse per codici Comuni: https://dait.interno.gov.it/territorio-e-autonomie-locali/sut/open_data.php

    //titolo di possesso
    const AA_PATRIMONIO_TITOLO_PROPRIETA=1;
    const AA_PATRIMONIO_TITOLO_POSSEDUTO=2;
    const AA_PATRIMONIO_TITOLO_DETENUTO=4;

    static $titoloList=null;
    private static function Inizialize()
    {
        if(self::$titoloList==null)
        {
            self::$titoloList=array(
                self::AA_PATRIMONIO_TITOLO_PROPRIETA=>"di proprietà",
                self::AA_PATRIMONIO_TITOLO_POSSEDUTO=>"posseduto",
                self::AA_PATRIMONIO_TITOLO_DETENUTO=>"detenuto",
            );    
        }

        if(self::$sezioneList==null)
        {
            self::$sezioneList=array(
                self::AA_PATRIMONIO_CATASTO_URBANO=>"catasto urbano",
                self::AA_PATRIMONIO_CATASTO_TERRENI=>"catasto terreni"
            );    
        }
    }
    public static function GetTitoloList()
    {
        self::Inizialize();
        return self::$titoloList;
    }

    //Sezione catastale
    const AA_PATRIMONIO_CATASTO_URBANO=0;
    const AA_PATRIMONIO_CATASTO_TERRENI=1;
    static $sezioneList=null;
    public static function GetSezioneList()
    {
        self::Inizialize();
        return self::$sezioneList;
    }

}

#Classe oggetto patrimonio
Class AA_Patrimonio extends AA_Object_V2
{
    //tabella dati db
    const AA_DBTABLE_DATA="aa_patrimonio_data";

    //Costruttore
    public function __construct($id=0, $user=null)
    {
        //data table
        $this->SetDbDataTable(static::AA_DBTABLE_DATA);

        //Db data binding
        $this->SetBind("Descrizione","descrizione");
        $this->SetBind("CodiceComune","codice_comune");
        $this->SetBind("SezioneCatasto","sezione_catasto");
        $this->SetBind("FoglioCatasto","foglio_catasto");
        $this->SetBind("ParticellaCatasto","particella_catasto");
        $this->SetBind("Indirizzo","indirizzo");
        $this->SetBind("RenditaCatasto","rendita_catasto");
        $this->SetBind("ConsistenzaCatasto","consistenza_catasto");
        $this->SetBind("ClasseCatasto","classe_catasto");
        $this->SetBind("Titolo","titolo"); 

        //Valori iniziali
        $this->SetProp("IdData",0);
        $this->SetProp("Titolo",0);

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
        $params['class']="AA_Patrimonio";
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
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && !$user->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $perms = AA_Const::AA_PERMS_READ;
        }
        //---------------------------------------

        return $perms;
    }

    static public function AddNew($object=null,$user=null,$bStandardCheck=true,$bSaveData=false)
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
        if(!$user->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            AA_Log::Log(__METHOD__." - L'utente corrente: ".$user->GetUserName()." non ha i permessi per inserire nuovi elementi.",100);
            return false;
        }

        //Verifica validità oggetto
        if(!($object instanceof AA_Patrimonio))
        {
            AA_Log::Log(__METHOD__." - Errore: oggetto non valido (".print_r($object,true).").",100);
            return false;
        }
        //----------------------------------------------

        return parent::AddNew($object,$user,$bStandardCheck,$bSaveData);
    }

    //restituisce il titolo di possesso
    public function GetTitolo($bNumeric=false)
    {
        if($bNumeric) return $this->GetProp("Titolo");
        $titoloList=AA_Patrimonio_Const::GetTitoloList();
        return $titoloList[$this->GetProp("Titolo")];
    }

    //restituisce la sezione catastale
    public function GetSezione($bNumeric=false)
    {
        if($bNumeric) return $this->GetProp("SezioneCatasto");
        $titoloList=AA_Patrimonio_Const::GetSezioneList();
        return $titoloList[$this->GetProp("SezioneCatasto")];
    }
}

#Classe per il modulo art30 gestione del patrimonio
Class AA_PatrimonioModule extends AA_GenericModule
{
    const AA_UI_PREFIX="AA_Patrimonio";

    const AA_MODULE_OBJECTS_CLASS="AA_Patrimonio";

    //Task per la gestione dei dialoghi standard
    const AA_UI_TASK_PUBBLICATE_FILTER_DLG="GetPatrimonioPubblicateFilterDlg";
    const AA_UI_TASK_BOZZE_FILTER_DLG="GetPatrimonioBozzeFilterDlg";
    const AA_UI_TASK_REASSIGN_DLG="GetPatrimonioReassignDlg";
    const AA_UI_TASK_PUBLISH_DLG="GetPatrimonioPublishDlg";
    const AA_UI_TASK_TRASH_DLG="GetPatrimonioTrashDlg";
    const AA_UI_TASK_RESUME_DLG="GetPatrimonioResumeDlg";
    const AA_UI_TASK_DELETE_DLG="GetPatrimonioDeleteDlg";
    const AA_UI_TASK_ADDNEW_DLG="GetPatrimonioAddNewDlg";
    const AA_UI_TASK_MODIFY_DLG="GetPatrimonioModifyDlg";
    //------------------------------------

    public function __construct($user=null)
    {
        parent::__construct($user);
        
        $this->SetId("AA_MODULE_PATRIMONIO");
        
        //Sidebar config
        $this->SetSideBarId("patrimonio");
        $this->SetSideBarIcon("mdi mdi-home");
        $this->SetSideBarTooltip("Gestione patrimonio");
        $this->SetSideBarName("Patrimonio");
        
        //Registrazione dei task-------------------
        $taskManager=$this->GetTaskManager();
        
        //Dialoghi di filtraggio
        $taskManager->RegisterTask("GetPatrimonioPubblicateFilterDlg");
        $taskManager->RegisterTask("GetPatrimonioBozzeFilterDlg");

        //patrimonio
        $taskManager->RegisterTask("GetPatrimonioModifyDlg");
        $taskManager->RegisterTask("GetPatrimonioAddNewDlg");
        $taskManager->RegisterTask("GetPatrimonioTrashDlg");
        $taskManager->RegisterTask("TrashPatrimonio");
        $taskManager->RegisterTask("GetPatrimonioDeleteDlg");
        $taskManager->RegisterTask("DeletePatrimonio");
        $taskManager->RegisterTask("GetPatrimonioResumeDlg");
        $taskManager->RegisterTask("ResumePatrimonio");
        $taskManager->RegisterTask("GetPatrimonioReassignDlg");
        $taskManager->RegisterTask("GetPatrimonioPublishDlg");
        $taskManager->RegisterTask("ReassignPatrimonio");
        $taskManager->RegisterTask("AddNewPatrimonio");
        $taskManager->RegisterTask("UpdatePatrimonio");
        $taskManager->RegisterTask("PublishPatrimonio");
        
        //Pdf export
        $taskManager->RegisterTask("PdfExport");
        
        #Sezioni----------------------------------------
        
        //Schede pubblicate
        $navbarTemplate=array($this->TemplateNavbar_Bozze(1,true)->toArray());
        $section=new AA_GenericModuleSection("Pubblicate","Schede pubblicate",true,static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX,$this->GetId(),true,true,false,true);
        $section->SetNavbarTemplate($navbarTemplate);
        $this->AddSection($section);
        
        //Bozze
        $navbarTemplate= $this->TemplateNavbar_Pubblicate(1,true)->toArray();
        $section=new AA_GenericModuleSection("Bozze","Schede in bozza",true,static::AA_UI_PREFIX."_".static::AA_UI_BOZZE_BOX,$this->GetId(),false,true,false,true);
        $section->SetNavbarTemplate($navbarTemplate);
        $this->AddSection($section);
        
        //dettaglio
        $navbarTemplate=$this->TemplateNavbar_Back(1,true)->toArray();
        $section=new AA_GenericModuleSection("Dettaglio","Dettaglio",false,static::AA_UI_PREFIX."_".static::AA_UI_DETAIL_BOX,$this->GetId(),false,true,true,true);
        $section->SetNavbarTemplate($navbarTemplate);
        $this->AddSection($section);
        
        #-------------------------------------------
    }
    
    //istanza
    protected static $oInstance=null;
    
    //Restituisce l'istanza corrente
    public static function GetInstance($user=null)
    {
        if(self::$oInstance==null)
        {
            self::$oInstance=new AA_PatrimonioModule($user);
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
        if($this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
            $params['where'][]=" AND ".AA_Patrimonio::AA_DBTABLE_DATA.".titolo = '".addslashes($params['Titolo'])."'";
        }
        return $params;
    }

     //Personalizza il template dei dati delle schede pubblicate per il modulo corrente
     protected function GetDataSectionPubblicate_CustomDataTemplate($data = array(),$object=null)
     {
         if($object instanceof AA_Patrimonio)
         {
             $data['pretitolo']=$object->GetTitolo();
             $data['tags']="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetSezione()."</span>";
         }
 
         return $data;
     }

    //Template sezione bozze (da specializzare)
    public function TemplateSection_Bozze($params=array())
    {
        $is_enabled= false;
       
        if($this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
            $params['where'][]=" AND ".AA_Patrimonio::AA_DBTABLE_DATA.".titolo = '".addslashes($params['Titolo'])."'";
        }
        return $params;
    }

    //Personalizza il template dei dati delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomDataTemplate($data = array(),$object=null)
    {
        if($object instanceof AA_Patrimonio)
        {
            $data['pretitolo']=$object->GetTitolo();
            $data['tags']="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetSezione()."</span>";
        }

        return $data;
    }
    
    //Template organismo publish dlg
    public function Template_GetPatrimonioPublishDlg($params)
    {
        //lista organismi da ripristinare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Patrimonio($curId,$this->oUser);
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

                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." organismi verranno pubblicato, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente organismo verrà pubblicato, vuoi procedere?")));

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
                $wnd->SetSaveTask('PublishPatrimonio');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per pubblicare gli organismi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }
            
            return $wnd;
        }
    }
    
    //Template organismo delete dlg
    public function Template_GetPatrimonioDeleteDlg($params)
    {
        //lista organismi da eliminare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Patrimonio($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_DELETE)>0)
                {
                    $ids_final[$curId]=$organismo->GetDescr();
                    unset($organismo);
                }
            }

            $id=$this->id."_DeleteDlg";

            //Esiste almeno un organismo che può essere eliminato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $forms_data['ids']=json_encode(array_keys($ids_final));
                $wnd=new AA_GenericFormDlg($id, "Elimina...", $this->id, $forms_data,$forms_data);

                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata=array();
                foreach($ids_final as $id_org=>$desc)
                {
                    $tabledata[]=array("Denominazione"=>$desc);
                }

                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." organismi verranno <span style='text-decoration:underline'>eliminati definitivamente</span>, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente organismo verrà <span style='text-decoration:underline'>eliminato definitivamente</span>, vuoi procedere?")));

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
                $wnd->SetSaveTask('DeletePatrimonio');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per eliminare gli organismi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }
            
            return $wnd;
        }
    }
        
    //Template dlg addnew patrimonio
    public function Template_GetPatrimonioAddNewDlg()
    {
        $id=$this->GetId()."_AddNew_Dlg";
        
        $form_data=array();
        
        //Struttura
        $struct=$this->oUser->GetStruct();
        $form_data['id_assessorato']=$struct->GetAssessorato(true);
        $form_data['id_direzione']=$struct->GetDirezione(true);
        $form_data['id_servizio']=$struct->GetServizio(true);
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi un nuovo immobile/terreno", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(640);
        $wnd->EnableValidation();
              
        //titolo di possesso
        $options=array(
            array("id"=>"1","value"=>"di proprietà"),
            array("id"=>"2","value"=>"posseduto"),
            array("id"=>"4","value"=>"detenuto")
        );
        $wnd->AddSelectField("Titolo","Titolo",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il titolo.","bottomLabel"=>"*Indicare il titolo di possesso","placeholder"=>"Scegli una voce...","options"=>$options,"value"=>"1"));
        
        //Nome
        $wnd->AddTextField("nome","Denominazione",array("required"=>true, "bottomLabel"=>"*Inserisci la denominazione dell'immobile/terreno.", "placeholder"=>"inserisci qui la denominazione dell'immobile/terreno"));
        
        //Descrizione
        $label="Descrizione";
        $wnd->AddTextareaField("Descrizione",$label,array("bottomLabel"=>"*Breve descrizione dell'immobile/terreno.", "required"=>true,"placeholder"=>"Inserisci qui la descrizione dell'immobile/terreno"));

        //Dati catastali
        $catasto = new AA_FieldSet("AA_PATRIMONIO_CATASTO","Dati catastali");

        //sezione catasto
        $label="Sezione";        
        $catasto->AddSwitchBoxField("SezioneCatasto",$label,array("onLabel"=>"Catasto terreni","offLabel"=>"Catasto urbano","bottomLabel"=>"*Indicare la sezione in cui è accatastato l'immobile/terreno.", "required"=>true));

        //codice comune
        $label="Cod. Comune";
        $catasto->AddTextField("CodiceComune",$label,array("bottomLabel"=>"*Codice Comune.", "required"=>true,"placeholder"=>"Inserisci qui il codice comune...")); 

        //classe
        $label="Classe";
        $catasto->AddTextField("ClasseCatasto",$label,array("bottomLabel"=>"*Inserisci la classe dell'immobile/terreno.", "required"=>true,"placeholder"=>"Inserisci qui la classe dell'immobile/terreno"), false); 
        
        //foglio catasto
        $label="Foglio";
        $catasto->AddTextField("FoglioCatasto",$label,array("tooltip"=>"*Inserire il numero del foglio in cui è accastato l'immobile/terreno.", "required"=>true,"placeholder"=>"..."));
        
        //particella catasto
        $label="Particella";
        $catasto->AddTextField("ParticellaCatasto",$label,array("tooltip"=>"*Inserire il numero della particella in cui è accastato l'immobile/terreno.", "required"=>true,"placeholder"=>"..."),false);

        //rendita catasto
        $label="Rendita";
        $catasto->AddTextField("RenditaCatasto",$label,array("tooltip"=>"*Inserire la rendita catatastale dell'immobile/terreno.", "required"=>true,"placeholder"=>"..."),false);

        //consistenza catasto
        $label="Consistenza";
        $catasto->AddTextField("ConsistenzaCatasto",$label,array("tooltip"=>"*Inserire la consistenza dell'immobile/terreno.", "required"=>true,"placeholder"=>"..."),false);

        //Indirizzo
        $label="Indirizzo";
        $catasto->AddTextField("Indirizzo",$label,array("bottomLabel"=>"*Inserire l'indirizzo dell'immobile/terreno.", "required"=>true,"placeholder"=>"Inserisci qui l'indirizzo dell'immobile/terreno."));
        
        $wnd->AddGenericObject($catasto);

        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->SetSaveTask("AddNewPatrimonio");
        
        return $wnd;
    }
    
    //Template dlg modify immobile
    public function Template_GetPatrimonioModifyDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_TASK_MODIFY_DLG;
        if(!($object instanceof AA_Patrimonio)) return new AA_GenericWindowTemplate($id, "Modifica i dati generali", $this->id);

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
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(640);
        
        //titolo di possesso
        $options=array(
            array("id"=>"1","value"=>"di proprietà"),
            array("id"=>"2","value"=>"posseduto"),
            array("id"=>"4","value"=>"detenuto")
        );
        $wnd->AddSelectField("Titolo","Titolo",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il titolo.","bottomLabel"=>"*Indicare il titolo di possesso","placeholder"=>"Scegli una voce...","options"=>$options,"value"=>"1"));

        //Nome
        $wnd->AddTextField("nome","Denominazione",array("required"=>true, "bottomLabel"=>"*Inserisci la denominazione dell'immobile/terreno.", "placeholder"=>"inserisci qui la denominazione dell'immobile/terreno"));

        //Descrizione
        $label="Descrizione";
        $wnd->AddTextareaField("Descrizione",$label,array("bottomLabel"=>"*Breve descrizione dell'immobile/terreno.", "required"=>true,"placeholder"=>"Inserisci qui la descrizione dell'immobile/terreno"));

        //Dati catastali
        $catasto = new AA_FieldSet("AA_PATRIMONIO_CATASTO","Dati catastali");

        //sezione catasto
        $label="Sezione";        
        $catasto->AddSwitchBoxField("SezioneCatasto",$label,array("onLabel"=>"Catasto terreni","offLabel"=>"Catasto urbano","bottomLabel"=>"*Indicare la sezione in cui è accatastato l'immobile/terreno.", "required"=>true));

        //codice comune
        $label="Cod. Comune";
        $catasto->AddTextField("CodiceComune",$label,array("bottomLabel"=>"*Codice Comune.", "required"=>true,"placeholder"=>"Inserisci qui il codice comune...")); 

        //classe
        $label="Classe";
        $catasto->AddTextField("ClasseCatasto",$label,array("bottomLabel"=>"*Inserisci la classe dell'immobile/terreno.", "required"=>true,"placeholder"=>"Inserisci qui la classe dell'immobile/terreno"), false); 

        //foglio catasto
        $label="Foglio";
        $catasto->AddTextField("FoglioCatasto",$label,array("tooltip"=>"*Inserire il numero del foglio in cui è accastato l'immobile/terreno.", "required"=>true,"placeholder"=>"..."));

        //particella catasto
        $label="Particella";
        $catasto->AddTextField("ParticellaCatasto",$label,array("tooltip"=>"*Inserire il numero della particella in cui è accastato l'immobile/terreno.", "required"=>true,"placeholder"=>"..."),false);

        //rendita catasto
        $label="Rendita";
        $catasto->AddTextField("RenditaCatasto",$label,array("tooltip"=>"*Inserire la rendita catatastale dell'immobile/terreno.", "required"=>true,"placeholder"=>"..."),false);

        //consistenza catasto
        $label="Consistenza";
        $catasto->AddTextField("ConsistenzaCatasto",$label,array("tooltip"=>"*Inserire la consistenza dell'immobile/terreno.", "required"=>true,"placeholder"=>"..."),false);

        //Indirizzo
        $label="Indirizzo";
        $catasto->AddTextField("Indirizzo",$label,array("bottomLabel"=>"*Inserire l'indirizzo dell'immobile/terreno.", "required"=>true,"placeholder"=>"Inserisci qui l'indirizzo dell'immobile/terreno."));

        $wnd->AddGenericObject($catasto);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdatePatrimonio");
        
        return $wnd;
    }
    
    //Template detail (da specializzare)
    public function TemplateSection_Detail($params)
    {
        //Gestione dei tab
        $id=static::AA_UI_PREFIX."_Detail_Generale_Tab_".$params['id'];
        $params['DetailOptionTab']=array(array("id"=>$id, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplatePatrimonioDettaglio_Generale_Tab"));
        
        return $this->TemplateGenericSection_Detail($params);
    }   
    
    //Template section detail, tab generale
    public function TemplatePatrimonioDettaglio_Generale_Tab($object=null)
    {
        if(!($object instanceof AA_Patrimonio)) return new AA_JSON_Template_Template(static::AA_UI_PREFIX."_Detail_Generale_Tab_",array("template"=>"Dati non validi"));
        
        $id=static::AA_UI_PREFIX."_Detail_Generale_Tab_".$object->GetId();
        $rows_fixed_height=50;

        $layout=$this->TemplateGenericDettaglio_Header_Generale_Tab($object,$id);
        
        //Descrizione
        $value=$object->GetProp("Descrizione");
        if($value=="")$value="n.d.";
        $descr=new AA_JSON_Template_Template($id."_Descrizione",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Descrizione:","value"=>$value)
        ));

        //Titolo di possesso
        $value= $object->GetTitolo();
        if($value=="") $value="n.d.";
        $titolo=new AA_JSON_Template_Template($id."_Titolo",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Titolo di possesso:","value"=>"<span class='AA_DataView_Tag AA_Label AA_Label_Orange'>".$value."</span>")
        ));

        //Sezione catastale
        $value= $object->GetSezione();
        if($value=="") $value="n.d.";
        $sezione=new AA_JSON_Template_Template($id."_Sezione",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Sezione:","value"=>"<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$value."</span>")
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

        //Indirizzo
        $value= $object->GetProp("Indirizzo");
        if($value=="") $value="n.d.";
        $indirizzo=new AA_JSON_Template_Template($id."_Indirizzo",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Indirizzo:","value"=>$value)
        ));
        
        //Prima riga
        $riga=new AA_JSON_Template_Layout($id."_FirstRow",array("height"=>$rows_fixed_height));
        $riga->AddCol($titolo);
        $layout->AddRow($riga);
        
        //seconda riga
        $riga=new AA_JSON_Template_Layout($id."_SecondRow",array("height"=>$rows_fixed_height));
        $riga->AddCol($descr);
        $layout->AddRow($riga);
        
        //terza riga
        $riga=new AA_JSON_Template_Layout($id."_ThirdRow",array("height"=>38,"type"=>"section","css"=>array("background"=>"#dadee0 !important;")));
        $riga->AddCol(new AA_JSON_Template_Generic($id."_Catasto_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Dati catastali</span>", "align"=>"center")));
        $layout->AddRow($riga);
        
        //Quarta riga
        $riga=new AA_JSON_Template_Layout($id."_FourRow",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($sezione);
        $riga->AddCol($comune);
        $riga->AddCol($classe);
        $layout->AddRow($riga);

        //Quinta riga
        $riga=new AA_JSON_Template_Layout($id."_FiveRow",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($foglio);
        $riga->AddCol($particella);
        $riga->AddCol($rendita);
        $riga->AddCol($consistenza);
        $layout->AddRow($riga);
        
        //layout ultima riga
        $last_row=new AA_JSON_Template_Layout($id."_LastRow");
        $last_row->addCol($indirizzo);
        
        $layout->AddRow($last_row);
        
        return $layout;
    }
     
    //Task Update Patrimonio
    public function Task_UpdatePatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi di modifica dell'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        return $this->Task_GenericUpdateObject($task,$_REQUEST,false,true);   
    }
    
    //Task trash Patrimonio
    public function Task_TrashPatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $task->SetError("L'utente corrente non ha i permessi per cestinare l'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per cestinare l'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        return $this->Task_GenericTrashObject($task,$_REQUEST,false);
    }
    
    //Task export pdf Patrimonio
    public function Task_PdfExport($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sessVar= AA_SessionVar::Get("SaveAsPdf_ids");
        
        //lista organismi da esportare
        if($sessVar->IsValid())
        {
            $ids = $sessVar->GetValue();
            
            if(is_array($ids))
            {
                foreach($ids as $curId)
                {
                    $organismo=new AA_Patrimonio($curId,$this->oUser);
                    if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_READ)>0)
                    {
                        $ids_final[$curId]=$organismo;
                        unset($organismo);
                    }
                }    
            }
            
            //Esiste almeno un organismo che può essere letto dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $this->Template_PatrimonioPdfExport($ids);
            }
            else
            {
                $task->SetError("Nella selezione non sono presenti dati leggibili dall'utente corrente (".$this->oUser->GetName().").");
                $sTaskLog="<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti organismi leggibili dall'utente corrente (".$this->oUser->GetName().").</error>";
                $task->SetLog($sTaskLog);

                return false;          
            }
        }
        else
        {
            $task->SetError("Non è stata selezionata nessuna voce.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Non è stata selezionata nessuna voce.</error>";
            $task->SetLog($sTaskLog);

            return false;          
        } 
    }
    
    //Task resume Patrimonio
    public function Task_ResumePatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericResumeObject($task,$_REQUEST,false);
    }
    
    //Task publish Patrimonio
    public function Task_PublishPatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericPublishObject($task,$_REQUEST,false);
    }
    
    //Task reassign Patrimonio
    public function Task_ReassignPatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericReassignObject($task,$_REQUEST,false);
    }
    
    //Task delete Patrimonio
    public function Task_DeletePatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        //lista organismi da eliminare
        if($_REQUEST['ids'])
        {
            $ids= json_decode($_REQUEST['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Patrimonio($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_DELETE)>0)
                {
                    $ids_final[$curId]=$organismo;
                    unset($organismo);
                }
            }
            
            //Esiste almeno un organismo che può essere cestinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $count=0;
                foreach( $ids_final as $id=>$organismo)
                {
                    
                    if(!$organismo->Trash($this->oUser,true))
                    {
                        $count++;
                        $result_error["$organismo->GetDenominazione()"]=AA_Log::$lastErrorLog;
                    }
                }
                
                if(sizeof($result_error)>0)
                {
                    $wnd=new AA_GenericWindowTemplate("DeletePatrimonio", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Sono stati eliminati ".(sizeof($ids)-sizeof($result_error))." organismi.<br>I seguenti non sono stati eliminati:")));
                
                    $tabledata=array();
                    foreach($result_error as $org=>$desc)
                    {
                        $tabledata[]=array("Denominazione"=>$org,"Errore"=>$desc);
                    }
                    $table=new AA_JSON_Template_Generic($id."_Table", array(
                        "view"=>"datatable",
                        "scrollX"=>false,
                        "autoConfig"=>true,
                        "select"=>false,
                        "data"=>$tabledata
                    ));
                    $wnd->AddView($table);
                    
                    $sTaskLog="<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog.=$wnd->toBase64();
                    $sTaskLog.="</error>";
                    $task->SetLog($sTaskLog);

                    return false;      
                }
                else
                {
                    $sTaskLog="<status id='status'>0</status><content id='content'>";
                    $sTaskLog.= "Sono stati eliminati ".sizeof($ids_final)." organismi.";
                    $sTaskLog.="</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            }
            else
            {
                $task->SetError("Nella selezione non sono presenti organismi eliminabili dall'utente corrente (".$this->oUser->GetName().").");
                $sTaskLog="<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti organismi eliminabili dall'utente corrente (".$this->oUser->GetName().").</error>";
                $task->SetLog($sTaskLog);

                return false;          
            }
        }
        else
        {
            $task->SetError("Non sono stati selezionati organismi.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Non sono stati selezionati organismi.</error>";
            $task->SetLog($sTaskLog);

            return false;          
        } 
    }
    
    //Task Aggiungi patrimonio
    public function Task_AddNewPatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per aggiungere nuovi elementi</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        return $this->Task_GenericAddNew($task,$_REQUEST,false,true);
    }
    
    //Task modifica organismo
    public function Task_GetPatrimonioModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non può modifcrae l'elemento.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $object= new AA_Patrimonio($_REQUEST['id'],$this->oUser);
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
            $sTaskLog.= $this->Template_GetPatrimonioModifyDlg($object)->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task resume organismo
    public function Task_GetPatrimonioResumeDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
            $sTaskLog.= $this->Template_GetGenericResumeObjectDlg($_REQUEST,"ResumePatrimonio")->toBase64();
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
    public function Task_GetPatrimonioPublishDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
            $sTaskLog.= $this->Template_GetGenericPublishObjectDlg($_REQUEST,"PublishPatrimonio")->toBase64();
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
    
    //Task Riassegna patrimonio
    public function Task_GetPatrimonioReassignDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
         if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
            $sTaskLog.= $this->Template_GetGenericReassignObjectDlg($_REQUEST,"ReassignPatrimonio")->toBase64();
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
    public function Task_GetPatrimonioTrashDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
            $sTaskLog.= $this->Template_GetGenericObjectTrashDlg($_REQUEST,"TrashPatrimonio")->toBase64();
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
       
    //Task dialogo elimina patrimonio
    public function Task_GetPatrimonioDeleteDlg($task)
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
            $sTaskLog.= $this->Template_GetPatrimonioDeleteDlg($_REQUEST)->toBase64();
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
    
    //Task aggiunta organismo
    public function Task_GetPatrimonioAddNewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per istanziare nuovi elementi.</error>";
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetPatrimonioAddNewDlg()->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task filter dlg
    public function Task_GetPatrimonioPubblicateFilterDlg($task)
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
    public function Task_GetPatrimonioBozzeFilterDlg($task)
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
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
        if($params['Titolo']=="") $formData['Titolo']=0;

        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","Titolo"=>0,"nome"=>"","cestinate"=>0,"revisionate"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Pubblicate_Filter", "Parametri di ricerca per le schede pubblicate",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));

        //Revisionate
        $dlg->AddSwitchBoxField("revisionate","Revisionate",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede revisionate."));
        
        //Denominazione
        $dlg->AddTextField("nome","Denominazione",array("bottomLabel"=>"*Filtra in base alla denominazione dell'immobile.", "placeholder"=>"Denominazione..."));
        
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //titolo di possesso
        $options=array(
            array("id"=>"0","value"=>"Qualunque"),
            array("id"=>"1","value"=>"di proprietà"),
            array("id"=>"2","value"=>"posseduto"),
            array("id"=>"4","value"=>"detenuto")
        );
        $dlg->AddSelectField("Titolo","Titolo",array("bottomLabel"=>"*Indicare il titolo di possesso","options"=>$options));
        
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
        if($params['Titolo']=="") $formData['Titolo']=0;
        
        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","Titolo"=>0,"nome"=>"","cestinate"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Bozze_Filter", "Parametri di ricerca per le bozze",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
        
        //Denominazione
        $dlg->AddTextField("nome","Denominazione",array("bottomLabel"=>"*Filtra in base alla denominazione dell'immobile.", "placeholder"=>"Denominazione..."));
        
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //titolo di possesso
        $options=array(
            array("id"=>"0","value"=>"Qualunque"),
            array("id"=>"1","value"=>"di proprietà"),
            array("id"=>"2","value"=>"posseduto"),
            array("id"=>"4","value"=>"detenuto")
        );
        $dlg->AddSelectField("Titolo","Titolo",array("bottomLabel"=>"*Indicare il titolo di possesso","options"=>$options));
        
        return $dlg->GetObject();
    }
    
    //Template pdf export single
    public function Template_PatrimonioPdfExport($ids=array(), $bToBrowser=true,$tipo_organismo="")
    {
        if(!is_array($ids)) return "";
        if(sizeof($ids)==0) return "";
        
        //recupero organismi
        
        $organismi=AA_Patrimonio::Search(array("ids"=>$ids),false,$this->oUser);
        $count = $organismi[0];
        #--------------------------------------------
            
        //nome file
        $filename="pubblicazioni_art22";
        $filename.="-".date("YmdHis");
        $doc = new AA_PDF_RAS_TEMPLATE_A4_PORTRAIT($filename);
        
        $doc->SetDocumentStyle("font-family: sans-serif; font-size: 3mm;");
        $doc->SetPageCorpoStyle("display: flex; flex-direction: column; justify-content: space-between; padding:0;");
        $curRow=0;
        $rowForPage=1;
        $lastRow=$rowForPage-1;
        $curPage=null;
        $curPage_row="";
        $curNumPage=0;
        //$columns_width=array("titolare"=>"10%","incarico"=>"8%","atto"=>"10%","struttura"=>"28%","curriculum"=>"10%","art20"=>"12%","altri_incarichi"=>"10%","1-ter"=>"10%","emolumenti"=>"10%");
        //$columns_width=array("dal"=>"10%","al"=>"10%","inconf"=>"10%","incomp"=>"10%","anno"=>"25%","titolare"=>"50%","tipo_incarico"=>"10%","atto_nomina"=>"10%","struttura"=>"40%","curriculum"=>"25%","altri_incarichi"=>"25%","1-ter"=>"25%","emolumenti"=>"10%");
        $rowContentWidth="width: 99.8%;";

        if($count >1)
        {
            //pagina di intestazione (senza titolo)
            $curPage=$doc->AddPage();
            $curPage->SetCorpoStyle("display: flex; flex-direction: column; justify-content: center; align-items: center; padding:0;");
            $curPage->SetFooterStyle("border-top:.2mm solid black");
            $curPage->ShowPageNumber(false);

            //Intestazione
            $intestazione="<div style='width: 100%; text-align: center; font-size: 24; font-weight: bold'>Pubblicazioni ai sensi dell'art.22 del d.lgs. 33/2013</div>";
            $intestazione.="<div style='width: 100%; text-align: center; font-size: x-small; font-weight: normal;margin-top: 3em;'>documento generato il ".date("Y-m-d")."</div>";

            $curPage->SetContent($intestazione);
            $curNumPage++;

            //pagine indice (50 nominativi per pagina)
            $indiceNumVociPerPagina=50;
            for($i=0; $i<$count/$indiceNumVociPerPagina; $i++)
            {
              $curPage=$doc->AddPage();
              $curPage->SetCorpoStyle("display: flex; flex-direction: column; padding:0;");
              $curNumPage++;
            }
            #---------------------------------------
        }
        
        //Imposta il titolo per le pagine successive
        $doc->SetTitle("Pubblicazioni ai sensi dell'art.22 del d.lgs. 33/2013 - report generato il ".date("Y-m-d"));
  
        $indice=array();
        $lastPage=$count/$rowForPage+$curNumPage;
        
        //Rendering pagine
        foreach($organismi[1] as $id=>$curPatrimonio)
        {
            //inizia una nuova pagina (intestazione)
            if($curRow==$rowForPage) $curRow=0; 
            if($curRow==0)
            {
              $border="";
              if($curPage != null) $curPage->SetContent($curPage_row);
              $curPage=$doc->AddPage();
              $curNumPage++;
              //$curPage->SetCorpoStyle("display: flex; flex-direction: column;  justify-content: space-between; padding:0; border: 1px solid black");
              $curPage_row="";
            }

            $indice[$curPatrimonio->GetID()]=$curNumPage."|".$curPatrimonio->GetDescrizione();
            $curPage_row.="<div id='".$curPatrimonio->GetID()."' style='display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; text-align: center; padding: 0mm; min-height: 9mm;'>";

            $template=""; //new AA_PatrimonioPublicReportTemplateView("report_organismo_pdf_".$curPatrimonio->GetId(),null,$curPatrimonio,$this->oUser);

            //AA_Log::Log($template,100,false,true);

            $curPage_row.=$template;
            $curPage_row.="</div>";
            $curRow++;          
        }
        if($curPage != null) $curPage->SetContent($curPage_row);
        #-----------------------------------------
        
        if($count > 1)
        {
            //Aggiornamento indice
            $curNumPage=1;
            $curPage=$doc->GetPage($curNumPage);
            $vociCount=0;
            $curRow=0;
            $bgColor="";
            $curPage_row="";

            foreach($indice as $id=>$data)
            {
              if($curNumPage != (int)($vociCount/$indiceNumVociPerPagina)+1)
              {
                $curPage->SetContent($curPage_row);
                $curNumPage=(int)($vociCount/$indiceNumVociPerPagina)+1;
                $curPage=$doc->GetPage($curNumPage);
                $curRow=0;
                $bgColor="";
              }

              if($curPage instanceof AA_PDF_Page)
              {
                if($vociCount%2 > 0)
                {
                  $dati=explode("|",$data);
                  $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#".$id."'>".$dati['1']."</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#".$id."'>pag. ".$dati[0]."</a></div>";
                  $curPage_row.="</div>";
                  if($vociCount == (sizeof($indice)-1)) $curPage->SetContent($curPage_row);
                  $curRow++;
                }
                else
                {
                  //Intestazione
                  if($curRow==0) $curPage_row="<div style='width:100%;text-align: center; font-size: 18px; font-weight: bold; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .3em;'>Indice</div>";

                  if($curRow%2) $bgColor="background-color: #f5f5f5;";
                  else $bgColor="";
                  $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";
                  $dati=explode("|",$data);
                  $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#".$id."'>".$dati['1']."</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#".$id."'>pag. ".$dati[0]."</a></div>";

                  //ultima voce
                  if($vociCount == (sizeof($indice)-1))
                  {
                    $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'>&nbsp; </div><div style='width:9%;text-align: right;padding-left: 10mm'>&nbsp; </div></div>";
                    $curPage->SetContent($curPage_row);
                  } 
                }
              }

              $vociCount++;
            }            
        }

        if($bToBrowser) $doc->Render();
        else
        {
            $doc->Render(false);
            return $doc->GetFilePath();
        }
    }
}
