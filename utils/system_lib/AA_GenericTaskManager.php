<?php
class AA_GenericTaskManager
{
    //task
    protected $aTasks = array();

    //Utente
    protected $oUser = null;

    //log dei task
    protected $aTaskLog = array();

    public function __construct($user = null)
    {
        if ($user instanceof AA_User && $user->isCurrentUser()) $this->oUser = $user;
        else $this->oUser = AA_User::GetCurrentUser();
    }

    //Registrazione di un nuovo task
    public function RegisterTask($task = null, $class = null)
    {
        if ($task instanceof AA_GenericTask) {
            AA_Log::Log(__METHOD__ . "() - Aggiunta di un nuovo task: " . $task->GetName());
            $this->aTasks[$task->GetName()] = $task;
        }

        if ($task != "" && class_exists($class)) {
            AA_Log::Log(__METHOD__ . "() - Aggiunta di un nuovo task (differito): " . $task . " - classe di gestione: " . $class);
            $this->aTasks[$task] = $class;
        }
    }

    //Svuotamento dei task
    public function Clear()
    {
        $this->aTasks = array();
    }

    //Restituisce il task in base al nome
    public function GetTask($name = "")
    {
        if ($name != "") {
            $task = $this->aTasks[$name];
            if ($task instanceof AA_GenericTask) return $task;
            else {
                if (class_exists($task)) {
                    $this->aTasks[$name] = new $task($this->oUser);
                    return $this->aTasks[$name];
                }
            }
        }

        return null;
    }

    //rimuove il task in base al nome
    public function UnregisterTask($name = "")
    {
        if ($name != "") {
            foreach ($this->aTasks as $key => $value) {
                if ($name == $key) $this->aTasks[$key] = null;
            }
        }
    }

    //Esegue un task
    public function RunTask($taskName = "")
    {
        $task = $this->GetTask($taskName);
        if ($task) {
            if ($task->Run()) {
                return true;
            }

            return false;
        }

        $this->aTaskLog[$taskName] = "<status id='status'>-1</status><error id='error'>" . __METHOD__ . "() - Task non registrato: " . $taskName . ".</error>";
        AA_Log::Log(__METHOD__ . "() - " . $this->aTaskLog[$taskName], 100, true, true);

        return false;
    }

    //Restituisce il log di un task
    public function GetTaskLog($taskName = "")
    {
        if ($this->aTasks[$taskName] instanceof AA_GenericTask) return $this->aTasks[$taskName]->GetLog();
        else return $this->aTaskLog[$taskName];
    }

    //Restituisce il log di errore di un task
    public function GetTaskError($taskName = "")
    {
        if ($this->aTasks[$taskName] instanceof AA_GenericTask) return $this->aTasks[$taskName]->GetError();
        else return $this->aTaskLog[$taskName];
    }

    //Verifica se un task è gestito
    public function IsManaged($taskName = "")
    {
        if ($taskName != "") {
            if ($this->aTasks[$taskName] != "") {
                if ($this->aTasks[$taskName] instanceof AA_GenericTask) return true;
                if (class_exists($this->aTasks[$taskName])) return true;
            }
        }

        return false;
    }
}
