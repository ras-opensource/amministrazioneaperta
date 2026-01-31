<?php
class AA_GenericTask
{
    protected $sTaskName = "";
    protected $sTaskLog = "";
    protected $sTaskError = "";
    protected $oUser = null;
    protected $taskManager = null;

    const AA_STATUS_FAILED=-1;
    const AA_STATUS_SUCCESS=0;
    const AA_STATUS_UNAUTH=-2;

    public function GetTaskManager()
    {
        return $this->taskManager;
    }

    public function SetTaskManager($taskManager = null)
    {
        if ($taskManager instanceof AA_GenericModuleTaskManager) $this->taskManager = $taskManager;
    }

    public function GetName()
    {
        return $this->sTaskName;
    }

    public function SetName($name = "")
    {
        if ($name != "") {
            $this->sTaskName = $name;
        }
    }

    public function GetError()
    {
        return $this->sTaskError;
    }

    public function SetError($error)
    {
        $this->sTaskError = $error;
    }

    //Funzione per la gestione del task
    public function Run()
    {

        return true;
    }

    public function __construct($taskName = "", $user = null, $taskManager = null)
    {
        $this->SetTaskManager($taskManager);

        if ($taskName != "") {
            $this->sTaskName = $taskName;

            if ($user instanceof AA_User && $user->isCurrentUser()) $this->oUser = $user;
            else $this->oUser = AA_User::GetCurrentUser();
        }
    }

    public function GetLog()
    {
        return $this->sTaskLog;
    }

    public function SetLog($log)
    {
        $this->sTaskLog = $log;
    }
}
