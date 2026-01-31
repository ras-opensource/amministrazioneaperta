<?php
class AA_Platform
{
    //Istanza
    static private $oInstance = null;

    //utente
    protected $oUser = null;

    //flag di validità
    protected $bValid = false;
    public function IsValid()
    {
        return $this->bValid;
    }

    //public overlay
    static protected $sOverlay='<div id="AA_MainOverlay" class="AA_MainOverlay" style="display: block;">
        <div class="AA_MainOverlayContent">
            <img class="AA_Header_Logo" src="immagini/logo_ras.svg" alt="logo RAS" title="www.regione.sardegna.it">
            <h1><span>A</span>mministrazione <span>A</span>perta</h1>
            <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        </div>
    </div>';

    static protected $sManutention='<div id="AA_MainOverlay" class="AA_MainOverlay" style="display: block;">
        <div class="AA_MainOverlayContent">
            <img class="AA_Header_Logo" src="immagini/logo_ras.svg" alt="logo RAS" title="www.regione.sardegna.it">
            <h1><span>A</span>mministrazione <span>A</span>perta</h1>
            <div>
            <p style="color:#fff; font-size:32px; font-weight: bold;text-align:center">
            Sito in manutenzione.
            </p>
            <p style="color:#fff; font-size:24px;text-align:center">
            Il servizio verra ripristinato nel piu breve tempo possibile.
            </p>
            </div>
        </div>
    </div>';
    public static function GetManutention()
    {
        return static::$sManutention;
    }

    public static function GetOverlay()
    {
        return static::$sOverlay;
    }

    public static function SetOverlay($var="")
    {
        static::$sOverlay=$var;
    }
    //---------------------------

    //restituisce l'istanza unica
    static public function GetInstance($user = null)
    {
        if (self::$oInstance == null || !self::$oInstance->bValid) {
            self::$oInstance = new AA_Platform($user);

            //AA_Log::Log(__METHOD__." - istanzio l'istanza: ".print_r(self::$oInstance,true),100);
        }

        //AA_Log::Log(__METHOD__." - restituisco l'istanza: ".print_r(self::$oInstance,true),100);
        return self::$oInstance;
    }

    //Restituisce l'url del task manager
    public function GetModuleTaskManagerURL($id_module = "")
    {
        if (!$this->IsValid()) {
            return AA_Const::AA_PUBLIC_LIB_PATH . DIRECTORY_SEPARATOR . "system_ops.php";
        }

        $module = $this->GetModule($id_module);
        if ($module == null) {
            return AA_Const::AA_PUBLIC_LIB_PATH . DIRECTORY_SEPARATOR . "system_ops.php";
        } else {
            return AA_Const::AA_PUBLIC_MODULES_PATH . "/" . $module['id_sidebar'] . "_" . $module['id'] . "/taskmanager.php";
        }
    }

    //Restituisce l'url della cartella del modulo
    public function GetModulePathURL($id_module = "")
    {
        if (!$this->IsValid()) {
            return AA_Const::AA_WWW_ROOT;
        }

        $module = $this->GetModule($id_module);
        if ($module == null) {
            return AA_Const::AA_WWW_ROOT;
        } else {
            if(isset($module["path"]) && $module["path"] != "") return AA_Const::AA_PUBLIC_MODULES_PATH . "/" . $module['path'];
            return AA_Const::AA_PUBLIC_MODULES_PATH . "/" . $module['id_sidebar'] . "_" . $module['id'];
        }
    }

    //public services
    protected $aPublicServices=array();

    public static function RegisterPublicService($service="",$serviceFunc=null)
    {
        $platform=AA_Platform::GetInstance(AA_User::GetCurrentUser());
        if(!$platform->IsValid())
        {
            AA_Log::Log(__METHOD__." - Piattaforma non inizializzata o utente non valido: ",100);
            return false;
        }

        if(!empty($service) && is_callable($serviceFunc,true))
        {
            $platform->aPublicServices[$service]=$serviceFunc;
            AA_Log::Log(__METHOD__." - Servizio registrato correttamente: ".$service." - Funzione: ".print_r($serviceFunc,true),100);
            return true;
        }
        else
        {
            AA_Log::Log(__METHOD__." - Servizio non registrato: ".$service." - Funzione: ".print_r($serviceFunc,true),100);
            return false;
        }
    }
    public static function RunPublicService($service="")
    {
        $platform=AA_Platform::GetInstance(AA_User::GetCurrentUser());
        if(!$platform->IsValid())
        {
            AA_Log::Log(__METHOD__." - Piattaforma non inizializzata o utente non valido: ",100);
            return false;
        }

        if(!empty($platform->aPublicServices[$service]) && is_callable($platform->aPublicServices[$service])) return call_user_func($platform->aPublicServices[$service]);
        else
        {
            AA_Log::Log(__METHOD__." - Servizio non registrato o non funzione non definita: ".$service." - Funzione: ".print_r($platform->aPublicServices[$service],true),100);
            return false;
        }
    }

    public function IsPublicServiceRegistered($service="")
    {
        $platform=AA_Platform::GetInstance(AA_User::GetCurrentUser());
        if(!$platform->IsValid())
        {
            AA_Log::Log(__METHOD__." - Piattaforma non inizializzata o utente non valido: ",100);
            return false;
        }

        if(!empty($this->aPublicServices[$service]) && is_callable($this->aPublicServices[$service])) return true;
        return false;
    }
    protected function __construct($user = null)
    {
        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                //AA_Log::Log(__METHOD__." - Autenticazione utente - ".$user,100);
                $user = AA_User::UserAuth();
            }

            //AA_Log::Log(__METHOD__." - Utente autenticato - ".$user,100);
        } else {
            //AA_Log::Log(__METHOD__." - Autenticazione utente ",100);
            $user = AA_User::UserAuth();
        }

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return;
        }

        $this->oUser = $user;
        $this->bValid = true;
    }

    //Gestione moduli
    protected $aModules = null;
    protected function LoadModules($bDisableCache = false)
    {
        if (!$this->bValid) {
            return;
        }

        //Carica i moduli
        if (!isset($_SESSION['platform_modules_cache']) || isset($_REQUEST['disable_cache']) || $bDisableCache) {
            $db = new AA_Database();
            $query = "SELECT * from aa_platform_modules ORDER by ordine";
            if (!$db->Query($query)) {
                AA_Log::Log(__METHOD__ . " - errore: " . $db->GetErrorMessage(), 100);
                return;
            }

            if ($db->GetAffectedRows() > 0) {
                $userFlags = $this->oUser->GetFlags(true);

                foreach ($db->GetResultSet() as $curMod) 
                {
                    $flags = array();
                    if($curMod['flags'] !="")
                    {
                        $mod_flags = json_decode($curMod['flags'], true);
                        if (!is_array($mod_flags)) 
                        {
                            if (json_last_error() > 0) AA_Log::Log(__METHOD__ . " - id module: ".$curMod['id_modulo']." - module flags:" . print_r($mod_flags, true) . " - error: " . json_last_error(), 100);
                        } 
                        else 
                        {
                            $flags = array_keys($mod_flags);
                            //AA_Log::Log(__METHOD__." - module flags:".print_r($mod_flags,true),100);
                        }
                    }

                    $admins = explode(",", $curMod['admins']);
                    if (in_array($this->oUser->GetId(), $admins) || $this->oUser->IsSuperUser()) 
                    {
                        //Amministratori del modulo
                        $this->aModules[$curMod['id_modulo']] = $curMod;
                    } 
                    else 
                    {
                        //Utilizzatori del modulo
                        if ($curMod['enable'] == 1) 
                        {
                            if (sizeof($flags) == 0) 
                            {
                                //modulo pubblico
                                $this->aModules[$curMod['id_modulo']] = $curMod;
                            } 
                            else 
                            {
                                //Modulo a visibilità limitata
                                if (sizeof($userFlags) > 0) 
                                {
                                    foreach ($userFlags as $curFlag) 
                                    {
                                        if (in_array($curFlag, $flags)) $this->aModules[$curMod['id_modulo']] = $curMod;
                                    }
                                } 
                                else 
                                {
                                    AA_Log::Log(__METHOD__ . " - L'utente corrente (" . $this->oUser->GetUsername() . ") non ha i permessi per accedere al modulo: " . $curMod['id_modulo'] . " - userFlags: " . print_r($userFlags, true) . " - module flags:" . print_r($flags, true), 100);
                                }
                            }
                        }
                    }
                }
            }

            //AA_Log::Log(__METHOD__." - salvo sessione: ".print_r($this->aModules,true),100);
            $_SESSION['platform_modules_cache'] = serialize($this->aModules);
        } else {
            //AA_Log::Log(__METHOD__." - sessione: ".$_SESSION['platform_modules_cache'],100);
            $this->aModules = unserialize($_SESSION['platform_modules_cache']);
        }
    }

    //registra un modulo
    static public function RegisterModule($idMod = "", $class = "", $user = null)
    {
        //to do
    }

    //Verifica se un modulo è registrato
    static public function IsRegistered($id = "", $user = null)
    {
        $platform = AA_Platform::GetInstance($user);

        if (!$platform->bValid) return false;

        if ($platform->aModules == null) $platform->LoadModules();

        foreach ($platform->aModules as $curId => $class) {
            if ($curId == $id) return true;
        }

        return false;
    }

    //Restituisce il modulo
    public function GetModule($id = "", $user = null)
    {
        if (!$this->bValid) return null;

        if ($this->aModules == null) $this->LoadModules();

        foreach ($this->aModules as $curId => $curMod) {
            if ($curId == $id) return $curMod;
        }

        return null;
    }

    //Restituisce la lista dei moduli registrati
    public function GetModules()
    {
        if (!$this->bValid) return array();

        //$modules=array();

        //AA_Log::Log(__METHOD__." - ".print_r($this,true),100);

        if ($this->aModules == null) $this->LoadModules();

        return $this->aModules;
    }

    //Restituisce la lista dei flag dei moduli registrati per l'utente loggato
    public function GetModulesFlags()
    {
        if (!$this->bValid) return array();

        if ($this->aModules == null) $this->LoadModules();

        $flags=array();

        foreach($this->aModules as $curMod)
        {
            if($curMod['flags'] != "" && $curMod['flags'] !="{}")
            {
                $flags=array_merge($flags,json_decode($curMod['flags'], true));
            }
        }

        return $flags;
    }

    //Restituisce la lista dei flag dei moduli registrati
    static public function GetAllModulesFlags()
    {
        $flags=array();
        $db=new AA_Database();
        $query="SELECT flags FROM aa_platform_modules WHERE enable=1";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
            return $flags;
        }

        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            foreach($rs as $curFlag)
            {
                if($curFlag !="")
                {
                    $moduleFlag=json_decode($curFlag['flags'],true);
                    if(is_array($moduleFlag) && sizeof($moduleFlag)>0)
                    {
                        $flags=array_merge($moduleFlag,$flags);
                    }
                    else
                    {
                        AA_Log::Log(__METHOD__." - Errore nel parsing del flag: ".$curFlag['flags'],100);
                    }    
                }
            }
        }

        return $flags;
    }

    //Restituisce la lista dei flag legacy
    static public function GetLegacyFlags()
    {
        $result=array(
            "accessi"=>"RIA",
            "admin_accessi"=>"RIA(adm)",
            "processi"=>"Mappatura processi",
            "processi_admin"=>"Mappatura processi(adm)",
            "incarichi"=>"Gestione incarichi",
            "incarichi_titolari"=>"Gestione Incarichi(adm)"
        );

        return $result;
    }

    //Restituisce l'utente corrente
    public function GetCurrentUser()
    {
        if ($this->bValid) return $this->oUser;
        else return AA_User::GetCurrentUser();
    }

    //Autenticazione
    public function Auth($token = "", $user = "", $pwd = "")
    {
        $user = AA_User::UserAuth($token, $user, $pwd);
        if ($user->isCurrentUser() && !$user->IsGuest()) {
            $this->oUser = $user;
            return true;
        }

        return false;
    }
}
