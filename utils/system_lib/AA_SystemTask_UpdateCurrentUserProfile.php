<?php
class AA_SystemTask_UpdateCurrentUserProfile extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("UpdateCurrentUserProfile", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content'>";

        $user=AA_User::GetCurrentUser();
        if($user->IsGuest())
        {
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Utente non valido o sessione scaduta</error>";
            $this->SetLog($sTaskLog);
            
            return false;
        }

        if($_REQUEST['email'] =="" || $_REQUEST['nome']=="" || $_REQUEST['cognome']=="")
        {
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Parametri non validi.</error>";
            $this->SetLog($sTaskLog);
            
            return false;
        }

        $imageFileName="";

        //Recupera il file immagine
        $imgFile=AA_SessionFileUpload::Get("UserProfileImage");
        if($imgFile->IsValid())
        {
            $imgFilePath=$imgFile->GetValue();
            if(!is_file($imgFilePath["tmp_name"]))
            {
                $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
                $sTaskLog.= "{}";
                $sTaskLog.="</content><error id='error'>Immagine profilo non caricata (2).</error>";
                $this->SetLog($sTaskLog);
    
                return false;
            }

            if(AA_Const::AA_ROOT_STORAGE_PATH == null || AA_Const::AA_ROOT_STORAGE_PATH == "")
            {
                //elimina la precedente immagine se è presente
                if(is_file(AA_Const::AA_APP_FILESYSTEM_FOLDER."/immagini/profili/".$user->GetImage()) && $user->GetImage() !="")
                {
                    if(!unlink(AA_Const::AA_APP_FILESYSTEM_FOLDER."/immagini/profili/".$user->GetImage()))
                    {
                        AA_Log::Log(__METHOD__." - Errore nell'eliminazione dell'immagine del profilo (".$user->GetImage().")",100);
                    }
                }

                $imageFileName=$user->GetId()."_".Date("Ymdhis");

                //copia l'immagine nella cartella dei profili
                if(!rename($imgFilePath["tmp_name"],AA_Const::AA_APP_FILESYSTEM_FOLDER."/immagini/profili/".$imageFileName))
                {
                    $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
                    $sTaskLog.= "{}";
                    $sTaskLog.="</content><error id='error'>Immagine profilo non caricata (3).</error>";
                    $this->SetLog($sTaskLog);

                    return false;            
                }
            }
            else
            {
                
                $storage=AA_Storage::GetInstance();
                if(!$storage->IsValid())
                {
                    AA_Log::Log(__METHOD__." - storage non inizializzato.",100);
                    if(!unlink($imgFilePath["tmp_name"]))
                    {
                        AA_Log::Log(__METHOD__." - file temporaneo non eliminato. (".$imgFilePath["tmp_name"].")",100);
                    }
                }
                else
                {
                    $imageFile=$storage->AddFile($imgFilePath["tmp_name"],$imgFilePath["name"],$imgFilePath["type"],1);
                    if($imageFile->IsValid()) $imageFileName=$imageFile->GetFileHash();
                }
            }
        }

        if(!AA_User::UpdateCurrentUserProfile($_REQUEST,$imageFileName))
        {
            $sTaskLog = "<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            $this->SetLog($sTaskLog);
            
            return false;
        }

        //Profilo aggiornato
        $sTaskLog .= "Profilo utente aggiornato con successo.</content>";

        $this->SetLog($sTaskLog);

        return true;
    }
}
