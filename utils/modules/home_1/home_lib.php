<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("config.php");
include_once("system_lib.php");

//Classe per la gestione del modulo home
Class AA_HomeModule extends AA_GenericModule
{
    const AA_UI_PREFIX="AA_Home";

    //Id modulo
    const AA_ID_MODULE="AA_MODULE_HOME";

    //main ui layout box
    const AA_UI_MODULE_MAIN_BOX="AA_Home_module_layout";

    //------- Sezione cruscotto -------
    //Id sezione
    const AA_ID_SECTION_DESKTOP="home_desktop";

    //nome sezione
    const AA_UI_SECTION_DESKTOP_NAME="Cruscotto";

    const AA_UI_SECTION_DESKTOP="Desktop_Content_Box";

    const AA_UI_SECTION_DESKTOP_ICON="mdi mdi-desktop-classic";
    //------------------------------

    //------- Sezione gestione utenti -------
    //Id sezione
    const AA_ID_SECTION_GESTUTENTI="home_gestutenti";

    //nome sezione
    const AA_UI_SECTION_GESTUTENTI_NAME="Gestione utenti";

    const AA_UI_SECTION_GESTUTENTI="Gestutenti_Content_Box";

    const AA_UI_SECTION_GESTUTENTI_ICON="mdi mdi-account-edit";
    //------------------------------

    //------- Sezione gestione strutture -------
    //Id sezione
    const AA_ID_SECTION_GESTSTRUCT="home_geststruct";

    //nome sezione
    const AA_UI_SECTION_GESTSTRUCT_NAME="Gestione strutture";

    const AA_UI_SECTION_GESTSTRUCT="Geststruct_Content_Box";

    const AA_UI_SECTION_GESTSTRUCT_ICON="mdi mdi-office-building-cog";
    //------------------------------

    //istanza
    protected static $oInstance=null;
    
    //Restituisce l'istanza corrente
    public static function GetInstance($user=null)
    {
        if(self::$oInstance==null)
        {
            self::$oInstance=new AA_HomeModule($user);
        }
        
        return self::$oInstance;
    }
    
    public function __construct($user=null) {
        parent::__construct($user,false);
        
        //Sezioni

        $gestutenti=false;
        if($user instanceof AA_User && $user->CanGestUtenti()) $gestutenti =true;
        
        $geststrutture=false;
        if($user instanceof AA_User && $user->CanGestStruct() && AA_Const::AA_ENABLE_LEGACY_DATA) $geststrutture=true;

        //main
        $section=new AA_GenericModuleSection(static::AA_ID_SECTION_DESKTOP,static::AA_UI_SECTION_DESKTOP_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_DESKTOP,$this->GetId(),true,true,false,true);
        if($gestutenti) 
        {
            if($geststrutture) $section->SetNavbarTemplate(array($this->TemplateNavbar_Gestutenti(1,false)->toArray(),$this->TemplateNavbar_GestStruct(2)->toArray()));
            else $section->SetNavbarTemplate(array($this->TemplateNavbar_Gestutenti(1)->toArray()));
        }
        else $section->SetNavbarTemplate($this->TemplateGenericNavbar_Void()->toArray());

        $this->AddSection($section);
        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DESKTOP,"TemplateSection_Desktop");

        if($gestutenti)
        {
            //Gestione utenti
            $section=new AA_GenericModuleSection(static::AA_ID_SECTION_GESTUTENTI,static::AA_UI_SECTION_GESTUTENTI_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GESTUTENTI,$this->GetId(),false,true,false,true);
            if($geststrutture)
            {
                $section->SetNavbarTemplate(array($this->TemplateNavbar_Cruscotto(1,false)->toArray(),$this->TemplateNavbar_Geststruct(2)->toArray()));
            }
            else $section->SetNavbarTemplate($this->TemplateNavbar_Cruscotto()->toArray());
            $this->AddSection($section);
            $this->SetSectionItemTemplate(static::AA_ID_SECTION_GESTUTENTI,"TemplateSection_GestUtenti");
        }
        
        if($geststrutture)
        {
            //Gestione strutture
            $section=new AA_GenericModuleSection(static::AA_ID_SECTION_GESTSTRUCT,static::AA_UI_SECTION_GESTSTRUCT_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GESTSTRUCT,$this->GetId(),false,true,false,true);
            if($gestutenti)
            {
                $section->SetNavbarTemplate(array($this->TemplateNavbar_Cruscotto(1,false)->toArray(),$this->TemplateNavbar_Gestutenti(2)->toArray()));
            }
            else $section->SetNavbarTemplate($this->TemplateNavbar_Cruscotto()->toArray());
            $this->AddSection($section);
            $this->SetSectionItemTemplate(static::AA_ID_SECTION_GESTSTRUCT,"TemplateSection_GestStruct");
        }
        #-------------------------------------------
    }

    //Template navbar cruscotto
    protected function TemplateNavbar_Cruscotto($level=1,$last=true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $id=static::AA_UI_PREFIX . "_Navbar_Link_".uniqid(time());
        $navbar =  new AA_JSON_Template_Template(
            $id,
            array(
                "type" => "clean",
                "css" => "AA_NavbarEventListener",
                "module_id" => $this->GetId(),
                "tooltip" => "Visualizza il cruscotto",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='".$id."' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"".static::AA_ID_SECTION_DESKTOP."\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => static::AA_UI_SECTION_DESKTOP_NAME, "class" => $class,"icon"=>static::AA_UI_SECTION_DESKTOP_ICON)
            )
        );
        return $navbar;
    }

    //Template navbar gestione utenti
    protected function TemplateNavbar_GestUtenti($level=1,$last=true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $id=static::AA_UI_PREFIX . "_Navbar_Link_".uniqid(time());
        $navbar =  new AA_JSON_Template_Template(
            $id,
            array(
                "type" => "clean",
                "css" => "AA_NavbarEventListener",
                "module_id" => $this->GetId(),
                "tooltip" => "Visualizza il la gestione utenti",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='".$id."' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"".static::AA_ID_SECTION_GESTUTENTI."\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => static::AA_UI_SECTION_GESTUTENTI_NAME, "class" => $class,"icon"=>static::AA_UI_SECTION_GESTUTENTI_ICON)
            )
        );
        return $navbar;
    }

    //Template navbar gestione utenti
    protected function TemplateNavbar_GestStruct($level=1,$last=true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $id=static::AA_UI_PREFIX . "_Navbar_Link_".uniqid(time());
        $navbar =  new AA_JSON_Template_Template(
            $id,
            array(
                "type" => "clean",
                "css" => "AA_NavbarEventListener",
                "module_id" => $this->GetId(),
                "tooltip" => "Visualizza il la gestione strutture",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='".$id."' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"".static::AA_ID_SECTION_GESTSTRUCT."\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => static::AA_UI_SECTION_GESTSTRUCT_NAME, "class" => $class,"icon"=>static::AA_UI_SECTION_GESTSTRUCT_ICON)
            )
        );
        return $navbar;
    }

    //Template cruscotto context menu
    public function TemplateActionMenu_Cruscotto()
    {
        $menu=new AA_JSON_Template_Generic(
            static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DESKTOP."_ActionMenu",array(
            "view"=>"contextmenu",
            "data"=>array(array(
                   "id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DESKTOP."_ActionMenuItem_Aggiorna",
                   "value"=>"Aggiorna",
                   "icon"=>"mdi mdi-reload",
                   "module_id" => $this->GetId(),
                   "handler"=>"refreshUiObject",
                   "handler_params"=>array(static::AA_UI_PREFIX."_".static::AA_UI_SECTION_DESKTOP,true)
                ))
            ));
        
        return $menu;  
    }

    //Template gestutenti context menu
    public function TemplateActionMenu_Gestutenti()
    {
        $menu=new AA_JSON_Template_Generic(
            static::AA_UI_PREFIX."_".static::AA_ID_SECTION_GESTUTENTI."_ActionMenu",array(
            "view"=>"contextmenu",
            "data"=>array(array(
                   "id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_GESTUTENTI."_ActionMenuItem_Aggiorna",
                   "value"=>"Aggiorna",
                   "icon"=>"mdi mdi-reload",
                   "module_id" => $this->GetId(),
                   "handler"=>"refreshUiObject",
                   "handler_params"=>array(static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GESTUTENTI,true)
                ))
            ));
        
        return $menu;  
    }

    //Template gestutenti context menu
    public function TemplateActionMenu_GestStruct()
    {
        $menu=new AA_JSON_Template_Generic(
            static::AA_UI_PREFIX."_".static::AA_ID_SECTION_GESTSTRUCT."_ActionMenu",array(
            "view"=>"contextmenu",
            "data"=>array(array(
                   "id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_GESTSTRUCT."_ActionMenuItem_Aggiorna",
                   "value"=>"Aggiorna",
                   "icon"=>"mdi mdi-reload",
                   "module_id" => $this->GetId(),
                   "handler"=>"refreshUiObject",
                   "handler_params"=>array(static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GESTSTRUCT,true)
                ))
            ));
        
        return $menu;  
    }
    
    //Template news content
    public function TemplateSection_News()
    {
        $news_box=new AA_JSON_Template_Layout("AA_Home_News_Box",array("type"=>"space","css"=>"AA_Desktop_Section_Box"));
        $news_layout = new AA_JSON_Template_Generic("AA_Home_News_Content",
                array(
                "view"=>"timeline",
                "css"=>array("background-color"=>"transparent"),
                "type"=>array(
                    "height"=>"auto",
                    "width"=>800,
                    "type"=>"left",
                    "lineColor"=>"skyblue"
                )
            ));
         
        $db = new AA_Database();
        
        $query="SELECT * from news WHERE archivio='0' order by data DESC";
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__."() - errore: ".$db->GetErrorMessage()." nella query: ".$query,100);
            return "";
        }

        $data=array();
        foreach($db->GetResultSet() as $row)
        {
            $data[]=array("id"=>$row['id'],"date"=>$row['data'],"value"=>$row['oggetto'],"details"=>$row['corpo']);
        }
        
        $news_layout->setProp('data',$data);
        
        $news_box->AddRow(new AA_JSON_Template_Generic("HomeNewsBoxTitle",array("view"=>"label","align"=>"center","label"=>"<span class='AA_Desktop_Section_Label'>News</span>")));
        $news_box->AddRow($news_layout);

        return $news_box;
    }

    //Template risorse content
    public function TemplateSection_Risorse()
    {
        $section_box=new AA_JSON_Template_Layout("AA_Home_Risorse_Box",array("type"=>"space","css"=>"AA_Desktop_Section_Box"));
        
        
        $section_box->AddRow(new AA_JSON_Template_Generic("HomeRisorseBoxTitle",array("view"=>"label","align"=>"center","label"=>"<span class='AA_Desktop_Section_Label'>Risorse</span>")));
        $section_box->AddRow(new AA_JSON_Template_Generic());
        return $section_box;
    }


    //Template cruscotto content
    public function TemplateSection_Desktop()
    {
        //AA_Log::Log(__METHOD__,100);
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_DESKTOP;
        $layout = new AA_JSON_Template_Layout($id,array("type"=>"clean","name" => static::AA_UI_SECTION_DESKTOP_NAME));

        $second_row=new AA_JSON_Template_Layout($id."_SecondRowBox",array("type"=>"space","css"=>array("background-color"=>"transparent")));
        $second_row->AddCol($this->TemplateSection_News());
        $second_row->AddCol($this->TemplateSection_Risorse());
        
        $layout->AddRow($second_row);

        //Moduli Row
        $platform=AA_Platform::GetInstance($this->oUser);
        if($platform->IsValid())
        {
            $platform_modules=$platform->GetModules();
            if(sizeof($platform_modules) <=5 ) $moduli_box=new AA_JSON_Template_Layout($id."_ModuliBox",array("type"=>"clean","padding"=>3,"css"=>array("background-color"=>"transparent")));
            else $moduli_box=new AA_JSON_Template_Carousel($id."_ModuliBox",array("type"=>"clean","padding"=>3,"css"=>array("background-color"=>"transparent")));
            if(is_array($platform_modules) && sizeof($platform_modules) > 0)
            {
                $minHeightModuliItem=intval(($_REQUEST['vh']-180)/2);
                //$numModuliBoxForrow=intval(sqrt(sizeof($moduli_data)));
                $WidthModuliItem=intval(($_REQUEST['vw']-110)/4);
                //$HeightModuliItem=intval(/$numModuliBoxForrow);"css"=>"AA_DataView_Moduli_item","margin"=>10

                $riepilogo_template="<div class='AA_DataView_Moduli_item' onclick=\"#onclick#\" style='cursor: pointer; border: 1px solid; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%';>";
                //icon
                $riepilogo_template.="<div style='display: flex; align-items: center; height: 120px; font-size: 90px;'><span class='#icon#'></span></div>";
                //name
                $riepilogo_template.="<div style='display: flex; align-items: center;justify-content: center; flex-direction: column; font-size: larger;height: 60px'>#name#</div>";
                //descr
                //$riepilogo_template.="<div style='display: flex; align-items: center;padding: 10px;height: 120px'><span>#descr#</span></div>";
                //go
                //$riepilogo_template.="<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 48px; padding: 5px'><a title='Apri il modulo' onclick=\"#onclick#\" class='AA_Button_Link'><span>Vai</span>&nbsp;<span class='mdi mdi-arrow-right-thick'></span></a></div>";
                $riepilogo_template.="</div>";

                /*$moduli_view=new AA_JSON_Template_Generic($id."_moduliView",array(
                    "view"=>"dataview",
                    "css"=>array("background-color"=>"transparent","border"=>"0px"),
                    "filtered"=>true,
                    "yCount"=>1,
                    "module_id"=>$this->id,
                    "type"=>array(
                        "type"=>"tiles",
                        "height"=>$minHeightModuliItem,
                        "width"=>$WidthModuliItem,
                        "css"=>"AA_DataView_Moduli_item",
                    ),
                    "template"=>$riepilogo_template,
                    "data"=>$moduli_data,
                    "eventHandlers"=>array("onItemClick"=>array("handler"=>"ModuleBoxClick","module_id"=>$this->GetId()))
                ));*/

                $nSlide=0;
                $nMod=0;
                $moduli_view=new AA_JSON_Template_Layout($id."_ModuliView_".$nSlide,array("type"=>"space","css"=>array("background-color"=>"transparent")));
                foreach($platform_modules as $curModId => $curMod)
                {
                    if($curModId != $this->GetId())
                    {
                        $nMod++;
                        //AA_Log::Log(__METHOD__." - Aggiungo la slide: ".$id."_ModuliView_".$nSlide." - nMod: ".$nMod ,100);

                        $name="<span style='font-weight:900'>".implode("</span><span>",explode("-",$curMod['tooltip']))."</span>";
                        $onclick="AA_MainApp.utils.callHandler('ModuleBoxClick','".$curModId."')";
                        $moduli_data=array("id"=>$curModId,"name"=>$name,'descr'=>$curMod['descrizione'],"icon"=>$curMod['icon'],"onclick"=>$onclick);
                        $moduli_view->AddCol(new AA_JSON_Template_Template($id."_ModuleBox_".$moduli_data['id'],array("template"=>$riepilogo_template,"borderless"=>true,"data"=>array($moduli_data),"eventHandlers"=>array("onItemClick"=>array("handler"=>"ModuleBoxClick","module_id"=>$this->GetId())))));
                        
                        if($nMod%4==0)
                        {
                            if(sizeof($platform_modules) <=5) $moduli_box->AddRow($moduli_view);
                            else $moduli_box->AddSlide($moduli_view);
                            $nSlide++;
                            $moduli_view=new AA_JSON_Template_Layout($id."_ModuliView_".$nSlide,array("type"=>"space"));
                        }
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
                        $moduli_view->addCol(new AA_JSON_Template_Generic());
                    }
                    if(sizeof($platform_modules) <=5 ) $moduli_box->AddRow($moduli_view);
                    else $moduli_box->AddSlide($moduli_view);
                }
            }
            else
            {
                $moduli_view=new AA_JSON_Template_Template($id."_Riepilogo_Tab",array("template"=>"<div style='display: flex; justify-content: center; align-items: center; width: 100%;height:100%'><div>Non sono presenti elementi.</div></div>"));
                $moduli_box->AddRow($moduli_view);
            }
          
            $layout->AddRow($moduli_box);
        }
        
        return $layout;
    }

    //Template gestutenti content
    public function TemplateSection_GestUtenti()
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GESTUTENTI;
        $layout = new AA_JSON_Template_Template($id,array("type"=>"clean","name" => static::AA_UI_SECTION_GESTUTENTI_NAME,"template"=>"In costruzione (gestione utenti)"));

        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean","name" => static::AA_UI_SECTION_GESTUTENTI_NAME));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //Pulsante di modifica
        $canModify=false;
        if($this->oUser->CanGestUtenti()) $canModify=true;
        if($canModify)
        {            
            $modify_btn=new AA_JSON_Template_Generic($id."_AddNew_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-account-plus",
                "label"=>"Aggiungi",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi un nuovo utente",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetHomeAddNewUserDlg\"},'".$this->id."')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        
        $layout->addRow($toolbar);        
        $columns=array(
            array("id"=>"id","header"=>array("<div style='text-align: center'>id</div>",array("content"=>"textFilter")),"width"=>50, "sort"=>"int","css"=>array("text-align"=>"center")),
            array("id"=>"user","header"=>array("<div style='text-align: center'>User</div>",array("content"=>"textFilter")),"width"=>200, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"email","header"=>array("<div style='text-align: center'>Email</div>",array("content"=>"textFilter")),"width"=>250, "css"=>array("text-align"=>"center"),"sort"=>"text"),            
            array("id"=>"denominazione","header"=>array("<div style='text-align: center'>Nome e cognome</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text"),
            array("id"=>"ruolo","header"=>array("<div style='text-align: center'>Ruolo</div>",array("content"=>"selectFilter")),"width"=>150, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"flags","header"=>array("<div style='text-align: center'>Abilitazioni</div>",array("content"=>"textFilter")), "fillspace"=>true,"css"=>array("text-align"=>"center"),"sort"=>"text")
        );

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $columns[]=array("id"=>"assessorato","header"=>array("<div style='text-align: center'>Assessorato</div>",array("content"=>"selectFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text");
            //$columns[]=array("id"=>"direzione","header"=>array("<div style='text-align: center'>Direzione</div>",array("content"=>"selectFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text");
            //$columns[]=array("id"=>"servizio","header"=>array("<div style='text-align: center'>Servizio</div>",array("content"=>"selectFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text");
        }

        if($canModify)
        {
            $columns[]=array("id"=>"ops","header"=>"<div style='text-align: center'>Operazioni</div>","width"=>100, "css"=>array("text-align"=>"center"));
        }

        $utenti=AA_User::Search(null,$this->oUser);
        $data=array();
        if(sizeof($utenti) > 0)
        {
            foreach($utenti as $curUser)
            {
                $flags=$curUser->GetFlags(true);
                if(sizeof($flags)>0) $flags="<span class='AA_Label AA_Label_LightGreen'>".implode("</span><span class='AA_Label AA_Label_LightGreen'>",$flags)."</span>";
                else $flags="";

                if(AA_Const::AA_ENABLE_LEGACY_DATA)
                {
                    $struct=$curUser->GetStruct();
                    $assessorato=$struct->GetAssessorato();
                    $direzione=$struct->GetDirezione();
                    $servizio=$struct->GetServizio();
                    $data[]=array("id"=>$curUser->GetId(),"user"=>$curUser->GetUsername(),"email"=>$curUser->GetEmail(),"denominazione"=>$curUser->GetNome()." ".$curUser->GetCognome(),"ruolo"=>$curUser->GetRuolo(),"flags"=>$flags,
                        "assessorato"=>$assessorato,
                        "direzione"=>$direzione,
                        "servizio"=>$servizio
                    );
                }
                else $data[]=array("id"=>$curUser->GetId(),"user"=>$curUser->GetUsername(),"email"=>$curUser->GetEmail(),"denominazione"=>$curUser->GetNome()." ".$curUser->GetCognome(),"ruolo"=>$curUser->GetRuolo(),"flags"=>$flags);
            }
            $table=new AA_JSON_Template_Generic($id."_UtentiTable", array(
                "view"=>"datatable",
                "scrollX"=>false,
                "select"=>false,
                "css"=>"AA_Header_DataTable",
                "hover"=>"AA_DataTable_Row_Hover",
                "columns"=>$columns,
                "data"=>$data
            ));
    
            $layout->addRow($table);
        }
        else
        {
            $layout->addRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Non sono presenti utenti.</span></div>")));
        }

        return $layout;
    }

    //Template gestione strutture content
    public function TemplateSection_GestStruct()
    {
        //AA_Log::Log(__METHOD__,100);
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GESTSTRUCT;
        $layout = new AA_JSON_Template_Template($id,array("type"=>"clean","name" => static::AA_UI_SECTION_GESTSTRUCT_NAME,"template"=>"In costruzione (gestione strutture)"));
        
        return $layout;
    }

    //Task action menù
    public function Task_GetActionMenu($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $content = "";

        switch ($_REQUEST['section']) {
            case static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_DESKTOP:
                $content = $this->TemplateActionMenu_Cruscotto();
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_GESTUTENTI:
                $content = $this->TemplateActionMenu_Gestutenti();
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_GESTSTRUCT:
                $content = $this->TemplateActionMenu_GestStruct();
                break;
            default:
                $content = new AA_JSON_Template_Generic();
                break;
        }

        if ($content != "") $sTaskLog .= $content->toBase64();

        $sTaskLog .= "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }
}
#----------------------------------------