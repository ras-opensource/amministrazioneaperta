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
        $this->SetBind("IdData","id");
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
        parent::__construct($id,$user,true);
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

        return self::AddNew($object,$user,$bStandardCheck,$bSaveData);
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
    const AA_UI_TASK_TRASH_DLG="GetPatrimonioTaskDlg";
    const AA_UI_TASK_RESUME_DLG="GetPatrimonioResumeDlg";
    const AA_UI_TASK_DELETE_DLG="GetPatrimonioDeleteDlg";
    const AA_UI_TASK_ADDNEW_DLG="GetPatrimonioAddNewDlg";
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
        return $this->GetDataGenericSectionPubblicate_List($params);
    }

    //Personalizza il filtro delle schede pubblicate per il modulo corrente
    protected function GetDataSectionPubblicate_CustomFilter($params = array())
    {
        return array();
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

        return $this->GetDataGenericSectionBozze_List($params);
    }

    //Personalizza il filtro delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomFilter($params = array())
    {
         return array();
    }
    
    //Template patrimonio trash dlg
    public function Template_GetPatrimonioTrashDlg($params)
    {
        //lista organismi da cestinare
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

            $id=$this->id."_TrashDlg";

            //Esiste almeno un organismo che può essere cestinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $forms_data['ids']=json_encode(array_keys($ids_final));
                $wnd=new AA_GenericFormDlg($id, "Cestina", $this->id, $forms_data,$forms_data);
               
                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata=array();
                foreach($ids_final as $id_org=>$desc)
                {
                    $tabledata[]=array("Denominazione"=>$desc);
                }

                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." organismi verranno cestinati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente organismo verrà cestinato, vuoi procedere?")));

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
                $wnd->SetSaveTask('TrashPatrimonio');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per cestinare gli organismi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }
            
            return $wnd;
        }
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
    
    //Template organismo resume dlg
    public function Template_GetPatrimonioResumeDlg($params)
    {
        //lista organismi da ripristinare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Patrimonio($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE)>0)
                {
                    $ids_final[$curId]=$organismo->GetDescr();
                    unset($organismo);
                }
            }

            $id=$this->id."_ResumeDlg";

            //Esiste almeno un organismo che può essere ripristinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $forms_data['ids']=json_encode(array_keys($ids_final));
                
                $wnd=new AA_GenericFormDlg($id, "Ripristina", $this->id, $forms_data,$forms_data);
               
                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata=array();
                foreach($ids_final as $id_org=>$desc)
                {
                    $tabledata[]=array("Denominazione"=>$desc);
                }

                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." organismi verranno ripristinati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente organismo verrà ripristinato, vuoi procedere?")));

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
                $wnd->SetSaveTask('ResumePatrimonio');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per ripristinare gli organismi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }
            
            return $wnd;
        }
    }
    
    //Template organismo reassign dlg
    public function Template_GetPatrimonioReassignDlg($params)
    {
        //lista organismi da ripristinare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Patrimonio($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE)>0)
                {
                    $ids_final[$curId]=$organismo->GetDescr();
                    unset($organismo);
                }
            }

            $id=$this->id."_ReassignDlg";

            //Esiste almeno un organismo che può essere ripristinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $forms_data['ids']=json_encode(array_keys($ids_final));
                $struct=$this->oUser->GetStruct();
                $forms_data['id_assessorato']=$struct->GetAssessorato(true);
                $forms_data['id_direzione']=$struct->GetDirezione(true);
                $forms_data['id_servizio']=0;
                if($forms_data['id_direzione'] > 0) $forms_data['struct_desc']=$struct->GetDirezione();
                else $forms_data['struct_desc']=$struct->GetAssessorato();
                
                $wnd=new AA_GenericFormDlg($id, "Riassegna", $this->id, $forms_data,$forms_data);
                
                //Aggiunge il campo per la struttura di riassegnazione
                $wnd->AddStructField(array("hideServices"=>1,"targetForm"=>$wnd->GetFormId()),array("select"=>true, "bottomLabel"=>"*Seleziona la struttura di riassegnazione."));
            
                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata=array();
                foreach($ids_final as $id_org=>$desc)
                {
                    $tabledata[]=array("Denominazione"=>$desc);
                }
                
                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." organismi verranno riassegnati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente organismo verrà riassegnato, vuoi procedere?")));

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
                $wnd->SetSaveTask('ReassignPatrimonio');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per ripristinare gli organismi selezionati.</p>")));
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
        $wnd->AddSelectField("Titolo","Titolo",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il titolo.","placeholder"=>"Scegli una voce...","options"=>$options,"value"=>"1"));
        
        //Nome
        $wnd->AddTextField("nome","Denominazione",array("required"=>true, "bottomLabel"=>"*Inserisci la denominazione dell'immobile", "placeholder"=>"inserisci qui la denominazione dell'immobile/terreno"));
        
        //Descrizione
        $label="Descrizione";
        $wnd->AddTextareaField("Descrizione",$label,array("bottomLabel"=>"*Breve descrizione dell'immobile.", "required"=>true,"placeholder"=>"Inserisci qui la descrizione dell'immobile/terreno"));

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
        $catasto->AddTextField("Indirizzo",$label,array("bottomLabel"=>"*Inserire l'indirizzo dell'immobile.", "required"=>true,"placeholder"=>"Inserisci qui l'indirizzo"));
        
        $wnd->AddGenericObject($catasto);

        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->SetSaveTask("AddNewPatrimonio");
        
        return $wnd;
    }
    
    //Template dlg modify immobile
    public function Template_GetPatrimonioModifyDlg($object=null)
    {
        $id="AA_Patrimonio_GetPatrimonioModifyDlg";
        if(!($object instanceof AA_Patrimonio)) return new AA_GenericWindowTemplate($id, "Modifica i dati generali", $this->id);

        $form_data['id']=$object->GetID();
        foreach($object->GetDbBindings() as $id_obj=>$field)
        {
            $form_data[$id_obj]=$object->GetProp($id_obj);
        }
        
        $wnd=new AA_GenericFormDlg($id, "Modifica i dati generali", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(160);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(720);
        
        //Descrizione
        $wnd->AddTextField("sDescrizione","Denominazione",array("required"=>true, "bottomLabel"=>"*Denominazione dell'organismo", "placeholder"=>"inserisci qui la denominazione dell'organismo"));
    
        //partita iva
        $wnd->AddTextField("sPivaCf","Partita iva/cf",array("bottomLabel"=>"*Riportare la partita iva dell'organismo.", "placeholder"=>"inserisci qui la partita iva o il cf dell'organismo"));
        
        //sede
        $wnd->AddTextField("sSedeLegale","Sede legale",array("bottomLabel"=>"*Sede legale dell'organismo.", "placeholder"=>"inserisci qui l'indirizzo della sede legale dell'organismo"));
        
        //pec
        $label="PEC";
        $wnd->AddTextField("sPec",$label,array("bottomLabel"=>"*".$label." dell'organismo.","placeholder"=>"Inserisci qui l'indirizzo pec"));
        
        //sito web
        $label="Sito web";
        $wnd->AddTextField("sSitoWeb",$label,array("bottomLabel"=>"*URL ".$label." dell'organismo.", "placeholder"=>"Inserisci qui l'url del sito web"), false);
        
        //Funzioni
        $label="Funzioni attribuite";
        $wnd->AddTextareaField("sFunzioni",$label,array("bottomLabel"=>"*".$label." all'organismo.", "required"=>true,"placeHolder"=>"Inserisci qui le funzioni attribuite"));
        
        //note
        $label="Note";
        $wnd->AddTextareaField("sNote",$label,array("placeholder"=>"Riporta qui le eventuali note"));

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdatePatrimonio");
        
        return $wnd;
    }
    
    //Template Detail
    public function TemplateSection_Detail($params)
    {
        $id="AA_Patrimonio_Detail_";
        $organismo= new AA_Patrimonio($params['id'],$this->oUser);
        if(!$organismo->isValid())
        {
            return new AA_JSON_Template_Template(
                        "AA_Patrimonio_Detail_Content_Box",
                        array("update_time"=>Date("Y-m-d H:i:s"),
                        "name"=>"Dettaglio scheda organismo",
                        "type"=>"clean","template"=>AA_Log::$lastErrorLog));            
        }
        
        #Stato
        if($organismo->GetStatus() & AA_Const::AA_STATUS_BOZZA) $status="bozza";
        if($organismo->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $status="pubblicata";
        if($organismo->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $status.=" revisionata";
        if($organismo->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $status.=" cestinata";
        $status="<span class='AA_Label AA_Label_LightBlue' title='Stato scheda organismo'>".$status."</span>";
        
        #Dettagli
        if($this->oUser->IsSuperUser() && $organismo->GetAggiornamento() != "") $details="<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;".$organismo->GetAggiornamento(true)."</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Utente'><span class='mdi mdi-account'></span>&nbsp;</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;".$organismo->GetId()."</span>";
        else
        {
            if($organismo->GetAggiornamento() != "") $details="<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;".$organismo->GetAggiornamento(true)."</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;".$organismo->GetId()."</span>";
        }
        
        $perms=$organismo->GetUserCaps($this->oUser);
        $id_org=$organismo->GetID();
        
        if(($perms & AA_Const::AA_PERMS_WRITE) ==0) $details.="&nbsp;<span class='AA_Label AA_Label_LightBlue' title=\" L'utente corrente non può apportare modifiche all'organismo\"><span class='mdi mdi-pencil-off'></span>&nbsp; sola lettura</span>";
        
        $header=new AA_JSON_Template_Layout($id."Header"."_$id_org",array("type"=>"clean", "height"=>38,"css"=>"AA_SectionContentHeader"));
        $header->addCol(new AA_JSON_Template_Generic($id."TabBar"."_$id_org",array(
            "view"=>"tabbar",
            "borderless"=>true,
            "value"=>$id."Generale_Tab"."_$id_org",
            "css"=>"AA_Header_TabBar",
            "width"=>400,
            "multiview"=>true,
            "view_id"=>$id."Multiview"."_$id_org",
            "options"=>array(
                array("id"=>$id."Generale_Tab"."_$id_org", "value"=>"Generale"),
                array("id"=>$id."DatiContabili_Tab"."_$id_org","value"=>"Dati contabili", "tooltip"=>"Dati contabili e dotazione organica"),
                array("id"=>$id."Nomine_Tab"."_$id_org","value"=>"Nomine"))
        )));
        $header->addCol(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $header->addCol(new AA_JSON_Template_Generic($id."Detail"."_$id_org",array(
            "view"=>"template",
            "borderless"=>true,
            "css"=>"AA_SectionContentHeader",
            "minWidth"=>500,
            "template"=>"<div style='display: flex; width:100%; height: 100%; justify-content: center; align-items: center;'>#status#<span>&nbsp;&nbsp;</span><span>#detail#</span></div>",
            "data"=>array("detail"=>$details,"status"=>$status)
        )));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar"."_$id_org",array(
            "type"=>"clean",
            "css"=>array("background"=>"#ebf0fa","border-color"=>"transparent"),
            "width"=>400
        ));
        
        //Inserisce il pulsante di pubblicazione
        if(($perms & AA_Const::AA_PERMS_PUBLISH) > 0 && ($organismo->GetStatus()&AA_Const::AA_STATUS_BOZZA) > 0 && ($organismo->GetStatus()&AA_Const::AA_STATUS_CESTINATA) == 0)
        {
            $menu_data[]= array(
                            "id"=>$this->id."_Publish"."_$id_org",
                            "value"=>"Pubblica",
                            "tooltip"=>"Pubblica l'elemento",
                            "icon"=>"mdi mdi-certificate",
                            "module_id"=>$this->id,
                            "handler"=>"sectionActionMenu.publish",
                            "handler_params"=>array("task"=>"GetPatrimonioPublishDlg","object_id"=>$organismo->GetID())
                        );
        }
        
        //Inserisce il pulsante di riassegnazione ripristino
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0)
        {
            if(($organismo->GetStatus()&AA_Const::AA_STATUS_CESTINATA) == 0)
            {
                //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
                //$menu_spacer=true;
                $menu_data[]= array(
                        "id"=>$this->id."_Reassign"."_$id_org",
                        "value"=>"Riassegna",
                        "tooltip"=>"Riassegna l'elemento",
                        "icon"=>"mdi mdi-share-all",
                        "module_id"=>$this->id,
                        "handler"=>"sectionActionMenu.reassign",
                        "handler_params"=>array("task"=>"GetPatrimonioReassignDlg","object_id"=>$organismo->GetID())
                    );                
            }
            if(($organismo->GetStatus() & AA_Const::AA_STATUS_CESTINATA) > 0)
            {
                $menu_data[]= array(
                        "id"=>$id."_Resume"."_$id_org",
                        "value"=>"Ripristina",
                        "tooltip"=>"Ripristina gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                        "icon"=>"mdi mdi-recycle",
                        "module_id"=>$this->id,
                        "handler"=>"sectionActionMenu.resume",
                        "handler_params"=>array("task"=>"GetPatrimonioResumeDlg","object_id"=>$organismo->GetID())
                    );
            }
        }
        
        //Inserisce le voci di esportazione
        //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
        //$menu_spacer=true;
        $menu_data[]= array(
                    "id"=>$id."_SaveAsPdf"."_$id_org",
                    "value"=>"Esporta in pdf",
                    "tooltip"=>"Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file pdf",
                    "icon"=>"mdi mdi-file-pdf",
                    "module_id"=>$this->id,
                    "handler"=>"sectionActionMenu.saveAsPdf",
                    "handler_params"=>array("task"=>"GetPatrimonioSaveAsPdfDlg","object_id"=>$organismo->GetID())
                );  
        $menu_data[]= array(
                    "id"=>$id."_SaveAsCsv"."_$id_org",
                    "value"=>"Esporta in csv",
                    "tooltip"=>"Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file csv",
                    "icon"=>"mdi mdi-file-table",
                    "module_id"=>$this->id,
                    "handler"=>"sectionActionMenu.saveAsCsv",
                    "handler_params"=>array("task"=>"GetPatrimonioSaveAsCsvDlg","object_id"=>$organismo->GetID())
                );
        #-------------------------------------
        
        //Inserisce la voce di eliminazione
        if(($perms & AA_Const::AA_PERMS_DELETE) > 0)
        {
            if(($organismo->GetStatus() & AA_Const::AA_STATUS_CESTINATA) == 0)
            {
                //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
                //$menu_spacer=true;
                
                $menu_data[]= array(
                            "id"=>$id."_Trash"."_$id_org",
                            "value"=>"Cestina",
                            "css"=>"AA_Menu_Red",
                            "tooltip"=>"Cestina l'elemento",
                            "icon"=>"mdi mdi-trash-can",
                            "module_id"=>$this->id,
                            "handler"=>"sectionActionMenu.trash",
                            "handler_params"=>array("task"=>"GetPatrimonioTrashDlg","object_id"=>$organismo->GetID())
                        );
            }
            else
            {
                
                $menu_data[]= array(
                            "id"=>$id."_Delete"."_$id_org",
                            "value"=>"Elimina",
                            "css"=>"AA_Menu_Red",
                            "tooltip"=>"Elimina definitivamente l'elemento",
                            "icon"=>"mdi mdi-trash-can",
                            "module_id"=>$this->id,
                            "handler"=>"sectionActionMenu.delete",
                            "handler_params"=>array("task"=>"GetPatrimonioDeleteDlg","object_id"=>$organismo->GetID())
                        );
            }
        }
        
        //Azioni
        $scriptAzioni="try{"
                . "let azioni_btn=$$('".$id."_Azioni_btn_$id_org');"
                . "if(azioni_btn){"
                . "let azioni_menu=webix.ui(azioni_btn.config.menu_data);"
                . "if(azioni_menu){"
                . "azioni_menu.setContext(azioni_btn);"
                . "azioni_menu.show(azioni_btn.\$view);"
                . "}"
                . "}"
                . "}catch(msg){console.error('".$id."_Azioni_btn_$id_org',this,msg);AA_MainApp.ui.alert(msg);}";
        $azioni_btn=new AA_JSON_Template_Generic($id."_Azioni_btn"."_$id_org",array(
            "view"=>"button",
            "type"=>"icon",
            "icon"=>"mdi mdi-dots-vertical",
            "label"=>"Azioni",
            "align"=>"right",
            "autowidth"=>true,
            "menu_data"=>new AA_JSON_Template_Generic($id."_ActionMenu"."_$id_org",array("view"=>"contextmenu","data"=>$menu_data, "module_id"=>$this->GetId(),"on"=>array("onItemClick"=>"AA_MainApp.utils.getEventHandler('onDetailMenuItemClick','".$this->GetId()."')"))),
            "tooltip"=>"Visualizza le azioni disponibili",
            "click"=>$scriptAzioni
        ));
        
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $toolbar->addElement($azioni_btn);
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>15)));
        
        $header->addCol(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $header->addCol($toolbar);
        
        //Content box
        $content = new AA_JSON_Template_Layout($id."Content_Box",
                array(
                "type"=>"clean",
                "name"=>$organismo->GetName(),
                "filtered"=>true
            ));
        $content->AddRow($header);
        
        $multiview=new AA_JSON_Template_Multiview($id."Multiview"."_$id_org",array(
            "type"=>"clean",
            "css"=>"AA_Detail_Content"
         ));
        $multiview->addCell($this->TemplateDettaglio_Generale_Tab($organismo));
        $multiview->addCell($this->TemplateDettaglio_DatiContabili_Tab($organismo));
        $content->AddRow($multiview);
        
        return $content;
    }
    
    //Template section detail, tab generale
    public function TemplateDettaglio_Generale_Tab($object=null)
    {
        $id="AA_Patrimonio_Detail_Generale_Tab_".$object->GetId();
        $rows_fixed_height=50;
        if(!($object instanceof AA_Patrimonio)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38));
        
        $soc_tags="";

        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //Pulsante di modifica
        $canModify=false;
        if(($object->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;
        if($canModify)
        {            
            $modify_btn=new AA_JSON_Template_Generic($id."_Modify_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil",
                "label"=>"Modifica",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Modifica le informazioni generali",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetPatrimonioModifyDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        
        $layout->addRow($toolbar);

        //Piva
        $value=$object->GetPivaCf();
        if($value=="")$value="n.d.";
        $piva=new AA_JSON_Template_Template($id."_Piva",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Partita iva/codice fiscale:","value"=>$value)
        ));
        
        //Sede legale
        $value=$object->GetSedeLegale();
        if($value=="")$value="n.d.";
        $sede_legale=new AA_JSON_Template_Template($id."_SedeLegale",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Sede legale:","value"=>$value)
        ));

        //Pec
        $value=$object->GetPec();
        if($value=="") $value="n.d.";
        $pec=new AA_JSON_Template_Template($id."_Pec",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"PEC:","value"=>$value)
        ));
        
        //Sito web
        $value="<a href='".$object->GetSitoWeb()."' target='_blank'>".$object->GetSitoWeb()."</a>";
        if($object->GetSitoWeb()=="")$value="n.d.";
        $sito=new AA_JSON_Template_Template($id."_SitoWeb",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Sito web:","value"=>$value)
        ));
        
        //Funzioni attribuite
        $value=$object->GetFunzioni();
        if($value=="")$value="n.d.";
        $funzioni=new AA_JSON_Template_Template($id."_Funzioni",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Funzioni attribuite:","value"=>$value)
        ));
        
        //data inizio
        $value=$object->GetDataInizioImpegno();
        if($value=="0000-00-00") $value="n.d.";
        $data_inizio=new AA_JSON_Template_Template($id."_DataInizio",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Data inizio impegno:","value"=>$value)
        ));
        
        //data costituzione
        $value=$object->GetDataInizioImpegno();
        if($value=="0000-00-00") $value="n.d.";
        $data_costituzione=new AA_JSON_Template_Template($id."_DataInizio",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Data costituzione:","value"=>$value)
        ));    

        //data fine
        $value=$object->GetDataFineImpegno();
        if($value=="0000-00-00") $value="n.d.";
        if($value=="9999-12-31") $value="a tempo indeterminato";
        $data_fine=new AA_JSON_Template_Template($id."_DataFine",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Data fine impegno:","value"=>$value)
        ));
        
        //data cessazione
        $value=$object->GetDataFineImpegno();
        if($value=="0000-00-00") $value="n.d.";
        if($value=="9999-12-31") $value="a tempo indeterminato";
        $data_cessazione=new AA_JSON_Template_Template($id."_DataFine",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Data cessazione:","value"=>$value)
        ));
        
        //partecipazione
        $value=$object->GetPartecipazione();
        if($value=="" || $value=="0") $value="indiretta";
        else
        {
            $part=explode("/",$value);
            $value="€ ".$part[0]." pari al ".$part[1]."% delle quote totali";
        }
        $partecipazione=new AA_JSON_Template_Template($id."_DataFine",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Partecipazione:","value"=>$value)
        ));
        
        //Stato
        $value=$object->GetStatoPatrimonio();
        if($value=="")$value="n.d.";
        $stato_società=new AA_JSON_Template_Template($id."_DataFine",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Stato società:","value"=>$value)
        ));        
        
        //note
        $value=$object->GetNote();
        if($value=="")$value="n.d.";
        $note=new AA_JSON_Template_Template($id."_Note",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Note:","value"=>$value)
        ));
        
        //Prima riga
        $riga=new AA_JSON_Template_Layout($id."_FirstRow",array("height"=>$rows_fixed_height,"css"=>array("border-top"=>"1px solid #dadee0 !important")));
        $riga->AddCol($piva);
        if(($object->GetTipologia(true)&AA_Patrimonio_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0) $riga->AddCol($data_inizio);
        else $riga->AddCol($data_costituzione);
        $layout->AddRow($riga);
        
        //seconda riga
        $riga=new AA_JSON_Template_Layout($id."_SecondRow",array("height"=>$rows_fixed_height));
        $riga->AddCol($sede_legale);
        if(($object->GetTipologia(true)&AA_Patrimonio_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0) $riga->AddCol($data_fine);
        else $riga->AddCol($data_cessazione);
        $layout->AddRow($riga);
        
        //terza riga
        $riga=new AA_JSON_Template_Layout($id."_ThirdRow",array("height"=>$rows_fixed_height));
        $riga->AddCol($pec);
        if(($object->GetTipologia(true)&AA_Patrimonio_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0) $riga->AddCol($partecipazione);
        else $riga->AddCol(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $layout->AddRow($riga);
        
        //Quarta riga
        $riga=new AA_JSON_Template_Layout($id."_FourRow",array("height"=>$rows_fixed_height));
        $riga->AddCol($sito);
        if(($object->GetTipologia(true)&AA_Patrimonio_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0) $riga->AddCol($stato_società);
        else $riga->AddCol(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $layout->AddRow($riga);
        
        //layout ultima riga
        $last_row=new AA_JSON_Template_Layout($id."_LastRow");
        $riga=new AA_JSON_Template_Layout($id."_FiveRow",array("css"=>array("border-top"=>"1px solid #dadee0 !important")));
        
        //funzioni
        $riga->AddRow($funzioni);
        
        //note
        $riga->AddRow($note);
        $last_row->AddCol($riga);
        
        $layout->AddRow($last_row);
        
        return $layout;
    }
    
    //Template section detail, tab dati contabili
    public function TemplateDettaglio_DatiContabili_Tab($object=null)
    {
        $id="AA_Patrimonio_Detail_DatiContabili_Tab_".$object->GetID();
        if(!($object instanceof AA_Patrimonio)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
        //flag società
        if(($object->GetTipologia(true)&AA_Patrimonio_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0) $società=true;
        else $società=false;
        
        //righe con altezza imposta
        $rows_fixed_height=50;
        
        //permessi
        $perms = $object->GetUserCaps($this->oUser);
        $canModify=false;
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;
        
        //layout generale
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"width"=>130));
        
        if($canModify)
        {            
            //Pulsante di Aggiunta dato contabile
            $addnew_btn=new AA_JSON_Template_Generic($id."_AddNew_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi annualità",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetPatrimonioAddNewDatoContabileDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));
            $toolbar->AddElement($addnew_btn);
        }
        
        $header=new AA_JSON_Template_Layout($id."_Header",array("type"=>"clean", "height"=>38, "css"=>"AA_SectionContentHeader"));
        
        $tabbar=new AA_JSON_Template_Generic($id."_TabBar",array(
            "view"=>"tabbar",
            "borderless"=>true,
            "css"=>"AA_Bottom_TabBar",
            "multiview"=>true,
            "optionWidth"=>100,
            "view_id"=>$id."_Multiview",
            "type"=>"bottom"
        ));
        
        $header->AddCol($tabbar);
        $header->AddCol(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $header->AddCol($toolbar);
        
        $multiview=new AA_JSON_Template_Multiview($id."_Multiview",array(
            "type"=>"clean",
            "css"=>"AA_Detail_Content"
         ));
        $layout->AddRow($multiview);
        $layout->addRow($header);
        
        //Aggiunge gli anni come tab
        $dati_contabili=$object->GetDatiContabili();
        $options=array();
        
        foreach($dati_contabili as $idDato=>$curDato)
        {
            $anno=$curDato->GetAnno();
            if($canModify) $label="<div style='display: flex; justify-content: space-between; align-items: center; padding-left: 5px; padding-right: 5px;'><span>".$anno."</span><a style='margin-left: 1em;' class='AA_DataTable_Ops_Button_Red' title='Elimina annualità' onClick='".'AA_MainApp.utils.callHandler("dlg", {task:"GetPatrimonioTrashDatoContabileDlg", params: [{id: "'.$object->GetId().'"},{id_dato_contabile:"'.$curDato->GetId().'"}]},"'.$this->id.'")'."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $label="<div style='display: flex; justify-content: center; align-items: center; padding-left: 5px; padding-right: 5px;'><span>".$anno."</span></div>";
            $options[]=array("id"=>$id."_".$curDato->GetID()."_Tab", "id_rec"=>$idDato, "value"=>$label);
            
            $curAnno=new AA_JSON_Template_Layout($id."_".$curDato->GetID()."_Tab",array("type"=>"clean"));
            
            $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38, "css"=>"AA_Header_Tabbar_Title"));
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
            $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_".$curDato->GetID(),array("view"=>"label","label"=>"<span style='color:#003380'>Dati contabili e dotazione organica - anno ".$anno."</span>", "align"=>"center")));
                
            //Pulsante di Modifica dato contabile
            if($canModify)
            {
                $modify_btn=new AA_JSON_Template_Generic($id."_Modify_".$curDato->GetID()."_btn",array(
                   "view"=>"button",
                    "type"=>"icon",
                    "icon"=>"mdi mdi-pencil",
                    "label"=>"Modifica",
                    "align"=>"right",
                    "width"=>120,
                    "tooltip"=>"Modifica dati contabili e dotazione organica per l'anno ".$anno,
                    "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetPatrimonioModifyDatoContabileDlg\", params: [{id: ".$object->GetId()."},{id_dato_contabile:".$curDato->GetId()."}]},'$this->id')"
                ));                
                $toolbar->AddElement($modify_btn);
            }
            else
            {
                $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
            }
            
            $curAnno->AddRow($toolbar);
            
            //Oneri totali
            $value=$curDato->GetOneriTotali();
            if($value=="")$value="n.d.";
            $val1=new AA_JSON_Template_Template($id."_OneriTotali_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Oneri totali:","value"=>$value)
            ));

            //Dotazione organica
            $value=$curDato->GetDotazioneOrganica();
            if($value=="") $value="n.d.";
            $val2=new AA_JSON_Template_Template($id."_DotazioneOrganica_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Dotazione organica:","value"=>$value)
            ));
            
            //Prima riga
            $riga=new AA_JSON_Template_Layout($id."_FirstRow_".$curDato->GetID(),array("height"=>$rows_fixed_height));
            $riga->AddCol($val1);$riga->AddCol($val2);
            $curAnno->AddRow($riga);
            
            //Spesa lavoro flessibile
            $value=$curDato->GetSpesaLavoroFlessibile();
            if($value=="")$value="n.d.";
            $val1=new AA_JSON_Template_Template($id."_SpesaLavoroFlessibile_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Spesa lavoro flessibile:","value"=>$value)
            ));

            //Dipendenti
            $value=intVal($curDato->GetDipendenti());
            $value2=intVal($curDato->GetDipendentiDir());
            if($value+$value2 == 0)$value="n.d.";
            if($value2 > 0)
            {
                $value=($value+$value2)." di cui ".$value2." dirigenti";
            }
            $val2=new AA_JSON_Template_Template($id."_Dipendenti_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Personale assunto a tempo indeterminato:","value"=>$value)
            ));

            // riga
            $riga=new AA_JSON_Template_Layout($id."_SecondRow_".$curDato->GetID(),array("height"=>$rows_fixed_height));
            $riga->AddCol($val1);$riga->AddCol($val2);
            $curAnno->AddRow($riga);


            //spesa incarichi
            $value=$curDato->GetSpesaIncarichi();
            if($value=="")$value="n.d.";
            $val1=new AA_JSON_Template_Template($id."_SpesaIncarichi_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Spesa incarichi:","value"=>$value)
            ));

            #Dipendenti a tempo
            $value=intVal($curDato->GetDipendentiDet());
            $value2=intVal($curDato->GetDipendentiDetDir());
            if($value+$value2 == 0)$value="n.d.";
            if($value2 > 0)
            {
                $value=($value+$value2)." di cui ".$value2." dirigenti";
            }
            $val2=new AA_JSON_Template_Template($id."_DipendentiDet_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Personale assunto a tempo determinato:","value"=>$value)
            ));          

            //riga
            $riga=new AA_JSON_Template_Layout($id."_FourRow_".$curDato->GetID(),array("height"=>$rows_fixed_height));
            $riga->AddCol($val1);$riga->AddCol($val2);
            $curAnno->AddRow($riga);

            #Fatturato
            if($società)
            {
                $value=$curDato->GetFatturato();
                if($value=="")$value="n.d.";
                $val1=new AA_JSON_Template_Template($id."_Fatturato_".$curDato->GetID(),array(
                    "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                    "data"=>array("title"=>"Fatturato:","value"=>$value)
                ));
            }
            else
            {
                $val1 = new AA_JSON_Template_Generic("",array("view"=>"spacer"));
            }
            
            //Spesa dotazione 
            $value=$curDato->GetSpesaDotazioneOrganica();
            if($value=="")$value="n.d.";
            $val2=new AA_JSON_Template_Template($id."_SpesaDotazione_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Spesa dotazione organica:","value"=>$value)
            ));

            //riga
            $riga=new AA_JSON_Template_Layout($id."_FiveRow_".$curDato->GetID(),array("height"=>$rows_fixed_height));           
            $riga->AddCol($val1);$riga->AddCol($val2);
            $curAnno->AddRow($riga);
            
            //note
            $value=$curDato->GetNote();
            $val1=new AA_JSON_Template_Template($id."_Note_".$curDato->GetID(),array("height"=>60,
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Note:","value"=>$value)
            ));

            $riga=new AA_JSON_Template_Layout($id."_SixRow_".$curDato->GetID(), array("css"=>array("border-top"=>"1px solid #dadee0 !important")));
            $riga->AddCol($val1);
            $curAnno->AddRow($riga);
            
            #bilanci----------------------------------
           
            $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar_Bilanci_".$curDato->GetID(),array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

            $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_Bilanci_Title_".$curDato->GetID(),array("view"=>"label","label"=>"<span style='color:#003380'>Bilanci e risultati di amministrazione - anno ".$anno."</span>", "align"=>"center")));

            if($canModify)
            {
                //Pulsante di aggiunta bilancio
                $add_bilancio_btn=new AA_JSON_Template_Generic($id."_AddBilancio_".$curDato->GetID()."_btn",array(
                   "view"=>"button",
                    "type"=>"icon",
                    "icon"=>"mdi mdi-pencil-plus",
                    "label"=>"Aggiungi",
                    "align"=>"right",
                    "width"=>120,
                    "tooltip"=>"Aggiungi bilancio per l'anno ".$anno,
                    "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetPatrimonioAddNewBilancioDlg\", params: [{id: ".$object->GetId()."},{id_dato_contabile:".$curDato->GetId()."}]},'$this->id')"
                ));

                $toolbar->AddElement($add_bilancio_btn);
            }
            else 
            {
                $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
            }
            
            $curAnno->AddRow($toolbar);
            
            $options_bilanci=array();
            
            if($canModify)
            {
                $options_bilanci[]=array("id"=>"tipo", "header"=>"Tipo di bilancio", "width"=>250, "css"=>array("text-align"=>"left"));
                $options_bilanci[]=array("id"=>"risultati", "header"=>"Risultati in €", "width"=>350,"css"=>array("text-align"=>"center"));
                $options_bilanci[]=array("id"=>"note", "header"=>"note", "fillspace"=>true,"css"=>array("text-align"=>"left"));
                $options_bilanci[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
            }
            else
            {
                $options_bilanci[]=array("id"=>"tipo", "header"=>"Tipo di bilancio", "width"=>250, "css"=>array("text-align"=>"left"));
                $options_bilanci[]=array("id"=>"risultati", "header"=>"Risultati in €", "width"=>350,"css"=>array("text-align"=>"center"));
                $options_bilanci[]=array("id"=>"note", "header"=>"note", "fillspace"=>true,"css"=>array("text-align"=>"left"));                
            }
            
            $bilanci=new AA_JSON_Template_Generic($id."_Bilanci_".$curDato->GetID(),array("view"=>"datatable", "scrollX"=>false, "select"=>true,"css"=>"AA_Header_DataTable","headerRowHeight"=>28,"columns"=>$options_bilanci));
            
            $bilanci_data=array();
            foreach($curDato->GetBilanci($this->oUser) as $id_bil=>$curBil)
            {
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetPatrimonioModifyBilancioDlg", params: [{id: "'.$object->GetId().'"},{id_dato_contabile:"'.$curDato->GetId().'"},{id_bilancio:"'.$curBil->GetId().'"}]},"'.$this->id.'")';
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetPatrimonioTrashBilancioDlg", params: [{id: "'.$object->GetId().'"},{id_dato_contabile:"'.$curDato->GetId().'"},{id_bilancio:"'.$curBil->GetId().'"}]},"'.$this->id.'")';
                $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
                $bilanci_data[]=array("id"=>$curBil->GetId(),"id_tipo"=>$curBil->GetTipo(),"id_dato_contabile"=>$curDato->GetID(),"id_organismo"=>$object->GetID(),"tipo"=>$curBil->GetTipo(),"risultati"=>$curBil->GetRisultati(),"note"=>$curBil->GetNote(),"ops"=>$ops);
            }
            $bilanci->SetProp("data",$bilanci_data);
            if(sizeof($bilanci_data) > 0) 
            {
                //AA_Log::Log(__METHOD__." Aggiungo il bilancio: ".print_r($bilanci,true),100);
                $curAnno->AddRow($bilanci);
            }
            else $curAnno->AddRow(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
            #--------------------------------------
            
            
            //$multiview->AddCell(new AA_JSON_Template_Generic($id."_ScrollView_".$curDato->GetID()."_Tab",array("view"=>"scrollview","scroll"=>"y","body"=>$curAnno)));
            $multiview->AddCell($curAnno);
        }
        
        $tabbar->SetProp("options",$options);
        
        return $layout;
    }
    
    
     
    //Task Update Patrimonio
    public function Task_UpdatePatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Patrimonio($_REQUEST['id'], $this->oUser);
        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismo non valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        //Aggiorna i dati
        if(!$organismo->ParseData($_REQUEST))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel parsing dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        //Salva i dati
        if(!$organismo->UpdateDb($this->oUser))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salavataggio dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Dati aggiornati con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task trash Patrimonio
    public function Task_TrashPatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        //lista organismi da cestinare
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
                    
                    if(!$organismo->Trash($this->oUser))
                    {
                        $count++;
                        $result_error["$organismo->GetDenominazione()"]=AA_Log::$lastErrorLog;
                    }
                }
                
                if(sizeof($result_error)>0)
                {
                    $wnd=new AA_GenericWindowTemplate("TrashPatrimonio", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Sono stati cestinati ".(sizeof($ids)-sizeof($result_error))." organismi.<br>I seguenti non sono stati cestinati:")));
                
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
                    $sTaskLog.= "SOno stati cestinati ".sizeof($ids_final)." organismi.";
                    $sTaskLog.="</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            }
            else
            {
                $task->SetError("Nella selezione non sono presenti organismi cestinabili dall'utente corrente (".$this->oUser->GetName().").");
                $sTaskLog="<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti organismi cestinabili dall'utente corrente (".$this->oUser->GetName().").</error>";
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
        
        //lista organismi da ripristinare
        if($_REQUEST['ids'])
        {
            $ids= json_decode($_REQUEST['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Patrimonio($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE)>0)
                {
                    $ids_final[$curId]=$organismo;
                    unset($organismo);
                }
            }
            
            //Esiste almeno un organismo che può essere ripristinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $count=0;
                foreach( $ids_final as $id=>$organismo)
                {
                    
                    if(!$organismo->Resume($this->oUser))
                    {
                        $count++;
                        $result_error["$organismo->GetDenominazione()"]=AA_Log::$lastErrorLog;
                    }
                }
                
                if(sizeof($result_error)>0)
                {
                    $wnd=new AA_GenericWindowTemplate("ResumePatrimonio", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Sono stati ripristinati ".(sizeof($ids)-sizeof($result_error))." organismi.<br>I seguenti non sono stati ripristinati:")));
                
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
                    $sTaskLog.= "Sono stati ripristinati ".sizeof($ids_final)." organismi.";
                    $sTaskLog.="</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            }
            else
            {
                $task->SetError("Nella selezione non sono presenti organismi ripristinabili dall'utente corrente (".$this->oUser->GetName().").");
                $sTaskLog="<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti organismi ripristinabili dall'utente corrente (".$this->oUser->GetName().").</error>";
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
    
    //Task publish Patrimonio
    public function Task_PublishPatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        //lista organismi da pubblicare
        if($_REQUEST['ids'])
        {
            $ids= json_decode($_REQUEST['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Patrimonio($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_PUBLISH)>0)
                {
                    $ids_final[$curId]=$organismo;
                    unset($organismo);
                }
            }
            
            //Esiste almeno un organismo che può essere pubblicato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $count=0;
                foreach( $ids_final as $id=>$organismo)
                {
                    if(!$organismo->Publish($this->oUser))
                    {
                        $count++;
                        $result_error["$organismo->GetDenominazione()"]=AA_Log::$lastErrorLog;
                    }
                }
                
                if(sizeof($result_error)>0)
                {
                    $wnd=new AA_GenericWindowTemplate("PublishPatrimonio", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Sono stati pubblicati ".(sizeof($ids)-sizeof($result_error))." organismi.<br>I seguenti non sono stati pubblicati:")));
                
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
                    $sTaskLog.= "Sono stati pubblicati ".sizeof($ids_final)." organismi.";
                    $sTaskLog.="</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            }
            else
            {
                $task->SetError("Nella selezione non sono presenti organismi pubblicabili dall'utente corrente (".$this->oUser->GetName().").");
                $sTaskLog="<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti organismi pubblicabili dall'utente corrente (".$this->oUser->GetName().").</error>";
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
    
    //Task reassign Patrimonio
    public function Task_ReassignPatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        //lista organismi da riassegnare
        if($_REQUEST['ids'])
        {
            $ids= json_decode($_REQUEST['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Patrimonio($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE)>0)
                {
                    $ids_final[$curId]=$organismo;
                    unset($organismo);
                }
            }
            
            //Esiste almeno un organismo che può essere ripristinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $count=0;
                $params['riassegna-id-assessorato']=$_REQUEST['id_assessorato'];
                $params['riassegna-id-direzione']=$_REQUEST['id_direzione'];
                $params['riassegna-id-servizio']=$_REQUEST['id_servizio'];
                foreach( $ids_final as $id=>$organismo)
                {                    
                    if(!$organismo->Reassign($params,$this->oUser))
                    {
                        $count++;
                        $result_error["$organismo->GetDenominazione()"]=AA_Log::$lastErrorLog;
                    }
                }
                
                if(sizeof($result_error)>0)
                {
                    $wnd=new AA_GenericWindowTemplate("ReassignPatrimonio", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Sono stati riassegnati ".(sizeof($ids)-sizeof($result_error))." organismi alla struttura: ".$_REQUEST['struct_desc'].".<br>I seguenti non sono stati riassegnati:")));
                
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
                    $sTaskLog.= "Sono stati riassegnati ".sizeof($ids_final)." organismi.";
                    $sTaskLog.="</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            }
            else
            {
                $task->SetError("Nella selezione non sono presenti organismi riassegnabili dall'utente corrente (".$this->oUser->GetName().").");
                $sTaskLog="<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti organismi riassegnabili dall'utente corrente (".$this->oUser->GetName().").</error>";
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
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $task->SetError("L'utente corrente non ha i permessi per aggiugere nuovi organismi");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per aggiugere nuovi organismi</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        $organismo= AA_Patrimonio::AddNew($_REQUEST, $this->oUser);
        
        if(!($organismo instanceof AA_Patrimonio))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status' id_Rec='".$organismo->GetId()."' action='showDetailView' action_params='".json_encode(array("id"=>$organismo->GetId()))."'>0</status><content id='content'>";
        $sTaskLog.= "Patrimonio aggiunto con successo (identificativo: ".$organismo->GetId().")";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task modifica organismo
    public function Task_GetPatrimonioModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Patrimonio($_REQUEST['id'],$this->oUser);
        if(!$organismo->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Patrimonio non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetPatrimonioModifyDlg($organismo)->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task resume organismo
    public function Task_GetPatrimonioResumeDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per ripristinare organismi.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetPatrimonioResumeDlg($_REQUEST)->toBase64();
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
    
    //Task publish organismo
    public function Task_GetPatrimonioPublishDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per pubblicare organismi.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetPatrimonioPublishDlg($_REQUEST)->toBase64();
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
    
    //Task resume organismo
    public function Task_GetPatrimonioReassignDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per riassegnare organismi.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetPatrimonioReassignDlg($_REQUEST)->toBase64();
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
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per cestinare/eliminare organismi.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetPatrimonioTrashDlg($_REQUEST)->toBase64();
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
    public function Task_GetPubblicateFilterDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->TemplatePubblicateFilterDlg();
        $sTaskLog.= base64_encode($content);
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task filter dlg
    public function Task_GetBozzeFilterDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->TemplateBozzeFilterDlg();
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
    public function TemplatePubblicateFilterDlg()
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$_REQUEST['id_assessorato'],"id_direzione"=>$_REQUEST['id_direzione'],"struct_desc"=>$_REQUEST['struct_desc'],"id_struct_tree_select"=>$_REQUEST['id_struct_tree_select'],"tipo"=>$_REQUEST['tipo'],"denominazione"=>$_REQUEST['denominazione'],"cestinate"=>$_REQUEST['cestinate'], "incaricato"=>$_REQUEST['incaricato']);
        
        //Valori default
        if($_REQUEST['tipo']=="") $formData['tipo']="0";
        if($_REQUEST['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($_REQUEST['id_assessorato']=="") $formData['id_assessorato']=0;
        if($_REQUEST['id_direzione']=="") $formData['id_direzione']=0;
        if($_REQUEST['id_servizio']=="") $formData['id_servizio']=0;
        if($_REQUEST['cestinate']=="") $formData['cestinate']=0;
        
        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","tipo"=>0,"denominazione"=>"","cestinate"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg("AA_Patrimonio_Pubblicate_Filter", "Parametri di ricerca per le schede pubblicate",$this->GetId(),$formData,$resetData,$applyActions);
       
        $dlg->SetHeight(580);
        
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
        
        //Denominazione
        $dlg->AddTextField("denominazione","Denominazione/P.IVA",array("bottomLabel"=>"*Filtra in base alla denominazione o alla partita iva dell'organismo.", "placeholder"=>"Denominazione o piva..."));
        
        //Struttura
        $dlg->AddStructField(array("showAll"=>1,"hideServices"=>1,"targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //Nominato
        $dlg->AddTextField("incaricato","Nominato",array("bottomLabel"=>"*Filtra in base al nome, cognome o cf del nominato.", "placeholder"=>"nome, cognome o cf del nominato..."));
        
        return $dlg->GetObject();
    }
    
    //Template filtro di ricerca
    public function TemplateBozzeFilterDlg()
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$_REQUEST['id_assessorato'],"id_direzione"=>$_REQUEST['id_direzione'],"struct_desc"=>$_REQUEST['struct_desc'],"id_struct_tree_select"=>$_REQUEST['id_struct_tree_select'],"tipo"=>$_REQUEST['tipo'],"denominazione"=>$_REQUEST['denominazione'],"cestinate"=>$_REQUEST['cestinate'],"incaricato"=>$_REQUEST['incaricato']);
        
        //Valori default
        if($_REQUEST['tipo']=="") $formData['tipo']="0";
        if($_REQUEST['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($_REQUEST['id_assessorato']=="") $formData['id_assessorato']=0;
        if($_REQUEST['id_direzione']=="") $formData['id_direzione']=0;
        if($_REQUEST['id_servizio']=="") $formData['id_servizio']=0;
        if($_REQUEST['cestinate']=="") $formData['cestinate']=0;
        
        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","tipo"=>0,"denominazione"=>"","cestinate"=>0,"incaricato"=>"");
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg("AA_Patrimonio_Bozze_Filter", "Parametri di ricerca per le bozze pubblicate",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
        
        //Denominazione
        $dlg->AddTextField("denominazione","Denominazione/P.IVA",array("bottomLabel"=>"*Filtra in base alla denominazione o alla partita iva dell'organismo.", "placeholder"=>"Denominazione o piva..."));
        
        //Struttura
        $dlg->AddStructField(array("hideServices"=>1,"targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //Nominato
        $dlg->AddTextField("incaricato","Nominato",array("bottomLabel"=>"*Filtra in base al nome, cognome o cf del nominato.", "placeholder"=>"nome, cognome o cf del nominato..."));
        
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
