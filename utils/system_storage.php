<?php

class AA_Storage
{
    //utente
    protected $oUser=null;

    //istanza
    protected static $oInstance=null;

    protected function __construct($user=null)
    {
        if($user instanceof AA_User && !$user->IsGuest() && !$user->isCurrentUser())
        {
            $this->oUser=AA_User::GetCurrentUser();
        }

        if(!($user instanceof AA_User))
        {
            $this->oUser=AA_User::GetCurrentUser();
        }
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
    public function AddFile($filePath=null)
    {

    }

    //Rimuove un file dallo storage (file può essere un oggetto AA_StorageFile o un hash)
    public function DelFile($file=null)
    {

    }

    //Restituisce un riferimento al file se è presente nello storage
    public function GetFile($fileHash="")
    {
        
    }
}

class AA_StorageFile
{

}

