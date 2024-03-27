<?php
#------------- Template View Class ----------------------------
#Classe base per la gestione delle views degli oggetti
class AA_ObjectTemplateView
{
    #Restituisce la visualizzazione per la finestra di dettaglio
    private $sDetail = "";
    public function SetDetail($newContent)
    {
        $this->sDetail = $newContent;
    }

    public function GetDetail()
    {
        return $this->sDetail;
    }

    public function DetailView()
    {
    }

    public function __construct()
    {
        
    }
}
#--------------------------------------------------

#Classe generico elemento html
class AA_XML_Element_Generic
{
    private $oParent = null;
    public function __construct($type = "div", $id = "AA_XML_ELEMENT_GENERIC", $parent = null)
    {
        $this->sElement = $type;
        $this->sId = $id;

        $this->sInnerClass = get_class();

        if ($parent instanceof AA_XML_Element_Generic) {
            $parent->AppendChild($this);
        }
    }

    //Flag di visibilità
    private $bHide = false;
    public function Hide($bHide = true)
    {
        $this->bHide = $bHide;
    }
    public function IsHidden()
    {
        return $this->bHide;
    }
    public function IsVisible()
    {
        return !$this->bHide;
    }
    public function Show($bShow = true)
    {
        if ($bShow) $this->bHide = false;
        else $this->bHide = true;
    }

    //Aggiungi sempre il tag di chiusura anche se il contenuto è vuoto;
    private $bAlwaysAddEndTag = false;
    public function AddAlwaysEndTag($bEnable = true)
    {
        $this->bAlwaysAddEndTag = $bEnable;
    }

    //Imposta il genitore
    public function SetParent($parent = null)
    {
        $this->oParent = null;
        if ($parent instanceof AA_XML_Element_Generic) {
            $this->oParent = $parent;
        }
    }
    public function GetParent()
    {
        return $this->oParent;
    }

    private $sId = "AA_XML_ELEMENT_GENERIC";
    public function SetId($id = "AA_XML_ELEMENT_GENERIC")
    {
        if ($id != "") $this->sId = $id;
    }
    public function GetId()
    {
        return $this->sId;
    }

    //Imposta il tipo di elemento
    private $sElement = "div";
    public function SetElement($element = "div")
    {
        if ($element != "") $this->sElement = $element;
    }
    public function GetElement()
    {
        return $this->sElement;
    }

    //stile
    protected $sStyle = "";
    public function SetStyle($style = "", $bAppend = false)
    {
        if (!$bAppend) $this->sStyle = $style;
        else {
            if ($this->sStyle != "") $this->sStyle .= ";" . $style;
            else $this->sStyle .= $style;
        }

        //AA_Log::Log(__METHOD__." - style:".$style." - newStyle: ".$this->sStyle,100);
    }
    public function GetStyle()
    {
        return $this->sStyle;
    }

    //Imposta la classe
    private $sClass = "";
    protected $sInnerClass = "AA_XML_Generic_Element";

    public function SetClass($class = "")
    {
        $this->sClass = $class;
    }
    public function GetClass()
    {
        return $this->sClass;
    }

    //Imposta gli attributi
    private $aAttribs = array();
    public function SetAttribs($attribs)
    {
        if (is_array($attribs)) $this->aAttribs = $attribs;
    }
    public function GetAttribs()
    {
        return $this->aAttribs;
    }
    public function SetAttribute($attribute = "", $value = "")
    {
        if ($attribute != "" && $attribute != "id" && $attribute != "style" && $attribute != "class") $this->aAttribs[$attribute] = $value;
    }
    public function GetAttribute($attribute = "")
    {
        if ($attribute == "id") return $this->sId;
        if ($attribute == "style") return $this->sStyle;
        if ($attribute == "class") return $this->sClass;
        if ($attribute != "" && array_key_exists($attribute, $this->aAttribs)) return $this->aAttribs[$attribute];
        return "";
    }

    //Elementi figli
    private $aChildren = array();
    public function AppendChild($child = "")
    {
        if ($child instanceof AA_XML_Element_Generic) {
            $this->aChildren[] = $child;
            $child->SetParent($this);
        } else $this->aChildren[] = new AA_XML_Element_Generic($child);
    }

    //Restituisce il primo figlio con l'identificativo indicato
    public function GetChild($id = "")
    {
        if ($id == "") return null;

        foreach ($this->aChildren as $curChild) {
            if (strcmp($curChild->GetId(), $id) == 0) return $curChild;
        }

        return null;
    }

    //Restituisce la chiave di ordinamento del figlio indicato
    public function GetChildKey($child = null)
    {
        if ($child instanceof AA_XML_Element_Generic) {
            $key = array_search($child, $this->aChildren);
            if ($key !== false) return $key;
        }

        if ($child == "") return -1;

        foreach ($this->aChildren as $key => $curChild) {
            if (strcmp($curChild->GetId(), $child) == 0) return $key;
        }

        return -1;
    }

    //inserisce un figlio all'inizio
    public function InsertChild($child = "")
    {
        if ($child instanceof AA_XML_Element_Generic) {
            array_splice($this->aChildren, 0, 0, array($child));
            $child->SetParent($this);
        }
    }

    //inserisce un figlio dopo un altro
    public function InsertChildAfter($child = null, $childRef = null)
    {
        if ($child instanceof AA_XML_Element_Generic && !($childRef instanceof AA_XML_Element_Generic)) return $this->AppendChild($child);
        if ($child instanceof AA_XML_Element_Generic && $childRef instanceof AA_XML_Element_Generic) {
            $key = array_search($childRef, $this->aChildren);
            if ($key !== false) {
                array_splice($this->aChildren, ($key + 1), 0, array($child));
                $child->SetParent($this);
                return;
            } else {
                return $this->AppendChild($child);
            }
        }
    }

    //inserisce un figlio prima di un altro
    public function InsertChildBefore($child = null, $childRef = null)
    {
        if ($child instanceof AA_XML_Element_Generic && !($childRef instanceof AA_XML_Element_Generic)) return $this->InsertChild($child);
        if ($child instanceof AA_XML_Element_Generic && $childRef instanceof AA_XML_Element_Generic) {
            $key = array_search($childRef, $this->aChildren);
            if ($key !== false) {
                array_splice($this->aChildren, $key, 0, array($child));
                $child->SetParent($this);
                return;
            } else {
                return $this->InsertChild($child);
            }
        }
    }

    //Rimuove un figlio
    public function RemoveChild($child = null)
    {
        if ($child instanceof AA_XML_Element_Generic) {
            $key = array_search($child, $this->aChildren);
            if ($key !== false) {
                array_splice($this->aChildren, $key + 1, 1);
                $child->SetParent(null);
                return true;
            }
        }

        //Non viene passato un oggetto
        foreach ($this->aChildren as $key => $curChild) {
            if (strcmp($curChild->GetId(), $child) == 0) {
                array_splice($this->aChildren, $key + 1, 1);
                $curChild->SetParent(null);
                return true;
            }
        }

        return false;
    }

    //Scambia due elementi
    public function Swap($child_one = null, $child_two = null)
    {
        if (!($child_one instanceof AA_XML_Element_Generic)) $child_one = $this->GetChild($child_one);
        if ($child_one == null) return false;

        if (!($child_two instanceof AA_XML_Element_Generic)) $child_two = $this->GetChild($child_two);
        if ($child_two == null) return false;

        $key_child_one = $this->GetChildKey($child_one);
        $key_child_two = $this->GetChildKey($child_two);

        $this->aChildren[$key_child_one] = $child_two;
        $this->aChildren[$key_child_two] = $child_one;

        return true;
    }

    //Rimuove tutti i figli
    public function RemoveAllChildren()
    {
        foreach ($this->aChildren as $curChild) {
            $curChild->SetParent(null);
        }
        $this->aChildren = array();
    }

    //Testo da inserire prima o dopo dei figli
    private $sTextBeforeChildren = "";
    private $sTextAfterChildren = "";
    public function SetText($text = "", $bBeforeChildren = true)
    {
        if ($bBeforeChildren) $this->sTextBeforeChildren = $text;
        else $this->sTextAfterChildren = $text;
    }
    public function GetText($bBeforeChildren = true)
    {
        if ($bBeforeChildren) return $this->sTextBeforeChildren;
        else return $this->sTextAfterChildren;
    }

    //Restituisce la rappresentazione dell'elemento come stringa di testo
    public function __toString()
    {
        //Restituisce una string vuota se l'elemento è invisibile
        if ($this->bHide) return "";

        $out = "<" . $this->sElement;
        if ($this->sId != "") $out .= ' id="' . addslashes($this->sId) . '"';
        if ($this->sStyle != "") $out .= ' style="' . $this->sStyle . '"';
        if ($this->sInnerClass != "" || $this->sClass != "") $out .= ' class="' . $this->sInnerClass . ' ' . $this->sClass . '"';

        foreach ($this->aAttribs as $attr => $value) {
            $out .= " " . $attr . '="' . $value . '"';
        }

        $content = "";
        foreach ($this->aChildren as $curChild) {
            $content .= $curChild;
        }

        //if($content == "" && $this->sTextBeforeChildren == "" && $this->sTextAfterChildren == "" && !$this->bAddAlwaysEndTag) $out.=" />";
        $out .= ">" . $this->sTextBeforeChildren . $content . $this->sTextAfterChildren . "</" . $this->sElement . ">";

        return $out;
    }
}
#--------------------------------------------------

#Classe div
class AA_XML_Div_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_DIV_ELEMENT", $parent = null)
    {
        parent::__construct("div", $id, $parent);

        $this->sInnerClass = get_class();

        //Aggiungi sempre il tag finale.
        $this->AddAlwaysEndTag();
    }
}
#-------------------------------------------------

#Classe a
class AA_XML_A_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_A_ELEMENT", $parent = null)
    {
        parent::__construct("a", $id, $parent);

        $this->sInnerClass = get_class();

        //Aggiungi sempre il tag finale.
        $this->AddAlwaysEndTag();
    }
}
#-------------------------------------------------

#Classe form
class AA_XML_Form_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_FORM_ELEMENT", $parent = null)
    {
        parent::__construct("form", $id, $parent);

        $this->sInnerClass = get_class();

        //Aggiungi sempre il tag finale.
        $this->AddAlwaysEndTag();
    }
}
#-------------------------------------------------


#Classe input
class AA_XML_Input_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_INPUT_ELEMENT", $parent = null)
    {
        parent::__construct("input", $id, $parent);

        $this->sInnerClass = get_class();
    }
}
#-------------------------------------------------

#Classe file
class AA_XML_File_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_FILE_ELEMENT", $parent = null)
    {
        parent::__construct("file", $id, $parent);

        $this->sInnerClass = get_class();
    }
}
#-------------------------------------------------

#Classe textarea
class AA_XML_Textarea_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_TEXTAREA_ELEMENT", $parent = null)
    {
        parent::__construct("textarea", $id, $parent);

        $this->sInnerClass = get_class();

        //Aggiungi sempre il tag finale.
        $this->AddAlwaysEndTag();
    }
}
#-------------------------------------------------

#Classe select
class AA_XML_Select_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_SELECT_ELEMENT", $parent = null)
    {
        parent::__construct("select", $id, $parent);

        $this->sInnerClass = get_class();

        //Aggiungi sempre il tag finale.
        $this->AddAlwaysEndTag();
    }
}
#-------------------------------------------------

#Classe option
class AA_XML_Option_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_OPTION_ELEMENT", $parent = null)
    {
        parent::__construct("option", $id, $parent);

        $this->sInnerClass = get_class();

        //Aggiungi sempre il tag finale.
        $this->AddAlwaysEndTag();
    }
}
#-------------------------------------------------

#Classe per il generico box
class AA_GenericBoxTemplateView extends AA_XML_Div_Element
{
    public function __construct($content = "", $id = "", $parent = null)
    {
        if ($id == "") $id = get_class();

        //error_log(__METHOD__."(".$id.")");

        parent::__construct($id, $parent);

        $this->SetText($content);
    }

    public function SetContent($newContent)
    {
        $this->SetText($newContent);
    }
    public function GetContent()
    {
        return $this->GetText();
    }
    public function ToHtml()
    {
        return $this->__toString();
    }
    public function ContentView()
    {
        return $this->ToHtml();
    }
}

#classe per la gestione della vista delle voci di un accordion
class AA_GenericAccordionItemTemplateView
{
    #Identificativo dell'item (deve essere un valore univoco)
    protected $sId = "";
    public function GetId()
    {
        return $this->sId;
    }
    public function SetId($val = "")
    {
        $this->sId = $val;
    }

    #Identificativo della classe di oggetti
    protected $sClass = "GenericObject";
    public function GetClass()
    {
        return $this->sClass;
    }
    public function SetClass($val = "GenericObject")
    {
        $this->sClass = $val;
    }

    #Titolo dell'item sull'header (può essere una stringa html)
    protected $sTitle = "";
    protected $sTitleStyle = "width:100%; font-size: 1.2em; font-weight: bold; margin-bottom: .2em;";
    protected $bShowTitle = true;

    #sottotitolo dell'item sull'header (può essere una stringa html)
    protected $sSubTitle = "";
    protected $sSubTitleStyle = "width:100%; margin-bottom: .2em;";
    protected $bShowSubTitle = true;

    #pretitolo dell'item sull'header (può essere una stringa html)
    protected $sPreTitle = "";
    protected $sPreTitleStyle = "width:100%; margin-bottom: .2em;";
    protected $bShowPreTitle = true;
    public function ShowPreTitle($bVal = true)
    {
        $this->bShowPreTitle = $bVal;
    }
    public function SetPreTitle($val = "")
    {
        $this->sPreTitle = $val;
    }
    public function GetPreTitle()
    {
        return $this->sPreTitle;
    }

    #stato dell'item (bozza, pubblicata, revisionata, etc.)
    protected $sStatus = "";
    protected $sStatusStyle = "width:100%";
    protected $bShowStatus = true;

    #dettagli (data ultimo aggiornamento, utente, etc.)
    protected $sDetails = "";
    protected $sDetailsStyle = "width:100%; margin-bottom: .3em;";
    protected $bShowDetails = true;

    #tags
    protected $aTags = array();
    protected $aTagsStyle = "width:100%";
    protected $bShowTags = false;

    #utente
    protected $oUser = null;

    #Gestione del command box content
    protected $sHeaderCommandBoxContent = "";
    public function GetHeaderCommandBoxContent()
    {
        return $this->sHeaderCommandBoxContent;
    }
    public function SetHeaderCommandBoxContent($val = "")
    {
        $this->sHeaderCommandBoxContent = $val;
    }

    #Costruttore standard
    public function __construct($Title = "", $SubTitle = "", $Content = null, $user = null)
    {
        $this->sTitle = $Title;
        $this->sSubTitle = $SubTitle;
        if ($Content != null) $this->SetContent($Content);
        if ($user instanceof AA_User && $user->isCurrentUser()) $this->oUser = $user;
        else $this->oUser = AA_User::GetCurrentUser();
    }

    #Gestisce lo style dell'HeaderBox
    protected $sHeaderBoxStyle = "display: flex; flex-direction: row; justify-content: space-between; width:100%";
    public function GetHeaderBoxStyle()
    {
        return $this->sHeaderBoxStyle;
    }
    public function SetHeaderBoxStyle($val = "display: flex; flex-direction: row; justify-content: space-between; width:100%")
    {
        $this->sHeaderBoxStyle = $val;
    }

    #Ordine dell'item nella lista
    protected $nIndex = 0;
    public function SetIndex($val)
    {
        $this->nIndex = $val;
    }

    #Restituisce l'header view dell'item in html
    protected $sHeaderPreviewBoxStyle = "display: flex; flex-direction: column; justify-content: space-between; align-items: center; width:80%; font-size: .8em";
    protected $sHeaderCommandBoxStyle = "display: flex; flex-direction: row; justify-content: space-between; align-items: center; width:19%; font-size: .8em";
    protected $sTagsStyle="";
    protected $sTags="";
    public function HeaderView()
    {
        $HeaderBox = "<h3 class='" . $this->sClass . "_HeaderBox' id-object='" . $this->sId . "' order='" . $this->nIndex . "'><div style='" . $this->sHeaderBoxStyle . "'>";

        #inserisce l'header preview box
        $HeaderBox .= "<div class='" . $this->sClass . "_HeaderPreviewBox' style='" . $this->sHeaderPreviewBoxStyle . "'>";

        #Inserisce il preTitolo
        if ($this->bShowPreTitle && $this->sPreTitle != "") {
            $HeaderBox .= "<div class='" . $this->sClass . "_HeaderSubTitleBox' style='" . $this->sPreTitleStyle . "'>";
            $HeaderBox .= $this->sPreTitle;
            $HeaderBox .= "</div>";
        }

        #Inserisce il titolo
        if ($this->bShowTitle) {
            $HeaderBox .= "<div class='" . $this->sClass . "_HeaderTitleBox' style='" . $this->sTitleStyle . "'>";
            $HeaderBox .= $this->sTitle;
            $HeaderBox .= "</div>";
        }

        #Inserisce il sotto titolo
        if ($this->bShowSubTitle && $this->sSubTitle != "") {
            $HeaderBox .= "<div class='" . $this->sClass . "_HeaderSubTitleBox' style='" . $this->sSubTitleStyle . "'>";
            $HeaderBox .= $this->sSubTitle;
            $HeaderBox .= "</div>";
        }

        #Inserisce i dettagli
        if ($this->bShowDetails && $this->bShowDetails != "") {
            $HeaderBox .= "<div class='" . $this->sClass . "_HeaderDetailBox' style='" . $this->sDetailsStyle . "'>";
            $HeaderBox .= $this->sDetails;
            $HeaderBox .= "</div>";
        }

        #Inserisce lo status
        if ($this->bShowStatus && $this->bShowStatus != "") {
            $HeaderBox .= "<div class='" . $this->sClass . "_HeaderSubTitleBox' style='" . $this->sStatusStyle . "'>";
            $HeaderBox .= $this->sStatus;
            $HeaderBox .= "</div>";
        }

        #Inserisce i tags
        if ($this->bShowTags && $this->bShowTags != "") {
            $HeaderBox .= "<div class='" . $this->sClass . "_HeaderTagsBox' style='" . $this->sTagsStyle . "'>";
            $HeaderBox .= $this->sTags;
            $HeaderBox .= "</div>";
        }

        #Chiude l'header preview box
        $HeaderBox .= "</div>";

        #Inserisce il command box
        $HeaderBox .= "<div class='" . $this->sClass . "_HeaderCommandBox' style='" . $this->sHeaderCommandBoxStyle . "'>";

        $HeaderBox .= $this->sHeaderCommandBoxContent;

        $HeaderBox .= "</div>";

        #Chiude l'headerbox e l'header tag
        $HeaderBox .= "</div></h3>";

        //AA_Log::Log(get_class()."->HeaderView(): ".$HeaderBox,100,true,true);
        return $HeaderBox;
    }
    #---------------------------------------------------------------------

    #contenuto dell'item
    protected $sContent = null;
    public function SetContent($newContent)
    {
        $this->sContent = $newContent;
    }
    public function GetContent()
    {
        return $this->sContent;
    }

    #Restituisce il content view dell'item
    protected $sContentBoxStyle = "display: flex; flex-direction: column; justify-content: space-between; padding: 0.1em; font-size: 0.9em;";
    public function ContentView()
    {
        $ContentBox = "<div class='" . $this->sClass . "_ContentBox' id-object='" . $this->sId . "' style='" . $this->sContentBoxStyle . "'>";
        $ContentBox .= $this->sContent;
        $ContentBox .= "</div>";

        return $ContentBox;
    }
    #----------------------------------------------------------------------
}
#---------------------------------------------------------

#Classe per la gestione della vista della lista degli item dell'accordion
class AA_GenericAccordionTemplateView
{
    #header box
    private $oHeaderBox = null;
    private $bShowHeader = true;
    private $sHeaderBoxStyle = "width: 100%; height: 5%";

    #content box
    private $oContentBox = null;
    private $bShowContent = true;
    private $sContentBoxStyle = "width: 100%";

    #footer box
    private $oFooterBox = null;
    private $bShowFooter = true;
    private $sFooterBoxStyle = "width: 100%; height: 5%";

    #costruttore
    public function __construct()
    {
        $this->oHeaderBox = new AA_GenericBoxTemplateView();
        $this->oContentBox = new AA_GenericBoxTemplateView();
        $this->oFooterBox = new AA_GenericBoxTemplateView();
    }

    #restituisce l'header della lista
    private $sHeader = "";
    public function SetHeaderContent($newContent)
    {
        $this->sHeader = $newContent;
    }
    public function GetHeaderContent()
    {
        return $this->sHeader;
    }
    public function HeaderView()
    {
        #Custom header
        $this->oHeaderBox->SetContent($this->sHeader);
        $this->oHeaderBox->SetStyle($this->sHeaderBoxStyle);
        return $this->oHeaderBox->ContentView();
    }

    #Restituisce la visualizzazione del contenuto dell'accordion
    private $sContent = "";
    public function SetContent($newContent)
    {
        $this->sContent = $newContent;
    }
    public function GetContent()
    {
        return $this->sContent;
    }
    public function ContentView($boxed = true)
    {
        $custom_content = "";

        #Custom content
        $custom_content .= $this->sContent;

        $accordion_content = "";
        foreach ($this->aItems as $curItem) {
            $accordion_content .= $curItem->HeaderView() . $curItem->ContentView();
        }

        if ($boxed) {
            #accordion box
            $this->oContentBox->SetContent($custom_content . $accordion_content);
            $this->oContentBox->SetStyle($this->sContentBoxStyle);
            return $this->oContentBox->ContentView();
        } else {
            return $custom_content . $accordion_content;
        }
    }

    #restituisce il footer della lista
    private $sFooter = "";
    public function SetFooter($newContent)
    {
        $this->sFooter = $newContent;
    }
    public function GetFooter()
    {
        return $this->sFooter;
    }
    public function FooterView()
    {
        #Custom footer
        $this->oFooterBox->SetContent($this->sFooter);
        $this->oFooterBox->SetStyle($this->sFooterBoxStyle);
        return $this->oFooterBox->ContentView();
    }

    #Aggiunge un oggetto alla lista degli item gestiti
    private $aItems = array();
    public function AddItem($newObject = null)
    {
        if ($newObject instanceof AA_GenericAccordionItemTemplateView) {
            $newObject->SetIndex(count($this->aItems));
            $this->aItems[] = $newObject;
        } else {
            AA_Log::Log(get_class() . "->AddObject() - oggetto non valido", 100, true, true);
            return false;
        }
        return true;
    }

    #Restituisce l'array degli item gestiti
    public function GetItems()
    {
        return $this->aItems;
    }
}

#Classe per i template view
class AA_GenericObjectTemplateView extends AA_GenericBoxTemplateView
{
    protected $obj=null;

    //costruttore
    public function __construct($id = "", $parent = null, $obj = null)
    {
        if ($id == "") $id = get_class();

        //error_log(__METHOD__."(".$id.")");

        parent::__construct("", $id, $parent);

        //Imposta l'oggetto
        if ($obj instanceof AA_Object) $this->obj = $obj;
        //else $this->obj=new AA_Object();
    }

    //oggetto collegato
    private $oObject = null;
    public function SetObject($obj = null)
    {
        $this->oObject = null;
        if ($obj instanceof AA_Object) $this->oObject = $obj;
    }
    public function GetObject()
    {
        return $this->oObject;
    }
}

//Classe per la gestione dei form
class AA_GenericFormTemplateView extends AA_GenericObjectTemplateView
{
    //Dimensione del box label
    private $nLabelFieldBoxSize = "25%";
    public function SetLabelFieldBoxSize($val = "25%")
    {
        $this->nLabelFieldBoxSize = $val;
    }
    public function GetLabelFieldBoxSize()
    {
        return $this->nLabelFieldBoxSize;
    }

    //Dimensione del box field
    private $nFieldBoxSize = "75%";
    public function SetFieldBoxSize($val = "75%")
    {
        $this->nFieldBoxSize = $val;
    }
    public function GetFieldBoxSize()
    {
        return $this->nFieldBoxSize;
    }

    private $oContentBox = null;
    public function __construct($id = "", $parent = null, $obj = null)
    {
        parent::__construct($id, $parent, $obj);

        $this->sInnerClass = get_class();

        $form = new AA_XML_Form_Element($id . "_Form", $this);
        $form->SetClass("form-data");
        $this->oContentBox = new AA_XML_Div_Element($id . "_Form_ContentBox", $form);
        $this->oContentBox->SetStyle("display:flex; flex-direction: column; justify-content: space-between; width:99%");
    }

    //Aggiunge un campo al form
    public function AddField($field_label = "Campo generico", $field_id = "id_campo", $field_type = "text", $field_value = "", $values = null, $field_notes = "", $field_style = "", $field_class = "")
    {
        $row = new AA_XML_Div_Element($this->oContentBox->GetId() . "_Row_" . $field_id, $this->oContentBox);
        $row->SetStyle("display: flex; justify-content: space-between; margin-bottom: 1em; width:99%");

        $label = new AA_XML_Div_Element($this->oContentBox->GetId() . "_Label_" . $field_id, $row);
        $label->SetStyle("width: " . $this->GetLabelFieldBoxSize());
        $label->SetText($field_label);

        $field_box = new AA_XML_Div_Element($this->oContentBox->GetId() . "_FieldBox_" . $field_id, $row);
        $field_box->SetStyle("width: " . $this->GetFieldBoxSize());
        switch ($field_type) {
            default:
            case "text":
                $field = new AA_XML_Input_Element($field_id, $field_box);
                $field->SetAttribute("name", $field_id);
                if ($field_style != "") $field->SetStyle($field_style);
                if ($field_value != "") $field->SetAttribute("value", $field_value);
                $field->SetClass("text ui-widget-content ui-corner-all " . $field_class);
                break;

            case "file":
                $field = new AA_XML_Input_Element($field_id, $field_box);
                $field->SetAttribute("name", $field_id);
                $field->SetAttribute("type", "file");
                if ($field_style != "") $field->SetStyle($field_style);
                if ($field_value != "") $field->SetAttribute("value", $field_value);
                $field->SetClass("file ui-widget-content ui-corner-all " . $field_class);
                break;

            case "select":
                $field = new AA_XML_Select_Element($field_id, $field_box);
                if ($field_style != "") $field->SetStyle($field_style);
                $field->SetAttribute("name", $field_id);
                $field->SetClass("select ui-widget-content ui-corner-all " . $field_class);
                if (is_array($values)) {
                    foreach ($values as $key => $curValue) {
                        $option = new AA_XML_Option_Element($field_id . "_Option_" . $key, $field);
                        $option->SetAttribute("value", $key);
                        $option->SetText($curValue);
                        if ($key == $field_value) $option->SetAttribute("selected", "true");
                    }
                }
                break;

            case "textarea":
            case "text-area":
                $field = new AA_XML_Textarea_Element($field_id, $field_box);
                $field->SetAttribute("name", $field_id);
                if ($field_style != "") $field->SetStyle($field_style);
                if ($field_value != "") $field->SetText($field_value);
                $field->SetClass("textarea ui-widget-content ui-corner-all " . $field_class);
                break;

            case "checkbox":
                $field = new AA_XML_Input_Element($field_id, $field_box);
                $field->SetAttribute("name", $field_id);
                if ($field_style != "") $field->SetStyle($field_style);
                if ($field_value != "") $field->SetAttribute("value", $field_value);
                if ($values == true) $field->SetAttribute("checked", "true");
                $field->SetAttribute("type", "checkbox");
                $field->SetClass("checkbox ui-widget-content ui-corner-all " . $field_class);
                break;
        }
        if ($field_notes != "") {
            $field_box->SetText("<div style='font-size: smaller'>" . $field_notes . "</div>", false);
        }
    }

    //Aggiunge un campo di testo semplice
    public function AddTextInput($field_label = "Campo generico", $field_id = "id_campo", $field_value = "", $field_notes = "", $field_style = "", $field_class = "")
    {
        if ($field_style == "") $field_style = "width: 99%";
        return $this->AddField($field_label, $field_id, "text", $field_value, null, $field_notes, $field_style, $field_class);
    }

    //Aggiunge un campo check box
    public function AddCheckBoxInput($field_label = "Campo generico", $field_id = "id_campo", $field_value = "", $isChecked = false, $field_notes = "", $field_style = "", $field_class = "")
    {
        return $this->AddField($field_label, $field_id, "checkbox", $field_value, $isChecked, $field_notes, $field_style, $field_class);
    }

    //Aggiunge un campo di textarea semplice
    public function AddTextareaInput($field_label = "Campo generico", $field_id = "id_campo", $field_value = "", $field_notes = "", $field_style = "", $field_class = "")
    {
        if ($field_style == "") $field_style = "width: 99%";
        return $this->AddField($field_label, $field_id, "textarea", $field_value, null, $field_notes, $field_style, $field_class);
    }

    //Aggiunge un campo per l'input di un file
    public function AddFileInput($field_label = "Documento", $field_id = "file-upload", $field_value = "", $field_notes = "<br>Selezionare solo file pdf firmati digitalmente in modalità PADES e inferiori a 2 Mbyte.", $field_style = "", $field_class = "")
    {
        if ($field_style == "") $field_style = "width: 99%";
        return $this->AddField($field_label, $field_id, "file", $field_value, null, $field_notes, $field_style, $field_class);
    }

    //Aggiunge un campo di combo a discesa
    public function AddSelectInput($field_label = "Campo generico", $field_id = "id_campo", $field_value = "", $field_values = null, $field_notes = "", $field_style = "", $field_class = "")
    {
        return $this->AddField($field_label, $field_id, "select", $field_value, $field_values, $field_notes, $field_style, $field_class);
    }

    //Aggiunge un campo di date picker
    public function AddDateInput($field_label = "Campo generico", $field_id = "id_campo", $field_value = "", $field_notes = "", $field_style = "", $field_class = "")
    {
        if ($field_style == "") $field_style = "width: 25%";
        $field_class .= " AA_DatePicker";
        return $this->AddField($field_label, $field_id, "text", $field_value, null, $field_notes, $field_style, $field_class);
    }

    //Aggiunge un campo nascosto
    public function AddHiddenField($field_id = "id_campo", $field_value = "")
    {
        //AA_Log::Log(__METHOD__." - parent: ".$this->oContentBox->GetParent(),100,false,true);

        $input = new AA_XML_Input_Element($field_id, $this->oContentBox->GetParent());
        $input->SetAttribute("type", "hidden");
        $input->SetAttribute("value", $field_value);
        $input->SetAttribute("name", $field_id);
    }
}

//Classe generic template list
class AA_GenericTableTemplateView extends AA_GenericObjectTemplateView
{
    //righe
    protected $aRows = array();
    public function GetRowsCount()
    {
        return sizeof($this->aRows);
    }
    public function GetRows()
    {
        return $this->aRows;
    }
    public function GetRow($RowNumber = 0)
    {
        if (isset($this->aRows[$RowNumber])) return $this->aRows[$RowNumber];

        return null;
    }
    public function AddRow()
    {
        $row = count($this->aRows);
        $this->aRows[$row] = new AA_GenericTableRowTemplateView("AA_GenericTableRowTemplateView_" . $row, $this);
        $this->aRows[$row]->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; width:100%; border-bottom: 1px solid black");
        $this->aRows[$row]->SetClass("AA_GenericTableRowTemplateView_evidenzia");

        return $this->aRows[$row];
    }

    //Colonne
    private $aColsSizes = array();
    private $aColsLabels = array();
    private $nCols = 1;
    public function SetColLabels($labels)
    {
        if (is_array($labels)) $this->aColsLabels = $labels;
    }
    public function GetColSize($nCol = 0)
    {
        if (isset($this->aColsSizes[$nCol])) return $this->aColsSizes[$nCol];
        return 0;
    }

    public function SetColSize($nCol = 0, $size = 1)
    {
        if (isset($this->aColsSizes[$nCol])) $this->aColsSizes[$nCol] = $size;
    }

    //Imposta l'header della tabella
    public function SetColSizes($sizes = null)
    {
        if (is_array($sizes)) {
            $i = 0;
            $this->nCols = count($sizes);
            $this->aColsSizes = array();
            foreach ($sizes as $nCol => $size) {
                if ($i < $this->nCols) $this->aColsSizes[$i] = $size;
                $i++;
            }
        }

        //AA_Log::Log(__METHOD__." - numero di colonne. (".$this->nCols.")",100,false,true);
    }

    //Imposta il testo dell'header delle colonne
    public function SetHeaderLabels($labels = null)
    {
        if (is_array($labels)) {
            $i = 0;
            foreach ($labels as $curLabel) {
                if ($i < $this->nCols) $this->aColsLabels[$i] = $curLabel;
                $this->SetCellText(0, $i, $curLabel, "center");
                $i++;
            }
        }
    }

    //Celle
    protected $aCells = array();
    public function GetCell($row = 0, $col = 0)
    {
        if ($col < count($this->aColsSizes)) {
            if (isset($this->aCells[$row . "_" . $col])) return $this->aCells[$row . "_" . $col];
            else {
                if (isset($this->aRows[$row])) {
                    $this->aCells[$row . "_" . $col] = new AA_GenericTableCellTemplateView("AA_GenericTableCellTemplateView_" . $row . "_" . $col, $this->aRows[$row]);
                    return $this->aCells[$row . "_" . $col];
                } else {
                    $this->aRows[$row] = new AA_GenericTableRowTemplateView("AA_GenericTableRowTemplateView_" . $row, $this);
                    $this->aRows[$row]->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; width:100%; border-bottom: 1px solid $this->defaultBorderColor");
                    $this->aRows[$row]->SetClass("AA_GenericTableRowTemplateView_evidenzia");

                    $this->aCells[$row . "_" . $col] = new AA_GenericTableCellTemplateView("AA_GenericTableCellTemplateView_" . $row . "_" . $col, $this->aRows[$row]);

                    return $this->aCells[$row . "_" . $col];
                }
            }
        }

        AA_Log::Log(__METHOD__ . " - indice di colonna oltre il massimo impostato. (" . $col . ")", 100, false, true);

        return null;
    }

    //colore di default del bordo
    protected $defaultBorderColor = "black";

    //colore di sfondo della riga di intestazione
    protected $h_bgcolor = "rgb(215, 215, 215)";

    public function __construct($id = "", $parent = null, $obj = null, $props = null)
    {
        if ($id == "") $id = get_class();

        parent::__construct($id, $parent, $obj);

        $this->SetStyle("display:flex; flex-direction: column; justify-content: space-between");

        //colore di default del bordo
        $this->defaultBorderColor = "black";

        //colore di sfondo della riga di intestazione
        $this->h_bgcolor = "rgb(215, 215, 215)";

        //proprietà
        if (is_array($props)) {
            //Imposta lo stile
            if (isset($props["style"])) {
                $this->SetStyle($props["style"], true);
            }
            
            if (isset($props["col_sizes"])) $this->SetColSizes($props["col_sizes"]);
            if (isset($props["col_label"])) $this->SetColLabels($props["col_label"]);
            if (isset($props["align-items"])) $this->SetStyle("align-items: " . $props["align-items"], true);
            else $this->SetStyle("align-items: center", true);
            if (isset($props["width"])) $this->SetStyle("width: " . $props["width"], true);
            else $this->SetStyle("width: 100%", true);

            if (isset($props["default-border-color"])) $this->defaultBorderColor = $props["default-border-color"];

            //bordo
            if (isset($props["border"])) {
                $this->SetStyle("border: " . $props["border"], true);
                $this->bBorder = true;
            }

            //titolo
            if (isset($props["title"])) $this->SetText("<div style='width:100%; font-size: 16px; font-weight: bold; border-bottom: 1px solid $this->defaultBorderColor'>" . $props["title"] . "</div>", true);

            //evidenzia le righe
            if (isset($props["evidentiate-rows"]) && $props["evidentiate-rows"]) $this->bEvidenziateRows = true;
            else $this->bEvidenziateRows = false;

            //colore di sfondo dell'intestazione
            if (isset($props['h_bgcolor'])) $this->h_bgcolor = $props['h_bgcolor'];
        } else {
            $this->SetStyle("align-items: center; width: 100%", true);
        }

        //Riga di intestazione
        $this->aRows[0] = new AA_XML_Div_Element($id . "_header", $this);
        $this->aRows[0]->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; width: 100%; font-weight: bold; border-bottom: 1px solid $this->defaultBorderColor; background-color: $this->h_bgcolor;");
    }

    protected $cellPadding="";
    public function GetCellPadding()
    {
        return $this->cellPadding;
    }
    public function SetCellPadding($val)
    {
        $this->cellPadding=$val;
    }

    //Imposta il contenuto di una cella
    public function SetCellText($row = 1, $col = 1, $content = "", $alignment = "left", $color = "", $grassetto = "",$padding="",$bgColor='')
    {
        $cell = $this->GetCell($row, $col);
        if ($cell instanceof AA_XML_Div_Element) {
            $cell->SetText($content);
            if (strpos($cell->GetStyle(), "text-align:") !== false) $cell->SetStyle(preg_replace("/(text-align:\ [center|left|right];)+/", "text-align: $alignment;", $cell->GetStyle()));
            else $cell->SetStyle("text-align: $alignment;", true);

            if ($color != "") {
                if (strpos("color:", $cell->GetStyle()) !== false) $cell->SetStyle(preg_replace("/(color:\ [.*];)+/", "color: $color;", $cell->GetStyle()));
                else $cell->SetStyle("color: $color", true);
            }

            if ($grassetto != "") {
                if (strpos($cell->GetStyle(),"font-weight:") !== false) $cell->SetStyle(preg_replace("/(font-weight:\ [.*];)+/", "font-weight: bold;", $cell->GetStyle()));
                else $cell->SetStyle("font-weight: bold", true);
            }

            if($padding=='' && $this->cellPadding !="") $padding=$this->cellPadding;
            if($padding!="")
            {
                if(strpos($cell->GetStyle(), "padding:") !== false)
                {
                    $cell->SetStyle(preg_replace("/(padding:\ [.*];)+/", "padding: ".$padding.";", $cell->GetStyle()));
                }
                else $cell->SetStyle("padding: ".$padding, true);
            }

            if($bgColor!="")
            {
                if(strpos($cell->GetStyle(), "background-color:") !== false)
                {
                    $cell->SetStyle(preg_replace("/(background-color:\ [.*];)+/", "background-color: ".$bgColor.";", $cell->GetStyle()));
                }
                else $cell->SetStyle("background-color: ".$bgColor, true);
            }

            return true;
        }

        return false;
    }

    //Restituisce il testo di una cella
    public function GetCellText($row = 1, $col = 1)
    {
        $cell = $this->GetCell($row, $col);
        if ($cell instanceof AA_XML_Div_Element) {
            return $cell->GetText();
        }

        return "";
    }

    //Imposta il contenuto da renderizzare prima del rendering dei figli
    private $oHeader = null;
    public function SetHeader($header = null)
    {
        if ($header instanceof AA_XML_Element_Generic) {
            if ($this->oHeader instanceof AA_XML_Element_Generic) $this->oHeader->SetParent(null);
            $this->oHeader = $header;
            $this->oHeader->SetParent($this);
        } else {
            $this->oHeader = new AA_GenericBoxTemplateView($header, get_class() . "_" . $this->GetId() . "_header", $this);
        }
    }
    public function GetHeader()
    {
        return $this->oHeader;
    }

    //Imposta il contenuto dell'header
    public function SetHeaderContent($val = "", $bConvertEntities = false)
    {
        if ($this->oHeader instanceof AA_XML_Element_Generic) {
            if ($bConvertEntities) $this->oHeader->SetText(htmlentities($val));
            else $this->oHeader->SetText($val);
        }
    }

    //Imposta il contenuto da renderizzare il rendering dei figli
    private $oFooter = null;
    public function SetFooter($footer = null)
    {
        if ($footer instanceof AA_XML_Element_Generic) {
            if ($this->oFooter instanceof AA_XML_Element_Generic) $this->oFooter->SetParent(null);
            $this->oFooter = $footer;
            $this->oFooter->SetParent($this);
        } else {
            $this->oFooter = new AA_GenericBoxTemplateView($footer, get_class() . "_" . $this->GetId() . "_footer", $this);
        }
    }
    public function GetFooter()
    {
        return $this->oFooter;
    }

    //Imposta il contenuto del footer
    public function SetFooterContent($val = "", $bConvertEntities = false)
    {
        if ($this->oFooter instanceof AA_XML_Element_Generic) {
            if ($bConvertEntities) $this->oFooter->SetText(htmlentities($val));
            else $this->oFooter->SetText($val);
        }
    }

    protected $bEvidenziateRows=false;
    protected $bBorder=false;

    //funzione di normalizzazione (aggiunge le celle mancanti e fa altri controlli di coerenza)
    protected function Normalize()
    {
        //Calcola la dimensione (relativa) totale impostata per le singole colonne
        $totalsize = 0;
        foreach ($this->aColsSizes as $curSize) {
            if ($curSize > 0) $totalsize += $curSize;
        }

        $curIndexRow = 0;

        //Scandisce tutte le righe, aggiunge le celle mancanti, impostando la dimensione effettiva
        foreach ($this->aRows as $row => $curRow) {
            if ($this->bEvidenziateRows && $curIndexRow > 0) {
                if (!($curIndexRow % 2)) $bgColor = "background-color: #f5f5f5;";
                else $bgColor = "";
                $curRow->SetStyle($bgColor, true);
            }
            $curIndexRow++;

            //Rimuove il bordo dell'ultima riga se la tabella ha il bordo e non c'è testo dopo l'ultima riga
            if ($this->bBorder && $curIndexRow == $this->GetRowsCount() && $this->GetText(false) == "") {
                $curRow->SetStyle(preg_replace("/(border-bottom:\ 1px\ solid\ black)+/", "", $curRow->GetStyle()));
            }

            for ($col = 0; $col < count($this->aColsSizes); $col++) {
                //AA_Log::Log("normalizzo la cella: ".$row." - ".$col, 100,false,true);

                $newSize = round($this->aColsSizes[$col] * 100 / $totalsize);

                if ($this->aCells[$row . "_" . $col] instanceof AA_XML_Element_Generic) {
                    if (strpos("width:", $this->aCells[$row . "_" . $col]->GetStyle()) !== false) $this->aCells[$row . "_" . $col]->SetStyle(preg_replace("/(width:\ [0-9]+)+/", "width: " . $newSize, $this->aCells[$row . "_" . $col]->GetStyle()));
                    else $this->aCells[$row . "_" . $col]->SetStyle("width: " . $newSize . "%", true);
                } else {
                    $this->aCells[$row . "_" . $col] = new AA_GenericTableCellTemplateView("AA_GenericTableCellTemplateView_" . $row . "_" . $col, $curRow);
                    $this->aCells[$row . "_" . $col]->SetStyle("width: " . $newSize . "%");
                }
            }
        }

        //Nascondi l'intestazione se c'è solo una riga
        if (count($this->aRows) == 1) $this->aRows[0]->Hide();
        else $this->aRows[0]->Show();
    }

    //Funzione di renderizzazione
    public function __toString()
    {
        $this->Normalize();
        return parent::__toString();
    }
}

//Classe per la gestione delle righe di una tabella
class AA_GenericTableRowTemplateView extends AA_XML_Div_Element
{
    public function __construct($id = "", $parent = null)
    {
        if ($id == "") $id = get_class();
        parent::__construct($id, $parent);
    }
}

//Classe per la gestione delle celle di una tabella
class AA_GenericTableCellTemplateView extends AA_XML_Div_Element
{
    //Dimensione relativa della cella
    private $nSize = 1;
    public function GetSize()
    {
        return $this->nSize;
    }
    public function SetSize($size = 1)
    {
        if ($size > 1 && $size < 100) $this->nSize = $size;
    }

    public function __construct($id = "", $parent = null, $size = 1)
    {
        if ($id == "") $id = get_class();
        parent::__construct($id, $parent);

        $this->SetSize($size);
    }
}
