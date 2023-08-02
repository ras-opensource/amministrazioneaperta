<?php
require_once __DIR__ . '../vendor/autoload.php';
use Exception;
use MongoDB\Client;
use MongoDB\Driver\ServerApi;

Class AA_NosqlDatabase
{
    private $bValid=false;
    public function isValid()
    {
        return $this->bValid;
    }

    private $connection=null;

    public function __construct($db="AmministrazioneAperta",$server="127.0.0.1",$port="27017",$user="",$pwd="")
    {
        $this->connection=new MongoDB\Client("mongodb://$user:$pwd@$server:$port");

        try 
        {
            // Send a ping to confirm a successful connection
            $this->connection->selectDatabase($db)->command(['ping' => 1]);
            $this->bValid=true;
            
        } catch (Exception $e) {
            AA_Log::Log(__METHOD__." - errore durante la connessione al db: ".$e->getMessage(),100);
        }
    }
}