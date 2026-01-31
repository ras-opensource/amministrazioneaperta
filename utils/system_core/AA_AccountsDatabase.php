<?php
class AA_AccountsDatabase extends PDO_Database
{
    //Parametri di connessione al DB
    static protected $oPdo=null;
    static protected $dbName="dbname";
	static protected $dbHost="localhost";
	static protected $dbUser="dbuser";
	static protected $dbPwd="dbpwd";
    protected $nLastInsertId = 0;
    static protected $sLastQuery = "";
    static protected $sLastErrorMessage="";

    private $AA_DBHOST = AA_Config::AA_ACCOUNTS_DBHOST;
    private $AA_DBNAME = AA_Config::AA_ACCOUNTS_DBNAME;
    private $AA_DBUSER = AA_config::AA_ACCOUNTS_DBUSER;
    private $AA_DBPWD = AA_Config::AA_ACCOUNTS_DBPWD;

    public function __construct($bReset = false)
    {
        if (!$this->Initialize($this->AA_DBNAME,$this->AA_DBHOST, $this->AA_DBUSER, $this->AA_DBPWD, $bReset)) {
            AA_Log::Log(__METHOD__ . " - Errore nella connessione al DB: " . $this->GetErrorMessage(), 100);
            return;
        }
    }
}
