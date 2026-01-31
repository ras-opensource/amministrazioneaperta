<?php
class AA_GenericModuleTaskManager extends AA_GenericTaskManager
{
    protected $oModule = null;
    public function GetModule()
    {
        return $this->oModule;
    }

    public function __construct($module = null, $user = null)
    {
        parent::__construct($user);

        if ($module instanceof AA_GenericModule) $this->oModule = $module;
        else $this->oModule = new AA_GenericModule($user);
    }

    //Registrazione di un nuovo task
    public function RegisterTask($task = null, $taskFunction = "")
    {

        if ($taskFunction == "") $taskFunction = "Task_" . $task;
        $module = $this->GetModule();
        if (method_exists($module, $taskFunction)) {
            AA_Log::Log(__METHOD__ . "() - Aggiunta di un nuovo task: " . $task . " - funzione: " . $taskFunction);
            $this->aTasks[$task] = new AA_GenericModuleTask($task, $this->oUser, $this, $taskFunction);
        } else {
            AA_Log::Log(__METHOD__ . "() - Errore task non registrato - task: " . $task . " - funzione: " . $taskFunction, 100);
        }
    }
}
