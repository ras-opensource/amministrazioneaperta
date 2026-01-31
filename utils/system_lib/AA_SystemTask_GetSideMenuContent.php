<?php
class AA_SystemTask_GetSideMenuContent extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetSideMenuContent", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $this->GetName());

        $this->sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        //registered mods
        $platform = AA_Platform::GetInstance($this->oUser);

        //AA_Log::Log(__METHOD__." - ".print_r($_REQUEST,true),100);

        if ($platform->IsValid()) {
            $sideBarContent = array();
            $mods = $platform->GetModules();

            $itemSelected="";
            foreach ($mods as $curMod) {

                $sideMenuContent[] = array("id" => $curMod['id_sidebar'], "icon" => $curMod['icon'], "value" => $curMod['name'], "tooltip" => $curMod['tooltip'], "type"=>"section", "module" => $curMod['id_modulo'], "section"=>$curMod['id_modulo']);
            }

            //configurazione moduli
            $this->sTaskLog .= base64_encode(json_encode($sideMenuContent));
            #------------------------

            $this->sTaskLog .= "</content>";

            return true;
        }
    }
}
