# API Reference - Amministrazione Aperta

> Generato automaticamente dal codice (script `scripts/gen_api.py`). Non modificare a mano:
> rigenerare dopo ogni cambio di firma in `utils/system_core/` e `utils/system_lib/`.
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

