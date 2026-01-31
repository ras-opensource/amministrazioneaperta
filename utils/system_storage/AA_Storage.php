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
                    $query="UPDATE ".static::AA_DBTABLE_STORAGE." set referencesCount=(referencesCount+1),aggiornamento='".date("Y-m-d")."', public='".addslashes($bPublic)."' WHERE id='".$props['id']."' LIMIT 1";
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

                $query="INSERT INTO ".static::AA_DBTABLE_STORAGE." SET name='".addslashes($name)."', mime='".$type."', aggiornamento='".date("Y-m-d")."', fileHash='".$hash."', size='".filesize($NewFilePath)."', referencesCount = 1, filePath='".$dir.DIRECTORY_SEPARATOR.$hash."', public='".addslashes($bPublic)."'";
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

    //Aggiunge un file allo storage a pertire da un upload
    public function AddFileFromUpload($fileUpload=null,$bPublic=0,$bAddReferenceIfExist=true)
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

        if(!($fileUpload instanceof AA_SessionFileUpload))
        {
            AA_Log::Log(__METHOD__." - Oggetto file Upload non definito.".print_r($fileUpload,true),100);
            return new AA_StorageFile();            
        }

        if(!$fileUpload->IsValid())
        {
            AA_Log::Log(__METHOD__." - Oggetto file Upload non valido.".print_r($fileUpload,true),100);
            return new AA_StorageFile();    
        }

        $file=$fileUpload->GetValue();

        if(file_exists($file['tmp_name']))
        {
            $hash=hash_file("sha256",$file['tmp_name']);

            //Verifica se il file è già presente
            $db=new AA_Database();
            $query="SELECT * from ".static::AA_DBTABLE_STORAGE." WHERE fileHash = '".$hash."'";

            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore nella query: ".$query,100);

                //rimuove il file temporaneo
                if(!unlink($file['tmp_name']))
                {
                    AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".print_r($file,true),100);
                }
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

                        //rimuove il file temporaneo
                        if(!unlink($file['tmp_name']))
                        {
                            AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".print_r($file,true),100);
                        }
                        return new AA_StorageFile();    
                    }
                    
                    //rimuove il file temporaneo
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".print_r($file,true),100);
                    }

                    return new AA_StorageFile($props);
                }
                else
                {
                    //rimuove il file temporaneo
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".print_r($file,true),100);
                    }

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

                        //rimuove il file temporaneo
                        if(!unlink($file['tmp_name']))
                        {
                            AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".print_r($file,true),100);
                        }

                        return new AA_StorageFile();
                    }
                }

                $NewFilePath=AA_Const::AA_ROOT_STORAGE_PATH.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$hash;
                if(!rename($file['tmp_name'],$NewFilePath))
                {
                    AA_Log::Log(__METHOD__." - errore durante la copia del file: ".$file['tmp_name']." in ".$NewFilePath,100);
                    
                    //rimuove il file temporaneo
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".print_r($file,true),100);
                    }
                    
                    return new AA_StorageFile();
                }

                $query="INSERT INTO ".static::AA_DBTABLE_STORAGE." SET name='".addslashes($file['name'])."', mime='".$file['type']."', aggiornamento='".date("Y-m-d")."', fileHash='".$hash."', size='".filesize($NewFilePath)."', referencesCount = 1, filePath='".$dir.DIRECTORY_SEPARATOR.$hash."', public='".addslashes($bPublic)."'";
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
                    'name'=>$file['name'],
                    'mime'=>$file['type'],
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

        AA_Log::Log(__METHOD__." - file ".$file['tmp_name']." non trovato.",100);
        return new AA_StorageFile();
    }

    //Rimuove un file dallo storage (file può essere un oggetto AA_StorageFile o un hash)
    public function DelFile($file=null)
    {
        if($this->oUser->IsGuest())
        {
            AA_Log::Log(__METHOD__." - L'utente guest non può eliminare files.",100);
            return false;
        }

        if(!$this->bValid)
        {
            AA_Log::Log(__METHOD__." - Storage non inizializzato.",100);
            return false;
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
            $query="UPDATE ".static::AA_DBTABLE_STORAGE." set referencesCount=(referencesCount-1),aggiornamento='".date("Y-m-d")."' WHERE fileHash='".$file->GetFileHash()."' LIMIT 1";
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
                return false;
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

    //Versione statica
    public function GetByHash($fileHash="",$user=null)
    {
        if(!($user instanceof AA_User)) $user=AA_User::GetCurrentUser();

        $storage=static::GetInstance($user);
        return $storage->GetFileByHash($fileHash);
    }

    //Versione statica
    public function GetByName($fileName="",$user=null)
    {
        if(!($user instanceof AA_User)) $user=AA_User::GetCurrentUser();

        $storage=static::GetInstance($user);
        return $storage->GetFileByName($fileName);
    }

    //Versione statica
    public function Delete($file="",$user=null)
    {
        if(!($user instanceof AA_User)) $user=AA_User::GetCurrentUser();

        $storage=static::GetInstance($user);
        return $storage->DelFile($file);
    }

    //Restituisce un riferimento al file in base all'hash se è presente nello storage
    public function GetFileByHash($fileHash="")
    {
        if(!$this->bValid)
        {
            AA_Log::Log(__METHOD__." - Storage non inizializzato.",100);
            return new AA_StorageFile();
        }

        if(strlen($fileHash) != 64) return new AA_StorageFile();

        $fileHash=str_replace("%","",$fileHash);

        //Verifica se il file è presente
        $db=new AA_Database();
        $query="SELECT * from ".static::AA_DBTABLE_STORAGE." WHERE fileHash = '".addslashes(trim($fileHash))."' LIMIT 1";

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

    public function AddFileReference($hash="",$user=null)
    {
        if(empty($hash))
        {
            AA_Log::Log(__METHOD__." - Hash non valido. ",100);
            return false;
        }

        $file=$this->GetFileByHash($hash);
        if(!$file->isValid())
        {
            AA_Log::Log(__METHOD__." - File non trovato. ",100);
            return false;
        }

        $db=new AA_Database();
        $query="UPDATE ".static::AA_DBTABLE_STORAGE." set referencesCount=(referencesCount+1),aggiornamento=".date("Y-m-d")." WHERE fileHash='".$file->GetFileHash()."' LIMIT 1";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query,100);
            return false;    
        }

        return true;
    }

    //rimuove dal db tutti i riferimenti a file inesistenti
    public function Purge()
    {
        //to do
    }
}
