<?php
class AA_Database extends PDO_Database
{
    static protected $oPdo=null;
    static protected $dbName="dbname";
	static protected $dbHost="localhost";
	static protected $dbUser="dbuser";
	static protected $dbPwd="dbpwd";
    protected $nLastInsertId = 0;
    static protected $sLastQuery = "";
    static protected $sLastErrorMessage="";

    //Parametri di connessione al DB
    private $AA_DBHOST = AA_Config::AA_DBHOST;
    private $AA_DBNAME = AA_Config::AA_DBNAME;
    private $AA_DBUSER = AA_config::AA_DBUSER;
    private $AA_DBPWD = AA_Config::AA_DBPWD;

    public function __construct($bReset = false)
    {
        if (!$this->Initialize(AA_Config::AA_DBNAME, AA_Config::AA_DBHOST, AA_Config::AA_DBUSER, AA_Config::AA_DBPWD, $bReset)) {
            AA_Log::Log(__METHOD__ . " - Errore nella connessione al DB: " . $this->GetErrorMessage(), 100);
            return;
        }
    }
}
