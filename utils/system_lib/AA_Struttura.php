<?php
Class AA_Struttura extends AA_GenericParsableDbObject
{
    static protected $dbDataTable="assessorati";
    static protected $dbClass="AA_AccountsDatabase";
    static protected $ObjectClass=__CLASS__;

    public function __construct($params = null)
    {
        $this->aProps['descrizione']="";
        $this->aProps['aggiornamento']=date("Y-m-d");
        $this->aProps['data_istituzione']=date("Y-m-d");
        $this->aProps['data_soppressione']="9999-12-31";
        $this->aProps['web']="";

        parent::__construct($params);
    }

    public function Delete($user=null)
    {
        #verifica utente
        if(!($user instanceof AA_User) || !$user->isCurrentUser())
        {
            $user=AA_User::GetCurrentUser();
        }

        if(!$user->CanGestStruct())
        {
            AA_Log::Log(__METHOD__." - L'utente corrente non è abilitato alla gestione strutture.",100);
            return false; 
        }

        $struct=$user->GetStruct();
        if(($this instanceof AA_Assessorato) && $struct->GetAssessorato(true) !=0)
        {
            AA_Log::Log(__METHOD__." - L'utente corrente non puo' modificare la struttura (0).",100);
            return false; 
        }

        if(($this instanceof AA_Direzione) && ($struct->GetDirezione(true) !=0 || ($struct->GetAssessorato(true) !=0 && $struct->GetAssessorato(true) != intVal($this->GetProp('id_assessorato')))))
        {
            AA_Log::Log(__METHOD__." - L'utente corrente non puo' modificare la struttura (1).",100);
            return false; 
        }

        if($struct->GetServizio(true) !=0)
        {
            AA_Log::Log(__METHOD__." - L'utente corrente non puo' modificare la struttura (2).",100);
            return false; 
        }
        #---------------------

        return $this->DeleteFromDb();
    }
}
