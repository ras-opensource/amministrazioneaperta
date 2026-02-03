<?php
class AA_GenericParsableObject
{
    protected $aProps=array();
    
    //Importa i valori da un array
    protected function Parse($values=null)
    {
        if(is_array($values))
        {
            foreach($values as $key=>$value)
            {
                if(isset($this->aProps[$key]) && $key != "") $this->aProps[$key]=$value;
            }
        }
    }

    protected $aTemplateViewProps=array();
    public function SetTemplateViewProps($props=null)
    {
        if(is_array($props))
        {
            $this->aTemplateViewProps=array();
            $keys=array_keys($this->aProps);
            foreach($keys as $key=>$value)
            {
                if(!empty($props[$key])) $this->aTemplateViewProps[$key]=$props[$key];
            }
        }
    }
    public function GetTemplateViewProps()
    {
        if(empty($this->aTemplateViewProps)) $this->SetDefaultTemplateViewProps();

        return $this->aTemplateViewProps;
    }

    protected function SetDefaultTemplateViewProps()
    {
        $this->aTemplateViewProps=array();
        foreach($this->aProps as $key=>$value)
        {
            $this->aTemplateViewProps[$key]=array("label"=>$key,"visible"=>true,"class"=>"aa-text");
        }
    }

    //template view
    protected $oTemplateView=null;
    public function GetTemplateView($bRefresh=false)
    {
        if($this->oTemplateView !=null && !$bRefresh) return $this->oTemplateView;

        $this->oTemplateView=$this->GetDefaultTemplateView();
        
        return $this->oTemplateView;
    }

    public function GetDefaultTemplateView()
    {
        $oTemplateView=new AA_GenericTemplate_Grid();
        $templateAreas=array();
        
        if(empty($this->aTemplateViewProps)) $this->SetDefaultTemplateViewProps();

        foreach($this->aTemplateViewProps as $propName=>$propConfig)
        {
            if($propConfig['visible'])
            {
                $templateAreas[]=$propName;
                $class='';
                if(!empty($propConfig['class'])) $class=$propConfig['class'];
                else $class='aa-templateview-prop-'.$propName;

                    $value="";
                if(empty($propConfig['function'])) $value = "<span class='".$class."'>" . $this->GetProp($propName) . "</span>";
                else 
                {
                    if(method_exists($this,$propConfig['function'])) $value = "<div class='".$class."'>".$this->{$propConfig['function']}()."</div>";
                    else $value = "<span class='".$class."'>n.d.</span>";
                }

                if(!$oTemplateView->AddCellToGrid(new AA_JSON_Template_Template("", array(
                    "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
                    "data" => array("title" => "".$propConfig['label'].":", "value" => $value),
                    "css" => array("border-bottom" => "1px solid #dadee0 !important","width"=>"auto !important","height"=> "auto !important")
                )), $propName))
                {
                    AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile aggiungere la cella alla template view per la proprietà: " . $propName, 100);
                }
            }
        }

        if(isset($this->aTemplateViewProps['__areas'])) $templateAreas=$this->aTemplateViewProps['__areas'];
        $oTemplateView->SetTemplateAreas($templateAreas);
      
        if(isset($this->aTemplateViewProps['__cols'])) $oTemplateView->SetTemplateCols($this->aTemplateViewProps['__cols']);

        if(isset($this->aTemplateViewProps['__rows'])) $oTemplateView->SetTemplateRows($this->aTemplateViewProps['__rows']);

        return $oTemplateView;
    }

    public function SetTemplateView($var = null)
    {
        if($var instanceof AA_JSON_Template_Generic)
        {
            $this->oTemplateView=$var;
        }
    }

    public function __construct($params=null)
    {
        //Definisce le proprietà dell'oggetto e i valori di default
        $this->aProps['id']=0;

        if(is_array($params)) $this->Parse($params);
    }

    //imposta il valore di una propietà
    public function SetProp($prop="",$value="")
    {
        if($prop !="" && isset($this->aProps[$prop])) $this->aProps[$prop]=$value;
    }

    //restituisce il valore di una propietà
    public function GetProp($prop="")
    {
        if($prop !="" && isset($this->aProps[$prop])) return $this->aProps[$prop];
        else return "";
    }

    //restituisce tutte le propietà
    public function GetProps()
    {
        //AA_Log::Log(__METHOD__." - ".print_r($this->aProps,true),100);
        return $this->aProps;
    }
}
