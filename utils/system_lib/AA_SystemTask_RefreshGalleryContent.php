<?php
class AA_SystemTask_RefreshGalleryContent extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("RefreshGalleryContent", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $immagini=AA_Risorse::Search(array("WHERE"=>array(array("FIELD"=>"categorie","VALUE"=>"'%galleria%'"))));

        $listData=array();
        foreach($immagini as $curImage)
        {
            $listData[]=array("id"=>$curImage->GetProp("id"),"img_url"=>AA_Config::AA_WWW_ROOT."/risorse/".$curImage->GetProp("url_name"),"url"=>$curImage->GetProp("url_name"));
        }
        
        die(json_encode($listData));
    }
}
