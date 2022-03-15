<?php

class Database
{
	protected $oSql=null;
	protected $oRecordSet=null;
	protected $oResult=null;
	protected $dbConnection=null;
	public $lastInsertId;
	public $nAffectedRows=0;
	public function GetAffectedRows()
	{
		return $this->nAffectedRows;
	}

	private $bValid=false;
	public $lastError="";
	public function GetErrorMessage()
	{
		return $this->lastError;
	}

	static protected $sLastErrorMessage="";
	static public function GetLastErrorMessage()
	{
		return self::$sLastErrorMessage;
	}

	static public $lastQuery="";
	static public function GetLastQuery()
	{
		return self::$lastQuery;
	}

	static protected $LAST_INSERT_DB=0;
	
	function Database( $host='localhost', $user='monitspese', $pass='1q2w3e4r', $db='monitspese', $utf8=true)
	{
		$connessione = new mysqli($host , $user, $pass, $db);
		if($connessione->connect_errno)
		{
			$this->lastError=$connessione->connect_error;
			self::$sLastErrorMessage=$this->lastError;
			error_log('errore di connessione al server:'.$connessione->connect_error);
			$this->oRecordSet = new RecordSet(false);
			return;
		}

		$this->dbConnection=$connessione;
		/*$select_db = mysqli_select_db($db, $connessione) or error_log('errore di connessione al db:'.mysql_error());*/

		if($utf8) $this->dbConnection->query("SET NAMES 'utf8'");

		$this->lastInsertId=0;
		$this->bValid=true;
	}

	public function __destruct()
	{
		//libera le risorse
		if($this->oResult instanceof mysqli_result) $this->oResult->free_result();
	}
	
	//Restituisce l'ultimo id inserito al livello globale
	static public function LastInsertId()
	{
		return self::$LAST_INSERT_DB;
	}

	//Restituisce l'ultimo id inserito al livello globale
	static public function GetLastInsertId()
	{
		return self::$LAST_INSERT_DB;
	}

	//Effettua una query sul database
	//Restituisce il numero di record in caso di successo.
	//Restituisce false in caso di errore e il campo $this->lastError contiene l'errore.
	function Query($sql)
	{
		//error_log("Database::Query(".$sql.")");

		$this->lastError="";

		if(!$this->bValid)
		{
			$this->lastError="Connessione al db non inizializzata.";
			self::$sLastErrorMessage=$this->lastError;
			error_log($this->lastError);
			//Libera il result set se è ancora istanziato
			if($this->oResult instanceof mysqli_result) mysqli_free_result($this->oResult);

			return FALSE;
		} 

		//Libera il result set se è ancora istanziato
		if($this->oResult instanceof mysqli_result) mysqli_free_result($this->oResult);

		$result = $this->dbConnection->query($sql);
		if($result===FALSE)
		{
			error_log("result: ".$this->lastError);
			$this->lastError = $this->dbConnection->error." - result:".$result;
			self::$sLastErrorMessage=$this->lastError;

			$this->nAffectedRows=0;
			return FALSE;
		}

		$this->lastInsertId=$this->dbConnection->insert_id;
		if(stripos($sql,"insert")===0) 
		{
			self::$LAST_INSERT_DB=$this->lastInsertId;
		}

		self::$lastQuery=$sql;

		if($result===true) 
		{
			$this->nAffectedRows=$this->dbConnection->affected_rows;
			//error_log("NON SELECT: ".$sql);
			return TRUE;
		}
		else 
		{
			$this->nAffectedRows=mysqli_num_rows($result);
			$this->oResult=$result;
			//error_log("SELECT");
			return TRUE;
		}
				
	}

	//Restituisce i record trovati come array
	public function GetResult()
	{
		if($this->oResult instanceof mysqli_result) return $this->oResult->fetch_all(MYSQLI_ASSOC);
		else return array();
	}
	
	function SetSql($sql) 
	{
		$this->oSql = new SqlQuery($sql);
	}
	
	function GetSql() 
	{
		return $this->oSql->GetSql();
	}
	
	function ExecQuery()
	{
		if(isset($this->oSql)) 
		{
			$result=$this->Query($this->GetSql());
			return $result;
		}
		else return false;
		
	}
	
	function GetRecordSet()
	{
		if($this->oResult instanceof mysqli_result)
		{
			return new RecordSet($this->oResult);
		}

		else return new RecordSet(false);
	}

	function isValid()
	{
		return $this->bValid;
	}
}

class SqlQuery
{
	public $sql;
	
	function SqlQuery($sql)
	{
		$this->SetSql($sql);
	}
	
	function SetSql($sql)
	{
		$this->sql = $sql;
	}
	
	function GetSql()
	{
		return $this->sql;
	}
}

class RecordSet
{
	public $result=false;
	public $numRows=0;
	public $bValid=false;
	public $curRow=null;
	public $nCurRow=-1;
	
	function __destruct() 
	{
       if($this->bValid && $this->result instanceof mysqli_result) 
	   {
		   //error_log("Chiusura automatica recordset.");
		   //$this->result->free_result();
	   }
   	}

	function RecordSet($result)
	{
		if($result===false)
		{
			$this->nCurRow=-1;
			$this->bValid = false;
		}
		else 
		{
			if($result instanceof mysqli_result)
			{
				$this->result=$result;
				if(($this->curRow = mysqli_fetch_assoc($this->result))===false) $this->bValid = false;
				else 
				{
					$this->bValid = true;
					$this->nCurRow=0;
					$this->numRows=mysqli_num_rows($this->result);
				}	
			}
		}
	}

	function SetResult($result)
	{
		$this->result = $result;
	}
	
	function GetResult()
	{
		return $this->result;
	}
	
	function Get($campo,$grezzo=true)
	{
		if (!$this->bValid) return null;
		if(isset($this->curRow["$campo"]))
		{
			if($grezzo) return $this->curRow["$campo"];
			else return htmlentities($this->curRow["$campo"],ENT_QUOTES,"ISO8859-15");
		}
		else return null;
	}
	
	function MoveNext()
	{
		if (!$this->bValid && $this->curRow==-1) return false;
		 return $this->Move($this->nCurRow+1);
	}
	
	function Move($pos)
	{
		if (!$this->bValid && $this->nCurRow==-1) return false;
		
		if ($pos >= $this->numRows || $pos < 0) return false;
		
		if(mysqli_data_seek($this->result,$pos))
		{
			if(($this->curRow = mysqli_fetch_assoc($this->result))===false) {$this->bValid = false;return false;}
			else 
			{
				$this->nCurRow = $pos;
				$this->bValid = true;
				return true;
			}			
		}
		else return false;
	}
	
	function MoveLast()
	{
		if (!$this->bValid && $this->nCurRow==-1) return false;
		return $this->Move($this->numRows-1);	
	}
	function MoveFirst()
	{
		if (!$this->bValid && $this->nCurRow==-1) return false;
		return $this->Move(0);
	}
	function MovPrev()
	{
		if (!$this->bValid && $this->nCurRow==-1) return false;
		 return $this->Move($this->nCurRow-1);
	}
	function GetCurPos()
	{
		if (!$this->bValid && $this->nCurRow==-1) return false;
		return $this->nCurRow;
	}
	function isValid()
	{
		return $this->bValid;
	}
	function GetNumCount()
	{
		return $this->numRows;
	}
	function GetCount()
	{
		return $this->numRows;
	}

	function Close()
	{
		if($this->bValid)
		{
			mysqli_free_result($this->result);
		}
	}
}

//Connessione datatbase PDO
class PDO_Database
{
	//PDO object
	static private $oPdo=null;

	//Ultimo id inserito
	static protected $nLastInsertId = 0;
	public static function GetLastInsertId()
	{
		return self::$nLastInsertId;
	}

	//ultima query eseguita con successo
	static protected $sLastQuery = "";
	static public function GetLastQuery()
	{
		return self::$sLastQuery;
	}

	static protected $sLastErrorMessage="";
	static public function GetLastErrorMessage()
	{
		return self::$sLastErrorMessage;
	}

	//PDO statment
	protected $oStat=null;

	//result set
	protected $aResultSet=array();
	public function GetResultSet($mode=PDO::FETCH_ASSOC)
	{
		if($this->oStat instanceof PDOStatement)
		{
			$this->aResultSet=$this->oStat->fetchAll($mode);
			return $this->aResultSet;
		}
		
		return array();
	}

	//numero di righe interessate dall'ultima query (INSERT, UPDATE, DELETE)
	protected $nAffectedRows=0;
	public function GetAffectedRows()
	{
		return $this->nAffectedRows;
	}

	//db params
	static private $dbName="dbname";
	static private $dbHost="localhost";
	static private $dbUser="dbuser";
	static private $dbPwd="dbpwd";

	//error messages
	protected $sError="";
	public function GetErrorMessage()
	{
		return $this->sError;
	}

	//status
	// -2= non inizializzato
	// -1= errore
	//  0= inizializzato
	protected $nStatus= -2; 
	public function GetStatus()
	{
		return $this->nStatus;
	}
	public function IsInitialized()
	{
		if($this->nStatus==0) return true;
		return false;
	}

	//flag di validità
	protected $bValid=false;
	public function IsValid()
	{
		return $this->bValid;
	}

	//Inizializza la connessione al DB
	protected function Initialize($dbname="dbname",$host="localhost",$user="dbuser",$pwd="dbpwd", $bReset=false)
	{
		//Connessione già inizializzata
		if(PDO_Database::$oPdo instanceof PDO)
		{
			if(!$bReset)
			{
				$this->bValid=true;
				$this->nStatus=0;
				$this->sError="";
	
				return true;
			}
			else
			{
				$this->oStat=null;
				PDO_Database::$oPdo=null;
				$this->bValid=false;
				$this->nStatus=-2;
				$this->sError="";	
			} 
		}
		
		try
		{
			PDO_Database::$oPdo = new PDO('mysql:host='.$host.';dbname='.$dbname,$user,$pwd,
			array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
				PDO::ATTR_PERSISTENT => true
			));

			$this->bValid=true;
			$this->nStatus=0;
			$this->sError="";

			PDO_Database::$dbName=$dbname;
			PDO_Database::$dbHost=$host;
			PDO_Database::$dbUser=$user;
			PDO_Database::$dbPwd=$pwd;

			return true;
		}
		catch(PDOException $pe)
		{
			$this->nStatus=-1;
			$this->bValid=false;
			$this->sError=$pe->getMessage()." (code: ".$pe->getCode().")";
			return false;
		}
	}

	//factory
	public static function GetInstance($dbname="dbname",$host="localhost",$user="dbuser",$pwd="dbpwd")
	{
		$db = new PDO_Database();
		$db->Initialize($dbname,$host,$user,$pwd);

		return $db;
	}

	public function __destruct()
	{
		$this->oStat=null;
	}

	//Chiudi l'istanza precedentemente aperta (utile per cambiare db)
	static public function CloseInstance()
	{
		if(PDO_Database::$oPdo instanceof PDO)
		{
			PDO_Database::$oPdo= null;
		}
	}

	//Prepara ed esegue una query parametrica
	public function Query($sql,$params=array(),$sql_params=array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY))
	{
		if(sizeof($params) == 0 || $params == null)
		{
			return $this->ExecQuery($sql);
		}

		//non inizializzato
		if($this->nStatus==-2)
		{
			$this->sError=__METHOD__." - Connessione al DB non inizializzata.";
			self::$sLastErrorMessage=$this->sError;

			return false;
		}

		//non valid
		if(!$this->bValid)
		{
			$this->sError=__METHOD__." - Connessione al DB non valida.";
			self::$sLastErrorMessage=$this->sError;
			return false;
		}

		if(PDO_Database::$oPdo instanceof PDO)
		{
			try
			{
				//prepara la query
				$this->oStat=null;
				$this->oStat=PDO_Database::$oPdo->prepare($sql,$sql_params);
				
				//Esegue la query
				if($this->oStat->execute($params))
				
				//Numero di righe interessate
				$this->nAffectedRows=$this->oStat->rowCount();
				self::$sLastQuery=$sql;

				if(stripos($sql,"INSERT") !==false)
				{
					//Ultimo id inserito
					self::$nLastInsertId=PDO_Database::$oPdo->lastInsertId();
					$this->nLastInsertId=self::$nLastInsertId;	
				}

				$this->nStatus=0;

				return true;
			}
			catch(PDOException $pe)
			{
				$this->nStatus=-1;
				$this->sError=$pe->getMessage()." (code: ".$pe->getCode().")";
				self::$sLastErrorMessage=$this->sError;
				$this->oStat=null;
				return false;
			}
		}
	}

	//restituisce il risultato dell'ultima query
	public function GetResult()
	{
		if($this->oStat) return $this->oStat;
		else return array();
	}

	//Esegue una query diretta
	public function ExecQuery($sql)
	{
		//non inizializzato
		if($this->nStatus==-2)
		{
			$this->sError=__METHOD__." - Connessione al DB non inizializzata.";
			self::$sLastErrorMessage=$this->sError;
			return false;
		}

		//non valid
		if(!$this->bValid)
		{
			$this->sError=__METHOD__." - Connessione al DB non valida.";
			self::$sLastErrorMessage=$this->sError;
			return false;
		}

		if(PDO_Database::$oPdo instanceof PDO)
		{
			try
			{
				//prepara la query
				$this->oStat=null;
				$this->oStat=PDO_Database::$oPdo->Query($sql,PDO::FETCH_ASSOC);

				//Numero di righe interessate
				$this->nAffectedRows=$this->oStat->rowCount();
				self::$sLastQuery=$sql;

				if(stripos($sql,"INSERT") !==false)
				{
					//Ultimo id inserito
					$this->nLastInsertId=PDO_Database::$oPdo->lastInsertId();
					self::$nLastInsertId=$this->nLastInsertId;	
				}

				$this->nStatus=0;

				return true;
			}
			catch(PDOException $pe)
			{
				$this->nStatus=-1;
				$this->sError=$pe->getMessage()." (code: ".$pe->getCode().")";
				self::$sLastErrorMessage=$this->sError;
				$this->oStat=null;
				
				return false;
			}
		}
	}
}
?>