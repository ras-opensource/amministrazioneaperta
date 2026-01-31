<?php
Class AA_GenericDatatableTemplate extends AA_JSON_Template_Layout
{
    //titolo
    protected $sTitle="";
    public function SetTitle($val="")
    {
        $this->sTitle=$val;
    }
    //----------------------

    //filter
    protected $bFiltered=false;
    protected $sFilterTask='';

    protected $sFilterId='';

    protected $aFilterParams=null;
    public function SetFilterParams($val=null)
    {
        $this->aFilterParams=$val;
    } 
    public function SetFilterId($val='')
    {
        $this->sFilterId=$val;
    }
    public function GetFilterId()
    {
        return $this->sFilterId;
    }


    public function EnableFilter($val=true,$filterTask=null)
    {
        if($val) $this->bFiltered=true;
        else $this->bFiltered=false;

        if($filterTask) $this->sFilterTask=$filterTask;
    }
    public function DisableFilter()
    {
        $this->EnableFilter(false);
    }
    public function IsFiltered()
    {
        return $this->bFiltered;
    }

    public function SetFilterTask($val='')
    {
        $this->sFilterTask=$val;
    }
    public function GetFilterTask()
    {
        return $this->sFilterTask;
    }

    protected $oFilterBoxContent=null;
    public function SetFilterBoxContent($val=null)
    {
        $this->oFilterBoxContent=$val;
    }
    public function GetFilterBoxContent()
    {
        return $this->oFilterBoxContent;
    }
    //-------------------------

    //addNew
    protected $bEnableAddNew=false;
    protected $sAddNewTask='';
    protected $sAddNewTaskParams=null;
    public function SetAddNewTaskParams($val)
    {
        $this->sAddNewTaskParams=$val;
    }
    public function EnableAddNew($val=true,$sAddNewTask=null)
    {
        if($val) $this->bEnableAddNew=true;
        else $this->bEnableAddNew=false;

        if($sAddNewTask) $this->sAddNewTask=$sAddNewTask;
    }
    public function DisableAddNew()
    {
        $this->EnableAddNew(false);
    }

    public function SetAddNewTask($val='')
    {
        $this->sAddNewTask=$val;
    }

    protected $sAddNewBtnIcon='mdi mdi-pencil-plus';
    public function SetAddNewBtnIcon($val)
    {
        $this->sAddNewBtnIcon=$val;
    }
    public function GetAddNewBtnIcon()
    {
        return $this->sAddNewBtnIcon;
    }
    protected $sAddNewBtnLabel='Aggiungi';
    public function SetAddNewBtnLabel($val)
    {
        $this->sAddNewBtnLabel=$val;
    }
    public function GetAddNewBtnLabel()
    {
        return $this->sAddNewBtnLabel;
    }
    protected $sAddNewBtnTooltip='Aggiungi un nuovo elemento';
    public function SetAddNewBtnTooltip($val)
    {
        $this->sAddNewBtnTooltip=$val;
    }
    public function GetAddNewBtnTooltip()
    {
        return $this->sAddNewBtnTooltip;
    }
    protected $sAddNewBtnCss='';
    public function SetAddNewBtnCss($val)
    {
        $this->sAddNewBtnCss=$val;
    }
    public function GetAddNewBtnCss()
    {
        return $this->sAddNewBtnCss;
    }
    //------------------------------------

    //header
    protected $bHeader=false;
    protected $oCustomHeader=null;
    public function EnableHeader($val=true,$oCustomHeader=null)
    {
        if($val) $this->bHeader=true;
        else $this->bHeader=false;

        if($oCustomHeader instanceof AA_JSON_Template_Generic) $this->oCustomHeader=$oCustomHeader;
    }
    protected $headerCss=null;
    public function SetHeaderCss($val=null)
    {
        $this->headerCss=$val;
    }
    public function GetHeaderCss()
    {
        return $this->headerCss;
    }

    protected $nHeaderHeight=32;
    public function SetHeaderHeight($val=32)
    {
        $this->nHeaderHeight=intVal($val);
    }
    public function GetHeaderHeight()
    {
        return $this->nHeaderHeight;
    }
    protected function BuildHeader()
    {
        $id=$this->GetId()."_Header_".uniqid();

        if(!$this->headerCss) $this->headerCss=array("border-bottom"=>"1px solid #dadee0 !important");
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>$this->nHeaderHeight,"css"=>$this->headerCss));
        
        if($this->bFiltered)
        {
            $this->SetProp("filtered",true);

            if($this->oFilterBoxContent)
            {
                if($this->oFilterBoxContent instanceof AA_JSON_Template_Generic) $toolbar->addElement($this->oFilterBoxContent);
                else $toolbar->addElement(new AA_JSON_Template_Generic($id."_FilterContent",array("view"=>"label","align"=>"left","label"=>$this->oFilterBoxContent)));
            }
            else
            {
                $toolbar->addElement(new AA_JSON_Template_Generic(""));
            }
            
            $filterId=$this->sFilterId;
            if($filterId=='') $filterId=$this->GetId();

            $this->SetProp("filter_id",$filterId);

            $params='';
            if($this->aFilterParams)
            {
                if(is_array($this->aFilterParams)) 
                {
                    foreach($this->aFilterParams as $curParam=>$curParamValue)
                    {
                        if(is_array($curParamValue)) $params.=",\"".$curParam."\":".json_encode($curParamValue);
                        else $params.=",\"".$curParam."\":".$curParamValue;
                    }
                }
                else $params=",".$this->aFilterParams;
            }
            else
            {
                $params=",postParams: AA_MainApp.curModule.getRuntimeValue('" . $filterId . "','filter_data'), module: AA_MainApp.curModule.id";
            }

            //bottone filtro
            
            if(!empty($this->sFilterTask))
            {
                $modify_btn=new AA_JSON_Template_Generic($id."_FilterUtenti_btn",array(
                    "view"=>"button",
                    "type"=>"icon",
                    "icon"=>"mdi mdi-filter-cog",
                    "label"=>"Filtra",
                    "align"=>"right",
                    "width"=>120,
                    "tooltip"=>"Opzioni di filtraggio",
                    "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"".$this->sFilterTask."\"".$params."},AA_MainApp.curModule.id)"
                ));
                $toolbar->AddElement($modify_btn);
            }
        }
        else
        {
            $toolbar->addElement(new AA_JSON_Template_Generic(""));
            if($this->sTitle !="")
            {
                $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"label","gravity"=>5,"label"=>$this->sTitle,"align"=>"center")));
                $toolbar->addElement(new AA_JSON_Template_Generic(""));
            }
        }

        if($this->bEnableAddNew)
        {
            $params='';
            if($this->sAddNewTaskParams)
            {
                if(is_array($this->sAddNewTaskParams)) 
                {
                    foreach($this->sAddNewTaskParams as $curParam=>$curParamValue)
                    {
                        if(is_array($curParamValue)) $params.=",\"".$curParam."\":".json_encode($curParamValue);
                        else $params.=",\"".$curParam."\":".$curParamValue;
                    }
                }
                else $params=",".$this->sAddNewTaskParams;

                //AA_Log::Log(__METHOD__." - params: ".$params,100);
            }

            $modify_btn=new AA_JSON_Template_Generic($id."_AddNew_btn",array(
                "view"=>"button",
                 "type"=>"icon",
                 "icon"=>$this->sAddNewBtnIcon,
                 "label"=>$this->sAddNewBtnLabel,
                 "css"=>$this->sAddNewBtnCss,
                 "align"=>"right",
                 "width"=>120,
                 "tooltip"=>$this->sAddNewBtnTooltip,
                 "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"".$this->sAddNewTask."\"".$params."},AA_MainApp.curModule.id);"
             ));
             $toolbar->AddElement($modify_btn);
        }

        return $toolbar;
    }
    //----------------------------------------------

    protected $bSelect=false;
    public function EnableSelect($val=true)
    {
        if($val) $this->bSelect=true;
        else $this->bSelect=false;
    }
    public function DisableSelect()
    {
        $this->EnableSelect(false);
    }
    protected $bAutoRowHeight=false;
    public function EnableAutoRowsHeight($val=true)
    {
        if($val) $this->bAutoRowHeight=true;
        else $this->bAutoRowHeight=false;
    }
    public function DisableAutoRowsHeight()
    {
        $this->EnableAutoRowsHeight(false);
    }

    public function SetCustomHeader($val=null)
    {
        if($val instanceof AA_JSON_Template_Generic) $this->oCustomHeader=$val;
    }
    public function GetCustomHeader()
    {
        $this->oCustomHeader;
    }
    //-----------------------------------

    //data
    protected $aData=array();
    public function SetData($val=null)
    {
        if(is_array($val)) $this->aData=$val;
    }

    public function GetData()
    {
        return $this->aData;
    }
    //--------------------------------------

    //columns
    protected $aColumns=array();
    public function SetColumnHeaderInfo($colNum=0,$id='',$headerLabel='',$width="fillspace",$filterType=null,$sortType=null,$css=null)
    {
        if(sizeof($this->aColumns)>=($colNum+1))
        {
            if($filterType) $header=array($headerLabel,array("content"=>$filterType));
            else $header=$headerLabel;

            $column=array("id"=>$id,"header"=>$header);

            if($width !="fillspace") $column['width']=$width;
            else $column['fillspace']=true;

            if($sortType)
            {
                $column['sort']=$sortType;
            }

            if($css )
            {
                $column['css']=$css;
            }

            //AA_Log::Log(__METHOD__." - column: ".print_r($column,true),100);

            $this->aColumns[$colNum]=$column;
            return true;
        }
        return false;
    }

    protected $cssRowHover=null;
    public function EnableRowOver($val=true,$css='AA_DataTable_Row_Hover')
    {
        if($val) $this->cssRowHover=$css;
        else $this->cssRowHover=null;
    }
    public function DisableRowHover()
    {
        $this->cssRowHover=null;
    }

    protected $tableHeaderCss=null;
    public function SetTableHeaderCss($val='AA_Header_DataTable')
    {
        if($val) $this->tableHeaderCss=$val;
        else $this->tableHeaderCss=null;
    }
    protected $aTableProps=array();

    protected $sDatatableID='';
    public function GetDatatableId()
    {
        return $this->GetId()."_".$this->sDatatableID;
    }
    public function __construct($id='',$sTitle="",$nNumCols=0,$layoutProps=null,$tableProps=null)
    {
        parent::__construct($id,$layoutProps);

        if($nNumCols>0)
        {
            for($i=0;$i<$nNumCols;$i++)
            {
                $this->aColumns[]=array("id"=>"id_".$i,"header"=>"header_".$i,"fillspace"=>true);
            }
        }

        if($sTitle !="") $this->sTitle=$sTitle;

        if(is_array($tableProps))
        {
            $tableProps['view']="datatable";
        }
        else 
        {
            $tableProps=array(
                "view"=>"datatable"
            );
        }

        $this->aTableProps=$tableProps;

        $this->sDatatableID=$this->GetId()."_".uniqid();
    }

    //Scroll
    protected $bEnableScrollX=true;
    protected $bEnableScrollY=true;

    public function EnableScroll($bScrollX=true,$bScrollY=true)
    {
        if($bScrollX) $this->bEnableScrollX=true;
        else $this->bEnableScrollX=false;

        if($bScrollY) $this->bEnableScrollY=true;
        else $this->bEnableScrollY=false;
    }
    //--------------------------

    //lineHeight
    protected $nRowLineHeight=24;
    public function SetRowLineHeight($val=24)
    {
        $this->nRowLineHeight=intVal($val);
    }
    public function toArray()
    {
        //header
        if($this->bHeader)
        {
            if($this->oCustomHeader instanceof AA_JSON_Template_Generic)
            {
                $this->AddRow($this->oCustomHeader);
            }
            else $this->AddRow($this->BuildHeader());
        }
        //-------------

        if($this->sTitle !="") $this->SetProp('name',$this->sTitle);

        if(sizeof($this->aData)>0)
        {

            $this->aTableProps['columns']=$this->aColumns;
            
            $this->aTableProps['data']=$this->aData;

            if($this->cssRowHover) $this->aTableProps['hover']=$this->cssRowHover;
            else if(isset($this->aTableProps['hover']))unset($this->aTableProps['hover']);

            if($this->tableHeaderCss) $this->aTableProps['css']=$this->tableHeaderCss;
            else if(isset($this->aTableProps['css']))unset($this->aTableProps['css']);

            if($this->bSelect) $this->aTableProps['select']=true;
            else $this->aTableProps['select']=false;

            if($this->bAutoRowHeight)
            {
                $this->aTableProps['fixedRowHeight']=false;
            }
            else
            {
                $this->aTableProps['fixedRowHeight']=false;
            }

            $this->aTableProps['rowLineHeight']=$this->nRowLineHeight;
            
            $this->aTableProps['scrollX']=$this->bEnableScrollX;
            $this->aTableProps['scrollY']=$this->bEnableScrollY;

            $table = new AA_JSON_Template_Generic($this->sDatatableID,$this->aTableProps);

            $this->AddRow($table);
        }
        else
        {
            $this->AddRow(new AA_JSON_Template_Template('',array("template"=>"<div style='width:100%;height: 100%;display:flex; justify-content:center;align-items:center'>Non sono presenti elementi</div>")));
        }

        return parent::toArray();
    }
}
