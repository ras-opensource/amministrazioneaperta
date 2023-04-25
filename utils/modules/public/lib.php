<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "config.php";
include_once "system_lib.php";

#Costanti
Class AA_Public_Const extends AA_Const
{
    
}

#Classe per il modulo Public
Class AA_PublicModule extends AA_GenericModule
{
    const AA_UI_PREFIX="AA_Public";

    //id layout base del modulo
    const AA_UI_MAIN_MODULE_BOX="AA_Public_module_layout";

    const AA_UI_PUBLIC_HOME_BOX="AA_PublicHome_Box";

    const AA_MODULE_OBJECTS_CLASS="AA_Public";

    //identificativo modulo (deve essere univoco ed uguale a quello impostato sul db)
    const AA_ID_MODULE="AA_MODULE_PUBLIC";

    //Nome di default del modulo
    const AA_MODULE_DEFAULT_NAME="Public";

    //icona di default del modulo
    const AA_MODULE_DEFAULT_ICON="mdi mdi-home";

    //Identificativi di sezione (devono essere uguali a quelli impostati suol DB)
    const AA_ID_SECTION_HOME="PUBLIC_HOME";

    public function __construct($user=null,$bDefaultSections=true)
    {
        $this->SetId(static::AA_ID_MODULE);
        
        parent::__construct($user,false);
        
        #--------------------------------Registrazione dei task-----------------------------
        $taskManager=$this->GetTaskManager();

        //section default
        $section=new AA_GenericModuleSection(static::AA_ID_SECTION_HOME,"Home",false,static::AA_UI_PREFIX."_".static::AA_UI_PUBLIC_HOME_BOX,$this->GetId(),true,true,false,true,"mdi mdi-home");
        $this->AddSection($section);   
    }

    //istanza
    protected static $oInstance=null;
    
    //Restituisce l'istanza corrente
    public static function GetInstance($user=null)
    {
        if(self::$oInstance==null)
        {
            self::$oInstance=new AA_PublicModule($user);
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

    //Task object content
    public function Task_GetObjectContent($task)
    {
        return $this->Task_GetPublicObjectContent($task, $_REQUEST);
    }

    //Task section content
    public function Task_GetSectionContent($task)
    {
        return $this->Task_GetPublicObjectContent($task, $_REQUEST,"section");
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

    public function Task_GetPublicObjectContent($task, $params = array(), $param = "object")
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        switch ($params[$param]) 
        {
            case static::AA_UI_PREFIX . "_" . static::AA_UI_PUBLIC_HOME_BOX:
                $template = $this->TemplateSection_Home($params);
                $content = array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_PUBLIC_HOME_BOX, "content" => $template->toArray());
                break;
            default:
                $content = array(
                    array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_PUBLIC_HOME_BOX, "content" => $this->TemplateSection_Placeholder()->toArray())
                );
        }

        //Codifica il contenuto in base64
        $sTaskLog .= base64_encode(json_encode($content)) . "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Template servizi fiscali conguaglio
    public function TemplateSection_Home($params)
    {
       $id=static::AA_UI_PREFIX . "_" . static::AA_UI_PUBLIC_HOME_BOX;
       $user=AA_User::GetCurrentUser();

       //impostazione dimensioni
       $width = .4*$_SESSION['viewport_width'];

       $isMobile=false;
       if($_SESSION['mobile'] == true)
       {
           $isMobile=true;
           $width=.9*$_SESSION['viewport_width'];
       }

       if(!$isMobile) $scrollview=new AA_JSON_Template_Generic($id,array("view"=>"scrollview","scroll"=>"y","name"=>"Home","icon"=>"mdi mdi-home"));
       else $scrollview=new AA_JSON_Template_Generic($id,array("view"=>"scrollview","scroll"=>"y","name"=>"Home","icon"=>"mdi mdi-home"));

       return new AA_JSON_Template_Template($id."_HomeContent",array("name"=>"Home","icon"=>"mdi mdi-home","template"=>"public content ".date("Y-m-d")));
    }
}

