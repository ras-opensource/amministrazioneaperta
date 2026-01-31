<?php
class AA_SystemTask_TrashFromGallery extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("TrashFromGallery", $user);
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

        if(!$img->Delete($this->oUser))
        {
            $this->sTaskLog = "<status id='status'>-1</status><error id='error'>Errore nell'eliminazione dell'immagine.</error>";
            return false;
        }

        $this->sTaskLog = "<status id='status' action='RefreshGallery' action_params='{\"galleryId\":\"".$_REQUEST['refresh_obj_id']."\"}'>0</status><content id='content'>Immagine eliminata</content><error id='error'></error>";
        return true;
    }
}
