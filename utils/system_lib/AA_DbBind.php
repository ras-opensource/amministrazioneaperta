<?php
class AA_DbBind
{
    //bind variabili -> campi db
    protected $aBindings = array();
    public function GetBindings()
    {
        return $this->aBindings;
    }

    //nome tabella
    protected $sTable = "";
    public function SetTable($table = "")
    {
        $this->sTable = $table;
    }
    public function GetTable()
    {
        return $this->sTable;
    }

    //Aggiungi un collegamento
    public function AddBind($nomeVariabile = "", $nomeCampo = "")
    {
        if ($nomeVariabile == "" || $nomeCampo == "" || $nomeCampo == "id") return false;

        $this->aBindings[$nomeVariabile] = $nomeCampo;
        return true;
    }

    //rimuovi un collegamento
    public function DelBind($nomeVariabile = "")
    {
        if (isset($this->aBindings[$nomeVariabile])) $this->aBindings[$nomeVariabile] = "";
        else {
            foreach ($this->aBindings as $key => $value) {
                if ($value == $nomeVariabile) $this->aBindings[$key] = "";
            }
        }
    }
}
