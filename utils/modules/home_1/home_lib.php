<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("config.php");
include_once("system_lib.php");

//Classe per la gestione dei task
Class AA_HomeTaskManager extends AA_SystemTaskManager
{
    public function __construct($user=null)
    {
        parent::__construct($user);
        
        //Restituisce il contenuto della sezione corrente
        $this->RegisterTask("GetSectionContent","AA_HomeTask_SectionContent");
        
        //Restituisce il contenuto della sezione corrente
        $this->RegisterTask("GetObjectContent","AA_HomeTask_ObjectContent");
        
        //Sezioni del modulo
        $this->RegisterTask("GetSections","AA_HomeTask_GetSections");
        
        //Layout del modulo
        $this->RegisterTask("GetLayout","AA_HomeTask_Layout");
        
        //Barra di navigazione modulo
        $this->RegisterTask("GetNavbarContent","AA_HomeTask_NavbarContent");
        
        //menu contestuale alla sezione attiva
        $this->RegisterTask("GetActionMenu","AA_HomeTask_ActionMenu");
    }
}
//------------------------------------------------------

//Task che restituisce il layout del modulo home
Class AA_HomeTask_Layout extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("GetLayout", $user);
    }
    
    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: ".$this->GetName());
        
        $module= AA_HomeModule::GetInstance();
        
        $this->sTaskLog="<status id='status'>0</status><content id='content' type='json'>";
        $content=$module->TemplateLayout();
        $this->sTaskLog.= $content;
        $this->sTaskLog.="</content>";
        return true;
    }
}
#--------------------------------------------

//Task che restituisce il layout del modulo home
Class AA_HomeTask_GetSections extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("GetSections", $user);
    }
    
    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: ".$this->GetName());
        
        $module= AA_HomeModule::GetInstance();
               
        $this->sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $this->sTaskLog.= $module->GetSections("base64");
        $this->sTaskLog.="</content>";
        return true;
    }
}
#--------------------------------------------

//Task che restituisce il contenuto  del modulo home come array json
Class AA_HomeTask_SectionContent extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("GetSectionContent", $user);
    }
    
    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: ".$this->GetName());
        
        $this->sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        
        $module= AA_HomeModule::GetInstance();
        
        switch($_REQUEST['section'])
        {
            case "News":
            case "AA_Home_News_Content_Box":
                $template= new  AA_JSON_Template_Layout(
                        "AA_Home_News_Content_Box",
                        array("update_time"=>Date("Y-m-d H:i:s"),
                        "name"=>"News",
                        "type"=>"clean"));
                $template->AddRow($module->TemplateSection_News());
                $content=array("id"=>"AA_Home_News_Content_Box","content"=>$template->toArray());
                break;
            
            case "Faq":
            case "AA_Home_FAQ_Content_Box":
                $template= new AA_JSON_Template_Layout(
                        "AA_Home_FAQ_Content_Box",
                        array("update_time"=>Date("Y-m-d H:i:s"),
                        "name"=>"F.A.Q.",
                        "type"=>"clean"));
                $template->AddRow(new AA_JSON_Template_Template("AA_Home_FAQ_Content",array( "type"=>"clean","template"=>"<span>F.A.Q. aggiornate al ".Date("Y-m-d H:i:s")."</span>")));
                $content=array("id"=>"AA_Home_FAQ_Content_Box", "content"=>$template->toArray());
                break;
            
            default:
                $template_news=new AA_JSON_Template_Layout("AA_Home_News_Content_Box",array(
                        "update_time"=>Date("Y-m-d H:i:s"),
                        "name"=>"News"));
                $template_news->AddRow(new AA_JSON_Template_Template("AA_Home_News_Content",array(
                            "type"=>"clean",
                            "template"=>"<span>News aggiornate al ".Date("Y-m-d H:i:s")."</span>"
                            )));
                $template_faq=new AA_JSON_Template_Layout("AA_Home_FAQ_Content_Box",array(
                        "update_time"=>Date("Y-m-d H:i:s"),
                        "name"=>"FAQ"));
                $template_faq->AddRow(new AA_JSON_Template_Template("AA_Home_FAQ_Content",array(
                            "type"=>"clean",
                            "template"=>"<span>FAQ aggiornate al ".Date("Y-m-d H:i:s")."</span>"
                            )));
                 $content=array(
                    array("id"=>"AA_Home_News_Content_Box","content"=>$template_news->toArray()),              
                    array("id"=>"AA_Home_FAQ_Content_Box","content"=>$template_faq->ToArray()));
        }
        
        //Codifica il contenuto in base64
        $this->sTaskLog.= base64_encode(json_encode($content))."</content>";
        return true;
    }
}
#--------------------------------------------

//Task che restituisce il contenuto  del modulo home come array json
Class AA_HomeTask_ObjectContent extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("GetObjectContent", $user);
    }
    
    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: ".$this->GetName());
        
        $this->sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        
        $module= AA_HomeModule::GetInstance();
        
        switch($_REQUEST['object'])
        {
            case "News":
            case "AA_Home_News_Content_Box":
                $template= new  AA_JSON_Template_Layout(
                        "AA_Home_News_Content_Box",
                        array("update_time"=>Date("Y-m-d H:i:s"),
                        "name"=>"News",
                        "type"=>"clean"));
                $template->AddRow($module->TemplateSection_News());
                $content=array("id"=>"AA_Home_News_Content_Box","content"=>$template->toArray());
                break;
            
            case "Faq":
            case "AA_Home_FAQ_Content_Box":
                $template= new AA_JSON_Template_Layout(
                        "AA_Home_FAQ_Content_Box",
                        array("update_time"=>Date("Y-m-d H:i:s"),
                        "name"=>"F.A.Q.",
                        "type"=>"clean"));
                $template->AddRow(new AA_JSON_Template_Template("AA_Home_FAQ_Content",array( "type"=>"clean","template"=>"<span>F.A.Q. aggiornate al ".Date("Y-m-d H:i:s")."</span>")));
                $content=array("id"=>"AA_Home_FAQ_Content_Box", "content"=>$template->toArray());
                break;
            
            default:
                $template_news=new AA_JSON_Template_Layout("AA_Home_News_Content_Box",array(
                        "update_time"=>Date("Y-m-d H:i:s"),
                        "name"=>"News"));
                $template_news->AddRow(new AA_JSON_Template_Template("AA_Home_News_Content",array(
                            "type"=>"clean",
                            "template"=>"<span>News aggiornate al ".Date("Y-m-d H:i:s")."</span>"
                            )));
                $template_faq=new AA_JSON_Template_Layout("AA_Home_FAQ_Content_Box",array(
                        "update_time"=>Date("Y-m-d H:i:s"),
                        "name"=>"FAQ"));
                $template_faq->AddRow(new AA_JSON_Template_Template("AA_Home_FAQ_Content",array(
                            "type"=>"clean",
                            "template"=>"<span>FAQ aggiornate al ".Date("Y-m-d H:i:s")."</span>"
                            )));
                 $content=array(
                    array("id"=>"AA_Home_News_Content_Box","content"=>$template_news->toArray()),              
                    array("id"=>"AA_Home_FAQ_Content_Box","content"=>$template_faq->ToArray()));
        }
        
        //Codifica il contenuto in base64
        $this->sTaskLog.= base64_encode(json_encode($content))."</content>";
        return true;
    }
}
#--------------------------------------------

//Task che restituisce il contenuto della navbar del modulo
Class AA_HomeTask_NavbarContent extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("GetNavbarContent", $user);
    }
    
    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: ".$this->GetName());
        
        $this->sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        
        $content=array();
        
        //Istanza del modulo
        $module= AA_HomeModule::GetInstance();
        
        if($_REQUEST['section']!="AA_Home_News_Content_Box")
        {
             $content[]=$module->TemplateNavbar_News()->toArray();
        }
        else
        {
            $content[]=$module->TemplateNavbar_Faq()->toArray();
        }
        
        $spacer=new AA_JSON_Template_Generic("navbar_spacer");
        $content[]= $spacer->toArray();
        
        $this->sTaskLog.=base64_encode(json_encode($content))."</content>";
        return true;
    }
}
#--------------------------------------------

//Task che restituisce il menu contestuale alla sezione attiva
Class AA_HomeTask_ActionMenu extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("GetActionMenu", $user);
    }
    
    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: ".$this->GetName());
        
        $this->sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        
        $module= AA_HomeModule::GetInstance();
        
        $content="";
        
        switch($_REQUEST['section'])
        {
            case "AA_Home_News_Content_Box":
                $content=$module->TemplateActionMenu_News();
                break;
            
            case "AA_Home_FAQ_Content_Box":
                $content=$module->TemplateActionMenu_Faq();
                break;
        }
        
        if($content !="") $this->sTaskLog.= base64_encode(json_encode($content));
        
        $this->sTaskLog.="</content>";
        return true;
    }
}
#--------------------------------------------

//Classe per la gestione del modulo home
Class AA_HomeModule extends AA_GenericModule
{
    //istanza
    protected static $oInstance=null;
    
    //Restituisce l'istanza corrente
    public static function GetInstance()
    {
        if(self::$oInstance==null)
        {
            self::$oInstance=new AA_HomeModule();
        }
        
        return self::$oInstance;
    }
    
    public function __construct() {
        $this->SetId("AA_MODULE_HOME");
        
        //Sidebar config
        $this->SetSideBarId("home");
        $this->SetSideBarIcon("mdi mdi-home");
        $this->SetSideBarTooltip("Home page");
        $this->SetSideBarName("Home");
        
        //Sezioni
        //news
        $section=new AA_GenericModuleSection("News","News",true,"AA_Home_News_Content_Box",$this->GetId(),true,true,false,true);
        $section->SetNavbarTemplate($this->TemplateNavbar_Faq()->toArray());
        $this->AddSection($section);
        
        //FAQ
        $section=new AA_GenericModuleSection("Faq","FAQ",true,"AA_Home_FAQ_Content_Box",$this->GetId(),false,true,false,true);
        $section->SetNavbarTemplate($this->TemplateNavbar_News()->toArray());
        $this->AddSection($section);
        #-------------------------------------------
    }
    
    //Template navbar news
    public function TemplateNavbar_News()
    {
        $template="<div class='AA_navbar_link_box_left #class#'><a class='AA_Home_Navbar_Link_Main_Content_Box' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"News\",\"".$this->id."\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>";

        $news = new AA_JSON_Template_Template("AA_Home_Navbar_Link_Main_Content_Box",array(
        "type"=>"clean",
        "css" => "AA_NavbarEventListener",
        "id_panel"=>"AA_Home_News_Content_Box",
        "tooltip"=>"Fai click per visualizzare le news",
        "module_id"=>"AA_MODULE_HOME",
        "section_id"=>"News",
        "template"=>$template,
        "data"=>array("label"=>"News","icon"=>"mdi mdi-rss", "class"=>"n1 AA_navbar_terminator_left")));
        
        return $news;
    }
    
    //Template navbar faq
    public function TemplateNavbar_Faq()
    {
        $navbar =  new AA_JSON_Template_Template("AA_Home_Navbar_Link_FAQ_Content_Box",array(
                "type"=>"clean",
                "css"=>"AA_NavbarEventListener",
                "id_panel"=>"AA_Home_FAQ_Content_Box",
                "module_id"=>"AA_MODULE_HOME",
                "section_id"=>"Faq",
                "tooltip"=>"Fai click per visualizzare le F.A.Q.",
                "template"=>"<div class='AA_navbar_link_box_left #class#'><a class='AA_Home_Navbar_Link_FAQ_Content_Box' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"Faq\",\"".$this->id."\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data"=>array("label"=>"F.A.Q.","icon"=>"mdi mdi-help-rhombus","class"=>"n1 AA_navbar_terminator_left"))
            );
        return $navbar;  
    }
    
    //Template news context menu
    public function TemplateActionMenu_News()
    {
        $menu=array(
            "id"=>"AA_ActionMenuBox",
            "view"=>"contextmenu",
            "data"=>array(array(
                   "id"=>"AA_HomeNewsActionMenuItem_Aggiorna",
                   "value"=>"Aggiorna",
                   "icon"=>"mdi mdi-reload",
                   "handler"=>"refreshUiObject",
                   "handler_params"=>array("AA_Home_News_Content_Box",true)
                ))
            );
        
        return $menu;  
    }
    
    //Template news context menu
    public function TemplateActionMenu_Faq()
    {
         
        $menu=array(
            "id"=>"AA_ActionMenuBox",
            "view"=>"contextmenu",
            "data"=>array(array(
                "id"=>"refresh_faq",
                "value"=>"Aggiorna",
                "icon"=>"mdi mdi-reload",
                "handler"=>"refreshUiObject",
                "handler_params"=>array("AA_Home_FAQ_Content_Box",true)
                ))
            );
        
        return $menu;  
    }
    
    //Template layout
    public function TemplateLayout()
    {        
        $template=new AA_JSON_Template_Multiview("AA_home_module_layout",array("type"=>"clean","fitBiggest"=>"true"));
        foreach($this->GetSections() as $curSection)
        {
            $template->addCell(new AA_JSON_Template_Template($curSection->GetViewId(),array("name"=>$curSection->GetName(),"type"=>"clean","template"=>"","initialized"=>false,"refreshed"=>false)));
        }
        
        //$template->addCell(new AA_JSON_Template_Template("AA_Home_News_Content_Box",array("name"=>"News","type"=>"clean","template"=>"","initialized"=>false,"refreshed"=>false)));
        //$template->addCell(new AA_JSON_Template_Template("AA_Home_FAQ_Content_Box",array("name"=>"F.A.Q.","type"=>"clean","template"=>"","initialized"=>false,"refreshed"=>false)));
                
        //AA_Log::Log("TemplateLayout - ".$template,100);
        
        return $template;
    }
    
    //Template news content
    public function TemplateSection_News()
    {
        
        $news_layout = new AA_JSON_Template_Generic("AA_Home_News_Content",
                array(
                "view"=>"timeline",
                "type"=>array(
                    "height"=>"auto",
                    "width"=>"800",
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
        
        return $news_layout;
    }
}
#----------------------------------------