<?php
class AA_GenericTemplate_Grid extends AA_JSON_Template_Layout
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "layout";
        if ($id == "")
            $id = "AA_GENERIC_TEMPLATE_GRID" . uniqid();

        parent::__construct($id, $props);
    }

    protected $templateCols=array();
    public function SetTemplateCols($cols=array())
    {
        if(is_array($cols)) $this->templateCols=$cols;
    }
    public function GetTemplateCols()
    {
        return $this->templateCols;
    }

    protected $templateRows=array();
    public function SetTemplateRows($rows=array())
    {
        if(is_array($rows)) $this->templateRows=$rows;
    }
    public function GetTemplateRows()
    {
        return $this->templateRows;
    }
    protected $templateAreas=array();
    public function SetTemplateAreas($areas=array())
    {
        $css=$this->GetProp("css");
        if(!is_array($css)) $css=array();

        if(is_array($areas) && sizeof($areas)>0) 
        {
            $this->templateAreas=$areas;
            $this->aAreas=array();
            
            $css['grid-template-areas']="";

            foreach($this->templateAreas as $curGridRow)
            {
                if(is_array($curGridRow)) 
                {
                    foreach($curGridRow as $curArea) 
                    {
                        if(!in_array($curArea,$this->aAreas)) $this->aAreas[]=$curArea;
                    }
                    
                    $css['grid-template-areas'].="'".implode(" ",$curGridRow)."' ";
                }
                else 
                {
                    if(!in_array($curGridRow,$this->aAreas)) $this->aAreas[]=$curGridRow;
                    $css['grid-template-areas'].="'".$curGridRow."' ";
                }
            }
        }
        else
        {
            $this->aAreas=array("main");
            $css['grid-template-areas']="main";
        }
        
        $this->SetProp("css", $css);
    }
    public function  GetTemplateAreas()
    {
        return $this->templateAreas;
    }

    protected $aAreas=array();
    public function GetAreas()
    {
        return $this->aAreas;
    }
    protected function Update()
    {
        $css=$this->GetProp("css");
        if(!is_array($css)) $css=array();
        $css['display']="grid";
        if(is_array($this->templateCols) && sizeof($this->templateCols)>0)
            $css['grid-template-columns']=implode(" ",$this->templateCols);
        else $css['grid-template-columns']="auto";

        if(is_array($this->templateRows) && sizeof($this->templateRows)>0)
            $css['grid-template-rows']=implode(" ",$this->templateRows);
        else $css['grid-template-rows']="auto";

        //AA_Log::Log(__METHOD__." setting css grid - ".print_r($css,true),100);
        $this->SetProp("css", $css);

        $this->cells=array();
        if(is_array($this->aGridCells) && sizeof($this->aGridCells)>0)
        {
            foreach($this->aGridCells as $curCell)
            {
                //AA_Log::Log(__METHOD__." Update grid - aggiungo la cella: ".print_r($curCell,true),100);
                $this->addRow($curCell);
            }
        }

        //AA_Log::Log(__METHOD__." Updated grid object - ".print_r($this,true),100);
    }

    public function ParseData($data=array())
    {
        if(is_array($data))
        {
            foreach($data as $curArea=>$curAreaData)
            {
                if(!empty($this->aGridCells[$curArea]))
                {
                    $this->aGridCells[$curArea]->SetProp("data",$curArea);
                }
            }
        }
    }

    protected $aGridCells=array();
    public function AddCellToGrid($cell=null, $area="")
    {
        if($cell instanceof AA_JSON_Template_Generic)
        {
            if(empty($area)) $area="main";
            $css=$cell->GetProp("css");
            if(!is_array($css)) $css=array();
            if($area!="") $css['grid-area']=$area;
            $cell->SetProp("css", $css);
            $this->aGridCells[$area]=$cell;

            return true;
        }

        return false;
    }

    public function SetGridCell($cell=null, $area="")
    {
        if($cell instanceof AA_JSON_Template_Generic)
        {
            if(empty($area)) $area="main";
            $css=$cell->GetProp("css");
            if(!is_array($css)) $css=array();
            if($area!="") $css['grid-area']=$area;
            $cell->SetProp("css", $css);
            $this->aGridCells[$area]=$cell;

            return true;
        }

        return false;
    }

    public function RemoveGridCell($area)
    {
        if(isset($this->aGridCells[$area]))
        {
            unset($this->aGridCells[$area]);
            return true;
        }

        return false;
    }

    public function GetGridCells()
    {
        return $this->aGridCells;
    }

    public function ClearGridCells()
    {
        $this->aGridCells=array();
    }

    public function toArray()
    {
        $this->Update();
        return parent::toArray();
    }
}
