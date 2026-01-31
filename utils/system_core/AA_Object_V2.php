<?php
class AA_Object_V2
{
    //tabella oggetti
    static protected $AA_DBTABLE_OBJECTS = "aa_objects";
    public static function GetObjectsDbDataTable()
    {
        return static::$AA_DBTABLE_OBJECTS;
    }

    //identificativo
    protected $nId = 0;
    public function GetId()
    {
        return $this->nId;
    }
    protected function SetId($var = 0)
    {
        $this->nId = $var;

        $this->bChanged = true;

        return true;
    }

    //stato
    protected $nStatus = 1;
    public function GetStatus()
    {
        return $this->nStatus;
    }
    protected function SetStatus($var = 0)
    {
        if (($var & $this->nStatusMask) > 0) {
            $this->nStatus = $var & $this->nStatusMask;

            $this->bChanged = true;

            return true;
        }

        return false;
    }

    //aggiornamento
    protected $sAggiornamento = "";
    public function GetAggiornamento()
    {
        return $this->sAggiornamento;
    }

    //nome
    protected $sName = "";
    public function GetName()
    {
        return $this->sName;
    }
    protected function SetName($var = "")
    {
        $this->sName = $var;
    }

    //Descrizione
    protected $sDescr = "";
    public function GetDescr()
    {
        return $this->sDescr;
    }
    protected function SetDescr($var = "")
    {
        $this->sDescr = $var;
    }

    //maschera degli stati possibili
    protected $nStatusMask = AA_Const::AA_STATUS_ALL;
    public function GetStatusMask()
    {
        return $this->nStatusMask;
    }
    protected function SetStatusMask($var = AA_Const::AA_STATUS_ALL)
    {
        $this->nStatusMask = AA_Const::AA_STATUS_ALL & $var;

        $this->bChanged = true;

        return true;
    }

    //restituisce una rappresentazione testula dell'oggetto
    public function serialize()
    {
        return json_encode(get_object_vars($this));
    }
    
    //Abilita o disabilita le revisioni
    public function EnableRevision($var = true)
    {
        if ($var) $this->nStatusMask |= AA_Const::AA_STATUS_REVISIONATA;
        else $this->nStatusMask = AA_Const::AA_STATUS_ALL - AA_Const::AA_STATUS_REVISIONATA;
    }

    //Tabella dati
    const AA_DBTABLE_DATA = "";

    //id data object
    protected $nId_Data = 0;
    public function GetIdData()
    {
        return $this->nId_Data;
    }
    protected function SetIdData($var = 0)
    {
        if ($var >= 0) {
            $this->nId_Data = $var;

            $this->bChanged = true;

            return true;
        }

        return false;
    }

    //id data rev object
    protected $nId_Data_Rev = 0;
    public function GetIdDataRev()
    {
        return $this->nId_Data_Rev;
    }
    protected function SetIdDataRev($var = 0)
    {
        if ($var >= 0) {
            $this->nId_Data_Rev = $var;

            $this->bChanged = true;

            return true;
        }

        return false;
    }

    //Class
    protected $sClass = "AA_Object_V2";
    public function GetClass()
    {
        return $this->sClass;
    }
    protected function SetClass($var = "AA_Object_V2")
    {
        if (class_exists($var)) {
            $this->sClass = $var;

            $this->bChanged = true;

            return true;
        }

        return false;
    }

    //------------------- Log -----------------
    protected $sLog = "";
    public function GetLog($bFormated = true)
    {
        if (!$bFormated) return $this->sLog;

        return new AA_Object_Log($this->sLog);
    }

    //Aggiungi un log
    protected function AddLog($log = "", $actionType = "0", $user = null)
    {
        //Verifica utente valido
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if($this->sLog != "") $logs=explode("\n",$this->sLog);
        else $logs=array();

        $logs[]=Date("Y-m-d H:i:s") . "|" . $user->GetUsername() . "|" . $actionType . "|" . $log;

        if(sizeof($logs)>1000)
        {
            //lascia solo gli ultimi 100 log
            $logs=array_slice($logs, -1000);
        }

        $this->sLog = implode("\n",$logs);
    }

    //resetta il log
    protected function ResetLog()
    {
        $this->sLog = "";
    }
    #-----------------------------------------------------------

    //Gestione struttura
    protected $oStruct = null;
    public function GetStruct()
    {
        if ($this->oStruct instanceof AA_Struct) return $this->oStruct;
        else return AA_Struct::GetStruct(0, 0, 0);
    }
    public function SetStruct($var = null)
    {
        if ($var instanceof AA_Struct) {
            $this->oStruct = $var;

            $this->bChanged = true;

            return true;
        }

        return false;
    }
    #-----------------------------------------------------

    //esportazione in csv
    public function ToCsv($separator="|",$bHeader=true,$bDetail=true,$bToBrowser=true)
    {
        $csv="";
        $header="";
        $rowSeparator="\n";

        if ($bHeader)
        {
            $header="id";
            if($bDetail)
            {
                $header.=$separator."aggiornamento".$separator."stato".$separator."struttura";
            }

            $header.=$separator."nome";
            $header.=$separator."descrizione";
            $header.=$this->CsvDataHeader($separator);
        }

        if(!empty($header))$csv=$header.$rowSeparator;

        $csv.=$this->GetId();
        if($bDetail)
        {
            $csv.=$separator.$this->GetAggiornamento();
            $csv.=$separator.$this->GetStatus();
            $struct= $this->GetStruct();
            $struct_text="Nessuna";
            if(!empty($struct->GetAssessorato(true))) $struct_text = (String) $struct->GetAssessorato();
            if(!empty($struct->GetDirezione(true))) $struct_text .= " - ".(String) $struct->GetDirezione();
            if(!empty($struct->GetServizio(true))) $struct_text .= " - ".(String) $struct->GetServizio();
            $csv.=$separator.$struct_text;
        }

        $csv.=$separator.str_replace("\n",' ',$this->GetName());
        $csv.= $separator.str_replace("\n",' ',$this->GetDescr());
        
        $data=$this->CsvData($separator);
        if(!empty($data)) $csv.= $data;

        if($bToBrowser)
        {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="export.csv"');
            die($csv);
        }

        return $csv;
    }

    //da specializzare
    protected function CsvDataHeader($separator="|")
    {
        return "";
    }

    //da specializzare
    protected function CsvData($separator= "|")
    {
        return "";
    }
    //--------------------------------------------

    //Flag di variazione
    protected $bChanged = false;
    public function IsChanged()
    {
        return $this->bChanged;
    }
    protected function SetChanged($var = true)
    {
        if ($var == true) $this->bChanged = true;
        else $this->bChanged = false;
    }

    protected static function SaveToDb($object = null, $user = null, $bSaveData = false)
    {
        //AA_Log::Log(__METHOD__);

        if (!($object instanceof AA_Object_V2)) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        if (!$object->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        if (!$object->IsChanged()) return true;

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        //Verifica solo se oggetto diretto di classe "AA_Object_V2" 
        $object_class = get_class($object);

        //Verifica permessi
        if ($object->GetId() > 0) //Oggetto esistente
        {
            $originalObject = new $object_class($object->GetId(), $user);
            if ($originalObject->isValid()) {
                $orgPerms = $originalObject->GetUserCaps($user);
                $perms = $object->GetUserCaps($user);

                $objStatus = $object->GetStatus();
                $orgObjStatus = $originalObject->GetStatus();

                //Cambio di stato
                if ($objStatus != $orgObjStatus) {
                    if (($orgObjStatus & AA_Const::AA_STATUS_BOZZA) > 0) {
                        //pubblicazione
                        if (($objStatus & AA_Const::AA_STATUS_PUBBLICATA) > 0 && (($perms & AA_Const::AA_PERMS_WRITE) == 0 || ($orgPerms & AA_Const::AA_PERMS_PUBLISH) == 0)) {
                            AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                            return false;
                        }

                        //cestinazione eliminazione
                        if (($objStatus & AA_Const::AA_STATUS_CESTINATA) > 0 && (($perms & AA_Const::AA_PERMS_DELETE) == 0 || ($orgPerms & AA_Const::AA_PERMS_DELETE) == 0)) {
                            AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                            return false;
                        }
                    } else {
                        //cestinazione eliminazione
                        if (($objStatus & AA_Const::AA_STATUS_CESTINATA) > 0 && (($perms & AA_Const::AA_PERMS_DELETE) == 0 || ($orgPerms & AA_Const::AA_PERMS_DELETE) == 0)) {
                            AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                            return false;
                        }

                        //revisione
                        if (($objStatus & AA_Const::AA_STATUS_REVISIONATA) > 0 && (($perms & AA_Const::AA_PERMS_WRITE) == 0 || ($orgPerms & AA_Const::AA_PERMS_WRITE) == 0)) {
                            AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                            return false;
                        }

                        //pubblicazione revisioni
                        if (($objStatus & AA_Const::AA_STATUS_REVISIONATA) == 0 && ($orgObjStatus & AA_Const::AA_STATUS_REVISIONATA) > 0  && (($perms & AA_Const::AA_PERMS_PUBLISH) == 0 || ($orgPerms & AA_Const::AA_PERMS_PUBLISH) == 0)) {
                            AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                            return false;
                        }
                    }
                } else {
                    //Modifica generica
                    if (($perms & AA_Const::AA_PERMS_WRITE) == 0 || ($orgPerms & AA_Const::AA_PERMS_WRITE) == 0) {
                        AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                        return false;
                    }
                }
            } else {
                AA_Log::Log(__METHOD__ . " - Oggetto originale non valido.", 100);
                return false;
            }

            //Tiene conto della revisione
            if ($object->GetDbDataTable() != "" && ($object->GetStatusMask() & AA_Const::AA_STATUS_REVISIONATA) > 0 && $object->GetId() > 0) {
                //Revisione
                if (($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) > 0 && $object->GetIdDataRev() == 0) {
                    $newIdData = $object->CloneData($object->nId_Data);
                    if ($newIdData > 0) {
                        $object->nId_Data_Rev = $object->nId_Data;
                        $object->nId_Data = $newIdData;
                    } else {
                        AA_Log::Log(__METHOD__ . " - ERRORE nel revisionamento dei dati", 100);
                        return false;
                    }
                }

                //pubblicazione Revisione
                if (($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) == 0 && $object->GetIdDataRev() > 0) {
                    if ($object->nId_Data > 0) {
                        if ($object->DeleteData($object->nId_Data)) {
                            $object->nId_Data = $object->nId_Data_Rev;
                            $object->nId_Data_Rev = 0;
                        } else {
                            AA_Log::Log(__METHOD__ . " - ERRORE nell'eliminazione dei dati clonati.", 100);
                            return false;
                        }
                    }
                }
            }
        } else {
            $object->SetStatus(AA_Const::AA_STATUS_BOZZA);
            $perms = $object->GetUserCaps($user);

            //Modifica generica
            if (($perms & AA_Const::AA_PERMS_WRITE) == 0) {
                AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                return false;
            }

            /*
            //cestinazione
            if(($objStatus&AA_Const::AA_STATUS_CESTINATA) > 0 && ($perms&AA_Const::AA_PERMS_DELETE) == 0)
            {
                AA_Log::Log(__METHOD__." - L'utente corrente: ".$user->GetUsername()." non ha sufficienti permessi per salvare le modifiche all'oggetto: ".$object->GetName(),100);
                return false;
            }

            //pubblicazione
            if(($objStatus&AA_Const::AA_STATUS_PUBBLICATA) > 0 && ($perms&AA_Const::AA_PERMS_PUBLISH) == 0)
            {
                AA_Log::Log(__METHOD__." - L'utente corrente: ".$user->GetUsername()." non ha sufficienti permessi per salvare le modifiche all'oggetto: ".$object->GetName(),100);
                return false;
            }*/
        }

        $db = new AA_Database();
        $where = "";
        $query = "";
        $sep = "";

        //Aggiorna la tabella dati se è impostata
        if ($bSaveData) {
            if (!$object->SaveData()) {
                AA_Log::Log(__METHOD__ . " - Errore nel salvataggio dei dati collegati all'oggetto.", 100);
                return false;
            }
        }

        //Salvataggio sul db        
        if ($object->GetId() == 0) {
            $query = "INSERT INTO " . static::$AA_DBTABLE_OBJECTS . " SET ";
            $where = "";
        } else {
            $query = "UPDATE " . static::$AA_DBTABLE_OBJECTS . " SET ";
            $where = " WHERE " . static::$AA_DBTABLE_OBJECTS . ".id='" . addslashes($object->GetId()) . "' LIMIT 1";
        }

        $struct = $object->GetStruct();

        //Imposta un nome di default se non ce l'ha
        if($object->GetName() =="") $object->SetName("Nuovo oggetto da rinominare");

        $query .= " id_data='" . $object->GetIdData() . "'";
        $query .= ", id_data_rev='" . $object->GetIdDataRev() . "'";
        $query .= ", status='" . $object->GetStatus() . "'";
        $query .= ", nome='" . addslashes(mb_substr($object->GetName(),0,250)) . "'";
        $query .= ", descrizione='" . addslashes($object->GetDescr()) . "'";
        $query .= ", id_assessorato='" . $struct->GetAssessorato(true) . "'";
        $query .= ", id_direzione='" . $struct->GetDirezione(true) . "'";
        $query .= ", id_servizio='" . $struct->GetServizio(true) . "'";
        $query .= ", class='" . $object_class . "'";
        $query .= ", logs='" . addslashes($object->GetLog(false)) . "'";

        //AA_Log::Log(__METHOD__ . "query: " . $query, 100);

        if (!$db->Query($query . $where)) {
            AA_Log::Log(__METHOD__ . " - Errore nell'aggiornamento al db - " . $db->GetErrorMessage(), 100);
            return false;
        }

        if ($object->GetId() == 0) {
            $object->SetId($db->GetLastInsertId());
        }

        $object->SetChanged(false);
        return true;
    }

    //funzione di clonazione dei dati collegati
    protected function CloneData($idData = 0, $user = null)
    {
        if ($this->GetDbDataTable() != "" && ($idData == $this->nId_Data || $idData == $this->nId_Data_Rev) && $idData > 0) {
            $select = "SELECT * FROM " . $this->GetDbDataTable() . " ";
            $where = " WHERE id='" . addslashes($idData) . "' LIMIT 1";

            $db = new AA_Database();
            if (!$db->Query($select . $where)) {
                AA_Log::Log(__METHOD__ . " - Errore nella clonazione dei dati - " . $db->GetErrorMessage(), 100);
                return 0;
            }

            if ($db->GetAffectedRows() == 0) {
                AA_Log::Log(__METHOD__ . " - Errore nella clonazione dei dati, dati non trovati ($idData)", 100);
                return 0;
            }

            $query = "INSERT INTO " . $this->GetDbDataTable() . " SET ";
            $rs = $db->GetResultSet();
            $sep = "";
            foreach ($this->GetDbBindings() as $prop => $dbField) {
                $query .= $sep . $dbField . " = '" . addslashes($rs[0][$dbField]) . "'";
                $sep = ",";
            }

            if (!$db->Query($query)) {
                AA_Log::Log(__METHOD__ . " - Errore nella clonazione dei dati - " . $db->GetErrorMessage(), 100);
                return 0;
            }

            return $db->GetLastInsertId();
        }

        return 0;
    }

    //funzione di eliminazione dei dati collegati
    protected function DeleteData($idData = 0, $user = null)
    {
        if ($this->GetDbDataTable() != "" && ($idData == $this->nId_Data || $idData == $this->nId_Data_Rev) && $idData > 0) {
            $query = "DELETE FROM " . $this->GetDbDataTable() . " WHERE id='" . addslashes($idData) . "' LIMIT 1";

            $db = new AA_Database();
            if (!$db->Query($query)) {
                AA_Log::Log(__METHOD__ . " - Errore nella eliminazione dei dati - " . $db->GetErrorMessage(), 100);
                return false;
            }

            return true;
        }

        return false;
    }

    //Funzione di salvataggio dei dati legati all'oggetto
    protected function SaveData()
    {
        if ($this->GetDbDataTable() != "") {
            if ($this->nId_Data_Rev > 0) {
                $query = "UPDATE " . $this->GetDbDataTable() . " SET ";
                $where = " WHERE " . $this->GetDbDataTable() . ".id = " . $this->nId_Data_Rev . " LIMIT 1";
            } else {
                //Primo inserimento
                if ($this->GetIdData() == 0) {
                    $query = "INSERT INTO " . $this->GetDbDataTable() . " SET ";
                    $where = "";
                } else {
                    $query = "UPDATE " . $this->GetDbDataTable() . " SET ";
                    $where = " WHERE " . $this->GetDbDataTable() . ".id = " . $this->nId_Data . " LIMIT 1";
                }
            }

            $sep = "";
            foreach ($this->GetDbBindings() as $prop => $dbField) {
                $query .= $sep . $dbField . " = '" . addslashes($this->GetProp($prop)) . "'";
                $sep = ",";
            }

            //AA_Log::Log(__METHOD__."query: ".$query,100);

            $db = new AA_Database();

            //Aggiorna tabella dati
            if (!$db->Query($query . $where)) {
                AA_Log::Log(__METHOD__ . " - Errore nell'aggiornamento della tabella dati - " . $db->GetErrorMessage() . " - Query:" . $query, 100);
                return false;
            }

            if (($this->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) == 0 && $this->GetIdData() == 0) {
                $this->SetIdData($db->GetLastInsertId());
            }
        }

        return true;
    }

    protected function Save($user = null, $bForce = false, $bSaveData = false)
    {
        if ($bForce) $this->bChanged = true;

        return static::SaveToDb($this, $user, $bSaveData);
    }

    //pubblica
    public function Publish($user = null, $bSaveData = false)
    {
        //Verifica se l'oggetto è valido
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        if (($this->GetUserCaps($user) & AA_Const::AA_PERMS_PUBLISH) == 0) {
            AA_Log::Log(__METHOD__ . " - Utente corrente non ha i permessi per pubblicare l'oggetto.", 100);
            return false;
        }

        $oldStatus = $this->GetStatus();
        $oldLog = $this->GetLog(false);
        $this->SetStatus(AA_Const::AA_STATUS_PUBBLICATA);
        $this->AddLog("Pubblicazione", AA_Const::AA_OPS_PUBLISH, $user);
        if (!$this->Save($user, true, $bSaveData)) {
            $this->nStatus = $oldStatus;
            $this->sLog = $oldLog;
            $this->bChanged = false;

            return false;
        }

        return true;
    }

    //Aggiungi nuovo oggetto
    static public function AddNew($object = null, $user = null, $bSaveData = false)
    {
        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        if (!($object instanceof AA_Object_V2)) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido", 100);
            return false;
        }

        $object->SetId(0);
        $object->AddLog("Inserimento", AA_Const::AA_OPS_ADDNEW, $user);

        if (!$object->Save($user, true, $bSaveData)) {
            return false;
        }

        return true;
    }

    protected $aTemplateViewProps=array();
    public function SetTemplateViewProps($props=null)
    {
        if(is_array($props))
        {
            $this->aTemplateViewProps=array();
            $keys=array_keys($this->aProps);
            foreach($keys as $key=>$value)
            {
                if(!empty($props[$key])) $this->aTemplateViewProps[$key]=$props[$key];
            }
        }
    }
    public function GetTemplateViewProps()
    {
        if(empty($this->aTemplateViewProps)) $this->SetDefaultTemplateViewProps();
        
        return $this->aTemplateViewProps;
    }

    protected function SetDefaultTemplateViewProps()
    {
        $this->aTemplateViewProps=array();
        foreach($this->aProps as $key=>$value)
        {
            $this->aTemplateViewProps[$key]=array("label"=>$key,"visible"=>true);
        }
    }

    //template view
    protected $oTemplateView=null;
    public function GetTemplateView($bRefresh=false)
    {
        if($this->oTemplateView !=null && !$bRefresh) return $this->oTemplateView;

        $this->oTemplateView=$this->GetDefaultTemplateView();
        
        return $this->oTemplateView;
    }

    public function GetDefaultTemplateView()
    {
        $oTemplateView=new AA_GenericTemplate_Grid();
        $templateAreas=array();
        
        if(empty($this->aTemplateViewProps)) $this->SetDefaultTemplateViewProps();

        foreach($this->aTemplateViewProps as $propName=>$propConfig)
        {
            if($propConfig['visible'])
            {
                $templateAreas[]=$propName;
                $class='';
                if(!empty($propConfig['class'])) $class=$propConfig['class'];
                else $class='aa-templateview-prop-'.$propName;

                    $value="";
                if(empty($propConfig['function'])) $value = "<span class='".$class."'>" . $this->GetProp($propName) . "</span>";
                else 
                {
                    if(method_exists($this,$propConfig['function'])) $value = "<div class='".$class."'>".$this->{$propConfig['function']}()."</div>";
                    else $value = "<span class='".$class."'>n.d.</span>";
                }

                if(!$oTemplateView->AddCellToGrid(new AA_JSON_Template_Template("", array(
                    "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
                    "data" => array("title" => "".$propConfig['label'].":", "value" => $value),
                    "css" => array("border-bottom" => "1px solid #dadee0 !important","width"=>"auto !important","height"=> "auto !important")
                )), $propName))
                {
                    AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile aggiungere la cella alla template view per la proprietà: " . $propName, 100);
                }
            }
        }

        if(isset($this->aTemplateViewProps['__areas'])) $templateAreas=$this->aTemplateViewProps['__areas'];
        $oTemplateView->SetTemplateAreas($templateAreas);
      
        if(isset($this->aTemplateViewProps['__cols'])) $oTemplateView->SetTemplateCols($this->aTemplateViewProps['__cols']);

        if(isset($this->aTemplateViewProps['__rows'])) $oTemplateView->SetTemplateRows($this->aTemplateViewProps['__rows']);

        return $oTemplateView;
    }

    public function SetTemplateView($var = null)
    {
        if($var instanceof AA_JSON_Template_Generic)
        {
            $this->oTemplateView=$var;
        }
    }

    //Aggiorna
    public function Update($user = null, $bSaveData = true, $logMsg = "")
    {
        //Verifica se l'oggetto è valido
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        $oldStatus = $this->GetStatus();
        $oldLog = $this->GetLog(false);
        $log = "modifica";

        if (($this->nStatusMask & AA_Const::AA_STATUS_REVISIONATA) > 0) {
            if (($oldStatus & AA_Const::AA_STATUS_PUBBLICATA) > 0 && ($this->GetUserCaps($user) &  AA_Const::AA_PERMS_PUBLISH) == 0) {
                $this->nStatus = $oldStatus | AA_Const::AA_STATUS_REVISIONATA;
                $log .= " (revisione)";
            }
        }

        if ($logMsg != "") $log .= " - " . $logMsg;

        $this->AddLog($log, AA_Const::AA_OPS_UPDATE, $user);
        if (!$this->Save($user, true, $bSaveData)) {
            $this->nStatus = $oldStatus;
            $this->sLog = $oldLog;

            return false;
        }

        return true;
    }

    //cestina
    public function Trash($user = null, $bSaveData = false)
    {
        //Verifica se l'oggetto è valido
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        $oldStatus = $this->GetStatus();
        $oldLog = $this->GetLog(false);

        $this->nStatus |= AA_Const::AA_STATUS_CESTINATA;

        $this->AddLog("Cestina", AA_Const::AA_OPS_TRASH, $user);
        if (!$this->Save($user, true, $bSaveData)) {
            $this->nStatus = $oldStatus;
            $this->sLog = $oldLog;

            return false;
        }

        return true;
    }

    //riassegna
    public function Reassign($oStruct = null, $user = null, $bSaveData = false)
    {
        //Verifica se l'oggetto è valido
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        //Verifica se la struttura è valida
        if (!($oStruct instanceof AA_Struct)) {
            AA_Log::Log(__METHOD__ . " - Struttura non valida.", 100);
            return false;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        $oldStruct = $this->GetStruct();
        $oldLog = $this->GetLog(false);

        $this->oStruct = $oStruct;

        $this->AddLog("Riassegna", AA_Const::AA_OPS_REASSIGN, $user);
        if (!$this->Save($user, true, $bSaveData)) {
            $this->sLog = $oldLog;
            $this->oStruct = $oldStruct;

            return false;
        }

        return true;
    }

    //riassegna
    public function Resume($user = null, $bSaveData = false)
    {
        //Verifica se l'oggetto è valido
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        $oldStatus = $this->GetStatus();
        $oldLog = $this->GetLog(false);

        if (($oldStatus & AA_Const::AA_STATUS_CESTINATA) > 0) {
            $this->SetStatus($oldStatus - AA_Const::AA_STATUS_CESTINATA);
        }

        $this->AddLog("Ripristina", AA_Const::AA_OPS_RESUME, $user);
        if (!$this->Save($user, true, $bSaveData)) {
            $this->nStatus = $oldStatus;
            $this->sLog = $oldLog;
            $this->bChanged = false;

            return false;
        }

        return true;
    }

    //Delete
    public function Delete($user = null)
    {
        //Verifica se l'oggetto è valido
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        //Verifica permessi
        if ($this->GetId() > 0) {
            $class = get_class($this);
            $originalObject = new $class($this->GetId(), $user);
            if ($originalObject->GetId() > 0) {
                $perms = $originalObject->GetUserCaps($user);
                if (($perms & AA_Const::AA_PERMS_DELETE) > 0) {
                    //Elimina i dati associati
                    if ($this->nId_Data > 0) {
                        if (!$this->DeleteData($this->nId_Data)) {
                            return false;
                        }
                    }

                    if ($this->nId_Data_Rev > 0) {
                        if (!$this->DeleteData($this->nId_Data_Rev)) {
                            return false;
                        }
                    }

                    $db = new AA_Database();
                    $query = "DELETE from " . static::$AA_DBTABLE_OBJECTS . " WHERE id = '" . $this->GetId() . "' LIMIT 1";
                    if (!$db->Query($query)) {
                        AA_Log::Log(__METHOD__ . " - Errore durante l'eliminazione dell'oggetto (" . $this->GetId() . ") - " . $db->GetErrorMessage(), 100);
                        return false;
                    }

                    return true;
                } else {
                    AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha i permessi per eliminare l'oggetto (" . $this->GetId() . ")", 100);
                    return false;
                }
            } else {
                AA_Log::Log(__METHOD__ . " - Oggetto non persistente o non trovato (" . $this->GetId() . ")", 100);
                return false;
            }
        } else return true;
    }

    //Verifica dei permessi
    public function GetUserCaps($user = null)
    {
        $perms = AA_Const::AA_PERMS_NONE;

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsSuperUser()) return AA_Const::AA_PERMS_ALL;

        //L'utente guest non vede le bozze o le schede cestinate
        if ($user->IsGuest() && ($this->nStatus & (AA_Const::AA_STATUS_BOZZA | AA_Const::AA_STATUS_CESTINATA)) > 0) return $perms;

        //Le pubblicate sono visibili a tutti
        if (($this->nStatus & (AA_Const::AA_STATUS_PUBBLICATA | AA_Const::AA_STATUS_CESTINATA)) == AA_Const::AA_STATUS_PUBBLICATA) {
            $perms += AA_Const::AA_PERMS_READ;
            if ($user->IsGuest()) return $perms;
        }

        $samestruct = true;

        $thisStruct = $this->GetStruct();
        $userStruct = $user->GetStruct();
        if ($userStruct->GetServizio(true) > 0 && $thisStruct->GetServizio(true) != $userStruct->GetServizio(true)) {
            $samestruct = false;
        } else {
            if ($userStruct->GetDirezione(true) > 0 && $thisStruct->GetDirezione(true) != $userStruct->GetDirezione(true)) {
                $samestruct = false;
            } else {
                if ($userStruct->GetAssessorato(true) > 0 && $thisStruct->GetAssessorato(true) != $userStruct->GetAssessorato(true)) {
                    $samestruct = false;
                }
            }
        }

        if ($samestruct && ($this->nStatus & AA_Const::AA_STATUS_BOZZA) > 0) {
            $perms = AA_Const::AA_PERMS_READ | AA_Const::AA_PERMS_WRITE | AA_Const::AA_PERMS_DELETE;
            if ($user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN) $perms = AA_Const::AA_PERMS_ALL;
        }

        if ($samestruct && ($this->nStatus & AA_Const::AA_STATUS_PUBBLICATA) > 0) {
            $perms = AA_Const::AA_PERMS_READ | AA_Const::AA_PERMS_WRITE;
            if ($user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN) $perms = AA_Const::AA_PERMS_ALL;
        }

        return $perms;
    }

    //Funzione di caricamento
    static public function Load($id = 0, $user = null, $bLoadData = true, $bCheckPerms=true)
    {
        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        $db = new AA_Database();
        $query = "SELECT * from " . static::$AA_DBTABLE_OBJECTS . " WHERE id ='" . addslashes($id) . "' LIMIT 1";
        if (!$db->Query($query)) 
        {
            AA_Log::Log(__METHOD__ . " - Errore: " . $db->GetErrorMessage(), 100);
            $object = new AA_Object_V2(0, $user, false);
            $object->bValid = false;
            return $object;
        }

        if ($db->GetAffectedRows() > 0) 
        {
            $rs = $db->GetResultSet();

            $objectClass = "AA_Object_V2";
            if (class_exists($rs[0]['class'])) {
                $objectClass = $rs[0]['class'];
            }

            $object = new $objectClass(0, $user);
            if(AA_Const::AA_ENABLE_LEGACY_DATA) $object->oStruct = AA_Struct::GetStruct($rs[0]['id_assessorato'], $rs[0]['id_direzione'], $rs[0]['id_servizio']);
            else $object->oStruct = AA_Struct::GetStruct(0,0,0);
            $object->nStatus = $rs[0]['status'];
            $object->sClass = $rs[0]['class'];
            $object->sAggiornamento = $rs[0]['aggiornamento'];
            $object->sName = $rs[0]['nome'];
            $object->sDescr = $rs[0]['descrizione'];
            $object->nId = $rs[0]['id'];
            $object->nId_Data = $rs[0]['id_data'];
            $object->nId_Data_Rev = $rs[0]['id_data_rev'];
            $object->sLog = $rs[0]['logs'];
            $object->bValid = true;

            //Carica i dati collegati
            if ($object->sDbDataTable != "" && $bLoadData && $object->nId > 0) {
                if (!$object->LoadData()) {
                    AA_Log::Log(__METHOD__ . " - Errore: dati non caricati.", 100);
                    $object->bValid = false;
                } else $object->bValid = true;
            }

            if($object->bValid && $bCheckPerms)
            {
                $perms = $object->GetUserCaps($user);
                if (($perms & AA_Const::AA_PERMS_READ) == 0) {
                    AA_Log::Log(__METHOD__ . " - Errore: l'utente corrente non ha i permessi per visualizzare l'oggetto. ".print_r($object,true), 100);
                    $object->bValid = false;
                    $object->nId = 0;
                    $object->nId_Data = 0;
                    $object->nId_Data_Rev = 0;
                    $object->sLog = "";
                    return $object;
                }
    
                if (($perms & AA_Const::AA_PERMS_WRITE) == 0) {
                    //AA_Log::Log(__METHOD__." - readonly: ".$perms." ".print_r($user,true),100);
                    $object->bReadOnly = true;
                } else {
                    //AA_Log::Log(__METHOD__." - writable: ".print_r($object,true),100);
                    $object->bReadOnly = false;
                    //AA_Log::Log(__METHOD__." - oggetto: ".print_r($object,true),100);
                }
            }
        } 
        else 
        {
            AA_Log::Log(__METHOD__ . " - Errore: oggetto non trovato ($id)", 100);
            $object = new AA_Object_V2(0, $user, false);
            $object->bValid = false;
        }

        //AA_Log::Log(__METHOD__." - oggetto: ".print_r($object,true),100);
        return $object;
    }

    //Carica  i dati collegati all'oggetto.
    protected function LoadData($user = null)
    {
        if ($this->sDbDataTable != "" && $this->bValid && $this->nId > 0 && ($this->nId_Data > 0 || $this->nId_Data_Rev > 0)) {
            //Verifica utente
            if ($user instanceof AA_User) {
                if (!$user->isCurrentUser() || $user->IsGuest()) {
                    $user = AA_User::GetCurrentUser();
                }
            } else $user = AA_User::GetCurrentUser();

            if (($this->nStatus & AA_Const::AA_STATUS_REVISIONATA) > 0 && !$this->IsReadOnly() && $this->nId_Data_Rev > 0) {
                $query = "SELECT * FROM " . $this->sDbDataTable . " WHERE id = " . $this->nId_Data_Rev . " LIMIT 1";
            } else {
                $query = "SELECT * FROM " . $this->sDbDataTable . " WHERE id = " . $this->nId_Data . " LIMIT 1";
            }

            $db = new AA_Database();
            if (!$db->Query($query)) {
                AA_Log::Log(__METHOD__ . " - Errore: " . $db->GetErrorMessage(), 100);
                return false;
            }

            if ($db->GetAffectedRows() > 0) {
                $data = $db->GetResultSet();
                foreach ($this->GetDbBindings() as $prop => $db_field) {
                    $this->aProps[$prop] = $data[0][$db_field];
                }
            } else {
                AA_Log::Log(__METHOD__ . " - Errore nessun dato trovato. " . $query, 100);
                return false;
            }
        }

        return true;
    }

    //Flag di validità
    protected $bValid = false;
    public function IsValid()
    {
        return $this->bValid;
    }

    //Flag di sola lettura
    protected $bReadOnly = true;
    public function  IsReadOnly()
    {
        return $this->bReadOnly;
    }

    //Costruttore standard
    public function __construct($id = 0, $user = null, $bLoadData = true, $bCheckPerms=true)
    {
        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($id > 0) 
        {
            $db = new AA_Database();
            $query = "SELECT * from " . static::$AA_DBTABLE_OBJECTS . " WHERE id ='" . addslashes($id) . "' LIMIT 1";
            if (!$db->Query($query)) 
            {
                AA_Log::Log(__METHOD__ . " - Errore: " . $db->GetErrorMessage(), 100);
                return;
            }

            if ($db->GetAffectedRows() > 0) 
            {
                $rs = $db->GetResultSet();
                $this->nStatus = $rs[0]['status'];
                if(AA_Const::AA_ENABLE_LEGACY_DATA) $this->oStruct = AA_Struct::GetStruct($rs[0]['id_assessorato'], $rs[0]['id_direzione'], $rs[0]['id_servizio']);
                else $this->oStruct = AA_Struct::GetStruct(0,0,0);
                $this->sClass = $rs[0]['class'];
                $this->sAggiornamento = $rs[0]['aggiornamento'];
                $this->sName = $rs[0]['nome'];
                $this->sDescr = $rs[0]['descrizione'];
                $this->nId = $rs[0]['id'];
                $this->nId_Data = $rs[0]['id_data'];
                $this->nId_Data_Rev = $rs[0]['id_data_rev'];
                $this->sLog = $rs[0]['logs'];
                $this->bValid = true;
                
                if($bLoadData && $this->sDbDataTable != "" ) $this->LoadData($user);

                if($bCheckPerms || $user->IsGuest())
                {
                    $perms=$this->GetUserCaps($user);
                    if(($perms&AA_Const::AA_PERMS_WRITE) > 0) $this->bReadOnly=false;
                    if(($perms & AA_Const::AA_PERMS_READ) == 0)
                    {
                        $this->nStatus = AA_Const::AA_STATUS_NONE;
                        $this->oStruct = AA_Struct::GetStruct(0,0,0);
                        $this->sClass = "AA_Object_V2";
                        $this->sAggiornamento = "";
                        $this->sName = "";
                        $this->sDescr = "";
                        $this->nId = 0;
                        $this->nId_Data = 0;
                        $this->nId_Data_Rev = 0;
                        $this->sLog = "";
                        $this->aProps=array();
                        $this->bValid=false;
                    } 
                }
            }

            /*$object = static::Load($id, $user, $bLoadData,false);

            $perms=$this->GetUserCaps($user);

            //AA_Log::Log(__METHOD__." - oggetto: ".print_r($object,true),100);

            if ($object->isValid()) {
                $this->nId = $id;
                $this->oStruct = $object->oStruct;
                $this->nStatus = $object->nStatus;
                $this->sClass = $object->sClass;
                $this->sAggiornamento = $object->sAggiornamento;
                $this->sName = $object->sName;
                $this->sDescr = $object->sDescr;
                $this->nId_Data = $object->nId_Data;
                $this->nId_Data_Rev = $object->nId_Data_Rev;
                $this->sLog = $object->sLog;
                $this->bValid = true;
                $this->bReadOnly = $object->bReadOnly;
                if($bLoadData) $this->aProps=$object->aProps;

                //AA_Log::Log(__METHOD__." - oggetto: ".print_r($object,true)." - this: ".print_r($this,true),100);
            } else {
                AA_Log::Log(__METHOD__ . " - Errore oggetto non valido - ".print_r($object,true), 100);
            }*/
        } else {
            $this->bValid = true;
        }
    }

    //Proprietà
    protected $aProps = array();
    public function GetProp($prop = "")
    {
        if ($prop != "" && $prop != null) {
            if (in_array($prop, array_keys($this->aProps))) return $this->aProps[$prop];
        }
        return "";
    }
    public function SetProp($prop = "", $val = null)
    {
        if ($prop != "" && $prop != null) {
            if (in_array($prop, array_keys($this->aProps))) {
                $this->aProps[$prop] = $val;
                return true;
            }
        }

        return false;
    }

    //Aggiunge una proprietà all'oggetto ed eventualmente la associa ad un campo db
    public function AddProp($prop = "", $initValue="",$dbBind="")
    {
        if ($prop != "" && $prop != null) {
            if (in_array($prop, array_keys($this->aProps))) {
                if($dbBind=="")
                {
                    if(isset($this->aDbBindings[$prop])) unset($this->aDbBindings[$prop]);
                }
                else $this->aDbBindings[$prop]=$dbBind;
                
            }
            else
            {
                $this->aProps[$prop]=$initValue;
                if($dbBind != "")
                {
                    $this->aDbBindings[$prop]=$dbBind;
                }
            }

            return true;
        }

        return false;
    }
    public function GetProps()
    {
        return $this->aProps;
    }

    //Parsing dei dati
    public function Parse($data = array(), $bOnlyData = false)
    {
        //Nome
        foreach ($data as $key => $value) {
            if (!$bOnlyData) {
                if ($key == "nome" || $key == "name") $this->SetName($value);
                if ($key == "descrizione" || $key == "descr") $this->SetDescr($value);
            }

            if (isset($this->aProps[$key])) $this->aProps[$key] = $value;
        }
    }

    //Db binding
    protected $aDbBindings = array();
    public function SetBind($prop = "", $dbField = "", $bAddProp = true)
    {
        if ($prop != "" && $prop != null && $dbField != "" && $dbField != null) {
            if (in_array($prop, array_keys($this->aProps))) {
                $this->aDbBindings[$prop] = $dbField;
                return true;
            } else {
                if ($bAddProp) $this->aProps[$prop] = "";
                $this->aDbBindings[$prop] = $dbField;

                return true;
            }
        }

        return false;
    }
    public function GetBind($prop = "")
    {
        if ($prop != "" && $prop != null) {
            if (in_array($prop, array_keys($this->aDbBindings))) {
                return $this->aDbBindings[$prop];
            }
        }

        return "";
    }
    public function GetDbBindings()
    {
        return $this->aDbBindings;
    }

    //Tabella dati
    protected $sDbDataTable = "";
    public function GetDbDataTable()
    {
        return $this->sDbDataTable;
    }
    public function SetDbDataTable($var = "")
    {
        $this->sDbDataTable = $var;
    }

    //funzione di ricerca
    static public function Search($params = array(), $user = null)
    {
        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        $public = false;
        if ($user->IsGuest()) {
            $public = true;
        }

        //Imposta la query base
        $select = "SELECT DISTINCT " . static::$AA_DBTABLE_OBJECTS . ".id FROM " . static::$AA_DBTABLE_OBJECTS . " ";
        $join = "";
        $where = "";
        $group = " GROUP by " . static::$AA_DBTABLE_OBJECTS . ".id";
        $having = "";
        $order = "";

        //Verifica del parametro class
        if ($params['class'] == "" || !class_exists($params['class']) || $params['class'] == null) {
            $params['class'] = "AA_Object_V2";
            $where .= " WHERE " . static::$AA_DBTABLE_OBJECTS . ".class='AA_Object_V2' ";
        } else {
            $where .= " WHERE " . static::$AA_DBTABLE_OBJECTS . ".class='" . addslashes($params['class']) . "' ";
        }

        //Collegamento tabella dati
        if ($params['class']::AA_DBTABLE_DATA != "") {
            //$join .= " INNER JOIN " . $params['class']::AA_DBTABLE_DATA . " ON " . $params['class']::AA_DBTABLE_DATA . ".id in (" . static::$AA_DBTABLE_OBJECTS . ".id_data," . static::$AA_DBTABLE_OBJECTS . ".id_data_rev)";
            $join .= " INNER JOIN " . $params['class']::AA_DBTABLE_DATA . " ON " . $params['class']::AA_DBTABLE_DATA . ".id = ". static::$AA_DBTABLE_OBJECTS . ".id_data ";
        }

        //Parametro status non impostato o non valido
        if ($params['status'] == "" || !isset($params['status']) || $public || ($params['status'] & AA_Const::AA_STATUS_ALL) == 0) $params['status'] = AA_Const::AA_STATUS_PUBBLICATA;

        $userStruct = $user->GetStruct();

        if (!$public) {
            if ($params['gestiti'] != "" || ($params['status'] & (AA_Const::AA_STATUS_BOZZA + AA_Const::AA_STATUS_CESTINATA + AA_Const::AA_STATUS_REVISIONATA) > 0)) {
                //Visualizza solo quelle della sua struttura
                if ($userStruct->GetAssessorato(true) > 0) {
                    $params['id_assessorato'] = $userStruct->GetAssessorato(true);
                    //$where.=" AND ".static::$AA_DBTABLE_OBJECTS.".id_assessorato='".$userStruct->GetAssessorato(true)."'";    
                }

                if ($userStruct->GetDirezione(true) > 0) {
                    $params['id_direzione'] = $userStruct->GetDirezione(true);
                    //$where.=" AND ".static::$AA_DBTABLE_OBJECTS.".id_direzione='".$userStruct->GetDirezione(true)."'";    
                }

                if ($userStruct->GetServizio(true) > 0) {
                    $params['id_servizio'] = $userStruct->GetServizio(true);
                    //$where.=" AND ".static::$AA_DBTABLE_OBJECTS.".id_servizio='".$userStruct->GetDirezione(true)."'";    
                }
            }
        }

        //Filtra solo oggetti della RAS
        if ($userStruct->GetTipo() == 0) {
            $join .= " INNER JOIN " . AA_Const::AA_DBTABLE_ASSESSORATI . " ON " . static::$AA_DBTABLE_OBJECTS . ".id_assessorato=" . AA_Const::AA_DBTABLE_ASSESSORATI . ".id ";

            //RAS
            $where .= " AND " . AA_Const::AA_DBTABLE_ASSESSORATI . ".tipo = 0";
        }

        //solo oggetti dell'ente
        if ($userStruct->GetTipo() > 0) {
            $params['id_assessorato'] = $userStruct->GetAssessorato(true);
        }

        //filtro struttura
        if (isset($params['id_assessorato']) && $params['id_assessorato'] != "" && $params['id_assessorato'] > 0) {
            $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".id_assessorato = '" . addslashes($params['id_assessorato']) . "'";
        }

        if (isset($params['id_direzione']) && $params['id_direzione'] != "" && $params['id_direzione'] > 0) {
            $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".id_direzione = '" . addslashes($params['id_direzione']) . "'";
        }

        if (isset($params['id_servizio']) && $params['id_servizio'] != "" && $params['id_servizio'] > 0) {
            $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".id_servizio = '" . addslashes($params['id_servizio']) . "'";
        }
        //------------------------

        //Filtra in base allo stato della scheda
        if (!isset($params['id']) || $params['id'] == "")
        {
            $where .= " AND status ='" . $params['status'] . "' ";

            //filtra in base al nome
            if (isset($params['nome']) && $params['nome'] != "") {
                $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".nome like '%" . addslashes($params['nome']) . "%'";
            }

            //filtra in base alla descrizione
            if (isset($params['descrizione']) && $params['descrizione'] != "") {
                $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".descrizione like '%" . addslashes($params['descrizione']) . "%'";
            }
        }

        //filtra in base all'id(s)
        if (isset($params['id']) && $params['id'] != "") {
            $ids = array();
            preg_match("/([0-9]+\,*)+/", $params['id'], $ids);

            if (count($ids) > 0) {
                $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".id in (" . $ids[0] . ")";
            } else $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".id = '" . addslashes($params['id']) . "'";
        }

        //aggiunge i join
        if (isset($params['join']) && is_array($params['join'])) {
            foreach ((array)$params['join'] as $curJoin) {
                $join .= " " . $curJoin . " ";
            }
        }
        //-----------------------

        //aggiunge i where
        if (isset($params['where']) && is_array($params['where'])) {
            foreach ((array)$params['where'] as $curParam) {
                if ($where == "") $where = " WHERE " . $curParam;
                else
                {
                    if(strpos($curParam," AND ") !==false || strpos($curParam," OR ") !==false) $where .= "  " . $curParam;
                    else $where .= "  AND " . $curParam;    
                }
            }
        }
        //-----------------------

        //aggiunge i having
        if (isset($params['having']) && is_array($params['having'])) {
            foreach ((array)$params['having'] as $curParam) {
                if ($having == "") $having = " HAVING " . $curParam;
                else $having .= " AND " . $curParam;
            }
        }
        //-----------------------

        //aggiunge i group
        if (isset($params['group']) && is_array($params['group'])) {
            foreach ((array)$params['group'] as $curParam) {
                if ($group == "") $group = " GROUP BY " . $curParam;
                else $group .= ", " . $curParam;
            }
        }
        //-----------------------

        //aggiunge gli order
        if (isset($params['order']) && is_array($params['order'])) {
            foreach ((array)$params['order'] as $curParam) {
                if ($order == "") $order = " ORDER BY " . $curParam;
                else $order .= ", " . $curParam;
            }
        }
        //-----------------------

        $db = new AA_Database();

        //Conta i risultati
        $query = "SELECT COUNT(id) as tot FROM (" . $select . $join . $where . $group . $having . ") as count_filter";

        //AA_Log::Log(get_class()."->Search(".print_r($params,TRUE).") - query: $query",100);

        if (!$db->Query($query)) {
            //Imposta lo stato di errore
            AA_Log::Log(__METHOD__ . "(" . print_r($params, TRUE) . ") - Errore :" . $db->GetErrorMessage()." - ".$query, 100);

            return array(0 => -1, array());
        }

        $rs = $db->GetResultSet();
        $tot_count = $rs['0']['tot'];

        //Restituisce un array vuoto se non trova nulla
        if ($tot_count == 0) {
            //AA_Log::Log(__METHOD__."(".print_r($params,TRUE).") - query vuota: $query");
            return array(0 => 0, array());
        }

        //Restituisce solo il numero
        if (isset($params['onlyCount']) && $params['onlyCount'] != "") return array(0 => $tot_count, array());

        //Limita a 10 risultati di default
        if (!isset($params['from']) || $params['from'] < 0 || $params['from'] > $tot_count) $params['from'] = 0;
        if (!isset($params['count']) || $params['count'] < 0) $params['count'] = 10;

        //Effettua la query
        $query = $select . $join;
        if (strlen($where) > 0) $query .= $where;
        if ($params['count'] != "all") $query .= $group . $having . $order . " LIMIT " . $params['from'] . "," . $params['count'];
        else $query .= $group . $having . $order;
        if (!$db->Query($query)) {
            //Errore query
            AA_Log::Log(__METHOD__ . "(" . print_r($params, TRUE) . ") - Errore nella query:" . $db->GetErrorMessage()." - ".$query, 100);
            return array(0 => -1, array());
        }

        //AA_Log::Log(get_class()."->Search(".print_r($params,TRUE).") - query: $query",100);

        //Popola l'array dei risultati
        $results = array();

        if ($db->GetAffectedRows() > 0) {
            foreach ($db->GetResultSet() as $curRec) {
                $curResult = new $params['class']($curRec['id'], $user);
                if ($curResult->isValid()) $results[$curRec['id']] = $curResult;
            }
        }

        return array(0 => $tot_count, $results);
    }
}
