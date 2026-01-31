<?php
class AA_User
{
    //stato utenti
    const AA_USER_STATUS_DELETED=-1;
    const AA_USER_STATUS_DISABLED=0;
    const AA_USER_STATUS_ENABLED=1;

    //built in groups ids
    const AA_USER_GROUP_GUESTS=0;
    const AA_USER_GROUP_SUPERUSER=1;
    const AA_USER_GROUP_ADMINS=2;
    const AA_USER_GROUP_OPERATORS=3;
    const AA_USER_GROUP_USERS=4;
    const AA_USER_GROUP_SERVEROPERATORS=5;

    //tabella utenti
    const AA_DB_TABLE="aa_users";

    //Nome
    protected $sNome = "Nessuno";

    //Cognome
    protected $sCognome = "Nessuno";

    //email
    protected $sEmail = "";

     //cf
     protected $sCf = "";


    //Nome utente
    protected $sUser = "Nessuno";

    //ID utente
    protected $nID = "0";

    //Struttura //old stuff
    protected $oStruct = null;

    //Flags
    protected $sFlags = "";

    //legacy Flags
    protected $sLegacyFlags = "";

    //Livello; //old stuff
    protected $nLivello = 3;

    //Flag disabilitato;
    protected $nDisabled = 1;

    //Flag di validità
    protected $bIsValid = false;

    //Flag utente corrente
    private $bCurrentUser = false;

    //legacy data
    protected $aLegacyData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0,"level"=>3,"flags"=>"");
    public function GetLegacyData()
    {
        return $this->aLegacyData;
    }

    //status
    protected $nStatus=0;
    public function GetStatus()
    {
        return $this->nStatus;
    }

    //gruppi utente (primari)
    protected $aGroups=array();
    public function GetGroups()
    {
        return $this->aGroups;
    }

    //Restituisce una stringa formattata con i flags
    public function GetLabelFlags()
    {
        $flags=$this->GetFlags(true);
        $platformFlags=AA_Platform::GetAllModulesFlags();
        $result="";
        foreach($flags as $curFlag)
        {
            if($curFlag=="accessi") $result.="<span class='AA_Label AA_Label_LightGreen'>RIA</span>&nbsp;";
            if($curFlag=="admin_accessi") $result.="<span class='AA_Label AA_Label_LightGreen'>RIA(adm)</span>&nbsp;";
            //($curFlag=="art22") $result.="<span class='AA_Label AA_Label_LightGreen'>SINES</span>&nbsp;";
            //if($curFlag=="art22_admin") $result.="<span class='AA_Label AA_Label_LightGreen'>SINES(adm)</span>&nbsp;";
            //if($curFlag=="art23") $result.="<span class='AA_Label AA_Label_LightGreen'>GESPA</span>&nbsp;";
            //if($curFlag=="patrimonio") $result.="<span class='AA_Label AA_Label_LightGreen'>GESPI</span>&nbsp;";
            //if($curFlag=="sier") $result.="<span class='AA_Label AA_Label_LightGreen'>SIER</span>&nbsp;";
            if($curFlag=="processi") $result.="<span class='AA_Label AA_Label_LightGreen'>SIMAP</span>&nbsp;";
            if($curFlag=="processi_admin") $result.="<span class='AA_Label AA_Label_LightGreen'>SIMAP(adm)</span>&nbsp;";
            if($curFlag=="incarichi") $result.="<span class='AA_Label AA_Label_LightGreen'>Incarichi</span>&nbsp;";
            if($curFlag=="incarichi_titolari") $result.="<span class='AA_Label AA_Label_LightGreen'>Incarichi(adm)</span>&nbsp;";
            if(isset($platformFlags[$curFlag])) $result.="<span class='AA_Label AA_Label_LightGreen'>".$platformFlags[$curFlag]."</span>&nbsp;";
        }
        
        return $result;
    }

    //ruolo
    public function GetRuolo($bNumeric=false)
    {

        $ruolo=static::AA_USER_GROUP_GUESTS;
        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if($this->nLivello==0) $ruolo=static::AA_USER_GROUP_ADMINS;
            if($this->nLivello==1) $ruolo=static::AA_USER_GROUP_OPERATORS;
        }

        if(array_search(static::AA_USER_GROUP_USERS,$this->aGroups) !== false) $ruolo=static::AA_USER_GROUP_USERS;
        if(array_search(static::AA_USER_GROUP_OPERATORS,$this->aGroups) !== false) $ruolo=static::AA_USER_GROUP_OPERATORS;
        if(array_search(static::AA_USER_GROUP_ADMINS,$this->aGroups) !==false) $ruolo=static::AA_USER_GROUP_ADMINS;
        if(array_search(static::AA_USER_GROUP_SERVEROPERATORS,$this->aGroups) !==false) $ruolo=static::AA_USER_GROUP_SERVEROPERATORS;
        if(array_search(static::AA_USER_GROUP_SUPERUSER,$this->aGroups) !==false) $ruolo=static::AA_USER_GROUP_SUPERUSER;
        
        if($bNumeric) return $ruolo;

        $ruoli=static::GetDefaultGroups();

        if($ruolo > 0) return $ruoli[$ruolo];
        else return "Ospite";
    }
    public function GetRole($bNumeric=false)
    {
        return $this->GetRuolo($bNumeric);
    }

    public function IsServerOperator()
    {
        if(array_search(static::AA_USER_GROUP_SERVEROPERATORS,$this->aGroups) !==false) return true;
        return false;
    }

    public function isAdministrator()
    {
        if(array_search(static::AA_USER_GROUP_ADMINS,$this->aGroups) !==false) return true;
        return false;
    }

    public function isOperator()
    {
        if(array_search(static::AA_USER_GROUP_OPERATORS,$this->aGroups) !==false) return true;
        return false;
    }

    static public function GetDefaultGroups()
    {
        return array(
            1=>"Super utente",
            2=>"Amministratore",
            3=>"Operatore",
            4=>"Utente",
            5=>"Operatore server"
        );
    }

    //gruppi utente compresi quelli secondari 
    protected $aAllGroups=array();
    protected $aSecondaryGroups=array();
    public function GetAllGroups()
    {
        if(sizeof($this->aGroups) > 0 && sizeof($this->aAllGroups) == 0)
        {
            $this->LoadAllGroups();
        }

        return $this->aAllGroups;
    }
    protected function LoadAllGroups()
    {
        if(sizeof($this->aGroups)==0) return array();

        if($this->IsServerOperator())
        {
            $this->aSecondaryGroups[]=AA_User::AA_USER_GROUP_ADMINS;
            $this->aSecondaryGroups[]=AA_User::AA_USER_GROUP_OPERATORS;
            $this->aSecondaryGroups[]=AA_User::AA_USER_GROUP_USERS;
        }

        foreach($this->aGroups as $curGroup)
        {
            $this->aSecondaryGroups=array_merge($this->aSecondaryGroups,AA_Group::GetDescendants($curGroup));
        }

        $this->aAllGroups=array_unique(array_merge($this->aGroups,$this->aSecondaryGroups));

        //AA_Log::Log(__METHOD__." - allGroups: ".print_r($this->aAllGroups,true)." - secondary: ".print_r($this->aSecondaryGroups,true)." - Primary: ".print_r($this->aGroups,true),100);
    }

    public function __construct()
    {
        //AA_Log::Log(get_class() . "__construct()");

        $this->oStruct = new AA_Struct();
    }

    //Login multiplo
    protected $nConcurrent=0;
    public function IsConcurrentEnabled()
    {
        if($this->nConcurrent>0) return true;

        return false;
    }

    //Verifica se l'utente è valido
    public function IsValid()
    {
        return $this->bIsValid;
    }

    //restituisce l'immagine associata all'utente (percorso pubblico)
    public function GetProfileImagePublicPath()
    {
        if(AA_Const::AA_ROOT_STORAGE_PATH==null || AA_Const::AA_ROOT_STORAGE_PATH =="")
        {
            $imgFile=AA_Const::AA_APP_FILESYSTEM_FOLDER."/immagini/profili/".$this->GetImage();
            if(is_file($imgFile))
            {
                return AA_Const::AA_WWW_ROOT."/immagini/profili/".$this->GetImage();
            }
            else
            {
                return AA_Const::AA_WWW_ROOT."/immagini/profili/generic.png";
            }    
        }
        else
        {
            $storage=AA_Storage::GetInstance();
            if($storage->IsValid())
            {
                $file=$storage->GetFileByHash($this->GetImage());
                if($file->IsValid())
                {
                    return AA_Const::AA_WWW_ROOT."/storage.php?object=".$this->GetImage();
                }
            }
            return AA_Const::AA_WWW_ROOT."/immagini/profili/generic.png";
        }
    }
    
    //restituisce l'immagine associata all'utente (percorso locale)
    public function GetProfileImageLocalPath()
    {
        if(AA_Const::AA_ROOT_STORAGE_PATH==null || AA_Const::AA_ROOT_STORAGE_PATH =="")
        {
            $imgfile=AA_Const::AA_APP_FILESYSTEM_FOLDER."/immagini/profili/".$this->GetImage();
            if(is_file($imgfile)) return $imgfile;
            else return AA_Const::AA_APP_FILESYSTEM_FOLDER."/immagini/profili/generic.png";    
        }
        else
        {
            $storage=AA_Storage::GetInstance();
            if($storage->IsValid())
            {
                $file=$storage->GetFileByHash($this->GetImage());
                if($file->IsValid())
                {
                    return AA_Const::AA_ROOT_STORAGE_PATH.DIRECTORY_SEPARATOR.$file->GetFilePath();
                }
            }
            return AA_Const::AA_APP_FILESYSTEM_FOLDER."/immagini/profili/generic.png";
        }
    }

    //Verifica se l'utente è disabilitato
    public function IsDisabled()
    {
        return $this->nDisabled;
    }

    //Verifica se è l'utente guest
    public function IsGuest()
    {
        return !$this->bIsValid;
    }

    //numero di telefono
    protected $sPhone="";
    public function GetPhone()
    {
        return $this->sPhone;
    }

    //last login time
    protected $sLastLogin="";
    public function GetLastLogin()
    {
        return $this->sLastLogin;
    }

    //ultima volta in cui è stato notificato il reset della password
    protected $sLastNotify="";
    public function GetLastNotify()
    {
        return $this->sLastNotify;
    }

    //immagine del profilo
    protected $sImage="";
    public function GetImage()
    {
        return $this->sImage;
    } 

    public function IsAdmin()
    {
        //if(AA_Const::AA_ENABLE_LEGACY_DATA)
        //{
        //    return $this->IsSuperUser();
        //}
        
        if(array_search(AA_USER::AA_USER_GROUP_ADMINS,$this->GetGroups())) return true;

        return false;
    }

    //Verifica se è l'utente super user
    public function IsSuperUser()
    {
        if ($this->nID == 1) return true;
        
        //if(AA_Const::AA_ENABLE_LEGACY_DATA && $this->HasFlag("SU")) return true;

        //gruppo super user
        if(array_search(AA_USER::AA_USER_GROUP_SUPERUSER,$this->GetGroups()) !==false) return true;

        return false;
    }

    //Restituisce la struttura
    public function GetStruct()
    {
        return $this->oStruct;
    }

    //Restituisce il livello
    public function GetLevel()
    {
        return $this->nLivello;
    }

    //Restituisce l'identificativo
    public function GetID()
    {
        return $this->nID;
    }

    //Restituisce il flag utente corrente
    public function isCurrentUser()
    {
        return $this->bCurrentUser;
    }

    //Popola i dati dell'utente
    static public function LoadUser($id_user)
    {
        //AA_Log::Log(get_class() . "->LoadUser($id_user)");
        $user = new AA_User();
        $user->bCurrentUser = false;

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT ".static::AA_DB_TABLE.".* from ".static::AA_DB_TABLE." where id = '" . addslashes($id_user) . "'");
        if ($db->GetAffectedRows() > 0) 
        {
            $row = $db->GetResultSet();
            $info=json_decode($row[0]['info'],true);
            if($info==null)
            {
                AA_Log::Log(__METHOD__." - errore nel parsing dei dati info: ".$row[0]['info'],100);
            }

            $user->nID = $row[0]['id'];
            if(is_array($info))
            {
                $user->sNome = $info['nome'];
                $user->sCognome = $info['cognome'];    
                $user->sImage = $info['image'];
                $user->sCf = $info['cf'];
                $user->sPhone = $info['phone'];    
            }

            $user->sUser = $row[0]['user'];
            $user->sEmail = $row[0]['email'];
            $user->nStatus= $row[0]['status'];
            if($user->nStatus==AA_User::AA_USER_STATUS_DISABLED) $user->nDisabled=1;
            else $user->nDisabled=0;

            $user->aGroups=explode(",",$row[0]['groups']);
            
            $user->LoadAllGroups();
            
            $user->sLastLogin = $row[0]['lastlogin'];
            $user->sLastNotify = $row[0]['lastnotify'];
            $user->sFlags = $row[0]['flags'];
            $user->bIsValid = true;

            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                if($user->nStatus==AA_User::AA_USER_STATUS_DISABLED) $user->nDisabled=1;
                else $user->nDisabled=0;

                $legacy_data=json_decode($row[0]['legacy_data'],true);
                if(is_array($legacy_data))
                {
                    $user->aLegacyData=$legacy_data;
                    $user->oStruct = AA_Struct::GetStruct($legacy_data['id_assessorato'], $legacy_data['id_direzione'], $legacy_data['id_servizio']);
                    $user->nLivello=$legacy_data['level'];
                    if(is_array($legacy_data['flags']))$user->sLegacyFlags=implode("|",$legacy_data['flags']);
                    else $user->sLegacyFlags=$legacy_data['flags'];
                }
                else
                {
                    AA_Log::Log(__METHOD__." - errore nel parsing dei dati legacy: ".$row[0]['legacy_data'],100);
                }
            }

            //Concurrent flag
            if(strpos($user->sFlags,"concurrent")!==false || strpos($user->sLegacyFlags,"concurrent")!==false)
            {
                //AA_Log::Log(__METHOD__." - accesso concorrente abilitato: ".$row[0]['user'],100);
                $user->nConcurrent=1;
            }

            return $user;
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            return static::LegacyLoadUser($id_user);
        }

        return $user;
    }

    //Popola i dati dell'utente (legacy)
    static public function LegacyLoadUser($id_user)
    {
        //AA_Log::Log(get_class() . "->LoadUser($id_user)");

        $user = new AA_User();
        $user->bCurrentUser = false;

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT utenti.* from utenti where id = '" . addslashes($id_user) . "'");
        if ($db->GetAffectedRows() > 0) {
            $row = $db->GetResultSet();

            $user->nID = $row[0]['id'];
            $user->sNome = $row[0]['nome'];
            $user->sCognome = $row[0]['cognome'];
            $user->sUser = $row[0]['user'];
            $user->sEmail = $row[0]['email'];
            $user->nLivello = $row[0]['livello'];
            $user->nDisabled = $row[0]['disable'];
            $user->sImage = $row[0]['image'];
            $user->sPhone = $row[0]['phone'];
            $user->sLastLogin = $row[0]['lastlogin'];
            $user->sLegacyFlags = $row[0]['flags'];
            $user->nConcurrent = $row[0]['concurrent'];
            $user->bIsValid = true;

            //Popola i dati della struttura
            $user->oStruct = AA_Struct::GetStruct($row[0]['id_assessorato'], $row[0]['id_direzione'], $row[0]['id_servizio']);
        }

        return $user;
    }

    //Reset password email params
    protected static $aResetPasswordEmailParams=array(
        "oggetto"=>'Amministrazione Aperta - Credenziali accesso.',
        "incipit"=>"<p>Buongiorno,<br>Di seguito le credenziali per l'accesso alla piattaforma applicativa \"Amministrazione Aperta\":<br>url d'accesso: https://#www#",
        "bShowStruct"=>true,
        "sendToUs"=>true,
        "post"=>'<p>Per ragioni di sicurezza e\' preferibile effettuare l\'accesso in piattaforma nella modalita\' email/otp.</p>E\' possibile cambiare la password accedendo al proprio profilo utente, dopo aver effettuato il login sulla piattaforma.<br>E&apos; anche possibile effettuare il login sulla piattaforma indicando l\'indirizzo email in vece del nome utente.<br>Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>',
        "firma"=>'<div>--
        <div><strong>Amministrazione Aperta</strong></div>
        <div>Presidentzia</div>
        <div>Presidenza</div>
        <div>V.le Trento, 69 - 09123 Cagliari</div>
        <img src="https://#www#/immagini/logo.jpg" data-mce-src="https://#www#/immagini/logo.jpg" moz-do-not-send="true" width="205" height="60"></div>'
    );

    protected static $aSendCredentialsEmailParams=array(
        "oggetto"=>'Amministrazione Aperta - Credenziali accesso.',
        "incipit"=>"<p>Buongiorno,<br>Di seguito le credenziali per l'accesso alla piattaforma applicativa \"Amministrazione Aperta\":<br>url d'accesso: https://#www#",
        "bShowStruct"=>true,
        "sendToUs"=>true,
        "post"=>'<p>Per ragioni di sicurezza e\' preferibile effettuare l\'accesso in piattaforma nella modalita\' email/otp.</p>Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>',
        "firma"=>'<div>--
        <div><strong>Amministrazione Aperta</strong></div>
        <div>Presidentzia</div>
        <div>Presidenza</div>
        <div>V.le Trento, 69 - 09123 Cagliari</div>
        <img src="https://#www#/immagini/logo.jpg" data-mce-src="https://#www#/immagini/logo.jpg" moz-do-not-send="true" width="205" height="60"></div>'
    );


    //send OTP password email params
    protected static $aOTPAuthEmailParams=array(
        "oggetto"=>'Amministrazione Aperta - OTP accesso piattaforma.',
        "incipit"=>"<p>Gentile collega,<br>è pervenuta una richiesta di accesso alla piattaforma applicativa \"<b><i>A</i></b>mministrazione <b><i>A</i></b>perta\" con il tuo indirizzo email.<br>Di seguito il codice temporaneo per l'accesso; hai a disposizione 5 minuti per utilizzarlo, trascorsi i quali dovrai ripetere la procedura.",
        "post"=>'Qualora non dovessi essere tu l\'autore della richiesta ti invitiamo a segnalarci l\'anomalia inviando una mail alla casella:: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p><p>Cordiali Saluti.</p>',
        "firma"=>'<div>--
        <div><strong><i>A</i>mministrazione <i>A</i>perta</strong></div>
        <div>Presidenza</div>
        <div>Direzione generale della Presidenza</div>
        <div>Servizio supporti direzionali</div>
        <div>V.le Trento, 69 - 09123 Cagliari</div>
        <div>url d\'accesso: https://#www#</div>
        <div>email: amministrazioneaperta@regione.sardegna.it</div>
        <img src="https://#www#/immagini/logo.jpg" data-mce-src="https://#www#/immagini/logo.jpg" moz-do-not-send="true" width="205" height="60"></div>'
    );

    //send OTP change password email params
    protected static $aOTPChangePwdEmailParams=array(
        "oggetto"=>'Amministrazione Aperta - OTP verifica utente.',
        "incipit"=>"<p>Gentile collega,<br>è pervenuta una richiesta di reimpostazione della password d'accesso alla piattaforma applicativa \"<b><i>A</i></b>mministrazione <b><i>A</i></b>perta\" con il tuo indirizzo email.<br>Di seguito il codice temporaneo di verifica; hai a disposizione 5 minuti per utilizzarlo, trascorsi i quali dovrai ripetere la procedura.",
        "post"=>'Qualora non dovessi essere tu l\'autore della richiesta ti invitiamo a segnalarci l\'anomalia inviando una mail alla casella:: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p><p>Cordiali Saluti.</p>',
        "firma"=>'<div>--
        <div><strong><i>A</i>mministrazione <i>A</i>perta</strong></div>
        <div>Presidenza</div>
        <div>Direzione generale della Presidenza</div>
        <div>Servizio supporti direzionali</div>
        <div>V.le Trento, 69 - 09123 Cagliari</div>
        <div>url d\'accesso: https://#www#</div>
        <div>email: amministrazioneaperta@regione.sardegna.it</div>
        <img src="https://#www#/immagini/logo.jpg" data-mce-src="https://#www#/immagini/logo.jpg" moz-do-not-send="true" width="205" height="60"></div>'
    );

    public static function SetResetPwdEmailParams($params=array())
    {
        foreach($params as $key=>$val)
        {
            if(isset(static::$aResetPasswordEmailParams[$key])) static::$aResetPasswordEmailParams[$key]=$val;
        }
    }

    public static function SetSendCredentialsEmailParams($params=array())
    {
        foreach($params as $key=>$val)
        {
            if(isset(static::$aSendCredentialsEmailParams[$key])) static::$aSendCredentialsEmailParams[$key]=$val;
        }
    }

    public static function GetResetPwdEmailParams()
    {
        $params=static::$aResetPasswordEmailParams;
        foreach($params as $key=>$param)
        {
            $params[$key]=str_replace("#www#",AA_Const::AA_DOMAIN_NAME.AA_Const::AA_WWW_ROOT,$param);
        }

        return $params;
    }

    //Popola i dati dell'utente a partire dal nome utente
    static public function LoadUserFromUserName($userName)
    {
        //AA_Log::Log(get_class() . "LoadUserFromUserName($userName)");

        $user = new AA_User();
        $user->bCurrentUser = false;

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT ".static::AA_DB_TABLE.".id from ".static::AA_DB_TABLE." where user = '" . $userName . "' and status >= 0 LIMIT 1");
        if($db->GetAffectedRows() > 0)
        {
            $row = $db->GetResultSet();
             return static::LoadUser($row[0]['id']);
        }
        
        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
             return static::LegacyLoadUserFromUserName($userName);
        }

        return $user;
    }

    //Popola i dati dell'utente a partire dal nome utente (legacy)
    static public function LegacyLoadUserFromUserName($userName)
    {
        //AA_Log::Log(get_class() . "LoadUserFromUserName($userName)");

        $user = new AA_User();
        $user->bCurrentUser = false;

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT utenti.* from utenti where user = '" . $userName . "' and eliminato='0'");
        if($db->GetAffectedRows() > 0)
        {
            $row = $db->GetResultSet();

            $user->nID = $row[0]['id'];
            $user->sNome = $row[0]['nome'];
            $user->sCognome = $row[0]['cognome'];
            $user->sUser = $row[0]['user'];
            $user->sEmail = $row[0]['email'];
            $user->nLivello = $row[0]['livello'];
            $user->nDisabled = $row[0]['disable'];
            $user->sImage = $row[0]['image'];
            $user->sPhone = $row[0]['phone'];
            $user->sLegacyFlags = $row[0]['flags'];
            $user->sLastLogin = $row[0]['lastlogin'];
            $user->nConcurrent = $row[0]['concurrent'];
            $user->bIsValid = true;

            //Popola i dati della struttura
            $user->oStruct = AA_Struct::GetStruct($row[0]['id_assessorato'], $row[0]['id_direzione'], $row[0]['id_servizio']);
        }
        
        return $user;
    }

    //Restituisce un array di oggetti AA_User
    static public function LoadUsersFromEmail($email,$bLegacyUsers=true)
    {
        $users = array();

        if(AA_Const::AA_ENABLE_LEGACY_DATA && $bLegacyUsers)
        {
            $users = static::LegacyLoadUsersFromEmail($email);
        }

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT ".static::AA_DB_TABLE.".id from ".static::AA_DB_TABLE." where email = '" . $email . "' and status > 0 ORDER by lastlogin desc");
        if($db->GetAffectedRows() > 0)
        {
            $rs = $db->GetResultSet();
            foreach($rs as $curRow)
            {
                $user = static::LoadUser($curRow['id']);    
                $users[$curRow['id']] = $user;    
            }
        }

        return $users;
    }

    static public function LoadLastLoggedUsers($bLegacyUsers=true)
    {
        $users = array();

        if(AA_Const::AA_ENABLE_LEGACY_DATA && $bLegacyUsers)
        {
            $users = static::LegacyLoadLastLoggedUsers();
        }

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT max(".static::AA_DB_TABLE.".id) from ".static::AA_DB_TABLE." where lastlogin != '' and status > 0 and email != '' GROUP BY email ORDER by lastlogin desc");
        if($db->GetAffectedRows() > 0)
        {
            $rs = $db->GetResultSet();
            foreach($rs as $curRow)
            {
                $user = static::LoadUser($curRow['id']);    
                $users[$curRow['id']] = $user;    
            }
        }

        return $users;
    }

    //Restituisce la lista dei profili per l'utente corrente
    public function GetProfiles()
    {
        if(!$this->IsValid()) return array();

        return static::LoadUsersFromEmail($this->GetEmail(),false);
    }

    //Popola i dati dell'utente a partire dal nome utente
    //Restituisce un array di oggetti AA_User
    static public function LegacyLoadUsersFromEmail($email)
    {
        AA_Log::Log(get_class() . "->LoadUserFromEmail($email)");

        $users = array();

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT utenti.* from utenti where email = '" . addslashes($email) . "' and eliminato='0' and disable='0'");
        if($db->GetAffectedRows() > 0)
        {
            $rs = $db->GetResultSet();
            foreach($rs as $curRow)
            {
                $user = new AA_User();

                $user->nID = $curRow['id'];
                $user->sNome = $curRow['nome'];
                $user->sCognome = $curRow['cognome'];
                $user->sUser = $curRow['user'];
                $user->sEmail = $curRow['email'];
                $user->nLivello = $curRow['livello'];
                $user->sLegacyFlags = $curRow['flags'];
                $user->sImage = $curRow['image'];
                $user->sPhone = $curRow['phone'];
                $user->nDisabled = $curRow['disable'];
                $user->sLastLogin = $curRow['lastlogin'];
                $user->nConcurrent = $curRow['concurrent'];
                $user->bCurrentUser = false;
                $user->bIsValid = true;
    
                //Popola i dati della struttura
                $user->oStruct = AA_Struct::GetStruct($curRow['id_assessorato'], $curRow['id_direzione'], $curRow['id_servizio']);
    
                $users[$curRow['id']] = $user;    
            }
        }

        return $users;
    }

    static public function LegacyLoadLastLoggedUsers()
    {
        //AA_Log::Log(get_class() . "->LoadUserFromEmail($email)");

        $users = array();

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT utenti.* from utenti where lastlogin != '' and email !='' and eliminato='0' and disable='0'");
        if($db->GetAffectedRows() > 0)
        {
            $rs = $db->GetResultSet();
            foreach($rs as $curRow)
            {
                $user = new AA_User();

                $user->nID = $curRow['id'];
                $user->sNome = $curRow['nome'];
                $user->sCognome = $curRow['cognome'];
                $user->sUser = $curRow['user'];
                $user->sEmail = $curRow['email'];
                $user->nLivello = $curRow['livello'];
                $user->sLegacyFlags = $curRow['flags'];
                $user->sImage = $curRow['image'];
                $user->sPhone = $curRow['phone'];
                $user->nDisabled = $curRow['disable'];
                $user->sLastLogin = $curRow['lastlogin'];
                $user->nConcurrent = $curRow['concurrent'];
                $user->bCurrentUser = false;
                $user->bIsValid = true;
    
                //Popola i dati della struttura
                $user->oStruct = AA_Struct::GetStruct($curRow['id_assessorato'], $curRow['id_direzione'], $curRow['id_servizio']);
    
                $users[$curRow['id']] = $user;    
            }
        }

        return $users;
    }

    //temporary auth (autenticazione valida solamente per questa esecuzione dello script)
    static public function TemporaryUserAuth($sUserName = "", $sUserPwd = "")
    {
        return static::UserAuth("",$sUserName,$sUserPwd,false,true);
    }

    //Autenticazione
    static public function UserAuth($sToken = "", $sUserName = "", $sUserPwd = "", $remember_me=false,$bTemporary=false)
    {
        //AA_Log::Log(get_class()."->UserAuth($sToken,$sUserName, $sUserPwd)");

        $db = new AA_AccountsDatabase();
        $user = AA_User::Guest();

        if ($sUserName != "" && $sUserPwd != "") 
        {
            //AA_Log::Log(__METHOD__."($sToken,$sUserName, $sUserPwd)",100);
            $rs=null;

            if (filter_var($sUserName, FILTER_VALIDATE_EMAIL)) 
            {
                //Login tramite email
                //AA_Log::Log(__METHOD__." - autenticazione in base alla mail.");
                $query_utenti = "SELECT ".static::AA_DB_TABLE.".* FROM ".static::AA_DB_TABLE." WHERE ".static::AA_DB_TABLE.".email = '".addslashes(trim($sUserName))."' AND status>=0";

                if($db->Query($query_utenti))
                {
                    if($db->GetAffectedRows()>0)
                    {
                        $result = $db->GetResultSet();
                        foreach($result as $curRow)
                        {
                            if(AA_Utils::password_verify($sUserPwd,$curRow['passwd']) || AA_Utils::password_verify(md5($sUserPwd),$curRow['passwd']))
                            {
                                $rs=$curRow;
                                break;
                            }
                        }
                        if(!is_array($rs))
                        {
                            AA_Log::Log(__METHOD__." - Credenziali errate.", 100);
                            return AA_User::Guest();    
                        }
                    }
                    else
                    {
                        AA_Log::Log(__METHOD__." - Non sono stati trovati utenti associati alla email indicata.", 100);
                    }
                } 
                else 
                {
                    AA_Log::Log(__METHOD__ . " - errore nell'accesso al db: " . $db->GetErrorMessage(), 100);
                    AA_Log::Log(__METHOD__ . " - errore di sistema.", 100);
                    return AA_User::Guest();
                }    
            } 
            else
            {
                //Login ordinario tramite username
                $query_utenti = sprintf("SELECT ".static::AA_DB_TABLE.".* FROM ".static::AA_DB_TABLE." WHERE ".static::AA_DB_TABLE.".user = '%s' AND status >= 0", addslashes(trim($sUserName)));
                if($db->Query($query_utenti))
                {
                    if($db->GetAffectedRows()>0)
                    {
                        $result = $db->GetResultSet();
                        if(AA_Utils::password_verify($sUserPwd,$result[0]['passwd']) || AA_Utils::password_verify(md5($sUserPwd),$result[0]['passwd']))
                        {
                            $rs=$result[0];
                        }
                        else
                        {
                            AA_Log::Log(__METHOD__." - Credenziali errate.", 100);
                            return AA_User::Guest();    
                        }
                    }
                    else
                    {
                        AA_Log::Log(__METHOD__." - Non sono stati trovati utenti con l'username indicato.", 100);
                    }
                } 
                else 
                {
                    AA_Log::Log(__METHOD__ . " - errore nell'accesso al db: " . $db->GetErrorMessage(), 100);
                    AA_Log::Log(__METHOD__ . " - errore di sistema.", 100);
                    return AA_User::Guest();
                }    
            }

            if (is_array($rs)) 
            {
                if ($rs['status'] == AA_USER::AA_USER_STATUS_DISABLED) {
                    AA_Log::Log(__METHOD__." - L'utente è disattivato (id: " . $rs["id"] . ").", 100);
                    return AA_User::Guest();
                }

                if ($rs['status'] == AA_User::AA_USER_STATUS_DELETED) {
                    AA_Log::Log(__METHOD__." - L'utente è stato disattivato permanentemente (id: " . $rs["id"] . ").", 100);
                    return AA_User::Guest();
                }

                if ($rs['status'] == AA_User::AA_USER_STATUS_ENABLED) 
                {
                    $user = AA_User::LoadUser($rs['id']);
                    $user->bCurrentUser = true;

                    if($bTemporary)
                    {
                        static::$oCurrentUser=$user;
                        return $user;
                    }

                    if(AA_Const::AA_ENABLE_LEGACY_DATA)
                    {
                        //Old stuff compatibility
                        $_SESSION['user'] = $user->GetUsername();
                        $_SESSION['nome'] = $user->GetNome();
                        $_SESSION['cognome'] = $user->GetCognome();
                        $_SESSION['email'] = $user->GetEmail();
                        $_SESSION['user_home'] = "reserved/index.php";
                        $_SESSION['id_user'] = $user->GetId();
                        $_SESSION['id_utente'] = $user->GetId();

                        $struct=$user->GetStruct();

                        $_SESSION['id_assessorato'] = $struct->GetAssessorato(true);
                        $_SESSION['tipo_struct'] = $struct->GetTipo();
                        $_SESSION['id_direzione'] = $struct->GetDirezione(true);
                        $_SESSION['id_servizio'] = $struct->GetServizio(true);
                        $_SESSION['id_settore'] = 0;
                        $_SESSION['livello'] = $user->GetLevel();
                        $_SESSION['level'] = $user->GetLevel();
                        $_SESSION['assessorato'] = $struct->GetAssessorato();
                        $_SESSION['direzione'] = $struct->GetDirezione();
                        $_SESSION['servizio'] = $struct->GetServizio();
                        $_SESSION['settore'] = "";
                        $_SESSION['user_flags'] = $user->GetFlags();
                        $_SESSION['flags'] = $user->GetFlags();
                        
                        AA_Log::LogAction($user->GetId(), 0, "Log In"); //old stuff
                    }

                    $concurrent=false;
                    if($user->IsConcurrentEnabled()) $concurrent=true;
                    
                    //imposta l'utente corrente
                    static::$oCurrentUser=$user;

                    if(!$bTemporary)
                    {
                        $_SESSION['token'] = AA_User::GenerateToken($user->GetId(),$remember_me,$concurrent);

                        if($remember_me)
                        {
                            //token di autenticazione valido per 30 giorni, utilizzabile solo in https.
                            setcookie("AA_AUTH_TOKEN",$_SESSION['token'],time()+(86400 * 30), "/",AA_Const::AA_DOMAIN_NAME,true, true);
                        }
    
                        //update last login time
                        $db->Query("UPDATE ".static::AA_DB_TABLE." set lastlogin = '".date("Y-m-d")."' WHERE id='".$user->GetId()."' LIMIT 1");
                    }

                    return $user;
                }

                AA_Log::Log(__METHOD__." - Errore nel caricamento dei dati utente.", 100);
                return AA_User::Guest();
            }

            if(AA_Const::AA_ENABLE_LEGACY_DATA && AA_Const::AA_MIGRATE_LEGACY_USERS)
            {
                AA_Log::Log(__METHOD__." - legacy login", 100);
                $user=AA_User::legacyUserAuth($sToken,$sUserName,md5($sUserPwd),$remember_me);

                if($user->IsValid())
                {
                    AA_Log::Log(__METHOD__." - Migrazione utente legacy: ".$user->GetNome()." ".$user->GetCognome()." (".$user->GetId().")",100);
                    static::MigrateLegacyUser($user, $sUserPwd);

                    return $user;
                }
            }

            return $user;
        }

        if($bTemporary) return AA_User::Guest();

        if ($sToken == null || $sToken == "" || $_COOKIE["AA_AUTH_TOKEN"] !="") 
        {
            if(static::$oCurrentUser instanceof AA_User) return static::$oCurrentUser;

            if(isset($_SESSION['token'])) $sToken = $_SESSION['token'];
            if($sToken == "" && isset($_COOKIE["AA_AUTH_TOKEN"]))
            {   
                $sToken=$_COOKIE["AA_AUTH_TOKEN"];
                AA_Log::Log(__METHOD__." - auth token login.",100);
            }
        }

        if ($sToken != null) {
            //AA_Log::Log(__METHOD__." - autenticazione in base al token.");

            $token_timeout_m = 30;
            $query_token = sprintf("SELECT * FROM tokens where (TIMESTAMPDIFF(MINUTE,data_rilascio, NOW()) < '%s' OR remember_me='1') and token ='%s' order by data_rilascio DESC LIMIT 1", $token_timeout_m, $sToken);

            if ($db->Query($query_token)) {
                $rs = $db->GetResultSet();
            } else {
                AA_Log::Log(__METHOD__ . " - errore nell'accesso al db: " . $db->GetErrorMessage(), 100);
                return AA_User::Guest();
            }

            if ($db->GetAffectedRows() > 0) 
            {
                if (strcmp($rs[0]['token'], $sToken) == 0) {
                    //AA_Log::Log(__METHOD__." - Authenticate token ($sToken) - success", 50);

                    $user = AA_User::LoadUser($rs[0]['id_utente']);
                    if ($user->IsDisabled()) {
                        AA_Log::Log(__METHOD__." - L'utente è disattivato.", 100);
                        return AA_User::Guest();
                    }

                    if(AA_Const::AA_ENABLE_LEGACY_DATA)
                    {
                        //Old stuff compatibility
                        $_SESSION['user'] = $user->GetUsername();
                        $_SESSION['nome'] = $user->GetNome();
                        $_SESSION['cognome'] = $user->GetCognome();
                        $_SESSION['email'] = $user->GetEmail();
                        $_SESSION['user_home'] = "admin/index.php";
                        $_SESSION['id_user'] = $user->GetId();
                        $_SESSION['id_utente'] = $user->GetId();
                        $struct=$user->GetStruct();
                        $_SESSION['id_assessorato'] = $struct->GetAssessorato(true);
                        $_SESSION['tipo_struct'] = $struct->GetTipo();
                        $_SESSION['id_direzione'] = $struct->GetDirezione(true);
                        $_SESSION['id_servizio'] = $struct->GetServizio(true);
                        $_SESSION['id_settore'] = 0;
                        $_SESSION['livello'] = $user->GetLevel();
                        $_SESSION['level'] = $user->GetLevel();
                        $_SESSION['assessorato'] = $struct->GetAssessorato();
                        $_SESSION['direzione'] = $struct->GetDirezione();
                        $_SESSION['servizio'] = $struct->GetServizio();
                        $_SESSION['settore'] = "";
                        $_SESSION['user_flags'] = $user->GetFlags();
                        $_SESSION['flags'] = $user->GetFlags();

                        //update last login time
                        $db->Query("UPDATE utenti set lastlogin = NOW() WHERE id='".$user->GetId()."' LIMIT 1");
                    }

                    //Rinfresco della durata del token
                    AA_User::RefreshToken($sToken);
                    $_SESSION['token'] = $sToken;

                    $user->bCurrentUser = true;

                    //update last login time
                    //AA_Log::Log(__METHOD__." - update last login time for user ".$user->GetId(),100);
                    if(!$db->Query("UPDATE ".static::AA_DB_TABLE." set lastlogin = NOW() WHERE id='".$user->GetId()."' LIMIT 1"))
                    {
                        AA_Log::Log(__METHOD__." - errore: " . $db->GetErrorMessage(), 100);
                    }
                    
                    return $user;
                }
            }

            //Old stuff
            //if (isset($log)) AA_Log::LogAction(), 0, "Authenticate token ($sToken) - failed");
            //----------

            AA_Log::Log(get_class() . "->UserAuth($sToken) - Authenticate token ($sToken) - failed", 100);
            return AA_User::Guest();
        }

        AA_Log::Log(get_class() . "->UserAuth($sToken,$sUserName) - Autenticazione fallita.", 100);
        return AA_User::Guest();
    }

    public function GetSSOAuthToken()
    {
        if(!$this->IsValid())
        {
            return "";
        }

        if(!$this->isCurrentUser())
        {
            return "";
        }

        if($_SESSION['token'])
        {
            return crypt($_SESSION['token'],uniqid());
        }
    }

    static public function VerifySSOAuthToken($token='',$bRegisterToken=false)
    {
        if($token=='') return false;

        $db=new AA_AccountsDatabase();

        $query="SELECT token FROM tokens ORDER by data_rilascio DESC";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__," - errore: ".$db->GetErrorMessage(),100);
        }

        $rs=$db->GetResultSet();
        foreach($rs as $curToken)
        {
            if (crypt($curToken['token'], $token)==$token)
            {
                $savedToken = $_SESSION['token'];
                $curUser=AA_User::UserAuth($curToken['token']);

                if($curUser->IsValid()) 
                {
                    if(!$bRegisterToken) AA_User::UserAuth($savedToken);

                    return true;
                }
                else
                {
                    if(!$bRegisterToken) AA_User::UserAuth($savedToken);

                    AA_Log::Log(__METHOD__," - errore: sso token scaduto o non valido.",100);
                    return false;
                }
            }
        }

        AA_Log::Log(__METHOD__," - errore: sso token non valido.",100);
        return false;
    }

    //Autenticazione legacy (md5 password)
    static public function legacyUserAuth($sToken = "", $sUserName = "", $sUserPwd = "", $remember_me=false)
    {
        //AA_Log::Log(get_class()."->legacyUserAuth($sToken,$sUserName, $sUserPwd)",100);

        $db = new AA_AccountsDatabase();

        if ($sUserName != null && $sUserPwd != null) {
            AA_Log::Log(__METHOD__." - autenticazione in base al nome utente.");

            if (filter_var($sUserName, FILTER_VALIDATE_EMAIL)) {
                //Login tramite email
                AA_Log::Log(__METHOD__." - autenticazione in base alla mail.");
                $query_utenti = sprintf("SELECT utenti.*,assessorati.tipo, assessorati.descrizione as assessorato, direzioni.descrizione as direzione, servizi.descrizione as servizio FROM utenti left join assessorati on utenti.id_assessorato=assessorati.id left join direzioni on utenti.id_direzione=direzioni.id left join servizi on utenti.id_servizio=servizi.id WHERE utenti.email = '%s' AND passwd= '%s' ", addslashes($sUserName), addslashes($sUserPwd));
            } else {
                //Login ordinario tramite username
                $query_utenti = sprintf("SELECT utenti.*,assessorati.tipo, assessorati.descrizione as assessorato, direzioni.descrizione as direzione, servizi.descrizione as servizio FROM utenti left join assessorati on utenti.id_assessorato=assessorati.id left join direzioni on utenti.id_direzione=direzioni.id left join servizi on utenti.id_servizio=servizi.id WHERE user = '%s' AND passwd= '%s' ", addslashes($sUserName), addslashes($sUserPwd));
            }

            if ($db->Query($query_utenti)) {
                $result = $db->GetResult();
                $rs = $result->fetch(PDO::FETCH_ASSOC);
            } else {
                AA_Log::Log(__METHOD__ . " - errore nell'accesso al db: " . $db->GetErrorMessage(), 100);
                return AA_User::Guest();
            }

            if ($db->GetAffectedRows() > 0) {
                if ($rs['disable'] == '1') {
                    AA_Log::Log(__METHOD__." - L'utente è disattivato (id: " . $rs["id"] . ").", 100);
                }

                if ($rs['eliminato'] == '1') {
                    AA_Log::Log(__METHOD__." - L'utente è stato disattivato permanentemente (id: " . $rs["id"] . ").", 100);
                }

                if ($rs['disable'] == '0' && $rs['eliminato'] == '0') {
                    //Old stuff compatibility
                    $_SESSION['user'] = $rs['user'];
                    $_SESSION['nome'] = $rs['nome'];
                    $_SESSION['cognome'] = $rs['cognome'];
                    $_SESSION['email'] = $rs['email'];
                    $_SESSION['user_home'] = $rs['home'];
                    $_SESSION['id_user'] = $rs['id'];
                    $_SESSION['id_utente'] = $rs['id'];
                    $_SESSION['id_assessorato'] = $rs['id_assessorato'];
                    $_SESSION['tipo_struct'] = $rs['tipo'];
                    $_SESSION['id_direzione'] = $rs['id_direzione'];
                    $_SESSION['id_servizio'] = $rs['id_servizio'];
                    $_SESSION['id_settore'] = $rs['id_settore'];
                    $_SESSION['livello'] = $rs['livello'];
                    $_SESSION['level'] = $rs['livello'];
                    $_SESSION['assessorato'] = $rs['assessorato'];
                    $_SESSION['direzione'] = $rs['direzione'];
                    $_SESSION['servizio'] = $rs['servizio'];
                    $_SESSION['settore'] = $rs['settore'];
                    $_SESSION['user_flags'] = $rs['flags'];
                    $_SESSION['flags'] = $rs['flags'];

                    //AA_Log::LogAction($rs['id'], 0, "Log In"); //old stuff

                    //New stuff
                    AA_Log::Log(__METHOD__." - Autenticazione avvenuta con successo (credenziali corrette).", 50);
                    $concurrent=false;
                    if(isset($rs['concurrent']) && $rs['concurrent'] > 0) $concurrent=true;
                    $_SESSION['token'] = AA_User::GenerateToken($rs['id'],$remember_me,$concurrent);

                    if($remember_me)
                    {
                        //token di autenticazione valido per 30 giorni, utilizzabile solo in https.
                        setcookie("AA_AUTH_TOKEN",$_SESSION['token'],time()+(86400 * 30), "/",AA_Const::AA_DOMAIN_NAME,true, true);
                    }

                    $user = AA_User::LoadUser($rs['id']);
                    $user->bCurrentUser = true;

                    //update last login time
                    $db->Query("UPDATE utenti set lastlogin = NOW() WHERE id='".$rs['id']."' LIMIT 1");

                    return $user;
                }

                return AA_User::Guest();
            }

            AA_Log::Log(__METHOD__." - Autenticazione fallita (credenziali errate).", 100);
            return AA_User::Guest();
        }

        if ($sToken == null || $sToken == "") 
        {
            if(isset($_SESSION['token'])) $sToken = $_SESSION['token'];
            if($sToken == "" && isset($_COOKIE["AA_AUTH_TOKEN"]))
            {   
                $sToken=$_COOKIE["AA_AUTH_TOKEN"];
                AA_Log::Log(__METHOD__." - auth token login.",100);
            }
        }

        if ($sToken != null) {
            //AA_Log::Log(__METHOD__." - autenticazione in base al token.");

            $token_timeout_m = 30;
            $query_token = sprintf("SELECT * FROM tokens where (TIMESTAMPDIFF(MINUTE,data_rilascio, NOW()) < '%s' OR remember_me='1') and ip_src = '%s' and token ='%s'", $token_timeout_m, $_SERVER['REMOTE_ADDR'], $sToken);

            if ($db->Query($query_token)) {
                $result = $db->GetResult();
                $rs = $result->fetch(PDO::FETCH_ASSOC);
            } else {
                AA_Log::Log(__METHOD__ . " - errore nell'accesso al db: " . $db->GetErrorMessage(), 100);
                return AA_User::Guest();
            }

            if ($db->GetAffectedRows() > 0) {

                if (strcmp($rs['token'], $sToken) == 0) {
                    //AA_Log::Log(__METHOD__." - Authenticate token ($sToken) - success", 50);

                    $user = AA_User::LoadUser($rs['id_utente']);
                    if ($user->IsDisabled()) {
                        AA_Log::Log(get_class() . "->LegacyUserAuth($sToken) - L'utente è disattivato.", 100);
                        return AA_User::Guest();
                    }

                     //Old stuff compatibility
                     $_SESSION['user'] = $user->GetUsername();
                     $_SESSION['nome'] = $user->GetNome();
                     $_SESSION['cognome'] = $user->GetCognome();
                     $_SESSION['email'] = $user->GetEmail();
                     $_SESSION['user_home'] = "admin/index.php";
                     $_SESSION['id_user'] = $user->GetId();
                     $_SESSION['id_utente'] = $user->GetId();
                     $struct=$user->GetStruct();
                     $_SESSION['id_assessorato'] = $struct->GetAssessorato(true);
                     $_SESSION['tipo_struct'] = $struct->GetTipo();
                     $_SESSION['id_direzione'] = $struct->GetDirezione(true);
                     $_SESSION['id_servizio'] = $struct->GetServizio(true);
                     $_SESSION['id_settore'] = 0;
                     $_SESSION['livello'] = $user->GetLevel();
                     $_SESSION['level'] = $user->GetLevel();
                     $_SESSION['assessorato'] = $struct->GetAssessorato();
                     $_SESSION['direzione'] = $struct->GetDirezione();
                     $_SESSION['servizio'] = $struct->GetServizio();
                     $_SESSION['settore'] = "";
                     $_SESSION['user_flags'] = $user->GetFlags();
                     $_SESSION['flags'] = $user->GetFlags();
                    //AA_Log::LogAction($rs['id'], 0, "Log In"); //old stuff

                    //Rinfresco della durata del token
                    AA_User::RefreshToken($sToken);
                    $_SESSION['token'] = $sToken;

                    $user->bCurrentUser = true;

                    //update last login time
                    $db->Query("UPDATE utenti set lastlogin = NOW() WHERE id='".$rs['id_utente']."' LIMIT 1");

                    return $user;
                }
            }

            //Old stuff
            if (isset($log)) AA_Log::LogAction($rs->Get('id'), 0, "Authenticate token ($sToken) - failed");
            //----------

            AA_Log::Log(get_class() . "->LegacyUserAuth($sToken) - Authenticate token ($sToken) - failed", 100);
            return AA_User::Guest();
        }

        AA_Log::Log(get_class() . "->LegacyUserAuth($sToken,$sUserName) - Autenticazione fallita.", 100);
        return AA_User::Guest();
    }

    //Cambia il profilo dell'utente corrente
    static public function ChangeProfile($newProfileID = "")
    {
        AA_Log::Log(get_class() . "->ChangeProfile($newProfileID)");

        $user = self::GetCurrentUser();
        if ($user->IsGuest()) {
            AA_Log::Log(get_class() . "->ChangeProfile($newProfileID) - utente non valido o sessione scaduta.", 100, true, true);
            return false;
        }

        foreach (self::LoadUsersFromEmail($user->GetEmail(),false) as $curProfile) {
            if ($curProfile->GetID() == $newProfileID) {
                $sToken = $_SESSION['token'];

                //Aggiorna il token con il nuovo id utente
                $db = new AA_AccountsDatabase();
                $query = "UPDATE tokens set id_utente='" . $newProfileID . "' where token='" . $sToken . "' LIMIT 1";
                if (!$db->Query($query)) {
                    AA_Log::Log(get_class() . "->ChangeProfile($newProfileID) - errore nella query:" . $query, 100, true, true);
                    return false;
                }

                $newUser = self::UserAuth($sToken);
                if ($newUser->IsGuest()) {
                    AA_Log::Log(get_class() . "->ChangeProfile($newProfileID) - cambio di profilo fallito, sessione non valida o scaduta.", 100, true, true);
                    return false;
                }

                //Old stuff compatibility
                $_SESSION['user'] = $newUser->GetUsername();
                $_SESSION['nome'] = $newUser->GetNome();
                $_SESSION['cognome'] = $newUser->GetCognome();
                $_SESSION['email'] = $newUser->GetEmail();
                $_SESSION['user_home'] = "";
                $_SESSION['id_user'] = $newUser->GetID();
                $_SESSION['id_utente'] = $newUser->GetID();

                $struct = $newUser->GetStruct();
                $_SESSION['id_assessorato'] = $struct->GetAssessorato(true);
                $_SESSION['tipo_struct'] = $struct->GetTipo();
                $_SESSION['id_direzione'] = $struct->GetDirezione(true);
                $_SESSION['id_servizio'] = $struct->GetServizio(true);
                $_SESSION['id_settore'] = 0;
                $_SESSION['livello'] = $newUser->GetLevel();
                $_SESSION['level'] = $newUser->GetLevel();
                $_SESSION['assessorato'] = $struct->GetAssessorato();
                $_SESSION['direzione'] = $struct->GetDirezione();
                $_SESSION['servizio'] = $struct->GetServizio();
                $_SESSION['settore'] = "";
                $_SESSION['user_flags'] = $newUser->GetFlags();
                $_SESSION['flags'] = $newUser->GetFlags();

                return true;
            }
        }

        AA_Log::Log(get_class() . "->ChangeProfile($newProfileID) - cambio di profilo fallito, nessun profilo corrispondente trovato per l'utente corrente.", 100, true, true);
        return false;
    }

    //Autenticazione via mail OTP - passo 1
    static public function MailOTPAuthChallenge($email = null,$remember_me=false)
    {
        //AA_Log::Log(__METHOD__." - Authenticate mail OTP");

        unset($_SESSION['MailOTP-code']);
        unset($_SESSION['MailOTP-email']);
        unset($_SESSION['MailOTP-remember']);

        $email = str_replace("'", "", trim($email));

        if ($email == "") {
            AA_Log::Log(__METHOD__." - mail non impostata.", 100, true, true);
            return false;
        }

        $users=static::LoadUsersFromEmail($email);
        if(sizeof($users) == 0)
        {
            AA_Log::Log(__METHOD__ . " - Nessuna utenza trovata.", 100);
            return false;
        }

        //genera ed invia il codice di controllo alla email indicata
        $code = substr(md5(uniqid(mt_rand(), true)), 0, 6);
        $_SESSION['MailOTP-code'] = $code;
        $_SESSION['MailOTP-email'] = $email;
        if($remember_me) $_SESSION['MailOTP-remember'] = true;
        else  $_SESSION['MailOTP-remember'] = false;

        $subject =AA_User::$aOTPAuthEmailParams['oggetto'];
        $body = str_replace("#www#",AA_Const::AA_DOMAIN_NAME.AA_Const::AA_WWW_ROOT,AA_User::$aOTPAuthEmailParams['incipit']);
        $body .= "<p>codice OTP: <span style='font-weight: bold; font-size: 150%;'>" . $code . "</span></p>";
        $body .= str_replace("#www#",AA_Const::AA_DOMAIN_NAME.AA_Const::AA_WWW_ROOT,AA_User::$aOTPAuthEmailParams['post'].AA_User::$aOTPAuthEmailParams['firma']);

        if(AA_Const::AA_ENABLE_SENDMAIL)
        {
            $result = SendMail(array(0 => $email), "", $subject, $body);

            if (!$result) {
                AA_Log::Log(__METHOD__." - invio mail fallito - errore: " . $result, 100, true, true);
                return false;
            }
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - OTP: " . $code, 100);
            return true;
        }
       
        return true;
    }
    //-------------------------------

    //Autenticazione via mail OTP - passo 2
    static public function MailOTPAuthChallengeVerify($code="")
    {
        //AA_Log::Log(__METHOD__. " - Verifica mail OTP");
        
        if(!isset($_SESSION['MailOTP-code']) || !isset($_SESSION['MailOTP-email']))
        {
            AA_Log::Log(__METHOD__. " - Errore nella verifica del codice OTP (0)", 100);
            return false;
        }

        if($code =="" || $code != $_SESSION['MailOTP-code'])
        {
            AA_Log::Log(__METHOD__. " - Errore nella verifica del codice OTP (1)", 100);
            return false;
        }

        unset($_SESSION['MailOTP-code']);
        
        $remember_me=$_SESSION['MailOTP-remember'];
        $users=AA_User::LoadUsersFromEmail($_SESSION['MailOTP-email']);
        
        unset($_SESSION['MailOTP-email']);
        unset($_SESSION['MailOTP-remember']);

        if(sizeof($users)>0)
        {
            $user=array_shift($users);
            $_SESSION['token'] = AA_User::GenerateToken($user->GetId(),$remember_me);

            if($remember_me)
            {
                //token di autenticazione valido per 30 giorni, utilizzabile solo in https.
                setcookie("AA_AUTH_TOKEN",$_SESSION['token'],time()+(86400 * 30), "/",AA_Const::AA_DOMAIN_NAME,true, true);
            }
        }
        else
        {
            return false;
        }
                
        return true;
    }
    //-------------------------------

    //Verifica utente via mail OTP - passo 1
    public function MailOTPChangePwdChallenge()
    {
        //AA_Log::Log(__METHOD__." - Authenticate mail OTP");
        if(!$this->IsValid() || !$this->IsCurrentUser())
        {
            AA_Log::Log(__METHOD__." - Utente non valido.");
            return false;
        }
        
        unset($_SESSION['MailOTP-changepwd-code']);

        $email = str_replace("'", "", trim($this->GetEmail()));

        if ($email == "") {
            AA_Log::Log(__METHOD__." - mail non impostata.", 100, true, true);
            return false;
        }

        //genera ed invia il codice di controllo alla email indicata
        $code = substr(md5(uniqid(mt_rand(), true)), 0, 6);
        $_SESSION['MailOTP-changepwd-code'] = $code;

        $subject =AA_User::$aOTPChangePwdEmailParams['oggetto'];
        $body = str_replace("#www#",AA_Const::AA_DOMAIN_NAME.AA_Const::AA_WWW_ROOT,AA_User::$aOTPChangePwdEmailParams['incipit']);
        $body .= "<p>codice OTP: <span style='font-weight: bold; font-size: 150%;'>" . $code . "</span></p>";
        $body .= str_replace("#www#",AA_Const::AA_DOMAIN_NAME.AA_Const::AA_WWW_ROOT,AA_User::$aOTPChangePwdEmailParams['post'].AA_User::$aOTPChangePwdEmailParams['firma']);

        if(AA_Const::AA_ENABLE_SENDMAIL)
        {
            $result = SendMail(array(0 => $email), "", $subject, $body);

            if (!$result) {
                AA_Log::Log(__METHOD__." - invio mail fallito - errore: " . $result, 100, true, true);
                return false;
            }
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - OTP: " . $code, 100);
            return true;
        }
       
        return true;
    }
    //-------------------------------

    //verifica utente per cambio pwd via mail OTP - passo 2
    public function MailOTPChangePwdChallengeVerify($code="")
    {
        //AA_Log::Log(__METHOD__. " - Verifica mail OTP");

        if(!$this->IsValid() || !$this->IsCurrentUser())
        {
            AA_Log::Log(__METHOD__. " - Utente non valido.", 100);
            return false;
        }
        
        if(!isset($_SESSION['MailOTP-changepwd-code']))
        {
            AA_Log::Log(__METHOD__. " - Errore nella verifica del codice OTP (0)", 100);
            return false;
        }

        if($code =="" || $code != $_SESSION['MailOTP-changepwd-code'])
        {
            AA_Log::Log(__METHOD__. " - Errore nella verifica del codice OTP (1)", 100);
            return false;
        }
                
        return true;
    }
    //-------------------------------

    //Autenticazione via mail OTP - passo 1
    static public function MailOTPAuthSend($email = null, $register = true)
    {
        AA_Log::Log(get_class() . "->MailOTPAuthSend($email) - Authenticate mail OTP");

        $email = str_replace("'", "", trim($email));

        if ($email == "") {
            AA_Log::Log(get_class() . "->MailOTPAuthSend($email) - mail non impostata.", 100, true, true);
            return false;
        }

        if ($register) {
            //Verifica se la mail è già registrata sul database (SmartCV).
            $registered = self::MailOTPAuthRegisterEmail($email);
            if (!$registered) {
                AA_Log::Log(get_class() . "->MailOTPAuthSend($email) - registrazione email fallita.", 100, true, true);
                return false;
            }
        } else {
            //Verifica che alla email sia associato un utente esistente e valido
            //to do
        }

        //genera ed invia il codice di controllo alla email indicata
        $_SESSION['MailOTP-user'] = "";

        $_SESSION['MailOTP-email'] = $email;

        $code = substr(md5(uniqid(mt_rand(), true)), 0, 5);
        $_SESSION['MailOTP-code'] = $code;

        //------ Procedura smartCV
        if ($register) {
            //Registra il codice nel db
            $db = new AA_AccountsDatabase();
            $query = "UPDATE email_login set codice='" . $code . "' WHERE email='" . addslashes($email) . "' LIMIT 1";
            if (!$db->Query($query)) {
                AA_Log::Log(get_class() . "->MailOTPAuthSend($email) - errore: " . $db->GetErrorMessage() . " - nella query: " . $query, 100, true, true);
                return false;
            }
        }
        //---------------------------

        $subject = "Amministrazione Aperta - Verifica email";
        $body = "Stai ricevendo questa email perchè è stai cercando di accedere sulla piattaforma \"Amministrazione Aperta\" della Regione Autonoma della Sardegna";
        $body .= "di seguito è riportato il codice di verifica da inserire sulla pagina di autenticazione:<br/>";
        $body .= "<p>codice di verifica: <span style='font-weight: bold; font-size: 150%;'>" . $code . "</span></p>";
        $body .= "<p>Qualora non sia stato tu ad avviare la procedura di verifica, puoi ignorare questo messaggio o segnalare l'anomalia alla casella: amministrazioneaperta@regione.sardegna.it</p>";

        $result = SendMail(array(0 => $email), "", $subject, $body);

        if (!$result) {
            AA_Log::Log(get_class() . "->MailOTPAuthSend($email) - invio mail fallito - errore: " . $result, 100, true, true);
            return false;
        }

        return true;
    }
    //-------------------------------

    //Autenticazione via mail OTP - passo 2
    static public function MailOTPAuthVerify($codice = null)
    {
        AA_Log::Log(get_class() . "->MailOTPAuthVerify($codice) - Authenticate mail OTP - passo 2");

        if ($codice == "") {
            AA_Log::Log(get_class() . "->MailOTPAuthVerify($codice) - codice non valido.", 100, true, true);
            return false;
        }

        $email = $_SESSION['MailOTP-email'];
        if ($email == '') {
            AA_Log::Log(get_class() . "->MailOTPAuthVerify($codice) - email non valida.", 100, true, true);
            return false;
        }

        //Verifica il paio email-codice
        $db = new AA_AccountsDatabase();
        $query = "SELECT * from email_login WHERE email='" . $email . "' AND codice='" . str_replace("'", "", trim($codice)) . "' LIMIT 1";

        if (!$db->Query($query)) {
            AA_Log::Log(get_class() . "->MailOTPAuthVerify($email) - errore: " . $db->GetErrorMessage() . " - nella query: " . $query, 100, true, true);
            return false;
        }

        $rs = $db->GetResultSet();
        if (sizeof($rs)> 0) {
            $_SESSION['MailOTP-user'] = $rs[0]["id"];
            $_SESSION['MailOTP-nome'] = $rs[0]["nome"];
            $_SESSION['MailOTP-cognome'] = $rs[0]["cognome"];
            $aggiornamento = $rs[0]["aggiornamento"];
            if ($aggiornamento != "") {
                $aggiornamento = explode("-", $aggiornamento);
                $aggiornamento = $aggiornamento[2] . "/" . $aggiornamento[1] . "/" . $aggiornamento[0];
            }
            $_SESSION['MailOTP-aggiornamento'] = $aggiornamento;

            return true;
        }

        AA_Log::Log(get_class() . "->MailOTPAuthVerify($email) - codice errato.", 100, true, true);
        return false;
    }
    //-------------------------------

    //Verifica se la mail è già registrata sul sistema
    static public function MailOTPAuthIsMailRegistered($email = "")
    {
        AA_Log::Log(get_class() . "->MailOTPAuthIsMailRegistered($email)");

        if ($email == "") {
            AA_Log::Log(get_class() . "->MailOTPAuthIsMailRegistered($email) - mail non impostata.", 100, true, true);
            return false;
        }

        $db = new AA_AccountsDatabase();
        $query = "SELECT email from email_login where email='" . str_replace("'", "", trim($email)) . "' LIMIT 1";
        if (!$db->Query($query)) {
            AA_Log::Log(get_class() . "->MailOTPAuthIsMailRegistered($email) - errore: " . $db->lastError . " - nella query: " . $query, 100, true, true);
            return false;
        }

        $rs = $db->GetResultSet();
        if (sizeof($rs) > 0) return true;
        return false;
    }
    //---------------------------------------------

    //Registra una nuova mail sul sistema
    static public function MailOTPAuthRegisterEmail($email = "")
    {
        AA_Log::Log(get_class() . "->MailOTPAuthRegisterEmail($email)");

        if (self::MailOTPAuthIsMailRegistered($email)) return true;

        if ($email == "") {
            AA_Log::Log(get_class() . "->MailOTPAuthRegisterEmail($email) - mail non impostata.", 100, true, true);
            return false;
        }

        $db = new AA_AccountsDatabase();
        $query = "INSERT INTO email_login set email='" . str_replace("'", "", trim($email)) . "', aggiornamento=NOW()";
        if (!$db->Query($query)) {
            AA_Log::Log(get_class() . "->MailOTPAuthRegisterEmail($email) - errore: " . $db->GetErrorMessage() . " - nella query: " . $query, 100, true, true);
            return false;
        }

        return true;
    }
    //-----------------------------------------------------

    //Rimuovi le informazioni di autenticazione
    public function LogOut()
    {
        //AA_Log::Log(get_class() . "->LogOut() - " . $this->sUser . "(" . $this->nID . ")");

        if ($this->bIsValid && $this->bCurrentUser) {
            $db = new AA_AccountsDatabase();
            $query = "DELETE from tokens WHERE token='" . $_SESSION['token'] . "'";
            $db->Query($query);

            $_SESSION['token'] = null;
            setcookie("AA_AUTH_TOKEN","");

            unset($_SESSION);
            session_destroy();
        }
    }

    //Genera il token di autenticazione
    static private function GenerateToken($id_user, $remember_me=false, $concurrent_access=false)
    {
        //AA_Log::Log(__METHOD__."(".print_r($id_user,true).",".print_r($remember_me,true).",".print_r($concurrent_access,true).")",100);

        $token = hash("sha256", $id_user . date("Y-m-d H:i:s") . uniqid() . $_SERVER['REMOTE_ADDR']);

        //AA_Log::Log(get_class() . "->GenerateToken($id_user) - new token: " . $token);

        $db = new AA_AccountsDatabase();

        if(!$concurrent_access && !$remember_me)
        {
            AA_Log::Log(__METHOD__." - accesso concorrente disattivato, elimino token precedenti.",100);
            $query = "DELETE from tokens where id_utente='" . $id_user . "'";
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
            }   
        }

        //cancella i token vecchi
        $query = "DELETE from tokens where id_utente='" . $id_user . "' and TIMESTAMPDIFF(MINUTE,data_rilascio, NOW()) > '60' and remember_me='0'";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
        }
        //-----------------------

        $query = "INSERT INTO tokens set token='" . $token . "', id_utente='" . $id_user . "',ip_src='" . $_SERVER['REMOTE_ADDR'] . "'";

        if($remember_me === true || $remember_me > 0)
        {
            $query.=", remember_me='1'";
        }

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
        } 

        //AA_Log::Log(__METHOD__." - nuovo token: ".$token,100);

        return $token;
    }

    //Rinfresca il token di autenticazione
    static private function RefreshToken($token)
    {
        //AA_Log::Log(get_class() . "->RefreshToken($token)");

        $db = new AA_AccountsDatabase();

        $query = "UPDATE tokens SET data_rilascio=NOW() where token ='" . addslashes($token) . "'";

        $db->Query($query);
    }

    //Restituisce l'utente attualmente loggato (guest se non c'è nessun utente loggato)
    static protected $oCurrentUser=null;
    static public function GetCurrentUser()
    {
        if(static::$oCurrentUser instanceof AA_User) return static::$oCurrentUser;

        return AA_User::UserAuth();
    }

    //Restituisce l'utente guest
    static public function Guest()
    {
        AA_Log::Log(get_class() . "->Guest()");

        return new AA_User();
    }

    public function toXML()
    {
        AA_Log::Log(get_class() . "->toXML()");

        $result = '<utente id="' . $this->nID . '" livello="' . $this->nLivello . '" valid="' . $this->bIsValid . '" disabled="' . $this->nDisabled . '">';
        $result .= '<nome>' . $this->sNome . '</nome>';
        $result .= '<cognome>' . $this->sCognome . '</cognome>';
        $result .= '<user>' . $this->sUser . '</user>';
        $result .= '<email>' . $this->sEmail . '</email>';
        $result .= '<flags>' . $this->sFlags . '</flags>';
        $result .= '<image>' . $this->GetProfileImagePublicPath() . '</image>';
        //$result.=$this->oStruct->toXML();
        $result .= '</utente>';

        return $result;
    }

    //Rappresentazione stringa
    public function __toString()
    {
        //AA_Log::Log(get_class() . "->__toString()");

        return $this->toXML();
    }

    //Verifica la presenza di qualche flag
    public function HasFlag($flag)
    {
        //AA_Log::Log(get_class()."->HasFlag($flag)");

        if($flag == "") return false;

        if(array_search("1",$this->GetGroups()) !==false || $this->nID==1) return true;

        $flags = explode("|", $this->sFlags);
        if (in_array($flag, $flags,true) != false)
        {
            //AA_Log::Log(get_class()."->HasFlag($flag) - l'utente: ".$this->sUser."(".$this->nID.") ha il flag - flags:".print_r($flags,true),100);
            return true;
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $legacy_flags=explode("|", $this->sLegacyFlags);
            if (in_array($flag, $legacy_flags,true) != false) {
                //AA_Log::Log(get_class()."->HasFlag($flag) - l'utente: ".$this->sUser."(".$this->nID.") ha il flag",100,FALSE,TRUE);
                return true;
            }
        }

        //AA_Log::Log(get_class()."->HasFlag($flag) - l'utente: ".$this->sUser."(".$this->nID.") non ha il flag - ".print_r($flags,true),100, false,true);
        return false;
    }

    //Restituisce il nome
    public function GetNome()
    {
        return $this->sNome;
    }

    //Restituisce il nome
    public function GetName()
    {
        return $this->sNome;
    }
    //Restituisce il cognome
    public function GetCognome()
    {
        return $this->sCognome;
    }

    //Restituisce il nome utente
    public function GetUsername()
    {
        return $this->sUser;
    }

     //Restituisce il codice fiscale
     public function GetCf()
     {
         return $this->sCf;
     }

    //Restituisce l'email
    public function GetEmail()
    {
        return $this->sEmail;
    }

    //Restituisce il nome
    public function GetFlags($bArray = false, $bLegacyFlags=true)
    {
        $flags=$this->sFlags;
        if(AA_Const::AA_ENABLE_LEGACY_DATA && $this->sLegacyFlags !="" && $bLegacyFlags)
        {
            if($flags!="") $flags.="|".$this->sLegacyFlags;
            else $flags=$this->sLegacyFlags;
        }
        if ($bArray)
        {
            if($flags=="") return array();

            return array_unique(explode("|", $flags));
        }

        if($flags=="") return "";

        return implode("|",array_unique(explode("|", $flags)));
    }

    //Restituisce i flags legacy
    public function GetLegacyFlags($bArray = false)
    {
        $flags=$this->sLegacyFlags;
        if ($bArray)
        {
            if($flags=="") return array();

            return explode("|", $flags);
        } 

        return $flags;
    }

    //Verifica se il nome utente esiste già
    static public function UserNameExist($userName = "")
    {
        //AA_Log::Log(get_class() . "->UserNameExist($userName)");
        if ($userName == "") return false;

        $db = new AA_AccountsDatabase();

        $sql = "SELECT user FROM ".static::AA_DB_TABLE." where user='" . $userName . "' ";
        if (!$db->Query($sql)) {
            AA_Log::Log(__METHOD__." - Errore nella query: " . $db->GetErrorMessage(), 100);
            return false;
        }
        if ($db->GetAffectedRows() > 0) return true;

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $sql = "SELECT user FROM utenti where user='" . $userName . "' AND eliminato=0";
            if (!$db->Query($sql)) {
                AA_Log::Log(get_class() . "->UserNameExist($userName) - Errore nella query: " . $db->GetErrorMessage(), 100);
                return false;
            } 

            if ($db->GetAffectedRows() > 0) return true;   
        }

        return false;
    }

    //Ricerca utenti
    static public function Search($params=array(),$user=null)
    {
        if(!($user instanceof AA_User))
        {
            $user=AA_User::GetCurrentUser();
        }

        if(!$user->CanGestUtenti())
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non è abilitato alla gestione utenti.",100);
            return array();
        }

        $query="SELECT id from ".static::AA_DB_TABLE." WHERE id <> '".$user->GetId()."'";
        if($user->GetId() !=1 ) $query.=" AND id <> 1 ";
        
        switch($user->GetRuolo(true))
        {
            case AA_User::AA_USER_GROUP_SUPERUSER:
                break;
            case AA_User::AA_USER_GROUP_SERVEROPERATORS:
                $query.=" AND (FIND_IN_SET('1',".static::AA_DB_TABLE.".groups) = 0 OR ".static::AA_DB_TABLE.".groups like '')";
                break;
            case AA_User::AA_USER_GROUP_ADMINS:
                $query.=" AND (FIND_IN_SET(".static::AA_DB_TABLE.".groups,'3,4') > 0 OR ".static::AA_DB_TABLE.".groups like '')";
                break;
            default:
            $query.=" AND id=".$user->GetId();
        }

        //username
        if(isset($params['user']) && $params['user']!="")
        {
            $query.=" AND ".static::AA_DB_TABLE.".user like '%".addslashes($params['user'])."%'";
        }

        //email
        if(isset($params['email']) && $params['email']!="")
        {
            $query.=" AND email like '%".addslashes($params['email'])."%'";
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            //if(!$user->IsSuperUser()) $query.=" AND status >=-1 ";

            $struct=$user->GetStruct();
            if($struct->GetAssessorato(true)>0)
            {
                $query.=" AND (legacy_data like '%\"id_assessorato\":\"".$struct->GetAssessorato(true)."\"%' OR legacy_data like '%\"id_assessorato\":".$struct->GetAssessorato(true).",%')";
            }
            else
            {
                if($params['id_assessorato']>0)
                {
                    $query.=" AND (legacy_data like '%\"id_assessorato\":\"".$params['id_assessorato']."\"%' OR legacy_data like '%\"id_assessorato\":".$params['id_assessorato'].",%')";
                }
            }

            if($struct->GetDirezione(true)>0)
            {
                $query.=" AND (legacy_data like '%\"id_direzione\":\"".$struct->GetDirezione(true)."\"%' OR legacy_data like '%\"id_direzione\":".$struct->GetDirezione(true).",%')";
            }
            else
            {
                if($params['id_direzione']>0)
                {
                    $query.=" AND (legacy_data like '%\"id_direzione\":\"".$params['id_direzione']."\"%' OR legacy_data like '%\"id_direzione\":".$params['id_direzione'].",%')";
                }
            }

            if($struct->GetServizio(true)>0)
            {
                $query.=" AND (legacy_data like '%\"id_servizio\":\"".$struct->GetServizio(true)."\"%' OR legacy_data like '%\"id_servizio\":".$struct->GetServizio(true).",%')";
            }
            else
            {
                if($params['id_servizio']>0)
                {
                    $query.=" AND (legacy_data like '%\"id_servizio\":\"".$params['id_servizio']."\"%' OR legacy_data like '%\"id_servizio\":".$params['id_servizio'].",%')";
                }
            }
        }

        //stato
        if(isset($params['status']) && $params['status']>-2)
        {
            $query.=" AND status='".addslashes($params['status'])."'";
        }

        if(isset($params['ruolo']) && $params['ruolo'] > 0)
        {
            $query.=" AND FIND_IN_SET('".addslashes($params['ruolo'])."',".static::AA_DB_TABLE.".groups) > 0 ";
        }

        $db=new AA_AccountsDatabase();

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore: ".$db->GetErrorMessage(),100);
            return array();
        }

        //Limita la ricerca ai primi 500
        $query.=" LIMIT 500";

        //AA_Log::Log(__METHOD__." - query: ".$query,100);

        $rs=$db->GetResultSet();
        if(sizeof($rs)>0)
        {
            $result=array();
            foreach($rs as $curRow)
            {
                $user=AA_User::LoadUser($curRow['id']);
                if($user->IsValid()) $result[]=$user;
            }

            return $result;
        }

        return array();
    }

    //Ricerca utenti (solo utenti di livello "user")
    static public function SearchUsers($params=array(),$user=null)
    {
        if(!($user instanceof AA_User))
        {
            $user=AA_User::GetCurrentUser();
        }

        if($user->IsGuest())
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non è abilitato alla funzione richiesta.",100);
            return array();
        }

        $query="SELECT id from ".static::AA_DB_TABLE." WHERE ".static::AA_DB_TABLE.".groups = ".AA_User::AA_USER_GROUP_USERS." AND status=".AA_User::AA_USER_STATUS_ENABLED;

        //username
        if(isset($params['user']) && $params['user']!="")
        {
            $query.=" AND ".static::AA_DB_TABLE.".user like '%".addslashes($params['user'])."%'";
        }

        //email
        if(isset($params['email']) && $params['email']!="")
        {
            $query.=" AND email like '%".addslashes($params['email'])."%'";
        }

        //flags
        if(isset($params['flags']) && sizeof($params['flags'])>0)
        {
            $query.=" AND (";
            $curQuery="";
            foreach($params['flags'] as $curFlag)
            {
                if($curQuery=="") $curQuery.=" flags like '".addslashes($curFlag)."' OR flags like '".addslashes($curFlag)."|%' OR flags like '%|".addslashes($curFlag)."' OR flags like '%|".addslashes($curFlag)."|%'";
                else $curQuery.=" OR flags like '".addslashes($curFlag)."' OR flags like '".addslashes($curFlag)."|%' OR flags like '%|".addslashes($curFlag)."' OR flags like '%|".addslashes($curFlag)."|%'";
            }
            $query.=$curQuery.")";
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if(!$user->IsSuperUser()) $query.=" AND status >=0 ";

            $struct=$user->GetStruct();
            if($struct->GetAssessorato(true)>0)
            {
                $query.=" AND legacy_data like '%\"id_assessorato\":\"".$struct->GetAssessorato(true)."\"%'";
            }
            else
            {
                if($params['id_assessorato']>0)
                {
                    $query.=" AND legacy_data like '%\"id_assessorato\":\"".$params['id_assessorato']."\"%'";
                }
            }

            if($struct->GetDirezione(true)>0)
            {
                $query.=" AND legacy_data like '%\"id_direzione\":\"".$struct->GetDirezione(true)."\"%'";
            }
            else
            {
                if($params['id_direzione']>0)
                {
                    $query.=" AND legacy_data like '%\"id_direzione\":\"".$params['id_direzione']."\"%'";
                }
            }

            if($struct->GetServizio(true)>0)
            {
                $query.=" AND legacy_data like '%\"id_servizio\":\"".$struct->GetServizio(true)."\"%'";
            }
            else
            {
                if($params['id_servizio']>0)
                {
                    $query.=" AND legacy_data like '%\"id_servizio\":\"".$params['id_servizio']."\"%'";
                }
            }
        }

        $db=new AA_AccountsDatabase();

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore: ".$db->GetErrorMessage(),100);
            return array();
        }

        //Limita la ricerca ai primi 500
        $query.=" LIMIT 500";

        //AA_Log::Log(__METHOD__." - query: ".$query,100);

        $rs=$db->GetResultSet();
        if(sizeof($rs)>0)
        {
            $result=array();
            foreach($rs as $curRow)
            {
                $user=AA_User::LoadUser($curRow['id']);
                if($user->IsValid()) $result[]=$user;
            }

            return $result;
        }

        return array();
    }

    //Ricerca utenti
    static public function LegacySearch($params=array(),$user=null,$bOnlyLegacy=true)
    {
        if(!($user instanceof AA_User))
        {
            $user=AA_User::GetCurrentUser();
        }

        if(!$user->CanGestUtenti())
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non è abilitato alla gestione utenti.",100);
            return array();
        }

        $query="SELECT utenti.id as legacyId, ".static::AA_DB_TABLE.".id as nuovoId from utenti ";
        
        $query.=" LEFt JOIN ".static::AA_DB_TABLE." on utenti.id=".static::AA_DB_TABLE.".id WHERE utenti.id <> '".$user->GetId()."' AND utenti.eliminato=0";

        //username
        if(isset($params['user']) && $params['user']!="")
        {
            $query.=" AND utenti.user like '%".addslashes($params['user'])."%'";
        }

        //email
        if(isset($params['email']) && $params['email']!="")
        {
            $query.=" AND utenti.email like '%".addslashes($params['email'])."%'";
        }

        $query.=" AND eliminato = 0 ";

        $struct=$user->GetStruct();
        if($struct->GetAssessorato(true)>0)
        {
            $query.=" AND utenti.id_assessorato='".$struct->GetAssessorato(true)."'";
        }
        else
        {
            if($params['id_assessorato']>0)
            {
                $query.=" AND utenti.id_assessorato='".$params['id_assessorato']."'";
            }
        }

        if($struct->GetDirezione(true)>0)
        {
            $query.=" AND utenti.id_direzione='".$struct->GetDirezione(true)."'";
        }
        else
        {
            if($params['id_direzione']>0)
            {
                $query.=" AND utenti.id_direzione='".$params['id_direzione']."'";
            }
        }

        if($struct->GetServizio(true)>0)
        {
            $query.=" AND utenti.id_servizio='".$struct->GetServizio(true)."'";
        }
        else
        {
            if($params['id_servizio']>0)
            {
                $query.=" AND utenti.id_servizio='".$params['id_servizio']."'";
            }
        }

        //ruolo
        if(isset($params['ruolo']))
        {
            if($params['ruolo']==0) $query.=" AND utenti.livello='0'";
            if($params['ruolo']==1) $query.=" AND utenti.livello='1'";
        }

        //stato
        if(isset($params['status']))
        {
            if($params['status']==0) $query.=" AND utenti.disable='1'";
            if($params['status']==1) $query.=" AND utenti.disable='0'";
        }

        $db=new AA_AccountsDatabase();

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore: ".$db->GetErrorMessage()." - ".$query,100);
            return array();
        }

        //Limita la ricerca ai primi 500
        //$query.=" LIMIT 500";

        //AA_Log::Log(__METHOD__." - query: ".$query,100);

        $rs=$db->GetResultSet();
        if(sizeof($rs)>0)
        {
            $result=array();
            foreach($rs as $curRow)
            {
                if($bOnlyLegacy)
                {
                    if($curRow['nuovoId'] == "")
                    {
                        $user=AA_User::LegacyLoadUser($curRow['legacyId']);
                        if($user->IsValid()) $result[]=$user;        
                    }
                }
                else
                {
                    $user=AA_User::LegacyLoadUser($curRow['legacyId']);
                    if($user->IsValid()) $result[]=$user;
                }
            }

            return $result;
        }

        return array();
    }

    //Verifica se l'utente corrente può gestire gli utenti
    public function CanGestUtenti()
    {
        //AA_Log::Log(get_class()."->CanGestUtenti()");

        if (!$this->bIsValid) return false;

        if(!$this->isCurrentUser()) return false;

        if ($this->IsSuperUser()) return true;

        //AA_Log::Log(__METHOD__." - Verifica gestione utenti - ruolo: ".print_r($this->GetRuolo(true),true),100);
        if($this->GetRuolo(true) == AA_User::AA_USER_GROUP_SERVEROPERATORS) return true;

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            //AA_Log::Log(__METHOD__." - Verifica gestione utenti - legacy",100);
            if ($this->nLivello != AA_Const::AA_USER_LEVEL_ADMIN) return false;

            //if (!$this->HasFlag("U0")) return true;
        }

        return false;
    }

    //Verifica se l'utente corrente può gestire le strutture
    public function CanGestStruct()
    {
        //AA_Log::Log(get_class() . "->CanGestStruct()");

        if (!$this->bIsValid) return false;

        if(!$this->isCurrentUser()) return false;

        if ($this->IsSuperUser()) return true;

        if(array_search(AA_User::AA_USER_GROUP_SERVEROPERATORS,$this->GetAllGroups()) !== false) return true;

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {

            return false;
            //if ($this->nLivello != AA_Const::AA_USER_LEVEL_ADMIN) return false;

            //old
            //if (!$this->HasFlag("S0")) return true;  
        }

        return false;
    }

    //Verifica se l'utente corrente può modificare il livello dell'utente indicato (legacy)
    public function CanPromoteUserAsAdmin($idUser = null)
    {
        //AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser)");

        if (!$this->IsValid()) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - utente corrente non valido: " . $this->GetUsername(), 100);
            return false;
        }

        //Il super utente può modificare tutto
        if ($this->IsSuperUser()) return true;

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //L'utente non può cambiare il suo livello
        if ($this->nID == $user->GetID()) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - l'utente non può modificare il proprio livello", 100);
            return false;
        }

        //Non si possono modificare i livelli di utenti dello stesso livello gerarchico (super user escluso)

        if ($this->oStruct->GetServizio(true) == $user->GetStruct()->GetServizio(true) && $this->oStruct->GetServizio(true) != 0) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - utente corrente non può modificare il livello dell'utente: " . $this->GetUsername() . " (stesso servizio)", 100);
            return false;
        }
        if ($this->oStruct->GetDirezione(true) == $user->GetStruct()->GetDirezione(true) && $user->GetStruct()->GetServizio(true) == 0 && $this->oStruct->GetDirezione(true) != 0) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - utente corrente non può modificare il livello dell'utente: " . $this->GetUsername() . " (stessa direzione)", 100);
            return false;
        }
        if ($this->oStruct->GetAssessorato(true) == $user->GetStruct()->GetAssessorato(true) && $user->GetStruct()->GetDirezione(true) == 0 && $this->oStruct->GetAssessorato(true) != 0) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - utente corrente non può modificare il livello dell'utente: " . $this->GetUsername() . " (stesso assessorato)", 100);
            return false;
        }

        //Controlla se l'utente corrente può modificare l'utente
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - utente corrente non può modificare l'utente: " . $this->GetUsername(), 100);
            return false;
        }

        return true;
    }

    //Verifica se l'utente corrente può modificare l'utente indicato
    public function CanModifyUser($idUser = null)
    {
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido: " . $this->GetUsername(), 100);
            return false;
        }

        //Il super utente può modificare tutto
        if ($this->IsSuperUser()) return true;

        //AA_Log::Log(__METHOD__." - utente: " . $idUser. " - ".$this, 100);

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(__METHOD__." - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //L'utente può modificare se stesso
        if ($this->nID == $user->GetID()) return true;

        //Controlla se l'utente corrente è abilitato alla gestione utenti
        if (!$this->CanGestUtenti()) {
            AA_Log::Log(__METHOD__." - utente corrente non autorizzato alla gestione utenti: " . $this->GetUsername(), 100);
            return false;
        }

        //L'utente root può essere modificato solament da se stesso
        if($this->nID != 1 && $user->GetID()==1)
        {
            AA_Log::Log(__METHOD__." - utente corrente non autorizzato alla modifica dell'utente: " . $user->GetUsername(), 100);
            return false;
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if ($this->GetStruct()->GetAssessorato(true) != 0 && $this->GetStruct()->GetAssessorato(true) != $user->GetStruct()->GetAssessorato(true)) {
                AA_Log::Log(__METHOD__." - L'utente corrente non può modificare utenti di altre strutture.", 100);
                return false;
            }
    
            if ($this->GetStruct()->GetDirezione(true) != 0 && $this->GetStruct()->GetDirezione(true) != $user->GetStruct()->GetDirezione(true)) {
                AA_Log::Log(__METHOD__." - L'utente corrente non può modificare utenti di altre strutture.", 100);
                return false;
            }
    
            if ($this->GetStruct()->GetServizio(true) != 0 && $this->GetStruct()->GetServizio(true) != $user->GetStruct()->GetServizio(true)) {
                AA_Log::Log(__METHOD__." - L'utente corrente non può modificare utenti di altre strutture.", 100);
                return false;
            }

            if($this->GetRuolo(true)== AA_User::AA_USER_GROUP_SERVEROPERATORS) return true;
    
            //Non può modificare utenti amministratori dello stesso livello gerarchico
            if ($this->GetStruct()->GetServizio(true) == $user->GetStruct()->GetServizio(true) && $user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN && $this->GetStruct()->GetServizio(true) != 0) {
                AA_Log::Log(__METHOD__." - L'utente corrente (" . $this . ") non può modificare utenti amministratori dello stesso livello gerarchico (stesso servizio).", 100);
                return false;
            }
    
            if ($this->GetStruct()->GetDirezione(true) == $user->GetStruct()->GetDirezione(true) && $user->GetStruct()->GetServizio(true) == 0 && $user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN && $this->GetStruct()->GetDirezione(true) != 0) {
                AA_Log::Log(__METHOD__." - L'utente corrente (" . $this . ") non può modificare utenti amministratori dello stesso livello gerarchico (stessa direzione).", 100);
                return false;
            }
    
            if ($this->GetStruct()->GetAssessorato(true) == $user->GetStruct()->GetAssessorato(true) && $user->GetStruct()->GetDirezione(true) == 0 && $user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN && $this->GetStruct()->GetAssessorato(true) != 0) {
                AA_Log::Log(__METHOD__." - L'utente corrente (" . $this . ") non può modificare utenti amministratori dello stesso livello gerarchico (stesso assessorato).", 100);
                return false;
            }    
        }

        return true;
    }

    //Aggiungi un nuovo utente
    public function AddNewUser($params)
    {
        if ($this->IsGuest()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido", 100);
            return false;
        }

        //Recupera l'utente corrente
        if (!$this->isCurrentUser() || !$this->CanGestUtenti()) {
            AA_Log::Log(__METHOD__." - utente non autenticato o non autorizzato alla gestione utenti", 100);
            return false;
        }

        //verifica che sia presente l'email
        if ($params['email'] == "")
        {
            AA_Log::Log(__METHOD__." - occore indicare una email di riferimento.", 100);
            return false;
        }

        //Verifica se il nome utente sia valido
        if (!isset($params['user']) || $params['user'] == "") {

            $email=trim(strtolower($params['email']));
            $account=explode("@",$email);
            $suff="";
            $num=0;
            $univoco=AA_user::UserNameExist($account[0].$suff);
            while($univoco)
            {
                $num++;
                $suff="_".$num;
                
                $univoco=AA_user::UserNameExist($account[0].$suff);
            }

            $params['user']=$account[0].$suff;
        }
        else
        {
            //Verifica se l'utente esiste già
            if (AA_user::UserNameExist($params['user'])) {
                AA_Log::Log(__METHOD__." - nome utente già esistente.", 100);
                return false;
            }
        }

        $allUserGroups=$this->GetAllGroups();

        //------------- Ruolo -----------------
        if(!isset($params['ruolo'])) 
        {
            $ruolo=static::AA_USER_GROUP_OPERATORS;
            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                if(isset($params['livello']) && $params['livello']==1) $ruolo=static::AA_USER_GROUP_OPERATORS;
                if(isset($params['livello']) && $params['livello']==0) $ruolo=static::AA_USER_GROUP_ADMINS;    
            }
        }
        else
        {
            $ruolo=$params['ruolo'];
        }

        if(array_search($ruolo,$allUserGroups)===false)
        {
            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                $params['livello']==2;
            }   
            $ruolo=static::AA_USER_GROUP_USERS;
        } 
        //-------------------------------------

        //--------------- Gruppi --------------
        if(!isset($params['groups'])) $params['groups']=array();
        
        $groups=array($ruolo);
        foreach($params['groups'] as $curGroup)
        {
            if($curGroup > 1000 && array_search($curGroup,$allUserGroups) !== false)
            {
                $groups[]=$curGroup;
            }
        }

        if(sizeof($groups)==0)
        {
            //default group (utenti)
            $groups=array(static::AA_USER_GROUP_USERS);
        }
        //-------------------------------------

        //--------------- Status --------------
        $status=static::AA_USER_STATUS_DISABLED;
        if(isset($params['status']) && $params['status']==static::AA_USER_STATUS_ENABLED) $status=static::AA_USER_STATUS_ENABLED;
        if(isset($params['status']) && $params['status']==static::AA_USER_STATUS_DELETED) $status=static::AA_USER_STATUS_DELETED;
        if(isset($params['status']) && $params['status']==static::AA_USER_STATUS_DISABLED) $status=static::AA_USER_STATUS_DISABLED;

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if(isset($params['status']))
            {
                if($status==static::AA_USER_STATUS_DISABLED) $params['disable']=1;
                if($status==static::AA_USER_STATUS_DELETED) $params['eliminato']=1;
            }
            else
            {   $status=static::AA_USER_STATUS_ENABLED;
                if(isset($params['disable']) && $params['disable'] > 0) $status=static::AA_USER_STATUS_DISABLED;
                if(isset($params['eliminato']) && $params['eliminato'] > 0) $status=static::AA_USER_STATUS_DELETED;
            }
        }
        //--------------------------------------

        $db = new AA_AccountsDatabase();

        //new stuff
        $info=json_encode(array(
            "nome"=>addslashes(trim($params['nome'])),
            "cognome"=>addslashes(trim($params['cognome'])),
            "phone"=>addslashes(trim($params['phone'])),
            "image"=>addslashes(trim($params['image'])),
            "cf"=>addslashes(trim($params['cf'])),
        ));

        $sql="INSERT INTO ".static::AA_DB_TABLE." SET ";
        $sql.="user='".addslashes(trim($params['user']))."'";
        $sql.=", email='".addslashes(trim($params['email']))."'";
        $sql.=", flags='".addslashes(trim($params['flags']))."'";
        $sql.=", info='".addslashes($info)."'";
        $sql.=", data_abilitazione='".date("Y-m-d")."'";
        $sql.=", status='".$status."'";
        if (isset($params['passwd']) && $params['passwd'] !="") $sql.=", passwd='".AA_Utils::password_hash($params['passwd'])."'";
        else $sql.=", passwd='".AA_Utils::password_hash(uniqid(date("Y-m-d")))."'";
        $sql.=", ".static::AA_DB_TABLE.".groups='".implode(",",$groups)."'";
        
        if (!$db->Query($sql)) 
        {
            AA_Log::Log(__METHOD__ . " - new stuff - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }

        $params['new_id']=$db->GetLastInsertId();

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            return $this->LegacyAddNewUser($params);
        }

        return true;
    }

    //Migra un utente legacy sul nuovo framework
    static public function MigrateLegacyUser($legacyUser,$legacyPwd,$oldMd5Pwd="")
    {
        $user=static::GetCurrentUser();
        if(!$user->IsValid())
        {
            AA_Log::Log(__METHOD__." - utente non valido.",100);
            return false;
        }

        if(!($legacyUser instanceof AA_User))
        {
            AA_Log::Log(__METHOD__." - utente legacy non valido.",100);
            return false;
        }

        if(!$user->CanModifyUser($legacyUser))
        {
            AA_Log::Log(__METHOD__." - l'utente  corrente non può modificare l'utente legacy.",100);
            return false;
        }

        $db = new AA_AccountsDatabase();
        if(!$db->Query("SELECT id from ".AA_User::AA_DB_TABLE." WHERE id='".$legacyUser->GetId()."'"))
        {
            AA_Log::Log(__METHOD__." - errore nel recupero dei dati. ".$db->GetErrorMessage(),100);
            return false;
        }

        $update=false;
        if($db->GetAffectedRows()>0)
        {
            AA_Log::Log(__METHOD__." - utente già presente, aggiorno i dati ".$db->GetErrorMessage(),100,true);
            
            $update=true;
        }

        //Stato utente
        $status=static::AA_USER_STATUS_ENABLED;
        if($legacyUser->nDisabled>0) $status=static::AA_USER_STATUS_DISABLED;

        //new stuff
        $info=json_encode(array(
            "nome"=>addslashes(trim($legacyUser->GetNome())),
            "cognome"=>addslashes(trim($legacyUser->GetCognome())),
            "phone"=>addslashes(trim($legacyUser->GetPhone())),
            "image"=>addslashes(trim($legacyUser->GetImage()))
        ));

        if(!$update) $sql="INSERT INTO ".static::AA_DB_TABLE." SET id='".$legacyUser->GetId()."' , user='".addslashes(trim($legacyUser->GetUsername()))."'";
        else $sql="UPDATE ".static::AA_DB_TABLE." SET user='".addslashes(trim($legacyUser->GetUsername()))."'";
        $sql.=", email='".addslashes(trim($legacyUser->GetEmail()))."'";
        $sql.=", info='".addslashes($info)."'";
        $sql.=", data_abilitazione='".date("Y-m-d")."'";
        $sql.=", status='".$status."'";
        
        if($legacyPwd && $legacyPwd !="") $sql.=", passwd='".AA_Utils::password_hash($legacyPwd)."'";
        else 
        {
            if ($oldMd5Pwd && $oldMd5Pwd !="") 
            {
                AA_Log::Log(__METHOD__." - legacyPwd: ".$oldMd5Pwd,100);
                $sql.=", passwd='".AA_Utils::password_hash($oldMd5Pwd)."'";
            }
            else
            {
                $sql.=", passwd='".AA_Utils::password_hash(uniqid())."'";
            }
        }

        {
            $groups=AA_USER::AA_USER_GROUP_USERS;
            if($legacyUser->nLivello==1) $groups=AA_User::AA_USER_GROUP_OPERATORS;
            if($legacyUser->nLivello==0) $groups=AA_User::AA_USER_GROUP_ADMINS;
            if($legacyUser->IsSuperUser()) $groups=AA_USER::AA_USER_GROUP_SUPERUSER;
            $sql.=", groups='".$groups."'";
        }

        //legacy flags import
        $legacyflags=$legacyUser->GetLegacyFlags(true);
        //AA_Log::Log(__METHOD__." - LegacyFlags: ".print_r($legacyflags,true),100);

        $modulesFlags=array_keys(AA_Platform::GetAllModulesFlags());
        //AA_Log::Log(__METHOD__." - modulesFlagsKeys: ".print_r($modulesFlags,true),100);

        $userFlags=$legacyUser->GetFlags(true,false);
        foreach($legacyflags as $curFlag)
        {
            if(array_search($curFlag,$modulesFlags) !==false) $userFlags[]=$curFlag;
        }
        if(sizeof($userFlags)>0) $userFlags=implode("|",array_unique($userFlags));
        else $userFlags="";
        $sql.=", flags='".addslashes($userFlags)."' ";

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $struct=$legacyUser->GetStruct();
            $legacy_data=json_encode(array(
                "id_assessorato"=>$struct->GetAssessorato(true),
                "id_direzione"=>$struct->GetDirezione(true),
                "id_servizio"=>$struct->GetServizio(true),
                "level"=>$legacyUser->nLivello,
                "flags"=>$legacyUser->sLegacyFlags
            ));
            $sql.=", legacy_data='".$legacy_data."'";
        }

        if($update) $sql.=" WHERE id='".$legacyUser->GetId()."' LIMIT 1"; 

        if (!$db->Query($sql)) {
            AA_Log::Log(__METHOD__ . " - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }

        return true;
    }

    //Aggiungi un nuovo utente legacy
    public function LegacyAddNewUser($params)
    {
        AA_Log::Log(get_class() . "->LegacyAddNewUser($params)");

        if ($this->IsGuest()) {
            AA_Log::Log(get_class() . "->LegacyAddNewUser($params) - utente corrente non valido", 100);
            return false;
        }

        //Recupera l'utente corrente
        if (!$this->isCurrentUser() || !$this->CanGestUtenti()) {
            AA_Log::Log(get_class() . "->LegacyAddNewUser($params) - utente non autenticato o non autorizzato alla gestione utenti", 100);
            return false;
        }

        //Verifica se il nome utente sia valido
        if ($params['user'] == "") {
            AA_Log::Log(get_class() . "->LegacyAddNewUser($params) - nome utente non impostato", 100);
            return false;
        }

        //Verifica se l'utente esiste già
        if (!isset($params['new_id']) && AA_user::UserNameExist($params['user'])) {
            AA_Log::Log(get_class() . "->LegacyAddNewUser($params) - nome utente già esistente.", 100);
            return false;
        }

        $db = new AA_AccountsDatabase();

        $new_id=0;

        if(isset($params['new_id']) && $params['new_id'] > 0) $new_id=$params['new_id'];
    
        if ($this->oStruct->GetAssessorato(true) != 0 && $this->oStruct->GetAssessorato(true) != $params['assessorato']) {
            AA_Log::Log(__METHOD__." - Assessorato diverso", 100);
            return false;
        }
        if ($this->oStruct->GetDirezione(true) != 0 && $this->oStruct->GetDirezione(true) != $params['direzione']) {
            AA_Log::Log(__METHOD__." - Direzione diversa", 100);
            return false;
        }
        if ($this->oStruct->GetServizio(true) != 0 && $this->oStruct->GetServizio(true) != $params['servizio']) {
            AA_Log::Log(__METHOD__." - Servizio diverso", 100);
            return false;
        }

        //Non si possono istanziare utenti amministratori dello stesso livello gerarchico (super user escluso)
        if ($this->oStruct->GetServizio(true) == $params['servizio'] && $params['livello'] == 0  && $this->oStruct->GetServizio(true) != 0) {
            $params['livello'] = "1";
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può istanziare utenti amministratori dello stesso livello gerarchico", 100);
        }
        if ($this->oStruct->GetDirezione(true) == $params['direzione'] && $params['servizio'] == 0 && $params['livello'] == 0 && $this->oStruct->GetDirezione(true) != 0) {
            $params['livello'] = "1";
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può istanziare utenti amministratori dello stesso livello gerarchico", 100);
        }
        if ($this->oStruct->GetAssessorato(true) == $params['assessorato'] && $params['direzione'] == 0 && $params['livello'] == 0 && $this->oStruct->GetAssessorato(true) != 0) {
            $params['livello'] = "1";
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può istanziare utenti amministratori dello stesso livello gerarchico", 100);
        }

        $flags = "U0|S0";
        $separatore = "";

        //Solo admin imposta le flags
        if ($this->IsSuperUser()) {
            if (!isset($params['gest_utenti'])) {
                $flags .= $separatore . "U0";
                $separatore = "|";
            }
            if (!isset($params['gest_struct'])) {
                $flags .= $separatore . "S0";
                $separatore = "|";
            }
            if (isset($params['gest_polizze'])) {
                $flags .= $separatore . "polizze";
                $separatore = "|";
            }
            if (isset($params['gest_debitori'])) {
                $flags .= $separatore . "debitori";
                $separatore = "|";
            }
            if (isset($params['gest_accessi']) || (isset($params['legacyFlag_accessi']) && $params['legacyFlag_accessi'] > 0)) {
                $flags .= $separatore . "accessi";
                $separatore = "|";
            }
            if (isset($params['admin_gest_accessi']) || (isset($params['legacyFlag_admin_accessi']) && $params['legacyFlag_admin_accessi'] > 0)) {
                $flags .= $separatore . "admin_accessi";
                $separatore = "|";
            }
            if (isset($params['art12'])) {
                $flags .= $separatore . "art12";
                $separatore = "|";
            }
            if (isset($params['art14c1a'])) {
                $flags .= $separatore . "art14c1a|art14";
                $separatore = "|";
            }
            if (isset($params['art14c1c'])) {
                $flags .= $separatore . "art14c1c|art14";
                $separatore = "|";
            }
            if (isset($params['art14c1bis'])) {
                $flags .= $separatore . "art14|art14c1bis";
                $separatore = "|";
            }
            if (isset($params['art23'])) {
                $flags .= $separatore . "art23";
                $separatore = "|";
            }
            if (isset($params['art22'])) {
                $flags .= $separatore . "art22";
                $separatore = "|";
            }
            if (isset($params['art22_admin'])) {
                $flags .= $separatore . "art22_admin";
                $separatore = "|";
            }
            if (isset($params['art30'])) {
                $flags .= $separatore . "art30";
                $separatore = "|";
            } //old

            if (isset($params['gest_processi']) || (isset($params['legacyFlag_processi']) && $params['legacyFlag_processi']>0)) {
                $flags .= $separatore . "processi";
                $separatore = "|";
            }
            if (isset($params['gest_processi_admin']) || (isset($params['legacyFlag_processi_admin']) && $params['legacyFlag_processi_admin']>0)) {
                $flags .= $separatore . "processi_admin";
                $separatore = "|";
            }
            if (isset($params['gest_incarichi_titolari']) || (isset($params['legacyFlag_incarichi_titolari']) && $params['legacyFlag_incarichi_titolari']>0)) {
                $flags .= $separatore . AA_Const::AA_USER_FLAG_INCARICHI_TITOLARI;
                $separatore = "|";
            }
            if (isset($params['gest_incarichi']) || (isset($params['legacyFlag_incarichi']) && $params['legacyFlag_incarichi']>0)) {
                $flags .= $separatore . AA_Const::AA_USER_FLAG_INCARICHI;
                $separatore = "|";
            }
            if (isset($params['patrimonio'])) {
                $flags .= $separatore . "patrimonio";
                $separatore = "|";
            }
            if (isset($params['concurrent']) && $params['concurrent']>0) {
                $flags .= $separatore . "concurrent";
                $separatore = "|";
            }

            //AA_Log::Log(get_class()."->UpdateUser($idUser, $params)", 100, false,true);
        }

        //la modifica delle schede pubblicate può essere abilitata anche dagli altri utenti amministratori
        if (isset($params['unlock']) && $params['livello'] == 0) {
            $flags .= $separatore . "P1";
            $separatore = "|";
        }

        //Inserisce l'utente
        $sql = "INSERT INTO utenti SET ";
        if($new_id > 0) $sql .= "id='".$new_id."', id_assessorato='" . $params['assessorato'] . "'";
        else $sql .= "id_assessorato='" . $params['assessorato'] . "'";
        $sql .= ",id_direzione='" . $params['direzione'] . "'";
        $sql .= ",id_servizio='" . $params['servizio'] . "'";
        $sql .= ",id_settore='0'";
        $sql .= ",user='" . addslashes(trim($params['user'])) . "'";
        if (isset($params['passwd'])) $sql .= ",passwd=MD5('" . $params['passwd'] . "')";
        else $sql .= ",passwd=MD5('" . date("Y/m/d H:i") . "')";
        $sql .= ",livello='" . $params['livello'] . "'";
        $sql .= ",nome='" . addslashes($params['nome']) . "'";
        $sql .= ",cognome='" . addslashes($params['cognome']) . "'";
        $sql .= ",email='" . addslashes($params['email']) . "'";
        $sql .= ",phone='" . addslashes($params['phone']) . "'";
        $sql .= ",image=''";
        $sql .= ",lastlogin=''";
        $sql .= ",flags='" . $flags . "'";
        if (isset($params['disable'])) $sql .= ",disable='1'";
        else $sql .= ",disable='0'";
        if (isset($params['concurrent']) && $params['concurrent']>0) $sql .= ",concurrent='1'";
        else $sql .= ",concurrent='0'";

        if (!$db->Query($sql)) {
            AA_Log::Log(__METHOD__." - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }

        $legacy_data=array(
            "id_assessorato"=>$params['assessorato'],
            "id_direzione"=>$params['direzione'],
            "id_servizio"=>$params['servizio'],
            "id"=>$new_id,
            "level"=>$params['livello'],
            "flags"=>$flags
        );

        if (isset($params['passwd']) && $params['passwd'] !="") $legacy_data['pwd']=md5($params['passwd']);

        //Aggiorna la nuova tabella
        if($new_id > 0) $db->Query("UPDATE ".static::AA_DB_TABLE." SET legacy_data='".addslashes(json_encode($legacy_data))."' WHERE id='".$new_id."' LIMIT 1");
        
        AA_Log::LogAction($this->GetID(), "1,9," . $new_id, $sql); //Old stuff

        return true;
    }

    //Aggiorna L'utente
    public function UpdateUser($idUser, $params)
    {
        if ($this->IsGuest()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido", 100);
            return false;
        }

        //Verifica se l'utente corrente può gestire gli utenti
        if (!$this->isCurrentUser()) {
            AA_Log::Log(__METHOD__." - utente non autenticato.", 100);
            return false;
        }

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(__METHOD__." - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica se l'utente corrente può modificare l'utente indicato
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare l'utente indicato: " . $user->GetUsername(), 100);
            return false;
        }

        if(!isset($params['user']) || $params['user']=="")
        {
            $params['user']=$user->GetUsername();
        }

        if($params['user'] !="" && $params['user'] !=$user->GetUsername())
        {
            if($this->UserNameExist($params['user']))
            {
                AA_Log::Log(__METHOD__." - Nome utente già in uso.", 100);
                return false;
            }
        }

        if(!isset($params['email']) || $params['email'] == "")
        {
            $params['email']=$user->GetEmail();
        }

        if(!isset($params['nome']) || $params['nome'] == "")
        {
            $params['nome']=$user->GetNome();
        }

        if(!isset($params['cognome']) || $params['cognome'] == "")
        {
            $params['cognome']=$user->GetCognome();
        }

        $allUserGroups=$this->GetAllGroups();

        //------------- Ruolo -----------------
        if(!isset($params['ruolo'])) 
        {
            $ruolo=$user->GetRuolo(true);
            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                if(isset($params['livello']) && $params['livello']==1) $ruolo=static::AA_USER_GROUP_OPERATORS;
                if(isset($params['livello']) && $params['livello']==0) $ruolo=static::AA_USER_GROUP_ADMINS;    
            }
        }
        else
        {
            $ruolo=$params['ruolo'];
        }

        if(array_search($ruolo,$allUserGroups)===false)
        {
            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                $params['livello']==2;
            }   
            $ruolo=static::AA_USER_GROUP_USERS;
        } 
        //-------------------------------------

        //--------------- Gruppi --------------
        if(!isset($params['groups'])) $params['groups']=$user->GetGroups();
        
        $groups=array($ruolo);
        foreach($params['groups'] as $curGroup)
        {
            if($curGroup > 1000 && array_search($curGroup,$allUserGroups) !== false)
            {
                $groups[]=$curGroup;
            }
        }

        if(sizeof($groups)==0)
        {
            //default group (utenti)
            $groups=array(static::AA_USER_GROUP_USERS);
        }
        //-------------------------------------

        //--------------- Status --------------
        $status=$user->GetStatus();
        if(isset($params['status']) && $params['status']==static::AA_USER_STATUS_ENABLED) $status=static::AA_USER_STATUS_ENABLED;
        if(isset($params['status']) && $params['status']==static::AA_USER_STATUS_DELETED) $status=static::AA_USER_STATUS_DELETED;
        if(isset($params['status']) && $params['status']==static::AA_USER_STATUS_DISABLED) $status=static::AA_USER_STATUS_DISABLED;

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if(isset($params['status']))
            {
                if($status==static::AA_USER_STATUS_DISABLED) $params['disable']=1;
                if($status==static::AA_USER_STATUS_DELETED) $params['eliminato']=1;
            }
            else
            {   
                if(isset($params['disable']) && $params['disable'] > 0) $status=static::AA_USER_STATUS_DISABLED;
                if(isset($params['eliminato']) && $params['eliminato'] > 0) $status=static::AA_USER_STATUS_DELETED;
            }

            if(!$this->LegacyUpdateUser($idUser,$params))
            {
                return false;
            }
        }
        //--------------------------------------

        if(!isset($params['flags']))
        {
            $params['flags']=$user->GetFlags(false,false);
        }

        $info=json_encode(array(
            "nome"=>trim($params['nome']),
            "cognome"=>trim($params['cognome']),
            "phone"=>trim($params['phone']),
            "image"=>trim($params['image']),
            "cf"=>trim($params['cf']),
        ));

        $sql="UPDATE ".static::AA_DB_TABLE." SET ";
        $sql.="user='".addslashes(trim($params['user']))."'";
        $sql.=", email='".addslashes(trim($params['email']))."'";
        $sql.=", flags='".addslashes(trim($params['flags']))."'";
        $sql.=", info='".addslashes($info)."'";
        $sql.=", data_abilitazione='".date("Y-m-d")."'";
        $sql.=", status='".$status."'";
        if (isset($params['passwd']) && $params['passwd'] !="") $sql.=", passwd='".AA_Utils::password_hash($params['passwd'])."'";

        $sql.=", ".static::AA_DB_TABLE.".groups='".addslashes(implode(",",$groups))."'";

        $sql.=" WHERE id='".$user->GetId()."' LIMIT 1";

        $db=new AA_AccountsDatabase();

        if ($db->Query($sql) === false) {
            AA_Log::Log(__METHOD__."  - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            AA_Log::Log(__METHOD__."  - Errore nell'aggiornamento dell'utente:  " . $user->GetUsername(), 100);
            return false;
        }

       return true;
    }

    //Aggiorna L'utente (legacy)
    public function LegacyUpdateUser($idUser, $params)
    {
        //AA_Log::Log(__METHOD__."");

        if ($this->IsGuest()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido", 100);
            return false;
        }

        //Verifica se l'utente corrente può gestire gli utenti
        if (!$this->isCurrentUser()) {
            AA_Log::Log(__METHOD__." - utente non autenticato.", 100);
            return false;
        }

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(__METHOD__." - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica se l'utente corrente può modificare l'utente indicato
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare l'utente indicato: " . $user->GetUsername(), 100);
            return false;
        }

        //Non si può modificare il livello per utenti amministratori dello stesso livello gerarchico (super user escluso)
        $struct = $this->oStruct;
        if ($struct->GetServizio(true) == $params['servizio'] && $params['livello'] == 0 && $struct->GetServizio(true) != 0) {
            $params['livello'] = "";
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare il livello dell'utente indicato: " . $user->GetUsername(), 100);
        }
        if ($struct->GetDirezione(true) == $params['direzione'] && $params['servizio'] == 0 && $params['livello'] == 0 && $struct->GetDirezione(true) != 0) {
            $params['livello'] = "";
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare il livello dell'utente indicato: " . $user->GetUsername(), 100);
        }
        if ($struct->GetAssessorato(true) == $params['assessorato'] && $params['direzione'] == 0 && $params['livello'] == 0 && $struct->GetAssessorato(true) != 0) {
            $params['livello'] = "";
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare il livello dell'utente indicato: " . $user->GetUsername(), 100);
        }

        $flags = "";
        $separatore = "";

        //Solo admin imposta le flags
        if ($this->IsSuperUser()) {
            if (!isset($params['gest_utenti'])) {
                $flags .= $separatore . "U0";
                $separatore = "|";
            }
            if (!isset($params['gest_struct'])) {
                $flags .= $separatore . "S0";
                $separatore = "|";
            }
            if (isset($params['gest_polizze'])) {
                $flags .= $separatore . "polizze";
                $separatore = "|";
            }
            if (isset($params['gest_debitori'])) {
                $flags .= $separatore . "debitori";
                $separatore = "|";
            }
            if (isset($params['gest_accessi']) || (isset($params['legacyFlag_accessi']) && $params['legacyFlag_accessi'] > 0)) {
                $flags .= $separatore . "accessi";
                $separatore = "|";
            }
            if (isset($params['admin_gest_accessi']) || (isset($params['legacyFlag_admin_accessi']) && $params['legacyFlag_admin_accessi'] > 0)) {
                $flags .= $separatore . "admin_accessi";
                $separatore = "|";
            }
            if (isset($params['art12'])) {
                $flags .= $separatore . "art12";
                $separatore = "|";
            }
            if (isset($params['art14c1a'])) {
                $flags .= $separatore . "art14c1a|art14";
                $separatore = "|";
            }
            if (isset($params['art14c1c'])) {
                $flags .= $separatore . "art14c1c|art14";
                $separatore = "|";
            }
            if (isset($params['art14c1bis'])) {
                $flags .= $separatore . "art14|art14c1bis";
                $separatore = "|";
            }
            if (isset($params['art23']) || (isset($params['flag_art23']) && $params['flag_art23'] > 0)) {
                $flags .= $separatore . "art23";
                $separatore = "|";
            }
            if (isset($params['art22']) || (isset($params['flag_art22']) && $params['flag_art22'] > 0)) {
                $flags .= $separatore . "art22";
                $separatore = "|";
            }
            if (isset($params['art22_admin'])) {
                $flags .= $separatore . "art22_admin";
                $separatore = "|";
            }
            if (isset($params['art30'])) {
                $flags .= $separatore . "art30";
                $separatore = "|";
            } //old

            if (isset($params['gest_processi']) || (isset($params['legacyFlag_processi']) && $params['legacyFlag_processi']>0)) {
                $flags .= $separatore . "processi";
                $separatore = "|";
            }
            if (isset($params['gest_processi_admin']) || (isset($params['legacyFlag_processi_admin']) && $params['legacyFlag_processi_admin']>0)) {
                $flags .= $separatore . "processi_admin";
                $separatore = "|";
            }
            if (isset($params['gest_incarichi_titolari']) || (isset($params['legacyFlag_incarichi_titolari']) && $params['legacyFlag_incarichi_titolari']>0)) {
                $flags .= $separatore . AA_Const::AA_USER_FLAG_INCARICHI_TITOLARI;
                $separatore = "|";
            }
            if (isset($params['gest_incarichi']) || (isset($params['legacyFlag_incarichi']) && $params['legacyFlag_incarichi']>0)) {
                $flags .= $separatore . AA_Const::AA_USER_FLAG_INCARICHI;
                $separatore = "|";
            }
            if (isset($params['patrimonio']) || (isset($params['flag_patrimonio']) && $params['flag_patrimonio'] > 0)) {
                $flags .= $separatore . "patrimonio";
                $separatore = "|";
            }
            if (isset($params['concurrent']) && $params['concurrent']>0) {
                $flags .= $separatore . "concurrent";
                $separatore = "|";
            }

            //AA_Log::Log(get_class()."->UpdateUser($idUser, $params)", 100, false,true);
        }
        else
        {
            $flags=$user->GetLegacyFlags();
        }

        //la modifica delle schede pubblicate può essere abilitata anche dagli altri utenti amministratori
        if (isset($params['unlock']) && $params['livello'] == 0) {
            $flags .= $separatore . "P1";
            $separatore = "|";
        }

        //Aggiorna l'utente
        $db = new AA_AccountsDatabase();
        $sql = "UPDATE utenti SET user=user";
        if ($params['passwd'] != "") $sql .= ",passwd=MD5('" . $params['passwd'] . "')";

        //Dati aggionabili solo se utenti diversi
        if ($this->GetID() != $user->GetID()) {
            $sql .= ",id_assessorato='" . $params['assessorato'] . "'";
            $sql .= ",id_direzione='" . $params['direzione'] . "'";
            $sql .= ",id_servizio='" . $params['servizio'] . "'";
            $sql .= ",id_settore='0'";
            if ($params['livello'] != "") $sql .= ",livello='" . $params['livello'] . "'";
            if ($this->IsSuperUser()) $sql .= ",flags='" . $flags . "'";
            if (isset($params['disable'])) $sql .= ",disable='1'";
            else $sql .= ",disable='0'";
            if (isset($params['concurrent']) && $params['concurrent']>0) $sql .= ",concurrent='1'";
            else $sql .= ",concurrent='0'";
        }

        $sql .= ",nome='" . addslashes($params['nome']) . "'";
        $sql .= ",cognome='" . addslashes($params['cognome']) . "'";
        $sql .= ",email='" . $params['email'] . "'";

        $sql .= " where id='" . $user->GetID() . "' LIMIT 1";

        if ($db->Query($sql) === false) {
            AA_Log::Log(__METHOD__."  - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }

        $legacy_data=json_encode(array(
            "id_assessorato"=>$params['assessorato'],
            "id_direzione"=>$params['direzione'],
            "id_servizio"=>$params['servizio'],
            "level"=>$params['livello'],
            "flags"=>$flags
        ));

        //if (isset($params['passwd']) && $params['passwd'] !="") $legacy_data['pwd']=md5($params['passwd']);

        //Aggiorna la nuova tabella
        $db->Query("UPDATE ".static::AA_DB_TABLE." SET legacy_data='".addslashes($legacy_data)."' WHERE id='".$user->GetId()."' LIMIT 1");
        
        AA_Log::LogAction($this->GetID(), "2,9," . $user->GetID(), $sql); //Old stuff

        return true;
    }

    //Aggiornamento della password utente
    public function ChangePwd($params=null)
    {
        if(!$this->isCurrentUser() || !$this->IsValid() || !is_array($params))
        {
            AA_Log::Log(__METHOD__." - Utente o parametri non validi",100);
            return false;
        }

        $newPwd=str_replace(array(",",";","'",'"',"+","-"," "),"",trim($params['new_user_pwd']));
        $reNewPwd=str_replace(array(",",";","'",'"',"+","-"," "),"",trim($params['re_new_user_pwd']));
        $otp=trim($params['otp']);

        //verifica OTP
        if(!$this->MailOTPChangePwdChallengeVerify($otp))
        {
            AA_Log::Log(__METHOD__." - Codice OTP errato.",100);
            return false;
        }

        //Verifica che la nuova password abbia almeno 12 caratteri, contenga un numero, una lettera maiuscola e una lettera minuscola e non contenga la vecchia password
        $password_regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/"; 

        if(preg_match($password_regex, $newPwd)==0 || $newPwd == "")
        {
            AA_Log::Log(__METHOD__ . " - La nuova password deve contenere:<br>almeno 12 caratteri<br>almeno una lettera maiuscola<br>almeno un numero<br>almeno una lettera minuscola<br>almeno uno dei seguenti simboli: @$!%*?&", 100);
            return false;
        }

        if($newPwd != $reNewPwd)
        {
           AA_Log::Log(__METHOD__ . " - La nuova password deve coincidere con quella ridigitata.", 100);
           return false;
        }

        //verifica vecchia password
        $db=new AA_AccountsDatabase();

        //salva la nuova password
        $query = "UPDATE ".static::AA_DB_TABLE." set passwd='" . AA_Utils::password_hash($newPwd) . "' where id='" . $this->GetID() . "' LIMIT 1";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__ . " - Errore query db. ".$query, 100);
            return false;
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $query = "UPDATE utenti set passwd='" .md5($newPwd)."' where id='" . $this->GetID() . "' LIMIT 1";
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__ . " - Errore query db. ".$query, 100);
                return false;
            }
        }

        if(isset($_SESSION['MailOTP-changepwd-code'])) unset($_SESSION['MailOTP-changepwd-code']);

        return true;
    }

    //Funzione di aggiornamento del profilo utente corrente
    public static function UpdateCurrentUserProfile($params,$imageFileName="")
    {
        $user=AA_User::GetCurrentUser();
        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente corrente non valido", 100);
            return false;
        }

        if($params["email"] != $user->GetEmail())
        {
            AA_Log::Log(__METHOD__ . " - L'utente corrente ha una email diversa da quella indicata.", 100);
            return false;
        }

        if($params['nome'] =="" || $params['cognome']=="")
        {
            AA_Log::Log(__METHOD__ . " - il nome e il cognome non possono essere vuoti.", 100);
            return false;            
        }

        $db=new AA_AccountsDatabase();
        $pwd=false;

        if($params["old_pwd"] !="" && $params['new_pwd'] !="" && $params["new_pwd_retype"] !="")
        {
            //Verifica che la nuova password abbia almeno 8 caratteri, contenga un numero, una lettera maiuscola e una lettera minuscola e non contenga la vecchia password
            $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{12,}$/"; 
            if(preg_match($password_regex, $params['new_pwd'])==0)
            {
                AA_Log::Log(__METHOD__ . " - La nuova password deve avere almeno 12 caratteri, almeno una lettera maiuscola e almeno una lettera minuscola.", 100);
                return false;
            }

            if($params['new_pwd'] != $params['new_pwd_retype'])
            {
                AA_Log::Log(__METHOD__ . " - La nuova password deve coincidere con quella ridigitata.", 100);
                return false;
            }

            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                if(!static::LegacyUpdateCurrentUserProfile($params,$imageFileName))
                {
                    return false;
                }
            }

            //verifica che la password attuale sia corretta
            $query="SELECT passwd,info from ".static::AA_DB_TABLE." WHERE id='".addslashes($user->getId())."' LIMIT 1";
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__ . " - Errore nella verifica delle credenziali impostate.", 100);
                return false;
            }

            if($db->GetAffectedRows()==0)
            {
                AA_Log::Log(__METHOD__ . " - Utente non trovato.", 100);

                if(!static::MigrateLegacyUser($user,$params['new_pwd']))
                {
                    return false;
                }

                return true;
            }

            $rs=$db->GetResultSet();
            if(!password_verify($params['old_pwd'],$rs[0]['passwd']))
            {
                AA_Log::Log(__METHOD__ . " - Vecchia password errata.", 100);
                return false;
            }

            $pwd=true;
        }

        $info=json_decode($rs[0]['info'],true);
        if(!is_array($info))
        {
            $info=array("nome"=>$params['nome'],"cognome"=>$params['cognome'],"phone"=>$params['phone'],"image"=>$imageFileName);
        }
        else
        {
            $info['nome']=$params['nome'];
            $info['cognome']=$params['cognome'];
            $info['phone']=$params['phone'];
            $info['image']=$imageFileName;
        }

        $query="UPDATE ".static::AA_DB_TABLE." SET info='".addSlashes(json_encode($info))."'";
        if($pwd) $query.=",passwd='".addslashes(AA_Utils::password_hash($params['new_pwd']))."'";
        $query.=" WHERE id='".$user->GetId()."' LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__ . " - Errore durante l'aggiornamento dei dati.", 100);
            return false;
        }

        return true;
    }

    //Funzione di aggiornamento del profilo utente corrente (legacy)
    public static function LegacyUpdateCurrentUserProfile($params,$imageFileName="")
    {
        $user=AA_User::GetCurrentUser();
        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente corrente non valido", 100);
            return false;
        }

        if($params["email"] != $user->GetEmail())
        {
            AA_Log::Log(__METHOD__ . " - L'utente corrente ha una email diversa da quella indicata.", 100);
            return false;
        }

        if($params['nome'] =="" || $params['cognome']=="")
        {
            AA_Log::Log(__METHOD__ . " - il nome e il cognome non possono essere vuoti.", 100);
            return false;            
        }

        $db=new AA_AccountsDatabase();
        $pwd=false;

        if($params["old_pwd"] !="" && $params['new_pwd'] !="" && $params["new_pwd_retype"] !="")
        {
            //verifica che la password attuale sia corretta
            $query="SELECT id from utenti WHERE email='".addslashes($params['email'])."' AND passwd=MD5('".addslashes($params['old_pwd'])."') LIMIT 1";
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__ . " - Errore nella verifica delle credenziali impostate.", 100);
                return false;
            }

            if($db->GetAffectedRows()==0)
            {
                AA_Log::Log(__METHOD__ . " - La password corrente è errata.", 100);
                return false;
            }

            //Verifica che la nuova password abbia almeno 8 caratteri, contenga un numero, una lettera maiuscola e una lettera minuscola e non contenga la vecchia password
            $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/"; 
            if(preg_match($password_regex, $params['new_pwd'])==0)
            {
                AA_Log::Log(__METHOD__ . " - La nuova password deve avere almeno 8 caratteri, almeno una lettera maiuscola e almeno una lettera minuscola.", 100);
                return false;
            }

            if($params['new_pwd'] != $params['new_pwd_retype'])
            {
                AA_Log::Log(__METHOD__ . " - La nuova password deve coincidere con quella ridigitata.", 100);
                return false;
            }

            $pwd=true;
        }

        $query="UPDATE utenti SET nome='".addSlashes($params['nome'])."',cognome='".addSlashes($params['cognome'])."',phone='".addslashes($params['phone'])."'";
        if($pwd) $query.=",passwd=MD5('".addslashes($params['new_pwd'])."')";
        if($imageFileName !="") $query.=", image='".addslashes($imageFileName)."'";
        $query.=" WHERE id='".$user->GetId()."' LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__ . " - Errore durante l'aggiornamento dei dati.", 100);
            return false;
        }

        //AA_Log::Log(__METHOD__ . " - query: ".$query, 100);

        return true;
    }

    //Elimina l'utente indicato
    public function LegacyDeleteUser($idUser)
    {
        //AA_Log::Log(__METHOD__,100);

        if ($this->IsGuest()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido", 100);
            return false;
        }

        //Verifica se l'utente corrente può gestire gli utenti
        if (!$this->isCurrentUser()) {
            AA_Log::Log(__METHOD__." - utente corrente non autenticato.", 100);
            return false;
        }

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(__METHOD__." - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica se l'utente corrente può modificare l'utente indicato
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare l'utente indicato: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica che non sia l'utente corrente
        if ($this->GetID() == $user->GetID()) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può eliminare se stesso", 100);
            return false;
        }

        //Elimina l'utente indicato
        $db = new AA_AccountsDatabase();
        $sql = "DELETE FROM utenti where id='" . $user->GetID() . "' LIMIT 1";

        if ($db->Query($sql) === false) {
            AA_Log::Log(__METHOD__." - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }

        //AA_Log::LogAction($this->GetID(), "3,9," . $user->GetID(), Database::$lastQuery); //Old stuff

        return true;
    }

    //Elimina l'utente indicato
    public function DeleteUser($idUser,$bOnlyTrash=true)
    {
        //AA_Log::Log(__METHOD__, 100);

        if ($this->IsGuest()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido", 100);
            return false;
        }

        //Verifica se l'utente corrente può gestire gli utenti
        if (!$this->isCurrentUser()) {
            AA_Log::Log(__METHOD__." - utente corrente non autenticato.", 100);
            return false;
        }

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(__METHOD__." - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica se l'utente corrente può modificare l'utente indicato
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare l'utente indicato: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica che non sia l'utente corrente
        if ($this->GetID() == $user->GetID()) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può eliminare se stesso", 100);
            return false;
        }

        $db = new AA_AccountsDatabase();

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if(!$bOnlyTrash)
            {
                if(!$this->LegacyDeleteUser($user))
                {
                    return false;
                }    
            }
            else
            {
                $sql = "UPDATE utenti SET disable='1' where id='" . $user->GetID() . "' LIMIT 1";

                if ($db->Query($sql) === false) {
                    AA_Log::Log(__METHOD__." - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
                    return false;
                }
            }
        }

        //Elimina l'utente indicato
        if($bOnlyTrash)
        {
            $sql = "UPDATE ".static::AA_DB_TABLE." SET status='".static::AA_USER_STATUS_DELETED."' where id='" . $user->GetID() . "' LIMIT 1";

            if ($db->Query($sql) === false) {
                AA_Log::Log(__METHOD__." - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
                return false;
            }
    
            return true;    
        }

        $userimage=$user->GetImage();
        if($userimage !="")
        {
            $storage=AA_Storage::GetInstance($this);
            if($storage->IsValid())
            {
                if(!$storage->DelFile($userimage))
                {
                    AA_Log::Log(__METHOD__." - Errore: immagine utente (".$userimage.") non trovata." , 100);
                }
            }
        }

        $sql = "DELETE FROM ".static::AA_DB_TABLE." WHERE id='" . $user->GetID() . "' LIMIT 1";

        if ($db->Query($sql) === false) {
            AA_Log::Log(__METHOD__." - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }
    
        return true;
    }

    //Ripristina l'utente indicato
    public function ResumeUser($idUser)
    {
        //AA_Log::Log(__METHOD__, 100);

        if ($this->IsGuest()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido", 100);
            return false;
        }

        //Verifica se l'utente corrente può gestire gli utenti
        if (!$this->isCurrentUser()) {
            AA_Log::Log(__METHOD__." - utente corrente non autenticato.", 100);
            return false;
        }

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(__METHOD__." - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica se l'utente corrente può modificare l'utente indicato
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare l'utente indicato: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica che non sia l'utente corrente
        if ($this->GetID() == $user->GetID()) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare lo stato di se stesso.", 100);
            return false;
        }

        //Elimina l'utente indicato
        $db = new AA_AccountsDatabase();
        
        $sql = "UPDATE ".static::AA_DB_TABLE." SET status='".static::AA_USER_STATUS_DISABLED."' where id='" . $user->GetID() . "' LIMIT 1";

        if ($db->Query($sql) === false) {
            AA_Log::Log(__METHOD__." - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }
    
        return true;
    }

    //Resetta la password dell'utente associato alla email indicata e la spedisce alla casella indicata
    static public function ResetPassword($email, $bSendEmail = true)
    {
        //AA_Log::Log(get_class() . "->RecoverPassword($email)");

        $users = AA_User::LoadUsersFromEmail($email);

        if (is_array($users) && count($users) > 0) {
            $credenziali = "";
            $db = new AA_AccountsDatabase();

            foreach ($users as $user) {
                //Verifica che l'utente sia valido
                if (!$user->IsValid()) {
                    AA_Log::Log(__METHOD__."- Utente non trovato.", 100);
                }

                //Verifica se l'utente è disattivato
                if ($user->IsDisabled()) {
                    AA_Log::Log(__METHOD__."- Utente disattivato.", 100);
                }

                //Reimposta la password
                if ($user->IsValid() && !$user->IsDisabled()) 
                {
                    $newPwd = "A".substr(md5(uniqid(mt_rand(), true)), 0, 8)."a";
                    $struttura="";
                    if(AA_Const::AA_ENABLE_LEGACY_DATA)
                    {
                        if(static::$aResetPasswordEmailParams['bShowStruct'])
                        {
                            $struttura = $user->GetStruct()->GetAssessorato();
                            if ($user->GetStruct()->GetDirezione(true) != 0) $struttura .= " - " . $user->GetStruct()->GetDirezione();
                            if ($user->GetStruct()->GetServizio(true) != 0) $struttura .= " - " . $user->GetStruct()->GetServizio();    
                        }

                        //Reimposta le credenziali dell'utente
                        $query = "UPDATE utenti set passwd=MD5('" . $newPwd . "') where id='" . $user->GetID() . "' LIMIT 1";
                        if (!$db->Query($query)) 
                        {
                            AA_Log::Log(__METHOD__."- Errore durante l'aggiornamento della password per l'utente legacy: " . $user->GetUserName() . " - " . $db->GetErrorMessage()." - query: ".$query, 100);
                        }
                    }
                    
                    //Reimposta le credenziali dell'utente
                    $query = "UPDATE ".static::AA_DB_TABLE." set passwd='" . AA_Utils::password_hash($newPwd) . "' where id='" . $user->GetID() . "' LIMIT 1";
                    if (!$db->Query($query)) 
                    {
                        AA_Log::Log(__METHOD__."- Errore durante l'aggiornamento della password per l'utente: " . $user->GetUserName() . " - " . $db->GetErrorMessage(), 100);
                    } 
                    else 
                    {
                        if(static::$aResetPasswordEmailParams['bShowStruct']) $credenziali .= '<p>struttura: ' . $struttura;
                        else $credenziali .= '<p>';
                        $credenziali .= '
                        nome utente: <b>' . $user->GetUserName() . '</b>
                        password: <b>' . $newPwd . '</b></p>';
                    }
                }
            }

            if ($credenziali != "") {
                $oggetto = str_replace(array("%NOME%","%COGNOME%"),array($user->GetName(),$user->GetCognome()),static::$aResetPasswordEmailParams['oggetto']);

                //$corpo = static::$aResetPasswordEmailParams['incipit'].$credenziali.static::$aResetPasswordEmailParams['post'];
                //$firma = static::$aResetPasswordEmailParams['firma'];

                $corpo = str_replace(array("#www#","%NOME%","%COGNOME%"),array(AA_Const::AA_DOMAIN_NAME.AA_const::AA_WWW_ROOT,$user->GetName(),$user->GetCognome()),static::$aResetPasswordEmailParams['incipit']).$credenziali.str_replace(array("%NOME%","%COGNOME%"),array($user->GetName(),$user->GetCognome()),static::$aResetPasswordEmailParams['post']);
                $firma = str_replace("#www#",AA_Const::AA_DOMAIN_NAME.AA_const::AA_WWW_ROOT,static::$aResetPasswordEmailParams['firma']);

                if ($bSendEmail) 
                {
                    if (!SendMail(array($email), array(), $oggetto, nl2br($corpo) . $firma, array(), 1)) {
                        AA_Log::Log(__METHOD__."- Errore nell'invio della email a: " . $email, 100);
                        return false;
                    }
                }

                return true;
            } else {
                AA_Log::Log(__METHOD__."- Nessun utente valido trovato.", 100);
                return false;
            }
        } else {
            AA_Log::Log(__METHOD__."- Nessun utente valido trovato.", 100);
            return false;
        }

        return false;
    }

    //Resetta la password dell'utente associato alla email indicata e la spedisce alla casella indicata
    static public function SendCredentials($userId=0, $bSendEmail = true)
    {
        //AA_Log::Log(get_class() . "->SendCredentials($email)");

        $user = AA_User::LoadUser($userId);

        if ($user->IsValid()) 
        {
            $credenziali = "";
            $db = new AA_AccountsDatabase();

            //Verifica se l'utente è disattivato
            if ($user->IsDisabled()) {
                AA_Log::Log(__METHOD__."- Utente disattivato.", 100);
                return false;
            }

            //Verifica se l'utente ha una email valida
            if ($user->GetEmail()=="") {
                AA_Log::Log(__METHOD__."- Utente senza email valida.", 100);
                return false;
            }

            $newPwd = "A".substr(md5(uniqid(mt_rand(), true)), 0, 8)."a";
            $struttura="";
            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                if(static::$aSendCredentialsEmailParams['bShowStruct'])
                {
                    $struttura = $user->GetStruct()->GetAssessorato();
                    if ($user->GetStruct()->GetDirezione(true) != 0) $struttura .= " - " . $user->GetStruct()->GetDirezione();
                    if ($user->GetStruct()->GetServizio(true) != 0) $struttura .= " - " . $user->GetStruct()->GetServizio();    
                }

                //Reimposta le credenziali dell'utente
                $query = "UPDATE utenti set passwd=MD5('" . $newPwd . "') where id='" . $user->GetID() . "' LIMIT 1";
                if (!$db->Query($query)) 
                {
                    AA_Log::Log(__METHOD__."- Errore durante l'aggiornamento della password per l'utente: " . $user->GetUserName() . " - " . $db->GetErrorMessage()." - query: ".$query, 100);
                }
            }
            
            //Reimposta le credenziali dell'utente
            $query = "UPDATE ".static::AA_DB_TABLE." set passwd='" . AA_Utils::password_hash($newPwd) . "', lastnotify='".date("Y-m-d H:i:s")."' where id='" . $user->GetID() . "' LIMIT 1";
            if (!$db->Query($query)) {
                AA_Log::Log(__METHOD__."- Errore durante l'aggiornamento della password per l'utente: " . $user->GetUserName() . " - " . $db->GetErrorMessage(), 100);
            } else {
                if(static::$aSendCredentialsEmailParams['bShowStruct']) $credenziali .= '<br>struttura: ' . $struttura;
                $credenziali .= '
                nome utente: <b>' . $user->GetUserName() . '</b>
                password: <b>' . $newPwd . '</b>';
            }
        
            if ($credenziali != "") {
                $oggetto = str_replace(array("%NOME%","%COGNOME%"),array($user->GetName(),$user->GetCognome()),static::$aSendCredentialsEmailParams['oggetto']);

                $corpo = str_replace(array("#www#","%NOME%","%COGNOME%"),array(AA_Const::AA_DOMAIN_NAME.AA_const::AA_WWW_ROOT,$user->GetName(),$user->GetCognome()),static::$aSendCredentialsEmailParams['incipit']).$credenziali.str_replace(array("%NOME%","%COGNOME%"),array($user->GetName(),$user->GetCognome()),static::$aSendCredentialsEmailParams['post']);
                $firma = str_replace(array("#www#"),array(AA_Const::AA_DOMAIN_NAME.AA_const::AA_WWW_ROOT),static::$aSendCredentialsEmailParams['firma']);

                if ($bSendEmail) {
                    if (!SendMail(array($user->GetEmail()), array(), $oggetto, nl2br($corpo) . $firma, array(), 1)) 
                    {
                        AA_Log::Log(__METHOD__."- Errore nell'invio della email a: " . $user->GetEmail(), 100);
                        return false;
                    }

                    if(isset(static::$aSendCredentialsEmailParams['sendToUs']) && static::$aSendCredentialsEmailParams['sendToUs'])
                    {
                        //invio notifica a se stesso
                        if(!SendMail(array(AA_Const::AA_EMAIL_FROM),array(),"Notifica reinvio credenziali utente: ". $user->GetUserName()." - email: ".$user->GetEmail(),"Reinvio credenziali all'utente:<br>login: ".$user->GetUsername()."<br>email: ".$user->GetEmail()."<br>Data: ".date("Y-m-d").$firma))
                        {
                            AA_Log::Log(__METHOD__."- Errore nell'invio notifica", 100);
                        }
                    }
                }
                else
                {
                    AA_Log::Log(__METHOD__."- Set new password to: " . $newPwd, 100);
                }

                return true;
            } else {
                AA_Log::Log(__METHOD__."- Nessun utente valido trovato.", 100);
                return false;
            }
        }
        else 
        {
            AA_Log::Log(__METHOD__."- Nessun utente valido trovato.", 100);
            return false;
        }

        return false;
    }
}
