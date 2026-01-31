<?php
class AA_Group
{
    //propietà
    protected $aProps=array();
    public function GetProp($key)
    {
        if($key !="" && isset($this->aProps[$key])) return $this->aProps[$key];
        return "";
    }

    //flag di validità
    protected $bValid=false;
    public function IsValid()
    {
        return $this->bValid;
    }

    const AA_DB_TABLE="aa_groups";

    protected function Parse($props=array())
    {
        if(is_array($props))
        {
            foreach($props as $key=>$val)
            {
                if(isset($this->aProps[$key])) $this->aProps[$key]=$val;
            }
        }
    }

    protected function __construct($props=array())
    {
        $this->aProps['id']=0;
        $this->aProps['id_parent']=0;
        $this->aProps['descr']="";
        $this->aProps['system']=0;

        $this->Parse($props);
    }

    static public function GetDescendants($id_group)
    {
        if($id_group <=0)
        {
            AA_Log::Log(__METHOD__." - identificativo di gruppo non valido. (".$id_group.")",100);
            return array();            
        }

        $result=array();
        $db=new AA_Database();
        $query="SELECT GROUP_CONCAT(id) as ids FROM ".static::AA_DB_TABLE." WHERE id_parent='".$id_group."'";
        //AA_Log::Log(__METHOD__." - query: ".$query,100);
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage()."  - query: ".$query,100);
            return array();
        }
        $rs=$db->GetResultSet();

        $num=0;
        while($rs[0]['ids'] != "" && $num < 10)
        {
            $result=array_merge($result,explode(",",$rs[0]['ids']));

            $query="SELECT GROUP_CONCAT(id) as ids FROM ".static::AA_DB_TABLE." WHERE FIND_IN_SET(id_parent,'".$rs[0]['ids']."')";
            //AA_Log::Log(__METHOD__." ids: ".$rs[0]['ids']." - result: ".print_r($result,true)." - query: ".$query,100);
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage()."  - query: ".$query,100);
                return array();
            }
            $rs=$db->GetResultSet();
            $num++;
        }

        //AA_Log::Log(__METHOD__." - result: ".print_r($result,true),100);
        return $result;
    }

    //Restituisce il gruppo con l'id specificato
    static public function GetGroup($id="",$user=null)
    {
        if($user == null) $user=AA_User::GetCurrentUser();
        if($user->IsGuest())
        {
            AA_Log::Log(__METHOD__." - utente non valido.",100);
            return new AA_Group();
        }

        if($id <=0)
        {
            AA_Log::Log(__METHOD__." - identificativo di gruppo non valido. (".$id.")",100);
            return new AA_Group();            
        }

        $db=new AA_Database();
        $query="SELECT * FROM ".static::AA_DB_TABLE." WHERE id='".addslashes($id)."' LIMIT 1";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query. (".$db->GetErrorMessage().") - query: ".$query,100);
            return new AA_Group();
        }

        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            
            $group = new AA_Group($rs[0]);
            $group->bValid=true;

            return $group;
        }

        return new AA_Group();
    }

    //Aggiunge un nuovo gruppo (restituisce il gruppo appena generato)
    static public function AddGroup($newGroupData=null,$user=null)
    {
        if($user == null) $user=AA_User::GetCurrentUser();
        if($user->IsGuest())
        {
            AA_Log::Log(__METHOD__." - utente non valido.",100);
            return new AA_Group();
        }

        if(!is_array($newGroupData))
        {
            AA_Log::Log(__METHOD__." - dati gruppo non validi.",100);
            return new AA_Group();
        }

        if(!isset($newGroupData['descr']) || $newGroupData['descr']=="")
        {
            AA_Log::Log(__METHOD__." - dati gruppo non validi.",100);
            return new AA_Group();
        }

        $db=new AA_Database();
        $query="INSERT INTO ".static::AA_DB_TABLE." SET descr='".addslashes($newGroupData['descr'])."'";
        
        if($user->IsSuperUser())
        {
            if(isset($newGroupData['system']) && $newGroupData['system'] > 0)
            {
                $query.=", system=1";
            }

            if(isset($newGroupData['id_parent']) && $newGroupData['id_parent'] > 0)
            {
                $query.=", id_parent='".addslashes($newGroupData['id_parent'])."'";
            }
        }

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query. (".$db->GetErrorMessage().") - query: ".$query,100);
            return new AA_Group();
        }

        return static::GetGroup($db->GetLastInsertId(),$user);
    }

    //modifica il gruppo indicato con quello passato
    static public function ModifyGroup($id_group=0,$groupData=null,$user=null)
    {
        if($user == null) $user=AA_User::GetCurrentUser();
        if($user->IsGuest())
        {
            AA_Log::Log(__METHOD__." - utente non valido.",100);
            return false;
        }

        $group=static::GetGroup($id_group,$user);
        if(!$group->IsValid())
        {
            AA_Log::Log(__METHOD__." - identificativo gruppo non valido.",100);
            return false;
        }

        if(!is_array($groupData))
        {
            AA_Log::Log(__METHOD__." - dati gruppo non validi.",100);
            return false;
        }

        if(!isset($groupData['descr']) || $groupData['descr']=="")
        {
            AA_Log::Log(__METHOD__." - dati gruppo non validi.",100);
            return false;
        }

        if(!$user->IsSuperUser())
        {
            if($group->GetProp("system") == 1)
            {
                AA_Log::Log(__METHOD__." - Gruppo non modificabile dall'utente corrente. (".$user->GetNome()." ".$user->GetCognome.")",100);
                return false;
            }
    
            if($group->GetProp("id_parent") > 0)
            {
                AA_Log::Log(__METHOD__." - Gruppo non modificabile dall'utente corrente. (".$user->GetNome()." ".$user->GetCognome.")",100);
                return false;
            }    
        }

        $db=new AA_Database();
        $query="UPDATE ".static::AA_DB_TABLE." SET descr='".addslashes($groupData['descr'])."'";

        if($user->IsSuperUser())
        {
            if(isset($groupData['system']))
            {
                if($groupData['system'] > 0) $query.=", system=1";
                else $query.=", system=0";
            }

            if(isset($groupData['id_parent']))
            {
                if($groupData['id_parent'] > 0) $query.=", id_parent='".addslashes($groupData['id_parent'])."'";
                else $query.=", id_parent='0'";
            }
        }

        $query.=" WHERE id='".addslashes($group->GetProp('id'))."' LIMIT 1";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query. (".$db->GetErrorMessage().") - query: ".$query,100);
            return false;
        }

        return true;
    }

    //Elimina un gruppo esistente
    static public function DelGroup($id_group=0,$user=null)
    {
        if($user == null) $user=AA_User::GetCurrentUser();
        if($user->IsGuest())
        {
            AA_Log::Log(__METHOD__." - utente non valido.",100);
            return false;
        }

        $group=static::GetGroup($id_group,$user);
        if(!$group->IsValid())
        {
            AA_Log::Log(__METHOD__." - identificativo gruppo non valido.",100);
            return false;
        }

        if(!$user->IsSuperUser())
        {
            if($group->GetProp("system") == 1)
            {
                AA_Log::Log(__METHOD__." - Gruppo non modificabile dall'utente corrente. (".$user->GetNome()." ".$user->GetCognome.")",100);
                return false;
            }
    
            if($group->GetProp("id_parent") > 0)
            {
                AA_Log::Log(__METHOD__." - Gruppo non modificabile dall'utente corrente. (".$user->GetNome()." ".$user->GetCognome.")",100);
                return false;
            }    
        }

        $db=new AA_Database();
        $query="DELETE FROM ".static::AA_DB_TABLE;
        $query.=" WHERE id='".addslashes($group->GetProp('id'))."' LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query. (".$db->GetErrorMessage().") - query: ".$query,100);
            return false;
        }

        return true;
    }

    //Restituisce un array di gruppi che soddisfano i criteri indicati
    static public function SearchGroups($params=array())
    {

        return array();
    }

    //restituisce gli utenti che partecipano del gruppo corrente
    public function GetUsers()
    {

        return array();
    }
}
