<?php
class AA_Const extends AA_Config
{
    //Tabella Assessorati
    const AA_DBTABLE_ASSESSORATI = "assessorati";

    //tabella db moduli
    const AA_DBTABLE_MODULES = "aa_platform_modules";

    //Permessi
    const AA_PERMS_NONE = 0;
    const AA_PERMS_READ = 1;
    const AA_PERMS_WRITE = 2;
    const AA_PERMS_PUBLISH = 4;
    const AA_PERMS_DELETE = 8;
    const AA_PERMS_ALL = 15;

    //Livelli utente
    const AA_USER_LEVEL_ADMIN = 0;
    const AA_USER_LEVEL_OPERATOR = 1;
    const AA_USER_LEVEL_GUEST = 2;

    //Stato
    const AA_STATUS_NONE = -1;
    const AA_STATUS_BOZZA = 1;
    const AA_STATUS_PUBBLICATA = 2;
    const AA_STATUS_REVISIONATA = 4;
    const AA_STATUS_CESTINATA = 8;
    const AA_STATUS_ALL = 15;

    //User flags (deprecated, compat only) - moved to modules
    const AA_USER_FLAG_PROCESSI = "processi";
    const AA_USER_FLAG_INCARICHI_TITOLARI = "incarichi_titolari";
    const AA_USER_FLAG_INCARICHI = "incarichi";
    const AA_USER_FLAG_ART22 = "art22";
    const AA_USER_FLAG_ART22_ADMIN = "art22_admin";

    //Task constant
    const AA_TASK_STATUS_OK = 0;
    const AA_TASK_STATUS_FAIL = -1;

    //Oggetti (deprecated, compat only) - moved to modules
    const AA_OBJECT_ART26 = 26;
    const AA_OBJECT_ART37 = 37;
    const AA_OBJECT_ART22 = 22;
    const AA_OBJECT_ART22_BILANCI = 23;
    const AA_OBJECT_ART22_NOMINE = 24;
    const AA_OBJECT_RISICO = 25;

    //Moduli (deprecated, compat only) - moved to modules
    const AA_MODULE_HOME = "AA_MODULE_HOME";
    const AA_MODULE_STRUTTURE = "AA_MODULE_STRUTTURE";
    const AA_MODULE_UTENTI = "AA_MODULE_UTENTI";
    const AA_MODULE_ART26 = "AA_MODULE_ART26";
    const AA_MODULE_ART37 = "AA_MODULE_ART37";
    const AA_MODULE_SINES = "AA_MODULE_SINES";
    const AA_MODULE_INCARICHI = "AA_MODULE_INCARICHI";

    //Operazioni
    const AA_OPS_ADDNEW = 1;
    const AA_OPS_UPDATE = 2;
    const AA_OPS_PUBLISH = 3;
    const AA_OPS_REASSIGN = 4;
    const AA_OPS_TRASH = 5;
    const AA_OPS_RESUME = 6;
}
