<?php
class AA_Object_Log
{
    protected $aLog = array();
    public function __construct($log = "")
    {
        $log = explode("\n", $log);

        foreach ($log as $curRow) {
            $row = explode("|", $curRow);
            $this->aLog[] = array(
                'data' => $row[0],
                'user' => $row[1],
                'op' => $row[2],
                'msg' => $row[3]
            );
        }
    }

    public function GetLog()
    {
        return $this->aLog;
    }

    //Restituisce l'ultimo log
    public function GetLastLog()
    {
        $count = sizeof($this->aLog);
        if ($count > 0) {
            return ($this->aLog[$count - 1]);
        } else return array();
    }
}
