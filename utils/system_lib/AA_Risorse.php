<?php
Class AA_Risorse extends AA_GenericParsableDbObject
{
    static protected $dbDataTable="aa_risorse";
    static protected $ObjectClass=__CLASS__;

    public function __construct($params = null)
    {
        $this->aProps['url_name']="";
        $this->aProps['categorie']="";
        $this->aProps['file_info']="";

        parent::__construct($params);
    }

    public static function LoadFromUrlName($url_name)
    {
        if(empty($url_name))
        {
            return false;
        }

        $risorse=AA_Risorse::Search(array("WHERE"=>array(array("FIELD"=>"url_name","VALUE"=>"'".addslashes(trim($url_name))."'"))));
        if(sizeof($risorse) > 0)
        {
            return current($risorse);
        }

        return false;
    } 

    protected function Parse($values = null)
    {
        $file_info=array();
        if(is_array($values) && isset($values['file_info']))
        {
            /*
            [name] => MyFile.txt (comes from the browser, so treat as tainted)
            [type] => text/plain  (not sure where it gets this from - assume the browser, so treat as tainted)
            [size] => 123   (the size in bytes)
            [hash] => file hash (storage)
            */
            if(is_array($values['file_info']))
            {
                foreach($values['file_info'] as $key=>$curValue)
                {

                    if($key=="name" || $key=="type" || $key=="size" || $key=="hash") $file_info[$key]=$curValue;
                }
            }

            if(sizeof($file_info)>0) $values['file_info']=json_encode($file_info);
        }

        return parent::Parse($values);
    }

    protected $fileInfo=null;
    public function GetFileInfo()
    {
        if(!empty($this->aProps['file_info']) && !is_array($this->fileInfo))
        {
            $this->fileInfo=json_decode($this->aProps['file_info'],true);
            if(!$this->fileInfo)
            {
                AA_Log::Log(__METHOD__." - errore nel parsing del file info: ".$this->aProps['file_info'],100);
                return array();
            }
        }

        if(is_array($this->fileInfo)) return $this->fileInfo;
        else return array();
    }
    public function SetFileInfo($val=null)
    {
        if(is_array($val)) 
        {
            $this->fileInfo=$val;
            $this->aProps['file_info']=json_encode($val);
        }
    }

    static protected function AddFile($params=null,$user=null)
    {
        $storage=AA_Storage::GetInstance($user);
        if(!$storage->IsValid())
        {
            AA_Log::Log(__METHOD__." - Storage non configurato.",100);
            return false;
        }

        if(!is_file($params['path']))
        {
            AA_Log::Log(__METHOD__." - File non valido.",100);
            return false;
        }

        if(empty($params['name'])) $params['name']="nuovo_file";
        if(empty($params['type'])) $params['type']=mime_content_type($params['path']);
        if(empty($params['size'])) $params['size']=filesize($params['path']);

        $fileInfo=array(
            'name'=>$params['name'],
            'type'=>$params['type'],
            'size'=>$params['size']
        );

        $newRes=array();
        $public=false;
        $newRes['url_name']=$params['url_name'];

        //carica il file sullo storage
        $newFile=$storage->AddFile($params['path'],$params['name'],$params['type'],$public);
        if($newFile->isValid())
        {
            $fileInfo['hash']=$newFile->GetFileHash();
            $newRes['file_info']=json_encode($fileInfo);
            
            if(!empty($params['categorie'])) $newRes['categorie']=$params['categorie'];

            $newResource=new AA_Risorse();
            if($newResource->Update($newRes,$user))
            {
                return $newResource;
            }
        }          

        return false;
    }

    static public function AddFileFromStorage($hash="",$url_name="",$categorie="",$user=null)
    {
        if(!($user instanceof AA_User) || $user->isCurrentUser()) $user=AA_User::GetCurrentUser();
        if(!$user->IsSuperUser())
        {
            AA_Log::Log(__METHOD__." - L'utente corrente non puo' aggiungere nuove risorse.",100);
            return false;
        }

        $storage=AA_Storage::GetInstance($user);
        if(!$storage->IsValid())
        {
            AA_Log::Log(__METHOD__." - Storage non configurato.",100);
            return false;
        }

        if(empty($hash))
        {
            AA_Log::Log(__METHOD__." - Hash non valido.",100);
            return false;
        }

        $fileStorage=$storage->GetFileByHash($hash);

        if(!$fileStorage->isValid())
        {
            AA_Log::Log(__METHOD__." - file non trovato: ".$hash,100);
            return false;
        }

        if(!$storage->AddFileReference($fileStorage,$user))
        {
            return false;
        }

        $fileInfo=array(
            'name'=>$fileStorage->GetName(),
            'type'=>$fileStorage->GetMimeType(),
            'size'=>$fileStorage->GetFileSize(),
            'hash'=>$fileStorage->GetFileHash()
        );

        $newRes=array();
        $newRes['url_name']=$url_name;
        $newRes['categorie']=$categorie;
        $newRes['file_info']=json_encode($fileInfo);
        
        $newResource=new AA_Risorse();
        if($newResource->Update($newRes,$user))
        {
            return $newResource;
        }

        return false;
    }

    public function AddGenericFileFromUpload($url_name="",$categorie="",$user=null)
    {
        if(!($user instanceof AA_User) || $user->isCurrentUser()) $user=AA_User::GetCurrentUser();
        if(!$user->IsSuperUser())
        {
            AA_Log::Log(__METHOD__." - L'utente corrente non puo' effettuare l'upload di file.",100);
            return false;
        }

        if(empty($_FILES))
        {
            AA_Log::Log(__METHOD__." - Non sono presenti file.",100);
            return false;
        }

        $file=current($_FILES);
        if(!is_file($file['tmp_name']))
        {
            AA_Log::Log(__METHOD__." - file non trovato: ".print_r($file,true),100);
            return false;
        }
        return AA_Risorse::AddFile(array('url_name'=>$url_name,'categorie'=>$categorie,'name'=>$file['name'],'type'=>$file['type'],'size'=>$file['size'],'path'=>$file['tmp_name']),$user);
    }

    public function GetFile($user=null)
    {
        $fileInfo=$this->GetFileInfo();
        if(empty($fileInfo['hash'])) return false;

        $storage=AA_Storage::GetInstance($user);
        if(!$storage->IsValid())
        {
            return false;
        }

        $file=$storage->GetFileByHash($fileInfo['hash']);
        if(!$file->IsValid()) return false;

        return $file;
    }

    public function Delete($user=null)
    {
        if(!($user instanceof AA_User) || $user->isCurrentUser()) $user=AA_User::GetCurrentUser();
        if(!$user->IsSuperUser())
        {
            AA_Log::Log(__METHOD__." - L'utente corrente non puo' eliminare risorse.",100);
            return false;
        }

        
        $storage=AA_Storage::GetInstance($user);
        if(!$storage->IsValid())
        {
            return false;
        }

        $fileInfo=$this->GetFileInfo();
        if(!$storage->DelFile($fileInfo['hash'])) return false;

        return parent::Delete($user);
    }
}
