<?php

class AA_Storage
{
    //tabella di appoggio
    const AA_DBTABLE_STORAGE="aa_storage";

    //utente
    protected $oUser=null;

    //istanza
    protected static $oInstance=null;

    //flag di validità
    protected $bValid=false;
    public function IsValid()
    {
        return $this->bValid;
    }

    protected function __construct($user=null)
    {
        if($user instanceof AA_User && $user->isCurrentUser())
        {
            $this->oUser=$user;
        }
        else $this->oUser=AA_User::GetCurrentUser();

        if(!is_writable(AA_Const::AA_ROOT_STORAGE_PATH))
        {
            AA_Log::Log(__METHOD__." - Percorso root storage non esistente o accesso in sola lettura: ".AA_Const::AA_ROOT_STORAGE_PATH,100);
            return;
        }

        $this->bValid=true;
    }

    //restituisce l'istanza unica
    public static function GetInstance($user=null)
    {
        if (static::$oInstance == null)
        {
            static::$oInstance= new AA_Storage($user);
        }

        return static::$oInstance;
    }

    //Aggiunge un file allo storage
    public function AddFile($filePath=null,$name="new_file",$type="",$bPublic=0,$bAddReferenceIfExist=true)
    {
        if($this->oUser->IsGuest())
        {
            AA_Log::Log(__METHOD__." - L'utente guest non può caricare files.",100);
            return new AA_StorageFile();
        }

        if(!$this->bValid)
        {
            AA_Log::Log(__METHOD__." - Storage non inizializzato.",100);
            return new AA_StorageFile();
        }

        if(file_exists($filePath))
        {
            $hash=hash_file("sha256",$filePath);

            //Verifica se il file è già presente
            $db=new AA_Database();
            $query="SELECT * from ".static::AA_DBTABLE_STORAGE." WHERE fileHash = '".$hash."'";

            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore nella query: ".$query,100);
                return new AA_StorageFile();
            }

            //file già presente
            if($db->GetAffectedRows()>0)
            {
                $rs=$db->GetResultSet();
                $props=$rs[0];
                $props['filePath']=AA_Const::AA_ROOT_STORAGE_PATH.DIRECTORY_SEPARATOR.$props['filePath'];
                $props['public']=$bPublic;
                if($bAddReferenceIfExist)
                {
                    //aumenta i riferimenti e restituisce il riferimento al file    
                    $props['aggiornamento']=date("Y-m-d");
                    $props['referencesCount']+=1;    
                    //Aggiorna il db
                    $query="UPDATE ".static::AA_DBTABLE_STORAGE." set referencesCount=(referencesCount+1),aggiornamento=".date("Y-m-d").", public='".addslashes($bPublic)."' WHERE id='".$props['id']."' LIMIT 1";
                    if(!$db->Query($query))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella query: ".$query,100);
                        return new AA_StorageFile();    
                    }

                    return new AA_StorageFile($props);
                }
                else
                {
                    return new AA_StorageFile($props);
                }
            }
            else
            {
                //Aggiunge il file allo storage
                $dir=date("Y-m");
                if(!is_dir(AA_Const::AA_ROOT_STORAGE_PATH.DIRECTORY_SEPARATOR.$dir))
                {
                    //crea la directory
                    if(!mkdir(AA_Const::AA_ROOT_STORAGE_PATH.DIRECTORY_SEPARATOR.$dir))
                    {
                        AA_Log::Log(__METHOD__." - errore durante la creazione della directory: ".AA_Const::AA_ROOT_STORAGE_PATH.DIRECTORY_SEPARATOR.$dir,100);
                        return new AA_StorageFile();
                    }
                }

                $NewFilePath=AA_Const::AA_ROOT_STORAGE_PATH.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$hash;
                if(!rename($filePath,$NewFilePath))
                {
                    AA_Log::Log(__METHOD__." - errore durante la copia del file: ".$filePath." in ".$NewFilePath,100);
                    return new AA_StorageFile();
                }

                $query="INSERT INTO ".static::AA_DBTABLE_STORAGE." SET name='".$name."', mime='".$type."', aggiornamento='".date("Y-m-d")."', fileHash='".$hash."', size='".filesize($NewFilePath)."', referencesCount = 1, filePath='".$dir.DIRECTORY_SEPARATOR.$hash."', public='".addslashes($bPublic)."'";
                if(!$db->Query($query))
                {
                    if(!unlink($NewFilePath))
                    {
                        AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$NewFilePath,100);
                    }

                    AA_Log::Log(__METHOD__." - errore nella query: ".$query,100);
                    return new AA_StorageFile();
                }

                $props=array(
                    'name'=>$name,
                    'mime'=>$type,
                    'aggiornamento'=>date("Y-m-d"),
                    'referencesCount'=>1,
                    'fileHash'=>$hash,
                    'filePath'=>$NewFilePath,
                    'size'=>filesize($NewFilePath),
                    'public'=>$bPublic
                );

                return new AA_StorageFile($props);
            }
        }

        AA_Log::Log(__METHOD__." - file ".$filePath." non trovato.",100);
        return new AA_StorageFile();
    }

    //Rimuove un file dallo storage (file può essere un oggetto AA_StorageFile o un hash)
    public function DelFile($file=null)
    {
        if($this->oUser->IsGuest())
        {
            AA_Log::Log(__METHOD__." - L'utente guest non può eliminare files.",100);
            return new AA_StorageFile();
        }

        if(!$this->bValid)
        {
            AA_Log::Log(__METHOD__." - Storage non inizializzato.",100);
            return new AA_StorageFile();
        }

        if(!($file instanceof AA_StorageFile)) $file=$this->GetFileByHash($file);

        if(!$file->isValid())
        {
            AA_Log::Log(__METHOD__." - File non valido: ".print_r($file,true),100);
            return false;
        }

        $db=new AA_Database();

        if($file->GetReferences() > 1)
        {
            AA_Log::Log(__METHOD__." - Riduzione riferimenti del file: ".print_r($file,true),100);

            //Aggiorna il db
            $query="UPDATE ".static::AA_DBTABLE_STORAGE." set referencesCount=(referencesCount-1),aggiornamento=".date("Y-m-d")." WHERE fileHash='".$file->GetFileHash()."' LIMIT 1";
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore nella query: ".$query,100);
                return false;
            }

            //Verifica se il file è da eliminare
            $query="SELECT * from ".static::AA_DBTABLE_STORAGE." WHERE fileHash = '".addslashes(trim($file->GetFileHash()))."'";

            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore nella query: ".$query,100);
                return new AA_StorageFile();
            }

            if($db->GetAffectedRows()>0)
            {
                $rs=$db->GetResultSet();
                if($rs[0]['referencesCount']<=0)
                {
                    //AA_Log::Log(__METHOD__." - Eliminazione del file: ".print_r($file,true),100);
                    if(!unlink($file->GetFilePath()))
                    {
                        AA_Log::Log(__METHOD__." - Errore durante l'eliminazione del file: ".$file->GetFilePath(),100);
                        return false;
                    }

                    //Aggiorna il db
                    $query="DELETE FROM ".static::AA_DBTABLE_STORAGE." WHERE fileHash='".$file->GetFileHash()."' LIMIT 1";
                    if(!$db->Query($query))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella query: ".$query,100);
                        return false;
                    }
                }
            }

            return true; 
        }
        else
        {
            AA_Log::Log(__METHOD__." - Eliminazione del file: ".print_r($file,true),100);
            if(!unlink($file->GetFilePath()))
            {
                AA_Log::Log(__METHOD__." - Errore durante l'eliminazione del file: ".print_r($file,true),100);
                return false;
            }

            //Aggiorna il db
            $query="DELETE FROM ".static::AA_DBTABLE_STORAGE." WHERE fileHash='".$file->GetFileHash()."' LIMIT 1";
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore nella query: ".$query,100);
                return false;
            }

            return true;
        }
    }

    //Restituisce un riferimento al file in base all'hash se è presente nello storage
    public function GetFileByHash($fileHash="")
    {
        if(!$this->bValid)
        {
            AA_Log::Log(__METHOD__." - Storage non inizializzato.",100);
            return new AA_StorageFile();
        }

        //Verifica se il file è presente
        $db=new AA_Database();
        $query="SELECT * from ".static::AA_DBTABLE_STORAGE." WHERE fileHash = '".addslashes(trim($fileHash))."'";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query,100);
            return new AA_StorageFile();
        }

        //file già presente
        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            $props=$rs[0];
            $props['filePath']=AA_Const::AA_ROOT_STORAGE_PATH.DIRECTORY_SEPARATOR.$props['filePath'];

            return new AA_StorageFile($props);
        }
        else return new AA_StorageFile();
    }

    //Restituisce un riferimento al file in base al nome se è presente nello storage
    public function GetFileByName($fileName="")
    {
        if(!$this->bValid)
        {
            AA_Log::Log(__METHOD__." - Storage non inizializzato.",100);
            return new AA_StorageFile();
        }

        //Verifica se il file è presente
        $db=new AA_Database();
        $query="SELECT * from ".static::AA_DBTABLE_STORAGE." WHERE name = '".addslashes(trim($fileName))."'";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query,100);
            return new AA_StorageFile();
        }

        //file già presente
        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            $props=$rs[0];
            $props['filePath']=AA_Const::AA_ROOT_STORAGE_PATH.DIRECTORY_SEPARATOR.$props['filePath'];

            return new AA_StorageFile($props);
        }
        else return new AA_StorageFile();
    }

    //rimuove dal db tutti i riferimenti a file inesistenti
    public function Purge()
    {
        //to do
    }
}

class AA_StorageFile
{
    protected $bValid=false;

    //nome file
    protected $sName="senza_nome";
    public function GetName()
    {
        return $this->sName;
    }

    //visibilità pubblica
    protected $bPublic=0;
    public function IsPublic()
    {
        if($this->bPublic > 0) return true;
        return false;
    }

    //mime type
    protected $sMimeType="";
    public function GetMimeType()
    {
        return $this->sMimeType;
    }

    //Ultimo aggiornamento
    protected $sAggiornamento="";
    public function GetAggiornamento()
    {
        return $this->sAggiornamento;
    }

    //hash
    protected $sHash="";
    public function GetFileHash()
    {
        return $this->sHash;
    }

    //server path
    protected $sFilePath="";
    public function GetFilePath()
    {
        return $this->sFilePath;
    }

    //size
    protected $nFileSize=0;
    public function GetFileSize()
    {
        return $this->nFileSize;
    }

    //referenze
    protected $nReferences=0;
    public function GetReferences()
    {
        return $this->nReferences;
    }

    public function __construct($props=null)
    {
        if(is_array($props))
        {
            if($props['name'] !="")
            {
                $this->sName=$props['name'];
            }

            if($props['mime'] !="")
            {
                $this->sMimeType=$props['mime'];
            }

            if($props['aggiornamento'] !="")
            {
                $this->sAggiornamento=$props['aggiornamento'];
            }

            if(file_exists($props['filePath']))
            {
                $this->sFilePath=$props['filePath'];
                if($props['referencesCount'] > 0)
                {
                    $hash=hash_file("sha256",$props['filePath']);
                    if($props['fileHash'] == $hash)
                    {
                        $this->sHash=$props['fileHash'];
                        $this->nReferences=$props['referencesCount'];
                        $this->nFileSize=filesize($props['filePath']);
                        if(isset($props['public']) && $props['public'] > 0) $this->bPublic=1;
                        $this->bValid=true;
                    }    
                }
            }
        }
    }

    public function isValid()
    {
        return $this->bValid;
    }
}

