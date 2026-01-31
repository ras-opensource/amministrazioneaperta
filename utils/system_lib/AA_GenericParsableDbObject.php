<?php
Class AA_GenericParsableDbObject extends AA_GenericParsableObject
{
    static protected $dbDataTable="";
    public static function GetDatatable()
    {
        return static::$dbDataTable;
    }
    static protected $ObjectClass=__CLASS__;
    public static function GetObjectClass()
    {
        return static::$ObjectClass;
    }
    static protected $dbClass="AA_Database";
    public static function GetDbClass()
    {
        return static::$dbClass;
    }

    public function __construct($params=null)
    {
        parent::__construct($params);
    }

    protected function Sync()
    {
        if(static::$dbDataTable == "") 
        {
            AA_Log::Log(__METHOD__." - Tabella non definita.",100);
            return false;
        }
 
        $db=new static::$dbClass();
        
        if($this->aProps['id']<=0)
        {
            $query="INSERT INTO ".static::$dbDataTable." SET ";
            $this->aProps['id']=0;
        }
        else
        {
            $query="UPDATE ".static::$dbDataTable." SET ";
        }

        $sep="";
        foreach($this->aProps as $key=>$val)
        {
            if($key !="id")
            {
                $query.=$sep.$key."='".addslashes($val)."'";
                $sep=",";
            }
        }

        if($this->aProps['id']>0)
        {
            $query.=" WHERE id='".intVal($this->aProps['id'])."' LIMIT 1";
        }

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
            return false;
        }

        if($this->aProps['id']==0)
        {
            $this->aProps['id']=$db->GetLastInsertId();
        }

        return true;
    }

    public function Update($params=null, $user=null)
    {
        if(is_array($params))
        {
            $this->Parse($params);
        }

        return $this->Sync();
    }

    public static function Search($params=null)
    {
        if(static::$dbDataTable == "") return array();
 
        $db=new static::$dbClass();
        $query="SELECT * FROM ".static::$dbDataTable;
        $where="";
        $order="";
        if(isset($params['WHERE']) && is_array($params['WHERE']))
        {
            foreach($params['WHERE'] as $curFilter)
            {
                $currentWhere="";
                if(is_array($curFilter))
                {
                    if(isset($curFilter['FIELD']))
                    {
                        $currentWhere.=" ".static::$dbDataTable.".".$curFilter['FIELD']." ";
                        
                        //operatore
                        if(isset($curFilter['OPERATOR']))
                        {
                            $currentWhere.=" ".$curFilter['OPERATOR']." ";
                        }
                        else
                        {
                            $currentWhere.=" LIKE ";
                        }

                        //valore
                        if(isset($curFilter['VALUE']))
                        {
                            $currentWhere.=" ".$curFilter['VALUE']." ";
                        }
                        else
                        {
                            AA_Log::Log(__METHOD__." - Errore parametro di ricerca, manca il campo VALUE - ".print_r($curFilter,true),100);
                            $currentWhere="";
                        }
                    }
                    else
                    {
                        AA_Log::Log(__METHOD__." - Errore parametro di ricerca, manca il campo FIELD - ".print_r($curFilter,true),100);
                    }
                }

                if($currentWhere !="")
                {
                    if($where=="")
                    {
                        $where = " WHERE ".$currentWhere;
                    }
                    else
                    {
                        if(isset($curFilter['CONCAT_OPERATOR']))
                        {
                            $where.=" ".$curFilter['CONCAT_OPERATOR']." ".$currentWhere;
                        }
                        else $where.=" AND ".$currentWhere;
                    }
                }
            }
        }

        //order
        if(isset($params['ORDER']) && is_array($params['ORDER']))
        {
            foreach($params['ORDER'] as $key=>$curOrder)
            {
                if($order=="") $order=" ORDER BY ".$curOrder;
                else $order.=",".$curOrder;
            }
        }

        //limit
        $limit="";
        if(isset($params['LIMIT']) && $params['LIMIT'] !="")
        {
            $limit=" LIMIT ".$params['LIMIT'];
        }

        $query.=$where.$order.$limit;
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." Query - ".$query." - Errore: ".$db->GetErrorMessage(),100);
            return array();
        }

        //AA_Log::Log(__METHOD__." - query: ".$query,100);

        if($db->GetAffectedRows() == 0) return array();
        
        $rs=$db->GetResultSet();
        $return=array();
        $class=static::$ObjectClass;
        if(!class_exists($class)) $class=__CLASS__;

        foreach($rs as $id=>$row)
        {
            $return[$id]=new $class($row);
        }

        return $return;
    }

    protected function LoadDataFromDb($id=0)
    {
        if($id<=0 || static::$dbDataTable=="") return null;

        if(static::$dbDataTable == "") return null;
 
        $db=new static::$dbClass();
        $query="SELECT * FROM ".static::$dbDataTable." WHERE id = '".addslashes($id)."'";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
            return null;
        }

        if($db->GetAffectedRows() == 0) return null;
        
        $rs=$db->GetResultSet();

        return $rs[0];
    }

    public function Load($id=0,$user=null)
    {
        $data=$this->LoadDataFromDb($id);
        if(is_array($data))
        {
            $this->Parse($data);
            return true;
        }

        return false;
    }

    public function Delete($user=null)
    {
        if($this->aProps['id']>0) return $this->DeleteFromDb();
        return true;
    }

    protected function DeleteFromDb()
    {
        if($this->aProps['id']<=0 || static::$dbDataTable == "")
        {
            AA_Log::Log(__METHOD__." - Identificativo non valido o tabella non definita.",100);
            return false;
        } 

        //AA_Log::Log(__METHOD__." - db class: ".static::$dbClass,100);
        
        $db=new static::$dbClass();

        $id=intVal($this->aProps['id']);
        if(!$db->Query("DELETE FROM ".static::$dbDataTable." WHERE id='".addslashes($id)."' LIMIT 1"))
        {
            AA_Log::Log(__METHOD__." - ".$db->GetErrorMessage(),100);
            return false;
        }

        return true;
    }
}
