<?php
class AA_SystemTask_GetGalleryTrashDlg extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetGalleryTrashDlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        if(!$this->oUser->IsSuperUser())
        {
            $this->sTaskLog = "<status id='status'>-1</status><error id='error'>L'utente corrente non puo' effettuare l'azione indicata.</error>";
            return false;
        }

        if(empty($_REQUEST['id']))
        {
            $this->sTaskLog = "<status id='status'>-1</status><error id='error'>Id immagine impostato.</error>";
            return false;
        }

        $img=new AA_Risorse();
        if(!$img->Load($_REQUEST['id']))
        {
            $this->sTaskLog = "<status id='status'>-1</status><error id='error'>Immagine non trovata</error>";
            return false;
        } 
        //AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());
        $wnd = new AA_GalleryTrashDlg("GalleriaTrashDlg_".uniqid(),"Galleria immagini",$img);

        //AA_Log::Log(__METHOD__." - ".$wnd->toString(),100);

        $this->sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>" . $wnd->toBase64() . "</content><error id='error'></error>";
        return true;
    }
}
