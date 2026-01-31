<?php
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
