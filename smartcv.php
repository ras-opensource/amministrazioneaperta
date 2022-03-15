<?php
session_start();
include_once "utils/system_lib.php";
include_once "utils/incarichi_lib.php";

$EmailOTP_sended=false;
$EmailOTP_verified=false;
$EmailOTP_error="";

//utente per l'aggiornamento
$AA_UserUpdater=AA_User::GetCurrentUser();
if($AA_UserUpdater->GetUsername() != "smartcv_ras" || !$AA_UserUpdater->IsValid()) $AA_UserUpdater=AA_User::UserAuth('',"smartcv_ras",md5("Ab20210209"));
if(!$AA_UserUpdater->IsValid())
{
  AA_Log::Log('Errore di autenticazione utente smartcv - '.AA_Log::$lastErrorLog,100,false,true);
  die('Errore di sistema nell\'interfacciamento alla piattaforma "Amministrazione Aperta" - autenticazione fallita - inviare una segnalazione alla casella: amministrazioneaperta@regione.sardegna.it');
}

$task=$_REQUEST['task'];

//resetta la sessione
if($task=="reset-session")
{
  unset($_SESSION["MailOTP-user"]);
  unset($_SESSION["MailOTP-nome"]);
  unset($_SESSION["MailOTP-cognome"]);
  unset($_SESSION["MailOTP-email"]);
  
  session_destroy();

  die("Sessione eliminata");
}

//Verifica email
if($task=="send")
{
  $email=$_REQUEST['email'];

  if(AA_User::MailOTPAuthSend($email))
  {
    $EmailOTP_sended=true;
    die("1");
    $EmailOTP_error="";
  }
  else
  {
    die("<div status='-1'>".AA_Log::$lastErrorLog."</div>");
  }
}

//Verifica codice
if($task=="verify")
{
  $code=$_REQUEST['code'];

  if(AA_User::MailOTPAuthVerify($code))
  {
    $EmailOTP_verified=true;

    $nome=$_SESSION['MailOTP-nome'];
    $cognome=$_SESSION['MailOTP-cognome'];
    $email=$_SESSION['MailOTP-email'];
    $aggiornamento=$_SESSION['MailOTP-aggiornamento'];
    $path="/home/sitod/uploads/amministrazione_trasparente/art14/curriculum/";
    $curriculum_file=md5($email).".pdf";
    if(file_exists($path.$curriculum_file))
    {
      $curriculum="<a href='https://sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/curriculum/?email=".$mail."&po=1'>presente</a>";
    }
    else
    {
      $curriculum="non ancora caricato.";
    }
  
    $titolare=AA_IncarichiTitolare::LoadFromEmail($email);
    if($titolare instanceof AA_IncarichiTitolare)
    {
      $nome=$titolare->GetNome();
      $cognome=$titolare->GetCognome();
      $email=$titolare->GetEmail();
      $aggiornamento=$titolare->GetAggiornamento();
      $last_incarico=$titolare->GetLastIncarico("",AA_Incarichi_Const::AA_INCARICO_POSIZIONE_ORGANIZZATIVA,null,null,0,0,0,$AA_UserUpdater->GetStruct()->GetTipo());
      $struttura="";
      if($last_incarico)
      {
        $tipo_incarico=$last_incarico->GetTipo();
        $struct=$last_incarico->GetStruct();
        if($struct->GetServizio(true) > 0) $struttura=$struct->GetServizio();
        else if($struct->GetDirezione(true) > 0) $struttura=$struct->GetDirezione();
        else if($struct->GetAssessorato(true) > 0) $struttura=$struct->GetAssessorato();
      }

      $return.="<nome>".$nome."</nome>";
      $return.="<cognome>".$cognome."</cognome>";
      $return.="<email>".$email."</email>";
      $return.="<aggiornamento>".$aggiornamento."</aggiornamento>";
      $curriculum=$titolare->GetLastCurriculum();
      if($curriculum!="")
      {
        $return.="<curriculum status='1'>$curriculum</curriculum>";
      }
      else
      {
        $return.="<curriculum status='0'>$curriculum</curriculum>";
      }
    }
    else
    {
      $return.="<nome>".$nome."</nome>";
      $return.="<cognome>".$cognome."</cognome>";
      $return.="<email>".$email."</email>";
      $return.="<aggiornamento>".$aggiornamento."</aggiornamento>";
      if(file_exists($path.$curriculum_file))
      {
        $return.="<curriculum status='1'>$curriculum_file</curriculum>";
      }
      else
      {
        $return.="<curriculum status='0'>$curriculum_file</curriculum>";
      }  
    }

    if($nome=="") $return.="<div status='2'>Verifica avvenuta con successo.</div>";
    else $return.="<div status='3'>Verifica avvenuta con successo.</div>";

    die($return);
  }
  else
  {
    die("<div status='-1'>".AA_Log::$lastErrorLog."</div>");
  }
}

//Aggiornamento dei dati
if($task=="update")
{
  $id_user=$_SESSION['MailOTP-user'];

  $error="";

  if($id_user == "")
  {
    die("<div status='0'>Sessione scaduta o non valida.</div>");
  }

  //Verifica dei dati
  if(trim($_REQUEST['nome']) =="")
  {
    $error.="<br/>Occorre inserire il nome.";
  }

  if(trim($_REQUEST['cognome']) == "")
  {
    $error.="<br/>Occorre inserire il cognome.";
  }

  if($_REQUEST['nome'] =="")
  {
    $error.="<br/>Occorre inserire il nome.";
  }

  if($_FILES['curriculum']['name'] !="")
  {
    if(strpos($_FILES['curriculum']['type'],"pdf") === false)
    {
      $error.="<br/>Il curriculum deve essere in formato pdf, firmato digitalmente in modalità Pades.";
    }
  
    if($_FILES['curriculum']['size'] > (2048*1024))
    {
      $error.="<br/>La dimensione del curriculum non può superare i 2 megabyte.";
    }  
  }

  if($_REQUEST['tipo_incarico']==0)  $error.="<br/>Occorre indicare il tipo di incarico.";
  if($_REQUEST['id_direzione']==0)  $error.="<br/>Occorre indicare almeno la direzione (possibilmente anche il servizio) presso il quale si svolge l'incarico";
  
  if($error !="")
  {
    die("<div status='-1'>".$error."</div>");
  }
  //--fine verifica--

  //Aggiorna i dati
  $nome=$_REQUEST['nome'];
  $cognome=$_REQUEST['cognome'];
  $email=$_SESSION['MailOTP-email'];
  $aggiornamento=Date("d/m/Y");
  $tipo_incarico=$_REQUEST['tipo_incarico'];
  $struct = AA_Struct::GetStruct($_REQUEST['id_assessorato'],$_REQUEST['id_direzione'],$_REQUEST['id_servizio']);
  
  //Verifica che la struttura selezionata sia visibile all'utente di interfaccia
  $userStruct=$AA_UserUpdater->GetStruct();
  if($userStruct->GetTipo() != $struct->GetTipo())
  {
    $error.="<br/>Struttura selezionata non disponibile.";
    die("<div status='-1'>".$error."</div>");
  }

  $_SESSION['MailOTP-aggiornamento']=$aggiornamento;
  
  //Carica il titolare
  $titolare=AA_IncarichiTitolare::LoadFromEmail($email);
  if($titolare == null)
  {
    //Istanzia un nuovo titolare
    $params['nome']=$nome;
    $params['cognome']=$cognome;
    $params['email']=$email;
    if(!AA_IncarichiOps::AddNewTitolare($params,$AA_UserUpdater))
    {
       die("<div status='-1'>".AA_IncarichiOps::lastError('AddNewTitolare')."</div>");
    }
    else $titolare=AA_IncarichiTitolare::LoadFromEmail($email);

    //Publica il titolare
    if(!AA_IncarichiOps::Publish($titolare,$AA_UserUpdater))
    {
       die("<div status='-1'>".AA_IncarichiOps::lastError('Publish')."</div>");
    }
  }
  
  //Aggiorna il nome e il cognome
  if($nome != $titolare->GetNome() || $cognome != $titolare->GetCognome())
  {
    $params["id"]=$titolare->GetID();
    $params['nome']=$nome;
    $params['cognome']=$cognome;
    if(!AA_IncarichiOps::UpdateTitolare($params,$AA_UserUpdater))
    {
       die("<div status='-1'>".AA_IncarichiOps::lastError('UpdateTitolare')."</div>");
    }
    else $titolare=AA_IncarichiTitolare::LoadFromEmail($email);
  }

  //Verifica se ci sono altri incarichi di posizione organizzativa per l'anno in corso
  $incarico = $titolare->GetLastIncarico(date("Y"),AA_Incarichi_Const::AA_INCARICO_POSIZIONE_ORGANIZZATIVA,null,null,0,0,0,$AA_UserUpdater->GetStruct()->GetTipo());
  if($incarico)
  {
    //Incarico diverso rispetto all'incarico precedente
    //Aggiorno la data di fine dell'incarico precedente se è maggiore della data di inizio dell'incarico attuale
    $struct_incarico=$incarico->GetStruct();
    if(($incarico->GetTipo(true) != $_REQUEST['tipo_incarico'] || $struct_incarico->GetAssessorato(true) != $_REQUEST['id_assessorato'] || $struct_incarico->GetDirezione(true) != $_REQUEST['id_direzione'] || $struct_incarico->GetServizio(true) != $_REQUEST['id_servizio']) && $incarico->GetDataFine() > $_REQUEST['incarico-data-inizio']) 
    {
      $params['id-incarico']=$incarico->GetID();
      $params['incarico-data-fine']=date("Y-m-d");
      if(!AA_IncarichiOps::UpdateIncarico($params,$AA_UserUpdater))
      {
        die("<div status='-1'>".AA_IncarichiOps::lastError('UpdateIncarico')."</div>");
      } 
    }
  }

  //Recupera gli incarichi di posizione organizzativa dello stesso tipo nell'anno in corso
  $incarico = $titolare->GetLastIncarico(date("Y"),$_REQUEST['tipo_incarico'],null,null,$_REQUEST['id_assessorato'],$_REQUEST['id_direzione'],$_REQUEST['id_servizio'],$AA_UserUpdater->GetStruct()->GetTipo());
  if($incarico == null)
  {
    //Aggiungo l'incarico
    AA_Log::Log("Aggiungo l'incarico perchè non è stato trovato.",100,false,true);
    $params['incarico-id-titolare']=$titolare->GetID();
    $params['incarico-tipo']=$_REQUEST['tipo_incarico'];
    $params['incarico-id-assessorato']=$_REQUEST['id_assessorato'];
    $params['incarico-id-direzione']=$_REQUEST['id_direzione'];
    $params['incarico-id-servizio']=$_REQUEST['id_servizio'];
    $params['incarico-data-inizio']=date("Y-m-d");
    $params['incarico-data-fine']=date("Y-12-31");
    if(!AA_IncarichiOps::AddNewIncarico($params,$AA_UserUpdater))
    {
      die("<div status='-1'>".AA_IncarichiOps::lastError('AddNewIncarico')."</div>");
    }
  }

  /*
  $db=new Database();
  $query="UPDATE email_login set aggiornamento=NOW(), nome='".$nome."', cognome='".$cognome."' WHERE id='".$id_user."' LIMIT 1";
  if(!$db->Query($query))
  {
    $error.="<div status='-1'>Errore nel salvataggio dei dati.</div>";
    error_log("email_login (update) - errore: ".$db->lastError." nella query: ".$query);
    die($error);
  }*/

  //gestione curriculum
  if($_FILES['curriculum']['name'] !="")
  {
    /*if(file_exists($path.$curriculum_file))
    {
      //Rimuovi il vecchio (vecchia procedura)
      $curriculum_file=md5($_SESSION['MailOTP-email']).".pdf"; //vecchia procedura
      $path="/home/sitod/uploads/amministrazione_trasparente/art14/curriculum/";
      if(!unlink($path.$curriculum_file))
      {
        $error.="<div status='-1'>Errore durante l'aggiornamento del curriculum sul server (1).</div>";
        die($error);
      }
    }*/

    //Salva il nuovo
    if(!AA_IncarichiOps::UploadCurriculumTitolare($titolare,date("Y"),$_FILES['curriculum']))
    {
      $error.="<div status='-1'>Errore durante l'aggiornamento del curriculum sul server (2).</div>";
      die($error);
    }  
  }

  $_SESSION['MailOTP-nome']=$nome;
  $_SESSION['MailOTP-cognome']=$cognome;
  $return="<div status='1'>Aggiornamento effettuato con successo.</div>";
  $return.="<nome>".$nome."</nome>";
  $return.="<cognome>".$cognome."</cognome>";
  $return.="<email>".$email."</email>";
  $return.="<aggiornamento>".$aggiornamento."</aggiornamento>";
  
  $curriculum=$titolare->GetLastCurriculum();

  if($curriculum!="")
  {
    $return.="<curriculum status='1'>$curriculum</curriculum>";
  }
  else
  {
    $return.="<curriculum status='0'>$curriculum</curriculum>";
  }

  die($return);
}

//Eliminazione profilo
if($task=="delete")
{
  $id_user=$_SESSION['MailOTP-user'];

  $error="";

  if($id_user == "")
  {
    die("<div status='0'>Sessione scaduta o non valida.</div>");
  }

  //gestione curriculum
  $curriculum_file=md5($_SESSION['MailOTP-email']).".pdf";
  $path="/home/sitod/uploads/amministrazione_trasparente/art14/curriculum/";
  if(file_exists($path.$curriculum_file))
  {
    //Rimuovi il curriculum
    if(!unlink($path.$curriculum_file))
    {
      $error.="<div status='-1'>Errore durante l'eliminazione del curriculum sul server (1).</div>";
      die($error);
    }
  }

  $db=new Database();
  $query="DELETE FROM email_login WHERE id='".$id_user."' LIMIT 1";
  if(!$db->Query($query))
  {
    $error.="<div status='-1'>Errore nell'eliminazione del profilo (db).</div>";
    error_log("email_login (delete) - errore: ".$db->lastError." nella query: ".$query);
    die($error);
  }

  unset($_SESSION["MailOTP-user"]);
  unset($_SESSION["MailOTP-nome"]);
  unset($_SESSION["MailOTP-cognome"]);
  unset($_SESSION["MailOTP-email"]);
  unset($_SESSION["MailOTP-aggiornamento"]);

  session_destroy();
  die("1");
}

if($task !="")
{
  die("task ($task) non ancora gestito.");
}

if(isset($_SESSION['MailOTP-user']) && $_SESSION['MailOTP-user'] > 0)
{
  $curpage="third-page";
  if($_SESSION['MailOTP-nome']=="") $curpage="second-page";

  //Carica il titolare dalla gestione incarichi
  $titolare=AA_IncarichiTitolare::LoadFromEmail($_SESSION['MailOTP-email']);
  if($titolare instanceof AA_IncarichiTitolare)
  {
    $nome=$titolare->GetNome();
    $cognome=$titolare->GetCognome();
    $email=$titolare->GetEmail();
    $aggiornamento=$titolare->GetAggiornamento();
    $last_incarico=$titolare->GetLastIncarico("",AA_Incarichi_Const::AA_INCARICO_POSIZIONE_ORGANIZZATIVA,null,null,0,0,0,$AA_UserUpdater->GetStruct()->GetTipo());
    $struttura="";
    if($last_incarico)
    {
      $tipo_incarico=$last_incarico->GetTipo();
      $struct=$last_incarico->GetStruct();
      if($struct->GetServizio(true) > 0) $struttura=$struct->GetServizio();
      else if($struct->GetDirezione(true) > 0) $struttura=$struct->GetDirezione();
      else if($struct->GetAssessorato(true) > 0) $struttura=$struct->GetAssessorato();
    }
  }
  else
  {
    $nome=$_SESSION['MailOTP-nome'];
    $cognome=$_SESSION['MailOTP-cognome'];
    $email=$_SESSION['MailOTP-email'];
    $aggiornamento=$_SESSION['MailOTP-aggiornamento'];
  }
  
  $path="/home/sitod/uploads/amministrazione_trasparente/art14/curriculum/";
  $curriculum_file=md5($email).".pdf";
  if(file_exists($path.$curriculum_file))
  {
    $curriculum="<a href='https://sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/curriculum/?email=".$email."&po=1' title='Fai click per scaricare il curriculum'><img src='immagini/icon-pdf.png' class='aa-icon-pdf' alt='scarica il curriculum'/></a>";
  }
  else
  {
    $curriculum="non ancora caricato.";
  }
}
else $curpage="first-page";

?>
<html>
<head>
<title>Amministrazione Aperta</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<link href="/web/amministrazione_aperta/stili/menu.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/web/amministrazione_aperta/stili/jstree/default/style.min.css" />
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="/web/amministrazione_aperta/utils/jstree.min.js"></script>
<script src="/web/amministrazione_aperta/utils/jquery.fileupload.js"></script>
<script src="/web/amministrazione_aperta/utils/system_lib.js"></script>
<script src="/web/amministrazione_aperta/utils/smartcv_lib.js"></script>
<link href="/web/amministrazione_aperta/stili/email_login.css" rel="stylesheet" type="text/css" />
<script>
  var error="<?php echo $EmailOTP_error;?>";
  var page="<?php echo $curpage;?>";
</script>
</head>

<body>
  <div id="top" style="margin-left:8%; top:5px; width:84%; z-index:6; visibility: visible;">
      <table width="100%">
      <tr>
        <td colspan="3" style="text-align: center"><a href="https://www.regione.sardegna.it"><img src="https://www.regione.sardegna.it/immagini/1_240_20120705162029.gif" border="0" title="www.regione.sardegna.it" alt="www.regione.sardegna.it"></a></td>
      </tr>
      <tr>
        <td width="20%">&nbsp;</td>
        <td width="60%" style="text-align: center"><img src="immagini/titolo.jpg" width="650" height="60"></td>
        <td width="20%">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" style="text-align: center"><span style="font-size: 28px;">Smart <span style="color: #ff1111">CV</span></span></td>
      </tr>
    </table>
  </div>
  <div id="dialog-msg" title="Messaggio">
    <p id="msg-content"><?php echo $EmailOTP_error;?></p>
  </div>
<div id="first-page">
  <div id="main-message" style="text-align: center; margin-left: 20%; width: 60%; z-index:6; padding: 0.2em;">
  <p>
    Benvenuta/o,
    <p>
      Questo applicativo, destinato ai titolari di posizione organizzativa (incarichi di responsabile di settore, incarichi di alta professionalità e incarichi di studio e ricerca), ti consentirà di pubblicare il tuo curriculum  per l'espletamento degli obblighi normativi in materia di trasparenza.
    </p>
    <p>
      Ti invitiamo a leggere l'<a href="https://www.regione.sardegna.it/documenti/1_5_20181115131046.pdf" target="_blank">informativa sulla privacy e protezione dei dati personali</a>.
    </p>
    </p>
      <a href="docs/smartcv.pdf" target="_blank">Fai click qui per scaricare un breve vademecum.</a>
    </p>
      Per iniziare inserisci la tua email nella casella sottostante e fai click sul pulsante "Accedi".
    </p>
  </div>
  <div id="Layer1" style="margin-left:40%; margin-top: 6%; width:20%; z-index:1" class="ui-widget-content ui-corner-all">
    <form id="login" name="login" method="post" action="">
      <table width="100%" border="0" cellspacing="5" cellpadding="5">
        <tr>
        <td colspan="2" align="center">
          <p style="font-weight: bold;text-align: center; font-variant: small-caps">autenticazione</p>
          <hr style=" border: 1px solid gray;"/>
        </td>
        </tr>
        <tr>
          <td style="font-weight:bold">email</font></td>
          <td><input name="email" type="text" id="email">
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center"><input type="submit" name="submit" value="Accedi">
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
  <div id="third-page" style="text-align: center; text-align: center; margin-left: 20%; width: 60%; z-index:6; padding: 0.2em;">
      <p>Benvenuta/o</p>
      <p>
        Di seguito sono indicate le informazioni che hai già inserito (aggiornate in data: <span id="aggiornamento_text"><?php echo $aggiornamento?></span>):
        <p>
          <hr />
          <div style="display: flex; width:100%">
            <div style="width: 50%; text-align: center;">
              <ul style="text-align:left;">
                <li><span style="font-weight: bold">Nome</span>: <span id="nome_text"><?php echo $nome;?></span></li>
                <li><span style="font-weight: bold">Cognome</span>: <span id="cognome_text"><?php echo $cognome;?></span></li>
                <?php if($titolare == null) {?>
                <li style="margin-top: .3em"><span style="font-weight: bold">Curriculum</span>: <span id="curriculum_link"><?php echo $curriculum?></span></li>
                <li style="margin-top: .3em"><span style="font-weight: bold">E' necessario un aggiornamento del curriculum, fai click sul pulsante di modifica per aggiornare i dati e caricare il curriculum aggiornato.</li>
                <?php } else
                {
                  //curriculum
                  $curriculum=$titolare->GetLastCurriculum();
                  if($curriculum !="") $curriculum='<a href="'.$curriculum.'">Fai click qui per scaricare il curriculum.<img src="immagini/icon-pdf.png" class="aa-icon-pdf" alt="scarica il curriculum"/></a>';
                  else $curriculum="non ancora caricato.";
                  echo '<li style="margin-top: .3em"><span style="font-weight: bold">Curriculum</span>: <span id="curriculum_link">'.$curriculum.'</span></li>';
                  if($last_incarico)
                  {
                    echo '<li style="margin-top: .3em"><span style="font-weight: bold">Tipo incarico</span>: <span id="tipo_incarico_desc">'.$tipo_incarico.'</span></li>';
                    echo '<li style="margin-top: .3em"><span style="font-weight: bold">Struttura</span>: <span id="incarico_struttura_desc">'.$struttura.'</span></li>';  
                  }
                  else
                  {
                    echo '<li style="margin-top: .3em"><span style="font-weight: bold">Tipo incarico</span>: <span id="tipo_incarico_desc">da indicare.</span></li>';
                    echo '<li style="margin-top: .3em"><span style="font-weight: bold">Struttura</span>: <span id="incarico_struttura_desc">da indicare.</span></li>';  
                  }
                }?>
              </ul>
            </div>
            <div style="width: 50%; text-align: center;" >
              <a href='#' id='modifica' title="Fai click per modificare i dati."><img src='immagini/icon-modify.png' class='aa-icon-modify' alt='modifica'/> Modifica</a>
            </div>
          </div>
          <hr />
          <div style="text-align: left;padding: 1em;" class="ui-widget-content ui-corner-all">
            <p>
              Il curriculum deve essere in formato pdf, non scansionato, di dimensioni inferiori a 2 Mbyte e firmato digitalmente in modalità Pades.<br/>
              <br/>Puoi scaricare il modello del curriculum <a href="docs/modello_curriculum2022.odt" target="_blank" title="Fai click per scaricare il modello compilabile del curriculum">cliccando qui <img src="immagini/icon-download.png" class="aa-icon-download" alt="scarica il modello di curriculum"/></a>
            </p>
          </div>
          <div style="text-align: left;padding: .8em;margin-top: 1em;" class="ui-widget-content ui-corner-all">
            <p style="text-align: center;margin-bottom: 1.5em; font-weight: bold;" class=""><img src="immagini/icon-alert.png" class="aa-icon-alert" alt="Attenzione!" />Attenzione alla pubblicazione dei dati personali!</p>
            <p>
              Per una corretta pubblicazione il curriculum vitae deve contenere solo i <b>dati personali minimi</b> (nome, cognome e anno di nascita), le informazioni relative ai titoli di studio e professionali e alle esperienze lavorative.
            </p>
            <p>
              E' necessario <b>omettere tutti gli altri dati personali eccedenti e non pertinenti rispetto allo scopo della pubblicazione</b> (città di nascita, data di nascita completa,  indirizzo di residenza , codice fiscale, numero telefonico, indirizzo mail, foto-tessera, firma autografa, etc.).
            </p>
            <p>
              In nessun caso sul CV dovranno essere esposti dati attinenti alla salute fisica o mentale, all’appartenenza politica e sindacale, all’orientamento sessuale, alle convinzioni filosofiche, allo stato di famiglia, a interessi personali o similari o ad ogni altro dato personale non pertinente allo scopo della pubblicazione.
            </p>
            </p>
              <a href="docs/smartcv.pdf" target="_blank">Fai click qui per scaricare un breve vademecum.</a>
            </p>
          </div>
        </p>
      </p>
  </div>
  <div id="second-page" style="text-align: center; text-align: center; margin-left: 20%; width: 60%; z-index:6; padding: 0.2em;">
      <p>Benvenuta/o</p>
      <p>Questa è la prima volta che accedi alla piattaforma o non hai ancora inserito i dati del tuo profilo,<br/> fai click <a href='#' id='inserisci'>qui <img src='immagini/icon-modify.png' class='aa-icon-modify' alt='modifica'/></a> per inserire i tuoi dati.</p>
      <div style="text-align: left;padding: 1em;" class="ui-widget-content ui-corner-all">
          <p>
            Il curriculum deve essere in formato pdf, non scansionato, di dimensioni inferiori a 2 Mbyte e firmato digitalmente in modalità Pades.<br/>
            <br/>Puoi scaricare il modello del curriculum <a href="docs/modello_curriculum2022.odt" target="_blank" title="Fai click per scaricare il modello compilabile del curriculum">cliccando qui <img src="immagini/icon-download.png" class="aa-icon-download" alt="scarica il modello di curriculum"/></a>
          </p>
          </div>
          <div style="text-align: left;padding: .8em;margin-top: 1em;" class="ui-widget-content ui-corner-all">
          <p style="text-align: center;margin-bottom: 1.5em; font-weight: bold;" class=""><img src="immagini/icon-alert.png" class="aa-icon-alert" alt="Attenzione!" />Attenzione alla pubblicazione dei dati personali!</p>
          <p>
            Per una corretta pubblicazione il curriculum vitae deve contenere solo i <b>dati personali minimi</b> (nome, cognome e anno di nascita), le informazioni relative ai titoli di studio e professionali e alle esperienze lavorative.
          </p>
          <p>
            E' necessario <b>omettere tutti gli altri dati personali eccedenti e non pertinenti rispetto allo scopo della pubblicazione</b> (città di nascita, data di nascita completa,  indirizzo di residenza , codice fiscale, numero telefonico, indirizzo mail, foto-tessera, firma autografa, etc.).
          </p>
          <p>
            In nessun caso sul CV dovranno essere esposti dati attinenti alla salute fisica o mentale, all’appartenenza politica e sindacale, all’orientamento sessuale, alle convinzioni filosofiche, allo stato di famiglia, a interessi personali o similari o ad ogni altro dato personale non pertinente allo scopo della pubblicazione.
          </p>
          </p>
            <a href="docs/smartcv.pdf" target="_blank">Fai click qui per scaricare un breve vademecum.</a>
          </p>
      </div>
  </div>

<!-- Dialogo conferma codice -->
<div id="dialog-confirm-code">
    <form id="form-confirm" name="form-confirm" method="post" action="">
      <table width="100%" border="0" cellspacing="5" cellpadding="5">
      <tr>
        <td colspan="2" align="center">
          <p style="text-align: left;">E' stato inviato un codice di verifica alla casella email indicata, inserisci nel campo sottostante il codice ricevuto e premi il tasto "Conferma"</p>
          <hr style=" border: 1px solid gray;"/>
        </td>
        </tr>
        <tr>
          <td style="font-weight:bold">codice</td>
          <td><input name="code" type="text" id="code">
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>

<!-- Dialogo modifica/caricamento dati -->
<div id="dialog-update">
    <form id="form-update" name="form-update" method="post" action="" enctype="multipart/form-data">
      <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
      <table width="100%" border="0" cellspacing="5" cellpadding="5">
        <tr>
          <td style="font-weight:bold">Nome</td>
          <td><input name="nome" type="text" id="nome" value="<?php echo $nome;?>">
          </td>
        </tr>
        <tr>
          <td style="font-weight:bold">Cognome</td>
          <td><input name="cognome" type="text" id="cognome" value="<?php echo $cognome;?>"></td>
        </tr>
        <tr>
          <td style="font-weight:bold">Curriculum</td>
          <td><input name="curriculum" type="file" id="curriculum"></td>
        </tr>
        <tr>
          <td style="font-weight:bold">Struttura</td>
          <td><a name="struttura_desc" id="struttura_desc" href="#">Scegli la struttura</a></td>
        </tr>
        <tr>
          <td style="font-weight:bold">Tipologia incarico</td>
          <td><select name="tipo_incarico" id="tipo_incarico">
            <option selected value="0">Scegli il tipo di incarico</option>
            <option value="2">Incarico di alta professionalità</option>
            <option value="4">Incarico di coordinamento di settore</option>
            <option value="8">Incarico di studio e ricerca</option>
          </td>
        </tr>
        <tr>
          <td colspan="2"><input type="hidden" name="id_assessorato" id="id_assessorato" value=0 /><input type="hidden" name="id_direzione" id="id_direzione" value=0 /><input type="hidden" name="id_servizio" id="id_servizio" value=0/></td>
        </tr>
      </table>
    </form>
  </div>
</div>

<!-- Dialogo elimina dati -->
  <div id="dialog-confirm-delete">
    <p>Vuoi procedere all'eliminazione dei tuoi dati caricati e del tuo curriculum?</p>
  </div>
</div>

</body>
</html>
