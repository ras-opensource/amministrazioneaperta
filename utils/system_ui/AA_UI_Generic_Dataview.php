<?php
class AA_UI_Generic_Dataview extends AA_JSON_Template_Layout
{
    //impostazione del template
    protected $template = "<div>#data#</div>";
    public function GetTemplate()
    {
        return $this->template;
    }
    public function SetTemplate($var = "")
    {
        $this->template = $var;
    }
    //------------------------------------

    //------------data-------------------
    protected $aData = array(array("id" => 1, "data" => "data"));
    public function SetData($var = array())
    {
        if (is_array($var) && sizeof($var) > 0)
            $this->aData = $var;
    }
    public function GetData()
    {
        $this->aData;
    }
    //------------------------------------

    //------------paging---------------------
    protected $bPaging = false;
    public function EnablePaging($val = true)
    {
        if ($val)
            $this->bPaging = true;
        else
            $this->bPaging = false;
    }
    //---------------------------------------


    //------------filtering------------------
    protected $bFilter = false;
    public function EnableFiltering($val = true)
    {
        if ($val)
            $this->bFilter = true;
        else
            $this->bFilter = false;
    }
    //---------------------------------------

    //columns
    protected $nColumns = 0; //auto
    public function SetColumnNum($var = 0)
    {
        if (is_int($var) && $var >= 0)
            $this->nColumns = $var;
    }
    public function GetColumnNum()
    {
        return $this->nColumns;
    }
    //---------------------------------

    //rows
    protected $nRows = 0; //auto
    public function SetRowsNum($var = 0)
    {
        if (is_int($var) && $var >= 0)
            $this->nRows = $var;
    }
    public function GetRowsNum()
    {
        return $this->nRows;
    }
    //----------------------------------

    //itemWidth
    protected $nItemWidth = 0;
    public function SetItemWidth($val = 0)
    {
        if (is_int($val) && $val >= 0)
            $this->nItemWidth = $val;
    }
    public function GetItemWidth()
    {
        return $this->nItemWidth;
    }
    //-------------------------------------

    //itemWidth
    protected $nItemHeight = 0;
    public function SetItemHeight($val = 0)
    {
        if (is_int($val) && $val >= 0)
            $this->nItemHeight = $val;
    }
    public function GetItemHeight()
    {
        return $this->nItemHeight;
    }
    //-------------------------------------

    //dataview css
    protected $dataviewCss = "";
    public function SetDataviewCss($val = "")
    {
        $this->dataviewCss = $val;
    }
    public function GetDataviewCss()
    {
        return $this->dataviewCss;
    }
    //-------------------------------------

    //---------------item css------------------------
    protected $itemCss = "";
    public function SetItemCss($val = "")
    {
        $this->itemCss = $val;
    }
    public function GetItemCss()
    {
        return $this->itemCss;
    }
    //------------------------------------------------

    //---------------item padding------------------------
    protected $itemPadding = "";
    public function SetItemPadding($val = "")
    {
        $this->itemPadding = $val;
    }
    public function GetItemPadding()
    {
        return $this->itemPadding;
    }
    //------------------------------------------------

    //------------------dataview component------------
    protected $oDataview = null;
    public function GetDataview()
    {
        return $this->oDataview;
    }
    public function SetDataview($var)
    {
        if ($var instanceof AA_JSON_Template_Generic && $var->props['view'] == "dataview")
            $this->oDataview = $var;
    }

    //---------------------pager component---------------
    protected $pager = null;
    public function GetPager()
    {
        return $this->pager;
    }
    public function SetPager($var)
    {
        if ($var instanceof AA_JSON_Template_Generic && $var->props['view'] == "pager")
            $this->pager = $var;
    }
    protected $pagerPosition = 1; //1=top, 2=bottom
    public function SetPagerPosition($pos = 1)
    {
        if (is_int($pos) && $pos > 0 && $pos <= 2)
            $this->pagerPosition = $pos;
    }
    public function GetPagerPosition()
    {
        return $this->pagerPosition;
    }
    protected $pagerSize = 5;
    public function SetPagerSize($val = 10)
    {
        if (is_int($val) && $val > 0)
            $this->pagerSize = $val;
    }
    public function GetPagerSize()
    {
        return $this->pagerSize;
    }

    protected $pagerGroup = 5;
    public function SetPagerGroup($val = 5)
    {
        if (is_int($val) && $val > 0)
            $this->pagerGroup = $val;
    }
    public function GetPagerGroup()
    {
        return $this->pagerGroup;
    }

    protected $pagerPage = 0;
    public function SetPagerPage($val = 0)
    {
        if (is_int($val) && $val >= 0)
            $this->pagerPage = $val;
    }
    public function GetPagerPage()
    {
        return $this->pagerPage;
    }

    protected $pagerTemplate = "<span>{common.first()}{common.pages()}{common.last()} pag. {common.page()} di #limit#</span>";
    public function SetPagerTemplate($val = "<span>{common.first()}{common.pages()}{common.last()}</span>")
    {
        $this->pagerTemplate = $val;
    }
    public function GetPagerTemplate()
    {
        return $this->pagerTemplate;
    }

    protected $pagerCss = "";
    public function SetPagerCss($val = "")
    {
        $this->pagerCss = $val;
    }
    //----------------------------------------------------

    //----------------filter component--------------------
    protected $filter = null;
    public function GetFilter()
    {
        return $this->filter;
    }
    public function SetFilter($var)
    {
        if ($var instanceof AA_JSON_Template_Generic && $var->props['view'] == "search")
            $this->filter = $var;
    }
    protected $sFilterFunct = "";
    public function SetFilterFunction($funct = "")
    {
        $this->sFilterFunct = $funct;
    }
    protected $sFilterField = "";
    public function SetFilterField($val = "")
    {
        $this->sFilterField = $val;
    }
    protected $filterCss = "";
    public function SetFilterCss($val = "")
    {
        $this->filterCss = $val;
    }
    //---------------------------------------------------------

    public function __construct($id = "", $props = null)
    {
        if (!isset($props['type']))
            $props['type'] = "clean";
        if (!isset($props['borderless']))
            $props['borderless'] = true;

        parent::__construct($id, $props);
    }

    public function toArray()
    {
        if ($this->oDataview == null) {
            $this->oDataview = new AA_JSON_Template_Generic($this->GetId() . "_Dataview", array("view" => "dataview", "borderless" => true));

            if (is_string($this->template))
                $this->oDataview->SetProp('template', $this->template);
            if ($this->template instanceof AA_XML_Element_Generic)
                $this->oDataview->SetProp('template', $this->template->__toString());

            $this->oDataview->SetProp('data', $this->aData);

            if ($this->dataviewCss != "") {
                $this->oDataview->SetProp('css', $this->dataviewCss);
            }

            $type = array();
            if ($this->nItemWidth > 0)
                $type["width"] = $this->nItemWidth;
            else
                $type["width"] = "auto";
            if ($this->nItemHeight > 0)
                $type["height"] = $this->nItemHeight;
            else
                $type["height"] = "auto";
            if ($this->itemPadding != "")
                $type["padding"] = $this->itemPadding;
            if ($this->itemCss != "")
                $type["css"] = $this->itemCss;
            $this->oDataview->SetProp('type', $type);

            if ($this->nColumns > 0)
                $this->oDataview->SetProp('xCount', $this->nColumns);
            if ($this->nRows > 0)
                $this->oDataview->SetProp('yCount', $this->nRows);

            $this->oDataview->SetProp('scroll', "y");
        }

        //dataview non inizialized
        if (!$this->oDataview instanceof AA_JSON_Template_Generic) {
            $this->addRow(new AA_JSON_Template_Template("", array("template" => "Dataview non inizializzato.")));
            return parent::toArray();
        }

        if ($this->oDataview->GetProp("view") != "dataview") {
            $this->addRow(new AA_JSON_Template_Template("", array("template" => "Dataview non inizializzato.")));
            return parent::toArray();
        }

        //pager
        if ($this->bPaging && $this->pager == null) {
            $this->pager = new AA_JSON_Template_Generic($this->GetId() . "_Pager", array("view" => "pager", "size" => $this->pagerSize, "group" => $this->pagerGroup, "page" => $this->pagerPage, "template" => $this->pagerTemplate));
            if ($this->pagerCss != "") {
                $this->pager->SetProp("css", $this->pagerCss);
            }
            //AA_Log::Log(__METHOD__." pager: ".$this->pager->__toString(),100);
            $this->oDataview->SetProp("pager", $this->GetId() . "_Pager");
        }

        if ($this->filter == null && $this->bFilter && $this->sFilterFunct != "") {
            $this->filter = new AA_JSON_Template_Search($this->GetId() . "_Filter", array("filterFunction" => $this->sFilterFunct, "clear" => true));
            if ($this->filterCss != "")
                $this->filter->setProp("css", $this->filterCss);
            $this->filter->setProp("filterTarget", $this->oDataview->getId());
            $this->filter->setProp("filterField", $this->sFilterField);
        }

        //insert filter
        if ($this->bFilter && $this->filter instanceof AA_JSON_Template_Generic) {
            if ($this->bPaging && $this->pagerPosition == 1 && $this->pager instanceof AA_JSON_Template_Generic) {
                $rowfiltering = new AA_JSON_Template_Layout("", array("type" => "clean"));
                $rowfiltering->AddCol($this->pager);
                $rowfiltering->AddCol($this->filter);
                $this->addRow($rowfiltering);
            } else {
                $this->addRow($this->filter);
            }
        } else {
            if ($this->bPaging && $this->pagerPosition == 1 && $this->pager instanceof AA_JSON_Template_Generic)
                $this->addRow($this->pager);
        }

        $this->AddRow($this->oDataview);

        if ($this->bPaging && $this->pagerPosition == 2 && $this->pager instanceof AA_JSON_Template_Generic)
            $this->addRow($this->pager);

        //AA_Log::Log(__METHOD__." dataview: ".$this->toString(),100);

        return parent::toArray();
    }
}
