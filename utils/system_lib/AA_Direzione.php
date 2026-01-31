<?php
Class AA_Direzione extends AA_Struttura
{
    static protected $dbDataTable="direzioni";

    static protected $ObjectClass=__CLASS__;

    public function __construct($params = null)
    {
        $this->aProps['descrizione']="";
        $this->aProps['id_assessorato']=0;
        $this->aProps['data_istituzione']=date("Y-m-d");
        $this->aProps['data_soppressione']="9999-12-31";

        parent::__construct($params);
    }

    public function Delete($user=null)
    {
        if(!($user instanceof AA_User) || !$user->isCurrentUser())
        {
            $user=AA_User::GetCurrentUser();
        }

        if(!$user->CanGestStruct())
        {
            AA_Log::Log("L'utente corrente non è abilitato alla gestione strutture.",100);
            return false; 
        }

        $servizi=$this->GetServizi(true);

        foreach($servizi as $curServizio)
        {
            if(!$curServizio->Delete($user))
            {
                return false;
            }
        }

        return parent::Delete($user);
    }

    public function GetServizi($bAsObjects=false)
    {
        $return = array();

        if($this->GetProp('id') !=0)
        {
            $db = new static::$dbClass();

            $query="SELECT distinct id from ".AA_Servizio::GetDatatable()." WHERE id_direzione='".$this->GetProp('id')."' ORDER by descrizione";

            if(!$db->Query($query))
            {
                AA_Log::Log("Errore nel recupero dei servizi. - ".$db->GetLastErrorMessage(),100);
                return $return;
            }

            $rs=$db->GetResultSet();
            foreach($rs as $curRow)
            {
                if($bAsObjects)
                {
                    $struct=new AA_Servizio();
                    if($struct->Load($curRow['id'])) $return[$curRow['id']]=$struct;
                }
                else $return[]=$curRow['id'];
            }
        }

        return $return;
    }
}
