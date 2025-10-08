<?php
include_once "config.php";
include_once "system_lib.php";

//Costanti
class AA_Organismi_Const extends AA_Const
{
    //percorso pubblicazione documenti
    const AA_ORGANISMI_NOMINE_DOCS_PATH="/amministrazione_trasparente/art22/nomine/docs";
    const AA_ORGANISMI_NOMINE_DOCS_PUBLIC_PATH="/pubblicazioni/art22/nomine/docs.php";

    //percorso pubblicazione provvedimenti
    const AA_ORGANISMI_PROVVEDIMENTI_PATH="/amministrazione_trasparente/art22/provvedimenti";
    const AA_ORGANISMI_PROVVEDIMENTI_PUBLIC_PATH="/pubblicazioni/art22/provvedimenti/docs.php";

    //percorso pubblicazione bilanci
    const AA_ORGANISMI_BILANCI_PATH="/amministrazione_trasparente/art22/bilanci";
    const AA_ORGANISMI_BILANCI_PUBLIC_PATH="/pubblicazioni/art22/bilanci/docs.php";
    
    //Tabella db
    const AA_ORGANISMI_DB_TABLE="art22_pubblicazioni";
    const AA_ORGANISMI_DATI_CONTABILI_DB_TABLE="art22_finanza";
    const AA_ORGANISMI_BILANCI_DB_TABLE="art22_bilanci";
    const AA_ORGANISMI_NOMINE_DB_TABLE="art22_nomine";
    const AA_ORGANISMI_COMPENSI_DB_TABLE="art22_nomine_compensi";
    const AA_ORGANISMI_PROVVEDIMENTI_DB_TABLE="art22_provvedimenti_new";
    const AA_DBTABLE_ORGANIGRAMMA="art22_organigramma";
    const AA_DBTABLE_ORGANIGRAMMA_INCARICHI="art22_organigramma_incarichi";

    //Tipologia organismi
    static private $TIPOLOGIA=null;
    static private $TIPOLOGIA_PLURALI=null;

    const AA_ORGANISMI_NONE=0;
    const AA_ORGANISMI_ENTE_PUBBLICO_VIGILATO=2;
    const AA_ORGANISMI_ENTE_PRIVATO_CONTROLLATO=4;
    const AA_ORGANISMI_SOCIETA_PARTECIPATA=8;

    //Tipo società
    static private $FORMA_GIURIDICA=null;
    const AA_ORGANISMI_FORMA_GIURIDICA_NONE=0;
    const AA_ORGANISMI_FORMA_GIURIDICA_SPA=2;
    const AA_ORGANISMI_FORMA_GIURIDICA_SRL=4;
    const AA_ORGANISMI_FORMA_GIURIDICA_SCARL=8;
    const AA_ORGANISMI_FORMA_GIURIDICA_SCPA=16;
    const AA_ORGANISMI_FORMA_GIURIDICA_CONSORZIO=32;

    //Stato società
    static private $STATO_SOCIETA=null;
    const AA_ORGANISMI_STATO_SOCIETA_NONE=0;
    const AA_ORGANISMI_STATO_SOCIETA_ATTIVO=2;
    const AA_ORGANISMI_STATO_SOCIETA_CESSATO=4;
    const AA_ORGANISMI_STATO_SOCIETA_IN_LIQUIDAZIONE=8;
    const AA_ORGANISMI_STATO_SOCIETA_IN_CONCORDATO=16;
    const AA_ORGANISMI_STATO_SOCIETA_IN_CONCORDATO_LIQUIDAZIONE=32;
    const AA_ORGANISMI_STATO_SOCIETA_IN_LIQUIDAZIONE_COATTA=64;
    const AA_ORGANISMI_STATO_SOCIETA_IN_FALLIMENTO=128;
    const AA_ORGANISMI_STATO_SOCIETA_INATTIVA=256;
    const AA_ORGANISMI_STATO_SOCIETA_SCIOGLIMENTO_LIQUIDAZIONE=512;
    
    //Tipo di bilancio
    static private $TIPO_BILANCIO=null;
    const AA_ORGANISMI_BILANCIO_NONE=0;
    const AA_ORGANISMI_BILANCIO_PRECONSUNTIVO=2;
    const AA_ORGANISMI_BILANCIO_CONSUNTIVO=16;
    const AA_ORGANISMI_BILANCIO_CONSOLIDATO=4;
    const AA_ORGANISMI_BILANCIO_ESERCIZIO=8;
    const AA_ORGANISMI_BILANCIO_RISULTATO_AMMINISTRAZIONE=16;
    const AA_ORGANISMI_BILANCIO_RISULTATO_GESTIONE=32;
    const AA_ORGANISMI_BILANCIO_PREVISIONE=64;
    
    //Tipo nomina
    static private $TIPO_NOMINE=null;
    const AA_ORGANISMI_NOMINA_NONE=0;
    const AA_ORGANISMI_NOMINA_PRESIDENTE=2;
    const AA_ORGANISMI_NOMINA_COMMISSARIO_STRAORDINARIO=4;
    const AA_ORGANISMI_NOMINA_CONSIGLIERE=8;
    const AA_ORGANISMI_NOMINA_LIQUIDATORE=16;
    const AA_ORGANISMI_NOMINA_AMMINISTRATORE_UNICO=32;
    const AA_ORGANISMI_NOMINA_AMMINISTRATORE_DELEGATO=64;
    const AA_ORGANISMI_NOMINA_SINDACO_COLLEGGIO=128;
    const AA_ORGANISMI_NOMINA_PRESIDENTE_COLLEGGIO=256;
    const AA_ORGANISMI_NOMINA_REVISORE_CONTI=512;
    const AA_ORGANISMI_NOMINA_DIRETTORE=1024;
    const AA_ORGANISMI_NOMINA_VICEPRESIDENTE=2048;
    const AA_ORGANISMI_NOMINA_DIRETTORE_TECNICO=4096;
    const AA_ORGANISMI_NOMINA_REVISORE_UNICO=8192;
    const AA_ORGANISMI_NOMINA_LIQUIDATORE_GIUDIZIARIO=16384;
    const AA_ORGANISMI_NOMINA_COMPONENTE_COMITATO_SCIENTIFICO=32768;
    const AA_ORGANISMI_NOMINA_PRESIDENTE_NON_CDA=65536;
    const AA_ORGANISMI_NOMINA_COMPONENTE_CONSIGLIO_DIRETTIVO=131072;
    const AA_ORGANISMI_NOMINA_PRESIDENTE_CONSIGLIO_DIRETTIVO=262144;
    const AA_ORGANISMI_NOMINA_PRESIDENTE_ONORARIO=524288;
    const AA_ORGANISMI_NOMINA_PRESIDENTE_CONSIGLIO_INDIRIZZO=1048576;
    const AA_ORGANISMI_NOMINA_REVISORE_CONTI_SUPPLENTE=2097152;
    const AA_ORGANISMI_NOMINA_COMPONENTE_OIV=4194304;
    const AA_ORGANISMI_NOMINA_PRESIDENTE_OIV=8388608;
    const AA_ORGANISMI_NOMINA_DIRETTORE_GENERALE=16777216; //16769216;
    const AA_ORGANISMI_NOMINA_PRESIDENTE_ORGANO_INDIRIZZO=33554432;
    const AA_ORGANISMI_NOMINA_COMPONENTE_ORGANO_INDIRIZZO=67108864; //67076864;
    const AA_ORGANISMI_NOMINA_COMPONENTE_SUPPLENTE_COLLEGGIO=134217728; //67076864;
    const AA_ORGANISMI_NOMINA_AMMINISTRATORE_DELEGATO_CDA=268435456;
    const AA_ORGANISMI_NOMINA_PRESIDENTE_COLLEGGIO_REVISORE=536870912; //2^29
    const AA_ORGANISMI_NOMINA_VICEPRESIDENTE_CDA=1073741824; //2^30
    const AA_ORGANISMI_NOMINA_COMMISSARIO_LIQUIDATORE=20; //16+4

    //nomine da pubblicare
    static function GetNomineDaOscurare()
    {
        $result= static::AA_ORGANISMI_NOMINA_COMPONENTE_OIV;
        $result+= static::AA_ORGANISMI_NOMINA_PRESIDENTE_OIV;
        $result+= static::AA_ORGANISMI_NOMINA_REVISORE_CONTI;
        $result+= static::AA_ORGANISMI_NOMINA_PRESIDENTE_COLLEGGIO_REVISORE;
        $result+= static::AA_ORGANISMI_NOMINA_PRESIDENTE_COLLEGGIO;
        $result+= static::AA_ORGANISMI_NOMINA_SINDACO_COLLEGGIO;
        $result+= static::AA_ORGANISMI_NOMINA_COMPONENTE_SUPPLENTE_COLLEGGIO;
        $result+= static::AA_ORGANISMI_NOMINA_REVISORE_CONTI_SUPPLENTE;
        $result+= static::AA_ORGANISMI_NOMINA_REVISORE_UNICO;
        $result+= static::AA_ORGANISMI_NOMINA_LIQUIDATORE_GIUDIZIARIO;

        return $result;
    }
    
    //const AA_NOMINE_NON_PUBBLICARE=146826128;

    //Tipo di documenti
    static private $TIPO_DOCS=null;
    const AA_ORGANISMI_DOC_NONE=0;
    const AA_ORGANISMI_DOC_CURRICULUM=2;
    const AA_ORGANISMI_DOC_DICH_INCONFERIBILITA=4;
    const AA_ORGANISMI_DOC_DICH_INCOMPATIBILITA=8;
    const AA_ORGANISMI_DOC_ATTESTAZIONE_VERIFICA=16;
    const AA_ORGANISMI_DOC_PROVVEDIMENTO_NOMINA=32;
    const AA_ORGANISMI_DOC_PROVVEDIMENTO_PROROGA=64;
    const AA_ORGANISMI_DOC_PROVVEDIMENTO_REVOCA=128;
    const AA_ORGANISMI_DOC_MASK=254;
    
    //Tipo di provvedimenti
    static private $TIPO_PROVV=null;
    const AA_ORGANISMI_PROVV_NONE=0;
    const AA_ORGANISMI_PROVV_COSTITUZIONE=2;
    const AA_ORGANISMI_PROVV_ACQUISTO_PARTECIPAZIONI=4;
    const AA_ORGANISMI_PROVV_GESTIONE=8;
    const AA_ORGANISMI_PROVV_ALIENAZIONE=16;
    const AA_ORGANISMI_PROVV_QUOTAZIONE=32;
    const AA_ORGANISMI_PROVV_RAZIONALIZZAZIONE=64;
    const AA_ORGANISMI_PROVV_LIQUIDAZIONE=128;
    const AA_ORGANISMI_PROVV_ATTI_INDIRIZZO=256;
    const AA_ORGANISMI_PROVV_NULLA_OSTA=512;
    const AA_ORGANISMI_PROVV_TUPI=1024;
    const AA_ORGANISMI_PROVV_MASK=2046;

    //tipo organigrammi
    static private $TIPO_ORGANIGRAMMA=null;
    const AA_ORGANISMI_ORGANIGRAMMA_NONE=0;
    const AA_ORGANISMI_ORGANIGRAMMA_AMMINISTRATIVO=1;
    const AA_ORGANISMI_ORGANIGRAMMA_CONTROLLO=2;
    const AA_ORGANISMI_ORGANIGRAMMA_DIREZIONALE=4;
    const AA_ORGANISMI_ORGANIGRAMMA_CONSULTIVO=8; //2^3
    const AA_ORGANISMI_ORGANIGRAMMA_INDIRIZZO=16; //2^4

    const AA_ORGANISMI_ORGANIGRAMMA_MASK=31;

    //Inizializza gli array
    static private function Initialize()
    {
        if(self::$TIPOLOGIA==null)
        {
        self::$TIPOLOGIA=array(
            self::AA_ORGANISMI_NONE=>"Nessuno",
            self::AA_ORGANISMI_ENTE_PUBBLICO_VIGILATO=>"Ente pubblico vigilato",
            self::AA_ORGANISMI_ENTE_PRIVATO_CONTROLLATO=>"Ente di diritto privato in controllo pubblico",
            self::AA_ORGANISMI_SOCIETA_PARTECIPATA=>"Società partecipata"
            );

        self::$TIPOLOGIA_PLURALI=array(
            self::AA_ORGANISMI_NONE=>"Nessuno",
            self::AA_ORGANISMI_ENTE_PUBBLICO_VIGILATO=>"Enti pubblici vigilati",
            self::AA_ORGANISMI_ENTE_PRIVATO_CONTROLLATO=>"Enti di diritto privato in controllo pubblico",
            self::AA_ORGANISMI_SOCIETA_PARTECIPATA=>"Società partecipate"
            );
        }

        if(self::$FORMA_GIURIDICA==null)
        {
        self::$FORMA_GIURIDICA=array(
            self::AA_ORGANISMI_FORMA_GIURIDICA_NONE=>"n.d.",
            self::AA_ORGANISMI_FORMA_GIURIDICA_SPA=>"SPA",
            self::AA_ORGANISMI_FORMA_GIURIDICA_SRL=>"SRL",
            self::AA_ORGANISMI_FORMA_GIURIDICA_SCARL=>"SCARL",
            self::AA_ORGANISMI_FORMA_GIURIDICA_SCPA=>"SCPA",
            self::AA_ORGANISMI_FORMA_GIURIDICA_CONSORZIO=>"CONSORZIO"
            );
        }

        if(self::$STATO_SOCIETA==null)
        {
        self::$STATO_SOCIETA=array(
            self::AA_ORGANISMI_STATO_SOCIETA_NONE=>"n.d.",
            self::AA_ORGANISMI_STATO_SOCIETA_ATTIVO=>"attiva",
            self::AA_ORGANISMI_STATO_SOCIETA_CESSATO=>"cessata",
            self::AA_ORGANISMI_STATO_SOCIETA_INATTIVA=>"inattiva",
            self::AA_ORGANISMI_STATO_SOCIETA_IN_LIQUIDAZIONE=>"in liquidazione",
            self::AA_ORGANISMI_STATO_SOCIETA_IN_LIQUIDAZIONE_COATTA=>"in liquidazione coatta amministrativa",
            self::AA_ORGANISMI_STATO_SOCIETA_IN_CONCORDATO=>"in concordato",
            self::AA_ORGANISMI_STATO_SOCIETA_IN_CONCORDATO_LIQUIDAZIONE=>"in concordato preventivo e in liquidazione",
            self::AA_ORGANISMI_STATO_SOCIETA_IN_FALLIMENTO=>"in fallimento",
            self::AA_ORGANISMI_STATO_SOCIETA_SCIOGLIMENTO_LIQUIDAZIONE=>"in scioglimento e liquidazione"
            );
        }

        if(self::$TIPO_BILANCIO==null)
        {
        self::$TIPO_BILANCIO=array(
            self::AA_ORGANISMI_NONE=>"n.d.",
            self::AA_ORGANISMI_BILANCIO_PRECONSUNTIVO=>"Bilancio preconsuntivo",
            self::AA_ORGANISMI_BILANCIO_CONSUNTIVO=>"Bilancio consuntivo",
            self::AA_ORGANISMI_BILANCIO_CONSOLIDATO=>"Bilancio consolidato",
            self::AA_ORGANISMI_BILANCIO_ESERCIZIO=>"Bilancio d'esercizio",
            self::AA_ORGANISMI_BILANCIO_PREVISIONE=>"Bilancio di previsione",
            self::AA_ORGANISMI_BILANCIO_RISULTATO_AMMINISTRAZIONE=>"Risultato di amministrazione",
            self::AA_ORGANISMI_BILANCIO_RISULTATO_GESTIONE=>"Risultato di gestione"
            );
        }

        if(self::$TIPO_NOMINE==null)
        {
        self::$TIPO_NOMINE=array(
            self::AA_ORGANISMI_NONE=>"n.d.",
            self::AA_ORGANISMI_NOMINA_AMMINISTRATORE_UNICO=>"Amministratore unico",
            self::AA_ORGANISMI_NOMINA_AMMINISTRATORE_DELEGATO=>"Amministratore delegato",
            self::AA_ORGANISMI_NOMINA_COMMISSARIO_LIQUIDATORE=>"Commissario liquidatore",
            self::AA_ORGANISMI_NOMINA_COMMISSARIO_STRAORDINARIO=>"Commissario straordinario",
            self::AA_ORGANISMI_NOMINA_COMPONENTE_COMITATO_SCIENTIFICO=>"Componente comitato",
            self::AA_ORGANISMI_NOMINA_COMPONENTE_CONSIGLIO_DIRETTIVO=>"Componente consiglio direttivo",
            self::AA_ORGANISMI_NOMINA_COMPONENTE_OIV=>"Componente OIV",
            self::AA_ORGANISMI_NOMINA_COMPONENTE_ORGANO_INDIRIZZO=>"Componente organo di indirizzo",
            self::AA_ORGANISMI_NOMINA_CONSIGLIERE=>"Consigliere CDA",
            self::AA_ORGANISMI_NOMINA_AMMINISTRATORE_DELEGATO_CDA=>"Consigliere CDA e Amministratore delegato",
            self::AA_ORGANISMI_NOMINA_DIRETTORE=>"Direttore",
            self::AA_ORGANISMI_NOMINA_DIRETTORE_GENERALE=>"Direttore generale",
            self::AA_ORGANISMI_NOMINA_DIRETTORE_TECNICO=>"Direttore tecnico",
            self::AA_ORGANISMI_NOMINA_LIQUIDATORE=>"Liquidatore",
            self::AA_ORGANISMI_NOMINA_LIQUIDATORE_GIUDIZIARIO=>"Liquidatore giudiziario",
            self::AA_ORGANISMI_NOMINA_PRESIDENTE_NON_CDA=>"Presidente",
            self::AA_ORGANISMI_NOMINA_PRESIDENTE=>"Presidente CDA",
            self::AA_ORGANISMI_NOMINA_PRESIDENTE_COLLEGGIO_REVISORE=>"Presidente collegio revisori dei conti",
            self::AA_ORGANISMI_NOMINA_PRESIDENTE_COLLEGGIO=>"Presidente collegio sindacale",
            self::AA_ORGANISMI_NOMINA_PRESIDENTE_CONSIGLIO_DIRETTIVO=>"Presidente consiglio direttivo",
            self::AA_ORGANISMI_NOMINA_PRESIDENTE_CONSIGLIO_INDIRIZZO=>"Presidente organo consultivo",
            self::AA_ORGANISMI_NOMINA_PRESIDENTE_ORGANO_INDIRIZZO=>"Presidente organo di indirizzo",
            self::AA_ORGANISMI_NOMINA_PRESIDENTE_ONORARIO=>"Presidente onorario",
            self::AA_ORGANISMI_NOMINA_PRESIDENTE_OIV=>"Presidente OIV",
            self::AA_ORGANISMI_NOMINA_REVISORE_CONTI=>"Revisore dei conti",
            self::AA_ORGANISMI_NOMINA_REVISORE_CONTI_SUPPLENTE=>"Revisore dei conti supplente",
            self::AA_ORGANISMI_NOMINA_REVISORE_UNICO=>"Revisore unico",
            self::AA_ORGANISMI_NOMINA_SINDACO_COLLEGGIO=>"Sindaco",
            self::AA_ORGANISMI_NOMINA_COMPONENTE_SUPPLENTE_COLLEGGIO=>"Sindaco supplente",
            self::AA_ORGANISMI_NOMINA_VICEPRESIDENTE=>"Vice Presidente",
            self::AA_ORGANISMI_NOMINA_VICEPRESIDENTE_CDA=>"Vice Presidente CDA"
            );
        }

        if(self::$TIPO_DOCS==null)
        {
            self::$TIPO_DOCS=array(
            self::AA_ORGANISMI_DOC_NONE=>"Documento generico",
            self::AA_ORGANISMI_DOC_CURRICULUM=>"Curriculum",
            self::AA_ORGANISMI_DOC_DICH_INCONFERIBILITA=>"Dichiarazione di assenza di cause di inconferibilità",
            self::AA_ORGANISMI_DOC_DICH_INCOMPATIBILITA=>"Dichiarazione di assenza di cause di incompatibilità",
            self::AA_ORGANISMI_DOC_PROVVEDIMENTO_NOMINA=>"Provvedimento di nomina/designazione",
            self::AA_ORGANISMI_DOC_PROVVEDIMENTO_PROROGA=>"Provvedimento di proroga",
            self::AA_ORGANISMI_DOC_PROVVEDIMENTO_REVOCA=>"Provvedimento di revoca",
            self::AA_ORGANISMI_DOC_ATTESTAZIONE_VERIFICA=>"Attestazione di verifica delle dichiarazioni (art.53 d.lgs 165/2001)"
            );
        }
        
        if(self::$TIPO_PROVV==null)
        {
            self::$TIPO_PROVV=array(
            self::AA_ORGANISMI_PROVV_NONE=>"Provvedimento generico",
            self::AA_ORGANISMI_PROVV_ATTI_INDIRIZZO=>"Atti di indirizzo",
            self::AA_ORGANISMI_PROVV_COSTITUZIONE=>"Costituzione",
            self::AA_ORGANISMI_PROVV_ACQUISTO_PARTECIPAZIONI=>"Acquisto partecipazioni",
            self::AA_ORGANISMI_PROVV_GESTIONE=>"Gestione",
            self::AA_ORGANISMI_PROVV_ALIENAZIONE=>"Alienazione",
            self::AA_ORGANISMI_PROVV_QUOTAZIONE=>"Quotazione in mercati regolamentati",
            self::AA_ORGANISMI_PROVV_RAZIONALIZZAZIONE=>"Razionalizzazione",
            self::AA_ORGANISMI_PROVV_LIQUIDAZIONE=>"Liquidazione",
            self::AA_ORGANISMI_PROVV_TUPI=>"Provvedimenti ex art. 53 del TUPI"
            );
        }

        if(self::$TIPO_ORGANIGRAMMA==null)
        {
            self::$TIPO_ORGANIGRAMMA=array(
                self::AA_ORGANISMI_ORGANIGRAMMA_NONE=>"Nessuno",
                self::AA_ORGANISMI_ORGANIGRAMMA_INDIRIZZO=>"Organo di indirizzo",
                self::AA_ORGANISMI_ORGANIGRAMMA_AMMINISTRATIVO=>"Organo di amministrazione",
                self::AA_ORGANISMI_ORGANIGRAMMA_CONTROLLO=>"Organo di controllo",
                self::AA_ORGANISMI_ORGANIGRAMMA_DIREZIONALE=>"Organo di gestione",
                self::AA_ORGANISMI_ORGANIGRAMMA_CONSULTIVO=>"Organo consultivo",
            );
        }
    }

    //Restituisce la lista della tipologia degli organigrammi
    static public function GetListaTipoOrganigramma()
    {
        self::Initialize();

        return self::$TIPO_ORGANIGRAMMA;
    } 
    
    //Restituisce la lista degli stati società
    static public function GetListaStatoOrganismi()
    {
        self::Initialize();

        return self::$STATO_SOCIETA;
    } 

    //Restituisce la lista della forma giuridica
    static public function GetListaFormaGiuridica()
    {
        self::Initialize();

        return self::$FORMA_GIURIDICA;
    } 

    //Restituisce la lista del tipo di documentazione
    static public function GetTipoDocs()
    {
        self::Initialize();

        return self::$TIPO_DOCS;
    }
    
    //Restituisce la lista del tipo di provvedimenti
    static public function GetTipoProvvedimenti()
    {
        self::Initialize();

        return self::$TIPO_PROVV;
    }

    //Restituisce la lista del tipo di bilancio
    static public function GetTipoBilanci()
    {
        self::Initialize();

        return self::$TIPO_BILANCIO;
    }

    //Restituisce la lista del tipo di nomina
    static public function GetTipoNomine()
    {
        self::Initialize();

        return self::$TIPO_NOMINE;
    }

    //Restituisce la lista del tipo organismi
    static public function GetTipoOrganismi($bPlurali=false)
    {
        self::Initialize();

        if($bPlurali) return self::$TIPOLOGIA_PLURALI;
        
        return self::$TIPOLOGIA;
    }

    //Restituisce una rappresentazione in xml delle costanti
    static public function toXML()
    {
        $result="<AA_Organismi_const>";
        $result.="<tipologia_organismi>";
        foreach(AA_Organismi_Const::GetTipoOrganismi() as $key=>$value)
        {
            $result.='<tipo_organismo id="'.$key.'">';
            $result.='<descrizione>'.$value.'</descrizione>';
            $result.="</tipo_organismo>";
        }
        $result.="</tipologia_organismi>";

        return $result;
    }
}

/*La classe "AA_Organismi_Organigramma_Incarico" rappresenta un incarico all'interno di un organigramma.

Il costruttore della classe ha un parametro opzionale "$data" che, se passato come un array, 
viene utilizzato per inizializzare le proprietà dell'oggetto tramite il metodo "ParseData()".

Ci sono diversi metodi nella classe per controllare le proprietà dell'oggetto, come ad esempio "IsNominaRas()" 
per verificare se l'incarico è una nomina RAS, "IsOpzionale()" per verificare se l'incarico è opzionale e "IsScadenzarioEnabled()"
per verificare se l'incarico deve avere lo scadenzario abilitato.

Il metodo "GetTipologia()" restituisce la tipologia dell'incarico, che può essere passata come parametro booleano 
per restituire l'indice numerico della tipologia invece del nome.

La classe ha anche metodi per impostare e ottenere le proprietà dell'oggetto.

Il metodo statico "LoadFromDb()" viene utilizzato per caricare i dati dell'incarico dal database, 
prendendo come parametro l'ID dell'incarico. Il metodo restituisce un'istanza della classe "AA_Organismi_Organigramma_Incarico" 
con le proprietà inizializzate.*/
Class AA_Organismi_Organigramma_Incarico
{
    //Costruttore di default
    public function __construct($data="")
    {
        $this->props['ordine']=0;
        if(is_array($data)) $this->ParseData($data);
    }

    public function IsNominaRas()
    {
        if($this->props['ras'] > 0) return true;
        return false;
    }

    public function IsOpzionale()
    {
        if($this->props['opzionale'] > 0) return true;
        return false;
    }

    public function IsScadenzarioEnabled()
    {
        if($this->props['ras'] > 0 || $this->props['forza_scadenzario'] > 0) return true;
        return false;
    }

    public function GetTipologia($bNumeric=False)
    {
        if($bNumeric) return $this->props['tipo'];
        
        $tipo=AA_Organismi_Const::GetTipoNomine();
        return $tipo[$this->props['tipo']];
    }

    //Props
    protected $props=array();
    protected $bValid=false;
    public function IsValid()
    {
        if($this->bValid) return true;
        else return false;
    }

    //Importa i dati
    public function ParseData($data="")
    {
        if(is_array($data))
        {
            foreach($data as $key=>$val)
            {
                if($key=="id") $this->props['id']=$val;
                if($key=="id_incarico") $this->props['id']=$val;
                if($key=="tipo") $this->props['tipo']=$val;
                if($key=="id_organigramma") $this->props['id_organigramma']=$val;
                if($key=="ras") $this->props['ras']=$val;
                if($key=="ordine") $this->props['ordine']=$val;
                if($key=="note") $this->props['note']=$val;
                if($key=="opzionale") $this->props['opzionale']=$val;
                if($key=="forza_scadenzario") $this->props['forza_scadenzario']=$val;
                if($key=="compenso_spettante") $this->props['compenso_spettante']=$val;
                if($key=="ordine") $this->props['ordine']=$val;
            }

            return true;
        }
        else return false;
    }

    //Restituisce l'id
    public function GetId()
    {
        return $this->props['id'];
    }

    //Restituisce una proprietà
    public function SetProp($prop="",$val="")
    {
        if($prop != "") return $this->ParseData(array($prop=>$val));
    }

    //Imposta una proprietà
    public function GetProp($prop="")
    {
        if($prop != "") return $this->props[$prop];
    }

    //Restituisce le proprietà
    public function GetProps()
    {
        return $this->props;
    }

    //Carica i dati dal database
    static function LoadFromDb($id=0)
    {
        $incarico=new AA_Organismi_Organigramma_Incarico();

        $db=new AA_Database();
        $query="SELECT * from ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA_INCARICHI." WHERE id ='".addslashes($id)."' LIMIT 1";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
            return $incarico;
        }
         
        if($db->GetAffectedRows() > 0)
        {
            $rs=$db->GetResultSet();

            $incarico->ParseData($rs[0]);
           
            $incarico->bValid=true;
        }

        return $incarico;
    }
}

//Classe organigramma
Class AA_Organismi_Organigramma
{
    //Costruttore di default
    public function __construct($data="")
    {
        if(is_array($data)) $this->ParseData($data);
    }

    public function IsScadenzarioEnabled()
    {
        if($this->organigramma_props['enable_scadenzario'] > 0) return true;
        return false;
    }

    //Props
    protected $organigramma_props=array();
    protected $organigramma_incarichi=null;
    protected $bValid=false;

    public function GetTipologia($bNumeric=False)
    {
        if($bNumeric) return $this->organigramma_props['tipo'];
        
        $tipo=AA_Organismi_Const::GetListaTipoOrganigramma();
        return $tipo[$this->organigramma_props['tipo']];
    }

    //Importa i dati
    public function ParseData($data="")
    {
        if(is_array($data))
        {
            foreach($data as $key=>$val)
            {
                if($key=="id") $this->organigramma_props['id']=$val;
                if($key=="id_organigramma") $this->organigramma_props['id']=$val;
                if($key=="tipo") $this->organigramma_props['tipo']=$val;
                if($key=="id_organismo") $this->organigramma_props['id_organismo']=$val;
                if($key=="enable_scadenzario") $this->organigramma_props['enable_scadenzario']=$val;
                if($key=="dal") $this->organigramma_props['dal']=$val;
                if($key=="al") $this->organigramma_props['al']=$val;
                if($key=="ordine") $this->organigramma_props['ordine']=$val;
                if($key=="note") $this->organigramma_props['note']=$val;
                if($key=="incarichi" && is_array($val))
                {
                    $this->organigramma_incarichi=array();
                    foreach($val as $curIncarico)
                    {
                        if($curIncarico instanceof AA_Organismi_Organigramma_Incarico)
                        {
                            $this->organigramma_incarichi[]=$curIncarico;
                        }
                    }
                }
            }

            return true;
        }
        else return false;
    }

    //rappresentazione xml
    public function toXml($detail=true)
    {
        $incarichi=$this->GetIncarichi();
        if(!is_array($incarichi))
        {
            return '<organigramma id="'.$this->GetId().'" tipo="'.$this->GetTipologia().'" componentiTot="0" componentiRas="0" />';
        }
        $incarichiTot=sizeof($incarichi);
        $nomineRasTot=0;
        $incarichi_xml="";
        if($detail) $incarichi_xml="<incarichi>";
        foreach($incarichi as $curIncarico)
        {
            if($curIncarico->IsNominaRas()) $nomineRasTot++;
            if($detail)
            {
                $incarichi_xml.=$curIncarico->ToXml();
            }
        }
        if($detail) $incarichi_xml="</incarichi>";

        $xml='<organigramma id="'.$this->GetId().'" tipo="'.$this->GetTipologia().'" componentiTot="'.$incarichiTot.'" componentiRas="'.$nomineRasTot.'">';
        $xml.=$incarichi_xml;
        $xml.="</organigramma>";

        return $xml;
    }

    //Restituisce una proprietà
    public function SetProp($prop="",$val="")
    {
        if($prop != "") return $this->ParseData(array($prop=>$val));
    }

    //Imposta una proprietà
    public function GetProp($prop="")
    {
        if($prop != "") return $this->organigramma_props[$prop];
    }

    public function GetId()
    {
        return $this->GetProp("id");
    }

    //Restituisce le proprietà
    public function GetProps()
    {
        return $this->organigramma_props;
    }

    public function GetIncarichi()
    {
        if($this->organigramma_incarichi==null && $this->organigramma_props['id'] > 0)
        {
            $db=new AA_Database();
            $query="SELECT * from ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA_INCARICHI." WHERE id_organigramma ='".addslashes($this->organigramma_props['id'])."'";
                
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
                $this->organigramma_incarichi=array();
            }
            
            if($db->GetAffectedRows() > 0)
            {
                $rs=$db->GetResultSet();
                $this->organigramma_incarichi=array();
                foreach($rs as $curIncarico)
                {
                    $this->organigramma_incarichi[]=new AA_Organismi_Organigramma_Incarico($curIncarico);
                }
            }
        }
        return $this->organigramma_incarichi;
    }

    //Carica i dati dal database
    static function LoadFromDb($id=0)
    {
        $organigramma=new AA_Organismi_Organigramma();

        $db=new AA_Database();
        $query="SELECT * from ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA." WHERE id ='".addslashes($id)."' LIMIT 1";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
            return $organigramma;
        }
         
        if($db->GetAffectedRows() > 0)
        {
           $rs=$db->GetResultSet();

           if($organigramma->ParseData($rs[0]))
           {
                $query="SELECT * from ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA_INCARICHI." WHERE id_organigramma ='".addslashes($id)."'";
                
                if(!$db->Query($query))
                {
                    AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
                    return $organigramma;
                }
                
                if($db->GetAffectedRows() > 0)
                {
                    $rs=$db->GetResultSet();
                    foreach($rs as $curIncarico)
                    {
                        $organigramma_incarichi[]=new AA_Organismi_Organigramma_Incarico($curIncarico);
                    }
                }

                $organigramma->bValid=true;
           }
        }

        return $organigramma;
    }
}

//Classe Organismi
/**
 * Summary of AA_Organismi
 */
class AA_Organismi extends AA_Object
{
    //Costruttore di default
    /**
     * Summary of __construct
     * @param mixed $id
     * @param mixed $user
     */
    public function __construct($id=0,$user=null)
    {
        AA_Log::Log(get_class()."__construct($id)");

        //Chiama il costruttore base
        parent::__construct($user);

        //Disattiva la revisione
        $this->SetStatusMask(AA_Const::AA_STATUS_BOZZA|AA_Const::AA_STATUS_PUBBLICATA|AA_Const::AA_STATUS_CESTINATA);

        $this->setType(AA_Const::AA_OBJECT_ART22);

        //Imposta la tabella del db sul db bind
        $this->oDbBind->SetTable(AA_Organismi_Const::AA_ORGANISMI_DB_TABLE);

        //Abilita l'aggiornamento dello stato nel db
        $this->EnableStatusDbSync();

        //Abilita l'aggiornamento del campo aggiornamento nel db
        $this->EnableAggiornamentoDbSync();

        //Abilita l'aggiornamento del campo utente nel db
        $this->EnableUserDbSync();

        //Abilita l'aggiornamento della struttura nel db
        $this->EnableStructDbSync();

        //Abilita la gestione dei logs
        $this->bLogEnabled=true;

        //Aggiunge i bindings ai campi del db
        $this->oDbBind->AddBind("sDescrizione","denominazione");
        $this->oDbBind->AddBind("nTipologia","tipo");
        $this->oDbBind->AddBind("sFunzioni","funzioni");
        $this->oDbBind->AddBind("sPartecipazione","partecipazione");
        $this->oDbBind->AddBind("sSitoWeb","sito_web");
        $this->oDbBind->AddBind("sDataInizioImpegno","data_inizio_impegno");
        $this->oDbBind->AddBind("sDataFineImpegno","data_fine_impegno");
        $this->oDbBind->AddBind("sNote","note");
        $this->oDbBind->AddBind("sPivaCf","piva_cf");
        $this->oDbBind->AddBind("sSedeLegale","sede_legale");
        $this->oDbBind->AddBind("sPec","pec");
        $this->oDbBind->AddBind("bInHouse","inhouse");
        $this->oDbBind->AddBind("bInTUSP","tusp");
        $this->oDbBind->AddBind("bMercatiReg","mercati_reg");
        $this->oDbBind->AddBind("nFormaSocietaria","forma_societaria");
        $this->oDbBind->AddBind("nStatoOrganismo","stato_organismo");
        
        if($id > 0) $this->LoadFromDb($id,$user);
        else $this->SetId(0);
    }

    //Denominazione
    /**
     * Summary of GetDenominazione
     * @return mixed|string
     */
    public function GetDenominazione()
    {
        return $this->GetDescrizione();
    }
    /**
     * Summary of SetDenominazione
     * @param mixed $var
     * @return void
     */
    public function SetDenominazione($var="denominazione organismo")
    {
        $this->SetDescrizione($var);
    }
    
    //Tipologia
    /**
     * Summary of nTipologia
     * @var int
     */
    protected $nTipologia=0;
    /**
     * Summary of SetTipologia
     * @param mixed $var
     * @return void
     */
    public function SetTipologia($var=0)
    {
        if(is_numeric($var) && $var >= 0)
        {
            $this->nTipologia=$var;
            $this->SetChanged();
        } 
        
    }
    /**
     * Summary of GetTipologia
     * @param mixed $bNumeric
     * @return float|int|string
     */
    public function GetTipologia($bNumeric=false)
    {
        if($bNumeric) return $this->nTipologia;

        $tipologia=AA_Organismi_Const::GetTipoOrganismi();
        return $tipologia[$this->nTipologia];
    }
    
    //Funzioni attribuite
    /**
     * Summary of sFunzioni
     * @var string
     */
    protected $sFunzioni="";
    /**
     * Summary of SetFunzioni
     * @param mixed $var
     * @return void
     */
    public function SetFunzioni($var="")
    {
        if($this->sFunzioni != $var)
        {
            $this->SetChanged();
            $this->sFunzioni=$var;
        } 
    }
    /**
     * Summary of GetFunzioni
     * @return mixed|string
     */
    public function GetFunzioni()
    {
        return $this->sFunzioni;
    }

    //Partecipazione
    /**
     * Summary of sPartecipazione
     * @var string
     */
    protected $sPartecipazione="";
    /**
     * Summary of SetPartecipazione
     * @param mixed $var
     * @return void
     */
    public function SetPartecipazione($var="")
    {
        if($this->sPartecipazione != $var)
        {
            $this->SetChanged();
            $this->sPartecipazione=$var;
        } 
    }
    /**
     * Summary of GetPartecipazione
     * @return array|string
     */
    public function GetPartecipazione($bAsObject=false)
    {
        if(!$bAsObject) return $this->sPartecipazione;
        else
        {
            if($this->nTipologia == AA_Organismi_Const::AA_ORGANISMI_ENTE_PUBBLICO_VIGILATO) return array("percentuale"=>100,"euro"=>0);
            $partecipazione=json_decode($this->sPartecipazione,true);
            if($partecipazione) return $partecipazione;
            else 
            {
                if($this->sPartecipazione!="")
                {
                    $data=explode("/",$this->sPartecipazione);
                    $partecipazione=array(
                        "percentuale"=>str_replace(",",".",str_replace(".","",$data[1])),
                        "euro"=>str_replace(",",".",str_replace(".","",$data[0]))
                    );

                    return $partecipazione;
                }
                else
                {
                    return array("percentuale"=>0,"euro"=>0);
                }
            }
        }
    }
    
    public function GetListaOrganismiPartecipabili($bFull=false,$user=null)
    {
        if(!$this->isValid()) return array();

        $select="SELECT DISTINCT ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id,".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".denominazione  FROM ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE." ";
        $join="";
        $where="";
        $group="";
        $having="";
        $order=" ORDER BY denominazione ASC";
        //$join.=" LEFT JOIN incarichi on incarichi_titolari.id=incarichi.id_incarico ";
        $group="";

        //Filtra in base allo stato della scheda
        $where.=" WHERE id <> ".$this->nID." AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".status ='".AA_Const::AA_STATUS_PUBBLICATA."' ";

        $discendenti=$this->GetOrganismiDiscendenti(false,$user);
        if(sizeof($discendenti)>0)
        {
            $where.=" AND id NOT IN (".implode(",",$discendenti).")";
        }

        //solo societa'
        $where.=" AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".tipo & ".(AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA|AA_Organismi_Const::AA_ORGANISMI_ENTE_PUBBLICO_VIGILATO)." > 0 ";

        //partecipazioni attuali
        $partecipazione=$this->GetPartecipazione(true);
        $partecipazioni=array();
        if(isset($partecipazione['partecipazioni'])) $partecipazioni=array_keys($partecipazione['partecipazioni']);
        
        $db=new AA_Database();
        if(!$db->Query($select.$where.$group.$having.$order))
        {
            AA_Log::Log(__METHOD__." - errore: ".$db->GetErrorMessage()." - query: ".$select.$where.$group.$having.$order,100);
            return array();
        }

        //AA_Log::Log(__METHOD__." - query: ".$select.$where.$group.$having.$order,100);

        $rs=$db->GetResultSet();
        $result=array();
        foreach($rs as $curRow)
        {
            if(!in_array($curRow['id'],$partecipazioni,true) || sizeof($partecipazioni)==0)
            {
                if($bFull) $result[$curRow['id']]=new AA_Organismi($curRow['id']);
                else $result[$curRow['id']]=$curRow['denominazione'];
            }
        }

        //AA_Log::Log(__METHOD__." - result: ".print_r($result,true),100);
        return $result;
    }

    static protected function GetOrganismiAscendenti($idOrg=0)
    {
        return array();
    }

    //Restituisce la partecipazione indiretta totale
    protected function RecursiveParentPartecipazioneIndirettaCalc($user=null)
    {
        $percent=0;

        if(!$this->IsValid()) return $percent;

        //partecipazione
        $partecipazione=$this->GetPartecipazione(true);
        $percent=$partecipazione['percentuale'];
        foreach($partecipazione['partecipazioni'] as $idParent=>$parentPartecipazione)
        {
            $parent=new AA_Organismi($idParent,$user);
            $percent+=($parentPartecipazione['percentuale'])*(0.01*$parent->RecursiveParentPartecipazioneIndirettaCalc($user));
        }

        //AA_Log::Log(__METHOD__." - percent: ".print_r($percent,true),100);
        return $percent;
    }

    public function GetPartecipazioneIndiretta($user=null)
    {
        $result=array("percentuale"=>0.00,"euro"=>0.00);
        if(!$this->IsValid()) return $result;
        
        $partecipazione=$this->GetPartecipazione(true);
        foreach($partecipazione['partecipazioni'] as $idParent=>$parentPartecipazione)
        {
            $parent=new AA_Organismi($idParent,$user);
            $parentPercent=(0.01*$parent->RecursiveParentPartecipazioneIndirettaCalc($user));
            
            $result['percentuale']+=$parentPartecipazione['percentuale']*$parentPercent;
            $result['euro']+=$parentPartecipazione['euro']*$parentPercent;
        }
       
        //AA_Log::Log(__METHOD__." - indiretta: ".print_r($result,true),100);
        return $result;
    }

    protected static function GetListaOrganismiDiscendenti($idOrg=0)
    {
        if($idOrg<=0) return array();

        $select="SELECT DISTINCT ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id,".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione  FROM ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE." ";
        $join="";
        $where="";
        $group="";
        $having="";
        $order="";
        //$join.=" LEFT JOIN incarichi on incarichi_titolari.id=incarichi.id_incarico ";
        $group="";

        //Filtra in base allo stato della scheda
        $where.=" WHERE id <> ".$idOrg." AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".status ='".AA_Const::AA_STATUS_PUBBLICATA."' ";

        //solo societa'
        $where.=" AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".tipo & ".AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA." > 0 ";

        //solo organismi che partecipano di questo organismo
        $where.=" AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione like '%\"".$idOrg."\":%' ";
        
        $db=new AA_Database();
        if(!$db->Query($select.$where.$group.$having.$order))
        {
            AA_Log::Log(__METHOD__." - errore: ".$db->GetErrorMessage()." - query: ".$select.$where.$group.$having.$order,100);
            return array();
        }

        $rs=$db->GetResultSet();
        $result=array();
        foreach($rs as $curRow)
        {
            $result[]=$curRow['id'];
            $result=array_merge($result,static::GetListaOrganismiDiscendenti($curRow['id']));
        }

        //AA_Log::Log(__METHOD__." - discendenti ($idOrg): ".print_r($result,true),100);

        return $result;
    }

    public function GetOrganismiDiscendenti($bFull=false,$user=null)
    {
        if(!$this->IsValid()) return array();

        $result=static::GetListaOrganismiDiscendenti($this->GetID());

        //AA_Log::Log(__METHOD__." - discendenti: ".print_r($result,true),100);

        if(!$bFull) return $result;

        $orgs=array();
        foreach($result as $idOrg)
        {
            $newOrg=new AA_Organismi($idOrg,$user);
            if($newOrg->IsValid()) $orgs[$newOrg->GetId()]=$newOrg;
        }

        return $orgs;
    }

    public function GetListaOrganismiPartecipati($bFull=false)
    {
        if(!$this->isValid()) return array();

        $select="SELECT DISTINCT ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id,".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".denominazione,".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione  FROM ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE." ";
        $join="";
        $where="";
        $group="";
        $having="";
        $order=" ORDER BY denominazione, aggiornamento DESC";
        //$join.=" LEFT JOIN incarichi on incarichi_titolari.id=incarichi.id_incarico ";
        $group=" GROUP BY ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id ";

        //Filtra in base allo stato della scheda
        $where.=" WHERE id <> ".$this->nID." AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".status ='".AA_Const::AA_STATUS_PUBBLICATA."' ";

        //solo societa'
        $where.=" AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".tipo & ".AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA." > 0 ";

        //solo organismi che partecipano di questo organismo
        $where.=" AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione like '%\"".$this->nID."\":%' ";
        
        $db=new AA_Database();
        if(!$db->Query($select.$where.$group.$having.$order))
        {
            AA_Log::Log(__METHOD__." - errore: ".$db->GetErrorMessage()." - query: ".$select.$where.$group.$having.$order,100);
            return array();
        }

        $rs=$db->GetResultSet();
        $result=array();
        foreach($rs as $curRow)
        {
            if($bFull) $result[$curRow['id']]=new AA_Organismi($curRow['id']);
            else $result[$curRow['id']]=array("denominazione"=>$curRow['denominazione'],"partecipazione"=>json_decode($curRow['partecipazione'],true));
        }

        return $result;
    }

    //Forma societaria
    /**
     * Summary of nFormaSocietaria
     * @var int
     */
    protected $nFormaSocietaria=0;
    /**
     * Summary of SetFormaSocietaria
     * @param mixed $var
     * @return void
     */
    public function SetFormaSocietaria($var=0)
    {
        if($this->nFormaSocietaria != $var)
        {
            $this->SetChanged();
            $this->nFormaSocietaria=$var;
        } 
    }
    /**
     * Summary of GetFormaSocietaria
     * @param mixed $bNumeric
     * @return int|mixed|string
     */
    public function GetFormaSocietaria($bNumeric=false)
    {
        if($bNumeric) return $this->nFormaSocietaria;
        else
        {
            $forma_giuridica=AA_Organismi_Const::GetListaFormaGiuridica();
            return $forma_giuridica[$this->nFormaSocietaria];
        }
    }

    //Stato società
    /**
     * Summary of nStatoOrganismo
     * @var int
     */
    protected $nStatoOrganismo=0;
    /**
     * Summary of SetStatoOrganismo
     * @param mixed $var
     * @return void
     */
    public function SetStatoOrganismo($var=0)
    {
        if($this->nStatoOrganismo != $var)
        {
            $this->SetChanged();
            $this->nStatoOrganismo=$var;
        } 
    }
    /**
     * Summary of GetStatoOrganismo
     * @param mixed $bNumeric
     * @return int|mixed|string
     */
    public function GetStatoOrganismo($bNumeric=false)
    {
        if($bNumeric) return $this->nStatoOrganismo;
        else
        {
            $val=AA_Organismi_Const::GetListaStatoOrganismi();
            return $val[$this->nStatoOrganismo];
        }
    }

    //Sito web
    /**
     * Summary of sSitoWeb
     * @var string
     */
    protected $sSitoWeb="";
    /**
     * Summary of SetSitoWeb
     * @param mixed $var
     * @return void
     */
    public function SetSitoWeb($var="")
    {
        if($this->sSitoWeb != $var)
        {
            $this->SetChanged();
            $this->sSitoWeb=$var;
        } 
    }
    /**
     * Summary of GetSitoWeb
     * @return mixed|string
     */
    public function GetSitoWeb()
    {
        return $this->sSitoWeb;
    }

    //Pec
    /**
     * Summary of sPec
     * @var string
     */
    protected $sPec="";
    /**
     * Summary of SetPec
     * @param mixed $var
     * @return void
     */
    public function SetPec($var="")
    {
        if($this->sPec != $var)
        {
            $this->SetChanged();
            $this->sPec=$var;
        } 
    }
    /**
     * Summary of GetPec
     * @return mixed|string
     */
    public function GetPec()
    {
        return $this->sPec;
    }

    //Restituisce i provvedimenti
    /**
     * Summary of GetProvvedimenti
     * @return array<AA_OrganismiProvvedimenti>
     */
    public function GetProvvedimenti()
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__."() - oggetto non valido.");

            return array();
        }

        //Impostazione dei parametri
        $query="SELECT * from ".AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_DB_TABLE." where id_organismo='".$this->GetId()."'";
        $query.= " ORDER by anno DESC, tipo, id";

        $db=new AA_Database();
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query: ".$query,100);
            return array();
        }

        $result=array();

        $rs=$db->GetResultSet();
        foreach($rs as $curRec)
        {   
            $provvedimento=new AA_OrganismiProvvedimenti($curRec['id'],$this->GetId(),$curRec['tipo'],$curRec['url'],$curRec['anno'],$curRec['estremi']);
            $result[$curRec['id']]=$provvedimento;
        }

        return $result;
    }
    
    //Data inizio impegno
    /**
     * Summary of sDataInizioImpegno
     * @var string
     */
    protected $sDataInizioImpegno="";
    /**
     * Summary of SetDataInizioImpegno
     * @param mixed $var
     * @return void
     */
    public function SetDataInizioImpegno($var="")
    {
        if($this->sDataInizioImpegno != $var)
        {
            $this->SetChanged();
            $this->sDataInizioImpegno=$var;
        } 
    }
    /**
     * Summary of GetDataInizioImpegno
     * @return mixed|string
     */
    public function GetDataInizioImpegno()
    {
        return $this->sDataInizioImpegno;
    }

    //Data fine impegno
    /**
     * Summary of sDataFineImpegno
     * @var string
     */
    protected $sDataFineImpegno="";
    /**
     * Summary of SetDataFineImpegno
     * @param mixed $var
     * @return void
     */
    public function SetDataFineImpegno($var="")
    {
        if($this->sDataFineImpegno != $var)
        {
            $this->SetChanged();
            $this->sDataFineImpegno=$var;
        } 
    }
    /**
     * Summary of GetDataFineImpegno
     * @return mixed|string
     */
    public function GetDataFineImpegno()
    {
        return $this->sDataFineImpegno;
    }

    //PivaCf
    /**
     * Summary of sPivaCf
     * @var string
     */
    protected $sPivaCf="";
    /**
     * Summary of SetPivaCf
     * @param mixed $var
     * @return void
     */
    public function SetPivaCf($var="")
    {
        if($this->sPivaCf != $var)
        {
            $this->SetChanged();
            $this->sPivaCf=$var;
        } 
    }
    /**
     * Summary of GetPivaCf
     * @return mixed|string
     */
    public function GetPivaCf()
    {
        return $this->sPivaCf;
    }

    //flag società in house
    /**
     * Summary of bInHouse
     * @var int
     */
    protected $bInHouse=0;
    /**
     * Summary of SetInHouse
     * @param mixed $var
     * @return void
     */
    public function SetInHouse($var=1)
    {
        if($this->bInHouse != $var)
        {
            $this->SetChanged();
            if($var !=0) $this->bInHouse=1;
            else $this->bInHouse=0;
        } 
    }
    /**
     * Summary of GetInHouse
     * @return int
     */
    public function GetInHouse()
    {
        return $this->bInHouse;
    }
    /**
     * Summary of IsInHouse
     * @return bool
     */
    public function IsInHouse()
    {
        if($this->bInHouse!=0) return true;
        else return false;
    }

    //flag società in TUSP
    /**
     * Summary of bInTUSP
     * @var int
     */
    protected $bInTUSP=0;
    /**
     * Summary of SetInTUSP
     * @param mixed $var
     * @return void
     */
    public function SetInTUSP($var=1)
    {
        if($this->bInTUSP != $var)
        {
            $this->SetChanged();
            if($var !=0) $this->bInTUSP=1;
            else $this->bInTUSP = 0;
        } 
    }
    /**
     * Summary of GetInTUSP
     * @return int
     */
    public function GetInTUSP()
    {
        return $this->bInTUSP;
    }
    public function IsInTUSP()
    {
        if($this->bInTUSP!= 0) return true;
        else return false;
    }

    protected $bMercatiReg=0;
    /**
     * Summary of GetInMercatiReg
     * @return int
     */
    public function GetInMercatiReg()
    {
        return $this->bMercatiReg;
    }
    public function SetInMercatiReg($val=true)
    {
        $this->SetChanged();
        $this->bMercatiReg=$val;
    }
    /**
     * Summary of IsInMercatiReg
     * @return bool
     */
    public function IsinMercatiReg()
    {
        if($this->bMercatiReg!= 0) return true;
        else return false;
    }
    
    //note
    /**
     * Summary of sNote
     * @var string
     */
    protected $sNote="";
    /**
     * Summary of SetNote
     * @param mixed $var
     * @return void
     */
    public function SetNote($var="")
    {
        if($this->sNote != $var)
        {
            $this->SetChanged();
            $this->sNote=$var;
        } 
    }
    /**
     * Summary of GetNote
     * @return mixed|string
     */
    public function GetNote()
    {
        return $this->sNote;
    }

    //Sede legale
    /**
     * Summary of sSedeLegale
     * @var string
     */
    protected $sSedeLegale="";
    /**
     * Summary of SetSedeLegale
     * @param mixed $var
     * @return void
     */
    public function SetSedeLegale($var="")
    {
        if($this->sSedeLegale != $var)
        {
            $this->SetChanged();
            $this->sSedeLegale=$var;
        } 
    }
    /**
     * Summary of GetSedeLegale
     * @return mixed|string
     */
    public function GetSedeLegale()
    {
        return $this->sSedeLegale;
    }

    /**
     * Summary of __toString
     * @return mixed|string
     */
    public function __toString()
    {
        return $this->GetDescrizione();
    }

    //Rappresentazione xml
    /**
     * Summary of toXml
     * @return string
     */
    public function toXml()
    {
        $status="bozza";
        if(($this->GetStatus()&AA_Const::AA_STATUS_PUBBLICATA)>0) $status="pubblicato";
        
        $xml='<organismo id="'.$this->GetID().'" aggiornamento="'.$this->GetAggiornamento().'" stato="'.$this->GetStatus().'">';

        $codice_partecipo=array(AA_Organismi_Const::AA_ORGANISMI_ENTE_PUBBLICO_VIGILATO=>"EPV",AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA=>"SP",AA_Organismi_Const::AA_ORGANISMI_ENTE_PRIVATO_CONTROLLATO=>"EDP");
        
        $partecipazione=$this->GetPartecipazione(true);
        $partecipazione_indiretta=$this->GetPartecipazioneIndiretta();
                
        //Aggiunte per interoperabilita' partecipo
        if($partecipazione['percentuale']+$partecipazione_indiretta['percentuale']>=100)
        {
            $xml.="<soggetto_controllato>1</soggetto_controllato>";
        }
        else $xml.="<soggetto_controllato>0</soggetto_controllato>";

        if($partecipazione['percentuale']==0 && $this->nTipologia == AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
        {
            $xml.="<indiretta>1</indiretta>";
        }
        else  $xml.="<indiretta>0</indiretta>";
        $xml.="<mercati_regolamentati>".$this->GetInMercatiReg()."</mercati_regolamentati>";
        $xml.="<partecipazione tot_percent='".($partecipazione['percentuale']+$partecipazione_indiretta['percentuale'])."'><diretta>".json_encode($partecipazione)."</diretta>";
        $xml.="<indiretta>".json_encode($partecipazione_indiretta)."</indiretta></partecipazione>";
        //----------------------------------------

        //parte generale
        $xml.="<denominazione>".mb_encode_numericentity($this->GetDenominazione(),array (0x0, 0xffff, 0, 0xffff), 'UTF-8')."</denominazione>";
        $xml.="<tipologia id_tipo_partecipo='".$codice_partecipo[$this->GetTipologia(true)]."' id_tipo='".$this->GetTipologia(true)."'>".$this->GetTipologia()."</tipologia>";
        $xml.="<cf>".$this->GetPivaCf()."</cf>";
        $xml.="<sede>".mb_encode_numericentity($this->GetSedeLegale(),array (0x0, 0xffff, 0, 0xffff), 'UTF-8')."</sede>";
        $xml.="<pec>".$this->GetPec()."</pec>";
        $xml.="<web>".mb_encode_numericentity($this->GetSitoWeb(),array (0x0, 0xffff, 0, 0xffff), 'UTF-8')."</web>";
        $xml.="<data_inizio>".$this->GetDataInizioImpegno()."</data_inizio>";
        $xml.="<data_fine>".$this->GetDataFineImpegno()."</data_fine>";
        //$xml.="<partecipazione>".$this->GetPartecipazione()."</partecipazione>";
        $xml.="<stato_organismo id_tipo='".$this->GetStatoOrganismo(true)."'>".$this->GetStatoOrganismo()."</stato_organismo>";
        $xml.="<funzioni>".mb_encode_numericentity($this->GetFunzioni(),array (0x0, 0xffff, 0, 0xffff), 'UTF-8')."</funzioni>";
        $xml.="<note>".mb_encode_numericentity($this->GetNote(),array (0x0, 0xffff, 0, 0xffff), 'UTF-8')."</note>";
        //--------------

        //dati contabili
        $xml.="<dati_contabili>";

        foreach($this->GetDatiContabili() as $curDatoContabile)
        {
            $xml.=$curDatoContabile->toXml();
        }

        $xml.="</dati_contabili>";
        //--------------

        //nomine
        $xml.="<nomine>";

        foreach($this->GetNomine() as $curNomina)
        {
            $xml.=$curNomina->toXml();
        }

        $xml.="</nomine>";
        //-----------

        //organigrammi
        $xml.="<organigrammi>";
        $organigrammi=$this->GetOrganigrammi();
        foreach($organigrammi as $curOrganigramma)
        {
            if($curOrganigramma->IsScadenzarioEnabled())
            {
                $xml.=$curOrganigramma->toXml(false);
            }
        }

        $xml.="</organigrammi>";
        //-------------
        $xml.="</organismo>";

        return $xml;
    }
    
    /**
     * Summary of toJSON
     * @param mixed $bDeep
     * @return void
     */
    public function toJSON($bDeep=false)
    {
        $return[]=array(
            "id"=>$this->GetId(),
            "denominazione"=>$this->GetDenominazione(),
            "tipo"=>$this->GetTipologia(),
            "tipo_id"=>$this->GetTipologia(true),
            "forma_societaria"=>$this->GetFormaSocietaria(),
            "forma_societaria_id"=>$this->GetFormaSocietaria(true),
            "aggiornamento"=>$this->GetAggiornamento(),
            "user"=>$this->GetUser()->GetUsername(),
            "data_inizio_impegno"=>$this->GetDataInizioImpegno(),
            "data_fine_impegno"=>$this->GetDataFineImpegno(),
            "funzioni"=>$this->GetFunzioni(),
            "in_house"=>$this->GetInHouse(),
            "in_tusp"=>$this->GetInTUSP(),
            "sede_legale"=>$this->GetSedeLegale(),
            "pec"=>$this->GetPec(),
            "partecipazione"=>$this->GetPartecipazione(),
            "sito_web"=>$this->GetSitoWeb(),
            "piva_cf"=>$this->GetPivaCf(),
            "stato"=>$this->GetStatoOrganismo(),
            "stato_id"=>$this->GetStatoOrganismo(true)
        );
    }

    //da specializzare
    protected function CsvDataHeader($separator="|")
    {
        return $separator."tipo".$separator."partecipazione_diretta".$separator."partecipazione_indiretta".$separator."partecipazione_tot";
    }

    //da specializzare
    protected function CsvData($separator= "|")
    {
        $tipo=$this->GetTipologia();
        $partecipazione=$this->GetPartecipazione(true);
        $partecipazione_indiretta=$this->GetPartecipazioneIndiretta();

        return $separator.$tipo.$separator.AA_Utils::number_format($partecipazione['percentuale'],2,",",".").$separator.AA_Utils::number_format($partecipazione_indiretta['percentuale'],2,",",".").$separator.AA_Utils::number_format(($partecipazione['percentuale']+$partecipazione_indiretta['percentuale']),2,",",".");
    }
    //--------------------------------------------

    //Verifica che l'oggetto collegato sia aggiornabile dall'utente corrente
    /**
     * Summary of VerifyDbSync
     * @param mixed $user
     * @return bool
     */
    protected function VerifyDbSync($user=null)
    {
        return parent::VerifyDbSync($user);
    }

    //Verifica che l'oggetto collegato sia visibile dall'utente corrente
    /**
     * Summary of VerifyDbLoad
     * @return bool
     */
    protected function VerifyDbLoad()
    {
        return parent::VerifyDbLoad();
    }

    //Carica l'organismo dal database
    /**
     * Summary of GetFromDb
     * @param mixed $id
     * @param mixed $user
     * @return AA_Organismi|null
     */
    static public function GetFromDb($id=0,$user=null)
    {
        AA_Log::Log(__METHOD__."($id)");

        if($id==0 || $id < 0)
        {
            AA_Log::Log(__METHOD__."($id) - id non valido",100, false, true);
            return null;
        }

        $organismo=new AA_Organismi($id,$user);

        return $organismo;
    }

    //Verifica permessi
    /**
     * Summary of GetUserCaps
     * @param mixed $user
     * @return int
     */
    public function GetUserCaps($user=null)
    {
        $perms=AA_Const::AA_PERMS_NONE;

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();        
        }
        
        //Permessi super user
        if($user->IsSuperUser()) return AA_Const::AA_PERMS_ALL;

        $perms=AA_Const::AA_PERMS_NONE;
        if(($this->GetStatus() & AA_Const::AA_STATUS_ALL)==AA_Const::AA_STATUS_PUBBLICATA) $perms=AA_Const::AA_PERMS_READ;
        
        if($user->IsGuest()) return $perms;

        //Verifica della struttura (fino al livello di direzione)
        $userStruct=$user->GetStruct();
        if($userStruct->GetDirezione(true) == $this->GetStruct()->GetDirezione(true) || $userStruct->GetDirezione(true)==0)
        {
            if($userStruct->GetAssessorato(true) == $this->GetStruct()->GetAssessorato(true) || $userStruct->GetAssessorato(true)==0)
            {
                if($user->HasFlag(AA_Const::AA_USER_FLAG_ART22))
                {
                    $perms=AA_Const::AA_PERMS_ALL;
                }
            } 
        }

        //Art 22 admin
        if($user->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            if($userStruct->GetTipo() == $this->GetStruct()->GetTipo()) $perms=AA_Const::AA_PERMS_ALL;
        }
        
        return $perms;
    }

    //Alias for Get
    /**
     * Summary of Load
     * @param mixed $id
     * @param mixed $user
     * @return AA_Organismi|null
     */
    static public function Load($id=0,$user=null)
    {
        return AA_Organismi::GetFromDb($id,$user);
    }

    //Aggiunge un nuovo provvedimento
    /**
     * Summary of AddNewProvvedimento
     * @param mixed $provvedimento
     * @param mixed $file
     * @param mixed $user
     * @return bool
     */
    public function AddNewProvvedimento($provvedimento=null, $file="", $user=null)
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->isValid())
        {
                AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
                return false;            
        }
        
        //Verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return false;
            }
        }

        //Verifica Flags
        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE)==0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non può modificare l'organismo.", 100,false,true);
            return false;
        }

        
        if(!($provvedimento instanceof AA_OrganismiProvvedimenti))
        {
            AA_Log::Log(__METHOD__." - Provvedimento non valido.", 100,false,true);
            return false;
        }
        
        $query="INSERT INTO ".AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_DB_TABLE." SET id_organismo='".$this->GetID()."'";
        $query.=", tipo='".$provvedimento->GetTipologia(true)."'";
        $query.=", url='".addslashes($provvedimento->GetUrl())."'";
        $query.=", anno='".addslashes($provvedimento->GetAnno())."'";
        $query.=", estremi='".addslashes($provvedimento->GetEstremi())."'";
        
        $db= new AA_Database();
        
        //AA_Log::Log(__METHOD__." - query: ".$query, 100);
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        $new_id=$db->GetLastInsertId();
        
        if($file !="")
        {
            if(is_uploaded_file($file))
            {
                if(!move_uploaded_file($file,AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf"))
                {
                    AA_Log::Log(__METHOD__." - Errore durante il salvataggio del file: ".AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf", 100,false,true);
                    return false;
                }
            }
            else 
            {
                AA_Log::Log(__METHOD__." - Errore durante il salvataggio del file: ".print_r($file,true), 100);
                
                if(!rename($file,AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf"))
                {
                    AA_Log::Log(__METHOD__." - Errore durante il salvataggio del file", 100);
                    return false;
                }
            }
        }
        
        $this->IsChanged();

        //Aggiorna il db
        if($this->bLogEnabled)
        {
            $this->AddLog("Aggiunto provvedimento (".$provvedimento->GetTipologia().")",AA_Const::AA_OPS_UPDATE,$user);
        }

        return $this->UpdateDb($user,null,false);
    }
    
    //Aggiorna un provvedimento esistente
    /**
     * Summary of UpdateProvvedimento
     * @param mixed $provvedimento
     * @param mixed $file
     * @param mixed $user
     * @return bool
     */
    public function UpdateProvvedimento($provvedimento=null, $file="", $user=null)
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->isValid())
        {
                AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
                return false;            
        }
        
        //Verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return false;
            }
        }

        //Verifica Flags
        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE)==0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non può modificare l'organismo.", 100,false,true);
            return false;
        }

        if(!($provvedimento instanceof AA_OrganismiProvvedimenti))
        {
            AA_Log::Log(__METHOD__." - Provvedimento non valido.", 100,false,true);
            return false;
        }
        
        $query="UPDATE ".AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_DB_TABLE." SET id_organismo='".$this->GetID()."'";
        $query.=", tipo='".$provvedimento->GetTipologia(true)."'";
        $query.=", url='".addslashes($provvedimento->GetUrl())."'";
        $query.=", anno='".addslashes($provvedimento->GetAnno())."'";
        $query.=", estremi='".addslashes($provvedimento->GetEstremi())."'";
        $query.=" WHERE id='".$provvedimento->GetId()."' LIMIT 1";
        
        $db= new AA_Database();
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        $new_id=$provvedimento->GetId();
        
        if($file !="")
        {
            if(is_uploaded_file($file))
            {
                if(!move_uploaded_file($file,AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf"))
                {
                    AA_Log::Log(__METHOD__." - Errore durante il salvataggio del file: ".AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf", 100,false,true);
                    return false;
                }
            }
            else 
            {
                if(!rename($file,AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf"))
                {
                    AA_Log::Log(__METHOD__." - Errore durante il salvataggio del file: ".AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf", 100,false,true);
                    return false;
                }
            }
        }
        
        $this->IsChanged();

        //Aggiorna il db
        if($this->bLogEnabled)
        {
            $this->AddLog("Aggiornato provvedimento (".$provvedimento->GetTipologia().")",AA_Const::AA_OPS_UPDATE,$user);
        }

        return $this->UpdateDb($user,null,false);
    }

    //Aggiorna un provvedimento esistente
    /**
     * Summary of UpdateOrganigramma
     * @param mixed $organigramma
     * @param mixed $user
     * @return bool
     */
    public function UpdateOrganigramma($organigramma=null, $user=null)
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->isValid())
        {
                AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
                return false;            
        }
        
        //Verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return false;
            }
        }

        //Verifica Flags
        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE)==0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non può modificare l'organismo.", 100,false,true);
            return false;
        }

        if(!($organigramma instanceof AA_Organismi_Organigramma))
        {
            AA_Log::Log(__METHOD__." - Organigramma non valido.", 100,false,true);
            return false;
        }
        
        $query="UPDATE ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA." SET id_organismo='".$this->GetID()."'";
        $query.=", tipo='".$organigramma->GetProp("tipo")."'";
        $query.=", note='".addslashes($organigramma->GetProp("note"))."'";
        if(intval($organigramma->GetProp("ordine")) >= 0) $query.=", ordine='".addslashes(intval($organigramma->GetProp("ordine")))."'";
        $query.=", enable_scadenzario='".addslashes($organigramma->GetProp("enable_scadenzario"))."'";
        $query.=" WHERE id='".$organigramma->GetId()."' LIMIT 1";
        
        $db= new AA_Database();
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
                
        $this->IsChanged();

        //Aggiorna il db
        if($this->bLogEnabled)
        {
            $this->AddLog("Aggiornato organigramma ".$organigramma->GetTipologia()."(id: ".$organigramma->GetId().")",AA_Const::AA_OPS_UPDATE,$user);
        }

        return $this->UpdateDb($user,null,false);
    }
    
    //Restituisce un provvedimento esistente
    /**
     * Summary of GetProvvedimento
     * @param mixed $id
     * @param mixed $user
     * @return AA_OrganismiProvvedimenti|null
     */
    public function GetProvvedimento($id=null, $user=null)
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->isValid())
        {
                AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
                return null;            
        }
        
        //Verifica utente
        /*if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return null;
            }
        }*/

        //Verifica Flags
        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_READ)==0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non può modificare l'organismo.", 100,false,true);
            return null;
        }
        
        $query="SELECT * FROM ".AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_DB_TABLE." WHERE id_organismo='".$this->GetID()."'";
        $query.=" AND id='".$id."' LIMIT 1";
        
        $db= new AA_Database();
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return null;            
        }
        
        if($db->GetAffectedRows() > 0)
        {
            $rs=$db->GetResultSet();
            $provvedimento=new AA_OrganismiProvvedimenti($rs[0]['id'],$this->GetID(),$rs[0]['tipo'],$rs[0]['url'],$rs[0]['anno'],$rs[0]['estremi']);
            
            return $provvedimento;
        }
        
        return null;
    }
    
    //Rimuovi un provvedimento esistente
    /**
     * Summary of DeleteProvvedimento
     * @param mixed $id
     * @param mixed $user
     * @return bool
     */
    public function DeleteProvvedimento($id=0, $user=null)
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->isValid())
        {
                AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
                return false;            
        }
        
        //Verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return false;
            }
        }

        //Verifica Flags
        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE)==0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non può modificare l'organismo.", 100,false,true);
            return false;
        }
        
        $provvedimento = $this->GetProvvedimento($id);
        if(!($provvedimento instanceof AA_OrganismiProvvedimenti))
        {
            AA_Log::Log(__METHOD__." - Provvedimento non valido.", 100,false,true);
            return false;
        }
        
        $query="DELETE FROM ".AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_DB_TABLE." WHERE id_organismo='".$this->GetID()."'";
        $query.=" AND id='".$provvedimento->GetId()."' LIMIT 1";
        
        $db= new AA_Database();
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        $new_id=$provvedimento->GetId();
        
        if(is_file(AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf"))
        {
            if(!unlink(AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf"))
            {
                AA_Log::Log(__METHOD__." - Errore durante la rimozione del file: ".AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf", 100,false,true);
                return false;
            }
        }
        
        $this->IsChanged();
        
        //log
        if($this->bLogEnabled)
        {
            $this->AddLog("Rimosso provvedimento (".$provvedimento->GetTipologia().")",AA_Const::AA_OPS_UPDATE,$user);
        }
        return $this->UpdateDb($user,null,false);
    }
    
    //Rimuovi tutti i provvedimenti
    /**
     * Summary of DeleteAllProvvedimenti
     * @param mixed $user
     * @return bool
     */
    public function DeleteAllProvvedimenti($user=null)
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->isValid())
        {
                AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
                return false;            
        }
        
        //Verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return false;
            }
        }

        //Verifica Flags
        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE)==0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non può modificare l'organismo.", 100,false,true);
            return false;
        }

        foreach($this->GetProvvedimenti() as $provvedimento)
        {
            if(!($provvedimento instanceof AA_OrganismiProvvedimenti))
            {
                AA_Log::Log(__METHOD__." - Provvedimento non valido.", 100,false,true);
                return false;
            }

            $query="DELETE FROM ".AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_DB_TABLE." WHERE id_organismo='".$this->GetID()."'";
            $query.=" AND id='".$provvedimento->GetId()."' LIMIT 1";

            $db= new AA_Database();

            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
                return false;            
            }

            $new_id=$provvedimento->GetId();

            if(is_file(AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf"))
            {
                if(!unlink(AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf"))
                {
                    AA_Log::Log(__METHOD__." - Errore durante la rimozione del file: ".AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$new_id.".pdf", 100,false,true);
                    return false;
                }
            }            
        }        
        
        $this->IsChanged();
        return $this->UpdateDb($user);
    }
    
    //Aggiunge un nuovo organismo al db
    /**
     * Summary of AddNewToDb
     * @param mixed $data
     * @param mixed $user
     * @return AA_Organismi|null
     */
    static public function AddNewToDb($data=null,$user=null)
    {
        AA_Log::Log(__METHOD__."($data)");

        //Verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return null;
            }
        }

        //Verifica Flags
        if(!$user->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$user->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            AA_Log::Log(__METHOD__." - l'utente non può gestire le pubblicazioni di cui all'art. 22 del d.lgs. 33/2013.", 100,false,true);
            return null;
        }

        $new_organismo = new AA_Organismi();

        if(!$new_organismo->ParseData($data,$user))
        {
            //AA_Log::Log(__METHOD__." - Errore durante il parsing dei dati: ".print_r($data,TRUE), 100,false,true);
            return null;
        }

        $new_organismo->SetStatus(AA_Const::AA_STATUS_BOZZA);
        $new_organismo->SetID(0);

        if(($new_organismo->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $new_organismo->EnableDbSync();
            //Aggiorna il log
            if($new_organismo->bLogEnabled)
            {
                $new_organismo->AddLog("Inserimento",AA_Const::AA_OPS_ADDNEW,$user);
            }

            if(!$new_organismo->UpdateDb($user,null,false))
            {
                AA_Log::Log(__METHOD__." - Errore durante il salvataggio del nuovo organismo sul DB.", 100,false,true);
                return null;    
            }

            return $new_organismo;
        }
        else
        {
            $new_organismo->SetStruct($user->GetStruct());

            //Aggiorna il log
            if($new_organismo->bLogEnabled)
            {
                $new_organismo->AddLog("Inserimento",AA_Const::AA_OPS_ADDNEW,$user);
            }
            if(!$new_organismo->UpdateDb($user,null,false))
            {
                AA_Log::Log(__METHOD__." - Errore durante il salvataggio del nuovo organismo sul DB (tentativo n. 2).", 100,false,true);
                return null;    
            }

            return $new_organismo;
        }
    }

    //Funzione di Parsing a partire da un array (non cambia l'identificativo dell'oggetto)
    /**
     * Summary of ParseData
     * @param mixed $data
     * @param mixed $user
     * @return bool
     */
    public function ParseData($data=null,$user=null)
    {
        //verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            if($this->oUser->IsCurrentUser()) $user=$this->oUser;
            else $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,true,true);
                return false;
            }
        }

        //Verifica i checkbox per le società
        if($this->GetTipologia(true)==AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
        {
            if(!isset($data['bInHouse'])) $this->SetInHouse(0);
            if(!isset($data['bInTUSP'])) $this->SetInTUSP(0);
        }

        //Verifica la sintassi della partecipazione
        //if(isset($data["sPartecipazione"]))
        //{
        //    $data["sPartecipazione"]=preg_replace("/[€|\ |A-Za-z_]/", "",$data["sPartecipazione"]);
        //}        

        if(!parent::ParseData($data,$user))
        {
            //AA_Log::Log(__METHOD__." - Errore nel parsing dei dati.", 100,false,true);
            return false;
        }

        //Verifica i dati
        if(trim($this->GetDenominazione())=="")
        {
            AA_Log::Log(__METHOD__." - Occorre specificare la denominazione dell'organismo.", 100,false,true);
            return false;
        }

        //Verifica che la data di fine impegno sia successiva alla data di inizio iumpegno
        if($this->GetId() == 0)
        {
            if($this->GetDataInizioImpegno() == "")
            {
                $this->SetDataInizioImpegno(Date("Y-m-d"));
            }

            if($this->GetDataFineImpegno() == "")
            {
                $this->SetDataFineImpegno("9999-12-31");
            }
        }

        if(strcmp($this->GetDataInizioImpegno(),$this->GetDataFineImpegno()) >= 0)
        {
            AA_Log::Log(__METHOD__." - La data di fine impegno deve essere maggiore della data di inizio impegno (data inizio: ".$this->GetDataInizioImpegno()." - data fine: ".$this->GetDataFineImpegno().").", 100,false,true);
            return false;
        }

        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $this->EnableDbSync();
            return true;
        }
        else
        {
            AA_Log::Log(__METHOD__." - L'utente (".$user.") non può aggiornare l'organismo (".$this->GetDescrizione().").", 100,false,true);
        }

        //Riprova cambiando la struttura
        $this->SetStruct($user->GetStruct());
        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $this->EnableDbSync();
            return true;
        }

        AA_Log::Log(__METHOD__." - L'utente (".$user.") non può aggiornare l'organismo (".$this->GetDescrizione().").", 100,false,true);
        return false;
    }

    //Cestina l'oggetto
    /**
     * Summary of Trash
     * @param mixed $user
     * @param mixed $bDelete
     * @return mixed
     */
    public function Trash($user=null, $bDelete=false)
    {
        //Verifica permessi
        if(!$this->VerifyDbSync($user) || !$this->IsValid())
        {
            return false;
        }
        $perms=$this->GetUserCaps($user);
        if(($perms & AA_Const::AA_PERMS_DELETE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente: ".$user->GetNome()." non ha i permessi per cestinare/eliminare l'oggetto", 100,false,true);
            return false;
        }

        if((($this->nStatus & AA_Const::AA_STATUS_CESTINATA) > 0) && $bDelete)
        {
            //Cancella i dati contabili;
            $bilanci=$this->GetDatiContabili();

            foreach($bilanci as $curBilancio)
            {
                if(!$curBilancio->Trash($user,true))
                {
                    return false;
                }
            }

            //Elimina le nomine
            $nomine=$this->GetNomine();

            foreach($nomine as $curNomina)
            {
                if(!$curNomina->Trash($user,true))
                {
                    return false;
                }
            }
            
            //elimina provvedimenti
            if(!$this->DeleteAllProvvedimenti($user)) return false;

            //elimina gli organigrammi
            if(!$this->DeleteAllOrganigrammi()) return false;
        }

        return parent::Trash($user,$bDelete);
    }

    //Elimina un organigramma
    /**
     * Summary of DeleteOrganigramma
     * @param mixed $id
     * @return bool
     */
    public function DeleteOrganigramma($id=0)
    {
        //Verifica permessi
        if(!$this->VerifyDbSync($this->oUser) || !$this->IsValid() || $id <= 0)
        {
            return false;
        }
        $perms=$this->GetUserCaps($this->oUser);
        if(($perms & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente: ".$this->oUser->GetNome()." non ha i permessi per cestinare/eliminare l'oggetto", 100,false,true);
            return false;
        }

        $db=new AA_Database();
        $query="DELETE FROM ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA_INCARICHI." WHERE id_organigramma='".addslashes($id)."'";

        if(!$db->Query($query))
        {
            //Errore query
            AA_Log::Log(__METHOD__."() - Errore nella query: $query",100);
            return false;
        }

        $query="DELETE FROM ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA." WHERE id_organismo ='".$this->GetID()."' AND id='".addslashes($id)."' limit 1";
        if(!$db->Query($query))
        {
            //Errore query
            AA_Log::Log(__METHOD__."() - Errore nella query: $query",100);
            return false;
        }

        $this->IsChanged();
        
        //log
        if($this->bLogEnabled)
        {
            $this->AddLog("Rimosso organigramma (id: ".$id.")",AA_Const::AA_OPS_UPDATE,$this->oUser);
        }

        $this->UpdateDb($this->oUser,null,false);

        return true;
    }

    //Elimina un organigramma
    /**
     * Summary of DeleteOrganigrammaIncarico
     * @param mixed $id
     * @param mixed $id_incarico
     * @return bool
     */
    public function DeleteOrganigrammaIncarico($id=0,$id_incarico=0)
    {
        //Verifica permessi
        if(!$this->VerifyDbSync($this->oUser) || !$this->IsValid() || $id <= 0)
        {
            return false;
        }
        $perms=$this->GetUserCaps($this->oUser);
        if(($perms & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente: ".$this->oUser->GetNome()." non ha i permessi per cestinare/eliminare l'oggetto", 100,false,true);
            return false;
        }

        //Verifica che l'organigramma sia dell'organismo corrente
        $organigramma=$this->GetOrganigramma($id);
        if($organigramma==null)
        {
            AA_Log::Log(__METHOD__." - Identificativo organigramma non valido.", 100,false,true);
            return false;
        }

        $db=new AA_Database();
        $query="DELETE FROM ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA_INCARICHI." WHERE id_organigramma='".addslashes($id)."' AND id='".addslashes($id_incarico)."' LIMIT 1";

        if(!$db->Query($query))
        {
            //Errore query
            AA_Log::Log(__METHOD__."() - Errore nella query: $query",100);
            return false;
        }

        $this->IsChanged();
        
        //log
        if($this->bLogEnabled)
        {
            $this->AddLog("Rimosso incarico (id: ".$id_incarico.")",AA_Const::AA_OPS_UPDATE,$this->oUser);
        }

        $this->UpdateDb($this->oUser,null,false);

        return true;
    }

    //Elimina tutti gli organigrammi
    /**
     * Summary of DeleteAllOrganigrammi
     * @return bool
     */
    protected function DeleteAllOrganigrammi()
    {
        //Verifica permessi
        if(!$this->VerifyDbSync($this->oUser) || !$this->IsValid())
        {
            return false;
        }
        $perms=$this->GetUserCaps($this->oUser);
        if(($perms & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente: ".$this->oUser->GetNome()." non ha i permessi per cestinare/eliminare l'oggetto", 100,false,true);
            return false;
        }

        $db=new AA_Database();
        $query="SELECT id FROM ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA." WHERE id_organismo='".$this->GetID()."'";
        if(!$db->Query($query))
        {
            //Errore query
            AA_Log::Log(__METHOD__."() - Errore nella query: $query",100);
            return false;
        }

        //non ci sono organigrammi
        if($db->GetAffectedRows() == 0) return true;

        $rs=$db->GetResultSet();
        foreach($rs as $curRow)
        {
            $query="DELETE FROM ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA_INCARICHI." WHERE id_organigramma='".addslashes($curRow["id"])."'";
            if(!$db->Query($query))
            {
                //Errore query
                AA_Log::Log(__METHOD__."() - Errore nella query: $query",100);
                return false;
            }

            $query="DELETE FROM ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA." WHERE id_organismo ='".$this->GetID()."' AND id='".addslashes($curRow['id'])."' limit 1";
            if(!$db->Query($query))
            {
                //Errore query
                AA_Log::Log(__METHOD__."() - Errore nella query: $query",100);
                return false;
            }
        }      

        return true;
    }
    
    //Funzione di pubblicazione
    /**
     * Summary of CanPublish
     * @return bool
     */
    public function CanPublish()
    {
        //Verifica dei campi obbligatori
        
        //denominazione
        if(trim($this->GetDenominazione()) == "")
        {
            AA_Log::Log(__METHOD__."() - La denominazione dell'organismo non può essere vuota.",100,false,true);
            return false;
        }

        //tipologia
        if($this->GetTipologia(true) == 0)
        {
            AA_Log::Log(__METHOD__."() - Occorre specificare la tipologia dell'organismo.",100,false,true);
            return false;
        }
        
        if($this->GetTipologia(true)==AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
        {
            //Forma societaria
            if($this->GetFormaSocietaria(true) == 0)
            {
                AA_Log::Log(__METHOD__."() - Occorre specificare la forma societaria.",100,false,true);
                return false;
            }

            //stato società
            if($this->GetStatoOrganismo(true) == 0)
            {
                AA_Log::Log(__METHOD__."() - Occorre specificare lo stato della società.",100,false,true);
                return false;
            }
        }

        return true;
    }

    //Aggiungi un organigramma
    /**
     * Summary of AddNewOrganigramma
     * @param mixed $newOrganigramma
     * @return bool|int|mixed|string
     */
    public function AddNewOrganigramma($newOrganigramma=null)
    {
        //Verifica permessi
        if(!$this->VerifyDbSync($this->oUser) || !$this->IsValid())
        {
            AA_Log::Log(__METHOD__." - Oggetto non valido.", 100);
            return 0;
        }
        $perms=$this->GetUserCaps($this->oUser);
        if(($perms & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente: ".$this->oUser->GetNome()." non ha i permessi per modificare l'oggetto", 100,false,true);
            return 0;
        }

        if(!($newOrganigramma instanceof AA_Organismi_Organigramma))
        {
            AA_Log::Log(__METHOD__." - Dati organigramma errati.",100);
            return 0;            
        }

        $dal=addslashes($newOrganigramma->GetProp("dal"));
        if($dal=="") $dal="0000-01-01";
        $al=addslashes($newOrganigramma->GetProp("al"));
        if($al=="") $al="9999-12-31";

        $db=new AA_Database();
        $query="INSERT INTO ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA." set id_organismo='".$this->GetId()."'";
        $query.=", tipo='".addslashes($newOrganigramma->GetProp("tipo"))."'";
        $query.=", dal='".$dal."'";
        $query.=", al='".$al."'";
        $query.=", enable_scadenzario='".addslashes($newOrganigramma->GetProp("enable_scadenzario"))."'";
        if(is_numeric($newOrganigramma->GetProp("ordine"))) $query.=", ordine='".addslashes($newOrganigramma->GetProp("ordine"))."'";

        $query.=", note='".addslashes($newOrganigramma->GetProp("note"))."'";
        
        if(!$db->Query($query))
        {
            //Errore query
            AA_Log::Log(__METHOD__."() - Errore nella query: $query",100);
            return 0;
        }

        $newID=$db->GetLastInsertId();
        //AA_Log::Log(__METHOD__." - id inserito ".$newID,100);

        $this->IsChanged();
        
        //log
        if($this->bLogEnabled)
        {
            $this->AddLog("Aggiunto organigramma ".$newOrganigramma->GetTipologia()."(id: ".$newID.")",AA_Const::AA_OPS_UPDATE,$this->oUser);
        }

        $this->UpdateDb($this->oUser,null,false);
        
        return $newID;
    }

    //Aggiungi un incarico ad un organigramma
    /**
     * Summary of AddNewOrganigrammaIncarico
     * @param mixed $incarico
     * @param mixed $idOrganigramma
     * @return bool|int|mixed|string
     */
    public function AddNewOrganigrammaIncarico($incarico=null, $idOrganigramma=0)
    {
        //Verifica permessi
        if(!$this->VerifyDbSync($this->oUser) || !$this->IsValid())
        {
            return 0;
        }
        $perms=$this->GetUserCaps($this->oUser);
        if(($perms & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente: ".$this->oUser->GetNome()." non ha i permessi per modificare l'oggetto", 100,false,true);
            return 0;
        }

        if(!($incarico instanceof AA_Organismi_Organigramma_Incarico))
        {
            AA_Log::Log(__METHOD__." - Dati incarico errati.",100);
            return 0;            
        }

        $organigramma=$this->GetOrganigramma($idOrganigramma);
        if(!($organigramma instanceof AA_Organismi_Organigramma))
        {
            AA_Log::Log(__METHOD__." - Identificativo organigramma non valido (".$idOrganigramma.")",100);
            return 0;                        
        }

        $db=new AA_Database();
        $query="INSERT INTO ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA_INCARICHI." set id_organigramma='".$organigramma->GetProp("id")."'";
        $query.=", tipo='".addslashes($incarico->GetProp("tipo"))."'";
        $query.=", ras='".addslashes($incarico->GetProp("ras"))."'";
        $query.=", ordine='".addslashes($incarico->GetProp("ordine"))."'";
        $query.=", note='".addslashes($incarico->GetProp("note"))."'";
        $query.=", opzionale='".addslashes($incarico->GetProp("opzionale"))."'";
        $query.=", compenso_spettante='".addslashes($incarico->GetProp("compenso_spettante"))."'";
        if($incarico->GetProp("forza_scadenzario") > 0) $query.=", forza_scadenzario='1'";
        else $query.=", forza_scadenzario='0'";
        
        if(!$db->Query($query))
        {
            //Errore query
            AA_Log::Log(__METHOD__."() - Errore - ".$db->GetErrorMessage()." - nella query: $query",100);
            return 0;
        }

        $newID=$db->GetLastInsertId();

        $this->IsChanged();
        
        //log
        if($this->bLogEnabled)
        {
            $this->AddLog("Aggiunto incarico (id: ".$newID.") all'organigramma ".$organigramma->GetTipologia()."(".$idOrganigramma.")",AA_Const::AA_OPS_UPDATE,$this->oUser);
        }

        $this->UpdateDb($this->oUser,null,false);

        return $newID;
    }

    //Aggiorna un incarico ad un organigramma
    /**
     * Summary of UpdateOrganigrammaIncarico
     * @param mixed $incarico
     * @param mixed $idOrganigramma
     * @return mixed
     */
    public function UpdateOrganigrammaIncarico($incarico=null, $idOrganigramma=0)
    {
        //Verifica permessi
        if(!$this->VerifyDbSync($this->oUser) || !$this->IsValid())
        {
            return 0;
        }
        $perms=$this->GetUserCaps($this->oUser);
        if(($perms & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente: ".$this->oUser->GetNome()." non ha i permessi per modificare l'oggetto", 100,false,true);
            return 0;
        }

        if(!($incarico instanceof AA_Organismi_Organigramma_Incarico))
        {
            AA_Log::Log(__METHOD__." - Dati incarico errati.",100);
            return 0;            
        }

        $organigramma=$this->GetOrganigramma($idOrganigramma);
        if(!($organigramma instanceof AA_Organismi_Organigramma))
        {
            AA_Log::Log(__METHOD__." - Identificativo organigramma non valido (".$idOrganigramma.")",100);
            return 0;                        
        }

        $db=new AA_Database();
        $query="UPDATE ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA_INCARICHI." SET ";
        $query.=" tipo='".addslashes($incarico->GetProp("tipo"))."'";
        $query.=", ras='".addslashes($incarico->GetProp("ras"))."'";
        $query.=", ordine='".addslashes($incarico->GetProp("ordine"))."'";
        $query.=", note='".addslashes($incarico->GetProp("note"))."'";
        $query.=", opzionale='".addslashes($incarico->GetProp("opzionale"))."'";
        $query.=", compenso_spettante='".addslashes($incarico->GetProp("compenso_spettante"))."'";
        if($incarico->GetProp("forza_scadenzario") > 0) $query.=", forza_scadenzario='1'";
        else $query.=", forza_scadenzario='0'";
        $query.=" WHERE ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA_INCARICHI.".id = '".addslashes($incarico->GetId())."' and ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA_INCARICHI.".id_organigramma='".$organigramma->GetProp("id")."' LIMIT 1";
        
        if(!$db->Query($query))
        {
            //Errore query
            AA_Log::Log(__METHOD__."() - Errore nella query: $query",100);
            return 0;
        }

        $newID=$incarico->GetId();

        $this->IsChanged();
        
        //log
        if($this->bLogEnabled)
        {
            $this->AddLog("Aggiornato incarico (id: ".$newID.") dell'organigramma ".$organigramma->GetTipologia()."(".$idOrganigramma.")",AA_Const::AA_OPS_UPDATE,$this->oUser);
        }

        $this->UpdateDb($this->oUser,null,false);

        return $newID;
    }

    //Funzione di recupero organismi per rappresentazione grafica
    /**
     * Summary of GetOrganismiPerRappresentazioneGrafica
     * @param mixed $params
     * @param mixed $user
     * @return array
     */
    static public function GetOrganismiPerRappresentazioneGrafica($params,$user=null)
    {
        //Verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();        
        }

        //Flag ricerca pubblica
        $public=false;

        if($user->IsGuest() || $params['public'] !="") $public=true;

        //Imposta la query base
        $select="SELECT DISTINCT ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id FROM ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE." ";
        $join="";
        $where="";
        $group="";
        $having="";
        $order=" ORDER BY id_assessorato,id_direzione, denominazione, aggiornamento DESC";
        $group=" GROUP BY ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id ";

        //Parametro status non impostato
        if($params['status'] == "" || !isset($params['status']) || $public) $params['status']=AA_Const::AA_STATUS_PUBBLICATA;

        if(!$public) $userStruct=$user->GetStruct();
        else
        {
            if($params['id_assessorato']) $userStruct=AA_Struct::GetStruct($params['id_assessorato']);
            else $userStruct=AA_Struct::GetStruct();
        } 

        //Includi l'assessorato
        $join.=" LEFT JOIN assessorati on ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id_assessorato=assessorati.id ";

        if($userStruct->GetAssessorato(true) > 0)
        {   
            if($userStruct->GetTipo() > 0) $where.=" AND id_assessorato='".$userStruct->GetAssessorato(true)."'";
            else $where.=" AND assessorati.tipo='0'";
        }
        else
        {
            $where.=" AND assessorati.tipo='0' ";
        }

        if($userStruct->GetDirezione(true) > 0)
        {   
            $where.=" AND id_direzione='".$userStruct->GetDirezione(true)."'";
        }

        //Filtra in base allo stato della scheda
        $where.=" AND status ='".$params['status']."' ";

        //Nascondi gli organismi scaduti
        $where.=" AND stato_organismo <> 4 ";

        //Filtra in base al tipo di organismo
        if($params['tipo']) $where.=" AND tipo & ".$params['tipo']." > 0 ";

        $db=new AA_Database();

        //Effettua la query
        $query=$select.$join;
        if(strlen($where) > 0) $query.=" WHERE 1 ".$where;
        $query.=$group.$having.$order;
        if(!$db->Query($query))
        {
            //Errore query
            AA_Log::Log(__METHOD__."(".print_r($params,TRUE).") - Errore nella query: $query",100, true,true);
            return array(0=>-1,array());
        }

        //Popola l'array dei risultati
        $results=array();
        $tot_count=0;
        if($db->GetAffectedRows() > 0)
        {
            $rs=$db->GetResultSet();
            foreach($rs as $curRow)
            {
                $curResult=new AA_Organismi($curRow['id'],$user);
                if($curResult != null) $curRow['id']=$curResult;
                $tot_count++;
            }
        }

        $result=array(0=>$tot_count,$results);

        return $result;
    }

    //Funzione di ricerca, restituisce un array, il primo elemento è un intero con il numero totale di voci, il secondo elemento è un array di oggetti AA_Processo.
    // $params: array dei parametri di ricerca, la chiave specifica il campo. valori speciali:
    // $params["from"], il record iniziale da cui partire.
    // $params["count"], il numero massimo di record da restituire.
    // $params['public']: Imposta una ricerca pubblica.
    // $params['tipo']: Imposta una ricerca sul tipo di organismo.
    // $params['denominazione']: Imposta una ricerca sulla base della denominazione piva_cf.
    // $params['gestiti']: Visualizza solo gli organismi gestiti dall'utente corrente.
    // $params['id_assessorato']: Visualizza solo gli organismi nell'assessorato indicato.
    // $params['id_direzione']: Visualizza solo gli organismi nella direzione indicata.
    // $params['dal']: Visualizza solo i titolari che hanno incarichi a partire dalla data impostata.
    // $params['al']: Visualizza solo i titolari che hanno incarichi prima della data impostata.
    // $params['incaricato']: Visualizza solo i gli organismi che hanno l'incaricato specificato.
    // $params['in_corso']: Visualizza gli organismi che hanno nomine che scadono tra più di un mese dalla data di scadenza impostata.
    // $params['in_scadenza']: Visualizza gli organismi che hanno nomine che scadono entro un mese dalla data di scadenza impostata.
    // $params['scadute']: Visualizza gli organismi che hanno nomine che scadute da più di un mese dalla data di scadenza impostata.
    // $params['recenti']: Visualizza solo i gli organismi che hanno nomine che sono scadute da meno di un mese dalla data di scadenza impostata.
    // $params['tipo_nomina']: Visualizza solo i gli organismi che hanno il tipo di nomina impostata.
    // $params['stato_organismo']: Visualizza solo gli organismi con lo stato indicato.
    
    /**
     * Summary of Search
     * @param mixed $params
     * @param mixed $bOnlyCount
     * @param mixed $user
     * @return array
     */
    static public function Search($params,$bOnlyCount=false, $user=null)
    {
        //Verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();        
        }

        //Flag ricerca pubblica
        $public=false;

        if($user->IsGuest() || $params['public'] !="") $public=true;

        //Imposta la query base
        $select="SELECT DISTINCT ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id FROM ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE." ";
        $join="";
        $where="";
        $group="";
        $having="";
        $order=" ORDER BY denominazione, aggiornamento DESC";
        //$join.=" LEFT JOIN incarichi on incarichi_titolari.id=incarichi.id_incarico ";
        $group=" GROUP BY ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id ";

        //Parametro status non impostato
        if($params['status'] == "" || !isset($params['status']) || $public) $params['status']=AA_Const::AA_STATUS_PUBBLICATA;

        $userStruct=$user->GetStruct();

        if($params['gestiti'] !="" || ($params['status'] & (AA_Const::AA_STATUS_BOZZA+AA_Const::AA_STATUS_CESTINATA) > 0 && $userStruct->GetAssessorato(true) > 0))
        {
            //Visualizza solo quelle della sua struttura
            if($user->HasFlag(AA_Const::AA_USER_FLAG_ART22))
            {
                $params['id_assessorato']="";
                $params['id_direzione']="";
                $where.=" AND id_assessorato='".$userStruct->GetAssessorato(true)."'";    
            }

            //Non visualizza le bozze se non ha il flag
            if(!$user->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$user->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
            {
                $params['id_assessorato']="999999";
                $params['id_direzione']="";
            }

            //visualizza tutto
            if($user->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
            {
                $params['id_assessorato']="";
                $params['id_direzione']="";
                $where="";
            }
        }       

        //Filtra in base allo stato della scheda
        $where.=" AND status ='".$params['status']."' ";

        //Filtra gli incarichi in base alla struttura
        if($params["id_assessorato"] > 0)
        {           
            $where.=" AND id_assessorato='".$params["id_assessorato"]."'";
        }
        if($params["id_direzione"] > 0)
        {                
            $where.=" AND id_direzione='".$params["id_direzione"]."'";
        }

        //Filtra in base alla data
        if($params["dal"] != "" && $params["dal"] != "0000-00-00")
        {           
            $where.=" AND (data_fine_impegno >= '".addslashes($params["dal"])."' OR data_fine_impegno = '0000-00-00')";
        }

        if($params["al"] != "" && $params["al"] != "0000-00-00")
        {           
            $where.=" AND (data_inizio_impegno <= '".addslashes($params["al"])."' OR data_inizio_impegno = '0000-00-00')";
        }

        //Filtra in funzione del tipo di organismo
        if(isset($params['tipo']) && $params['tipo'] > 0)
        {
            $where.=" AND tipo & ".$params['tipo']." > 0 ";
        }
                
        //Filtra in base all'incaricato o tipo di nomina
        if($params['incaricato'] !="" || $params['tipo_nomina'] > 0 || $params['over65'] > 0)
        {
            $join=" LEFT JOIN ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE." on ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".id_organismo=".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id";
            if($params['incaricato'] !="")
            {
                $where.=" AND (".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".nome like '%". addslashes(trim($params['incaricato']))."%' ";
                $where.=" OR ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".cognome like '%". addslashes(trim($params['incaricato']))."%' ";
                $where.=" OR ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".codice_fiscale like '%". addslashes(trim($params['incaricato']))."%') ";    
            }
            if($params['tipo_nomina'] > 0)
            {
                $where.=" AND ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".tipo_incarico like '". addslashes(trim($params['tipo_nomina']))."' ";
            }

            //Seleziona solo gli organismi in cui ci sono dei nominati che hanno una età uguale o superiore a 65 anni nell'arco di durata dell'incarico
            if($params['over65']>0)
            {
                //Anno corrente
                $cur_anno=substr(date("Y")-65,2,2);
                $where.=" AND (SUBSTR(".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".codice_fiscale,7,2) <= '".$cur_anno."' AND SUBSTR(".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".codice_fiscale,7,2) <> '') AND ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".data_fine > NOW() ";
            }
        }

        //partecipazione
        if(!empty($params['partecipazione'])) $params['tipo']=AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA;
        
        if(($params['tipo']&AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0)
        {
            if($params['partecipazione']>0)
            {
                switch($params['partecipazione'])
                {
                    case 1:
                        $where.=" AND (".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione not like '%{\"percentuale\":\"0.00\"%' AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione NOT LIKE '' AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione not like '%\"partecipazioni\":{%') ";
                        break;
                    case 2:
                        $where.=" AND (".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione like '%{\"percentuale\":\"0.00\"%' AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione NOT LIKE '' AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione like '%\"partecipazioni\":%') ";
                        break;
                    case 3:
                        $where.=" AND (".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione not like '%{\"percentuale\":\"0.00\"%' AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione NOT LIKE '') ";
                        break;
                    case 4:
                        $where.=" AND (".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione like '%{\"percentuale\":\"0.00\"%' AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione NOT LIKE '') ";
                        break;
                    case 5:
                        $where.=" AND (".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione not like '%{\"percentuale\":\"0.00\"%' AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione NOT LIKE '' AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".partecipazione like '%\"partecipazioni\":%') ";
                        break;
                }
            }
        }
        
        //Filtra in base alla denominazione o alla partita iva/cf
        if(isset($params['denominazione']) && $params['denominazione'] !="") $where.=" AND (denominazione like '%".addslashes(trim($params['denominazione']))."%' OR piva_cf like '%".addslashes(trim($params['denominazione']))."%') ";

        //id impostati
        if(is_array($params['ids']) && sizeof($params['ids']) > 0)
        {
            $where=" AND id in(".implode(",",$params['ids']).")";
        }
        
        //Filtra in base alle nomine in corso
        $now=date("Y-m-d");
        $ricerca_scadenzario=false;
        if((isset($params['in_corso']) && $params['in_corso'] !="") || (isset($params['in_scadenza']) && $params['in_scadenza'] !="") || (isset($params['scadute']) && $params['scadute'] !="") || (isset($params['recenti']) && $params['recenti'] !=""))
        {
            if($params['in_corso'] =="0" && $params['in_scadenza'] =="0" && $params['scadute'] =="0" && $params['recenti'] =="0")
            {
                //Array vuoto
                return array(0=>0,array());
            }
            
            //default
            if(!isset($params['data_scadenzario']) || $params['data_scadenzario'] == "") $params['data_scadenzario']=Date("Y-m-d");
            if(!isset($params['finestra_temporale']) || $params['finestra_temporale'] =="") $params['finestra_temporale']=1;
            if(!isset($params['raggruppamento']) || $params['raggruppamento'] == "") $params['raggruppamento']=0; //ricerca in base all'incarico

            //organismi cessati
            if($params['cessati'] != 1) $where.=" AND ((".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".stato_organismo <> 4 AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".stato_organismo <> 0 ) OR (".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".stato_organismo = 0 AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".data_fine_impegno > '".$now."'))";

            //if(isset($params['raggruppamento']) && $params['raggruppamento']==1) 
            {
                $select="SELECT DISTINCT ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id, ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".denominazione, ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".aggiornamento, ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".nome,".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".cognome,".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".codice_fiscale, MAX(".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".data_fine) as data_fine_incarico FROM ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE." ";
                $group.=",".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".nome, ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".cognome";
            }
            /*else/
            {
                $select="SELECT DISTINCT ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id, ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".denominazione, ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".aggiornamento, ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".tipo_incarico, MAX(".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".data_fine) as data_fine_incarico FROM ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE." ";
                $group.=",".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".tipo_incarico";
            }*/
            
            $join=" LEFT JOIN ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE." on ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".id_organismo=".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".id";
            
            $where.= " AND ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".data_inizio <= '".$params['data_scadenzario']."' AND ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".nomina_ras =1";

            $meseProx = new DateTime($params['data_scadenzario']);
            $meseProx->modify("+".$params['finestra_temporale']." month");            
            $mesePrec = new DateTime($params['data_scadenzario']);
            $mesePrec->modify("-".$params['finestra_temporale']." month");
            
            //In corso
            if(isset($params['in_corso']) && $params['in_corso'] =="1")
            {
                if($having =="") $having=" HAVING ";
                $having.=" data_fine_incarico >= '".$meseProx->format("Y-m-d")."' ";
            }
            
            //Scaduti
            if(isset($params['scadute']) && $params['scadute'] =="1")
            {
                
                if($having =="") $having.=" HAVING data_fine_incarico >= '".$mesePrec->format("Y-m-d")."' ";
                else $having.=" OR data_fine_incarico <= '".$mesePrec->format("Y-m-d")."' ";
            }
            
            //In scadenza
            if(isset($params['in_scadenza']) && $params['in_scadenza'] == "1")
            {                                
                if($having =="") $having.=" HAVING (data_fine_incarico < '".$meseProx->format("Y-m-d")."' AND data_fine_incarico > '". addslashes($params['data_scadenzario'])."') ";
                else $having.=" OR (data_fine_incarico < '".$meseProx->format("Y-m-d")."' AND data_fine_incarico > '".addslashes ($params['data_scadenzario'])."') ";
            }

            //Scadute da breve termine
            if(isset($params['recenti']) && $params['recenti'] == "1")
            {
                if($params['raggruppamento']==0) //ricerca in base all'incarico
                {
                    if($having =="") $having.=" HAVING (data_fine_incarico > '".$mesePrec->format("Y-m-d")."' AND data_fine_incarico < '".addslashes($params['data_scadenzario'])."') ";
                    else $having.=" OR (data_fine_incarico > '".$mesePrec->format("Y-m-d")."' AND data_fine_incarico < '".addslashes($params['data_scadenzario'])."') ";     
                }
                else
                {
                    if($having =="") $having.=" HAVING (data_fine_incarico > '".$mesePrec->format("Y-m-d")."' AND data_fine_incarico < '".addslashes($params['data_scadenzario'])."') ";
                    else $having.=" OR (data_fine_incarico > '".$mesePrec->format("Y-m-d")."' AND data_fine_incarico < '".addslashes($params['data_scadenzario'])."') ";     
                }
            }

            //Non considerare le nomine archiviate (storiche)
            if(!isset($params['archivio']) || $params['archivio'] != 1) $where.=" AND ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE.".storico = 0 ";
            
            if(strlen($where)>0) $where=" WHERE 1 ".$where;
            $select="SELECT id,denominazione,aggiornamento FROM (".$select.$join.$where.$group.$having.") as organismi_scadenzario GROUP BY id";
            $join="";
            $group="";
            $where="";
            $having="";
            //$order="";
            $scadenzario_count=10;
            if(isset($params['count']) && intval($params['count'])>0 || $params['count']=="all") $scadenzario_count=$params['count'];
            $params['count']="all";
            $ricerca_scadenzario=true;
        }
        else
        {
            //Ricerca ordinaria (al di fuori dello scadenzario)
            if(isset($params['stato_organismo']) && $params['stato_organismo'] != "" && intval($params['stato_organismo']) > 0 && $params['stato_organismo'] != 4 && $params['stato_organismo'] != 2) $where.=" AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".stato_organismo = '".$params['stato_organismo']."'";
            if(isset($params['stato_organismo']) && $params['stato_organismo'] == 4) $where.=" AND ((".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".stato_organismo = 4) OR (".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".data_fine_impegno < '".$now."' AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".stato_organismo = 0))";
            if(isset($params['stato_organismo']) && $params['stato_organismo'] == 2) $where.=" AND ((".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".stato_organismo = 2) OR (".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".data_fine_impegno > '".$now."' AND ".AA_Organismi_Const::AA_ORGANISMI_DB_TABLE.".stato_organismo = 0))";
        }     
        
        $tot_count=0;
        $db = new AA_Database();
        if(!$ricerca_scadenzario)
        {
            //Conta i risultati
            $query="SELECT COUNT(id) as tot FROM (".$select.$join;
            if(strlen($where) > 0) $query.=" WHERE 1 ".$where;
            $query.=$group.$having.") as organismi_filter";

            //AA_Log::Log(get_class()."->Search(".print_r($params,TRUE).") - query: $query",100);
            $db->Query($query);
            if($db->Query($query)===false)
            {
                //Imposta lo stato di errore
                AA_Log::Log(__METHOD__."(".print_r($params,TRUE).") - Errore nella query: $query",100, true, true);

                return array(0=>-1,array());
            }

            //AA_Log::Log(get_class()."->Search(".print_r($params,TRUE).") - query: $query",100);

            $rs=$db->GetResultSet();
            if(sizeof($rs) > 0) $tot_count=$rs[0]['tot'];

            //Restituisce un array vuoto se non trova nulla
            if($tot_count==0)
            {
                AA_Log::Log(__METHOD__."(".print_r($params,TRUE).") - query vuota: $query",0, false, true);
                return array(0=>0,array());
            }

            //Limita a 10 risultati di default
            if(!isset($params['from']) || $params['from'] < 0 || $params['from'] > $tot_count) $params['from'] = 0;
            if(!isset($params['count']) || $params['count'] < 0) $params['count'] = 10;
            if($params['from'] >= $tot_count && $tot_count >=10) $params['from']=$tot_count-10;

            //Restituisce solo il numero
            if($bOnlyCount) return array(0=>$tot_count,array());
        }

        //Effettua la query
        $query=$select.$join;
        if(strlen($where) > 0) $query.=" WHERE 1 ".$where;
        if($params['count'] !="all") $query.=$group.$having.$order." LIMIT ".$params['from'].",".$params['count'];
        else $query.=$group.$having.$order;
        if(!$db->Query($query))
        {
            //Errore query
            AA_Log::Log(__METHOD__."(".print_r($params,TRUE).") - Errore nella query: $query",100, true,true);
            return array(0=>-1,array());
        }

        //AA_Log::Log(get_class()."->Search(".print_r($params,TRUE).") - query: $query",100);

        $rs=$db->GetResultSet();
        
        if($ricerca_scadenzario)
        {
            $tot_count=sizeof($rs);
            if(!isset($params['from']) || $params['from'] < 0 || $params['from'] > $tot_count) $params['from'] = 0;
            //if(!isset($params['count']) || $params['count'] < 0) $params['count'] = 10;
            if($params['from'] >= $tot_count && $tot_count >=10) $params['from']=$tot_count-10;
        }

        //Popola l'array dei risultati
        $results=array();
        $curRecIndex=0;
        $curRecCount=0;
        if(sizeof($rs) > 0)
        {
            $data_scadenzario=new DateTime($params['data_scadenzario']);
            //AA_Log::Log(__METHOD__." - params: ".print_r($params,true)." - data scadenzario: ".print_r($data_scadenzario,true),100);

            foreach($rs as $curRow)
            {
                $curResult=new AA_Organismi($curRow['id'],$user);
                if($curResult != null)
                {
                    //verifica organigrammi;
                    if($ricerca_scadenzario)
                    {
                        $nomine=$curResult->GetNomineScadenzario($params);
                        if(sizeof($nomine)>0) 
                        {
                            $bInsert=false;

                            foreach($nomine as $nomina)
                            {
                                foreach($nomina as $curNomina)
                                {
                                    $datafine=new DateTime($curNomina->GetDataFine());
                                
                                    if($params['in_corso']=="1" && $datafine > $meseProx)
                                    {
                                        $bInsert=true;
                                    }
                                        
                                    if($params['in_scadenza']=="1" && $datafine >= $data_scadenzario && $datafine <= $meseProx)
                                    {
                                        $bInsert=true;
                                    }
                                    
                                    if($params['recenti']=="1" && $datafine >= $mesePrec && $datafine <= $data_scadenzario)
                                    {
                                        $bInsert=true;
                                        //AA_Log::Log(__METHOD__." - Inserisco: ".$curNomina->GetNome()." - organismo: ".$curResult->GetDenominazione()." - bInsert: ".$bInsert,100);
                                    }
                                    
                                    if($params['scadute']=="1" && $datafine < $mesePrec)
                                    {
                                        $bInsert=true;
                                    }
                
                                    if($params['in_corso']=="1" && strpos($curNomina->GetDataFine(),"9999") !== false)
                                    {
                                        $bInsert=true;
                                    }
                                }
                            }

                            //if(!$bInsert)
                            //{
                            //    AA_Log::Log(__METHOD__." - Escludo: ".$curResult->GetDenominazione(). " - bInsert: ".$bInsert,100);
                            //} 

                            if($curRecIndex >= $params['from'] && ($curRecCount < $scadenzario_count || $scadenzario_count=="all") && $bInsert)
                            {
                                $results[$curRow['id']]=$curResult;
                                $curRecCount++;
                            }
                            if(!$bInsert) 
                            {
                                $tot_count--;
                                $params['from']++;
                            }
                            
                            $curRecIndex++;
                        }
                        else 
                        {
                            $tot_count--;
                            $params['from']++;
                        }
                    }
                    else $results[$curRow['id']]=$curResult;
                }
            }
        }

        $result=array(0=>$tot_count,$results);

        return $result;
    }
    //------------------------------------------------------------------------------

    //Restituisce i dati contabili legati all'organismo, opzionalmente in un arco temporale limitato, ordinati dal più recente al meno recente
    /**
     * Summary of GetDatiContabili
     * @param mixed $dal
     * @param mixed $al
     * @return array<AA_OrganismiDatiContabili>
     */
    public function GetDatiContabili($dal="", $al="")
    {
        AA_Log::Log(__METHOD__."($dal,$al)");

        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__."($dal,$al) - oggetto non valido.");

            return array();
        }

        //Impostazione dei parametri
        $query="SELECT id from ".AA_Organismi_Const::AA_ORGANISMI_DATI_CONTABILI_DB_TABLE." where id_organismo='".$this->GetId()."'";
        if($dal !="" ) $query.=" AND anno >= '".addslashes($dal)."'";
        if($al !="") $query.=" AND anno <= '".addslashes($al)."'";
        $query.= " ORDER by anno desc";

        $db=new Database();
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__."($dal,$al) - errore nella query: ".$query,100,false,true);
            return array();
        }

        $result=array();

        $rs=$db->GetRecordSet();
        if($rs->GetCount()>0)
        {
            do
            {
                $bilancio=new AA_OrganismiDatiContabili($rs->Get('id'),$this,AA_User::GetCurrentUser());
                if($bilancio->IsValid()) $result[$bilancio->GetId()]=$bilancio;
            }while($rs->MoveNext());
        }

        return $result;
    }

    //Restituisce le nomine legati all'organismo, opzionalmente in un arco temporale limitato, ordinati dal più recente al meno recente
    /**
     * Summary of GetNomine
     * @param mixed $dal
     * @param mixed $al
     * @param mixed $onlyRas
     * @return array<AA_OrganismiNomine>
     */
    public function GetNomine($dal="", $al="", $onlyRas=false)
    {
        AA_Log::Log(__METHOD__."($dal,$al)");

        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__."($dal,$al) - oggetto non valido.");

            return array();
        }

        //Impostazione dei parametri
        $query="SELECT id from ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE." where id_organismo='".$this->GetId()."'";
        if($dal !="" ) $query.=" AND data_fine >= '".addslashes($dal)."'";
        if($al !="") $query.=" AND data_inizio <= '".addslashes($al)."'";
        if($onlyRas) $query.=" AND nomina_ras = '1'";
        $query.= " ORDER by data_inizio DESC, data_fine asc, tipo_incarico, nome, cognome";

        //AA_Log::Log(__METHOD__."($dal,$al,$onlyRas) - ".$query,100);
         
        $db=new Database();
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__."($dal,$al) - errore nella query: ".$query,100,false,true);
            return array();
        }

        $result=array();

        $rs=$db->GetRecordSet();
        if($rs->GetCount()>0)
        {
            do
            {
                $nomina=new AA_OrganismiNomine($rs->Get('id'),$this,AA_User::GetCurrentUser());
                if($nomina->IsValid()) $result[$nomina->GetId()]=$nomina;
            }while($rs->MoveNext());
        }

        return $result;
    }
    
    //Restituisce le nomine legati all'organismo raggruppate per nominato
    /**
     * Summary of GetNomineGrouped
     * @param mixed $params
     * @return array<array>
     */
    public function GetNomineGrouped($params=array())
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__."() - oggetto non valido.");

            return array();
        }

        //Impostazione dei parametri
        $query="SELECT id, nome, cognome, data_fine from ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE." where id_organismo='".$this->GetId()."'";

        //Nascondi le nomine scadute
        if($params['scadute']=="0") $query.=" AND (data_fine > NOW())";
        
        //Nascondi quelle in corso
        if($params['in_corso']=="0") $query.=" AND (data_fine < NOW())";
        
        //Nascondi nomine RAS
        if($params['nomina_ras']=="0") $query.=" AND nomina_ras='0'";
        
        //Nascondi altre nomine
        if($params['nomina_altri']=="0") $query.=" AND nomina_ras='1'";
        
        //Parametri scadenzario
        if($params['scadenzario_dal'] !="") $query.=" AND data_fine >= '".$params['scadenzario_dal']."'";
        if($params['scadenzario_al'] !="") $query.=" AND data_fine <= '".$params['scadenzario_al']."'";
        
        //Tipo incarico
        if($params['tipo'] > 0) $query.=" AND tipo_incarico='".$params['tipo']."'";
        
        $query.= " ORDER by cognome, nome, tipo_incarico, data_fine DESC, data_inizio DESC, codice_fiscale";

        $db=new AA_Database();
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__."() - errore nella query: ".$query,100,false,true);
            return array();
        }

        //AA_Log::Log(__METHOD__."() - query: ".$query,100);
        
        $result=array();

        $rs=$db->GetResultSet();
        
        foreach($rs as $curNomina)
        {
            $nomina=new AA_OrganismiNomine($curNomina['id'],$this,$this->oUser);
            if($nomina->IsValid())
            {
                $index=base64_encode(trim(strtolower($nomina->GetNome()))."|".trim(strtolower($nomina->GetCognome()))."|".trim(strtolower($nomina->GetCodiceFiscale())));
                if(isset($params['raggruppamento']) && $params['raggruppamento'] == "0")  $index=$nomina->GetTipologia(true);
                
                $result[$index][$curNomina['id']]=$nomina;
            }
        }
        
        //AA_Log::Log(__METHOD__."() - result: ".print_r(array_keys($result),true),100);
        
        return $result;
    }

    //Restituisce le nomine raggruppate per tipologia di incarico
    /**
     * Summary of GetNomineGroupedForOrganigramma
     * @param mixed $params
     * @return array<array>
     */
    public function GetNomineGroupedForOrganigramma($params=array())
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__."() - oggetto non valido.");

            return array();
        }

        //Impostazione dei parametri
        $query="SELECT id, nome, cognome, data_fine, tipo_incarico, nomina_ras, facente_funzione,data_inizio from ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE." where id_organismo='".$this->GetId()."'";

        //Nascondi le nomine scadute
        if($params['scadute']=="0") $query.=" AND (data_fine > NOW())";
        
        //Nascondi quelle in corso
        if($params['in_corso']=="0") $query.=" AND (data_fine < NOW())";
        
        //Nascondi nomine RAS
        if($params['nomina_ras']=="0") $query.=" AND nomina_ras='0'";
        
        //Nascondi altre nomine
        if($params['nomina_altri']=="0") $query.=" AND nomina_ras='1'";
        
        //Parametri scadenzario
        if($params['scadenzario_dal'] !="") $query.=" AND data_fine >= '".$params['scadenzario_dal']."'";
        if($params['scadenzario_al'] !="") $query.=" AND data_fine <= '".$params['scadenzario_al']."'";
        
        //Tipo incarico
        if($params['tipo'] > 0) $query.=" AND tipo_incarico='".$params['tipo']."'";
        
        $query.= " ORDER by tipo_incarico, data_fine DESC, data_inizio DESC, nome ,cognome, codice_fiscale";

        $db=new AA_Database();
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__."() - errore nella query: ".$query,100,false,true);
            return array();
        }

        //AA_Log::Log(__METHOD__."() - query: ".$query,100);
        
        $result=array();

        $rs=$db->GetResultSet();
        
        foreach($rs as $curNomina)
        {
            //$nomina=new AA_OrganismiNomine($curNomina['id'],$this,$this->oUser);
            //if($nomina->IsValid())
            {
                //$index=base64_encode(trim(strtolower($nomina->GetNome()))."|".trim(strtolower($nomina->GetCognome()))."|".trim(strtolower($nomina->GetCodiceFiscale())));
                //if($params['raggruppamento'] == "0")  $index=$nomina->GetTipologia(true);
                
                if(isset($result[$curNomina['tipo_incarico']]) && isset($result[$curNomina['tipo_incarico']][$curNomina['nomina_ras']]) && is_array($result[$curNomina['tipo_incarico']][$curNomina['nomina_ras']])) $result[$curNomina['tipo_incarico']][$curNomina['nomina_ras']][]=$curNomina;
                else 
                {
                    if(!isset($result[$curNomina['tipo_incarico']])) $result[$curNomina['tipo_incarico']]=array();
                    $result[$curNomina['tipo_incarico']][$curNomina['nomina_ras']]=array($curNomina);
                }
                
            }
        }
        
        //AA_Log::Log(__METHOD__."() - result: ".print_r($result,true),100);
        
        return $result;
    }

    //Restituisce gli organigrammi dell'organismo
    /**
     * Summary of GetOrganigrammi
     * @param mixed $params
     * @return array<AA_Organismi_Organigramma>
     */
    public function GetOrganigrammi($params=array())
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__."() - oggetto non valido.");

            return array();
        }

        //Impostazione dei parametri
        $query="SELECT id,tipo,enable_scadenzario,note from ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA." where id_organismo='".$this->GetId()."'";
        $query.=" ORDER by ordine,tipo";

        $db=new AA_Database();
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__."() - errore nella query: ".$query,100,false,true);
            return array();
        }

        //AA_Log::Log(__METHOD__."() - query: ".$query,100);
        
        $result=array();

        $rs=$db->GetResultSet();
        
        foreach($rs as $curRow)
        {
            $org=new AA_Organismi_Organigramma($curRow);
            if($org instanceof AA_Organismi_Organigramma)
            {
                $result[$curRow['id']]=$org;
            }
        }
        
        //AA_Log::Log(__METHOD__."() - result: ".print_r(array_keys($result),true),100);
        
        return $result;
    }

    //Restituisce true se l'organismo ha almeno un organigramma impostato
    /**
     * Summary of HasOrganigrammi
     * @param mixed $params
     * @return bool
     */
    public function HasOrganigrammi($params=array())
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__."() - oggetto non valido.");

            return false;
        }

        //Impostazione dei parametri
        $query="SELECT id from ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA." where id_organismo='".$this->GetId()."'";

        $db=new AA_Database();
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__."() - errore nella query: ".$query,100,false,true);
            return false;
        }

        //AA_Log::Log(__METHOD__."() - query: ".$query,100);

        if($db->GetAffectedRows()>0)
        {
            return true;
        }

        return false;
    }

    //Restituisce l'organigramma indicato
    /**
     * Summary of GetOrganigramma
     * @param mixed $id
     * @return AA_Organismi_Organigramma|null
     */
    public function GetOrganigramma($id=0)
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__."() - oggetto non valido.");

            return null;
        }

        if($id <= 0)
        {
            AA_Log::Log(__METHOD__."() - identificativo non valido.");

            return null;
        }

        //Impostazione dei parametri
        $query="SELECT id,tipo,enable_scadenzario,ordine,note from ".AA_Organismi_Const::AA_DBTABLE_ORGANIGRAMMA." where id_organismo='".$this->GetId()."' AND id='".addslashes($id)."'";
        $query.= " ORDER by tipo";

        $db=new AA_Database();
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__."() - errore nella query: ".$query,100,false,true);
            return null;
        }

        //AA_Log::Log(__METHOD__."() - query: ".$query,100);

        $rs=$db->GetResultSet();
        
        foreach($rs as $curRow)
        {
            $org=new AA_Organismi_Organigramma($curRow);
            if($org instanceof AA_Organismi_Organigramma)
            {
                return $org;
            }
        }
        
        //AA_Log::Log(__METHOD__."() - result: ".print_r(array_keys($result),true),100);
        
        return null;
    }

    //Restituisce la lista degli organigrammi associati all'organismo
    /**
     * Summary of GetListOrganigrammi
     * @return array
     */
    public function GetListOrganigrammi()
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__."() - oggetto non valido.");

            return array();
        }

        return array();
    }

    //Restituisce le nomine legati all'organismo raggruppate per nominato
    /**
     * Summary of GetNomineScadenzario
     * @param mixed $params
     * @return array<array>
     */
    public function GetNomineScadenzario($params=array())
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__."() - oggetto non valido.");

            return array();
        }

        $organigrammi=$this->GetOrganigrammi();
        $nomine_index=array();

        //restituisce il dato senza filtro organigrammi se non ce ne sono
        if(sizeof($organigrammi) == 0 || $params['raggruppamento'] != "0")
        {
            //Impostazione dei parametri
            $query="SELECT id, nome,cognome,codice_fiscale, data_fine, tipo_incarico from ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE." where id_organismo='".$this->GetId()."'";
            
            //Nascondi nomine RAS
            if($params['nomina_ras']=="0") $query.=" AND nomina_ras='0'";
            
            //Nascondi altre nomine
            if($params['nomina_altri']=="0") $query.=" AND nomina_ras='1'";

            //Tipo incarico
            if($params['tipo_nomina'] > 0) $query.=" AND tipo_incarico='".$params['tipo_nomina']."'";

            //nomine storiche
            if($params['archivio'] != 1) $query.=" AND storico = 0";

            //incaricato
            if($params['incaricato'] != "") $query.=" AND (nome like '%".addslashes($params['incaricato'])."%' OR cognome like '%".addslashes($params['incaricato'])."%' OR codice_fiscale like '%".addslashes($params['incaricato'])."%') ";

            //if($params['raggruppamento'] == "0") $query.= " GROUP by tipo_incarico, nome, cognome ";
            //$query.= " GROUP by nome, cognome ";
            
            /*$query.= " HAVING id > 0 ";

            //Nascondi le nomine scadute
            if($params['scadute']=="0") $query.=" AND (data_fine > NOW())";
            
            //Nascondi quelle in corso
            if($params['in_corso']=="0") $query.=" AND (data_fine < NOW())";
            
            //Parametri scadenzario
            if($params['scadenzario_dal'] !="") $query.=" AND data_fine >= '".$params['scadenzario_dal']."'";
            if($params['scadenzario_al'] !="") $query.=" AND data_fine <= '".$params['scadenzario_al']."'";*/

            $query.=" ORDER by nome, cognome, codice_fiscale, data_fine DESC, tipo_incarico, data_inizio DESC";

            $db=new AA_Database();
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__."() - errore nella query: ".$query,100,false,true);
                return array();
            }

            //AA_Log::Log(__METHOD__."() - query: ".$query,100);
            
            $result=array();
            $blacklist=array();

            $rs=$db->GetResultSet();
            
            $curIndex="";
            foreach($rs as $curNomina)
            {
                $insert=0;
                $index = trim(strtolower($curNomina['nome']))."|".trim(strtolower($curNomina['cognome']))."|".trim(strtolower($curNomina['codice_fiscale']));
                if($curIndex != $index)
                {
                    //AA_Log::Log(__METHOD__."() - aggiorno index: ".print_r($curNomina,true)." - oldIndex: ".$curIndex." - new index: ".$index ,100);
                    $insert++;
                    $curIndex=$index;

                    //mette in black list gli eventuali altri incarichi precedenti riferiti alla stessa nomina
                    if($curNomina['data_fine'] >= $params['scadenzario_al'] && $params['scadenzario_al'] != "")
                    {
                        //AA_Log::Log(__METHOD__."() - blacklisto: ".print_r($curNomina,true),100);
                        $blacklist[$index]=1;
                    }
                    
                    if(!isset($blacklist[$index]) || $blacklist[$index] != 1)
                    {
                                if($params['scadenzario_dal'] !="" && $curNomina['data_fine'] >= $params['scadenzario_dal']) $insert++;
                                if($params['scadenzario_al'] !="" && $curNomina['data_fine'] <= $params['scadenzario_al']) $insert++;
                                if($params['scadenzario_dal'] == "") $insert++;
                                if($params['scadenzario_al'] == "") $insert++;        
                    }
                }

                if($insert>=3)
                {
                    //AA_Log::Log(__METHOD__."() - insert: ".$insert." - inserisco: ".print_r($curNomina,true),100);
                    if($params['raggruppamento'] == "0" && !isset($result[$curNomina['tipo_incarico']][$index]))
                    {
                        $nomina=new AA_OrganismiNomine($curNomina['id'],$this,$this->oUser);
                        if($nomina->IsValid())
                        {
                            $result[$curNomina['tipo_incarico']][$index]=$nomina;
                            $blacklist[$index]=1;
                        }    
                    }
                    else
                    {
                        $nomina=new AA_OrganismiNomine($curNomina['id'],$this,$this->oUser);
                        if($nomina->IsValid())
                        {
                            $result[base64_encode($index)][$curNomina['id']]=$nomina;
                            $blacklist[$index]=1;
                        }
                    }
                }
            }

            if($params['raggruppamento'] == "0")
            {
                foreach(array_keys($result) as $curKey)
                {
                    ksort($result[$curKey]);
                }
            }

            return $result;
        }
        
        //------------ matching organigrammi ------------------------
        //AA_Log::Log(__METHOD__."() - matching organigrammi",100);
        $nomine=$this->GetNomineGroupedForOrganigramma();
        $result=array();
        $nomine_index=array();
        $now=date("Y-m-d");
        $curNominaIndex=0;
        foreach($organigrammi as $curOrganigramma)
        {
            if($curOrganigramma->IsScadenzarioEnabled())
            {
                $incarichi=$curOrganigramma->GetIncarichi();
                //AA_Log::Log(__METHOD__." - ".print_r($incarichi,TRUE),100);
                foreach($incarichi as $id_incarico=>$incarico)
                {
                    if($incarico->IsScadenzarioEnabled())
                    {
                        //dati incarichi organigramma per riepilogo
                        $curTipoIncarico=$incarico->GetTipologia(true);
                        $scaduto=false;
                        $vacante=true;
                        $opzionale=false;
                        $ras=false;
                        $tempo_indeterminato=false;
                        if($incarico->IsOpzionale()) 
                        {
                            $opzionale=true;
                        }
                        if($incarico->IsNominaRas())
                        {
                            $ras=true;
                        }
                        if(!is_array($nomine_index[$curTipoIncarico])) $nomine_index[$curTipoIncarico]=array($incarico->getProp('ras')=>0);
                        
                        //if($this->GetId()==25) AA_Log::Log(__METHOD__." - curTipoIncarico: $curTipoIncarico - NominaRas: ".$incarico->getProp('ras')." - ".print_r($nomine[$curTipoIncarico][$incarico->getProp('ras')],TRUE),100);

                        if(is_array($nomine[$curTipoIncarico][$incarico->getProp('ras')]) && sizeof($nomine[$curTipoIncarico][$incarico->getProp('ras')]) > $nomine_index[$curTipoIncarico][$incarico->getProp('ras')])
                        {
                            $curNominaIndex=0+$nomine_index[$curTipoIncarico][$incarico->getProp('ras')];
                            if($nomine[$curTipoIncarico][$incarico->getProp('ras')][$curNominaIndex]['data_fine'] < $now) 
                            {
                                $scaduto=true;
                            }
                            $dataScadenza=$nomine[$curTipoIncarico][$incarico->getProp('ras')][$curNominaIndex]['data_fine'];
                            if($dataScadenza == "9999-12-31") $tempo_indeterminato=true;
                            if(!$opzionale || ($opzionale && !$scaduto))
                            {
                                $vacante=false;
                                $nomine_index[$curTipoIncarico][$incarico->getProp('ras')]+=1;
                            }
                        }

                        //nomina scaduta
                        if($scaduto && !$vacante)
                        {
                            //if((!isset($params['scadenzario_al']) || $params['scadenzario_al'] =="") || ( isset($params['scadenzario_al']) && $params['scadenzario_al'] !="" && $nomine[$curTipoIncarico][$incarico->getProp('ras')][$curNominaIndex]['data_fine'] <= $params['scadenzario_al'])) 
                            {
                                $index = trim(strtolower($nomine[$curTipoIncarico][$incarico->getProp('ras')][$curNominaIndex]['nome']))."|".trim(strtolower($nomine[$curTipoIncarico][$incarico->getProp('ras')][$curNominaIndex]['cognome']))."|".trim(strtolower($nomine[$curTipoIncarico][$incarico->getProp('ras')][$curNominaIndex]['codice_fiscale']));
                                $nomina=new AA_OrganismiNomine($nomine[$curTipoIncarico][$incarico->getProp('ras')][$curNominaIndex]['id'],$this,$this->oUser);
                                $result[$curTipoIncarico][$index]=$nomina;    
                            }
                        }
                        //-------------------------

                        //nomina in scadenza
                        if(!$scaduto && !$vacante)
                        {
                            if((isset($params['scadenzario_al']) && $params['scadenzario_al'] !="" && $nomine[$curTipoIncarico][$incarico->getProp('ras')][$curNominaIndex]['data_fine'] <= $params['scadenzario_al']) || (!isset($params['scadenzario_al']) || $params['scadenzario_al'] =="")) 
                            {
                                $index = trim(strtolower($nomine[$curTipoIncarico][$incarico->getProp('ras')][$curNominaIndex]['nome']))."|".trim(strtolower($nomine[$curTipoIncarico][$incarico->getProp('ras')][$curNominaIndex]['cognome']))."|".trim(strtolower($nomine[$curTipoIncarico][$incarico->getProp('ras')][$curNominaIndex]['codice_fiscale']));
                                $nomina=new AA_OrganismiNomine($nomine[$curTipoIncarico][$incarico->getProp('ras')][$curNominaIndex]['id'],$this,$this->oUser);
                                $result[$curTipoIncarico][$index]=$nomina;    
                            }
                        }
                        //-----------------------    
                    }
                }
            }
        }
        //-------------------------------------------------
        
        //AA_Log::Log(__METHOD__." - organismo: ".$this->GetDenominazione()." - nomine: ".sizeof($result),100);

        return $result;
    }
}

#Classe per la gestione dei bilanci
Class AA_OrganismiDatiContabili extends AA_Object
{
    public function __construct($id=0,$parent=null,$user=null)
    {
        AA_Log::Log(get_class()."__construct($id)");

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();        
        }

        //Chiama il costruttore base
        parent::__construct($user);

        $this->SetType(AA_Const::AA_OBJECT_ART22_BILANCI);

        //Imposta la tabella del db sul db bind
        $this->oDbBind->SetTable(AA_Organismi_Const::AA_ORGANISMI_DATI_CONTABILI_DB_TABLE);

        //Disabilita l'aggiornamento dello stato nel db
        $this->EnableStatusDbSync(false);

        //Disabilita l'aggiornamento del campo aggiornamento nel db
        $this->EnableAggiornamentoDbSync(false);

        //Disabilita l'aggiornamento del campo utente nel db
        $this->EnableUserDbSync(false);

        //disabilita l'aggiornamento della struttura nel db
        $this->EnableStructDbSync(false);

        //Aggiunge i bindings ai campi del db
        $this->oDbBind->AddBind("nIdParent","id_organismo");
        $this->oDbBind->AddBind("nTipologia","tipo_bilancio");
        $this->oDbBind->AddBind("nAnno","anno");
        $this->oDbBind->AddBind("sOneriTotali","oneri_totali");
        $this->oDbBind->AddBind("sRisultatiBilancio","risultati_bilancio");
        $this->oDbBind->AddBind("sNote","note");
        $this->oDbBind->AddBind("nDipendenti","dipendenti");
        $this->oDbBind->AddBind("nDipendentiDir","dipendenti_dir");
        $this->oDbBind->AddBind("nDipendentiDet","dipendenti_det");
        $this->oDbBind->AddBind("nDipendentiDetDir","dipendenti_det_dir");
        $this->oDbBind->AddBind("sSpesaDotazioneOrganica","spesa_complessiva_personale");
        $this->oDbBind->AddBind("sSpesaLavoroFlessibile","spesa_lavoro_flessibile");
        $this->oDbBind->AddBind("sSpesaIncarichi","spesa_incarichi");
        $this->oDbBind->AddBind("sFatturato","fatturato");
        $this->oDbBind->AddBind("Gap","gap");
        $this->oDbBind->AddBind("Gbc","gbc");

        if($parent instanceof AA_Organismi && $id==0)
        {
            //Imposta l'elemento padre
            parent::SetParent($parent);

            //Imposta il controllo dei permessi sul padre
            //AA_Log::Log(__METHOD__." - Abilito il controllo dei permessi del genitore.",100, false,true);
            $this->EnableParentPermsCheck();

            //Disabilita il controllo dei permessi locale
            $this->DisableLocalPermsCheck();
            
            //Abilita l'aggiornamento del padre quando viene aggiornato questo elemento
            $this->EnableUpdateParent();
        }

        if($id > 0)
        {
            if($this->LoadFromDb($id,$user))
            {
                if(!($parent instanceof AA_Organismi)) $parent=AA_Organismi::Load($this->nIdParent,$user);
                if($parent instanceof AA_Organismi && $parent->GetID() != $this->nIdParent) $parent=AA_Organismi::Load($this->nIdParent,$user);
                    
                if($parent instanceof AA_Organismi)
                {
                    //Imposta l'elemento padre
                    parent::SetParent($parent);

                    //Imposta il controllo dei permessi sul padre
                    //AA_Log::Log(__METHOD__." - Abilito il controllo dei permessi del genitore.",100, false,true);
                    $this->EnableParentPermsCheck();

                    //Disabilita il controllo dei permessi locale
                    $this->DisableLocalPermsCheck();
                    
                    //Abilita l'aggiornamento del padre quando viene aggiornato questo elemento
                    $this->EnableUpdateParent();
                }
                else
                {
                    AA_Log::Log(__METHOD__." - Genitore non trovato o non esistente: ".$parent,100, false,true);
                }    
            }
            else
            {
                AA_Log::Log(__METHOD__." - Genitore non trovato o non esistente (identificativo: $id).",100, false,true);    
            }
        }
        else
        {
            AA_Log::Log(__METHOD__." - Genitore non impostato.",100, false,true);
        }
    }

    //Gap
    protected $Gap=0;
    public function IsInGap()
    {
        if($this->Gap > 0) return true;
        else return false;
    }
    public function SetInGap($val=true)
    {
        if($val>0) $this->Gap=1;
        else $this->Gap=0;
    }

    //Gbc
    protected $Gbc=0;
    public function IsInGbc()
    {
        if($this->Gbc > 0) return true;
        else return false;
    }
    public function SetInGbc($val=true)
    {
        if($val>0) $this->Gbc=1;
        else $this->Gbc=0;
    }

    //Identitficativo del genitore
    protected $nIdParent=0;
    public function GetParent($user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();        
        }
        
        $parent=parent::GetParent();
        if( $parent instanceof AA_Organismi && $parent->GetId() == $this->nIdParent) return $parent;

        if($this->nIdParent > 0)
        {
            $parent=AA_Organismi::Load($this->nIdParent,$user);
        }

        if(!$parent->IsValid()) AA_Log::Log(__METHOD__." - Genitore non trovato o non esistente: ".$parent,100, false,true);
        return $parent;
    }

    //Imposta il genitore
    public function SetParent($parent=null,$user=null)
    {      
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();        
        }

        //Verifica che $parent sia un oggetto organismi
        if(!($parent instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - L'oggetto parente indicato non valido",100, false,true);
            return false;
        }

        //Verifica che l'utente possa modificare l'oggetto  
        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0 && ($parent->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $this->ClearCache();

            parent::SetParent($parent);
            $this->nIdParent=$parent->GetId();

            //Imposta il controllo dei permessi sul padre
            $this->EnableParentPermsCheck();

            //Disabilita il controllo dei permessi locale
            $this->DisableLocalPermsCheck();
            
            //Abilita l'aggiornamento del padre quando viene aggiornato questo elemento
            $this->EnableUpdateParent();

            return true;
        }
        else
        {
            AA_Log::Log(__METHOD__." - L'utente: ".$user->GetNome()." ".$user->GetCognome()." non ha i permessi per modificare l'oggetto o il genitore",100, false,true);
            return false;
        }
    }

    //Tipologia
    protected $nTipologia=0;
    public function GetTipologia($bNumeric=false)
    {
        if($bNumeric)return $this->nTipologia;
        
        $tipo_bilanci=AA_Organismi_Const::GetTipoBilanci();

        return $tipo_bilanci[$this->nTipologia];
    }
    public function SetTipologia($val=0)
    {
        if($val>=0) $this->nTipologia=$val;
    }

    //Note
    protected $sNote="";
    public function SetNote($val)
    {
        $this->sNote=$val;
    }
    public function GetNote()
    {
        return $this->sNote;
    }

    //Anno
    protected $nAnno=0000;
    public function SetAnno($val="")
    {
        if($val=="") $val=date("Y");
        $this->nAnno=$val;
    }
    public function GetAnno()
    {
        return $this->nAnno;
    }

    //Oneri Totali
    protected $sOneriTotali="";
    public function GetOneriTotali()
    {
        return $this->sOneriTotali;
    }
    public function SetOneriTotali($val="")
    {
        $this->sOneriTotali=preg_replace("/[€|\ |A-Za-z_]/", "",$val);
    }

    //Dotazione organica
    public function GetDotazioneOrganica()
    {
        return intVal($this->nDipendenti)+intVal($this->nDipendentiDet)+intVal($this->nDipendentiDir)+intVal($this->nDipendentiDetDir);
    }

    //dipendenti
    protected $nDipendenti="";
    public function GetDipendenti()
    {
        return $this->nDipendenti;
    }
    public function SetDipendenti($val="")
    {
        $this->nDipendenti=preg_replace("/[€|\ |A-Za-z_\,]/", "",$val);
    }

    //dipendenti dir
    protected $nDipendentiDir="";
    public function GetDipendentiDir()
    {
        return $this->nDipendentiDir;
    }
    public function SetDipendentiDir($val="")
    {
        $this->nDipendentiDir=preg_replace("/[€|\ |A-Za-z_\,]/", "",$val);
    }

     //dipendenti det
     protected $nDipendentiDet="";
     public function GetDipendentiDet()
     {
         return $this->nDipendentiDet;
     }
     public function SetDipendentiDet($val="")
     {
         $this->nDipendentiDet=preg_replace("/[€|\ |A-Za-z_\,]/", "",$val);
     }
 
     //dipendenti det dir
     protected $nDipendentiDetDir="";
     public function GetDipendentiDetDir()
     {
         return $this->nDipendentiDetDir;
     }
     public function SetDipendentiDetDir($val="")
     {
         $this->nDipendentiDetDir=preg_replace("/[€|\ |A-Za-z_\,]/", "",$val);
     }
 
    //Spesa complessiva dotazione organica
    protected $sSpesaDotazioneOrganica="";
    public function GetSpesaDotazioneOrganica()
    {
        return $this->sSpesaDotazioneOrganica;
    }
    public function SetSpesaDotazioneOrganica($val="")
    {
        $this->sSpesaDotazioneOrganica=preg_replace("/[€|\ |A-Za-z_]/", "",$val);
    }

    //Spesa lavoro flessibile
    protected $sSpesaLavoroFlessibile="";
    public function GetSpesaLavoroFlessibile()
    {
        return $this->sSpesaLavoroFlessibile;
    }
    public function SetSpesaLavoroFlessibile($val="")
    {
        $this->sSpesaLavoroFlessibile=preg_replace("/[€|\ |A-Za-z_]/", "",$val);
    }

    //Spesa incarichi
    protected $sSpesaIncarichi="";
    public function GetSpesaIncarichi()
    {
        return $this->sSpesaIncarichi;
    }
    public function SetSpesaIncarichi($val="")
    {
        $this->sSpesaIncarichi=preg_replace("/[€|\ |A-Za-z_]/", "",$val);
    }
    
    //Fatturato
    protected $sFatturato="";
    public function GetFatturato()
    {
        return $this->sFatturato;
    }
    public function SetFatturato($val="")
    {
        $this->sFatturato=preg_replace("/[€|\ |A-Za-z_]/", "",$val);
    }

    //Risultati di bilancio
    protected $sRisultatiBilancio="";
    public function GetRisultatiBilancio()
    {
        return $this->sRisultatiBilancio;
    }
    public function SetRisultatiBilancio($val="")
    {
        $this->sRisultatiBilancio=preg_replace("/[€|\ |A-Za-z_]/", "",$val);
    }

    public function UpdateDb($user=null,$data=null,$bLog=true,$sDetailLog="")
    {
        //verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            if($this->oUser->IsCurrentUser()) $user=$this->oUser;
            else $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,true,true);
                return false;
            }
        }

        return parent::UpdateDb($user,$data,$bLog,$sDetailLog);
    }

    //Funzione di Parsing a partire da un array (non cambia l'identificativo dell'oggetto)
    public function ParseData($data=null,$user=null)
    {
        //verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            if($this->oUser->IsCurrentUser()) $user=$this->oUser;
            else $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,true,true);
                return false;
            }
        }

        //Verifica di coerenza dei dati
        //if(is_array($data) && $data['nTipologia'] > 0 && $data['sRisultatiBilancio']=="")
        //{
        //    AA_Log::Log(__METHOD__." - Occorre inserire i risultati di bilancio.", 100,false,true);
        //    return false;
        //}

        if(!parent::ParseData($data,$user))
        {
            AA_Log::Log(__METHOD__." - Errore nel parsing dei dati.", 100,false,true);
            return false;
        }

        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $this->EnableDbSync();
            return true;
        }
        else
        {
            AA_Log::Log(__METHOD__." - L'utente (".$user->GetNome().") non può aggiornare il bilancio.", 100,false,true);
        }

        return false;
    }
    
    //Restituisce la rappresentazione in formato xml
    public function ToXml()
    {
        $return="<dato_contabile anno='".$this->GetAnno()."'>";
        $return.="<oneri>".$this->GetOneriTotali()."</oneri>";
        $return.="<spesa_lavoro_flessibile>".$this->GetSpesaLavoroFlessibile()."</spesa_lavoro_flessibile>";
        $return.="<spesa_incarichi>".$this->GetSpesaIncarichi()."</spesa_incarichi>";
        $return.="<dotazione_organica>".$this->GetDotazioneOrganica()."</dotazione_organica>";
        $return.="<dipendenti>".$this->GetDipendenti()."</dipendenti>";
        $return.="<spesa_dotazione_organica>".$this->GetSpesaDotazioneOrganica()."</spesa_dotazione_organica>";
        $return.="<note>".AA_Utils::xmlentities($this->GetNote())."</note>";
        $return.="<bilanci>";
        
        $bilanci = $this->GetBilanci();
        foreach($bilanci as $cur_bilancio)
        {
            $return.=$cur_bilancio->ToXml();
        }
        $return.="</bilanci>";
        $return.="</dato_contabile>";
        
        return $return;
    }

    //Aggiunge un nuovo organismo al db
    static public function AddNewToDb($data=null, $parent=null, $user=null)
    {
        AA_Log::Log(__METHOD__."($data)");

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return null;
            }
        }

        //Verifica Flags
        if(!$user->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$user->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            AA_Log::Log(__METHOD__." - l'utente non può gestire le pubblicazioni di cui all'art. 22 del d.lgs. 33/2013.", 100,false,true);
            return null;
        }

        //Verifica il parente
        if(!($parent instanceof AA_Organismi) || ($parent->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - Oggetto genitore non valido o l'utente non ha i permessi per modificarlo.", 100,false,true);
            return null;
        }

        $new_bilancio = new AA_OrganismiDatiContabili(0,$parent,$user);

        if(!$new_bilancio->ParseData($data,$user))
        {
            AA_Log::Log(__METHOD__." - Errore durante il parsing dei dati: ".print_r($data,TRUE), 100,false,true);
            return null;
        }

        $new_bilancio->SetID(0);
        if(!$new_bilancio->SetParent($parent))
        {
            return null;
        }

        $new_bilancio->EnableDbSync();
        if(!$new_bilancio->UpdateDb($user,null,true," Aggiunta nuovi dati contabili (anno ".$new_bilancio->GetAnno().")"))
        {
            AA_Log::Log(__METHOD__." - Errore durante il salvataggio del nuovo bilancio sul DB.", 100,false,true);
            return null;    
        }

        return $new_bilancio;
    }

    //Restituisce la lista dei bilanci collegati all'oggetto corrente
    public function GetBilanci($user=null, $tipo=0)
    {
        AA_Log::Log(__METHOD__."()");

        $return = array();

        //Verifica validità
        if(!$this->IsValid()) 
        {
            AA_Log::Log(__METHOD__." - oggetto corrente non valido.", 100,false,true);
            return $return;
        }

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return $return;
            }
        }

        $perms=$this->GetUserCaps($user);
        if(($perms & AA_Const::AA_PERMS_READ) == 0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non ha i permessi per accedere ai bilanci.", 100,false,true);
            return $return;
        }

        $db = new Database();

        $query="SELECT * from ".AA_Organismi_Const::AA_ORGANISMI_BILANCI_DB_TABLE." WHERE id_dati_contabili='".$this->GetId()."'";
        if($tipo > 0) $query.=" AND tipo_bilancio='".$tipo."'";
        $query.=" ORDER by id DESC";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query: ".$query, 100,false,true);
            return $return;    
        }

        $rs=$db->GetRecordSet();
        if($rs->GetCount() > 0)
        {
            do
            {
                $bilancio=new AA_OrganismiBilanci();
                $bilancio->SetId($rs->Get("id"));
                $bilancio->SetIdDatiContabili($rs->Get("id_dati_contabili"));
                $bilancio->SetTipo($rs->Get("tipo_bilancio"));
                $bilancio->SetRisultati($rs->Get("risultati"));
                $bilancio->SetNote($rs->Get("note"));
                $return[$rs->Get("id")]=$bilancio;
            }while($rs->MoveNext());
        }

        return $return;
    }

    //Restituisce un bilancio specifico
    public function GetBilancio($id=0,$user=null)
    {
        AA_Log::Log(__METHOD__."($id)");

        $return = null;

        //Verifica validità
        if(!$this->IsValid()) 
        {
            AA_Log::Log(__METHOD__." - oggetto corrente non valido.", 100,false,true);
            return $return;
        }

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return $return;
            }
        }

        $perms=$this->GetUserCaps($user);
        if(($perms & AA_Const::AA_PERMS_READ) == 0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non ha i permessi per accedere ai bilanci.", 100,false,true);
            return $return;
        }

        $db = new Database();

        $query="SELECT * from ".AA_Organismi_Const::AA_ORGANISMI_BILANCI_DB_TABLE." WHERE id_dati_contabili='".$this->GetId()."' AND id='".addslashes($id)."'";
        $query.=" LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query: ".$query, 100,false,true);
            return $return;    
        }

        $rs=$db->GetRecordSet();
        if($rs->GetCount() > 0)
        {
            $bilancio=new AA_OrganismiBilanci();
            $bilancio->SetId($rs->Get("id"));
            $bilancio->SetIdDatiContabili($rs->Get("id_dati_contabili"));
            $bilancio->SetTipo($rs->Get("tipo_bilancio"));
            $bilancio->SetRisultati($rs->Get("risultati"));
            $bilancio->SetNote($rs->Get("note"));
            
            $return=$bilancio;
        }

        return $return;
    }

    //Elimina un bilancio specifico
    public function DelBilancio($id=0,$user=null)
    {
        AA_Log::Log(__METHOD__."($id)");

        //Verifica validità
        if(!$this->IsValid()) 
        {
            AA_Log::Log(__METHOD__." - oggetto corrente non valido.", 100,false,true);
            return false;
        }

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return false;
            }
        }

        $perms=$this->GetUserCaps($user);
        if(($perms & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non ha i permessi per eliminare i bilanci.", 100,false,true);
            return false;
        }

        $bilancio=$this->GetBilancio($id);

        $db = new Database();

        $query="DELETE from ".AA_Organismi_Const::AA_ORGANISMI_BILANCI_DB_TABLE." WHERE id_dati_contabili='".$this->GetId()."' AND id='".addslashes($id)."'";
        $query.=" LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query: ".$query, 100,false,true);
            return false;    
        }

        $data=null;
        if($bilancio instanceof AA_OrganismiBilanci && $bilancio->GetTipo(true)==AA_Organismi_const::AA_ORGANISMI_BILANCIO_ESERCIZIO)
        {
            $data=array("nTipologia"=>0);
            $data=array("sRisultatiBilancio"=>"");
        }
        
        return $this->UpdateDb($user,$data,true," Rimozione ".$bilancio->GetTipo()." per l'anno ".$this->GetAnno()."");
    }

    //Elimina i bilanci
    public function DelBilanci($user=null)
    {
        AA_Log::Log(__METHOD__."()");

        //Verifica validità
        if(!$this->IsValid()) 
        {
            AA_Log::Log(__METHOD__." - oggetto corrente non valido.", 100,false,true);
            return false;
        }

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return false;
            }
        }

        $perms=$this->GetUserCaps($user);
        if(($perms & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non ha i permessi per eliminare i bilanci.", 100,false,true);
            return false;
        }

        $db = new Database();

        $query="DELETE from ".AA_Organismi_Const::AA_ORGANISMI_BILANCI_DB_TABLE." WHERE id_dati_contabili='".$this->GetId()."'";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query: ".$query, 100,false,true);
            return false;    
        }

        return $this->UpdateDb($user,array("sRisultatiBilancio"=>"","nTipologia"=>0));
    }

    //Aggiunge un bilancio
    public function AddNewBilancio($bilancio=null,$user=null)
    {
        AA_Log::Log(__METHOD__."()");

        //Verifica validità
        if(!$this->IsValid()) 
        {
            AA_Log::Log(__METHOD__." - oggetto corrente non valido.", 100,false,true);
            return false;
        }

        //verifica l'oggetto bilancio
        if(!($bilancio instanceof AA_OrganismiBilanci))
        {
            AA_Log::Log(__METHOD__." - oggetto bilancio non valido.", 100,false,true);
            return false;
        }

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return false;
            }
        }

        $perms=$this->GetUserCaps($user);
        if(($perms & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non ha i permessi per aggiungere bilanci.", 100,false,true);
            return false;
        }

        $db = new Database();

        $query="INSERT into ".AA_Organismi_Const::AA_ORGANISMI_BILANCI_DB_TABLE." SET id_dati_contabili='".$this->GetId()."'";
        $query.=",tipo_bilancio='".$bilancio->GetTipo(true)."'";
        $query.=",risultati='".$bilancio->GetRisultati()."'";
        $query.=",note='".$bilancio->GetNote()."'";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query: ".$query, 100,false,true);
            return false;    
        }

        $data=array();

        //Aggiorna il campo sul dato contabile se si tratta di bilancio di esercizio o risultato di amministrazione
        if($bilancio->GetTipo(true)==AA_Organismi_const::AA_ORGANISMI_BILANCIO_ESERCIZIO || $bilancio->GetTipo(true)==AA_Organismi_const::AA_ORGANISMI_BILANCIO_RISULTATO_AMMINISTRAZIONE)
        {
            $data=array("nTipologia"=>$bilancio->GetTipo(true),"sRisultatiBilancio"=>$bilancio->GetRisultati());
        }

        return $this->UpdateDb($user,$data,true," Aggiunta nuovo bilancio (".$bilancio->GetTipo().")");
    }

    //Aggiorna un bilancio
    public function UpdateBilancio($bilancio=null,$user=null)
    {
        AA_Log::Log(__METHOD__."($bilancio)");

        //Verifica validità
        if(!$this->IsValid()) 
        {
            AA_Log::Log(__METHOD__." - oggetto corrente non valido.", 100,false,true);
            return false;
        }

        //verifica l'oggetto bilancio
        if(!($bilancio instanceof AA_OrganismiBilanci))
        {
            AA_Log::Log(__METHOD__." - oggetto bilancio non valido.", 100,false,true);
            return false;
        }

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return false;
            }
        }

        $perms=$this->GetUserCaps($user);
        if(($perms & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non ha i permessi per aggiungere bilanci.", 100,false,true);
            return false;
        }

        $db = new Database();

        $query="UPDATE ".AA_Organismi_Const::AA_ORGANISMI_BILANCI_DB_TABLE." SET id_dati_contabili='".$this->GetId()."'";
        $query.=",tipo_bilancio='".$bilancio->GetTipo(true)."'";
        $query.=",risultati='".$bilancio->GetRisultati()."'";
        $query.=",note='".addslashes($bilancio->GetNote())."'";
        $query.=" WHERE id='".$bilancio->GetId()."' LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query: ".$query, 100,false,true);
            return false;    
        }

        $data=array();

        //Aggiorna il campo il dato contabile se si tratta di bilancio di esercizio
        if($bilancio->GetTipo(true)==AA_Organismi_const::AA_ORGANISMI_BILANCIO_ESERCIZIO || $bilancio->GetTipo(true)==AA_Organismi_const::AA_ORGANISMI_BILANCIO_RISULTATO_AMMINISTRAZIONE)
        {
            $data=array("nTipologia"=>$bilancio->GetTipo(true),"sRisultatiBilancio"=>$bilancio->GetRisultati());
        }

        return $this->UpdateDb($user,$data,true," Aggiornamento bilancio (".$bilancio->GetTipo().")");
        
    }

    //elimina dati contabili
    public function Trash($user=null,$bDelete=true)
    {
        AA_Log::Log(__METHOD__."()");

        //Verifica validità
        if(!$this->IsValid()) 
        {
            AA_Log::Log(__METHOD__." - oggetto corrente non valido.", 100,false,true);
            return false;
        }

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return false;
            }
        }

        $perms=$this->GetUserCaps($user);
        if(($perms & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non ha i permessi per aggiungere bilanci.", 100,false,true);
            return false;
        }

        //Elimina i bilanci
        if(!$this->DelBilanci($user))
        {
            AA_Log::Log(__METHOD__." - Errore durante l'eliminazione dei bilanci collegati.", 100,false,true);
            return false;
        }

        $return=parent::Trash($user,true);
        if($return)
        {
            $org=$this->GetParent($user);
            if($org)
            {
                $org->UpdateDb($user,null,true, " Rimozione dati contabili per l'anno ".$this->GetAnno());
            }
            
            return $return;
        }
    }
}

//Classe per la gestione dei provvedimenti
Class AA_OrganismiProvvedimenti
{
    protected $id=0;
    public function GetId()
    {
        return $this->id;
    }
    public function SetId($id=0)
    {
        $this->id=$id;
    }
    
    protected $url="";
    public function GetUrl()
    {
        return $this->url;
    }
    public function SetUrl($url="")
    {
        $this->url=$url;
    }
    
    protected $anno="";
    public function GetAnno()
    {
        return $this->anno;
    }
    public function SetAnno($anno="")
    {
        $this->anno=$anno;
    }
    
    protected $estremi="";
    public function GetEstremi()
    {
        return $this->estremi;
    }
    public function SetEstremi($val="")
    {
        $this->estremi=$val;
    }

    public function GetFilePath()
    {
        if(is_file(AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$this->id.".pdf"))
        {
            return AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$this->id.".pdf";
        }
        
        return "";
    }
    
    public function GetFilePublicPath()
    {
        if(is_file(AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PATH."/".$this->id.".pdf"))
        {
            return AA_Organismi_Const::AA_ORGANISMI_PROVVEDIMENTI_PUBLIC_PATH."?id=".$this->id."&id_organismo=".$this->id_organismo;
        }
        
        return "";
    }
    
    protected $tipo=0;
    public function GetTipologia($numeric=false)
    {
        if($numeric) return $this->tipo;
        
        $tipologia = AA_Organismi_Const::GetTipoProvvedimenti();
        
        //AA_Log::Log(__METHOD__." tipo: ".$this->tipo." - tipologia: ".$tipologia[$this->tipo]." - tipi: ".print_r($tipologia,true),100);
        
        return $tipologia[$this->tipo];
    }
    
    public function SetTipologia($tipo=0)
    {
        $tipologia= AA_Organismi_Const::GetTipoProvvedimenti();
        if($tipologia[$tipo] !="") $this->tipo=$tipo;
    }
    
    protected $id_organismo=0;
    public function GetIdOrganismo()
    {
        return $this->id_organismo;
    }
    public function SetIdOrganismo($id=0)
    {
        $this->id_organismo=$id;
    }
    
    public function __construct($id=0,$id_organismo=0,$tipo=0,$url="",$anno="",$estremi="")
    {
        //AA_Log::Log(__METHOD__." id: $id, id_organismo: $id_organismo, tipo: $tipo, url: $url",100);
        
        if($anno=="") $anno=Date("Y");
        $this->id=$id;
        $this->id_organismo=$id_organismo;
        $this->url=$url;
        $this->tipo=$tipo;
        $this->anno=$anno;
        $this->estremi=$estremi;
    }
    
    //Download del documento
    public function Download($embed=false)
    {
        $filename=$this->GetFilePath();

        if(is_file($filename))
        {
            header("Cache-control: private");
            header("Content-type: application/pdf");
            header("Content-Length: ".filesize($filename));
            if(!$embed) header('Content-Disposition: attachment; filename="'.$this->tipo."_".$this->id.'.pdf"');

            $fd = fopen ($filename, "rb");
            echo fread ($fd, filesize ($filename));
            fclose ($fd);
            die();
        }
        else
        {
            die("file non trovato");
        }
    }
}

#Classe per la gestione del template view degli item
Class AA_OrganismiAccordionTemplateItemView extends AA_GenericAccordionItemTemplateView
{
    #Costruttore standard
    public function __construct($object=null, $user=null)
    {
        parent::__construct("","",null,$user);

        $this->Initialize($object);
    }

    //HeaderView
    public function HeaderView()
    {
        //AA_Log::Log(get_class()."->HeaderView() titolo: ".$this->sTitle,100,true,true);
        return parent::HeaderView();
    }

    private function Initialize($object=null)
    {        
        if($object instanceof AA_Organismi)
        {
            //AA_Log::Log(get_class()."->Initialize($object, $user)",100,true,true);
            
            $permessi=$object->GetUserCaps($this->oUser);
            $canModify=false;
            if($permessi & AA_Const::AA_PERMS_WRITE) $canModify = true;
            $canPublish=false;
            if($permessi & AA_Const::AA_PERMS_PUBLISH) $canPublish = true;
            
            //Class
            $this->SetClass(get_class($object));
            $struct=$object->GetStruct();
            $struttura_gest=$struct->GetAssessorato();
            if($struct->GetDirezione() !="") $struttura_gest.=" -> ".$struct->GetDirezione();

            //Id object
            $this->SetId($object->GetID());

            #Società-----------
            if($object->GetTipologia(true)==AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
            {
                $soc_tags="";
                if($object->GetPartecipazione() == "" || $object->GetPartecipazione() == "0") $soc_tags.="<span style='padding:.1em .3em .1em .3em; color: orange; border: 1px solid orange; display: inline-block; margin-right: .5em;' class='ui-widget-content ui-corner-all ui-state-highlight' title='Società non direttamente partecipata dalla RAS'>indiretta</span>";
                if($object->IsInHouse() == true) $soc_tags.="<span style='padding:.1em .3em .1em .3em; color: green; border: 1px solid green; display: inline-block; margin-right: .5em;' class='ui-widget-content ui-corner-all ui-state-highlight'>in house</span>";
                if($object->IsInTUSP() == true) $soc_tags.="<span style='padding:.1em .3em .1em .3em; color: blue; border: 1px solid blue; display: inline-block; margin-right: .5em;' class='ui-widget-content ui-corner-all ui-state-highlight'>TUSP</span>";
                
                //forma giuridica
                $soc_tags.="<span style='padding:.1em .3em .1em .3em; color: DarkGreen; border: 1px solid DarkGreen; display: inline-block; margin-right: .5em;' class='ui-widget-content ui-corner-all ui-state-highlight'>".$object->GetFormaSocietaria()."</span>";
                
                //stato società
                if($object->GetStatoOrganismo(true) > AA_Organismi_Const::AA_ORGANISMI_STATO_SOCIETA_ATTIVO) $soc_tags.="<span style='padding:.1em .3em .1em .3em; color: DarkGreen; border: 1px solid DarkGreen; display: inline-block; margin-right: .5em;' class='ui-widget-content ui-corner-all ui-state-highlight'>".$object->GetStatoOrganismo()."</span>";

                if($soc_tags !="") $soc_tags="<div style='font-size: xx-small; font-weight: normal; margin-top: .2em'>".$soc_tags."</div>";
            }
            #------------------

            #Titolo
            $this->sTitle=$object->GetDescrizione().$soc_tags;

            #pretitolo
            $pretitolo="<span style='font-size:0.8em; padding:0.1em .3em .1em .3em;' class='ui-widget-content ui-corner-all ui-state-highlight'>".$object->GetTipologia().'</span>';
            $this->SetPreTitle($pretitolo);
            
            #Sottotitolo
            $this->sSubTitle="<span>".$struttura_gest."</span>";
            
            #Stato
            if($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) $this->sStatus="<span style='padding:.1em .3em .1em .3em;' class='ui-widget-content ui-corner-all ui-state-error'>bozza</span>&nbsp;";
            if($object->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $this->sStatus="<span style='padding: 0.1em .3em .1em .3em; display: inline-block;' class='ui-widget-content ui-corner-all ui-state-error'>pubblicato</span>&nbsp;";
            if($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $this->sStatus.="<span style='padding:0.1em .3em .1em .3em;' class='ui-widget-content ui-corner-all ui-state-error'>revisionato</span>&nbsp;";
            if($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $this->sStatus.="<span style='padding:0.1em .3em .1em .3em;' class='ui-widget-content ui-corner-all ui-state-error'>cestinato</span>&nbsp;";

            #Dettagli
            if($this->oUser->IsSuperUser() && $object->GetAggiornamento() != "") $this->sDetails="<span style='margin-left:0em;'>ultimo aggiornamento: ".$object->GetAggiornamento()." (".$object->GetUser()->GetUsername().")</span>";
            else
            {
                if($object->GetAggiornamento() != "") $this->sDetails="<span style='margin-left:0em;'>ultimo aggiornamento: ".$object->GetAggiornamento()."</span>";
            }
            $this->sDetails.="<span style='margin-left:1em;padding-left:.3em;padding-right:.3em;'>identificativo: ".$this->GetId()."</span>";

            #--------------------------------Contenuto ---------------------------------
            $this->sContent='<ul style="background: none;">
            <li tabNum="0"><a href="#generale-tab-'.$this->sId.'">Generale</a></li>
            <li tabNum="1"><a href="#bilanci-tab-'.$this->sId.'">Dati contabili e dotazione organica</a></li>
            <li tabNum="2"><a href="#nomine-tab-'.$this->sId.'">Nomine</a></li>
            </ul>';

            #Generale tab
            $this->sContent.='<div id="generale-tab-'.$this->sId.'" style="display:flex; flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap">';
            $generale_tab=new AA_OrganismiGeneralTabTemplateView("generale-content-".$this->sId,null,$object,$this->oUser);
            $this->sContent.=$generale_tab;
            $this->sContent.='</div>';
            #-------------------

            //Bilanci tab
            $this->sContent.='<div id="bilanci-tab-'.$this->sId.'" style="display:flex; flex-direction: column; justify-content: space-between; align-items: center;">';

            $template=new AA_OrganismiDatiContabiliListTemplateView("BilanciList-".$object->GetId(),null,$object,$this->oUser);
            $this->sContent.=$template;

            $this->sContent.='</div>';
            #-------------------

            //nomine tab
            $this->sContent.='<div id="nomine-tab-'.$this->sId.'" style="display:flex; flex-direction: column; justify-content: space-between; align-items: center;">';

            $template=new AA_OrganismiNomineListTemplateView("NomineList-".$object->GetId(),null,$object,$this->oUser);
            $this->sContent.=$template;

            $this->sContent.='</div>';
            #-------------------
            #---------------------------------------------------------------

            #Command box 
            //print button
            //to be done

            //Riassegna (solo per utenti che hanno la getione degli organismi)
            if($canModify && ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA)==0)
            {
                $this->sHeaderCommandBoxContent.="<span><button id='".$this->sClass."-riassegna-".$this->sId."' class='".$this->sClass."-riassegna' id-object='".$this->sId."' id-assessorato='".$struct->GetAssessorato(true)."' id-direzione='".$struct->GetDirezione(true)."' id-servizio='".$struct->GetServizio(true)."' title='riassegna ad altra struttura'>riassegna</button></span>";
            }
            
            //Modifica
            if($canModify && !($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA)) $this->sHeaderCommandBoxContent.="<span><button id='".$this->sClass."-modifica-".$this->sId."' class='".$this->sClass."-modifica' id-object='".$this->sId."'>Modifica</button></span>";

            //Pubblica
            if($permessi & AA_Const::AA_PERMS_PUBLISH && ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA)==0 && ($object->GetStatus() & AA_Const::AA_STATUS_BOZZA || $object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA))
            {
                $this->sHeaderCommandBoxContent.="<span><button id='".$this->sClass."-pubblica-".$this->sId."' class='".$this->sClass."-pubblica' id-object='".$this->sId."' stato='".$object->GetStatus()."'>pubblica</button></span>";
            }

            //Ripristina
            if($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA && $canModify)
            {
                $this->sHeaderCommandBoxContent.="<span><button id='".$this->sClass."-ripristina-".$this->sId."' class='".$this->sClass."-ripristina' id-object='".$this->sId."'>ripristina</button></span>";
            }
            
            //Elimina
            if($permessi & AA_Const::AA_PERMS_DELETE && $object->GetStatus() & AA_Const::AA_STATUS_CESTINATA)
            {
                $this->sHeaderCommandBoxContent.="<span><button id='".$this->sClass."-elimina-".$this->sId."' class='".$this->sClass."-elimina' cestinato='".($object->GetStatus()&AA_Const::AA_STATUS_CESTINATA)."' id-object='".$this->sId."' title='Elimina definitivamente'>elimina</button></span>";
            }
            
            //Cestina
            if($permessi & AA_Const::AA_PERMS_DELETE && !($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA))
            {
                $this->sHeaderCommandBoxContent.="<span><button id='".$this->sClass."-elimina-".$this->sId."' class='".$this->sClass."-elimina' cestinato='".($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA)."' id-object='".$this->sId."'>cestina</button></span>";
            }
            #-----------------------------------------------
        }
        else
        {
            $this->sTitle="";
            $this->sStatus="";
            $this->sDetails="";
            $this->sHeaderCommandBoxContent="";
        }

        //AA_Log::Log(get_class()."->Initialize() titolo: ".$this->sTitle,100,true,true);
    }

    public function SetOrganismo($object=null, $user=null)
    {
        $this->Initialize($object, $user);
    }
}
#----------------------------------------------------

#Classe per la gestione del template accordion view
Class AA_OrganismiAccordionTemplateView extends AA_GenericAccordionTemplateView
{

}
#-----------------------------------------------------

//Classe per la gestione dei task
Class AA_OrganismiTaskManager extends AA_SystemTaskManager
{
    public function __construct($user=null)
    {
        parent::__construct($user);

        //Registrazione task
        $this->RegisterTask("add-new-organismo","AA_OrganismiTask_AddNewOrganismo");
        $this->RegisterTask("addnew-organismo-dlg","AA_OrganismiTask_AddNewOrganismoDlg");
        $this->RegisterTask("update-organismo","AA_OrganismiTask_UpdateOrganismo");
        $this->RegisterTask("modify-organismo-dlg","AA_OrganismiTask_UpdateOrganismoDlg");
        $this->RegisterTask("organismi-modify-field-dlg","AA_OrganismiTask_ModifyFieldOrganismoDlg");
        $this->RegisterTask("publish-organismo-dlg","AA_OrganismiTask_PublishOrganismoDlg");
        $this->RegisterTask("publish-organismo","AA_OrganismiTask_PublishOrganismo");
        $this->RegisterTask("delete-organismo","AA_OrganismiTask_TrashOrganismo");
        $this->RegisterTask("delete-organismi-dlg","AA_OrganismiTask_TrashOrganismoDlg");
        $this->RegisterTask("resume","AA_OrganismiTask_ResumeOrganismo");
        $this->RegisterTask("resume-organismi-dlg","AA_OrganismiTask_ResumeOrganismiDlg");
        $this->RegisterTask("reassign","AA_OrganismiTask_ReassignOrganismo");
        $this->RegisterTask("search-organismi","AA_OrganismiTask_SearchOrganismi");
        $this->RegisterTask("search-organismo-dlg","AA_OrganismiTask_SearchOrganismoDlg");
        
        //Dati contabili
        $this->RegisterTask("add-new-dati-contabili-dlg","AA_OrganismiTask_AddNewDatiContabiliDlg");
        $this->RegisterTask("add-new-dati-contabili","AA_OrganismiTask_AddNewDatiContabili");
        $this->RegisterTask("edit-dati-contabili-dlg","AA_OrganismiTask_EditDatiContabiliDlg");
        $this->RegisterTask("edit-dati-contabili","AA_OrganismiTask_EditDatiContabili");
        $this->RegisterTask("trash-dati-contabili-dlg","AA_OrganismiTask_TrashDatiContabiliDlg");
        $this->RegisterTask("trash-dati-contabili","AA_OrganismiTask_TrashDatiContabili");

        //bilanci
        $this->RegisterTask("view-bilanci-dlg","AA_OrganismiTask_ViewBilanciDlg");
        $this->RegisterTask("add-new-bilancio-dlg","AA_OrganismiTask_AddNewBilancioDlg");
        $this->RegisterTask("add-new-bilancio","AA_OrganismiTask_AddNewBilancio");
        $this->RegisterTask("edit-bilancio-dlg","AA_OrganismiTask_EditBilancioDlg");
        $this->RegisterTask("edit-bilancio","AA_OrganismiTask_EditBilancio");
        $this->RegisterTask("trash-bilancio-dlg","AA_OrganismiTask_TrashBilancioDlg");
        $this->RegisterTask("trash-bilancio","AA_OrganismiTask_TrashBilancio");

        //Nomine
        $this->RegisterTask("add-new-nomina-dlg","AA_OrganismiTask_AddNewNominaDlg");
        $this->RegisterTask("add-new-nomina","AA_OrganismiTask_AddNewNomina");
        $this->RegisterTask("edit-nomina-dlg","AA_OrganismiTask_EditNominaDlg");
        $this->RegisterTask("edit-nomina","AA_OrganismiTask_EditNomina");
        $this->RegisterTask("trash-nomina-dlg","AA_OrganismiTask_TrashNominaDlg");
        $this->RegisterTask("trash-nomina","AA_OrganismiTask_TrashNomina");

        //documenti
        $this->RegisterTask("view-docs-nomina-dlg","AA_OrganismiTask_ViewDocsNominaDlg");
        $this->RegisterTask("upload-doc-nomina-dlg","AA_OrganismiTask_UploadDocNominaDlg");
        $this->RegisterTask("upload-doc-nomina","AA_OrganismiTask_UploadDocNomina");
        $this->RegisterTask("trash-doc-nomina-dlg","AA_OrganismiTask_TrashDocNominaDlg");
        $this->RegisterTask("trash-doc-nomina","AA_OrganismiTask_TrashDocNomina");
        
        //Task nuova interfaccia
        $this->RegisterTask("GetLayout","AA_SinesTask_Layout");
        $this->RegisterTask("GetActionMenu","AA_SinesTask_ActionMenu");
        $this->RegisterTask("GetNavbarContent","AA_SinesTask_NavbarContent");
        $this->RegisterTask("GetSectionContent","AA_SinesTask_SectionContent");
    }
}
//------------------------------------------------------

//Task per il dialogo per l'aggiunta di un nuovo organismo
Class AA_OrganismiTask_AddNewOrganismoDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("addnew-organismo-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        #Dialog contents
        $this->sTaskLog.='<p class="validateTips"> </p><div>';
        $this->sTaskLog.="<form class='form-data'>";
        $this->sTaskLog.='<div style="display:flex; flex-direction: column; justify-content: space-between;">';
        
        //Denominazione
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">Nome</div><div style="width: 75%;"><input class="text ui-widget-content ui-corner-all" id="sDescrizione" name="sDescrizione" style="width: 96%;"/></div></div>';

        //tipologia
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">Tipologia</div><div style="width: 75%;"><select class="select ui-widget-content ui-corner-all" id="nTipologia" name="nTipologia" onChange="AA_OrganismiInitializeDlg(this)" style="width: 96%;"/>';
        foreach(AA_Organismi_Const::GetTipoOrganismi() as $key=>$value)
        {
            if($key > 0) $this->sTaskLog.='<option value="'.$key.'">'.$value."</option>";
        }

        $this->sTaskLog.='</select></div></div>';

        #box società
        $this->sTaskLog.='<div id="AA_OrganismiSocBox" style="display:flex; flex-direction: column; justify-content: space-between;">';

        //forma giuridica
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">Forma giuridica</div><div style="width: 75%;"><select class="select ui-widget-content ui-corner-all" id="nFormaSocietaria" name="nFormaSocietaria" style="width: 96%;"/>';
        foreach(AA_Organismi_Const::GetListaFormaGiuridica() as $key=>$value)
        {
            $this->sTaskLog.='<option value="'.$key.'">'.$value."</option>";
        }
        $this->sTaskLog.='</select></div></div>';

        //stato società
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">Stato società</div><div style="width: 75%;"><select class="select ui-widget-content ui-corner-all" id="nStatoOrganismo" name="nStatoOrganismo" style="width: 96%;"/>';
        foreach(AA_Organismi_Const::GetListaStatoOrganismi() as $key=>$value)
        {
            $this->sTaskLog.='<option value="'.$key.'">'.$value."</option>";
        }
        $this->sTaskLog.='</select></div></div>';

        //In house
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">&nbsp;</div><div style="width: 75%;"><input type="checkbox" class="checkbox ui-widget-content ui-corner-all" id="bInHouse" name="bInHouse" value="1" /> <span style="font-size: smaller;">Abilitare se la società è in house</span></div></div>';

        //In TUSP
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">&nbsp;</div><div style="width: 75%;"><input type="checkbox" class="checkbox ui-widget-content ui-corner-all" id="bInTUSP" name="bInTUSP" value="1" /> <span style="font-size: smaller;">Abilitare se la società rientra nell\'allegato A del TUSP</span></div></div>';

        $this->sTaskLog.='</div>';
        #-----------------------

        $this->sTaskLog.="</div></form>";
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per l'aggiunta di un nuovo organismo
Class AA_OrganismiTask_AddNewOrganismo extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("add-new-organismo", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $new_organismo=AA_Organismi::AddNewToDb($_REQUEST,$this->oUser);
        if(!($new_organismo instanceof AA_Organismi))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        else
        {
            $this->sTaskLog="<status id='status' idRec='".Database::LastInsertId()."'>0</status><error id='error'>Organismo inserito con successo.</error>";
            return true;
        }
        return false;
    }
}
//---------------------------------------------------------

//Task per il dialogo per la modifica di un organismo esistente
Class AA_OrganismiTask_UpdateOrganismoDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("modify-organismo-dlg", $user);
    }


    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $organismo=new AA_Organismi($_REQUEST['id'],$this->oUser);

        #Verifica permessi
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>l'utente corrente (".$this->oUser->GetUsername().") non dispone delle autorizzazione necessarie per modificare l'organismo</error>";
            return false;
        }

        #Dialog contents
        $this->sTaskLog.="<form class='form-data'>";
        $this->sTaskLog.='<div style="display:flex; flex-direction: column; justify-content: space-between;">';
        
        //Denominazione
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">Nome</div><div style="width: 75%;"><input class="text ui-widget-content ui-corner-all" id="sDescrizione" name="sDescrizione" style="width: 96%;" value="'.$organismo->GetDenominazione().'"/></div></div>';

        //tipologia
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">Tipologia</div><div style="width: 75%;"><select class="select ui-widget-content ui-corner-all" id="nTipologia" name="nTipologia" onChange="AA_OrganismiInitializeDlg(this)" style="width: 96%;"/>';
        foreach(AA_Organismi_Const::GetTipoOrganismi() as $key=>$value)
        {
            if($key > 0 && $key !=$organismo->GetTipologia(true)) $this->sTaskLog.='<option value="'.$key.'">'.$value."</option>";
            if($key > 0 && $key == $organismo->GetTipologia(true)) $this->sTaskLog.='<option value="'.$key.'" selected>'.$value."</option>";
        }

        $this->sTaskLog.='</select></div></div>';

        #box società
        $this->sTaskLog.='<div id="AA_OrganismiSocBox" style="display:flex; flex-direction: column; justify-content: space-between;">';
        
        //forma giuridica
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">Forma giuridica</div><div style="width: 75%;"><select class="select ui-widget-content ui-corner-all" id="nFormaSocietaria" name="nFormaSocietaria" style="width: 96%;"/>';
        foreach(AA_Organismi_Const::GetListaFormaGiuridica() as $key=>$value)
        {
            if($key !=$organismo->GetFormaSocietaria(true)) $this->sTaskLog.='<option value="'.$key.'">'.$value."</option>";
            if($key == $organismo->GetFormaSocietaria(true)) $this->sTaskLog.='<option value="'.$key.'" selected>'.$value."</option>";
        }
        $this->sTaskLog.='</select></div></div>';
        
        //In house
        $checked="";
        if($organismo->IsInHouse())$checked="checked";
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">&nbsp;</div><div style="width: 75%;"><input type="checkbox" class="checkbox ui-widget-content ui-corner-all" id="bInHouse" name="bInHouse" value="1" '.$checked.'/> <span style="font-size: smaller;">Abilitare se la società è in house</span></div></div>';

        //In TUSP
        $checked="";
        if($organismo->IsInTUSP())$checked="checked";
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">&nbsp;</div><div style="width: 75%;"><input type="checkbox" class="checkbox ui-widget-content ui-corner-all" id="bInTUSP" name="bInTUSP" value="1" '.$checked.'/> <span style="font-size: smaller;">Abilitare se la società rientra nell\'allegato A del TUSP</span></div></div>';

        $this->sTaskLog.='</div>';
        #-----------------------

        //identificativo
        $this->sTaskLog.='<input type="hidden" id="id" name="id" value="'.$organismo->GetID().'"/>';

        $this->sTaskLog.="</div></form>";
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per la modifica di un organismo
Class AA_OrganismiTask_UpdateOrganismo extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("update-organismo", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        if($_REQUEST['id']=="")
        {
            $this->sTaskError="Identificativo organismo non impostato";
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id']."'>-1</status><error id='error'>Identificativo organismo non impostato.</error>";
            return false;
        }

        $organismo=new AA_Organismi($_REQUEST['id'],$this->oUser);

        if(!$organismo->IsValid())
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id']."'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        //Carica i dati
        if(!$organismo->ParseData($_REQUEST))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id']."'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        //Effettua le modifiche
        if(!$organismo->UpdateDb($this->oUser))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id']."'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        else
        {
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id']."'>0</status><error id='error'>Dati oggiornati con successo</error>";
            return true;
        }

        return false;
    }
}
//--------------------------------------------------------

//Task per il dialogo per la modifica di un campo su un organismo esistente
Class AA_OrganismiTask_ModifyFieldOrganismoDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("organismi-modify-field-dlg", $user);
    }


    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $organismo=new AA_Organismi($_REQUEST['id'],$this->oUser);

        #Verifica permessi
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>l'utente corrente (".$this->oUser->GetUsername().") non dispone delle autorizzazione necessarie per modificare l'organismo</error>";
            return false;
        }

        #Dialog contents
        $field_name=$_REQUEST['field'];
        $field_notes=$_REQUEST['field_notes'];
        if($field_name=="sPartecipazione")
        {
            $field_notes=htmlentities("Indicare solo valori numerici nel formato: <valore>/<quota>");
            $field_notes.="<ul><li>".htmlentities("<valore>")." è il dato numerico in euro delle quote possedute.</li>";
            $field_notes.="<li>".htmlentities("<quota>")." è il dato numerico in percentuale delle quote possedute.</li></ul>";
            $field_notes.="ad esempio: 1.000.000/15,25 (1 milione di euro pari al 15,25 percento delle quote totali)";
            $field_notes.="<p>Lasciare il campo vuoto se la partecipazione è indiretta.</p>";
        }

        if($field_name=="nStatoOrganismo")
        {
            $_REQUEST['field-type']="select";
            foreach(AA_Organismi_const::GetListaStatoOrganismi() as $key=>$value)
            {
                if($key>0) $select_list[$key]=$value;
            }
            $value=$organismo->GetStatoOrganismo(true);
        }

        if($_REQUEST['field-name'] !="") $field_name=$_REQUEST['field-name'];

        $this->sTaskLog.='<p class="validateTips"> </p>';
        $template=new AA_GenericFormTemplateView("AA_OrganismiEditFieldTemplateView_".$organismo->GetId(),null,$organismo);
        
        switch($_REQUEST['field-type'])
        {
            default:
            case "text":
                $template->AddField($field_name,$_REQUEST['field'],"text",$organismo->GetProp($_REQUEST['field']),null,$field_notes," width: 99%");
                break;
            case "text-area":
            case "textarea":
                $template->AddField($field_name,$_REQUEST['field'],"textarea",$organismo->GetProp($_REQUEST['field']),null,$field_notes," width: 99%");
                break;
            case "date":
                $template->AddField($field_name,$_REQUEST['field'],"text",$organismo->GetProp($_REQUEST['field']),null,$field_notes," width: 25%","AA_DatePicker");
                break;
            case "select":
                $template->AddSelectInput($field_name,$_REQUEST['field'],$value,$select_list);
                break;
        }

        $template->AddHiddenField("id",$organismo->GetId());

        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per il dialogo di pubblicazione di un organismo
Class AA_OrganismiTask_PublishOrganismoDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("publish-organismo-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $organismo=new AA_Organismi($_REQUEST['id'],$this->oUser);

        #Verifica permessi
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_PUBLISH) == 0)
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>l'utente corrente (".$this->oUser->GetUsername().") non dispone delle autorizzazione necessarie per pubblicare l'organismo</error>";
            return false;
        }

        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        #Dialog contents
        $this->sTaskLog.='<p class="validateTips"> </p><div>';
        $this->sTaskLog.="<form class='form-data'>";
        $this->sTaskLog.='<div style="display:flex; flex-direction: column; justify-content: space-between; align-items: center;">';

        //verifica se l'organismo è già pubblicato
        if(($organismo->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) > 0)
        {
            $this->sTaskError="Organismo già pubblicato.";
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>Organismo già pubblicato.</error>";
            return false;
        }

        //pubblica
        $this->sTaskLog.='<p style="text-align: center">Vuoi procedere alla pubblicazione dell\'organismo: <br/><br/><span style="font-weight: bold;">'.$organismo->GetDenominazione().'</span></p>';

        //identificativo
        $this->sTaskLog.='<input type="hidden" id="id" name="id" value="'.$organismo->GetID().'"/>';

        $this->sTaskLog.="</div></form>";
        #-------------------------

        $this->sTaskLog.="</content>";
        return true;
    }
}
#------------------------------------------------------------

//Task per la pubblicare la scheda di un titolare
Class AA_OrganismiTask_PublishOrganismo extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("publish-organismo", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $organismo=new AA_Organismi($_REQUEST['id']);
        if(!$organismo->IsValid())
        {
            $this->sTaskError="Organismo non trovato, identificativo non valido (id: ".$_REQUEST['id'].")";
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id']."'>-1</status><error id='error'>Organismo non trovato, identificativo non valido (id: ".$_REQUEST['id'].")</error>";
            return false;
        }

        if(!$organismo->Publish($this->oUser))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id']."'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        else
        {
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id']."'>0</status><error id='error'>Scheda organismo pubblicata con successo.</error>";
            return true;
        }

        return false;
    }
}
//------------------------------------------------

//Task per eliminare la scheda di un titolare
Class AA_OrganismiTask_TrashOrganismo extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("delete-organismo", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $organismo=new AA_Organismi($_REQUEST['id']);
        if(!$organismo->Trash($this->oUser,true))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id']."'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        else
        {
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id']."'>0</status><error id='error'>Scheda organismo cestinata/eliminata con successo.</error>";
            return true;
        }

        return false;
    }
}
#------------------------------------------------------------

//Task per il dialogo di elimina di un organismo
Class AA_OrganismiTask_TrashOrganismoDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("delete-organismo-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $organismo=new AA_Organismi($_REQUEST['id'],$this->oUser);

        #Verifica permessi
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) == 0)
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>l'utente corrente (".$this->oUser->GetUsername().") non dispone delle autorizzazione necessarie per cestinare o eliminare l'organismo</error>";
            return false;
        }

        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        #Dialog contents
        $this->sTaskLog.='<p class="validateTips"> </p><div>';
        $this->sTaskLog.="<form class='form-data'>";
        $this->sTaskLog.='<div style="display:flex; flex-direction: column; justify-content: space-between; align-items: center;">';

        //cestina
        if(($organismo->GetStatus() & AA_Const::AA_STATUS_CESTINATA)==0)
        {
            $this->sTaskLog.='<p style="text-align: center">Questa operazione sposterà sul cestino l\'organismo: <br/><br/><span style="font-weight: bold;">'.$organismo->GetDenominazione().'</span></p><p style="text-align: center">Questa operazione può essere annullata.</p>';
        }
        else
        {
            $this->sTaskLog.='<p style="text-align: center">Elimino l\'organismo: <br/><br/><span style="font-weight: bold;">'.$organismo->GetDenominazione().'</span></p><p style="text-align: center"><span style="font-weight: bold">ATTENZIONE!</span><br/>Questa operazione non può essere annullata</p>';
        }

        //identificativo
        $this->sTaskLog.='<input type="hidden" id="id" name="id" value="'.$organismo->GetID().'"/>';

        $this->sTaskLog.="</div></form>";
        #-------------------------

        $this->sTaskLog.="</content>";
        return true;
    }
}
#------------------------------------------------------------

//Task per ripristinare la scheda di un organismo
Class AA_OrganismiTask_ResumeOrganismo extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("resume", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $organismo=new AA_Organismi($_REQUEST['id'],$this->oUser);

        if(!$organismo->Resume($this->oUser))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id']."'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        else
        {
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id']."'>0</status><error id='error'>Scheda organismi ripristinata con successo.</error>";
            return true;
        }

        return false;
    }
}
#------------------------------------------------------------

//Task per il dialogo di ripristino di un organismo
Class AA_OrganismiTask_ResumeOrganismiDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("resume-organismi-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $organismo=new AA_Organismi($_REQUEST['id'],$this->oUser);

        #Verifica permessi
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) == 0)
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>l'utente corrente (".$this->oUser->GetUsername().") non dispone delle autorizzazione necessarie per cestinare o eliminare l'organismo</error>";
            return false;
        }

        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        #Dialog contents
        $this->sTaskLog.='<p class="validateTips"> </p><div>';
        $this->sTaskLog.="<form class='form-data'>";
        $this->sTaskLog.='<div style="display:flex; flex-direction: column; justify-content: space-between; align-items: center;">';
        
        $this->sTaskLog.='<p style="text-align: center">Questa operazione ripristinerà, in stato di bozza, l\'organismo: <br/><br/><span style="font-weight: bold;">'.$organismo->GetDenominazione().'</span></p>';
        
        //identificativo
        $this->sTaskLog.='<input type="hidden" id="id" name="id" value="'.$organismo->GetID().'"/>';

        $this->sTaskLog.="</div></form>";
        
        #-------------------------

        $this->sTaskLog.="</content>";
        return true;
    }
}
#------------------------------------------------------------

//Task per riassegnare la scheda di un titolare
Class AA_OrganismiTask_ReassignOrganismo extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("reassign", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $organismo=new AA_Organismi($_REQUEST['riassegna-id-object'],$this->oUser);
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>l'utente corrente (".$this->oUser->GetUsername().") non dispone delle autorizzazione necessarie per riassegnare l'organismo</error>";
            return false;
        }

        if(!$organismo->Reassign($_REQUEST, $this->oUser))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['reassign-id-object']."'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        else
        {
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['reassign-id-object']."'>0</status><error id='error'>Organismo riassegnato con successo.</error>";
            return true;
        }

        return false;
    }
}
#------------------------------------------------------------

//Task per cercare organismi
Class AA_OrganismiTask_SearchOrganismi extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("search-organismi", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        //$userStruct=$this->oUser->GetStruct();

        //Parametri di ricerca in base allo stato
        $params['status']=AA_Const::AA_STATUS_PUBBLICATA;

        if(isset($_REQUEST['stato_scheda_search'])) $params['status'] = $_REQUEST['stato_scheda_search'];
        else $params['status']=AA_Const::AA_STATUS_PUBBLICATA;
        //if(isset($_REQUEST['stato_revisionata_search'])) $params['status']=AA_Const::AA_STATUS_PUBBLICATA+AA_Const::AA_STATUS_REVISIONATA;
        if(isset($_REQUEST['stato_cestinata_search'])) $params['status']+=AA_Const::AA_STATUS_CESTINATA;

        //Pagina corrente
        if(isset($_REQUEST['curPage']) && $_REQUEST['curPage'] > 1) $params['from'] = ($_REQUEST['curPage']-1)*10;
        else $params['from']=0;

        //numero di voci da visualizzare per pagina
        if(isset($_REQUEST['count'])) $params['count']=$_REQUEST['count'];
        else $params['count']=10;

        if(isset($_REQUEST['goPage']) && $_REQUEST['goPage'] >= 1)
        {
            $params['from'] = ($_REQUEST['goPage'] - 1) * 10;
            $page=$_REQUEST['goPage']-1;
        }

        //Filtra per denominazione
        if(isset($_REQUEST['denominazione_search']) && $_REQUEST['denominazione_search'] !="") $params['denominazione']=$_REQUEST['denominazione_search'];

        //Filtra per tipo di incarico
        if(isset($_REQUEST['tipo_search'])) $params['tipo']=$_REQUEST['tipo_search'];
        
        //Filtra per struttura
        if(isset($_REQUEST['id_assessorato_search'])) $params['id_assessorato']=$_REQUEST['id_assessorato_search'];
        if(isset($_REQUEST['id_direzione_search'])) $params['id_direzione']=$_REQUEST['id_direzione_search'];
        //if(isset($_REQUEST['id_servizio_search'])) $params['idServizioIncarico']=$_REQUEST['id_servizio_search'];

        $organismi=AA_Organismi::Search($params,false,$this->oUser);

        if($organismi[0]==-1)
        {
            AA_Log::Log("organismi_ops - task: ".$this->sTaskName." - ".AA_Log::$lastErrorLog,100,false,true); 
            $this->sTaskError=AA_Log::$lastErrorLog;     
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        else
        {
            $count=$organismi[0];
            $total_pages=round((($count/10)+.4),0,PHP_ROUND_HALF_UP);

            //Restituisce un flusso xml
            if(isset($_REQUEST['xml']))
            {
                //header("Cache-control: private");
                //header("Content-type: application/xml");
                //header("Content-Disposition: filename=\"titolari_di_incarico-".date("d-m-Y").".xml\"");

                $this->sTaskLog='<?xml version="1.0" encoding="UTF-8"?>'."\n";
                $this->sTaskLog.='<organismi count="'.$count.'">';
                foreach($organismi[1] as $id=>$curOrganismo)
                {
                    $this->sTaskLog.=$curOrganismo;
                }
                $this->sTaskLog.="</organismi>";
                return true;
            }

            if(isset($_REQUEST['curPage']) && $_REQUEST['curPage'] <= $total_pages && is_numeric($_REQUEST['curPage']) && $_REQUEST['curPage'] >= 1) $page=$_REQUEST['curPage']-1;
            else $page=0;

            if(isset($_REQUEST['goPage']) && $_REQUEST['goPage'] >= 1)
            {
            if($_REQUEST['goPage'] > $total_pages) $page=$total_pages - 1;
            else $page=$_REQUEST['goPage']-1;
            }

            $navigator="<div id='navigator'><table style='width:80%; padding-top:.4em;'><tr>";
            if($page > 0) $navigator.="<td style='width:25%'><button id='first'>Prima pagina</button></td><td style='width:25%'><button id='prev'>Pagina precedente</button></td>";
            else $navigator.="<td style='width:25%'>&nbsp;</td><td style='width:25%'>&nbsp;</td>";
            if($page < $total_pages-1) $navigator.="<td style='width:25%'></span><button id='next'>Prossima pagina</button></td><td style='width:25%'><button id='last'>Ultima pagina</button></td>";
            else $navigator.="<td style='width:25%'>&nbsp;</td><td style='width:25%'>&nbsp;</td>";
            $navigator.="</tr></table></div>";

            $this->sTaskLog="<status id='status'>0</status><params id='params'></params>";
            
            //Navigator
            $this->sTaskLog.="<content id='navigator' curPage='".$page."' totalPages='".$total_pages."'>".$navigator."</content>";
            
            //Content
            $this->sTaskLog.="<content id='content' count='".$count."'>";

            $accordion_list= new AA_OrganismiAccordionTemplateView();

            //$index=0;
            foreach($organismi[1] as $id=>$curOrganismo)
            {
                $accordion_list->AddItem(new AA_OrganismiAccordionTemplateItemView($curOrganismo,$this->oUser));
            }

            $this->sTaskLog.=$accordion_list->ContentView(false);

            $this->sTaskLog.="</content>";

            return true;
        }
        return false;
    }
}
#------------------------------------------------------------

//Task per le opzioni di ricerca di un titolare
Class AA_OrganismiTask_SearchOrganismoDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("search-organismo-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";
        #Dialog contents
        
        //Organismo
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">Denominazione/piva</div><div style="width: 75%;"><input class="text ui-widget-content ui-corner-all" id="denominazione_search" name="denominazione_search" style="width: 96%;"/></div></div>';
        //----------

        //Tipo organismo
        $tipo=AA_Organismi_Const::GetTipoOrganismi();
        $this->sTaskLog.='<div style="display: flex; justify-content: space-between; margin-bottom: 1em"><div style="width: 25%;">Tipologia</div><div style="width: 75%;"><select class="select ui-widget-content ui-corner-all" id="tipo_search" name="tipo_search" style="width: 96%;"/>';
        foreach($tipo as $key=>$value)
        {
            if($key==0) $this->sTaskLog.='<option value="0">Qualunque</option>';
            else $this->sTaskLog.='<option value="'.$key.'">'.$value.'</option>';
        }
        $this->sTaskLog.='</select></div></div>';

        $this->sTaskLog.="</content>";
        return true;
    }
}
#------------------------------------------------------------

//Task per il dialogo per l'aggiunta di un nuovo bilancio
Class AA_OrganismiTask_AddNewDatiContabiliDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("add-new-dati-contabili-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $organismo=new AA_Organismi($_REQUEST['id'],$this->oUser);

        #Dialog contents
        $this->sTaskLog.='<p class="validateTips"> </p>';
        $template=new AA_OrganismiDatiContabiliAddNewTemplateView("AA_OrganismiDatiContabiliAddNewTemplateView_".$organismo->GetId(),null,$organismo);
        
        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per il dialogo per la modifica di un bilancio esistente
Class AA_OrganismiTask_EditDatiContabiliDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("edit-dati-contabili-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $bilancio=new AA_OrganismiDatiContabili($_REQUEST['id'],null,$this->oUser);

        if(!($bilancio->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>Bilancio non valido o non trovato - ".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        #Dialog contents
        $this->sTaskLog.='<p class="validateTips"> </p>';
        $template=new AA_OrganismiDatiContabiliEditTemplateView("AA_OrganismiDatiContabiliEditTemplateView_".$_REQUEST['id'],null,$bilancio);
        
        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per il dialogo per l'eliminazione di un bilancio esistente
Class AA_OrganismiTask_TrashDatiContabiliDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("trash-bilancio-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $bilancio=new AA_OrganismiDatiContabili($_REQUEST["id"],null,$this->oUser);

        if(!($bilancio->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        if(($bilancio->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) == 0)
        {
            $this->sTaskError="L'utente: ".$this->oUser->GetNome()." non ha i permessi per cestinare/eliminare il bilancio.";
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".$this->sTaskError."</error>";
        }

        #Dialog contents
        $template=new AA_OrganismiDatiContabiliTrashTemplateView("",null,$bilancio);
        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per l'aggiunta di un nuovo bilancio
Class AA_OrganismiTask_AddNewDatiContabili extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("add-new-dati-contabili", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $parent=AA_Organismi::Load($_REQUEST["nIdParent"],$this->oUser);

        if(!($parent->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $new_bilancio=AA_OrganismiDatiContabili::AddNewToDb($_REQUEST,$parent,$this->oUser);
        if(!($new_bilancio instanceof AA_OrganismiDatiContabili))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        else
        {
            $this->sTaskLog="<status id='status' idRec='".Database::LastInsertId()."'>0</status><error id='error'>Bilancio inserito con successo.</error>";
            return true;
        }

        return false;
    }
}
//---------------------------------------------------------

//Task per la modifica di un bilancio
Class AA_OrganismiTask_EditDatiContabili extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("edit-dati-contabili", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $bilancio=new AA_OrganismiDatiContabili($_REQUEST["id"],null,$this->oUser);

        if(!($bilancio->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>Dati contabili non trovati o non valido - ".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $organismo=$bilancio->GetParent($this->oUser);
        if(!($organismo instanceof AA_Organismi) || !$organismo->IsValid())
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        
        if(!$bilancio->ParseData($_REQUEST,$this->oUser))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        if(!$bilancio->UpdateDb(null,$this->oUser))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $this->sTaskLog="<status id='status' idRec='".$bilancio->GetID()."'>0</status><error id='error'>Dati contabili aggiornati con successo.</error>";
        return true;
    }
}
//---------------------------------------------------------

//Task per l'eliminazione di un bilancio
Class AA_OrganismiTask_TrashDatiContabili extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("trash-dati-contabili", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $bilancio=new AA_OrganismiDatiContabili($_REQUEST["id"],null,$this->oUser);

        if(!($bilancio->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        if(!$bilancio->Trash($this->oUser,true))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $this->sTaskLog="<status id='status' idRec='".$bilancio->GetID()."'>0</status><error id='error'>Dati contabili eliminati con successo.</error>";
        return true;
    }
}
//---------------------------------------------------------

//Task per la visualizzazione della lista dei bilanci di un dato contabile
Class AA_OrganismiTask_ViewBilanciDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("view-bilanci-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $daticontabili=new AA_OrganismiDatiContabili($_REQUEST["id"],null,$this->oUser);

        if(!($daticontabili->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        #Dialog contents
        $template=new AA_OrganismiBilanciListTemplateView("AA_OrganismiBilanciListTemplateView_".$daticontabili->GetId(),null,$daticontabili);
        
        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per il dialogo per l'aggiunta di un nuovo bilancio
Class AA_OrganismiTask_AddNewBilancioDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("add-new-bilancio-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $daticontabili=new AA_OrganismiDatiContabili($_REQUEST['id'],$this->oUser);
        if(!$daticontabili->IsValid())
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        #Dialog contents
        $this->sTaskLog.='<p class="validateTips"> </p>';
        $template=new AA_OrganismiBilanciAddNewTemplateView("AA_OrganismiBilanciAddNewTemplateView_".$daticontabili->GetId(),null,$daticontabili);
        
        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per l'aggiunta di un nuovo bilancio
Class AA_OrganismiTask_AddNewBilancio extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("add-new-bilancio", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $parent=new AA_OrganismiDatiContabili($_REQUEST["id_dati_contabili"],null,$this->oUser);

        if(!($parent->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $bilancio=new AA_OrganismiBilanci();
        $bilancio->SetIdDatiContabili($parent->GetId());
        $bilancio->SetTipo($_REQUEST['tipo']);
        $bilancio->SetRisultati($_REQUEST['risultati']);
        $bilancio->SetNote($_REQUEST['note']);

        if(!$parent->AddNewBilancio($bilancio))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        else
        {
            $this->sTaskLog="<status id='status' idRec='".Database::LastInsertId()."'>0</status><error id='error'>Bilancio aggiunto con successo.</error>";
            return true;
        }

        return false;
    }
}
//---------------------------------------------------------

//Task per la modifica di un bilancio esistente
Class AA_OrganismiTask_EditBilancio extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("edit-bilancio", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $parent=new AA_OrganismiDatiContabili($_REQUEST["id_dati_contabili"],null,$this->oUser);

        if(!($parent->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        if($_REQUEST['id_bilancio']=="")
        {
            $this->sTaskError="identificativo bilancio non impostato";
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo bilancio non impostato.</error>";
            return false;
        }

        $bilancio=new AA_OrganismiBilanci();
        $bilancio->SetIdDatiContabili($parent->GetId());
        $bilancio->SetTipo($_REQUEST['tipo']);
        $bilancio->SetRisultati($_REQUEST['risultati']);
        $bilancio->SetNote($_REQUEST['note']);
        $bilancio->SetId($_REQUEST['id_bilancio']);

        if(!$parent->UpdateBilancio($bilancio,$this->oUser))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        else
        {
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id_bilancio']."'>0</status><error id='error'>Bilancio aggiornato con successo.</error>";
            return true;
        }

        return false;
    }
}
//---------------------------------------------------------

//Task per il dialogo per la modifica di un bilancio esistente
Class AA_OrganismiTask_EditBilancioDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("edit-bilancio-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $daticontabili=new AA_OrganismiDatiContabili($_REQUEST['id-dati-contabili'],null,$this->oUser);

        if(!($daticontabili->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>Dati contabili non validi o non trovati - ".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $bilancio=$daticontabili->GetBilancio($_REQUEST['id']);
        if($bilancio instanceof AA_OrganismiBilanci)
        {
            #Dialog contents
            $this->sTaskLog.='<p class="validateTips"> </p>';
            $template=new AA_OrganismiBilanciEditTemplateView("AA_OrganismiBilanciEditTemplateView_".$_REQUEST['id'],null,$bilancio);
            
            $this->sTaskLog.=$template;
            #-------------------------
            $this->sTaskLog.="</content>";

            return true;
        }
        else
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>Bilancio non valido o non trovato - ".AA_Log::$lastErrorLog."</error>";
            return false;
        }
    }
}
//---------------------------------------------------------

//form di aggiunta bilancio
Class AA_OrganismiBilanciAddNewTemplateView extends AA_GenericFormTemplateView
{
    public function __construct($id="AA_OrganismiBilanciAddNewTemplateView",$parent=null,$obj=null)
    {
        parent::__construct($id,$parent,$obj);

        if($obj instanceof AA_OrganismiDatiContabili)
        {
            //Imposta le dimensioni dei box
            $this->SetLabelFieldBoxSize("30%");
            $this->SetFieldBoxSize("70%");

            $this->AddField("Tipo di bilancio","tipo", "select", "0", AA_Organismi_Const::GetTipoBilanci(),"Indicare sulle note l'eventuale motivazione dell'indisponibilità del dato.");
            $this->AddField("Risultati di bilancio in €","risultati","text","",null,"Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            $this->AddField("Note","note","textarea","",null,""," width: 99%;");

            $this->AddHiddenField("id_dati_contabili",$obj->GetId());
        }
    }
}

//form di modifica bilancio
Class AA_OrganismiBilanciEditTemplateView extends AA_GenericFormTemplateView
{
    public function __construct($id="AA_OrganismiBilanciEditTemplateView",$parent=null,$obj=null)
    {
        parent::__construct($id,$parent,$obj);

        if($obj instanceof AA_OrganismiBilanci)
        {
            //Imposta le dimensioni dei box
            $this->SetLabelFieldBoxSize("30%");
            $this->SetFieldBoxSize("70%");

            $this->AddField("Tipo di bilancio","tipo", "select", $obj->GetTipo(true), AA_Organismi_Const::GetTipoBilanci(),"Indicare sulle note l'eventuale motivazione dell'indisponibilità del dato.");
            $this->AddField("Risultati di bilancio in €","risultati","text",$obj->GetRisultati(),null,"Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            $this->AddField("Note","note","textarea",$obj->GetNote(),null,""," width: 99%;");

            $this->AddHiddenField("id_dati_contabili",$obj->GetIdDatiContabili());
            $this->AddHiddenField("id_bilancio",$obj->GetId());
        }
    }
}

//Task per il dialogo per l'eliminazione di un bilancio esistente
Class AA_OrganismiTask_TrashBilancioDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("trash-bilancio-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $daticontabili=new AA_OrganismiDatiContabili($_REQUEST["id-dati-contabili"],null,$this->oUser);

        if(!($daticontabili->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $bilancio=$daticontabili->GetBilancio($_REQUEST["id"]);
        if($bilancio==null)
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;    
        }

        #Dialog contents
        $template=new AA_OrganismiBilanciTrashTemplateView("",null,$bilancio);
        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per la modifica di un bilancio esistente
Class AA_OrganismiTask_TrashBilancio extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("trash-bilancio", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $parent=new AA_OrganismiDatiContabili($_REQUEST["id_dati_contabili"],null,$this->oUser);

        if(!($parent->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        if($_REQUEST['id_bilancio']=="")
        {
            $this->sTaskError="identificativo bilancio non impostato";
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo bilancio non impostato.</error>";
            return false;
        }

        if(!$parent->DelBilancio($_REQUEST['id_bilancio'],$this->oUser))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        else
        {
            $this->sTaskLog="<status id='status' idRec='".$_REQUEST['id_bilancio']."'>0</status><error id='error'>Bilancio eliminato con successo.</error>";
            return true;
        }

        return false;
    }
}
//---------------------------------------------------------

//form di eliminazione del bilancio
Class AA_OrganismiBilanciTrashTemplateView extends AA_GenericFormTemplateView
{
    public function __construct($id="AA_OrganismiBilanciTrashTemplateView",$parent=null,$obj=null)
    {
        parent::__construct($id,$parent,$obj);

        if($obj instanceof AA_OrganismiBilanci)
        {
            $this->SetText("<p style='text-align: center'>Vuoi eliminare il bilancio: ".$obj->GetTipo()." (".$obj->GetRisultati().")?</p><p style='text-align: center'>Questa operazione non può essere annullata</p>");
            $this->AddHiddenField("id_dati_contabili",$obj->GetIdDatiContabili());
            $this->AddHiddenField("id_bilancio",$obj->GetId());
        }
    }
}
//----------------------------------------------------------

#Classe template per la gestione dalla lista dei dati contabili sull'item organismo
Class AA_OrganismiDatiContabiliListTemplateView extends AA_GenericTableTemplateView
{
    public function __construct($id="AA_OrganismiDatiContabiliListTemplateView",$parent=null,$organismo=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo);
        
        $canModify=false;
        if(($organismo->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;

        //Aggiunge il pulsante di aggiunta bilancio
        if($canModify)
        {
            //Contenitore pulsante
            $div=new AA_XML_Div_Element("",null);
            $div->SetStyle("width:99%; margin-bottom: 1em;");
            
            //Pulsante di aggiunta
            $a = new AA_XML_A_Element("", $div);
            $a->SetClass("AA_Button_AddNew_DatiContabili");
            $a->SetStyle("cursor: pointer;");
            $a->SetAttribs(array("id-object"=>$organismo->GetId(),"task"=>"add-new-dati-contabili-dlg","title"=>"Aggiungi dati contabili e dotazione organica"));
            $a->SetText("Aggiungi dati contabili e dotazione organica");

            $this->InsertChild($div);
        } 

        $daticontabili=$organismo->GetDatiContabili();

        if(sizeof($daticontabili)>0)
        {
            if($canModify)
            {
                $this->SetColSizes(array("5","13","13","13","13","30","10"));
                if($organismo->GetTipologia(true) == AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) $this->SetHeaderLabels(array("Anno","Oneri totali<sup>1</sup>","Risultati di bilancio<sup>2</sup>", "Fatturato", "Dotazione organica<sup>3</sup>", "Note", "Operazioni"));
                else $this->SetHeaderLabels(array("Anno","Oneri totali<sup>1</sup>","Risultati di amministrazione<sup>2</sup>", "Spesa dotazione organica", "Dotazione organica<sup>3</sup>", "Note", "Operazioni"));
            }
            else
            {
                $this->SetColSizes(array("5","15","15","15","15","30"));
                if($organismo->GetTipologia(true) == AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) $this->SetHeaderLabels(array("Anno","Oneri totali<sup>1</sup>","Risultati di bilancio<sup>2</sup>", "Fatturato", "Dotazione organica<sup>3</sup>", "Note"));
                else $this->SetHeaderLabels(array("Anno","Oneri totali<sup>1</sup>","Risultati di amministrazione<sup>2</sup>", "Spesa dotazione organica", "Dotazione organica<sup>3</sup>", "Note"));
            }

            $curRow=1;
            foreach($daticontabili as $id=>$curDatoContabile)
            {
                //Anno
                $this->SetCellText($curRow,0,$curDatoContabile->GetAnno(), "center");

                //oneri
                $curVal="€ ".preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "", $curDatoContabile->GetOneriTotali());
                if($curVal=="€ ") $curVal="n.d.";
                $this->SetCellText($curRow,1,$curVal, "center");

                //Risultati di bilancio
                $curVal="€ ".preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "", $curDatoContabile->GetRisultatiBilancio());
                if($curVal=="€ ") $curVal="n.d";
                if(strpos($curVal,"-")!==false)
                {
                    $color="red";
                }
                else $color="";
                $this->SetCellText($curRow,2,$curVal,"center",$color);
                #-----------------------

                if($organismo->GetTipologia(true) == AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
                {
                    //Fatturato
                    $curVal="€ ".preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "", $curDatoContabile->GetFatturato());
                    if($curVal=="€ ") $curVal="n.d.";
                    if(strpos($curVal,"-")!==false)
                    {
                        $color="red";
                    }
                    else $color="";
                    $this->SetCellText($curRow,3,$curVal,"center",$color);
                }
                else
                {
                    //Spese dotazione organica
                    $curVal="€ ".preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "", $curDatoContabile->GetSpesaDotazioneOrganica());
                    if($curVal=="€ ") $curVal="n.d.";
                    if(strpos($curVal,"-")!==false)
                    {
                        $color="red";
                    }
                    else $color="";
                    $this->SetCellText($curRow,3,$curVal,"center",$color);
                }

                #Dotazione organica------
                $curVal=preg_replace("/[\)|\(|€|\ |A-Za-z_\,]/", "", $curDatoContabile->GetDotazioneOrganica());
                if($curVal=="") $curVal="n.d.";
                $dip=preg_replace("/[\)|\(|€|\ |A-Za-z_\,]/", "", $curDatoContabile->GetDipendenti());
                if($dip=="") $dip="n.d.";
                $this->SetCellText($curRow,4,$curVal." (".$dip.")","center");

                $box=$this->GetCell($curRow,4);
                $box->SetStyle("text-align: center", true);
                
                $vedi=new AA_XML_A_Element("",$box); $vedi->SetClass("AA_Button_Bilanci_View ui-icon ui-icon-search"); $vedi->SetAttribs(array("id-object"=>$organismo->GetId(),"id-dati-contabili"=>$curDatoContabile->GetId(),"title"=>"Visualizza i dettagli"));
                $vedi->SetStyle("display: inline-block; margin-right: 1em; cursor: pointer");
                $vedi->SetText("Visualizza i dettagli");
                #-----------------------

                //Note
                $note=$this->GetCell($curRow,5);
                $note->SetStyle("font-size: smaller;", true);
                $text_align="center";
                if(strlen($curDatoContabile->GetNote()) > 75) $text_align="left";
                $this->SetCellText($curRow,5,$curDatoContabile->GetNote(), $text_align);

                //Operazioni
                if($canModify)
                {
                    //$operazioni=new AA_XML_Div_Element("",$curRiga);$operazioni->SetStyle("width: 10%; text-align: center");
                    $operazioni=$this->GetCell($curRow,6);
                    $operazioni->SetStyle("text-align: center;", true);
                    
                    //gestisci i bilanci
                    $vedi=new AA_XML_A_Element("",$operazioni); $vedi->SetClass("AA_Button_DatiContabili_View_Bilanci ui-icon ui-icon-calculator"); $vedi->SetAttribs(array("id-object"=>$organismo->GetId(),"id-dati-contabili"=>$curDatoContabile->GetId(),"title"=>"Gestisci i bilanci"));
                    $vedi->SetStyle("display: inline-block; margin-right: 1em; cursor: pointer");
                    $vedi->SetText("Gestisci i bilanci");
                    //---------------------------

                    //Modifica
                    $edit=new AA_XML_A_Element("",$operazioni); $edit->SetClass("AA_Button_Edit_DatiContabili ui-icon ui-icon-pencil"); $edit->SetAttribs(array("id-object"=>$organismo->GetId(),"id-dati-contabili"=>$curDatoContabile->GetId(),"title"=>"Modifica dati contabili"));
                    $edit->SetStyle("display: inline-block; margin-right: 1em;cursor: pointer");
                    $edit->SetText("Modifica dati contabili");

                    //elimina
                    $trash=new AA_XML_A_Element("",$operazioni); $trash->SetClass("AA_Button_Trash_DatiContabili ui-icon ui-icon-trash"); $trash->SetAttribs(array("id-object"=>$organismo->GetId(),"id-dati-contabili"=>$curDatoContabile->GetId(),"title"=>"Elimina dati contabili"));
                    $trash->SetStyle("display: inline-block;cursor: pointer");
                    $trash->SetText("elimina dati contabili");
                }

                $curRow++;
            }
            $footer="<div style='font-style: italic; text-align: left; width: 100%; margin-top: 1em;font-size: smaller;'>1. Somme effettivamente liquidate.</div>";
            $footer.="<div style='font-style: italic; text-align: left; width: 100%; margin-top: .5em;font-size: smaller;'>2. Risultati del bilancio di esercizio dell'anno di riferimento.</div>";
            $footer.="<div style='font-style: italic; text-align: left; width: 100%; margin-top: .5em;font-size: smaller;'>3. Il valore all'esterno delle parentesi indica il numero totale di dipendenti (dirigenti e non), quello all'interno indica il numero di dipendenti (dirigenti e non) assunti a tempo indeterminato. Durante il primo semestre dell'anno in corso (".date("Y")."), per i valori relativi allo stesso anno (".date("Y")."), è possibile indicare quelli relativi al 31 dicembre dell'anno precedente (".(date("Y")-1)."), successivamente devono riportarsi esclusivamente quelli relativi all'anno in corso (".date("Y")."). Per l'annualità ".(date("Y")-1)." e precedenti devono riportarsi esclusivamente i dati relativi al 31 dicembre dell'anno di riferimento.</div>";

            $this->SetText($footer,false);
        }
    }
}

#Classe template per la gestione dalla lista dei bilanci sull'item dati contabili
Class AA_OrganismiBilanciListTemplateView extends AA_GenericTableTemplateView
{
    public function __construct($id="AA_OrganismiBilanciListTemplateView",$parent=null,$daticontabili=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($daticontabili instanceof AA_OrganismiDatiContabili))
        {
            AA_Log::Log(__METHOD__." - dati contabili non validi.", 100,false,true);
            return;
        }

        $organismo=$daticontabili->GetParent($user);

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$daticontabili);
        
        $canModify=false;
        if(($daticontabili->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;

        //Aggiunge il pulsante di aggiunta bilancio
        if($canModify)
        {
            //Contenitore pulsante
            $div=new AA_XML_Div_Element("",null);
            $div->SetStyle("width:99%; margin-bottom: 1em;");
            
            //Pulsante di aggiunta
            $a = new AA_XML_A_Element("", $div);
            $a->SetClass("AA_Button_AddNew_Bilancio");
            $a->SetStyle("cursor: pointer;");
            $a->SetAttribs(array("id-object"=>$organismo->GetId(),"id-dati-contabili"=>$daticontabili->GetId(),"task"=>"add-new-bilancio-dlg","title"=>"Aggiungi bilancio"));
            $a->SetText("Aggiungi bilancio");

            $this->InsertChild($div);
        } 

        $bilanci=$daticontabili->GetBilanci();
        if(sizeof($bilanci)>0)
        {
            if($canModify)
            {
                $this->SetColSizes(array("25","25","35","15"));
                $this->SetHeaderLabels(array("Tipo bilancio","Risultati","Note", "Operazioni"));
            }
            else
            {
                $this->SetColSizes(array("25","25","40"));
                $this->SetHeaderLabels(array("Tipo bilancio","Risultati","Note"));
            }

            $curRow=1;
            foreach($bilanci as $id=>$curBilancio)
            {
                //tipo bilancio
                $this->SetCellText($curRow,0,$curBilancio->GetTipo(),"center");

                //Risultati
                $curVal="€ ".preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "", $curBilancio->GetRisultati());
                if($curVal=="€ " || $curBilancio->GetTipo(true) == 0) $curVal="n.d.";
                if(strpos($curVal,"-")!==false)
                {
                    $color="red";
                }
                else $color="";
                $this->SetCellText($curRow,1,$curVal,"center",$color);

                //Note
                $this->SetCellText($curRow,2,$curBilancio->GetNote(), "center");

                //Operazioni
                if($canModify)
                {
                    //$operazioni=new AA_XML_Div_Element("",$curRiga);$operazioni->SetStyle("width: 10%; text-align: center");
                    $operazioni=$this->GetCell($curRow,3);
                    $operazioni->SetStyle("text-align: center", true);
                    
                    //Modifica
                    $edit=new AA_XML_A_Element("",$operazioni); $edit->SetClass("AA_Button_Edit_Bilancio ui-icon ui-icon-pencil"); $edit->SetAttribs(array("id-object"=>$organismo->GetId(),"id-dati-contabili"=>$daticontabili->GetId(),"id-bilancio"=>$curBilancio->GetId(),"title"=>"Modifica bilancio"));
                    $edit->SetStyle("display: inline-block; margin-right: 1em;cursor: pointer");
                    $edit->SetText("Modifica bilancio");

                    //elimina
                    $trash=new AA_XML_A_Element("",$operazioni); $trash->SetClass("AA_Button_Trash_Bilancio ui-icon ui-icon-trash"); $trash->SetAttribs(array("id-object"=>$organismo->GetId(),"id-dati-contabili"=>$daticontabili->GetId(), "id-bilancio"=>$curBilancio->GetId(),"title"=>"Elimina bilancio"));
                    $trash->SetStyle("display: inline-block;cursor: pointer");
                    $trash->SetText("Elimina bilancio");
                }

                $curRow++;
            }

            $footer="<div style='font-style: italic; text-align: left; width: 100%; margin-top: 1em;font-size: smaller;'></div>";

            $this->SetText($footer,false);
        }
    }
}

//form di aggiunta dati contabili
Class AA_OrganismiDatiContabiliAddNewTemplateView extends AA_GenericFormTemplateView
{
    public function __construct($id="AA_OrganismiDatiContabiliAddNewTemplateView",$parent=null,$obj=null)
    {
        parent::__construct($id,$parent,$obj);

        if($obj instanceof AA_Organismi)
        {
            $anno_array=array();
            for($i=date("Y");$i > 2013;$i--)
            {
                $anno_array[$i]=$i;
            }
            
            //Imposta le dimensioni dei box
            $this->SetLabelFieldBoxSize("30%");
            $this->SetFieldBoxSize("70%");

            $this->AddField("Anno","nAnno", "select", date("Y"),$anno_array,""," width: 25%");
            $this->AddField("Oneri totali in €","sOneriTotali","text","",null,"Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            //$this->AddField("Tipo di bilancio","nTipologia", "select", "0", AA_Organismi_Const::GetTipoBilanci(),"Indicare sulle note l'eventuale motivazione dell'indisponibilità del dato.");
            //$this->AddField("Risultati di bilancio in €","sRisultatiBilancio","text","",null,"Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            if($obj->GetTipologia(true) == AA_Organismi_const::AA_ORGANISMI_SOCIETA_PARTECIPATA) $this->AddField("Fatturato in €","sFatturato","text","",null,"Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            $this->AddField("Dotazione organica","sDotazioneOrganica","text","",null,"Indicare il numero totale di unità di personale (dirigente e non) interno che esterno. Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            $this->AddField("Personale dipendente","sPersonale","text","",null,"Indicare il numero totale di unità di personale (dirigente e non) assunto a tempo indeterminato. Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            $this->AddField("Spesa per la dotazione organica in €","sSpesaDotazioneOrganica","text","",null,"Indicare la spesa complessiva per la dotazione organica. Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            $this->AddField("Spesa per lavoro flessibile in €","sSpesaLavoroFlessibile","text","",null,"Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            $this->AddField("Spesa per gli incarichi in €","sSpesaIncarichi","text","",null,"Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");

            $this->AddField("Note","sNote","textarea","",null,""," width: 99%;");

            $this->AddHiddenField("nIdParent",$obj->GetId());
        }
    }
}

//form di modifica bilancio
Class AA_OrganismiDatiContabiliEditTemplateView extends AA_GenericFormTemplateView
{
    public function __construct($id="AA_OrganismiDatiContabiliEditTemplateView",$parent=null,$obj=null)
    {
        parent::__construct($id,$parent,$obj);

        if($obj instanceof AA_OrganismiDatiContabili)
        {
            $anno_array=array();
            for($i=date("Y");$i > 2013;$i--)
            {
                $anno_array[$i]=$i;
            }
            
            $organismo=$obj->GetParent();

            $this->AddField("Anno","nAnno", "select", $obj->GetAnno(),$anno_array,""," width: 25%");
            $this->AddField("Oneri totali in €","sOneriTotali","text",preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "",$obj->GetOneriTotali()),null,"Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            //$this->AddField("Tipo di bilancio","nTipologia", "select", $obj->GetTipologia(true), AA_Organismi_Const::GetTipoBilanci(),"<br>Indicare sulle note l'eventuale motivazione dell'indisponibilità del dato.");
            //$this->AddField("Risultati di bilancio in €","sRisultatiBilancio","text",preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "",$obj->GetRisultatiBilancio()),null,"Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            if($organismo->GetTipologia(true) == AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) $this->AddField("Fatturato in €","sFatturato","text",preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "",$obj->GetFatturato()),null,"Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            $this->AddField("Dotazione organica","sDotazioneOrganica","text",preg_replace("/[\)|\(|€|\ |A-Za-z_\.\,]/", "",$obj->GetDotazioneOrganica()),null,"Indicare il numero totale di unità di personale (dirigente e non) interno che esterno. Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            $this->AddField("Personale dipendente","sPersonale","text",preg_replace("/[\)|\(|€|\ |A-Za-z_\.\,]/", "",$obj->GetDipendenti()),null,"Indicare il numero totale di unità di personale (dirigente e non) assunto a tempo indeterminato. Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            $this->AddField("Spesa per la dotazione organica in €","sSpesaDotazioneOrganica","text",preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "",$obj->GetSpesaDotazioneOrganica()),null,"Indicare la spesa complessiva per la dotazione organica. Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            $this->AddField("Spesa per lavoro flessibile in €","sSpesaLavoroFlessibile","text",preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "",$obj->GetSpesaLavoroFlessibile()),null,"Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");
            $this->AddField("Spesa per gli incarichi in €","sSpesaIncarichi","text",preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "",$obj->GetSpesaIncarichi()),null,"Inserire solo valori numerici, lasciare vuoto in caso di dati assenti."," width: 99%");

            $this->AddField("Note","sNote","textarea",$obj->GetNote(),null,""," width: 99%");

            $this->AddHiddenField("nIdParent",$obj->GetParent()->GetId());
            $this->AddHiddenField("id",$obj->GetId());
        }
    }
}

//form di eliminazione del bilancio
Class AA_OrganismiDatiContabiliTrashTemplateView extends AA_GenericFormTemplateView
{
    public function __construct($id="AA_OrganismiDatiContabiliTrashTemplateView",$parent=null,$obj=null)
    {
        parent::__construct($id,$parent,$obj);

        if($obj instanceof AA_OrganismiDatiContabili)
        {
            $this->SetText("<p style='text-align: center'>Vuoi eliminare i dati contabili dell'organismo: ".$obj->GetParent()->GetDenominazione().", per l'anno: ".$obj->GetAnno()."?</p><br/><p style='text-align: center'>Questa operazione non può essere annullata</p>");
            $this->AddHiddenField("nIdParent",$obj->GetParent()->GetId());
            $this->AddHiddenField("id",$obj->GetId());
        }
    }
}

//Classe per la gestione delle nomine
Class AA_OrganismiNomine extends AA_Object
{
    public function __construct($id=0,$parent=null,$user=null)
    {
        AA_Log::Log(get_class()."__construct($id)");

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();        
        }

        //Chiama il costruttore base
        parent::__construct($user);

        $this->SetType(AA_Const::AA_OBJECT_ART22_NOMINE);

        //Imposta la tabella del db sul db bind
        $this->oDbBind->SetTable(AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE);

        //Disabilita l'aggiornamento dello stato nel db
        $this->EnableStatusDbSync(false);

        //Disabilita l'aggiornamento del campo aggiornamento nel db
        $this->EnableAggiornamentoDbSync(false);

        //Disabilita l'aggiornamento del campo utente nel db
        $this->EnableUserDbSync(false);

        //disabilita l'aggiornamento della struttura nel db
        $this->EnableStructDbSync(false);

        //Aggiunge i bindings ai campi del db
        $this->oDbBind->AddBind("nIdParent","id_organismo");
        $this->oDbBind->AddBind("nTipologia","tipo_incarico");
        $this->oDbBind->AddBind("nFacenteFunzione","facente_funzione");
        $this->oDbBind->AddBind("sDataInizio","data_inizio");
        $this->oDbBind->AddBind("sDataFine","data_fine");
        $this->oDbBind->AddBind("sNome","nome");
        $this->oDbBind->AddBind("sCognome","cognome");
        $this->oDbBind->AddBind("sCompensoSpettante","compenso_spettante");
        $this->oDbBind->AddBind("sCodiceFiscale","codice_fiscale");
        $this->oDbBind->AddBind("sNote","note");
        $this->oDbBind->AddBind("bNominaRas","nomina_ras");
        $this->oDbBind->AddBind("sEstremiProvvedimento","estremi_provvedimento");
        $this->oDbBind->AddBind("nStorico","storico");
        $this->oDbBind->AddBind("bDataFinePresunta","data_fine_presunta");
        
        if($parent instanceof AA_Organismi && $id==0)
        {
            //Imposta l'elemento padre
            parent::SetParent($parent);

            //Imposta il controllo dei permessi sul padre
            //AA_Log::Log(__METHOD__." - Abilito il controllo dei permessi del genitore.",100, false,true);
            $this->EnableParentPermsCheck();

            //Disabilita il controllo dei permessi locale
            $this->DisableLocalPermsCheck();
            
            //Abilita l'aggiornamento del padre quando viene aggiornato questo elemento
            $this->EnableUpdateParent();
        }

        if($id > 0)
        {
            if($this->LoadFromDb($id,$user))
            {
                if(!($parent instanceof AA_Organismi)) $parent=AA_Organismi::Load($this->nIdParent,$user);
                if($parent instanceof AA_Organismi && $parent->GetID() != $this->nIdParent) $parent=AA_Organismi::Load($this->nIdParent,$user);
                    
                if($parent instanceof AA_Organismi)
                {
                    //Imposta l'elemento padre
                    parent::SetParent($parent);

                    //Imposta il controllo dei permessi sul padre
                    //AA_Log::Log(__METHOD__." - Abilito il controllo dei permessi del genitore.",100, false,true);
                    $this->EnableParentPermsCheck();

                    //Disabilita il controllo dei permessi locale
                    $this->DisableLocalPermsCheck();
                    
                    //Abilita l'aggiornamento del padre quando viene aggiornato questo elemento
                    $this->EnableUpdateParent();
                }
                else
                {
                    AA_Log::Log(__METHOD__." - Genitore non trovato o non esistente: ".$parent,100, false,true);
                }    
            }
            else
            {
                AA_Log::Log(__METHOD__." - Genitore non trovato o non esistente (identificativo: $id).",100, false,true);    
            }
        }
        else
        {
            AA_Log::Log(__METHOD__." - Genitore non impostato.",100, false,true);
        }
    }

    //Rappresentazione xml
    public function ToXml()
    {
        $return="<nomina nomina_ras='".$this->IsNominaRas()."'>";
        $return.="<nome>".AA_Utils::xmlentities($this->GetNome())."</nome>";
        $return.="<cognome>".AA_Utils::xmlentities($this->GetCognome())."</cognome>";
        //$return.="<cf>".AA_Utils::xmlentities($this->GetCodiceFiscale())."</cf>";
        $return.="<incarico id_tipo='".$this->GetTipologia(true)."'>".AA_Utils::xmlentities($this->GetTipologia())."</incarico>";
        $return.="<data_inizio>".AA_Utils::xmlentities($this->GetDataInizio())."</data_inizio>";
        $return.="<data_fine>".AA_Utils::xmlentities($this->GetDataFine())."</data_fine>";
        $return.="<data_fine_presunta>".AA_Utils::xmlentities($this->IsDataFinePresunta())."</data_fine_presunta>";
        $return.="<emolumenti_totali>".AA_Utils::xmlentities($this->GetCompensoSpettante())."</emolumenti_totali>";
        $return.="<note>".AA_Utils::xmlentities($this->GetNote())."</note>";
        $return.="<compensi>";
        
        $compensi=$this->GetCompensi();
        foreach($compensi as $cur_compenso)
        {
            $return.=$cur_compenso->toXml();
        }
        $return.="</compensi>";
        $return.="</nomina>";
        return $return;
    }
    
    //Aggiorna il compenso totale
    protected function UpdateTrattamentoEconomico($user=null)
    {
        if(!$this->isValid()) return;
        
        $trattamento=0;
        
        foreach($this->GetCompensi($user) as $curCompenso)
        {
            $trattamento+= floatval(str_replace(array(".",","),array("","."),$curCompenso->GetParteFissa()))+floatval(str_replace(array(".",","),array("","."),$curCompenso->GetParteVariabile()));
        }
        
        $this->SetCompensoSpettante(number_format($trattamento,2,",","."));
    }
    
    //Nome
    protected $sNome="";
    public function GetNome()
    {
        return $this->sNome;
    }
    public function SetNome($val="")
    {
        $this->sNome=$val;
        $this->SetChanged();
    }

    //Cognome
    protected $sCognome="";
    public function GetCognome()
    {
        return $this->sCognome;
    }
    public function SetCognome($val="")
    {
        $this->sCognome=$val;
        $this->SetChanged();
    }
    
    //Estremi provvedimento
    protected $sEstremiProvvedimento="";
    public function GetEstremiProvvedimento()
    {
        return $this->sEstremiProvvedimento;
    }
    public function SetEstremiProvvedimento($val="")
    {
        $this->sEstremiProvvedimento=$val;
        $this->SetChanged();
    }

    //Codice fiscale
    protected $sCodiceFiscale="";
    public function GetCodiceFiscale()
    {
        return $this->sCodiceFiscale;
    }
    public function SetCodiceFiscale($val="")
    {
        $this->sCodiceFiscale=$val;
        $this->SetChanged();
    }

    //Data inizio
    protected $sDataInizio="";
    public function GetDataInizio()
    {
        return $this->sDataInizio;
    }
    public function SetDataInizio($val="")
    {
        $this->sDataInizio=$val;
        $this->SetChanged();
    }

    //Data fine
    protected $sDataFine="";
    public function GetDataFine()
    {
        return $this->sDataFine;
    }
    public function SetDataFine($val="")
    {
        $this->sDataFine=$val;
        $this->SetChanged();
    }

    protected $bDataFinePresunta=false;
    public function IsDataFinePresunta()
    {
        return $this->bDataFinePresunta;
    }

    public function SetDataFinePresunta($val=true)
    {
        if($val) $this->bDataFinePresunta=true;
        else $this->bDataFinePresunta=false;
    }

    //Id parent
    protected $nIdParent=0;
    public function GetParent($user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();        
        }
        
        $parent=parent::GetParent();
        if( $parent instanceof AA_Organismi && $parent->GetId() == $this->nIdParent) return $parent;

        if($this->nIdParent > 0)
        {
            $parent=AA_Organismi::Load($this->nIdParent,$user);
        }

        if(!$parent->IsValid()) AA_Log::Log(__METHOD__." - Genitore non trovato o non esistente: ".$parent,100, false,true);
        return $parent;
    }
    public function GetParentId()
    {
        return $this->nIdParent;
    }

    //Imposta il genitore
    public function SetParent($parent=null,$user=null)
    {      
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();        
        }

        //Verifica che $parent sia un oggetto organismi
        if(!($parent instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - L'oggetto parente indicato non valido",100, false,true);
            return false;
        }

        //Verifica che l'utente possa modificare l'oggetto  
        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0 && ($parent->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            //svuota la cahce dei permessi
            $this->ClearCache();

            parent::SetParent($parent);
            $this->nIdParent=$parent->GetId();

            //Imposta il controllo dei permessi sul padre
            $this->EnableParentPermsCheck();

            //Disabilita il controllo dei permessi locale
            $this->DisableLocalPermsCheck();
            
            //Abilita l'aggiornamento del padre quando viene aggiornato questo elemento
            $this->EnableUpdateParent();

            return true;
        }
        else
        {
            AA_Log::Log(__METHOD__." - L'utente: ".$user->GetNome()." ".$user->GetCognome()." non ha i permessi per modificare l'oggetto o il genitore",100, false,true);
            return false;
        }
    }

    //Tipologia
    protected $nTipologia=0;
    public function GetTipologia($bNumeric=false)
    {
        if($bNumeric)return $this->nTipologia;
        
        $tipo=AA_Organismi_Const::GetTipoNomine();

        return $tipo[$this->nTipologia];
    }
    public function SetTipologia($val=0)
    {
        if($val>=0) $this->nTipologia=$val;
    }

    //Facente funzione
    protected $nFacenteFunzione=0;
    public function IsFacenteFunzione()
    {
        return $this->nFacenteFunzione;
        
    }
    public function SetFacenteFunzione($val=1)
    {
        if($val>0) $this->nFacenteFunzione=1;
        else $this->nFacenteFunzione=0;
    }

    //Note
    protected $sNote="";
    public function SetNote($val)
    {
        $this->sNote=$val;
    }
    public function GetNote()
    {
        return $this->sNote;
    }

    //flag di nomina RAS
    protected $bNominaRas=0;
    public function SetNominaRas($val=1)
    {
        $this->bNominaRas=$val;
    }
    public function GetNominaRas()
    {
        return $this->bNominaRas;
    }
    public function IsNominaRas()
    {
        if($this->bNominaRas > 0) return true;
        else return false;
    }

    //Compenso spettante
    protected $sCompensoSpettante="";
    public function SetCompensoSpettante($val=0)
    {
        $this->sCompensoSpettante=preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "",$val);
    }
    public function GetCompensoSpettante()
    {
        return $this->sCompensoSpettante;
    }

    //Flag di storicizzazione
    protected $nStorico=0;
    public function SetStorico($val=true)
    {
        $this->nStorico=0;
        if($val==true) $this->nStorico=1;
    }
    public function IsStorico()
    {
        if($this->nStorico > 0) return true;
        else return false;
    }

    //Compenso erogato
    protected $sCompensoErogato="";
    public function SetCompensoErogato($val=0)
    {
        $this->sCompensoErogato=preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "",$val);
    }
    public function GetCompensoErogato()
    {
        return $this->sCompensoErogato;
    }

    //Verifica se l'incaricato compie o supera i 65 anni durante l'incarico
    public function IsOver65()
    {
        if(intval("19".substr($this->sCodiceFiscale,6,2))+65 <= date("Y") && $this->sCodiceFiscale !="" && strtolower(trim($this->sCodiceFiscale))!="n.d.") return true;
        return false;
    }

    //Aggiunge una nuova nomina al db
    static public function AddNewToDb($data=null, $parent=null, $user=null)
    {
        AA_Log::Log(__METHOD__."($data)");

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return null;
            }
        }

        //Verifica Flags
        if(!$user->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$user->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            AA_Log::Log(__METHOD__." - l'utente (".$user->GetNome()." ".$user->GetCognome().") non può gestire le pubblicazioni di cui all'art. 22 del d.lgs. 33/2013.", 100,false,true);
            return null;
        }

        //Verifica il parente
        if(!($parent instanceof AA_Organismi) || ($parent->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - Oggetto genitore non valido o l'utente non ha i permessi per modificarlo.", 100,false,true);
            return null;
        }

        $new_nomina = new AA_OrganismiNomine(0,$parent,$user);

        if(!$new_nomina->ParseData($data,$user))
        {
            //AA_Log::Log(__METHOD__." - Errore durante il parsing dei dati: ".print_r($data,TRUE), 100,false,true);
            return null;
        }

        $new_nomina->SetID(0);
        
        //Se è un facente funzione la data fine è uguale alla data inizio
        if($new_nomina->IsFacenteFunzione())
        {
            $new_nomina->SetDataFine($new_nomina->GetDataInizio());
        }

        if(!$new_nomina->SetParent($parent,$user))
        {
            return null;
        }

        if(!($new_nomina->VerifyData()))
        {
            return null;
        }

        $new_nomina->EnableDbSync();
        if(!$new_nomina->UpdateDb($user,null,true," Aggiunta nuovo incarico: ".$new_nomina->GetTipologia()." per: ".$new_nomina->GetNome()." ".$new_nomina->GetCognome()))
        {
            AA_Log::Log(__METHOD__." - Errore durante il salvataggio della nuova nomina sul DB.", 100,false,true);
            return null;    
        }

        return $new_nomina;
    }
    
    //Aggiunge un compenso ad un incarico
    public function AddNewCompenso($compenso=null, $user=null)
    {
        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__." - incarico non valido.", 100);
            return false;
        }
                
        if(!($compenso instanceof AA_OrganismiNomineCompensi)) 
        {
            AA_Log::Log(__METHOD__." - compenso non valido.", 100);
            return false;
        }
        
        //verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            if($this->oUser->IsCurrentUser()) $user=$this->oUser;
            else $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,true,true);
                return false;
            }
        }
        
        //Verifica permessi
        $parent=$this->GetParent();
        if(($parent->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente (".$user.") non può modificare l'organismo (".$parent->GetDescrizione().").", 100);
            return false;
        }
        
        //Verifica dati compenso
        if($compenso->GetAnno() < "2013" || $compenso->GetAnno() > "2060")
        {
            AA_Log::Log(__METHOD__." - Anno compenso errato.", 100);
            return false;
        }
        
        //Verifica se esiste già un altro compenso  per l'anno impostato
        $curCompenso=$this->GetCompenso("", $compenso->GetAnno(),$user);
        if($curCompenso instanceof AA_OrganismiNomineCompensi)
        {
            AA_Log::Log(__METHOD__." - Compenso per l'anno: ".$compenso->GetAnno()." già presente.", 100);
            return false;
        }
        
        //Inserisce il compenso
        $db = new AA_Database();
        
        $query="INSERT INTO ".AA_Organismi_Const::AA_ORGANISMI_COMPENSI_DB_TABLE." set id_nomina='".$this->GetId()."'";
        $query.=", anno ='".addslashes($compenso->GetAnno())."'";
        $query.=", parte_fissa='".addslashes($compenso->GetParteFissa())."'";
        $query.=", parte_variabile='".addslashes($compenso->GetParteVariabile())."'";
        $query.=", rimborsi='".addslashes($compenso->GetRimborsi())."'";
        $query.=", note='".addslashes($compenso->GetNote())."'";
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella questy: ".$query." - ".$db->GetLastErrorMessage(), 100);
            return false;
        }
        
        $this->UpdateTrattamentoEconomico($user);
        
        $this->SetChanged();
        $this->UpdateDb($user);
        
        return true;
    }
    
    //Aggiorna un compenso per l'incarico
    public function UpdateCompenso($compenso=null, $user=null)
    {   
        //Verifica oggetto
        if(!$this->isValid())
        {
            AA_Log::Log(__METHOD__." - nomina non valida.", 100);
            return false;
        }

        //Verifica dati compenso
        if(!($compenso instanceof AA_OrganismiNomineCompensi)) 
        {
            AA_Log::Log(__METHOD__." - compenso non valido.", 100);
            return false;
        }
        
        
        //verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            if($this->oUser->IsCurrentUser()) $user=$this->oUser;
            else $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,true,true);
                return false;
            }
        }
        
        //Verifica permessi
        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente (".$user.") non può modificare l'organismo (".$this->GetDescrizione().").", 100);
            return false;
        }
        
        //Verifica se il compenso esiste già
        $curCompenso=$this->GetCompenso($compenso->GetId(),"",$user);
        if(!($curCompenso instanceof AA_OrganismiNomineCompensi))
        {
            AA_Log::Log(__METHOD__." - compenso non valido.", 100);
            return false;
        }
        
        //Verifica dati compenso
        if($compenso->GetAnno() < "2013" || $compenso->GetAnno() > "2060")
        {
            AA_Log::Log(__METHOD__." - Anno compenso errato.", 100);
            return false;
        }
        
        //Aggiorna il compenso
        $db = new AA_Database();
        
        $query="UPDATE ".AA_Organismi_Const::AA_ORGANISMI_COMPENSI_DB_TABLE." set id_nomina='".$this->GetId()."'";
        $query.=", anno ='".addslashes($compenso->GetAnno())."'";
        $query.=", parte_fissa='".addslashes($compenso->GetParteFissa())."'";
        $query.=", parte_variabile='".addslashes($compenso->GetParteVariabile())."'";
        $query.=", rimborsi='".addslashes($compenso->GetRimborsi())."'";
        $query.=", note='".addslashes($compenso->GetNote())."'";
        $query.=" WHERE id='".addslashes($compenso->GetId())."' and id_nomina='".$this->GetId()."'";
        $query.=" LIMIT 1";
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query: ".$query." - ".$db->GetLastErrorMessage(), 100);
            return false;
        }
        
        $this->UpdateTrattamentoEconomico($user);
        
        $this->SetChanged();
        $this->UpdateDb($user);
        
        return true;
    }
    
    //Restituisce la lista dei compensi per l'incarico
    public function GetCompensi($user=null)
    {   
        //Verifica oggetto
        if(!$this->isValid())
        {
            AA_Log::Log(__METHOD__." - nomina non valida.", 100);
            return array();
        }
        
        //verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            if($this->oUser->IsCurrentUser()) $user=$this->oUser;
            else $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,true,true);
                return array();
            }
        }
        
        //Verifica permessi
        $parent=$this->GetParent();
        if(($parent->GetUserCaps($user) & AA_Const::AA_PERMS_READ) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente (".$user.") non può visualizzare l'organismo (".$parent->GetDescrizione().").", 100);
            return array();
        }
                
        //recupera i compensi
        $db = new AA_Database();
        
        $query="SELECT * FROM ".AA_Organismi_Const::AA_ORGANISMI_COMPENSI_DB_TABLE;
        $query.=" WHERE id_nomina='".$this->GetID()."'";
        $query.=" ORDER BY anno DESC";
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella questy: ".$query." - ".$db->GetLastErrorMessage(), 100);
            return array();
        }
        
        $return=array();
        $rs=$db->GetResultSet();
        if($db->GetAffectedRows()>0)
        {
            //AA_Log::Log(__METHOD__." - recordset: ".print_r($rs,true), 100);
            
            foreach($rs as $curRec)
            {
                $return[$curRec['id']]=new AA_OrganismiNomineCompensi($curRec['id'],$curRec['anno'],$curRec['parte_fissa'],$curRec['parte_variabile'],$curRec['rimborsi'],$curRec['note']);
            }
        }
        
        return $return;
    }
    
    //Restituisce la lista dei compensi per l'incarico
    public function GetCompenso($id="",$anno="",$user=null)
    {   
        //Verifica oggetto
        if(!$this->isValid())
        {
            AA_Log::Log(__METHOD__." - nomina non valida.", 100);
            return array();
        }
        
        //verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            if($this->oUser->IsCurrentUser()) $user=$this->oUser;
            else $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,true,true);
                return array();
            }
        }
        
        //Verifica permessi
        $parent=$this->GetParent();
        if(($parent->GetUserCaps($user) & AA_Const::AA_PERMS_READ) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente (".$user.") non può visualizzare l'organismo (".$parent->GetDescrizione().").", 100);
            return array();
        }
                
        //recupera il compensi
        $db = new AA_Database();
        
        $query="SELECT * FROM ".AA_Organismi_Const::AA_ORGANISMI_COMPENSI_DB_TABLE."  WHERE id_nomina='".$this->GetID()."'";
        if($id !="") $query.=" AND id='".addslashes($id)."'";
        else
        {
            if($anno !="")
            {
                $query.=" AND anno like '".addslashes($anno)."'";
            }
        }
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query: ".$query." - ".$db->GetLastErrorMessage(), 100);
            return array();
        }
        
        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            return new AA_OrganismiNomineCompensi($rs[0]['id'],$rs[0]['anno'],$rs[0]['parte_fissa'],$rs[0]['parte_variabile'],$rs[0]['rimborsi'],$rs[0]['note']);
        }
        
        return null;
    }
    
    //elimina un compenso per l'incarico
    public function TrashCompenso($id_compenso="", $user=null)
    {   
        //Verifica oggetto
        if(!$this->isValid())
        {
            AA_Log::Log(__METHOD__." - nomina non valida.", 100);
            return false;
        }
        
        //verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            if($this->oUser->IsCurrentUser()) $user=$this->oUser;
            else $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,true,true);
                return false;
            }
        }

        //Verifica se il compenso esiste già
        $curCompenso=$this->GetCompenso($id_compenso,$user);
        if(!($curCompenso instanceof AA_OrganismiNomineCompensi))
        {
            AA_Log::Log(__METHOD__." - compenso non valido.", 100);
            return false;
        }        
        
        //Verifica permessi
        $parent=$this->GetParent();
        if(($parent->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente (".$user.") non può modificare l'organismo (".$parent->GetDescrizione().").", 100);
            return false;
        }
        
        //elimina il compenso il compenso
        $db = new AA_Database();
        
        $query="DELETE FROM ".AA_Organismi_Const::AA_ORGANISMI_COMPENSI_DB_TABLE;
        $query.=" WHERE id='".addslashes($id_compenso)."' and id_nomina='".$this->GetID()."' LIMIT 1";
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query: ".$query." - ".$db->GetLastErrorMessage(), 100);
            return false;
        }
        
        $this->UpdateTrattamentoEconomico($user);
        
        $this->SetChanged();
        $this->UpdateDb($user);
        
        return true;
    }
    
    //elimina un compenso per l'incarico
    public function DeleteAllCompensi($user=null)
    {   
        //Verifica oggetto
        if(!$this->isValid())
        {
            AA_Log::Log(__METHOD__." - nomina non valida.", 100);
            return false;
        }
        
        //verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            if($this->oUser->IsCurrentUser()) $user=$this->oUser;
            else $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,true,true);
                return false;
            }
        }
        
        //Verifica permessi
        $parent=$this->GetParent();
        if(($parent->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente (".$user.") non può modificare l'organismo (".$parent->GetDescrizione().").", 100);
            return false;
        }        
                
        //elimina il compenso il compenso
        $db = new AA_Database();
        
        $query="DELETE FROM ".AA_Organismi_Const::AA_ORGANISMI_COMPENSI_DB_TABLE;
        $query.=" WHERE id_nomina='".$this->GetID()."' ";
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query: ".$query." - ".$db->GetLastErrorMessage(), 100);
            return false;
        }
        
        $this->SetChanged();
        
        return true;
    }
    
    //Verifica la congruenza dei dati
    public function VerifyData()
    {
        $err="";
        if(trim($this->GetNome())=="") $err.="\r\n- Occorre inserire il nome.";
        if(trim($this->GetCognome())=="") $err.="\r\n- Occorre inserire il cognome.";
        if(trim($this->GetDataInizio())=="" || $this->GetDataInizio()=="0000-00-00") $err.="\r\n- Occorre inserire la data di inizio.";
        if(trim($this->GetDataFine())=="" || $this->GetDataFine() < $this->GetDataInizio()) $err.="\r\n- Occorre inserire la data di fine che deve essere maggiore o uguale a quella di inizio.";
        if($this->GetTipologia(true)==0) $err.="\r\n- Occorre selezionare un tipo di nomina.";
         
        //Verifica di coerenza dei dati
        if($err !="")
        {
            AA_Log::Log("Sono stati rilevati i seguenti errori:\r\n".$err, 100,false,true);
            return false;
        }

        return true;
    }

    //Aggiorna il database
    public function UpdateDb($user=null,$data=null, $bLog=true,$sDetailLog="")
    {
        if(!($this->VerifyData()))
        {
            return false;
        }
        
        //verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            if($this->oUser->IsCurrentUser()) $user=$this->oUser;
            else $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,true,true);
                return false;
            }
        }

        //Se è un facente funzione la data fine è uguale alla data inizio    
        if($data['nFacenteFunzione'] == 1)
        {
            $data['sDataFine']=$data['sDataInizio'];
        }

        return parent::UpdateDb($user,$data,$bLog,$sDetailLog);
    }

    //Funzione di Parsing a partire da un array (non cambia l'identificativo dell'oggetto)
    public function ParseData($data=null,$user=null)
    {
        //verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            if($this->oUser->IsCurrentUser()) $user=$this->oUser;
            else $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,true,true);
                return false;
            }
        }

        //Verifica i dati
        if(trim($data['sNome']) == "")
        {
            AA_Log::Log(__METHOD__." - Occorre specificare il nome.", 100,true,true);
            return false;
        }

        if(trim($data['sCognome']) == "")
        {
            AA_Log::Log(__METHOD__." - Occorre specificare il cognome.", 100,true,true);
            return false;
        }

        if($data['nTipologia'] == "0")
        {
            AA_Log::Log(__METHOD__." - Occorre specificare l'incarico.", 100,true,true);
            return false;
        }

        //Verifica i checkbox 
        if(!isset($data['bNominaRas'])) $this->SetNominaRas(0);
        
        //facente funzione
        if(!isset($data['nFacenteFunzione'])) $this->SetFacenteFunzione(0);

        if($data['nFacenteFunzione'] == 1)
        {
            $data['sDataFine']=$data['sDataInizio'];
        }

        if(!parent::ParseData($data,$user))
        {
            AA_Log::Log(__METHOD__." - Errore nel parsing dei dati.", 100,false,true);
            return false;
        }

        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $this->EnableDbSync();
            return true;
        }
        else
        {
            AA_Log::Log(__METHOD__." - L'utente (".$user->GetNome().") non può aggiornare la nomina.", 100,false,true);
        }

        return false;
    }

    //Restituisce i documenti collegati, sottoforma di array
    public function GetDocs($annoRif="",$tipodoc=0,$serial="",$user=null)
    {
        if(!$this->IsValid()) return array();

        return AA_OrganismiNomineDocument::GetAllDocs($this,$annoRif,$tipodoc,$serial,$user);
    }
    
    //elimina l'oggetto
    public function Trash($user=null, $bDelete=true)
    {
        //Verifica permessi
        if(!$this->VerifyDbSync($user) || !$this->IsValid())
        {
            return false;
        }

        $perms=$this->GetUserCaps($user);
        if(($perms & AA_Const::AA_PERMS_DELETE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente: ".$user->GetNome()." non ha i permessi per cestinare/eliminare l'oggetto", 100,false,true);
            return false;
        }

        if(!$this->DeleteAllCompensi())
        {
            return false;
        }
        
        if($this->DeleteAllDocs("",0,"",$user)) 
        {
            $return = parent::Trash($user, true);
            $organismo=$this->GetParent($user);
            if($organismo)
            {
                $organismo->UpdateDb($user,null,true," Eliminazione nomina/incarico per ".$this->GetCognome()." ".$this->GetNome());
            }
            return $return;
        }
        else
        {
            return false;
        }
    }

    //Download dei documenti
    public function DownloadAllDocs()
    {
        if(!$this->bValid)
        {
            die("Documento non trovato.");
        }

        $download=false;
        $docs=$this->GetDocs();
        if(sizeof($docs))
        {         
            $zip = new ZipArchive();
            $zip_path="/tmp/".uniqid().".zip";
            
            error_log($zip_path);

            if ($zip->open($zip_path, ZipArchive::CREATE)!==TRUE)
            {
              AA_Log::Log(__METHOD__."() - Errore durante la generazione del file temporaneo.", 100, false, true);
              error_log(__METHOD__."() - Errore durante la generazione del file temporaneo.");
              exit("Errore durante la generazione del file temporaneo (AA_ERR_".__METHOD__.").");
            }

            foreach($docs as $curDoc)
            {
                $curDichiarazione=$this->GetId()."_".$curDoc->GetAnno()."_".$curDoc->GetTipologia(true)."_".$curDoc->GetSerial().".pdf";
                if(!$zip->addFile($curDoc->GetLocalDocumentPath(), $curDichiarazione))
                {
                    AA_Log::Log(__METHOD__."() - errore durante l'inserimento del file: ", 100, false, true);
                    error_log(__METHOD__."() - errore durante l'inserimento del file: ", 100);
                    $zip->close();
                    if(!unlink($zip_path))
                    {
                        AA_Log::Log(__METHOD__."() - errore durante la rimozione del file compresso: ".$zip_path, 100, false, true);
                        error_log(__METHOD__."() - errore durante la rimozione del file compresso: ".$zip_path);
                        exit("Errore durante la rimozione del file temporaneo (AA_ERR_".__METHOD__.")");
                    }
                    error_log("Errore durante l'inserimento del curriculum: ".$curDichiarazione." nel file temporaneo (AA_ERR_".__METHOD__.")");
                    exit("Errore durante l'inserimento del curriculum: ".$curDichiarazione." nel file temporaneo (AA_ERR_".__METHOD__.").");
                }
            }
            $download=true;
            $zip->close();
        }

        if($download)
        {
            $filename="pubblicazioni_art22_".date("Ymd").".zip";
            header("Cache-control: private");
            header("Content-type: application/zip");
            header("Content-Length: ".filesize($zip_path));
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            
            $fd = fopen ($zip_path, "rb");
            echo fread ($fd, filesize ($zip_path));
            fclose ($fd);
            
            if(!unlink($zip_path))
            {
              error_log("Errore durante l'eliminazione dle file compresso: ".$zip_path." (AA_ERR_".__METHOD__.").");
              exit("Errore durante l'eliminazione dle file compresso: ".$zip_path." (AA_ERR_".__METHOD__.").");
            }
            die();    
        }
        else die ("Documenti non trovati.");
    }

    //Elimina tutti i documenti collegati, che soddisfano i criteri indicati
    public function DeleteAllDocs($annoRif="",$tipodoc=0,$serial="",$user=null)
    {
        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__." - Nomina non valida.", 100,false,true);
            return false;
        }

        //verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            if($this->oUser->IsCurrentUser()) $user=$this->oUser;
            else $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,true,true);
                return false;
            }
        }

        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - L'utente (".$user->GetNome().") non può modificare la nomina.", 100,false,true);
            return false;
        }

        $bUpdate=false;
        foreach($this->GetDocs($annoRif,$tipodoc,$serial) as $curDoc)
        {

            if(!$curDoc->Delete($user))
            {
                return false;
            }
            else 
            {

                $bUpdate=true;
            }
        }

        if($bUpdate) return $this->UpdateDb($user);
        
        return true;
    }

    //Elimina un singolo documento (se sono impostati i parametri)
    public function DelDoc($annoRif="",$tipoDoc=0,$serial="",$user=null)
    {
        return $this->DeleteAllDocs($annoRif,$tipoDoc,$serial,$user);
    }
}

#Classe per la gestione dei bilanci sui dati contabili
Class AA_OrganismiBilanci
{
    public function __toString()
    {
        return "AA_OrganismiBilanci(".$this->GetId().")";                
    }
    //Identificativo dati contabili
    private $nIdDatiContabili=0;
    public function SetIdDatiContabili($val=0)
    {
        $this->nIdDatiContabili=$val;
    }
    public function GetIdDatiContabili()
    {
        return $this->nIdDatiContabili;
    }

    //Restituisce l'oggetto DatiContabili collegato
    public function GetDatiContabili($user=null)
    {
        if($this->nIdDatiContabili>0)
        {
            return new AA_OrganismiDatiContabili($this->nIdDatiContabili,null,$user);
        }

        AA_Log::Log(__METHOD__." - identificativo dati contabili non valido (".$this->nIdDatiContabili.")",100,false,true);
        return null;
    }

    //Identificativo bilancio
    private $nId=0;
    public function SetId($val=0)
    {
        $this->nId=$val;
    }
    public function GetId()
    {
        return $this->nId;
    }

    //Tipo bilancio
    protected $nTipo=0;
    public function SetTipo($val=0)
    {
        $this->nTipo=$val;
    }
    public function GetTipo($bNumeric=false)
    {
        if($bNumeric) return $this->nTipo;
        else
        {
            $tipo=AA_Organismi_Const::GetTipoBilanci();
            return $tipo[$this->nTipo];
        }
    }

    //risultati di bilancio
    protected $sRisultati="";
    public function SetRisultati($val="")
    {
        $this->sRisultati=preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "",$val);
    }
    public function GetRisultati()
    {
        return $this->sRisultati;
    }

    //restituisce il percorso locale al documento
    public function GetLocalDocumentPath()
    {
        if(!$this->bValid) return "";

        if(file_exists(AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_NOMINE_DOCS_PATH."/".$this->nIdDatiContabili."_".$this->nId.".pdf"))
        {
            return AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_NOMINE_DOCS_PATH."/".$this->nIdDatiContabili."_".$this->nId.".pdf";
        }
    }

    //restituisce il percorso pubblico
    public function GetPublicDocumentPath()
    {
        if(!$this->bValid) return "";

        return AA_Organismi_Const::AA_ORGANISMI_NOMINE_DOCS_PUBLIC_PATH."?nomina=".$this->nIdDatiContabili."_".$this->nId;
    }
 

    //Download del documento
    public function Download($embed=false)
    {
        if(!$this->bValid)
        {
            die("Documento non trovato.");
        }

        $filename=$this->GetLocalDocumentPath();

        header("Cache-control: private");
		header("Content-type: application/pdf");
		header("Content-Length: ".filesize($filename));
		if(!$embed) header('Content-Disposition: attachment; filename="bilancio_'.$this->nIdDatiContabili."_".$this->nId.".pdf'");
		
		$fd = fopen ($filename, "rb");
		echo fread ($fd, filesize ($filename));
		fclose ($fd);
        die();
    }

    //note
    protected $sNote="";
    public function SetNote($val="")
    {
        $this->sNote=$val;
    }
    public function GetNote()
    {
        return $this->sNote;
    }
        
    //Flag di validità
    private $bValid=false;
    public function IsValid()
    {
        return $this->bValid;
    }

    public function __construct()
    {

    }
    
    public function ToXml()
    {
        $return ="<bilancio>";
        $return.="<tipo id_tipo='".$this->GetTipo(true)."'>".$this->GetTipo()."</tipo>";
        $return.="<risultati>".$this->GetRisultati()."</risultati>";
        $return.="<note>".mb_encode_numericentity($this->GetNote(),array (0x0, 0xffff, 0, 0xffff), 'UTF-8')."</note>";
        $return.="</bilancio>";
        
        return $return;
    }
}

#Classe per la gestione dei documenti
Class AA_OrganismiNomineDocument
{
    //identificativo nomina collegata
    private $nIdNomina=0;
    public function SetIdNomina($id=0)
    {
        $this->nIdNomina=$id;
    }
    public function GetIdNomina()
    {
        return $this->nIdNomina;
    }
    public function GetNomina($user=null)
    {
        if($this->nIdNomina>0)
        {
            return new AA_OrganismiNomine($this->nIdNomina,null,$user);
        }

        AA_Log::Log(__METHOD__." - identificativo nomina non valido (".$this->nIdNomina.")",100,false,true);
        return null;
    }

    //anno di riferimento
    private $nAnno="";
    public function SetAnno($val="")
    {
        if($val=="")$val=date("Y");

        $this->nAnno=$val;
    }
    public function GetAnno()
    {
        return $this->nAnno;
    }

    //tipologia documento
    private $nTipologia=0;
    public function SetTipologia($val=0)
    {
        $val = $val & AA_Organismi_Const::AA_ORGANISMI_DOC_MASK;
        
        //AA_Log::Log(__METHOD__." - tipologia: ".AA_Organismi_Const::AA_ORGANISMI_DOC_MASK,100);
        
        $this->nTipologia=$val;
    }
    public function GetTipologia($bValue=false)
    {
        if($bValue) return $this->nTipologia;
        else 
        {
            $tipodocs=AA_Organismi_Const::GetTipoDocs();
            return $tipodocs[$this->nTipologia];
        }
    }

    //serial
    private $sSerial="";
    public function SetSerial($val="")
    {
        $this->sSerial=$val;
    }
    public function GetSerial()
    {
        return $this->sSerial;
    }

    //Flag di validità
    private $bValid=false;
    public function IsValid()
    {
        return $this->bValid;
    }

    private function __construct($idNomina=0,$anno="",$tipo=0, $serial="0",$user=null)
    {
        $this->SetIdNomina($idNomina);

        if($anno=="") $anno=date("Y");
        $this->SetAnno($anno);

        $this->SetTipologia($tipo);

        $this->SetSerial($serial);

        $file=AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_NOMINE_DOCS_PATH."/".$idNomina."_".$anno."_".$tipo.".pdf";
        if($serial !="")
        {
            $file=AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_NOMINE_DOCS_PATH."/".$idNomina."_".$anno."_".$tipo."_".$serial.".pdf";
        }

        if(file_exists($file))
        {
            $this->bValid=true;
        }
        else
        {
            AA_Log::Log(__METHOD__." - file non trovato: ".$file,100);
        }
    }

    //restituisce il percorso locale al documento
    public function GetLocalDocumentPath()
    {
        if(!$this->bValid) return "";

        $file=AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_NOMINE_DOCS_PATH."/".$this->nIdNomina."_".$this->nAnno."_".$this->nTipologia.".pdf";
        if($this->sSerial !="")
        {
            $file=AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_NOMINE_DOCS_PATH."/".$this->nIdNomina."_".$this->nAnno."_".$this->nTipologia."_".$this->sSerial.".pdf";
        }

        if(file_exists($file))
        {
            return $file;
        }
    }

    //restituisce il percorso pubblico
    public function GetPublicDocumentPath()
    {
        if(!$this->bValid) return "";
        
        return AA_Organismi_Const::AA_ORGANISMI_NOMINE_DOCS_PUBLIC_PATH."?nomina=".$this->nIdNomina."&anno=".$this->nAnno."&tipo=".$this->nTipologia."&serial=".$this->sSerial;
    }
 

    //Download del documento
    public function Download($embed=false)
    {
        if(!$this->bValid)
        {
            die("Documento non trovato.");
        }

        $filename=$this->GetLocalDocumentPath();

        header("Cache-control: private");
		header("Content-type: application/pdf");
		header("Content-Length: ".filesize($filename));
		if(!$embed) header('Content-Disposition: attachment; filename="'.$this->nIdNomina."_".$this->nAnno."_".$this->GetTipologia(true).'_'.$this->GetSerial().'.pdf"');
		
		$fd = fopen ($filename, "rb");
		echo fread ($fd, filesize ($filename));
		fclose ($fd);
        die();
    }

    public function Delete($user=null)
    {
        if(!$this->bValid)
        {
            AA_Log::Log(__METHOD__." - Documento non valido.",100,false,true);
            return false;
        }

        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return false;
            }
        }

        $nomina=$this->GetNomina($user);
        if(($nomina->GetUserCaps($user)&AA_Const::AA_PERMS_WRITE)==0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente (".$user->GetNome()." ".$user->GetCognome().") non può eliminare il documento (".$this->nIdNomina."_".$this->nAnno."_".$this->nTipologia.") per la nomina: ".$nomina->GetNome()." ".$nomina->GetCognome()." (".$nomina->GetId().")", 100,false,true);
            return false;
        }

        if(is_file($this->GetLocalDocumentPath()) && !unlink($this->GetLocalDocumentPath()))
        {
            AA_Log::Log(__METHOD__." - Errore durante l'eliminazione del documento (".$this->nIdNomina."_".$this->nAnno."_".$this->nTipologia.") per la nomina: ".$nomina->GetNome()." ".$nomina->GetCognome()." (".$nomina->GetId().") - file non eliminato.", 100,false,true);
            return false;
        }

        return true;
    }

    //Salva un documento per una nomina
    static public function UploadDoc($nomina=null,$anno="",$tipo=0,$file="",$bOverride=false,$user=null)
    {
        //Verifica nomina
        if(!($nomina instanceof AA_OrganismiNomine))
        {
            AA_Log::Log(__METHOD__." - Nomina non valida o non impostata.", 100,false,true);
            return false;
        }

        //Verifica anno
        if($anno=="") $anno=date("Y");
        if(strlen($anno) < 4 || strlen($anno) > 4)
        {
            AA_Log::Log(__METHOD__." - Anno di riferimento non impostato correttamente (".$anno.").", 100,false,true);
            return false;
        }

        //Verifica tipo
        if($tipo=="" || $tipo== "0")
        {
            AA_Log::Log(__METHOD__." - Occorre indicare il tipo di documento (".$tipo.").", 100,false,true);
            return false;
        }

        //Verifica il file 
        if(!file_exists($file) || $file=="")
        {
            AA_Log::Log(__METHOD__." - file da caricare non trovato (".$file.").", 100,false,true);
            return false;
        }

        //permessi utente
        $perms=$nomina->GetUserCaps($user);
        if(($perms & AA_Const::AA_PERMS_WRITE) == 0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente (".$user->GetNome()." ".$user->GetCognome().") non può modificare gli incarichi di: ".$nomina->GetNome()." ".$nomina->GetCognome()." (".$nomina->GetId().")", 100,false,true);
            return false;
        }

        //Verifica se esiste già un file
        //$oldDoc=new AA_OrganismiNomineDocument($nomina->GetId(),$anno,$tipo,"",$user);
        //if($oldDoc->IsValid())
        //{
        //    AA_Log::Log(__METHOD__." - Documento già presente e sovrascrittura disabilitata.", 100,false,true);
        //    return false;
        //}

        $serial=time();
        $filename=AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_NOMINE_DOCS_PATH."/".$nomina->GetId()."_".$anno."_".$tipo."_".$serial.".pdf";

        if(($perms & AA_Const::AA_PERMS_WRITE) > 0)
        {
            if(is_uploaded_file($file))
            {
                if(!move_uploaded_file($file,$filename))
                {
                    AA_Log::Log(__METHOD__." - Errore durante il salvataggio del file: ".$filename, 100,false,true);
                    return false;
                }
                else return true;                
            }
            else 
            {
                if(!rename($file,$filename))
                {
                    AA_Log::Log(__METHOD__." - Errore durante il salvataggio del file: ".$filename, 100,false,true);
                    return false;
                }
                else return true; 
            }
        }
        else
        {
            AA_Log::Log(__METHOD__." - l'utente corrente (".$user->GetNome()." ".$user->GetCognome().") non può caricare documenti per la nomina: ".$nomina->GetNome()." ".$nomina->GetCognome()." (".$nomina->GetId().")", 100,false,true);
            return false;
        }
    }
    
    //Restituisce un oggetto collegato al documento
    static public function GetDoc($nomina=null,$anno="",$tipo=0,$serial="0",$user=null)
    {
        if($nomina instanceof AA_OrganismiNomine)
        {
            //AA_Log::Log(__METHOD__." - Carico tramite nomina",100);
            if($nomina->IsValid()) return new AA_OrganismiNomineDocument($nomina->GetId(),$anno,$tipo,$serial,$user);
        }

        //Tenta il caricamento tramite id
        $nomina=new AA_OrganismiNomine($nomina,null,$user);
        if($nomina instanceof AA_OrganismiNomine)
        {
            //AA_Log::Log(__METHOD__." - Carico tramite id nomina",100);
            if($nomina->IsValid()) return new AA_OrganismiNomineDocument($nomina->GetId(),$anno,$tipo,$serial,$user);
        }

        return null;
    }

    //Restituisce un array con tutti i documenti collegati ad una nomina che soddisfano determinati criteri
    static public function GetAllDocs($nomina=null,$anno="",$tipo=0,$serial="",$user)
    {
        if(!($nomina instanceof AA_OrganismiNomine))
        {
            $nomina = new AA_OrganismiNomine($nomina,$user);
        }

        //verifica la nomina
        if(!$nomina->IsValid())
        {
            AA_Log::Log(__METHOD__." - Nomina non valida.", 100,false,true);
            return array();
        }

        $path=AA_Const::AA_UPLOADS_PATH.AA_Organismi_Const::AA_ORGANISMI_NOMINE_DOCS_PATH."/".$nomina->GetId()."_*.pdf";
        $return=array();

        foreach(glob($path) as $key=>$value)
        {
            $curPath=explode("/",$value);
            $file=array_pop($curPath);
            $fileParts=explode("_",$file);

            $curAnno=$fileParts[1];
            $curTipo=$fileParts[2];
            $curSerial=substr($fileParts[3],0,-4);

            //AA_Log::Log(__METHOD__." - ".print_r($fileParts,true),100);

            if(($anno != "" && $curAnno == $anno) || $anno =="")
            {
                //AA_Log::Log(__METHOD__." - anno: ".$anno,100);
                if(($tipo > 0 && $tipo == $curTipo) || $tipo == 0)
                {
                    //AA_Log::Log(__METHOD__." - tipo: ".$tipo,100);
                    if(($serial !="" && $serial == $curSerial) || $serial=="")
                    {
                        //AA_Log::Log(__METHOD__." - inserisco il doc: ".print_r($fileParts,true),100);
                        $doc=new AA_OrganismiNomineDocument($nomina->GetId(),$curAnno,$curTipo,$curSerial,$user);
                        if($doc->IsValid())
                        {
                            $return[]=$doc;
                        }    
                    }
                }
            }
        }

        //AA_Log::Log(__METHOD__." - ".print_r($return,true),100);
        
        return $return;
    }
}

#Classe template per la gestione dalla lista delle nomine sull'item organismo
Class AA_OrganismiNomineListTemplateView extends AA_GenericTableTemplateView
{
    public function __construct($id="AA_OrganismiNomineListTemplateView",$parent=null,$organismo=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo);
        
        $canModify=false;
        if(($organismo->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;

        //Aggiunge il pulsante di aggiunta nomina
        if($canModify)
        {
            //Contenitore pulsante
            $div=new AA_XML_Div_Element("",null);
            $div->SetStyle("width:99%; margin-bottom: 1em;");
            
            //Pulsante di aggiunta
            $a = new AA_XML_A_Element("", $div);
            $a->SetClass("AA_Button_AddNew_Nomina");
            $a->SetStyle("cursor: pointer");
            $a->SetAttribs(array("id-object"=>$organismo->GetId(),"task"=>"add-new-nomina-dlg","title"=>"Aggiungi nuova nomina"));

            $a->SetText("Aggiungi nuova nomina");

            $this->InsertChild($div);
        } 

        $nomine=$organismo->GetNomine();
        if(sizeof($nomine)>0)
        {
            if($canModify)
            {
                $this->SetColSizes(array("6","6","11","8","7","7","8","8","20","8"));
                $this->SetHeaderLabels(array("Nome<sup>1</sup>","Cognome<sup>1</sup>","Codice fiscale", "Incarico","Data inizio", "Data fine", "Compenso spettante<sup>2</sup>", "Documenti", "Note", "Operazioni"));    
            }
            else
            {
                $this->SetColSizes(array("7","7","12","8", "8","8","8","8", "25"));
                $this->SetHeaderLabels(array("Nome<sup>1</sup>","Cognome<sup>1</sup>","Codice fiscale", "Incarico","Data inizio", "Data fine", "Compenso spettante<sup>2</sup>", "Documenti", "Note"));    
            }

            $curRow=1;
            foreach($nomine as $id=>$curNomina)
            {
                $color="";
                $grassetto="";
                if($curNomina->IsNominaRas())
                {
                    $color="DarkGreen";
                    $grassetto=1;
                } 
                
                //Nome
                $this->SetCellText($curRow,0,$curNomina->GetNome(), "center",$color,$grassetto);

                //Cognome
                $this->SetCellText($curRow,1,$curNomina->GetCognome(), "center",$color,$grassetto);

                //Codice fiscale
                $curVal=$curNomina->GetCodiceFiscale();
                if($curVal=="") $curVal="n.d.";
                $this->SetCellText($curRow,2,$curVal, "center");

                //tipo nomina
                $this->SetCellText($curRow,3,$curNomina->GetTipologia(),"center");

                //Data inizio
                $this->SetCellText($curRow,4,$curNomina->GetDataInizio(), "center");

                //Data fine
                $dataFine=$curNomina->GetDataFine();
                $curDate=date("Y-m-d");
                if($dataFine < $curDate)
                {
                    $color="red";
                }
                else $color="";
                if($dataFine=="9999-12-31") $dataFine="a tempo indeterminato";
                $this->SetCellText($curRow,5,$curNomina->GetDataFine(), "center", $color);

                //compenso spettante
                $curVal="€ ".preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "", $curNomina->GetCompensoSpettante());
                if($curVal=="€ ") $curVal="n.d.";
                $this->SetCellText($curRow,6,$curVal, "center");
                
                //Documenti------------------
                $box=$this->GetCell($curRow,7);
                $box->SetStyle("text-align: center", true);

                if(sizeof($curNomina->GetDocs()) > 0)
                {
                    //vedi i documenti
                    $vedi=new AA_XML_A_Element("",$box); $vedi->SetClass("AA_Button_Nomina_View_Docs ui-icon ui-icon-folder-open"); $vedi->SetAttribs(array("id-object"=>$organismo->GetId(),"id-nomina"=>$curNomina->GetId(),"title"=>"Gestisci i documenti"));
                    $vedi->SetStyle("display: inline-block; margin-right: 1em; cursor: pointer");
                    $vedi->SetText("Gestisci i documenti");
                    //---------------------------
                }
                else
                {
                    $this->SetCellText($curRow,7,"n.d.", "center");
                }

                //Note
                $note=$this->GetCell($curRow,8);
                $note->SetStyle("font-size: smaller;", true);
                $text_align="center";
                if(strlen($curNomina->GetNote()) > 75) $text_align="left";
                $this->SetCellText($curRow,8,$curNomina->GetNote(), $text_align);

                //Operazioni
                if($canModify)
                {
                    //$operazioni=new AA_XML_Div_Element("",$curRiga);$operazioni->SetStyle("width: 10%; text-align: center");
                    $operazioni=$this->GetCell($curRow,9);
                    $operazioni->SetStyle("text-align: center; cursor: pointer", true);
                    
                    //gestisci i documenti
                    $vedi=new AA_XML_A_Element("",$operazioni); $vedi->SetClass("AA_Button_Nomina_View_Docs ui-icon ui-icon-folder-open"); $vedi->SetAttribs(array("id-object"=>$organismo->GetId(),"id-nomina"=>$curNomina->GetId(),"title"=>"Gestisci i documenti"));
                    $vedi->SetStyle("display: inline-block; margin-right: 1em; cursor: pointer");
                    $vedi->SetText("Gestisci i documenti");
                    //---------------------------

                    //Modifica
                    $edit=new AA_XML_A_Element("",$operazioni); $edit->SetClass("AA_Button_Edit_Nomina ui-icon ui-icon-pencil"); $edit->SetAttribs(array("id-object"=>$organismo->GetId(),"id-nomina"=>$curNomina->GetId(),"title"=>"Modifica nomina"));
                    $edit->SetStyle("display: inline-block; margin-right: 1em; cursor: pointer");
                    $edit->SetText("Modifica nomina");

                    //elimina
                    $trash=new AA_XML_A_Element("",$operazioni); $trash->SetClass("AA_Button_Trash_Nomina ui-icon ui-icon-trash"); $trash->SetAttribs(array("id-object"=>$organismo->GetId(),"id-nomina"=>$curNomina->GetId(),"title"=>"Elimina nomina"));
                    $trash->SetStyle("display: inline-block; cursor: pointer");
                    $trash->SetText("elimina nomina");
                }

                $curRow++;
            }

            $footer="<div style='font-style: italic; text-align: left; width: 100%; margin-top: 1em;font-size: smaller;'>1. I nominativi riportati in grassetto verde indicano che l'incarico corrispondente è stato conferito dalla Regione Autonoma della Sardegna.</div>";
            $footer.="<div style='font-style: italic; text-align: left; width: 100%; margin-top: .3em;font-size: smaller;'>2. Qualora la durata dell'incarico sia pluriennale o non venga specificato diversamente nelle note, l'importo relativo al trattamento economico complessivo spettante è da intendersi su base annua.</div>";

            $this->SetText($footer,false);
        }
    }
}

//form di aggiunta nomina
Class AA_OrganismiNomineAddNewTemplateView extends AA_GenericFormTemplateView
{
    public function __construct($id="AA_OrganismiNomineAddNewTemplateView",$parent=null,$obj=null)
    {
        parent::__construct($id,$parent,$obj);

        if($obj instanceof AA_Organismi)
        {
            $now=date("Y-m-d");
            $tipo_nomine=AA_Organismi_Const::GetTipoNomine();
            $tipo_nomine[0]="Scegliere...";
            
            $this->AddCheckBoxInput("Nomina RAS","bNominaRas",1,false,"Abilitare in caso di incarico conferito dalla RAS");
            $this->AddTextInput("Nome","sNome");
            $this->AddTextInput("Cognome","sCognome");
            $this->AddTextInput("Codice fiscale","sCodiceFiscale");
            $this->AddSelectInput("Incarico","nTipologia",0,$tipo_nomine);
            $this->AddDateInput("Data inizio","sDataInizio",$now);
            $this->AddDateInput("Data fine","sDataFine","");
            $this->AddTextInput("Compenso spettante in €","sCompensoSpettante");
            $this->AddTextareaInput("Note","sNote");

            $this->AddHiddenField("nIdParent",$obj->GetId());
        }
    }
}

//Task per il dialogo per l'aggiunta di una nuova nomina
Class AA_OrganismiTask_AddNewNominaDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("add-new-nomina-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $organismo=new AA_Organismi($_REQUEST['id'],$this->oUser);

        #Dialog contents
        $this->sTaskLog.='<p class="validateTips"> </p>';
        $template=new AA_OrganismiNomineAddNewTemplateView("AA_OrganismiNomineAddNewTemplateView_".$organismo->GetId(),null,$organismo);
        
        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per l'aggiunta di una nuova nomina
Class AA_OrganismiTask_AddNewNomina extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("add-new-nomina", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $parent=AA_Organismi::Load($_REQUEST["nIdParent"],$this->oUser);

        if(!($parent->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $new_obj=AA_OrganismiNomine::AddNewToDb($_REQUEST,$parent,$this->oUser);
        if(!($new_obj instanceof AA_OrganismiNomine))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        else
        {
            $this->sTaskLog="<status id='status' idRec='".Database::LastInsertId()."'>0</status><error id='error'>Nomina inserita con successo.</error>";
            return true;
        }

        return false;
    }
}
//---------------------------------------------------------

//form di modifica nomina
Class AA_OrganismiNomineEditTemplateView extends AA_GenericFormTemplateView
{
    public function __construct($id="AA_OrganismiNomineEditTemplateView",$parent=null,$obj=null)
    {
        parent::__construct($id,$parent,$obj);

        if($obj instanceof AA_OrganismiNomine)
        {
            $tipo_nomine=AA_Organismi_Const::GetTipoNomine();
            $tipo_nomine[0]="Scegliere...";
            
            $this->AddCheckBoxInput("Nomina RAS","bNominaRas",1,$obj->IsNominaRas(),"Abilitare in caso di incarico conferito dalla RAS");
            $this->AddTextInput("Nome","sNome",$obj->GetNome());
            $this->AddTextInput("Cognome","sCognome",$obj->GetCognome());
            $this->AddTextInput("Codice fiscale","sCodiceFiscale",$obj->GetCodiceFiscale());
            $this->AddSelectInput("Incarico","nTipologia",$obj->GetTipologia(true),$tipo_nomine);
            $this->AddDateInput("Data inizio","sDataInizio",$obj->GetDataInizio());
            $this->AddDateInput("Data fine","sDataFine",$obj->GetDataFine());
            $this->AddTextInput("Compenso spettante in €","sCompensoSpettante",$obj->GetCompensoSpettante());
            $this->AddTextareaInput("Note","sNote",$obj->GetNote());

            $this->AddHiddenField("nIdParent",$obj->GetParent()->GetId());
            $this->AddHiddenField("id",$obj->GetId());
        }
    }
}

//Task per il dialogo per la modifica di una nomina esistente
Class AA_OrganismiTask_EditNominaDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("edit-nomina-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $nomina=new AA_OrganismiNomine($_REQUEST['id'],null,$this->oUser);

        if(!($nomina->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>Nomina non valida o non trovato - ".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        #Dialog contents
        $this->sTaskLog.='<p class="validateTips"> </p>';
        $template=new AA_OrganismiNomineEditTemplateView("AA_OrganismiNomineEditTemplateView_".$_REQUEST['id'],null,$nomina);
        
        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per la modifica di una nomina
Class AA_OrganismiTask_EditNomina extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("edit-nomina", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $obj=new AA_OrganismiNomine($_REQUEST["id"],null,$this->oUser);

        if(!($obj->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>Nomina non trovata o non valido - ".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $organismo=$obj->GetParent($this->oUser);
        if(!($organismo instanceof AA_Organismi) || !$organismo->IsValid())
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }
        
        if(!$obj->ParseData($_REQUEST,$this->oUser))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        if(!$obj->UpdateDb(null,$this->oUser,true,"Aggiornamento nomina: ".$obj->GetNome()." ".$obj->GetCognome()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $this->sTaskLog="<status id='status' idRec='".$obj->GetID()."'>0</status><error id='error'>Nomina aggiornata con successo.</error>";
        return true;
    }
}
//---------------------------------------------------------

//form di eliminazione della nomina
Class AA_OrganismiNomineTrashTemplateView extends AA_GenericFormTemplateView
{
    public function __construct($id="AA_OrganismiNomineTrashTemplateView",$parent=null,$obj=null)
    {
        parent::__construct($id,$parent,$obj);

        if($obj instanceof AA_OrganismiNomine)
        {
            $this->SetText("<p style='text-align: center'>Vuoi eliminare la nomina di ".$obj->GetNome()." ".$obj->GetCognome()." nell'organismo: ".$obj->GetParent()->GetDenominazione()."?</p><br/><p style='text-align: center'>Questa operazione non può essere annullata</p>");
            $this->AddHiddenField("nIdParent",$obj->GetParent()->GetId());
            $this->AddHiddenField("id",$obj->GetId());
        }
    }
}
//----------------------------------------------------------

//Task per il dialogo per l'eliminazione di una nomina esistente
Class AA_OrganismiTask_TrashNominaDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("trash-nomina-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $obj=new AA_OrganismiNomine($_REQUEST["id"],null,$this->oUser);

        if(!($obj->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        if(($obj->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) == 0)
        {
            $this->sTaskError="L'utente: ".$this->oUser->GetNome()." non ha i permessi per cestinare/eliminare la nomina.";
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".$this->sTaskError."</error>";
        }

        #Dialog contents
        $template=new AA_OrganismiNomineTrashTemplateView("",null,$obj);
        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per l'eliminazione di una nomina
Class AA_OrganismiTask_TrashNomina extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("trash-nomina", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $obj=new AA_OrganismiNomine($_REQUEST["id"],null,$this->oUser);

        if(!($obj->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        if(!$obj->Trash($this->oUser,true))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $this->sTaskLog="<status id='status' idRec='".$obj->GetID()."'>0</status><error id='error'>Nomina eliminata con successo.</error>";
        return true;
    }
}
//---------------------------------------------------------

#Classe template per la gestione dalla lista dei documenti sull'item nomina 
Class AA_OrganismiNomineDocumentListTemplateView extends AA_GenericTableTemplateView
{
    public function __construct($id="AA_OrganismiNomineDocumentListTemplateView",$parent=null,$nomina=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($nomina instanceof AA_OrganismiNomine))
        {
            AA_Log::Log(__METHOD__." - nomina non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$nomina);
        
        $canModify=false;
        if(($nomina->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;

        //Aggiunge il pulsante di aggiunta documento
        if($canModify)
        {
            //Contenitore pulsante
            $div=new AA_XML_Div_Element("",null);
            $div->SetStyle("width:99%; margin-bottom: 1em;");
            
            //Pulsante di aggiunta
            $a = new AA_XML_A_Element("", $div);
            $a->SetClass("AA_Button_Nomina_Upload_Doc");
            $a->SetStyle("cursor: pointer");
            $a->SetAttribs(array("id-nomina"=>$nomina->GetId(),"task"=>"upload-doc-nomina-dlg","title"=>"Aggiungi nuovo documento"));
            $a->SetText("Aggiungi nuovo documento");

            $this->InsertChild($div);
        } 

        $docs=$nomina->GetDocs();
        if(sizeof($docs)>0)
        {
          
            $this->SetColSizes(array("20","55","20"));
            $this->SetHeaderLabels(array("Anno","Tipo","Operazioni"));    
          
            $curRow=1;
            foreach($docs as $id=>$curDoc)
            {
                //Anno
                $this->SetCellText($curRow,0,$curDoc->GetAnno(), "center");

                //tipo
                $this->SetCellText($curRow,1,$curDoc->GetTipologia(), "center");

                //Operazioni
                $operazioni=$this->GetCell($curRow,2);
                $operazioni->SetStyle("text-align: center", true);

                //Scarica
                $edit=new AA_XML_A_Element("",$operazioni); $edit->SetClass("AA_Button_Download_Doc_Nomina ui-icon ui-icon-disk"); $edit->SetAttribs(array("href"=>$curDoc->GetPublicDocumentPath(),"target"=>"_blank", "id-nomina"=>$nomina->GetId(),"anno"=>$curDoc->GetAnno(),"tipo"=>$curDoc->GetTipologia(true),"title"=>"Scarica il documento"));
                $edit->SetStyle("display: inline-block; margin-right: 1em; cursor: pointer");
                $edit->SetText("Scarica documento");
                                
                if($canModify)
                {
                    //elimina
                    $trash=new AA_XML_A_Element("",$operazioni); $trash->SetClass("AA_Button_Trash_Doc_Nomina ui-icon ui-icon-trash"); $trash->SetAttribs(array("id-nomina"=>$nomina->GetId(),"anno"=>$curDoc->GetAnno(),"tipo"=>$curDoc->GetTipologia(true),"title"=>"Elimina documento"));
                    $trash->SetStyle("display: inline-block; cursor: pointer");
                    $trash->SetText("elimina documento");
                }

                $curRow++;
            }
        }

        //Imposta il genitore
        if($parent instanceof AA_XML_Element_Generic) $parent->AppendChild($this);
    }
}

//Task per la visualizzazione della lista dei documenti di una nomina
Class AA_OrganismiTask_ViewDocsNominaDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("view-docs-nomina", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $nomina=new AA_OrganismiNomine($_REQUEST["id"],null,$this->oUser);

        if(!($nomina->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        #Dialog contents
        $template=new AA_OrganismiNomineDocumentListTemplateView("AA_OrganismiNomineDocumentListTemplateView_".$nomina->GetId(),null,$nomina);
        
        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//form di aggiunta documento nomina
Class AA_OrganismiNomineUploadDocumentTemplateView extends AA_GenericFormTemplateView
{
    public function __construct($id="AA_OrganismiNomineUploadDocumentTemplateView",$parent=null,$obj=null)
    {
        parent::__construct($id,$parent,$obj);

        if($obj instanceof AA_OrganismiNomine)
        {
            $tipo_doc=AA_Organismi_Const::GetTipoDocs();
            $tipo_doc[0]="Scegliere...";
            
            $anno_array=array();
            for($i=date("Y");$i > 2013;$i--)
            {
                $anno_array[$i]=$i;
            }

            //Anno
            $this->AddSelectInput("Anno","anno",date("Y"),$anno_array);

            //Tipo
            $this->AddSelectInput("Tipo di documento","tipo",0,$tipo_doc);

            //file
            $this->AddFileInput("Documento");

            //Id nomina
            $this->AddHiddenField("id-nomina",$obj->GetId());
        }
    }
}

//Task per il dialogo per l'upload di un nuovo documento di una nomina
Class AA_OrganismiTask_UploadDocNominaDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("upload-doc-nomina-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $nomina=new AA_OrganismiNomine($_REQUEST['id'],null,$this->oUser);

        if(!($nomina->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>Nomina non valida o non trovata - ".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        #Dialog contents
        $this->sTaskLog.='<p class="validateTips"> </p>';
        $template=new AA_OrganismiNomineUploadDocumentTemplateView("AA_OrganismiNomineUploadDocumentTemplateView_".$_REQUEST['id'],null,$nomina);
        
        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per l'aggiunta di una nuova nomina
Class AA_OrganismiTask_UploadDocNomina extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("upload-doc-nomina", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $parent=new AA_OrganismiNomine($_REQUEST["id-nomina"],null,$this->oUser);

        if(!($parent->IsValid()))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        if(!(AA_OrganismiNomineDocument::UploadDoc($parent,$_REQUEST['anno'],$_REQUEST['tipo'],$_FILES['file-upload']['tmp_name'],false,$this->oUser)))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $this->sTaskLog="<status id='status'>0</status><error id='error'>Documento caricato con successo.</error>";
        return true;
    }
}
//---------------------------------------------------------

//form di eliminazione di un documento di una nomina
Class AA_OrganismiNomineTrashDocTemplateView extends AA_GenericFormTemplateView
{
    public function __construct($id="AA_OrganismiNomineTrashDocTemplateView",$parent=null,$obj=null)
    {
        parent::__construct($id,$parent,$obj);

        if($obj instanceof AA_OrganismiNomineDocument)
        {
            $nomina=$obj->GetNomina();

            $this->SetText("<p style='text-align: center'>Vuoi eliminare il documento: ".$obj->GetTipologia()." della nomina: ".$nomina->GetNome()." ".$nomina->GetCognome().", per l'anno: ".$obj->GetAnno()."?</p><br/><p style='text-align: center'>Questa operazione non può essere annullata</p>");
            $this->AddHiddenField("id-nomina",$nomina->GetId());
            $this->AddHiddenField("anno",$obj->GetAnno());
            $this->AddHiddenField("tipo",$obj->GetTipologia(true));
        }
    }
}
//-----------------------------------------

//Task per il dialogo per l'eliminazione di un documento esistente
Class AA_OrganismiTask_TrashDocNominaDlg extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("trash-doc-nomina-dlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $this->sTaskLog="<status id='status'>0</status><params id='params'></params><content id='content'>";

        $doc=AA_OrganismiNomineDocument::GetDoc($_REQUEST["id-nomina"],$_REQUEST['anno'],$_REQUEST['tipo'],$this->oUser);

        if(!($doc instanceof AA_OrganismiNomineDocument))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $nomina=$doc->GetNomina();
        if(($nomina->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) == 0)
        {
            $this->sTaskError="L'utente: ".$this->oUser->GetNome()." non ha i permessi per cestinare/eliminare il documento.";
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".$this->sTaskError."</error>";
        }

        #Dialog contents
        $template=new AA_OrganismiNomineTrashDocTemplateView("",null,$doc);
        $this->sTaskLog.=$template;
        #-------------------------
        $this->sTaskLog.="</content>";

        return true;
    }
}
//---------------------------------------------------------

//Task per l'eliminazione di un documento nomina
Class AA_OrganismiTask_TrashDocNomina extends AA_GenericTask
{
    public function __construct($user=null)
    {
        parent::__construct("trash-nomina", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__."() - task: "+$this->GetName());

        $obj=AA_OrganismiNomineDocument::GetDoc($_REQUEST["id-nomina"],$_REQUEST["anno"],$_REQUEST["tipo"],$this->oUser);

        if(!($obj instanceof AA_OrganismiNomineDocument))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        if(!$obj->Delete($this->oUser))
        {
            $this->sTaskError=AA_Log::$lastErrorLog;
            $this->sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            return false;
        }

        $this->sTaskLog="<status id='status'>0</status><error id='error'>Documento eliminato con successo.</error>";
        return true;
    }
}
//---------------------------------------------------------

//Rappresentazione xml
class AA_XML_FEED_ORGANISMO extends AA_XML_FEED
{
    //Organismo collegato
    private $obj=null;

    public function __construct($obj=null)
    {
        $this->id="AA_XML_FEED_ORGANISMO";

        if($obj instanceof AA_Organismi)
        {
            $this->obj=$obj;
        }
    }

    public function toXml()
    {
        if($this->obj instanceof AA_Object) $this->SetContent($this->obj->toXml());

        return parent::toXml();
    }
}
//-------------------------------------------------

//Rappresentazione xml di un set di organismi
class AA_XML_FEED_ORGANISMI extends AA_XML_FEED
{
    //Organismo collegato
    private $params=array();

    public function __construct($params=null)
    {
        $this->id="AA_XML_FEED_ORGANISMI";

        if(is_array($params))
        {
            $this->params=$params;
        }
    }

    public function toXml()
    {

        return parent::toXml();
    }
}
//-------------------------------------------------

//XML feed per le pubblicazioni ex art. 22
class AA_XML_FEED_ART22 extends AA_XML_FEED
{
  function __construct()
  {
    $this->id="AA_XML_FEED_ART22";
    $this->sTimestamp=date("Y-m-d H:i:s");
  }
}

#Classe template per la gestione del report pdf dell'organismo
Class AA_OrganismiPublicReportTemplateView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_OrganismiPublicReportTemplateView",$parent=null,$organismo=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo);
        
        $this->SetStyle("width: 99%; display:flex; flex-direction: column; align-items: center;");

        #Parte generale---------------------------------
        $generale=new AA_XML_Div_Element("AA_OrganismiPublicReportTemplateView-generale",$this);
        $generale->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; width: 100%");

        #Denominazione----------------------------------
        $denominazione=new AA_XML_Div_Element("generale-tab-denominazione",$generale);
        $denominazione->SetStyle('width:100%; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .2em; font-size: 20px; font-weight: bold');
        $denominazione->SetText($organismo->GetDenominazione()."<br><span style='font-size: x-small; font-weight: normal'>".$organismo->GetTipologia()."</span>");
        #-----------------------------------------------

        //left panel-------
        $left_panel= new AA_XML_Div_Element("generale-tab-left-panel",$generale);
        $left_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; width:49.9%");

        //Piva
        $val=$organismo->GetPivaCf();
        if($val=="") $val="n.d.";
        $piva=new AA_XML_Div_Element("piva",$left_panel);
        $piva->SetStyle("width: 100%; margin-bottom: .8em");
        $piva->SetText('<span style="font-weight:bold">Partita Iva/Codice fiscale:</span><br/>'.$val);

        //Sede legale
        $val=$organismo->GetSedeLegale();
        if($val=="") $val="n.d.";
        $sede_legale = new AA_XML_Div_Element("sede",$left_panel);
        $sede_legale->SetStyle("width: 100%; margin-bottom: .8em");
        $sede_legale->SetText('<span style="font-weight:bold">Sede legale:</span>'."<br/>".$val);
        
        //Pec
        $val=$organismo->GetPec();
        if($val=="") $val="n.d.";
        $pec = new AA_XML_Div_Element("pec",$left_panel);
        $pec->SetStyle("width: 100%; margin-bottom: .8em");
        $pec->SetText('<span style="font-weight:bold">PEC:</span>'."<br/>".$val); 

        //Sito web
        $val=$organismo->GetSitoWeb();
        if($val=="") $val="n.d.";
        $sito_web = new AA_XML_Div_Element("sito_web",$left_panel);
        $sito_web->SetStyle("width: 100%; margin-bottom: .8em");
        $sito_web->SetText('<span style="font-weight:bold">Sito web:</span><br/><a href="'.$val.'" target="_blank">'.$val."</a>");
        #-------------------

        //right panel ------
        $right_panel= new AA_XML_Div_Element("generale-tab-right-panel",$generale);
        $right_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; width:49.9%");
        
        if($organismo->GetTipologia(true) == AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
        {
            //Data inizio impegno
            $val=$organismo->GetDataInizioImpegno();
            if($val=="0000-00-00") $val="n.d.";
            $data_inizio = new AA_XML_Div_Element("generale-tab-right-panel-data_inizio",$right_panel);
            $data_inizio->SetStyle("width:100%; margin-bottom: .8em");
            $data_inizio->SetText('<span style="font-weight:bold">Data inizio impegno:</span><br/>'.$val);

            //Data fine impegno
            $val=$organismo->GetDataFineImpegno();
            if($val=="0000-00-00") $val="n.d.";
            if(trim($val)=="9999-12-31") $val="a tempo indeterminato";
            $data_fine = new AA_XML_Div_Element("generale-tab-right-panel-data_fine",$right_panel);
            $data_fine->SetStyle("width:100%; margin-bottom: .8em");
            $data_fine->SetText('<span style="font-weight:bold">Data fine impegno:</span><br/>'.$val);

            //partecipazione
            $val=$organismo->GetPartecipazione(true);
            if($val['percentuale']==0 && $val['euro']=="0") $val="nessuna";
            else
            {
                $val="€ ".AA_Utils::number_format($val['euro'],2,",",".")." pari al ".AA_Utils::number_format($val['percentuale'],2,",",".")."% delle quote totali";
            }
            $partecipazione= new AA_XML_Div_Element("generale-right-panel-partecipazione",$right_panel);
            $partecipazione->SetStyle("width:100%; margin-bottom: .8em");
            $partecipazione->SetText('<span style="font-weight:bold">Partecipazione diretta RAS:</span><br/>'.$val);
        }
        else
        {
            //Data inizio impegno
            $val=$organismo->GetDataInizioImpegno();
            if($val=="0000-00-00") $val="n.d.";
            $data_inizio = new AA_XML_Div_Element("generale-tab-right-panel-data_inizio",$right_panel);
            $data_inizio->SetStyle("width:100%; margin-bottom: .8em");
            $data_inizio->SetText('<span style="font-weight:bold">Data costituzione:</span><br/>'.$val);

            //Data fine impegno
            $val=$organismo->GetDataFineImpegno();
            if($val=="0000-00-00") $val="n.d.";
            if(trim($val)=="9999-12-31") $val="a tempo indeterminato";
            $data_fine = new AA_XML_Div_Element("generale-tab-right-panel-data_fine",$right_panel);
            $data_fine->SetStyle("width:100%; margin-bottom: .8em");
            $data_fine->SetText('<span style="font-weight:bold">Data cessazione:</span><br/>'.$val);
        }

        //Funzioni attribuite
        $val=$organismo->GetFunzioni();
        if($val=="") $val="n.d.";
        $funzioni = new AA_XML_Div_Element("funzioni",$generale);
        $funzioni->SetStyle("width: 100%; margin-bottom: .8em; border-top: 1px solid gray; text-align: left;");
        $funzioni->SetText('<span style="font-weight:bold">Funzioni attribuite:</span><br>'.$val);

        //note
        $note=new AA_XML_Div_Element("generale-tab-note",$generale);
        $note->SetStyle('width:100%; border-top: 1px solid gray; margin-bottom: .8em;text-align: left;');
        $note->SetText(nl2br($organismo->GetNote()));
        #-------------------

        //Aggiunge i dati contabili
        $bilanci=new AA_OrganismiReportDatiContabiliListTemplateView("AA_OrganismiReportDatiContabiliListTemplateView",null,$organismo, $user);
        $this->AppendChild($bilanci);

        //Aggiunge le nomine
        $nomine=new AA_OrganismiReportNomineListTemplateView("AA_OrganismiPublicReportTemplateView-nomine",null,$organismo, $user);        
        $this->AppendChild($nomine);

        //legenda
        $footer="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%; margin-bottom: 2em'>Nel presente prospetto sono esposti i dati dei rappresentanti designati dall'Amministrazione regionale negli organi di governo dell’ente e quelli relativi ai titolari di incarichi di amministratore dell'ente, siano essi nominati dalla Regione o meno.</span></div>";
        $footer.="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$organismo->GetAggiornamento()."</span></div>";
        $this->SetText($footer,false);
    }
}

#Classe template per la gestione del report pdf dell'organismo (pagina generale)
Class AA_OrganismiPublicReportTemplateGeneralPageView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_OrganismiPublicReportTemplateGeneralPageView",$parent=null,$organismo=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo);
        
        $this->SetStyle("width: 99%; display:flex; flex-direction: column; align-items: center;");

        #Parte generale---------------------------------
        $generale=new AA_XML_Div_Element("AA_OrganismiPublicReportTemplateView-generale",$this);
        $generale->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; width: 100%");

        #Denominazione----------------------------------
        $denominazione=new AA_XML_Div_Element("generale-tab-denominazione",$generale);
        $denominazione->SetStyle('width:100%; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .2em; font-size: 20px; font-weight: bold');
        $denominazione->SetText($organismo->GetDenominazione()."<br><span style='font-size: x-small; font-weight: normal'>".$organismo->GetTipologia()."</span>");
        #-----------------------------------------------

        //left panel-------
        $left_panel= new AA_XML_Div_Element("generale-tab-left-panel",$generale);
        $left_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; width:49.9%");

        //Piva
        $val=$organismo->GetPivaCf();
        if($val=="") $val="n.d.";
        $piva=new AA_XML_Div_Element("piva",$left_panel);
        $piva->SetStyle("width: 100%; margin-bottom: .8em");
        $piva->SetText('<span style="font-weight:bold">Partita Iva/Codice fiscale:</span><br/>'.$val);

        //Sede legale
        $val=$organismo->GetSedeLegale();
        if($val=="") $val="n.d.";
        $sede_legale = new AA_XML_Div_Element("sede",$left_panel);
        $sede_legale->SetStyle("width: 100%; margin-bottom: .8em");
        $sede_legale->SetText('<span style="font-weight:bold">Sede legale:</span>'."<br/>".$val);
        
        //Pec
        $val=$organismo->GetPec();
        if($val=="") $val="n.d.";
        $pec = new AA_XML_Div_Element("pec",$left_panel);
        $pec->SetStyle("width: 100%; margin-bottom: .8em");
        $pec->SetText('<span style="font-weight:bold">PEC:</span>'."<br/>".$val); 

        //Sito web
        $val=$organismo->GetSitoWeb();
        if($val=="") $val="n.d.";
        $sito_web = new AA_XML_Div_Element("sito_web",$left_panel);
        $sito_web->SetStyle("width: 100%; margin-bottom: .8em");
        $sito_web->SetText('<span style="font-weight:bold">Sito web:</span><br/><a href="'.$val.'" target="_blank">'.$val."</a>");
        #-------------------

        //right panel ------
        $right_panel= new AA_XML_Div_Element("generale-tab-right-panel",$generale);
        $right_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; width:49.9%");
        
        if($organismo->GetTipologia(true) == AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
        {
            //Data inizio impegno
            $val=$organismo->GetDataInizioImpegno();
            if($val=="0000-00-00") $val="n.d.";
            $data_inizio = new AA_XML_Div_Element("generale-tab-right-panel-data_inizio",$right_panel);
            $data_inizio->SetStyle("width:100%; margin-bottom: .8em");
            $data_inizio->SetText('<span style="font-weight:bold">Data inizio impegno:</span><br/>'.$val);

            //Data fine impegno
            $val=$organismo->GetDataFineImpegno();
            if($val=="0000-00-00") $val="n.d.";
            if(trim($val)=="9999-12-31") $val="a tempo indeterminato";
            $data_fine = new AA_XML_Div_Element("generale-tab-right-panel-data_fine",$right_panel);
            $data_fine->SetStyle("width:100%; margin-bottom: .8em");
            $data_fine->SetText('<span style="font-weight:bold">Data fine impegno:</span><br/>'.$val);

            //partecipazione
            $val=$organismo->GetPartecipazione(true);
            if($val['percentuale']==0 && $val['euro']=="0") $val="indiretta";
            else
            {
                $val="€ ".AA_Utils::number_format($val['euro'],2,",",".")." pari al ".AA_Utils::number_format($val['percentuale'],2,",",".")."% delle quote totali";
            }
            $partecipazione= new AA_XML_Div_Element("generale-right-panel-partecipazione",$right_panel);
            $partecipazione->SetStyle("width:100%; margin-bottom: .8em");
            $partecipazione->SetText('<span style="font-weight:bold">Partecipazione:</span><br/>'.$val);
        }
        else
        {
            //Data inizio impegno
            $val=$organismo->GetDataInizioImpegno();
            if($val=="0000-00-00") $val="n.d.";
            $data_inizio = new AA_XML_Div_Element("generale-tab-right-panel-data_inizio",$right_panel);
            $data_inizio->SetStyle("width:100%; margin-bottom: .8em");
            $data_inizio->SetText('<span style="font-weight:bold">Data costituzione:</span><br/>'.$val);

            //Data fine impegno
            $val=$organismo->GetDataFineImpegno();
            if($val=="0000-00-00") $val="n.d.";
            if(trim($val)=="9999-12-31") $val="a tempo indeterminato";
            $data_fine = new AA_XML_Div_Element("generale-tab-right-panel-data_fine",$right_panel);
            $data_fine->SetStyle("width:100%; margin-bottom: .8em");
            $data_fine->SetText('<span style="font-weight:bold">Data cessazione:</span><br/>'.$val);
        }

        //Funzioni attribuite
        $val=$organismo->GetFunzioni();
        if($val=="") $val="n.d.";
        $funzioni = new AA_XML_Div_Element("funzioni",$generale);
        $funzioni->SetStyle("width: 100%; margin-bottom: .8em; border-top: 1px solid gray; text-align: left;");
        $funzioni->SetText('<span style="font-weight:bold">Funzioni attribuite:</span><br>'.$val);

        //note
        $note=new AA_XML_Div_Element("generale-tab-note",$generale);
        $note->SetStyle('width:100%; border-top: 1px solid gray; margin-bottom: .8em;text-align: left;');
        $note->SetText(nl2br($organismo->GetNote()));
        #-------------------

        //Provvedimenti
        /*$provvedimenti=$organismo->GetProvvedimenti();

        if(sizeof($provvedimenti) > 0 && sizeof($provvedimenti) < 21)
        {
            //Chiama il costruttore della classe base
            $provvedimenti_table= new AA_GenericTableTemplateView($id."_provvedimenti_table",$generale,$organismo,array("evidentiate-rows"=>true,"title"=>"Provvedimenti","border"=>"1px solid black;","style"=>"font-size: smaller; margin-bottom: 1em; margin-top: 1em"));

            $provvedimenti_table->SetColSizes(array("5","30","50","10"));
            $provvedimenti_table->SetHeaderLabels(array("Anno","Tipo","Estremi","Url"));    

            $curRow=1;
            foreach($provvedimenti as $id=>$curProvvedimento)
            {
                //Anno
                $provvedimenti_table->SetCellText($curRow,0,$curProvvedimento->GetAnno(), "center");

                //tipo provvedimento
                $curval=$curProvvedimento->GetTipologia();
                if($curval=="" || $curval=="n.d.") $curval="n.d.";
                $provvedimenti_table->SetCellText($curRow,1,$curval,"center");

                //estremi
                $note=$curProvvedimento->GetEstremi();
                $text_align="left";
                $provvedimenti_table->SetCellText($curRow,2,$note, $text_align);

                if($curProvvedimento->GetUrl() !="")
                {
                    //url
                    $url="<a href='".$curProvvedimento->GetUrl()."' target='_blank'>consulta</a>";
                    $provvedimenti_table->SetCellText($curRow,3,$url, "center");
                }
                else
                {
                    $url="<a href='https://".AA_Config::AA_DOMAIN_NAME.$curProvvedimento->GetFilePublicPath()."' target='_blank'>consulta</a>";
                    $provvedimenti_table->SetCellText($curRow,3,$url, "center");
                }

                $curRow++;
            }
        }
        
        */

        //Aggiunge i dati contabili
        $bilanci=new AA_OrganismiReportDatiContabiliListTemplateView("AA_OrganismiReportDatiContabiliListTemplateView",null,$organismo, $user);
        $this->AppendChild($bilanci);

        //legenda
        //$footer="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$organismo->GetAggiornamento()."</span></div>";
        //$this->SetText($footer,false);
    }
}

#Classe template per la gestione del report pdf dell'organismo (pagina generale)
Class AA_OrganismiReportScadenzarioTemplateGeneralPageView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_OrganismiPublicReportTemplateGeneralPageView",$parent=null,$organismo=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo);
        
        $this->SetStyle("width: 99%; display:flex; flex-direction: column; align-items: center;");

        #Parte generale---------------------------------
        $generale=new AA_XML_Div_Element("AA_OrganismiScadenzarioReportTemplateView-generale",$this);
        $generale->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; width: 100%");

        #Denominazione----------------------------------
        $denominazione=new AA_XML_Div_Element("generale-tab-denominazione",$generale);
        $denominazione->SetStyle('width:100%; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .2em; font-size: 20px; font-weight: bold');
        $denominazione->SetText($organismo->GetDenominazione()."<br><span style='font-size: x-small; font-weight: normal'>".$organismo->GetTipologia()."</span>");
        #-----------------------------------------------

        //left panel-------
        $left_panel= new AA_XML_Div_Element("generale-tab-left-panel",$generale);
        $left_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; width:49.9%");

        //Piva
        $val=$organismo->GetPivaCf();
        if($val=="") $val="n.d.";
        $piva=new AA_XML_Div_Element("piva",$left_panel);
        $piva->SetStyle("width: 100%; margin-bottom: .8em");
        $piva->SetText('<span style="font-weight:bold">Partita Iva/Codice fiscale:</span><br/>'.$val);

        //Sede legale
        $val=$organismo->GetSedeLegale();
        if($val=="") $val="n.d.";
        $sede_legale = new AA_XML_Div_Element("sede",$left_panel);
        $sede_legale->SetStyle("width: 100%; margin-bottom: .8em");
        $sede_legale->SetText('<span style="font-weight:bold">Sede legale:</span>'."<br/>".$val);
        
        //Pec
        $val=$organismo->GetPec();
        if($val=="") $val="n.d.";
        $pec = new AA_XML_Div_Element("pec",$left_panel);
        $pec->SetStyle("width: 100%; margin-bottom: .8em");
        $pec->SetText('<span style="font-weight:bold">PEC:</span>'."<br/>".$val); 

        //Sito web
        $val=$organismo->GetSitoWeb();
        if($val=="") $val="n.d.";
        $sito_web = new AA_XML_Div_Element("sito_web",$left_panel);
        $sito_web->SetStyle("width: 100%; margin-bottom: .8em");
        $sito_web->SetText('<span style="font-weight:bold">Sito web:</span><br/><a href="'.$val.'" target="_blank">'.$val."</a>");
        #-------------------

        //right panel ------
        $right_panel= new AA_XML_Div_Element("generale-tab-right-panel",$generale);
        $right_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; width:49.9%");
        
        if($organismo->GetTipologia(true) == AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
        {
            //Data inizio impegno
            $val=$organismo->GetDataInizioImpegno();
            if($val=="0000-00-00") $val="n.d.";
            $data_inizio = new AA_XML_Div_Element("generale-tab-right-panel-data_inizio",$right_panel);
            $data_inizio->SetStyle("width:100%; margin-bottom: .8em");
            $data_inizio->SetText('<span style="font-weight:bold">Data inizio impegno:</span><br/>'.$val);

            //Data fine impegno
            $val=$organismo->GetDataFineImpegno();
            if($val=="0000-00-00") $val="n.d.";
            if(trim($val)=="9999-12-31") $val="a tempo indeterminato";
            $data_fine = new AA_XML_Div_Element("generale-tab-right-panel-data_fine",$right_panel);
            $data_fine->SetStyle("width:100%; margin-bottom: .8em");
            $data_fine->SetText('<span style="font-weight:bold">Data fine impegno:</span><br/>'.$val);

            //partecipazione
            $val=$organismo->GetPartecipazione(true);
            if($val['percentuale']==0 && $val['euro']==0) $val="nessuna";
            else
            {
                $val="€ ".AA_Utils::number_format($val['euro'],2,",",".")." pari al ".AA_Utils::number_format($val['percentuale'],2,",",".")."% delle quote totali";
            }
            $partecipazione= new AA_XML_Div_Element("generale-right-panel-partecipazione",$right_panel);
            $partecipazione->SetStyle("width:100%; margin-bottom: .8em");
            $partecipazione->SetText('<span style="font-weight:bold">Partecipazione diretta RAS:</span><br/>'.$val);
        }
        else
        {
            //Data inizio impegno
            $val=$organismo->GetDataInizioImpegno();
            if($val=="0000-00-00") $val="n.d.";
            $data_inizio = new AA_XML_Div_Element("generale-tab-right-panel-data_inizio",$right_panel);
            $data_inizio->SetStyle("width:100%; margin-bottom: .8em");
            $data_inizio->SetText('<span style="font-weight:bold">Data costituzione:</span><br/>'.$val);

            //Data fine impegno
            $val=$organismo->GetDataFineImpegno();
            if($val=="0000-00-00") $val="n.d.";
            if(trim($val)=="9999-12-31") $val="a tempo indeterminato";
            $data_fine = new AA_XML_Div_Element("generale-tab-right-panel-data_fine",$right_panel);
            $data_fine->SetStyle("width:100%; margin-bottom: .8em");
            $data_fine->SetText('<span style="font-weight:bold">Data cessazione:</span><br/>'.$val);
        }

        //Funzioni attribuite
        $val=$organismo->GetFunzioni();
        if($val=="") $val="n.d.";
        $funzioni = new AA_XML_Div_Element("funzioni",$generale);
        $funzioni->SetStyle("width: 100%; margin-bottom: .8em; border-top: 1px solid gray; text-align: left;");
        $funzioni->SetText('<span style="font-weight:bold">Funzioni attribuite:</span><br>'.$val);

        //note
        $note=new AA_XML_Div_Element("generale-tab-note",$generale);
        $note->SetStyle('width:100%; border-top: 1px solid gray; margin-bottom: .8em;text-align: left;');
        $note->SetText(nl2br($organismo->GetNote()));
        #-------------------

        //legenda
        $footer="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$organismo->GetAggiornamento()."</span></div>";
        $this->SetText($footer,false);
    }
}

#Classe template per la gestione del report pdf dell'organismo (pagina generale)
Class AA_OrganismiFullReportTemplateGeneralPageView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_OrganismiFullReportTemplateGeneralPageView",$parent=null,$organismo=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo);
        
        $this->SetStyle("width: 99%; display:flex; flex-direction: column; align-items: center;");

        #Parte generale---------------------------------
        $generale=new AA_XML_Div_Element("AA_OrganismiPublicReportTemplateView-generale",$this);
        $generale->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: top; flex-wrap: wrap; width: 100%");

        #Denominazione----------------------------------
        $denominazione=new AA_XML_Div_Element("generale-tab-denominazione",$generale);
        $denominazione->SetStyle('width:100%; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .2em; font-size: 20px; font-weight: bold');
        $denominazione->SetText($organismo->GetDenominazione()."<br><span style='font-size: x-small; font-weight: normal'>".$organismo->GetTipologia()."</span>");
        #-----------------------------------------------

        //left panel-------
        $left_panel= new AA_XML_Div_Element("generale-tab-left-panel",$generale);
        $left_panel->SetStyle("display:flex; flex-direction: column; justify-content: flex-start; align-items: flex-start; width:49.9%");

        //Piva
        $val=$organismo->GetPivaCf();
        if($val=="") $val="n.d.";
        $piva=new AA_XML_Div_Element("piva",$left_panel);
        $piva->SetStyle("width: 100%; margin-bottom: .8em");
        $piva->SetText('<span style="font-weight:bold">Partita Iva/Codice fiscale:</span><br/>'.$val);

        //Sede legale
        $val=$organismo->GetSedeLegale();
        if($val=="") $val="n.d.";
        $sede_legale = new AA_XML_Div_Element("sede",$left_panel);
        $sede_legale->SetStyle("width: 100%; margin-bottom: .8em");
        $sede_legale->SetText('<span style="font-weight:bold">Sede legale:</span>'."<br/>".$val);
        
        //Pec
        $val=$organismo->GetPec();
        if($val=="") $val="n.d.";
        $pec = new AA_XML_Div_Element("pec",$left_panel);
        $pec->SetStyle("width: 100%; margin-bottom: .8em");
        $pec->SetText('<span style="font-weight:bold">PEC:</span>'."<br/>".$val); 

        //Sito web
        $val=$organismo->GetSitoWeb();
        if($val=="") $val="n.d.";
        $sito_web = new AA_XML_Div_Element("sito_web",$left_panel);
        $sito_web->SetStyle("width: 100%; margin-bottom: .8em");
        $sito_web->SetText('<span style="font-weight:bold">Sito web:</span><br/><a href="'.$val.'" target="_blank">'.$val."</a>");
        #-------------------

        //right panel ------
        $right_panel= new AA_XML_Div_Element("generale-tab-right-panel",$generale);
        $right_panel->SetStyle("display:flex; flex-direction: column; justify-content: flex-start; align-items: flex-start; width:48%;");
        
        if($organismo->GetTipologia(true) == AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
        {
            //Data inizio impegno
            $val=$organismo->GetDataInizioImpegno();
            if($val=="0000-00-00") $val="n.d.";
            $data_inizio = new AA_XML_Div_Element("generale-tab-right-panel-data_inizio",$right_panel);
            $data_inizio->SetStyle("width:100%; margin-bottom: .8em");
            $data_inizio->SetText('<span style="font-weight:bold">Data inizio impegno:</span><br/>'.$val);

            //Data fine impegno
            $val=$organismo->GetDataFineImpegno();
            if($val=="0000-00-00") $val="n.d.";
            if(trim($val)=="9999-12-31") $val="a tempo indeterminato";
            $data_fine = new AA_XML_Div_Element("generale-tab-right-panel-data_fine",$right_panel);
            $data_fine->SetStyle("width:100%; margin-bottom: .8em");
            $data_fine->SetText('<span style="font-weight:bold">Data fine impegno:</span><br/>'.$val);

            //partecipazione
            $val=$organismo->GetPartecipazione(true);
            if($val['percentuale']==0 && $val['euro']=="0") $val="nessuna";
            else
            {
                $val="€ ".AA_Utils::number_format($val['euro'],2,",",".")." pari al ".AA_Utils::number_format($val['percentuale'],2,",",".")."% delle quote totali";
            }
            $partecipazione= new AA_XML_Div_Element("generale-right-panel-partecipazione",$right_panel);
            $partecipazione->SetStyle("width:100%; margin-bottom: .8em");
            $partecipazione->SetText('<span style="font-weight:bold">Partecipazione diretta RAS:</span><br/>'.$val);
        }
        else
        {
            //Data inizio impegno
            $val=$organismo->GetDataInizioImpegno();
            if($val=="0000-00-00") $val="n.d.";
            $data_inizio = new AA_XML_Div_Element("generale-tab-right-panel-data_inizio",$right_panel);
            $data_inizio->SetStyle("width:100%; margin-bottom: .8em");
            $data_inizio->SetText('<span style="font-weight:bold">Data costituzione:</span><br/>'.$val);

            //Data fine impegno
            $val=$organismo->GetDataFineImpegno();
            if($val=="0000-00-00") $val="n.d.";
            if(trim($val)=="9999-12-31") $val="a tempo indeterminato";
            $data_fine = new AA_XML_Div_Element("generale-tab-right-panel-data_fine",$right_panel);
            $data_fine->SetStyle("width:100%; margin-bottom: .8em");
            $data_fine->SetText('<span style="font-weight:bold">Data cessazione:</span><br/>'.$val);
        }

        //Funzioni attribuite
        $val=nl2br($organismo->GetFunzioni());
        if($val=="") $val="n.d.";
        $funzioni = new AA_XML_Div_Element("funzioni",$generale);
        $funzioni->SetStyle("width: 100%; margin-bottom: .8em; border-top: 1px solid gray; text-align: left;");
        $funzioni->SetText('<span style="font-weight:bold">Funzioni attribuite:</span><br>'.$val);

        //note
        $val=nl2br($organismo->GetNote());
        if($val!="")
        {
        $note=new AA_XML_Div_Element("generale-tab-note",$generale);
        $note->SetStyle("width: 100%; margin-bottom: .8em; border-top: 1px solid gray; text-align: left;");
        $note->SetText('<span style="font-weight:bold">Note:</span><br>'.$val);   
        }
        #-------------------

        //Provvedimenti
        $provvedimenti=$organismo->GetProvvedimenti();

        if(sizeof($provvedimenti) > 0)
        {
            //Chiama il costruttore della classe base
            $provvedimenti_table= new AA_GenericTableTemplateView($id."_provvedimenti_table",$generale,$organismo,array("evidentiate-rows"=>true,"title"=>"Provvedimenti","border"=>"1px solid black;","style"=>"font-size: smaller; margin-bottom: 1em; margin-top: 1em"));

            $provvedimenti_table->SetColSizes(array("5","30","50","10"));
            $provvedimenti_table->SetHeaderLabels(array("Anno","Tipo","Estremi","Url"));    

            $curRow=1;
            foreach($provvedimenti as $id=>$curProvvedimento)
            {
                //Anno
                $provvedimenti_table->SetCellText($curRow,0,$curProvvedimento->GetAnno(), "center");

                //tipo provvedimento
                $curval=$curProvvedimento->GetTipologia();
                if($curval=="" || $curval=="n.d.") $curval="n.d.";
                $provvedimenti_table->SetCellText($curRow,1,$curval,"center");

                //estremi
                $note=$curProvvedimento->GetEstremi();
                $text_align="left";
                $provvedimenti_table->SetCellText($curRow,2,$note, $text_align);

                if($curProvvedimento->GetUrl() !="")
                {
                    //url
                    $url="<a href='".$curProvvedimento->GetUrl()."' target='_blank'>consulta</a>";
                    $provvedimenti_table->SetCellText($curRow,3,$url, "center");
                }
                else
                {
                    $url="<a href='https://".AA_Config::AA_DOMAIN_NAME.$curProvvedimento->GetFilePublicPath()."' target='_blank'>consulta</a>";
                    $provvedimenti_table->SetCellText($curRow,3,$url, "center");
                }

                $curRow++;
            }
        }

        //legenda
        //$footer="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%; border-top: 1px solid gray; '>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$organismo->GetAggiornamento()."</span></div>";
        //$this->SetText($footer,false);
    }
}

#Classe template per la gestione del report pdf dell'organismo (pagina generale)
Class AA_OrganismiFullReportTemplateDaticontabiliPageView extends AA_GenericObjectTemplateView
{
    protected $nRelNumPage=0;
    public function GetRelNumPage()
    {
        return $this->nRelNumPage;
    }
    public function __construct($id="AA_OrganismiFullReportTemplateDaticontabiliPageView",$parent=null,$organismo=null, $user=null, $doc=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        //parent::__construct($id,$parent,$organismo);

        $daticontabili=$organismo->GetDatiContabili();
        $anniForPage=3;
        $curAnniRow=0;
        $curRelNumPage=0;
        
        $curPage=$doc->AddPage();

        $corpo_page=new AA_GenericObjectTemplateView($id."_corpo_page_".$curRelNumPage,null,$organismo);
        $corpo_page->SetStyle('display:flex;  flex-direction: column; width: 100%; align-items: center; justify-content: space-between; text-align: center; padding: 0mm; min-height: 9mm;');

        #Denominazione----------------------------------
        $denominazione=new AA_XML_Div_Element($id."_generale-tab-denominazione",$corpo_page);
        $denominazione->SetStyle('width:100%; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .2em; font-size: 20px; font-weight: bold');
        $denominazione->SetText($organismo->GetDenominazione()."<br><span style='font-size: x-small; font-weight: normal'>".$organismo->GetTipologia()."</span>");
        #-----------------------------------------------     

        foreach($daticontabili as $curDatocontabile)
        {    
            if($curAnniRow >= $anniForPage)
            {
                $curPage->SetContent($corpo_page->__toString());
                $curPage->SetFooterContent("<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$organismo->GetAggiornamento()."</span></div>");
                $curRelNumPage++;
                $curPage=$doc->AddPage();
                $curAnniRow=0;

                $corpo_page=new AA_GenericObjectTemplateView($id."_corpo_page_".$curRelNumPage,null,$organismo);
                $corpo_page->SetStyle('display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; text-align: center; padding: 0mm; min-height: 9mm;');
        
                #Denominazione----------------------------------
                $denominazione=new AA_XML_Div_Element($id."_generale-tab-denominazione",$corpo_page);
                $denominazione->SetStyle('width:100%; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .2em; font-size: 20px; font-weight: bold');
                $denominazione->SetText($organismo->GetDenominazione()."<br><span style='font-size: x-small; font-weight: normal'>".$organismo->GetTipologia()."</span>");
                #-----------------------------------------------        
            }

            $this_id=$id."_".$curDatocontabile->GetID();

            //intestazione anno
            if($curDatocontabile->IsInGap()) 
            {
                $gap_label="<span class='AA_Label AA_Label_LightYellow' title='Stato scheda organismo'>GAP</span>";
            }
            else 
            {
                $gap_label="";
            }
            
            if($curDatocontabile->IsInGbc()) 
            {
                $gbc_label="<span class='AA_Label AA_Label_LightYellow' title='Stato scheda organismo'>GBC</span>";
            }
            else 
            {
                $gbc_label="";
            }

            $intestazione=new AA_XML_Div_Element($this_id."_generale-tab-intestazione",$corpo_page);
            $intestazione->SetStyle('width:97.6%; border-top: 1px solid gray; border-left: 1px solid gray; border-right: 1px solid gray; background-color: #dedede; text-align: center; font-weight: bold; font-size: larger;padding: .5em');
            $intestazione->SetText("Dati contabili per l'anno ".$curDatocontabile->GetAnno()." ".$gap_label." ".$gbc_label);
            
            #Parte generale---------------------------------
            $generale=new AA_XML_Div_Element($this_id."_Generale",$corpo_page);
            $generale->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; width: 98%; border: 1px solid gray; margin-bottom: .5em; padding: .5em;");
            //left panel-------
            $left_panel= new AA_XML_Div_Element($this_id."_generale-tab-left-panel",$generale);
            $left_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; width:48.9%");

            //right panel ------
            $right_panel= new AA_XML_Div_Element($this_id."_generale-tab-right-panel",$generale);
            $right_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; width:48.9%");

            //Oneri totali
            $val=$curDatocontabile->GetOneriTotali();
            if($val=="") $val="n.d.";
            else $val=number_format(floatVal(str_replace(array(".",","),array("","."),$val)),2,",",".");
            $piva=new AA_XML_Div_Element($this_id."_oneri",$left_panel);
            $piva->SetStyle("width: 100%; margin-bottom: .8em");
            $piva->SetText('<span style="font-weight:bold">Oneri totali:</span><br/>€ '.$val);

            //spesa lavoro flessibile
            $val=$curDatocontabile->GetSpesaLavoroFlessibile();
            if($val=="") $val="n.d.";
            else $val=number_format(floatVal(str_replace(array(".",","),array("","."),$val)),2,",",".");
            $piva=new AA_XML_Div_Element($this_id."_lavoroflessibile",$left_panel);
            $piva->SetStyle("width: 100%; margin-bottom: .8em");
            $piva->SetText('<span style="font-weight:bold">Spesa lavoro flessibile:</span><br/>€ '.$val);

            //spesa incarichi
            $val=$curDatocontabile->GetSpesaIncarichi();
            if($val=="") $val="n.d.";
            else $val=number_format(floatVal(str_replace(array(".",","),array("","."),$val)),2,",",".");
            $piva=new AA_XML_Div_Element($this_id."_spesaincarichi",$left_panel);
            $piva->SetStyle("width: 100%; margin-bottom: .8em");
            $piva->SetText('<span style="font-weight:bold">Spesa incarichi:</span><br/>€ '.$val);

            if($organismo->GetTipologia(true)==AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
            {
                //fatturato
                $val=$curDatocontabile->GetFatturato();
                if($val=="") $val="n.d.";
                else $val=number_format(floatVal(str_replace(array(".",","),array("","."),$val)),2,",",".");
                $piva=new AA_XML_Div_Element($this_id."_fatturato",$left_panel);
                $piva->SetStyle("width: 100%; margin-bottom: .8em");
                $piva->SetText('<span style="font-weight:bold">Fatturato:</span><br/>€ '.$val);
            }

            //dotazione organica
            $val=$curDatocontabile->GetDotazioneOrganica();
            if($val=="") $val="n.d.";
            $piva=new AA_XML_Div_Element($this_id."_dotazione_organica",$right_panel);
            $piva->SetStyle("width: 100%; margin-bottom: .8em");
            $piva->SetText('<span style="font-weight:bold">Dotazione organica:</span><br/>'.$val);

            //personale assunto a tempo indeterminato
            $val=intval($curDatocontabile->GetDipendenti())+intval($curDatocontabile->GetDipendentiDir());
            if($val!="")
            {
                if($curDatocontabile->GetDipendentiDir() > 0)
                {
                    $val.=" di cui ".$curDatocontabile->GetDipendentiDir()." dirigenti";
                }
            } 
            else $val="n.d.";
            $piva=new AA_XML_Div_Element($this_id."_dipendenti",$right_panel);
            $piva->SetStyle("width: 100%; margin-bottom: .8em");
            $piva->SetText('<span style="font-weight:bold">Personale assunto a tempo indeterminato:</span><br/>'.$val);

            //personale assunto a tempo determinato
            $val=intval($curDatocontabile->GetDipendentiDet())+intval($curDatocontabile->GetDipendentiDetDir());
            if($val!="")
            {
                if($curDatocontabile->GetDipendentiDetDir() > 0)
                {
                    $val.=" di cui ".$curDatocontabile->GetDipendentiDetDir()." dirigenti";
                }
            } 
            else $val="n.d.";
            $piva=new AA_XML_Div_Element($this_id."_dipendenti_det",$right_panel);
            $piva->SetStyle("width: 100%; margin-bottom: .8em");
            $piva->SetText('<span style="font-weight:bold">Personale assunto a tempo determinato:</span><br/>'.$val);
            
            //spesa dotazione organica
            $val=$curDatocontabile->GetSpesaDotazioneOrganica();
            if($val=="") $val="n.d.";
            else $val=number_format(floatVal(str_replace(array(".",","),array("","."),$val)),2,",",".");
            $piva=new AA_XML_Div_Element($this_id."_spesa_dotazione_organica",$right_panel);
            $piva->SetStyle("width: 100%; margin-bottom: .8em");
            $piva->SetText('<span style="font-weight:bold">Spesa dotazione organica:</span><br/>€ '.$val);

            $bilanci=$curDatocontabile->GetBilanci();
            if(sizeof($bilanci) > 0)
            {
                $curRow=1;
                $bilanci_table= new AA_GenericTableTemplateView($this_id."_bilanci_table",$generale,$organismo,array("evidentiate-rows"=>true,"border"=>"1px solid black;","style"=>"font-size: smaller; margin-bottom: 1em; margin-top: 1em"));
                $bilanci_table->SetColSizes(array("30","20","45"));
                $bilanci_table->SetHeaderLabels(array("Tipo di bilancio","Risultati in €", "Note"));
                foreach($bilanci as $curBilancio)
                {
                    //tipo
                    $bilanci_table->SetCellText($curRow,0,$curBilancio->GetTipo(), "center");

                    //risultati
                    $bilanci_table->SetCellText($curRow,1,number_format(floatVal(str_replace(array(".",","),array("","."),$curBilancio->GetRisultati())),2,",","."), "center");

                    //note
                    $bilanci_table->SetCellText($curRow,2,$curBilancio->GetNote(), "left");

                    $curRow++;
                }
            }


            //note
            $val=nl2br($curDatocontabile->GetNote());
            if($val !="")
            {
                $note=new AA_XML_Div_Element($this_id."_generale-tab-note",$generale);
                $note->SetStyle('width:100%; border-top: 1px solid gray; margin-bottom: .8em;text-align: left;');
                $note->SetText($val);    
            }
            #-------------------

            //legenda
            //$footer="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$organismo->GetAggiornamento()."</span></div>";
            //$corpo_page->SetText($footer,false);

            $curAnniRow++;
        }

        if($curAnniRow > 0)
        {
            $curPage->SetContent($corpo_page->__toString());
            $curPage->SetFooterContent("<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$organismo->GetAggiornamento()."</span></div>");
        }

        $this->nRelNumPage=$curRelNumPage;
    }
}

#Classe template per la gestione del report pdf dell'organismo (pagina nomine)
Class AA_OrganismiPublicReportTemplateNominePageView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_OrganismiPublicReportTemplateNominePageView",$parent=null,$organismo=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo);
        
        $this->SetStyle("width: 99%; display:flex; flex-direction: column; align-items: center;");

        #Parte generale---------------------------------
        $generale=new AA_XML_Div_Element("AA_OrganismiPublicReportTemplateView-generale",$this);
        $generale->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; width: 100%");

        #Denominazione----------------------------------
        $denominazione=new AA_XML_Div_Element("generale-tab-denominazione",$generale);
        $denominazione->SetStyle('width:100%; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .2em; font-size: 20px; font-weight: bold');
        $denominazione->SetText($organismo->GetDenominazione()."<br><span style='font-size: x-small; font-weight: normal'>".$organismo->GetTipologia()."</span>");
        #-----------------------------------------------

        //Aggiunge le nomine
        $nomine=new AA_OrganismiReportNomineListTemplateView("AA_OrganismiPublicReportTemplateView-nomine",null,$organismo, $user);        
        $this->AppendChild($nomine);

        //legenda
        $footer="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%; margin-bottom: 2em'>Nel presente prospetto sono esposti i dati dei rappresentanti designati dall'Amministrazione regionale negli organi di governo dell’ente e quelli relativi ai titolari di incarichi di amministratore dell'ente, siano essi nominati dalla Regione o meno.</span></div>";
        $footer.="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$organismo->GetAggiornamento()."</span></div>";
        $this->SetText($footer,false);
    }
}

#Classe template per la gestione del report pdf dell'organismo (pagina nomine)
Class AA_OrganismiPublicReportTemplateProvvedimentiPageView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_OrganismiPublicReportTemplateProvvedimentiPageView",$parent=null,$organismo=null, $user=null,$provvedimenti=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo);
        
        $this->SetStyle("width: 99%; display:flex; flex-direction: column; align-items: center;");

        #Parte generale---------------------------------
        $generale=new AA_XML_Div_Element("AA_OrganismiPublicReportTemplateView-generale",$this);
        $generale->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; width: 100%");

        #Denominazione----------------------------------
        $denominazione=new AA_XML_Div_Element("generale-tab-denominazione",$generale);
        $denominazione->SetStyle('width:100%; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .2em; font-size: 20px; font-weight: bold');
        $denominazione->SetText($organismo->GetDenominazione()."<br><span style='font-size: x-small; font-weight: normal'>".$organismo->GetTipologia()."</span>");
        #-----------------------------------------------

        //Aggiunge i provvedimenti
        $list=new AA_OrganismiReportProvvedimentiListTemplateView("AA_OrganismiPublicReportTemplateView-provvedimenti",null,$organismo, $user,$provvedimenti);        
        $this->AppendChild($list);

        //legenda
        $footer="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$organismo->GetAggiornamento()."</span></div>";
        $this->SetText($footer,false);
    }
}

#Classe template per la gestione del report pdf dell'organismo (pagina nomine)
Class AA_OrganismiFullReportTemplateNominePageView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_OrganismiFullReportTemplateNominePageView",$parent=null,$organismo=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo);
        
        $this->SetStyle("width: 99%; display:flex; flex-direction: column; align-items: center;");

        #Parte generale---------------------------------
        $generale=new AA_XML_Div_Element("AA_OrganismiFullReportTemplateView-generale",$this);
        $generale->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; width: 100%");

        #Denominazione----------------------------------
        $denominazione=new AA_XML_Div_Element("generale-tab-denominazione",$generale);
        $denominazione->SetStyle('width:100%; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .2em; font-size: 20px; font-weight: bold');
        $denominazione->SetText($organismo->GetDenominazione()."<br><span style='font-size: x-small; font-weight: normal'>".$organismo->GetTipologia()."</span>");
        #-----------------------------------------------

        //Aggiunge le nomine
        $nomine=new AA_OrganismiReportNomineListTemplateView("AA_OrganismiFullReportTemplateView-nomine",null,$organismo, $user, true);        
        $this->AppendChild($nomine);

        //legenda
        $footer="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%; margin-bottom: 2em'>Nel presente prospetto sono esposti i dati dei rappresentanti designati dall'Amministrazione regionale negli organi di governo dell’ente e quelli relativi ai titolari di incarichi di amministratore dell'ente, siano essi nominati dalla Regione o meno.</span></div>";
        //$footer.="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$organismo->GetAggiornamento()."</span></div>";
        $this->SetText($footer,false);
    }
}

#Classe template per la gestione del report pdf dello scadenzario (pagina nomine)
Class AA_OrganismiReportScadenzarioNomineTemplateView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_OrganismiReportScadenzarioNomineTemplateView",$parent=null,$organismo=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo);
        
        $this->SetStyle("width: 99%; display:flex; flex-direction: column; align-items: center;");

        #Parte generale---------------------------------
        $generale=new AA_XML_Div_Element("AA_OrganismiPublicReportTemplateView-generale",$this);
        $generale->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; width: 100%");

        #Denominazione----------------------------------
        $denominazione=new AA_XML_Div_Element("generale-tab-denominazione",$generale);
        $denominazione->SetStyle('width:100%; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .2em; font-size: 20px; font-weight: bold');
        $denominazione->SetText($organismo->GetDenominazione()."<br><span style='font-size: x-small; font-weight: normal'>".$organismo->GetTipologia()."</span>");
        #-----------------------------------------------

        //Aggiunge le nomine
        $parametri=unserialize($_SESSION['AA_Organismi_Scadenzario_Filter_Params']);
        $meseProx=new DateTime($parametri['data_scadenzario']);
        $meseProx->modify("+".$parametri['finestra_temporale']." month");
        $mesePrec=new DateTime($parametri['data_scadenzario']);
        $mesePrec->modify("-".$parametri['finestra_temporale']." month");
        $data_scadenzario=new DateTime($parametri['data_scadenzario']);

        //Raggruppa per incarico
        $params_nomine['raggruppamento']=$parametri['raggruppamento'];

        //Incarichi archiviati
        $params_nomine['archivio']=$parametri['archivio'];

        //nominativo
        $params_nomine['incaricato']=$parametri['incaricato'];

        //Tipo incarico
        $params_nomine['tipo_nomina']=$parametri['tipo_nomina'];

        //Imposta i limiti temporali
        if($parametri['in_scadenza'] != "1" || $parametri['in_corso'] != "1" || $parametri['scadute'] != "1" || $parametri['recenti'] != "1")
        {
           //limite superiore
           if($parametri['scadute'] == "1") $params_nomine['scadenzario_al']=$mesePrec->format("Y-m-d");
           if($parametri['recenti'] == "1") $params_nomine['scadenzario_al']=$parametri['data_scadenzario'];
           if($parametri['in_scadenza'] == "1") $params_nomine['scadenzario_al']=$meseProx->format("Y-m-d");
           if($parametri['in_corso'] == "1") $params_nomine['scadenzario_al']="";

           //limite inferiore
           if($parametri['in_corso'] == "1") $params_nomine['scadenzario_dal']=$meseProx->format("Y-m-d");
           if($parametri['in_scadenza'] == "1") $params_nomine['scadenzario_dal']=$parametri['data_scadenzario'];
           if($parametri['recenti'] == "1") $params_nomine['scadenzario_dal']=$mesePrec->format("Y-m-d");
           if($parametri['scadute'] == "1") $params_nomine['scadenzario_dal']="";
        }
        
        $nomine=$organismo->GetNomineScadenzario($params_nomine);

        $organigrammi=$organismo->GetOrganigrammi();
        $organigrammi_scadenzario=array();
        foreach($organigrammi as $id=>$curOrganigramma)
        {
            if($curOrganigramma->IsScadenzarioEnabled()) $organigrammi_scadenzario[$id]=$curOrganigramma;
        }

        $nomine_list=array();
        
        foreach($nomine as $tipoIncarico=>$nomina)
        {
            foreach($nomina as $curNomina)
            {
                $organigramma_label="";
                $tipo_organigramma="";

                $datafine=new DateTime($curNomina->GetDataFine());
                $datainizio=new DateTime($curNomina->GetDataInizio());
                
                $view=false;
                if($parametri['in_corso']=="1" && $datafine > $meseProx)
                {
                    $view=true;
                    $label_class="AA_Label_LightGreen";
                    $label_scadenza="Cessa il: ";
                    $index="in_corso";
                }
                    
                if($parametri['in_scadenza']=="1" && $datafine >= $data_scadenzario && $datafine <= $meseProx)
                {
                    $view=true;
                    $label_class="AA_Label_LightYellow";
                    $label_scadenza="Cessa il: ";
                    $index="in_scadenza";
                }
                
                if($parametri['recenti']=="1" && $datafine >= $mesePrec && $datafine <= $data_scadenzario)
                {
                    $view=true;
                    $label_class="AA_Label_LightOrange";
                    $label_scadenza="Cessata il: ";
                    $index="recenti";
                }
                
                if($parametri['scadute']=="1" && $datafine < $mesePrec)
                {
                    $view=true;
                    $label_class="AA_Label_LightRed";
                    $label_scadenza="Cessata il: ";
                    $index="cessate";
                }
                
                //AA_Log::Log(__METHOD__." - data_fine: ".print_r($datafine,true)." - data_scadenzario: ".print_r($data_scadenzario,true)." - mese prox: ".print_r($meseProx,true)." - mese prec: ".print_r($mesePrec,true),100);
                
                if($view)
                {
                    //recupera l'organigramma di cui fa parte
                    foreach($organigrammi_scadenzario as $id=>$curOrganigramma)
                    {
                        $incarichi=$curOrganigramma->GetIncarichi();
                        foreach($incarichi as $curIncarico)
                        {
                            if($curIncarico->IsNominaRas() && $curIncarico->GetTipologia(true)==$curNomina->GetTipologia(true)) 
                            {
                                $organigramma_label=$tipo_organigramma=$curOrganigramma->GetTipologia();
                            }

                            if($organigramma_label!="") break;
                        }
                        if($organigramma_label!="") break;
                    }

                    $dati=array();
                                        
                    $nomina_label=$curNomina->GetNome()." ".$curNomina->GetCognome();
                    $dati['nome']=$curNomina->GetNome()." ".$curNomina->GetCognome();

                    if($organigramma_label !="")
                    {
                        $organigramma_label="<div><span style='font-weight:900; font-size:larger'>".$organigramma_label."</span></div>";
                    }
                    //AA_Log::Log(__METHOD__." - inserisco: ".$nomina_label,100);
                    //if($curNomina->GetCodiceFiscale() !="") $nomina_label.=" <span style='font-size: smaller'>(".trim($curNomina->GetCodiceFiscale()).")</span>";
                    $nominaRas="";
                    if($curNomina->IsNominaRas()) 
                    {
                        $nominaRas="<div style='margin-top: .5em;'><span style='font-size: smaller'>Nomina Ras</span>";
                        $dati['ras']=1;
                    }
                    else 
                    {
                        $dati['ras']=0;
                        $nominaRas="<div style='margin-top: .5em;'>";
                    }

                    if($curNomina->GetEstremiProvvedimento() !="")
                    {
                        if($curNomina->IsNominaRas()) $nominaRas.="<br>";
                        $nominaRas.="<span style='font-size: smaller'>".$curNomina->GetEstremiProvvedimento()."</span></div>";
                        $dati['estremi']=$curNomina->GetEstremiProvvedimento();
                    }
                    else
                    {
                        $nominaRas.="</div>";
                        $dati['estremi']="";
                    }

                    if(!$curNomina->IsFacenteFunzione()) 
                    {
                        $nomine_list[$index][$tipo_organigramma]['text'].="<div class='AA_Label ".$label_class."' style='margin-right: 1em; margin-bottom:1em;font-size:larger; padding:10px'>".$organigramma_label."<div style='font-weight: 900; margin-top: .5em'>".$curNomina->GetTipologia()."</div><div>".$nomina_label."</div>$nominaRas<div style='margin-top: .5em'>".$label_scadenza.$curNomina->GetDataFine()." <span style='font-size: smaller'>(".$datafine->diff($data_scadenzario)->format("%a")." gg)</span></div></div>";
                        
                        $dati['tipo_nomina']=$curNomina->GetTipologia();
                        $dati['ff']=0;
                        $dati['cessazione']="<div style=''>".date("d-m-Y",strtotime($curNomina->GetDataFine()))." <span style='font-size: smaller'>(".$datafine->diff($data_scadenzario)->format("%a")." gg)</span></div>";

                        if(!isset($nomine_list[$index][$tipo_organigramma]['struct'])) $nomine_list[$index][$tipo_organigramma]['struct']=array();
                        $nomine_list[$index][$tipo_organigramma]['struct'][]=$dati;
                    }
                    else
                    {

                        $dati['tipo_nomina']=$curNomina->GetTipologia();
                        $dati['ff']=1;
                        $dati['cessazione']="<div style=''>facente funzione dal <br>".date("d-m-Y",strtotime($curNomina->GetDataFine()))." <span style='font-size: smaller'>(".$datafine->diff($data_scadenzario)->format("%a")." gg)</span></div>";

                        $nomine_list[$index][$tipo_organigramma]['text'].="<div class='AA_Label AA_Label_LightYellow' style='margin-right: 1em; margin-bottom:1em;font-size:larger; padding:10px'>".$organigramma_label."<div style='font-weight: 900; margin-top: .5em'>".$curNomina->GetTipologia()."<br><span style='font-weight:normal;font-size:smaller'>(facente funzione)</span></div><div>".$nomina_label."</div>$nominaRas<div style='margin-top: .5em'>".$label_scadenza.$curNomina->GetDataFine()." <span style='font-size: smaller'>(".$datafine->diff($data_scadenzario)->format("%a")." gg)</span></div></div>";
                        
                        if(!isset($nomine_list[$index][$tipo_organigramma]['struct'])) $nomine_list[$index][$tipo_organigramma]['struct']=array();
                        $nomine_list[$index][$tipo_organigramma]['struct'][]=$dati;
                    }
                }
            }
        }

        $result="";
        if($parametri['finestra_temporale']==1) $mese="mese";
        else $mese="mesi";
        $data=date_format($data_scadenzario,"Y-m-d");

        $table_colors["in_corso"]="AA_Label_LightGreen";
        $table_colors["in_scadenza"]="AA_Label_LightYellow";
        $table_colors["recenti"]="AA_Label_LightOrange";
        $table_colors["cessate"]="AA_Label_LightRed";

        $box=array();
        $box["in_corso"]=new AA_XML_Div_Element("AA_OrganismiReportScadenzarioNomineTableTemplateView_label_in_corso".$organismo->GetId(),$this);
        $box["in_scadenza"]=new AA_XML_Div_Element("AA_OrganismiReportScadenzarioNomineTableTemplateView_label_in_scadenza".$organismo->GetId(),$this);
        $box["recenti"]=new AA_XML_Div_Element("AA_OrganismiReportScadenzarioNomineTableTemplateView_label_recenti".$organismo->GetId(),$this);
        $box["cessate"]=new AA_XML_Div_Element("AA_OrganismiReportScadenzarioNomineTableTemplateView_label_cessate".$organismo->GetId(),$this);

        foreach($nomine_list as $index=>$x)
        {     
            $result="";       
            $title="";

            $curRow=0;
            foreach($x as $tipo_organigramma=>$y)
            {
                $table_data=new AA_GenericTableTemplateView($id,$box[$index],$organismo,array("evidentiate-rows"=>false,"title"=>"<div style='padding: 5px; font-size:smaller; background-color: #fff; '>".$tipo_organigramma."</div>","border"=>"1px solid gray;","style"=>"margin-bottom: 1em; margin-top: 1em;"));
                //$table_data->SetClass($table_colors[$index]);
                $table_data->SetColSizes(array("30","20","20","30"));
                $table_data->SetCellPadding("5px");
                $table_data->SetHeaderLabels(array("<div style='text-align: left'>Incarico</div>","<div style='text-align: left'>Nome e cognome</div>","Data", "<div style='text-align: left'>estremi atto di nomina</div>"));
                $curRow=1;
                foreach($y['struct'] as $val)
                {
                    $tipo_nomina=$val['tipo_nomina'];
                    if($val['ff']) $tipo_nomina.=" (f.f.)";
                    if($val['ras']) $tipo_nomina.="<br><span style='font-size:smaller'>nomina ras</span>";
                    $table_data->SetCellText($curRow,0,$tipo_nomina);
                    $table_data->SetCellText($curRow,1,$val['nome']);
                    $table_data->SetCellText($curRow,2,$val['cessazione'],"center");
                    $table_data->SetCellText($curRow,3,$val['estremi']);
                
                    $curRow+=1;
                }
               
                $result.=$y['text'];
            }
            
            if($index=="in_corso")
            {
                $box[$index]->SetStyle("display:flex; flex-direction: row; align-items: left; flex-wrap: wrap; width: 100%; margin-bottom: 2em; border-bottom: 2px solid gray");
                //$box[$index]->SetClass($table_colors[$index]);
                $box[$index]->SetText("<div style='font-size: larger'>Incarichi <span style='color:  #138d75; font-weight: bold'> che cesseranno tra più di ".$parametri['finestra_temporale']." $mese</span> a far data del ".date("d-m-Y",strtotime($data)).":</div>");
            }
            
            if($index=="in_scadenza") 
            {
                $box[$index]->SetStyle("display:flex; flex-direction: row; align-items: left; flex-wrap: wrap; width: 100%; margin-bottom: 2em; border-bottom: 2px solid gray");
                //$box[$index]->SetClass($table_colors[$index]);
                $box[$index]->SetText("<div style='font-size: larger'>Incarichi <span style='color: #f39c12; font-weight: bold'> che cesseranno entro ".$parametri['finestra_temporale']." $mese</span> a far data del ".date("d-m-Y",strtotime($data)).":</div>");
            }

            if($index=="recenti")
            {
                $box[$index]->SetStyle("display:flex; flex-direction: row; align-items: left; flex-wrap: wrap; width: 100%; margin-bottom: 2em; border-bottom: 2px solid gray");
                //$box[$index]->SetClass($table_colors[$index]);
                $box[$index]->SetText("<div style='font-size: larger'>Incarichi <span style='color: red; font-weight: bold'>cessati da meno di ".$parametri['finestra_temporale']." $mese</span> a far data del ".date("d-m-Y",strtotime($data)).":</div>");
            }
            
            if($index=="cessate") 
            {
                $box[$index]->SetStyle("display:flex; flex-direction: row; align-items: left; flex-wrap: wrap; width: 100%; margin-bottom: 2em; border-bottom: 2px solid gray");
                //$box[$index]->SetClass($table_colors[$index]);
                $box[$index]->SetText("<div style='font-size: larger'>Incarichi <span style='color: red; font-weight: bold'>cessati da più di ".$parametri['finestra_temporale']." $mese</span> a far data del ".date("d-m-Y",strtotime($data)).":</div>");
            }

            /*
            if($title != "")
            {
                $table=new AA_XML_Div_Element("AA_OrganismiReportScadenzarioNomineTableTemplateView_label_".$index."_".$organismo->GetId(),$this);
                $table->SetStyle("display:flex; flex-direction: row; align-items: left; flex-wrap: wrap; width: 100%; margin-bottom: 1mm;");
                $table->SetText($title);

                $table=new AA_XML_Div_Element("AA_OrganismiReportScadenzarioNomineTableTemplateView_items_".$index."_".$organismo->GetId(),$this);
                $table->SetStyle("display:flex; flex-direction: row; align-items: left; flex-wrap: wrap; width: 100%; margin-bottom: 10mm; gap: 1em 1em");
                $table->SetText($table_data->__toString());
                //$table->SetText($result);
            }*/
        }
    }
}


#Classe template per la gestione della view generale dell'item dell'organismo
Class AA_OrganismiGeneralTabTemplateView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_OrganismiGeneralTabTemplateView",$parent=null,$organismo=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo);

        $permessi=$organismo->GetUserCaps($user);
        $canModify=false;
        if($permessi & AA_Const::AA_PERMS_WRITE) $canModify = true;
        $canPublish=false;
        if($permessi & AA_Const::AA_PERMS_PUBLISH) $canPublish = true;
        
        #Parte generale---------------------------------
        $this->SetStyle("display:flex; flex-direction: row; justify-content: space-around; align-items: center; flex-wrap: wrap; width: 100%;");

        //left panel-------
        $left_panel= new AA_XML_Div_Element("generale-tab-left-panel",$this);
        $left_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; width:49.9%");

        //Piva
        $val=$organismo->GetPivaCf();
        if($val=="") $val="n.d.";
        $piva=new AA_XML_Div_Element("piva",$left_panel);
        $piva->SetStyle("width: 100%; margin-bottom: .8em");
        $content='<span style="font-weight:bold">Partita Iva/Codice fiscale:</span><br/>'.$val;
        if($canModify)
        {
            $content.='<a  style="margin-left: .5em; display: inline-block; cursor: pointer" id-object="'.$organismo->GetID().'" id-organismo="'.$organismo->GetID().'" class="AA_Button-modify-field ui-icon ui-icon-pencil" task-get="organismi-modify-field-dlg" field-type="text" task-post="update-organismo" field="sPivaCf" field-name="Partita iva/cf" title="Modifica la partita iva/cf">Modifica la partita iva/cf</a>';
        }
        $piva->SetText($content);

        //Sede legale
        $val=$organismo->GetSedeLegale();
        if($val=="") $val="n.d.";
        $sede_legale = new AA_XML_Div_Element("sede",$left_panel);
        $sede_legale->SetStyle("width: 100%; margin-bottom: .8em");
        $content='<span style="font-weight:bold">Sede legale:</span>'."<br/>".$val;
        if($canModify)
        {
            $content.='<a  style="margin-left: .5em; display: inline-block; cursor: pointer" id-object="'.$organismo->GetID().'" id-organismo="'.$organismo->GetID().'" class="AA_Button-modify-field ui-icon ui-icon-pencil" task-get="organismi-modify-field-dlg" field-type="text" task-post="update-organismo" field="sSedeLegale" field-name="Sede legale" title="Modifica">Modifica</a>';
        }
        $sede_legale->SetText($content);    
        
        //Pec
        $val=$organismo->GetPec();
        if($val=="") $val="n.d.";
        $pec = new AA_XML_Div_Element("pec",$left_panel);
        $pec->SetStyle("width: 100%; margin-bottom: .8em");
        $content='<span style="font-weight:bold">PEC:</span>'."<br/>".$val;
        if($canModify)
        {
            $content.='<a  style="margin-left: .5em; display: inline-block; cursor: pointer" id-object="'.$organismo->GetID().'" id-organismo="'.$organismo->GetID().'" class="AA_Button-modify-field ui-icon ui-icon-pencil" task-get="organismi-modify-field-dlg" field-type="text" task-post="update-organismo" field="sPec" field-name="PEC" title="Modifica">Modifica</a>';
        }
        $pec->SetText($content);

        //Sito web
        $val=$organismo->GetSitoWeb();
        if($val=="") $val="n.d.";
        $sito_web = new AA_XML_Div_Element("sito_web",$left_panel);
        $sito_web->SetStyle("width: 100%; margin-bottom: .8em");
        $content='<span style="font-weight:bold">Sito web:</span><br/><a href="'.$val.'" target="_blank">'.$val."</a>";
        if($canModify)
        {
            $content.='<a  style="margin-left: .5em; display: inline-block; cursor: pointer" id-object="'.$organismo->GetId().'" id-organismo="'.$organismo->GetID().'" class="AA_Button-modify-field ui-icon ui-icon-pencil" task-get="organismi-modify-field-dlg" field-type="text" task-post="update-organismo" field="sSitoWeb" field-name="Sito web" title="Modifica">Modifica</a>';
        }
        $sito_web->SetText($content);
        #-------------------

        //right panel ------
        $right_panel= new AA_XML_Div_Element("generale-tab-right-panel",$this);
        $right_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; width:49.9%");
        
        if($organismo->GetTipologia(true) == AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
        {
            //Data inizio impegno
            $val=$organismo->GetDataInizioImpegno();
            if($val=="0000-00-00") $val="n.d.";
            $data_inizio = new AA_XML_Div_Element("generale-tab-right-panel-data_inizio",$right_panel);
            $data_inizio->SetStyle("width:100%; margin-bottom: .8em");
            $content='<span style="font-weight:bold">Data inizio impegno:</span><br/>'.$val;
            if($canModify)
            {
                $content.='<a  style="margin-left: .5em; display: inline-block; cursor: pointer" id-object="'.$organismo->GetId().'" id-organismo="'.$organismo->GetId().'" class="AA_Button-modify-field ui-icon ui-icon-pencil" task-get="organismi-modify-field-dlg" field-type="date" task-post="update-organismo" field="sDataInizioImpegno" field-name="Data inizio impegno" title="Modifica">Modifica</a>';
            }
            $data_inizio->SetText($content);

            //Data fine impegno
            $val=$organismo->GetDataFineImpegno();
            if($val=="0000-00-00") $val="n.d.";
            if($val=="9999-12-31") $val="a tempo indeterminato";
            $data_fine = new AA_XML_Div_Element("generale-tab-right-panel-data_fine",$right_panel);
            $data_fine->SetStyle("width:100%; margin-bottom: .8em");
            $content='<span style="font-weight:bold">Data fine impegno:</span><br/>'.$val;
            if($canModify)
            {
                $content.='<a  style="margin-left: .5em; display: inline-block; cursor: pointer" id-object="'.$organismo->GetId().'" id-organismo="'.$organismo->GetID().'" class="AA_Button-modify-field ui-icon ui-icon-pencil" task-get="organismi-modify-field-dlg" field-type="date" task-post="update-organismo" field="sDataFineImpegno" field-name="Data fine impegno" title="Modifica">Modifica</a>';
            }          
            $data_fine->SetText($content);

            //partecipazione
            $val=$organismo->GetPartecipazione();
            if($val=="" || $val=="0") $val="indiretta";
            else
            {
                $part=explode("/",$val);
                $val="€ ".$part[0]." pari al ".$part[1]."% delle quote totali";
            }
            $partecipazione= new AA_XML_Div_Element("generale-right-panel-partecipazione",$right_panel);
            $partecipazione->SetStyle("width:100%; margin-bottom: .8em");
            $content='<span style="font-weight:bold">Partecipazione:</span><br/>'.$val;
            if($canModify)
            {
                $content.='<a  style="margin-left: .5em; display: inline-block; cursor: pointer" id-object="'.$organismo->GetId().'" id-organismo="'.$organismo->GetId().'" class="AA_Button-modify-field ui-icon ui-icon-pencil" task-get="organismi-modify-field-dlg" field-type="text" task-post="update-organismo" field="sPartecipazione" field-name="Partecipazione" title="Modifica">Modifica</a>';
            }
            $partecipazione->SetText($content);

            //stato società
            $val=$organismo->GetStatoOrganismo();
            $stato_soc= new AA_XML_Div_Element("generale-right-panel-stato_organismo",$right_panel);
            $stato_soc->SetStyle("width:100%; margin-bottom: .8em");
            $content='<span style="font-weight:bold">Stato società:</span><br/>'.$val;
            if($canModify)
            {
                $content.='<a  style="margin-left: .5em; display: inline-block; cursor: pointer" id-object="'.$organismo->GetId().'" id-organismo="'.$organismo->GetId().'" class="AA_Button-modify-field ui-icon ui-icon-pencil" task-get="organismi-modify-field-dlg" field-type="select" task-post="update-organismo" field="nStatoOrganismo" field-name="Stato società" title="Modifica">Modifica</a>';
            }
            $stato_soc->SetText($content);
        }
        else
        {
            //Data inizio impegno
            $val=$organismo->GetDataInizioImpegno();
            if($val=="0000-00-00") $val="n.d.";
            $data_inizio = new AA_XML_Div_Element("generale-tab-right-panel-data_inizio",$right_panel);
            $data_inizio->SetStyle("width:100%; margin-bottom: .8em");
            $content='<span style="font-weight:bold">Data costituzione:</span><br/>'.$val;
            if($canModify)
            {
                $content.='<a  style="margin-left: .5em; display: inline-block; cursor: pointer" id-object="'.$organismo->GetId().'" id-organismo="'.$organismo->GetID().'" class="AA_Button-modify-field ui-icon ui-icon-pencil" task-get="organismi-modify-field-dlg" field-type="date" task-post="update-organismo" field="sDataInizioImpegno" field-name="Data costituzione" title="Modifica">Modifica</a>';
            }       
            $data_inizio->SetText($content);

            //Data fine impegno
            $val=$organismo->GetDataFineImpegno();
            if(trim($val)=="0000-00-00") $val="n.d.";
            if(trim($val)=="9999-12-31") $val="a tempo indeterminato";
            $data_fine = new AA_XML_Div_Element("generale-tab-right-panel-data_fine",$right_panel);
            $data_fine->SetStyle("width:100%; margin-bottom: .8em");
            $content='<span style="font-weight:bold">Data cessazione:</span><br/>'.$val;
            if($canModify)
            {
                $content.='<a  style="margin-left: .5em; display: inline-block; cursor: pointer" id-object="'.$organismo->GetId().'" id-organismo="'.$organismo->GetId().'" class="AA_Button-modify-field ui-icon ui-icon-pencil" task-get="organismi-modify-field-dlg" field-type="date" task-post="update-organismo" field="sDataFineImpegno" field-name="Data cessazione" title="Modifica">Modifica</a>';
            }
            $data_fine->SetText($content);
        }

        //Funzioni attribuite
        $val=$organismo->GetFunzioni();
        if($val=="") $val="n.d.";
        $funzioni = new AA_XML_Div_Element("funzioni",$this);
        $funzioni->SetStyle("width: 100%; margin-bottom: .8em; border-top: 1px solid gray; text-align: left;");
        $content='<span style="font-weight:bold">Funzioni attribuite:</span><br>'.$val;
        if($canModify)
        {
            $content.='<a  style="margin-left: .5em; display: inline-block; cursor: pointer" id-object="'.$organismo->GetId().'" id-organismo="'.$organismo->GetId().'" class="AA_Button-modify-field ui-icon ui-icon-pencil" task-get="organismi-modify-field-dlg" field-type="text-area" task-post="update-organismo" field="sFunzioni" field-name="Funzioni attribuite" title="Modifica">Modifica</a>';
        }
        $funzioni->SetText($content);

        //note
        $note=new AA_XML_Div_Element("generale-tab-note",$this);
        $note->SetStyle('width:100%; border-top: 1px solid gray; margin-bottom: .8em;');
        $content='<span style="font-weight:bold">Note:</span><br>'.$organismo->GetNote();
        if($canModify)
        {
            $content.='<a  style="margin-left: .5em; display: inline-block; cursor: pointer" id-object="'.$organismo->GetId().'" id-organismo="'.$organismo->GetId().'" class="AA_Button-modify-field ui-icon ui-icon-pencil" task-get="organismi-modify-field-dlg" field-type="text-area" task-post="update-organismo" field="sNote" field-name="Note" title="Modifica">Modifica</a>';
        }
        $note->SetText($content);
        #-------------------
    }
}
#-------------------------------------

#Classe template per la visualizzazione dei dati contabili sul report
Class AA_OrganismiReportDatiContabiliListTemplateView extends AA_GenericTableTemplateView
{
    public function __construct($id="AA_OrganismiReportDatiContabiliListTemplateView",$parent=null,$organismo=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo,array("evidentiate-rows"=>true,"title"=>"Dati contabili","border"=>"1px solid black;","style"=>"font-size: smaller; margin-bottom: 1em; margin-top: 1em"));

        //Solo gli ultimi 5 anni
        $dal=(date("Y")-6)."-12-31";
        $daticontabili=$organismo->GetDatiContabili($dal);
        $num_daticontabili=sizeof($daticontabili);
        if($num_daticontabili>0)
        {

            $this->SetColSizes(array("5","19","19","22","32"));
            if($organismo->GetTipologia(true) == AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) $this->SetHeaderLabels(array("Anno","Oneri totali in €<sup>1</sup>","Tipo di bilancio","Risultati di bilancio in €<sup>2</sup>", "Note"));    
            else $this->SetHeaderLabels(array("Anno","Oneri totali in €<sup>1</sup>","Tipo di bilancio","Risultati di amministrazione in €<sup>2</sup>", "Note"));    

            $curRow=1;
            foreach($daticontabili as $id=>$curDatoContabile)
            {
                //Anno
                $this->SetCellText($curRow,0,$curDatoContabile->GetAnno(), "center");

                //oneri
                $curVal=preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "", $curDatoContabile->GetOneriTotali());
                if($curVal=="") $curVal="n.d.";
                $this->SetCellText($curRow,1,$curVal, "center");

                //tipo bilancio
                $curval=$curDatoContabile->GetTipologia();
                if($curval=="" || $curval=="n.d.") $curval="n.d.";
                $this->SetCellText($curRow,2,$curval,"center");

                //Risultati
                $curVal=preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "", $curDatoContabile->GetRisultatiBilancio());
                if($curVal=="" || $curDatoContabile->GetTipologia(true) == 0) $curVal="n.d.";
                if(strpos($curVal,"-")!==false)
                {
                    $color="red";
                }
                else $color="";
                $this->SetCellText($curRow,3,$curVal,"center",$color);

                //Note
                $note=$curDatoContabile->GetNote();
                $ratio=5*strlen($note)/$num_daticontabili;
                if(strlen($note) > $ratio) $note=substr($note,0,$ratio)."...";
                $note_box=$this->GetCell($curRow,4);
                $note_box->SetStyle("font-size: smaller;", true);
                $text_align="center";
                if(strlen($note) > 75) $text_align="left";
                $this->SetCellText($curRow,4,$note, $text_align);

                $curRow++;
            }
            $footer="<div style='font-style: italic; text-align: left; width: 100%; margin-top: 1em;font-size: smaller;'>1. Somme effettivamente liquidate.</div>";
            if($organismo->GetTipologia(true) == AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) $footer.="<div style='font-style: italic; text-align: left; width: 100%; margin-top: .5em;font-size: smaller;'>2. Risultati di bilancio d'esercizio dell'anno di riferimento.</div>";
            else $footer.="<div style='font-style: italic; text-align: left; width: 100%; margin-top: .5em;font-size: smaller;'>2. Risultati di amministrazione dell'anno di riferimento.</div>";

            $this->SetText($footer,false);
        }
    }
}

#Classe template per la visualizzazione dei dati contabili sul report
Class AA_OrganismiReportProvvedimentiListTemplateView extends AA_GenericTableTemplateView
{
    public function __construct($id="AA_OrganismiReportProvvedimentiListTemplateView",$parent=null,$organismo=null, $user=null,$provvedimenti=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo,array("evidentiate-rows"=>true,"title"=>"Provvedimenti","border"=>"1px solid black;","width"=>"99%","style"=>"font-size: smaller; margin-bottom: 1em; margin-top: 1em"));

        //Provvedimenti
        if(!$provvedimenti) $provvedimenti=$organismo->GetProvvedimenti();

        if(sizeof($provvedimenti) > 0)
        {
            //Chiama il costruttore della classe base
            $this->SetColSizes(array("5","30","50","10"));
            $this->SetHeaderLabels(array("Anno","Tipo","Estremi","Url"));    

            $curRow=1;
            foreach($provvedimenti as $id=>$curProvvedimento)
            {
                //Anno
                $this->SetCellText($curRow,0,$curProvvedimento->GetAnno(), "center");

                //tipo provvedimento
                $curval=$curProvvedimento->GetTipologia();
                if($curval=="" || $curval=="n.d.") $curval="n.d.";
                $this->SetCellText($curRow,1,$curval,"center");

                //estremi
                $note=$curProvvedimento->GetEstremi();
                $text_align="left";
                $this->SetCellText($curRow,2,$note, $text_align);

                if($curProvvedimento->GetUrl() !="")
                {
                    //url
                    $url="<a href='".$curProvvedimento->GetUrl()."' target='_blank'>consulta</a>";
                    $this->SetCellText($curRow,3,$url, "center");
                }
                else
                {
                    $url="<a href='https://".AA_Config::AA_DOMAIN_NAME.$curProvvedimento->GetFilePublicPath()."' target='_blank'>consulta</a>";
                    $this->SetCellText($curRow,3,$url, "center");
                }

                $curRow++;
            }
        }
    }
}

#Classe template per la visualizzazione della lista delle nomine sul report
Class AA_OrganismiReportNomineListTemplateView extends AA_GenericTableTemplateView
{
    public function __construct($id="AA_OrganismiReportNomineListTemplateView",$parent=null,$organismo=null, $user=null,$full=false)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        if(!($organismo instanceof AA_Organismi))
        {
            AA_Log::Log(__METHOD__." - organismo non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$organismo,array("evidentiate-rows"=>true,"title"=>"Rappresentanti dell'Amministrazione Regionale negli organi di governo e incarichi di Amministratore","border"=>"1px solid black;","style"=>"font-size: smaller; margin-bottom: 1em; margin-top: 1em"));
        
        //solo gli ultimi 5 anni
        $dal=(date("Y")-6)."-12-31";
        $nomine=$organismo->GetNomine($dal,"",false);

        $num_nomine=sizeof($nomine);
        if($num_nomine>0)
        {
            $this->SetColSizes(array("10","10","10","10","10","10", "10","25"));
            $this->SetHeaderLabels(array("Nome<sup>1</sup>","Cognome<sup>1</sup>","Incarico","Data inizio", "Data fine", "Trattamento econ.<sup>2</sup>", "Documenti", "Note"));    
         
            $curRow=1;

            $num_nomine_ras=0;
            $curDate=date("Y-m-d");

            $nomineNoPub=AA_Organismi_Const::GetNomineDaOscurare();
            foreach($nomine as $id=>$curNomina)
            {
                if(($curNomina->GetTipologia(true)&$nomineNoPub) == 0 || $full)
                {
                    //AA_Log::Log(__METHOD__." - pubblico: ".$curNomina->GetNome()." ".$curNomina->GetCognome()."  - tipologia: ".$curNomina->GetTipologia()." - verifica: ".($curNomina->GetTipologia(true)&AA_Organismi_Const::AA_NOMINE_NON_PUBBLICARE),100);
                    $color="";
                    $dataFine=$curNomina->GetDataFine();
                    if($curNomina->IsNominaRas())
                    {
                        $color="green";
                        if($dataFine >= $curDate)
                        {
                            $num_nomine_ras++;
                        }
                    }

                    //Nome
                    $this->SetCellText($curRow,0,$curNomina->GetNome(), "center",$color);

                    //Cognome
                    $this->SetCellText($curRow,1,$curNomina->GetCognome(), "center",$color);

                    //tipo nomina
                    $this->SetCellText($curRow,2,$curNomina->GetTipologia(),"center");

                    //Data inizio
                    $this->SetCellText($curRow,3,$curNomina->GetDataInizio(), "center");

                    $color="";
                    if($dataFine < $curDate)
                    {
                        $color="red";
                    }
                    
                    $this->SetCellText($curRow,4,$curNomina->GetDataFine(), "center", $color);

                    //compenso spettante
                    $curVal="€ ".preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "", $curNomina->GetCompensoSpettante());
                    if($curVal=="€ ") $curVal="n.d.";
                    $this->SetCellText($curRow,5,$curVal, "center");

                    //compenso erogato
                    //$curVal="€ ".preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "", $curNomina->GetCompensoErogato());
                    //if($curVal=="€ ") $curVal="n.d.";
                    //$this->SetCellText($curRow,6,$curVal, "center");
                    
                    //Documenti------------------
                    $box=$this->GetCell($curRow,6);
                    $box->SetStyle("text-align: center", true);

                    if(sizeof($curNomina->GetDocs()) > 0)
                    {
                        //vedi i documenti
                        $vedi=new AA_XML_A_Element("",$box); $vedi->SetClass("AA_Button_Nomina_View_Docs ui-icon ui-icon-disk"); $vedi->SetAttribs(array("href"=>"https://amministrazioneaperta.regione.sardegna.it".AA_Organismi_const::AA_ORGANISMI_NOMINE_DOCS_PUBLIC_PATH."/docs.php?all=1&nomina=".$curNomina->GetId(), "id-object"=>$organismo->GetId(),"id-nomina"=>$curNomina->GetId(),"title"=>"download"));
                        $vedi->SetStyle("display: inline-block; margin-right: 1em; cursor: pointer");
                        $vedi->SetText("download");
                        //---------------------------
                    }
                    else
                    {
                        $this->SetCellText($curRow,6,"n.d.", "center");
                    }
                    #--------------------------------

                    //Note
                    $note=$curNomina->GetNote();
                    $ratio=5*strlen($note)/$num_nomine;
                    if($ratio < 80) $ratio=80;
                    if(strlen($note) > $ratio) $note=substr($note,0,$ratio)."...";
                    $note_box=$this->GetCell($curRow,7);
                    $note_box->SetStyle("font-size: smaller;", true);
                    $text_align="center";
                    if(strlen($note) > 75) $text_align="left";
                    $this->SetCellText($curRow,7,$note, $text_align);
                    $curRow++;
                }
                else
                {
                    //AA_Log::Log(__METHOD__."Da non pubblicare: ".$curNomina->GetNome()." ".$curNomina->GetCognome()." ".$curNomina->GetTipologia(),100);
                }
            }

            $footer="<div style='font-style: italic; text-align: left; width: 100%; margin-top: .3em;font-size: smaller;'>1. I nominativi dei rappresentanti dell'Amministrazione Regionale sono indicati in colore verde.</div>";
            $footer.="<div style='font-style: italic; text-align: left; width: 100%; margin-top: .3em;font-size: smaller;'>2. Il trattamento economico complessivo è la somma degli emolumenti spettanti relativi all'arco temporale di validità dell'incarico o alla data di aggiornamento del presente prospetto qualora l'incarico sia ancora in corso.</div>";
            if($num_nomine_ras >0) $footer.="<div style='text-align: left; width: 100%; margin-top: .8em;'>Il numero dei rappresentanti in carica dell'Amministrazione Regionale è ".$num_nomine_ras.".</div>";
            else $footer.="<div style='text-align: left; width: 100%; margin-top: .8em;'>Non sono presenti rappresentanti in carica dell'Amministrazione Regionale.</div>";

            $this->SetText($footer,false);
        }
    }
}

#Classe template per la visualizzazione del contenuto del modulo
Class AA_OrganismiTemplateViewModuleHeaderContent extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_OrganismiTemplateViewModuleHeaderContent",$parent=null, $user=null)
    {
        //Verifica utente
        if(!($user instanceof AA_User) || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return;
            }
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent);
    }
}

#Classe per la gestione dei compensi per gli incarichi
Class AA_OrganismiNomineCompensi
{
    //identificativo
    protected $nId=0;
    public function SetId($val=0)
    {
        $this->nId=trim($val);
    }
    public function GetId()
    {
        return $this->nId;
    }
    
    //anno
    protected $nAnno="2021";
    public function SetAnno($val="")
    {
        if(trim($val)=="") $val=Date('Y');
        $this->nAnno=$val;
    }
    public function GetAnno()
    {
        return $this->nAnno;
    }
    
    //parte fissa
    protected $nParteFissa="";
    public function SetParteFissa($val="")
    {
        $this->nParteFissa=trim($val);
    }
    public function GetParteFissa()
    {
        return $this->nParteFissa;
    }
    
    //parte variabile
    protected $nParteVariabile="";
    public function SetParteVariabile($val="")
    {
        $this->nParteVariabile=trim($this->nParteVariabile);
    }
    public function GetParteVariabile()
    {
        return $this->nParteVariabile;
    }
    
    //rimborsi
    protected $nRimborsi="";
    public function SetRimborsi($val="")
    {
        $this->nRimborsi=trim($val);
    }
    public function GetRimborsi()
    {
        return $this->nRimborsi;
    }
    
    //note
    protected $sNote="";
    public function SetNote($val="")
    {
        $this->sNote=trim($val);
    }
    public function GetNote()
    {
        return $this->sNote;
    }
    
    public function __construct($id=0, $anno="",$parte_fissa="",$parte_variabile="",$rimborsi="",$note="")
    {
        if($id > 0) $this->nId=$id;
        if($anno > 0) $this->nAnno=$anno;
        if($anno=="") $this->nAnno=Date('Y');
        $this->nParteFissa=$parte_fissa;
        $this->nParteVariabile=$parte_variabile;
        $this->nRimborsi=$rimborsi;
        $this->sNote=$note;
        
        //AA_Log::Log(__METHOD__." - id: ".$id." - "."anno: ".$anno." - parte fisso: ".$parte_fissa." - parte_variabile: ".$parte_variabile." - rimborsi: ".$rimborsi." - note".$note, 100);
    }
    
    //Rappresentazione xml
    public function toXml()
    {
        $return="<compenso>";
        $return.="<anno>".$this->GetAnno()."</anno>";
        $return.="<parte_fissa>".$this->GetParteFissa()."</parte_fissa>";
        $return.="<parte_variabile>".$this->GetParteVariabile()."</parte_variabile>";
        $return.="<rimborsi>".$this->GetRimborsi()."</rimborsi>";
        $return.="<note>".AA_Utils::xmlentities($this->GetNote())."</note>";
        $return.="</compenso>";
        return $return;
    }
}
?>