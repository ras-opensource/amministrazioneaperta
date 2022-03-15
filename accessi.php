<?php session_start();?>
<?php include_once("../../utils/lib_accessi.php");
if(!CheckUser(true,2,0,0,0,0))
{
 header("location: ../../login.php");
 exit;
}
if(!isset($root)) $root="../../";
if(!isset($up)) $up="../";
if(!isset($schede_dir)) $schede_dir="";
?>
<html>
<head>
<title>Registro informatizzato degli accessi civici (art. 5, comma 1 e 2 d.lgs 33/2013) e documentali (legge 241/1990).</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<link href="/web/amministrazione_aperta/stili/menu.css" rel="stylesheet" type="text/css">
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="/web/amministrazione_aperta/utils/accessi_lib.js"></script>
<script src="/web/amministrazione_aperta/utils/menu_lib.js"></script>
<script>
var queryString = "<?php echo $_SERVER['QUERY_STRING'];?>";
var idPub=0;
var oggetto_search="<?php echo $_GET['oggetto_search'];?>";
var stato_pubblicata_search="<?php echo $_GET['stato_pubblicata_search'];?>";
var stato_bozza_search="<?php echo $_GET['stato_bozza_search'];?>";
var stato_cestinata_search="<?php echo $_GET['stato_cestinata_search'];?>";
var stato_revisionata_search="<?php echo $_GET['stato_revisionata_search'];?>";
var tipoaccesso_search="<?php echo $_GET['tipoaccesso_search'];?>";
var accessi_pubblicate_dal="<?php echo $_GET['accessi_pubblicate_dal'];?>";
var accessi_pubblicate_al="<?php echo $_GET['accessi_pubblicate_al'];?>";
var filtra_pubblicate_search="<?php echo $_GET['filtra_pubblicate_search'];?>";
var curPage="<?php echo $_GET['curPage'];?>";
var totalPages=0;
</script>
<style type="text/css">
<!--
.manina {
	cursor: hand;
}

.ui-widget {
font-size: 1em;
}
-->
</style>
<link href="../../stili/menutab.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include "../testata.php"; ?>
<div id="dialog-form-accessi-options" title="Opzioni di visualizzazione">
  <p class="validateTips"> </p>
  <form id="form-accessi-options" name="form-accessi-options">
    <table style="width:100%;">
      <tr>
	<td style="width: 25%;">Opzioni di ordinamento</td>
	<td style="width: 75%;" colspan="3"><input class="text ui-widget-content ui-corner-all" id="order_options" name="order_options" style="width: 96%;"/></td>
      </tr>
    </table>
  </form>
</div>
<div id="dialog-form-accessi-search" title="Opzioni di ricerca">
  <p class="validateTips"> </p>
  <form id="form-accessi-search" name="form-accessi-search">
    <table style="width:100%;">
      <tr>
	<td style="width: 25%;">Oggetto</td>
	<td style="width: 75%;" colspan="3"><input class="text ui-widget-content ui-corner-all" id="oggetto_search" name="oggetto_search" style="width: 96%;"/></td>
      </tr>
      <tr>
	  <td style="width: 25%;">Stato della voce</td>
	  <td style="width: 75%;" colspan="3">
	   <div id="status_set_search">
	    <input type="radio" id="stato_pubblicata_search" class="text ui-widget-content ui-corner-all" name="stato_scheda_search" value="0"/><label for="stato_pubblicata_search">Pubblicata</label>
	    <input type="radio" id="stato_bozza_search" class="text ui-widget-content ui-corner-all" name="stato_scheda_search" value="1"/><label for="stato_bozza_search">Bozza</label>
	    <input type="checkbox" id="stato_cestinata_search" class="text ui-widget-content ui-corner-all" name="stato_cestinata_search"/><label for="stato_cestinata_search">Cestinata</label>
	    <input type="checkbox" id="stato_revisionata_search" class="text ui-widget-content ui-corner-all" name="stato_revisionata_search"/><label for="stato_revisionata_search">Revisionata</label>
	    <br>
	    <input type="checkbox" id="filtra_pubblicate_search" class="text ui-widget-content ui-corner-all" name="filtra_pubblicate_search" value="0"/><label for="filtra_pubblicate_search">visualizza solo le voci della mia struttura</label>
	   </div>
	 </td>
      </tr>
      <tr>
	  <td style="width: 100%;text-align: center;" colspan="4"><hr><strong>Opzioni avanzate</strong><hr></td>	  
      </tr>
      <tr>
	<td style="width: 25%;">Tipo di accesso</td>
	<td style="width: 75%;" colspan="3">
            <select id="tipoaccesso_search" class="text ui-widget-content ui-corner-all" name="tipoaccesso_search" style="width: 96%;">
                <option value="-1" selected="true">Qualunque</option>
                <option value="0">Accesso civico (art.5, comma 1)</option>
                <option value="1">Accesso civico generalizzato (art.5, comma 2)</option>
                <option value="2">Accesso documentale (legge 241/1990)</option>
            </select>
	</td>
      </tr>
      <tr>
	<td style="width: 25%;">Pubblicate dal</td>
	<td style="width: 25%;"><input type="text" id="accessi_pubblicate_dal" class="text ui-widget-content ui-corner-all" name="accessi_pubblicate_dal" style="width: 90%;" value="" /></td>
	<td style="width: 25%;text-align: right;">al</td>
	<td style="width: 25%;"><input type="text" id="accessi_pubblicate_al" class="text ui-widget-content ui-corner-all" name="accessi_pubblicate_al" style="width: 90%;" value="" /></td>
      </tr>
      <tr>
	<td style="width: 25%;">Vai alla pagina</td>
	<td style="width: 75%;" colspan="3"><input id="goPage" class="text ui-widget-content ui-corner-all" name="goPage" style="width: 5em;" value="" /></td>
      </tr> 
    </table>
  </form>
</div>
<div id="dialog-confirm-elimina-pubblicazione" title="Elimina">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><span class="message-delete-pubblicazione"></span></p>
  <input type="hidden" id="idRecDelPub" name="idRecDelPub" value="0"/>
</div>
<div id="dialog-confirm-pubblica" title="Pubblica">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><span class="message-pubblica"></span></p>
  <input type="hidden" id="idRecPub" name="idRecPub" value="0"/>
</div>
<div id="dialog-confirm-resume-pubblicazione" title="Ripristina">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><span class="message-resume-pubblicazione"></span></p>
  <input type="hidden" id="idRecResumePub" name="idRecResumePub" value="0"/>
</div>

<div id="dialog-form-accessi-modify" title="Modifica i dati">
  <p class="validateTips"> </p>
  <form id="datiAccessi">
    <table cellpadding="2" cellspacing="0" style="width:100%">
      <tr>
	<td style="width:20%"><label for="tipo">Tipo accesso</label></td>
	<td style="width:30%">
	  <select name="tipo" id="tipo" style="width:90%;">
	    <?php if(strpos($_SESSION['flags'],"admin_accessi") !== false || $_SESSION['id_user'] == 1) {?> <option value="0">Accesso civico (art.5, c.1, d.lgs.33/2013)</option> <?php } ?>
	    <option value="1">Accesso civ. gen. (art.5, c.2, d.lgs.33/2013)</option>
	    <option value="2">Accesso documentale (legge 241/1990)</option>
	  </select>
	</td>
	<td style="width:20%"><label for="richiedente">Richiedente</label></td>
	<td style="width:30%">
	  <select name="richiedente" id="richiedente" style="width:90%;">
	    <option value="4">Associazione sindacale</option>
	    <option value="0" selected>Cittadino</option>
	    <option value="1">Esponente politico</option>
	    <option value="2">Ente pubblico</option>
	    <option value="3">Società o Ente privato</option>
	    <option value="5">Studio legale</option>
	  </select>
	</td>
      </tr>
      <tr>
      	<td style="width:20%"><label for="data_presentazione" id="label_data_presentazione">Data di presentazione</label></td>
	<td style="width:30%"><input type="text" name="data_presentazione" id="data_presentazione" class="text ui-widget-content ui-corner-all" /></td>
	<td style="width:20%"><label for="esito">Esito</label></td>
	<td style="width:30%">
            <select id="esito" name="esito" style="width:90%;">
                <option value="0">Accoglimento</option>
                <option value="3">Differimento</option>
                <option value="1">Rifiuto parziale</option>
                <option value="2">Rifiuto totale</option>
            </select>
        </td>
      </tr>
      <tr>
      	<td style="width:20%"><label for="data_provvedimento" id="label_data_conclusione">Data conclusione</label></td>
	<td style="width:30%"><input type="text" name="data_provvedimento" id="data_provvedimento" class="text ui-widget-content ui-corner-all" /></td>
        <td style="width:20%"><label for="motivo_rifiuto" id="label_motivo_rifiuto">Motivazione</label></td>
	<td style="width:30%"><textarea name="motivo_rifiuto" id="motivo_rifiuto" style="width:89%;" rows="2" class="text ui-widget-content ui-corner-all"></textarea></td>
      </tr>
      <tr>
        <td style="width:20%">&nbsp;</td>
        <td style="width:30%"><div id="controinteressati_box"><input type="checkbox" name="controinteressati" id="controinteressati" class="text ui-widget-content ui-corner-all" value=""><label for="controinteressati" id="label_controinteressati">Controinteressati</label></div><div id="differimento_box"><input type="checkbox" name="differimento" id="differimento" class="text ui-widget-content ui-corner-all" value=""><label for="differimento" id="label_differimento">Differimento</label></div>
        </td>
        <td style="width:20%">&nbsp;</td>
        <td style="width:30%">&nbsp;</td>
      </tr>
      <tr>
	<td><label for="oggetto">Oggetto</label></td>
	<td colspan="3"><textarea name="oggetto" id="oggetto" style="width:96%;" rows="3" class="text ui-widget-content ui-corner-all"></textarea><br/>nell'oggetto non vanno indicati dati personali ma solo informazioni che siano utili a comprendere l'oggetto della richiesta.</td>
      </tr>
      <tr>
        <td colspan="4" style="text-align: center">
        <hr />
        <input type="checkbox" name="ricorso" id="ricorso" class="text ui-widget-content ui-corner-all" value=""><strong><label for="ricorso" id="label_ricorso"> Ricorso</label></strong>
            <div id="ricorso_block" style="width:100%; margin-top: 2em;">
                <table style="width:100%">
                    <tr>
                        <td style="width:20%"><label for="ricorso_data_comunicazione" id="label_ricorso_data_comunicazione">Data di comunicazione</label></td>
                        <td style="width:30%"><input type="text" name="ricorso_data_comunicazione" id="ricorso_data_comunicazione" class="text ui-widget-content ui-corner-all" /></td>
                        <td style="width:20%"><label for="ricorso_esito">Esito</label></td>
                        <td style="width:30%">
                            <select id="ricorso_esito" name="ricorso_esito" style="width:90%;">
                                <option value="0">Accoglimento</option>
                                <option value="1">Rifiuto parziale</option>
                                <option value="2">Rifiuto totale</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        <hr />
        </td>
      </tr>
      <tr>
	<td colspan="4" style="text-align: center">
	<div id="accesso_generalizzato_block" style="width:100%;">
            <table style="width:100%;">    
                <tr>
                    <td colspan="4" style="text-align: center">
                    <hr />
                        <input type="checkbox" name="riesame" id="riesame" class="text ui-widget-content ui-corner-all" value=""><strong><label for="riesame" id="label_riesame"> Riesame</label></strong>
                        <div id="riesame_block" style="width:100%; margin-top: 2em;">
                            <table style="width:100%">
                                <tr>
                                    <td style="width:20%"><label for="riesame_data_presentazione" id="label_riesame_data_presentazione">Data di presentazione</label></td>
                                    <td style="width:30%"><input type="text" name="riesame_data_presentazione" id="riesame_data_presentazione" class="text ui-widget-content ui-corner-all" /></td>
                                    <td style="width:20%"><label for="riesame_esito">Esito</label></td>
                                    <td style="width:30%">
                                        <select id="riesame_esito" name="riesame_esito" style="width:90%;">
                                            <option value="0">Accoglimento</option>
                                            <option value="1">Rifiuto parziale</option>
                                            <option value="2">Rifiuto totale</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:20%"><label for="riesame_data_provvedimento" id="label_riesame_data_conclusione">Data conclusione</label></td>
                                    <td style="width:30%"><input type="text" name="riesame_data_provvedimento" id="riesame_data_provvedimento" class="text ui-widget-content ui-corner-all" /></td>
                                    <td style="width:20%"><label for="riesame_motivo_rifiuto" id="label_riesame_motivo_rifiuto">Motivazione rifiuto</label></td>
                                    <td style="width:30%"><textarea name="riesame_motivo_rifiuto" id="riesame_motivo_rifiuto" style="width:89%;" rows="2" class="text ui-widget-content ui-corner-all"></textarea></td>
                                </tr>          
                            </table>
                        </div>
                    <hr />
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                    </td>
                </tr>      
            </table>
        </div>    
	</td>
      </tr>
      <tr>
	<td><label for="note">Note</label></td>
	<td colspan="3"><textarea name="note" id="note" style="width:96%;" rows="3" class="text ui-widget-content ui-corner-all"></textarea><br />le note sono visibili solamente alla struttura interna, pertanto è possibile utilizzarle per annotazioni che siano utili alla gestione del procedimento, come numeri di prot., fascicoli, etc.</td>
      </tr>
      <tr>
	<td colspan="4"><input type="hidden" name="id_pubblicazione" id="id_pubblicazione" value=""/></td>
      </tr>
    </table>
  </form>
</div>

<div style="align: center; width:100%; min-width: 1280px; z-index:6">
  <table style="width:80%;margin-left:8%;" class="ui-widget-content ui-corner-all">
    <tr>
      <td style="text-align:left;">
      <div id="navigator">
      </div>
      </td>
      <td style="text-align:center;width:70%; padding-top: .4em;">
	<span><strong>Registro informatizzato degli accessi civici (art. 5, comma 1 e 2 d.lgs. 33/2013) e documentali (legge 241/1990)</strong></span><br>
	<span style="font-size: 0.8em;" id="navigatorTitle"></span>
      </td>
      <td  style="width: 4%; padding-top: .4em; text-align: center;">
        <span><button id="accessi_help">Guida alla compilazione</button></span>
      </td>
      <td  style="width: 7%; padding: .7em .4em .4em; text-align: center;">
        <span><button id="accessi_print">Stampa</button></span><span><button id="accessi_cerca">Cerca</button></span>
      </td>
      <td  style="width: 7%; padding: .7em .4em .4em; text-align: center;">
	<?php if($_SESSION['livello']< 2) {?><span><button id="accessi_new">Aggiungi una nuova bozza</button></span><?php }?><span><button id="accessi_opts">Opzioni</button></span>
      </td>
    </tr>
    <tr>
      <td colspan=5 style="padding: 0;margin: 0;">
      <hr/>
      </td>
    </tr>
    <tr>
      <td colspan=5>
	<div id="accessi_list"></div>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
