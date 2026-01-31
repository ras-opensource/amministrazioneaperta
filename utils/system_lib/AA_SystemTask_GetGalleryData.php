<?php
class AA_SystemTask_GetGalleryData extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetGalleryData", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $pos=0;
        $tot=0;
        $count=50;
        //calcola il numero totale di immagini
        $db=new AA_Database();
        $query="SELECT count(id) as tot FROM ".AA_Risorse::GetDatatable()." WHERE categorie like '%galleria%'";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage()." - query: ".$query,100);
        }
        else
        {
            $rs=$db->GetResultSet();
            $tot=$rs[0]['tot'];

            AA_Log::Log(__METHOD__." - Trovate ".$tot." immagini",100);
        }

        if(!empty($_REQUEST['start'])) $pos=intVal($_REQUEST['start']);
        if(!empty($_REQUEST['count'])) $count=intVal($_REQUEST['count']);

        $immagini=AA_Risorse::Search(array("WHERE"=>array(array("FIELD"=>"categorie","VALUE"=>"'%galleria%'")),"LIMIT"=>$pos.",".$count));

        $listData=array("pos"=>$pos,"total_count"=>$tot,"data"=>array());

        foreach($immagini as $curImage)
        {
            $listData['data'][]=array("id"=>$curImage->GetProp("id"),"img_url"=>AA_Config::AA_WWW_ROOT."/risorse/".$curImage->GetProp("url_name"),"url"=>$curImage->GetProp("url_name"));
        }
        
        die(json_encode($listData));
    }
}
