<?php
class AA_ObjectVarMapping
{
    //Oggetto collegato
    private $oObject = null;
    public function GetObject()
    {
        return $this->oObject;
    }
    public function SetObject($object = null)
    {
        if ($object instanceof AA_Object) $this->oObject = $object;
    }

    public function __construct($object = null)
    {
        if ($object instanceof AA_Object) $this->oObject = $object;
    }

    //Aggiunge un mapping alle variabili
    private $aVarMapping = array();
    public function AddVar($var_name = "", $name = "", $type = "", $label = "")
    {
        if ($name == "") $name = $var_name;
        if ($type == "") $type = "text";
        if ($label == "") $label = $name;
        if ($var_name != "") $this->aVarMapping[$var_name] = $name . "|" . $type . "|" . $label;
    }

    //Rimuove un mapping ad una variabile
    public function DelVar($var_name = "")
    {
        if ($var_name != "" && $this->aVarMapping[$var_name] != "") {
            $this->aVarMapping[$var_name] = "";
        }
    }

    //restituisce il nome di una variabile mappata
    public function GetName($var_name = "")
    {
        if ($var_name != "" && $this->aVarMapping[$var_name] != "") {
            $mapping = explode("|", $this->aVarMapping[$var_name]);
            return $mapping[0];
        }

        return $var_name;
    }

    //Restituisce il tipo di una variabile mappata
    public function GetType($var_name = "")
    {
        if ($var_name != "" && $this->aVarMapping[$var_name] != "") {
            $mapping = explode("|", $this->aVarMapping[$var_name]);
            return $mapping[1];
        }

        return "text";
    }

    //restituisce il lable di una variabile mappata
    public function GetLabel($var_name = "")
    {
        if ($var_name != "" && $this->aVarMapping[$var_name] != "") {
            $mapping = explode("|", $this->aVarMapping[$var_name]);
            return $mapping[2];
        }

        return $var_name;
    }
}
