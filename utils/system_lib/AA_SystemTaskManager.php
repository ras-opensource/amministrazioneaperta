<?php
class AA_SystemTaskManager extends AA_GenericTaskManager
{
    public function __construct($user = null)
    {
        parent::__construct($user);

        //Registrazione task per l'albero delle strutture
        $this->RegisterTask("struttura-utente", "AA_SystemTask_TreeStruct");

        //Restituisce lo stato dell piattaforma
        $this->RegisterTask("GetAppStatus", "AA_SystemTask_GetAppStatus");

        //Restituisce lo stato dell piattaforma
        $this->RegisterTask("GetStructTreeData", "AA_SystemTask_GetStructTreeData");

        //Restituisce il contenuto del sidemenu
        $this->RegisterTask("GetSideMenuContent", "AA_SystemTask_GetSideMenuContent");

        //Registrazione task per la finestra dell'albero delle strutture utente (nuova versione)
        $this->RegisterTask("GetStructDlg", "AA_SystemTask_GetStructDlg");

        //Restituisce la struttura base della finestra AMAAI
        $this->RegisterTask("AMAAI_Start", "AA_SystemTask_AMAAI_Start");

        //Restituisce la la finestra del pdf preview
        $this->RegisterTask("GetPdfPreviewDlg", "AA_SystemTask_GetPdfPreviewDlg");

        //imposta una variabile di sessione
        $this->RegisterTask("SetSessionVar", "AA_SystemTask_SetSessionVar");

        //Upload session file
        $this->RegisterTask("UploadSessionFile", "AA_SystemTask_UploadSessionFile");

        //Restituisce la finestra dei log di un oggetto
        $this->RegisterTask("GetLogDlg", "AA_SystemTask_GetLogDlg");

        //Aggiorna la password dell'utente corrente
        $this->RegisterTask("GetChangeCurrentUserPwdDlg","AA_SystemTask_GetChangeCurrentUserPwdDlg");

        //Visualizza i dati del profilo utente corrente
        $this->RegisterTask("GetCurrentUserProfileDlg","AA_SystemTask_GetCurrentUserProfileDlg");

        //Aggiorna il profilo dell'utente corrente
        $this->RegisterTask("UpdateCurrentUserProfile","AA_SystemTask_UpdateCurrentUserProfile");

        //Aggiorna la password dell'utente corrente
        $this->RegisterTask("UpdateCurrentUserPwd","AA_SystemTask_UpdateCurrentUserPwd");

        //Cambia il profilo utente corrente
        $this->RegisterTask("GetChangeCurrentUserProfileDlg","AA_SystemTask_GetChangeCurrentUserProfileDlg");
        //Cambia il profilo utente corrente
        $this->RegisterTask("ChangeCurrentUserProfile","AA_SystemTask_ChangeCurrentUserProfile");

        //visualizza lo stato del server
        $this->RegisterTask("GetServerStatus","AA_SystemTask_GetServerStatus");

        //visualizza lo stato del server dlg
        $this->RegisterTask("GetServerStatusDlg","AA_SystemTask_GetServerStatusDlg");

        //caricamento file da ckeditor5
        $this->RegisterTask("UploadFromCKeditor5","AA_SystemTask_UploadFromCKeditor5");

        //caricamento file dalla galleria
        $this->RegisterTask("UploadFromGallery","AA_SystemTask_UploadFromGallery");

        //galleria immagini
        $this->RegisterTask("GetGalleryDlg","AA_SystemTask_GetGalleryDlg");

        //galleria trash immagini
        $this->RegisterTask("GetGalleryTrashDlg","AA_SystemTask_GetGalleryTrashDlg");

        //refresh galleria immagini
        $this->RegisterTask("RefreshGalleryContent","AA_SystemTask_GetGalleryData");

        //refresh galleria immagini
        $this->RegisterTask("GetGalleryData","AA_SystemTask_GetGalleryData");

        //trash from galleria immagini
        $this->RegisterTask("TrashFromGallery","AA_SystemTask_TrashFromGallery");
    }
}
