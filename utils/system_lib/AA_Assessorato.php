<?php
Class AA_Assessorato extends AA_Struttura
{
    const AA_TIPO_ASSESSORATO=0;
    const AA_TIPO_AGENZIA=2;
    const AA_TIPO_ENTE=1;
    const AA_TIPO_COMMISSARIO=3;

    static protected $aTipologie=null;
    static public function GetTipologie()
    {
        if(static::$aTipologie==null)
        {
            static::$aTipologie=array(
                static::AA_TIPO_AGENZIA=>"Agenzia",
                static::AA_TIPO_ASSESSORATO=>"Assessorato",
                static::AA_TIPO_COMMISSARIO=>"Commissario",
                static::AA_TIPO_ENTE=>"Ente"
            );
        }

        return static::$aTipologie;
    }
    public function __construct($params = null)
    {
        $this->aProps['tipo']=-1;

        parent::__construct($params);
    }

    public function GetDirezioni($bAsObjects=false)
    {
        $return = array();

        if($this->GetProp('id') !=0)
        {
            $db = new static::$dbClass();

            $query="SELECT distinct id from ".AA_Direzione::GetDatatable()." WHERE id_assessorato='".$this->GetProp('id')."' ORDER by descrizione";

            if(!$db->Query($query))
            {
                AA_Log::Log("Errore nel recupero delle direzioni. - ".$db->GetLastErrorMessage(),100);
                return $return;
            }

            $rs=$db->GetResultSet();
            foreach($rs as $curRow)
            {
                if($bAsObjects)
                {
                    $struct=new AA_Direzione();
                    if($struct->Load($curRow['id'])) $return[$curRow['id']]=$struct;
                }
                else $return[]=$curRow['id'];
            }
        }

        return $return;
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

        $servizi=$this->GetDirezioni(true);

        foreach($servizi as $curDirezione)
        {
            if(!$curDirezione->Delete($user))
            {
                return false;
            }
        }

        return parent::Delete($user);
    }
}
