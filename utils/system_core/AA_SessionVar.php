<?php
class AA_SessionVar
{
    protected $name = "varName";
    public function GetName()
    {
        return $this->name;
    }

    protected $value = null;
    public function GetValue()
    {
        return $this->value;
    }

    protected $bValid = false;
    public function IsValid()
    {
        return $this->bValid;
    }

    static public function UnsetVar($name="")
    {
        if (isset($_SESSION['SessionVars'][$name]) && $name != "") 
        {
            unset($_SESSION['SessionVars'][$name]);
        }
    }

    //Costruttore
    protected function __construct($name = "varName", $value = "")
    {
        $this->name = $name;
        $this->value = $value;

        if ($this->name != "") {
            $this->bValid = true;
        }
    }

    static public function Get($name = "varName")
    {
        if (isset($_SESSION['SessionVars'][$name]) && $name != "") {
            return new AA_SessionVar($name, unserialize($_SESSION['SessionVars'][$name]));
        } else return new AA_SessionVar();
    }

    static public function Set($name = "varName", $value = "", $parse=true)
    {
        if ($name != "" && $value != "") {
            if($parse) $var = json_decode($value);
            else $var=$value;
            if (is_string($value)) {
                if (json_last_error() === JSON_ERROR_NONE && $parse) {
                    $_SESSION['SessionVars'][$name] = serialize($var);
                    //AA_Log::Log(__METHOD__." - name:".$name." - value: ".print_r($var,true),100);
                    //AA_Log::Log(__METHOD__." - name:".$name." - value: ".$_SESSION['SessionVars'][$name],100);
                } else $_SESSION['SessionVars'][$name] = $value;
            } else {
                $_SESSION['SessionVars'][$name] = serialize($var);
                //AA_Log::Log(__METHOD__." - name:".$name." - value: ".print_r($var,true),100);
                //AA_Log::Log(__METHOD__." - name:".$name." - value: ".$_SESSION['SessionVars'][$name],100);
            }

            return true;
        } else return false;
    }
}
