<?php
class AA_SystemTask_GetAppStatus extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetAppStatus", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $this->GetName());

        $this->sTaskLog = "<status id='status'>0</status><content id='content' type='xml'>";

        //Imposta i parametri del device e del viewport
        if(isset($_REQUEST['mobile']))
        {
            if($_REQUEST['mobile'] > 0 ) $_SESSION['mobile']=true;
            else $_SESSION['mobile']=false;

            //AA_Log::Log(__METHOD__." - mobile: ".$_REQUEST['mobile'],100);
        } 

        if(isset($_REQUEST['viewport_width']))
        {
            $_SESSION['viewport_width']=$_REQUEST['viewport_width'];
            //AA_Log::Log(__METHOD__." - viewport_width: ".$_REQUEST['viewport_width'],100);
        }

        if(isset($_REQUEST['viewport_height']))
        {
            $_SESSION['viewport_height']=$_REQUEST['viewport_height'];
            //AA_Log::Log(__METHOD__." - viewport_width: ".$_REQUEST['viewport_width'],100);
        }

        //dati utente
        $this->sTaskLog .= $this->oUser->toXml();

        //registered mods
        $platform = AA_Platform::GetInstance($this->oUser);

        //AA_Log::Log(__METHOD__." - ".print_r($_REQUEST,true),100);

        if ($platform->IsValid()) {
            $sideBarContent = array();
            $mods = $platform->GetModules();

            $itemSelected="";
            foreach ($mods as $curMod) 
            {
                $admins = explode(",", $curMod['admins']);
                if($curMod['visible']==1 || in_array($this->oUser->GetId(), $admins) || $this->oUser->IsSuperUser())
                {
                    //Modulo da selezionare
                    if ($_REQUEST['module'] == $curMod['id_modulo']) {
                        //AA_Log::Log(__METHOD__." - Seleziono il modulo: ".$_REQUEST['module'],100);
                        $itemSelected = $curMod['id_sidebar'];
                    }

                    $modules[] = array("id" => $curMod['id_modulo'], "remote_folder" => AA_Const::AA_PUBLIC_MODULES_PATH . DIRECTORY_SEPARATOR . $curMod['id_sidebar'] . "_" . $curMod['id'], "icon" => $curMod['icon'], "name" => $curMod['tooltip']);

                    $sideBarContent[] = array("id" => $curMod['id_sidebar'], "icon" => $curMod['icon'], "value" => $curMod['name'], "tooltip" => $curMod['tooltip'], "module" => $curMod['id_modulo']);
                }  
            }

            $this->sTaskLog .= "<sidebar id='sidebar' itemSelected='$itemSelected'>";
            $this->sTaskLog .= json_encode($sideBarContent);

            $this->sTaskLog .= '</sidebar>';

            $this->sTaskLog .= "<sidebar id='sidebar'>";

            //configurazione moduli
            $this->sTaskLog .= "<modules id='modules'>";
            $this->sTaskLog .= json_encode($modules);
            $this->sTaskLog .= "</modules>";
            #------------------------

            $this->sTaskLog .= "</content>";

            return true;
        }
    }
}
