<?php
class AA_GenericTaskResponse
{
    protected $sError = "";
    protected $sContent = "";
    protected $nStatus = AA_Const::AA_TASK_STATUS_FAIL;

    public function SetStatus($newStatus = AA_Const::AA_TASK_STATUS_OK)
    {
        $status = AA_Const::AA_TASK_STATUS_OK | AA_Const::AA_TASK_STATUS_FAIL;
        $this->nStatus = $newStatus & $status;
    }
    public function GetStatus()
    {
        return $this->nStatus;
    }

    public function SetError($error = "")
    {
        $this->sError = $error;
    }

    public function GetError()
    {
        return $this->sError;
    }

    public function SetMsg($error = "")
    {
        $this->sError = $error;
    }

    public function GetMsg()
    {
        return $this->sError;
    }

    public function SetContent($val = "")
    {
        $this->sContent = $val;
    }

    public function GetContent()
    {
        return $this->sContent;
    }

    public function toString()
    {
    }
}
