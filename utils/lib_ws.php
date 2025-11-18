<?php
/*$path = '/home/sitod/web/amministrazione_aperta/utils';
setlocale(LC_MONETARY, 'it_IT');
set_include_path(get_include_path().PATH_SEPARATOR.$path);
include_once "system_lib.php";
include_once "check_user.php";
include_once "php2html_lib.php";
include_once "lib_art37.php";
include_once "lib_art15.php";
include_once "lib_accessi.php";
include_once "lib_sibar.php";
include_once "lib_mail.php";
*/
//include_once "libs.php";

$log=false;
function UserAuth($user,$pwd,$token)
{
    $user=AA_User::UserAuth($token,$user,$pwd);
    
    if(!$user->IsValid())
    {
      $user=AA_User::legacyUserAuth($token,$user,$pwd);
      if($user->IsValid())
      {
        AA_User::MigrateLegacyUser($user,"",$pwd);

        return true;
      }

      $user=AA_User::legacyUserAuth($token,$user,md5($pwd));
      if($user->IsValid())
      {
        AA_User::MigrateLegacyUser($user,$pwd);

        return true;
      }
    }

    return $user->IsValid();

    /*
    $db=new Database(); 
    
    if($user != null && $pwd != null)
    {
        $query_utenti = sprintf("SELECT utenti.*,assessorati.tipo, assessorati.descrizione as assessorato, direzioni.descrizione as direzione, servizi.descrizione as servizio, settori.descrizione as settore FROM utenti left join assessorati on utenti.id_assessorato=assessorati.id left join direzioni on utenti.id_direzione=direzioni.id left join servizi on utenti.id_servizio=servizi.id left join settori on utenti.id_settore=settori.id WHERE user = '%s' AND passwd= '%s' AND disable = 0 and eliminato = 0", str_replace("'","",$user), str_replace("'","",$pwd));

        //error_log($query_utenti);
        
        $db->Query($query_utenti);
        $rs=$db->GetRecordSet();
        
        if($rs->GetCount() > 0)
        {
                $_SESSION['user']=$rs->Get('user');
                $_SESSION['nome']=$rs->Get('nome');
                $_SESSION['cognome']=$rs->Get('cognome');
                $_SESSION['email']=$rs->Get('email');
                $_SESSION['user_home']=$rs->Get('home');
                $_SESSION['id_user']=$rs->Get('id');
                $_SESSION['id_utente']=$rs->Get('id');
                $_SESSION['id_assessorato']=$rs->Get('id_assessorato');
                $_SESSION['tipo_struct']=$rs->Get('tipo');
                $_SESSION['id_direzione']=$rs->Get('id_direzione');
                $_SESSION['id_servizio']=$rs->Get('id_servizio');
                $_SESSION['id_settore']=$rs->Get('id_settore');
                $_SESSION['livello']=$rs->Get('livello');
                $_SESSION['level']=$rs->Get('livello');
                $_SESSION['assessorato']=$rs->Get('assessorato');
                $_SESSION['direzione']=$rs->Get('direzione');
                $_SESSION['servizio']=$rs->Get('servizio');
                $_SESSION['settore']=$rs->Get('settore');
                $_SESSION['user_flags']=$rs->Get('flags');
                $_SESSION['flags']=$rs->Get('flags');
                
                if($log) LogAction($rs->Get('id'),0,"Log In - web services");
                return true;
        }
        return false;
    }
    
    if($token != null)
    {
        $query_token = sprintf("SELECT id_utente, token FROM tokens where TIMESTAMPDIFF(MINUTE,data_rilascio, NOW()) < 10 and ip_src = '%s' and id_utente='%s' and token ='%s'",$_SERVER['REMOTE_ADDR'],$_SESSION['id_user'],$token);

        //error_log("Query: ".$query_token);
        
        $db->Query($query_token);
        $rs=$db->GetRecordSet();
        
        if($rs->GetCount() > 0)
        {
                do
                {
                    //error_log("token: ".$rs->Get('token'));
                    
                    if(strcmp($rs->Get('token'),$token) == 0 && $_SESSION['id_user'] == $rs->Get('id_utente'))
                    {
                        if($log) LogAction($rs->Get('id'),0,"Authenticate token ($token) - success - web services");
                        return true;
                    }
                }while ($rs->MoveNext());
        }
        
        if($log) LogAction($rs->Get('id'),0,"Authenticate token ($token) - failed - web services");
        return false;
    }
    
    return false;*/
}

/*
function GenerateToken()
{
    $token = hash("sha256",$_SESSION['user'].date("Y-m-d H:i:s").uniqid().$_SERVER['REMOTE_ADDR']);
    
    $db=new Database();
    
    $query="DELETE from tokens where id_utente='".$_SESSION['id_user']."' and ip_src='".$_SERVER['REMOTE_ADDR']."'";
    $db->Query($query);
    
    $query="INSERT INTO tokens set token='".$token."', id_utente='".$_SESSION['id_user']."',ip_src='".$_SERVER['REMOTE_ADDR']."'";
    $db->Query($query);
    
    return $token;
}*/

function Art26_Query($params)
{
  $id_scheda=$params->getElementsByTagName("id_scheda")->item(0);
  $status=$params->getElementsByTagName("status")->item(0);
  $titolo=$params->getElementsByTagName("titolo")->item(0);
  $anno_rif=$params->getElementsByTagName("anno_rif")->item(0);
  $responsabile=$params->getElementsByTagName("responsabile")->item(0);
  $beneficiario=$params->getElementsByTagName("beneficiario")->item(0);
  $codice_fornitore=$params->getElementsByTagName("codice_fornitore")->item(0);
  
  $db=new Database();
  $query="SELECT * from schede WHERE 1";
  $query_count="SELECT count(id) as tot from schede WHERE 1";
  $filter ="";
  $order=" ORDER BY aggiornamento DESC";
  $limit=" LIMIT 10";
  if($id_scheda)
  {
    $filter.=" AND id='".$id_scheda->textContent."'";
    if($_SESSION['id_assessorato'] != "0") $filter.=" AND schede.id_assessorato = ".$_SESSION['id_assessorato'];
    if($_SESSION['id_direzione'] != "0") $filter.=" AND schede.id_direzione = ".$_SESSION['id_direzione'];
    if($_SESSION['id_servizio'] != "0") $filter.=" AND schede.id_servizio = ".$_SESSION['id_servizio'];
    
    unset($status);
  }
  
  if($status)
  {
    if($status->textContent=="0")
    {
      $filter.=" AND bozza=1 ";
      if($_SESSION['id_assessorato'] != "0") $filter.=" AND schede.id_assessorato = ".$_SESSION['id_assessorato'];
      if($_SESSION['id_direzione'] != "0") $filter.=" AND schede.id_direzione = ".$_SESSION['id_direzione'];
      if($_SESSION['id_servizio'] != "0") $filter.=" AND schede.id_servizio = ".$_SESSION['id_servizio'];
    }
    if($status->textContent=="1") $filter.=" AND bozza = 0 AND cestinata = 0";
    if($status->textContent=="3")
    {
      $filter.=" AND cancellata=1 AND bozza = 1";
      if($_SESSION['id_assessorato'] != "0") $filter.=" AND schede.id_assessorato = ".$_SESSION['id_assessorato'];
      if($_SESSION['id_direzione'] != "0") $filter.=" AND schede.id_direzione = ".$_SESSION['id_direzione'];
      if($_SESSION['id_servizio'] != "0") $filter.=" AND schede.id_servizio = ".$_SESSION['id_servizio'];
    }
   if($status->textContent <> "0" && $status->textContent <> "1" && $status->textContent <> "2" && $status->textContent <> "3") $filter.=" AND bozza = 0 AND cancellata = 0";
  }
  else
  {
    if(is_null($id_scheda)) $filter.=" AND bozza = 0 AND cancellata = 0";
  }

  if($titolo) $filter.=" AND titolo like '%".$titolo->textContent."%'";
  if($anno_rif) $filter.=" AND anno_rif like '%".$anno_rif->textContent."%'";
  if($responsabile) $filter.=" AND responsabile '%".$responsabile->textContent."%'";
  if($beneficiario) $filter.=" AND beneficiario like '%".$beneficiario->textContent."%'";
  if($codice_fornitore) $filter.=" AND codice_beneficiario like '%".$codice_fornitore->textContent."%'";
  
  $db->Query($query_count.$filter);
  $rs=$db->GetRecordSet();
  $count = $rs->Get("tot");
  if( $count > 0)
  {
    $xml_result="<count>".$count."</count>";
    $db->Query($query.$filter.$order.$limit);
    $rs=$db->GetRecordSet();

    if($id_scheda && $id_scheda->getAttribute("showGUI")=="1")
    {
      if($rs->Get("bozza")=="0")
      {
	header('Location: admin/gest_schede/schede_detail.php?id='.$rs->Get("id"));
	exit;
      }
      
      if($rs->Get("bozza")=="1")
      {
	header('Location: admin/gest_schede/bozze_detail.php?id='.$rs->Get("id"));
	exit;	
      }      
    }
    
    do
    {
      $tipo_beneficiario ="";
      if($rs->Get("tipo_beneficiario") & 5) $tipo_beneficiario.="persona_fisica";
      if($rs->Get("tipo_beneficiario") & 1) $tipo_beneficiario.=" internazionale";
      if($rs->Get("tipo_beneficiario") & 2) $tipo_beneficiario.=" ente_pubblico";
      $xml_status = "";
      $xml_result.= "<scheda ";
      $xml_result.= ' id="'.$rs->Get("id").'"';
      if($rs->Get("bozza")==0) $xml_result.= ' id_pubblicazione="'.$rs->Get("seriale").' "';
      if($rs->Get("bozza")==1) $xml_status = "0";
      else $xml_status= "1";
      if($rs->Get("cancellata")==1) $xml_status= "3";
      $xml_result.= ' status = "'.$xml_status.'"';
      $xml_result.= ' anno_rif="'.$rs->Get("anno_rif").'"';
      $xml_result.= ' norma="'.str_replace("&","&amp;",$rs->Get("sottotitolo")).'"';
      $xml_result.= ' link_norma="'.str_replace("&","&amp;",$rs->Get("link_norma")).'"';
      $xml_result.= ' ufficio="'.str_replace("&","&amp;",$rs->Get("ufficio")).'"';
      $xml_result.= ' responsabile="'.str_replace("&","&amp;",$rs->Get("responsabile")).'"';
      $xml_result.= ' nome_beneficiario="'.str_replace("&","&amp;",$rs->Get("nome_beneficiario")).'"';
      $xml_result.= ' tipo_beneficiario="'.$tipo_beneficiario.'"';
      $xml_result.= ' indirizzo_beneficiario="'.str_replace("&","&amp;",$rs->Get("indirizzo_beneficiario")).'"';
      $xml_result.= ' importo="'.$rs->Get("costo").'"';
      $xml_result.= ' importo_erogato="'.$rs->Get("importo_erogato").'"';
      $xml_result.= ' cf="'.$rs->Get("cf_beneficiario").'"';
      $xml_result.= ' piva="'.$rs->Get("piva_beneficiario").'"';
      $xml_result.= ' cig="'.$rs->Get("cig_beneficiario").'"';
      $xml_result.= ' cup="'.$rs->Get("cup_beneficiario").'"';
      $xml_result.= ' codice_fornitore_sap="'.$rs->Get("codice_beneficiario").'"';
      $xml_result.=">";
      $xml_result.="<titolo>".str_replace("&","&amp;",$rs->Get("titolo"))."</titolo>";
      $modalita=GetTipoAffido();
      $xml_result.= "<modalita>".str_replace("&","&amp;",$modalita[$rs->Get("tipo_affido")])."</modalita>";
  
      //allegati
      $db1=new Database();     
      $db1->Query("Select * from allegati where id_scheda='".$this->nID."'");
      $rs1=$db1->GetRecordSet();
      if($rs1->GetCount() > 0)
      {
          $xml.="<links count='".$rs1->GetCount()."'>";
          do
          {		  
              if($rs1->Get("type")=="file") $url="https:///sitod.regione.sardegna.it/web/amministrazione_aperta/allegati.php?op=4&idRec=".$rs1->Get("id");
              else $url=$rs1->Get("file");
              $xml.="<link url='".AA_Utils::Xml_entities($url)."'>".AA_Utils::Xml_entities($allegato_desc[$rs1->Get("descrizione")])."</link>";		  
          }while ($rs1->MoveNext());
          $xml.="</links>";
      }
      //---------------
      $xml_result.="</scheda>";
      
    }while($rs->MoveNext());
    
    return $xml_result;
  }
  else
  {
    return "<count>0</count>";
  }
}
function Art26_QueryTables($params)
{
  $xml_result.="<art26><modalita>";
  $modalita=GetTipoAffido();
  foreach($modalita as $key=>$value)
  {
    $xml_result.='<voce value="'.$key.'">'.$value.'</voce>';
  }
  $xml_result.="</modalita></art26>";
  
  return $xml_result;
}

function Art26_GetInfo($id,$fullDetail)
{

}

function Art26_AddNew($params,$test)
{
  if($_SESSION['livello'] > 1) return "<error>utente non abilitato all'inserimento</error>";
  
  $titolo=$params->getElementsByTagName("titolo")->item(0);
  $anno_rif=$params->getElementsByTagName("anno_rif")->item(0);
  $norma=$params->getElementsByTagName("norma")->item(0);
  $link_norma=$params->getElementsByTagName("link_norma")->item(0);
  $responsabile=$params->getElementsByTagName("responsabile")->item(0);
  $beneficiario=$params->getElementsByTagName("beneficiario")->item(0);
  $indirizzo_beneficiario=$params->getElementsByTagName("indirizzo_beneficiario")->item(0);
  $cf=$params->getElementsByTagName("cf")->item(0);
  $piva=$params->getElementsByTagName("piva")->item(0);
  $cig=$params->getElementsByTagName("cig")->item(0);
  $cup=$params->getElementsByTagName("cup")->item(0);
  $codice_fornitore=$params->getElementsByTagName("codice_fornitore")->item(0);
  $modalita=$params->getElementsByTagName("modalita")->item(0);
  $importo_impegnato=$params->getElementsByTagName("importo_impegnato")->item(0);  
  $importo_erogato=$params->getElementsByTagName("importo_erogato")->item(0);
  $note=$params->getElementsByTagName("note")->item(0);
  
  $_SESSION['art']=26;
  
  $newID=NewBozza();
  if($newID !="")
  {
    if($test)
    {
      DelScheda($newID);
      return "<info>La simulazione di inserimento è andata a buon fine</info>";
    }
    else
    {
      $tipo_beneficiario=0;
      if($beneficiario->hasAttribute("internazionale") && $beneficiario->getAttribute("internazionale") == "1") $tipo_beneficiario+=1;
      if($beneficiario->hasAttribute("ente_pubblico") && $beneficiario->getAttribute("ente_pubblico") == "1") $tipo_beneficiario+=2;
      if($beneficiario->hasAttribute("persona_fisica") && $beneficiario->getAttribute("persona_fisica") == "1") $tipo_beneficiario+=5;
      //$tipo_affido=GetTipoAffido();
      
      $sql="UPDATE schede set titolo='".addslashes($titolo->textContent)."',tipo_beneficiario='".$tipo_beneficiario."'";
      if($anno_rif->textContent != "") $sql.=",anno_rif ='".$anno_rif->textContent."'";
      if($norma->textContent != "") $sql.=",sottotitolo ='".addslashes($norma->textContent)."'";
      if($link_norma->textContent != "") $sql.=",link_norma ='".addslashes($link_norma->textContent)."'";
      if($responsabile->textContent != "") $sql.=",responsabile ='".addslashes($responsabile->textContent)."'";
      if($beneficiario->textContent != "") $sql.=",nome_beneficiario='".addslashes($beneficiario->textContent)."'";
      if($indirizzo_beneficiario->textContent != "") $sql.=",indirizzo_beneficiario='".addslashes($indirizzo_beneficiario->textContent)."'";
      if($cf->textContent != "") $sql.=",cf_beneficiario='".addslashes($cf->textContent)."'";
      if($piva->textContent != "") $sql.=",piva_beneficiario='".addslashes($piva->textContent)."'";
      if($cig->textContent != "") $sql.=",cig_beneficiario='".addslashes($cig->textContent)."'";
      if($cup->textContent != "") $sql.=",cup_beneficiario='".addslashes($cup->textContent)."'";
      if($codice_fornitore->textContent != "") $sql.=",codice_beneficiario='".addslashes($codice_fornitore->textContent)."'";
      if($note->textContent != "") $sql.=",note='".addslashes($note->textContent)."'";
      if(is_numeric($modalita->textContent)) $sql.=", tipo_affido='".$modalita->textContent."'";
      if($importo_impegnato->textContent !="") $sql.=", costo='".str_replace(",",".",$importo_impegnato->textContent)."'";
      if($importo_erogato->textContent !="") $sql.=", importo_erogato='".str_replace(",",".",$importo_erogato->textContent."'");
      
      $sql.=" where id=".$newID." limit 1";
      
      $db=new Database();
      $db->Query($sql);
      
      LogAction(0,'2,1,'.$newID,$sql);
      
      return '<scheda id="'.$newID.'" />';
    }
  }
  return "<error> errore durante l'inserimento della scheda </error>";
}

function Art26_Modify($params)
{
  $idScheda=$params->getElementsByTagName("id_scheda")->item(0);
  $titolo=$params->getElementsByTagName("titolo")->item(0);
  $anno_rif=$params->getElementsByTagName("anno_rif")->item(0);
  $norma=$params->getElementsByTagName("norma")->item(0);
  $link_norma=$params->getElementsByTagName("link_norma")->item(0);
  $responsabile=$params->getElementsByTagName("responsabile")->item(0);
  $beneficiario=$params->getElementsByTagName("beneficiario")->item(0);
  $indirizzo_beneficiario=$params->getElementsByTagName("indirizzo_beneficiario")->item(0);
  $cf=$params->getElementsByTagName("cf")->item(0);
  $piva=$params->getElementsByTagName("piva")->item(0);
  $cig=$params->getElementsByTagName("cig")->item(0);
  $cup=$params->getElementsByTagName("cup")->item(0);
  $codice_fornitore=$params->getElementsByTagName("codice_fornitore")->item(0);
  $modalita=$params->getElementsByTagName("modalita")->item(0);
  $importo_impegnato=$params->getElementsByTagName("importo_impegnato")->item(0);  
  $importo_erogato=$params->getElementsByTagName("importo_erogato")->item(0);
  $note=$params->getElementsByTagName("note")->item(0);

  if($idScheda == null || $idScheda->textContent == "")
  {
    return "<error>Non è stato impostato l'identificativo della scheda</error>"; 
  }
  if(SchedaUserAccessOpts($idScheda->textContent) < 2)
  {
    return "<error>L'utente non ha i privilegi di modifica sulla scheda (identificativo: ".$idScheda->textContent.")</error>";
  }
  
  $tipo_beneficiario=0;
  if($beneficiario->hasAttribute("internazionale") && $beneficiario->getAttribute("internazionale") == "1") $tipo_beneficiario+=1;
  if($beneficiario->hasAttribute("ente_pubblico") && $beneficiario->getAttribute("ente_pubblico") == "1") $tipo_beneficiario+=2;
  if($beneficiario->hasAttribute("persona_fisica") && $beneficiario->getAttribute("persona_fisica") == "1") $tipo_beneficiario+=5;
  $tipo_affido=GetTipoAffido();
  
  $sql="UPDATE schede set titolo='".addslashes($titolo->textContent)."',tipo_beneficiario='".$tipo_beneficiario."'";
  if($anno_rif->textContent != "") $sql.=",anno_rif ='".$anno_rif->textContent."'";
  if($norma->textContent != "") $sql.=",link_norma ='".addslashes($norma->textContent)."'";
  if($link_norma->textContent != "") $sql.=",sottotitolo ='".addslashes($link_norma->textContent)."'";
  if($responsabile->textContent != "") $sql.=",responsabile ='".addslashes($responsabile->textContent)."'";
  if($beneficiario->textContent != "") $sql.=",nome_beneficiario='".addslashes($beneficiario->textContent)."'";
  if($indirizzo_beneficiario->textContent != "") $sql.=",indirizzo_beneficiario='".addslashes($indirizzo_beneficiario->textContent)."'";
  if($cf->textContent != "") $sql.=",cf_beneficiario='".addslashes($cf->textContent)."'";
  if($piva->textContent != "") $sql.=",piva_beneficiario='".addslashes($piva->textContent)."'";
  if($cig->textContent != "") $sql.=",cig_beneficiario='".addslashes($cig->textContent)."'";
  if($cup->textContent != "") $sql.=",cup_beneficiario='".addslashes($cup->textContent)."'";
  if($codice_fornitore->textContent != "") $sql.=",codice_beneficiario='".addslashes($codice_fornitore->textContent)."'";
  if($note->textContent != "") $sql.=",note='".addslashes($note->textContent)."'";
  if(is_numeric($modalita->textContent)) $sql.=", tipo_affido='".$modalita->textContent."'";
  
  if($importo_impegnato->textContent !="") $sql.=", costo='".str_replace(",",".",$importo_impegnato->textContent)."'";
  if($importo_erogato->textContent !="") $sql.=", importo_erogato='".str_replace(",",".",$importo_erogato->textContent)."'";
  
  $sql.=" where id=".$idScheda->textContent." limit 1";
  
  $db=new Database();
  $db->Query($sql);
  
  LogAction(0,'2,1,'.$idScheda->textContent,$sql);
  
  return "<info>La scheda è stata modificata con successo!</info>";
}

function Art26_Trash($params)
{
  $idScheda=$params->getElementsByTagName("id_scheda")->item(0);
  
  if($idScheda==null || $idScheda->textContent == "")
  {
    return "<error>Non è stato impostato l'identificativo della scheda</error>"; 
  }
  if(CestinaScheda($idScheda->textContent) !== true)
  {
    return "<error>non è stato possibile annullare la scheda (identificativo: ".$idScheda->textContent.")</error>";
  }
  else return "<info>la scheda è stata annullata</info>";
}

function Art26_Delete($params)
{
  $idScheda=$params->getElementsByTagName("id_scheda")->item(0);
  
  if($idScheda==null || $idScheda->textContent =="")
  {
    return "<error>Non è stato impostato l'identificativo della scheda</error>"; 
  }
  $db=new Database();
  $db->Query("SELECT bozza,cancellata from schede where id='".$idScheda->textContent."' limit 1");
  $rs=$db->GetRecordSet();
  if($rs->GetCount()==1)
  {
    if($rs->Get("bozza")=="1")
    {
      if(DelScheda($idScheda->textContent))
      {
	return "<error>non è stato possibile eliminare la scheda (identificativo: ".$idScheda->textContent.")</error>";
      }
      else return "<info>la scheda è stata eliminata</info>";
    }
    else return "<error>La scheda non può essere rimossa definitivamente in quanto pubblicata</error>";
  }
  else return "<error>La scheda indicata non è stata trovata (identificativo: ".$idScheda->textContent.")</error>";
}

function Art26_Publish($params)
{
   $idScheda=$params->getElementsByTagName("id_scheda")->item(0);
  
  if($idScheda==null || $idScheda->textContent == "")
  {
    return "<error>Non è stato impostato l'identificativo della scheda</error>"; 
  }
  $return = Pubblica($idScheda->textContent);
  if($return===true)
  {
    $db=new Database();
    $db->Query("SELECT seriale from schede where id='".$idScheda->textContent."'");
    $rs=$db->GetRecordSet();
    return "<id_pubblicazione>".$rs->Get("seriale")."</id_pubblicazione>";
  }
  else
  {
    return "<error>La scheda non può essere pubblicata perchè: ".implode("-",$return)."</error>";
  }
}

function Art26_PublishTest($params)
{
   $idScheda=$params->getElementsByTagName("id_scheda")->item(0);
  
  if($idScheda==null || $idScheda->textContent == "")
  {
    return "<error>Non è stato impostato l'identificativo della scheda</error>"; 
  }
  $return = TestPubblica($idScheda->textContent);
  if($return===true)
  {
    return "<success>La scheda può essere pubblicata</success>";
  }
  else
  {
    return "<error>La scheda non può essere pubblicata perchè: ".implode("-",$return)."</error>";
  }
}

function Art37_Query($params)
{
  $id_scheda=$params->getElementsByTagName("id_scheda")->item(0);
  $status=$params->getElementsByTagName("status")->item(0);
  $titolo=$params->getElementsByTagName("titolo")->item(0);
  $anno_rif=$params->getElementsByTagName("anno_rif")->item(0);
  $cig=$params->getElementsByTagName("cig")->item(0);
  $partecipante=$params->getElementsByTagName("partecipante")->item(0);
  $aggiudicatario=$params->getElementsByTagName("aggiudicatario")->item(0);
  
  $db=new Database();
  $query="SELECT DISTINCT appalti_pubblicazioni.id from appalti_pubblicazioni";
  $query_count="SELECT DISTINCT count(appalti_pubblicazioni.id) as tot from appalti_pubblicazioni";
  $joins = "";
  $filter =" WHERE 1";
  $order=" ORDER BY appalti_pubblicazioni.dataUltimoAggiornamentoDataset DESC";
  $limit=" LIMIT 10";
  
  if($cig && $cig->textContent !="")
  {
    $joins.=" left join appalti_lotti on appalti_pubblicazioni.id=appalti_lotti.id_pubblicazione";
  }
  if($partecipante || $aggiudicatario)
  {
    $joins=" left join appalti_lotti on appalti_pubblicazioni.id=appalti_lotti.id_pubblicazione";
    if($partecipante && $partecipante->textContent !="") $joins.=" left join appalti_partecipanti on appalti_lotti.id=appalti_partecipanti.id_lotto";
    if($aggiudicatario && $aggiudicatario->textContent !="") $joins.=" left join appalti_aggiudicatari on appalti_lotti.id=appalti_aggiudicatari.id_lotto";
  }
  if($id_scheda)
  {
    $filter.=" AND appalti_pubblicazioni.id='".$id_scheda->textContent."'";
    if($status && $status->textContent != "1")
    {
      if($_SESSION['id_assessorato'] != "0") $filter.=" AND appalti_pubblicazioni.id_assessorato = ".$_SESSION['id_assessorato'];
      if($_SESSION['id_direzione'] != "0") $filter.=" AND appalti_pubblicazioni.id_direzione = ".$_SESSION['id_direzione'];
      if($_SESSION['id_servizio'] != "0") $filter.=" AND appalti_pubblicazioni.id_servizio = ".$_SESSION['id_servizio'];
    }
    
    unset($status);
  }
  
  if($status)
  {
    if($status->textContent=="0")
    {
      $filter.=" AND bozza=1 ";
      if($_SESSION['id_assessorato'] != "0") $filter.=" AND appalti_pubblicazioni.id_assessorato = ".$_SESSION['id_assessorato'];
      if($_SESSION['id_direzione'] != "0") $filter.=" AND appalti_pubblicazioni.id_direzione = ".$_SESSION['id_direzione'];
      if($_SESSION['id_servizio'] != "0") $filter.=" AND appalti_pubblicazioni.id_servizio = ".$_SESSION['id_servizio'];
    }
    if($status->textContent=="1") $filter.=" AND bozza = 0 AND cestinata = 0 AND revisionata = 0";
    if($status->textContent=="2")
    {
      $filter.=" AND revisionata=1";
      if($_SESSION['id_assessorato'] != "0") $filter.=" AND appalti_pubblicazioni.id_assessorato = ".$_SESSION['id_assessorato'];
      if($_SESSION['id_direzione'] != "0") $filter.=" AND appalti_pubblicazioni.id_direzione = ".$_SESSION['id_direzione'];
      if($_SESSION['id_servizio'] != "0") $filter.=" AND appalti_pubblicazioni.id_servizio = ".$_SESSION['id_servizio'];
    }
    if($status->textContent=="3")
    {
      $filter.=" AND cestinata=1 AND bozza = 1";
      if($_SESSION['id_assessorato'] != "0") $filter.=" AND appalti_pubblicazioni.id_assessorato = ".$_SESSION['id_assessorato'];
      if($_SESSION['id_direzione'] != "0") $filter.=" AND appalti_pubblicazioni.id_direzione = ".$_SESSION['id_direzione'];
      if($_SESSION['id_servizio'] != "0") $filter.=" AND appalti_pubblicazioni.id_servizio = ".$_SESSION['id_servizio'];
    }
    if($status->textContent <> "0" && $status->textContent <> "1" && $status->textContent <> "2" && $status->textContent <> "3") $filter.=" AND bozza = 0 AND cestinata = 0 AND revisionata = 0";
  }
  else
  {
    if(is_null($id_scheda)) $filter.=" AND bozza = 0 AND cestinata = 0 AND revisionata = 0";
  }
  
  if($titolo) $filter.=" AND titolo like '%".$titolo->textContent."%'";
  if($anno_rif) $filter.=" AND annoRiferimento like '%".$anno_rif->textContent."%'";
  if($cig) $filter.=" AND appalti_lotti.cig like '%".$cig->textContent."%'";
  if($partecipante) $filter.=" AND (appalti_partecipanti.ragioneSociale like '%".$partecipante->textContent."%' OR appalti_partecipanti.CodiceFiscale like '%".$partecipante->textContent."%')";
  if($aggiudicatario) $filter.=" AND (appalti_aggiudicatari.ragioneSociale like '%".$aggiudicatario->textContent."%' OR appalti_aggiudicatari.CodiceFiscale like '%".$aggiudicatario->textContent."%')";
  
  //error_log($query.$joins.$filter.$order.$limit);

  $db->Query($query_count.$joins.$filter);
  $rs=$db->GetRecordSet();
  $count = $rs->Get("tot");
  if( $count > 0)
  {
    $xml_result="<count>".$count."</count>";
    $db->Query($query.$joins.$filter.$order.$limit);
    $rs=$db->GetRecordSet();
    
    if($id_scheda && $id_scheda->getAttribute("showGUI")=="1")
    {
	    header('Location: admin/gest_schede/art37_detail.php?id='.$rs->Get("id"));
	    exit;
    }

    $db2=new Database();
    
    do
    {
      $idScheda=$rs->Get("id");      
      $db2->Query("SELECT * from appalti_pubblicazioni WHERE id='".$idScheda."' limit 1");
      $rs2=$db2->GetRecordSet();
      if($rs2->GetCount())
      {
	$seriale=str_pad($rs2->Get('id_assessorato'), 6, "0", STR_PAD_LEFT).substr($rs2->Get('dataPubblicazioneDataset'),0,4).str_pad($rs2->Get('id'), 10, "0", STR_PAD_LEFT);
	$xml_status = "";
	$xml_result.= "<scheda ";
	$xml_result.= ' id="'.$idScheda.'"';
	if($rs2->Get("bozza")==0) $xml_result.= ' id_pubblicazione="'.$seriale.' "';
	if($rs2->Get("bozza")==1) $xml_status = "0";
	else $xml_status= "1";
	if($rs2->Get("cestinata")==1) $xml_status= "3";
	if($rs2->Get("revisionata")==1) $xml_status= "2";
	$xml_result.= ' status = "'.$xml_status.'">';
	$xml_result.="<metadata>";
	$xml_result.="<titolo>".str_replace("&","&amp;",$rs2->Get("titolo"))."</titolo>";
	$xml_result.="<abstract>".str_replace("&","&amp;",$rs2->Get("abstract"))."</abstract>";
	$xml_result.="<entePubblicatore>".str_replace("&","&amp;",$rs2->Get("entePubblicatore"))."</entePubblicatore>";
	$xml_result.="<annoRiferimento>".$rs2->Get("annoRiferimento")."</annoRiferimento>";
	$xml_result.="</metadata>";
	$xml_result.="<data>";
	
	$db2->Query("SELECT * from appalti_lotti where id_pubblicazione='".$idScheda."'");
	$rs2=$db2->GetRecordSet();
	if($rs2->GetCount())
	{
	  do
	  {
	    $idLotto=$rs2->Get("id");
	    $xml_result.='<lotto id="'.$rs2->Get("id").'" numero="'.$rs2->Get("numero").'">';
	    $xml_result.="<cig>".$rs2->Get("cig")."</cig>";
	    $xml_result.="<strutturaProponente><codiceFiscaleProp>".$rs2->Get("codiceFiscaleProp")."</codiceFiscaleProp>";
	    $xml_result.="<denominazione>".str_replace("&","&amp;",$rs2->Get("denominazione"))."</denominazione></strutturaProponente>";
	    $xml_result.="<oggetto>".str_replace("&","&amp;",$rs2->Get("oggetto"))."</oggetto>";
	    $xml_result.="<sceltaContraente>".str_replace("&","&amp;",$rs2->Get("sceltaContraente"))."</sceltaContraente>";
	    $xml_result.="<identificativo_web>".$rs2->Get("id_bando")."</identificativo_web>";
	    $xml_result.="<importoAggiudicazione>".str_replace(".",",",$rs2->Get("importoAggiudicazione"))."</importoAggiudicazione>";
	    $xml_result.="<tempistica>";
      if($rs2->Get("dataInizio") != "") $xml_result.="<dataInizio>".$rs2->Get("dataInizio")."</dataInizio>";
	    if($rs2->Get("dataUltimazione") != "") $xml_result.="<dataUltimazione>".$rs2->Get("dataUltimazione")."</dataUltimazione>";
      $xml_result.="</tempistica>";
	    $xml_result.="<identificativo_web>".str_replace("&","&amp;",$rs2->Get("identificativo_web"))."</identificativo_web>";
	    $xml_result.="<importoSommeLiquidate>".str_replace(".",",",$rs2->Get("importoSommeLiquidate"))."</importoSommeLiquidate>";
	    
	    $db2->Query("SELECT * from appalti_partecipanti where id_lotto='".$idLotto."' order by gruppo,ragioneSociale");
	    $rs3=$db2->GetRecordSet();
	    if($rs3->GetCount())
	    {
	      $xml_result.="<partecipanti>";
	      do
	      {
		$xml_result.='<partecipante id="'.$rs3->Get("id").'">';
		$xml_result.="<codiceFiscale>".$rs3->Get("codiceFiscale")."</codiceFiscale>";
		$xml_result.="<identificativoFiscaleEstero>".$rs3->Get("identificativoFiscaleEstero")."</identificativoFiscaleEstero>";
		$xml_result.="<ragioneSociale>".str_replace("&","&amp;",$rs3->Get("ragioneSociale"))."</ragioneSociale>";
		$xml_result.="<gruppo>".str_replace("&","&amp;",$rs3->Get("gruppo"))."</gruppo>";
		$xml_result.="<ruolo>".str_replace("&","&amp;",$rs3->Get("ruolo"))."</ruolo>";
		$xml_result.="</partecipante>";
	      }while($rs3->MoveNext());
	      $xml_result.="</partecipanti>";
	    }
	    
	    $db2->Query("SELECT * from appalti_aggiudicatari where id_lotto='".$idLotto."' order by gruppo,ragioneSociale");
	    $rs3=$db2->GetRecordSet();
	    if($rs3->GetCount())
	    {
	      $xml_result.="<aggiudicatari>";
	      do
	      {
		$xml_result.='<aggiudicatario id="'.$rs3->Get("id").'">';
		$xml_result.="<codiceFiscale>".$rs3->Get("codiceFiscale")."</codiceFiscale>";
		$xml_result.="<identificativoFiscaleEstero>".$rs3->Get("identificativoFiscaleEstero")."</identificativoFiscaleEstero>";
		$xml_result.="<ragioneSociale>".str_replace("&","&amp;",$rs3->Get("ragioneSociale"))."</ragioneSociale>";
		$xml_result.="<gruppo>".str_replace("&","&amp;",$rs3->Get("gruppo"))."</gruppo>";
		$xml_result.="<ruolo>".str_replace("&","&amp;",$rs3->Get("ruolo"))."</ruolo>";
		$xml_result.="</aggiudicatario>";
	      }while($rs3->MoveNext());
	      $xml_result.="</aggiudicatari>";
	    }
	    $xml_result.="</lotto>";
	  }while($rs2->MoveNext());
	}
	$xml_result.="</data>";
	$xml_result.="</scheda>";
      }
      
    }while($rs->MoveNext());
    
    return $xml_result;
  }
  else
  {
    return "<count>0</count>";
  }
}
function Art37_QueryTables($params)
{
  $xml_result="<art37>";
  $xml_result.="<sceltaContraente>";
  $xml_result.="<voce>01-PROCEDURA APERTA</voce>";
  $xml_result.="<voce>02-PROCEDURA RISTRETTA</voce>";
  $xml_result.="<voce>03-PROCEDURA NEGOZIATA PREVIA PUBBLICAZIONE</voce>";
  $xml_result.="<voce>04-PROCEDURA NEGOZIATA SENZA PREVIA PUBBLICAZIONE</voce>";
  $xml_result.="<voce>05-DIALOGO COMPETITIVO</voce>";
  $xml_result.="<voce>06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)</voce>";
  $xml_result.="<voce>07-SISTEMA DINAMICO DI ACQUISIZIONE</voce>";
  $xml_result.="<voce>08-AFFIDAMENTO IN ECONOMIA - COTTIMO FIDUCIARIO</voce>";
  $xml_result.="<voce>14-PROCEDURA SELETTIVA EX ART 238 C.7, D.LGS. 163/2006</voce>";
  $xml_result.="<voce>17-AFFIDAMENTO DIRETTO EX ART. 5 DELLA LEGGE N.381/91</voce>";
  $xml_result.="<voce>21-PROCEDURA RISTRETTA DERIVANTE DA AVVISI CON CUI SI INDICE LA GARA</voce>";
  $xml_result.="<voce>22-PROCEDURA NEGOZIATA CON PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)</voce>";
  $xml_result.="<voce>23-AFFIDAMENTO DIRETTO</voce>";
  $xml_result.="<voce>24-AFFIDAMENTO DIRETTO A SOCIETA' IN HOUSE</voce>";
  $xml_result.="<voce>25-AFFIDAMENTO DIRETTO A SOCIETA' RAGGRUPPATE/CONSORZIATE O CONTROLLATE NELLE CONCESSIONI DI LL.PP</voce>";
  $xml_result.="<voce>26-AFFIDAMENTO DIRETTO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE</voce>";
  $xml_result.="<voce>27-CONFRONTO COMPETITIVO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE</voce>";
  $xml_result.="<voce>28-PROCEDURA AI SENSI DEI REGOLAMENTI DEGLI ORGANI COSTITUZIONALI</voce>";
  $xml_result.="<voce>29-PROCEDURA RISTRETTA SEMPLIFICATA</voce>";
	$xml_result.="<voce>30-PROCEDURA DERIVANTE DA LEGGE REGIONALE</voce>";
  $xml_result.="<voce>31-AFFIDAMENTO DIRETTO PER VARIANTE SUPERIORE AL 20% DELL'IMPORTO CONTRATTUALE</voce>";
  $xml_result.="<voce>32-AFFIDAMENTO RISERVATO</voce>";
  $xml_result.="<voce>33-PROCEDURA NEGOZIATA PER AFFIDAMENTI SOTTO SOGLIA</voce>";
  $xml_result.="<voce>34-PROCEDURA ART.16 COMMA 2-BIS DPR 380/2001 PER OPERE URBANIZZAZIONE A SCOMPUTO PRIMARIE SOTTO SOGLIA COMUNITARIA</voce>";
  $xml_result.="<voce>35-PARTERNARIATO PER L’INNOVAZIONE</voce>";
  $xml_result.="<voce>36-AFFIDAMENTO DIRETTO PER LAVORI, SERVIZI O FORNITURE SUPPLEMENTARI</voce>";
  $xml_result.="<voce>37-PROCEDURA COMPETITIVA CON NEGOZIAZIONE</voce>";
  $xml_result.="<voce>38-PROCEDURA DISCIPLINATA DA REGOLAMENTO INTERNO PER SETTORI SPECIALI</voce>";
  $xml_result.="</sceltaContraente>";
  $xml_result.="<ruolo>";
  $xml_result.="<voce>01-MANDANTE</voce>";
	$xml_result.="<voce>02-MANDATARIA</voce>";
	$xml_result.="<voce>03-ASSOCIATA</voce>";
	$xml_result.="<voce>04-CAPOGRUPPO</voce>";
	$xml_result.="<voce>05-CONSORZIATA</voce>";
  $xml_result.="</ruolo>";
  $xml_result.="</art37>";
  
  return $xml_result;
}

function Art37_AddNew($param,$test)
{
  $params['titolo']=$param->getElementsByTagName("titolo")->item(0)->textContent;
  if($params['titolo'] == "") $params['titolo']="Nuova bozza art.37";

  $params['abstract']=$param->getElementsByTagName("abstract")->item(0)->textContent;
  $params['entePubblicatore']=$param->getElementsByTagName("entePubblicatore")->item(0)->textContent;
  $params['annoRiferimento']=$param->getElementsByTagName("annoRiferimento")->item(0)->textContent;
  if($params['annoRiferimento'] == "") $params['annoRiferimento']= date("Y");
  $params['licenza']=$param->getElementsByTagName("licenza")->item(0)->textContent;
  
  $params['id_assessorato']=$_SESSION["id_assessorato"];
  $params['id_direzione']=$_SESSION["id_direzione"];
  $params['id_servizio']=$_SESSION["id_servizio"];
  $params['id_settore']=$_SESSION["id_settore"];
  $params['id_utente']=$_SESSION["id_utente"];
  
  $idScheda=AddPubArt37($params);
  if($idScheda === false)
  {
    return "<error>errore durante l'inserimento della nuova bozza</error>";
  }
  
  if($test == true)
  {
    $params["id"]=$idScheda;
    if(DeletePubArt37($params))
    {
      return "<info>Simulazione di inserimento bozza avvenuta con successo</info>";
    }
    
    return "<error>Errore durante la simulazione di inserimento bozza art.37 - non è stato possibile eliminare la bozza </error>";
  }
  
  $xml_result='<scheda id="'.$idScheda.'" />';
  
  $lotti=$param->getElementsByTagName("lotto");
  for($i=0;$i<$lotti->length;$i++)
  {
    unset($params);
    $params['id_assessorato']=$_SESSION["id_assessorato"];
    $params['id_direzione']=$_SESSION["id_direzione"];
    $params['id_servizio']=$_SESSION["id_servizio"];
    $params['id_settore']=$_SESSION["id_settore"];
    $params['id_utente']=$_SESSION["id_utente"];

    $curlotto=$lotti->item($i);
    if($curlotto)
    {
      $params["id_pubblicazione"]=$idScheda;
      $params["cig"]=$curlotto->getElementsByTagName("cig")->item(0)->textContent;
      $params["numero"]=$curlotto->getAttribute("numero");
      if($params["numero"]=="") $params["numero"]="1";
      $params["codiceFiscaleProp"]=$curlotto->getElementsByTagName("codiceFiscaleProp")->item(0)->textContent;
      $params["denominazione"]=$curlotto->getElementsByTagName("denominazione")->item(0)->textContent;
      $params["oggetto"]=$curlotto->getElementsByTagName("oggetto")->item(0)->textContent;
      $params["sceltaContraente"]=$curlotto->getElementsByTagName("sceltaContraente")->item(0)->textContent;
      $params["dataInizio"]=$curlotto->getElementsByTagName("dataInizio")->item(0)->textContent;
      $params["dataUltimazione"]=$curlotto->getElementsByTagName("dataUltimazione")->item(0)->textContent;
      $params["importoSommeLiquidate"]=$curlotto->getElementsByTagName("importoSommeLiquidate")->item(0)->textContent;
      $params["importoAggiudicazione"]=$curlotto->getElementsByTagName("importoAggiudicazione")->item(0)->textContent;
      $params["id_bando"]=$curlotto->getElementsByTagName("identificativo_web")->item(0)->textContent;
      
      if(!Art37CigIsValid(trim($params["cig"])))
      {
        $xml_result.="<error>Errore durante l'inserimento del lotto (cig:".$params['cig'].") - cig non valido o già inserito, verrà sostituito automaticamente con quello di default</error>";
        $params['cig']="0000000000";
      }

      $idLotto=AddArt37Lotto($params);
      if($idLotto === false)
      {
	      $xml_result.="<error>Errore durante l'inserimento del lotto (cig:".$params['cig']." - idScheda:".$idScheda." ) - ".AA_Log::$lastErrorLog."</error>";
      }      
      $params["id_lotto"]=$idLotto;
      
      $partecipanti=$curlotto->getElementsByTagName("partecipante");
      for($k=0;$k<$partecipanti->length;$k++)
      {
        unset($params);
        $params["id_pubblicazione"]=$idScheda;
        $params["id_lotto"]=$idLotto;

        $curpartecipante=$partecipanti->item($k);
        if($curpartecipante && is_numeric($idLotto))
        {
          $params["codiceFiscale"]=$curpartecipante->getElementsByTagName("codiceFiscale")->item(0)->textContent;
          $params["ragioneSociale"]=$curpartecipante->getElementsByTagName("ragioneSociale")->item(0)->textContent;
          $params["identificativoFiscaleEstero"]=$curpartecipante->getElementsByTagName("identificativoFiscaleEstero")->item(0)->textContent;
          $params["gruppo"]=$curpartecipante->getElementsByTagName("gruppo")->item(0)->textContent;
          $params["ruolo"]=$curpartecipante->getElementsByTagName("ruolo")->item(0)->textContent;
          
          if(AddPartecipanteArt37Lotto($params)===false)
          {
            $xml_result.="<error>Errore durante l'inserimento del partecipante (".$params['ragioneSociale'].") sul lotto (cig:".$params['cig'].")</error>";
          }
        }
      }

      $aggiudicatari=$curlotto->getElementsByTagName("aggiudicatario");
      for($k=0;$k<$aggiudicatari->length;$k++)
      {
        unset($params);
        $params["id_pubblicazione"]=$idScheda;
        $params["id_lotto"]=$idLotto;

        $curaggiudicatario=$aggiudicatari->item($k);
        if($curaggiudicatario && is_numeric($idLotto))
        {
          $params["codiceFiscale"]=$curaggiudicatario->getElementsByTagName("codiceFiscale")->item(0)->textContent;
          $params["ragioneSociale"]=$curaggiudicatario->getElementsByTagName("ragioneSociale")->item(0)->textContent;
          $params["identificativoFiscaleEstero"]=$curaggiudicatario->getElementsByTagName("identificativoFiscaleEstero")->item(0)->textContent;
          $params["gruppo"]=$curaggiudicatario->getElementsByTagName("gruppo")->item(0)->textContent;
          $params["ruolo"]=$curaggiudicatario->getElementsByTagName("ruolo")->item(0)->textContent;
          
          if(AddAggiudicatarioArt37Lotto($params)===false)
          {
            $xml_result.="<error>Errore durante l'inserimento dell'aggiudicatario (".$params['ragioneSociale'].") sul lotto (cig:".$params['cig'].")</error>";
          }
        }
      }
    }
  }
  return $xml_result;
}

function Art37_Modify($param)
{
  $idScheda=$param->getElementsByTagName("id_scheda")->item(0)->textContent;
  $params["id_pubblicazione"]=$idScheda;
  if($idScheda=="")
  {
    return "<error>identificativo scheda non impostato</error>";
  }

  if($param->getElementsByTagName("titolo")->item(0)->textContent !="") $params['titolo']=$param->getElementsByTagName("titolo")->item(0)->textContent;
  if($param->getElementsByTagName("abstract")->item(0)->textContent !="") $params['abstract']=$param->getElementsByTagName("abstract")->item(0)->textContent;
  if($param->getElementsByTagName("entePubblicatore")->item(0)->textContent !="") $params['entePubblicatore']=$param->getElementsByTagName("entePubblicatore")->item(0)->textContent;
  if($param->getElementsByTagName("annoRiferimento")->item(0)->textContent !="") $params['annoRiferimento']=$param->getElementsByTagName("annoRiferimento")->item(0)->textContent;
  if($param->getElementsByTagName("licenza")->item(0)->textContent !="") $params['licenza']=$param->getElementsByTagName("licenza")->item(0)->textContent;
  
  $params['id_assessorato']=$_SESSION["id_assessorato"];
  $params['id_direzione']=$_SESSION["id_direzione"];
  $params['id_servizio']=$_SESSION["id_servizio"];
  $params['id_settore']=$_SESSION["id_settore"];
  $params['id_utente']=$_SESSION["id_utente"];
  
  $params['id']=$idScheda;
  
  error_log(print_r($params,true));

  $result=UpdatePubArt37Header($params);
  if($result === false)
  {
    return "<error>Errore durante l'aggiornamento dell'header della scheda (id:".$params["id"].")</error>";
  }
  
  $xml_result='<info> header scheda (id="'.$params["id"].'") aggiornato</info>';
  
  $lotti=$param->getElementsByTagName("lotto");
  for($i=0;$i<$lotti->length;$i++)
  {
    $curlotto=$lotti->item($i);
    unset($params);
    $params["id_pubblicazione"]=$idScheda;
    $params['id_assessorato']=$_SESSION["id_assessorato"];
    $params['id_direzione']=$_SESSION["id_direzione"];
    $params['id_servizio']=$_SESSION["id_servizio"];
    $params['id_settore']=$_SESSION["id_settore"];
    $params['id_utente']=$_SESSION["id_utente"];

    if($curlotto->getAttribute("id") == "")
    {      
      $params["cig"]=$curlotto->getElementsByTagName("cig")->item(0)->textContent;
      $params["numero"]=$curlotto->getAttribute("numero");
      if($params["numero"]=="") $params["numero"]="1";
      $params["codiceFiscaleProp"]=$curlotto->getElementsByTagName("codiceFiscaleProp")->item(0)->textContent;
      $params["denominazione"]=$curlotto->getElementsByTagName("denominazione")->item(0)->textContent;
      $params["oggetto"]=$curlotto->getElementsByTagName("oggetto")->item(0)->textContent;
      $params["sceltaContraente"]=$curlotto->getElementsByTagName("sceltaContraente")->item(0)->textContent;
      $params["dataInizio"]=$curlotto->getElementsByTagName("dataInizio")->item(0)->textContent;
      $params["dataUltimazione"]=$curlotto->getElementsByTagName("dataUltimazione")->item(0)->textContent;
      $params["importoSommeLiquidate"]=$curlotto->getElementsByTagName("importoSommeLiquidate")->item(0)->textContent;
      $params["importoAggiudicazione"]=$curlotto->getElementsByTagName("importoAggiudicazione")->item(0)->textContent;
      $params["id_bando"]=$curlotto->getElementsByTagName("identificativo_web")->item(0)->textContent;
      
      if(!Art37CigIsValid(trim($params["cig"])))
      {
	$xml_result.="<error>Errore durante l'inserimento del lotto (cig:".$params['cig'].") - cig non valido o già inserito, verrà sostituito automaticamente con quello di default</error>";
	$params['cig']="0000000000";
      }

      $idLotto=AddArt37Lotto($params);
      if($idLotto === false)
      {
	$xml_result.="<error>Errore durante l'inserimento del lotto (cig:".$params['cig']." - idScheda:".$result." ) - accesso negato</error>";
      }
      else $xml_result.="<info>Lotto aggiunto (cig:".$params['cig'].")</info>";
    
      $partecipanti=$curlotto->getElementsByTagName("partecipante");
      for($k=0;$k<$partecipanti->length;$k++)
      {
      	unset($params);
      	$params["id_pubblicazione"]=$idScheda;
	$params["id_lotto"]=$idLotto;
	
	$curpartecipante=$partecipanti->item($k);
	if($curpartecipante && is_numeric($idLotto))
	{
	  $params["codiceFiscale"]=$curpartecipante->getElementsByTagName("codiceFiscale")->item(0)->textContent;
	  $params["ragioneSociale"]=$curpartecipante->getElementsByTagName("ragioneSociale")->item(0)->textContent;
	  $params["identificativoFiscaleEstero"]=$curpartecipante->getElementsByTagName("identificativoFiscaleEstero")->item(0)->textContent;
	  $params["gruppo"]=$curpartecipante->getElementsByTagName("gruppo")->item(0)->textContent;
	  $params["ruolo"]=$curpartecipante->getElementsByTagName("ruolo")->item(0)->textContent;
	  
	  if(AddPartecipanteArt37Lotto($params)===false)
	  {
	    $xml_result.="<error>Errore durante l'inserimento del partecipante (".$params['ragioneSociale'].") sul lotto (cig:".$params['cig'].")</error>";
	  }
	  else $xml_result.="<info>Partecipante inserito (".$params['ragioneSociale'].") sul lotto (cig:".$params['cig'].")</info>";
	}
	
      }

      $aggiudicatari=$curlotto->getElementsByTagName("aggiudicatario");
      for($k=0;$k<$aggiudicatari->length;$k++)
      {
	unset($params);
	$params["id_pubblicazione"]=$idScheda;
	$params["id_lotto"]=$idLotto;

	$curaggiudicatario=$aggiudicatari->item($k);	
	if($curaggiudicatario && is_numeric($idLotto))
	{
	  $params["codiceFiscale"]=$curaggiudicatario->getElementsByTagName("codiceFiscale")->item(0)->textContent;
	  $params["ragioneSociale"]=$curaggiudicatario->getElementsByTagName("ragioneSociale")->item(0)->textContent;
	  $params["identificativoFiscaleEstero"]=$curaggiudicatario->getElementsByTagName("identificativoFiscaleEstero")->item(0)->textContent;
	  $params["gruppo"]=$curaggiudicatario->getElementsByTagName("gruppo")->item(0)->textContent;
	  $params["ruolo"]=$curaggiudicatario->getElementsByTagName("ruolo")->item(0)->textContent;
	  
	  if(AddAggiudicatarioArt37Lotto($params)===false)
	  {
	    $xml_result.="<error>Errore durante l'inserimento dell'aggiudicatario (".$params['ragioneSociale'].") sul lotto (cig:".$params['cig'].")</error>";
	  }
	  else $xml_result.="<info>Aggiudicatario inserito (".$params['ragioneSociale'].") sul lotto (cig:".$params['cig'].")</info>";
	}
      }
    }
    
    if(is_numeric($curlotto->getAttribute("id")) && $curlotto->getAttribute("delete") != "1")
    {
      $params["id"]=$curlotto->getAttribute("id");
      if($curlotto->getElementsByTagName("cig")->item(0)->textContent !="") $params["cig"]=$curlotto->getElementsByTagName("cig")->item(0)->textContent;
      if($curlotto->getAttribute("numero") !="") $params["numero"]=$curlotto->getAttribute("numero");
      if($curlotto->getElementsByTagName("codiceFiscaleProp")->item(0)->textContent!="") $params["codiceFiscaleProp"]=$curlotto->getElementsByTagName("codiceFiscaleProp")->item(0)->textContent;
      if($curlotto->getElementsByTagName("denominazione")->item(0)->textContent !="") $params["denominazione"]=$curlotto->getElementsByTagName("denominazione")->item(0)->textContent;
      if($curlotto->getElementsByTagName("oggetto")->item(0)->textContent !="") $params["oggetto"]=$curlotto->getElementsByTagName("oggetto")->item(0)->textContent;
      if($curlotto->getElementsByTagName("sceltaContraente")->item(0)->textContent!="") $params["sceltaContraente"]=$curlotto->getElementsByTagName("sceltaContraente")->item(0)->textContent;
      if($curlotto->getElementsByTagName("dataInizio")->item(0)->textContent!="") $params["dataInizio"]=$curlotto->getElementsByTagName("dataInizio")->item(0)->textContent;
      if($curlotto->getElementsByTagName("dataUltimazione")->item(0)->textContent!="") $params["dataUltimazione"]=$curlotto->getElementsByTagName("dataUltimazione")->item(0)->textContent;
      if($curlotto->getElementsByTagName("importoSommeLiquidate")->item(0)->textContent !="") $params["importoSommeLiquidate"]=$curlotto->getElementsByTagName("importoSommeLiquidate")->item(0)->textContent;
      if($curlotto->getElementsByTagName("importoAggiudicazione")->item(0)->textContent !="") $params["importoAggiudicazione"]=$curlotto->getElementsByTagName("importoAggiudicazione")->item(0)->textContent;
      if($curlotto->getElementsByTagName("identificativo_web")->item(0)->textContent !="") $params["id_bando"]=$curlotto->getElementsByTagName("identificativo_web")->item(0)->textContent;
      
      if(!Art37CigIsValid(trim($params["cig"]),$params['id']) && isset($params["cig"]))
      {
	$xml_result.="<error>Errore durante l'aggiornamento del lotto (cig:".$params['cig'].") - cig non valido o già inserito, verrà sostituito automaticamente con quello di default</error>";
	$params['cig']="0000000000";
      }

      if(UpdatePubArt37Lotto($params) === false)
      {
	$xml_result.="<error>Errore durante l'aggiornamento del lotto (id:".$params['id']." - scheda id:".$params["id_pubblicazione"]." ) - accesso negato</error>";
      }
      else $xml_result.="<info>Lotto aggiornato (id: ".$params['id']." - scheda id: ".$params["id_pubblicazione"]." )</info>";
      
      $partecipanti=$curlotto->getElementsByTagName("partecipante");
      for($k=0;$k<$partecipanti->length;$k++)
      {
	unset($params);
	$params["id_pubblicazione"]=$idScheda;
	$params["id_lotto"]=$curlotto->getAttribute("id");
	
	$curpartecipante=$partecipanti->item($k);
	if($curpartecipante->getAttribute("id") == "")
	{
	  $params["codiceFiscale"]=$curpartecipante->getElementsByTagName("codiceFiscale")->item(0)->textContent;
	  $params["ragioneSociale"]=$curpartecipante->getElementsByTagName("ragioneSociale")->item(0)->textContent;
	  $params["identificativoFiscaleEstero"]=$curpartecipante->getElementsByTagName("identificativoFiscaleEstero")->item(0)->textContent;
	  $params["gruppo"]=$curpartecipante->getElementsByTagName("gruppo")->item(0)->textContent;
	  $params["ruolo"]=$curpartecipante->getElementsByTagName("ruolo")->item(0)->textContent;
	  
	  if(AddPartecipanteArt37Lotto($params)===false)
	  {
	    $xml_result.="<error>Errore durante l'inserimento del partecipante (".$params['ragioneSociale'].") sul lotto (id:".$curlotto->getAttribute("id").")</error>";
	  }
	  else $xml_result.="<info>Partecipante (".$params['ragioneSociale'].") sul lotto (id:".$curlotto->getAttribute("id").") inserito.</info>";
	}
	if(is_numeric($curpartecipante->getAttribute("id")) && $curpartecipante->getAttribute("delete") != "1")
	{
	  $params["idRecPartecipante"]=$curpartecipante->getAttribute("id");
	  if($curpartecipante->getElementsByTagName("codiceFiscale")->item(0)->textContent !="") $params["codiceFiscale"]=$curpartecipante->getElementsByTagName("codiceFiscale")->item(0)->textContent;
	  if($curpartecipante->getElementsByTagName("ragioneSociale")->item(0)->textContent !="") $params["ragioneSociale"]=$curpartecipante->getElementsByTagName("ragioneSociale")->item(0)->textContent;
	  if($curpartecipante->getElementsByTagName("identificativoFiscaleEstero")->item(0)->textContent !="") $params["identificativoFiscaleEstero"]=$curpartecipante->getElementsByTagName("identificativoFiscaleEstero")->item(0)->textContent;
	  if($curpartecipante->getElementsByTagName("gruppo")->item(0)->textContent !="") $params["gruppo"]=$curpartecipante->getElementsByTagName("gruppo")->item(0)->textContent;
	  if($curpartecipante->getElementsByTagName("ruolo")->item(0)->textContent !="") $params["ruolo"]=$curpartecipante->getElementsByTagName("ruolo")->item(0)->textContent;
	  
	  if(UpdatePartecipanteArt37($params)===false)
	  {
	    $xml_result.="<error>Errore durante l'aggiornamento del partecipante (id:".$params['idRecPartecipante'].") sul lotto (id:".$curlotto->getAttribute("id").")</error>";
	  }
	  else $xml_result.="<info>Partecipante (id: ".$params['idRecPartecipante'].") sul lotto (id: ".$curlotto->getAttribute("id").") aggiornato.</info>";
	}
	if(is_numeric($curpartecipante->getAttribute("id")) && $curpartecipante->getAttribute("delete") == "1")
	{
	  $params["id"]=$curpartecipante->getAttribute("id");
	  if(DeletePubArt37Partecipante($params) === false)
	  {
	    $xml_result.="<error>Errore durante l'eliminazione del partecipante (id:".$params['idRecPartecipante'].") sul lotto (id:".$curlotto->getAttribute("id").")</error>";
	  }
	  else $xml_result.="<info>Partecipante (id: ".$params['idRecPartecipante'].") sul lotto (id: ".$curlotto->getAttribute("id").") rimosso.</info>";
	}
      }

      $aggiudicatari=$curlotto->getElementsByTagName("aggiudicatario");
      for($k=0;$k<$aggiudicatari->length;$k++)
      {
      	unset($params);
	$params["id_pubblicazione"]=$idScheda;
	$params["id_lotto"]=$curlotto->getAttribute("id");

	$curaggiudicatario=$aggiudicatari->item($k);
	if($curaggiudicatario->getAttribute("id") == "")
	{
	  $params["codiceFiscale"]=$curaggiudicatario->getElementsByTagName("codiceFiscale")->item(0)->textContent;
	  $params["ragioneSociale"]=$curaggiudicatario->getElementsByTagName("ragioneSociale")->item(0)->textContent;
	  $params["identificativoFiscaleEstero"]=$curaggiudicatario->getElementsByTagName("identificativoFiscaleEstero")->item(0)->textContent;
	  $params["gruppo"]=$curaggiudicatario->getElementsByTagName("gruppo")->item(0)->textContent;
	  $params["ruolo"]=$curaggiudicatario->getElementsByTagName("ruolo")->item(0)->textContent;
	  
	  if(AddAggiudicatarioArt37Lotto($params)===false)
	  {
	    $xml_result.="<error>Errore durante l'inserimento dell'aggiudicatario (".$params['ragioneSociale'].") sul lotto (id:".$curlotto->getAttribute("id").")</error>";
	  }
	  else $xml_result.="<info>Aggiudicatario (".$params['ragioneSociale'].") sul lotto (id:".$curlotto->getAttribute("id").") inserito.</info>";
	}
	
	if(is_numeric($curaggiudicatario->getAttribute("id")) && $curaggiudicatario->getAttribute("delete") != "1")
	{
	  $params["idRecAggiudicatario"]=$curaggiudicatario->getAttribute("id");
	  if($curaggiudicatario->getElementsByTagName("codiceFiscale")->item(0)->textContent !="") $params["codiceFiscale"]=$curaggiudicatario->getElementsByTagName("codiceFiscale")->item(0)->textContent;
	  if($curaggiudicatario->getElementsByTagName("ragioneSociale")->item(0)->textContent !="") $params["ragioneSociale"]=$curaggiudicatario->getElementsByTagName("ragioneSociale")->item(0)->textContent;
	  if($curaggiudicatario->getElementsByTagName("identificativoFiscaleEstero")->item(0)->textContent !="") $params["identificativoFiscaleEstero"]=$curaggiudicatario->getElementsByTagName("identificativoFiscaleEstero")->item(0)->textContent;
	  if($curaggiudicatario->getElementsByTagName("gruppo")->item(0)->textContent !="") $params["gruppo"]=$curaggiudicatario->getElementsByTagName("gruppo")->item(0)->textContent;
	  if($curaggiudicatario->getElementsByTagName("ruolo")->item(0)->textContent !="") $params["ruolo"]=$curaggiudicatario->getElementsByTagName("ruolo")->item(0)->textContent;
	  
	  if(UpdateAggiudicatarioArt37($params)===false)
	  {
	    $xml_result.="<error>Errore durante l'aggiornamento dell'aggiudicatario (id:".$params['idRecAggiudicatario'].") sul lotto (id:".$curlotto->getAttribute("id").")</error>";
	  }
	  else $xml_result.="<info>Aggiudicatario (id:".$params['idRecAggiudicatario'].") sul lotto (id:".$curlotto->getAttribute("id").") aggiornato.</info>";
	}
	if(is_numeric($curaggiudicatario->getAttribute("id")) && $curaggiudicatario->getAttribute("delete") == "1")
	{
	  $params["id"]=$curaggiudicatario->getAttribute("id");
	  if(DeletePubArt37Aggiudicatario($params) === false)
	  {
	    $xml_result.="<error>Errore durante l'eliminazione dell'aggiudicatario (id:".$params['idRecAggiudicatario'].") sul lotto (id:".$curlotto->getAttribute("id").")</error>";
	  }
	  else $xml_result.="<info>Aggiudicatario (id:".$params['idRecAggiudicatario'].") sul lotto (id:".$curlotto->getAttribute("id").") rimosso.</info>";
	}
      }
    }
    
    if(is_numeric($curlotto->getAttribute("id")) && $curlotto->getAttribute("delete") == "1")
    {
	$params["id"]=$curlotto->getAttribute("id");
	if(!DeletePubArt37Lotto($params))
	{
	  $xml_result.="<error>Errore durante l'eliminazione del lotto (id: ".$curlotto->getAttribute("id").")</error>";
	}
	else $xml_result.="<info>Lotto (id:".$curlotto->getAttribute("id").") rimosso.</info>";
    }
  }
  return $xml_result;
}

function Art37_Trash($param)
{
  $idScheda=$param->getElementsByTagName("id_scheda")->item(0)->textContent;
  $params["id"]=$idScheda;
  if($idScheda=="")
  {
    return "<error>identificativo scheda non impostato</error>";
  }
  
  if(!Art37IsCestinata($idScheda)) $message="<info>La scheda (id:".$idScheda.") è stata cestinata.</info>";
  else $message="<info>La scheda (id:".$idScheda.") è stata eliminata.</info>";
  
  if(!DeletePubArt37($params))
  {
    return "<error>Non è stato possibile eliminare la scheda (id:".$idScheda.") - operazione non consentita</error>";
  }
  else return $message;
}

function Art37_Publish($param)
{
  $idScheda=$param->getElementsByTagName("id_scheda")->item(0)->textContent;
  $params["id_pubblicazione"]=$idScheda;
  if($idScheda=="")
  {
    return "<error>identificativo scheda errato o non impostato.</error>";
  }
  
  $test=Art37CanPublish($idScheda);
  if($test !== true)
  {
    return "<error>".$test."</error>";
  }
  
  if(!PubblicaPubArt37($params))
  {
    return "<error>Non è stato possibile pubblicare la scheda (id:".$idScheda.") - operazione non consentita</error>";
  }
  
  $db=new Database();
  $db->Query("SELECT * from appalti_pubblicazioni where id='".$idScheda."'");
  $rs=$db->GetRecordSet();
  $seriale=str_pad($rs->Get('id_assessorato'), 6, "0", STR_PAD_LEFT).substr($rs->Get('dataPubblicazioneDataset'),0,4).str_pad($rs->Get('id'), 10, "0", STR_PAD_LEFT);
  
  return "<id_pubblicazione>".$seriale."</id_pubblicazione>";
}

function Art37_PublishTest($param)
{
  $idScheda=$param->getElementsByTagName("id_scheda")->item(0)->textContent;
  $params["id_pubblicazione"]=$idScheda;
  
  if($idScheda == "" || $idScheda == "0")
  {
    return "<error>identificativo scheda errato o non impostato.</error>";
  }
  
  $test=Art37CanPublish($idScheda);
  if($test !== true)
  {
    return "<error>".$test."</error>";
  }
  else return "<success>La scheda è pubblicabile</success>";
}

function Art15_Query($params)
{
  $id_scheda=$params->getElementsByTagName("id_scheda")->item(0);
  $status=$params->getElementsByTagName("status")->item(0);
  $descrizione=$params->getElementsByTagName("descrizione")->item(0);
  $anno_rif=$params->getElementsByTagName("anno_rif")->item(0);
  $beneficiario=$params->getElementsByTagName("beneficiario")->item(0);
  $estremi=$params->getElementsByTagName("estremiAtto")->item(0);
  
  $db=new Database();
  $query="SELECT * from art15_pubblicazioni WHERE 1";
  $query_count="SELECT count(id) as tot from art15_pubblicazioni WHERE 1";
  $filter ="";
  $order=" ORDER BY dataUltimoAggiornamento DESC";
  $limit=" LIMIT 10";
  if($id_scheda)
  {
    $filter.=" AND id='".$id_scheda->textContent."'";
    if($_SESSION['id_assessorato'] != "0") $filter.=" AND art15_pubblicazioni.id_assessorato = ".$_SESSION['id_assessorato'];
    if($_SESSION['id_direzione'] != "0") $filter.=" AND art15_pubblicazioni.id_direzione = ".$_SESSION['id_direzione'];
    if($_SESSION['id_servizio'] != "0") $filter.=" AND art15_pubblicazioni.id_servizio = ".$_SESSION['id_servizio'];
    
    unset($status);
  }
  
  if($status)
  {
    if($status->textContent=="0")
    {
      $filter.=" AND bozza=1 ";
      if($_SESSION['id_assessorato'] != "0") $filter.=" AND art15_pubblicazioni.id_assessorato = ".$_SESSION['id_assessorato'];
      if($_SESSION['id_direzione'] != "0") $filter.=" AND art15_pubblicazioni.id_direzione = ".$_SESSION['id_direzione'];
      if($_SESSION['id_servizio'] != "0") $filter.=" AND art15_pubblicazioni.id_servizio = ".$_SESSION['id_servizio'];
    }
    if($status->textContent=="1") $filter.=" AND bozza = 0 AND cestinata = 0 AND revisionata = 0";
    if($status->textContent=="2")
    {
      $filter.=" AND revisionata=1";
      if($_SESSION['id_assessorato'] != "0") $filter.=" AND art15_pubblicazioni.id_assessorato = ".$_SESSION['id_assessorato'];
      if($_SESSION['id_direzione'] != "0") $filter.=" AND art15_pubblicazioni.id_direzione = ".$_SESSION['id_direzione'];
      if($_SESSION['id_servizio'] != "0") $filter.=" AND art15_pubblicazioni.id_servizio = ".$_SESSION['id_servizio'];
    }
    if($status->textContent=="3")
    {
      $filter.=" AND cestinata=1 AND bozza = 1";
      if($_SESSION['id_assessorato'] != "0") $filter.=" AND art15_pubblicazioni.id_assessorato = ".$_SESSION['id_assessorato'];
      if($_SESSION['id_direzione'] != "0") $filter.=" AND art15_pubblicazioni.id_direzione = ".$_SESSION['id_direzione'];
      if($_SESSION['id_servizio'] != "0") $filter.=" AND art15_pubblicazioni.id_servizio = ".$_SESSION['id_servizio'];
    }
    if($status->textContent <> "0" && $status->textContent <> "1" && $status->textContent <> "2" && $status->textContent <> "3") $filter.=" AND bozza = 0 AND cestinata = 0 AND revisionata = 0";
  }
  else
  {
    if(is_null($id_scheda)) $filter.=" AND bozza = 0 AND cestinata = 0 AND revisionata = 0";
  }
  
  if($descrizione) $filter.=" AND descrizioneIncarico like '%".$descrizione->textContent."%'";
  if($anno_rif) $filter.=" AND anno_rif like '%".$anno_rif->textContent."%'";
  if($beneficiario) $filter.=" AND (denominazione like '%".$beneficiario->textContent."%' OR cognome like '%".$beneficiario->textContent."%' OR partitaIva like '%".$beneficiario->textContent."%' OR codiceFiscale like '%".$beneficiario->textContent."%')";
  if($estremi) $filter.=" AND estremiAtto like '%".$estremi->textContent."%'";
  
  $db->Query($query_count.$filter);
  $rs=$db->GetRecordSet();
  $count = $rs->Get("tot");
  if( $count > 0)
  {
    $xml_result="<count>".$count."</count>";
    $db->Query($query.$filter.$order.$limit);
    $rs=$db->GetRecordSet();

    if($id_scheda && $id_scheda->getAttribute("showGUI")=="1")
    {
      if($rs->Get("bozza")=="0") $url="stato_pubblicata_search=1&descrizione_search=".$rs->Get("id");
      if($rs->Get("revisionata") == "1") $url="stato_revisionata_search=1&descrizione_search=".$rs->Get("id");
      if($rs->Get("bozza")=="1") $url="stato_bozza_search=1&descrizione_search=".$rs->Get("id");
      
      header('Location: admin/gest_schede/art15.php?'.$url);
      exit;
    }

    do
    {
      $tipo_beneficiario ="";
      if($rs->Get("tipo_beneficiario") & 5) $tipo_beneficiario.="persona_fisica";
      if($rs->Get("tipo_beneficiario") & 1) $tipo_beneficiario.=" internazionale";
      if($rs->Get("tipo_beneficiario") & 2) $tipo_beneficiario.=" ente_pubblico";
      $xml_status = "";
      $xml_result.= "<scheda ";
      $xml_result.= ' id="'.$rs->Get("id").'"';
      if($rs->Get("bozza")=="0")
      {
	$xml_result.= ' id_pubblicazione="'."3315".$rs->Get('id_assessorato').substr($rs->Get('dataPubblicazione'),0,4).str_pad($rs->Get('id'), 10, "0", STR_PAD_LEFT).'"';
	$xml_status = "1";
      }
      if($rs->Get("bozza")=="1") $xml_status = "0";
      if($rs->Get("revisionata")=="1") $xml_status= "2";
      if($rs->Get("cestinata")=="1") $xml_status= "3";
      $xml_result.= ' status = "'.$xml_status.'">';
      $xml_result.= '<denominazione>'.$rs->Get("denominazione").'</denominazione>';
      $xml_result.= '<cognome>'.$rs->Get("cognome").'</cognome>';
      $xml_result.= '<codiceFiscale>'.$rs->Get("codiceFiscale").'</codiceFiscale>';
      $xml_result.= '<partitaIva>'.$rs->Get("partitaIva").'</partitaIva>';
      if($rs->Get("estero")=="0") $estero="N";
      else $estero="S";
      $xml_result.= '<estero>'.$rs->Get("estero").'</estero>';
      $xml_result.= '<annoRiferimento>'.$rs->Get("annoRiferimento").'</annoRiferimento>';
      $xml_result.= '<semestreRiferimento>'.$rs->Get("semestreRiferimento").'</semestreRiferimento>';
      $xml_result.= "<modalitaAcquisizione>".art15GetModalitaAcquisizioneDesc($rs->Get('modalitaAcquisizione'))."</modalitaAcquisizione>";
      if($rs->Get('modalitaAcquisizione') == "1")
      {
	$xml_result.="<norma>".art15GetTipoNormaDesc($rs->Get('rifNormativo'))." n.".$rs->Get("numero");
	if($rs->Get("articolo") != "") $xml_result.=", art.".$rs->Get("articolo");
	if($rs->Get("comma") != "") $xml_result.=", comma".$rs->Get("comma");
	$xml_result.="</norma>";
      }
      $xml_result.="<tipoRapporto>".art15GetTipoRapportoDesc($rs->Get('tipoRapporto'))."</tipoRapporto>";
      $xml_result.="<attivitaEconomica>".art15GetAttivitaEconomicaDesc($rs->Get('attivitaEconomica'))."</attivitaEconomica>";
      $xml_result.="<descrizioneIncarico>".str_replace("&","&amp;",$rs->Get('descrizioneIncarico'))."</descrizioneIncarico>";
      $xml_result.="<dataAffidamento>".$rs->Get('dataAffidamento')."</dataAffidamento>";
      $xml_result.="<dataInizio>".$rs->Get('dataInizio')."</dataInizio>";
      $xml_result.="<dataFine>".$rs->Get('dataFine')."</dataFine>";
      $xml_result.="<importo>".$rs->Get('importo')."</importo>";
      $xml_result.="<importoSaldato>".$rs->Get('pagamentoImporto')."</importoSaldato>";
      $xml_result.="<statoPagamento>".art15GetTipoImporto($rs->Get('incaricoSaldato'))."</statoPagamento>";
      $xml_result.="<parteVariabile>".$rs->Get('parteVariabile')."</parteVariabile>";
      $xml_result.="<pagamentoAnno>".$rs->Get('pagamentoAnno')."</pagamentoAnno>";
      $xml_result.="<pagamentoSemestre>".$rs->Get('pagamentoSemestre')."</pagamentoSemestre>";
      $xml_result.="<soggettoConferente>".$rs->Get('soggettoConferente')."</soggettoConferente>";
      $xml_result.="<dataUltimoAggiornamento>".$rs->Get('dataUltimoAggiornamento')."</dataUltimoAggiornamento>";
      $xml_result.="<estremiAtto>".$rs->Get('estremiAtto')."</estremiAtto>";
      $xml_result.="<incaricoDirigenziale>".str_replace(array("0","1"),array("N","S"),$rs->Get('incaricoDirigenziale'))."</incaricoDirigenziale>";
      if($rs->Get('altriIncarichi')=="1") $altri_incarichi="S";
      else $altri_incarichi="N";
      $xml_result.="<altriIncarichi>".$altri_incarichi."</altriIncarichi>";
      $xml_result.="<note>".str_replace("&","&amp;",$rs->Get('note'))."</note>";
      $path="/home/sitod/uploads/monitspese/art15/curriculum/";
      if(file_exists($path.$rs->Get('id')))
      {
	      $xml_result.="<curriculum>https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/art15/curriculum/?id=".$rs->Get('id')."</curriculum>";
      }
      $path="/home/sitod/uploads/monitspese/art15/dichiarazioni/";
      if(file_exists($path.$rs->Get('id')))
      {
	      $xml_result.="<dichiarazione>https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/art15/dichiarazioni/?id=".$rs->Get('id')."</dichiarazione>";
      }
      $path="/home/sitod/uploads/monitspese/art15/attestazioni/";
      if(file_exists($path.$rs->Get('id')))
      {
	      $xml_result.="<attestazione>https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/art15/attestazioni/?id=".$rs->Get('id')."</attestazione>";
      }
      $xml_result.= "</scheda>";
      
    }while($rs->MoveNext());
    
    return $xml_result;
  }
  else
  {
    return "<count>0</count>";
  }
}
function Art15_QueryTables($params)
{
  $xml_result.="<art15>";
  $tipologiaIncaricato=art15GetTipologiaIncaricato();
  $tipologiaAzienda=art15GetTipologiaAzienda();
  $modalitaAcquisizione=art15GetModalitaAcquisizione();
  $tipoRapporto=art15GetTipoRapporto();
  $attivitaEconomica=art15GetAttivitaEconomica();
  $tipoNorma=art15GetTipoNorma();
  $statoPagamento=art15GetIncaricoSaldato();
  $tipoImporto=art15GetTipoImporto();
  
  $xml_result.="<tipologiaIncaricato>";
  foreach($tipologiaIncaricato as $key=>$value)
  {
    $xml_result.='<voce value="'.$key.'">'.$value.'</voce>';
  }
  $xml_result.="</tipologiaIncaricato>";
  
  $xml_result.="<tipologiaAzienda>";
  foreach($tipologiaAzienda as $key=>$value)
  {
    $xml_result.='<voce value="'.$key.'">'.$value.'</voce>';
  }
  $xml_result.="</tipologiaAzienda>";
  
  $xml_result.="<modalitaAcquisizione>";
  foreach($modalitaAcquisizione as $key=>$value)
  {
    $xml_result.='<voce value="'.$key.'">'.$value.'</voce>';
  }
  $xml_result.="</modalitaAcquisizione>";
  
  $xml_result.="<tipoRapporto>";
  foreach($tipoRapporto as $key=>$value)
  {
    $xml_result.='<voce value="'.$key.'">'.$value.'</voce>';
  }
  $xml_result.="</tipoRapporto>";

  $xml_result.="<attivitaEconomica>";
  foreach($attivitaEconomica as $key=>$value)
  {
    $xml_result.='<voce value="'.$key.'">'.$value.'</voce>';
  }
  $xml_result.="</attivitaEconomica>";

  $xml_result.="<tipoNorma>";
  foreach($tipoNorma as $key=>$value)
  {
    $xml_result.='<voce value="'.$key.'">'.$value.'</voce>';
  }
  $xml_result.="</tipoNorma>";

  $xml_result.="<statoPagamento>";
  foreach($statoPagamento as $key=>$value)
  {
    $xml_result.='<voce value="'.$key.'">'.$value.'</voce>';
  }
  $xml_result.="</statoPagamento>";
  
  $xml_result.="<tipoImporto>";
  foreach($tipoImporto as $key=>$value)
  {
    $xml_result.='<voce value="'.$key.'">'.$value.'</voce>';
  }
  $xml_result.="</tipoImporto>";
  
  $xml_result.="</art15>";
  return $xml_result;
}

function Art15_AddNew($param,$test)
{
  $params['id_assessorato']=$_SESSION["id_assessorato"];
  $params['id_direzione']=$_SESSION["id_direzione"];
  $params['id_servizio']=$_SESSION["id_servizio"];
  $params['id_settore']=$_SESSION["id_settore"];
  $params['id_utente']=$_SESSION["id_utente"];
  
  $params["descrizioneIncarico"]=$param->getElementsByTagName("descrizioneIncarico")->item(0)->textContent;
  $params["tipologiaIncaricato"]=$param->getElementsByTagName("tipologiaIncaricato")->item(0)->textContent;
  $params["codiceFiscale"]=$param->getElementsByTagName("codiceFiscale")->item(0)->textContent;
  $params["partitaIva"]=$param->getElementsByTagName("partitaIva")->item(0)->textContent;
  $params["denominazione"]=$param->getElementsByTagName("denominazione")->item(0)->textContent;
  $params["cognome"]=$param->getElementsByTagName("cognome")->item(0)->textContent;
  $params["tipologiaAzienda"]=$param->getElementsByTagName("tipologiaAzienda")->item(0)->textContent;
  $params["codiceComuneSede"]=$param->getElementsByTagName("codiceComuneSede")->item(0)->textContent;
  $params["estero"]=$param->getElementsByTagName("estero")->item(0)->textContent;
  $params["dataNascita"]=$param->getElementsByTagName("dataNascita")->item(0)->textContent;
  $params["sesso"]=$param->getElementsByTagName("sesso")->item(0)->textContent;
  $params["annoRiferimento"]=$param->getElementsByTagName("annoRiferimento")->item(0)->textContent;
  $params["semestreRiferimento"]=$param->getElementsByTagName("semestreRiferimento")->item(0)->textContent;
  $params["codiceEnte"]=$param->getElementsByTagName("codiceEnte")->item(0)->textContent;
  $params["modalitaAcquisizione"]=$param->getElementsByTagName("modalitaAcquisizione")->item(0)->textContent;
  $params["tipoRapporto"]=$param->getElementsByTagName("tipoRapporto")->item(0)->textContent;
  $params["attivitaEconomica"]=$param->getElementsByTagName("attivitaEconomica")->item(0)->textContent;
  $params["riferimentoRegolamento"]=$param->getElementsByTagName("riferimentoRegolamento")->item(0)->textContent;
  $params["dataAffidamento"]=$param->getElementsByTagName("dataAffidamento")->item(0)->textContent;
  $params["dataInizio"]=$param->getElementsByTagName("dataInizio")->item(0)->textContent;
  $params["dataFine"]=$param->getElementsByTagName("dataFine")->item(0)->textContent;
  $params["incaricoSaldato"]=$param->getElementsByTagName("statoPagamento")->item(0)->textContent;
  $params["tipoImporto"]=$param->getElementsByTagName("tipoImporto")->item(0)->textContent;
  $params["importo"]=$param->getElementsByTagName("importo")->item(0)->textContent;
  if($param->getElementsByTagName("parteVariabile")->item(0) != null) $params["parteVariabile"]=$param->getElementsByTagName("parteVariabile")->item(0)->textContent;
  $params["note"]=$param->getElementsByTagName("note")->item(0)->textContent;
  if($param->getElementsByTagName("norma")->item(0) != null)
  {
    if($param->getElementsByTagName("norma")->item(0) != null) $params["rifNormativo"]=$param->getElementsByTagName("norma")->item(0)->textContent;
    if($param->getElementsByTagName("norma")->item(0)->getAttribute("comma") != null) $params["comma"]=$param->getElementsByTagName("norma")->item(0)->getAttribute("comma");
    if($param->getElementsByTagName("norma")->item(0)->getAttribute("articolo") != null) $params["articolo"]=$param->getElementsByTagName("norma")->item(0)->getAttribute("articolo");
    if($param->getElementsByTagName("norma")->item(0)->getAttribute("numero") != null) $params["numero"]=$param->getElementsByTagName("norma")->item(0)->getAttribute("numero");
    if($param->getElementsByTagName("norma")->item(0)->getAttribute("data") != null) $params["dataRifNormativo"]=$param->getElementsByTagName("norma")->item(0)->getAttribute("data");
  }
  
  //$params["comma"]=$param->getElementsByTagName("comma")->item(0)->textContent;
  //$params["articolo"]=$param->getElementsByTagName("articolo")->item(0)->textContent;
  //$params["numero"]=$param->getElementsByTagName("numero")->item(0)->textContent;
  //$params["dataRifNormativo"]=$param->getElementsByTagName("dataRifNormativo")->item(0)->textContent;
  //$params["rifNormativo"]=$param->getElementsByTagName("rifNormativo")->item(0)->textContent;
  
  $params["estremiAtto"]=$param->getElementsByTagName("estremiAtto")->item(0)->textContent;
  $params["pagamentoImporto"]=$param->getElementsByTagName("importoSaldato")->item(0)->textContent;
  $params["pagamentoAnno"]=$param->getElementsByTagName("pagamentoAnno")->item(0)->textContent;
  $params["pagamentoSemestre"]=$param->getElementsByTagName("pagamentoSemestre")->item(0)->textContent;
  $params["soggettoConferente"]=$param->getElementsByTagName("soggettoConferente")->item(0)->textContent;
  if($param->getElementsByTagName("rifNormativo")->item(0) != null) $params["rifNormativo"]=$param->getElementsByTagName("rifNormativo")->item(0)->textContent;
  if($param->getElementsByTagName("incaricoDirigenziale")->item(0) != null) $params["incaricoDirigenziale"]=str_replace(array("n","N","s","S"),array("0","0","1","1"),$param->getElementsByTagName("incaricoDirigenziale")->item(0)->textContent);
  if($param->getElementsByTagName("altriIncarichi")->item(0) != null) $params["altriIncarichi"]=str_replace(array("n","N","s","S"),array("0","0","1","1"),$param->getElementsByTagName("altriIncarichi")->item(0)->textContent);
  
  $idScheda=AddPubArt15($params);
  if($idScheda === false)
  {
    return "<error>errore durante l'inserimento della nuova bozza</error>";
  }
  else return '<scheda id="'.$idScheda.'" />';
}

function Art15_Modify($param)
{
  $params['id_assessorato']=$_SESSION["id_assessorato"];
  $params['id_direzione']=$_SESSION["id_direzione"];
  $params['id_servizio']=$_SESSION["id_servizio"];
  $params['id_settore']=$_SESSION["id_settore"];
  $params['id_utente']=$_SESSION["id_utente"];
 
  $idScheda=$param->getElementsByTagName("id_scheda")->item(0)->textContent;
  $params["id"]=$idScheda;
  if($idScheda == "")
  {
    return "<error>identificativo scheda non impostato</error>";
  }
  
  if($param->getElementsByTagName("descrizioneIncarico")->item(0) != null) $params["descrizioneIncarico"]=$param->getElementsByTagName("descrizioneIncarico")->item(0)->textContent;
  if($param->getElementsByTagName("tipologiaIncaricato")->item(0) != null)$params["tipologiaIncaricato"]=$param->getElementsByTagName("tipologiaIncaricato")->item(0)->textContent;
  if($param->getElementsByTagName("codiceFiscale")->item(0) != null) $params["codiceFiscale"]=$param->getElementsByTagName("tipologiaIncaricato")->item(0)->textContent;
  if($param->getElementsByTagName("partitaIva")->item(0) != null) $params["partitaIva"]=$param->getElementsByTagName("partitaIva")->item(0)->textContent;
  if($param->getElementsByTagName("denominazione")->item(0) != null) $params["denominazione"]=$param->getElementsByTagName("denominazione")->item(0)->textContent;
  if($param->getElementsByTagName("cognome")->item(0) != null) $params["cognome"]=$param->getElementsByTagName("cognome")->item(0)->textContent;
  if($param->getElementsByTagName("tipologiaAzienda")->item(0) != null) $params["tipologiaAzienda"]=$param->getElementsByTagName("tipologiaAzienda")->item(0)->textContent;
  if($param->getElementsByTagName("codiceComuneSede")->item(0) != null) $params["codiceComuneSede"]=$param->getElementsByTagName("codiceComuneSede")->item(0)->textContent;
  if($param->getElementsByTagName("estero")->item(0) != null) $params["estero"]=$param->getElementsByTagName("estero")->item(0)->textContent;
  if($param->getElementsByTagName("dataNascita")->item(0) != null) $params["dataNascita"]=$param->getElementsByTagName("dataNascita")->item(0)->textContent;
  if($param->getElementsByTagName("sesso")->item(0) != null) $params["sesso"]=$param->getElementsByTagName("sesso")->item(0)->textContent;
  if($param->getElementsByTagName("annoRiferimento")->item(0) != null) $params["annoRiferimento"]=$param->getElementsByTagName("annoRiferimento")->item(0)->textContent;
  if($param->getElementsByTagName("semestreRiferimento")->item(0) != null) $params["semestreRiferimento"]=$param->getElementsByTagName("semestreRiferimento")->item(0)->textContent;
  if($param->getElementsByTagName("codiceEnte")->item(0) != null) $params["codiceEnte"]=$param->getElementsByTagName("codiceEnte")->item(0)->textContent;
  if($param->getElementsByTagName("modalitaAcquisizione")->item(0) != null) $params["modalitaAcquisizione"]=$param->getElementsByTagName("modalitaAcquisizione")->item(0)->textContent;
  if($param->getElementsByTagName("tipoRapporto")->item(0) != null) $params["tipoRapporto"]=$param->getElementsByTagName("tipoRapporto")->item(0)->textContent;
  if($param->getElementsByTagName("attivitaEconomica")->item(0) != null) $params["attivitaEconomica"]=$param->getElementsByTagName("attivitaEconomica")->item(0)->textContent;
  if($param->getElementsByTagName("riferimentoRegolamento")->item(0) != null) $params["riferimentoRegolamento"]=$param->getElementsByTagName("riferimentoRegolamento")->item(0)->textContent;
  if($param->getElementsByTagName("dataAffidamento")->item(0) != null) $params["dataAffidamento"]=$param->getElementsByTagName("dataAffidamento")->item(0)->textContent;
  if($param->getElementsByTagName("dataInizio")->item(0) != null) $params["dataInizio"]=$param->getElementsByTagName("dataInizio")->item(0)->textContent;
  if($param->getElementsByTagName("dataFine")->item(0) != null) $params["dataFine"]=$param->getElementsByTagName("dataFine")->item(0)->textContent;
  if($param->getElementsByTagName("statoPagamento")->item(0) != null) $params["incaricoSaldato"]=$param->getElementsByTagName("statoPagamento")->item(0)->textContent;
  if($param->getElementsByTagName("tipoImporto")->item(0) != null) $params["tipoImporto"]=$param->getElementsByTagName("tipoImporto")->item(0)->textContent;
  if($param->getElementsByTagName("importo")->item(0) != null) $params["importo"]=$param->getElementsByTagName("importo")->item(0)->textContent;
  if($param->getElementsByTagName("parteVariabile")->item(0) != null) $params["parteVariabile"]=$param->getElementsByTagName("parteVariabile")->item(0)->textContent;
  if($param->getElementsByTagName("norma")->item(0) != null)
  {
    if($param->getElementsByTagName("norma")->item(0) != null) $params["rifNormativo"]=$param->getElementsByTagName("norma")->item(0)->textContent;
    if($param->getElementsByTagName("norma")->item(0)->getAttribute("comma") != null) $params["comma"]=$param->getElementsByTagName("norma")->item(0)->getAttribute("comma");
    if($param->getElementsByTagName("norma")->item(0)->getAttribute("articolo") != null) $params["articolo"]=$param->getElementsByTagName("norma")->item(0)->getAttribute("articolo");
    if($param->getElementsByTagName("norma")->item(0)->getAttribute("numero") != null) $params["numero"]=$param->getElementsByTagName("norma")->item(0)->getAttribute("numero");
    if($param->getElementsByTagName("norma")->item(0)->getAttribute("data") != null) $params["dataRifNormativo"]=$param->getElementsByTagName("norma")->item(0)->getAttribute("data");
  }
  if($param->getElementsByTagName("note")->item(0) != null) $params["note"]=$param->getElementsByTagName("note")->item(0)->textContent;
  if($param->getElementsByTagName("estremiAtto")->item(0) != null) $params["estremiAtto"]=$param->getElementsByTagName("estremiAtto")->item(0)->textContent;
  if($param->getElementsByTagName("pagamentoImporto")->item(0) != null) $params["pagamentoImporto"]=$param->getElementsByTagName("importoSaldato")->item(0)->textContent;
  if($param->getElementsByTagName("pagamentoAnno")->item(0) != null) $params["pagamentoAnno"]=$param->getElementsByTagName("pagamentoAnno")->item(0)->textContent;
  if($param->getElementsByTagName("pagamentoSemestre")->item(0) != null) $params["pagamentoSemestre"]=$param->getElementsByTagName("pagamentoSemestre")->item(0)->textContent;
  if($param->getElementsByTagName("soggettoConferente")->item(0) != null) $params["soggettoConferente"]=$param->getElementsByTagName("soggettoConferente")->item(0)->textContent;
  if($param->getElementsByTagName("incaricoDirigenziale")->item(0) != null) $params["incaricoDirigenziale"]=str_replace(array("n","N","s","S"),array("0","0","1","1"),$param->getElementsByTagName("incaricoDirigenziale")->item(0)->textContent);
  if($param->getElementsByTagName("altriIncarichi")->item(0) != null) $params["altriIncarichi"]=str_replace(array("n","N","s","S"),array("0","0","1","1"),$param->getElementsByTagName("altriIncarichi")->item(0)->textContent);
  
  if(UpdatePubArt15($params) === false)
  {
    return "<error>Errore durante l'aggiornamento della scheda (id: ".$idScheda.").</error>";
  }
  else return "<info>La scheda (id: ".$idScheda.") è stata aggiornata.</info>";
}
function Art15_Trash($param)
{
  $idScheda=$param->getElementsByTagName("id_scheda")->item(0)->textContent;
  $params["id"]=$idScheda;
  if($idScheda=="")
  {
    return "<error>identificativo scheda non impostato</error>";
  }
  
  $message="<info>La scheda (id:".$idScheda.") è stata cestinata.</info>";
  if(Art15IsCestinata($idScheda) && !Art15IsPubblicata($idScheda)) $message="<info>La scheda (id:".$idScheda.") è stata eliminata.</info>";
    
  if(!DeletePubArt15($params))
  {
    return "<error>Non è stato possibile eliminare la scheda (id:".$idScheda.") - operazione non consentita</error>";
  }
  else return $message;
}

function Art15_Publish($param)
{
  $idScheda=$param->getElementsByTagName("id_scheda")->item(0)->textContent;
  $params["id_pubblicazione"]=$idScheda;
  if($idScheda=="")
  {
    return "<error>identificativo scheda non impostato</error>";
  }
  
  if(!PubblicaPubArt15($params))
  {
    return "<error>Non è stato possibile pubblicare la scheda (id:".$idScheda.") - operazione non consentita</error>";
  }
  
  $db=new Database();
  $db->Query("SELECT * from art15_pubblicazioni where id='".$idScheda."'");
  $rs=$db->GetRecordSet();
  $seriale="3315".$rs->Get('id_assessorato').substr($rs->Get('dataPubblicazione'),0,4).str_pad($rs->Get('id'), 10, "0", STR_PAD_LEFT);
  
  return "<id_pubblicazione>".$seriale."</id_pubblicazione>";
}

function Art15_PublishTest($param)
{
  $idScheda=$param->getElementsByTagName("id_scheda")->item(0)->textContent;
  $params["id_pubblicazione"]=$idScheda;
  if($idScheda=="")
  {
    return "<error>identificativo scheda non impostato</error>";
  }
  
  if(!PubblicaPubArt15Test($params))
  {
    return "<error>Non è stato possibile pubblicare la scheda (id:".$idScheda.") - operazione non consentita</error>";
  }
  else return "<success>La scheda è pubblicabile</success>";
}

//Pubblicazioni art. 14 comma 1b d.lgs. 33/3013
function Art14_1b_Query($param)
{

  $anno=$param->getElementsByTagName("anno")->item(0)->textContent;
  $nome=$param->getElementsByTagName("nome")->item(0)->textContent;
  $cognome=$param->getElementsByTagName("cognome")->item(0)->textContent;
  $email=$param->getElementsByTagName("email")->item(0)->textContent;
  $verbose=$param->getElementsByTagName("verbose")->item(0)->textContent;
  $id_struttura=$param->getElementsByTagName("id_struttura")->item(0)->textContent; //identificativo struttura, lasciare vuoto per RAS
  $struttura=$param->getElementsByTagName("struttura")->item(0)->textContent; //nome struttura, lasciare vuoto per RAS
  $id_user=$param->getElementsByTagName("id_user")->item(0)->textContent; //identificativo utente
  $onlyReport=$param->getElementsByTagName("report")->item(0)->textContent; //Visualizza solo il report
  $download=$param->getElementsByTagName("download")->item(0)->textContent; //Scarica le dichiarazione
  $po=false;
  if($param->getElementsByTagName("po")->item(0)->textContent=="1") $po=true; //Scarica il curriculum per le posizioni organizzative
  if($anno == "")
  {
    $anno=date("Y");
  } 

  

  //Download il curriculum del dirigente
  if($download !="") return Art14_1b_Download($email,$anno,$id_user,$po);

  //Restituisce il report di assolvimento dei dirigenti
  if($onlyReport=="2") return Art14_1b_ReportDirigenti($param);

  //accesso al db
  $db=new Database();

  //Recupera gli identificativi dei dirigenti
  $query="SELECT utenti.*, assessorati.descrizione as struttura  from utenti left join assessorati on utenti.id_assessorato=assessorati.id where utenti.livello = '0' and utenti.eliminato = '0' ";
  if($id_struttura != "") $query.=" and utenti.id_assessorato='".$id_struttura."'";
  if($struttura != "") $query.=" and assessorati.descrizione like '%".$struttura."%'";
  if($nome != "") $query.=" and utenti.nome like '".$nome."'";
  if($cognome != "") $query.=" and utenti.cognome like '".$cognome."'";
  if($email != "") $query.=" and email like '".$email."'";
  if($id_user != "") $query.=" and utenti.id like '".$id_user."'";

  //Percorso dichiarazioni
  $path = "/home/sitod/uploads/amministrazione_trasparente/art14/1bis-dirigenti/1b_curriculum/".$anno."/";
  $dichiarazioni=scandir($path);

  $tot_assolti=0;
  $tot_non_conformi=0;

  //Dirigenti RAS
  if($id_struttura == "" && $struttura == "")
  {
    $query.=" and (assessorati.tipo = '0' or assessorati.tipo = '3')";
    $dirigenti_list=file_get_contents($path."all_dirs.txt");
    if($dirigenti_list !="") $dirigenti=split("\n",$dirigenti_list);
  }

  $query.=" ORDER by utenti.cognome, utenti.nome, utenti.user, utenti.email";

  $return="<art14_1b>";
  $return.="<anno>".$anno."</anno>";

  $count = 0;
  
  //Urlbase curriculum
  $urlbase="https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/curriculum/?anno=".$anno."&amp;uid=";
  $db->Query($query);
  $rs=$db->GetRecordSet();
  if($rs->GetCount() > 0)
  {
    $lastPerson=$rs->Get("nome").$rs->Get("cognome").$rs->Get("email"); //flag di verifica se si tratta della stessa persona
    $first=true;
    do
    {
      //id dirigente
      $id_dirigente=$rs->Get("id");
      
      $curPerson=$rs->Get("nome").$rs->Get("cognome").$rs->Get("email");

      //Verifica la presenza di almeno una dichiarazione
      $bInsert=0;
      $nonConforme=0;
      foreach ($dichiarazioni as $curDichiarazione)
      {
        //if($id_dirigente=="2410") error_log("file corrente: ".$curDichiarazione." - id dirigente: ".$id_dirigente." - check: ".strpos($curDichiarazione,$id_dirigente."_"));
        if(strpos($curDichiarazione,$id_dirigente."_") === 0)
        {
          if(substr($curDichiarazione, -3) == "pdf")
          {
            //error_log("inserito: ".$id_dirigente);
            $bInsert=1;
            break;
          }
          else
          {
            $nonConforme=1;
          }
        } 
      }

      if($bInsert==1)
      {
          //Aggiorna la lista dei dirigenti
          if($curPerson != "")
          {
            foreach($dirigenti as $key=>$value)
            {
              if(stripos($value,$rs->Get("nome"))!==FALSE && stripos($value,$rs->Get("cognome")) !==FALSE)
              {
                $dirigenti[$key].=" - Assolto";
                $tot_assolti++;
              }
            }
          }

        if($curPerson != $lastPerson || ($curPerson=="" && $lastPerson=="")) 
        {
          if($first==true) $return.='<dirigente uid="'.$id_dirigente.'">';
          else $return.='</curriculum></dirigente><dirigente uid="'.$id_dirigente.'">';
          if($curPerson == "")
          {
            $return.="<nome>".$rs->Get("user")."</nome>";
            $return.="<cognome>".$id_dirigente."</cognome>";
            if($verbose !="") $return.="<email>".$rs->Get("user")."@regione.sardegna.it</email>";
          }
          else
          {
            $return.="<nome>".$rs->Get("nome")."</nome>";
            $return.="<cognome>".$rs->Get("cognome")."</cognome>";
            if($verbose !="") $return.="<email>".$rs->Get("email")."</email>";
          }
          if($verbose !="") $return.="<struttura>".$rs->Get("struttura")."</struttura>";
          $return.="<curriculum>";
          $return.="<url>".$urlbase.$id_dirigente."</url>";
        }
        else $return.="<url>".$urlbase.$id_dirigente."</url>";
        
        $count++;
        $first=false;
        $lastPerson=$curPerson;
      }

      if($nonConforme == 1)
      {
        if($curPerson != "")
        {
          foreach($dirigenti as $key=>$value)
          {
            if(stripos($value,$rs->Get("nome"))!==FALSE && stripos($value,$rs->Get("cognome")) !==FALSE)
            {
              $dirigenti[$key].=" - Non conforme";
              $tot_non_conformi++;
            }
          }
        }
      }
    }while($rs->MoveNext());

    if($count > 0) $return.="</curriculum></dirigente>";
    $return.="<count>".$count."</count></art14_1b>";

    if($onlyReport)
    {
      header("Cache-control: private");
      header("Content-type: text/html");
      foreach($dirigenti as $dirigente)
      {
        echo $dirigente."</br>";
      }

      echo "Dirigenti che hanno assolto: ".$tot_assolti."</br>";
      echo "Dirigenti che hanno caricato file non conformi: ".$tot_non_conformi."</br>";
      echo "Dirigenti che devono assolvere: ".(count($dirigenti)-$tot_assolti)."</br>";

      die();
    }
    return $return;
  }
  else
  {
    error_log("Art14_1b_Query() - query vuota: ".$query);
    return $return."<count>0</count></art14_1b>";
  }
}

//Pubblicazioni art. 14, comma 1b, d.lgs. 33/3013 - report
function Art14_1b_ReportDirigenti($param)
{
  //Verifica abilitazione
  //if(!UserHasFlag("art14_1b_report")) return("<art14_1b><error>Utente non abilitato al servizio.</error></art14_1b>");

  $anno=$param->getElementsByTagName("anno")->item(0)->textContent;
  $id_struttura=$param->getElementsByTagName("id_struttura")->item(0)->textContent; //identificativo struttura, lasciare vuoto per RAS
  if($id_struttura != $_SESSION['id_assessorato'] && $_SESSION['tipo_struct'] != "0" && $_SESSION['id_user'] !='1') $id_struttura=$_SESSION['id_assessorato'];
  
  // Notifica via email.
  // null (default): nessuna notifica
  // 1: notifica gli adempienti
  // 2: notifica i non conformi
  // 0: notifica gli inadempienti
  $notify=$param->getElementsByTagName("notify")->item(0)->textContent;
  
  //Imposta di default l'anno corrente
  if($anno=="") $anno=date("Y");

  //Percorso curriculum
  $path = "/home/sitod/uploads/amministrazione_trasparente/art14/1bis-dirigenti/1b_curriculum/".$anno."/";
  $dichiarazioni=scandir($path);

  //Lista dirigenti struttura
  $FileListaAssente=false;
  if($id_struttura == "")
  {
    $dirigenti_list=file_get_contents($path."ras_dir.csv");
    if($dirigenti_list !="") $dirigenti_rows=explode("\n",$dirigenti_list);
    else $FileListaAssente=true;
  }
  else
  {
    $dirigenti_list=file_get_contents($path.$id_struttura."_dir.csv");
    if($dirigenti_list !="") $dirigenti_rows=split("\n",$dirigenti_list);    
    else $FileListaAssente=true;
  }

  //header xml
  $return='<art14_1b anno="'.$anno.'">';

  //Non è presente la lista dei dirigenti
  if($FileListaAssente)
  {
    $return.="<error>Lista dirigenti assente</error></art14_1b>";
    return $return;
  }

  //accesso al db
  $db=new Database();

  foreach ($dirigenti_rows as $key=>$value)
  {
      //error_log($value);
      $curDirigente=explode('|',$value); //formato: nome|cognome|email

      if(trim($curDirigente[2]) !="" && $value !="") //email valorizzata
      {
        //Cerca il dirigente nel db
        $query="SELECT utenti.*,assessorati.descrizione as assessorato, direzioni.descrizione as direzione, servizi.descrizione as servizio from utenti left join assessorati on utenti.id_assessorato=assessorati.id left join direzioni on utenti.id_direzione=direzioni.id left join servizi on utenti.id_servizio=servizi.id where utenti.email like '".trim($curDirigente[2])."' and utenti.disable=0 and utenti.eliminato=0 order by email";

        $db->Query($query);
        $rs=$db->GetRecordSet();
        if($rs->GetCount() > 0)
        {
          do
          {
            $return.='<dirigente uid="'.$rs->Get("id").'"><nome>'.trim($rs->Get("nome")).'</nome><cognome>'.trim($rs->Get("cognome")).'</cognome><email>'.trim($rs->Get("email")).'</email>';
            $return.='<struttura>';
            if($rs->Get("assessorato") !="") $return.="<assessorato>".trim($rs->Get("assessorato"))."</assessorato>";
            if($rs->Get("direzione") !="") $return.="<direzione>".trim($rs->Get("direzione"))."</direzione>";
            if($rs->Get("servizio") !="") $return.="<servizio>".trim($rs->Get("servizio"))."</servizio>";
            $return.='</struttura>';

            $nonConforme = false;
            $conforme = false;
            
            foreach($dichiarazioni as $curDichiarazione)
            {
                if(strpos($curDichiarazione,$rs->Get("id")."_") === 0)
                {
                  if(substr($curDichiarazione, -3) == "pdf")
                  {
                    $conforme=true;
                  }
                  else
                  {
                    $nonConforme=true;
                  }
                }
            }

            $struttura=trim($rs->Get("assessorato"))." - ".trim($rs->Get("direzione"))." - ".trim($rs->Get("servizio"))." - uid: ".$rs->Get("id")." - utente: ".$rs->Get("user");
            $email=$rs->Get("email");
            //$email="esaiucarta@presidenza.regione.sardegna.it";

            if(!$conforme && !$nonConforme)
            {
              $return.="<status>0</status>"; //Dichiarazione non presente
              if($notify !="" && $notify =="0")
              {
                if(!Art14_1b_NotifyDir($email,$anno,$struttura,0)) error_log("Art14_1b_ReportDirigenti() - Errore durante l'invio della notifica a: ".$rs->Get("email"));
                else error_log("Art14_1b_ReportDirigenti() - Notifica inviata a: ".$rs->Get("email"));
              }
            }

            if($conforme && !$nonConforme)
            {
              $return.="<status>1</status>"; //Dichiarazioni conformi
              if($notify !="" && $notify =="1")
              {
                if(!Art14_1b_NotifyDir($email,$anno,$struttura,1)) error_log("Art14_1b_ReportDirigenti() - Errore durante l'invio della notifica a: ".$rs->Get("email"));
                else error_log("Art14_1b_ReportDirigenti() - Notifica inviata a: ".$rs->Get("email"));
              }
            }

            if(!$conforme && $nonConforme)
            {
              $return.="<status>2</status>";  //Dichiarazione presente non conforme
              if($notify !="" && $notify =="2")
              {
                if(!Art14_1b_NotifyDir($email,$anno,$struttura,2)) error_log("Art14_1b_ReportDirigenti() - Errore durante l'invio della notifica a: ".$rs->Get("email"));
                else error_log("Art14_1b_ReportDirigenti() - Notifica inviata a: ".$rs->Get("email"));
              }
            } 
            
            if($conforme && $nonConforme)
            {
              $return.="<status>3</status>";  //Alcune dichiarazioni non conformi  
              if($notify !="" && $notify =="2")
              {
                if(!Art14_1b_NotifyDir($email,$anno,$struttura,2)) error_log("Art14_1b_ReportDirigenti() - Errore durante l'invio della notifica a: ".$rs->Get("email"));
                else error_log("Art14_1b_ReportDirigenti() - Notifica inviata a: ".$rs->Get("email"));
              }              
            } 
            
            $return.="</dirigente>";
          }while($rs->MoveNext()); 
        }
        else
        {
          //dirigente non presente sul db o senza email impostata
          $return.='<dirigente uid="0"><nome>'.trim($curDirigente[0]).'</nome><cognome>'.trim($curDirigente[1]).'</cognome><email>'.$curDirigente[2].'</email></dirigente>';
        }
      }
      else
      {
        //dirigente senza email
        if($value !="") $return.='<dirigente uid="0"><nome>'.trim($curDirigente[0]).'</nome><cognome>'.trim($curDirigente[1]).'</cognome></dirigente>';
      }
  }
  $return.="</art14_1b>";

  return $return;
}

//Pubblicazioni art. 14, comma 1b, d.lgs. 33/3013 - download
function Art14_1b_Download($email,$anno,$uid,$po=false,$bUrl=false)
{
  //Nuova gestione incarichi dal 2021
  if($anno >= "2021")
  {
    $titolare=AA_IncarichiTitolare::LoadFromEmail($email);
    if($titolare instanceof AA_IncarichiTitolare)
    {
      $curriculum=$titolare->GetLastCurriculum();
      if($curriculum !="")
      {
        //restituisce l'url
        if($bUrl) return $curriculum;

        header("Cache-control: private");
        header("Content-type: application/pdf");
        header('Location: '.$curriculum);
        exit();
      }
      else
      {
        if($bUrl) return "https://sitod.regione.sardegna.it/web/amministrazione_aperta/pubblicazioni/art14/curriculum/?email=XXXXXXXXXXXXXXXXXXXXX";
      }
    }
  }

  //Download curriculum posizioni organizzative
  if($po)
  {
    $curriculum_file=md5($email).".pdf";
    $path = "/home/sitod/uploads/amministrazione_trasparente/art14/curriculum/";  

    if(file_exists($path.$curriculum_file))
    {
      if($bUrl)
      {
         return "https://sitod.regione.sardegna.it/web/amministrazione_aperta/pubblicazioni/art14/curriculum/?email=".$email."&anno=".$anno."&po=1";
      } 
      header("Cache-control: private");
      header("Content-type: application/pdf");
      header("Content-Length: ".filesize($path.$curriculum_file));
      header('Content-Disposition: attachment; filename="curriculum.pdf"');
      $filename = $path.$curriculum_file;
      $fd = fopen ($filename, "rb");
      echo fread ($fd, filesize ($filename));
      fclose ($fd);
      exit();
    }
    else
    {
      if($bUrl) return "https://sitod.regione.sardegna.it/web/amministrazione_aperta/pubblicazioni/art14/curriculum/?email=XXXXXXXXXXXXXXXXXXXXX";
      return 0;
    }
  }

  //Percorso curriculum
  $path = "/home/sitod/uploads/amministrazione_trasparente/art14/1bis-dirigenti/1b_curriculum/".$anno."/";
  if(!is_dir($path))
  {
    if($bUrl) return "https://sitod.regione.sardegna.it/web/amministrazione_aperta/pubblicazioni/art14/curriculum/?email=XXXXXXXXXXXXXXXXXXXXX";
    return 0;
  }
  
  $dichiarazioni=scandir($path);

  $zip = new ZipArchive();
  $zip_path="/tmp/".uniqid().".zip";
  $num_files=0;
  if ($zip->open($zip_path, ZipArchive::CREATE)!==TRUE)
  {
    error_log("Art14_1b_Download() - errore durante la creazione del file zip temporaneo: ".$zip_path);
    exit("Errore durante la generazione del file temporaneo (AA_ERR_143301).");
  }

  //$zip->addFromString("info.txt" . time(), "Questo file contiene le dichiarazio");
  
  //Cerca per email
  if($email !="" && $uid == "")
  {
    $db=new Database();
    $db->Query("SELECT GROUP_CONCAT(id) as ids FROM `utenti` WHERE email like '".$email."'");
    $rs=$db->GetRecordSet();

    $ids="";

    if($rs->GetCount() > 0)
    {
      $ids=explode(",",$rs->Get('ids'));
    }
    else 
    {
      error_log("Art14_1b_Download() - errore durante la rimozione del file compresso: ".$zip_path." - nessun curriculum trovata.");
      if($bUrl) return "https://sitod.regione.sardegna.it/web/amministrazione_aperta/pubblicazioni/art14/curriculum/?email=XXXXXXXXXXXXXXXXXXXXX";
      exit("Art14_1b_Download() - Nessun curriculum trovato per l'email: ".$email);
    }
  }
  
  foreach($dichiarazioni as $curDichiarazione)
  {
    $include=false;

    if($ids != "")
    {
      foreach($ids as $curID)
      {
        if(strpos($curDichiarazione,$curID."_") === 0 && substr($curDichiarazione, -3) == "pdf")
        {
          if($bUrl) return "https://sitod.regione.sardegna.it/web/amministrazione_aperta/pubblicazioni/art14/curriculum/?email=".$email."&anno=".$anno;

          if(!$zip->addFile($path.$curDichiarazione, $curDichiarazione))
          {
            error_log("Art14_1b_Download() - errore durante l'inserimento del file: ".$curDichiarazione." - nel file compresso: ".$zip_path);
            $zip->close();
            if(!unlink($zip_path))
            {
              error_log("Art14_1b_Download() - errore durante la rimozione del file compresso: ".$zip_path);
              exit("Errore durante la rimozione del file temporaneo (AA_ERR_143302).");;
            }
            exit("Errore durante l'inserimento del curriculum: ".$curDichiarazione." nel file temporaneo (AA_ERR_143303).");;
          }
          $num_files++;
        }        
      }
    }

    if(strpos($curDichiarazione,$uid."_") === 0 && substr($curDichiarazione, -3) == "pdf" && $ids == "")
    {
        if($bUrl) return "https://sitod.regione.sardegna.it/web/amministrazione_aperta/pubblicazioni/art14/curriculum/?email=".$email."&anno=".$anno;

        if(!$zip->addFile($path.$curDichiarazione, $curDichiarazione))
        {
          error_log("Art14_1b_Download() - errore durante l'inserimento del file: ".$curDichiarazione." - nel file compresso: ".$zip_path);
          $zip->close();
          if(!unlink($zip_path))
          {
            error_log("Art14_1b_Download() - errore durante la rimozione del file compresso: ".$zip_path);
            exit("Errore durante la rimozione del file temporaneo (AA_ERR_143302).");;
          }
          exit("Errore durante l'inserimento del curriculum: ".$curDichiarazione." nel file temporaneo (AA_ERR_143303).");;
        }
        $num_files++;
    }
  }

  $zip->close();

  if($num_files > 0)
  {
    if($bUrl) return "https://sitod.regione.sardegna.it/web/amministrazione_aperta/pubblicazioni/art14/curriculum/?email=".$email."&anno=".$anno;

    header("Cache-control: private");
    header("Content-type: application/octet-stream");
    header("Content-Length: ".filesize($zip_path));
    header('Content-Disposition: attachment; filename="curriculum.zip"');
    $filename = $zip_path;
    $fd = fopen ($filename, "rb");
    echo fread ($fd, filesize ($filename));
    fclose ($fd);
  }
  else
  {
    //Prova a scaricare il curriculum dei titolari di posizione organizzativa
    $curriculum_file=md5($email).".pdf";
    $path = "/home/sitod/uploads/amministrazione_trasparente/art14/curriculum/";  

    if(file_exists($path.$curriculum_file))
    {
      //Restituisce l'url
      if($bUrl) return "https://sitod.regione.sardegna.it/web/amministrazione_aperta/pubblicazioni/art14/curriculum/?email=".$email."&anno=".$anno."&po=1";

      header("Cache-control: private");
      header("Content-type: application/pdf");
      header("Content-Length: ".filesize($path.$curriculum_file));
      header('Content-Disposition: attachment; filename="curriculum.pdf"');
      $filename = $path.$curriculum_file;
      $fd = fopen ($filename, "rb");
      echo fread ($fd, filesize ($filename));
      fclose ($fd);
    }
    else
    {
      if($bUrl) return "https://sitod.regione.sardegna.it/web/amministrazione_aperta/pubblicazioni/art14/curriculum/?email=XXXXXXXXXXXXXXXXXXXXX";
        
      if(!unlink($zip_path))
      {
        error_log("Art14_1b_Download() - errore durante la rimozione del file compresso: ".$zip_path." - nessun curriculum trovato.");
        return -1;
      }
      
      return -1;
    }
  }

  if(!unlink($zip_path))
  {
    error_log("Art14_1b_Download() - errore durante la rimozione del file compresso: ".$zip_path." - nessun curriculum trovato.");
    return -1;
  }

	return 0;
}

//Notifica il dirigente sullo stato delle pubblicazioni sul curriculum
function Art14_1b_NotifyDir($mail="",$anno="",$struttura="",$type="",$oggetto="",$corpo="",$firma="")
{
    if($mail=="") return false;

    if($anno=="") $anno=date("Y");
    if($struttura=="") $struttura="struttura non indicata";
    if($oggetto=="") $oggetto="Amministrazione Aperta - Notifica a seguito di controllo automatizzato";

    //Non conforme
    if($corpo=="" && $type >=2) $corpo='<p>Buongiorno,
    A seguito di un controllo automatizzato risulta che il curriculum, per l\'anno '.$anno.', rilasciato ai sensi dell\'art.14 comma 1b d.lgs.33/2013, per l\'incarico di direttore della struttura:
    '.$struttura.'

    Caricata sulla piattaforma "Amministrazione Aperta", non è conforme alle seguenti specifiche di pubblicazione:
    1) Il file, in formato pdf, deve essere firmato digitalmente in modalità Pades.

    Occorre, pertanto, procedere alla sostituzione, sulla piattaforma "Amministrazione Aperta", del curriculum non conforme con un nuovo curriculum conforme alle specifiche sopra indicate.

    Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>';

    //Non caricato
    if($corpo=="" && $type == 0) $corpo='<p>Buongiorno,
    A seguito di un controllo automatizzato non risulta caricato il curriculum, per l\'anno '.$anno.', rilasciato ai sensi dell\'art.14 comma 1b d.lgs.33/2013, per l\'incarico di direttore della struttura:
    '.$struttura.'

    Occorre, pertanto, procedere al caricamento, sulla piattaforma "Amministrazione Aperta", del curriculum.

    Si ricorda che il curriculum va caricato in formato pdf firmate digitalmente in modalità Pades.
        
    Si chiede di segnalare se questo messaggio si riferisce ad un incarico cessato o non corretto in modo che vengano aggiornati gli archivi e non venga più inviato.

    Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>';

    //conformi
    if($corpo=="" && $type == 1) $corpo='<p>Buongiorno,
    A seguito di un controllo automatizzato si notifica che il curriculum, per l\'anno '.$anno.', rilasciato ai sensi dell\'art.14, comma 1b d.lgs.33/2013, per l\'incarico di direttore della struttura:
    '.$struttura.'

    risultano caricate in piattaforma e conformi alle specifiche di pubblicazione sul sito istituzionale.

    Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>';

    if($firma=="") $firma='<div>--
                <div><strong>Amministrazione Aperta</strong></div>
                <div>Presidentzia</div>
                <div>Presidenza</div>
                <div>Ufficio del Responsabile della prevenzione della corruzione e della trasparenza</div>
                <div>V.le Trento, 69 - 09123 Cagliari</div>
                <img src="https:///sitod.regione.sardegna.it/web/logo.jpg" data-mce-src=https:////sitod.regione.sardegna.it/web/logo.jpg" moz-do-not-send="true" width="205" height="60"></div>';

    return SendMail(array($mail), array(), $oggetto,nl2br($corpo).$firma,array(),1);
}

//Pubblicazioni art. 20 d.lgs. 39/3013
function Art20_39_Query($param)
{
  $anno=$param->getElementsByTagName("anno")->item(0)->textContent;
  $nome=$param->getElementsByTagName("nome")->item(0)->textContent;
  $cognome=$param->getElementsByTagName("cognome")->item(0)->textContent;
  $email=$param->getElementsByTagName("email")->item(0)->textContent;
  $verbose=$param->getElementsByTagName("verbose")->item(0)->textContent;
  $id_struttura=$param->getElementsByTagName("id_struttura")->item(0)->textContent; //identificativo struttura, lasciare vuoto per RAS
  $struttura=$param->getElementsByTagName("struttura")->item(0)->textContent; //nome struttura, lasciare vuoto per RAS
  $id_user=$param->getElementsByTagName("id_user")->item(0)->textContent; //identificativo utente
  $onlyReport=$param->getElementsByTagName("report")->item(0)->textContent; //Visualizza solo il report
  $download=$param->getElementsByTagName("download")->item(0)->textContent; //Scarica le dichiarazione
  $tipo_incarico=$param->getElementsByTagName("tipo_incarico")->item(0)->textContent; //tipologia incarico
  if($tipo_incarico == "") $tipo_incarico=0;
  if($anno == "") $anno=date("Y");

  //Download le dichiarazioni del dirigente
  if($download !="") return Art20_39_Download($id_user,$anno, $email);

  //Restituisce il report di assolvimento dei dirigenti
  if($onlyReport=="2") return Art20_39_ReportDirigenti($param);

  //accesso al db
  $db=new Database();

  //Recupera gli identificativi dei dirigenti
  $query="SELECT utenti.*, assessorati.descrizione as struttura  from utenti left join assessorati on utenti.id_assessorato=assessorati.id where utenti.livello = '0' and utenti.eliminato = '0' ";
  if($id_struttura != "") $query.=" and utenti.id_assessorato='".$id_struttura."'";
  if($struttura != "") $query.=" and assessorati.descrizione like '".$struttura."'";
  if($nome != "") $query.=" and utenti.nome like '".$nome."'";
  if($cognome != "") $query.=" and utenti.cognome like '".$cognome."'";
  if($email != "") $query.=" and email like '".$email."'";
  if($id_user != "") $query.=" and utenti.id like '".$id_user."'";
  if($tipo_incarico != "") $query.=" and utenti.tipo_incarico = '".$tipo_incarico."'";

  //Percorso dichiarazioni
  $path = "/home/sitod/uploads/amministrazione_trasparente/art14/1bis-dirigenti/dichiarazioni/".$anno."/";
  $dichiarazioni=scandir($path);

  $tot_assolti=0;
  $tot_non_conformi=0;

  //Dirigenti RAS
  if($id_struttura == "" && $struttura == "")
  {
    $query.=" and (assessorati.tipo='0' or assessorati.tipo='3')";
    $dirigenti_list=file_get_contents($path."all_dirs.txt");
    if($dirigenti_list !="") $dirigenti=split("\n",$dirigenti_list);
  }

  $query.=" ORDER by utenti.cognome, utenti.nome, utenti.user, utenti.email";

  $return="<art20_39>";
  $return.="<anno>".$anno."</anno>";

  $count = 0;
  
  //Urlbase dichiarazioni
  $urlbase="https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art20_39/?anno=".$anno."&amp;uid=";
  $db->Query($query);
  $rs=$db->GetRecordSet();
  if($rs->GetCount() > 0)
  {
    $lastPerson=$rs->Get("nome").$rs->Get("cognome").$rs->Get("email"); //flag di verifica se si tratta della stessa persona
    $first=true;
    do
    {
      //id dirigente
      $id_dirigente=$rs->Get("id");
      
      $curPerson=$rs->Get("nome").$rs->Get("cognome").$rs->Get("email");

      //Verifica la presenza di almeno una dichiarazione
      $bInsert=0;
      $nonConforme=0;
      foreach ($dichiarazioni as $curDichiarazione)
      {
        //if($id_dirigente=="2410") error_log("file corrente: ".$curDichiarazione." - id dirigente: ".$id_dirigente." - check: ".strpos($curDichiarazione,$id_dirigente."_"));
        if(strpos($curDichiarazione,$id_dirigente."_") === 0)
        {
          if(substr($curDichiarazione, -3) == "pdf")
          {
            //error_log("inserito: ".$id_dirigente);
            $bInsert=1;
            break;
          }
          else
          {
            $nonConforme=1;
          }
        } 
      }

      if($bInsert==1)
      {
          //Aggiorna la lista dei dirigenti
          if($curPerson != "")
          {
            foreach($dirigenti as $key=>$value)
            {
              if(stripos($value,$rs->Get("nome"))!==FALSE && stripos($value,$rs->Get("cognome")) !==FALSE)
              {
                $dirigenti[$key].=" - Assolto";
                $tot_assolti++;
              }
            }
          }

        if($curPerson != $lastPerson || ($curPerson=="" && $lastPerson=="")) 
        {
          if($first==true) $return.='<dirigente uid="'.$id_dirigente.'">';
          else $return.='</dichiarazioni></dirigente><dirigente uid="'.$id_dirigente.'">';
          if($curPerson == "")
          {
            $return.="<nome>".$rs->Get("user")."</nome>";
            $return.="<cognome>".$id_dirigente."</cognome>";
            if($verbose !="") $return.="<email>".$rs->Get("user")."@regione.sardegna.it</email>";
          }
          else
          {
            $return.="<nome>".$rs->Get("nome")."</nome>";
            $return.="<cognome>".$rs->Get("cognome")."</cognome>";
            if($verbose !="") $return.="<email>".$rs->Get("email")."</email>";
          }
          if($verbose !="") $return.="<struttura>".$rs->Get("struttura")."</struttura>";
          $return.="<dichiarazioni>";
          $return.="<url>".$urlbase.$id_dirigente."</url>";
        }
        else $return.="<url>".$urlbase.$id_dirigente."</url>";
        
        $count++;
        $first=false;
        $lastPerson=$curPerson;
      }

      if($nonConforme == 1)
      {
        if($curPerson != "")
        {
          foreach($dirigenti as $key=>$value)
          {
            if(stripos($value,$rs->Get("nome"))!==FALSE && stripos($value,$rs->Get("cognome")) !==FALSE)
            {
              $dirigenti[$key].=" - Non conforme";
              $tot_non_conformi++;
            }
          }
        }
      }
    }while($rs->MoveNext());

    if($count > 0) $return.="</dichiarazioni></dirigente>";
    $return.="<count>".$count."</count></art20_39>";

    if($onlyReport)
    {
      header("Cache-control: private");
      header("Content-type: text/html");
      foreach($dirigenti as $dirigente)
      {
        echo $dirigente."</br>";
      }

      echo "Dirigenti che hanno assolto: ".$tot_assolti."</br>";
      echo "Dirigenti che hanno caricato file non conformi: ".$tot_non_conformi."</br>";
      echo "Dirigenti che devono assolvere: ".(count($dirigenti)-$tot_assolti)."</br>";

      die();
    }
    return $return;
  }
  else
  {
    error_log("Art20_39_Query() - query vuota: ".$query);
    return $return."<count>0</count></art20_39>";
  }
}

//Pubblicazioni art. 20 d.lgs. 39/3013 - report
function Art20_39_ReportDirigenti($param)
{
  //Verifica abilitazione
  //if(!UserHasFlag("art20_39_report")) return("<art20_39><error>Utente non abilitato al servizio.</error></art20_39>");

  $anno=$param->getElementsByTagName("anno")->item(0)->textContent;
  $id_struttura=$param->getElementsByTagName("id_struttura")->item(0)->textContent; //identificativo struttura, lasciare vuoto per RAS
  if($id_struttura != $_SESSION['id_assessorato'] && $_SESSION['tipo_struct'] != "0" && $_SESSION['id_user'] !='1') $id_struttura=$_SESSION['id_assessorato'];
  
  //Notifica via email.
  // null (default): nessuna notifica
  // 1: notifica gli adempienti
  // 2: notifica i non conformi
  // 0: notifica gli inadempienti
  $notify=$param->getElementsByTagName("notify")->item(0)->textContent;
  
  //Imposta di default l'anno corrente
  if($anno=="") $anno=date("Y");

  //Percorso dichiarazioni
  $path = "/home/sitod/uploads/amministrazione_trasparente/art14/1bis-dirigenti/dichiarazioni/".$anno."/";
  $dichiarazioni=scandir($path);

  //Lista dirigenti struttura
  $FileListaAssente=false;
  if($id_struttura == "")
  {
    $dirigenti_list=file_get_contents($path."ras_dir.csv");
    if($dirigenti_list !="") $dirigenti_rows=explode("\n",$dirigenti_list);
    else $FileListaAssente=true;
  }
  else
  {
    $dirigenti_list=file_get_contents($path.$id_struttura."_dir.csv");
    if($dirigenti_list !="") $dirigenti_rows=split("\n",$dirigenti_list);    
    else $FileListaAssente=true;
  }

  //header xml
  $return='<art20_39 anno="'.$anno.'">';

  //Non è presente la lista dei dirigenti
  if($FileListaAssente)
  {
    $return.="<error>Lista dirigenti assente</error></art20_39>";
    return $return;
  }

  //accesso al db
  $db=new Database();

  foreach ($dirigenti_rows as $key=>$value)
  {
      //error_log($value);
      $curDirigente=explode('|',$value); //formato: nome|cognome|email

      if(trim($curDirigente[2]) !="" && $value !="") //email valorizzata
      {
        //Cerca il dirigente nel db
        $query="SELECT utenti.*,assessorati.descrizione as assessorato, direzioni.descrizione as direzione, servizi.descrizione as servizio from utenti left join assessorati on utenti.id_assessorato=assessorati.id left join direzioni on utenti.id_direzione=direzioni.id left join servizi on utenti.id_servizio=servizi.id where utenti.email like '".trim($curDirigente[2])."' and utenti.disable=0 and utenti.eliminato=0 order by email";

        $db->Query($query);
        $rs=$db->GetRecordSet();
        if($rs->GetCount() > 0)
        {
          do
          {
            $return.='<dirigente uid="'.$rs->Get("id").'"><nome>'.trim($rs->Get("nome")).'</nome><cognome>'.trim($rs->Get("cognome")).'</cognome><email>'.trim($rs->Get("email")).'</email>';
            $return.='<struttura>';
            if($rs->Get("assessorato") !="") $return.="<assessorato>".trim($rs->Get("assessorato"))."</assessorato>";
            if($rs->Get("direzione") !="") $return.="<direzione>".trim($rs->Get("direzione"))."</direzione>";
            if($rs->Get("servizio") !="") $return.="<servizio>".trim($rs->Get("servizio"))."</servizio>";
            $return.='</struttura>';

            $nonConforme = false;
            $conforme = false;
            
            foreach($dichiarazioni as $curDichiarazione)
            {
                if(strpos($curDichiarazione,$rs->Get("id")."_") === 0)
                {
                  if(substr($curDichiarazione, -3) == "pdf")
                  {
                    $conforme=true;
                  }
                  else
                  {
                    $nonConforme=true;
                  }
                }
            }

            $struttura=trim($rs->Get("assessorato"))." - ".trim($rs->Get("direzione"))." - ".trim($rs->Get("servizio"))." - uid: ".$rs->Get("id")." - utente: ".$rs->Get("user");
            $email=$rs->Get("email");
            //$email="esaiucarta@presidenza.regione.sardegna.it";

            if(!$conforme && !$nonConforme)
            {
              $return.="<status>0</status>"; //Dichiarazione non presente
              if($notify !="" && $notify =="0")
              {
                if(!Art20_39_NotifyDir($email,$anno,$struttura,0)) error_log("Art20_39_ReportDirigenti() - Errore durante l'invio della notifica a: ".$rs->Get("email"));
                else error_log("Art20_39_ReportDirigenti() - Notifica inviata a: ".$rs->Get("email"));
              }
            }

            if($conforme && !$nonConforme)
            {
              $return.="<status>1</status>"; //Dichiarazioni conformi
              if($notify !="" && $notify =="1")
              {
                if(!Art20_39_NotifyDir($email,$anno,$struttura,1)) error_log("Art20_39_ReportDirigenti() - Errore durante l'invio della notifica a: ".$rs->Get("email"));
                else error_log("Art20_39_ReportDirigenti() - Notifica inviata a: ".$rs->Get("email"));
              }
            }

            if(!$conforme && $nonConforme)
            {
              $return.="<status>2</status>";  //Dichiarazione presente non conforme
              if($notify !="" && $notify =="2")
              {
                if(!Art20_39_NotifyDir($email,$anno,$struttura,2)) error_log("Art20_39_ReportDirigenti() - Errore durante l'invio della notifica a: ".$rs->Get("email"));
                else error_log("Art20_39_ReportDirigenti() - Notifica inviata a: ".$rs->Get("email"));
              }
            } 
            
            if($conforme && $nonConforme)
            {
              $return.="<status>3</status>";  //Alcune dichiarazioni non conformi  
              if($notify !="" && $notify =="2")
              {
                if(!Art20_39_NotifyDir($email,$anno,$struttura,2)) error_log("Art20_39_ReportDirigenti() - Errore durante l'invio della notifica a: ".$rs->Get("email"));
                else error_log("Art20_39_ReportDirigenti() - Notifica inviata a: ".$rs->Get("email"));
              }              
            } 
            
            $return.="</dirigente>";
          }while($rs->MoveNext()); 
        }
        else
        {
          //dirigente non presente sul db o senza email impostata
          $return.='<dirigente uid="0"><nome>'.trim($curDirigente[0]).'</nome><cognome>'.trim($curDirigente[1]).'</cognome><email>'.$curDirigente[2].'</email></dirigente>';
        }
      }
      else
      {
        //dirigente senza email
        if($value !="") $return.='<dirigente uid="0"><nome>'.trim($curDirigente[0]).'</nome><cognome>'.trim($curDirigente[1]).'</cognome></dirigente>';
      }
  }
  $return.="</art20_39>";

  return $return;
}

//Pubblicazioni art. 20 d.lgs. 39/3013 - download
function Art20_39_Download($uid,$anno,$email="")
{
  //Percorso dichiarazioni
  $path = "/home/sitod/uploads/amministrazione_trasparente/art14/1bis-dirigenti/dichiarazioni/".$anno."/";
  $dichiarazioni=scandir($path);

  $zip = new ZipArchive();
  $zip_path="/tmp/".uniqid().".zip";
  $num_files=0;
  if ($zip->open($zip_path, ZipArchive::CREATE)!==TRUE)
  {
    error_log("Art20_39_Download() - errore durante la creazione del file zip temporaneo:".$zip_path);
    exit("Errore durante la generazione del file temporaneo (AA_ERR_203901).");
  }
  
  //Cerca per email
  if($email !="" && $uid == "")
  {
    $db=new Database();
    $db->Query("SELECT GROUP_CONCAT(id) as ids FROM `utenti` WHERE email like '".$email."'");
    $rs=$db->GetRecordSet();

    $ids="";

    if($rs->GetCount() > 0)
    {
      $ids=explode(",",$rs->Get('ids'));
    }
    else 
    {
      error_log("Art20_39_Download()  - errore durante la rimozione del file compresso: ".$zip_path." - nessun curriculum trovata.");
      exit("Art20_39_Download()  - Nessun curriculum trovato per l'email: ".$email);
    }
  }
  
  foreach($dichiarazioni as $curDichiarazione)
  {
    $include=false;

    if($ids != "")
    {
      foreach($ids as $curID)
      {
        if(strpos($curDichiarazione,$curID."_") === 0 && substr($curDichiarazione, -3) == "pdf")
        {
            if(!$zip->addFile($path.$curDichiarazione, $curDichiarazione))
            {
              error_log("Art20_39_Download() - errore durante l'inserimento del file: ".$curDichiarazione." - nel file compresso: ".$zip_path);
              $zip->close();
              if(!unlink($zip_path))
              {
                error_log("Art20_39_Download() - errore durante la rimozione del file compresso: ".$zip_path);
                exit("Errore durante la rimozione del file temporaneo (AA_ERR_203902).");;
              }
              exit("Errore durante l'inserimento della dichiarazione: ".$curDichiarazione." nel file temporaneo (AA_ERR_203903).");;
            }
            $num_files++;
        }        
      }
    }

    if(strpos($curDichiarazione,$uid."_") === 0 && substr($curDichiarazione, -3) == "pdf" && $ids == "")
    {
        if(!$zip->addFile($path.$curDichiarazione, $curDichiarazione))
        {
          error_log("Art20_39_Download() - errore durante l'inserimento del file: ".$curDichiarazione." - nel file compresso: ".$zip_path);
          $zip->close();
          if(!unlink($zip_path))
          {
            error_log("Art20_39_Download() - errore durante la rimozione del file compresso: ".$zip_path);
            exit("Errore durante la rimozione del file temporaneo (AA_ERR_203902).");;
          }
          exit("Errore durante l'inserimento della dichiarazione: ".$curDichiarazione." nel file temporaneo (AA_ERR_203903).");;
        }
        $num_files++;
    }
  }

  $zip->close();

  if($num_files > 0)
  {
    header("Cache-control: private");
    header("Content-type: application/octet-stream");
    header("Content-Length: ".filesize($zip_path));
    header('Content-Disposition: attachment; filename="dichiarazioni.zip"');
    $filename = $zip_path;
    $fd = fopen ($filename, "rb");
    echo fread ($fd, filesize ($filename));
    fclose ($fd);
  }

  if(!unlink($zip_path))
  {
    error_log("Art20_39_Download() - errore durante la rimozione del file compresso: ".$zip_path." - nessuna dichiarazione trovata.");
    exit("Nessuna dichiarazione trovata.");
  }
  
  exit();
}

//Notifica il dirigente sullo stato delle dichiarazioni
function Art20_39_NotifyDir($mail="",$anno="",$struttura="",$type="",$oggetto="",$corpo="",$firma="")
{
    if($mail=="") return false;

    if($anno=="") $anno=date("Y");
    if($struttura=="") $struttura="struttura non indicata";
    if($oggetto=="") $oggetto="Amministrazione Aperta - Notifica a seguito di controllo automatizzato";

    //Non conforme
    if($corpo=="" && $type >=2) $corpo='<p>Buongiorno,
    A seguito di un controllo automatizzato risulta che almeno una dichiarazione, per l\'anno '.$anno.', rilasciata ai sensi dell\'art.20 d.lgs.39/2013, per l\'incarico di direttore della struttura:
    '.$struttura.'

    Caricata sulla piattaforma "Amministrazione Aperta", non è conforme alle seguenti specifiche di pubblicazione:
    1) Il file, in formato pdf, deve essere firmato digitalmente in modalità Pades.

    Occorre, pertanto, procedere alla sostituzione, sulla piattaforma "Amministrazione Aperta", delle dichiarazioni non conformi con nuove dichiarazioni conformi alle specifiche sopra indicate.

    Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>';

    //Non caricato
    if($corpo=="" && $type == 0) $corpo='<p>Buongiorno,
    A seguito di un controllo automatizzato non risultano caricate le dichiarazioni, per l\'anno '.$anno.', rilasciate ai sensi dell\'art.20 d.lgs.39/2013, per l\'incarico di direttore della struttura:
    '.$struttura.'

    Occorre, pertanto, procedere al caricamento, sulla piattaforma "Amministrazione Aperta", delle dichiarazioni di cui sopra.

    Si ricorda che le dichiarazioni devono essere rilasciate esclusivamente dai dirigenti o dai funzionari con incarico dirigenziale, redatte sulla base del modello scaricabile dal sito istituzionale, vanno caricate in formato pdf firmate digitalmente in modalità Pades.
        
    Si chiede di segnalare se questo messaggio si riferisce ad un incarico cessato o non corretto in modo che vengano aggiornati gli archivi e non venga più inviato.

    Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>';

    //conformi
    if($corpo=="" && $type == 1) $corpo='<p>Buongiorno,
    A seguito di un controllo automatizzato si notifica che le dichiarazioni, per l\'anno '.$anno.', rilasciate ai sensi dell\'art.20 d.lgs.39/2013, per l\'incarico di direttore della struttura:
    '.$struttura.'

    risultano caricate in piattaforma e conformi alle specifiche di pubblicazione sul sito istituzionale.

    Questo messaggio automatizzato non sostituisce le verifiche di validità delle dichiarazioni da parte degli organi competenti.

    Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>';

    if($firma=="") $firma='<div>--
                <div><strong>Amministrazione Aperta</strong></div>
                <div>Presidentzia</div>
                <div>Presidenza</div>
                <div>Ufficio del Responsabile della prevenzione della corruzione e della trasparenza</div>
                <div>V.le Trento, 69 - 09123 Cagliari</div>
                <img src="https:///sitod.regione.sardegna.it/web/logo.jpg" data-mce-src=https:////sitod.regione.sardegna.it/web/logo.jpg" moz-do-not-send="true" width="205" height="60"></div>';

    return SendMail(array($mail), array(), $oggetto,nl2br($corpo).$firma,array(),1);
}

//Query Articolo 12 d.lgs 33/2013
function Art12_Query($params)
{
  $oggetto=$params->getElementsByTagName("oggetto")->item(0)->textContent;
  $tipo=$params->getElementsByTagName("tipo")->item(0)->textContent;
  $concluse_dal=$params->getElementsByTagName("concluse_dal")->item(0)->textContent;
  $concluse_al=$params->getElementsByTagName("concluse_al")->item(0)->textContent;

}

//Gestisci le chiamate alle funzioni
function AA_FunctionCallFromXML($params)
{
  AA_Log::Log("AA_FunctionCallFromXML($params)");

  $AA_Class=$params->getElementsByTagName("aa_class")->item(0)->textContent;
  $AA_Function=$params->getElementsByTagName("aa_function")->item(0)->textContent;
  $AA_FunctionParams=array();
  foreach ($params->getElementsByTagName("aa_function_param") as $curParam)
  {
    $AA_FunctionParams[]=$curParam->textContent;
  }

  if(function_exists($AA_Function) || function_exists($AA_Class::$AA_Function))
  {
    if($AA_Class != "") $AA_Class::$AA_Function(implode(",",$AA_FunctionParams));
    else $AA_Function(implode(",",$AA_FunctionParams));
  }
  else
  {
    AA_Log::Log("AA_FunctionCallFromXML($params) - Funzione non esistente: ".$AA_Class."::".$AA_Function,100);
  }
}

//Flusso xml pubblicazioni in capo ai titolari di posizione dirigenziale (art 14 d.lgs. 33/2013 e art 20 d.lgs. 39/2013)
function AA_XML_ReportDirigenti($param="")
{
  //Parametri
  $anno=$param->getElementsByTagName("anno")->item(0)->textContent;
  $nome=$param->getElementsByTagName("nome")->item(0)->textContent;
  $cognome=$param->getElementsByTagName("cognome")->item(0)->textContent;
  $email=$param->getElementsByTagName("email")->item(0)->textContent;
  $verbose=$param->getElementsByTagName("verbose")->item(0)->textContent;
  $id_struttura=$param->getElementsByTagName("id_struttura")->item(0)->textContent; //identificativo struttura, lasciare vuoto per RAS
  $struttura=$param->getElementsByTagName("struttura")->item(0)->textContent; //nome struttura, lasciare vuoto per RAS
  $id_user=$param->getElementsByTagName("id_user")->item(0)->textContent; //identificativo utente
  $tipo_incarico=$param->getElementsByTagName("tipo_incarico")->item(0)->textContent; //tipologia incarico
  $output=$param->getElementsByTagName("output")->item(0)->textContent; //tipologia output ("pdf","csv","xml");
  if($output == "") $output="xml";
  if($anno == "") $anno=date("Y");

  //accesso al db
  $db=new Database();

  //Recupera gli identificativi dei dirigenti
  $query="SELECT utenti.nome, utenti.cognome, utenti.user, utenti.email,utenti.id, utenti.tipo_incarico, assessorati.descrizione as struttura, direzioni.descrizione as dirgen  from utenti left join assessorati on utenti.id_assessorato=assessorati.id left join direzioni on utenti.id_direzione=direzioni.id where utenti.livello = '0' and utenti.disable='0' and utenti.eliminato = '0' and utenti.email not like '' and data_creazione <= '".$anno."-12-31' ";
  if($id_struttura != "") $query.=" and utenti.id_assessorato='".$id_struttura."'";
  if($struttura != "") $query.=" and assessorati.descrizione like '%".$struttura."%'";
  if($nome != "") $query.=" and utenti.nome like '".$nome."'";
  if($cognome != "") $query.=" and utenti.cognome like '".$cognome."'";
  if($email != "") $query.=" and email like '".$email."'";
  if($id_user != "") $query.=" and utenti.id like '".$id_user."'";
  if($tipo_incarico !="") $query.=" and utenti.tipo_incarico ='".$tipo_incarico."'";

  //Percorso generale
  $path = "/home/sitod/uploads/amministrazione_trasparente/art14/1bis-dirigenti";
  $path_curriculum=$path."/1b_curriculum/".$anno."/";
  $path_art20=$path."/dichiarazioni/".$anno."/";
  $path_altri_incarichi=$path."/1d_1e_altri_incarichi/".$anno."/";
  $path_1ter=$path."/1ter/".$anno."/";

  $curriculum=scandir($path_curriculum);
  $dichiarazioni=scandir($path_art20);
  $altri_incarichi=scandir($path_altri_incarichi);
  $comma_1ter=scandir($path_1ter);

  //Dirigenti RAS
  if($id_struttura == "" && $struttura == "")
  {
    $query.=" and (assessorati.tipo = '0' or assessorati.tipo = '3')";
    //logo
    $logo="logo_ras.gif";
  }
  else
  {
    if($struttura == "ASPAL") $logo="27_logo.jpg";
  }

  $query.=" ORDER by utenti.cognome, utenti.nome, utenti.email, utenti.id, utenti.user";

  $return="<report_pubblicazioni_dirigenti>";
  $return.="<anno>".$anno."</anno>";

  $count = 0;
  $dirigenti=array();
  
  $db->Query($query);
  $rs=$db->GetRecordSet();
  if($rs->GetCount() > 0)
  {
    do
    {
      //id dirigente
      $id_dirigente=$rs->Get("id");
      $email_dir=$rs->Get("email");
      
      $dirigenti[$email_dir]['nome']=$rs->Get("nome");
      $dirigenti[$email_dir]['cognome']=$rs->Get("cognome");
      if($tipo_incarico !=1) $dirigenti[$email_dir]['struttura']=$rs->Get("struttura");
      else $dirigenti[$email_dir]['struttura']=$rs->Get("dirgen");
      if($rs->Get("tipo_incarico") == 0) $dirigenti[$email_dir]['incarico']="Direttore";
      if($rs->Get("tipo_incarico") == 1) $dirigenti[$email_dir]['incarico']="Direttore Generale";
      if($rs->Get("tipo_incarico") == 2) $dirigenti[$email_dir]['incarico']="Capo ufficio gabinetto";
      if($dirigenti[$email_dir]['id'] !="") $dirigenti[$email_dir]['id'].="|".$id_dirigente;
      else  $dirigenti[$email_dir]['id'].=$id_dirigente;


      //------- Verifica curriculum ------
      foreach ($curriculum as $curFile)
      {
        //error_log("file corrente: ".$curFile." - id dirigente: ".$id_dirigente." - check: ".strpos($curFile,$id_dirigente."_"));
        if(strpos($curFile,$id_dirigente."_") === 0)
        {
          if(substr($curFile, -3) == "pdf")
          {
            $dirigenti[$email_dir]["curriculum"]=1;
            break;
          }
        } 
      }
      //----- Fine verifica curriculum -------

      //------- Verifica art 20 ------
      if($tipo_incarico < 2) //solo dirigenti
      {
        foreach ($dichiarazioni as $curFile)
        {
          if(strpos($curFile,$id_dirigente."_") === 0)
          {
            if(substr($curFile, -3) == "pdf")
            {
              $dirigenti[$email_dir]["art20"]=1;
              break;
            }
          } 
        }  
      }
      //----- Fine verifica art20 -------

      //------- Verifica altri incarichi ------
      foreach ($altri_incarichi as $curFile)
      {
        if(strpos($curFile,$id_dirigente."_") === 0)
        {
          if(substr($curFile, -3) == "pdf")
          {
            //error_log("inserito: ".$id_dirigente);
            $dirigenti[$email_dir]["altri_incarichi"]=1;
            break;
          }
        } 
      }
      //----- Fine verifica altri incarichi -------
      
      //------- Verifica 1 ter ------
      if($tipo_incarico <= 2) //solo dirigenti e cappi ufficio di gabinetto
      {        
        foreach ($comma_1ter as $curFile)
        {
          if(strpos($curFile,$id_dirigente."_") === 0)
          {
            if(substr($curFile, -3) == "pdf")
            {
              //error_log("inserito: ".$id_dirigente);
              $dirigenti[$email_dir]["1ter"]=1;
              break;
            }
          } 
        }
      }
      //----- Fine verifica 1ter -------
      $count++;
    }while($rs->MoveNext());

    $return.="<count>".$count."</count>";

    $doc=null;
    if($output=="pdf")
    {
      if($struttura !="") $id="_".$struttura;
      if($id_struttura !="") $id="_".$id_struttura;
      $doc = new AA_PDF_RAS_TEMPLATE_A4_LANDSCAPE("pubblicazioni_art14_".$id."_".$tipo_incarico."_".$anno);
      $doc->SetLogoImage($logo);
      $doc->SetDocumentStyle("font-family: sans-serif; font-size: 3mm;");
      $doc->SetPageCorpoStyle("border: 1px solid black; display: flex; flex-direction: column; justify-content: space-between; padding:0;");
      if($tipo_incarico == 0) $doc->SetTitle("Pubblicazioni dei titolari di incarichi dirigenziali non di vertice, ai sensi dell'art.14, comma 1-bis e 1-ter del d.lgs. 33/2013 e art. 20 d.lgs.39/2013 - anno ".$anno);
      if($tipo_incarico == 1) $doc->SetTitle("Pubblicazioni dei titolari di incarichi dirigenziali di vertice, ai sensi dell'art.14, comma 1-bis e 1-ter del d.lgs. 33/2013 e art. 20 d.lgs.39/2013 - anno ".$anno);
      if($tipo_incarico == 2) $doc->SetTitle("Pubblicazioni dei capi ufficio di gabinetto, ai sensi dell'art.14, comma 1 del d.lgs. 33/2013 - anno ".$anno);
      $curRow=0;
      $rowForPage=14;
      $curPage=null;
      $curNumPage=0;
      $lastPage=sizeof($dirigenti)/$rowForPage;
      if($tipo_incarico != 2) $columns_width=array("titolare"=>"10%","incarico"=>"8%","atto"=>"10%","struttura"=>"28%","curriculum"=>"10%","art20"=>"12%","altri_incarichi"=>"10%","1-ter"=>"10%","emolumenti"=>"10%");
      if($tipo_incarico == 2) $columns_width=array("titolare"=>"8%","incarico"=>"8%","atto"=>"10%","struttura"=>"28%","curriculum"=>"10%","altri_incarichi"=>"10%","1-ter"=>"20%","emolumenti"=>"10%");
    }

    foreach($dirigenti as $email=>$curDir)
    {
      $return.='<dirigente uid="'.$curDir['id'].'">';
      if($doc)
      {
        $curPage_Row="";

        //inizia una nuova pagina (intestazione)
        if($curRow==$rowForPage) $curRow=0; 
        if($curRow==0)
        {
          $border="";
          if($curPage != null) $curPage->SetContent($curPage_row);
          $curPage=$doc->AddPage();
          $curNumPage++;
          if($curNumPage >= $lastPage) $curPage->SetCorpoStyle("border: 1px solid black; display: flex; flex-direction: column; padding:0;");
          $curPage_row="<div style='display:flex; align-items: center; justify-content: space-between; background-color: rgb(190, 190, 190); border-bottom: 1px solid black; font-weight: bold; text-align: center; padding: .3mm; min-height: 10mm'>";
          $curPage_row.="<div style='".$border." width:".$columns_width["titolare"].";'>Titolare</div>";
          $curPage_row.="<div style='".$border." width:".$columns_width["incarico"]."'>Incarico</div>";
          $curPage_row.="<div style='".$border." width:".$columns_width["struttura"].";'>Struttura</div>";
          if($tipo_incarico == 2) $curPage_row.="<div style='width:".$columns_width["curriculum"]."'>Curriculum e atto di nomina</div>";
          else
          {
            //$curPage_row.="<div style='".$border." width:".$columns_width["atto"]."'>Atto di nomina</div>";
            $curPage_row.="<div style='".$border." width:".$columns_width["curriculum"]."'>Curriculum</div>";
            $curPage_row.="<div style='".$border." width:".$columns_width["art20"]."'>Dichiarazioni ex art.20 d.lgs. 39/2013</div>";
          }
          $curPage_row.="<div style='".$border." width:".$columns_width["altri_incarichi"]."'>Altri incarichi</div>";
          $curPage_row.="<div style='".$border." width:".$columns_width["1-ter"]."'>Emolumenti complessivi a carico della finanza pubblica</div>";
          //$curPage_row.="<div style='".$border." width:".$columns_width["emolumenti"]."'>Emolumenti e importi di viaggio e missione</div>";
          $curPage_row.="</div>";

          $curRow++;
        }
        //----------------------
        if(!($curRow%2)) $bgColor="background-color: #f5f5f5;";
        else $bgColor="";        
        if($curRow != $rowForPage-1) $curPage_row.="<div style='display:flex;  align-items: center; justify-content: space-between; border-bottom: 1px solid black; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";
        else $curPage_row.="<div style='display:flex;  align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";
      }
      if($curDir['nome'] == "")
      {
        $return.="<cognome>".$curDir['id']."</cognome>";
        $return.="<nome>".$email."</nome>";
        if($verbose !="") $return.="<email>".$email."@regione.sardegna.it</email>";
        if($doc) $curPage_row.="<div style='".$border." width:".$columns_width["titolare"]."; text-align: left; text-align: left; padding-left: 1mm'>".$curDir['id']."</div>";
      }
      else
      {
        $return.="<nome>".$curDir["nome"]."</nome>";
        $return.="<cognome>".$curDir["cognome"]."</cognome>";
        if($verbose !="") $return.="<email>".$email."</email>";
        if($doc) $curPage_row.="<div style='".$border." width:".$columns_width["titolare"]."; text-align: left; padding-left: 1mm'>".$curDir['cognome']." ".$curDir['nome']."</div>";
      }
      $return.="<incarico>".$curDir["incarico"]."</incarico>";
      if($doc) $curPage_row.="<div style='".$border." width:".$columns_width["incarico"]."'>".$curDir["incarico"]."</div>";
      
      if($verbose !="")
      {
        $return.="<struttura>".$curDir["struttura"]."</struttura>";
        if($doc) $curPage_row.="<div style='".$border." width:".$columns_width["struttura"].";'>".$curDir['struttura']."</div>";
      }
      
      $urlCurriculum="https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/curriculum/?anno=".$anno."&amp;email=".$email;
      $urlArt20="https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art20_39/?anno=".$anno."&amp;email=".$email;
      $urlAltriIncarichi="https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/altri_incarichi/?anno=".$anno."&amp;email=".$email;
      $url1ter="https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/1ter/?anno=".$anno."&amp;email=".$email;
      
      //$urlAtto="https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/1ter/?anno=".$anno."&amp;email=".$email;
      //if($doc) $curPage_row.="<div style='".$border." width:".$columns_width["atto"]."'><a href='".$urlAtto."' alt='Atto di nomina'>consulta</a></div>";
  
      if($curDir["curriculum"]==1)
      {
        $return.="<curriculum stato='1'>".$urlCurriculum."</curriculum>";
        if($doc)
        {
          $curPage_row.="<div style='".$border." width:".$columns_width["curriculum"]."'><a href='".$urlCurriculum."' alt='Consulta il curriculum'>consulta</a></div>";
        }
      } 
      else
      {
        $return.="<curriculum stato='0'>Non ancora rilasciato dal titolare</curriculum>";
        if($doc) $curPage_row.="<div style='".$border." width:".$columns_width["curriculum"]."'>Non ancora rilasciato dal titolare</div>";
      } 

      if($tipo_incarico < 2)
      {
        if($curDir["art20"]==1)
        {
          $return.="<art20_39 stato='1'>".$urlArt20."</art20_39>";
          if($doc) $curPage_row.="<div style='".$border." width:".$columns_width["art20"]."'><a href='".$urlArt20."' alt='Consulta le dichiarazioni'>consulta</a></div>";
        } 
        else
        {
          $return.="<art20_39 stato='0'>Non ancora rilasciate dal titolare</art20_39>";
          if($doc) $curPage_row.="<div style='".$border." width:".$columns_width["art20"]."'>Non ancora rilasciate dal titolare</div>";
        } 
      }
      else $return.="<art20_39 stato='0'>Non soggetto all'obbligo di legge</art20_39>";  

      if($curDir["altri_incarichi"] == 1)
      {
        $return.="<altri_incarichi stato='1'>".$urlAltriIncarichi."</altri_incarichi>";
        if($doc)
        {
          $curPage_row.="<div style='".$border." width:".$columns_width["altri_incarichi"]."'><a href='".$urlAltriIncarichi."' alt='Consulta gli altri incarichi'>consulta</a></div>";
        }
      } 
      else
      {
        $return.="<altri_incarichi stato='0'>Non ancora rilasciate dal titolare</altri_incarichi>";
        if($doc) $curPage_row.="<div style='".$border." width:".$columns_width["altri_incarichi"]."'>Non ancora rilasciato dal titolare</div>";
      } 

      if($tipo_incarico <= 2)
      {
        if($curDir["1ter"]==1)
        {
          $return.="<emolumenti_complessivi stato='1'>".$url1ter."</emolumenti_complessivi>";
          if($doc) $curPage_row .="<div style='".$border." width:".$columns_width["1-ter"]."'><a href='".$url1ter."' alt='Consulta gli emolumenti complessivi'>consulta</a></div>";
        } 
        else
        {
          $return.="<emolumenti_complessivi stato='0'>Non ancora rilasciate dal titolare</emolumenti_complessivi>";
          if($doc)
          {
            $curPage_row .="<div style='".$border." width:".$columns_width["1-ter"]."'>Non ancora rilasciato dal titolare</div>";    
          } 
        } 
      }
      else
      {
        $return.="<emolumenti_complessivi stato='0'>Non soggetto all'obbligo di legge</emolumenti_complessivi>";
      } 

      if($doc)
      {
        //$urlEmolumenti="https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/1ter/?anno=".$anno."&amp;email=".$email;
        //$curPage_row .="<div style='".$border." width:".$columns_width["emolumenti"]."'><a href='".$urlEmolumenti."' alt='Consulta gli emolumenti e gli importi di viaggio e missione'>consulta</a></div>";
      }

      $return.="</dirigente>";
      if($doc)
      {
        $curPage_row.="</div>";
        $curRow++;
      }
    }
  }

  $return.="</report_pubblicazioni_dirigenti>";
  if($doc)
  {
    if($curPage != null) $curPage->SetContent($curPage_row);
    $doc->Render();
    exit;
  } 
  
  return $return;
}

//Flusso xml pubblicazioni in capo ai titolari di posizione dirigenziale (art 14 d.lgs. 33/2013 e art 20 d.lgs. 39/2013)
function AA_XML_ReportDirigenti_V2($param="")
{
 //AA_Log::Log("AA_XML_ReportDirigenti_V2(".$param->C14N().")",100,false,true);

 //Parametri
 $dal=$param->getElementsByTagName("dal")->item(0)->textContent;
 $al=$param->getElementsByTagName("al")->item(0)->textContent;
 $email=$param->getElementsByTagName("email")->item(0)->textContent;
 $verbose=$param->getElementsByTagName("verbose")->item(0)->textContent;
 $onlyRas=$param->getElementsByTagName("only_ras")->item(0)->textContent;
 $idAssessorato=$param->getElementsByTagName("id_struttura")->item(0)->textContent; //identificativo struttura, lasciare vuoto per RAS
 $idDirezione=$param->getElementsByTagName("id_direzione")->item(0)->textContent; //identificativo direzione
 $idServizio=$param->getElementsByTagName("id_servizio")->item(0)->textContent; //identificativo servizio
 $id_user=$param->getElementsByTagName("id_user")->item(0)->textContent; //identificativo utente
 $tipo_incarico=$param->getElementsByTagName("tipo_incarico")->item(0)->textContent; //tipologia incarico
 $output=$param->getElementsByTagName("output")->item(0)->textContent; //tipologia output ("pdf","csv","xml");
 if($output == "") $output="xml";
 
 if($al == "")
 {
   
   if($dal != "" && $dal >= "2021-01-01")
   {
     $var=explode("-",$dal);
     $al=($var[0]+3)."-12-31";
   }
   else $al=date("Y")."-12-31";
 }

 //Niente dati prima del 2021
 if($dal < "2021-01-01")
 {
   $var=explode("-",$al);
   $dal=$var[0]-3;
   if($dal < "2021-01-01") $dal="2021-01-01";
 }

  //Recupera gli identificativi dei titolari
  $params['only_ras']=$onlyRas;
  $params['idAssessoratoIncarico']=$idAssessorato;
  $params['idDirezioneIncarico']=$idDirezione;
  $params['idServizioIncarico']=$idServizio;
  $params['tipoIncarico']=$tipo_incarico;
  $params['dal']=$dal;
  $params['al']=$al;
  $params['count']="all";

  $xml=new AA_XML_FEED_ART14_DIRIGENTI();
  $xml->SetURL($_SERVER['SCRIPT_NAME']);
  $xml->SetParams($params);
  $return="<dirigenti>";

  $dirigenti=AA_IncarichiOps::SearchTitolari($params);
  $count = $dirigenti[0];
  if($count > 0)
  {
    $doc=null;
    if($output=="pdf")
    {
      if($struttura !="") $id="_".$struttura;
      if($id_struttura !="") $id="_".$id_struttura;
      $doc = new AA_PDF_RAS_TEMPLATE_A4_LANDSCAPE("pubblicazioni_art14_dirigenti_".$dal."_".$al);
      if($idAssessorato == 27)
      {
        $doc = new AA_PDF_ASPAL_TEMPLATE_A4_LANDSCAPE("pubblicazioni_art14_ASPAL_".$dal."_".$al);   
      }
      $doc->SetDocumentStyle("font-family: sans-serif; font-size: 3mm;");
      $doc->SetPageCorpoStyle("display: flex; flex-direction: column; justify-content: space-between; padding:0;");
      if(($tipo_incarico & AA_Incarichi_Const::AA_INCARICO_DIRIGENZIALE)> 0) $doc->SetTitle("Pubblicazioni dei titolari di incarichi dirigenziali ai sensi dell'art.14, comma 1-bis e 1-ter del d.lgs. 33/2013 e art. 20 d.lgs.39/2013");
      if(($tipo_incarico & AA_Incarichi_Const::AA_INCARICO_CAPO_UFFICIO_GABINETTO) > 0) $doc->SetTitle("Pubblicazioni dei titolari di incarichi politici ai sensi dell'art.14, comma 1 del d.lgs. 33/2013");
      
      $curRow=0;
      $rowForPage=2;
      $lastRow=$rowForPage-1;
      $curPage=null;
      $curNumPage=0;
      //$columns_width=array("titolare"=>"10%","incarico"=>"8%","atto"=>"10%","struttura"=>"28%","curriculum"=>"10%","art20"=>"12%","altri_incarichi"=>"10%","1-ter"=>"10%","emolumenti"=>"10%");
      if(($tipo_incarico & AA_Incarichi_Const::AA_INCARICO_DIRIGENZIALE)> 0) $columns_width=array("dal"=>"10%","al"=>"10%","inconf"=>"10%","incomp"=>"10%","anno"=>"25%","titolare"=>"50%","tipo_incarico"=>"10%","atto_nomina"=>"10%","struttura"=>"40%","curriculum"=>"25%","altri_incarichi"=>"25%","1-ter"=>"25%","emolumenti"=>"10%");
      if(($tipo_incarico & AA_Incarichi_Const::AA_INCARICO_CAPO_UFFICIO_GABINETTO) > 0) $columns_width=array("dal"=>"10%","al"=>"10%","inconf"=>"10%","incomp"=>"10%","anno"=>"25%","titolare"=>"50%","tipo_incarico"=>"25%","atto_nomina"=>"20%","struttura"=>"40%","curriculum"=>"25%","altri_incarichi"=>"25%","1-ter"=>"25%","emolumenti"=>"10%");
      $rowContentWidth="width: 99.8%;";

      //pagine indice (30 nominativi per pagina)
      $indiceNumVociPerPagina=30;
      for($i=0; $i<$count/$indiceNumVociPerPagina; $i++)
      {
        $curPage=$doc->AddPage();
        $curPage->SetCorpoStyle("display: flex; flex-direction: column; padding:0;");
        $curNumPage++;
      }
    
      $indice=array();
      $lastPage=$count/$rowForPage+$curNumPage;
    }
    $return.="<count>".$count."</count>";

    foreach($dirigenti[1] as $id=>$curDirigente)
    {
      //id dirigente
      $id_dirigente=$id;
      $return.='<dirigente uid="'.$id.'">';
      if($doc)
      {
        //inizia una nuova pagina (intestazione)
        if($curRow==$rowForPage) $curRow=0; 
        if($curRow==0)
        {
          $border="";
          if($curPage != null) $curPage->SetContent($curPage_row);
          $curPage=$doc->AddPage();
          $curNumPage++;
          if($curNumPage >= $lastPage) $curPage->SetCorpoStyle("display: flex; flex-direction: column; justify-content: space-between; padding:0;");
          $curPage_row="";
          //$curPage_row="<div style='display:flex; align-items: center; justify-content: space-between; background-color: rgb(190, 190, 190); border-bottom: 1px solid black; font-weight: bold; text-align: center; padding: .3mm; min-height: 10mm'>";
          //$curPage_row.="<div style='".$border." width:".$columns_width["titolare"].";'>Titolare</div>";
          //$curPage_row.="<div style='".$border." width:".$columns_width["curriculum"]."'>Curriculum</div>";
          //$curPage_row.="<div style='".$border." width:".$columns_width["emolumenti"]."'>Emolumenti e importi di viaggio e missione</div>";
          //$curPage_row.="<div style='".$border." width:".$columns_width["altri_incarichi"]."'>Altri incarichi</div>";
          //$curPage_row.="<div style='".$border." width:".$columns_width["1-ter"]."'>Emolumenti complessivi a carico della finanza pubblica</div>";
          //$curPage_row.="</div>";

          //$curRow++;
        }
        //----------------------
        //if(!($curRow%2)) $bgColor="background-color: #f5f5f5;";
        //else $bgColor="";        
        //if($curRow == 0) $curPage_row.="<div style='display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; border: 1px solid black; text-align: center; padding: 0mm; min-height: 9mm;'>";
        //if($curRow > 0 && $curRow != $lastRow) $curPage_row.="<div style='display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; border-bottom: 1px solid black; border-top: 1px solid black; text-align: center; padding: 0mm; min-height: 9mm;'>";
        //if($curRow == $lastRow) $curPage_row.="<div style='display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; border-top: 1px solid black; text-align: center; padding: 0mm; min-height: 9mm;'>";
        $indice[$curDirigente->GetEmail()]=$curNumPage."|".$curDirigente->GetCognome()." ".$curDirigente->GetNome();
        $curPage_row.="<div id='".$curDirigente->GetEmail()."' style='display:flex;  flex-direction: column; width:99.8%; align-items: center; justify-content: space-between; border: 1px solid black; text-align: center; padding: 0mm; min-height: 9mm;'>";
      }
      
      //dati generali
      $return.="<nome>".$curDirigente->GetNome()."</nome>";
      $return.="<cognome>".$curDirigente->GetCognome()."</cognome>";
      if($verbose !="") $return.="<email>".$curDirigente->GetEmail()."</email>";
      if($doc)
      {
        //Nome e cognome
        //$curPage_row.="<div style='display:flex; flex-direction: column; width:100%; border-bottom: 1px solid black; padding: .3mm; min-height: 10mm'>";
        $curPage_row.="<div style='".$border." background-color: rgb(190, 190, 190); width: 100%; padding-bottom: 1mm; text-align: center;'><span style='font-weight: bold'>".$curDirigente->GetCognome()." ".$curDirigente->GetNome()."</span><br/><a href='mailto:".$curDirigente->GetEmail()."'>".$curDirigente->GetEmail()."</a></div>";
      }

      //pubblicazioni
      $from=explode("-",$dal);
      $to=explode("-",$al);
      $pubblicazioni=$curDirigente->GetPubblicazioni($from[0],$to[0]);

      $return.="<pubblicazioni>";
      if($doc)
      {
        $curPage_row.="<div style='".$rowContentWidth." display:flex; align-items: center; justify-content: space-between; background-color: rgb(215, 215, 215); border-top: 1px solid gray; font-weight: bold; padding: .3mm; min-height: 5mm'><div style='width: 100%; text-align: center;'>Pubblicazioni ai sensi dell'art.14, d.lgs. 33/2013</div></div>";
        $curPage_row.="<div style='display:flex;".$rowContentWidth." align-items: center; justify-content: space-between; background-color: rgb(215, 215, 215); border-bottom: 1px solid gray; font-weight: bold; text-align: center; padding: .3mm; min-height: 5mm'>";
        $curPage_row.="<div style='".$border."text-align: center; width:".$columns_width["anno"].";'>Anno</div>";
        $curPage_row.="<div style='".$border." text-align: center; width:".$columns_width["curriculum"]."'>Curriculum</div>";
        //$curPage_row.="<div style='".$border." width:".$columns_width["emolumenti"]."'>Emolumenti e importi di viaggio e missione</div>";
        $curPage_row.="<div style='".$border." text-align: center; width:".$columns_width["altri_incarichi"]."'>Altri incarichi</div>";
        if(($tipo_incarico & AA_Incarichi_Const::AA_INCARICO_DIRIGENZIALE)> 0) $curPage_row.="<div style='".$border." text-align: center; width:".$columns_width["1-ter"]."'>Emolumenti complessivi</div>";
        $curPage_row.="</div>";
      }

      $curInnerRow=0;
      $pubCountAll=sizeof($pubblicazioni)-1;
      $pubCount=0;
      foreach($pubblicazioni as $curPubblicazione)
      {
        $tipoIncarichi=$curDirigente->GetTipoIncarichi($curPubblicazione->GetAnno());
        if($tipoIncarichi > 0)
        {
            $return.="<pubblicazione anno='".$curPubblicazione->GetAnno()."'><curriculum>".$curPubblicazione->GetCurriculum()."</curriculum><altri_incarichi>".$curPubblicazione->GetAltriIncarichi()."</altri_incarichi><emolumenti_complessivi>".$curPubblicazione->GetEmolumentiComplessivi()."</emolumenti_complessivi></pubblicazione>";
            if($doc)
            {
              if(!($curInnerRow%2)) $bgColor="";
              else $bgColor="background-color: #f5f5f5;";

              //------------pubblicazione box------------------
              if($pubCount < $pubCountAll) $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; border-bottom: 1px solid gray; text-align: center; padding: .3mm; min-height: 5mm;".$bgColor."'>";
              else $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 5mm;".$bgColor."'>";

              //anno
              $curPage_row.="<div style='".$border." width:".$columns_width["anno"]."; text-align: center;'>".$curPubblicazione->GetAnno()."</div>";

              //curriculum
              $curriculum=$curPubblicazione->GetCurriculum();
              if($curriculum !="") $curriculum="<a href='".$curriculum."' target='_blank'>consulta</a>";
              else $curriculum="non ancora rilasciato dal titolare";
              $curPage_row.="<div style='".$border." width:".$columns_width["curriculum"]."; text-align: center;'>".$curriculum."</div>";

              //Altri incarichi
              $altriIncarichi=$curPubblicazione->GetAltriIncarichi();
              if($altriIncarichi !="") $altriIncarichi="<a href='".$altriIncarichi."' target='_blank'>consulta</a>";
              else $altriIncarichi="non ancora rilasciato/a dal titolare";
              $curPage_row.="<div style='".$border." width:".$columns_width["altri_incarichi"]."; text-align: center;'>".$altriIncarichi."</div>";

              if(($tipo_incarico & AA_Incarichi_Const::AA_INCARICO_DIRIGENZIALE)> 0)
              {
                  //emolumenti complessivi
                  $emolumentiComplessivi=$curPubblicazione->GetEmolumentiComplessivi();
                  if($emolumentiComplessivi !="") $emolumentiComplessivi="<a href='".$emolumentiComplessivi."' target='_blank'>consulta</a>";
                  else $emolumentiComplessivi="non ancora rilasciato dal titolare";
                  $curPage_row.="<div style='".$border." width:".$columns_width["1-ter"]."; text-align: center;'>".$emolumentiComplessivi."</div>";
              }

              //----------------pubblicazione box-----------------
              $curPage_row.="</div>";
              $curInnerRow++;
              $pubCount++;
            }
        }  
      }
      $return.="</pubblicazioni>";       
    
      //incarichi
      if($onlyRas || $idAssessorato == "" || $idAssessorato == 0) $incarichi=$curDirigente->GetIncarichi("",$tipo_incarico,$dal,$al,$idAssessorato,$idDirezione,$idServizio,0);
      else
      {
        $struct=AA_Struct::GetStruct($idAssessorato,$idDirezione,$idServizio);
        $incarichi=$curDirigente->GetIncarichi("",$tipo_incarico,$dal,$al,$idAssessorato,$idDirezione,$idServizio,$struct->GetTipo());
      }

      $return.="<incarichi>";
      if($doc)
      {
        $curPage_row.="<div style='".$rowContentWidth."text-align: center; background-color: rgb(215, 215, 215); border-top: 1px solid gray; font-weight: bold; padding: .3mm; min-height: 5mm'>Incarichi</div>";
        $curPage_row.="<div style='".$rowContentWidth."display:flex; align-items: center; justify-content: space-between; background-color: rgb(215, 215, 215); border-bottom: 1px solid gray; font-weight: bold; text-align: center; padding: .3mm; min-height: 5mm'>";
        $curPage_row.="<div style='".$border." width:".$columns_width["tipo_incarico"]."; text-align:center;'>Tipo incarico</div>";
        $curPage_row.="<div style='".$border." width:".$columns_width["struttura"].";  text-align:center;'>Struttura</div>";
        $curPage_row.="<div style='".$border." width:".$columns_width["dal"]."; text-align:center;'>Data inizio</div>";
        $curPage_row.="<div style='".$border." width:".$columns_width["al"]."; text-align:center;'>Data fine</div>";
        $curPage_row.="<div style='".$border." width:".$columns_width["atto_nomina"].";  text-align:center;'>Atto di nomina</div>";
        
        if(($tipo_incarico & AA_Incarichi_Const::AA_INCARICO_DIRIGENZIALE)> 0)
        {
            $curPage_row.="<div style='".$border." width:".$columns_width["inconf"]."; text-align:center;'>Dich. assenza di cause di inconferibilità</div>";
            $curPage_row.="<div style='".$border." width:".$columns_width["incomp"]."; text-align:center;'>Dich. assenza di cause di incompatibilità</div>";            
        }
        $curPage_row.="</div>";
      }
      
      $curInnerRow=0;
      $incCountAll=sizeof($incarichi)-1;
      $incCount=0;
      foreach($incarichi as $curIncarico)
      {
        $return.="<incarico><tipo_incarico id='".$curIncarico->GetTipo(true)."' dal='".$curIncarico->GetDataInizio()."' al='".$curIncarico->GetDataFine()."'>".$curIncarico->GetTipo()."</tipo_incarico>".$curIncarico->GetStruttura(false)->toXML()."<atto_nomina>".$curIncarico->GetAttonomina()."</atto_nomina><dich_assenza_inconf>".$curIncarico->GetDichInsussInconf()."</dich_assenza_inconf><dich_assenza_incomp>".$curIncarico->GetDichInsussIncomp()."</dich_assenza_incomp></incarico>";
        
        if($doc)
        {
          if(!($curInnerRow%2)) $bgColor="";
          else $bgColor="background-color: #f5f5f5;";
          //------------incarichi box------------------
          if($incCount==$incCountAll) $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";
          else $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; border-bottom: 1px solid black; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";

          //Tipo incarico
          $curPage_row.="<div style='".$border." width:".$columns_width["tipo_incarico"]."; text-align: center;'>".$curIncarico->GetTipo()."</div>";

          //Struttura
          $curPage_row.="<div style='".$border." width:".$columns_width["struttura"]."; text-align: center;'>".$curIncarico->GetStruttura()."</div>";

          //data inizio
          $curPage_row.="<div style='".$border." width:".$columns_width["dal"]."; text-align: center;'>".$curIncarico->GetDataInizio()."</div>";

          //Data fine
          $curPage_row.="<div style='".$border." width:".$columns_width["al"]."; text-align: center;'>".$curIncarico->GetDataFine()."</div>";

          //atto di nomina
          $document=$curIncarico->GetAttoNomina();
          if($document !="") $document="<a href='".$document."' target='_blank'>consulta</a>";
          else $document="non ancora rilasciato dal titolare";
          $curPage_row.="<div style='".$border." width:".$columns_width["atto_nomina"]."; text-align: center;'>".$document."</div>";
          
          if(($tipo_incarico & AA_Incarichi_Const::AA_INCARICO_DIRIGENZIALE) > 0)
          {
            //dichiarazione inconferibilità
            $document=$curIncarico->GetDichInsussInconf();
            if($document !="") $document="<a href='".$document."' target='_blank'>consulta</a>";
            else $document="non ancora rilasciato dal titolare";
            if(($curIncarico->GetTipo(true) & AA_Incarichi_Const::AA_INCARICO_DIRIGENZIALE) == 0)
            $document="dichiarazione non dovuta";

            $curPage_row.="<div style='".$border." width:".$columns_width["inconf"]."; text-align: center;'>".$document."</div>";

            //dichiarazione incompatibilità
            $document=$curIncarico->GetDownloadDicIncompUrl();
            if($document !="") $document="<a href='".$document."' target='_blank'>consulta</a>";
            else $document="non ancora rilasciato dal titolare";
            if(($curIncarico->GetTipo(true) & AA_Incarichi_Const::AA_INCARICO_DIRIGENZIALE) == 0)
            $document="dichiarazione non dovuta";

            $curPage_row.="<div style='".$border." width:".$columns_width["incomp"]."; text-align:center'>".$document."</div>";              
          }

          //------------incarichi box------------------
          $curPage_row.="</div>";
          $curInnerRow++;
          $incCount++;
        }
      }
      //fine incarichi
      $return.="</incarichi>";

      $return.="</dirigente>";
      if($doc)
      {
        $curPage_row.="</div>";
        $curRow++;
      }
    }
  }

  $return.="</dirigenti>";
  if($doc)
  {
    if($curPage != null) $curPage->SetContent($curPage_row);

    //Aggiornamento indice
    $curNumPage=0;
    $curPage=$doc->GetPage($curNumPage);
    $vociCount=0;
    $curRow=0;
    $bgColor="";
    $curPage_row="";

    foreach($indice as $email=>$data)
    {
      if($curNumPage != (int)($vociCount/$indiceNumVociPerPagina))
      {
        $curPage->SetContent($curPage_row);
        $curNumPage=(int)($vociCount/$indiceNumVociPerPagina);
        $curPage=$doc->GetPage($curNumPage);
        $curPage_row="";
        $curRow=0;
        $bgColor="";
      }

      if($curPage instanceof AA_PDF_Page)
      {
        if($vociCount%2 > 0)
        {
          $dati=explode("|",$data);
          $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#".$email."'>".$dati['1']."</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#".$email."'>pag. ".$dati[0]."</a></div>";
          $curPage_row.="</div>";
          if($vociCount == (sizeof($indice)-1)) $curPage->SetContent($curPage_row);
          $curRow++;
        }
        else
        {
          if($curRow%2) $bgColor="background-color: #f5f5f5;";
          else $bgColor="";
          $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";
          $dati=explode("|",$data);
          $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#".$email."'>".$dati['1']."</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#".$email."'>pag. ".$dati[0]."</a></div>";
          
          //ultima voce
          if($vociCount == (sizeof($indice)-1))
          {
            $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'>&nbsp; </div><div style='width:9%;text-align: right;padding-left: 10mm'>&nbsp; </div></div>";
            $curPage->SetContent($curPage_row);
          } 
        }
      }

      $vociCount++;
    }

    //render del documento
    $doc->Render();
    exit;
  }
  
  $xml->SetContent($return);

  return $xml->toXML();
}

//Flusso xml pubblicazioni in capo ai titolari di posizione dirigenziale inadempienti (art 14 d.lgs. 33/2013 e art 20 d.lgs. 39/2013)
function AA_XML_ReportDirigenti_Inadempienti($param="")
{
 //Parametri
 $dal=$param->getElementsByTagName("dal")->item(0)->textContent;
 $al=$param->getElementsByTagName("al")->item(0)->textContent;
 $email=$param->getElementsByTagName("email")->item(0)->textContent;
 $verbose=$param->getElementsByTagName("verbose")->item(0)->textContent;
 $onlyRas=$param->getElementsByTagName("only_ras")->item(0)->textContent;
 $idAssessorato=$param->getElementsByTagName("id_struttura")->item(0)->textContent; //identificativo struttura, lasciare vuoto per RAS
 $idDirezione=$param->getElementsByTagName("id_direzione")->item(0)->textContent; //identificativo direzione
 $idServizio=$param->getElementsByTagName("id_servizio")->item(0)->textContent; //identificativo servizio
 $id_user=$param->getElementsByTagName("id_user")->item(0)->textContent; //identificativo utente
 $tipo_incarico=$param->getElementsByTagName("tipo_incarico")->item(0)->textContent; //tipologia incarico
 $output=$param->getElementsByTagName("output")->item(0)->textContent; //tipologia output ("pdf","csv","xml");
 if($output == "") $output="xml";
 
 if($al == "")
 {
   
   if($dal != "" && $dal >= "2021-01-01")
   {
     $var=explode("-",$dal);
     $al=($var[0]+3)."-12-31";
   }
   else $al=date("Y")."-12-31";
 }

 //Niente dati prima del 2021
 if($dal < "2021-01-01")
 {
   $var=explode("-",$al);
   $dal=$var[0]-3;
   if($dal < "2021-01-01") $dal="2021-01-01";
 }

  //Recupera gli identificativi dei titolari
  $params['only_ras']=$onlyRas;
  $params['idAssessoratoIncarico']=$idAssessorato;
  $params['idDirezioneIncarico']=$idDirezione;
  $params['idServizioIncarico']=$idServizio;
  $params['tipoIncarico']=$tipo_incarico;
  $params['dal']=$dal;
  $params['al']=$al;
  $params['count']="all";

  $xml=new AA_XML_FEED_ART14_DIRIGENTI();
  $xml->SetURL($_SERVER['SCRIPT_NAME']);
  $xml->SetParams($params);
  $return="<dirigenti>";
  $csv="nome;cognome;email;curriculum;altri_incarichi;emolumenti;incarico_1_tipo;incarico_1_struttura;incarico_1_dal;incarico_1_al;incarico_1_atto;incarico_1_inconf;incarico_1_incomp;incarico_2_tipo;incarico_2_struttura;incarico_2_dal;incarico_2_al;incarico_2_atto;incarico_2_inconf;incarico_2_incomp";

  $dirigenti=AA_IncarichiOps::SearchTitolari($params);
  $count = $dirigenti[0];
  if($count > 0)
  {
    $doc=null;
    if($output=="pdf")
    {
      if($struttura !="") $id="_".$struttura;
      if($id_struttura !="") $id="_".$id_struttura;
      $doc = new AA_PDF_RAS_TEMPLATE_A4_LANDSCAPE("pubblicazioni_art14_dirigenti_".$dal."_".$al);
      if($idAssessorato == 27)
      {
        $doc = new AA_PDF_ASPAL_TEMPLATE_A4_LANDSCAPE("pubblicazioni_art14_ASPAL_".$dal."_".$al);   
      }
      $doc->SetDocumentStyle("font-family: sans-serif; font-size: 3mm;");
      $doc->SetPageCorpoStyle("display: flex; flex-direction: column; justify-content: space-between; padding:0;");
      $doc->SetTitle("Pubblicazioni dei titolari di incarichi dirigenziali ai sensi dell'art.14, comma 1-bis e 1-ter del d.lgs. 33/2013 e art. 20 d.lgs.39/2013");
      $curRow=0;
      $rowForPage=2;
      $lastRow=$rowForPage-1;
      $curPage=null;
      $curNumPage=0;
      //$columns_width=array("titolare"=>"10%","incarico"=>"8%","atto"=>"10%","struttura"=>"28%","curriculum"=>"10%","art20"=>"12%","altri_incarichi"=>"10%","1-ter"=>"10%","emolumenti"=>"10%");
      $columns_width=array("dal"=>"10%","al"=>"10%","inconf"=>"10%","incomp"=>"10%","anno"=>"25%","titolare"=>"50%","tipo_incarico"=>"10%","atto_nomina"=>"10%","struttura"=>"40%","curriculum"=>"25%","altri_incarichi"=>"25%","1-ter"=>"25%","emolumenti"=>"10%");
      $rowContentWidth="width: 99.8%;";

      //pagine indice (30 nominativi per pagina)
      $indiceNumVociPerPagina=30;
      for($i=0; $i<$count/$indiceNumVociPerPagina; $i++)
      {
        $curPage=$doc->AddPage();
        $curPage->SetCorpoStyle("display: flex; flex-direction: column; padding:0;");
        $curNumPage++;
      }
    
      $indice=array();
      $lastPage=$count/$rowForPage+$curNumPage;
    }
    
    $inadempienti=0;
    
    foreach($dirigenti[1] as $id=>$curDirigente)
    {
      $from=explode("-",$dal);
      $to=explode("-",$al);
      if($curDirigente->IsInadempiente($from[0],$to[0],$tipo_incarico,$idAssessorato,$idDirezione,$idServizio,0))
      {
        $inadempienti++;
        //id dirigente
        $id_dirigente=$id;
        $return.='<dirigente uid="'.$id.'">';
        $csv.="\r\n";
        if($doc)
        {
          //inizia una nuova pagina (intestazione)
          if($curRow==$rowForPage) $curRow=0; 
          if($curRow==0)
          {
            $border="";
            if($curPage != null) $curPage->SetContent($curPage_row);
            $curPage=$doc->AddPage();
            $curNumPage++;
            if($curNumPage >= $lastPage) $curPage->SetCorpoStyle("display: flex; flex-direction: column; justify-content: space-between; padding:0;");
            $curPage_row="";
            //$curPage_row="<div style='display:flex; align-items: center; justify-content: space-between; background-color: rgb(190, 190, 190); border-bottom: 1px solid black; font-weight: bold; text-align: center; padding: .3mm; min-height: 10mm'>";
            //$curPage_row.="<div style='".$border." width:".$columns_width["titolare"].";'>Titolare</div>";
            //$curPage_row.="<div style='".$border." width:".$columns_width["curriculum"]."'>Curriculum</div>";
            //$curPage_row.="<div style='".$border." width:".$columns_width["emolumenti"]."'>Emolumenti e importi di viaggio e missione</div>";
            //$curPage_row.="<div style='".$border." width:".$columns_width["altri_incarichi"]."'>Altri incarichi</div>";
            //$curPage_row.="<div style='".$border." width:".$columns_width["1-ter"]."'>Emolumenti complessivi a carico della finanza pubblica</div>";
            //$curPage_row.="</div>";

            //$curRow++;
          }
          //----------------------
          //if(!($curRow%2)) $bgColor="background-color: #f5f5f5;";
          //else $bgColor="";        
          //if($curRow == 0) $curPage_row.="<div style='display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; border: 1px solid black; text-align: center; padding: 0mm; min-height: 9mm;'>";
          //if($curRow > 0 && $curRow != $lastRow) $curPage_row.="<div style='display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; border-bottom: 1px solid black; border-top: 1px solid black; text-align: center; padding: 0mm; min-height: 9mm;'>";
          //if($curRow == $lastRow) $curPage_row.="<div style='display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; border-top: 1px solid black; text-align: center; padding: 0mm; min-height: 9mm;'>";
          $indice[$curDirigente->GetEmail()]=$curNumPage."|".$curDirigente->GetCognome()." ".$curDirigente->GetNome();
          $curPage_row.="<div id='".$curDirigente->GetEmail()."' style='display:flex;  flex-direction: column; width:99.8%; align-items: center; justify-content: space-between; border: 1px solid black; text-align: center; padding: 0mm; min-height: 9mm;'>";
        }

        //dati generali
        $return.="<nome>".$curDirigente->GetNome()."</nome>";
        $return.="<cognome>".$curDirigente->GetCognome()."</cognome>";
        $csv.=$curDirigente->GetNome().";";
        $csv.=$curDirigente->GetCognome().";";
        $csv.=$curDirigente->GetEmail().";";
        if($verbose !="") $return.="<email>".$curDirigente->GetEmail()."</email>";
        if($doc)
        {
          //Nome e cognome
          //$curPage_row.="<div style='display:flex; flex-direction: column; width:100%; border-bottom: 1px solid black; padding: .3mm; min-height: 10mm'>";
          $curPage_row.="<div style='".$border." background-color: rgb(190, 190, 190); width: 100%; padding-bottom: 1mm; text-align: center;'><span style='font-weight: bold'>".$curDirigente->GetCognome()." ".$curDirigente->GetNome()."</span><br/><a href='mailto:".$curDirigente->GetEmail()."'>".$curDirigente->GetEmail()."</a></div>";
        }

        //pubblicazioni
        $pubblicazioni=$curDirigente->GetPubblicazioni($from[0],$to[0]);

        $return.="<pubblicazioni>";
        if($doc)
        {
          $curPage_row.="<div style='".$rowContentWidth." display:flex; align-items: center; justify-content: space-between; background-color: rgb(215, 215, 215); border-top: 1px solid gray; font-weight: bold; padding: .3mm; min-height: 5mm'><div style='width: 100%; text-align: center;'>Pubblicazioni ai sensi dell'art.14, d.lgs. 33/2013</div></div>";
          $curPage_row.="<div style='display:flex;".$rowContentWidth." align-items: center; justify-content: space-between; background-color: rgb(215, 215, 215); border-bottom: 1px solid gray; font-weight: bold; text-align: center; padding: .3mm; min-height: 5mm'>";
          $curPage_row.="<div style='".$border."text-align: center; width:".$columns_width["anno"].";'>Anno</div>";
          $curPage_row.="<div style='".$border." text-align: center; width:".$columns_width["curriculum"]."'>Curriculum</div>";
          //$curPage_row.="<div style='".$border." width:".$columns_width["emolumenti"]."'>Emolumenti e importi di viaggio e missione</div>";
          $curPage_row.="<div style='".$border." text-align: center; width:".$columns_width["altri_incarichi"]."'>Altri incarichi</div>";
          $curPage_row.="<div style='".$border." text-align: center; width:".$columns_width["1-ter"]."'>Emolumenti complessivi</div>";
          $curPage_row.="</div>";
        }

        $curInnerRow=0;
        $pubCountAll=sizeof($pubblicazioni)-1;
        $pubCount=0;
        foreach($pubblicazioni as $curPubblicazione)
        {
          $return.="<pubblicazione anno='".$curPubblicazione->GetAnno()."'><curriculum>".$curPubblicazione->GetCurriculum()."</curriculum><altri_incarichi>".$curPubblicazione->GetAltriIncarichi()."</altri_incarichi><emolumenti_complessivi>".$curPubblicazione->GetEmolumentiComplessivi()."</emolumenti_complessivi></pubblicazione>";
          $csv.=$curPubblicazione->GetCurriculum().";".$curPubblicazione->GetAltriIncarichi().";".$curPubblicazione->GetEmolumentiComplessivi().";";
          if($doc)
          {
            if(!($curInnerRow%2)) $bgColor="";
            else $bgColor="background-color: #f5f5f5;";

            //------------pubblicazione box------------------
            if($pubCount < $pubCountAll) $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; border-bottom: 1px solid gray; text-align: center; padding: .3mm; min-height: 5mm;".$bgColor."'>";
            else $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 5mm;".$bgColor."'>";

            //anno
            $curPage_row.="<div style='".$border." width:".$columns_width["anno"]."; text-align: center;'>".$curPubblicazione->GetAnno()."</div>";

            //curriculum
            $curriculum=$curPubblicazione->GetCurriculum();
            if($curriculum !="") $curriculum="<a href='".$curriculum."' target='_blank'>consulta</a>";
            else $curriculum="non ancora rilasciato dal titolare";
            $curPage_row.="<div style='".$border." width:".$columns_width["curriculum"]."; text-align: center;'>".$curriculum."</div>";

            //Altri incarichi
            $altriIncarichi=$curPubblicazione->GetAltriIncarichi();
            if($altriIncarichi !="") $altriIncarichi="<a href='".$altriIncarichi."' target='_blank'>consulta</a>";
            else $altriIncarichi="non ancora rilasciato/a dal titolare";
            $curPage_row.="<div style='".$border." width:".$columns_width["altri_incarichi"]."; text-align: center;'>".$altriIncarichi."</div>";

            //emolumenti complessivi
            $emolumentiComplessivi=$curPubblicazione->GetEmolumentiComplessivi();
            if($emolumentiComplessivi !="") $emolumentiComplessivi="<a href='".$emolumentiComplessivi."' target='_blank'>consulta</a>";
            else $emolumentiComplessivi="non ancora rilasciato dal titolare";
            $curPage_row.="<div style='".$border." width:".$columns_width["1-ter"]."; text-align: center;'>".$emolumentiComplessivi."</div>";

            //----------------pubblicazione box-----------------
            $curPage_row.="</div>";
            $curInnerRow++;
            $pubCount++;
          }
        }
        $return.="</pubblicazioni>";       

         //incarichi
        if($onlyRas || $idAssessorato == "" || $idAssessorato == 0) $incarichi=$curDirigente->GetIncarichi("",$tipo_incarico,$dal,$al,$idAssessorato,$idDirezione,$idServizio,0);
        else
        {
          $struct=AA_Struct::GetStruct($idAssessorato,$idDirezione,$idServizio);
          $incarichi=$curDirigente->GetIncarichi("",$tipo_incarico,$dal,$al,$idAssessorato,$idDirezione,$idServizio,$struct->GetTipo());
        }

        $return.="<incarichi>";
        if($doc)
        {
          $curPage_row.="<div style='".$rowContentWidth."text-align: center; background-color: rgb(215, 215, 215); border-top: 1px solid gray; font-weight: bold; padding: .3mm; min-height: 5mm'>Incarichi</div>";
          $curPage_row.="<div style='".$rowContentWidth."display:flex; align-items: center; justify-content: space-between; background-color: rgb(215, 215, 215); border-bottom: 1px solid gray; font-weight: bold; text-align: center; padding: .3mm; min-height: 5mm'>";
          $curPage_row.="<div style='".$border." width:".$columns_width["tipo_incarico"]."; text-align:center;'>Tipo incarico</div>";
          $curPage_row.="<div style='".$border." width:".$columns_width["struttura"].";  text-align:center;'>Struttura</div>";
          $curPage_row.="<div style='".$border." width:".$columns_width["dal"]."; text-align:center;'>Data inizio</div>";
          $curPage_row.="<div style='".$border." width:".$columns_width["al"]."; text-align:center;'>Data fine</div>";
          $curPage_row.="<div style='".$border." width:".$columns_width["atto_nomina"].";  text-align:center;'>Atto di nomina</div>";
          $curPage_row.="<div style='".$border." width:".$columns_width["inconf"]."; text-align:center;'>Dich. assenza di cause di inconferibilità</div>";
          $curPage_row.="<div style='".$border." width:".$columns_width["incomp"]."; text-align:center;'>Dich. assenza di cause di incompatibilità</div>";
          $curPage_row.="</div>";
        }

        $curInnerRow=0;
        $incCountAll=sizeof($incarichi)-1;
        $incCount=0;
        foreach($incarichi as $curIncarico)
        {
          $return.="<incarico><tipo_incarico id='".$curIncarico->GetTipo(true)."' dal='".$curIncarico->GetDataInizio()."' al='".$curIncarico->GetDataFine()."'>".$curIncarico->GetTipo()."</tipo_incarico>".$curIncarico->GetStruttura(false)->toXML()."<atto_nomina>".$curIncarico->GetAttonomina()."</atto_nomina><dich_assenza_inconf>".$curIncarico->GetDichInsussInconf()."</dich_assenza_inconf><dich_assenza_incomp>".$curIncarico->GetDichInsussIncomp()."</dich_assenza_incomp></incarico>";
          if($incCount<2) $csv.=$curIncarico->GetTipo(true).";". str_replace(";",",",$curIncarico->GetStruttura()).";".$curIncarico->GetDataInizio().";".$curIncarico->GetDataFine().";".$curIncarico->GetAttoNomina().";".$curIncarico->GetDichInsussInconf().";".$curIncarico->GetDownloadDicIncompUrl().";";
          if($doc)
          {
            if(!($curInnerRow%2)) $bgColor="";
            else $bgColor="background-color: #f5f5f5;";
            //------------incarichi box------------------
            if($incCount==$incCountAll) $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";
            else $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; border-bottom: 1px solid black; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";

            //Tipo incarico
            $curPage_row.="<div style='".$border." width:".$columns_width["tipo_incarico"]."; text-align: center;'>".$curIncarico->GetTipo()."</div>";

            //Struttura
            $curPage_row.="<div style='".$border." width:".$columns_width["struttura"]."; text-align: center;'>".$curIncarico->GetStruttura()."</div>";

            //data inizio
            $curPage_row.="<div style='".$border." width:".$columns_width["dal"]."; text-align: center;'>".$curIncarico->GetDataInizio()."</div>";

            //Data fine
            $curPage_row.="<div style='".$border." width:".$columns_width["al"]."; text-align: center;'>".$curIncarico->GetDataFine()."</div>";

            //atto di nomina
            $document=$curIncarico->GetAttoNomina();
            if($document !="") $document="<a href='".$document."' target='_blank'>consulta</a>";
            else $document="non ancora rilasciato dal titolare";
            $curPage_row.="<div style='".$border." width:".$columns_width["atto_nomina"]."; text-align: center;'>".$document."</div>";

            //dichiarazione inconferibilità
            $document=$curIncarico->GetDichInsussInconf();
            if($document !="") $document="<a href='".$document."' target='_blank'>consulta</a>";
            else $document="non ancora rilasciato dal titolare";
            $curPage_row.="<div style='".$border." width:".$columns_width["inconf"]."; text-align: center;'>".$document."</div>";

            //dichiarazione incompatibilità
            $document=$curIncarico->GetDownloadDicIncompUrl();
            if($document !="") $document="<a href='".$document."' target='_blank'>consulta</a>";
            else $document="non ancora rilasciato dal titolare";
            $curPage_row.="<div style='".$border." width:".$columns_width["incomp"]."; text-align:center'>".$document."</div>";

            //------------incarichi box------------------
            $curPage_row.="</div>";
            $curInnerRow++;
            $incCount++;
          }
        }
        //fine incarichi
        $return.="</incarichi>";
        if($incCount<2) $csv.=";;;;;;;";

        $return.="</dirigente>";
        if($doc)
        {
          $curPage_row.="</div>";
          $curRow++;
        }
      }
    }
  }

  $return.="</dirigenti>";
  $return.="<count>".$inadempienti."(".$count.")</count>";
  if($doc)
  {
    if($curPage != null) $curPage->SetContent($curPage_row);

    //Aggiornamento indice
    $curNumPage=0;
    $curPage=$doc->GetPage($curNumPage);
    $vociCount=0;
    $curRow=0;
    $bgColor="";
    $curPage_row="";

    foreach($indice as $email=>$data)
    {
      if($curNumPage != (int)($vociCount/$indiceNumVociPerPagina))
      {
        $curPage->SetContent($curPage_row);
        $curNumPage=(int)($vociCount/$indiceNumVociPerPagina);
        $curPage=$doc->GetPage($curNumPage);
        $curPage_row="";
        $curRow=0;
        $bgColor="";
      }

      if($curPage instanceof AA_PDF_Page)
      {
        if($vociCount%2 > 0)
        {
          $dati=explode("|",$data);
          $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#".$email."'>".$dati['1']."</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#".$email."'>pag. ".$dati[0]."</a></div>";
          $curPage_row.="</div>";
          if($vociCount == (sizeof($indice)-1)) $curPage->SetContent($curPage_row);
          $curRow++;
        }
        else
        {
          if($curRow%2) $bgColor="background-color: #f5f5f5;";
          else $bgColor="";
          $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";
          $dati=explode("|",$data);
          $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#".$email."'>".$dati['1']."</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#".$email."'>pag. ".$dati[0]."</a></div>";
          
          //ultima voce
          if($vociCount == (sizeof($indice)-1))
          {
            $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'>&nbsp; </div><div style='width:9%;text-align: right;padding-left: 10mm'>&nbsp; </div></div>";
            $curPage->SetContent($curPage_row);
          } 
        }
      }

      $vociCount++;
    }

    //render del documento
    $doc->Render();
    exit;
  }
  
  $xml->SetContent($return);

  if($output=="csv")
  {
    header("Cache-control: private");
    header("Content-type: text/csv");
    header('Content-Disposition: attachment; filename="export.csv"');
    echo $csv;
    exit;
  }
  
  return $xml->toXML();
}

//Flusso xml pubblicazioni art 22 d.lgs. 33/2013 e art 20 d.lgs. 39/2013
function AA_XML_ReportArt22($param="")
{
 //AA_Log::Log("AA_XML_ReportArt22(".$param->C14N().")",100,false,true);

 //Parametri
 $dal=$param->getElementsByTagName("dal")->item(0)->textContent;
 $al=$param->getElementsByTagName("al")->item(0)->textContent;
 $verbose=$param->getElementsByTagName("verbose")->item(0)->textContent;
 $idAssessorato=$param->getElementsByTagName("id_struttura")->item(0)->textContent; //identificativo struttura, lasciare vuoto per RAS
 $idDirezione=$param->getElementsByTagName("id_direzione")->item(0)->textContent; //identificativo direzione
 $tipo_organismo=$param->getElementsByTagName("tipo_organismo")->item(0)->textContent; //tipologia organismo
 $output=$param->getElementsByTagName("output")->item(0)->textContent; //tipologia output ("pdf","csv","xml");
 $partecipazione=$param->getElementsByTagName("partecipazione")->item(0)->textContent; //percentuale di partecipazione
 if($output == "") $output="xml";
 
 if($al == "")
 {
   
   if($dal != "" && $dal >= "2021-01-01")
   {
     $var=explode("-",$dal);
     $al=($var[0]+3)."-12-31";
   }
   else $al=date("Y")."-12-31";
 }

  //Recupera gli identificativi dei titolari
  $params['id_assessorato']=$idAssessorato;
  $params['id_direzione']=$idDirezione;
  $params['tipo']=$tipo_organismo;
  $params['dal']=$dal;
  $params['al']=$al;
  $params['count']="all";

  if(!empty($partecipazione)) $params['partecipazione']=$partecipazione;
  if(($params['tipo']&AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0) $params['partecipazione']=3;

  $xml=new AA_XML_FEED_ART22();
  $xml->SetURL($_SERVER['SCRIPT_NAME']);
  $xml->SetParams($params);
  $return="";

  $filename="pubblicazioni_art22";
  $filename="pubblicazioni_art22";
  if($tipo_organismo !="")
  {
    $tipo=AA_Organismi_Const::GetTipoOrganismi(true);
    //$filename.="-".str_replace(" ","_",$tipo[$tipo_organismo]);
  }
  $filename.="_".date("Y-m-d");

  //Imposta l'utente
  $user=AA_User::UserAuth("","aa_user_art22","A3a3babbfa",false,true);

  $organismi=AA_Organismi::Search($params);
  $count = $organismi[0];
  if($count > 0)
  {
    $doc=null;
    if($output=="pdf")
    {
      $doc = new AA_PDF_RAS_TEMPLATE_A4_PORTRAIT($filename);
      if($idAssessorato == 27)
      {
        $doc = new AA_PDF_ASPAL_TEMPLATE_A4_PORTRAIT($filename."_ASPAL");
      }
      $doc->SetDocumentStyle("font-family: sans-serif; font-size: 3mm;");
      $doc->SetPageCorpoStyle("display: flex; flex-direction: column; justify-content: space-between; padding:0;");
      $curRow=0;
      $rowForPage=1;
      $lastRow=$rowForPage-1;
      $curPage=null;
      $curNumPage=0;
      //$columns_width=array("titolare"=>"10%","incarico"=>"8%","atto"=>"10%","struttura"=>"28%","curriculum"=>"10%","art20"=>"12%","altri_incarichi"=>"10%","1-ter"=>"10%","emolumenti"=>"10%");
      //$columns_width=array("dal"=>"10%","al"=>"10%","inconf"=>"10%","incomp"=>"10%","anno"=>"25%","titolare"=>"50%","tipo_incarico"=>"10%","atto_nomina"=>"10%","struttura"=>"40%","curriculum"=>"25%","altri_incarichi"=>"25%","1-ter"=>"25%","emolumenti"=>"10%");
      $rowContentWidth="width: 99.8%;";

      //pagina di intestazione (senza titolo)
      $curPage=$doc->AddPage();
      $curPage->SetCorpoStyle("display: flex; flex-direction: column; justify-content: center; align-items: center; padding:0;");
      $curPage->SetFooterStyle("border-top:.2mm solid black");
      $curPage->ShowPageNumber(false);

      //Intestazione
      $intestazione="<div style='width: 100%; text-align: center; font-size: 24; font-weight: bold'>Pubblicazioni ai sensi dell'art.22 del d.lgs. 33/2013</div>";
      if($tipo_organismo !="") 
      {
        $intestazione.="<div style='width: 100%; text-align: center; font-size: 18; font-weight: bold;'>".$tipo[$tipo_organismo]."</div>";
      }
      $intestazione.="<div style='width: 100%; text-align: center; font-size: x-small; font-weight: normal;margin-top: 3em;'>documento generato il ".date("Y-m-d")."</div>";

      $curPage->SetContent($intestazione);
      $curNumPage++;

      //Imposta il titolo per le pagine successive
      $doc->SetTitle("Pubblicazioni ai sensi dell'art.22 del d.lgs. 33/2013 - report generato il ".date("Y-m-d"));

      //pagine indice (50 nominativi per pagina)
      $indiceNumVociPerPagina=50;
      for($i=0; $i<$count/$indiceNumVociPerPagina; $i++)
      {
        $curPage=$doc->AddPage();
        $curPage->SetCorpoStyle("display: flex; flex-direction: column; padding:0;");
        $curNumPage++;
      }
    
      $indice=array();
      $lastPage=$count/$rowForPage+$curNumPage;
    }
    $return.="<count>".$count."</count>";

    foreach($organismi[1] as $id=>$curOrganismo)
    {
      //contenuto xml
      $return.=$curOrganismo->ToXml();

      //pdf
      if($doc)
      {
        //Aggiunge una pagina
        $curPage=$doc->AddPage();
        $curNumPage++;
        $curPage_row="";

        //Aggiorna l'indice
        $indice[$curOrganismo->GetID()]=$curNumPage."|".$curOrganismo->GetDescrizione();
        $curPage_row.="<div id='".$curOrganismo->GetID()."' style='display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; text-align: center; padding: 0mm; min-height: 9mm;'>";

        //$template=new AA_OrganismiPublicReportTemplateView("report_organismo_pdf_".$curOrganismo->GetId(),null,$curOrganismo,$this->oUser);
        
        //Prima pagina
        $curPage_row.=new AA_OrganismiPublicReportTemplateGeneralPageView("report_organismo_pdf_general_page_".$curOrganismo->GetId(),null,$curOrganismo,$user);
        
        $provvedimenti=$curOrganismo->GetProvvedimenti();
        $provvedimenti_newPage=false;
        if(sizeof($provvedimenti) > 0 && sizeof($provvedimenti) < 10 && strlen($curOrganismo->GetNote())>1500) $provvedimenti_newPage=true;
        if(sizeof($provvedimenti) > 10) $provvedimenti_newPage=true;
        
        if(sizeof($provvedimenti) > 0)
        {
            if(!$provvedimenti_newPage)
            {
                $provvedimenti_table = new AA_OrganismiReportProvvedimentiListTemplateView("report_organismo_pdf_provvedimenti_page_".$curOrganismo->GetId(),null,$curOrganismo,$user,$provvedimenti);
                $curPage_row.=$provvedimenti_table;

                //footer
                $curPage_row.="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$curOrganismo->GetAggiornamento()."</span></div>";
                $curPage_row.="</div>";
                $curPage->SetContent($curPage_row);
            }
            else
            {
                $curPage_row.="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$curOrganismo->GetAggiornamento()."</span></div>";
                $curPage_row.="</div>";
                $curPage->SetContent($curPage_row);

                $curPage=$doc->AddPage();
                $curNumPage++;
                $curPage_row="";

                $curPage_row.="<div style='display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; text-align: center; padding: 0mm; min-height: 9mm;'>";

                $curPage_row.=new AA_OrganismiPublicReportTemplateProvvedimentiPageView("report_organismo_pdf_provvedimenti_page_".$curOrganismo->GetId(),null,$curOrganismo,$user,$provvedimenti);

                $curPage_row.="</div>";
                $curPage->SetContent($curPage_row);
            }
        }
        else
        {
            $curPage_row.="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente organismo sono state aggiornate l'ultima volta il ".$curOrganismo->GetAggiornamento()."</span></div>";
            $curPage_row.="</div>";
            $curPage->SetContent($curPage_row);
        }

        //seconda pagina
        //Aggiunge una pagina
        $curPage=$doc->AddPage();
        $curNumPage++;
        $curPage_row="";
        $curPage_row.="<div id='".$curOrganismo->GetID()."' style='display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; text-align: center; padding: 0mm; min-height: 9mm;'>";
        $curPage_row.=new AA_OrganismiPublicReportTemplateNominePageView("report_organismo_pdf_nomine_page_".$curOrganismo->GetId(),null,$curOrganismo,$user);
        $curPage_row.="</div>";
        $curPage->SetContent($curPage_row);
      }
    }
  }

  if($doc)
  {
    //if($curPage != null) $curPage->SetContent($curPage_row);

    //Aggiornamento indice
    $curNumPage=1;
    $curPage=$doc->GetPage($curNumPage);
    $vociCount=0;
    $curRow=0;
    $bgColor="";
    $curPage_row="";

    foreach($indice as $id=>$data)
    {
      if($curNumPage != (int)($vociCount/$indiceNumVociPerPagina)+1)
      {
        $curPage->SetContent($curPage_row);
        $curNumPage=(int)($vociCount/$indiceNumVociPerPagina)+1;
        $curPage=$doc->GetPage($curNumPage);
        $curRow=0;
        $bgColor="";
      }

      if($curPage instanceof AA_PDF_Page)
      {
        if($vociCount%2 > 0)
        {
          $dati=explode("|",$data);
          $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#".$id."'>".$dati['1']."</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#".$id."'>pag. ".$dati[0]."</a></div>";
          $curPage_row.="</div>";
          if($vociCount == (sizeof($indice)-1)) $curPage->SetContent($curPage_row);
          $curRow++;
        }
        else
        {
          //Intestazione
          if($curRow==0) $curPage_row="<div style='width:100%;text-align: center; font-size: 18px; font-weight: bold; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .3em;'>Indice</div>";
          
          if($curRow%2) $bgColor="background-color: #f5f5f5;";
          else $bgColor="";
          $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";
          $dati=explode("|",$data);
          $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#".$id."'>".$dati['1']."</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#".$id."'>pag. ".$dati[0]."</a></div>";
          
          //ultima voce
          if($vociCount == (sizeof($indice)-1))
          {
            $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'>&nbsp; </div><div style='width:9%;text-align: right;padding-left: 10mm'>&nbsp; </div></div>";
            $curPage->SetContent($curPage_row);
          } 
        }
      }

      $vociCount++;
    }

    //render del documento
    $doc->Render();
    exit;
  }
  
  $xml->SetContent($return);
  return $xml->toXML();
}
#-------------------------------------------------------------------------------------

//Flusso xml pubblicazioni in capo ai titolari di posizione organizzative (art 14 d.lgs. 33/2013 comma 1-quinquies) - vecchio modulo
function AA_XML_ReportPO($param="")
{
  //Parametri
  $anno=$param->getElementsByTagName("anno")->item(0)->textContent;
  $nome=$param->getElementsByTagName("nome")->item(0)->textContent;
  $cognome=$param->getElementsByTagName("cognome")->item(0)->textContent;
  $email=$param->getElementsByTagName("email")->item(0)->textContent;
  $verbose=$param->getElementsByTagName("verbose")->item(0)->textContent;
  $id_struttura=$param->getElementsByTagName("id_struttura")->item(0)->textContent; //identificativo struttura, lasciare vuoto per RAS
  $struttura=$param->getElementsByTagName("struttura")->item(0)->textContent; //nome struttura, lasciare vuoto per RAS
  $id_user=$param->getElementsByTagName("id_user")->item(0)->textContent; //identificativo utente
  $tipo_incarico=$param->getElementsByTagName("tipo_incarico")->item(0)->textContent; //tipologia incarico
  $output=$param->getElementsByTagName("output")->item(0)->textContent; //tipologia output ("pdf","csv","xml");
  if($output == "") $output="xml";
  if($anno == "") $anno=date("Y");

  //accesso al db
  $db=new Database();

  //Recupera gli identificativi dei dirigenti
  $query="SELECT * FROM email_login WHERE aggiornamento >='".($anno-3)."' AND amministrazione='ras' AND nome not like '' AND cognome not like ''";
  /*if($id_struttura != "") $query.=" and utenti.id_assessorato='".$id_struttura."'";
  if($struttura != "") $query.=" and assessorati.descrizione like '%".$struttura."%'";
  if($nome != "") $query.=" and utenti.nome like '".$nome."'";
  if($cognome != "") $query.=" and utenti.cognome like '".$cognome."'";
  if($email != "") $query.=" and email like '".$email."'";
  if($id_user != "") $query.=" and utenti.id like '".$id_user."'";
  if($tipo_incarico !="") $query.=" and utenti.tipo_incarico ='".$tipo_incarico."'";

  //Percorso generale
  $path = "/home/sitod/uploads/amministrazione_trasparente/art14/1bis-dirigenti";
  $path_curriculum=$path."/1b_curriculum/".$anno."/";
  $path_art20=$path."/dichiarazioni/".$anno."/";
  $path_altri_incarichi=$path."/1d_1e_altri_incarichi/".$anno."/";
  $path_1ter=$path."/1ter/".$anno."/";

  $curriculum=scandir($path_curriculum);
  $dichiarazioni=scandir($path_art20);
  $altri_incarichi=scandir($path_altri_incarichi);
  $comma_1ter=scandir($path_1ter);

  //Dirigenti RAS
  if($id_struttura == "" && $struttura == "")
  {
    $query.=" and (assessorati.tipo = '0' or assessorati.tipo = '3')";
    //logo
    $logo="logo_ras.gif";
    
  }
  else
  {
    if($struttura == "ASPAL") $logo="27_logo.jpg";
  }*/

  $query.=" ORDER by cognome, nome";

  $return="<report_pubblicazioni_po>";
  $return.="<anno>".$anno."</anno>";

  $count = 0;
  $po=array();

  $doc=null;
  $logo="logo_ras.gif";

  $db->Query($query);
  $rs=$db->GetRecordSet();
  $po_count=$rs->GetCount();
  if($po_count > 0)
  {
    if($output=="pdf")
    {
      $doc = new AA_PDF_RAS_TEMPLATE_A4_PORTRAIT("pubblicazioni_art14_RAS_PO".$anno);
      $doc->SetLogoImage($logo);
      $doc->SetDocumentStyle("font-family: sans-serif; font-size: 3mm;");
      $doc->SetPageCorpoStyle("border: 1px solid black; display: flex; flex-direction: column; justify-content: space-between; padding:0;");
      $doc->SetTitle("Pubblicazioni dei titolari di incarichi di posizione organizzativa - art.14, comma 1-quinquies del d.lgs. 33/2013 - anno ".$anno);
      $curRow=0;
      $rowForPage=23;
      $curPage=null;
      $curCol=0;
      $curNumPage=0;
      $lastPage=sizeof($po)/$rowForPage;
      $columns_width=array("titolare_1col"=>"25%","curriculum_1col"=>"25%","titolare_2col"=>"25%","curriculum_2col"=>"25%");
    }

    $path = "/home/sitod/uploads/amministrazione_trasparente/art14/curriculum/";
    
    do 
    {
      $nome=$rs->Get("nome");
      $cognome=$rs->Get("cognome");
      $email=$rs->Get("email");
      $curriculum_file=md5($email).".pdf";

      if(file_exists($path.$curriculum_file))
      {
        $col=1;
        if(!($count%2)) $col=2;
  
        if($doc)
        {
          $curPage_Row="";
          $border="";
          //inizia una nuova pagina (intestazione)
          if($curRow==$rowForPage) $curRow=0; 
          if($curRow==0)
          {
            $border="";
            if($curPage != null) $curPage->SetContent($curPage_row);
            $curPage=$doc->AddPage();
            $curNumPage++;
            if($curNumPage >= $lastPage) $curPage->SetCorpoStyle("border: 1px solid black; display: flex; flex-direction: column; padding:0;");
            $curPage_row="<div style='display:flex; align-items: center; justify-content: space-between; background-color: rgb(190, 190, 190); border-bottom: 1px solid black; font-weight: bold; text-align: center; padding: .3mm; min-height: 10mm'>";
            $curPage_row.="<div style='".$border." width:".$columns_width["titolare_1col"].";'>Titolare</div>";
            $curPage_row.="<div style='border-right: 1px solid black;".$border." width:".$columns_width["curriculum_1col"]."'>Curriculum</div>";
            $curPage_row.="<div style='".$border." width:".$columns_width["titolare_2col"].";'>Titolare</div>";
            $curPage_row.="<div style='".$border." width:".$columns_width["curriculum_2col"]."'>Curriculum</div>";
            $curPage_row.="</div>";
  
            $curRow++;
          }
          //----------------------
          if(!($count%2))
          {
            if(!($curRow%2)) $bgColor="background-color: #f5f5f5;";
            else $bgColor="";        
            if($curRow != $rowForPage-1) $curPage_row.="<div style='display:flex;  align-items: center; justify-content: space-between; border-bottom: 1px solid black; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";
            else $curPage_row.="<div style='display:flex;  align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";  
          }
        }
        else
        {
          $return.='<po uid="'.$rs->Get('id').'">';
        }
        
        //Nome e cognome
        {
          $return.="<nome>".$nome."</nome>";
          $return.="<cognome>".$cognome."</cognome>";
          if($verbose !="") $return.="<email>".$email."</email>";
          if($count%2) $border="border-left: 1px solid black;";
          if($doc) $curPage_row.="<div style='".$border." width:".$columns_width["titolare_".$col."col"]."; text-align: left; padding-left: 10mm'>".ucwords(mb_strtolower($cognome,"UTF-8"))." ".ucwords(mb_strtolower($nome,"UTF-8"))."</div>";
          $border="";
        }
        
        $urlCurriculum="https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/curriculum/?po=1&amp;anno=".$anno."&amp;email=".$email;
    
        $return.="<curriculum stato='1'>".$urlCurriculum."</curriculum>";
        if($doc)
        {
          $curPage_row.="<div style='".$border." width:".$columns_width["curriculum_".$col."col"]."'><a href='".$urlCurriculum."' alt='Consulta il curriculum'>consulta</a></div>";
        }
  
        $return.="</po>";
        $count++;
  
        if($doc)
        {
          //$curPage_row.=$count%2;
          if(($count%2) == 0)
          {
            $curPage_row.="</div>";
            $curRow++;  
          }
        }  
      }
    }while($rs->MoveNext());

    if($doc && $count%2)
    {
        $curPage_row.="</div>";
        //$curRow++;  
    }
  }

  $return.="<count>".$count."</count>";
  $return.="</report_pubblicazioni_po>";
  if($doc)
  {
    if($curPage != null) $curPage->SetContent($curPage_row);
    $doc->Render();
    exit;
  } 
  
  return $return;
}

//Flusso xml pubblicazioni in capo ai titolari di posizione organizzative (art 14 d.lgs. 33/2013 comma 1-quinquies) - modulo gestione incarichi
function AA_XML_ReportPO_V2($param="")
{
  //Parametri
  $dal=$param->getElementsByTagName("dal")->item(0)->textContent;
  $al=$param->getElementsByTagName("al")->item(0)->textContent;
  $email=$param->getElementsByTagName("email")->item(0)->textContent;
  $verbose=$param->getElementsByTagName("verbose")->item(0)->textContent;
  $onlyRas=$param->getElementsByTagName("only_ras")->item(0)->textContent;
  $idAssessorato=$param->getElementsByTagName("id_struttura")->item(0)->textContent; //identificativo struttura, lasciare vuoto per RAS
  $idDirezione=$param->getElementsByTagName("id_direzione")->item(0)->textContent; //identificativo direzione
  $idServizio=$param->getElementsByTagName("id_servizio")->item(0)->textContent; //identificativo servizio
  $id_user=$param->getElementsByTagName("id_user")->item(0)->textContent; //identificativo utente
  $tipo_incarico=$param->getElementsByTagName("tipo_incarico")->item(0)->textContent; //tipologia incarico
  $output=$param->getElementsByTagName("output")->item(0)->textContent; //tipologia output ("pdf","csv","xml");
  if($output == "") $output="xml";
  
  if($al == "")
  {
    
    if($dal != "" && $dal >= "2021-01-01")
    {
      $var=explode("-",$dal);
      $al=($var[0]+3)."-12-31";
    }
    else $al=date("Y")."-12-31";
  }

  //Niente dati prima del 2021
  if($dal < "2021-01-01")
  {
    $var=explode("-",$al);
    $dal=$var[0]-3;
    if($dal < "2021-01-01") $dal="2021-01-01";
  }

  //Recupera gli identificativi dei titolari
  $params['only_ras']=$onlyRas;
  $params['idAssessoratoIncarico']=$idAssessorato;
  $params['idDirezioneIncarico']=$idDirezione;
  $params['idServizioIncarico']=$idServizio;
  $params['tipoIncarico']=$tipo_incarico;
  $params['dal']=$dal;
  $params['al']=$al;
  $params['count']="all";
  $titolari=AA_IncarichiOps::SearchTitolari($params);

  $xml=new AA_XML_FEED_ART14_PO();
  $xml->SetURL($_SERVER['SCRIPT_NAME']);
  $xml->SetParams($params);
  $return="<posizioni_organizzative>";

  $csv="nome;cognome;email;tipo_incarico;curriculum";
  
  $count = 0;
  $po=array();

  $doc=null;

  $po_count = $titolari[0];

  if($po_count > 0)
  {
    if($output=="pdf")
    {
      //default
      $doc = new AA_PDF_RAS_TEMPLATE_A4_PORTRAIT("pubblicazioni_art14_RAS_PO-".$dal."_".$al);
      
      //Aspal
      if($idAssessorato == 27)
      {
        $doc = new AA_PDF_ASPAL_TEMPLATE_A4_PORTRAIT("pubblicazioni_art14_ASPAL_PO".$dal."_".$al);   
      }

      $doc->SetHeaderHeight(27);
      $doc->SetDocumentStyle("font-family: sans-serif; font-size: 3mm;");
      $doc->SetPageCorpoStyle("border: 1px solid black; display: flex; flex-direction: column; justify-content: space-between; padding:0;");
      $doc->SetTitle("Pubblicazioni dei titolari di incarichi di posizione organizzativa - art.14, comma 1-quinquies del d.lgs. 33/2013<br/>periodo di riferimento dal: ".$dal." al: ".$al);
      $curRow=0;
      $rowForPage=23;
      $curPage=null;
      $curCol=0;
      $curNumPage=0;
      $lastPage=sizeof($po)/$rowForPage;
      $columns_width=array("titolare_1col"=>"25%","curriculum_1col"=>"25%","titolare_2col"=>"25%","curriculum_2col"=>"25%");
    }

    foreach ($titolari[1] as $id=>$curTitolare)
    {
      $nome=$curTitolare->GetNome();
      $cognome=$curTitolare->GetCognome();
      $email=$curTitolare->GetEmail();
      $curriculum_file=$curTitolare->GetLastCurriculum();

      if($curriculum_file !="")
      {
        $col=1;
        if(!($count%2)) $col=2;

        if($doc)
        {
          $curPage_Row="";
          $border="";
          //inizia una nuova pagina (intestazione)
          if($curRow==$rowForPage) $curRow=0; 
          if($curRow==0)
          {
            $border="";
            if($curPage != null) $curPage->SetContent($curPage_row);
            $curPage=$doc->AddPage();
            $curNumPage++;
            if($curNumPage >= $lastPage) $curPage->SetCorpoStyle("border: 1px solid black; display: flex; flex-direction: column; padding:0;");
            $curPage_row="<div style='display:flex; align-items: center; justify-content: space-between; background-color: rgb(190, 190, 190); border-bottom: 1px solid black; font-weight: bold; text-align: center; padding: .3mm; min-height: 10mm'>";
            $curPage_row.="<div style='".$border." width:".$columns_width["titolare_1col"].";'>Titolare</div>";
            $curPage_row.="<div style='border-right: 1px solid black;".$border." width:".$columns_width["curriculum_1col"]."'>Curriculum</div>";
            $curPage_row.="<div style='".$border." width:".$columns_width["titolare_2col"].";'>Titolare</div>";
            $curPage_row.="<div style='".$border." width:".$columns_width["curriculum_2col"]."'>Curriculum</div>";
            $curPage_row.="</div>";

            $curRow++;
          }
          //----------------------
          if(!($count%2))
          {
            if(!($curRow%2)) $bgColor="background-color: #f5f5f5;";
            else $bgColor="";        
            if($curRow != $rowForPage-1) $curPage_row.="<div style='display:flex;  align-items: center; justify-content: space-between; border-bottom: 1px solid black; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";
            else $curPage_row.="<div style='display:flex;  align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";  
          }
        }
        else
        {
          $return.='<po uid="'.$id.'">';
          
          if($output=="csv")
          {
              $lastIncarico=$curTitolare->GetLastIncarico();
              $csv.="\r\n".$nome.";".$cognome.";".$email.";".$lastIncarico->GetTipo().";".$curriculum_file;
          }
        }
        
        //Nome e cognome
        {
          $return.="<nome>".$nome."</nome>";
          $return.="<cognome>".$cognome."</cognome>";
          if($verbose !="") $return.="<email>".$email."</email>";
          if($count%2) $border="border-left: 1px solid black;";
          if($doc) $curPage_row.="<div style='".$border." width:".$columns_width["titolare_".$col."col"]."; text-align: left; padding-left: 10mm'>".ucwords(mb_strtolower($cognome,"UTF-8"))." ".ucwords(mb_strtolower($nome,"UTF-8"))."</div>";
          $border="";
        }

        $return.="<curriculum stato='1'>".$curriculum_file."</curriculum>";
        if($doc)
        {
          $curPage_row.="<div style='".$border." width:".$columns_width["curriculum_".$col."col"]."'><a href='".$curriculum_file."' alt='Consulta il curriculum'>consulta</a></div>";
        }

        $return.="</po>";
        $count++;

        if($doc)
        {
          //$curPage_row.=$count%2;
          if(($count%2) == 0)
          {
            $curPage_row.="</div>";
            $curRow++;  
          }
        }  
      }
    }

    if($doc && $count%2)
    {
        $curPage_row.="</div>";
        //$curRow++;  
    }
  }

  $return.="<count>".$count."</count>";
  $return.="</posizioni_organizzative>";
  if($doc)
  {
    if($curPage != null) $curPage->SetContent($curPage_row);
    $doc->Render();
    exit;
  }
  
  if($output=="csv") return $csv;
  
  $xml->SetContent($return);
  return $xml->toXML();
}
//--------------------------------------------

//Flusso xml pubblicazioni collaboratori e consulenti (art 15 d.lgs. 33/2013) - vecchio modulo
function AA_XML_ReportArt15($param="")
{
  //Parametri
  $anno=$param->getElementsByTagName("anno")->item(0)->textContent;
  $nome=$param->getElementsByTagName("nome")->item(0)->textContent;
  $cognome=$param->getElementsByTagName("cognome")->item(0)->textContent;
  $email=$param->getElementsByTagName("email")->item(0)->textContent;
  $verbose=$param->getElementsByTagName("verbose")->item(0)->textContent;
  $id_struttura=$param->getElementsByTagName("id_struttura")->item(0)->textContent; //identificativo struttura, lasciare vuoto per RAS
  $struttura=$param->getElementsByTagName("struttura")->item(0)->textContent; //nome struttura, lasciare vuoto per RAS
  $id_user=$param->getElementsByTagName("id_user")->item(0)->textContent; //identificativo utente
  $tipo_incarico=$param->getElementsByTagName("tipo_incarico")->item(0)->textContent; //tipologia incarico
  $output=$param->getElementsByTagName("output")->item(0)->textContent; //tipologia output ("pdf","csv","xml");
  if($output == "") $output="xml";
  if($anno == "") $anno=date("Y");

  //accesso al db
  $db=new AA_Database();

  //Recupera le pubblicazioni
  $query="SELECT * FROM art15_pubblicazioni LEFT JOIN assessorati on  art15_pubblicazioni.id_assessorato=assessorati.id WHERE anno_rif ='".$anno."' AND assessorati.tipo=0 AND nome not like '' AND cognome not like ''";
  $path = "/home/sitod/uploads/monitspese/art15";
  $path_curriculum=$path."/curriculum/";
  $path_dichiarazioni=$path."/dichiarazioni/";
  $path_attestazioni=$path."/attestazioni/";
  
  $logo="logo_ras.gif";

  $query.=" ORDER by art15_pubblicazioni.cognome, art15_pubblicazioni.nome";

  $return="<pubblicazioni_art15>";
  $return.="<anno>".$anno."</anno>";

  $count = 0;
  $po=array();

  $doc=null;
  $logo="logo_ras.gif";

  $db->Query($query);
  
  if($output=="pdf")
  {
    $doc = new AA_PDF_RAS_TEMPLATE_A4_LANDSCAPE("pubblicazioni_art15_RAS_".$anno);
    $doc->SetLogoImage($logo);
    $doc->SetDocumentStyle("font-family: sans-serif; font-size: 3mm;");
    $doc->SetPageCorpoStyle("border: 1px solid black; display: flex; flex-direction: column; justify-content: space-between; padding:0;");
    $doc->SetTitle("Pubblicazioni Titolari di incarichi di collaborazione o consulenza - art.15 del d.lgs. 33/2013 - anno ".$anno);
    $curRow=0;
    $rowForPage=10;
    $curPage=null;
    $curNumPage=0;
    $lastPage=sizeof($po)/$rowForPage;
    $columns_width=array("anno_rif"=>"7%","cognome"=>"7%","nome"=>"7%","cf"=>"7%","oggetto"=>"7%","data_conferimento"=>"7%","data_inizio"=>"7%","data_fine"=>"7%","estremi_atto"=>"7%","importo","parte_variabile","importo_erogato","attestazione_verifica","curriculum","dichiarazione");
  }

  $tot_count=$db->GetAffectedRows();
  if($tot_count > 0)
  {
    $rs=$db->GetResultSet();
    $path = "/home/sitod/uploads/amministrazione_trasparente/art14/curriculum/";
    
    do 
    {
      $nome=$rs->Get("nome");
      $cognome=$rs->Get("cognome");
      $email=$rs->Get("email");
      $curriculum_file=md5($email).".pdf";

      if(file_exists($path.$curriculum_file))
      {
        $col=1;
        if(!($count%2)) $col=2;
  
        if($doc)
        {
          $curPage_Row="";
          $border="";
          //inizia una nuova pagina (intestazione)
          if($curRow==$rowForPage) $curRow=0; 
          if($curRow==0)
          {
            $border="";
            if($curPage != null) $curPage->SetContent($curPage_row);
            $curPage=$doc->AddPage();
            $curNumPage++;
            if($curNumPage >= $lastPage) $curPage->SetCorpoStyle("border: 1px solid black; display: flex; flex-direction: column; padding:0;");
            $curPage_row="<div style='display:flex; align-items: center; justify-content: space-between; background-color: rgb(190, 190, 190); border-bottom: 1px solid black; font-weight: bold; text-align: center; padding: .3mm; min-height: 10mm'>";
            $curPage_row.="<div style='".$border." width:".$columns_width["titolare_1col"].";'>Titolare</div>";
            $curPage_row.="<div style='border-right: 1px solid black;".$border." width:".$columns_width["curriculum_1col"]."'>Curriculum</div>";
            $curPage_row.="<div style='".$border." width:".$columns_width["titolare_2col"].";'>Titolare</div>";
            $curPage_row.="<div style='".$border." width:".$columns_width["curriculum_2col"]."'>Curriculum</div>";
            $curPage_row.="</div>";
  
            $curRow++;
          }
          //----------------------
          if(!($count%2))
          {
            if(!($curRow%2)) $bgColor="background-color: #f5f5f5;";
            else $bgColor="";        
            if($curRow != $rowForPage-1) $curPage_row.="<div style='display:flex;  align-items: center; justify-content: space-between; border-bottom: 1px solid black; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";
            else $curPage_row.="<div style='display:flex;  align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";  
          }
        }
        else
        {
          $return.='<po uid="'.$rs->Get('id').'">';
        }
        
        //Nome e cognome
        {
          $return.="<nome>".$nome."</nome>";
          $return.="<cognome>".$cognome."</cognome>";
          if($verbose !="") $return.="<email>".$email."</email>";
          if($count%2) $border="border-left: 1px solid black;";
          if($doc) $curPage_row.="<div style='".$border." width:".$columns_width["titolare_".$col."col"]."; text-align: left; padding-left: 10mm'>".ucwords(mb_strtolower($cognome,"UTF-8"))." ".ucwords(mb_strtolower($nome,"UTF-8"))."</div>";
          $border="";
        }
        
        $urlCurriculum="https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/curriculum/?po=1&amp;anno=".$anno."&amp;email=".$email;
    
        $return.="<curriculum stato='1'>".$urlCurriculum."</curriculum>";
        if($doc)
        {
          $curPage_row.="<div style='".$border." width:".$columns_width["curriculum_".$col."col"]."'><a href='".$urlCurriculum."' alt='Consulta il curriculum'>consulta</a></div>";
        }
  
        $return.="</po>";
        $count++;
  
        if($doc)
        {
          //$curPage_row.=$count%2;
          if(($count%2) == 0)
          {
            $curPage_row.="</div>";
            $curRow++;  
          }
        }  
      }
    }while($rs->MoveNext());

    if($doc && $count%2)
    {
        $curPage_row.="</div>";
        //$curRow++;  
    }
  }

  $return.="<count>".$count."</count>";
  $return.="</report_pubblicazioni_po>";
  if($doc)
  {
    if($curPage != null) $curPage->SetContent($curPage_row);
    $doc->Render();
    exit;
  } 
  
  return $return;
}

//Pubblicazioni art. 14 comma 1d e 1e d.lgs. 33/3013
function Art14_1d_1e_Query($param)
{

  $anno=$param->getElementsByTagName("anno")->item(0)->textContent;
  $nome=$param->getElementsByTagName("nome")->item(0)->textContent;
  $cognome=$param->getElementsByTagName("cognome")->item(0)->textContent;
  $email=$param->getElementsByTagName("email")->item(0)->textContent;
  $verbose=$param->getElementsByTagName("verbose")->item(0)->textContent;
  $id_struttura=$param->getElementsByTagName("id_struttura")->item(0)->textContent; //identificativo struttura, lasciare vuoto per RAS
  $struttura=$param->getElementsByTagName("struttura")->item(0)->textContent; //nome struttura, lasciare vuoto per RAS
  $id_user=$param->getElementsByTagName("id_user")->item(0)->textContent; //identificativo utente
  $onlyReport=$param->getElementsByTagName("report")->item(0)->textContent; //Visualizza solo il report
  $download=$param->getElementsByTagName("download")->item(0)->textContent; //Scarica le dichiarazione
  $po=false;
  if($param->getElementsByTagName("po")->item(0)->textContent=="1") $po=true; //Scarica il curriculum per le posizioni organizzative
  if($anno == "") $anno=date("Y");

  

  //Download il curriculum del dirigente
  if($download !="") return Art14_1d_1e_Download($email,$anno,$id_user,$po);

  //Restituisce il report di assolvimento dei dirigenti
  if($onlyReport=="2") return Art14_1d_1e_ReportDirigenti($param);

  //accesso al db
  $db=new Database();

  //Recupera gli identificativi dei dirigenti
  $query="SELECT utenti.*, assessorati.descrizione as struttura  from utenti left join assessorati on utenti.id_assessorato=assessorati.id where utenti.livello = '0' and utenti.eliminato = '0' ";
  if($id_struttura != "") $query.=" and utenti.id_assessorato='".$id_struttura."'";
  if($struttura != "") $query.=" and assessorati.descrizione like '%".$struttura."%'";
  if($nome != "") $query.=" and utenti.nome like '".$nome."'";
  if($cognome != "") $query.=" and utenti.cognome like '".$cognome."'";
  if($email != "") $query.=" and email like '".$email."'";
  if($id_user != "") $query.=" and utenti.id like '".$id_user."'";

  //Percorso dichiarazioni
  $path = "/home/sitod/uploads/amministrazione_trasparente/art14/1bis-dirigenti/1d_1e_altri_incarichi/".$anno."/";
  $dichiarazioni=scandir($path);

  $tot_assolti=0;
  $tot_non_conformi=0;

  //Dirigenti RAS
  if($id_struttura == "" && $struttura == "")
  {
    $query.=" and (assessorati.tipo = '0' or assessorati.tipo = '3')";
    $dirigenti_list=file_get_contents($path."all_dirs.txt");
    if($dirigenti_list !="") $dirigenti=split("\n",$dirigenti_list);
  }

  $query.=" ORDER by utenti.cognome, utenti.nome, utenti.user, utenti.email";

  $return="<art14_1d_1e>";
  $return.="<anno>".$anno."</anno>";

  $count = 0;
  
  //Urlbase curriculum
  $urlbase="https:///sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/altri_incarichi/?anno=".$anno."&amp;email=";
  $db->Query($query);
  $rs=$db->GetRecordSet();
  if($rs->GetCount() > 0)
  {
    $lastPerson=$rs->Get("nome").$rs->Get("cognome").$rs->Get("email"); //flag di verifica se si tratta della stessa persona
    $first=true;
    do
    {
      //id dirigente
      $id_dirigente=$rs->Get("id");
      $email=$rs->Get("email");
      
      $curPerson=$rs->Get("nome").$rs->Get("cognome").$rs->Get("email");

      //Verifica la presenza di almeno una dichiarazione
      $bInsert=0;
      $nonConforme=0;
      foreach ($dichiarazioni as $curDichiarazione)
      {
        //if($id_dirigente=="2410") error_log("file corrente: ".$curDichiarazione." - id dirigente: ".$id_dirigente." - check: ".strpos($curDichiarazione,$id_dirigente."_"));
        if(strpos($curDichiarazione,$id_dirigente."_") === 0)
        {
          if(substr($curDichiarazione, -3) == "pdf")
          {
            //error_log("inserito: ".$id_dirigente);
            $bInsert=1;
            break;
          }
          else
          {
            $nonConforme=1;
          }
        } 
      }

      if($bInsert==1)
      {
          //Aggiorna la lista dei dirigenti
          if($curPerson != "")
          {
            foreach($dirigenti as $key=>$value)
            {
              if(stripos($value,$rs->Get("nome"))!==FALSE && stripos($value,$rs->Get("cognome")) !==FALSE)
              {
                $dirigenti[$key].=" - Assolto";
                $tot_assolti++;
              }
            }
          }

        if($curPerson != $lastPerson || ($curPerson=="" && $lastPerson=="")) 
        {
          if($first==true) $return.='<dirigente uid="'.$id_dirigente.'">';
          else $return.='</curriculum></dirigente><dirigente uid="'.$id_dirigente.'">';
          if($curPerson == "")
          {
            $return.="<nome>".$rs->Get("user")."</nome>";
            $return.="<cognome>".$id_dirigente."</cognome>";
            if($verbose !="") $return.="<email>".$rs->Get("user")."@regione.sardegna.it</email>";
          }
          else
          {
            $return.="<nome>".$rs->Get("nome")."</nome>";
            $return.="<cognome>".$rs->Get("cognome")."</cognome>";
            if($verbose !="") $return.="<email>".$rs->Get("email")."</email>";
          }
          if($verbose !="") $return.="<struttura>".$rs->Get("struttura")."</struttura>";
          $return.="<curriculum>";
          $return.="<url>".$urlbase.$email."</url>";
        }
        else $return.="<url>".$urlbase.$email."</url>";
        
        $count++;
        $first=false;
        $lastPerson=$curPerson;
      }

      if($nonConforme == 1)
      {
        if($curPerson != "")
        {
          foreach($dirigenti as $key=>$value)
          {
            if(stripos($value,$rs->Get("nome"))!==FALSE && stripos($value,$rs->Get("cognome")) !==FALSE)
            {
              $dirigenti[$key].=" - Non conforme";
              $tot_non_conformi++;
            }
          }
        }
      }
    }while($rs->MoveNext());

    if($count > 0) $return.="</curriculum></dirigente>";
    $return.="<count>".$count."</count></art14_1d_1e>";

    if($onlyReport)
    {
      header("Cache-control: private");
      header("Content-type: text/html");
      foreach($dirigenti as $dirigente)
      {
        echo $dirigente."</br>";
      }

      echo "Dirigenti che hanno assolto: ".$tot_assolti."</br>";
      echo "Dirigenti che hanno caricato file non conformi: ".$tot_non_conformi."</br>";
      echo "Dirigenti che devono assolvere: ".(count($dirigenti)-$tot_assolti)."</br>";

      die();
    }
    return $return;
  }
  else
  {
    error_log("Art14_1b_Query() - query vuota: ".$query);
    return $return."<count>0</count></art14_1b>";
  }
}

//Pubblicazioni art. 14, comma 1b, d.lgs. 33/3013 - report
function Art14_1d_1e_ReportDirigenti($param)
{
  //to do;
}

//Pubblicazioni art. 14, comma 1d, 1e, d.lgs. 33/3013 - download
function Art14_1d_1e_Download($email,$anno,$uid)
{
  //Percorso
  $path = "/home/sitod/uploads/amministrazione_trasparente/art14/1bis-dirigenti/1d_1e_altri_incarichi/".$anno."/";
  $dichiarazioni=scandir($path);

  $zip = new ZipArchive();
  $zip_path="/tmp/".uniqid().".zip";
  $num_files=0;
  if ($zip->open($zip_path, ZipArchive::CREATE)!==TRUE)
  {
    error_log("Art14_1d_1e_Download() - errore durante la creazione del file zip temporaneo:".$zip_path);
    exit("Errore durante la generazione del file temporaneo (AA_ERR_143301).");
  }

  //$zip->addFromString("info.txt" . time(), "Questo file contiene le dichiarazio");
  
  //Cerca per email
  if($email !="" && $uid == "")
  {
    $db=new Database();
    $db->Query("SELECT GROUP_CONCAT(id) as ids FROM `utenti` WHERE email like '".$email."'");
    $rs=$db->GetRecordSet();

    $ids="";

    if($rs->GetCount() > 0)
    {
      $ids=explode(",",$rs->Get('ids'));
    }
    else 
    {
      error_log("Art14_1d_1e_Download() - errore durante la rimozione del file compresso: ".$zip_path." - nessun documento trovato.");
      exit("Art14_1d_1e_Download() - Nessun documento trovato per l'email: ".$email);
    }
  }
  
  foreach($dichiarazioni as $curDichiarazione)
  {
    $include=false;

    if($ids != "")
    {
      foreach($ids as $curID)
      {
        if(strpos($curDichiarazione,$curID."_") === 0 && substr($curDichiarazione, -3) == "pdf")
        {
            if(!$zip->addFile($path.$curDichiarazione, $curDichiarazione))
            {
              error_log("Art14_1d_1e_Download() - errore durante l'inserimento del file: ".$curDichiarazione." - nel file compresso: ".$zip_path);
              $zip->close();
              if(!unlink($zip_path))
              {
                error_log("Art14_1d_1e_Download() - errore durante la rimozione del file compresso: ".$zip_path);
                exit("Errore durante la rimozione del file temporaneo (AA_ERR_143302).");;
              }
              exit("Errore durante l'inserimento del documento: ".$curDichiarazione." nel file temporaneo (AA_ERR_143303).");;
            }
            $num_files++;
        }        
      }
    }

    if(strpos($curDichiarazione,$uid."_") === 0 && substr($curDichiarazione, -3) == "pdf" && $ids == "")
    {
        if(!$zip->addFile($path.$curDichiarazione, $curDichiarazione))
        {
          error_log("Art14_1d_1e_Download() - errore durante l'inserimento del file: ".$curDichiarazione." - nel file compresso: ".$zip_path);
          $zip->close();
          if(!unlink($zip_path))
          {
            error_log("Art14_1d_1e_Download() - errore durante la rimozione del file compresso: ".$zip_path);
            exit("Errore durante la rimozione del file temporaneo (AA_ERR_143302).");;
          }
          exit("Errore durante l'inserimento del documento: ".$curDichiarazione." nel file temporaneo (AA_ERR_143303).");;
        }
        $num_files++;
    }
  }

  $zip->close();

  if($num_files > 0)
  {
    header("Cache-control: private");
    header("Content-type: application/octet-stream");
    header("Content-Length: ".filesize($zip_path));
    header('Content-Disposition: attachment; filename="dichiarazione.zip"');
    $filename = $zip_path;
    $fd = fopen ($filename, "rb");
    echo fread ($fd, filesize ($filename));
    fclose ($fd);
  }

  if(!unlink($zip_path))
  {
    error_log("Art14_1d_1e_Download() - errore durante la rimozione del file compresso: ".$zip_path." - nessun curriculum trovato.");
    exit("Art14_1d_1e_Download() - Nessun documento trovato.");
  }

	exit();
}

//Notifica il dirigente sullo stato delle pubblicazioni sul curriculum
function Art14_1d_1e_NotifyDir($mail="",$anno="",$struttura="",$type="",$oggetto="",$corpo="",$firma="")
{
    if($mail=="") return false;

    if($anno=="") $anno=date("Y");
    if($struttura=="") $struttura="struttura non indicata";
    if($oggetto=="") $oggetto="Amministrazione Aperta - Notifica a seguito di controllo automatizzato";

    //Non conforme
    if($corpo=="" && $type >=2) $corpo='<p>Buongiorno,
    A seguito di un controllo automatizzato risulta che il curriculum, per l\'anno '.$anno.', rilasciato ai sensi dell\'art.14 comma 1b d.lgs.33/2013, per l\'incarico di direttore della struttura:
    '.$struttura.'

    Caricata sulla piattaforma "Amministrazione Aperta", non è conforme alle seguenti specifiche di pubblicazione:
    1) Il file, in formato pdf, deve essere firmato digitalmente in modalità Pades.

    Occorre, pertanto, procedere alla sostituzione, sulla piattaforma "Amministrazione Aperta", del curriculum non conforme con un nuovo curriculum conforme alle specifiche sopra indicate.

    Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>';

    //Non caricato
    if($corpo=="" && $type == 0) $corpo='<p>Buongiorno,
    A seguito di un controllo automatizzato non risulta caricato il curriculum, per l\'anno '.$anno.', rilasciato ai sensi dell\'art.14 comma 1b d.lgs.33/2013, per l\'incarico di direttore della struttura:
    '.$struttura.'

    Occorre, pertanto, procedere al caricamento, sulla piattaforma "Amministrazione Aperta", del curriculum.

    Si ricorda che il curriculum va caricato in formato pdf firmate digitalmente in modalità Pades.
        
    Si chiede di segnalare se questo messaggio si riferisce ad un incarico cessato o non corretto in modo che vengano aggiornati gli archivi e non venga più inviato.

    Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>';

    //conformi
    if($corpo=="" && $type == 1) $corpo='<p>Buongiorno,
    A seguito di un controllo automatizzato si notifica che il curriculum, per l\'anno '.$anno.', rilasciato ai sensi dell\'art.14, comma 1b d.lgs.33/2013, per l\'incarico di direttore della struttura:
    '.$struttura.'

    risultano caricate in piattaforma e conformi alle specifiche di pubblicazione sul sito istituzionale.

    Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>';

    if($firma=="") $firma='<div>--
                <div><strong>Amministrazione Aperta</strong></div>
                <div>Presidentzia</div>
                <div>Presidenza</div>
                <div>Ufficio del Responsabile della prevenzione della corruzione e della trasparenza</div>
                <div>V.le Trento, 69 - 09123 Cagliari</div>
                <img src="https:///sitod.regione.sardegna.it/web/logo.jpg" data-mce-src=https:////sitod.regione.sardegna.it/web/logo.jpg" moz-do-not-send="true" width="205" height="60"></div>';

    return SendMail(array($mail), array(), $oggetto,nl2br($corpo).$firma,array(),1);
}

//Pubblicazioni art. 14, comma 1ter d.lgs. 33/3013 - download
function Art14_1ter_Download($email,$anno,$uid)
{
  //Percorso
  $path = "/home/sitod/uploads/amministrazione_trasparente/art14/1bis-dirigenti/1ter/".$anno."/";
  $dichiarazioni=scandir($path);

  $zip = new ZipArchive();
  $zip_path="/tmp/".uniqid().".zip";
  $num_files=0;
  if ($zip->open($zip_path, ZipArchive::CREATE)!==TRUE)
  {
    error_log("Art14_1ter_Download() - errore durante la creazione del file zip temporaneo:".$zip_path);
    exit("Errore durante la generazione del file temporaneo (AA_ERR_143301).");
  }

  //$zip->addFromString("info.txt" . time(), "Questo file contiene le dichiarazio");
  
  //Cerca per email
  if($email !="" && $uid == "")
  {
    $db=new Database();
    $db->Query("SELECT GROUP_CONCAT(id) as ids FROM `utenti` WHERE email like '".$email."'");
    $rs=$db->GetRecordSet();

    $ids="";

    if($rs->GetCount() > 0)
    {
      $ids=explode(",",$rs->Get('ids'));
    }
    else 
    {
      error_log("Art14_1ter_Download() - errore durante la rimozione del file compresso: ".$zip_path." - nessun documento trovato.");
      exit("Art14_1ter_Download() - Nessun documento trovato per l'email: ".$email);
    }
  }
  
  foreach($dichiarazioni as $curDichiarazione)
  {
    $include=false;

    if($ids != "")
    {
      foreach($ids as $curID)
      {
        if(strpos($curDichiarazione,$curID."_") === 0 && substr($curDichiarazione, -3) == "pdf")
        {
            if(!$zip->addFile($path.$curDichiarazione, $curDichiarazione))
            {
              error_log("Art14_1ter_Download() - errore durante l'inserimento del file: ".$curDichiarazione." - nel file compresso: ".$zip_path);
              $zip->close();
              if(!unlink($zip_path))
              {
                error_log("Art14_1ter_Download() - errore durante la rimozione del file compresso: ".$zip_path);
                exit("Errore durante la rimozione del file temporaneo (AA_ERR_143302).");;
              }
              exit("Errore durante l'inserimento del documento: ".$curDichiarazione." nel file temporaneo (AA_ERR_143303).");;
            }
            $num_files++;
        }        
      }
    }

    if(strpos($curDichiarazione,$uid."_") === 0 && substr($curDichiarazione, -3) == "pdf" && $ids == "")
    {
        if(!$zip->addFile($path.$curDichiarazione, $curDichiarazione))
        {
          error_log("Art14_1ter_Download() - errore durante l'inserimento del file: ".$curDichiarazione." - nel file compresso: ".$zip_path);
          $zip->close();
          if(!unlink($zip_path))
          {
            error_log("Art14_1ter_Download() - errore durante la rimozione del file compresso: ".$zip_path);
            exit("Errore durante la rimozione del file temporaneo (AA_ERR_143302).");;
          }
          exit("Errore durante l'inserimento del documento: ".$curDichiarazione." nel file temporaneo (AA_ERR_143303).");;
        }
        $num_files++;
    }
  }

  $zip->close();

  if($num_files > 0)
  {
    header("Cache-control: private");
    header("Content-type: application/octet-stream");
    header("Content-Length: ".filesize($zip_path));
    header('Content-Disposition: attachment; filename="dichiarazione.zip"');
    $filename = $zip_path;
    $fd = fopen ($filename, "rb");
    echo fread ($fd, filesize ($filename));
    fclose ($fd);
  }

  if(!unlink($zip_path))
  {
    error_log("Art14_1ter_Download() - errore durante la rimozione del file compresso: ".$zip_path." - nessun curriculum trovato.");
    exit("Art14_1ter_Download() - Nessun documento trovato.");
  }

	exit();
}
?>