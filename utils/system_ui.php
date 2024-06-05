<?php
//Template generico JSON webix
class AA_JSON_Template_Generic
{
    //Restituisce la reppresentazione dell'oggetto come una una stringa
    public function __toString()
    {
        //Restituisce l'oggetto come stringa
        return json_encode($this->toArray());
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function toBase64()
    {
        return base64_encode($this->__toString());
    }

    public function toArray()
    {
        $result = array();

        //Gestori eventi
        if(sizeof($this->aEventHandlers)>0)
        {
            $result["on"]=array();
            $result['eventHandlers']=$this->aEventHandlers;
            foreach($this->aEventHandlers as $event=>$curHandler)
            {
                $result["on"][$event]="AA_MainApp.utils.getEventHandler('".$curHandler['handler']."','".$curHandler['module_id']."')";
            }
        }

        //Infopopup
        if((isset($this->props['label']) || isset($this->props['bottomLabel']))&& isset($this->props['infoPopup']) && is_array($this->props['infoPopup']))
        {
            $script="AA_MainApp.utils.callHandler(\"dlg\", {task:\"infoPopup\", params: [{id: \"".$this->props['infoPopup']['id']."\"}]},\"".$this->props['infoPopup']['id_module']."\")";
            if(isset($this->props['bottomLabel'])) $this->props['bottomLabel'].="<a href='#' onclick='".$script."'><span class='mdi mdi-help-circle'></span></a>";
            else $this->props['label'].="&nbsp;<a href='#' onclick='".$script."' title='fai click per ricevere ulteriori informazioni.'><span class='mdi mdi-help-circle'></span></a>";
        }

        //Proprietà
        foreach ($this->props as $key => $prop) {
            if ($prop instanceof AA_JSON_Template_Generic) $result[$key] = $prop->toArray();
            else $result[$key] = $prop;
        }

        //rows
        if (is_array($this->rows)) {
            $result['rows'] = array();
            foreach ($this->rows as $curRow) {
                $result['rows'][] = $curRow->toArray();
            }
        }

        //cols
        if (is_array($this->cols)) {
            $result['cols'] = array();
            foreach ($this->cols as $curCol) {
                $result['cols'][] = $curCol->toArray();
            }
        }
        if (isset($result['view']) && $result['view'] == "layout" && isset($result['rows']) && !is_array($result['rows']) && isset($result['cols']) && !is_array($result['cols'])) $result['rows'] = array(array("view" => "spacer"));

        //cells
        if (is_array($this->cells)) {
            $result['cells'] = array();
            foreach ($this->cells as $curCell) {
                $result['cells'][] = $curCell->toArray();
            }
        }
        if (isset ($result['view']) && $result['view'] == "multiview" && isset($result['cells']) && !is_array($result['cells'])) $result['cells'] = array(array("view" => "spacer"));

        //elements
        if (is_array($this->elements)) {
            $result['elements'] = array();
            foreach ($this->elements as $curCell) {
                $result['elements'][] = $curCell->toArray();
            }
        }
        if (isset($result['view']) && $result['view'] == "toolbar" && isset($result['elements']) && !is_array($result['elements'])) $result['elements'] = array(array("view" => "spacer"));

        //bodyRows
        if (is_array($this->bodyRows) || is_array($this->bodyCols)) {
            $result['body'] = array();
            if (is_array($this->bodyRows)) {
                foreach ($this->bodyRows as $curBodyRow) {
                    if (!is_array($result['body']['rows'])) $result['body']['rows'] = array();
                    $result['body']['rows'][] = $curBodyRow->toArray();
                }
            }

            if (is_array($this->bodyCols)) {
                foreach ($this->bodyCols as $curBodyCol) {
                    if (!is_array($result['body']['cols'])) $result['body']['cols'] = array();
                    $result['body']['cols'][] = $curBodyCol->toArray();
                }
            }
        }

        //Restituisce l'oggetto come array
        return $result;
    }

    protected $props = array();
    public function SetProp($prop = "", $value = "")
    {
        $this->props[$prop] = $value;
    }
    public function GetProp($prop)
    {
        if(isset($this->props[$prop])) return $this->props[$prop];
        else return "";
    }

    //Aggiunta righe
    protected $rows = null;
    public function addRow($row = null)
    {
        if ($row instanceof AA_JSON_Template_Generic) {
            //AA_Log::Log(__METHOD__." ".$row->toString(),100);

            if (!is_array($this->rows)) $this->rows = array();
            $this->rows[] = $row;
        }
    }

    //Aggiunta row al body
    protected $bodyRows = null;
    public function addRowToBody($row = null)
    {
        if ($row instanceof AA_JSON_Template_Generic) {
            //AA_Log::Log(__METHOD__." ".$row->toString(),100);

            if (!is_array($this->bodyRows)) $this->bodyRows = array();
            $this->bodyRows[] = $row;
        }
    }

    //Aggiunta col al body
    protected $bodyCols = null;
    public function addColToBody($col = null)
    {
        if ($col instanceof AA_JSON_Template_Generic) {
            //AA_Log::Log(__METHOD__." ".$row->toString(),100);

            if (!is_array($this->bodyCols)) $this->bodyCols = array();
            $this->bodyCols[] = $col;
        }
    }

    //Aggiunta colonne
    protected $cols = null;
    public function addCol($col = null)
    {
        if ($col instanceof AA_JSON_Template_Generic) {
            if (!is_array($this->cols)) $this->cols = array();
            $this->cols[] = $col;
        }
    }

    //gestori degli eventi
    protected $aEventHandlers=array();
    public function AddEventHandler($event="",$handler="",$handlerParams=null,$module_id="")
    {
        try
        {
            if($event !="" && $handler!="") $this->aEventHandlers[$event]=array("handler"=>$handler,"params"=>$handlerParams,"module_id"=>$module_id);
        }
        catch(Exception $e)
        {
            AA_Log::Log(__METHOD__." - ".$e->getMessage(),100);
        }
    }
    public function DelEventHandler($event="")
    {
        if($event !="" && isset($aEventHandlers[$event])) unset($aEventHandlers[$event]);
    }

    //Aggiunta celle
    protected $cells = null;
    public function addCell($cell = null, $bFromHead = false)
    {
        if ($cell instanceof AA_JSON_Template_Generic) {
            if (!is_array($this->cells)) $this->cells = array();
            if (!$bFromHead) $this->cells[] = $cell;
            else array_unshift($this->cells, $cell);
        }
    }

    //Aggiunta elementi
    protected $elements = null;
    public function addElement($obj = null)
    {
        if ($obj instanceof AA_JSON_Template_Generic) {
            if (!is_array($this->elements)) $this->elements = array();
            $this->elements[] = $obj;
        }
    }
    public function __construct($id = "", $props = null)
    {
        if ($id != "") $this->props["id"] = $id;
        else $this->props["id"]="AA_JSON_TEMPLATE_GENERIC_".uniqid(time());
        
        if (is_array($props)) {
            foreach ($props as $key => $value) {
                if($key != "eventHandlers") $this->props[$key] = $value;
            }

            if(isset($props['fixedRowHeight']) && !$props['fixedRowHeight'])
            {
                if(!isset($props['eventHandlers']))
                {
                    $props['eventHandlers']=array("onresize"=>array("handler"=>"adjustRowHeight","module_id"=>""));
                }
                else
                {
                    if(!isset($props['eventHandlers']['onresize']))
                    {
                        $props['eventHandlers']['onresize']=array("handler"=>"adjustRowHeight","module_id"=>"");
                    }
                }
            }
        }

        if(isset($props['eventHandlers']) && is_array($props['eventHandlers']))
        {
            $this->aEventHandlers=$props['eventHandlers'];
        }
    }

    public function GetId()
    {
        return $this->props['id'];
    }
}

//Classe per la gestione delle multiviste
class AA_JSON_Template_Multiview extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "multiview";
        if ($id == "") $id = "AA_JSON_TEMPLATE_MULTIVIEW".uniqid(time());

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei layout
class AA_JSON_Template_Layout extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "layout";
        if ($id == "") $id = "AA_JSON_TEMPLATE_LAYOUT".uniqid(time());

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei dataview
class AA_UI_Generic_Dataview extends AA_JSON_Template_Layout
{
    //impostazione del template
    protected $template="<div>#data#</div>";
    public function GetTemplate()
    {
        return $this->template;
    }
    public function SetTemplate($var="")
    {
        $this->template=$var;
    }
    //------------------------------------

    //------------data-------------------
    protected $aData=array(array("id"=>1,"data"=>"data"));
    public function SetData($var=array())
    {
        if(is_array($var) && sizeof($var) > 0) $this->aData=$var;
    }
    public function GetData()
    {
        $this->aData;
    }
    //------------------------------------

    //------------paging---------------------
    protected $bPaging=false;
    public function EnablePaging($val=true)
    {
        if($val) $this->bPaging=true;
        else $this->bPaging=false;
    }
    //---------------------------------------


    //------------filtering------------------
    protected $bFilter=false;
    public function EnableFiltering($val=true)
    {
        if($val) $this->bFilter=true;
        else $this->bFilter=false;
    }
    //---------------------------------------

    //columns
    protected $nColumns=0; //auto
    public function SetColumnNum($var=0)
    {
        if(is_int($var) && $var >=0) $this->nColumns=$var;
    }
    public function GetColumnNum()
    {
        return $this->nColumns;
    }
    //---------------------------------

    //rows
    protected $nRows=0; //auto
    public function SetRowsNum($var=0)
    {
        if(is_int($var) && $var >=0) $this->nRows=$var;
    }
    public function GetRowsNum()
    {
        return $this->nRows;
    }
    //----------------------------------

    //itemWidth
    protected $nItemWidth=0;
    public function SetItemWidth($val=0)
    {
        if(is_int($val) && $val >=0) $this->nItemWidth=$val;
    }
    public function GetItemWidth()
    {
        return $this->nItemWidth;
    }
    //-------------------------------------

    //itemWidth
    protected $nItemHeight=0;
    public function SetItemHeight($val=0)
    {
        if(is_int($val) && $val >=0) $this->nItemHeight=$val;
    }
    public function GetItemHeight()
    {
        return $this->nItemHeight;
    }
    //-------------------------------------
    
    //dataview css
    protected $dataviewCss="";
    public function SetDataviewCss($val="")
    {
        $this->dataviewCss=$val;
    }
    public function GetDataviewCss()
    {
        return $this->dataviewCss;
    }
    //-------------------------------------

    //---------------item css------------------------
    protected $itemCss="";
    public function SetItemCss($val="")
    {
        $this->itemCss=$val;
    }
    public function GetItemCss()
    {
        return $this->itemCss;
    }
    //------------------------------------------------

    //---------------item padding------------------------
    protected $itemPadding="";
    public function SetItemPadding($val="")
    {
        $this->itemPadding=$val;
    }
    public function GetItemPadding()
    {
        return $this->itemPadding;
    }
    //------------------------------------------------

    //------------------dataview component------------
    protected $oDataview=null;
    public function GetDataview()
    {
        return $this->oDataview;
    }
    public function SetDataview($var)
    {
        if($var instanceof AA_JSON_Template_Generic && $var->props['view']=="dataview") $this->oDataview=$var;
    }

    //---------------------pager component---------------
    protected $pager=null;
    public function GetPager()
    {
        return $this->pager;
    }
    public function SetPager($var)
    {
        if($var instanceof AA_JSON_Template_Generic && $var->props['view']=="pager") $this->pager=$var;
    }
    protected $pagerPosition=1; //1=top, 2=bottom
    public function SetPagerPosition($pos=1)
    {
        if(is_int($pos) && $pos >0 && $pos <=2) $this->pagerPosition=$pos;
    }
    public function GetPagerPosition()
    {
        return $this->pagerPosition;
    }
    protected $pagerSize=5;
    public function SetPagerSize($val=10)
    {
        if(is_int($val) && $val > 0) $this->pagerSize=$val;
    }
    public function GetPagerSize()
    {
        return $this->pagerSize;
    }

    protected $pagerGroup=5;
    public function SetPagerGroup($val=5)
    {
        if(is_int($val) && $val > 0) $this->pagerGroup=$val;
    }
    public function GetPagerGroup()
    {
        return $this->pagerGroup;
    }

    protected $pagerPage=0;
    public function SetPagerPage($val=0)
    {
        if(is_int($val) && $val >= 0) $this->pagerPage=$val;
    }
    public function GetPagerPage()
    {
        return $this->pagerPage;
    }

    protected $pagerTemplate="<span>{common.first()}{common.pages()}{common.last()} pag. {common.page()} di #limit#</span>";
    public function SetPagerTemplate($val="<span>{common.first()}{common.pages()}{common.last()}</span>")
    {
        $this->pagerTemplate=$val;
    }
    public function GetPagerTemplate()
    {
        return $this->pagerTemplate;
    }

    protected $pagerCss="";
    public function SetPagerCss($val="")
    {
        $this->pagerCss=$val;
    }
    //----------------------------------------------------

    //----------------filter component--------------------
    protected $filter=null;
    public function GetFilter()
    {
        return $this->filter;
    }
    public function SetFilter($var)
    {
        if($var instanceof AA_JSON_Template_Generic && $var->props['view']=="search") $this->filter=$var;
    }
    protected $sFilterFunct="";
    public function SetFilterFunction($funct="")
    {
        $this->sFilterFunct=$funct;
    }
    protected $sFilterField=""; 
    public function SetFilterField($val="")
    {
        $this->sFilterField=$val;
    }
    protected $filterCss=""; 
    public function SetFilterCss($val="")
    {
        $this->filterCss=$val;
    }
    //---------------------------------------------------------

    public function __construct($id = "", $props = null)
    {
        if(!isset($props['type'])) $props['type']="clean";
        if(!isset($props['borderless'])) $props['borderless']=true;

        parent::__construct($id, $props);
    }

    public function toArray()
    {
        if($this->oDataview == null)
        {
            $this->oDataview=new AA_JSON_Template_Generic($this->GetId()."_Dataview",array("view"=>"dataview","borderless"=>true));

            if(is_string($this->template)) $this->oDataview->SetProp('template',$this->template);
            if($this->template instanceof AA_XML_Element_Generic) $this->oDataview->SetProp('template',$this->template->__toString());
    
            $this->oDataview->SetProp('data',$this->aData);

            if($this->dataviewCss !="")
            {
                $this->oDataview->SetProp('css',$this->dataviewCss);
            }
    
            $type=array();
            if($this->nItemWidth > 0) $type["width"]=$this->nItemWidth;
            else $type["width"]="auto";
            if($this->nItemHeight > 0) $type["height"]=$this->nItemHeight;
            else $type["height"]="auto";
            if($this->itemPadding !="") $type["padding"]=$this->itemPadding;
            if($this->itemCss !="") $type["css"]=$this->itemCss;
            $this->oDataview->SetProp('type',$type);
    
            if($this->nColumns > 0) $this->oDataview->SetProp('xCount',$this->nColumns);
            if($this->nRows > 0) $this->oDataview->SetProp('yCount',$this->nRows);

            $this->oDataview->SetProp('scroll',"y");
        }

        //dataview non inizialized
        if(!$this->oDataview instanceof AA_JSON_Template_Generic)
        {
            $this->addRow(new AA_JSON_Template_Template("",array("template"=>"Dataview non inizializzato.")));
            return parent::toArray();
        }

        if($this->oDataview->GetProp("view") !="dataview")
        {
            $this->addRow(new AA_JSON_Template_Template("",array("template"=>"Dataview non inizializzato.")));
            return parent::toArray();
        }

        //pager
        if($this->bPaging && $this->pager==null)
        {
            $this->pager=new AA_JSON_Template_Generic($this->GetId()."_Pager",array("view"=>"pager","size"=>$this->pagerSize,"group"=>$this->pagerGroup,"page"=>$this->pagerPage,"template"=>$this->pagerTemplate));
            if($this->pagerCss !="")
            {
                $this->pager->SetProp("css",$this->pagerCss);
            }
            //AA_Log::Log(__METHOD__." pager: ".$this->pager->__toString(),100);
            $this->oDataview->SetProp("pager",$this->GetId()."_Pager");
        }

        if($this->filter==null && $this->bFilter && $this->sFilterFunct !="")
        {
            $this->filter=new AA_JSON_Template_Search($this->GetId()."_Filter",array("filterFunction"=>$this->sFilterFunct,"clear"=>true));
            if($this->filterCss !="") $this->filter->setProp("css",$this->filterCss);
            $this->filter->setProp("filterTarget",$this->oDataview->getId());
            $this->filter->setProp("filterField",$this->sFilterField);
        }

        //insert filter
        if($this->bFilter && $this->filter instanceof AA_JSON_Template_Generic)
        {
            if($this->bPaging && $this->pagerPosition==1 && $this->pager instanceof AA_JSON_Template_Generic)
            {
                $rowfiltering=new AA_JSON_Template_Layout("",array("type"=>"clean"));
                $rowfiltering->AddCol($this->pager);
                $rowfiltering->AddCol($this->filter);
                $this->addRow($rowfiltering);
            }
            else
            {
                $this->addRow($this->filter);
            }
        }
        else
        {
            if($this->bPaging && $this->pagerPosition==1 && $this->pager instanceof AA_JSON_Template_Generic) $this->addRow($this->pager);
        }

        $this->AddRow($this->oDataview);

        if($this->bPaging && $this->pagerPosition==2 && $this->pager instanceof AA_JSON_Template_Generic) $this->addRow($this->pager);

        //AA_Log::Log(__METHOD__." dataview: ".$this->toString(),100);

        return parent::toArray();
    }
}

//Classe per la gestione dell'upload dei file nei form
class AA_JSON_Template_Fileupload extends AA_JSON_Template_Layout
{
    public function __construct($id = "", $props = null, $sessionFileName="AA_SessionFileUploader")
    {
        if ($id == "") $id = "AA_JSON_TEMPLATE_FILEUPLOAD".uniqid(time());
        $props['name'] = "AA_FileUploader";

        if (!isset($props['value'])) $props['value'] = "Sfoglia...";
        $props['autosend'] = false;
        if ($props['multiple'] == "") $props['multiple'] = false;
        $props['view'] = "uploader";
        $props['link'] = $id . "_FileUpload_List";
        $props['layout_id'] = $id . "_FileUpload_Layout";
        $props['formData'] = array("file_id" => $sessionFileName);

        parent::__construct($id . "_FileUpload_Layout", array("type" => "clean", "borderless" => true,"autoheight"=>true));

        $this->AddRow(new AA_JSON_Template_Generic($id . "_FileUpload_Field", $props));
        $this->AddRow(new AA_JSON_Template_Generic($id . "_FileUpload_List", array(
            "view" => "list",
            "scroll" => false,
            "autoheight"=>true,
            "minHeight"=>32,
            "type" => "uploader",
            "css" => array("background" => "transparent")
        )));

        if ($props['bottomLabel']) {
            $this->AddRow(new AA_JSON_Template_Template($id . "_FileUpload_BottomLabel", array(
                "autoheight"=>true,
                "template" => "<span style='font-size: smaller; font-style:italic'>" . $props['bottomLabel'] . "</span>",
                "css" => array("background" => "transparent")
            )));
        }
    }
}

//Classe per la gestione dei caroselli
class AA_JSON_Template_Carousel extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["navigation"]=array();
        $this->props["view"] = "carousel";
        $this->props["navigation"]["type"] = "side";
        $this->props["navigation"]["items"]=true;
        $this->props["scrollSpeed"]="800ms";

        if ($id == "") $id = "AA_JSON_TEMPLATE_CAROUSEL".uniqid(time());

        parent::__construct($id, $props);
    }

    protected $slides=array();
    public function AddSlide($slide=null)
    {
        if($slide instanceof AA_JSON_Template_Generic)
        {
            $this->slides[]=$slide;
        }
    }

    protected $autoScroll=false;
    protected $autoScrollSlideTime=5000;
    public function EnableAutoScroll($bVal=true)
    {
        $this->autoScroll=$bVal;
    }
    public function SetAutoScrollSlideTime($val=5000)
    {
        if($val > 1000)
        {
            $this->autoScrollSlideTime=$val;
        }
    }

    public function ShowNavigationButtons($bVal=true)
    {
        $this->props['navigation']['buttons']=$bVal;
    }

    public function SetScrollSpeed($speed=500)
    {
        if($speed > 0) $this->props["scrollSpeed"]=$speed."ms";
    }

    public function GetSlides()
    {
        return $this->slides;
    }

    public function SetTipe($newType="side")
    {
        $this->props["navigation"]["type"]=$newType;
    }

    public function ShowItems($show=true)
    {
        $this->props["navigation"]["items"]=$show;
    }

    public function toArray()
    {
        foreach ($this->slides as $curSlide)
        {
            $this->AddCol($curSlide);
        }

        $this->props['autoScroll']=$this->autoScroll;
        $this->props['autoScrollSlideTime']=$this->autoScrollSlideTime;
        $this->props['slidesCount']=sizeof($this->slides);

        return parent::toArray();
    }
}

//Classe per la gestione delle toolbar
class AA_JSON_Template_Toolbar extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "toolbar";
        if ($id == "") $id = "AA_JSON_TEMPLATE_TOOLBAR".uniqid(time());

        parent::__construct($id, $props);
    }
}

//Classe per la gestione delle tree view
class AA_JSON_Template_Search extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "search";
        if ($id == "") $id = "AA_JSON_TEMPLATE_SEARCH".uniqid(time());

        parent::__construct($id, $props);
    }
}

//Classe per la gestione delle date
class AA_JSON_Template_Datepicker extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "datepicker";
        if ($id == "") $id = "AA_JSON_TEMPLATE_DATEPICKER".uniqid(time());
        if(!isset($props['clear'])) $props['clear']=true;

        parent::__construct($id, $props);
    }
}

//Classe per la gestione delle tree view
class AA_JSON_Template_Tree extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "tree";
        if ($id == "") $id = "AA_JSON_TEMPLATE_TREE".uniqid(time());

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei template
class AA_JSON_Template_Template extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "template";
        if ($id == "") $id = "AA_JSON_TEMPLATE_TEMPLATE".uniqid(time());

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei checkbox
class AA_JSON_Template_Checkbox extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "checkbox";
        if ($id == "") $id = "AA_JSON_TEMPLATE_CHECKBOX".uniqid(time());

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei switch
class AA_JSON_Template_Switch extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "switch";
        if ($id == "") $id = "AA_JSON_TEMPLATE_SWITCH".uniqid(time());

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei campi di testo
class AA_JSON_Template_Text extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "text";
        if ($id == "") $id = "AA_JSON_TEMPLATE_TEMPLATE".uniqid(time());
        if(!isset($props['clear'])) $props['clear']=true;

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei campi di testo
class AA_JSON_Template_Richtext extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "richtext";
        if ($id == "") $id = "AA_JSON_TEMPLATE_RICHTEXT_".uniqid(time());
        if(!isset($props['clear'])) $props['clear']=true;

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei campi di testo
class AA_JSON_Template_Ckeditor5 extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "ckeditor5";
        if ($id == "") $id = "AA_JSON_TEMPLATE_CKEDITOR5_".uniqid(time());

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei campi di testo
class AA_JSON_Template_Select extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "richselect";
        if ($id == "") $id = "AA_JSON_TEMPLATE_RICHSELECT".uniqid(time());

        parent::__construct($id, $props);
    }
}



//Classe per la gestione dei campi radio
class AA_JSON_Template_Radio extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "radio";
        if ($id == "") $id = "AA_JSON_TEMPLATE_RADIO".uniqid(time());

        parent::__construct($id, $props);
    }
}

//Classe per la gestione del textarea
class AA_JSON_Template_Textarea extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "textarea";
        if ($id == "") $id = "AA_JSON_TEMPLATE_TEMPLATE".uniqid(time());

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei form
class AA_JSON_Template_Form extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "form";
        if ($id == "") $id = "AA_JSON_TEMPLATE_FORM".uniqid(time());

        parent::__construct($id, $props);
    }
}

//Classe per la gestione del layout delle finestre
class AA_GenericWindowTemplate
{
    protected $id = "AA_TemplateGenericWnd";
    public function SetId($id = "")
    {
        if ($id != "") $this->id = $id;
    }

    public function GetId()
    {
        return $this->id;
    }

    public function GetWndId()
    {
        return $this->id."_Wnd";
    }

    protected $body = "";
    protected $head = "";
    protected $wnd = "";

    protected $modal = true;
    public function EnableModal()
    {
        $this->modal = true;
    }
    public function DisableModal()
    {
        $this->modal = false;
    }

    protected $module = "";
    public function SetModule($idModule)
    {
        $this->module = $idModule;
    }
    public function GetModule()
    {
        return $this->module;
    }

    private $title = "finestra di dialogo";
    public function __construct($id = "", $title = "", $module = "",$bodyProps=null)
    {
        if ($id != "") $this->id = $id;
        if ($title != "") $this->title = $title;

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->module = $module;

        $script = 'try{if($$(\'' . $this->id . '_Wnd\').config.fullscreen){webix.fullscreen.exit();$$(\'' . $this->id . '_btn_resize\').define({icon:"mdi mdi-fullscreen", tooltip:"Mostra la finestra a schermo intero"});$$(\'' . $this->id . '_btn_resize\').refresh();}else{webix.fullscreen.set($$(\'' . $this->id . '_Wnd\'));$$(\'' . $this->id . '_btn_resize\').define({icon:"mdi mdi-fullscreen-exit", tooltip:"Torna alla visualizzazione normale"});$$(\'' . $this->id . '_btn_resize\').refresh();}}catch(msg){console.error(msg);}';

        if(!is_array($bodyProps)) $bodyProps=array("type" => "clean");
        if(!isset($bodyProps['type']))$bodyProps['type']="clean";
        
        $this->body = new AA_JSON_Template_Layout($this->id . "_Content_Box", $bodyProps);
        $this->head = new AA_JSON_Template_Generic($this->id . "_head", array("css" => "AA_Wnd_header_box", "view" => "toolbar", "height" => "38", "elements" => array(
            array("id" => $this->id . "_Title", "css" => "AA_Wnd_title", "template" => $this->title),
            array("id" => $this->id . "_btn_resize", "view" => "icon", "icon" => "mdi mdi-fullscreen", "css" => "AA_Wnd_btn_fullscreen", "width" => 24, "height" => 24, "tooltip" => "Mostra la finestra a schermo intero", "click" => $script),
            array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "hotkey"=>"esc","css" => "AA_Wnd_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi la finestra", "click" => "try{if($$('" . $this->id . "_Wnd').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Wnd').close();}catch(msg){console.error(msg)}")
        )));

        $this->wnd = new AA_JSON_Template_Generic($this->id . "_Wnd", array(
            "view" => "window",
            "height" => $this->height,
            "width" => $this->width,
            "position" => "center",
            "modal" => $this->modal,
            "move" => true,
            "resize" => true,
            "css" => "AA_Wnd"
        ));

        $this->wnd->SetProp("head", $this->head);
        $this->wnd->SetProp("body", $this->body);
    }

    protected function Update()
    {
        $this->wnd->setProp("height", $this->height);
        $this->wnd->setProp("width", $this->width);
        $this->wnd->setProp("modal", $this->modal);
    }

    protected $width = "1280";
    public function SetWidth($width = "1280")
    {
        if ($width > 0) $this->width = $width;
    }
    public function GetWidth()
    {
        return $this->width;
    }

    protected $height = "720";
    public function SetHeight($height = "720")
    {
        if ($height > 0) $this->height = $height;
    }
    public function GetHeight()
    {
        return $this->height;
    }

    //Gestione del contenuto
    public function AddView($view)
    {
        if (is_array($view) && $view['id'] != "") {
            $this->body->AddRow(new AA_JSON_Template_Generic($view['id'], $view));
        }

        if ($view instanceof AA_JSON_Template_Generic) $this->body->AddRow($view);
    }

    public function __toString()
    {
        $this->Update();
        return json_encode($this->wnd->toArray());
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function GetObject()
    {
        $this->Update();
        return $this->wnd;
    }

    public function toBase64()
    {
        $this->Update();

        return $this->wnd->toBase64();
    }
}

//Classe per la gestione del layout dei popup
class AA_GenericPopupTemplate
{
    protected $id = "AA_TemplateGenericPopup";
    public function SetId($id = "")
    {
        if ($id != "") $this->id = $id;
    }

    public function GetId()
    {
        return $this->id;
    }

    public function GetPopupId()
    {
        return $this->id."_Popup";
    }

    protected $body = "";
    public function GetContent()
    {
        return $this->body;
    }

    protected $head = "";
    protected $wnd = "";
    
    protected $title="";
    public function SetTitle($val="")
    {
        $this->title=$val;
    }
    public function GetTitle()
    {
        return $this->title;
    }

    //content css
    protected $css = "";
    public function SetCss($val="")
    {
        $this->css=$val;
    }

    protected $bClose=true;
    public function Enableclose($val=true)
    {
        if($val) $this->bClose=true;
        else $this->bClose=false;
    }

    protected $closePosition=2; //0 top-left, 1 top-center, 2: top-right (default), 3: bottom-left, 4: bottom-center, 5 bottom-right
    public function SetClosePosition($val=2)
    {
        if(intval($val) >= 0 && intval($val) <= 5)
        {
            $this->closePosition=$val;
        }
    }

    protected $modal = true;
    public function EnableModal()
    {
        $this->modal = true;
    }
    public function DisableModal()
    {
        $this->modal = false;
    }

    protected $module = "";
    public function SetModule($idModule)
    {
        $this->module = $idModule;
    }
    public function GetModule()
    {
        return $this->module;
    }

    public function __construct($id = "", $module = "")
    {
        if ($id != "") $this->id = $id;

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->module = $module;

        $this->body = new AA_JSON_Template_Layout($this->id . "_Content_Box", array("type" => "clean", "padding"=>5));

        $this->wnd = new AA_JSON_Template_Generic($this->id . "_Popup", array(
            "view" => "popup",
            "height" => $this->height,
            "width" => $this->width,
            "modal"=>$this->modal,
            "position" => "center",
            "css" => "AA_Popup"
        ));
    }

    protected function Update()
    {
        $this->wnd->setProp("height", $this->height);
        $this->wnd->setProp("width", $this->width);
        $this->wnd->setProp("modal", $this->modal);

        if($this->bClose && $this->closePosition <3)
        {
            if($this->closePosition == 0)
            {

                if($this->title !="") 
                {
                    $this->head = new AA_JSON_Template_Generic($this->id . "_head", array("css" => "AA_Popup_header_box", "type"=>"clean", "view" => "toolbar", "height" => "38", "elements" => array(
                    array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                    array("view"=>"label","label"=>$this->title,"align"=>"center"),
                    )));
                }
                else
                {
                    $this->head = new AA_JSON_Template_Generic($this->id . "_head", array("css" => "AA_Popup_header_box", "type"=>"clean", "view" => "toolbar", "height" => "38", "elements" => array(
                        array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                        array("view"=>"spacer"),
                        )));    
                }
            }
            if($this->closePosition == 1)
            {
                if($this->title !="") $this->body->addRow(new AA_JSON_Template_Generic($this->id."_Title",array("view"=>"label","label"=>$this->title,"align"=>"center")));
                $this->head = new AA_JSON_Template_Generic($this->id . "_head", array("css" => "AA_Popup_header_box", "type"=>"clean", "view" => "toolbar", "height" => "38", "elements" => array(
                    array("view" => "spacer"),
                    array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                    array("view" => "spacer")
                )));
            }
            if($this->closePosition == 2)
            {   
                if($this->title !="") 
                {
                    $this->head = new AA_JSON_Template_Generic($this->id . "_head", array("css" => "AA_Popup_header_box", "type"=>"clean", "view" => "toolbar", "height" => "38", "elements" => array(
                        array("view"=>"label","label"=>$this->title,"align"=>"center"),
                        array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                    )));
                }
                else
                {
                    $this->head = new AA_JSON_Template_Generic($this->id . "_head", array("css" => "AA_Popup_header_box", "type"=>"clean", "view" => "toolbar", "height" => "38", "elements" => array(
                        array("view"=>"spacer"),
                        array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "hotkey"=>"esc","css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                        )));    
                }
            }

            if($this->head instanceof AA_JSON_Template_Generic) $this->body->addRow($this->head);
        }
        else
        {
            if($this->title !="") $this->body->addRow(new AA_JSON_Template_Generic($this->id."_Title",array("view"=>"label","label"=>$this->title,"align"=>"center")));
        }

        //inserisce i figli
        foreach($this->children as $curChild)
        {
            $this->body->addRow($curChild);
        }

        if($this->bClose && $this->closePosition >=3)
        {
            if($this->closePosition == 3)
            {
                $this->head = new AA_JSON_Template_Generic($this->id . "_head", array("css" => "AA_Popup_header_box", "type"=>"clean", "view" => "toolbar", "height" => "38", "elements" => array(
                    array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                    array("view" => "spacer")
                )));
            }
            if($this->closePosition == 4)
            {
                $this->head = new AA_JSON_Template_Generic($this->id . "_head", array("css" => "AA_Popup_header_box", "type"=>"clean", "view" => "toolbar", "height" => "38", "elements" => array(
                    array("view" => "spacer"),
                    array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                    array("view" => "spacer")
                )));
            }
            if($this->closePosition == 5)
            {
                $this->head = new AA_JSON_Template_Generic($this->id . "_head", array("css" => "AA_Popup_header_box", "type"=>"clean", "view" => "toolbar", "height" => "38", "elements" => array(
                    array("view" => "spacer"),
                    array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}")
                )));
            }

            if($this->head instanceof AA_JSON_Template_Generic)
            {
                $this->body->addRow(new AA_JSON_Template_Generic());
                $this->body->addRow($this->head);
            } 
        }

        if($this->css !="") $this->body->SetProp("css",$this->css);

        //Aggiunge il body
        $this->wnd->SetProp("body", $this->body);
    }

    protected $width = "1280";
    public function SetWidth($width = "1280")
    {
        if ($width > 0) $this->width = $width;
    }
    public function GetWidth()
    {
        return $this->width;
    }

    protected $height = "720";
    public function SetHeight($height = "720")
    {
        if ($height > 0) $this->height = $height;
    }
    public function GetHeight()
    {
        return $this->height;
    }

    //Gestione del contenuto
    protected $children=array();
    public function AddView($view)
    {
        if (is_array($view) && $view['id'] != "") {
            $this->children[]=new AA_JSON_Template_Generic($view['id'], $view);
            return;
        }

        if ($view instanceof AA_JSON_Template_Generic) 
        {
            $this->children[]=$view;
        }
    }

    public function __toString()
    {
        $this->Update();
        return json_encode($this->wnd->toArray());
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function GetObject()
    {
        $this->Update();
        return $this->wnd;
    }

    public function toBase64()
    {
        $this->Update();

        return $this->wnd->toBase64();
    }

    public function toArray()
    {
        $this->Update();

        return $this->wnd->toArray();
    }
}

//Template generic filter box
class AA_GenericFormDlg extends AA_GenericWindowTemplate
{
    protected $form = "";
    public function GetForm()
    {
        return $this->form;
    }
    public function GetFormId()
    {
        if ($this->form instanceof AA_JSON_Template_Form) return $this->form->GetId();
    }

    protected $layout = "";
    public function GetLayout()
    {
        return $this->layout;
    }
    public function GetLayoutId()
    {
        if ($this->layout instanceof AA_JSON_Template_Generic) return $this->layout->GetId();
    }

    protected $curRow = null;
    public function GetCurRow()
    {
        return $this->curRow;
    }

    protected $bottomPadding = 18;
    public function SetBottomPadding($val = 18)
    {
        $this->bottomPadding = $val;
    }

    protected $validation = false;
    public function EnableValidation($bVal = true)
    {
        $this->validation = $bVal;
    }

    protected $bEnableApplyHotkey = true;
    public function EnableApplyHotkey($bVal = true)
    {
        $this->bEnableApplyHotkey = $bVal;
    }

    protected $sApplyHotkey = "enter";
    public function SetApplyHotkey($val = "enter")
    {
        $this->sApplyHotkey = $val;
    }

    protected $applyActions = "";
    public function SetApplyActions($actions = "")
    {
        $this->applyActions = $actions;
    }

    protected $saveFormDataId = "";
    public function SetSaveformDataId($id = "")
    {
        $this->saveFormDataId = $id;
    }
    public function GetSaveFormDataId()
    {
        return $this->saveFormDataId;
    }

    protected $labelWidth = 120;
    public function SetLabelWidth($val = 120)
    {
        $this->labelWidth = $val;
    }

    protected $labelAlign = "left";
    public function SetLabelAlign($val = "left")
    {
        $this->labelAlign = $val;
    }

    protected $labelPosition="left";
    public function SetLabelPosition($val = "left")
    {
        $this->labelPosition = $val;
    }
    protected $defaultFocusedItem="right";
    public function SetDefaultFocusedItem($sVal="")
    {
        $this->defaultFocusedItem = $sVal;
    }

    //Gestione pulsanti
    protected $applyButton = null;
    protected $applyButtonName = "Salva";
    public function SetApplyButtonName($val = "Salva")
    {
        $this->applyButtonName = $val;
    }
    protected $resetButtonName = "Reset";
    public function SetResetButtonName($val = "Reset")
    {
        $this->resetButtonName = $val;
    }
    protected $enableReset = true;
    public function EnableResetButton($bVal = true)
    {
        $this->enableReset = $bVal;
    }
    protected $applyButtonStyle="AA_Button_primary";
    public function SetApplybuttonStyle($sStyle="")
    {
        $this->applyButtonStyle = $sStyle;
    }
    protected $applyButtonPosition="right";
    public function SetApplybuttonPosition($sVal="right")
    {
        $this->applyButtonPosition = $sVal;
    }
    #----------------------------------------------------

    //Valori form
    protected $formData = array();
    protected $resetData = array();

    public function __construct($id = "", $title = "", $module = "", $formData = array(), $resetData = array(), $applyActions = "", $save_formdata_id = "")
    {
        parent::__construct($id, $title, $module);

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->SetWidth("700");
        $this->SetHeight("400");

        $this->applyActions = $applyActions;
        $this->saveFormDataId = $save_formdata_id;
        $this->formData = $formData;
        if (sizeof($resetData) == 0) $resetData = $formData;
        $this->resetData = $resetData;

        $this->form = new AA_JSON_Template_Form($this->id . "_Form", array(
            "data" => $formData,
        ));

        $this->body->AddRow($this->form);
        $this->layout = new AA_JSON_Template_Layout($id . "_Form_Layout", array("type" => "clean"));
        $this->form->AddRow($this->layout);

        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 10, "css" => array("border-top" => "1px solid #e6f2ff !important;"))));
    }

    //File upload id
    protected $fileUploader_id = "";
    public function SetFileUploaderId($id="")
    {
        $this->fileUploader_id=$id;
    }

    #Gestione salvataggio dati
    protected $refresh = true; //Rinfresca la view in caso di salvataggio 
    public function enableRefreshOnSuccessfulSave($bVal = true)
    {
        $this->refresh = $bVal;
    }
    protected $refresh_obj_id = "";
    public function SetRefreshObjId($id = "")
    {
        $this->refresh_obj_id = $id;
    }
    protected $closeWnd = true;
    public function EnableCloseWndOnSuccessfulSave($bVal = true)
    {
        $this->closeWnd = $bVal;
    }
    protected $saveTask = "";
    public function SetSaveTask($task = "")
    {
        $this->saveTask = $task;
    }
    protected $saveTaskParams = array();
    public function SetSaveTaskParams($params = array())
    {
        if (is_array($params)) $this->saveTaskParams = $params;
    }

    protected $sTaskManager = "";
    public function SetTaskManager($var="")
    {
        $this->sTaskManager = $var;
    }
    #-----------------------------------------------------
        
    protected function Update()
    {
        $elementsConfig = array("labelWidth" => $this->labelWidth, "labelAlign" => $this->labelAlign, "bottomPadding" => $this->bottomPadding,"labelPosition"=>$this->labelPosition);
        if ($this->validation) {
            $this->form->SetProp("validation", "validateForm");
        }

        if($this->defaultFocusedItem !="") $this->form->SetProp("defaultFocusedItem",$this->id . "_Field_".$this->defaultFocusedItem);

        $this->form->SetProp("elementsConfig", $elementsConfig);

        if ($this->applyActions == "") {
            if ($this->saveTask != "") {
                $params = "{task: '$this->saveTask'";
                if($this->sTaskManager !="") $params .= ", taskManager: ".$this->sTaskManager;
                if (sizeof($this->saveTaskParams) > 0) $params .= ", taskParams: " . json_encode(array($this->saveTaskParams));
                if ($this->closeWnd) $params .= ", wnd_id: '" . $this->id . "_Wnd'";
                if ($this->refresh) $params .= ", refresh: true";
                if ($this->refresh_obj_id) $params .= ", refresh_obj_id: '$this->refresh_obj_id'";
                if ($this->fileUploader_id != "") $params .= ", fileUploader_id: '$this->fileUploader_id'";
                $params .= ", data: $$('" . $this->id . "_Form').getValues()}";
                if ($this->validation) $validate = "if($$('" . $this->id . "_Form').validate())";
                else $validate = "";
                $buttonApply=$this->id."_Button_Bar_Apply";
                $setTimeout="setTimeout('if($$(\"".$buttonApply."\")) { $$(\"".$buttonApply."\").enable()};',800)";
                $this->applyActions = $validate.'{$$(\''.$buttonApply."').disable();AA_MainApp.utils.callHandler('saveData',$params,'$this->module');".$setTimeout."}";
            }
        }

        //Apply button
        if($this->bEnableApplyHotkey) $this->applyButton = new AA_JSON_Template_Generic($this->id . "_Button_Bar_Apply", array("view" => "button", "width" => 80, "css"=>"webix_primary ".$this->applyButtonStyle,"hotkey"=>$this->sApplyHotkey,"label" => $this->applyButtonName));
        else $this->applyButton = new AA_JSON_Template_Generic($this->id . "_Button_Bar_Apply", array("view" => "button", "width" => 80, "css"=>"webix_primary ".$this->applyButtonStyle,"label" => $this->applyButtonName));

        //Toolbar
        $toolbar = new AA_JSON_Template_Layout($this->id . "_Button_Bar", array("height" => 38));
        $toolbar->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 15)));

        //reset form button
        if ($this->enableReset && is_array($this->resetData)) {
            $resetAction = "if($$('" . $this->id . "_Form')) "."{"."$$('".$this->id."_Button_Bar_Apply').enable();$$('" . $this->id . "_Form').setValues(" . json_encode($this->resetData) . ")}";
            $toolbar->addCol(new AA_JSON_Template_Generic($this->id . "_Button_Bar_Reset", array("view" => "button", "width" => 80, "label" => $this->resetButtonName, "tooltip" => "Reimposta i valori di default", "css"=>"AA_Button_secondary","click" => $resetAction)));
        }

        if($this->applyButtonPosition != "left") $toolbar->addCol(new AA_JSON_Template_Generic());
        $toolbar->addCol($this->applyButton);
        if($this->applyButtonPosition != "right") $toolbar->addCol(new AA_JSON_Template_Generic());
        
        $toolbar->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 15)));
        $this->body->AddRow($toolbar);
        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 10)));
        $this->applyButton->SetProp("click", $this->applyActions);
        //AA_log::log(__METHOD__." - apply: ".$this->applyActions,100);
        parent::Update();
    }

    //Aggiungi un campo al form
    public function AddField($name = "", $label = "", $type = "text", $props = array(), $newRow = true)
    {
        if ($name != "" && $label != "") {
            $props['name'] = $name;
            $props['label'] = $label;

            if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
                $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_".uniqid(time()));
                $this->layout->AddRow($this->curRow);
            }
            if ($type == "text") $this->curRow->AddCol(new AA_JSON_Template_Text($this->id . "_Field_" . $name, $props));
            if ($type == "richtext") $this->curRow->AddCol(new AA_JSON_Template_Richtext($this->id . "_Field_" . $name, $props));
            if ($type == "ckeditor5") $this->curRow->AddCol(new AA_JSON_Template_Ckeditor5($this->id . "_Field_" . $name, $props));
            if ($type == "textarea") $this->curRow->AddCol(new AA_JSON_Template_Textarea($this->id . "_Field_" . $name, $props));
            if ($type == "checkbox") $this->curRow->AddCol(new AA_JSON_Template_Checkbox($this->id . "_Field_" . $name, $props));
            if ($type == "select") $this->curRow->AddCol(new AA_JSON_Template_Select($this->id . "_Field_" . $name, $props));
            if ($type == "switch") $this->curRow->AddCol(new AA_JSON_Template_Switch($this->id . "_Field_" . $name, $props));
            if ($type == "datepicker") $this->curRow->AddCol(new AA_JSON_Template_Datepicker($this->id . "_Field_" . $name, $props));
            if ($type == "radio") $this->curRow->AddCol(new AA_JSON_Template_Radio($this->id . "_Field_" . $name, $props));

            //Se il campo è invisibile aggiunge uno spacer
            if (isset($props['hidden']) && $props['hidden'] == true) {
                $this->curRow->AddCol(new AA_JSON_Template_Generic($this->id . "_Spacer_" . $name, array("view" => "spacer", "minHeight" => "0", "minWidth" => "0", "height" => 1)));
            }
        }
    }

    //Aggiungi una nuova sezione
    public function AddSection($name = "New Section", $newRow = true)
    {
        if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_".uniqid(time()));
            $this->layout->AddRow($this->curRow);
            $this->curRow->AddCol(new AA_JSON_Template_Generic($this->id . "_Section_", array("type" => "section", "template" => $name)));
        } else {
            $this->curRow->AddCol(new AA_JSON_Template_Generic($this->id . "_Section_" . $name, array("type" => "section", "template" => $name)));
        }
    }

    //Aggiungi uno spazio
    public function AddSpacer($newRow = true)
    {
        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_".uniqid(time()));
            $this->layout->AddRow($this->curRow);
        }

        $this->curRow->AddCol(new AA_JSON_Template_Generic($this->id . "_Field_Spacer_".uniqid(time()), array("view" => "spacer")));
    }

    //Aggiungi un campo di testo
    public function AddTextField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "text", $props, $newRow);
    }

    //Aggiungi un campo di area di testo
    public function AddTextareaField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "textarea", $props, $newRow);
    }

    //Aggiungi un campo richtext
    public function AddRichtextField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "richtext", $props, $newRow);
    }

     //Aggiungi un campo richtext ckeditor5
     public function AddCkeditor5Field($name = "", $label = "", $props = array(), $newRow = true)
     {
         return $this->AddField($name, $label, "ckeditor5", $props, $newRow);
     }

    //Aggiungi un checkbox
    public function AddCheckBoxField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "checkbox", $props, $newRow);
    }

    //Aggiungi un switchbox
    public function AddSwitchBoxField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "switch", $props, $newRow);
    }

    //Aggiungi una select
    public function AddSelectField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "select", $props, $newRow);
    }

    //Aggiungi un radio control
    public function AddRadioField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "radio", $props, $newRow);
    }

    //Aggiungi un campo per la scelta delle strutture
    public function AddStructField($taskParams = array(), $params = array(), $fieldParams = array(), $newRow = true)
    {
        $onSearchScript = "try{ if($$('" . $this->form->GetId()."')){AA_MainApp.ui.MainUI.structDlg.lastSelectedItem={id: $$('" . $this->form->GetId()."').getValues().id_struct_tree_select};}; AA_MainApp.ui.MainUI.structDlg.show(" . json_encode($taskParams) . "," . json_encode($params) . ");}catch(msg){console.error(msg)}";

        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_".uniqid(time()));
            $this->layout->AddRow($this->curRow);
        }

        if (!isset($fieldParams['name']) || $fieldParams['name'] == "") $fieldParams['name'] = "struct_desc";
        if (!isset($fieldParams['label']) || $fieldParams['label'] == "") $fieldParams['label'] = "Struttura";
        if (!isset($fieldParams['readonly']) || $fieldParams['readonly'] == "") $fieldParams['readonly'] = true;
        if (!isset($fieldParams['click']) || $fieldParams['click'] == "") $fieldParams['click'] = $onSearchScript;

        $this->curRow->AddCol(new AA_JSON_Template_Search($this->id . "_Field_Struct_Search", $fieldParams));
    }

    //Aggiungi un campo per l'upload di file
    public function AddFileUploadField($name = "AA_FileUploader", $label = "Sfoglia...", $props = array(), $newRow = true)
    {
        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_".uniqid(time()));
            $this->layout->AddRow($this->curRow);
        }

        $props['name'] = "AA_FileUploader";
        if ($label == "") $props['value'] = "Sfoglia...";
        else $props['value'] = $label;
        $props['autosend'] = false;
        if (!isset($props['multiple']) || $props['multiple'] == "") $props['multiple'] = false;
        $props['view'] = "uploader";
        $props['link'] = $this->id . "_FileUpload_List";
        $props['layout_id'] = $this->id . "_FileUpload_Layout";
        $props['formData'] = array("file_id" => $name);

        $this->fileUploader_id = $this->id . "_FileUpload_Field";

        $template = new AA_JSON_Template_Layout($this->id . "_FileUpload_Layout", array("type" => "clean", "borderless" => true,"autoheight"=>true,));
        $template->AddRow(new AA_JSON_Template_Generic($this->id . "_FileUpload_Field", $props));
        $template->AddRow(new AA_JSON_Template_Generic($this->id . "_FileUpload_List", array(
            "view" => "list",
            "scroll" => false,
            "autoheight"=>true,
            "autoHeight"=>32,
            "type" => "uploader",
            "css" => array("background" => "transparent")
        )));

        if ($props['bottomLabel']) {
            $template->AddRow(new AA_JSON_Template_Template($this->id . "_FileUpload_BottomLabel", array(
                "autoheight"=>true,
                "template" => "<span style='font-size: smaller; font-style:italic'>" . $props['bottomLabel'] . "</span>",
                "css" => array("background" => "transparent")
            )));
        }

        $this->curRow->AddCol($template);
    }

    //Aggiungi un campo data
    public function AddDateField($name = "", $label = "", $props = array(), $newRow = true)
    {
        $props['timepick'] = false;
        if (!isset($props['format']) || $props['format'] == "") $props['format'] = "%Y-%m-%d";
        $props['stringResult'] = true;
        return $this->AddField($name, $label, "datepicker", $props, $newRow);
    }

    //Aggiungi un generico oggetto
    public function AddGenericObject($obj, $newRow = true)
    {
        if ($obj instanceof AA_JSON_Template_Generic) {
            if ($newRow) {
                $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row_".uniqid(time()));
                $this->layout->AddRow($this->curRow);
            }

            $this->curRow->AddCol($obj);
        }
    }
}

//Template generic filter box
class AA_GenericFilterDlg extends AA_GenericFormDlg
{
    protected $saveFilterId = "";
    public function SetSaveFilterId($id = "")
    {
        $this->saveFilterId = $id;
    }
    public function GetSaveFilterId()
    {
        return $this->saveFilterId;
    }

    protected $enableSessionSave = false;
    public function EnableSessionSave($bVal = true)
    {
        $this->enableSessionSave = $bVal;
    }

    public function __construct($id = "", $title = "", $module = "", $formData = array(), $resetData = array(), $applyActions = "", $save_filter_id = "")
    {
        parent::__construct($id, $title, $module, $formData, $resetData, $applyActions, $save_filter_id);

        $this->SetWidth("700");
        $this->SetHeight("400");

        $this->applyActions = $applyActions;
        $this->saveFilterId = $save_filter_id;

        /*$this->form=new AA_JSON_Template_Form($this->id."_Form",array(
            "data"=>$formData,
            "elementsConfig"=>array("labelWidth"=>180)
        ));
        
        $this->body->AddRow($this->form);
        
        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view"=>"spacer", "height"=>10, "css"=>array("border-top"=>"1px solid #e6f2ff !important;"))));
        
        //Apply button
        $this->applyButton = new AA_JSON_Template_Generic($this->id."_Button_Bar_Apply",array("view"=>"button","width"=>80, "label"=>"Applica"));
        
        //Toolbar
        $toolbar=new AA_JSON_Template_Layout($this->id."_Button_Bar",array("height"=>38));
        $toolbar->addCol(new AA_JSON_Template_Generic("spacer",array("view"=>"spacer","width"=>15)));
        
        //reset form button
        if(is_array($resetData))$resetAction="if($$('".$this->id."_Form')) $$('".$this->id."_Form').setValues(".json_encode($resetData).")";
        else $resetAction="";
        $toolbar->addCol(new AA_JSON_Template_Generic($this->id."_Button_Bar_Reset",array("view"=>"button","width"=>80,"label"=>"Reset", "tooltip"=>"Reimposta i valori di default", "click"=>$resetAction)));
        
        $toolbar->addCol(new AA_JSON_Template_Generic());
        
        $toolbar->addCol($this->applyButton);
        $toolbar->addCol(new AA_JSON_Template_Generic("spacer",array("view"=>"spacer","width"=>15)));
        $this->body->AddRow($toolbar);
        $this->body->AddRow(new AA_JSON_Template_Generic("spacer",array("view"=>"spacer","height"=>10)));*/
    }

    protected function Update()
    {
        parent::Update();

        if ($this->module == "") $module = "module=AA_MainApp.curModule";
        else $module = "module=AA_MainApp.getModule('" . $this->module . "')";

        if ($this->saveFilterId == "") $filter_id = "module.getActiveView()";
        else $filter_id = "'" . $this->saveFilterId . "'";

        if ($this->enableSessionSave) {
            $sessionSave = "AA_MainApp.setSessionVar(" . $filter_id . ", $$('" . $this->id . "_Form').getValues());";
        }
        else $sessionSave="";

        $this->applyButton->SetProp("click", "try{" . $module . "; if(module.isValid()) {" . $sessionSave . "module.setRuntimeValue(" . $filter_id . ",'filter_data',$$('" . $this->id . "_Form').getValues());" . $this->applyActions . ";}$$('" . $this->id . "_Wnd').close()}catch(msg){console.error(msg)}");
    }

    /*
    //Aggiungi un campo al form
    public function AddField($name="", $label="", $type="text", $props=array())
    {
        if($name !="" && $label !="")
        {
            $props['name']=$name;
            $props['label']=$label;
            
            if($type=="text") $this->form->AddElement(new AA_JSON_Template_Text($this->id."_Field_".$name,$props));
            if($type=="textarea") $this->form->AddElement(new AA_JSON_Template_Textarea($this->id."_Field_".$name,$props));
            if($type=="checkbox") $this->form->AddElement(new AA_JSON_Template_Checkbox($this->id."_Field_".$name,$props));
            if($type=="select") $this->form->AddElement(new AA_JSON_Template_Select($this->id."_Field_".$name,$props));
            if($type=="switch") $this->form->AddElement(new AA_JSON_Template_Switch($this->id."_Field_".$name,$props));
        }
    }
    
    //Aggiungi un campo di testo
    public function AddTextField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"text",$props);
    }
    
    //Aggiungi un campo di area di testo
    public function AddTextareaField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"textarea",$props);
    }
    
    //Aggiungi un checkbox
    public function AddCheckBoxField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"checkbox",$props);
    }
    
    //Aggiungi un switchbox
    public function AddSwitchBoxField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"switch",$props);
    }
    
    //Aggiungi una select
    public function AddSelectField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"select",$props);
    }
    
    //Aggiungi un campo per la scelta delle strutture
    public function AddStructField($taskParams=array(),$params=array(), $fieldParams=array())
    {
        $onSearchScript="try{ if($$('".$this->id."_Form').getValues().id_struct_tree_select) AA_MainApp.ui.MainUI.structDlg.lastSelectedItem={id: $$('".$this->id."_Form').getValues().id_struct_tree_select}; AA_MainApp.ui.MainUI.structDlg.show(". json_encode($taskParams).",".json_encode($params).");}catch(msg){console.error(msg)}";
        
        if($fieldParams['name']== "") $fieldParams['name']="struct_desc";
        if($fieldParams['label']== "") $fieldParams['label']="Struttura";
        if($fieldParams['readonly']== "") $fieldParams['readonly']=true;
        if($fieldParams['click']== "") $fieldParams['click']=$onSearchScript;
        
        $this->form->AddElement(new AA_JSON_Template_Search($this->id."_Field_Struct_Search",$fieldParams));
    }*/
}

//Classe gestione set di campi 
class AA_FieldSet extends AA_JSON_Template_Generic
{
    protected $formId="";
    public function GetFormId()
    {
        return $this->formId;
    }
    public function setFormId($val="")
    {
        $this->formId=$val;
    }

    public function __construct($id = "field_set", $label = "Generic field set",$formId="",$gravity=1,$props=array("type"=>"clean"))
    {
        $this->props['view'] = "fieldset";
        $this->props['label'] = $label;
        $this->props['id']=$id;
        $this->props['gravity']=$gravity;
        $this->layout = new AA_JSON_Template_Layout($id . "_FieldSet_Layout",array("type"=>"clean"));
        $this->addRowToBody($this->layout);

        $this->formId=$formId;
    }

    protected $layout = null;
    public function GetLayout()
    {
        return $this->layout;
    }
    public function GetLayoutId()
    {
        if ($this->layout instanceof AA_JSON_Template_Generic) return $this->layout->GetId();
    }

    protected $curRow = null;
    public function GetCurRow()
    {
        return $this->curRow;
    }

    //Aggiungi un campo al field set
    public function AddField($name = "", $label = "", $type = "text", $props = array(), $newRow = true)
    {
        if ($name != "" && $label != "") {
            $props['name'] = $name;
            $props['label'] = $label;

            if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
                $unique=uniqid(time());
                $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_".$unique);
                $this->layout->AddRow($this->curRow);
            }

            if ($type == "text") $this->curRow->AddCol(new AA_JSON_Template_Text($this->GetId() . "_Field_" . $name, $props));
            if ($type == "textarea") $this->curRow->AddCol(new AA_JSON_Template_Textarea($this->GetId() . "_Field_" . $name, $props));
            if ($type == "richtext") $this->curRow->AddCol(new AA_JSON_Template_Richtext($this->Getid() . "_Field_" . $name, $props));
            if ($type == "ckeditor5") $this->curRow->AddCol(new AA_JSON_Template_Ckeditor5($this->GetId() . "_Field_" . $name, $props));
            if ($type == "checkbox") $this->curRow->AddCol(new AA_JSON_Template_Checkbox($this->GetId() . "_Field_" . $name, $props));
            if ($type == "select") $this->curRow->AddCol(new AA_JSON_Template_Select($this->GetId() . "_Field_" . $name, $props));
            if ($type == "switch") $this->curRow->AddCol(new AA_JSON_Template_Switch($this->GetId() . "_Field_" . $name, $props));
            if ($type == "datepicker") $this->curRow->AddCol(new AA_JSON_Template_Datepicker($this->GetId() . "_Field_" . $name, $props));
            if ($type == "radio") $this->curRow->AddCol(new AA_JSON_Template_Radio($this->GetId() . "_Field_" . $name, $props));
        }
    }

    //Aggiungi una nuova sezione
    public function AddSection($name = "New Section", $newRow = true)
    {
        if ($newRow) {
            $unique=uniqid(time());
            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_".$unique);
            $this->layout->AddRow($this->curRow);
            $this->curRow->AddCol(new AA_JSON_Template_Generic($this->GetId() . "_Section_", array("type" => "section", "template" => $name)));
        } else {
            $this->curRow->AddCol(new AA_JSON_Template_Generic($this->GetId() . "_Section_" . $name, array("type" => "section", "template" => $name)));
        }
    }

    //Aggiungi uno spazio
    public function AddSpacer($newRow = true)
    {
        $unique=uniqid(time());
        if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
            
            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_".$unique);
            $this->layout->AddRow($this->curRow);
        }
        $this->curRow->AddCol(new AA_JSON_Template_Generic($this->GetId() . "_Field_Spacer_".$unique, array("view" => "spacer")));
    }

    //Aggiungi un campo di testo
    public function AddTextField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "text", $props, $newRow);
    }

    //Aggiungi un campo di area di testo
    public function AddTextareaField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "textarea", $props, $newRow);
    }

    //Aggiungi un campo richtext
    public function AddRichtextField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "richtext", $props, $newRow);
    }

    //Aggiungi un campo richtext ckeditor5
    public function AddCkeditor5Field($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "ckeditor5", $props, $newRow);
    } 

    //Aggiungi un checkbox
    public function AddCheckBoxField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "checkbox", $props, $newRow);
    }

    //Aggiungi un switchbox
    public function AddSwitchBoxField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "switch", $props, $newRow);
    }

    //Aggiungi una select
    public function AddSelectField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "select", $props, $newRow);
    }

    //Aggiungi un radio control
    public function AddRadioField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "radio", $props, $newRow);
    }

    //Aggiungi un campo per la scelta delle strutture
    public function AddStructField($taskParams = array(), $params = array(), $fieldParams = array(), $newRow = true)
    {
        if($this->formId !="") $form=$this->formId;
        else $form=$this->GetId() . "_Form";
        $onSearchScript = "try{ if($$('" . $form."')){AA_MainApp.ui.MainUI.structDlg.lastSelectedItem={id: $$('" . $form."').getValues().id_struct_tree_select};}; AA_MainApp.ui.MainUI.structDlg.show(" . json_encode($taskParams) . "," . json_encode($params) . ");}catch(msg){console.error(msg)}";

        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_".uniqid(time()));
            $this->layout->AddRow($this->curRow);
        }

        if ($fieldParams['name'] == "") $fieldParams['name'] = "struct_desc";
        if ($fieldParams['label'] == "") $fieldParams['label'] = "Struttura";
        if ($fieldParams['readonly'] == "") $fieldParams['readonly'] = true;
        if ($fieldParams['click'] == "") $fieldParams['click'] = $onSearchScript;

        $this->curRow->AddCol(new AA_JSON_Template_Search($this->GetId() . "_Field_Struct_Search", $fieldParams));
    }

    protected $fileUploader_id="";

    //Aggiungi un campo per l'upload di file
    public function AddFileUploadField($name = "AA_FileUploader", $label = "Sfoglia...", $props = array(), $newRow = true)
    {
        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_".uniqid(time()));
            $this->layout->AddRow($this->curRow);
        }

        $props['name'] = "AA_FileUploader";
        if ($label == "") $props['value'] = "Sfoglia...";
        else $props['value'] = $label;
        $props['autosend'] = false;
        if ($props['multiple'] == "") $props['multiple'] = false;
        $props['view'] = "uploader";
        $props['link'] = $this->GetId() . "_FileUpload_List";
        $props['layout_id'] = $this->GetId() . "_FileUpload_Layout";
        $props['formData'] = array("file_id" => $name);

        $this->fileUploader_id = $this->GetId() . "_FileUpload_Field";

        $template = new AA_JSON_Template_Layout($this->GetId() . "_FileUpload_Layout", array("type" => "clean", "borderless" => true,"autoheight"=>true));
        $template->AddRow(new AA_JSON_Template_Generic($this->GetId() . "_FileUpload_Field", $props));
        $template->AddRow(new AA_JSON_Template_Generic($this->GetId() . "_FileUpload_List", array(
            "view" => "list",
            "scroll" => false,
            "autoheight"=>true,
            "minHeight"=>32,
            "type" => "uploader",
            "css" => array("background" => "transparent")
        )));

        if ($props['bottomLabel']) {
            $template->AddRow(new AA_JSON_Template_Template($this->GetId() . "_FileUpload_BottomLabel", array(
                "autoheight"=>true,
                "template" => "<span style='font-size: smaller; font-style:italic'>" . $props['bottomLabel'] . "</span>",
                "css" => array("background" => "transparent")
            )));
        }

        $this->curRow->AddCol($template);
    }

    //Aggiungi un campo data
    public function AddDateField($name = "", $label = "", $props = array(), $newRow = true)
    {
        $props['timepick'] = false;
        if ($props['format'] == "") $props['format'] = "%Y-%m-%d";
        $props['stringResult'] = true;
        return $this->AddField($name, $label, "datepicker", $props, $newRow);
    }

    //Aggiungi un generico oggetto
    public function AddGenericObject($obj, $newRow = true)
    {
        if ($obj instanceof AA_JSON_Template_Generic) {
            if ($newRow) {
                $unique=uniqid(time());
                $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row_".$unique);
                $this->layout->AddRow($this->curRow);
            }

            $this->curRow->AddCol($obj);
        }
    }
}

class AA_SystemChangeCurrentUserPwdDlg extends AA_GenericFormDlg
{
    public function __construct($id = "", $title = "", $formData = array(), $resetData = array(), $applyActions = "", $save_formdata_id = "")
    {
        parent::__construct($id, $title,"",$formData,$resetData,$applyActions,$save_formdata_id);

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->SetWidth("480");
        $this->SetHeight("480");

        $this->SetLabelWidth(150);
        $this->EnableValidation();

        $this->AddTextField("old_user_pwd","Password attuale",array("required"=>true,"type"=>"password","bottomLabel"=>"Inserisci la tua password attuale."));
        $this->AddTextField("new_user_pwd","Nuova password",array("required"=>true,"type"=>"password","bottomLabel"=>"Inserisci la nuova password."));
        $this->AddTextField("re_new_user_pwd","Ridigita la nuova password",array("required"=>true,"type"=>"password","bottomLabel"=>"Reinserisci la nuova password."));

        $this->AddSpacer();
        $this->AddGenericObject(new AA_JSON_Template_Template($id."_ChangeUserPwdTips",array("type"=>"clean","autoheight"=>"true","template"=>"<div style='display: flex; flex-direction: column;'><span>La nuova password deve contenere:</span><ul><li>almeno 12 caratteri</li><li>almeno un numero</li><li>almeno una lettera maiuscola</li><li>almeno una lettera minuscola</li><li>almeno uno dei seguenti caratteri speciali: @$!%*?&</li><li>deve essere diversa dalla vecchia password</li></ul></div>")));
        $this->AddSpacer();

        $this->SetSaveTask("UpdateCurrentUserPwd");
        $this->SetTaskManager("AA_MainApp.taskManager");
        //$this->SetApplybuttonStyle("AA_Button_primary");
        $this->enableRefreshOnSuccessfulSave(false);
        $this->EnableCloseWndOnSuccessfulSave();
    }
}

class AA_SystemCurrentUserProfileDlg extends AA_GenericWindowTemplate
{
    public function __construct($id = "", $title = "")
    {
        parent::__construct($id, $title,"");

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->SetWidth("600");
        $this->SetHeight("480");

        $layout_tab=new AA_JSON_Template_Layout("",array("type"=>"clean","minWidth"=>500));

        $user=AA_User::GetCurrentUser();

        //Nome
        $value=$user->GetNome();
        $nome=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Nome:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //Cognome
        $value=$user->GetCognome();
        $cognome=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Cognome:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //ruolo
        $value=$user->GetRuolo();
        $ruolo=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Ruolo:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //nome profilo
        $value=$user->GetUsername();
        $profilo=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Profilo corrente:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //struttura
        $struttura="";
        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $struct=$user->GetStruct();
            $value="Nessuna";
            if($struct->GetAssessorato(true)>0)
            {
                $value=$struct->GetAssessorato();
            }
            if($struct->GetDirezione(true)>0)
            {
                $value.="<br>".$struct->GetDirezione();
            }

            if($struct->GetServizio(true)>0)
            {
                $value.="<br>".$struct->GetServizio();
            }

            $struttura=new AA_JSON_Template_Template("",array(
                "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "gravity"=>1,
                "data"=>array("title"=>"Struttura:","value"=>$value),
                "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
            ));
        }

        //Abilitazioni
        $value=$user->GetLabelFlags();
        $abilitazioni=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Abilitazioni:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        $row=new AA_JSON_Template_Layout("",array("type"=>"clean","css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $row->AddCol($profilo);
        $layout_tab->addRow($row);

        $row=new AA_JSON_Template_Layout("",array("type"=>"clean","css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $row->AddCol($nome);
        $row->AddCol($cognome);
        $row->AddCol($ruolo);
        $layout_tab->addRow($row);

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $row=new AA_JSON_Template_Layout("",array("type"=>"clean","css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
            $row->AddRow($struttura);
            $layout_tab->addRow($row);
        }

        $row=new AA_JSON_Template_Layout("",array("type"=>"clean", "css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $row->AddRow($abilitazioni);
        $layout_tab->addRow($row);

        //toolbar
        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38));

        //pwd change
        $pwd_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-key-chain",
             "label"=>"Cambia password",
             "align"=>"right",
             "css"=>"webix_primary ",
             "width"=>190,
             "tooltip"=>"Cambia la password di accesso al sistema",
             "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetChangeCurrentUserPwdDlg\",taskManager: AA_MainApp.taskManager, module: \"\"},'')"
        ));

        //profile change
        $profile_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-account-box-multiple",
             "label"=>"Cambia profilo",
             "align"=>"right",
             "css"=>"webix_primary ",
             "width"=>190,
             "tooltip"=>"Cambia il profilo utente corrente",
             "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetChangeCurrentUserProfileDlg\",taskManager: AA_MainApp.taskManager, module: \"\"},'')"
        ));

        $toolbar->AddElement(new AA_JSON_Template_Generic());
        $toolbar->AddElement($pwd_btn);
        $toolbar->AddElement(new AA_JSON_Template_Generic());
        $toolbar->AddElement($profile_btn);
        $toolbar->AddElement(new AA_JSON_Template_Generic());

        $row=new AA_JSON_Template_Layout("",array("type"=>"clean"));
        $row->AddRow($toolbar);
        
        $layout_tab->AddRow(new AA_JSON_Template_Generic("",array("height"=>20)));
        $layout_tab->AddRow($row);
        $layout_tab->AddRow(new AA_JSON_Template_Generic("",array("height"=>20)));

        $this->AddView($layout_tab);
    }
}