<?php
class AA_Utils
{
    //password_hashes
    static public function password_hash($password="")
    {
        if(function_exists("password_hash"))
        {
            return password_hash($password,PASSWORD_DEFAULT);
        }

        return crypt($password,uniqid());
    }

    //password_verify
    static public function password_verify($password="",$hash="")
    {
        if(function_exists("password_verify"))
        {
            return password_verify($password,$hash);
        }

        AA_Log::Log(__METHOD__." - crypt()",100);
        if(crypt($password,$hash)==$hash) return true;
    
        return false;
    }

    //Formata un numero
    static public function number_format($number, $decimals='', $sep1='', $sep2='',$round=true) 
    {
        if($round) return number_format(floatval($number), $decimals, $sep1, $sep2);

        $resto=($number * pow(10 , $decimals + 1) % 10 );
        if ($resto >= 5)
        {
            $diff=$resto*pow(10 , -($decimals+1));
            //AA_Log::Log(__METHOD__." - cambio da: ".$number." a: ".($number-$diff),100);
            $number -= $diff;
        }  
        return number_format(floatVal($number), $decimals, $sep1, $sep2);
    }

    //Accoda il log attuale al log di sessione
    static public function AppendLogToSession()
    {
        //$_SESSION['log'].=AA_Log::toHTML(true);
    }

    //funzione per la generazione di identificativi univoci
    static public function uniqid($lenght = 13)
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            return uniqid();
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

    static public function GetSessionLog()
    {
        $return = "";
        if(!isset($_SESSION['log'])) $session_log=array();
        else $session_log = array_reverse(unserialize($_SESSION['log']));

        foreach ($session_log as $key => $curLogString) {
            if(is_string($curLogString)) $curLog=unserialize($curLogString);
            else $curLog=$curLogString;
            $return .= '<div style="display:flex; flex-direction: row; justify-content: space-between; align-items: stretch; flex-wrap: wrap; width: 100%; border: 1px solid black; margin-bottom: 1em; font-size: smaller">';
            $return .= '<div style="width: 8%; border: 1px solid black; text-align: center; font-weight: bold; background-color: #DBDBDB; padding: .1em;">Data</div>';
            $return .= '<div style="display: flex; align-items: flex-start; width: 4%; border: 1px solid black; text-align: center; font-weight: bold; background-color: #DBDBDB; padding: .1em;"><div style="width: 100%">Livello</div></div>';
            $return .= '<div style="width: 42%; border: 1px solid black;text-align: center; font-weight: bold; background-color: #DBDBDB; padding: .1em;">Messaggio</div>';
            $return .= '<div style="width: 45%; border: 1px solid black;text-align: center; font-weight: bold; background-color: #DBDBDB;padding: .1em;">backtrace</div>';
            $return .= '<div style="width: 8%; border: 1px solid black;text-align: center; padding: .1em;"><span>' . $curLog->GetTime() . '</span></div>';
            $return .= '<div style="display: flex; align-items: flex-start; width: 4%; border: 1px solid black; text-align: center; padding: .1em;"><div style="width: 100%">' . $curLog->GetLevel() . '</div></div>';
            
            $msg=$curLog->GetMsg();
            if(is_array($msg))
            {
                $result="";
                foreach($msg as $curMsg)
                {
                    $result.=htmlentities($curMsg)."<br>";
                }

                $msg=$result;
            }
            else
            {
                $msg=htmlentities($msg);
            }
            $return .= '<div style="width: 42%; border: 1px solid black; padding: .1em; overflow: auto; word-break: break-all;">' .$msg. '</div>';
            $return .= '<div style="width: 45%; border: 1px solid black; padding: .1em; font-size: smaller">';
            $html = "";
            $i = 0;
            foreach ($curLog->GetBackTrace() as $key => $value) {
                if ($i > 0) {
                    $html .= "<p>#" . $key . " - " . $value['file'] . " (line: " . $value['line'] . ")";
                    $html .= "<br/>";
                    if(isset($value['class'])) $html.=$value['class'];
                    if(isset($value['type']))  $html.=$value['type'];
                    if(isset($value['function'])) 
                    {
                        $html.=$value['function'] . "(";
                        $separatore = "";
                        foreach ($value['args'] as $curArg) {
                            if ($curArg == "") $html .= $separatore . '""';
                            else if (!is_array($curArg))
                            {
                                if(is_string($curArg)) $html .= $separatore . htmlentities($curArg);
                                else $html .= $separatore . htmlentities(print_r($curArg, true));
                            } 
                            $separatore = ",";
                        }
                        $html .= ")";
                    }
                    $html.="</p>";
                }
                $i++;
            }
            if ($html == "") $html = "&nbsp;";

            $return .= $html . '</div></div>';
        }

        return $return;
    }

    //Reinizializza il log di sessione
    static public function ResetSessionLog()
    {
        $_SESSION['log'] = "";
    }

    //Check SQL strings
    static public function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        AA_Log::Log(get_class() . "->GetSQLValueString($theValue, $theType, $theDefinedValue, $theNotDefinedValue)");

        $theValue = addslashes($theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }

        return $theValue;
    }

    //Old stuff user check
    static public function CheckUser($reqUser, $levelMin, $id_assessorato = 0, $id_direzione = 0, $id_servizio = 0, $id_settore = 0)
    {
        AA_Log::Log(get_class() . "->CheckUser($reqUser, $levelMin, $id_assessorato, $id_direzione, $id_servizio, $id_settore)");

        if (!$reqUser) return true;

        $user = AA_User::GetCurrentUser();
        if ($user->IsGuest()) return false;

        if ($user->GetLevel() > $levelMin) return false;

        $struct = $user->GetStruct();
        if ($id_assessorato != 0 && $struct->GetAssessorato(true) != $id_assessorato) return false;
        if ($id_direzione != 0 && $struct->GetDirezione(true) != $id_direzione) return false;
        if ($id_servizio != 0 && $struct->GetServizio(true) != $id_servizio) return false;

        return true;
    }

    //Verifica che la stringa siua una data valida
    static function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    //Rimuove le informazioni di autenticazione più vecchie di 1 giorno
    static public function CleanOldTokens()
    {
        AA_Log::Log(get_class() . "->CleanOldTokens()", 100);

        $db = new Database();

        $query = "DELETE from tokens where data_rilascio < '" . date("Y-m-d") . "'";
        $db->Query($query);
    }

    //Sostituisce le entità xml con i codici
    static public function Xml_entities($string)
    {
        return strtr(
            $string,
            array(
                "<" => "&lt;",
                ">" => "&gt;",
                '"' => "&quot;",
                "'" => "&apos;",
                "&" => "&amp;",
                "€" => "&#8364;"
            )
        );
    }

    //Sostituisce le entità xml con i codici
    static public function xmlentities($string)
    {
        return strtr(
            $string,
            array(
                "<" => "&lt;",
                ">" => "&gt;",
                '"' => "&quot;",
                "'" => "&apos;",
                "&" => "&amp;",
                "€" => "&#8364;"
            )
        );
    }

    //Verifica se l'URL esiste
    static public function CheckURL($url)
    {
        //no internet // da sistemare perchè il server non esce su internet
        return true;

        $handle = curl_init($url);
        //curl_setopt($handle, CURLOPT_PROXY, '172.30.3.100');
        //curl_setopt($handle, CURLOPT_PROXYTYPE, 'HTTP');
        //curl_setopt($handle, CURLOPT_PROXYPORT, '80');
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($handle, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($handle, CURLOPT_HEADER, 1);
        curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML,like Gecko) Chrome/27.0.1453.94 Safari/537.36");
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 2);

        // Get the HTML or whatever is linked in $url. 
        $risposta = curl_exec($handle);
        //error_log("errore: ".curl_error($handle));

        // Check for 404 (file not found). 
        $httpCode = curl_getinfo($handle);
        curl_close($handle);

        //timeout

        // If the document has loaded successfully without any redirection or error 
        if ($httpCode['http_code'] != 200) {
            //echo $httpCode."<br/>";
            error_log("codice http: " . $httpCode['http_code']);
            //error_log("risposta: ".$risposta."\n");
            return false;
        } else {
            //echo $httpCode."<br/>";
            return true;
        }
    }
}
