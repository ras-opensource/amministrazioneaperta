<?php
class AA_GenericPagedSectionTemplate
{
    //Header box
    protected $header_box = "";
    public function GetHeader()
    {
        return $this->header_box;
    }
    public function SetHeader($obj = "")
    {
        if ($obj instanceof AA_JSON_Template_Generic) $this->header_box = $obj;
    }

    //Content box
    protected $content = null;
    protected $content_box = null;
    public function SetContentBox($obj = "")
    {
        if ($obj instanceof AA_JSON_Template_Generic) $this->content_box = $obj;
    }

    //Content box template
    protected $contentBoxTemplate = "";
    public function SetContentBoxTemplate($template = "")
    {
        $this->contentBoxTemplate = $template;
    }
    public function GetContentBoxTemplate()
    {
        return $this->contentBoxTemplate;
    }

    //Content box data
    protected $contentBoxData = array();
    public function SetContentBoxData($data = array())
    {
        $this->contentBoxData = $data;
    }
    public function GetContentBoxData()
    {
        return $this->contentBoxData;
    }
    protected $contentEnableSelect = false;
    protected $contentEnableMultiSelect = false;
    public function EnableSelect($bVal = true)
    {
        $this->contentEnableSelect = $bVal;
    }
    public function EnableMultiSelect($bVal = true)
    {
        $this->contentEnableMultiSelect = $bVal;
    }
    protected $contentItemsForPage = "5";
    public function SetContentItemsForPage($val = "5")
    {
        $this->contentItemsForPage = $val;
    }
    public function GetContentItemsForPage()
    {
        return $this->contentItemsForPage;
    }

    protected $contentItemsForRow = 1;
    public function SetContentItemsForRow($val = 1)
    {
        $this->contentItemsForRow = $val;
    }
    public function GetContentItemsForRow()
    {
        return $this->contentItemsForRow;
    }

    protected $contentItemHeight = "auto";
    public function SetContentItemHeight($val = "auto")
    {
        $this->contentItemHeight = $val;
    }
    public function GetContentItemHeight()
    {
        return $this->contentItemHeight;
    }

    //Funzioni di rendering
    public function toObject()
    {
        $this->Update();
        return $this->content;
    }
    public function __toString()
    {
        return $this->toObject()->toString();
    }
    public function toArray()
    {
        return $this->toObject()->toArray();
    }
    public function toBase64()
    {
        return $this->toObject()->toBase64();
    }
    #----------------------------------

    //pager box
    protected $pager_box = "";
    public function GetPager()
    {
        return $this->pager_box;
    }
    public function SetPager($obj = "")
    {
        if ($obj instanceof AA_JSON_Template_Generic) $this->pager_box = $obj;
    }

    //pager filter
    protected $pager_filtered=false;
    public function SetPagerFiltered($var=true)
    {
        $this->pager_filtered=$var;
    }
    public function IsPagerFiltered()
    {
        return $this->pager_filtered;
    }

    //Pager title box
    protected $pagerTitle_box = "";
    public function GetPagerTitle()
    {
        return $this->pagerTitle_box;
    }
    public function SetPagerTitle($obj = "")
    {
        if ($obj instanceof AA_JSON_Template_Generic) $this->pagerTitle_box = $obj;
    }

    //Toolbar box
    protected $toolbar_box = "";
    public function GetToolbar()
    {
        return $this->toolbar_box;
    }
    public function SetToolbar($obj = "")
    {
        if ($obj instanceof AA_JSON_Template_Generic) $this->toolbar_box = $obj;
    }

    protected $module = "";
    public function SetModule($id)
    {
        $this->module = $id;
    }
    public function GetModule()
    {
        return $this->module;
    }

    protected $id = "AA_GenericPagedSectionTemplate";

    public function __construct($id = "AA_GenericPagedSectionTemplate", $module = "", $content_box = "")
    {
        $this->module = $module;
        $this->id = $id;
        $this->content_box = $content_box;
        $this->contentBoxTemplate = "<div class='AA_DataView_ItemContent'>"
            . "<div>#pretitolo#</div>"
            . "<div><span class='AA_DataView_ItemTitle'>#denominazione#</span></div>"
            . "<div>#tags#</div>"
            . "<div><span class='AA_DataView_ItemSubTitle'>#sottotitolo#</span></div>"
            . "<div><span class='AA_Label AA_Label_LightBlue' title='Stato elemento'>#stato#</span>&nbsp;<span class='AA_DataView_ItemDetails'>#dettagli#</span></div>"
            . "</div>";
    }

    protected $showDetailSectionFunc="showDetailView";
    public function SetShowDetailSectionFunc($val="")
    {
        $this->showDetailSectionFunc=$val;
    }
    
    protected function Update()
    {
        if (!($this->content_box instanceof AA_JSON_Template_Generic)) {
            $module = "AA_MainApp.getModule('" . $this->module . "')";
            if ($this->module == "") $module = "AA_MainApp.curModule";

            $selectionChangeEvent = "try{AA_MainApp.utils.getEventHandler('onSelectChange','" . $this->module . "','" . $this->id . "_List_Box')}catch(msg){console.error(msg)}";
            $onDblClickEvent = "try{AA_MainApp.utils.getEventHandler('".$this->showDetailSectionFunc."','" . $this->module . "','" . $this->id . "_List_Box')}catch(msg){console.error(msg)}";
            if (sizeof($this->contentBoxData) > 0 && $this->contentBoxTemplate != "") {

                $this->content_box = new AA_JSON_Template_Generic($this->id . "_List_Box", array(
                    "view" => "dataview",
                    "paged" => true,
                    "pager_id" => $this->id . "_Pager",
                    "filtered" => $this->filtered,
                    "filter_id" => $this->saveFilterId,
                    "xCount" => $this->contentItemsForRow,
                    "yCount" => $this->contentItemsForPage,
                    "select" => $this->contentEnableSelect,
                    "multiselect" => $this->contentEnableMultiSelect,
                    "toolbar_id" => $this->id . "_Toolbar",
                    "module_id" => $this->module,
                    "type" => array(
                        "type" => "tiles",
                        "height" => $this->contentItemHeight,
                        "width" => "auto",
                        "css" => "AA_DataView_item"
                    ),
                    "template" => $this->contentBoxTemplate,
                    "data" => $this->contentBoxData,
                    "on" => array("onSelectChange" => $selectionChangeEvent, "onItemDblClick" => $onDblClickEvent)
                ));
            } else {
                $this->content_box = new AA_JSON_Template_Template(
                    $this->id . "_List_Box",
                    array(
                        "template" => "<div style='text-align: center'>#contenuto#</div>",
                        "data" => array("contenuto" => "Non sono presenti elementi."),
                        "is_void" => true
                    )
                );
            }
        }

        if ($this->paged || $this->withPager) {
            if ($this->pagerTarget == "") $this->pagerTarget = $this->content_box->GetId();

            if ($this->pagerItemsCount % $this->pagerItemsForPage) $totPages = intVal($this->pagerItemsCount / $this->pagerItemsForPage) + 1;
            else $totPages = intVal($this->pagerItemsCount / $this->pagerItemsForPage);
            if ($totPages == 0) $totPages = 1;

            $pager = new AA_JSON_Template_Generic($this->id . "_Pager", array(
                "view" => "pager",
                "minWidth" => "400",
                "isFiltered"=>$this->pager_filtered,
                "master" => false,
                "size" => $this->pagerItemsForPage,
                "group" => $this->pagerGroup,
                "count" => $this->pagerItemsCount,
                "title_id" => $this->id . "_Pager_Title",
                "module_id" => $this->module,
                "target" => $this->pagerTarget,
                "targetAction" => $this->pagerTargetAction,
                "template" => "<div style='display: flex; justify-content:flex-start; align-items: center; height:100%' pager='" . $this->id . "_Pager'>{common.first()} {common.prev()} {common.pages()} {common.next()} {common.last()}<div>",
                //"on"=>array("onItemClick"=>"try{module=AA_MainApp.getModule('".$this->module."'); if(module.isValid()) module.pagerEventHandler;}catch(msg){console.error(msg)}")
                "on" => array("onItemClick" => "try{AA_MainApp.utils.getEventHandler('pagerEventHandler','$this->module','" . $this->id . "_Content_Box')}catch(msg){console.error(msg)}")
            ));

            $filtered="";
            if($this->pager_filtered)
            {
                $filtered=" <span class='mdi mdi-filter' title='Contenuto filtrato'></span>";
            }
            $pager_title = new AA_JSON_Template_Generic($this->id . "_Pager_Title", array("view" => "template", "type" => "clean", "minWidth" => "150", "align" => "center", "template" => "<div style='display: flex; justify-content: center; align-items: center; height: 100%; color: #006699;'>Pagina #curPage# di #totPages#".$filtered."</div>", "data" => array("curPage" => ($this->pagerCurPage + 1), "totPages" => $totPages)));
        }

        if ($this->withPager || $this->filtered || $this->saveAsPdfView || $this->saveAsCsvView || $this->trashView || $this->reassignView || $this->publishView || $this->resumeView || $this->detailView) {
            $header_box = new AA_JSON_Template_Layout(
                $this->id . "_Header_box",
                array(
                    "css" => "AA_DataView",
                    "height" => 38
                )
            );

            if ($this->withPager) {
                $header_box->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 10)));
                $header_box->addCol($pager);
                $header_box->AddCol($pager_title);
            } else {
                $header_box->AddCol(new AA_JSON_Template_Generic());
            }

            if ($this->filtered || $this->enableAddNewMulti || $this->enableAddNew || $this->saveAsPdfView || $this->saveAsCsvView || $this->trashView || $this->reassignView || $this->publishView || $this->resumeView || $this->detailView) {
                $toolbar = new AA_JSON_Template_Generic($this->id . "_Toolbar", array(
                    "view" => "toolbar",
                    "type" => "clean",
                    "css" => array("background" => "#ebf0fa", "border-color" => "transparent"),
                    "minWidth" => 500
                ));

                $menu_data = array();

                $toolbar->addElement(new AA_JSON_Template_Generic());

                if ($this->filtered && $this->filterDlgTask != "") {
                    if ($this->saveFilterId != "") $saveFilterId = "'" . $this->saveFilterId . "'";
                    else $saveFilterId = "module.getActiveView()";

                    $filterClickAction = "try{module=AA_MainApp.getModule('" . $this->module . "'); if(module.isValid()){module.dlg({task:'" . $this->filterDlgTask . "',postParams: module.getRuntimeValue(" . $saveFilterId . ",'filter_data'), module: '" . $this->module . "'})}}catch(msg){console.error(msg)}";

                    $filter_btn = new AA_JSON_Template_Generic($this->id . "_Filter_btn", array(
                        "view" => "button",
                        "align" => "right",
                        "type" => "icon",
                        "icon" => "mdi mdi-filter",
                        "label" => "Filtra",
                        "width" => 80,
                        "filter_data" => $this->filterData,
                        "tooltip" => "Imposta un filtro di ricerca",
                        "click" => $filterClickAction
                    ));

                    $toolbar->addElement($filter_btn);
                    $toolbar_spacer = true;
                }

                //Aggiunta elementi
                if ($this->enableAddNew && $this->addNewDlgTask != "") {
                    if ($toolbar_spacer) $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 10)));
                    $toolbar_spacer = true;

                    $addnewClickAction = "try{module=AA_MainApp.getModule('" . $this->module . "'); if(module.isValid()){module.dlg({task:'" . $this->addNewDlgTask . "',module:'" . $this->module . "'})}}catch(msg){console.error(msg)}";

                    $addnew_btn = new AA_JSON_Template_Generic($this->id . "_AddNew_btn", array(
                        "view" => "button",
                        "align" => "right",
                        "type" => "icon",
                        "icon" => "mdi mdi-pencil-plus",
                        "label" => "Aggiungi",
                        "width" => 110,
                        "css"=>"webix_primary",
                        "tooltip" => "Aggiungi una nuova bozza",
                        "click" => $addnewClickAction
                    ));

                    $toolbar->addElement($addnew_btn);
                    $toolbar_spacer = true;
                }

                //Aggiunta elementi da csv
                if ($this->enableAddNewMulti && $this->addNewMultiDlgTask != "") {
                    if ($toolbar_spacer) $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 10)));
                    $toolbar_spacer = true;

                    $addnewMultiClickAction = "try{module=AA_MainApp.getModule('" . $this->module . "'); if(module.isValid()){module.dlg({task:'" . $this->addNewMultiDlgTask . "',module:'" . $this->module . "'})}}catch(msg){console.error(msg)}";

                    $addnewmulti_btn = new AA_JSON_Template_Generic($this->id . "_AddNewMulti_btn", array(
                        "view" => "button",
                        "align" => "right",
                        "type" => "icon",
                        "icon" => "mdi mdi-plus-box-multiple",
                        "label" => "da CSV",
                        "width" => 110,
                        "tooltip" => "Caricamento multiplo da file CSV",
                        "click" => $addnewMultiClickAction
                    ));

                    $toolbar->addElement($addnewmulti_btn);
                    $toolbar_spacer = true;
                }

                if ($this->detailView) {
                    if ($toolbar_spacer) $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 10)));
                    $toolbar_spacer = true;

                    $toolbar->addElement(new AA_JSON_Template_Generic($this->id . "_Detail_btn", array(
                        "view" => "button",
                        "css" => "AA_Detail_btn",
                        "type" => "icon",
                        "icon" => "mdi mdi-text-box-search",
                        "label" => "Dettagli",
                        "enableOnItemSelected" => true,
                        "align" => "right",
                        "width" => 100,
                        "disabled" => true,
                        "tooltip" => "Visualizza i dettagli dell'elemento selezionato",
                        "click" => "AA_MainApp.utils.callHandler('".$this->showDetailSectionFunc."',$$('" . $this->id . "_List_Box').getSelectedItem(),'" . $this->module . "','" . $this->id . "_Content_Box')"
                    )));
                }

                if ($this->reassignView || $this->publishView || $this->resumeView) {
                    $menu_spacer = true;

                    if ($this->publishView) {
                        $this->publishHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_Publish",
                            "value" => "Pubblica",
                            "tooltip" => "Pubblica gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                            "icon" => "mdi mdi-certificate",
                            "module_id" => $this->module,
                            "handler" => $this->publishHandler,
                            "handler_params" => $this->publishHandlerParams

                        );
                    }

                    if ($this->reassignView) {
                        $this->reassignHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_Reassign",
                            "value" => "Riassegna",
                            "tooltip" => "Riassegna gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) ad altra struttura",
                            "icon" => "mdi mdi-share-all",
                            "module_id" => $this->module,
                            "handler" => $this->reassignHandler,
                            "handler_params" => $this->reassignHandlerParams

                        );
                    }

                    if ($this->resumeView) {
                        $this->resumeHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_Resume",
                            "value" => "Ripristina",
                            "tooltip" => "Ripristina gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                            "icon" => "mdi mdi-recycle",
                            "module_id" => $this->module,
                            "handler" => $this->resumeHandler,
                            "handler_params" => $this->resumeHandlerParams
                        );
                    }
                }

                if ($this->saveAsPdfView || $this->saveAsCsvView) {
                    if ($menu_spacer) $menu_data[] = array("\$template" => "Separator");
                    $menu_spacer = true;

                    if ($this->saveAsPdfView) {
                        $this->saveAsPdfHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_SaveAsPdf",
                            "value" => "Esporta in pdf",
                            "tooltip" => "Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file pdf",
                            "icon" => "mdi mdi-file-pdf-box",
                            "module_id" => $this->module,
                            "handler" => $this->saveAsPdfHandler, //"defaultHandlers.saveAsPdf",
                            "handler_params" => $this->saveAsPdfHandlerParams, //array($this->id."_Content_Box",true)
                        );
                    }

                    if ($this->saveAsCsvView) {
                        $this->saveAsCsvHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_SaveAsCsv",
                            "value" => "Esporta in csv",
                            "tooltip" => "Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file csv",
                            "icon" => "mdi mdi-file-table",
                            "module_id" => $this->module,
                            "handler" => $this->saveAsCsvHandler,
                            "handler_params" => $this->saveAsCsvHandlerParams //array($this->id."_Content_Box",true)
                        );
                    }
                }

                if ($this->deleteView || $this->trashView) {
                    if ($menu_spacer) $menu_data[] = array("\$template" => "Separator");
                    $menu_spacer = true;

                    if ($this->trashView) {

                        $this->trashHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_Trash",
                            "value" => "Cestina",
                            "css" => "AA_Menu_Red",
                            "tooltip" => "Cestina gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                            "icon" => "mdi mdi-trash-can",
                            "module_id" => $this->module,
                            "handler" => $this->trashHandler,
                            "handler_params" => $this->trashHandlerParams
                        );
                    }

                    if ($this->deleteView) {
                        $this->deleteHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_Delete",
                            "value" => "Elimina",
                            "css" => "AA_Menu_Red",
                            "tooltip" => "Elimina definitivamente gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                            "icon" => "mdi mdi-trash-can",
                            "module_id" => $this->module,
                            "handler" => $this->deleteHandler,
                            "handler_params" => $this->deleteHandlerParams
                        );
                    }
                }

                if ($toolbar_spacer) $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 10)));
                $toolbar_spacer = true;

                //Azioni
                $scriptAzioni = "try{"
                    . "let azioni_btn=$$('" . $this->id . "_Azioni_btn');"
                    . "if(azioni_btn){"
                    . "let azioni_menu=webix.ui(azioni_btn.config.menu_data);"
                    . "if(azioni_menu){"
                    . "azioni_menu.setContext(azioni_btn);"
                    . "azioni_menu.show(azioni_btn.\$view);"
                    . "}"
                    . "}"
                    . "}catch(msg){console.error('" . $this->id . "_Azioni_btn'.this,msg);AA_MainApp.ui.alert(msg);}";
                $azioni_btn = new AA_JSON_Template_Generic($this->id . "_Azioni_btn", array(
                    "view" => "button",
                    "type" => "icon",
                    "icon" => "mdi mdi-dots-vertical",
                    "label" => "Azioni",
                    "align" => "right",
                    "disabled" => $this->content_box->GetProp("is_void"),
                    "width" => 90,
                    "menu_data" => new AA_JSON_Template_Generic($this->id . "_ActionMenu", array("view" => "contextmenu", "data" => $menu_data, "module_id" => $this->module, "on" => array("onItemClick" => "AA_MainApp.utils.getEventHandler('onMenuItemClick','$this->module')"))),
                    "tooltip" => "Visualizza le azioni disponibili",
                    "click" => $scriptAzioni
                ));

                $toolbar->addElement($azioni_btn);

                $header_box->addCol($toolbar);
                $header_box->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 10)));
            } else {
                $header_box->AddCol(new AA_JSON_Template_Generic());
            }
        }

        $this->content = new AA_JSON_Template_Layout($this->id . "_Content_Box", array(
            "update_time" => Date("Y-m-d H:i:s"),
            "paged" => $this->paged,
            "filtered" => $this->filtered,
            "filter_id" => $this->saveFilterId,
            "list_view_id" => $this->id . "_List_Box",
            "name" => $this->sectionName
        ));

        if ($this->paged || $this->withPager) {
            $this->content->SetProp("pager_id", $this->id . "_Pager");
        }

        if ($header_box) $this->content->AddRow($header_box);
        $this->content->AddRow($this->content_box);
    }

    //Nome della sezione
    protected $sectionName = "Titolo";
    public function SetSectionName($val = "Titolo")
    {
        $this->sectionName = $val;
    }
    public function GetSectionName()
    {
        return $this->sectionName;
    }

    //Gestione paginazione
    protected $paged = false;
    protected $withPager = false;
    protected $pagerCurPage = 0;
    protected $pagerItemsForPage = 10;
    protected $pagerGroup = 5;
    protected $pagerItemsCount = 10;
    protected $pagerTarget = "";
    protected $pagerTargetAction = "refreshData";

    public function EnablePaging($bVal = true)
    {
        $this->paged = $bVal;
    }

    public function EnablePager($bVal = true)
    {
        $this->withPager = $bVal;
    }
    public function DisablePaging()
    {
        $this->paged = false;
    }
    public function IsPaged()
    {
        return $this->paged;
    }
    public function SetPagerCurPage($page = 0)
    {
        $this->pagerCurPage = $page;
    }
    public function SetPagerItemForPage($var = 10)
    {
        $this->pagerItemsForPage = $var;
    }
    public function SetPagerItemCount($var = 10)
    {
        $this->pagerItemsCount = $var;
    }
    public function SetPagerTarget($var = "")
    {
        $this->pagerTarget = $var;
    }
    public function SetPagerTargetAction($var = "")
    {
        $this->pagerTargetAction = $var;
    }

    #-----------------------------------------

    //Gestione filtraggio
    protected $filtered = false;
    public function EnableFiltering()
    {
        $this->filtered = true;
    }
    public function DisableFiltering()
    {
        $this->filtered = false;
    }
    public function IsFiltered()
    {
        return $this->filtered;
    }

    protected $saveFilterId = "";
    public function SetSaveFilterId($id = "")
    {
        $this->saveFilterId = $id;
    }
    public function GetSaveFilterId()
    {
        return $this->saveFilterId;
    }

    protected $filterData = array();
    public function SetFilterData($data = array())
    {
        $this->filterData = $data;
    }
    public function GetFilterData()
    {
        return $this->filterData;
    }

    protected $filterDlgTask = "";
    public function SetFilterDlgTask($var = "")
    {
        $this->filterDlgTask = $var;
    }
    public function GetFilterDlgTask()
    {
        return $this->filterDlgTask;
    }

    //Gestione aggiunta
    protected $enableAddNew = false;
    public function EnableAddNew($bVal = true)
    {
        $this->enableAddNew = $bVal;
    }
    protected $addNewDlgTask = "";
    public function SetAddNewDlgTask($task = "")
    {
        $this->addNewDlgTask = $task;
    }
    #----------------------------

    //Gestione aggiunta multipla
    protected $enableAddNewMulti = false;
    public function EnableAddNewMulti($bVal = true)
    {
        $this->enableAddNewMulti = $bVal;
    }
    
    protected $addNewMultiDlgTask = "";
    public function SetAddNewMultiDlgTask($task = "")
    {
        $this->addNewMultiDlgTask = $task;
    }
    #----------------------------

    //Dettaglio
    protected $detailView = false;
    protected $detailEnable = false;
    public function ViewDetail($bVal = true)
    {
        $this->detailView = $bVal;
        $this->detailEnable = $bVal;
    }
    public function HideDetail()
    {
        $this->detailEnable = false;
        $this->detailView = false;
    }
    public function EnableDetail($bVal = true)
    {
        $this->detailEnable = $bVal;
        $this->detailView = $bVal;
    }
    public function DisableDetail()
    {
        $this->detailEnable = false;
        $this->detailView = false;
    }

    //cestino
    protected $trashView = false;
    protected $trashEnable = false;
    protected $trashHandler = "sectionActionMenu.trash";
    protected $trashHandlerParams = "";
    public function ViewTrash($bVal = true)
    {
        $this->trashView = $bVal;
        $this->trashEnable = $bVal;
    }
    public function HideTrash()
    {
        $this->trashView = false;
        $this->trashEnable = false;
    }
    public function EnableTrash($bVal = true)
    {
        $this->trashEnable = $bVal;
        $this->trashView = $bVal;

    }
    public function DisableTrash()
    {
        $this->trashEnable = false;
        $this->trashView = false;
    }
    public function SetTrashHandler($handler = null, $params = null)
    {
        $this->trashHandler = $handler;
        if ($params) $this->trashHandlerParams = $params;
    }
    public function SetTrashHandlerParams($params = null)
    {
        $this->trashHandlerParams = $params;
    }
    #-----------------------------

    //elimina
    protected $deleteView = false;
    protected $deleteEnable = false;
    public function ViewDelete($bVal = true)
    {
        $this->deleteView = $bVal;
        $this->deleteEnable = $bVal;
    }
    public function HideDelete()
    {
        $this->deleteView = false;
        $this->deleteEnable = false;
    }
    public function EnableDelete($bVal = true)
    {
        $this->deleteEnable = $bVal;
        $this->deleteView = $bVal;
    }
    public function DisableDelete()
    {
        $this->deleteEnable = false;
        $this->deleteView = false;
    }
    protected $deleteHandler = "sectionActionMenu.delete";
    protected $deleteHandlerParams = "";
    public function SetDeleteHandler($handler = null, $params = null)
    {
        $this->deleteHandler = $handler;
        if ($params) $this->deleteHandlerParams = $params;
    }
    public function SetDeleteHandlerParams($params = null)
    {
        $this->deleteHandlerParams = $params;
    }

    //riassegna
    protected $reassignView = false;
    protected $reassignEnable = false;
    public function ViewReassign($bVal = true)
    {
        $this->reassignView = $bVal;
        $this->reassignEnable = $bVal;
    }
    public function HideReassign()
    {
        $this->reassignView = false;
        $this->reassignEnable = false;
    }
    public function EnableReassign($bVal = true)
    {
        $this->reassignEnable = $bVal;
        $this->reassignView = $bVal;
    }
    public function DisableReassign()
    {
        $this->reassignEnable = false;
        $this->reassignView = false;
    }
    protected $reassignHandler = "sectionActionMenu.reassign";
    protected $reassignHandlerParams = "";
    public function SetReassignHandler($handler = null, $params = null)
    {
        $this->reassignHandler = $handler;
        if ($params) $this->reassignHandlerParams = $params;
    }
    public function SetReassignHandlerParams($params = null)
    {
        $this->reassignHandlerParams = $params;
    }
    #------------------------------------------------

    //Ripristina
    protected $resumeView = false;
    protected $resumeEnable = false;
    public function ViewResume($bVal = true)
    {
        $this->resumeView = $bVal;
        $this->resumeEnable = $bVal;
    }
    public function HideResume()
    {
        $this->resumeView = false;
        $this->resumeEnable = false;
    }
    public function EnableResume($bVal = true)
    {
        $this->resumeEnable = $bVal;
        $this->resumeView = $bVal;
    }
    public function DisableResume()
    {
        $this->resumeEnable = false;
        $this->resumeView = false;
    }
    protected $resumeHandler = "sectionActionMenu.resume";
    protected $resumeHandlerParams = "";
    public function SetResumeHandler($handler = null, $params = null)
    {
        $this->resumeHandler = $handler;
        if ($params) $this->resumeHandlerParams = $params;
    }
    public function SetResumeHandlerParams($params = null)
    {
        $this->resumeHandlerParams = $params;
    }
    #---------------------------------------

    //pubblica
    protected $publishEnable = false;
    protected $publishView = false;
    public function ViewPublish($bVal = true)
    {
        $this->publishView = $bVal;
        $this->publishEnable = $bVal;
    }
    public function HidePublish()
    {
        $this->publishView = false;
        $this->publishEnable = false;
    }
    public function EnablePublish($bVal = true)
    {
        $this->publishEnable = $bVal;
        $this->publishView=$bVal;
    }
    public function DisablePublish()
    {
        $this->publishEnable = false;
        $this->publishView=false;

    }
    protected $publishHandler = "sectionActionMenu.publish";
    protected $publishHandlerParams = "";
    public function SetPublishHandler($handler = null, $params = null)
    {
        $this->publishHandler = $handler;
        if ($params) $this->publishHandlerParams = $params;
    }
    public function SetPublishHandlerParams($params = null)
    {
        $this->publishHandlerParams = $params;
    }
    #--------------------------------------------

    //Gestione export
    protected $saveAsPdfEnable = false;
    protected $saveAsPdfView = false;
    public function ViewSaveAsPdf($bVal = true)
    {
        $this->saveAsPdfView = $bVal;
    }
    public function HideSaveAsPdf()
    {
        $this->saveAsPdfView = false;
    }
    public function EnableSaveAsPdf($bVal = true)
    {
        $this->saveAsPdfEnable = $bVal;
    }
    public function DisableSaveAsPdf()
    {
        $this->saveAsPdfEnable = false;
    }

    protected $saveAsCsvEnable = false;
    protected $saveAsCsvView = false;
    public function ViewSaveAsCsv($bVal = true)
    {
        $this->saveAsCsvView = $bVal;
    }
    public function HideSaveAsCsv()
    {
        $this->ViewSaveAsCsv(false);
    }
    public function EnableSaveAsCsv($bVal = true)
    {
        $this->saveAsCsvEnable = $bVal;
    }
    public function DisableSaveAsCsv()
    {
        $this->EnableSaveAsCsv(false);
    }
    public function ViewExportFunctions($bVal = true)
    {
        $this->saveAsCsvView = $bVal;
        $this->saveAsPdfView = $bVal;
    }
    public function HideExportFunctions()
    {
        $this->ViewExportFunctions(false);
    }
    public function EnableExportFunctions($bVal = true)
    {
        $this->saveAsCsvEnable = $bVal;
        $this->saveAsPdfEnable = $bVal;
        $this->saveAsCsvView = $bVal;
        $this->saveAsPdfView = $bVal;
    }
    public function DisableExportFunctions()
    {
        $this->EnableExportFunctions(false);
    }
    protected $saveAsPdfHandler = "sectionActionMenu.saveAsPdf";
    protected $saveAsPdfHandlerParams = array();
    public function SetSaveAsPdfHandler($handler = null, $params = null)
    {
        $this->saveAsPdfHandler = $handler;
        if ($params) $this->saveAsPdfHandlerParams = $params;
    }
    public function SetSaveAsPdfHandlerParams($params = null)
    {
        $this->saveAsPdfHandlerParams = $params;
    }
    protected $saveAsCsvHandler = "sectionActionMenu.saveAsCsv";
    protected $saveAsCsvHandlerParams = array();
    public function SetSaveAsCsvHandler($handler = null, $params = null)
    {
        $this->saveAsCsvHandler = $handler;
        if ($params) $this->saveAsCsvHandlerParams = $params;
    }
    public function SetSaveAsCsvHandlerParams($params = null)
    {
        $this->saveAsCsvHandlerParams = $params;
    }
    #-------------------------------
}
