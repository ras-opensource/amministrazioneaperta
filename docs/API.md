# API Reference - Amministrazione Aperta

> Generato automaticamente dal codice (script `scripts/gen_api.py`). Non modificare a mano:
> Rigenerare dopo ogni cambio di firma in `utils/system_core/`, `utils/system_lib/` e `utils/modules/**`.
> Verifica coerenza doc/codice: `sha256sum -c docs/api.sha256`

## Superficie HTTP (entry point)

| Endpoint | File | Descrizione |
|---|---|---|
| `GET /index.2.0.php` | `index.2.0.php` | Entry point app: auth, overlay, Webix UI, loop moduli |
| `GET/POST /utils/system_ops.php` | `utils/system_ops.php` | Dispatcher task di sistema (AA_SystemTaskManager) |
| `GET/POST /utils/modules/<id>/taskmanager.php` | per-modulo | Entry point AJAX dei task del modulo (`?task=<NomeTask>`) |
| `GET /storage.php?object=HASH` | `storage.php` | Accesso file pubblico autenticato (hash) |
| `POST /ws.php` | `ws.php` | Service XML pubblicazioni (D.Lgs. 33/2013, 39/2013), parametri `<param art=..>` |

## Protocollo task (frontend → backend)

- Richiesta: `taskmanager.php?task=<NomeTask>&<params>...` (+ viewport `vw`,`vh`,`mobile`)
- Registrazione: `$taskManager->RegisterTask("<Nome>")` → risolve `Task_<Nome>()` nella classe modulo
- Risposta XML:
  ```xml
  <root>
    <status id='status'>0</status>      <!-- 0=ok, -1=errore, -2=auth -->
    <error id='error'></error>
    <content id='content' encode='base64' type='json'>...</content>
  </root>
  ```
- Client: `AA_Task()` / `AA_VerboseTask()` / `AA_Module.doTask()` / `AA_MainApp.utils.callHandler()` in `utils/system_lib.js`

## `system_core/`

### `utils/system_core/AA_AccountsDatabase.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_AccountsDatabase` | PDO_Database | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$bReset = false` | 19 |

### `utils/system_core/AA_Const.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Const` | AA_Config | 2 |

### `utils/system_core/AA_Database.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Database` | PDO_Database | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$bReset = false` | 19 |

### `utils/system_core/AA_Group.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Group` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetProp()` | `$key` | 6 |
| `IsValid()` | `—` | 14 |
| `Parse()` | `$props=array(` | 21 |
| `__construct()` | `$props=array(` | 32 |
| `GetDescendants()` | `$id_group` | 42 |
| `GetGroup()` | `$id="",$user=null` | 82 |
| `AddGroup()` | `$newGroupData=null,$user=null` | 119 |
| `ModifyGroup()` | `$id_group=0,$groupData=null,$user=null` | 166 |
| `DelGroup()` | `$id_group=0,$user=null` | 238 |
| `SearchGroups()` | `$params=array(` | 283 |
| `GetUsers()` | `—` | 290 |

### `utils/system_core/AA_Log.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Log` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetTime()` | `—` | 10 |
| `GetLevel()` | `—` | 16 |
| `GetMsg()` | `—` | 22 |
| `GetBackTrace()` | `—` | 28 |
| `GetUser()` | `—` | 34 |
| `Log()` | `$msg, $level = 0, $bWithbacktrace = false, $bLogToSession = true, $user = null` | 42 |
| `__construct()` | `$level = 0, $msg = "", $time = "", $backtrace = null, $user = null` | 84 |
| `toHTML()` | `$bWithbacktrace = false` | 95 |
| `SetLogLevel()` | `$nNewLogLevel = 100` | 120 |
| `GetLog()` | `—` | 127 |
| `LogAction()` | `$id_utente = 0, $op, $sql` | 133 |

### `utils/system_core/AA_Object.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Object` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `SetId()` | `$val = 0` | 6 |
| `GetType()` | `—` | 15 |
| `SetType()` | `$val = 0` | 19 |
| `SetParent()` | `$parent = null` | 26 |
| `GetParent()` | `—` | 31 |
| `EnableUpdateParent()` | `$bEnable = true` | 38 |
| `IsParentUpdateEnabled()` | `—` | 42 |
| `EnableParentPermsCheck()` | `$bEnable = true` | 49 |
| `IsParentPermsCheckEnabled()` | `—` | 56 |
| `DisableLocalPermsCheck()` | `$bDisable = true` | 63 |
| `IsLocalPermsCheckDisabled()` | `—` | 67 |
| `SetAggiornamento()` | `$val = ""` | 77 |
| `EnableAggiornamentoDbSync()` | `$bEnable = true` | 84 |
| `IsAggiornamentoDbSyncEnabled()` | `—` | 88 |
| `GetLog()` | `$bFormated = true` | 96 |
| `AddLog()` | `$log = "", $actionType = "0", $user = null` | 104 |
| `ResetLog()` | `—` | 118 |
| `SetStruct()` | `$struct = null` | 125 |
| `EnableStructDbSync()` | `$bEnable = true` | 135 |
| `IsStructDbSyncEnabled()` | `—` | 139 |
| `EnableUserDbSync()` | `$bEnable = true` | 149 |
| `IsUserDbSyncEnabled()` | `—` | 153 |
| `SetStatus()` | `$val = 0` | 160 |
| `EnableStatusDbSync()` | `$bEnable = true` | 170 |
| `IsStatusDbSyncEnabled()` | `—` | 174 |
| `SetStatusMask()` | `$mask = AA_Const::AA_STATUS_ALL` | 181 |
| `GetStatusMask()` | `—` | 187 |
| `SetTitolo()` | `$val = ""` | 194 |
| `SetDescrizione()` | `$val = ""` | 201 |
| `__construct()` | `$user = null` | 210 |
| `EnablePermsCaching()` | `$bEnable = true` | 226 |
| `IsPermsCachingEnabled()` | `—` | 230 |
| `ClearCache()` | `—` | 234 |
| `GetUserCaps()` | `$user = null` | 240 |
| `GetID()` | `—` | 318 |
| `isValid()` | `—` | 324 |
| `GetStatus()` | `—` | 330 |
| `GetTitolo()` | `—` | 336 |
| `GetDescrizione()` | `—` | 342 |
| `GetAggiornamento()` | `$bShort = false` | 348 |
| `GetUser()` | `—` | 358 |
| `GetStruct()` | `—` | 364 |
| `__toString()` | `—` | 370 |
| `Snapshot()` | `$date = "", $object = null` | 378 |
| `IsPublished()` | `—` | 405 |
| `toXml()` | `—` | 415 |
| `ToCsv()` | `$separator="|",$bHeader=true,$bDetail=true,$bToBrowser=true` | 429 |
| `CsvDataHeader()` | `$separator="|"` | 480 |
| `CsvData()` | `$separator= "|"` | 486 |
| `SetChanged()` | `$bVal = true` | 494 |
| `IsChanged()` | `—` | 501 |
| `GetBindings()` | `—` | 509 |
| `IsDbSyncEnabled()` | `—` | 516 |
| `EnableDbSync()` | `$val = true` | 520 |
| `DbSync()` | `$user = null, $bForce = false` | 526 |
| `GetProp()` | `$prop = null` | 656 |
| `ParseData()` | `$data = null, $user = null` | 666 |
| `UpdateDb()` | `$user = null, $data = null, $bLog = true,$sDetailLog=""` | 717 |
| `Trash()` | `$user = null, $bDelete = false` | 778 |
| `Resume()` | `$user = null` | 831 |
| `Reassign()` | `$params = null, $user = null` | 865 |
| `Publish()` | `$user = null` | 904 |
| `CanPublish()` | `—` | 944 |
| `VerifyDbSync()` | `$user = null` | 951 |
| `VerifyDbLoad()` | `—` | 1001 |
| `LoadFromDb()` | `$id = 0, $user = null` | 1024 |

### `utils/system_core/AA_Object_Log.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Object_Log` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$log = ""` | 5 |
| `GetLog()` | `—` | 20 |
| `GetLastLog()` | `—` | 26 |

### `utils/system_core/AA_Object_V2.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Object_V2` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetObjectsDbDataTable()` | `—` | 6 |
| `GetId()` | `—` | 13 |
| `SetId()` | `$var = 0` | 17 |
| `GetStatus()` | `—` | 28 |
| `SetStatus()` | `$var = 0` | 32 |
| `GetAggiornamento()` | `—` | 47 |
| `GetName()` | `—` | 54 |
| `SetName()` | `$var = ""` | 58 |
| `GetDescr()` | `—` | 65 |
| `SetDescr()` | `$var = ""` | 69 |
| `GetStatusMask()` | `—` | 76 |
| `SetStatusMask()` | `$var = AA_Const::AA_STATUS_ALL` | 80 |
| `serialize()` | `—` | 90 |
| `EnableRevision()` | `$var = true` | 96 |
| `GetIdData()` | `—` | 107 |
| `SetIdData()` | `$var = 0` | 111 |
| `GetIdDataRev()` | `—` | 126 |
| `SetIdDataRev()` | `$var = 0` | 130 |
| `GetClass()` | `—` | 145 |
| `SetClass()` | `$var = "AA_Object_V2"` | 149 |
| `GetLog()` | `$bFormated = true` | 164 |
| `AddLog()` | `$log = "", $actionType = "0", $user = null` | 172 |
| `ResetLog()` | `—` | 196 |
| `GetStruct()` | `—` | 204 |
| `SetStruct()` | `$var = null` | 209 |
| `ToCsv()` | `$separator="|",$bHeader=true,$bDetail=true,$bToBrowser=true` | 224 |
| `CsvDataHeader()` | `$separator="|"` | 275 |
| `CsvData()` | `$separator= "|"` | 281 |
| `IsChanged()` | `—` | 289 |
| `SetChanged()` | `$var = true` | 293 |
| `SaveToDb()` | `$object = null, $user = null, $bSaveData = false` | 299 |
| `CloneData()` | `$idData = 0, $user = null` | 493 |
| `DeleteData()` | `$idData = 0, $user = null` | 530 |
| `SaveData()` | `—` | 548 |
| `Save()` | `$user = null, $bForce = false, $bSaveData = false` | 589 |
| `Publish()` | `$user = null, $bSaveData = false` | 597 |
| `AddNew()` | `$object = null, $user = null, $bSaveData = false` | 638 |
| `SetTemplateViewProps()` | `$props=null` | 668 |
| `GetTemplateViewProps()` | `—` | 680 |
| `SetDefaultTemplateViewProps()` | `—` | 687 |
| `GetTemplateView()` | `$bRefresh=false` | 698 |
| `GetDefaultTemplateView()` | `—` | 707 |
| `SetTemplateView()` | `$var = null` | 752 |
| `Update()` | `$user = null, $bSaveData = true, $logMsg = ""` | 761 |
| `Trash()` | `$user = null, $bSaveData = false` | 806 |
| `Reassign()` | `$oStruct = null, $user = null, $bSaveData = false` | 843 |
| `Resume()` | `$user = null, $bSaveData = false` | 886 |
| `Delete()` | `$user = null` | 926 |
| `GetUserCaps()` | `$user = null` | 986 |
| `Load()` | `$id = 0, $user = null, $bLoadData = true, $bCheckPerms=true` | 1038 |
| `LoadData()` | `$user = null` | 1123 |
| `IsValid()` | `—` | 1161 |
| `IsReadOnly()` | `—` | 1168 |
| `__construct()` | `$id = 0, $user = null, $bLoadData = true, $bCheckPerms=true` | 1174 |
| `GetProp()` | `$prop = ""` | 1265 |
| `SetProp()` | `$prop = "", $val = null` | 1272 |
| `AddProp()` | `$prop = "", $initValue="",$dbBind=""` | 1285 |
| `GetProps()` | `—` | 1310 |
| `Parse()` | `$data = array(` | 1316 |
| `SetBind()` | `$prop = "", $dbField = "", $bAddProp = true` | 1331 |
| `GetBind()` | `$prop = ""` | 1347 |
| `GetDbBindings()` | `—` | 1357 |
| `GetDbDataTable()` | `—` | 1364 |
| `SetDbDataTable()` | `$var = ""` | 1368 |
| `Search()` | `$params = array(` | 1374 |

### `utils/system_core/AA_Platform.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Platform` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `IsValid()` | `—` | 12 |
| `GetManutention()` | `—` | 40 |
| `GetOverlay()` | `—` | 45 |
| `SetOverlay()` | `$var=""` | 50 |
| `GetInstance()` | `$user = null` | 57 |
| `GetModuleTaskManagerURL()` | `$id_module = ""` | 70 |
| `GetModulePathURL()` | `$id_module = ""` | 85 |
| `RegisterPublicService()` | `$service="",$serviceFunc=null` | 103 |
| `RunPublicService()` | `$service=""` | 124 |
| `IsPublicServiceRegistered()` | `$service=""` | 141 |
| `__construct()` | `$user = null` | 153 |
| `LoadModules()` | `$bDisableCache = false` | 179 |
| `RegisterModule()` | `$idMod = "", $class = "", $user = null` | 259 |
| `IsRegistered()` | `$id = "", $user = null` | 265 |
| `GetModule()` | `$id = "", $user = null` | 281 |
| `GetModules()` | `—` | 295 |
| `GetModulesFlags()` | `—` | 309 |
| `GetAllModulesFlags()` | `—` | 329 |
| `GetLegacyFlags()` | `—` | 364 |
| `GetCurrentUser()` | `—` | 379 |
| `Auth()` | `$token = "", $user = "", $pwd = ""` | 386 |

### `utils/system_core/AA_SessionFileUpload.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SessionFileUpload` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetId()` | `—` | 5 |
| `GetValue()` | `—` | 11 |
| `IsValid()` | `—` | 17 |
| `__construct()` | `$id = "", $value = ""` | 22 |
| `Get()` | `$id = ""` | 36 |
| `Add()` | `$id = "", $value = ""` | 49 |

### `utils/system_core/AA_SessionVar.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SessionVar` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetName()` | `—` | 5 |
| `GetValue()` | `—` | 11 |
| `IsValid()` | `—` | 17 |
| `UnsetVar()` | `$name=""` | 22 |
| `__construct()` | `$name = "varName", $value = ""` | 31 |
| `Get()` | `$name = "varName"` | 41 |
| `Set()` | `$name = "varName", $value = "", $parse=true` | 48 |

### `utils/system_core/AA_Struct.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Struct` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `IsValid()` | `—` | 21 |
| `__construct()` | `—` | 29 |
| `GetStruct()` | `$id_assessorato = '', $id_direzione = '', $id_servizio = '', $type = ''` | 34 |
| `GetStructTree()` | `—` | 130 |
| `GetAssessorato()` | `$getID = false` | 237 |
| `GetTipo()` | `—` | 244 |
| `GetDirezione()` | `$getID = false` | 250 |
| `GetServizio()` | `$getID = false` | 257 |
| `toXML()` | `—` | 264 |
| `toJSON()` | `$bEncode = false` | 288 |
| `toArray()` | `$params = array(` | 297 |
| `__toString()` | `—` | 346 |

### `utils/system_core/AA_User.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_User` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetLegacyData()` | `—` | 62 |
| `GetStatus()` | `—` | 69 |
| `GetGroups()` | `—` | 76 |
| `GetLabelFlags()` | `—` | 82 |
| `GetRuolo()` | `$bNumeric=false` | 107 |
| `GetRole()` | `$bNumeric=false` | 130 |
| `IsServerOperator()` | `—` | 135 |
| `isAdministrator()` | `—` | 141 |
| `isOperator()` | `—` | 147 |
| `GetDefaultGroups()` | `—` | 153 |
| `GetAllGroups()` | `—` | 167 |
| `LoadAllGroups()` | `—` | 176 |
| `__construct()` | `—` | 197 |
| `IsConcurrentEnabled()` | `—` | 206 |
| `IsValid()` | `—` | 214 |
| `GetProfileImagePublicPath()` | `—` | 220 |
| `GetProfileImageLocalPath()` | `—` | 250 |
| `IsDisabled()` | `—` | 274 |
| `IsGuest()` | `—` | 280 |
| `GetPhone()` | `—` | 287 |
| `GetLastLogin()` | `—` | 294 |
| `GetLastNotify()` | `—` | 301 |
| `GetImage()` | `—` | 308 |
| `IsAdmin()` | `—` | 313 |
| `IsSuperUser()` | `—` | 326 |
| `GetStruct()` | `—` | 339 |
| `GetLevel()` | `—` | 345 |
| `GetID()` | `—` | 351 |
| `isCurrentUser()` | `—` | 357 |
| `LoadUser()` | `$id_user` | 363 |
| `LegacyLoadUser()` | `$id_user` | 444 |
| `SetResetPwdEmailParams()` | `$params=array(` | 539 |
| `SetSendCredentialsEmailParams()` | `$params=array(` | 547 |
| `GetResetPwdEmailParams()` | `—` | 555 |
| `LoadUserFromUserName()` | `$userName` | 567 |
| `LegacyLoadUserFromUserName()` | `$userName` | 591 |
| `LoadUsersFromEmail()` | `$email,$bLegacyUsers=true` | 626 |
| `LoadLastLoggedUsers()` | `$bLegacyUsers=true` | 650 |
| `GetProfiles()` | `—` | 675 |
| `LegacyLoadUsersFromEmail()` | `$email` | 684 |
| `LegacyLoadLastLoggedUsers()` | `—` | 724 |
| `TemporaryUserAuth()` | `$sUserName = "", $sUserPwd = ""` | 765 |
| `UserAuth()` | `$sToken = "", $sUserName = "", $sUserPwd = "", $remember_me=false,$bTemporary=false` | 771 |
| `GetSSOAuthToken()` | `—` | 1045 |
| `VerifySSOAuthToken()` | `$token='',$bRegisterToken=false` | 1063 |
| `legacyUserAuth()` | `$sToken = "", $sUserName = "", $sUserPwd = "", $remember_me=false` | 1130 |
| `ChangeProfile()` | `$newProfileID = ""` | 1303 |
| `MailOTPAuthChallenge()` | `$email = null,$remember_me=false` | 1364 |
| `MailOTPAuthChallengeVerify()` | `$code=""` | 1418 |
| `MailOTPChangePwdChallenge()` | `—` | 1463 |
| `MailOTPChangePwdChallengeVerify()` | `$code=""` | 1510 |
| `MailOTPAuthSend()` | `$email = null, $register = true` | 1537 |
| `MailOTPAuthVerify()` | `$codice = null` | 1598 |
| `MailOTPAuthIsMailRegistered()` | `$email = ""` | 1643 |
| `MailOTPAuthRegisterEmail()` | `$email = ""` | 1666 |
| `LogOut()` | `—` | 1689 |
| `GenerateToken()` | `$id_user, $remember_me=false, $concurrent_access=false` | 1707 |
| `RefreshToken()` | `$token` | 1753 |
| `GetCurrentUser()` | `—` | 1766 |
| `Guest()` | `—` | 1774 |
| `toXML()` | `—` | 1781 |
| `__toString()` | `—` | 1799 |
| `HasFlag()` | `$flag` | 1807 |
| `GetNome()` | `—` | 1836 |
| `GetName()` | `—` | 1842 |
| `GetCognome()` | `—` | 1847 |
| `GetUsername()` | `—` | 1853 |
| `GetCf()` | `—` | 1859 |
| `GetEmail()` | `—` | 1865 |
| `GetFlags()` | `$bArray = false, $bLegacyFlags=true` | 1871 |
| `GetLegacyFlags()` | `$bArray = false` | 1892 |
| `UserNameExist()` | `$userName = ""` | 1906 |
| `Search()` | `$params=array(` | 1935 |
| `SearchUsers()` | `$params=array(` | 2060 |
| `LegacySearch()` | `$params=array(` | 2172 |
| `CanGestUtenti()` | `—` | 2295 |
| `CanGestStruct()` | `—` | 2320 |
| `CanPromoteUserAsAdmin()` | `$idUser = null` | 2346 |
| `CanModifyUser()` | `$idUser = null` | 2398 |
| `AddNewUser()` | `$params` | 2475 |
| `MigrateLegacyUser()` | `$legacyUser,$legacyPwd,$oldMd5Pwd=""` | 2628 |
| `LegacyAddNewUser()` | `$params` | 2745 |
| `UpdateUser()` | `$idUser, $params` | 2949 |
| `LegacyUpdateUser()` | `$idUser, $params` | 3116 |
| `ChangePwd()` | `$params=null` | 3310 |
| `UpdateCurrentUserProfile()` | `$params,$imageFileName=""` | 3371 |
| `LegacyUpdateCurrentUserProfile()` | `$params,$imageFileName=""` | 3475 |
| `LegacyDeleteUser()` | `$idUser` | 3548 |
| `DeleteUser()` | `$idUser,$bOnlyTrash=true` | 3599 |
| `ResumeUser()` | `$idUser` | 3694 |
| `ResetPassword()` | `$email, $bSendEmail = true` | 3744 |
| `SendCredentials()` | `$userId=0, $bSendEmail = true` | 3835 |

### `utils/system_core/AA_Utils.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Utils` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `password_hash()` | `$password=""` | 5 |
| `password_verify()` | `$password="",$hash=""` | 16 |
| `number_format()` | `$number, $decimals='', $sep1='', $sep2='',$round=true` | 30 |
| `AppendLogToSession()` | `—` | 45 |
| `uniqid()` | `$lenght = 13` | 51 |
| `GetSessionLog()` | `—` | 64 |
| `ResetSessionLog()` | `—` | 134 |
| `GetSQLValueString()` | `$theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""` | 140 |
| `CheckUser()` | `$reqUser, $levelMin, $id_assessorato = 0, $id_direzione = 0, $id_servizio = 0, $id_settore = 0` | 169 |
| `validateDate()` | `$date, $format = 'Y-m-d'` | 189 |
| `CleanOldTokens()` | `—` | 196 |
| `Xml_entities()` | `$string` | 207 |
| `xmlentities()` | `$string` | 223 |
| `CheckURL()` | `$url` | 239 |

## `system_lib/`

### `utils/system_lib/AA_AMAAI.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_AMAAI` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetInstance()` | `—` | 5 |
| `TemplateLayout()` | `—` | 13 |
| `TemplateStart()` | `—` | 19 |

### `utils/system_lib/AA_Archivio.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Archivio` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `Snapshot()` | `$date = "", $id_object = 0, $object_type = 0, $content = "", $user = null` | 5 |
| `Resume()` | `$date = "", $id_object = 0, $object_type = 0` | 33 |
| `ResumeMulti()` | `$id_object = 0, $object_type = 0, $date = "", $num = 1` | 45 |

### `utils/system_lib/AA_Assessorato.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Assessorato` | AA_Struttura | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetTipologie()` | `—` | 10 |
| `__construct()` | `$params = null` | 24 |
| `GetDirezioni()` | `$bAsObjects=false` | 31 |
| `Delete()` | `$user=null` | 62 |

### `utils/system_lib/AA_DbBind.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_DbBind` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetBindings()` | `—` | 6 |
| `SetTable()` | `$table = ""` | 13 |
| `GetTable()` | `—` | 17 |
| `AddBind()` | `$nomeVariabile = "", $nomeCampo = ""` | 23 |
| `DelBind()` | `$nomeVariabile = ""` | 32 |

### `utils/system_lib/AA_Direzione.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Direzione` | AA_Struttura | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$params = null` | 8 |
| `Delete()` | `$user=null` | 18 |
| `GetServizi()` | `$bAsObjects=false` | 44 |

### `utils/system_lib/AA_GenericLogDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericLogDlg` | AA_GenericWindowTemplate | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$id = "", $title = "Logs", $user = null` | 4 |
| `Update()` | `—` | 74 |

### `utils/system_lib/AA_GenericModule.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericModule` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetTaskManagerUrl()` | `—` | 82 |
| `AddSection()` | `$section` | 88 |
| `GetSection()` | `$section = "AA_GENERIC_MODULE_SECTION"` | 92 |
| `AddObjectTemplate()` | `$idObject="",$template=""` | 100 |
| `DelObjectTemplate()` | `$idObject=""` | 121 |
| `GetObjectTemplate()` | `$idObject=""` | 125 |
| `GetSections()` | `$format = "raw"` | 133 |
| `GetFlags()` | `—` | 166 |
| `SetSectionItemTemplate()` | `$section = "", $template = ""` | 197 |
| `GetSectionItemTemplate()` | `$section = ""` | 204 |
| `Task_GetSections()` | `$task` | 211 |
| `Task_GetLayout()` | `$task` | 225 |
| `Task_AMAAI_Start()` | `$task` | 240 |
| `Template_GenericAMAAIDlg()` | `—` | 253 |
| `GetSideBarConfig()` | `$format = "raw"` | 266 |
| `GetSideBarId()` | `—` | 284 |
| `SetSideBarId()` | `$var = ""` | 288 |
| `GetSideBarIcon()` | `—` | 295 |
| `SetSideBarIcon()` | `$var = ""` | 299 |
| `GetSideBarName()` | `—` | 306 |
| `SetSideBarName()` | `$var = ""` | 310 |
| `GetSideBarTooltip()` | `—` | 317 |
| `SetSideBarTooltip()` | `$var = ""` | 321 |
| `GetUser()` | `—` | 327 |
| `RegisterPublicService()` | `—` | 332 |
| `PublicServiceHandler()` | `—` | 339 |
| `__construct()` | `$user = null, $bDefaultSections = true` | 345 |
| `GetId()` | `—` | 403 |
| `SetId()` | `$newId = ""` | 409 |
| `GetTaskManager()` | `—` | 417 |
| `GetDataGenericSectionPubblicate_List()` | `$params = array(` | 428 |
| `GetDataSectionPubblicate_List()` | `$params = array(` | 530 |
| `TemplateGenericLayout()` | `—` | 536 |
| `TemplateLayout()` | `—` | 547 |
| `TemplateGenericSection_Placeholder()` | `—` | 553 |
| `TemplateSection_Placeholder()` | `—` | 567 |
| `Task_GetGenericActionMenu()` | `$task` | 573 |
| `Task_GetActionMenu()` | `$task` | 612 |
| `TemplateGenericActionMenu_Bozze()` | `—` | 618 |
| `Task_GetGenericNavbarContent()` | `$task, $params = array(` | 640 |
| `Task_GetNavbarContent()` | `$task` | 672 |
| `Task_GetSideMenuContent()` | `$task` | 678 |
| `Task_GenericAddNew()` | `$task, $params = array(` | 704 |
| `Task_GenericUpdateObject()` | `$task, $params = array(` | 760 |
| `Task_GetLogDlg()` | `$task` | 813 |
| `Template_GenericLogDlg()` | `$object=null` | 850 |
| `Template_GetGenericObjectTrashDlg()` | `$params, $saveTask = "TrashObject"` | 881 |
| `Template_GetGenericObjectDeleteDlg()` | `$params, $saveTask = "GenericDeleteObject"` | 950 |
| `Template_GetGenericResumeObjectDlg()` | `$params = array(` | 1019 |
| `Task_GenericResumeObject()` | `$task, $params = array(` | 1089 |
| `Template_GetGenericReassignObjectDlg()` | `$params = array(` | 1179 |
| `Task_GenericReassignObject()` | `$task, $params = array(` | 1253 |
| `Task_GenericTrashObject()` | `$task, $params = array(` | 1352 |
| `Task_GenericDeleteObject()` | `$task, $params = array(` | 1442 |
| `Template_GetGenericPublishObjectDlg()` | `$params = array(` | 1532 |
| `Task_GenericPublishObject()` | `$task, $params = array(` | 1602 |
| `Task_PdfExport()` | `$task` | 1693 |
| `Template_PdfExport()` | `$objects = array(` | 1765 |
| `Task_CsvExport()` | `$task` | 1771 |
| `Template_CsvExport()` | `$objects = array(` | 1850 |
| `Template_GenericCsvExport()` | `$objects=array(` | 1855 |
| `Template_GenericPdfExport()` | `$objects = array(` | 1890 |
| `TemplateGenericNavbar_Bozze()` | `$level = 1, $last = false, $refresh_view = true` | 2071 |
| `TemplateGenericNavbar_Section()` | `$section=null, $level = 1, $last = false, $refresh_view = true` | 2090 |
| `Task_GetGenericObjectContent()` | `$task, $params = array(` | 2119 |
| `Task_GetObjectContent()` | `$task` | 2246 |
| `Task_GetSectionContent()` | `$task` | 2252 |
| `Task_GetGenericObjectData()` | `$task, $params = array(` | 2276 |
| `Task_GetObjectData()` | `$task` | 2308 |
| `TemplateSection_Detail()` | `$params` | 2314 |
| `TemplateNavbar_Bozze()` | `$level = 1, $last = false, $refresh_view = true` | 2320 |
| `TemplateGenericNavbar_Pubblicate()` | `$level = 1, $last = false, $refresh_view = true` | 2326 |
| `TemplateNavbar_Pubblicate()` | `$level = 1, $last = false, $refresh_view = true` | 2346 |
| `TemplateGenericNavbar_Back()` | `$level = 1, $last = false, $refresh_view = false` | 2352 |
| `TemplateGenericNavbar_Void()` | `$level = 1, $last = false` | 2373 |
| `TemplateNavbar_Back()` | `$level = 1, $last = false, $refresh_view = true` | 2393 |
| `TemplateActionMenu_Bozze()` | `—` | 2399 |
| `TemplateGenericActionMenu_Pubblicate()` | `—` | 2405 |
| `TemplateActionMenu_Pubblicate()` | `—` | 2429 |
| `TemplateGenericActionMenu_Detail()` | `—` | 2435 |
| `TemplateActionMenu_Detail()` | `—` | 2459 |
| `TemplateGenericSection_Pubblicate()` | `$params = array(` | 2465 |
| `DataSectionIsFiltered()` | `$params = array(` | 2534 |
| `CustomDataSectionIsFiltered()` | `$params = array(` | 2548 |
| `TemplateSection_Pubblicate()` | `$params = array(` | 2554 |
| `GetDataGenericSectionBozze_List()` | `$params = array(` | 2561 |
| `GetDataSectionBozze_List()` | `$params = array(` | 2658 |
| `TemplateGenericSection_Bozze()` | `$params, $contentData = null` | 2664 |
| `TemplateSection_Bozze()` | `$params = array(` | 2741 |
| `TemplateGenericSection_Detail()` | `$params` | 2748 |
| `TemplateGenericTabbedSection()` | `$id_section="",$object=null,$params=null` | 3039 |
| `TemplateGenericDettaglio_Generale_Tab()` | `$object = null` | 3371 |
| `TemplateGenericDettaglio_Header_Generale_Tab()` | `$object = null, $id = "",$header_content=null,$bModify=null` | 3418 |

### `utils/system_lib/AA_GenericModuleSection.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericModuleSection` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetId()` | `—` | 5 |
| `GetName()` | `—` | 11 |
| `SetName()` | `$val = "new name"` | 15 |
| `GetIcon()` | `—` | 21 |
| `SetIcon()` | `$val = ""` | 25 |
| `IsVisibleInNavbar()` | `—` | 32 |
| `GetViewId()` | `—` | 38 |
| `GetModuleId()` | `—` | 44 |
| `isValid()` | `—` | 50 |
| `IsDefault()` | `—` | 56 |
| `IsDetail()` | `—` | 62 |
| `SetDetail()` | `$bVal = true` | 66 |
| `SetTemplate()` | `$val=""` | 72 |
| `GetTemplate()` | `—` | 76 |
| `SetNavbarTemplate()` | `$template = "{}"` | 82 |
| `EnableRefreshView()` | `$bVal = true` | 88 |
| `toArray()` | `—` | 93 |
| `__toString()` | `—` | 109 |
| `toString()` | `—` | 114 |
| `toBase64()` | `—` | 119 |
| `__construct()` | `$id = "AA_GENERIC_MODULE_SECTION", $name = "section name", $navbar = false, $view_id = "AA_Section_Content_Box", $module_id = "AA_GENERIC_MODULE", $default = false, $refresh_view = true, $detail = false, $valid = false, $icon="",$template=""` | 124 |
| `TemplateActionMenu()` | `—` | 139 |
| `TemplateGenericActionMenu()` | `—` | 144 |

### `utils/system_lib/AA_GenericModuleTask.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericModuleTask` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `SetContent()` | `$val="", $json=false, $encode=true` | 7 |
| `SetStatus()` | `$val=0` | 27 |
| `SetStatusAction()` | `$action="",$params=null,$json_encode=false` | 34 |
| `SetStatusActionParams()` | `$params=null,$json_encode=false` | 40 |
| `SetError()` | `$val="", $json=false, $encode=true` | 52 |
| `__construct()` | `$task = "", $user = null, $taskManager = null, $taskFunction = ""` | 65 |
| `Run()` | `—` | 81 |
| `GetLog()` | `—` | 92 |

### `utils/system_lib/AA_GenericModuleTaskManager.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericModuleTaskManager` | AA_GenericTaskManager | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetModule()` | `—` | 5 |
| `__construct()` | `$module = null, $user = null` | 10 |
| `RegisterTask()` | `$task = null, $taskFunction = ""` | 19 |

### `utils/system_lib/AA_GenericNews.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericNews` | AA_GenericParsableDbObject | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$params = null` | 6 |

### `utils/system_lib/AA_GenericPagedSectionTemplate.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericPagedSectionTemplate` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetHeader()` | `—` | 6 |
| `SetHeader()` | `$obj = ""` | 10 |
| `SetContentBox()` | `$obj = ""` | 18 |
| `SetContentBoxTemplate()` | `$template = ""` | 25 |
| `GetContentBoxTemplate()` | `—` | 29 |
| `SetContentBoxData()` | `$data = array(` | 36 |
| `GetContentBoxData()` | `—` | 40 |
| `EnableSelect()` | `$bVal = true` | 46 |
| `EnableMultiSelect()` | `$bVal = true` | 50 |
| `SetContentItemsForPage()` | `$val = "5"` | 55 |
| `GetContentItemsForPage()` | `—` | 59 |
| `SetContentItemsForRow()` | `$val = 1` | 65 |
| `GetContentItemsForRow()` | `—` | 69 |
| `SetContentItemHeight()` | `$val = "auto"` | 75 |
| `GetContentItemHeight()` | `—` | 79 |
| `toObject()` | `—` | 85 |
| `__toString()` | `—` | 90 |
| `toArray()` | `—` | 94 |
| `toBase64()` | `—` | 98 |
| `GetPager()` | `—` | 106 |
| `SetPager()` | `$obj = ""` | 110 |
| `SetPagerFiltered()` | `$var=true` | 117 |
| `IsPagerFiltered()` | `—` | 121 |
| `GetPagerTitle()` | `—` | 128 |
| `SetPagerTitle()` | `$obj = ""` | 132 |
| `GetToolbar()` | `—` | 139 |
| `SetToolbar()` | `$obj = ""` | 143 |
| `SetModule()` | `$id` | 149 |
| `GetModule()` | `—` | 153 |
| `__construct()` | `$id = "AA_GenericPagedSectionTemplate", $module = "", $content_box = ""` | 160 |
| `SetShowDetailSectionFunc()` | `$val=""` | 175 |
| `Update()` | `—` | 180 |
| `SetSectionName()` | `$val = "Titolo"` | 543 |
| `GetSectionName()` | `—` | 547 |
| `EnablePaging()` | `$bVal = true` | 562 |
| `EnablePager()` | `$bVal = true` | 567 |
| `DisablePaging()` | `—` | 571 |
| `IsPaged()` | `—` | 575 |
| `SetPagerCurPage()` | `$page = 0` | 579 |
| `SetPagerItemForPage()` | `$var = 10` | 583 |
| `SetPagerItemCount()` | `$var = 10` | 587 |
| `SetPagerTarget()` | `$var = ""` | 591 |
| `SetPagerTargetAction()` | `$var = ""` | 595 |
| `EnableFiltering()` | `—` | 604 |
| `DisableFiltering()` | `—` | 608 |
| `IsFiltered()` | `—` | 612 |
| `SetSaveFilterId()` | `$id = ""` | 618 |
| `GetSaveFilterId()` | `—` | 622 |
| `SetFilterData()` | `$data = array(` | 628 |
| `GetFilterData()` | `—` | 632 |
| `SetFilterDlgTask()` | `$var = ""` | 638 |
| `GetFilterDlgTask()` | `—` | 642 |
| `EnableAddNew()` | `$bVal = true` | 649 |
| `SetAddNewDlgTask()` | `$task = ""` | 654 |
| `EnableAddNewMulti()` | `$bVal = true` | 662 |
| `SetAddNewMultiDlgTask()` | `$task = ""` | 668 |
| `ViewDetail()` | `$bVal = true` | 677 |
| `HideDetail()` | `—` | 682 |
| `EnableDetail()` | `$bVal = true` | 687 |
| `DisableDetail()` | `—` | 692 |
| `ViewTrash()` | `$bVal = true` | 703 |
| `HideTrash()` | `—` | 708 |
| `EnableTrash()` | `$bVal = true` | 713 |
| `DisableTrash()` | `—` | 719 |
| `SetTrashHandler()` | `$handler = null, $params = null` | 724 |
| `SetTrashHandlerParams()` | `$params = null` | 729 |
| `ViewDelete()` | `$bVal = true` | 738 |
| `HideDelete()` | `—` | 743 |
| `EnableDelete()` | `$bVal = true` | 748 |
| `DisableDelete()` | `—` | 753 |
| `SetDeleteHandler()` | `$handler = null, $params = null` | 760 |
| `SetDeleteHandlerParams()` | `$params = null` | 765 |
| `ViewReassign()` | `$bVal = true` | 773 |
| `HideReassign()` | `—` | 778 |
| `EnableReassign()` | `$bVal = true` | 783 |
| `DisableReassign()` | `—` | 788 |
| `SetReassignHandler()` | `$handler = null, $params = null` | 795 |
| `SetReassignHandlerParams()` | `$params = null` | 800 |
| `ViewResume()` | `$bVal = true` | 809 |
| `HideResume()` | `—` | 814 |
| `EnableResume()` | `$bVal = true` | 819 |
| `DisableResume()` | `—` | 824 |
| `SetResumeHandler()` | `$handler = null, $params = null` | 831 |
| `SetResumeHandlerParams()` | `$params = null` | 836 |
| `ViewPublish()` | `$bVal = true` | 845 |
| `HidePublish()` | `—` | 850 |
| `EnablePublish()` | `$bVal = true` | 855 |
| `DisablePublish()` | `—` | 860 |
| `SetPublishHandler()` | `$handler = null, $params = null` | 868 |
| `SetPublishHandlerParams()` | `$params = null` | 873 |
| `ViewSaveAsPdf()` | `$bVal = true` | 882 |
| `HideSaveAsPdf()` | `—` | 886 |
| `EnableSaveAsPdf()` | `$bVal = true` | 890 |
| `DisableSaveAsPdf()` | `—` | 894 |
| `ViewSaveAsCsv()` | `$bVal = true` | 901 |
| `HideSaveAsCsv()` | `—` | 905 |
| `EnableSaveAsCsv()` | `$bVal = true` | 909 |
| `DisableSaveAsCsv()` | `—` | 913 |
| `ViewExportFunctions()` | `$bVal = true` | 917 |
| `HideExportFunctions()` | `—` | 922 |
| `EnableExportFunctions()` | `$bVal = true` | 926 |
| `DisableExportFunctions()` | `—` | 933 |
| `SetSaveAsPdfHandler()` | `$handler = null, $params = null` | 939 |
| `SetSaveAsPdfHandlerParams()` | `$params = null` | 944 |
| `SetSaveAsCsvHandler()` | `$handler = null, $params = null` | 950 |
| `SetSaveAsCsvHandlerParams()` | `$params = null` | 955 |

### `utils/system_lib/AA_GenericParsableDbObject.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericParsableDbObject` | AA_GenericParsableObject | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetDatatable()` | `—` | 5 |
| `GetObjectClass()` | `—` | 10 |
| `GetDbClass()` | `—` | 15 |
| `__construct()` | `$params=null` | 20 |
| `Sync()` | `—` | 25 |
| `Update()` | `$params=null, $user=null` | 74 |
| `Search()` | `$params=null` | 84 |
| `LoadDataFromDb()` | `$id=0` | 189 |
| `Load()` | `$id=0,$user=null` | 211 |
| `Delete()` | `$user=null` | 223 |
| `DeleteFromDb()` | `—` | 229 |

### `utils/system_lib/AA_GenericParsableObject.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericParsableObject` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `Parse()` | `$values=null` | 7 |
| `SetTemplateViewProps()` | `$props=null` | 19 |
| `GetTemplateViewProps()` | `—` | 31 |
| `SetDefaultTemplateViewProps()` | `—` | 38 |
| `GetTemplateView()` | `$bRefresh=false` | 49 |
| `GetDefaultTemplateView()` | `—` | 58 |
| `SetTemplateView()` | `$var = null` | 103 |
| `__construct()` | `$params=null` | 111 |
| `GetId()` | `—` | 120 |
| `SetProp()` | `$prop="",$value=""` | 126 |
| `GetProp()` | `$prop=""` | 132 |
| `GetProps()` | `—` | 139 |

### `utils/system_lib/AA_GenericPdfPreviewDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericPdfPreviewDlg` | AA_GenericWindowTemplate | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$id = "", $title = "Pdf Viewer", $module = ""` | 4 |
| `Update()` | `—` | 16 |

### `utils/system_lib/AA_GenericResetPwdDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericResetPwdDlg` | AA_GenericFormDlg | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `___construct()` | `$id = "", $title = "", $formData = array(` | 4 |

### `utils/system_lib/AA_GenericResources.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericResources` | AA_GenericParsableDbObject | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$params = null` | 4 |

### `utils/system_lib/AA_GenericServerStatusDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericServerStatusDlg` | AA_GenericWindowTemplate | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$id = "", $title = "Logs", $user = null` | 4 |
| `Update()` | `—` | 31 |

### `utils/system_lib/AA_GenericStructDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericStructDlg` | AA_GenericWindowTemplate | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetTargetForm()` | `—` | 9 |
| `SetApplyActions()` | `$actions = ""` | 14 |
| `__construct()` | `$id = "", $title = "", $options = null, $applyActions = "", $module = "", $user = null` | 21 |
| `Update()` | `—` | 105 |

### `utils/system_lib/AA_GenericTask.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericTask` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetTaskManager()` | `—` | 14 |
| `SetTaskManager()` | `$taskManager = null` | 19 |
| `GetName()` | `—` | 24 |
| `SetName()` | `$name = ""` | 29 |
| `GetError()` | `—` | 36 |
| `SetError()` | `$error` | 41 |
| `Run()` | `—` | 47 |
| `__construct()` | `$taskName = "", $user = null, $taskManager = null` | 53 |
| `GetLog()` | `—` | 65 |
| `SetLog()` | `$log` | 70 |

### `utils/system_lib/AA_GenericTaskManager.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericTaskManager` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 13 |
| `RegisterTask()` | `$task = null, $class = null` | 20 |
| `Clear()` | `—` | 34 |
| `GetTask()` | `$name = ""` | 40 |
| `UnregisterTask()` | `$name = ""` | 57 |
| `RunTask()` | `$taskName = ""` | 67 |
| `GetTaskLog()` | `$taskName = ""` | 85 |
| `GetTaskError()` | `$taskName = ""` | 92 |
| `IsManaged()` | `$taskName = ""` | 99 |

### `utils/system_lib/AA_GenericTaskResponse.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_GenericTaskResponse` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `SetStatus()` | `$newStatus = AA_Const::AA_TASK_STATUS_OK` | 8 |
| `GetStatus()` | `—` | 13 |
| `SetError()` | `$error = ""` | 18 |
| `GetError()` | `—` | 23 |
| `SetMsg()` | `$error = ""` | 28 |
| `GetMsg()` | `—` | 33 |
| `SetContent()` | `$val = ""` | 38 |
| `GetContent()` | `—` | 43 |
| `toString()` | `—` | 48 |

### `utils/system_lib/AA_NewsTags.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_NewsTags` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `Initialize()` | `—` | 8 |
| `__construct()` | `—` | 13 |
| `GetTags()` | `$params=null` | 19 |

### `utils/system_lib/AA_ObjectVarMapping.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_ObjectVarMapping` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetObject()` | `—` | 6 |
| `SetObject()` | `$object = null` | 10 |
| `__construct()` | `$object = null` | 15 |
| `AddVar()` | `$var_name = "", $name = "", $type = "", $label = ""` | 22 |
| `DelVar()` | `$var_name = ""` | 31 |
| `GetName()` | `$var_name = ""` | 39 |
| `GetType()` | `$var_name = ""` | 50 |
| `GetLabel()` | `$var_name = ""` | 61 |

### `utils/system_lib/AA_Risorse.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Risorse` | AA_GenericParsableDbObject | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$params = null` | 7 |
| `LoadFromUrlName()` | `$url_name` | 16 |
| `Parse()` | `$values = null` | 32 |
| `GetFileInfo()` | `—` | 59 |
| `SetFileInfo()` | `$val=null` | 74 |
| `AddFile()` | `$params=null,$user=null` | 83 |
| `AddFileFromStorage()` | `$hash="",$url_name="",$categorie="",$user=null` | 131 |
| `AddGenericFileFromUpload()` | `$url_name="",$categorie="",$user=null` | 187 |
| `GetFile()` | `$user=null` | 211 |
| `Delete()` | `$user=null` | 228 |

### `utils/system_lib/AA_Servizio.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Servizio` | AA_Struttura | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$params = null` | 8 |

### `utils/system_lib/AA_Struttura.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Struttura` | AA_GenericParsableDbObject | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$params = null` | 8 |
| `Delete()` | `$user=null` | 19 |

### `utils/system_lib/AA_SystemTaskManager.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTaskManager` | AA_GenericTaskManager | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |

### `utils/system_lib/AA_SystemTask_AMAAI_Start.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_AMAAI_Start` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_ChangeCurrentUserProfile.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_ChangeCurrentUserProfile` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetAppStatus.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetAppStatus` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetChangeCurrentUserProfileDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetChangeCurrentUserProfileDlg` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetChangeCurrentUserPwdDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetChangeCurrentUserPwdDlg` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetCurrentUserProfileDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetCurrentUserProfileDlg` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetGalleryData.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetGalleryData` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetGalleryDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetGalleryDlg` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetGalleryTrashDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetGalleryTrashDlg` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetLogDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetLogDlg` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetNews.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetNews` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetPdfPreviewDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetPdfPreviewDlg` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetServerStatus.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetServerStatus` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetServerStatusDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetServerStatusDlg` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetSideMenuContent.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetSideMenuContent` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetStructDlg.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetStructDlg` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_GetStructTreeData.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_GetStructTreeData` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_RefreshGalleryContent.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_RefreshGalleryContent` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_SetSessionVar.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_SetSessionVar` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_TrashFromGallery.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_TrashFromGallery` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_TreeStruct.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_TreeStruct` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_UpdateCurrentUserProfile.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_UpdateCurrentUserProfile` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_UpdateCurrentUserPwd.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_UpdateCurrentUserPwd` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_UploadFromCKeditor5.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_UploadFromCKeditor5` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_UploadFromGallery.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_UploadFromGallery` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_SystemTask_UploadSessionFile.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SystemTask_UploadSessionFile` | AA_GenericTask | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null` | 4 |
| `Run()` | `—` | 10 |

### `utils/system_lib/AA_XML_FEED.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_XML_FEED` |  | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `SetURL()` | `$var = ""` | 15 |
| `GetURL()` | `—` | 19 |
| `Timestamp()` | `—` | 26 |
| `GetParams()` | `—` | 33 |
| `SetParams()` | `$params = array(` | 37 |
| `GetContent()` | `—` | 44 |
| `SetContent()` | `$var` | 48 |
| `toXML()` | `—` | 54 |
| `__toString()` | `—` | 72 |

### `utils/system_lib/AA_XML_FEED_ARCHIVIO.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_XML_FEED_ARCHIVIO` | AA_XML_FEED | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `—` | 4 |

## `modules/geco_6/`

### `utils/modules/geco_6/config.php`

### `utils/modules/geco_6/lib.js.php`

### `utils/modules/geco_6/lib.php`

| Metodo | Parametri | Linea |
|---|---|---|
| `GetListaModalita()` | `—` | 30 |
| `GetListaTipoAllegati()` | `—` | 51 |
| `GetTipoCriteri()` | `—` | 69 |
| `GetCategorieAllegati()` | `—` | 104 |
| `DeleteData()` | `$idData = 0, $user = null` | 146 |
| `serialize()` | `—` | 165 |
| `GetModalita()` | `—` | 173 |
| `GetNorma()` | `—` | 194 |
| `GetBeneficiario()` | `—` | 213 |
| `GetRevisione()` | `—` | 232 |
| `GetResponsabile()` | `—` | 251 |
| `GetRevoca()` | `—` | 270 |
| `CloneData()` | `$idData = 0, $user = null` | 288 |
| `__construct()` | `$id=0, $user=null` | 298 |
| `Search()` | `$params=array(` | 324 |
| `GetUserCaps()` | `$user=null` | 344 |
| `AddNew()` | `$object=null,$user=null,$bSaveData=true` | 377 |
| `Update()` | `$user = null, $bSaveData = true, $logMsg = ''` | 414 |
| `AddNewAllegato()` | `$allegato=null, $user=null` | 421 |
| `UpdateAllegato()` | `$allegato=null, $user=null` | 506 |
| `DeleteAllegato()` | `$id_allegato="", $user=null` | 592 |
| `GetAllegati()` | `—` | 658 |
| `GetAllegato()` | `$id_allegato=""` | 675 |
| `SetAllegati()` | `$val=""` | 696 |
| `CsvDataHeader()` | `$separator="|"` | 703 |
| `CsvData()` | `$separator= "|"` | 709 |
| `__construct()` | `$user=null,$bDefaultSections=true` | 759 |
| `TemplateGenericNavbar_Atti()` | `$level = 1, $last = false, $refresh_view = true` | 844 |
| `PublicServiceHandler()` | `—` | 864 |
| `RegisterPublicService()` | `—` | 871 |
| `GetInstance()` | `$user=null` | 881 |
| `TemplateLayout()` | `—` | 892 |
| `TemplateSection_Placeholder()` | `—` | 898 |
| `TemplateSection_Pubblicate()` | `$params=array(` | 904 |
| `TemplateSection_Bozze()` | `$params=array(` | 920 |
| `GetDataSectionPubblicate_List()` | `$params=array(` | 944 |
| `GetDataSectionPubblicate_CustomFilter()` | `$params = array(` | 950 |
| `GetDataSectionPubblicate_CustomDataTemplate()` | `$data = array(` | 997 |
| `GetDataSectionBozze_List()` | `$params=array(` | 1030 |
| `GetDataSectionBozze_CustomFilter()` | `$params = array(` | 1042 |
| `GetDataSectionBozze_CustomDataTemplate()` | `$data = array(` | 1083 |
| `Template_GetGecoPublishDlg()` | `$params` | 1107 |
| `Template_GetGecoDeleteDlg()` | `$params` | 1175 |
| `Template_GetGecoAddNewDlg()` | `—` | 1181 |
| `Template_GetGecoAddNewMultiDlg()` | `—` | 1319 |
| `Template_GetGecoAddNewMultiPreviewDlg()` | `—` | 1364 |
| `Template_GetGecoConfirmPrivacyDlg()` | `$form_id=''` | 1467 |
| `Template_GetGecoRevisionViewDlg()` | `$object=null` | 1524 |
| `Template_GetGecoCsvImportErrorDetailDlg()` | `$errors=array(` | 1565 |
| `Template_GetGecoAddNewAllegatoDlg()` | `$object=null` | 1593 |
| `Template_GetGecoAddNewCriteriDlg()` | `—` | 1664 |
| `Template_GetGecoModifyCriteriDlg()` | `$criterio=null` | 1751 |
| `Template_GetGecoCopyCriteriDlg()` | `$criterio=null` | 1854 |
| `Template_GetGecoModifyAllegatoDlg()` | `$object=null,$allegato=null` | 1956 |
| `Template_GetGecoTrashCriteriDlg()` | `$object=null` | 2016 |
| `Template_GetGecoTrashAllegatoDlg()` | `$object=null,$allegato=null` | 2064 |
| `Task_AddNewGecoAllegato()` | `$task` | 2114 |
| `Task_AddNewGecoCriteri()` | `$task` | 2276 |
| `Task_UpdateGecoCriteri()` | `$task` | 2431 |
| `Task_DeleteGecoCriteri()` | `$task` | 2623 |
| `Template_GetGecoModifyDlg()` | `$object=null` | 2659 |
| `Template_GetGecoBeneficiarioModifyDlg()` | `$object=null` | 2782 |
| `Template_GetGecoRevocaDlg()` | `$object=null` | 2834 |
| `Template_GetGecoRevisionDlg()` | `$params=null` | 2891 |
| `Template_GetGecoHelpDlg()` | `—` | 2937 |
| `TemplateSection_Detail()` | `$params` | 3018 |
| `TemplateGecoDettaglio_Generale_Tab()` | `$object=null` | 3040 |
| `TemplateGecoDettaglio_Allegati_Tab()` | `$object=null` | 3352 |
| `TemplateSection_Criteri()` | `$params=array(` | 3478 |
| `Task_UpdateGecoDatiGenerali()` | `$task` | 3565 |
| `Task_UpdateGecoDatiBeneficiario()` | `$task` | 3705 |
| `Task_UpdateGecoDatiRevoca()` | `$task` | 3778 |
| `Task_TrashGeco()` | `$task` | 3870 |
| `Task_ResumeGeco()` | `$task` | 3887 |
| `Task_PublishGeco()` | `$task` | 3895 |
| `Task_ReassignGeco()` | `$task` | 3903 |
| `Task_DeleteGeco()` | `$task` | 3910 |
| `Task_AddNewGeco()` | `$task` | 3927 |
| `Task_GecoAddNewMulti()` | `$task` | 4032 |
| `Task_GetGecoModifyDlg()` | `$task` | 4171 |
| `Task_GetGecoRevisionViewDlg()` | `$task` | 4206 |
| `Task_GetGecoRevisionDlg()` | `$task` | 4241 |
| `Task_GetGecoRevocaModifyDlg()` | `$task` | 4276 |
| `Task_GetGecoConfirmPrivacyDlg()` | `$task` | 4311 |
| `Task_GetGecoBeneficiarioModifyDlg()` | `$task` | 4331 |
| `Task_GetGecoResumeDlg()` | `$task` | 4367 |
| `Task_GetGecoPublishDlg()` | `$task` | 4393 |
| `Task_GetGecoReassignDlg()` | `$task` | 4419 |
| `Task_GetGecoTrashDlg()` | `$task` | 4445 |
| `Task_GetGecoDeleteDlg()` | `$task` | 4470 |
| `Task_GetGecoAddNewDlg()` | `$task` | 4495 |
| `Task_GetGecoAddNewMultiDlg()` | `$task` | 4514 |
| `Task_GetGecoAddNewMultiPreviewDlg()` | `$task` | 4533 |
| `Task_GetGecoAddNewMultiPreviewCalc()` | `$task` | 4552 |
| `Task_GetGecoCsvImportErrorDetailDlg()` | `$task` | 4751 |
| `Task_GetGecoAddNewAllegatoDlg()` | `$task` | 4769 |
| `Task_GetGecoAddNewCriteriDlg()` | `$task` | 4804 |
| `Task_GetGecoModifyCriteriDlg()` | `$task` | 4820 |
| `Task_GetGecoCopyCriteriDlg()` | `$task` | 4845 |
| `Task_GecoCriteriPdfExport()` | `$task` | 4870 |
| `Task_UpdateGecoAllegato()` | `$task` | 4890 |
| `Task_DeleteGecoAllegato()` | `$task` | 5094 |
| `Task_GetGecoModifyAllegatoDlg()` | `$task` | 5141 |
| `Task_GetGecoTrashAllegatoDlg()` | `$task` | 5197 |
| `Task_GetGecoTrashCriteriDlg()` | `$task` | 5243 |
| `Task_GetGecoPubblicateFilterDlg()` | `$task` | 5275 |
| `Task_GetGecoBozzeFilterDlg()` | `$task` | 5290 |
| `Task_GetNavbarContent()` | `$task` | 5305 |
| `TemplatePubblicateFilterDlg()` | `$params=array(` | 5316 |
| `TemplateBozzeFilterDlg()` | `$params=array(` | 5384 |
| `Template_PdfExport()` | `$ids=array(` | 5452 |
| `Template_CriteriPdfExport()` | `$ids=array(` | 5459 |
| `Template_PdfExportAsync()` | `$ids=array(` | 5466 |
| `Task_AMAAI_Start()` | `$task` | 5473 |
| `Template_GecoPdfExport()` | `$id="", $parent=null,$object=null,$user=null` | 5487 |
| `Template_GecoCriteriPdfExport()` | `$id="", $parent=null,$object=null,$user=null` | 5500 |
| `Template_PublicReportPageHeaderPdf()` | `$object=null` | 5514 |
| `Template_CriteriPublicReportPageHeaderPdf()` | `$object=null` | 5568 |
| `TemplateDettaglio_Allegati()` | `$object=null,$id="", $canModify=false` | 5597 |
| `Task_GetActionMenu()` | `$task` | 5671 |
| `__construct()` | `$id="AA_GecoPublicReportTemplateView",$parent=null,$object=null` | 5695 |
| `__construct()` | `$id="AA_GecoCriteriPublicReportTemplateView",$parent=null,$object=null` | 5871 |
| `GetName()` | `—` | 5932 |
| `__construct()` | `$params=null,$user=null` | 5937 |
| `Parse()` | `$params=null, $user=null` | 5965 |
| `Search()` | `$params=null` | 5975 |
| `Load()` | `$id=0,$user=null` | 5987 |
| `GetUserCaps()` | `$user=null` | 5999 |
| `Update()` | `$params=null,$user=null` | 6032 |
| `Delete()` | `$user=null` | 6082 |

### `utils/modules/geco_6/services.php`

### `utils/modules/geco_6/taskmanager.php`

## `modules/gecop_8/`

### `utils/modules/gecop_8/config.php`

### `utils/modules/gecop_8/lib.js.php`

### `utils/modules/gecop_8/lib.php`

| Metodo | Parametri | Linea |
|---|---|---|
| `GetListaRuoloComponente()` | `—` | 24 |
| `GetListaComuni()` | `—` | 39 |
| `DeleteData()` | `$idData = 0, $user = null` | 80 |
| `serialize()` | `—` | 116 |
| `GetAggiudicatario()` | `—` | 125 |
| `GetLinks()` | `—` | 144 |
| `GetCommissione()` | `—` | 163 |
| `GetGestioneFinanziaria()` | `—` | 182 |
| `CloneData()` | `$idData = 0, $user = null` | 200 |
| `__construct()` | `$id=0, $user=null` | 210 |
| `Search()` | `$params=array(` | 232 |
| `GetUserCaps()` | `$user=null` | 252 |
| `AddNew()` | `$object=null,$user=null,$bSaveData=true` | 285 |
| `Update()` | `$user = null, $bSaveData = true, $logMsg = ''` | 322 |
| `CigIsValid()` | `$cig='',$escludeId=0` | 328 |
| `GetAllegati()` | `—` | 362 |
| `GetAllegato()` | `$id_allegato=""` | 379 |
| `DeleteAllegato()` | `$id_allegato="", $user=null` | 401 |
| `SetAllegati()` | `$val=""` | 464 |
| `__construct()` | `$user=null,$bDefaultSections=true` | 507 |
| `TemplateGenericNavbar_Atti()` | `$level = 1, $last = false, $refresh_view = true` | 573 |
| `GetInstance()` | `$user=null` | 595 |
| `TemplateLayout()` | `—` | 606 |
| `TemplateSection_Placeholder()` | `—` | 612 |
| `TemplateSection_Pubblicate()` | `$params=array(` | 618 |
| `TemplateSection_Bozze()` | `$params=array(` | 634 |
| `GetDataSectionPubblicate_List()` | `$params=array(` | 649 |
| `GetDataSectionPubblicate_CustomFilter()` | `$params = array(` | 655 |
| `GetDataSectionPubblicate_CustomDataTemplate()` | `$data = array(` | 685 |
| `GetDataSectionBozze_List()` | `$params=array(` | 699 |
| `GetDataSectionBozze_CustomFilter()` | `$params = array(` | 711 |
| `GetDataSectionBozze_CustomDataTemplate()` | `$data = array(` | 741 |
| `Template_GetGecopPublishDlg()` | `$params` | 756 |
| `Template_GetGecopDeleteDlg()` | `$params` | 824 |
| `Template_GetGecopAddNewDlg()` | `—` | 830 |
| `Template_GetGecopAddNewMultiDlg()` | `—` | 888 |
| `Template_GetGecopAddNewMultiPreviewDlg()` | `—` | 941 |
| `Template_GetGecopAddNewMultiResultDlg()` | `—` | 1044 |
| `Template_GetGecopConfirmPrivacyDlg()` | `$form_id=''` | 1079 |
| `Template_GetGecopAddNewAllegatoDlg()` | `$object=null` | 1136 |
| `Template_GetGecopAddNewComponenteDlg()` | `$object=null` | 1207 |
| `Template_GetGecopModifyComponenteDlg()` | `$object=null,$componente=null` | 1273 |
| `Template_GetGecopModifyPraticaDlg()` | `$object=null,$pratica=null` | 1346 |
| `Template_GetGecopCopyPraticaDlg()` | `$object=null,$pratica=null` | 1437 |
| `Template_GetGecopModifyAllegatoDlg()` | `$object=null,$allegato=null` | 1527 |
| `Template_GetGecopTrashComponenteDlg()` | `$object=null,$componente=null` | 1587 |
| `Template_GetGecopTrashAllegatoDlg()` | `$object=null,$allegato=null` | 1639 |
| `Task_AddNewGecopAllegato()` | `$task` | 1689 |
| `Task_AddNewGecopComponente()` | `$task` | 1851 |
| `Task_UpdateGecopComponente()` | `$task` | 2015 |
| `Template_GetGecopModifyDlg()` | `$object=null` | 2212 |
| `Template_GetGecopModifyRendicontoDlg()` | `$object=null` | 2271 |
| `Template_GetGecopBeneficiarioModifyDlg()` | `$object=null` | 2327 |
| `Template_GetGecopHelpDlg()` | `—` | 2378 |
| `TemplateSection_Detail()` | `$params` | 2480 |
| `TemplateDettaglio_Commissione()` | `$object=null,$canModify=false` | 2502 |
| `TemplateDettaglio_RendicontoFinanziario()` | `$object=null,$canModify=false` | 2562 |
| `TemplateGecopDettaglio_Generale_Tab()` | `$object=null` | 2699 |
| `TemplateGecopDettaglio_Allegati_Tab()` | `$object=null` | 2811 |
| `Task_UpdateGecopDatiGenerali()` | `$task` | 2937 |
| `Task_UpdateGecopDatiBeneficiario()` | `$task` | 3016 |
| `Task_TrashGecop()` | `$task` | 3089 |
| `Task_ResumeGecop()` | `$task` | 3106 |
| `Task_PublishGecop()` | `$task` | 3114 |
| `Task_ReassignGecop()` | `$task` | 3125 |
| `Task_DeleteGecop()` | `$task` | 3132 |
| `Task_AddNewGecop()` | `$task` | 3149 |
| `Task_AddNewMultiGecop()` | `$task` | 3194 |
| `Task_GetGecopModifyDlg()` | `$task` | 3334 |
| `Task_GetGecopConfirmPrivacyDlg()` | `$task` | 3369 |
| `Task_GetGecopModifyRendicontoDlg()` | `$task` | 3389 |
| `Task_GetGecopResumeDlg()` | `$task` | 3425 |
| `Task_GetGecopPublishDlg()` | `$task` | 3451 |
| `Task_GetGecopReassignDlg()` | `$task` | 3477 |
| `Task_GetGecopTrashDlg()` | `$task` | 3503 |
| `Task_GetGecopDeleteDlg()` | `$task` | 3528 |
| `Task_GetGecopListaCodiciIstat()` | `$task` | 3553 |
| `Task_GetGecopAddNewDlg()` | `$task` | 3591 |
| `Task_AddNewGecopPratica()` | `$task` | 3610 |
| `Task_UpdateGecopRendiconto()` | `$task` | 3709 |
| `Task_DeleteGecopComponente()` | `$task` | 3836 |
| `Task_GetGecopAddNewMultiDlg()` | `$task` | 3911 |
| `Task_GetGecopAddNewMultiPreviewDlg()` | `$task` | 3930 |
| `Task_GetGecopAddNewMultiResultDlg()` | `$task` | 3949 |
| `Task_GetGecopAddNewMultiPreviewCalc()` | `$task` | 3968 |
| `Task_GetGecopAddNewAllegatoDlg()` | `$task` | 4030 |
| `Task_GetGecopAddNewComponenteDlg()` | `$task` | 4065 |
| `Task_GetGecopModifyPraticaDlg()` | `$task` | 4100 |
| `Task_GetGecopCopyPraticaDlg()` | `$task` | 4147 |
| `Task_GetGecopTrashComponenteDlg()` | `$task` | 4194 |
| `Task_UpdateGecopAllegato()` | `$task` | 4242 |
| `Task_DeleteGecopAllegato()` | `$task` | 4446 |
| `Task_GetGecopModifyAllegatoDlg()` | `$task` | 4493 |
| `Task_GetGecopModifyComponenteDlg()` | `$task` | 4549 |
| `Task_GetGecopTrashAllegatoDlg()` | `$task` | 4605 |
| `Task_GetGecopPubblicateFilterDlg()` | `$task` | 4651 |
| `Task_GetGecopBozzeFilterDlg()` | `$task` | 4666 |
| `Task_GetNavbarContent()` | `$task` | 4681 |
| `TemplatePubblicateFilterDlg()` | `$params=array(` | 4692 |
| `TemplateBozzeFilterDlg()` | `$params=array(` | 4746 |
| `Template_PdfExport()` | `$ids=array(` | 4800 |
| `Template_PublicReportPageHeaderPdf()` | `$object=null` | 4806 |
| `Task_AMAAI_Start()` | `$task` | 4853 |
| `Template_GecopPdfExport()` | `$id="", $parent=null,$object=null,$user=null` | 4867 |
| `TemplateDettaglio_Allegati()` | `$object=null,$id="", $canModify=false` | 4880 |
| `Task_GetActionMenu()` | `$task` | 4954 |
| `__construct()` | `$id="AA_GecopPublicReportTemplateView",$parent=null,$object=null` | 4978 |
| `GetRowCount()` | `—` | 5105 |

### `utils/modules/gecop_8/taskmanager.php`

## `modules/geser_7/`

### `utils/modules/geser_7/config.php`

### `utils/modules/geser_7/lib.js.php`

### `utils/modules/geser_7/lib.php`

| Metodo | Parametri | Linea |
|---|---|---|
| `GetListaStatiImpianto()` | `—` | 30 |
| `GetListaTipoVia()` | `—` | 53 |
| `GetListaStatiPratica()` | `—` | 73 |
| `GetListaTipoPratica()` | `—` | 93 |
| `GetListaTipoImpianti()` | `—` | 117 |
| `GetListaComuni()` | `—` | 137 |
| `DeleteData()` | `$idData = 0, $user = null` | 178 |
| `serialize()` | `—` | 197 |
| `GetGeolocalizzazione()` | `—` | 206 |
| `GetTipo()` | `—` | 223 |
| `GetStato()` | `—` | 230 |
| `GetPratiche()` | `—` | 239 |
| `GetProprietario()` | `—` | 258 |
| `GetGseData()` | `—` | 277 |
| `CloneData()` | `$idData = 0, $user = null` | 295 |
| `__construct()` | `$id=0, $user=null` | 305 |
| `Search()` | `$params=array(` | 335 |
| `GetUserCaps()` | `$user=null` | 355 |
| `AddNew()` | `$object=null,$user=null,$bSaveData=true` | 388 |
| `Update()` | `$user = null, $bSaveData = true, $logMsg = ''` | 425 |
| `GetAllegati()` | `—` | 434 |
| `GetAllegato()` | `$id_allegato=""` | 451 |
| `DeleteAllegato()` | `$id_allegato="", $user=null` | 473 |
| `SetAllegati()` | `$val=""` | 536 |
| `__construct()` | `$user=null,$bDefaultSections=true` | 617 |
| `GeserDownloadGSEData()` | `$url="", $from=0,$count=19` | 691 |
| `TemplateGenericNavbar_Atti()` | `$level = 1, $last = false, $refresh_view = true` | 969 |
| `GetInstance()` | `$user=null` | 991 |
| `TemplateLayout()` | `—` | 1002 |
| `TemplateSection_Placeholder()` | `—` | 1008 |
| `TemplateSection_Pubblicate()` | `$params=array(` | 1014 |
| `TemplateSection_Bozze()` | `$params=array(` | 1030 |
| `GetDataSectionPubblicate_List()` | `$params=array(` | 1045 |
| `GetDataSectionPubblicate_CustomFilter()` | `$params = array(` | 1051 |
| `GetDataSectionPubblicate_CustomDataTemplate()` | `$data = array(` | 1075 |
| `GetDataSectionBozze_List()` | `$params=array(` | 1094 |
| `GetDataSectionBozze_CustomFilter()` | `$params = array(` | 1106 |
| `GetDataSectionBozze_CustomDataTemplate()` | `$data = array(` | 1130 |
| `Template_GetGeserPublishDlg()` | `$params` | 1154 |
| `Template_GetGeserDeleteDlg()` | `$params` | 1222 |
| `Template_GetGeserAddNewDlg()` | `—` | 1228 |
| `Template_GetGeserAddNewMultiDlg()` | `—` | 1328 |
| `Template_GetGeserAddNewMultiPreviewDlg()` | `—` | 1384 |
| `Template_GetGeserAddNewMultiResultDlg()` | `—` | 1487 |
| `Template_GetGeserConfirmPrivacyDlg()` | `$form_id=''` | 1522 |
| `Template_GetGeserAddNewAllegatoDlg()` | `$object=null` | 1579 |
| `Template_GetGeserAddNewPraticaDlg()` | `$object=null` | 1650 |
| `Template_GetGeserModifyPraticaDlg()` | `$object=null,$pratica=null` | 1730 |
| `Template_GetGeserCopyPraticaDlg()` | `$object=null,$pratica=null` | 1821 |
| `Template_GetGeserModifyAllegatoDlg()` | `$object=null,$allegato=null` | 1911 |
| `Template_GetGeserTrashPraticaDlg()` | `$object=null,$pratica=null` | 1971 |
| `Template_GetGeserTrashAllegatoDlg()` | `$object=null,$allegato=null` | 2022 |
| `Task_AddNewGeserAllegato()` | `$task` | 2072 |
| `Template_GetGeserModifyDlg()` | `$object=null` | 2234 |
| `Template_GetGeserBeneficiarioModifyDlg()` | `$object=null` | 2347 |
| `Template_GetGeserHelpDlg()` | `—` | 2398 |
| `TemplateSection_Detail()` | `$params` | 2457 |
| `TemplateDettaglio_Pratiche()` | `$object=null` | 2479 |
| `TemplateGeserDettaglio_Generale_Tab()` | `$object=null` | 2533 |
| `TemplateGeserDettaglio_Pratiche_Tab()` | `$object=null` | 2742 |
| `TemplateGeserDettaglio_Allegati_Tab()` | `$object=null` | 2798 |
| `Task_UpdateGeserDatiGenerali()` | `$task` | 2924 |
| `Task_UpdateGeserDatiBeneficiario()` | `$task` | 3001 |
| `Task_TrashGeser()` | `$task` | 3074 |
| `Task_ResumeGeser()` | `$task` | 3091 |
| `Task_PublishGeser()` | `$task` | 3099 |
| `Task_ReassignGeser()` | `$task` | 3110 |
| `Task_DeleteGeser()` | `$task` | 3117 |
| `Task_AddNewGeser()` | `$task` | 3134 |
| `Task_AddNewMultiGeser()` | `$task` | 3179 |
| `Task_GetGeserModifyDlg()` | `$task` | 3328 |
| `Task_GetGeserConfirmPrivacyDlg()` | `$task` | 3363 |
| `Task_GetGeserBeneficiarioModifyDlg()` | `$task` | 3383 |
| `Task_GetGeserResumeDlg()` | `$task` | 3419 |
| `Task_GetGeserPublishDlg()` | `$task` | 3445 |
| `Task_GetGeserReassignDlg()` | `$task` | 3471 |
| `Task_GetGeserTrashDlg()` | `$task` | 3497 |
| `Task_GetGeserDeleteDlg()` | `$task` | 3522 |
| `Task_GetGeserListaCodiciIstat()` | `$task` | 3547 |
| `Task_GetGeserAddNewDlg()` | `$task` | 3585 |
| `Task_AddNewGeserPratica()` | `$task` | 3604 |
| `Task_UpdateGeserPratica()` | `$task` | 3703 |
| `Task_DeleteGeserPratica()` | `$task` | 3810 |
| `Task_GetGeserAddNewMultiDlg()` | `$task` | 3871 |
| `Task_GetGeserAddNewMultiPreviewDlg()` | `$task` | 3890 |
| `Task_GetGeserAddNewMultiResultDlg()` | `$task` | 3909 |
| `Task_GetGeserAddNewMultiPreviewCalc()` | `$task` | 3928 |
| `ImportGseJson()` | `$impianti=null` | 3996 |
| `ElaborateCsvImportTerna()` | `$csvRows=null` | 4117 |
| `ElaborateCsvImportAssIndustria()` | `$rows=null` | 4263 |
| `ElaborateCsvImportAssAmbiente()` | `$rows=null` | 4268 |
| `ElaborateCsvImportSuape()` | `$csvRows=null` | 4273 |
| `Task_GetGeserAddNewAllegatoDlg()` | `$task` | 4429 |
| `Task_GetGeserAddNewPraticaDlg()` | `$task` | 4464 |
| `Task_GetGeserModifyPraticaDlg()` | `$task` | 4499 |
| `Task_GetGeserCopyPraticaDlg()` | `$task` | 4546 |
| `Task_GetGeserTrashPraticaDlg()` | `$task` | 4593 |
| `Task_UpdateGeserAllegato()` | `$task` | 4641 |
| `Task_DeleteGeserAllegato()` | `$task` | 4845 |
| `Task_GetGeserModifyAllegatoDlg()` | `$task` | 4892 |
| `Task_GetGeserTrashAllegatoDlg()` | `$task` | 4948 |
| `Task_GetGeserPubblicateFilterDlg()` | `$task` | 4994 |
| `Task_GetGeserBozzeFilterDlg()` | `$task` | 5009 |
| `Task_GetNavbarContent()` | `$task` | 5024 |
| `TemplatePubblicateFilterDlg()` | `$params=array(` | 5035 |
| `TemplateBozzeFilterDlg()` | `$params=array(` | 5097 |
| `Template_PdfExport()` | `$ids=array(` | 5159 |
| `Task_AMAAI_Start()` | `$task` | 5165 |
| `Template_GeserPdfExport()` | `$id="", $parent=null,$object=null,$user=null` | 5179 |
| `TemplateDettaglio_Allegati()` | `$object=null,$id="", $canModify=false` | 5192 |
| `Task_GetActionMenu()` | `$task` | 5266 |
| `__construct()` | `$id="AA_GeserPublicReportTemplateView",$parent=null,$object=null` | 5290 |

### `utils/modules/geser_7/taskmanager.php`

## `modules/home_1/`

### `utils/modules/home_1/config.php`

### `utils/modules/home_1/home_lib.js.php`

### `utils/modules/home_1/home_lib.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_CorsiFormazione` | AA_GenericParsableDbObject | 4082 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetInstance()` | `$user=null` | 80 |
| `__construct()` | `$user=null` | 90 |
| `Task_GetHomeUtentiFilterDlg()` | `$task` | 222 |
| `Task_GetHomeUtentiLegacyFilterDlg()` | `$task` | 235 |
| `Task_GetHomeUtentiModifyDlg()` | `$task` | 248 |
| `Task_HomeUtentiTrash()` | `$task` | 289 |
| `Task_HomeUtentiImport()` | `$task` | 334 |
| `Task_HomeUtentiResume()` | `$task` | 388 |
| `Task_GetHomeUtentiTrashDlg()` | `$task` | 431 |
| `Task_GetHomeRngDlg()` | `$task` | 472 |
| `Task_GetCKeditor5Dlg()` | `$task` | 480 |
| `Task_GetHomeCorsiViewDlg()` | `$task` | 488 |
| `Task_HomeCkeditor5Test()` | `$task` | 495 |
| `Task_GetEmailSuggest()` | `$task` | 502 |
| `Task_HomeRngOut()` | `$task` | 529 |
| `Task_HomeRngOutPdf()` | `$task` | 539 |
| `Task_GetHomeUtentiAddNewDlg()` | `$task` | 545 |
| `Task_GetHomeRisorseAddNewDlg()` | `$task` | 560 |
| `Task_GetHomeRisorseModifyDlg()` | `$task` | 575 |
| `Task_GetHomeRisorseTrashDlg()` | `$task` | 598 |
| `Task_GetHomeStructAddNewDlg()` | `$task` | 621 |
| `Task_GetHomeStructModifyDlg()` | `$task` | 652 |
| `Task_GetHomeStructTrashDlg()` | `$task` | 735 |
| `Task_GetHomeUtentiLegacyImportDlg()` | `$task` | 818 |
| `Task_HomeUtentiUpdate()` | `$task` | 833 |
| `Task_HomeUtentiAddNew()` | `$task` | 949 |
| `Task_HomeRisorseAddNew()` | `$task` | 1052 |
| `Task_HomeRisorseUpdate()` | `$task` | 1155 |
| `Task_HomeRisorseDelete()` | `$task` | 1296 |
| `Task_HomeStructAddNew()` | `$task` | 1330 |
| `Task_HomeStructUpdate()` | `$task` | 1416 |
| `Task_HomeStructDelete()` | `$task` | 1510 |
| `TemplateNavbar_Cruscotto()` | `$level=1,$last=true` | 1590 |
| `TemplateNavbar_GestUtenti()` | `$level=1,$last=true` | 1610 |
| `TemplateNavbar_GestStruct()` | `$level=1,$last=true` | 1630 |
| `TemplateNavbar_GestRisorse()` | `$level=1,$last=true` | 1650 |
| `TemplateActionMenu_Cruscotto()` | `—` | 1671 |
| `TemplateActionMenu_Gestutenti()` | `—` | 1690 |
| `TemplateActionMenu_GestStruct()` | `—` | 1709 |
| `TemplateActionMenu_GestRisorse()` | `—` | 1728 |
| `TemplateSection_News()` | `—` | 1747 |
| `TemplateSection_Risorse()` | `—` | 1787 |
| `Template_GetHomeUtentiTrashDlg()` | `$object=null` | 1814 |
| `Template_GetHomeStructTrashDlg()` | `$object=null` | 1867 |
| `Template_GetHomeUtentiLegacyImportDlg()` | `—` | 1942 |
| `Template_DatatableUtentiLegacy()` | `$id=""` | 1957 |
| `Task_GetObjectContent()` | `$task` | 2091 |
| `TemplateSection_Desktop()` | `—` | 2105 |
| `TemplateSection_GestRisorse()` | `—` | 2309 |
| `Template_GetHomeRisorseAddNewDlg()` | `—` | 2378 |
| `Template_GetHomeRisorseModifyDlg()` | `$risorsa=null` | 2421 |
| `Template_GetHomeRisorseTrashDlg()` | `$object=null` | 2475 |
| `TemplateSection_GestUtenti()` | `—` | 2527 |
| `TemplateHomeUtentiFilterDlg()` | `—` | 2762 |
| `Template_GetHomeCorsiViewDlg()` | `—` | 2854 |
| `Template_GetHomeCorsiViewLayout()` | `$id="",$idCorso=0` | 2866 |
| `Template_GetHomeRngDlg()` | `—` | 2924 |
| `Template_HomeRngOutPdf()` | `$start=1,$end=100,$numCount=1,$bToBrowser=true` | 2960 |
| `Template_RngEstrazionePage()` | `$start=1,$end=100,$count=1` | 3131 |
| `TemplateHomeUtentiLegacyFilterDlg()` | `—` | 3214 |
| `Template_GetHomeUtentiModifyDlg()` | `$object=null` | 3280 |
| `Template_GetCKeditor5Dlg()` | `—` | 3446 |
| `Template_GetHomeUtentiAddNewDlg()` | `—` | 3479 |
| `Template_GetHomeStructAddNewDlg()` | `$id_assessorato=0,$id_direzione=0` | 3644 |
| `Template_GetHomeStructModifyDlg()` | `$struct=null` | 3693 |
| `TemplateSection_GestStruct()` | `—` | 3750 |
| `Task_HomeUtentiSendCredenzials()` | `$task` | 3935 |
| `Task_HomeUtentiConfirmSendCredenzials()` | `$task` | 3976 |
| `Template_HomeUtentiConfirmSendUserCredentials()` | `$user=null` | 4010 |
| `Task_GetActionMenu()` | `$task` | 4047 |
| `__construct()` | `$params = null` | 4222 |
| `GetDati()` | `—` | 4231 |
| `GetCorsi()` | `$code=''` | 4240 |

### `utils/modules/home_1/taskmanager.php`

## `modules/patrimonio_3/`

### `utils/modules/patrimonio_3/config.php`

### `utils/modules/patrimonio_3/patrimonio_lib.php`

| Metodo | Parametri | Linea |
|---|---|---|
| `Inizialize()` | `—` | 24 |
| `GetTitoloList()` | `—` | 52 |
| `GetSezioneList()` | `—` | 62 |
| `GetTipoCanoneList()` | `—` | 73 |
| `GetComuneFromCodice()` | `$codice=""` | 93 |
| `AddNewMulti()` | `$objects=null,$user=null,$bSaveData=true,$struct=null` | 120 |
| `LoadCanoni()` | `$idData=0` | 196 |
| `GetCanoni()` | `—` | 251 |
| `GetCanone()` | `$serial=""` | 259 |
| `DeleteData()` | `$idData = 0, $user = null` | 276 |
| `CloneData()` | `$idData = 0, $user = null` | 299 |
| `AddNewCanonePriv()` | `$newCanone=null,$user=null` | 325 |
| `AddNewCanone()` | `$newCanone=null,$user=null` | 362 |
| `UpdateCanone()` | `$canone=null, $user=null` | 389 |
| `LinkCanone()` | `$canone=null, $user=null` | 438 |
| `DeleteCanone()` | `$canone=null,$user=null` | 477 |
| `__construct()` | `$id=0, $user=null` | 529 |
| `Search()` | `$params=array(` | 571 |
| `GetUserCaps()` | `$user=null` | 591 |
| `AddNew()` | `$object=null,$user=null,$bSaveData=true` | 624 |
| `GetTitolo()` | `$bNumeric=false` | 659 |
| `GetSezione()` | `$bNumeric=false` | 667 |
| `__construct()` | `$user=null,$bDefaultSections=true` | 698 |
| `GetInstance()` | `$user=null` | 757 |
| `TemplateLayout()` | `—` | 768 |
| `TemplateSection_Placeholder()` | `—` | 774 |
| `TemplateSection_Pubblicate()` | `$params=array(` | 780 |
| `GetDataSectionPubblicate_List()` | `$params=array(` | 793 |
| `GetDataSectionPubblicate_CustomFilter()` | `$params = array(` | 799 |
| `GetDataSectionPubblicate_CustomDataTemplate()` | `$data = array(` | 830 |
| `TemplateSection_Bozze()` | `$params=array(` | 842 |
| `GetDataSectionBozze_List()` | `$params=array(` | 877 |
| `GetDataSectionBozze_CustomFilter()` | `$params = array(` | 889 |
| `GetDataSectionBozze_CustomDataTemplate()` | `$data = array(` | 920 |
| `Template_GetPatrimonioPublishDlg()` | `$params` | 932 |
| `Template_GetPatrimonioDeleteDlg()` | `$params` | 1001 |
| `Template_GetPatrimonioAddNewDlg()` | `—` | 1007 |
| `Template_GetPatrimonioAddNewMultiDlg()` | `—` | 1107 |
| `Template_GetPatrimonioAddNewMultiPreviewDlg()` | `—` | 1158 |
| `Template_GetPatrimonioAddNewMultiResultDlg()` | `—` | 1240 |
| `Template_GetPatrimonioModifyDlg()` | `$object=null` | 1303 |
| `Template_GetPatrimonioAddNewCanoneDlg()` | `$object=null` | 1407 |
| `GetCanoniList()` | `$bAll=true` | 1473 |
| `Template_GetPatrimonioLinkCanoneDlg()` | `$object=null` | 1507 |
| `Template_GetPatrimonioModifyCanoneDlg()` | `$object=null,$canone=null` | 1558 |
| `Template_GetPatrimonioTrashCanoneDlg()` | `$object=null,$canone=null` | 1672 |
| `TemplateSection_Detail()` | `$params` | 1728 |
| `TemplatePatrimonioDettaglio_Generale_Tab()` | `$object=null` | 1738 |
| `TemplatePatrimonioDettaglio_Canoni_Tab()` | `$object=null` | 1918 |
| `Task_UpdatePatrimonio()` | `$task` | 2077 |
| `Task_TrashPatrimonio()` | `$task` | 2094 |
| `Task_ResumePatrimonio()` | `$task` | 2111 |
| `Task_PublishPatrimonio()` | `$task` | 2119 |
| `Task_ReassignPatrimonio()` | `$task` | 2127 |
| `Task_DeletePatrimonio()` | `$task` | 2134 |
| `Task_AddNewPatrimonio()` | `$task` | 2141 |
| `Task_AddNewCanone()` | `$task` | 2158 |
| `Task_UpdateCanone()` | `$task` | 2204 |
| `Task_PatrimonioLinkCanone()` | `$task` | 2272 |
| `Task_TrashCanone()` | `$task` | 2326 |
| `Task_GetPatrimonioModifyDlg()` | `$task` | 2381 |
| `Task_GetPatrimonioResumeDlg()` | `$task` | 2418 |
| `Task_GetPatrimonioPublishDlg()` | `$task` | 2455 |
| `Task_GetPatrimonioReassignDlg()` | `$task` | 2494 |
| `Task_GetPatrimonioTrashDlg()` | `$task` | 2534 |
| `Task_GetPatrimonioDeleteDlg()` | `$task` | 2573 |
| `Task_GetPatrimonioAddNewDlg()` | `$task` | 2603 |
| `Task_GetPatrimonioAddNewMultiDlg()` | `$task` | 2628 |
| `Task_GetPatrimonioAddNewMulti()` | `$task` | 2651 |
| `Task_GetPatrimonioAddNewMultiResultDlg()` | `$task` | 2670 |
| `Task_GetPatrimonioAddNewMultiPreviewCalc()` | `$task` | 2693 |
| `Task_GetPatrimonioAddNewMultiPreviewDlg()` | `$task` | 2877 |
| `Task_GetPatrimonioListaCodiciIstat()` | `$task` | 2900 |
| `Task_GetPatrimonioAddNewCanoneDlg()` | `$task` | 2938 |
| `Task_GetPatrimonioLinkCanoneDlg()` | `$task` | 2972 |
| `Task_GetPatrimonioModifyCanoneDlg()` | `$task` | 3006 |
| `Task_GetPatrimonioTrashCanoneDlg()` | `$task` | 3041 |
| `Task_GetPatrimonioPubblicateFilterDlg()` | `$task` | 3077 |
| `Task_GetPatrimonioBozzeFilterDlg()` | `$task` | 3092 |
| `Task_GetNavbarContent()` | `$task` | 3107 |
| `TemplatePubblicateFilterDlg()` | `$params=array(` | 3118 |
| `TemplateBozzeFilterDlg()` | `$params=array(` | 3181 |
| `Template_PdfExport()` | `$ids=array(` | 3240 |
| `Template_PatrimonioPdfExport()` | `$id="", $parent=null,$object=null,$user=null` | 3247 |
| `Template_PatrimonioPdfExportShort()` | `$id="", $parent=null,$object=null,$user=null` | 3258 |
| `__construct()` | `$id="AA_PatrimonioPublicReportTemplateView",$parent=null,$object=null` | 3274 |
| `__construct()` | `$id="AA_PatrimonioPublicShortReportTemplateView",$parent=null,$object=null` | 3399 |
| `GetProps()` | `—` | 3592 |
| `SetProp()` | `$prop="",$value=""` | 3597 |
| `GetProp()` | `$prop=""` | 3607 |
| `LoadCanone()` | `$id=""` | 3617 |
| `IsValid()` | `—` | 3650 |
| `__construct()` | `$props=null` | 3655 |
| `__construct()` | `$id="AA_PatrimonioReportCanoniListTemplateView",$parent=null,$object=null` | 3690 |

### `utils/modules/patrimonio_3/taskmanager.php`

## `modules/provvedimenti_4/`

### `utils/modules/provvedimenti_4/config.php`

### `utils/modules/provvedimenti_4/lib.js.php`

### `utils/modules/provvedimenti_4/lib.php`

| Metodo | Parametri | Linea |
|---|---|---|
| `init()` | `—` | 26 |
| `GetListaModalita()` | `—` | 72 |
| `GetListaTipologia()` | `—` | 79 |
| `DeleteData()` | `$idData = 0, $user = null` | 94 |
| `CloneData()` | `$idData = 0, $user = null` | 113 |
| `__construct()` | `$id=0, $user=null` | 123 |
| `GetTipo()` | `$bNumeric=false` | 156 |
| `GetModalita()` | `$bNumeric=false` | 165 |
| `Search()` | `$params=array(` | 174 |
| `GetUserCaps()` | `$user=null` | 194 |
| `AddNew()` | `$object=null,$user=null,$bSaveData=true` | 227 |
| `GetAllegato()` | `$id=null, $user=null` | 262 |
| `AddNewAllegato()` | `$allegato=null, $file="", $user=null` | 321 |
| `UpdateAllegato()` | `$allegato=null, $file="", $user=null` | 410 |
| `DeleteAllegato()` | `$allegato=null, $user=null` | 511 |
| `GetAllegati()` | `$idData=0` | 595 |
| `__construct()` | `$user=null,$bDefaultSections=true` | 669 |
| `GetInstance()` | `$user=null` | 715 |
| `TemplateLayout()` | `—` | 726 |
| `TemplateSection_Placeholder()` | `—` | 732 |
| `TemplateSection_Pubblicate()` | `$params=array(` | 738 |
| `GetDataSectionPubblicate_List()` | `$params=array(` | 751 |
| `GetDataSectionPubblicate_CustomFilter()` | `$params = array(` | 757 |
| `GetDataSectionPubblicate_CustomDataTemplate()` | `$data = array(` | 781 |
| `TemplateSection_Bozze()` | `$params=array(` | 806 |
| `GetDataSectionBozze_List()` | `$params=array(` | 833 |
| `GetDataSectionBozze_CustomFilter()` | `$params = array(` | 845 |
| `GetDataSectionBozze_CustomDataTemplate()` | `$data = array(` | 869 |
| `Template_GetProvvedimentiPublishDlg()` | `$params` | 895 |
| `Template_GetProvvedimentiDeleteDlg()` | `$params` | 963 |
| `Template_GetProvvedimentiAddNewDlg()` | `—` | 969 |
| `Template_GetProvvedimentiAddNewAllegatoDlg()` | `$object=null` | 1038 |
| `Template_GetProvvedimentiModifyAllegatoDlg()` | `$object=null,$allegato=null` | 1083 |
| `Template_GetProvvedimentiTrashAllegatoDlg()` | `$object=null,$allegato=null` | 1131 |
| `Task_AddNewProvvedimentiAllegato()` | `$task` | 1181 |
| `Template_GetProvvedimentiModifyDlg()` | `$object=null` | 1252 |
| `TemplateSection_Detail()` | `$params` | 1323 |
| `TemplateProvvedimentiDettaglio_Generale_Tab()` | `$object=null` | 1333 |
| `Task_UpdateProvvedimenti()` | `$task` | 1436 |
| `Task_TrashProvvedimenti()` | `$task` | 1453 |
| `Task_ResumeProvvedimenti()` | `$task` | 1470 |
| `Task_PublishProvvedimenti()` | `$task` | 1478 |
| `Task_ReassignProvvedimenti()` | `$task` | 1486 |
| `Task_DeleteProvvedimenti()` | `$task` | 1493 |
| `Task_AddNewProvvedimenti()` | `$task` | 1500 |
| `Task_GetProvvedimentiModifyDlg()` | `$task` | 1517 |
| `Task_GetProvvedimentiResumeDlg()` | `$task` | 1554 |
| `Task_GetProvvedimentiPublishDlg()` | `$task` | 1591 |
| `Task_GetProvvedimentiReassignDlg()` | `$task` | 1630 |
| `Task_GetProvvedimentiTrashDlg()` | `$task` | 1670 |
| `Task_GetProvvedimentiDeleteDlg()` | `$task` | 1709 |
| `Task_GetProvvedimentiAddNewDlg()` | `$task` | 1739 |
| `Task_GetProvvedimentiAddNewAllegatoDlg()` | `$task` | 1762 |
| `Task_UpdateProvvedimentiAllegato()` | `$task` | 1799 |
| `Task_DeleteProvvedimentiAllegato()` | `$task` | 1874 |
| `Task_GetProvvedimentiModifyAllegatoDlg()` | `$task` | 1929 |
| `Task_GetProvvedimentiTrashAllegatoDlg()` | `$task` | 1975 |
| `Task_GetProvvedimentiPubblicateFilterDlg()` | `$task` | 2021 |
| `Task_GetProvvedimentiBozzeFilterDlg()` | `$task` | 2036 |
| `Task_GetNavbarContent()` | `$task` | 2051 |
| `TemplatePubblicateFilterDlg()` | `$params=array(` | 2062 |
| `TemplateBozzeFilterDlg()` | `$params=array(` | 2120 |
| `Template_PdfExport()` | `$ids=array(` | 2177 |
| `Template_ProvvedimentiPdfExport()` | `$id="", $parent=null,$object=null,$user=null` | 2183 |
| `TemplateDettaglio_Allegati()` | `$object=null,$id="", $canModify=false` | 2196 |
| `__construct()` | `$id="AA_ProvvedimentiPublicReportTemplateView",$parent=null,$object=null` | 2278 |
| `GetId()` | `—` | 2337 |
| `SetId()` | `$id=0` | 2341 |
| `GetUrl()` | `—` | 2347 |
| `SetUrl()` | `$url=""` | 2351 |
| `GetEstremi()` | `—` | 2357 |
| `SetEstremi()` | `$val=""` | 2361 |
| `GetFilePath()` | `—` | 2366 |
| `GetFileLocalPath()` | `—` | 2376 |
| `GetFilePublicPath()` | `—` | 2381 |
| `GetIdProvvedimento()` | `—` | 2392 |
| `SetIdProvvedimento()` | `$id=0` | 2396 |
| `__construct()` | `$id=0,$id_provvedimento=0,$estremi="",$url=""` | 2401 |
| `Download()` | `$embed=false` | 2412 |

### `utils/modules/provvedimenti_4/taskmanager.php`

## `modules/public/`

### `utils/modules/public/config.php`

### `utils/modules/public/lib.js.php`

### `utils/modules/public/lib.php`

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user=null,$bDefaultSections=true` | 40 |
| `GetInstance()` | `$user=null` | 58 |
| `TemplateLayout()` | `—` | 69 |
| `TemplateSection_Placeholder()` | `—` | 75 |
| `Task_GetObjectContent()` | `$task` | 81 |
| `Task_GetSectionContent()` | `$task` | 87 |
| `Task_GetSideMenuContent()` | `$task` | 93 |
| `Task_GetPublicObjectContent()` | `$task, $params = array(` | 118 |
| `TemplateSection_Home()` | `$params` | 145 |

### `utils/modules/public/taskmanager.php`

## `modules/sicar_9/`

### `utils/modules/sicar_9/config.php`

### `utils/modules/sicar_9/lib.js.php`

### `utils/modules/sicar_9/lib.php`

### `utils/modules/sicar_9/taskmanager.php`

### `utils/modules/sicar_9/classes/AA_SicarAlloggio.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SicarAlloggio` | AA_Object_V2 | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$id = 0, $user = null, $bLoadData = true, $bCheckPerms = true` | 7 |
| `GetImmobile()` | `$bAsObject=true` | 37 |
| `SetImmobile()` | `$var = 0` | 51 |
| `GetDescrizione()` | `—` | 53 |
| `SetDescrizione()` | `$var = ""` | 54 |
| `GetTipologiaUtilizzo()` | `$asText=true` | 56 |
| `SetTipologiaUtilizzo()` | `$var = ""` | 64 |
| `GetStatoConservazione()` | `$asText=true` | 66 |
| `SetStatoConservazione()` | `$var = ""` | 75 |
| `GetAnnoRistrutturazione()` | `—` | 82 |
| `SetAnnoRistrutturazione()` | `$var = 0` | 83 |
| `GetCondominioMisto()` | `—` | 85 |
| `GetSuperficieNetta()` | `—` | 96 |
| `SetSuperficieNetta()` | `$var = 0` | 97 |
| `GetSuperficieNonResidenziale()` | `—` | 105 |
| `SetSuperficieNonResidenziale()` | `$var = 0` | 106 |
| `GetSuperficieParcheggi()` | `—` | 114 |
| `SetSuperficieParcheggi()` | `$var = 0` | 115 |
| `GetVaniAbitabili()` | `—` | 123 |
| `SetVaniAbitabili()` | `$var = 0` | 124 |
| `GetSuperficieUtileAbitabile()` | `—` | 132 |
| `SetSuperficieUtileAbitabile()` | `$var = 0` | 133 |
| `GetPiano()` | `—` | 141 |
| `SetPiano()` | `$var = 0` | 142 |
| `GetAscensore()` | `—` | 144 |
| `SetAscensore()` | `$var = false` | 145 |
| `GetFruibileDis()` | `$bNumeric=false` | 147 |
| `SetFruibileDis()` | `$var = false` | 161 |
| `GetGestione()` | `$bAsObject=true` | 163 |
| `GetGestore()` | `—` | 172 |
| `GetProprietario()` | `—` | 182 |
| `GetGestioneDal()` | `—` | 192 |
| `SetGestione()` | `$var = array(` | 200 |
| `GetProprieta()` | `$bAsObject=true` | 202 |
| `SetProprieta()` | `$var = array(` | 211 |
| `GetProprietaDal()` | `—` | 214 |
| `SetOccupazione()` | `$var = null` | 222 |
| `SetInterventi()` | `$var = null` | 230 |
| `GetNote()` | `—` | 237 |
| `SetNote()` | `$var = ""` | 238 |
| `Update()` | `$user = null, $bSaveData = true,$logMsg="Aggiornamento dati generali alloggio"` | 240 |
| `GetOccupazione()` | `$bAsObject=true` | 245 |
| `GetInterventi()` | `$bAsObject=true` | 254 |
| `Validate()` | `—` | 264 |
| `GetDisplayName()` | `—` | 303 |
| `CsvDataHeader()` | `$separator = "|"` | 313 |
| `CsvData()` | `$separator = "|"` | 322 |
| `GetNucleoAssegnatario()` | `$dal="",$bAsObject=true` | 333 |
| `Search()` | `$params=array(` | 381 |
| `Parse()` | `$params = array(` | 402 |
| `GetUserCaps()` | `$user = null` | 408 |
| `AddNew()` | `$object = null, $user = null, $bSaveData = true` | 437 |
| `GetAssociatedOfImmobile()` | `$id=0,$bBozze=false,$bCestinate=false,$bAsObjects=true,$user=null` | 480 |

### `utils/modules/sicar_9/classes/AA_SicarEnte.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SicarEnte` | AA_GenericParsableDbObject | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$params = array(` | 9 |
| `GetOperatori()` | `$bAsObject=false` | 46 |
| `SetOperatori()` | `$operatori=""` | 66 |
| `GetOperatoriView()` | `—` | 86 |
| `GetTemplateView()` | `$bRefresh=false` | 106 |
| `GetListaEnti()` | `—` | 112 |
| `GetDenominazione()` | `—` | 136 |
| `SetDenominazione()` | `$var = ""` | 141 |
| `GetTipologia()` | `$bAsText=true` | 148 |
| `SetTipologia()` | `$var = 0` | 158 |
| `GetGeolocalizzazione()` | `—` | 164 |
| `SetGeolocalizzazione()` | `$var=""` | 168 |
| `GetContatti()` | `$bAsObject=false` | 174 |
| `GetIndirizzo()` | `—` | 187 |
| `SetIndirizzo()` | `$var = ""` | 192 |
| `GetNote()` | `—` | 199 |
| `SetNote()` | `$var = ""` | 204 |
| `GetSitoWeb()` | `—` | 211 |
| `SetSitoWeb()` | `$var = ""` | 216 |
| `GetSitoWebView()` | `—` | 222 |
| `GetPec()` | `—` | 229 |
| `SetPec()` | `$var = ""` | 233 |
| `GetPecView()` | `—` | 238 |
| `Validate()` | `—` | 246 |
| `GetDisplayName()` | `—` | 280 |
| `CsvDataHeader()` | `$separator = "|"` | 291 |
| `CsvData()` | `$separator = "|"` | 297 |
| `Parse()` | `$params = array(` | 308 |
| `GetUserCaps()` | `$user = null` | 319 |
| `AddNew()` | `$params, $user = null` | 345 |
| `Sync()` | `$user = null` | 359 |

### `utils/modules/sicar_9/classes/AA_SicarFinanziamento.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SicarFinanziamento` | AA_GenericParsableDbObject | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$params = array(` | 9 |
| `GetTemplateView()` | `$bRefresh=false` | 39 |
| `GetListaFinanziamenti()` | `—` | 45 |
| `GetDenominazione()` | `—` | 69 |
| `GetName()` | `—` | 74 |
| `SetDenominazione()` | `$var = ""` | 79 |
| `SetName()` | `$var = ""` | 85 |
| `GetEstremi()` | `—` | 92 |
| `SetEstremi()` | `$var = ""` | 97 |
| `GetData()` | `—` | 104 |
| `SetData()` | `$var = ""` | 109 |
| `GetTipologia()` | `$bAsText=true` | 116 |
| `SetTipologia()` | `$var = 0` | 126 |
| `GetNote()` | `—` | 133 |
| `SetNote()` | `$var = ""` | 138 |
| `Validate()` | `—` | 145 |
| `GetDisplayName()` | `—` | 162 |
| `CsvDataHeader()` | `$separator = "|"` | 170 |
| `CsvData()` | `$separator = "|"` | 177 |
| `Parse()` | `$params = array(` | 188 |
| `GetUserCaps()` | `$user = null` | 199 |
| `AddNew()` | `$params, $user = null` | 225 |
| `Sync()` | `$user = null` | 239 |

### `utils/modules/sicar_9/classes/AA_SicarGraduatoria.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SicarGraduatoria` | AA_GenericParsableDbObject | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$params = array(` | 9 |
| `GetTemplateView()` | `$bRefresh=false` | 39 |
| `GetListaGraduatoria()` | `—` | 45 |
| `GetDenominazione()` | `—` | 69 |
| `SetDenominazione()` | `$var = ""` | 74 |
| `GetTipologia()` | `$bAsText=true` | 81 |
| `SetTipologia()` | `$var = 0` | 91 |
| `GetNote()` | `—` | 98 |
| `SetNote()` | `$var = ""` | 103 |
| `Validate()` | `—` | 110 |
| `GetDisplayName()` | `—` | 127 |
| `CsvDataHeader()` | `$separator = "|"` | 135 |
| `CsvData()` | `$separator = "|"` | 141 |
| `Parse()` | `$params = array(` | 151 |
| `GetUserCaps()` | `$user = null` | 162 |
| `AddNew()` | `$params, $user = null` | 188 |
| `Sync()` | `$user = null` | 202 |

### `utils/modules/sicar_9/classes/AA_SicarImmobile.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SicarImmobile` | AA_GenericParsableDbObject | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$params = array(` | 9 |
| `GetTemplateViewCatasto()` | `—` | 55 |
| `GetTemplateView()` | `$bRefresh=false` | 69 |
| `GetTemplateViewAttributi()` | `—` | 75 |
| `GetTemplateViewGestore()` | `—` | 96 |
| `GetListaImmobili()` | `$comune = ""` | 116 |
| `GetAlloggi()` | `$bBozze=false,$bCestinate=false,$bAsObject=true,$user=null` | 141 |
| `GetDescrizione()` | `—` | 149 |
| `SetDescrizione()` | `$var = ""` | 154 |
| `GetTipologia()` | `$bAsText=true` | 161 |
| `SetTipologia()` | `$var = 0` | 171 |
| `GetComune()` | `$bDescr=true` | 178 |
| `SetComune()` | `$var = ""` | 184 |
| `GetGeolocalizzazione()` | `—` | 191 |
| `SetGeolocalizzazione()` | `$var = ""` | 196 |
| `GetUbicazione()` | `$bDescr=true` | 203 |
| `SetUbicazione()` | `$var = 0` | 215 |
| `GetIndirizzo()` | `—` | 222 |
| `SetIndirizzo()` | `$var = ""` | 227 |
| `GetNumeroAlloggiTot()` | `—` | 234 |
| `GetGestione()` | `$bAsObject=true` | 242 |
| `GetGestore()` | `—` | 251 |
| `GetGestioneDal()` | `—` | 261 |
| `SetGestione()` | `$var = array(` | 269 |
| `GetCatasto()` | `$bAsObject=true` | 281 |
| `SetCatasto()` | `$var = ""` | 290 |
| `GetAttributi()` | `$bAsObject=true` | 298 |
| `GetInterventi()` | `$bAsObject=true` | 308 |
| `IsCondominioMisto()` | `—` | 318 |
| `SetAttributi()` | `$var = ""` | 325 |
| `SetInterventi()` | `$var = ""` | 332 |
| `GetZonaUrbanistica()` | `$bDescr=true` | 340 |
| `SetZonaUrbanistica()` | `$var = ""` | 354 |
| `GetPiani()` | `—` | 361 |
| `SetPiani()` | `$var = 0` | 366 |
| `GetNote()` | `—` | 373 |
| `SetNote()` | `$var = ""` | 378 |
| `Validate()` | `—` | 385 |
| `GetDisplayName()` | `—` | 426 |
| `CsvDataHeader()` | `$separator = "|"` | 440 |
| `CsvData()` | `$separator = "|"` | 447 |
| `Parse()` | `$params = array(` | 459 |
| `GetUserCaps()` | `$user = null` | 470 |
| `AddNew()` | `$params, $user = null` | 496 |
| `Sync()` | `$user = null` | 510 |

### `utils/modules/sicar_9/classes/AA_SicarModule.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SicarModule` | AA_GenericModule | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user = null, $bDefaultSections = true` | 123 |
| `TemplateGenericNavbar_Desktop()` | `$level = 1, $last = false, $refresh_view = true` | 286 |
| `TemplateLayout()` | `—` | 306 |
| `TemplateSection_Placeholder()` | `—` | 312 |
| `GetDataSectionBozze_List()` | `$params = array(` | 318 |
| `GetDataSectionBozze_CustomDataTemplate()` | `$data = array(` | 330 |
| `CustomDataSectionIsFiltered()` | `$params = array(` | 487 |
| `GetDataSectionBozze_CustomFilter()` | `$params = array(` | 497 |
| `TemplateSection_Bozze()` | `$params = array(` | 535 |
| `GetDataSectionPubblicate_List()` | `$params = array(` | 559 |
| `GetDataSectionPubblicate_CustomDataTemplate()` | `$data = array(` | 572 |
| `GetDataSectionPubblicate_CustomFilter()` | `$params = array(` | 729 |
| `TemplateSection_Desktop()` | `—` | 766 |
| `TemplateSection_News()` | `—` | 892 |
| `TemplateSection_Pubblicate()` | `$params = array(` | 937 |
| `Template_GetSicarAddNewAlloggioDlg()` | `—` | 958 |
| `Template_GetSicarModifyAlloggioDlg()` | `$object=null` | 1064 |
| `Template_GetSicarAddNewImmobileDlg()` | `—` | 1222 |
| `Template_GetSicarAddNewEnteDlg()` | `—` | 1365 |
| `Template_GetSicarAddNewNucleoDlg()` | `—` | 1438 |
| `Template_GetSicarAddNewStatoOccupazioneAlloggioDlg()` | `$object=null` | 1505 |
| `Template_GetSicarAddNewStatoInterventiAlloggioDlg()` | `$object=null` | 1592 |
| `Template_GetSicarAddNewInterventoImmobileDlg()` | `$object=null` | 1694 |
| `Template_GetSicarModifyInterventoImmobileDlg()` | `$object=null,$id_intervento` | 1793 |
| `Template_GetSicarModifyStatoInterventiAlloggioDlg()` | `$object=null,$id_intervento=null` | 1902 |
| `Template_GetSicarModifyStatoOccupazioneAlloggioDlg()` | `$object=null,$dal=""` | 2042 |
| `Template_GetSicarModifyNucleoDlg()` | `$object` | 2147 |
| `Template_GetSicarModifyEnteDlg()` | `$object=null` | 2214 |
| `Template_GetSicarModifyImmobileDlg()` | `$immobile=null` | 2294 |
| `Task_GetSicarDeleteImmobileDlg()` | `$task` | 2438 |
| `Template_GetSicarDeleteImmobileDlg()` | `$object=null` | 2469 |
| `Template_GetSicarDeleteNucleoDlg()` | `$object=null` | 2519 |
| `Template_GetSicarDeleteStatoOccupazioneAlloggioDlg()` | `$object=null,$dal=""` | 2594 |
| `Template_GetSicarDeleteStatoInterventiAlloggioDlg()` | `$object=null,$id_intervento=null` | 2678 |
| `Template_GetSicarDeleteInterventoImmobileDlg()` | `$object=null,$id_intervento=null` | 2758 |
| `Task_GetSicarDeleteNucleoDlg()` | `$task` | 2838 |
| `Task_GetSicarListaCodiciIstat()` | `$task` | 2861 |
| `Task_GetSicarAddNewDlg()` | `$task` | 2899 |
| `Task_GetSicarBozzeFilterDlg()` | `$task` | 2912 |
| `Task_GetSicarPubblicateFilterDlg()` | `$task` | 2920 |
| `Task_GetSicarSearchImmobiliDlg()` | `$task` | 2928 |
| `Task_GetSicarInterventiImmobileDlg()` | `$task` | 2942 |
| `Task_GetSicarSearchEntiDlg()` | `$task` | 2956 |
| `Task_GetSicarSearchNucleiDlg()` | `$task` | 2970 |
| `Task_GetSicarOperatoriEnteDlg()` | `$task` | 2984 |
| `Template_GetSicarSearchImmobiliDlg()` | `—` | 3006 |
| `Template_GetSicarInterventiImmobileDlg()` | `—` | 3021 |
| `Template_GetSicarSearchEntiDlg()` | `—` | 3036 |
| `Template_GetSicarSearchNucleiDlg()` | `—` | 3051 |
| `Template_GetSicarOperatoriEnteDlg()` | `$ente=null` | 3066 |
| `Template_DatatableOperatoriEnte()` | `$id="",$ente=null` | 3081 |
| `Template_GetSicarDetailImmobileDlg()` | `$immobile=null` | 3197 |
| `Template_GetSicarDetailStatoOccupazioneAlloggioDlg()` | `$stato=null` | 3218 |
| `Template_DatatableSearchImmobili()` | `$id=""` | 3311 |
| `Template_DatatableInterventiImmobile()` | `$id="",$object=null,$canModify=false` | 3412 |
| `Template_DatatableSearchEnti()` | `$id=""` | 3569 |
| `Template_DatatableSearchNuclei()` | `$id=""` | 3670 |
| `TemplateBozzeFilterDlg()` | `$params=array(` | 3789 |
| `TemplatePubblicateFilterDlg()` | `$params=array(` | 3854 |
| `Task_GetSicarAddNewImmobileDlg()` | `$task` | 3919 |
| `Task_GetSicarAddNewEnteDlg()` | `$task` | 3932 |
| `Task_GetSicarAddNewNucleoDlg()` | `$task` | 3945 |
| `Task_GetSicarModifyEnteDlg()` | `$task` | 3958 |
| `Task_GetSicarModifyNucleoDlg()` | `$task` | 3986 |
| `Task_GetSicarModifyImmobileDlg()` | `$task` | 4014 |
| `Task_GetSicarModifyDlg()` | `$task` | 4042 |
| `Task_GetSicarAddNewStatoInterventiAlloggioDlg()` | `$task` | 4070 |
| `Task_GetSicarAddNewInterventoImmobileDlg()` | `$task` | 4098 |
| `Task_GetSicarModifyInterventoImmobileDlg()` | `$task` | 4127 |
| `Task_GetSicarModifyStatoInterventiAlloggioDlg()` | `$task` | 4171 |
| `Task_GetSicarAddNewStatoOccupazioneAlloggioDlg()` | `$task` | 4208 |
| `Task_GetSicarModifyStatoOccupazioneAlloggioDlg()` | `$task` | 4236 |
| `Task_GetSicarDeleteStatoOccupazioneAlloggioDlg()` | `$task` | 4270 |
| `Task_GetSicarDeleteStatoInterventiAlloggioDlg()` | `$task` | 4304 |
| `Task_GetSicarDeleteInterventoImmobileDlg()` | `$task` | 4346 |
| `Task_GetSicarDetailImmobileDlg()` | `$task` | 4388 |
| `Task_GetSicarDetailStatoOccupazioneAlloggioDlg()` | `$task` | 4410 |
| `Template_GetSicarModifyDlg()` | `$object` | 4440 |
| `GetAlloggio()` | `$id = 0, $user = null` | 4446 |
| `SearchAlloggi()` | `$params = array(` | 4451 |
| `AddAlloggio()` | `$alloggio = null, $user = null` | 4457 |
| `Task_GetSicarTipologie()` | `$task` | 4466 |
| `Task_GetSicarUbicazioni()` | `$task` | 4482 |
| `Task_GetSicarZoneUrbanistiche()` | `$task` | 4513 |
| `Task_GetSicarComuni()` | `$task` | 4544 |
| `Task_ExportSicarCsv()` | `$task` | 4567 |
| `Task_AddNewAlloggioSicar()` | `$task` | 4605 |
| `Task_AddNewStatoOccupazioneAlloggioSicar()` | `$task` | 4652 |
| `Task_AddNewStatoInterventiAlloggioSicar()` | `$task` | 4824 |
| `Task_AddNewInterventoImmobileSicar()` | `$task` | 4990 |
| `Task_UpdateInterventoImmobileSicar()` | `$task` | 5154 |
| `Task_UpdateStatoInterventiAlloggioSicar()` | `$task` | 5313 |
| `Task_DeleteStatoInterventiAlloggioSicar()` | `$task` | 5488 |
| `Task_DeleteInterventoImmobileSicar()` | `$task` | 5576 |
| `Task_GetNavbarContent()` | `$task` | 5654 |
| `Task_AddNewImmobileSicar()` | `$task` | 5665 |
| `Task_AddNewEnteSicar()` | `$task` | 5794 |
| `Task_AddNewNucleoSicar()` | `$task` | 5840 |
| `Task_UpdateEnteSicar()` | `$task` | 5892 |
| `Task_UpdateNucleoSicar()` | `$task` | 5947 |
| `Task_UpdateImmobileSicar()` | `$task` | 6007 |
| `Task_DeleteImmobileSicar()` | `$task` | 6150 |
| `Task_DeleteNucleoSicar()` | `$task` | 6188 |
| `Task_UpdateSicar()` | `$task` | 6226 |
| `Task_UpdateStatoOccupazioneAlloggioSicar()` | `$task` | 6334 |
| `Task_DeleteStatoOccupazioneAlloggioSicar()` | `$task` | 6529 |
| `Task_DeleteSicar()` | `$task` | 6662 |
| `Task_PublishSicar()` | `$task` | 6675 |
| `Task_TrashSicar()` | `$task` | 6681 |
| `Task_ResumeSicar()` | `$task` | 6696 |
| `Task_ReassignSicar()` | `$task` | 6702 |
| `Task_GetSicarTrashDlg()` | `$task` | 6708 |
| `Task_GetSicarPublishDlg()` | `$task` | 6730 |
| `Task_GetSicarReassignDlg()` | `$task` | 6756 |
| `Task_GetSicarResumeDlg()` | `$task` | 6782 |
| `Task_GetSicarDeleteDlg()` | `$task` | 6804 |
| `TemplateSection_Detail()` | `$params` | 6829 |
| `TemplateSicarDettaglio_Generale_Tab()` | `$object = null` | 6851 |
| `TemplateDettaglio_Occupazione()` | `$object=null,$id="", $canModify=false` | 7092 |
| `TemplateDettaglio_Interventi()` | `$object=null,$id="", $canModify=false` | 7191 |
| `TemplateSection_Immobili()` | `$params=array(` | 7304 |
| `TemplateSection_Enti()` | `$params=array(` | 7431 |
| `TemplateSection_Nuclei()` | `$params=array(` | 7506 |
| `TemplateSection_Finanziamenti()` | `$params=array(` | 7611 |
| `TemplateSection_Graduatorie()` | `$params=array(` | 7675 |
| `GetTipologiaDesc()` | `$tipologia_id` | 7739 |
| `GetStatusDesc()` | `$status` | 7750 |

### `utils/modules/sicar_9/classes/AA_SicarNucleo.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SicarNucleo` | AA_GenericParsableDbObject | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$params = array(` | 9 |
| `GetTemplateView()` | `$bResfresh=false` | 32 |
| `Delete()` | `$user = null` | 79 |
| `GetStatoAssegnazione()` | `$bAsObject=true,$last=true` | 124 |
| `GetAlloggioAttuale()` | `$bAsObject=true` | 185 |
| `GetComponenti()` | `$bAsObject=true` | 202 |
| `GetIseePreview()` | `—` | 223 |
| `SetComponenti()` | `$var=""` | 228 |
| `GetListaNuclei()` | `—` | 249 |
| `GetDescrizione()` | `—` | 273 |
| `SetDescrizione()` | `$var = ""` | 278 |
| `GetCf()` | `—` | 285 |
| `SetCf()` | `$var = ""` | 290 |
| `GetIndirizzo()` | `—` | 297 |
| `SetIndirizzo()` | `$var = ""` | 302 |
| `GetNote()` | `—` | 309 |
| `SetNote()` | `$var = ""` | 314 |
| `Validate()` | `—` | 321 |
| `GetDisplayName()` | `—` | 345 |
| `GetComune()` | `$bDescr=true` | 356 |
| `SetComune()` | `$var = ""` | 367 |
| `CsvDataHeader()` | `$separator = "|"` | 374 |
| `CsvData()` | `$separator = "|"` | 380 |
| `Parse()` | `$params = array(` | 391 |
| `GetUserCaps()` | `$user = null` | 402 |
| `HasAlloggio()` | `—` | 424 |
| `AddNew()` | `$params, $user = null` | 440 |
| `Sync()` | `$user = null` | 456 |

### `utils/modules/sicar_9/classes/AA_SicarRichiestaFinanziamento.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_SicarRichiestaFinanziamento` | AA_GenericParsableDbObject | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$params = array(` | 9 |
| `GetTemplateView()` | `$bRefresh=false` | 41 |
| `GetListaFinanziamenti()` | `—` | 47 |
| `GetDenominazione()` | `—` | 71 |
| `SetDenominazione()` | `$var = ""` | 76 |
| `GetName()` | `—` | 82 |
| `SetName()` | `$var = ""` | 87 |
| `GetTipologia()` | `$bAsText=true` | 94 |
| `SetTipologia()` | `$var = 0` | 104 |
| `GetNote()` | `—` | 111 |
| `SetNote()` | `$var = ""` | 116 |
| `Validate()` | `—` | 123 |
| `GetDisplayName()` | `—` | 140 |
| `CsvDataHeader()` | `$separator = "|"` | 148 |
| `CsvData()` | `$separator = "|"` | 154 |
| `Parse()` | `$params = array(` | 164 |
| `GetUserCaps()` | `$user = null` | 175 |
| `AddNew()` | `$params, $user = null` | 201 |
| `Sync()` | `$user = null` | 215 |

### `utils/modules/sicar_9/classes/AA_Sicar_Const.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Sicar_Const` | AA_Const | 2 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetListaTipologie()` | `$bSimpleArray=false` | 13 |
| `GetListaTipologieProgFinanziamento()` | `$bSimpleArray=false` | 34 |
| `GetListaTipologieEnte()` | `$bSimpleArray=false` | 55 |
| `GetListaStatiConservazioneAlloggio()` | `$bSimpleArray=false` | 72 |
| `GetListaStatoLavori()` | `$bSimpleArray=false` | 89 |
| `GetListaTipologieCanoneAlloggio()` | `$bSimpleArray=false` | 106 |
| `GetListaTipologieUtilizzoAlloggio()` | `$bSimpleArray=false` | 123 |
| `GetListaUbicazioni()` | `$bSimpleArray=false` | 139 |
| `GetListaTipologieOccupazione()` | `$bSimpleArray=false` | 156 |
| `GetListaTipologieIntervento()` | `$bSimpleArray=false` | 182 |
| `GetListaZoneUrbanistiche()` | `—` | 198 |
| `GetComuneDescrFromCodiceIstat()` | `$codice_istat` | 219 |
| `GetConfig()` | `$key = null` | 462 |
| `GetMessage()` | `$key, $locale = 'it'` | 482 |
| `GetListaFruibilitaDisabile()` | `—` | 487 |
| `ValidateField()` | `$field, $value` | 497 |

## `modules/sier_5/`

### `utils/modules/sier_5/config.php`

### `utils/modules/sier_5/lib.js.php`

| Metodo | Parametri | Linea |
|---|---|---|
| `AA_SierWebAppGenericParams()` | `—` | 238 |

### `utils/modules/sier_5/lib.php`

| Metodo | Parametri | Linea |
|---|---|---|
| `GetFlags()` | `—` | 36 |
| `GetFlagsForTags()` | `—` | 55 |
| `GetCircoscrizioni()` | `—` | 85 |
| `GetCircoscrizione()` | `$id=0` | 104 |
| `GetTipoAllegati()` | `—` | 126 |
| `GetTipoRendicontiServizi()` | `—` | 154 |
| `GetDestinatari()` | `—` | 180 |
| `Parse()` | `$values=null` | 203 |
| `GetListe()` | `—` | 213 |
| `toArray()` | `$full=false` | 218 |
| `__construct()` | `$params=null` | 230 |
| `SetProp()` | `$prop="",$value=""` | 247 |
| `GetProp()` | `$prop=""` | 253 |
| `GetProps()` | `—` | 260 |
| `Parse()` | `$values=null` | 272 |
| `toArray()` | `$full=false` | 283 |
| `GetCandidati()` | `$circoscrizione=""` | 295 |
| `__construct()` | `$params=null` | 300 |
| `SetProp()` | `$prop="",$value=""` | 318 |
| `GetProp()` | `$prop=""` | 324 |
| `GetProps()` | `—` | 331 |
| `Parse()` | `$values=null` | 343 |
| `toArray()` | `$full=false` | 354 |
| `__construct()` | `$params=null` | 365 |
| `SetProp()` | `$prop="",$value=""` | 386 |
| `GetProp()` | `$prop=""` | 392 |
| `GetProps()` | `—` | 399 |
| `Parse()` | `$values=null` | 411 |
| `toArray()` | `$full=false` | 422 |
| `AddLog()` | `$msg="",$user="",$oc="",$date=""` | 437 |
| `GetOperatori()` | `$bAsObject=false` | 454 |
| `GetComunicazioni()` | `$bAsObject=false` | 474 |
| `GetSavedLogs()` | `$bAsObject=false` | 494 |
| `GetLogs()` | `$bAsObject=false` | 523 |
| `GetAffluenza()` | `$bAsObject=false` | 529 |
| `GetAnalisiRisultati()` | `$bAsObject=false` | 551 |
| `GetRisultati()` | `$bAsObject=false` | 573 |
| `GetRendiconti()` | `$bAsObject=false` | 613 |
| `SetOperatori()` | `$operatori=""` | 653 |
| `SetComunicazioni()` | `$comunicazioni=""` | 673 |
| `SetAffluenza()` | `$val=""` | 693 |
| `SetAnalisiRisultati()` | `$val=""` | 713 |
| `SetRisultati()` | `$val=""` | 733 |
| `SetRendiconti()` | `$val=""` | 753 |
| `SetFeedRisultati()` | `$val=""` | 774 |
| `__construct()` | `$params=null` | 795 |
| `GetFeedRisultati()` | `$bAsObject=false` | 830 |
| `SetProp()` | `$prop="",$value=""` | 933 |
| `GetProp()` | `$prop=""` | 939 |
| `GetProps()` | `—` | 946 |
| `DeleteData()` | `$idData = 0, $user = null` | 964 |
| `serialize()` | `—` | 997 |
| `UpdateAnalisiRisultati()` | `$comune=null,$circoscrizione=null` | 1032 |
| `AnalizeComunicazioni()` | `$comunicazioni=null,$comune=null` | 1056 |
| `AnalizeRendiconti()` | `$rendiconti=null,$comune=null` | 1143 |
| `AnalizeRisultati()` | `$risultati=null,$circoscrizione=0,$comune=null` | 1292 |
| `GetAbilitazioni()` | `—` | 1498 |
| `GetGiornate()` | `—` | 1504 |
| `GetControlPannel()` | `—` | 1517 |
| `SetControlPannel()` | `$val=null` | 1531 |
| `CloneData()` | `$idData = 0, $user = null` | 1541 |
| `__construct()` | `$id=0, $user=null` | 1551 |
| `GetCoalizioni()` | `$params=array(` | 1589 |
| `BuildRendicontiSerial()` | `$rendiconti=null` | 1650 |
| `BuildCandidatiFeed()` | `$params=array(` | 1667 |
| `UpdateComuneFeedRisultati()` | `$id_comune=0,$user=null,$bReset=false,$bInitializeOnly=false,$bUpdateComune=true` | 1732 |
| `BuildRisultatiAffluenzaFeed()` | `$params=array(` | 2110 |
| `GetCoalizione()` | `$id_coalizione=0` | 2705 |
| `GetListe()` | `$coalizione=null,$orderedForCircoscrizione=0` | 2755 |
| `GetLista()` | `$id_lista=""` | 2800 |
| `GetOCEmailCSV()` | `—` | 2835 |
| `GetCSVImportMap()` | `$toCsv=false` | 2859 |
| `ExportCorpoElettoraleComuniCSV()` | `$circoscrizione=null` | 2913 |
| `ExportDatiComuniCSV()` | `$circoscrizione=null` | 2991 |
| `ExportDatiComuniCSV_Regionale()` | `—` | 3035 |
| `ExportDatiComuniCSV_Comunale()` | `—` | 3090 |
| `ExportDatiComuniCSV_PreferenzeCircoscrizionali()` | `—` | 3148 |
| `ExportDatiComuniCSV_Circoscrizionale()` | `—` | 3207 |
| `ExportDatiComuniCSV_PreferenzeComunali()` | `—` | 3269 |
| `ExportDatiComuniCSV_DatiVotanti()` | `—` | 3329 |
| `ExportCandidatiCSV()` | `$circoscrizione=null` | 3399 |
| `GetCandidati()` | `$coalizione=null,$lista=null,$circoscrizione=0` | 3425 |
| `GetCandidato()` | `$id=""` | 3474 |
| `GetComuni()` | `$circoscrizione=null,$params=null` | 3510 |
| `GetComune()` | `$id="",$cf_oc=""` | 3619 |
| `Search()` | `$params=array(` | 3684 |
| `GetUserCaps()` | `$user=null` | 3704 |
| `AddNew()` | `$object=null,$user=null,$bSaveData=true` | 3737 |
| `GetAllegato()` | `$id=null, $user=null` | 3775 |
| `AddNewLista()` | `$lista=null, $coalizione=null, $user=null` | 3833 |
| `UpdateLista()` | `$lista=null, $coalizione=null, $user=null` | 3920 |
| `AddNewCoalizione()` | `$newCoalizione=null, $user=null` | 3992 |
| `UpdateCoalizione()` | `$newCoalizione=null, $user=null,$log=""` | 4061 |
| `UpdateComune()` | `$newComune=null, $user=null,$AppendLog=""` | 4135 |
| `DeleteCoalizione()` | `$coalizione=null, $user=null` | 4251 |
| `DeleteLista()` | `$lista=null,$coalizione=null, $user=null` | 4378 |
| `AddNewCandidato()` | `$candidato=null, $user=null` | 4505 |
| `AddNewComune()` | `$comune=null, $user=null` | 4586 |
| `AddNewComuneMulti()` | `$data=array(` | 4666 |
| `UpdateCandidato()` | `$candidato=null, $user=null,$logMsg=""` | 4718 |
| `AddNewAllegato()` | `$allegato=null, $user=null` | 4786 |
| `UpdateAllegato()` | `$allegato=null, $user=null` | 4871 |
| `DeleteAllegato()` | `$allegato=null, $user=null` | 4957 |
| `DeleteCandidato()` | `$candidato=null, $user=null` | 5043 |
| `DeleteComune()` | `$comune=null, $user=null` | 5134 |
| `DeleteComuni()` | `$circoscrizione=null,$user=null` | 5221 |
| `ResetComunicazioniComuni()` | `$circoscrizione=null,$user=null,$resetCorpoElettorale=false` | 5262 |
| `ResetRisultatiComuni()` | `$circoscrizione=null,$user=null` | 5326 |
| `ResetFeedRisultatiComuni()` | `$circoscrizione=null,$user=null` | 5369 |
| `UpdateFeedRisultatiComuni()` | `$circoscrizione=null,$user=null` | 5423 |
| `UpdateFeedRisultatiGenerali()` | `$user=null` | 5477 |
| `ResetAffluenzaComuni()` | `$circoscrizione=null,$user=null` | 5537 |
| `GetAllegati()` | `$idData=0` | 5580 |
| `__construct()` | `—` | 5633 |
| `GetInstance()` | `—` | 5638 |
| `ChallengeLogin()` | `$cf="",$objectId=0` | 5654 |
| `VerifyLogin()` | `$token=""` | 5736 |
| `IsValid()` | `—` | 5781 |
| `IsLogged()` | `—` | 5785 |
| `GetOperatoreComunaleNome()` | `—` | 5789 |
| `SetOperatoreComunaleNome()` | `$val=""` | 5793 |
| `GetOperatoreComunaleCognome()` | `—` | 5797 |
| `SetOperatoreComunaleCognome()` | `$val=""` | 5801 |
| `GetOperatoreComunaleRuolo()` | `—` | 5805 |
| `SetOperatoreComunaleRuolo()` | `$val=0` | 5809 |
| `GetOperatoreComunaleLastlogin()` | `—` | 5813 |
| `SetOperatoreComunaleLastlogin()` | `$val=""` | 5817 |
| `GetOperatoreComunaleCf()` | `—` | 5821 |
| `SetOperatoreComunaleCf()` | `$val=""` | 5825 |
| `GetOperatoreComunaleComune()` | `—` | 5829 |
| `SetOperatoreComunaleComune()` | `$val=0` | 5833 |
| `GetOperatoreComunaleObjectId()` | `—` | 5837 |
| `SetOperatoreComunaleObject()` | `$val=0` | 5841 |
| `IsOperatoreComunaleUIEnabled()` | `—` | 5908 |
| `EnableOperatoreComunaleInterface()` | `$val=true` | 5913 |
| `__construct()` | `$user=null,$bDefaultSections=true` | 5919 |
| `GetInstance()` | `$user=null` | 6243 |
| `TemplateLayout()` | `—` | 6254 |
| `TemplateSection_Placeholder()` | `—` | 6260 |
| `TemplateSection_Pubblicate()` | `$params=array(` | 6266 |
| `GetDataSectionPubblicate_List()` | `$params=array(` | 6280 |
| `GetDataSectionPubblicate_CustomFilter()` | `$params = array(` | 6286 |
| `GetDataSectionPubblicate_CustomDataTemplate()` | `$data = array(` | 6297 |
| `Template_GetSierComuneAddNewMultiDlg()` | `$object=null` | 6323 |
| `Template_GetSierComuneRisultatiPreferenzeCsvImportDlg()` | `$object=null,$comune=null` | 6367 |
| `Template_GetSierOCRisultatiPreferenzeCsvImportDlg()` | `$object=null,$comune=null` | 6417 |
| `Template_GetSierComuneRisultatiPreferenzeCsvImportPreviewDlg()` | `$object=null,$comune=null` | 6467 |
| `Template_GetSierOCRisultatiPreferenzeCsvImportPreviewDlg()` | `$object=null,$comune=null` | 6550 |
| `Template_GetSierComuneAddNewMultiPreviewDlg()` | `$object=null` | 6616 |
| `Template_GetSierComuneAddNewMultiResultDlg()` | `$object=null,$count=array(0,0` | 6686 |
| `TemplateSection_Bozze()` | `$params=array(` | 6709 |
| `GetDataSectionBozze_List()` | `$params=array(` | 6744 |
| `GetDataSectionBozze_CustomFilter()` | `$params = array(` | 6756 |
| `GetDataSectionBozze_CustomDataTemplate()` | `$data = array(` | 6768 |
| `Template_GetSierPublishDlg()` | `$params` | 6797 |
| `Template_GetSierDeleteDlg()` | `$params` | 6865 |
| `Template_GetSierAddNewDlg()` | `—` | 6871 |
| `Template_GetSierAddNewGiornataDlg()` | `$object=null` | 6921 |
| `Template_GetSierModifyGiornataDlg()` | `$object=null,$data=""` | 6966 |
| `Template_GetSierAddNewListaDlg()` | `$object=null,$coalizione=null` | 7024 |
| `Template_GetSierModifyListaDlg()` | `$object=null,$coalizione=null,$lista=null` | 7080 |
| `Template_GetSierAddNewCoalizioneDlg()` | `$object=null` | 7136 |
| `Template_GetSierModifyCoalizioneDlg()` | `$object=null,$coalizione=null` | 7182 |
| `Template_GetSierAddNewCandidatoDlg()` | `$object=null,$lista=null,$id_circoscrizione=0` | 7238 |
| `Template_GetSierComuneOperatoriAddNewDlg()` | `$object=null,$comune=null` | 7320 |
| `Template_GetSierComuneOperatoriModifyDlg()` | `$object=null,$comune=null,$operatore=null` | 7372 |
| `Template_GetSierModifyCandidatoDlg()` | `$object=null,$candidato=null` | 7434 |
| `Template_GetOCVerifyLoginDlg()` | `$object=null` | 7514 |
| `Template_GetSierAddNewCandidatoCVDlg()` | `$object=null,$candidato=null` | 7548 |
| `Template_GetSierModifyPresidenteCVDlg()` | `$object=null,$coalizione=null` | 7592 |
| `Template_GetSierModifyPresidenteCGDlg()` | `$object=null,$coalizione=null` | 7636 |
| `Template_GetSierModifyCandidatoCVDlg()` | `$object=null,$candidato=null` | 7680 |
| `Template_GetSierAddNewCandidatoCGDlg()` | `$object=null,$candidato=null` | 7725 |
| `Template_GetSierModifyCandidatoCGDlg()` | `$object=null,$candidato=null` | 7769 |
| `Template_GetSierAddNewAllegatoDlg()` | `$object=null` | 7815 |
| `Template_GetSierModifyAllegatoDlg()` | `$object=null,$allegato=null` | 7896 |
| `Template_GetSierOCRendicontiModifyAllegatoDlg()` | `$object=null,$allegato=null` | 8000 |
| `Template_GetSierCopyAllegatoDlg()` | `$object=null,$allegato=null` | 8058 |
| `Template_GetSierTrashAllegatoDlg()` | `$object=null,$allegato=null` | 8162 |
| `Template_GetSierTrashComuneDlg()` | `$object=null,$comune=null` | 8212 |
| `Template_GetSierTrashCandidatoCVDlg()` | `$object=null,$candidato=null` | 8261 |
| `Template_GetSierTrashCandidatoCGDlg()` | `$object=null,$candidato=null` | 8311 |
| `Template_GetSierTrashPresidenteCGDlg()` | `$object=null,$coalizione=null` | 8361 |
| `Template_GetSierTrashPresidenteCVDlg()` | `$object=null,$coalizione=null` | 8411 |
| `Template_GetSierTrashCandidatoDlg()` | `$object=null,$candidato=null` | 8461 |
| `Template_GetSierComuneOperatoriTrashDlg()` | `$object=null,$comune=null,$operatore=null` | 8508 |
| `Template_GetSierComuneAffluenzaTrashDlg()` | `$object=null,$comune=null,$giornata=null` | 8559 |
| `Template_GetSierComuneRisultatiPreferenzeTrashDlg()` | `$object=null,$comune=null,$candidato=null` | 8608 |
| `Task_AddNewSierGiornata()` | `$task` | 8658 |
| `Task_UpdateSierGiornata()` | `$task` | 8726 |
| `Task_UpdateSierControlPannel()` | `$task` | 8802 |
| `Task_DeleteSierGiornata()` | `$task` | 8961 |
| `Template_GetSierTrashGiornataDlg()` | `$object=null,$giornata=""` | 9018 |
| `Template_GetSierTrashCoalizioneDlg()` | `$object=null,$coalizione=null` | 9066 |
| `Template_GetSierTrashListaDlg()` | `$object=null,$coalizione=null,$lista=null` | 9113 |
| `Template_GetSierAnalisiRisultatiDlg()` | `$object=null,$comune=null` | 9160 |
| `Template_GetSierAnalisiComunicazioniDlg()` | `$object=null,$comune=null` | 9196 |
| `Template_GetSierAnalisiCorpoElettoraleDlg()` | `$object=null,$comune=null` | 9229 |
| `Task_AddNewSierAllegato()` | `$task` | 9289 |
| `Task_AddNewSierCandidato()` | `$task` | 9447 |
| `Task_GetSierComuneAddNewMultiResultDlg()` | `$task` | 9533 |
| `Task_AddNewSierCandidatoCV()` | `$task` | 9552 |
| `Task_AddNewSierCandidatoCG()` | `$task` | 9727 |
| `Task_UpdateSierCandidato()` | `$task` | 9896 |
| `Task_UpdateSierCandidatoCG()` | `$task` | 9967 |
| `Task_UpdateSierCandidatoCV()` | `$task` | 10135 |
| `Task_UpdateSierPresidenteCV()` | `$task` | 10303 |
| `Task_UpdateSierPresidenteCG()` | `$task` | 10468 |
| `Task_DeleteSierCandidatoCV()` | `$task` | 10633 |
| `Task_DeleteSierCandidatoCG()` | `$task` | 10719 |
| `Task_DeleteSierPresidenteCG()` | `$task` | 10805 |
| `Task_DeleteSierPresidenteCV()` | `$task` | 10878 |
| `Task_DeleteSierCandidato()` | `$task` | 10951 |
| `Task_AddNewSierLista()` | `$task` | 11004 |
| `Task_UpdateSierLista()` | `$task` | 11175 |
| `Task_AddNewSierCoalizione()` | `$task` | 11382 |
| `Task_UpdateSierCoalizione()` | `$task` | 11511 |
| `Task_DeleteSierCoalizione()` | `$task` | 11732 |
| `Task_DeleteSierLista()` | `$task` | 11776 |
| `Template_GetSierModifyDlg()` | `$object=null` | 11820 |
| `Template_GetSierControlPannelDlg()` | `$object=null` | 11910 |
| `Template_GetSierExportDatiServizioElettoraleDlg()` | `$object=null` | 12261 |
| `Template_BuildSorteggioComuniRendicontazione()` | `$object=null` | 12361 |
| `Template_RngEstrazionePage()` | `$object=null` | 12563 |
| `Template_GetSierConfirmResetComunicazioniComuniDlg()` | `$object=null` | 12747 |
| `Template_GetSierComuneRendicontiConfirmTrashServiziDlg()` | `$object=null,$comune=null,$id_servizio` | 12780 |
| `Template_GetSierOCRendicontiConfirmTrashServiziDlg()` | `$object=null,$comune=null,$id_servizio` | 12822 |
| `Template_GetSierOCRendicontiConfirmTrashAllegatiDlg()` | `$object=null,$comune=null,$allegato=null` | 12864 |
| `Template_GetSierComuneRendicontiConfirmTrashPersonaleDeterminatoDlg()` | `$object=null,$comune=null,$id_personale_det` | 12906 |
| `Template_GetSierOCRendicontiConfirmTrashPersonaleDeterminatoDlg()` | `$object=null,$comune=null,$id_personale_det` | 12949 |
| `Template_GetSierConfirmResetFeedRisultatiComuniDlg()` | `$object=null` | 12990 |
| `Template_GetSierConfirmUpdateFeedRisultatiGeneraliDlg()` | `$object=null` | 13023 |
| `Template_GetSierConfirmResetRisultatiComuniDlg()` | `$object=null` | 13056 |
| `Template_GetSierConfirmDeleteSorteggioDocDlg()` | `$object=null` | 13089 |
| `Template_GetSierConfirmTrashWebAppQRCodeDlg()` | `$object=null` | 13121 |
| `Template_GetSierConfirmUpdateFeedRisultatiComuniDlg()` | `$object=null` | 13154 |
| `Template_OC_ConfirmCertCorpoElettoraleDlg()` | `$object=null,$comune=null` | 13187 |
| `Template_GetSierConfirmResetAffluenzaComuniDlg()` | `$object=null` | 13220 |
| `Template_GetSierHelpDlg()` | `—` | 13252 |
| `TemplateSection_Detail()` | `$params` | 13313 |
| `TemplateSierDettaglio_Generale_Tab()` | `$object=null` | 13332 |
| `TemplateSection_OC_Login()` | `$object=null` | 13438 |
| `TemplateSection_OC_Desktop()` | `$object=null` | 13516 |
| `Template_OC_Generale()` | `$id,$object=null,$comune=null,$modify=false` | 13840 |
| `Template_OC_Comunicazioni()` | `$id,$object=null,$comune=null,$modify=false` | 14155 |
| `Template_OC_Documenti()` | `$id,$object=null,$comune=null,$modify=false` | 14193 |
| `Template_OC_Rendiconti()` | `$id,$object=null,$comune=null,$canModify=false` | 14290 |
| `Template_OC_Risultati()` | `$id,$object=null,$comune=null,$modify=false` | 15608 |
| `Template_OC_Affluenza()` | `$id,$object=null,$comune=null,$modify=false` | 16077 |
| `TemplateSierDettaglio_Allegati_Tab()` | `$object=null` | 16146 |
| `TemplateDettaglio_Coalizioni_Riepilogo_Tab()` | `$object=null,$id="",$riepilogo_data=array(` | 16272 |
| `TemplateSierDettaglio_Coalizioni_Tab()` | `$object=null,$filterData=""` | 16383 |
| `TemplateSierDettaglio_Candidati_Tab()` | `$object=null` | 16711 |
| `TemplateSierDettaglio_Comuni_Tab()` | `$object=null` | 16870 |
| `Template_GetSierComuneLogsDlg()` | `$object = null, $comune = null` | 17321 |
| `Template_GetSierComuneFilterDlg()` | `$object=null,$params=null` | 17354 |
| `Task_UpdateSier()` | `$task` | 17430 |
| `Task_TrashSier()` | `$task` | 17462 |
| `Task_ResumeSier()` | `$task` | 17479 |
| `Task_PublishSier()` | `$task` | 17487 |
| `Task_ReassignSier()` | `$task` | 17495 |
| `Task_DeleteSier()` | `$task` | 17502 |
| `Task_GetSierComuneAddNewMultiPreviewCalc()` | `$task` | 17519 |
| `Task_SierComuneAddNewMulti()` | `$task` | 17682 |
| `Task_AddNewSier()` | `$task` | 17738 |
| `Task_GetSierWebApp()` | `$task` | 17755 |
| `Task_GetSierListaCandidati()` | `$task` | 17766 |
| `Task_GetSierComuneRisultatiPreferenzeCsvImportCalc()` | `$task` | 17804 |
| `Task_GetSierOCRisultatiPreferenzeCsvImportCalc()` | `$task` | 17972 |
| `Task_SierComuneRisultatiPreferenzeCsvImport()` | `$task` | 18136 |
| `Task_Update_OC_RisultatiPreferenzeCsvImport()` | `$task` | 18240 |
| `Task_OCLogin()` | `$task` | 18356 |
| `Task_OCVerifyLogin()` | `$task` | 18407 |
| `Task_GetSierOCAffluenzaAddNewDlg()` | `$task` | 18440 |
| `Task_GetSierOCModifyDatiGeneraliDlg()` | `$task` | 18481 |
| `Task_GetSierOCModifyCorpoElettoraleDlg()` | `$task` | 18522 |
| `Task_GetSierOCModifyComunicazioniDlg()` | `$task` | 18563 |
| `Task_GetSierOCConfirmCertCorpoElettoraleDlg()` | `$task` | 18604 |
| `Task_Update_OC_ComuneDatiGenerali()` | `$task` | 18645 |
| `Task_Update_OC_ComuneCorpoElettorale()` | `$task` | 18697 |
| `Task_GetSierModifyDlg()` | `$task` | 18777 |
| `Task_GetSierControlPannelDlg()` | `$task` | 18814 |
| `Task_GetSierConfirmDeleteSorteggioDocDlg()` | `$task` | 18840 |
| `Task_DoSorteggioComuniRendicontazione()` | `$task` | 18872 |
| `Task_DeleteSorteggioComuniRendicontazione()` | `$task` | 18946 |
| `Task_GetSierResumeDlg()` | `$task` | 19006 |
| `Task_GetSierPublishDlg()` | `$task` | 19043 |
| `Task_GetSierReassignDlg()` | `$task` | 19082 |
| `Task_GetSierTrashDlg()` | `$task` | 19122 |
| `Task_GetSierDeleteDlg()` | `$task` | 19161 |
| `Task_GetSierAddNewDlg()` | `$task` | 19191 |
| `Task_GetSierAddNewGiornataDlg()` | `$task` | 19214 |
| `Task_GetSierModifyGiornataDlg()` | `$task` | 19251 |
| `Task_GetSierTrashGiornataDlg()` | `$task` | 19297 |
| `Task_GetSierAddNewCandidatoDlg()` | `$task` | 19343 |
| `Task_GetSierModifyCandidatoDlg()` | `$task` | 19415 |
| `Task_GetSierAddNewCandidatoCVDlg()` | `$task` | 19475 |
| `Task_GetSierAddNewCandidatoCGDlg()` | `$task` | 19523 |
| `Task_GetSierModifyCandidatoCGDlg()` | `$task` | 19571 |
| `Task_GetSierTrashCandidatoCGDlg()` | `$task` | 19619 |
| `Task_GetSierTrashPresidenteCGDlg()` | `$task` | 19667 |
| `Task_GetSierTrashPresidenteCVDlg()` | `$task` | 19715 |
| `Task_GetSierTrashCandidatoCVDlg()` | `$task` | 19763 |
| `Task_GetSierTrashCandidatoDlg()` | `$task` | 19811 |
| `Task_GetSierModifyCandidatoCVDlg()` | `$task` | 19859 |
| `Task_GetSierModifyPresidenteCVDlg()` | `$task` | 19907 |
| `Task_GetSierModifyPresidenteCGDlg()` | `$task` | 19955 |
| `Task_GetSierAddNewAllegatoDlg()` | `$task` | 20003 |
| `Task_GetSierOCEmailsCSV()` | `$task` | 20040 |
| `Task_ExportCorpoElettoraleComuniCSV()` | `$task` | 20073 |
| `Task_ExportDatiComuniCSV()` | `$task` | 20106 |
| `Task_ExportCandidatiCSV()` | `$task` | 20139 |
| `Task_GetSierAddNewListaDlg()` | `$task` | 20172 |
| `Task_GetSierModifyListaDlg()` | `$task` | 20220 |
| `Task_GetSierAddNewCoalizioneDlg()` | `$task` | 20283 |
| `Task_GetSierComuneDatiGeneraliViewDlg()` | `$task` | 20320 |
| `Task_GetSierExportDatiServizioElettoraleDlg()` | `$task` | 20347 |
| `Task_ExportDatiComuniCSV_Regionale()` | `$task` | 20367 |
| `Task_ExportDatiComuniCSV_Circoscrizionale()` | `$task` | 20382 |
| `Task_ExportDatiComuniCSV_PreferenzeCircoscrizionali()` | `$task` | 20398 |
| `Task_ExportDatiComuniCSV_PreferenzeComunali()` | `$task` | 20413 |
| `Task_ExportDatiComuniCSV_DatiVotanti()` | `$task` | 20428 |
| `Task_ExportDatiComuniCSV_Comunale()` | `$task` | 20443 |
| `Task_GetSierComuneCorpoElettoraleViewDlg()` | `$task` | 20459 |
| `Task_GetSierComuneComunicazioniViewDlg()` | `$task` | 20487 |
| `Task_GetSierComuneAffluenzaViewDlg()` | `$task` | 20514 |
| `Task_GetSierComuneAddNewMultiDlg()` | `$task` | 20541 |
| `Task_GetSierComuneAddNewMultiPreviewDlg()` | `$task` | 20560 |
| `Task_GetSierComuneAffluenzaAddNewDlg()` | `$task` | 20579 |
| `Task_GetSierComuneRendicontiExportRasPdf()` | `$task` | 20606 |
| `Task_GetSierOCRendicontiExportPdf()` | `$task` | 20635 |
| `Task_GetSierComuneAffluenzaModifyDlg()` | `$task` | 20680 |
| `Task_GetSierComuneAffluenzaTrashDlg()` | `$task` | 20722 |
| `Task_GetSierComuneOperatoriAddNewDlg()` | `$task` | 20764 |
| `Task_GetSierAnalisiRisultatiDlg()` | `$task` | 20791 |
| `Task_GetSierAnalisiComunicazioniDlg()` | `$task` | 20818 |
| `Task_GetSierAnalisiCorpoElettoraleDlg()` | `$task` | 20845 |
| `Task_GetSierComuneOperatoriModifyDlg()` | `$task` | 20872 |
| `Task_GetSierComuneRisultatiGeneraliModifyDlg()` | `$task` | 20913 |
| `Task_GetSierComuneRendicontiSeggiModifyDlg()` | `$task` | 20940 |
| `Task_GetSierComuneRendicontiBuoniModifyDlg()` | `$task` | 20967 |
| `Task_GetSierComuneRendicontiImportoAmmessoModifyDlg()` | `$task` | 20994 |
| `Task_GetSierComuneRendicontiRasModifyDlg()` | `$task` | 21021 |
| `Task_GetSierComuneRendicontiRasLiquidazioniModifyDlg()` | `$task` | 21048 |
| `Task_GetSierComuneRendicontiServiziModifyDlg()` | `$task` | 21075 |
| `Task_GetSierOCRendicontiServiziModifyDlg()` | `$task` | 21102 |
| `Task_GetSierComuneRendicontiPersonaleDeterminatoModifyDlg()` | `$task` | 21136 |
| `Task_GetSierOCRendicontiPersonaleDeterminatoModifyDlg()` | `$task` | 21163 |
| `Task_GetSierOCRendicontiBuoniModifyDlg()` | `$task` | 21197 |
| `Task_GetSierOCRendicontiAllegatiModifyDlg()` | `$task` | 21231 |
| `Task_GetSierComuneRendicontiConfirmTrashServiziDlg()` | `$task` | 21275 |
| `Task_GetSierOCRendicontiConfirmTrashServiziDlg()` | `$task` | 21309 |
| `Task_GetSierComuneRendicontiConfirmTrashPersonaleDeterminatoDlg()` | `$task` | 21357 |
| `Task_GetSierOCRendicontiConfirmTrashPersonaleDeterminatoDlg()` | `$task` | 21391 |
| `Task_GetSierOCRendicontiConfirmTrashAllegatiDlg()` | `$task` | 21432 |
| `Task_GetSierComuneRendicontiPersonaleIndeterminatoModifyDlg()` | `$task` | 21475 |
| `Task_GetSierComuneRisultatiCoalizioniModifyDlg()` | `$task` | 21501 |
| `Task_GetSierComuneRisultatiPreferenzeModifyDlg()` | `$task` | 21542 |
| `Task_GetSierComuneRisultatiPreferenzeCsvImportDlg()` | `$task` | 21591 |
| `Task_GetSierComuneRisultatiPreferenzeCsvImportPreviewDlg()` | `$task` | 21618 |
| `Task_GetSierOCRisultatiPreferenzeCsvImportPreviewDlg()` | `$task` | 21655 |
| `Task_GetSierComuneRisultatiPreferenzeModifyMultiDlg()` | `$task` | 21699 |
| `Task_GetSierComuneRisultatiPreferenzeTrashDlg()` | `$task` | 21764 |
| `Task_GetSierComuneRisultatiListeModifyDlg()` | `$task` | 21799 |
| `Task_GetSierOCModifyRisultatiGeneraliDlg()` | `$task` | 21840 |
| `Task_GetSierOCRendicontiSeggiModifyDlg()` | `$task` | 21874 |
| `Task_GetSierOCRendicontiPersonaleIndeterminatoModifyDlg()` | `$task` | 21908 |
| `Task_GetSierOCModifyRisultatiCoalizioniDlg()` | `$task` | 21942 |
| `Task_GetSierOCModifyRisultatiListeDlg()` | `$task` | 21990 |
| `Task_GetSierOCModifyRisultatiPreferenzeDlg()` | `$task` | 22037 |
| `Task_GetSierOCRisultatiPreferenzeCsvImportDlg()` | `$task` | 22093 |
| `Task_GetSierOCModifyRisultatiPreferenzeMultiDlg()` | `$task` | 22141 |
| `Task_GetSierComuneOperatoriTrashDlg()` | `$task` | 22213 |
| `Task_GetOCLoginVerifyDlg()` | `$task` | 22254 |
| `Task_GetSierComuneOperatoriViewDlg()` | `$task` | 22281 |
| `Task_GetSierComuneLogsDlg()` | `$task` | 22307 |
| `Task_GetSierSerialize()` | `$task` | 22332 |
| `Task_GetSierFeedRisultatiAffluenza()` | `$task` | 22350 |
| `Task_GetSierFeedCandidati()` | `$task` | 22368 |
| `Task_GetSierComuneRisultatiViewDlg()` | `$task` | 22386 |
| `Task_GetSierComuneRendicontiViewDlg()` | `$task` | 22413 |
| `Task_AddNewSierComuneAffluenza()` | `$task` | 22440 |
| `Task_UpdateSierComuneAffluenza()` | `$task` | 22521 |
| `Task_DeleteSierWebAppQrCode()` | `$task` | 22601 |
| `Task_TrashSierComuneAffluenza()` | `$task` | 22656 |
| `Task_FixComuneLogs()` | `$task` | 22710 |
| `Task_UpdateSierComuneRisultatiGenerali()` | `$task` | 22774 |
| `Task_UpdateSierComuneRisultatiCoalizioni()` | `$task` | 22906 |
| `Task_UpdateSierComuneRisultatiListe()` | `$task` | 23003 |
| `Task_UpdateSierComuneRisultatiPreferenze()` | `$task` | 23098 |
| `Task_UpdateSierComuneRisultatiPreferenzeMulti()` | `$task` | 23235 |
| `Task_TrashSierComuneRisultatiPreferenze()` | `$task` | 23356 |
| `Task_Update_OC_ComuneRisultatiListe()` | `$task` | 23412 |
| `Task_Update_OC_ComuneRisultatiPreferenze()` | `$task` | 23521 |
| `Task_Update_OC_ComuneRisultatiPreferenzeMulti()` | `$task` | 23672 |
| `Task_Update_OC_ComuneRisultatiGenerali()` | `$task` | 23808 |
| `Task_Update_OC_ComuneAffluenza()` | `$task` | 23954 |
| `Task_Update_OC_ComuneComunicazioni()` | `$task` | 24050 |
| `Task_Update_OC_CertificazioneCorpoElettorale()` | `$task` | 24121 |
| `Task_Update_OC_ComuneRisultatiCoalizioni()` | `$task` | 24200 |
| `Task_AddNewSierComuneOperatore()` | `$task` | 24308 |
| `Task_UpdateSierComuneOperatore()` | `$task` | 24383 |
| `Task_TrashSierComuneOperatore()` | `$task` | 24459 |
| `Task_UpdateSierComuneDatiGenerali()` | `$task` | 24513 |
| `Task_UpdateSierComuneRendicontiSeggi()` | `$task` | 24554 |
| `Task_Update_OC_ComuneRendicontiSeggi()` | `$task` | 24652 |
| `Task_UpdateSierComuneRendicontiRas()` | `$task` | 24768 |
| `Task_UpdateSierComuneRendicontiRasLiquidazioni()` | `$task` | 24926 |
| `Template_GetSierComuneRendicontiExportRasPdf()` | `$object=null,$comune=null,$bToBrowser = true, $title = "Rendicontazione spese elettorali", $index=true, $subTitle=""` | 25002 |
| `Template_RendicontiReportProspettoRiassuntivo()` | `$object=null,$comune=null,$rendiconti=null,$bDifferenziateRowsColor=true` | 25254 |
| `Template_RendicontiReportSeggi()` | `$object=null,$comune=null,$rendiconti=null,$bDifferenziateRowsColor=true` | 25411 |
| `Template_RendicontiReportBuoni()` | `$object=null,$comune=null,$rendiconti=null,$bDifferenziateRowsColor=true` | 25501 |
| `Template_RendicontiReportStraordinario()` | `$object=null,$comune=null,$rendiconti=null,$bDifferenziateRowsColor=true` | 25588 |
| `Template_RendicontiReportStraordinarioAttestazioni()` | `$object=null,$comune=null,$rendiconti=null,$bDifferenziateRowsColor=true` | 25778 |
| `Template_RendicontiReportAssunzioni()` | `$object=null,$comune=null,$rendiconti=null,$bDifferenziateRowsColor=true,$page=0,$lastPage=false` | 25817 |
| `Template_RendicontiReportServizi()` | `$object=null,$comune=null,$rendiconti=null,$bDifferenziateRowsColor=true,$page=0` | 25939 |
| `Task_UpdateSierComuneRendicontiBuoni()` | `$task` | 26032 |
| `Task_Update_OC_ComuneRendicontiBuoni()` | `$task` | 26144 |
| `Task_UpdateSierComuneRendicontiServizi()` | `$task` | 26272 |
| `Task_UpdateSierComuneRendicontiImportoAmmesso()` | `$task` | 26348 |
| `Task_Update_OC_ComuneRendicontiServizi()` | `$task` | 26453 |
| `Task_UpdateSierComuneRendicontiPersonaleDeterminato()` | `$task` | 26543 |
| `Task_Update_OC_ComuneRendicontiPersonaleDeterminato()` | `$task` | 26635 |
| `Task_DeleteSierComuneRendicontiServizi()` | `$task` | 26749 |
| `Task_Delete_OC_ComuneRendicontiServizi()` | `$task` | 26811 |
| `Task_Delete_OC_ComuneRendicontiAllegati()` | `$task` | 26887 |
| `Task_DeleteSierComuneRendicontiPersonaleDeterminato()` | `$task` | 26972 |
| `Task_Delete_OC_ComuneRendicontiPersonaleDeterminato()` | `$task` | 27034 |
| `Task_UpdateSierComuneRendicontiPersonaleIndeterminato()` | `$task` | 27110 |
| `Task_Update_OC_ComuneRendicontiPersonaleIndeterminato()` | `$task` | 27314 |
| `Task_UpdateSierComuneCorpoElettorale()` | `$task` | 27532 |
| `Task_UpdateSierComuneComunicazioni()` | `$task` | 27617 |
| `Task_ResetComunicazioniComuni()` | `$task` | 27676 |
| `Task_GetSierConfirmResetComunicazioniComuniDlg()` | `$task` | 27710 |
| `Task_GetSierConfirmResetFeedRisultatiComuniDlg()` | `$task` | 27737 |
| `Task_GetSierConfirmUpdateFeedRisultatiComuniDlg()` | `$task` | 27764 |
| `Task_GetSierConfirmResetRisultatiComuniDlg()` | `$task` | 27791 |
| `Task_GetSierConfirmUpdateFeedRisultatiGeneraliDlg()` | `$task` | 27818 |
| `Task_GetSierConfirmResetAffluenzaComuniDlg()` | `$task` | 27845 |
| `Task_GetSierConfirmTrashWebAppQRCodeDlg()` | `$task` | 27871 |
| `Task_ResetAffluenzaComuni()` | `$task` | 27898 |
| `Task_ResetRisultatiComuni()` | `$task` | 27932 |
| `Task_UpdateFeedRisultatiComuni()` | `$task` | 27965 |
| `Task_UpdateFeedRisultatiGenerali()` | `$task` | 27999 |
| `Task_ResetFeedRisultatiComuni()` | `$task` | 28033 |
| `Task_UpdateAnalisiRisultatiComuni()` | `$task` | 28067 |
| `Task_GetSierModifyCoalizioneDlg()` | `$task` | 28101 |
| `Task_UpdateSierAllegato()` | `$task` | 28149 |
| `Task_Update_OC_ComuneRendicontiAllegati()` | `$task` | 28331 |
| `Task_DeleteSierAllegato()` | `$task` | 28522 |
| `Task_DeleteSierComune()` | `$task` | 28577 |
| `Task_GetSierModifyAllegatoDlg()` | `$task` | 28632 |
| `Task_GetSierCopyAllegatoDlg()` | `$task` | 28678 |
| `Task_GetSierTrashCoalizioneDlg()` | `$task` | 28724 |
| `Task_GetSierTrashListaDlg()` | `$task` | 28770 |
| `Task_GetSierTrashAllegatoDlg()` | `$task` | 28828 |
| `Task_GetSierTrashComuneDlg()` | `$task` | 28874 |
| `Task_GetSierPubblicateFilterDlg()` | `$task` | 28920 |
| `Task_GetSierBozzeFilterDlg()` | `$task` | 28935 |
| `Task_GetSierComuneFilterDlg()` | `$task` | 28950 |
| `Task_GetNavbarContent()` | `$task` | 28972 |
| `TemplatePubblicateFilterDlg()` | `$params=array(` | 28983 |
| `TemplateBozzeFilterDlg()` | `$params=array(` | 29042 |
| `Template_PdfExport()` | `$ids=array(` | 29099 |
| `Task_AMAAI_Start()` | `$task` | 29105 |
| `Template_SierPdfExport()` | `$id="", $parent=null,$object=null,$user=null` | 29119 |
| `TemplateDettaglio_Allegati()` | `$object=null,$id="", $canModify=false` | 29132 |
| `TemplateDettaglio_Giornate()` | `$object=null,$id="", $canModify=false` | 29214 |
| `Template_GetSierComuneDatiGeneraliViewDlg()` | `$object=null,$comune=null` | 29292 |
| `Template_GetSierComuneComunicazioniViewDlg()` | `$object=null,$comune=null` | 29354 |
| `Template_GetSierOCAffluenzaAddNewDlg()` | `$object=null,$comune=null` | 29434 |
| `Template_GetSierComuneAffluenzaAddNewDlg()` | `$object=null,$comune=null` | 29522 |
| `Template_GetSierComuneAffluenzaModifyDlg()` | `$object=null,$comune=null,$giornata=""` | 29600 |
| `Template_OC_DatiGeneraliModifyDlg()` | `$object=null,$comune=null` | 29683 |
| `Template_OC_CorpoElettoraleModifyDlg()` | `$object=null,$comune=null` | 29739 |
| `Template_GetSierComuneCorpoElettoraleViewDlg()` | `$object=null,$comune=null` | 29816 |
| `Template_GetSierComuneRisultatiViewLayout()` | `$object=null,$comune=null,$id=""` | 29908 |
| `Template_GetSierComuneRendicontiViewLayout()` | `$object=null,$comune=null,$id=""` | 30452 |
| `Template_GetSierComuneRisultatiGeneraliModifyDlg()` | `$object=null,$comune=null` | 32429 |
| `Template_GetSierComuneRendicontiSeggiModifyDlg()` | `$object=null,$comune=null` | 32512 |
| `Template_GetSierComuneRendicontiImportoAmmessoModifyDlg()` | `$object=null,$comune=null` | 32598 |
| `Template_GetSierOCRendicontiSeggiModifyDlg()` | `$object=null,$comune=null` | 32688 |
| `Template_GetSierComuneRendicontiServiziModifyDlg()` | `$object=null,$comune=null` | 32773 |
| `Template_GetSierOCRendicontiServiziModifyDlg()` | `$object=null,$comune=null` | 32856 |
| `Template_GetSierComuneRendicontiRasLiquidazioniModifyDlg()` | `$object=null,$comune=null` | 32938 |
| `Template_GetSierComuneRendicontiBuoniModifyDlg()` | `$object=null,$comune=null` | 32996 |
| `Template_GetSierOCRendicontiBuoniModifyDlg()` | `$object=null,$comune=null` | 33091 |
| `Template_GetSierComuneRendicontiRasModifyDlg()` | `$object=null,$comune=null` | 33185 |
| `Template_GetSierComuneRendicontiPersonaleIndeterminatoModifyDlg()` | `$object=null,$comune=null` | 33270 |
| `Template_GetSierOCRendicontiPersonaleIndeterminatoModifyDlg()` | `$object=null,$comune=null` | 33412 |
| `Template_GetSierComuneRendicontiPersonaleDeterminatoModifyDlg()` | `$object=null,$comune=null` | 33552 |
| `Template_GetSierOCRendicontiPersonaleDeterminatoModifyDlg()` | `$object=null,$comune=null` | 33655 |
| `Template_GetSierComuneRisultatiCoalizioniModifyDlg()` | `$object=null,$comune=null` | 33756 |
| `Template_GetSierComuneRisultatiPreferenzeModifyDlg()` | `$object=null,$comune=null,$candidato=null` | 33821 |
| `Template_GetSierComuneRisultatiPreferenzeModifyMultiDlg()` | `$object=null,$comune=null,$lista=null` | 33877 |
| `Template_GetSierOCModifyRisultatiPreferenzeDlg()` | `$object=null,$comune=null,$candidato=null` | 33955 |
| `Template_GetSierOCModifyRisultatiPreferenzeMultiDlg()` | `$object=null,$comune=null,$lista=null` | 34008 |
| `Template_GetSierComuneRisultatiListeModifyDlg()` | `$object=null,$comune=null` | 34085 |
| `Template_GetSierOCModifyRisultatiGeneraliDlg()` | `$object=null,$comune=null` | 34178 |
| `Template_GetSierOCModifyComunicazioniDlg()` | `$object=null,$comune=null` | 34264 |
| `Template_GetSierOCModifyRisultatiCoalizioniDlg()` | `$object=null,$comune=null` | 34337 |
| `Template_GetSierOCModifyRisultatiListeDlg()` | `$object=null,$comune=null` | 34401 |
| `Template_GetSierComuneRisultatiViewDlg()` | `$object=null,$comune=null` | 34490 |
| `Template_GetSierComuneRendicontiViewDlg()` | `$object=null,$comune=null` | 34504 |
| `Template_GetSierReportViewDlg()` | `$object=null,$circoscrizione=null,$comune=null` | 34518 |
| `Template_GetSierReportViewLayout()` | `$object=null,$circoscrizione=null,$comune=null,$id=""` | 34540 |
| `Template_GetSierComuneAffluenzaViewDlg()` | `$object=null,$comune=null` | 34636 |
| `Template_GetSierComuneAffluenzaViewLayout()` | `$object=null,$comune=null,$id=""` | 34651 |
| `Template_GetSierComuneOperatoriViewDlg()` | `$object=null,$comune=null` | 34736 |
| `Template_GetSierComuneOperatoriViewLayout()` | `$object=null,$comune=null,$id=""` | 34751 |
| `Task_GetObjectContent()` | `$task` | 34856 |
| `Task_GetActionMenu()` | `$task` | 34886 |
| `TemplateActionMenu_OC_Desktop()` | `—` | 34910 |
| `TemplateDettaglio_Abilitazioni()` | `$object=null,$id=""` | 34929 |
| `__construct()` | `$id="AA_SierPublicReportTemplateView",$parent=null,$object=null` | 35064 |
| `GetId()` | `—` | 35123 |
| `SetId()` | `$id=0` | 35127 |
| `GetUrl()` | `—` | 35133 |
| `SetUrl()` | `$url=""` | 35137 |
| `GetTipo()` | `$asArray=false` | 35143 |
| `SetTipo()` | `$val=""` | 35148 |
| `GetTipoDescr()` | `$asArray=false` | 35152 |
| `GetDestinatari()` | `$asArray=false` | 35169 |
| `SetDestinatari()` | `$val=""` | 35174 |
| `GetDestinatariDescr()` | `$asArray=false` | 35179 |
| `toArray()` | `$full=false` | 35195 |
| `GetEstremi()` | `—` | 35200 |
| `SetEstremi()` | `$val=""` | 35204 |
| `GetAggiornamento()` | `—` | 35210 |
| `SetAggiornamento()` | `$val=""` | 35214 |
| `GetFileHash()` | `—` | 35220 |
| `SetFileHash()` | `$val=""` | 35224 |
| `GetFilePath()` | `—` | 35229 |
| `GetFileLocalPath()` | `—` | 35239 |
| `GetFilePublicPath()` | `—` | 35244 |
| `GetIdSier()` | `—` | 35255 |
| `SetIdSier()` | `$id=0` | 35259 |
| `GetOrdine()` | `—` | 35265 |
| `SetOrdine()` | `$val=0` | 35269 |
| `__construct()` | `$id=0,$id_sier=0,$estremi="",$url="",$file="",$tipo="",$aggiornamento="",$destinatari="",$ordine=0` | 35274 |
| `Download()` | `$embed=false` | 35290 |

### `utils/modules/sier_5/taskmanager.php`

## `modules/sines_2/`

### `utils/modules/sines_2/art22_lib.php`

| Classe | Estende | Linea |
|---|---|---|
| `AA_Organismi_Const` | AA_Const | 6 |
| `AA_Organismi` | AA_Object | 719 |
| `AA_XML_FEED_ORGANISMO` | AA_XML_FEED | 9502 |
| `AA_XML_FEED_ORGANISMI` | AA_XML_FEED | 9527 |
| `AA_XML_FEED_ART22` | AA_XML_FEED | 9551 |

| Metodo | Parametri | Linea |
|---|---|---|
| `GetNomineDaOscurare()` | `—` | 110 |
| `Initialize()` | `—` | 167 |
| `GetListaTipoOrganigramma()` | `—` | 313 |
| `GetListaStatoOrganismi()` | `—` | 321 |
| `GetListaFormaGiuridica()` | `—` | 329 |
| `GetTipoDocs()` | `—` | 337 |
| `GetTipoProvvedimenti()` | `—` | 345 |
| `GetTipoBilanci()` | `—` | 353 |
| `GetTipoNomine()` | `—` | 361 |
| `GetTipoOrganismi()` | `$bPlurali=false` | 369 |
| `toXML()` | `—` | 379 |
| `__construct()` | `$data=""` | 415 |
| `IsNominaRas()` | `—` | 421 |
| `IsOpzionale()` | `—` | 427 |
| `IsScadenzarioEnabled()` | `—` | 433 |
| `GetTipologia()` | `$bNumeric=False` | 439 |
| `IsValid()` | `—` | 450 |
| `ParseData()` | `$data=""` | 457 |
| `GetId()` | `—` | 482 |
| `SetProp()` | `$prop="",$val=""` | 488 |
| `GetProp()` | `$prop=""` | 494 |
| `GetProps()` | `—` | 500 |
| `LoadFromDb()` | `$id=0` | 506 |
| `__construct()` | `$data=""` | 535 |
| `IsScadenzarioEnabled()` | `—` | 540 |
| `GetTipologia()` | `$bNumeric=False` | 551 |
| `ParseData()` | `$data=""` | 560 |
| `toXml()` | `$detail=true` | 594 |
| `SetProp()` | `$prop="",$val=""` | 623 |
| `GetProp()` | `$prop=""` | 629 |
| `GetId()` | `—` | 634 |
| `GetProps()` | `—` | 640 |
| `GetIncarichi()` | `—` | 645 |
| `LoadFromDb()` | `$id=0` | 672 |
| `__construct()` | `$id=0,$user=null` | 727 |
| `GetDenominazione()` | `—` | 786 |
| `SetDenominazione()` | `$var="denominazione organismo"` | 795 |
| `SetTipologia()` | `$var=0` | 811 |
| `GetTipologia()` | `$bNumeric=false` | 825 |
| `SetFunzioni()` | `$var=""` | 844 |
| `GetFunzioni()` | `—` | 856 |
| `SetPartecipazione()` | `$var=""` | 872 |
| `GetPartecipazione()` | `$bAsObject=false` | 884 |
| `GetListaOrganismiPartecipabili()` | `$bFull=false,$user=null` | 915 |
| `GetOrganismiAscendenti()` | `$idOrg=0` | 969 |
| `RecursiveParentPartecipazioneIndirettaCalc()` | `$user=null` | 975 |
| `GetPartecipazioneIndiretta()` | `$user=null` | 994 |
| `GetListaOrganismiDiscendenti()` | `$idOrg=0` | 1013 |
| `GetOrganismiDiscendenti()` | `$bFull=false,$user=null` | 1055 |
| `GetListaOrganismiPartecipati()` | `$bFull=false` | 1075 |
| `SetFormaSocietaria()` | `$var=0` | 1126 |
| `GetFormaSocietaria()` | `$bNumeric=false` | 1139 |
| `SetStatoOrganismo()` | `$var=0` | 1160 |
| `GetStatoOrganismo()` | `$bNumeric=false` | 1173 |
| `SetSitoWeb()` | `$var=""` | 1194 |
| `GetSitoWeb()` | `—` | 1206 |
| `SetPec()` | `$var=""` | 1222 |
| `GetPec()` | `—` | 1234 |
| `GetProvvedimenti()` | `—` | 1244 |
| `SetDataInizioImpegno()` | `$var=""` | 1289 |
| `GetDataInizioImpegno()` | `—` | 1301 |
| `SetDataFineImpegno()` | `$var=""` | 1317 |
| `GetDataFineImpegno()` | `—` | 1329 |
| `SetPivaCf()` | `$var=""` | 1345 |
| `GetPivaCf()` | `—` | 1357 |
| `SetInHouse()` | `$var=1` | 1373 |
| `GetInHouse()` | `—` | 1386 |
| `IsInHouse()` | `—` | 1394 |
| `SetPartecipabile()` | `$var=1` | 1411 |
| `GetPartecipabile()` | `—` | 1424 |
| `IsPartecipabile()` | `—` | 1432 |
| `SetControllato()` | `$var=1` | 1449 |
| `GetControllato()` | `—` | 1462 |
| `IsControllato()` | `—` | 1470 |
| `SetInTUSP()` | `$var=1` | 1487 |
| `GetInTUSP()` | `—` | 1500 |
| `IsInTUSP()` | `—` | 1504 |
| `GetInMercatiReg()` | `—` | 1515 |
| `SetInMercatiReg()` | `$val=true` | 1519 |
| `IsinMercatiReg()` | `—` | 1528 |
| `SetNote()` | `$var=""` | 1545 |
| `GetNote()` | `—` | 1557 |
| `SetSedeLegale()` | `$var=""` | 1573 |
| `GetSedeLegale()` | `—` | 1585 |
| `__toString()` | `—` | 1594 |
| `toXml()` | `—` | 1604 |
| `toJSON()` | `$bDeep=false` | 1693 |
| `CsvDataHeader()` | `$separator="|"` | 1720 |
| `CsvData()` | `$separator= "|"` | 1726 |
| `VerifyDbSync()` | `$user=null` | 1743 |
| `VerifyDbLoad()` | `—` | 1753 |
| `GetFromDb()` | `$id=0,$user=null` | 1765 |
| `GetUserCaps()` | `$user=null` | 1786 |
| `Load()` | `$id=0,$user=null` | 1833 |
| `AddNewProvvedimento()` | `$provvedimento=null, $file="", $user=null` | 1846 |
| `UpdateProvvedimento()` | `$provvedimento=null, $file="", $user=null` | 1941 |
| `UpdateOrganigramma()` | `$organigramma=null, $user=null` | 2031 |
| `GetProvvedimento()` | `$id=null, $user=null` | 2099 |
| `DeleteProvvedimento()` | `$id=0, $user=null` | 2157 |
| `DeleteAllProvvedimenti()` | `$user=null` | 2231 |
| `AddNewToDb()` | `$data=null,$user=null` | 2302 |
| `ParseData()` | `$data=null,$user=null` | 2379 |
| `Trash()` | `$user=null, $bDelete=false` | 2469 |
| `DeleteOrganigramma()` | `$id=0` | 2523 |
| `DeleteOrganigrammaIncarico()` | `$id=0,$id_incarico=0` | 2575 |
| `DeleteAllOrganigrammi()` | `—` | 2625 |
| `CanPublish()` | `—` | 2679 |
| `AddNewOrganigramma()` | `$newOrganigramma=null` | 2723 |
| `AddNewOrganigrammaIncarico()` | `$incarico=null, $idOrganigramma=0` | 2789 |
| `UpdateOrganigrammaIncarico()` | `$incarico=null, $idOrganigramma=0` | 2856 |
| `GetOrganismiPerRappresentazioneGrafica()` | `$params,$user=null` | 2924 |
| `Search()` | `$params,$bOnlyCount=false, $user=null` | 3042 |
| `GetDatiContabili()` | `$dal="", $al=""` | 3463 |
| `GetNomine()` | `$dal="", $al="", $onlyRas=false` | 3510 |
| `GetNomineGrouped()` | `$params=array(` | 3558 |
| `GetNomineGroupedForOrganigramma()` | `$params=array(` | 3629 |
| `GetOrganigrammi()` | `$params=array(` | 3706 |
| `HasOrganigrammi()` | `$params=array(` | 3754 |
| `GetOrganigramma()` | `$id=0` | 3791 |
| `GetListOrganigrammi()` | `—` | 3843 |
| `GetNomineScadenzario()` | `$params=array(` | 3863 |
| `__construct()` | `$id=0,$parent=null,$user=null` | 4081 |
| `IsInGap()` | `—` | 4191 |
| `SetInGap()` | `$val=true` | 4196 |
| `IsInGbc()` | `—` | 4204 |
| `SetInGbc()` | `$val=true` | 4209 |
| `GetParent()` | `$user=null` | 4217 |
| `ExportToCsv()` | `—` | 4238 |
| `SetParent()` | `$parent=null,$user=null` | 4248 |
| `GetTipologia()` | `$bNumeric=false` | 4291 |
| `SetTipologia()` | `$val=0` | 4299 |
| `SetNote()` | `$val` | 4306 |
| `GetNote()` | `—` | 4310 |
| `SetAnno()` | `$val=""` | 4317 |
| `GetAnno()` | `—` | 4322 |
| `GetOneriTotali()` | `—` | 4329 |
| `SetOneriTotali()` | `$val=""` | 4333 |
| `GetDotazioneOrganica()` | `—` | 4339 |
| `GetDipendenti()` | `—` | 4346 |
| `SetDipendenti()` | `$val=""` | 4350 |
| `GetDipendentiDir()` | `—` | 4357 |
| `SetDipendentiDir()` | `$val=""` | 4361 |
| `GetDipendentiDet()` | `—` | 4368 |
| `SetDipendentiDet()` | `$val=""` | 4372 |
| `GetDipendentiDetDir()` | `—` | 4379 |
| `SetDipendentiDetDir()` | `$val=""` | 4383 |
| `GetSpesaDotazioneOrganica()` | `—` | 4390 |
| `SetSpesaDotazioneOrganica()` | `$val=""` | 4394 |
| `GetSpesaDotazioneOrganicaImpegnato()` | `—` | 4401 |
| `SetSpesaDotazioneOrganicaImpegnato()` | `$val=""` | 4405 |
| `GetSpesaLavoroFlessibile()` | `—` | 4412 |
| `SetSpesaLavoroFlessibile()` | `$val=""` | 4416 |
| `GetSpesaLavoroFlessibileImpegnato()` | `—` | 4423 |
| `SetSpesaLavoroFlessibileImpegnato()` | `$val=""` | 4427 |
| `GetSpesaLavoroFlessibileNum()` | `—` | 4434 |
| `SetSpesaLavoroFlessibileNum()` | `$val=""` | 4438 |
| `GetSpesaIncarichi()` | `—` | 4445 |
| `SetSpesaIncarichi()` | `$val=""` | 4449 |
| `GetSpesaIncarichiImpegnato()` | `—` | 4456 |
| `SetSpesaIncarichiImpegnato()` | `$val=""` | 4460 |
| `GetSpesaIncarichiNum()` | `—` | 4467 |
| `SetSpesaIncarichiNum()` | `$val=""` | 4471 |
| `GetFatturato()` | `—` | 4478 |
| `SetFatturato()` | `$val=""` | 4482 |
| `GetRisultatiBilancio()` | `—` | 4489 |
| `SetRisultatiBilancio()` | `$val=""` | 4493 |
| `UpdateDb()` | `$user=null,$data=null,$bLog=true,$sDetailLog=""` | 4498 |
| `ParseData()` | `$data=null,$user=null` | 4517 |
| `ToXml()` | `—` | 4559 |
| `AddNewToDb()` | `$data=null, $parent=null, $user=null` | 4585 |
| `GetBilanci()` | `$user=null, $tipo=0` | 4640 |
| `GetBilancio()` | `$id=0,$user=null` | 4703 |
| `DelBilancio()` | `$id=0,$user=null` | 4763 |
| `DelBilanci()` | `$user=null` | 4817 |
| `AddNewBilancio()` | `$bilancio=null,$user=null` | 4861 |
| `UpdateBilancio()` | `$bilancio=null,$user=null` | 4923 |
| `Trash()` | `$user=null,$bDelete=true` | 4987 |
| `GetId()` | `—` | 5042 |
| `SetId()` | `$id=0` | 5046 |
| `GetUrl()` | `—` | 5052 |
| `SetUrl()` | `$url=""` | 5056 |
| `GetAnno()` | `—` | 5062 |
| `SetAnno()` | `$anno=""` | 5066 |
| `GetEstremi()` | `—` | 5072 |
| `SetEstremi()` | `$val=""` | 5076 |
| `GetFilePath()` | `—` | 5081 |
| `GetFilePublicPath()` | `—` | 5091 |
| `GetTipologia()` | `$numeric=false` | 5102 |
| `SetTipologia()` | `$tipo=0` | 5113 |
| `GetIdOrganismo()` | `—` | 5120 |
| `SetIdOrganismo()` | `$id=0` | 5124 |
| `__construct()` | `$id=0,$id_organismo=0,$tipo=0,$url="",$anno="",$estremi=""` | 5129 |
| `Download()` | `$embed=false` | 5143 |
| `__construct()` | `$object=null, $user=null` | 5170 |
| `HeaderView()` | `—` | 5178 |
| `Initialize()` | `$object=null` | 5184 |
| `SetOrganismo()` | `$object=null, $user=null` | 5329 |
| `__construct()` | `$user=null` | 5346 |
| `__construct()` | `$user=null` | 5410 |
| `Run()` | `—` | 5416 |
| `__construct()` | `$user=null` | 5477 |
| `Run()` | `—` | 5483 |
| `__construct()` | `$user=null` | 5507 |
| `Run()` | `—` | 5514 |
| `__construct()` | `$user=null` | 5585 |
| `Run()` | `—` | 5591 |
| `__construct()` | `$user=null` | 5640 |
| `Run()` | `—` | 5647 |
| `__construct()` | `$user=null` | 5720 |
| `Run()` | `—` | 5726 |
| `__construct()` | `$user=null` | 5771 |
| `Run()` | `—` | 5777 |
| `__construct()` | `$user=null` | 5809 |
| `Run()` | `—` | 5815 |
| `__construct()` | `$user=null` | 5838 |
| `Run()` | `—` | 5844 |
| `__construct()` | `$user=null` | 5888 |
| `Run()` | `—` | 5894 |
| `__construct()` | `$user=null` | 5920 |
| `Run()` | `—` | 5926 |
| `__construct()` | `$user=null` | 5963 |
| `Run()` | `—` | 5969 |
| `__construct()` | `$user=null` | 6001 |
| `Run()` | `—` | 6007 |
| `__construct()` | `$user=null` | 6123 |
| `Run()` | `—` | 6129 |
| `__construct()` | `$user=null` | 6159 |
| `Run()` | `—` | 6165 |
| `__construct()` | `$user=null` | 6187 |
| `Run()` | `—` | 6193 |
| `__construct()` | `$user=null` | 6222 |
| `Run()` | `—` | 6228 |
| `__construct()` | `$user=null` | 6261 |
| `Run()` | `—` | 6267 |
| `__construct()` | `$user=null` | 6301 |
| `Run()` | `—` | 6307 |
| `__construct()` | `$user=null` | 6351 |
| `Run()` | `—` | 6357 |
| `__construct()` | `$user=null` | 6386 |
| `Run()` | `—` | 6392 |
| `__construct()` | `$user=null` | 6422 |
| `Run()` | `—` | 6428 |
| `__construct()` | `$user=null` | 6456 |
| `Run()` | `—` | 6462 |
| `__construct()` | `$user=null` | 6501 |
| `Run()` | `—` | 6507 |
| `__construct()` | `$user=null` | 6554 |
| `Run()` | `—` | 6560 |
| `__construct()` | `$id="AA_OrganismiBilanciAddNewTemplateView",$parent=null,$obj=null` | 6599 |
| `__construct()` | `$id="AA_OrganismiBilanciEditTemplateView",$parent=null,$obj=null` | 6621 |
| `__construct()` | `$user=null` | 6644 |
| `Run()` | `—` | 6650 |
| `__construct()` | `$user=null` | 6685 |
| `Run()` | `—` | 6691 |
| `__construct()` | `$id="AA_OrganismiBilanciTrashTemplateView",$parent=null,$obj=null` | 6731 |
| `__construct()` | `$id="AA_OrganismiDatiContabiliListTemplateView",$parent=null,$organismo=null, $user=null` | 6748 |
| `__construct()` | `$id="AA_OrganismiBilanciListTemplateView",$parent=null,$daticontabili=null, $user=null` | 6915 |
| `__construct()` | `$id="AA_OrganismiDatiContabiliAddNewTemplateView",$parent=null,$obj=null` | 7024 |
| `__construct()` | `$id="AA_OrganismiDatiContabiliEditTemplateView",$parent=null,$obj=null` | 7061 |
| `__construct()` | `$id="AA_OrganismiDatiContabiliTrashTemplateView",$parent=null,$obj=null` | 7097 |
| `__construct()` | `$id=0,$parent=null,$user=null` | 7113 |
| `ToXml()` | `—` | 7214 |
| `UpdateTrattamentoEconomico()` | `$user=null` | 7239 |
| `GetNome()` | `—` | 7255 |
| `SetNome()` | `$val=""` | 7259 |
| `GetCognome()` | `—` | 7267 |
| `SetCognome()` | `$val=""` | 7271 |
| `GetEstremiProvvedimento()` | `—` | 7279 |
| `SetEstremiProvvedimento()` | `$val=""` | 7283 |
| `GetCodiceFiscale()` | `—` | 7291 |
| `SetCodiceFiscale()` | `$val=""` | 7295 |
| `GetDataInizio()` | `—` | 7303 |
| `SetDataInizio()` | `$val=""` | 7307 |
| `GetDataFine()` | `—` | 7315 |
| `SetDataFine()` | `$val=""` | 7319 |
| `IsDataFinePresunta()` | `—` | 7326 |
| `SetDataFinePresunta()` | `$val=true` | 7331 |
| `GetParent()` | `$user=null` | 7339 |
| `GetParentId()` | `—` | 7358 |
| `SetParent()` | `$parent=null,$user=null` | 7364 |
| `GetTipologia()` | `$bNumeric=false` | 7408 |
| `SetTipologia()` | `$val=0` | 7416 |
| `IsFacenteFunzione()` | `—` | 7423 |
| `SetFacenteFunzione()` | `$val=1` | 7428 |
| `SetNote()` | `$val` | 7436 |
| `GetNote()` | `—` | 7440 |
| `SetNominaRas()` | `$val=1` | 7447 |
| `GetNominaRas()` | `—` | 7451 |
| `IsNominaRas()` | `—` | 7455 |
| `SetCompensoSpettante()` | `$val=0` | 7463 |
| `GetCompensoSpettante()` | `—` | 7467 |
| `SetStorico()` | `$val=true` | 7474 |
| `IsStorico()` | `—` | 7479 |
| `SetCompensoErogato()` | `$val=0` | 7487 |
| `GetCompensoErogato()` | `—` | 7491 |
| `IsOver65()` | `—` | 7497 |
| `AddNewToDb()` | `$data=null, $parent=null, $user=null` | 7504 |
| `AddNewCompenso()` | `$compenso=null, $user=null` | 7571 |
| `UpdateCompenso()` | `$compenso=null, $user=null` | 7646 |
| `GetCompensi()` | `$user=null` | 7725 |
| `GetCompenso()` | `$id="",$anno="",$user=null` | 7784 |
| `TrashCompenso()` | `$id_compenso="", $user=null` | 7843 |
| `DeleteAllCompensi()` | `$user=null` | 7902 |
| `VerifyData()` | `—` | 7950 |
| `UpdateDb()` | `$user=null,$data=null, $bLog=true,$sDetailLog=""` | 7970 |
| `ParseData()` | `$data=null,$user=null` | 8000 |
| `GetDocs()` | `$annoRif="",$tipodoc=0,$serial="",$user=null` | 8065 |
| `Trash()` | `$user=null, $bDelete=true` | 8073 |
| `DownloadAllDocs()` | `—` | 8110 |
| `DeleteAllDocs()` | `$annoRif="",$tipodoc=0,$serial="",$user=null` | 8178 |
| `DelDoc()` | `$annoRif="",$tipoDoc=0,$serial="",$user=null` | 8226 |
| `__toString()` | `—` | 8235 |
| `SetIdDatiContabili()` | `$val=0` | 8241 |
| `GetIdDatiContabili()` | `—` | 8245 |
| `GetDatiContabili()` | `$user=null` | 8251 |
| `SetId()` | `$val=0` | 8264 |
| `GetId()` | `—` | 8268 |
| `SetTipo()` | `$val=0` | 8275 |
| `GetTipo()` | `$bNumeric=false` | 8279 |
| `SetRisultati()` | `$val=""` | 8291 |
| `GetRisultati()` | `—` | 8295 |
| `GetLocalDocumentPath()` | `—` | 8301 |
| `GetPublicDocumentPath()` | `—` | 8312 |
| `Download()` | `$embed=false` | 8321 |
| `SetNote()` | `$val=""` | 8343 |
| `GetNote()` | `—` | 8347 |
| `IsValid()` | `—` | 8354 |
| `__construct()` | `—` | 8359 |
| `ToXml()` | `—` | 8364 |
| `SetIdNomina()` | `$id=0` | 8381 |
| `GetIdNomina()` | `—` | 8385 |
| `GetNomina()` | `$user=null` | 8389 |
| `SetAnno()` | `$val=""` | 8402 |
| `GetAnno()` | `—` | 8408 |
| `SetTipologia()` | `$val=0` | 8415 |
| `GetTipologia()` | `$bValue=false` | 8423 |
| `SetSerial()` | `$val=""` | 8435 |
| `GetSerial()` | `—` | 8439 |
| `IsValid()` | `—` | 8446 |
| `__construct()` | `$idNomina=0,$anno="",$tipo=0, $serial="0",$user=null` | 8451 |
| `GetLocalDocumentPath()` | `—` | 8479 |
| `GetPublicDocumentPath()` | `—` | 8496 |
| `Download()` | `$embed=false` | 8505 |
| `Delete()` | `$user=null` | 8525 |
| `UploadDoc()` | `$nomina=null,$anno="",$tipo=0,$file="",$bOverride=false,$user=null` | 8562 |
| `GetDoc()` | `$nomina=null,$anno="",$tipo=0,$serial="0",$user=null` | 8641 |
| `GetAllDocs()` | `$nomina=null,$anno="",$tipo=0,$serial="",$user` | 8661 |
| `__construct()` | `$id="AA_OrganismiNomineListTemplateView",$parent=null,$organismo=null, $user=null` | 8718 |
| `__construct()` | `$id="AA_OrganismiNomineAddNewTemplateView",$parent=null,$obj=null` | 8882 |
| `__construct()` | `$user=null` | 8910 |
| `Run()` | `—` | 8916 |
| `__construct()` | `$user=null` | 8938 |
| `Run()` | `—` | 8944 |
| `__construct()` | `$id="AA_OrganismiNomineEditTemplateView",$parent=null,$obj=null` | 8978 |
| `__construct()` | `$user=null` | 9006 |
| `Run()` | `—` | 9012 |
| `__construct()` | `$user=null` | 9041 |
| `Run()` | `—` | 9047 |
| `__construct()` | `$id="AA_OrganismiNomineTrashTemplateView",$parent=null,$obj=null` | 9091 |
| `__construct()` | `$user=null` | 9108 |
| `Run()` | `—` | 9114 |
| `__construct()` | `$user=null` | 9147 |
| `Run()` | `—` | 9153 |
| `__construct()` | `$id="AA_OrganismiNomineDocumentListTemplateView",$parent=null,$nomina=null, $user=null` | 9182 |
| `__construct()` | `$user=null` | 9270 |
| `Run()` | `—` | 9276 |
| `__construct()` | `$id="AA_OrganismiNomineUploadDocumentTemplateView",$parent=null,$obj=null` | 9306 |
| `__construct()` | `$user=null` | 9339 |
| `Run()` | `—` | 9345 |
| `__construct()` | `$user=null` | 9374 |
| `Run()` | `—` | 9380 |
| `__construct()` | `$id="AA_OrganismiNomineTrashDocTemplateView",$parent=null,$obj=null` | 9409 |
| `__construct()` | `$user=null` | 9429 |
| `Run()` | `—` | 9435 |
| `__construct()` | `$user=null` | 9469 |
| `Run()` | `—` | 9475 |
| `__construct()` | `$obj=null` | 9507 |
| `toXml()` | `—` | 9517 |
| `__construct()` | `$params=null` | 9532 |
| `toXml()` | `—` | 9542 |
| `__construct()` | `—` | 9553 |
| `__construct()` | `$id="AA_OrganismiPublicReportTemplateView",$parent=null,$organismo=null, $user=null` | 9563 |
| `__construct()` | `$id="AA_OrganismiPublicReportTemplateGeneralPageView",$parent=null,$organismo=null, $user=null` | 9712 |
| `__construct()` | `$id="AA_OrganismiPublicReportTemplateGeneralPageView",$parent=null,$organismo=null, $user=null` | 9901 |
| `__construct()` | `$id="AA_OrganismiFullReportTemplateGeneralPageView",$parent=null,$organismo=null, $user=null` | 10041 |
| `GetRelNumPage()` | `—` | 10277 |
| `__construct()` | `$id="AA_OrganismiFullReportTemplateDaticontabiliPageView",$parent=null,$organismo=null, $user=null, $doc=null` | 10281 |
| `__construct()` | `$id="AA_OrganismiPublicReportTemplateNominePageView",$parent=null,$organismo=null, $user=null` | 10536 |
| `__construct()` | `$id="AA_OrganismiPublicReportTemplateProvvedimentiPageView",$parent=null,$organismo=null, $user=null,$provvedimenti=null` | 10585 |
| `__construct()` | `$id="AA_OrganismiFullReportTemplateNominePageView",$parent=null,$organismo=null, $user=null` | 10633 |
| `__construct()` | `$id="AA_OrganismiReportScadenzarioNomineTemplateView",$parent=null,$organismo=null, $user=null` | 10682 |
| `__construct()` | `$id="AA_OrganismiGeneralTabTemplateView",$parent=null,$organismo=null, $user=null` | 10983 |
| `__construct()` | `$id="AA_OrganismiReportDatiContabiliListTemplateView",$parent=null,$organismo=null, $user=null` | 11184 |
| `__construct()` | `$id="AA_OrganismiReportProvvedimentiListTemplateView",$parent=null,$organismo=null, $user=null,$provvedimenti=null` | 11268 |
| `__construct()` | `$id="AA_OrganismiReportNomineListTemplateView",$parent=null,$organismo=null, $user=null,$full=false` | 11337 |
| `__construct()` | `$id="AA_OrganismiTemplateViewModuleHeaderContent",$parent=null, $user=null` | 11471 |
| `SetId()` | `$val=0` | 11495 |
| `GetId()` | `—` | 11499 |
| `SetAnno()` | `$val=""` | 11506 |
| `GetAnno()` | `—` | 11511 |
| `SetParteFissa()` | `$val=""` | 11518 |
| `GetParteFissa()` | `—` | 11522 |
| `SetParteVariabile()` | `$val=""` | 11529 |
| `GetParteVariabile()` | `—` | 11533 |
| `SetRimborsi()` | `$val=""` | 11540 |
| `GetRimborsi()` | `—` | 11544 |
| `SetNote()` | `$val=""` | 11551 |
| `GetNote()` | `—` | 11555 |
| `__construct()` | `$id=0, $anno="",$parte_fissa="",$parte_variabile="",$rimborsi="",$note=""` | 11560 |
| `toXml()` | `—` | 11574 |

### `utils/modules/sines_2/config.php`

### `utils/modules/sines_2/sines_lib.php`

| Metodo | Parametri | Linea |
|---|---|---|
| `__construct()` | `$user=null` | 25 |
| `GetInstance()` | `$user=null` | 185 |
| `TemplateLayout()` | `—` | 196 |
| `TemplateSection_Placeholder()` | `—` | 209 |
| `TemplateSection_Bozze()` | `$params=null` | 222 |
| `TemplateSection_Pubblicate()` | `$params=null` | 293 |
| `TemplateSection_Scadenzario()` | `—` | 353 |
| `GetDataSectionPubblicate_List()` | `$params=array(` | 400 |
| `GetDataSectionScadenzario_List()` | `$params=array(` | 525 |
| `GetDataSectionBozze_List()` | `$params=array(` | 762 |
| `TemplateSection_Revisionate()` | `—` | 884 |
| `Template_GetOrganismoTrashDlg()` | `$params` | 897 |
| `Template_GetOrganismoPublishDlg()` | `$params` | 964 |
| `Template_GetOrganismoResumeDlg()` | `$params` | 1032 |
| `Template_GetOrganismoReassignDlg()` | `$params` | 1100 |
| `Template_GetOrganismoDeleteDlg()` | `$params` | 1177 |
| `Template_GetOrganismoTrashBilancioDlg()` | `$object=null,$dato_contabile=null,$bilancio=null` | 1244 |
| `Template_GetOrganismoTrashProvvedimentoDlg()` | `$object=null,$provvedimento=null` | 1289 |
| `Template_GetOrganismoTrashIncaricoDlg()` | `$object=null,$incarico=null` | 1340 |
| `Template_GetOrganismoDeleteOrganigrammaDlg()` | `$object=null,$organigramma=null` | 1388 |
| `Template_GetOrganismoDeleteOrganigrammaIncaricoDlg()` | `$object=null,$organigramma=null,$incarico=null` | 1437 |
| `Template_GetOrganismoTrashIncaricoDocDlg()` | `$object=null,$incarico=null,$doc=null` | 1492 |
| `Template_GetOrganismoTrashIncaricoCompensoDlg()` | `$object=null,$incarico=null,$compenso=null` | 1545 |
| `Template_GetOrganismoAddNewOrganigrammaIncaricoDlg()` | `$object=null,$organigramma=null` | 1598 |
| `Template_GetOrganismoModifyOrganigrammaIncaricoDlg()` | `$object=null,$organigramma=null,$incarico=null` | 1648 |
| `Template_GetOrganismoAddNewIncaricoCompensoDlg()` | `$object=null,$incarico=null` | 1698 |
| `Template_GetOrganismoModifyIncaricoCompensoDlg()` | `$object=null,$incarico=null,$compenso=null` | 1747 |
| `Template_GetOrganismoTrashNominaDlg()` | `$object=null,$incarichi=array(` | 1800 |
| `Template_GetSinesTrashPartecipazioneDlg()` | `$object=null,$idOrgPart=null` | 1852 |
| `Template_GetOrganismoTrashDatoContabileDlg()` | `$object=null,$dato_contabile=null` | 1896 |
| `Template_GetOrganismoAddNewDlg()` | `—` | 1941 |
| `Template_GetOrganismoModifyDlg()` | `$object=null` | 1998 |
| `Template_GetOrganismoModifyDatoContabileDlg()` | `$object=null,$dato=null` | 2135 |
| `Template_GetOrganismoModifyIncaricoDlg()` | `$object=null,$incarico=null` | 2252 |
| `Template_GetOrganismoAddNewIncaricoDlg()` | `$object=null,$params=array(` | 2333 |
| `Template_GetSinesAddNewPartecipazioneDlg()` | `$object=null` | 2426 |
| `Template_GetSinesModifyPartecipazioneDlg()` | `$object=null,$idOrgPart=0` | 2478 |
| `Template_GetOrganismoAddNewIncaricoDocDlg()` | `$object=null,$incarico=null` | 2537 |
| `Template_GetOrganismoAddNewProvvedimentoDlg()` | `$object=null` | 2590 |
| `Template_GetOrganismoAddNewOrganigrammaDlg()` | `$object=null` | 2647 |
| `Template_GetOrganismoModifyOrganigrammaDlg()` | `$object=null,$organigramma=null` | 2691 |
| `Template_GetOrganismoModifyProvvedimentoDlg()` | `$object=null,$provvedimento=null` | 2736 |
| `Template_GetOrganismoRenameNominaDlg()` | `$object=null,$params` | 2793 |
| `Template_GetOrganismoAddNewNominaDlg()` | `$object=null` | 2835 |
| `Template_GetOrganismoAddNewDatoContabileDlg()` | `$object=null` | 2906 |
| `Template_GetOrganismoAddNewBilancioDlg()` | `$object=null,$dato_contabile=null` | 3016 |
| `Template_GetOrganismoModifyBilancioDlg()` | `$object=null,$dato_contabile=null,$bilancio=null` | 3054 |
| `Template_GetOrganismoNominaDetailViewDlg()` | `$object=null,$nomina=null` | 3096 |
| `Template_GetOrganismoNominaDetailViewLayout()` | `$object=null,$incarico=null,$id=""` | 3111 |
| `TemplateSection_Detail()` | `$params` | 3398 |
| `TemplateDettaglio_Generale_Tab()` | `$object=null` | 3659 |
| `TemplateDettaglio_DatiContabili_Tab()` | `$object=null` | 3882 |
| `TemplateDettaglio_Nomine_Tab()` | `$object=null,$filterData=""` | 4263 |
| `TemplateDettaglio_Organigramma_Tab()` | `$object=null,$filterData=""` | 4366 |
| `TemplateDettaglio_Partecipazioni()` | `$object=null,$canModify=false` | 4739 |
| `TemplateDettaglio_Partecipazioni_Tab()` | `$object=null,$canModify=false` | 4781 |
| `TemplateDettaglio_Provvedimenti()` | `$object=null,$id="", $canModify=false` | 4853 |
| `TemplateDettaglio_Nomine_Riepilogo_Tab()` | `$object=null,$id="",$riepilogo_data=array(` | 4936 |
| `TemplateDettaglio_Organigramma_Riepilogo_Tab()` | `$object=null,$id="",$riepilogo_data=array(` | 5068 |
| `TemplateActionMenu_Bozze()` | `—` | 5184 |
| `TemplateActionMenu_Scadenzario()` | `—` | 5204 |
| `TemplateActionMenu_Pubblicate()` | `—` | 5224 |
| `TemplateActionMenu_Revisionate()` | `—` | 5245 |
| `TemplateActionMenu_Detail()` | `—` | 5265 |
| `TemplateNavbar_Bozze()` | `$level=1,$last=false,$refresh_view=true` | 5287 |
| `TemplateNavbar_Pubblicate()` | `$level=1,$last=false,$refresh_view=true` | 5304 |
| `TemplateNavbar_Scadenzario()` | `$level=1,$last=false,$refresh_view=true` | 5321 |
| `TemplateNavbar_Back()` | `$level=1,$last=false,$refresh_view=false` | 5338 |
| `TemplateNavbar_Revisionate()` | `$level=1,$last=false,$refresh_view=true` | 5356 |
| `Task_GetActionMenu()` | `$task` | 5374 |
| `Task_GetTipoBilanci()` | `$task` | 5416 |
| `Task_GetLayout()` | `$task` | 5444 |
| `Task_GetLogDlg()` | `$task` | 5459 |
| `Task_UpdateOrganismo()` | `$task` | 5473 |
| `Task_UpdateAllOrganismi()` | `$task` | 5585 |
| `Task_TrashOrganismi()` | `$task` | 5705 |
| `Task_PdfExport()` | `$task` | 5801 |
| `Task_ResumeOrganismi()` | `$task` | 5889 |
| `Task_PublishOrganismo()` | `$task` | 5984 |
| `Task_ReassignOrganismi()` | `$task` | 6081 |
| `Task_DeleteOrganismi()` | `$task` | 6177 |
| `Task_AddNewOrganismo()` | `$task` | 6272 |
| `Task_AddNewOrganismoDatoContabile()` | `$task` | 6330 |
| `Task_AMAAI_Start()` | `$task` | 6376 |
| `Template_GetSinesHelpDlg()` | `—` | 6390 |
| `Task_AddNewOrganismoNomina()` | `$task` | 6449 |
| `Task_AddNewSinesPartecipazione()` | `$task` | 6497 |
| `Task_UpdateSinesPartecipazione()` | `$task` | 6568 |
| `Task_TrashSinesPartecipazione()` | `$task` | 6639 |
| `Task_AddNewOrganismoIncarico()` | `$task` | 6694 |
| `Task_AddNewOrganismoOrganigramma()` | `$task` | 6741 |
| `Task_AddNewOrganismoOrganigrammaIncarico()` | `$task` | 6786 |
| `Task_UpdateOrganismoOrganigrammaIncarico()` | `$task` | 6831 |
| `Task_AddNewOrganismoIncaricoDoc()` | `$task` | 6880 |
| `Task_AddNewOrganismoProvvedimento()` | `$task` | 6938 |
| `Task_UpdateOrganismoProvvedimento()` | `$task` | 6999 |
| `Task_TrashOrganismoProvvedimento()` | `$task` | 7058 |
| `Task_AddNewOrganismoIncaricoCompenso()` | `$task` | 7101 |
| `Task_UpdateOrganismoDatoContabile()` | `$task` | 7157 |
| `Task_UpdateOrganismoIncarico()` | `$task` | 7220 |
| `Task_UpdateOrganismoOrganigramma()` | `$task` | 7283 |
| `Task_RenameOrganismoNomina()` | `$task` | 7341 |
| `Task_UpdateOrganismoBilancio()` | `$task` | 7401 |
| `Task_TrashOrganismoBilancio()` | `$task` | 7462 |
| `Task_TrashOrganismoDatoContabile()` | `$task` | 7524 |
| `Task_TrashOrganismoIncarico()` | `$task` | 7578 |
| `Task_DeleteOrganismoOrganigramma()` | `$task` | 7630 |
| `Task_DeleteOrganismoOrganigrammaIncarico()` | `$task` | 7684 |
| `Task_AddNewOrganismoBilancio()` | `$task` | 7757 |
| `Task_GetSinesAddNewPartecipazioneDlg()` | `$task` | 7816 |
| `Task_GetSinesModifyPartecipazioneDlg()` | `$task` | 7844 |
| `Task_GetSinesTrashPartecipazioneDlg()` | `$task` | 7881 |
| `Task_GetSections()` | `$task` | 7918 |
| `Task_GetOrganismoModifyDlg()` | `$task` | 7932 |
| `Task_GetOrganismoResumeDlg()` | `$task` | 7959 |
| `Task_GetOrganismoPublishDlg()` | `$task` | 7996 |
| `Task_GetOrganismoReassignDlg()` | `$task` | 8035 |
| `Task_GetOrganismoTrashDlg()` | `$task` | 8075 |
| `Task_GetOrganismoTrashBilancioDlg()` | `$task` | 8115 |
| `Task_GetOrganismoTrashIncaricoDlg()` | `$task` | 8168 |
| `Task_GetOrganismoDeleteOrganigrammaDlg()` | `$task` | 8209 |
| `Task_GetOrganismoDeleteOrganigrammaIncaricoDlg()` | `$task` | 8250 |
| `Task_GetOrganismoTrashIncaricoDocDlg()` | `$task` | 8322 |
| `Task_GetOrganismoTrashIncaricoCompensoDlg()` | `$task` | 8375 |
| `Task_GetOrganismoModifyIncaricoCompensoDlg()` | `$task` | 8428 |
| `Task_TrashOrganismoIncaricoDoc()` | `$task` | 8481 |
| `Task_TrashOrganismoIncaricoCompenso()` | `$task` | 8551 |
| `Task_UpdateOrganismoIncaricoCompenso()` | `$task` | 8621 |
| `Task_GetOrganismoAddNewIncaricoDocDlg()` | `$task` | 8693 |
| `Task_GetOrganismoAddNewIncaricoCompensoDlg()` | `$task` | 8726 |
| `Task_GetOrganismoAddNewOrganigrammaDlg()` | `$task` | 8759 |
| `Task_GetOrganismoModifyOrganigrammaDlg()` | `$task` | 8791 |
| `Task_GetOrganismoTrashDatoContabileDlg()` | `$task` | 8829 |
| `Task_GetOrganismoDeleteDlg()` | `$task` | 8862 |
| `Task_GetOrganismoAddNewDlg()` | `$task` | 8892 |
| `Task_GetOrganismoModifyDatoContabileDlg()` | `$task` | 8915 |
| `Task_GetOrganismoAddNewDatoContabileDlg()` | `$task` | 8948 |
| `Task_GetOrganismoAddNewOrganigrammaIncaricoDlg()` | `$task` | 8980 |
| `Task_GetOrganismoModifyOrganigrammaIncaricoDlg()` | `$task` | 9023 |
| `Task_GetOrganismoAddNewProvvedimentoDlg()` | `$task` | 9078 |
| `Task_GetOrganismoModifyProvvedimentoDlg()` | `$task` | 9115 |
| `Task_GetOrganismoTrashProvvedimentoDlg()` | `$task` | 9154 |
| `Task_GetOrganismoNominaDetailViewDlg()` | `$task` | 9193 |
| `Task_GetOrganismoModifyIncaricoDlg()` | `$task` | 9220 |
| `Task_GetOrganismoModifyBilancioDlg()` | `$task` | 9253 |
| `Task_GetOrganismoAddNewBilancioDlg()` | `$task` | 9294 |
| `Task_GetOrganismoAddNewIncaricoDlg()` | `$task` | 9327 |
| `Task_GetOrganismoAddNewNominaDlg()` | `$task` | 9372 |
| `Task_GetOrganismoRenameNominaDlg()` | `$task` | 9404 |
| `Task_GetOrganismoTrashNominaDlg()` | `$task` | 9448 |
| `Task_TrashOrganismoNomina()` | `$task` | 9495 |
| `Task_GetPubblicateFilterDlg()` | `$task` | 9562 |
| `Task_GetBozzeFilterDlg()` | `$task` | 9577 |
| `Task_GetScadenzarioFilterDlg()` | `$task` | 9592 |
| `Task_GetOrganismoNomineFilterDlg()` | `$task` | 9607 |
| `Task_GetObjectData()` | `$task` | 9622 |
| `Task_GetNavbarContent()` | `$task` | 9663 |
| `Task_GetSectionContent()` | `$task` | 9709 |
| `Task_GetObjectContent()` | `$task` | 9747 |
| `TemplatePubblicateFilterDlg()` | `—` | 9798 |
| `TemplateBozzeFilterDlg()` | `—` | 9881 |
| `TemplateScadenzarioFilterDlg()` | `—` | 9964 |
| `Template_GetOrganismoNomineFilterDlg()` | `$params=null,$filter_id=""` | 10063 |
| `Task_CsvExport()` | `$task` | 10130 |
| `Task_GetOrganismoExportDatoContabile()` | `$task` | 10198 |
| `Template_CsvExport()` | `$objects = array(` | 10225 |
| `Template_OrganismiPdfExport()` | `$organismi=array(` | 10233 |
| `Template_OrganismiPdfExportFull()` | `$organismi=array(` | 10440 |
| `Template_OrganismiPdfRappresentazioneGraficaExport()` | `$organismi=array(` | 10629 |
| `Template_OrganismiScadenzarioPdfExport()` | `$organismi=array(` | 10800 |
| `__construct()` | `$id = "", $title = "Logs", $user=null` | 10963 |
| `Update()` | `—` | 11028 |

### `utils/modules/sines_2/taskmanager.php`

