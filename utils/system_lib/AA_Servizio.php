<?php
class AA_Servizio extends AA_Struttura
{
    static protected $dbDataTable="servizi";

    static protected $ObjectClass=__CLASS__;

    public function __construct($params = null)
    {
        $this->aProps['descrizione']="";
        $this->aProps['id_direzione']=0;
        $this->aProps['data_istituzione']=date("Y-m-d");
        $this->aProps['data_soppressione']="9999-12-31";

        parent::__construct($params);
    }
}
