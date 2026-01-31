<?php
class AA_GenericModuleTask extends AA_GenericTask
{
    protected $sContentType="";
    protected $sContentEncode="";
    protected $sContent="";
    public function SetContent($val="", $json=false, $encode=true)
    {
        $content=$val;
        if($json)
        {
            if($val instanceof AA_JSON_Template_Generic) $content=$val->__toString();
            if(is_array($val))  $content=json_encode($val);
            $this->sContentType="json";
        }

        if($encode) 
        {
            $content=base64_encode($content);
            $this->sContentEncode="base64";
        }

        $this->sContent=$content;
    }

    protected $nStatus=-1;
    public function SetStatus($val=0)
    {
        if($val == 0) $this->nStatus=0;
        if($val == -1) $this->nStatus=-1;
        if($val == -2) $this->nStatus=-2;
    }
    protected $sStatusAction="";
    public function SetStatusAction($action="",$params=null,$json_encode=false)
    {
        $this->sStatusAction=$action;
        $this->SetStatusActionParams($params,$json_encode);
    }
    protected $sStatusActionParams="";
    public function SetStatusActionParams($params=null,$json_encode=false)
    {
        if($json_encode && $params)
        {
            $this->sStatusActionParams=json_encode($params);
        }
        else $this->sStatusActionParams=$params;
    }

    protected $sError="";
    protected $sErrorType="";
    protected $sErrorEncode="";
    public function SetError($val="", $json=false, $encode=true)
    {
    
        $content=$val;

        if($encode) $content=base64_encode($content);

        if($json) $this->sErrorType="json";
        if($encode) $this->sErrorEncode="base64";
        $this->sError=$content;
    }

    protected $taskFunction = "";
    public function __construct($task = "", $user = null, $taskManager = null, $taskFunction = "")
    {
        if ($task == "") {
            $task = "GenericTask";
        }

        if ($taskFunction == "") {
            $this->taskFunction = "Task_" . $task;
        } else $this->taskFunction = $taskFunction;

        parent::__construct($task, $user, $taskManager);

        //AA_Log::Log(__METHOD__." - ".print_r($this,true),100);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $module = $this->GetTaskManager()->GetModule();
        $taskFunction = $this->taskFunction;
        if (method_exists($module, $taskFunction)) return $module->$taskFunction($this);
        else {
            $this->sTaskLog = "<status id='status'>-1</status><error id='error'>" . __METHOD__ . "() - Task non registrato: " . $this->sTaskName . ".</error>";
            return false;
        }
    }

    public function GetLog()
    {
        if($this->sTaskLog=="")
        {
            $this->sTaskLog="<status id='status' action='".$this->sStatusAction."' action_params='".$this->sStatusActionParams."'>".$this->nStatus."</status><content id='content' type='".$this->sContentType."' encode='".$this->sContentEncode."'>".$this->sContent."</content><error id='error' type='".$this->sErrorType."' encode='".$this->sErrorEncode."'>".$this->sError."</error>";
        }

        //AA_Log::Log(__METHOD__." - sTaskLog: ".$this->sTaskLog,100);
        return $this->sTaskLog;
    }
}
