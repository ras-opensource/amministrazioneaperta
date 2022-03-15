<?php session_start();?>
<?php
include_once "utils/lib_ws.php";

header("Cache-control: private");
header("Content-type: application/xml");
//header("Content-Disposition: filename=\"aaws-".date("d-m-Y").".xml\"");

$xml= new xml_tree();
$response='<?xml version="1.0" encoding="UTF-8"?>'."\n";

//Xml contenente le operazioni da svolgere
$xml_data="";

//xml impostato sulla sessione (usato sono una volta)
if(isset($_SESSION['xml_data']))
{
	$xml_data=$_SESSION['xml_data'];
	unset($_SESSION['xml_data']);
}

//xml passato come POST di un form (ha la precedenza)
if(isset($_POST['xml'])) $xml_data=$_POST['xml'];

if($xml->ParseString($xml_data,false))
{
  $user=$xml->GetProperty("auth","user");
  $pwd=$xml->GetProperty("auth","pwd");
  $token=$xml->GetProperty("auth","token");

  //Se non viene impostato il parametro token cerca se esiste il token sulla sessione attuale e tenta un'autenticazione tramite token
  if($user == '' && $token == '') $curUser=AA_User::GetCurrentUser();

  $public=false;
  
  if($user=="guest")
  {
	$public=true;
  } 
  else
  {
	if(!isset($curUser))
	{
		if($token == "")
		{
			if(!UserAuth($user,$pwd,null))
			{
				$response.="<auth><error>".AA_Log::$lastErrorLog."</error></auth>"; //return wrong auth
				die($response);
			}
		}
		else
		{
			if(!UserAuth(null,null,$token))
			{
				$response.="<auth><error>".AA_Log::$lastErrorLog."</error></auth>"; //return wrong auth
				die($response);
			}    
		}	
	}
  }

  $params=$xml->GetElement("params");
  if($params == null)
  {
      $token = $_SESSION['token'];
      $response.='<auth token="'.$token.'"></auth>'; //return auth;
      die($response);
  }
  
  $response.="<params>";
  for($i=0; $i < $params->childNodes->length;$i++)
  {
    $curparam=$params->childNodes->item($i);
    if($curparam->nodeType==1)
    {
			$param_id=$curparam->getAttribute("id");
			$param_op=$curparam->getAttribute("op");
			$param_art=$curparam->getAttribute("art");
			
			//Chiamate pubbliche
			if($public)
			{
				if($param_art == "20_39") //art. 20 d.lgs. 39/2013 pubblicazioni dei dirigenti
				{
					$response.='<param id="'.$param_id.'" op="0" art="20_39" status="1">';
					$response.=Art20_39_Query($curparam);
					$response.="</param>";
				}
				
				if($param_art == "pubblicazioni_dirigenti") //pubblicazioni dei dirigenti (art14 d.lgs 33/2013 e art 20 d.lgs.39/2013)
				{
					$response.='<param id="'.$param_id.'" op="0" art="pubblicazioni_dirigenti" status="1">';
					$response.=AA_XML_ReportDirigenti($curparam);
					$response.="</param>";
				}

				if($param_art == "14_1b") //art. 14 d.lgs. 33/2013 pubblicazioni dei dirigenti
				{
					$response.='<param id="'.$param_id.'" op="0" art="14_1b" status="1">';
					$response.=Art14_1b_Query($curparam);
					$response.="</param>";
				}

				if($param_art == "14_1d_1e") //art. 14 d.lgs. 33/2013 pubblicazioni dei dirigenti
				{
					$response.='<param id="'.$param_id.'" op="0" art="14_1d_1e" status="1">';
					$response.=Art14_1d_1e_Query($curparam);
					$response.="</param>";
				}

				if($param_art == "12") //art. 12 d.lgs. 33/2013 pubblicazioni dei dirigenti
				{
					$response.='<param id="'.$param_id.'" op="0" art="12" status="1">';
					//$response.=Art12_Query($curparam);
					$response.="</param>";
				}
			}
			else
			{
				switch($param_art) //Pubblicazioni autenticate
					{
						case "pubblicazioni_dirigenti":
						{
							//pubblicazioni dei dirigenti (art14 d.lgs 33/2013 e art 20 d.lgs.39/2013)
							$response.='<param id="'.$param_id.'" op="0" art="'.$param_art.'" status="1">';
							$response.=AA_XML_ReportDirigenti($curparam);
							$response.="</param>";
							break;
						}
						
						//Chiama una funzione di sistema
						case "AA_XMLFC":
						{
							$response.='<param id="'.$param_id.'" op="" art="" status="1">';
							$response.=AA_FunctionCallFromXML($curparam);
							$response.="</param>";
							
							break;
						}

						case "14_1d_1e":
						{
							//Consultazione, report e download
							if($param_op == "0" || $param_op == "")
							{
								$response.='<param id="'.$param_id.'" op="0" art="'.$param_art.'" status="1">';
								$response.=Art14_1d_1e_Query($curparam);
								$response.="</param>";
							}
							break;
						}

						case "20_39":
						{
							//Consultazione, report e download
							if($param_op == "0" || $param_op == "")
							{
								$response.='<param id="'.$param_id.'" op="0" art="20_39" status="1">';
								$response.=Art20_39_Query($curparam);
								$response.="</param>";
							}
							break;
						}
						case "26":
							if($param_op == "0" || $param_op == "")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="26" status="1">';
								$response.=Art26_Query($curparam);
								$response.="</param>";
							}
									if($param_op == "1")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="26" status="1">';
								$response.=Art26_QueryTables($curparam);
								$response.="</param>";
							}

							if($param_op == "10")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="26" status="1">';
								$response.=Art26_AddNew($curparam,false);
								$response.="</param>";
							}
									if($param_op == "11")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="26" status="1">';
								$response.=Art26_AddNew($curparam,true);
								$response.="</param>";
							}
							if($param_op == "20")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="26" status="1">';
								$response.=Art26_Modify($curparam);
								$response.="</param>";
							}
							if($param_op == "30")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="26" status="1">';
								$response.=Art26_Trash($curparam);
								$response.="</param>";
							}
							if($param_op == "40")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="26" status="1">';
								$response.=Art26_Publish($curparam);
								$response.="</param>";
							}
							
							if($param_op == "41")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="26" status="1">';
								$response.=Art26_PublishTest($curparam);
								$response.="</param>";
							}

						break;
						case "37":

							if($param_op == "0" || $param_op == "")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="37" status="1">';
								$response.=Art37_Query($curparam);
								$response.="</param>";
							}
							if($param_op == "1")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="37" status="1">';
								$response.=Art37_QueryTables($curparam);
								$response.="</param>";
							}	    
							if($param_op == "10")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="37" status="1">';
								$response.=Art37_AddNew($curparam,false);
								$response.="</param>";
							}
									if($param_op == "11")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="37" status="1">';
								$response.=Art37_AddNew($curparam,true);
								$response.="</param>";
							}
							if($param_op == "20")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="37" status="1">';
								$response.=Art37_Modify($curparam);
								$response.="</param>";
							}
							if($param_op == "30")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="37" status="1">';
								$response.=Art37_Trash($curparam);
								$response.="</param>";
							}
							if($param_op == "40")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="37" status="1">';
								$response.=Art37_Publish($curparam);
								$response.="</param>";
							}
							if($param_op == "41")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="37" status="1">';
								$response.=Art37_PublishTest($curparam);
								$response.="</param>";
							}

							//Ottiene la struttura della scheda
							if($param_op == "50")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="37" status="1">';
								$response.=Art37_GetStruct($curparam);
								$response.="</param>";
							}
							
							//Imposta la struttura della scheda
							if($param_op == "51")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="37" status="1">';
								$response.=Art37_SetStruct($curparam);
								$response.="</param>";
							}

						break;
						case "15":
							if($param_op == "0" || $param_op == "")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="15" status="1">';
								$response.=Art15_Query($curparam);
								$response.="</param>";
							}
									if($param_op == "1")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="15" status="1">';
								$response.=Art15_QueryTables($curparam);
								$response.="</param>";
							}

							if($param_op == "10")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="15" status="1">';
								$response.=Art15_AddNew($curparam,false);
								$response.="</param>";
							}
									if($param_op == "11")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="15" status="1">';
								$response.=Art15_AddNew($curparam,true);
								$response.="</param>";
							}
							if($param_op == "20")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="15" status="1">';
								$response.=Art15_Modify($curparam);
								$response.="</param>";
							}
							if($param_op == "30")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="15" status="1">';
								$response.=Art15_Trash($curparam);
								$response.="</param>";
							}
							if($param_op == "40")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="15" status="1">';
								$response.=Art15_Publish($curparam);
								$response.="</param>";
							}
									if($param_op == "41")
							{
								$response.='<param id="'.$param_id.'" op="'.$param_op.'" art="15" status="1">';
								$response.=Art15_PublishTest($curparam);
								$response.="</param>";
							}
						break;					
					}
			}	
    }
  }
  $response.="</params>";
  
  AA_Utils::AppendLogToSession();

  die($response);
}
else
{
  $response.="<params><error>".$LastParsingError."</error></params>"; //return
  
  AA_Utils::AppendLogToSession();
  
  die($response);
}
?>
