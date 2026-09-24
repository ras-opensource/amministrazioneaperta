<?php
class AA_SicarFinanziamento extends AA_GenericParsableDbObject
{
    // Tabella dati per i finanziamenti
    static protected $dbDataTable="aa_sicar_finanziamenti";
    static protected $ObjectClass=__CLASS__;
    
    // Costruttore
    public function __construct($params = array())
    {
        
        // Imposta i binding tra proprietà e campi database
        $this->aProps['denominazione']="Nuovo finanziamento";
        $this->aProps['estremi']="";
        $this->aProps['data']="";
        $this->aProps['tipologia']=0;

        $this->aProps['note']="";
        
        //template view props
        $this->aTemplateViewProps['denominazione']=array("label"=>"Denominazione","type"=>"text","visible"=>true);
        $this->aTemplateViewProps['estremi']=array("label"=>"Estremi","type"=>"text","visible"=>true);
        $this->aTemplateViewProps['data']=array("label"=>"Data","type"=>"text","visible"=>true);
        $this->aTemplateViewProps['tipologia']=array("label"=>"Tipologia","type"=>"text","function"=>"GetTipologia","visible"=>true);
        $this->aTemplateViewProps['note']=array("label"=>"Note","type"=>"textarea","visible"=>true);

        //areas, cols e rows di default
        $this->aTemplateViewProps['__areas']=array(
            array("denominazione", "denominazione","tipologia"),
            array("estremi", "data","note"),
        );
        $this->aTemplateViewProps['__cols']=array("1fr","1fr","1fr");
        $this->aTemplateViewProps['__rows']=array("1fr","1fr","1fr");

        // Chiama il costruttore padre
        parent::__construct($params);
    }

    public function GetTemplateView($bRefresh=false)
    {
        return parent::GetTemplateView($bRefresh);
    }
        
    //lista dei finanziamenti
    public static function GetListaFinanziamenti()
    {
        $db = new AA_Database();
        $query = "SELECT * FROM ".static::$dbDataTable." ORDER BY denominazione";
        
        $return = array();

        if($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach($rs as $row) {
                $return[] = new AA_SicarFinanziamento($row);
            }
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile ottenere la lista dei finanziamenti. - ".$db->GetErrorMessage(), 100);
        }

        return $return;
    }

    // Metodi Getter e Setter per le proprietà
    
    // Descrizione
    public function GetDenominazione()
    {
        return $this->GetProp("denominazione");
    }

    public function GetName()
    {
        return $this->GetProp("denominazione");
    }
    
    public function SetDenominazione($var = "")
    {
        $this->SetProp("denominazione", $var);
        return true;
    }

    public function SetName($var = "")
    {
        $this->SetProp("denominazione", $var);
        return true;
    }

    // Estremi
    public function GetEstremi()
    {
        return $this->GetProp("estremi");
    }
    
    public function SetEstremi($var = "")
    {
        $this->SetProp("estremi", $var);
        return true;
    }

    // Data
    public function GetData()
    {
        return $this->GetProp("data");
    }
    
    public function SetData($var = "")
    {
        $this->SetProp("data", $var);
        return true;
    }

    // Tipologia
    public function GetTipologia($bAsText=true)
    {
        if(!$bAsText) return $this->GetProp("tipologia"); 
        
        $tipo=AA_Sicar_Const::GetListaTipologieProgFinanziamento(true);
        if(!empty($tipo[$this->GetProp("tipologia")])) return $tipo[$this->GetProp("tipologia")];
        else return "n.d.";
        
    }
    
    public function SetTipologia($var = 0)
    {
        $this->SetProp("tipologia", $var);
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
        if (empty($this->GetDenominazione())) {
            $errors[] = "La denominazione è obbligatoria";
        }
        
        if (empty($this->GetTipologia())) {
            $errors[] = "La tipologia è obbligatoria";
        }
        
        return $errors;
    }
    
    // Metodo per ottenere una rappresentazione testuale del finanziamento
    public function GetDisplayName()
    {
        $display = strval($this->GetDenominazione());

        return $display;
    }
    
    // Metodo per l'esportazione CSV
    protected function CsvDataHeader($separator = "|")
    {
        return "denominazione".$separator . "tipologia" . 
               $separator . "estremi" . $separator . "data" . 
               $separator . "note";
    }
    
    protected function CsvData($separator = "|")
    {
        return $this->GetDenominazione().$separator . $this->GetTipologia() . 
               $separator . $this->GetEstremi() . $separator . $this->GetData() .
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
        
        // Se l'utente ha il flag e può modificare il finanziamento allora può fare tutto
        if ($user->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $perms = AA_Const::AA_PERMS_ALL;
        }
        
        return $perms;
    }
    
    /**
     * Funzione statica per l'aggiunta di nuovi finanziamenti
    * @param array $params dati del finanziamento
     * @return bool|int ID del finanziamento creato o false in caso di errore
     */
    static public function AddNew($params, $user = null)
    {
        $object = new AA_SicarFinanziamento($params);

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
            AA_Log::Log(__METHOD__ . " - ERRORE: i dati del finanziamento non sono validi.", 100);
            return false;
        }

        return parent::Sync();
    }
}