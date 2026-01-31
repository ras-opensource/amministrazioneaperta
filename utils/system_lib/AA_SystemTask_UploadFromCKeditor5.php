<?php
class AA_SystemTask_UploadFromCKeditor5 extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("UploadFromCKeditor5", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        //AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());
        $return=array();

        if(!$this->oUser->IsValid())
        {
            $return['error']=array("message"=>"Utente non valido.");

            die(json_encode($return));
        }

        if(empty($_FILES))
        {
            $return['error']=array("message"=>"File non presente (0).");

            die(json_encode($return));
        }

        $file=current($_FILES);
        if(!is_file($file['tmp_name']))
        {
            $return['error']=array("message"=>"File non presente (1).");

            die(json_encode($return));
        }

        if(strpos($file["type"],"image")===false)
        {
            $return['error']=array("message"=>"Il tipo di file selezionato non e' supportato.");

            die(json_encode($return));
        }

        $storage=AA_Storage::GetInstance($this->oUser);
        if(!$storage->IsValid())
        {
            $return['error']=array("message"=>"Storage non inizializzato.");

            die(json_encode($return));
        }

        $storageFile=$storage->AddFile($file['tmp_name'],$file['name'],$file['type']);
        if(!$storageFile->isValid())
        {
            $return['error']=array("message"=>"Errore nel caricamento del file sullo storage.");

            die(json_encode($return));
        }

        $fileInfo=array(
            "name"=>$storageFile->GetName(),
            "type"=>$storageFile->GetMimeType(),
            "size"=>$storageFile->GetFileSize(),
            "hash"=>$storageFile->GetFileHash()
        );

        $newRes=new AA_Risorse();
        $newRes->SetProp("categorie","galleria,ckeditor5");
        $newRes->SetProp("url_name","res_".uniqid(time()));
        $newRes->SetFileInfo($fileInfo);

        if(!$newRes->Update(null,$this->oUser))
        {
            $return['error']=array("message"=>"Errore nel salvataggio della risorsa.");

            die(json_encode($return));
        }

        $return['url']=AA_Const::AA_WWW_ROOT."/risorse/".$newRes->GetProp("url_name");
        die(json_encode($return));
    }
}
