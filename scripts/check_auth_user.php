<?php
/**
 * check_auth_user.php — verifica utente di test per la suite E2E (login email/OTP).
 * Uso CLI:  php scripts/check_auth_user.php [email]
 * Exit code: 0 = utente trovato con status>0, 1 = assente/non abilitato, 2 = errore DB.
 */
$root = dirname(__DIR__);
include_once $root."/utils/config.php";
include_once $root."/utils/db.php";

$email = isset($argv[1]) ? $argv[1] : "esaiucarta@regione.sardegna.it";

$db = new Database();
if (!$db->Query("SELECT id, user, email, status, lastlogin FROM aa_users WHERE email = '".$email."' LIMIT 1")) {
    fwrite(STDERR, "ERRORE DB: ".$db->GetErrorMessage()."\n");
    exit(2);
}
$rows = $db->GetResult();
if (count($rows) === 0) {
    echo "ASSESENTE: nessuna riga in aa_users per $email\n";
    exit(1);
}
$r = $rows[0];
echo "TROVATO id={$r['id']} user={$r['user']} email={$r['email']} status={$r['status']} lastlogin={$r['lastlogin']}\n";
exit(intval($r['status']) > 0 ? 0 : 1);
