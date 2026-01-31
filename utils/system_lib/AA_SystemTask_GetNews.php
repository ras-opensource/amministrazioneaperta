<?php
class AA_SystemTask_GetNews extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetNews", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $this->GetName());

        if($this->oUser->IsSuperUser())
        {
            $curlSES=curl_init(); 
            
            //aggiorna il feed completo
            curl_setopt($curlSES,CURLOPT_URL,"http://localhost/server-status");
            curl_setopt($curlSES,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curlSES,CURLOPT_HEADER, false); 
            $result=curl_exec($curlSES);
            curl_close($curlSES);

            $info=curl_getinfo($curlSES);
            //AA_Log::Log(__METHOD__." - info: ".print_r(curl_getinfo($curlSES),true), 100);

            if($info['http_code']!="200")
            {
                AA_Log::Log(__METHOD__." - Errore http(".$info['http_code'].") per l'url: http://localhost/server-status", 100);
                die(json_encode(array("status"=>"<div>Statistiche server non presenti.</div>")));
            }

            if($result===false)
            {
                AA_Log::Log(__METHOD__." - Errore (".curl_error($curlSES).").", 100);
                die(json_encode(array("status"=>"<div>Statistiche server non presenti.</div>")));
            }
            else
            {
                
               die(json_encode(array("status"=>"<div>".mb_substr($result,strpos($result,"<body>")+7,-15)."</div>")));
            }
        }
        else
        {
           die(json_encode(array("status"=>"<div>Statistiche server non presenti.</div>")));
        }
    }
}
