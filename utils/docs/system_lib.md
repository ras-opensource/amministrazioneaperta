# PHP System Library Documentation

## Class `AA_AMAAI`

- **File:** `/AA_AMAAI.php` (hash: `078d932cb49ef3d8a96e8a5daa2ed7c2db1c9daf2e053ad235466254c3e3f4a0`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `GetInstance` | `GetInstance()` |
| `TemplateLayout` | `TemplateLayout()` |
| `TemplateStart` | `TemplateStart()` |

## Class `AA_Archivio`

- **File:** `/AA_Archivio.php` (hash: `65464ba1a8f7d4cabc92e09ea0caa2250927431126d9a2754b02b85c2a423849`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `Snapshot` | `Snapshot($date = "", $id_object = 0, $object_type = 0, $content = "", $user = null)` |
| `Resume` | `Resume($date = "", $id_object = 0, $object_type = 0)` |
| `ResumeMulti` | `ResumeMulti($id_object = 0, $object_type = 0, $date = "", $num = 1)` |

## Class `AA_Assessorato`

- **File:** `/AA_Assessorato.php` (hash: `7bf3c9b4144f057d73f0fc11d5c6aa0b23af7a3467f381793ad2756ed499fc3f`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `GetTipologie` | `GetTipologie()` |
| `__construct` | `__construct($params = null)` |
| `GetDirezioni` | `GetDirezioni($bAsObjects=false)` |
| `Delete` | `Delete($user=null)` |

## Class `AA_DbBind`

- **File:** `/AA_DbBind.php` (hash: `0ce02f3c43a12263bf7ab09fbfbeddfc21d76d9a28b1fe4b0fb45fd6ee91ed5d`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `GetBindings` | `GetBindings()` |
| `SetTable` | `SetTable($table = "")` |
| `GetTable` | `GetTable()` |
| `AddBind` | `AddBind($nomeVariabile = "", $nomeCampo = "")` |
| `DelBind` | `DelBind($nomeVariabile = "")` |

## Class `AA_Direzione`

- **File:** `/AA_Direzione.php` (hash: `48eb02068564ec8477008582c4447f183c6511e2dc95afabe82c2ccf058350a2`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($params = null)` |
| `Delete` | `Delete($user=null)` |
| `GetServizi` | `GetServizi($bAsObjects=false)` |

## Class `AA_GenericLogDlg`

- **File:** `/AA_GenericLogDlg.php` (hash: `b5650b9b5221281dd50c4193828006631e7f53717a31ad66f9c792a7632196b2`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($id = "", $title = "Logs", $user = null)` |
| `Update` | `Update()` |

## Class `AA_GenericModule`

- **File:** `/AA_GenericModule.php` (hash: `144fa8dcf305861e31249f2467b5c8ea01bde083c6ffd8b79437c4ea2052872c`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `GetTaskManagerUrl` | `GetTaskManagerUrl()` |
| `AddSection` | `AddSection($section)` |
| `GetSection` | `GetSection($section = "AA_GENERIC_MODULE_SECTION")` |
| `AddObjectTemplate` | `AddObjectTemplate($idObject="",$template="")` |
| `DelObjectTemplate` | `DelObjectTemplate($idObject="")` |
| `GetObjectTemplate` | `GetObjectTemplate($idObject="")` |
| `GetSections` | `GetSections($format = "raw")` |
| `GetFlags` | `GetFlags()` |
| `SetSectionItemTemplate` | `SetSectionItemTemplate($section = "", $template = "")` |
| `GetSectionItemTemplate` | `GetSectionItemTemplate($section = "")` |
| `Task_GetSections` | `Task_GetSections($task)` |
| `Task_GetLayout` | `Task_GetLayout($task)` |
| `Task_AMAAI_Start` | `Task_AMAAI_Start($task)` |
| `Template_GenericAMAAIDlg` | `Template_GenericAMAAIDlg()` |
| `GetSideBarConfig` | `GetSideBarConfig($format = "raw")` |
| `GetSideBarId` | `GetSideBarId()` |
| `SetSideBarId` | `SetSideBarId($var = "")` |
| `GetSideBarIcon` | `GetSideBarIcon()` |
| `SetSideBarIcon` | `SetSideBarIcon($var = "")` |
| `GetSideBarName` | `GetSideBarName()` |
| `SetSideBarName` | `SetSideBarName($var = "")` |
| `GetSideBarTooltip` | `GetSideBarTooltip()` |
| `SetSideBarTooltip` | `SetSideBarTooltip($var = "")` |
| `GetUser` | `GetUser()` |
| `RegisterPublicService` | `RegisterPublicService()` |
| `PublicServiceHandler` | `PublicServiceHandler()` |
| `__construct` | `__construct($user = null, $bDefaultSections = true)` |
| `GetId` | `GetId()` |
| `SetId` | `SetId($newId = "")` |
| `GetTaskManager` | `GetTaskManager()` |
| `GetDataGenericSectionPubblicate_List` | `GetDataGenericSectionPubblicate_List($params = array()` |
| `GetDataSectionPubblicate_List` | `GetDataSectionPubblicate_List($params = array()` |
| `TemplateGenericLayout` | `TemplateGenericLayout()` |
| `TemplateLayout` | `TemplateLayout()` |
| `TemplateGenericSection_Placeholder` | `TemplateGenericSection_Placeholder()` |
| `TemplateSection_Placeholder` | `TemplateSection_Placeholder()` |
| `Task_GetGenericActionMenu` | `Task_GetGenericActionMenu($task)` |
| `Task_GetActionMenu` | `Task_GetActionMenu($task)` |
| `TemplateGenericActionMenu_Bozze` | `TemplateGenericActionMenu_Bozze()` |
| `Task_GetGenericNavbarContent` | `Task_GetGenericNavbarContent($task, $params = array()` |
| `Task_GetNavbarContent` | `Task_GetNavbarContent($task)` |
| `Task_GetSideMenuContent` | `Task_GetSideMenuContent($task)` |
| `Task_GenericAddNew` | `Task_GenericAddNew($task, $params = array()` |
| `Task_GenericUpdateObject` | `Task_GenericUpdateObject($task, $params = array()` |
| `Task_GetLogDlg` | `Task_GetLogDlg($task)` |
| `Template_GenericLogDlg` | `Template_GenericLogDlg($object=null)` |
| `Template_GetGenericObjectTrashDlg` | `Template_GetGenericObjectTrashDlg($params, $saveTask = "TrashObject")` |
| `Template_GetGenericObjectDeleteDlg` | `Template_GetGenericObjectDeleteDlg($params, $saveTask = "GenericDeleteObject")` |
| `Template_GetGenericResumeObjectDlg` | `Template_GetGenericResumeObjectDlg($params = array()` |
| `Task_GenericResumeObject` | `Task_GenericResumeObject($task, $params = array()` |
| `Template_GetGenericReassignObjectDlg` | `Template_GetGenericReassignObjectDlg($params = array()` |
| `Task_GenericReassignObject` | `Task_GenericReassignObject($task, $params = array()` |
| `Task_GenericTrashObject` | `Task_GenericTrashObject($task, $params = array()` |
| `Task_GenericDeleteObject` | `Task_GenericDeleteObject($task, $params = array()` |
| `Template_GetGenericPublishObjectDlg` | `Template_GetGenericPublishObjectDlg($params = array()` |
| `Task_GenericPublishObject` | `Task_GenericPublishObject($task, $params = array()` |
| `Task_PdfExport` | `Task_PdfExport($task)` |
| `Template_PdfExport` | `Template_PdfExport($objects = array()` |
| `Task_CsvExport` | `Task_CsvExport($task)` |
| `Template_CsvExport` | `Template_CsvExport($objects = array()` |
| `Template_GenericCsvExport` | `Template_GenericCsvExport($objects=array()` |
| `Template_GenericPdfExport` | `Template_GenericPdfExport($objects = array()` |
| `TemplateGenericNavbar_Bozze` | `TemplateGenericNavbar_Bozze($level = 1, $last = false, $refresh_view = true)` |
| `TemplateGenericNavbar_Section` | `TemplateGenericNavbar_Section($section=null, $level = 1, $last = false, $refresh_view = true)` |
| `Task_GetGenericObjectContent` | `Task_GetGenericObjectContent($task, $params = array()` |
| `Task_GetObjectContent` | `Task_GetObjectContent($task)` |
| `Task_GetSectionContent` | `Task_GetSectionContent($task)` |
| `Task_GetGenericObjectData` | `Task_GetGenericObjectData($task, $params = array()` |
| `Task_GetObjectData` | `Task_GetObjectData($task)` |
| `TemplateSection_Detail` | `TemplateSection_Detail($params)` |
| `TemplateNavbar_Bozze` | `TemplateNavbar_Bozze($level = 1, $last = false, $refresh_view = true)` |
| `TemplateGenericNavbar_Pubblicate` | `TemplateGenericNavbar_Pubblicate($level = 1, $last = false, $refresh_view = true)` |
| `TemplateNavbar_Pubblicate` | `TemplateNavbar_Pubblicate($level = 1, $last = false, $refresh_view = true)` |
| `TemplateGenericNavbar_Back` | `TemplateGenericNavbar_Back($level = 1, $last = false, $refresh_view = false)` |
| `TemplateGenericNavbar_Void` | `TemplateGenericNavbar_Void($level = 1, $last = false)` |
| `TemplateNavbar_Back` | `TemplateNavbar_Back($level = 1, $last = false, $refresh_view = true)` |
| `TemplateActionMenu_Bozze` | `TemplateActionMenu_Bozze()` |
| `TemplateGenericActionMenu_Pubblicate` | `TemplateGenericActionMenu_Pubblicate()` |
| `TemplateActionMenu_Pubblicate` | `TemplateActionMenu_Pubblicate()` |
| `TemplateGenericActionMenu_Detail` | `TemplateGenericActionMenu_Detail()` |
| `TemplateActionMenu_Detail` | `TemplateActionMenu_Detail()` |
| `TemplateGenericSection_Pubblicate` | `TemplateGenericSection_Pubblicate($params = array()` |
| `DataSectionIsFiltered` | `DataSectionIsFiltered($params = array()` |
| `CustomDataSectionIsFiltered` | `CustomDataSectionIsFiltered($params = array()` |
| `TemplateSection_Pubblicate` | `TemplateSection_Pubblicate($params = array()` |
| `GetDataGenericSectionBozze_List` | `GetDataGenericSectionBozze_List($params = array()` |
| `GetDataSectionBozze_List` | `GetDataSectionBozze_List($params = array()` |
| `TemplateGenericSection_Bozze` | `TemplateGenericSection_Bozze($params, $contentData = null)` |
| `TemplateSection_Bozze` | `TemplateSection_Bozze($params = array()` |
| `TemplateGenericSection_Detail` | `TemplateGenericSection_Detail($params)` |
| `TemplateGenericTabbedSection` | `TemplateGenericTabbedSection($id_section="",$object=null,$params=null)` |
| `TemplateGenericDettaglio_Generale_Tab` | `TemplateGenericDettaglio_Generale_Tab($object = null)` |
| `TemplateGenericDettaglio_Header_Generale_Tab` | `TemplateGenericDettaglio_Header_Generale_Tab($object = null, $id = "",$header_content=null,$bModify=null)` |

## Class `AA_GenericModuleSection`

- **File:** `/AA_GenericModuleSection.php` (hash: `b12f1f9e91f7ee3dff8006ba59850bd79efbe058b085bfbaee810d7e58ef6f28`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `GetId` | `GetId()` |
| `GetName` | `GetName()` |
| `SetName` | `SetName($val = "new name")` |
| `GetIcon` | `GetIcon()` |
| `SetIcon` | `SetIcon($val = "")` |
| `IsVisibleInNavbar` | `IsVisibleInNavbar()` |
| `GetViewId` | `GetViewId()` |
| `GetModuleId` | `GetModuleId()` |
| `isValid` | `isValid()` |
| `IsDefault` | `IsDefault()` |
| `IsDetail` | `IsDetail()` |
| `SetDetail` | `SetDetail($bVal = true)` |
| `SetTemplate` | `SetTemplate($val="")` |
| `GetTemplate` | `GetTemplate()` |
| `SetNavbarTemplate` | `SetNavbarTemplate($template = "{}")` |
| `EnableRefreshView` | `EnableRefreshView($bVal = true)` |
| `toArray` | `toArray()` |
| `__toString` | `__toString()` |
| `toString` | `toString()` |
| `toBase64` | `toBase64()` |
| `__construct` | `__construct($id = "AA_GENERIC_MODULE_SECTION", $name = "section name", $navbar = false, $view_id = "AA_Section_Content_Box", $module_id = "AA_GENERIC_MODULE", $default = false, $refresh_view = true, $detail = false, $valid = false, $icon="",$template="")` |
| `TemplateActionMenu` | `TemplateActionMenu()` |
| `TemplateGenericActionMenu` | `TemplateGenericActionMenu()` |

## Class `AA_GenericModuleTask`

- **File:** `/AA_GenericModuleTask.php` (hash: `64bbeed1b45aedda5653a1c0e7be47a341c1f358daaddb75507efcce4aa86990`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `SetContent` | `SetContent($val="", $json=false, $encode=true)` |
| `SetStatus` | `SetStatus($val=0)` |
| `SetStatusAction` | `SetStatusAction($action="",$params=null,$json_encode=false)` |
| `SetStatusActionParams` | `SetStatusActionParams($params=null,$json_encode=false)` |
| `SetError` | `SetError($val="", $json=false, $encode=true)` |
| `__construct` | `__construct($task = "", $user = null, $taskManager = null, $taskFunction = "")` |
| `Run` | `Run()` |
| `GetLog` | `GetLog()` |

## Class `AA_GenericModuleTaskManager`

- **File:** `/AA_GenericModuleTaskManager.php` (hash: `06b785d830b8995619368777244cc2754eee370ae85fd4abf73dc22b3f834945`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `GetModule` | `GetModule()` |
| `__construct` | `__construct($module = null, $user = null)` |
| `RegisterTask` | `RegisterTask($task = null, $taskFunction = "")` |

## Class `AA_GenericNews`

- **File:** `/AA_GenericNews.php` (hash: `815b55bcf2d165d8edb750c62b798b92eb1e87f66e00a2979f8865faa72892e0`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($params = null)` |

## Class `AA_GenericPagedSectionTemplate`

- **File:** `/AA_GenericPagedSectionTemplate.php` (hash: `5481fa8350a458e3b50724990e5aa79ae69fd3841492e68eaa642210cd2a5959`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `GetHeader` | `GetHeader()` |
| `SetHeader` | `SetHeader($obj = "")` |
| `SetContentBox` | `SetContentBox($obj = "")` |
| `SetContentBoxTemplate` | `SetContentBoxTemplate($template = "")` |
| `GetContentBoxTemplate` | `GetContentBoxTemplate()` |
| `SetContentBoxData` | `SetContentBoxData($data = array()` |
| `GetContentBoxData` | `GetContentBoxData()` |
| `EnableSelect` | `EnableSelect($bVal = true)` |
| `EnableMultiSelect` | `EnableMultiSelect($bVal = true)` |
| `SetContentItemsForPage` | `SetContentItemsForPage($val = "5")` |
| `GetContentItemsForPage` | `GetContentItemsForPage()` |
| `SetContentItemsForRow` | `SetContentItemsForRow($val = 1)` |
| `GetContentItemsForRow` | `GetContentItemsForRow()` |
| `SetContentItemHeight` | `SetContentItemHeight($val = "auto")` |
| `GetContentItemHeight` | `GetContentItemHeight()` |
| `toObject` | `toObject()` |
| `__toString` | `__toString()` |
| `toArray` | `toArray()` |
| `toBase64` | `toBase64()` |
| `GetPager` | `GetPager()` |
| `SetPager` | `SetPager($obj = "")` |
| `SetPagerFiltered` | `SetPagerFiltered($var=true)` |
| `IsPagerFiltered` | `IsPagerFiltered()` |
| `GetPagerTitle` | `GetPagerTitle()` |
| `SetPagerTitle` | `SetPagerTitle($obj = "")` |
| `GetToolbar` | `GetToolbar()` |
| `SetToolbar` | `SetToolbar($obj = "")` |
| `SetModule` | `SetModule($id)` |
| `GetModule` | `GetModule()` |
| `__construct` | `__construct($id = "AA_GenericPagedSectionTemplate", $module = "", $content_box = "")` |
| `SetShowDetailSectionFunc` | `SetShowDetailSectionFunc($val="")` |
| `Update` | `Update()` |
| `SetSectionName` | `SetSectionName($val = "Titolo")` |
| `GetSectionName` | `GetSectionName()` |
| `EnablePaging` | `EnablePaging($bVal = true)` |
| `EnablePager` | `EnablePager($bVal = true)` |
| `DisablePaging` | `DisablePaging()` |
| `IsPaged` | `IsPaged()` |
| `SetPagerCurPage` | `SetPagerCurPage($page = 0)` |
| `SetPagerItemForPage` | `SetPagerItemForPage($var = 10)` |
| `SetPagerItemCount` | `SetPagerItemCount($var = 10)` |
| `SetPagerTarget` | `SetPagerTarget($var = "")` |
| `SetPagerTargetAction` | `SetPagerTargetAction($var = "")` |
| `EnableFiltering` | `EnableFiltering()` |
| `DisableFiltering` | `DisableFiltering()` |
| `IsFiltered` | `IsFiltered()` |
| `SetSaveFilterId` | `SetSaveFilterId($id = "")` |
| `GetSaveFilterId` | `GetSaveFilterId()` |
| `SetFilterData` | `SetFilterData($data = array()` |
| `GetFilterData` | `GetFilterData()` |
| `SetFilterDlgTask` | `SetFilterDlgTask($var = "")` |
| `GetFilterDlgTask` | `GetFilterDlgTask()` |
| `EnableAddNew` | `EnableAddNew($bVal = true)` |
| `SetAddNewDlgTask` | `SetAddNewDlgTask($task = "")` |
| `EnableAddNewMulti` | `EnableAddNewMulti($bVal = true)` |
| `SetAddNewMultiDlgTask` | `SetAddNewMultiDlgTask($task = "")` |
| `ViewDetail` | `ViewDetail($bVal = true)` |
| `HideDetail` | `HideDetail()` |
| `EnableDetail` | `EnableDetail($bVal = true)` |
| `DisableDetail` | `DisableDetail()` |
| `ViewTrash` | `ViewTrash($bVal = true)` |
| `HideTrash` | `HideTrash()` |
| `EnableTrash` | `EnableTrash($bVal = true)` |
| `DisableTrash` | `DisableTrash()` |
| `SetTrashHandler` | `SetTrashHandler($handler = null, $params = null)` |
| `SetTrashHandlerParams` | `SetTrashHandlerParams($params = null)` |
| `ViewDelete` | `ViewDelete($bVal = true)` |
| `HideDelete` | `HideDelete()` |
| `EnableDelete` | `EnableDelete($bVal = true)` |
| `DisableDelete` | `DisableDelete()` |
| `SetDeleteHandler` | `SetDeleteHandler($handler = null, $params = null)` |
| `SetDeleteHandlerParams` | `SetDeleteHandlerParams($params = null)` |
| `ViewReassign` | `ViewReassign($bVal = true)` |
| `HideReassign` | `HideReassign()` |
| `EnableReassign` | `EnableReassign($bVal = true)` |
| `DisableReassign` | `DisableReassign()` |
| `SetReassignHandler` | `SetReassignHandler($handler = null, $params = null)` |
| `SetReassignHandlerParams` | `SetReassignHandlerParams($params = null)` |
| `ViewResume` | `ViewResume($bVal = true)` |
| `HideResume` | `HideResume()` |
| `EnableResume` | `EnableResume($bVal = true)` |
| `DisableResume` | `DisableResume()` |
| `SetResumeHandler` | `SetResumeHandler($handler = null, $params = null)` |
| `SetResumeHandlerParams` | `SetResumeHandlerParams($params = null)` |
| `ViewPublish` | `ViewPublish($bVal = true)` |
| `HidePublish` | `HidePublish()` |
| `EnablePublish` | `EnablePublish($bVal = true)` |
| `DisablePublish` | `DisablePublish()` |
| `SetPublishHandler` | `SetPublishHandler($handler = null, $params = null)` |
| `SetPublishHandlerParams` | `SetPublishHandlerParams($params = null)` |
| `ViewSaveAsPdf` | `ViewSaveAsPdf($bVal = true)` |
| `HideSaveAsPdf` | `HideSaveAsPdf()` |
| `EnableSaveAsPdf` | `EnableSaveAsPdf($bVal = true)` |
| `DisableSaveAsPdf` | `DisableSaveAsPdf()` |
| `ViewSaveAsCsv` | `ViewSaveAsCsv($bVal = true)` |
| `HideSaveAsCsv` | `HideSaveAsCsv()` |
| `EnableSaveAsCsv` | `EnableSaveAsCsv($bVal = true)` |
| `DisableSaveAsCsv` | `DisableSaveAsCsv()` |
| `ViewExportFunctions` | `ViewExportFunctions($bVal = true)` |
| `HideExportFunctions` | `HideExportFunctions()` |
| `EnableExportFunctions` | `EnableExportFunctions($bVal = true)` |
| `DisableExportFunctions` | `DisableExportFunctions()` |
| `SetSaveAsPdfHandler` | `SetSaveAsPdfHandler($handler = null, $params = null)` |
| `SetSaveAsPdfHandlerParams` | `SetSaveAsPdfHandlerParams($params = null)` |
| `SetSaveAsCsvHandler` | `SetSaveAsCsvHandler($handler = null, $params = null)` |
| `SetSaveAsCsvHandlerParams` | `SetSaveAsCsvHandlerParams($params = null)` |

## Class `AA_GenericParsableDbObject`

- **File:** `/AA_GenericParsableDbObject.php` (hash: `bd56fe9eee8e36e9b7e74da8241350bd25d6eaab9e7f6e13e566f29b38676f6c`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `GetDatatable` | `GetDatatable()` |
| `GetObjectClass` | `GetObjectClass()` |
| `GetDbClass` | `GetDbClass()` |
| `__construct` | `__construct($params=null)` |
| `Sync` | `Sync()` |
| `Update` | `Update($params=null, $user=null)` |
| `Search` | `Search($params=null)` |
| `LoadDataFromDb` | `LoadDataFromDb($id=0)` |
| `Load` | `Load($id=0,$user=null)` |
| `Delete` | `Delete($user=null)` |
| `DeleteFromDb` | `DeleteFromDb()` |

## Class `AA_GenericParsableObject`

- **File:** `/AA_GenericParsableObject.php` (hash: `404568d8b6b3c32c645d1e341975c71e363ca06535e8dbdc5b5fd31b9470d82b`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `Parse` | `Parse($values=null)` |
| `SetTemplateViewProps` | `SetTemplateViewProps($props=null)` |
| `GetTemplateViewProps` | `GetTemplateViewProps()` |
| `SetDefaultTemplateViewProps` | `SetDefaultTemplateViewProps()` |
| `GetTemplateView` | `GetTemplateView($bRefresh=false)` |
| `GetDefaultTemplateView` | `GetDefaultTemplateView()` |
| `SetTemplateView` | `SetTemplateView($var = null)` |
| `__construct` | `__construct($params=null)` |
| `SetProp` | `SetProp($prop="",$value="")` |
| `GetProp` | `GetProp($prop="")` |
| `GetProps` | `GetProps()` |

## Class `AA_GenericPdfPreviewDlg`

- **File:** `/AA_GenericPdfPreviewDlg.php` (hash: `73207d9b506d1441ef705406d0ba6d67dcd659c3ed45ace6a619e13d0f0d5c07`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($id = "", $title = "Pdf Viewer", $module = "")` |
| `Update` | `Update()` |

## Class `AA_GenericResetPwdDlg`

- **File:** `/AA_GenericResetPwdDlg.php` (hash: `c881de52d56b24e0e553f878402ae607b863627a2c6f195f68b3b39eb30e388b`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `___construct` | `___construct($id = "", $title = "", $formData = array()` |

## Class `AA_GenericResources`

- **File:** `/AA_GenericResources.php` (hash: `a602b6f507b7c85096c62834555f720c7c1b726e51fcdced7462e2046b341a0b`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($params = null)` |

## Class `AA_GenericServerStatusDlg`

- **File:** `/AA_GenericServerStatusDlg.php` (hash: `63bfdf35e531fe267a65546bb75e02c60f09610c281c3c8fbbc7bf9e960a40ff`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($id = "", $title = "Logs", $user = null)` |
| `Update` | `Update()` |

## Class `AA_GenericStructDlg`

- **File:** `/AA_GenericStructDlg.php` (hash: `76d5914f6be9a07790dafe8ca1b1673c65356de598c718345dd6d3cdceb56a90`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `GetTargetForm` | `GetTargetForm()` |
| `SetApplyActions` | `SetApplyActions($actions = "")` |
| `__construct` | `__construct($id = "", $title = "", $options = null, $applyActions = "", $module = "", $user = null)` |
| `Update` | `Update()` |

## Class `AA_GenericTask`

- **File:** `/AA_GenericTask.php` (hash: `d1a0d2a3951336ac811f8b47f867fb90bbbf6e45a18853455e6ddb3b7ab36ce2`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `GetTaskManager` | `GetTaskManager()` |
| `SetTaskManager` | `SetTaskManager($taskManager = null)` |
| `GetName` | `GetName()` |
| `SetName` | `SetName($name = "")` |
| `GetError` | `GetError()` |
| `SetError` | `SetError($error)` |
| `Run` | `Run()` |
| `__construct` | `__construct($taskName = "", $user = null, $taskManager = null)` |
| `GetLog` | `GetLog()` |
| `SetLog` | `SetLog($log)` |

## Class `AA_GenericTaskManager`

- **File:** `/AA_GenericTaskManager.php` (hash: `927eee49494f4690df5f75147bd612a76bc2ddfae230e3adb219d10e48cfe1da`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `RegisterTask` | `RegisterTask($task = null, $class = null)` |
| `Clear` | `Clear()` |
| `GetTask` | `GetTask($name = "")` |
| `UnregisterTask` | `UnregisterTask($name = "")` |
| `RunTask` | `RunTask($taskName = "")` |
| `GetTaskLog` | `GetTaskLog($taskName = "")` |
| `GetTaskError` | `GetTaskError($taskName = "")` |
| `IsManaged` | `IsManaged($taskName = "")` |

## Class `AA_GenericTaskResponse`

- **File:** `/AA_GenericTaskResponse.php` (hash: `15a96beff69a973b81efdf88ab9d2f5c83cbe321bff94636e709fe32179d0045`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `SetStatus` | `SetStatus($newStatus = AA_Const::AA_TASK_STATUS_OK)` |
| `GetStatus` | `GetStatus()` |
| `SetError` | `SetError($error = "")` |
| `GetError` | `GetError()` |
| `SetMsg` | `SetMsg($error = "")` |
| `GetMsg` | `GetMsg()` |
| `SetContent` | `SetContent($val = "")` |
| `GetContent` | `GetContent()` |
| `toString` | `toString()` |

## Class `AA_NewsTags`

- **File:** `/AA_NewsTags.php` (hash: `11fbccf78b5dc6d600d354447a24484b0ebddcc1bcda4494d0a2ee065dad0c48`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `Initialize` | `Initialize()` |
| `__construct` | `__construct()` |
| `GetTags` | `GetTags($params=null)` |

## Class `AA_ObjectVarMapping`

- **File:** `/AA_ObjectVarMapping.php` (hash: `527e669e84ea371713ee30c47ba85819ef8fb3acee19bb158ae917e92a50385d`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `GetObject` | `GetObject()` |
| `SetObject` | `SetObject($object = null)` |
| `__construct` | `__construct($object = null)` |
| `AddVar` | `AddVar($var_name = "", $name = "", $type = "", $label = "")` |
| `DelVar` | `DelVar($var_name = "")` |
| `GetName` | `GetName($var_name = "")` |
| `GetType` | `GetType($var_name = "")` |
| `GetLabel` | `GetLabel($var_name = "")` |

## Class `AA_Risorse`

- **File:** `/AA_Risorse.php` (hash: `a178241b5a4df207046830afb35d749ec0d49256646aa9431a922ada1b041041`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($params = null)` |
| `LoadFromUrlName` | `LoadFromUrlName($url_name)` |
| `Parse` | `Parse($values = null)` |
| `GetFileInfo` | `GetFileInfo()` |
| `SetFileInfo` | `SetFileInfo($val=null)` |
| `AddFile` | `AddFile($params=null,$user=null)` |
| `AddFileFromStorage` | `AddFileFromStorage($hash="",$url_name="",$categorie="",$user=null)` |
| `AddGenericFileFromUpload` | `AddGenericFileFromUpload($url_name="",$categorie="",$user=null)` |
| `GetFile` | `GetFile($user=null)` |
| `Delete` | `Delete($user=null)` |

## Class `AA_Servizio`

- **File:** `/AA_Servizio.php` (hash: `2ca661b0e735748dff6adc4c55bef13d29ad8a6edf521abe9eff05b358a62646`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($params = null)` |

## Class `AA_Struttura`

- **File:** `/AA_Struttura.php` (hash: `427f4178e927046acaf101a8ba31adc48e56931d022e318e5b84b5842bb3d6ee`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($params = null)` |
| `Delete` | `Delete($user=null)` |

## Class `AA_SystemTask_AMAAI_Start`

- **File:** `/AA_SystemTask_AMAAI_Start.php` (hash: `c77ec490618ae6b5ff07be8d8870aa0c8e8f4044849c062f5b7e8df226d8113e`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_ChangeCurrentUserProfile`

- **File:** `/AA_SystemTask_ChangeCurrentUserProfile.php` (hash: `c162f506eaa994d2832672bddedca42dacfe3295028a6bab2c74392a4fe1ac99`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetAppStatus`

- **File:** `/AA_SystemTask_GetAppStatus.php` (hash: `4e38628d87fcb51821224ce7046477c7617ae62645ada654119729b77262ba18`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetChangeCurrentUserProfileDlg`

- **File:** `/AA_SystemTask_GetChangeCurrentUserProfileDlg.php` (hash: `f653b096c1edcc030d657451131092ecee50aba66ffffcf58dffecf9a268f01b`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetChangeCurrentUserPwdDlg`

- **File:** `/AA_SystemTask_GetChangeCurrentUserPwdDlg.php` (hash: `316f7e5551e6e98dfeee40ac39d5bbdcdba29fadcc628d640b7cbc5dac1ae823`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetCurrentUserProfileDlg`

- **File:** `/AA_SystemTask_GetCurrentUserProfileDlg.php` (hash: `c0da10479204b5830a343352e7f5ea37071a1cb1c6e2fbc5c65f961a4bdc90c4`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetGalleryData`

- **File:** `/AA_SystemTask_GetGalleryData.php` (hash: `d47a1f922f7c109e7bb07736342f5d59033097502f60fc3d3adc954a5ce95ba8`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetGalleryDlg`

- **File:** `/AA_SystemTask_GetGalleryDlg.php` (hash: `4692cbf997e18b7356dac83bb38009d03115ab062967d7848889cdf8d0ba6721`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetGalleryTrashDlg`

- **File:** `/AA_SystemTask_GetGalleryTrashDlg.php` (hash: `ad446cdb9e6e1b027e747211486be6d022c5dc352fdfe15466265c5995096da1`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetLogDlg`

- **File:** `/AA_SystemTask_GetLogDlg.php` (hash: `1ba7f17a46c6a484e5e175874e98cbb558d264b81249efedb0efdc3158588e2e`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetNews`

- **File:** `/AA_SystemTask_GetNews.php` (hash: `aa33b2c5e75843e61c9750618c53b93167e16e240937af1c9315ce73266ffdc6`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetPdfPreviewDlg`

- **File:** `/AA_SystemTask_GetPdfPreviewDlg.php` (hash: `57f02ff695448bc956a4ceed34676cac6022183d06e4fab5ee619867f318e94b`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetServerStatus`

- **File:** `/AA_SystemTask_GetServerStatus.php` (hash: `26befd652c113d5fbc765c0fa1545bfc62e1fee5e7977255a9662a3121ea0699`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetServerStatusDlg`

- **File:** `/AA_SystemTask_GetServerStatusDlg.php` (hash: `a16913789df5b0ae88e1a88d0da4dcfb82be3f04a4cd750d1ee44bee67b2fdcb`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetSideMenuContent`

- **File:** `/AA_SystemTask_GetSideMenuContent.php` (hash: `6c62d8d7f0f9cd512515bb08d2163ed4c772e99dd8c0b83583eaf5e6cc661ca3`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetStructDlg`

- **File:** `/AA_SystemTask_GetStructDlg.php` (hash: `98a96807d6b642820db4394a8c03538994c04692c7a443d7a7485d98744a8445`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_GetStructTreeData`

- **File:** `/AA_SystemTask_GetStructTreeData.php` (hash: `94b9d038c84ae684b5a3f78046f5a20e0c120e9bb5cd92c721d2834149ec7590`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_RefreshGalleryContent`

- **File:** `/AA_SystemTask_RefreshGalleryContent.php` (hash: `56117aae3f80b0f80ed9c4ee775e37549f87828ae55ef19e649f17556922654b`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_SetSessionVar`

- **File:** `/AA_SystemTask_SetSessionVar.php` (hash: `5f8215301c944f79f5129d15cb4a54eda80e8a1e5f041a702e473eb0149f95e0`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_TrashFromGallery`

- **File:** `/AA_SystemTask_TrashFromGallery.php` (hash: `19cdfc454cecae0d237ce06fd2f16b40d83ae15ac14bb30ebcf786e8c6a99f77`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_TreeStruct`

- **File:** `/AA_SystemTask_TreeStruct.php` (hash: `72af5d550a847c8543002aff7ab22348a4ed404b31074b1664748402afab88c1`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_UpdateCurrentUserProfile`

- **File:** `/AA_SystemTask_UpdateCurrentUserProfile.php` (hash: `db5eac34df7366e7c854ca8a0c42864cd1d2341e7ff104d1eafd2f147f8585c0`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_UpdateCurrentUserPwd`

- **File:** `/AA_SystemTask_UpdateCurrentUserPwd.php` (hash: `a1ac45ad718fb4b2efe706da30aaf3dfba633665560ca3b90123a7b8cd4eb333`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_UploadFromCKeditor5`

- **File:** `/AA_SystemTask_UploadFromCKeditor5.php` (hash: `08129fc996f0852614c6e5bdccae3c610dbf8674f80978b7305b1eb922c5d996`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_UploadFromGallery`

- **File:** `/AA_SystemTask_UploadFromGallery.php` (hash: `1a3c3d5059a8b534d3dd8273d434275ac6cc76e88400c6c4dbaff4d36000e291`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTask_UploadSessionFile`

- **File:** `/AA_SystemTask_UploadSessionFile.php` (hash: `9eecbb45b005d506958a7a4a192d9145364480ec14d441de1d1285adef25dd58`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |
| `Run` | `Run()` |

## Class `AA_SystemTaskManager`

- **File:** `/AA_SystemTaskManager.php` (hash: `3598a84825f8917c800280f493a6c0dcb68d983d525b33f67f8e1a977a81883a`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct($user = null)` |

## Class `AA_XML_FEED`

- **File:** `/AA_XML_FEED.php` (hash: `a6afeb76317a008055a5c874cdcedddbceef605f627c2a37d80b7ea31a68aabf`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `SetURL` | `SetURL($var = "")` |
| `GetURL` | `GetURL()` |
| `Timestamp` | `Timestamp()` |
| `GetParams` | `GetParams()` |
| `SetParams` | `SetParams($params = array()` |
| `GetContent` | `GetContent()` |
| `SetContent` | `SetContent($var)` |
| `toXML` | `toXML()` |
| `__toString` | `__toString()` |

## Class `AA_XML_FEED_ARCHIVIO`

- **File:** `/AA_XML_FEED_ARCHIVIO.php` (hash: `e9d4e78f7983fc7dd0470c2f8702a421c196f963176c1a24df98fd8b8992a3e4`)
- **Description:** 

| Method | Signature |
|-------|-----------|
| `__construct` | `__construct()` |

---
Generated by script.
## Riepilogo delle classi PHP in `system_lib`

| Classe | Scopo / Responsabilità principale |
|--------|---------------------------------|
| **AA_AMAAI.php** | Gestisce il modulo **AMAAI** (probabile interfaccia avanzata di amministrazione). Contiene costanti e helper specifici. |
| **AA_Archivio.php** | Rappresenta l’oggetto **Archivio**. Offre operazioni CRUD su file e gestisce l’archiviazione nel sistema. |
| **AA_Assessorato.php** | Modello per un **Assessorato** (dipartimento/ufficio). Gestisce nome, ID e permessi, con bind al DB. |
| **AA_DbBind.php** | Helper generico per il binding DB: converte oggetti PHP in righe DB e viceversa, gestendo casting e generazione SQL. |
| **AA_Direzione.php** | Modello per **Direzione**. Gestisce relazioni con altri moduli e mappatura ruoli/permessi. |
| **AA_GenericLogDlg.php** | Dialogo UI per visualizzare i log generici. Formatta e mostra le voci di log nella UI web. |
| **AA_GenericModule.php** | Core framework per tutti i **moduli generici** (notizie, risorse, archivio…). Gestisce sezioni, task e costanti UI (box di lista, filtri, ecc.). |
| **AA_GenericModuleSection.php** | Rappresenta una **sezione** di un modulo generico (es. “Bozze”, “Pubblicate”). Tratta ID, nomi e costanti. |
| **AA_GenericModuleTaskManager.php** | Gestisce l’esecuzione dei task generici (pubblica, elimina, riprendi, ecc.) su tutti i moduli. |
| **AA_GenericModuleTask.php** | Classe astratta per un singolo task di un modulo generico. Implementazioni concrete definiscono le azioni. |
| **AA_GenericNews.php** | Rappresenta un articolo di notizia. Estende la gestione generica, aggiungendo campi come titolo, sommario, corpo, immagine. |
| **AA_GenericPagedSectionTemplate.php** | Fornisce la logica di paginazione per sezioni di un modulo generico (es. elenco articoli con filtri). |
| **AA_GenericParsableDbObject.php** | Base per oggetti DB‑backed che possono essere serializzati in XML/JSON. Gestisce (de)serializzazione. |
| **AA_GenericParsableObject.php** | Utility per oggetti “parsable” (non necessariamente DB). Converte in XML/JSON. |
| **AA_GenericPdfPreviewDlg.php** | Componente UI che mostra un PDF in un dialog, integrato nel flusso generico. |
| **AA_GenericResetPwdDlg.php** | Dialogo/UI per reimpostare password utente nel framework generico. |
| **AA_GenericResources.php** | Gestisce oggetti **risorsa** (file, immagini, documenti). Offre upload, cancellazione e recupero. |
| **AA_GenericServerStatusDlg.php** | Mostra lo stato del server (CPU, RAM, uptime) in un dialog. |
| **AA_GenericStructDlg.php** | Dialogo per creare/modificare **strutture** (categorie, gerarchie) di un modulo generico. |
| **AA_GenericTaskManager.php** | Coordinatore di più **AA_GenericTask**. Controlla flusso di esecuzione e report di errore. |
| **AA_GenericTask.php** | Classe astratta di un task. Concrete implementazioni definiscono `Execute()`. |
| **AA_GenericTaskResponse.php** | DTO semplice per restituire risultati di un task (stato + messaggi). |
| **AA_NewsTags.php** | Gestisce i **tag** associabili alle notizie. Offre CRUD per tag e associazioni. |
| **AA_ObjectVarMapping.php** | Mappa nomi di variabili a proprietà di oggetti, semplificando il trasferimento dati UI→backend. |
| **AA_Risorse.php** | Alternativa alla gestione delle risorse: fornisce metodi per ottenere liste, categorie e permessi. |
| **AA_Servizio.php** | Modello per un **servizio**. Gestisce metadati e bind a risorse (es. allegati). |
| **AA_Struttura.php** | Gestisce strutture gerarchiche (alberi). Fornisce funzioni per costruire, percorrere e salvare strutture. |
| **AA_SystemTask_AMAAI_Start.php** | Task di sistema che avvia il modulo AMAAI (es. all’accesso o all’avvio). |
| **AA_SystemTask_ChangeCurrentUserProfile.php** | Task per cambiare il profilo utente corrente (ruolo, permessi) nella sessione. |
| **AA_SystemTask_GetAppStatus.php** | Restituisce lo stato globale dell’app (pronto, manutenzione, ecc.) in JSON. |
| **AA_SystemTask_GetChangeCurrentUserProfileDlg.php** | Genera il dialogo per cambiare il profilo utente. |
| **AA_SystemTask_GetChangeCurrentUserPwdDlg.php** | Dialogo di modifica password utente. |
| **AA_SystemTask_GetCurrentUserProfileDlg.php** | Dialogo che mostra il profilo utente corrente. |
| **AA_SystemTask_GetGalleryData.php** | Recupera dati della galleria (immagini, file) per cartella/ricerca. |
| **AA_SystemTask_GetGalleryDlg.php** | Apre il dialogo galleria. |
| **AA_SystemTask_GetGalleryTrashDlg.php** | Dialogo per la galleria della spazzatura. |
| **AA_SystemTask_GetLogDlg.php** | Dialogo dei log. |
| **AA_SystemTask_GetNews.php** | Restituisce notizie (lista o singola) in JSON. |
| **AA_SystemTask_GetPdfPreviewDlg.php** | Genera dialogo anteprima PDF. |
| **AA_SystemTask_GetServerStatusDlg.php** | Mostra dialogo con stato server. |
| **AA_SystemTask_GetServerStatus.php** | Restituisce dati grezzi di stato server. |
| **AA_SystemTask_GetSideMenuContent.php** | Fornisce struttura del menu laterale (sezioni, link). |
| **AA_SystemTask_GetStructDlg.php** | Dialogo per l’editing di una struttura. |
| **AA_SystemTask_GetStructTreeData.php** | Restituisce JSON a struttura ad albero di una struttura. |
| **AA_SystemTaskManager.php** | Dispatcher dei task di sistema. Riceve nome task, argomenti e restituisce risultato. |
| **AA_SystemTask_RefreshGalleryContent.php** | Aggiorna i metadati della galleria (es. dopo upload). |
| **AA_SystemTask_SetSessionVar.php** | Setta una variabile di sessione da richiesta HTTP. |
| **AA_SystemTask_TrashFromGallery.php** | Sposta un elemento dalla galleria nella spazzatura. |
| **AA_SystemTask_TreeStruct.php** | Funzioni di manipolazione albero (sposta, ordina, elimina). |
| **AA_SystemTask_UpdateCurrentUserProfile.php** | Salva le modifiche al profilo utente. |
| **AA_SystemTask_UpdateCurrentUserPwd.php** | Salva nuova password utente. |
| **AA_SystemTask_UploadFromCKeditor5.php** | Gestisce upload di immagini/file da CKEditor 5. |
| **AA_SystemTask_UploadFromGallery.php** | Upload diretto nella galleria (drag & drop). |
| **AA_SystemTask_UploadSessionFile.php** | Upload per la cartella sessione (temporal). |
| **AA_XML_FEED_ARCHIVIO.php** | Crea feed XML per la sezione Archivio. |
| **AA_XML_FEED.php** | Generatore XML generic, usato da notizie e altri moduli. |

### Come si interconnettono
- **Moduli generici** – `AA_GenericModule` e le sue classi di supporto forniscono un framework riusabile per la maggior parte degli oggetti.  |
- **Task di sistema** – `AA_SystemTaskManager` dispatcha ogni file `AA_SystemTask_*`, che viene chiamato tramite AJAX e restituisce JSON o genera dialoghi. |
- **Modelli dati** – oggetti concreti (`Archivio`, `News`, `Servizio`, ecc.) estendono le classi di base per gestire campi DB e serializzazione. |
- **Dialog UI** – i file `...Dlg.php` generano le finestre modali che vengono inviate al browser. |
- **Utility di supporto** – logging, sessioni, XML feed e upload sono centralizzati nei file `AA_*`.
