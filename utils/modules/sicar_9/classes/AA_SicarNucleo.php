<?php
class AA_SicarNucleo extends AA_GenericParsableDbObject
{
    // Tabella dati per gli enti
    static protected $dbDataTable="aa_sicar_nuclei";
    static protected $ObjectClass=__CLASS__;
    
    // Costruttore
    public function __construct($params = array())
    {
        
        // Imposta i binding tra proprietà e campi database
        $this->aProps['descrizione']="Nuovo nucleo";
        $this->aProps['cf']="";
        $this->aProps['comune']="";
        $this->aProps['indirizzo']="";
        $this->aProps['note']="";
        $this->aProps['alloggio_attuale']=0;
        $this->aProps['storico_assegnazioni']="";
        
        //template view props
        $this->aTemplateViewProps['descrizione']=array("label"=>"Descrizione","type"=>"text","maxlength"=>AA_Sicar_Const::MAX_DESCRIZIONE_LENGTH,"required"=>true,"visible"=>true);
        $this->aTemplateViewProps['cf']=array("label"=>"Codice fiscale","type"=>"text","required"=>true,"visible"=>true);
        $this->aTemplateViewProps['comune']=array("label"=>"Comune di residenza","type"=>"text","required"=>true,"function"=>"GetComune","visible"=>true);
        $this->aTemplateViewProps['indirizzo']=array("label"=>"Indirizzo di residenza","type"=>"text","required"=>true,"visible"=>true);
        $this->aTemplateViewProps['note']=array("label"=>"Note","type"=>"textarea","required"=>false,"visible"=>true);

        // Chiama il costruttore padre
        parent::__construct($params);
    }

    public function GetTemplateView($bResfresh=false)
    {
        if($this->oTemplateView !=null && !$bResfresh) return $this->oTemplateView;
        
        $templateView=new AA_GenericTemplate_Grid();
        $templateAreas=array(
            array("descrizione", "descrizione","cf"),
            array("indirizzo", "indirizzo", "comune"),
            array("note", "note", "note")
        );
        $templateView->SetTemplateAreas($templateAreas);
        $templateView->SetTemplateCols(array("1fr","1fr","1fr"));
        $templateView->SetTemplateRows(array("1fr","1fr","1fr"));        

        foreach($this->aTemplateViewProps as $propName=>$propConfig)
        {
            if($propConfig['visible'])
            {
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

                if(!$templateView->AddCellToGrid(new AA_JSON_Template_Template("", array(
                    "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
                    "data" => array("title" => "".$propConfig['label'].":", "value" => $value),
                    "css" => array("border-bottom" => "1px solid #dadee0 !important","width"=>"auto !important","height"=> "auto !important"),
                )), $propName))
                {
                    AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile aggiungere la cella alla template view per la proprietà: " . $propName, 100);
                }
            }
        }

        $this->SetTemplateView($templateView);

        //AA_Log::Log(__METHOD__ . " - INFO: generata la template view per l'immobile con ID: " . $this->GetProp("id")." - ".print_r($templateView,true), 100);
        return $templateView;
    }
    
    public function Delete($user = null)
    {
        
        if($user instanceof AA_User)
        {
            if(!$user->isCurrentUser())
            {
                $user = AA_User::GetCurrentUser();
            }
        }
        else
        {
            $user = AA_User::GetCurrentUser();
        }

        // Controlla se il nucleo è assegnato ad un alloggio e in tal caso libera l'alloggio
        $alloggioAttuale = $this->GetAlloggioAttuale();
        AA_Log::Log(__METHOD__ . " - INFO: controllo l'alloggio attuale per il nucleo con ID: " . $this->GetProp('id')." - alloggio: ".($alloggioAttuale ? $alloggioAttuale->GetDisplayName() : "nessuno o non trovato"), 100);
        if ($alloggioAttuale instanceof AA_SicarAlloggio) {
            //AA_Log::Log(__METHOD__ . " - ERRORE: impossibile eliminare il nucleo in quanto è attualmente assegnato all'alloggio: " . $alloggioAttuale->GetDisplayName(), 100);
            
            $occupazione = $alloggioAttuale->GetOccupazione();
            $lastAssegnazione = current($occupazione);
            if($lastAssegnazione['occupazione_id_nucleo'] == $this->GetProp('id')) 
            {
                //imposto lo stato dell'alloggio a libero
                $occupazione[array_key_first($occupazione)] = array(
                    "tipo" => 0,
                    "note"=>"Liberato il ".date("Y-m-d")." per eliminazione nucleo occupante.",
                    'occupazione_id_nucleo' => 0,
                    'occupazione_data_assegnazione'=>"",
                    'occupazione_tipo_canone'=>0,
                    'occupazione_riserva'=>0,
                    'occupazione_abusivo'=>0,
                    'occupazione_residenza'=>0
                );
                
                $alloggioAttuale->SetOccupazione($occupazione);
                $alloggioAttuale->Update($user ,true,"Aggiornamento stato occupazione - libero");
            }
        }

        return parent::Delete($user);
    }
    //stato assegnazione
    public function GetStatoAssegnazione($bAsObject=true,$last=true)
    {
        $return = array();
        $last_assegnazione=array();
        $last_assegnazione_dal="";
        $db=new AA_Database();
        $query="SELECT id,occupazione from ".AA_SicarAlloggio::AA_DBTABLE_DATA." WHERE occupazione like '%\"nucleo\":".$this->GetProp('id')."}'";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore query: ".$db->GetErrorMessage(),100);
            if($bAsObject) return array();
            else return "Nessuna";
        }
        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            foreach($rs as $curAlloggio)
            {
                $occupazione=json_decode($curAlloggio['occupazione'],true);
                $idAlloggio=$curAlloggio['id'];

                if(is_array($occupazione))
                {
                    foreach($occupazione as $curOccupazione)
                    {
                        if($curOccupazione['nucleo']==$this->GetProp("id"))
                        {
                            $return[$idAlloggio]=$curOccupazione;
                            if($curOccupazione['dal'] > $last_assegnazione_dal) 
                            {
                                $last_assegnazione_dal=$curOccupazione['dal'];
                                $last_assegnazione=$curOccupazione;
                            }
                        }
                        else
                        {
                            if($curOccupazione['dal'] > $last_assegnazione_dal) 
                            {
                                $last_assegnazione_dal = "";
                                $last_assegnazione=array();
                            }
                        }
                    }
                }
            }
            ksort($return);
        }

        if($bAsObject)
        {
            if(!$last) return $return;
            return $last_assegnazione;
        }
        else
        {
            if(sizeof($last_assegnazione) > 0) return $last_assegnazione['tipo'];
            else return "Nessuna";
        }
    }

    //Restituisce l'alloggio attuale se presente
    public function GetAlloggioAttuale($bAsObject=true)
    {
        if(!$bAsObject) return $this->GetProp('alloggio_attuale');

        if($this->GetProp('alloggio_attuale')>0)
        {
            $alloggio=new AA_SicarAlloggio($this->GetProp("alloggio_attuale"));
            if($alloggio->IsValid())
            {
                return $alloggio;
            }
            else return null;
        }

        return null;
    }

     public function GetComponenti($bAsObject=true)
    {
        $ret="";
        if($bAsObject) $ret=array();
        if(!isset($this->aProps['componenti']) || $this->aProps['componenti']=="") return $ret;
        
        if($bAsObject)
        {
            $ret=json_decode($this->aProps['componenti'],true);
            if($ret) return $ret;
            else
            {
                AA_Log::Log(__METHOD__." - Errore nell'importazione dei componenti: ".$this->aProps['id'],100);
                return array();
            }
        }

        return $this->aProps['componenti'];
    }

    //isee
    public function GetIseePreview()
    {
        return "n.d.";
    }

    public function SetComponenti($var="")
    {
        if(is_array($var))
        {
            if(sizeof($var)>0)
            {
                $var=json_encode($var);
                if($var===false)
                {
                    AA_Log::Log(__METHOD__." - Errore nella codifica dei componenti. ".print_r($var,true),100);
                    return false;
                }    
            }
            else $var="";
        }

        $this->SetProp("componenti",$var);
        return true;
    }

    //lista dei nuclei
    public static function GetListaNuclei()
    {
        $db = new AA_Database();
        $query = "SELECT * FROM ".static::$dbDataTable." ORDER BY descrizione";
        
        $return = array();

        if($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach($rs as $row) {
                $return[] = new AA_SicarNucleo($row);
            }
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile ottenere la lista degli enti. - ".$db->GetErrorMessage(), 100);
        }

        return $return;
    }

    // Metodi Getter e Setter per le proprietà
    
    // Descrizione
    public function GetDescrizione()
    {
        return $this->GetProp("descrizione");
    }
    
    public function SetDescrizione($var = "")
    {
        $this->SetProp("descrizione", $var);
        return true;
    }

    // CF
    public function GetCf()
    {
        return $this->GetProp("cf");
    }
    
    public function SetCf($var = "")
    {
        $this->SetProp("cf", $var);
        return true;
    }

    // Indirizzo
    public function GetIndirizzo()
    {
        return $this->GetProp("indirizzo");
    }
    
    public function SetIndirizzo($var = "")
    {
        $this->SetProp("indirizzo", $var);
        return true;
    }
    
    // Note
    public function GetNote()
    {
        return $this->GetProp("note");
    }
    
    public function SetNote($var = "")
    {
        $this->SetProp("note", $var);
        return true;
    }
    
    // Metodi per la validazione
    public function Validate()
    {
        $errors = array();
        
        // Validazione campi obbligatori
        if (empty($this->GetDescrizione())) {
            $errors[] = "La descrizione è obbligatoria.";
        }
        if (empty($this->GetCf())) {
            $errors[] = "il codice fiscale è obbligatorio.";
        }
        
        //if (empty($this->GetIndirizzo())) {
        //    $errors[] = "L'indirizzo è obbligatorio.";
        //}

        //if (empty($this->GetComune(false))) {
        //    $errors[] = "Il Comune di residenza è obbligatorio.";
        //}
        
        return $errors;
    }
    
    // Metodo per ottenere una rappresentazione testuale dell'immobile
    public function GetDisplayName()
    {
        $display = strval($this->GetDescrizione());
        if (!empty($this->GetIndirizzo())) {
            $display .= " - " . $this->GetIndirizzo();
        }

        return $display;
    }

     // Comune
    public function GetComune($bDescr=true)
    {
        if($bDescr) 
        {
            if(empty($this->GetProp("comune"))) return "n.d.";
            else return AA_Sicar_Const::GetComuneDescrFromCodiceIstat($this->GetProp("comune"));
        }

        return $this->GetProp("comune");
    }

    public function SetComune($var = "")
    {
        $this->SetProp("comune", $var);
        return true;
    }
    
    // Metodo per l'esportazione CSV
    protected function CsvDataHeader($separator = "|")
    {
        return "descrizione".$separator . "tipologia" . 
               $separator . "indirizzo" . $separator . "note";
    }
    
    protected function CsvData($separator = "|")
    {
        return $this->GetDescrizione().$separator .  
               $separator .  str_replace("\n", ' ', $this->GetIndirizzo()) . 
               $separator . str_replace("\n", ' ', $this->GetNote());
    }
    
    /**
     * Metodo per popolare l'oggetto dai parametri
     * @param array $params Parametri da parsare
     */
    public function Parse($params = array())
    {
        // Chiama il metodo padre per le proprietà base
        return parent::Parse($params);
    }
    
    /**
     * Funzione per verificare i permessi dell'utente
     * @param AA_User $user Utente da verificare
     * @return int Permessi dell'utente
     */
    public function GetUserCaps($user = null)
    {
        // Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser()) {
                $user = AA_User::GetCurrentUser();
            }
        } else {
            $user = AA_User::GetCurrentUser();
        }
        
        $perms = AA_Const::AA_PERMS_READ;
        
        // Se l'utente ha il flag e può modificare l'immobile allora può fare tutto
        if ($user->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $perms = AA_Const::AA_PERMS_ALL;
        }
        
        return $perms;
    }
    
    //verifica se il nucleo è attualmente assegnato ad un alloggio
    public function HasAlloggio()
    {   
        $statoAssegnazione=$this->GetProp("alloggio_attuale");
        if($statoAssegnazione > 0)
        {
            //AA_Log::Log(__METHOD__." - Il nucleo con ID: ".$this->GetProp("cf")." è attualmente assegnato all'alloggio con ID: ".$statoAssegnazione, 100);
            return true;
        } 
        else return false;
    }

    /**
     * Funzione statica per l'aggiunta di nuovi immobili
    * @param array $params dati dell'immobile
     * @return bool|int ID dell'immobile creato o false in caso di errore
     */
    static public function AddNew($params, $user = null)
    {
        $object = new AA_SicarNucleo();
        $object->Parse($params);
        $object->SetProp("id",0);

        $validate=$object->Validate();
        if(sizeof($validate)>0)
        {
            AA_Log::Log(__METHOD__." - Sono stati trovati i seguenti errori: ".print_r($validate,true),100);
            return false;
        }

        return $object->Sync($user);
    }

    public function Sync($user = null)
    {
        if($user instanceof AA_User)
        {
            if(!$user->isCurrentUser())
            {
                $user = AA_User::GetCurrentUser();
            }
        }
        else
        {
            $user = AA_User::GetCurrentUser();
        }
        
        if($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE==0) 
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: l'utente corrente: " . $user->GetName() . " non ha i permessi per inserire nuovi elementi.", 100);
            return false;
        }

        if(!empty($this->Validate())) 
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: i dati del nucleo non sono validi.", 100);
            return false;
        }

        return parent::Sync();
    }
}

/**
 * Classe principale del modulo SICAR
 */
