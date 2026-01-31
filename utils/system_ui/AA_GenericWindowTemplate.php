<?php
class AA_GenericWindowTemplate
{
    protected $id = "AA_TemplateGenericWnd";
    public function SetId($id = "")
    {
        if ($id != "")
            $this->id = $id;
    }

    public function GetId()
    {
        return $this->id;
    }

    public function GetWndId()
    {
        return $this->id . "_Wnd";
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
    public function __construct($id = "", $title = "", $module = "", $bodyProps = null)
    {
        if ($id != "")
            $this->id = $id;
        if ($title != "")
            $this->title = $title;

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->module = $module;

        $script = 'try{if($$(\'' . $this->id . '_Wnd\').config.fullscreen){webix.fullscreen.exit();$$(\'' . $this->id . '_btn_resize\').define({icon:"mdi mdi-fullscreen", tooltip:"Mostra la finestra a schermo intero"});$$(\'' . $this->id . '_btn_resize\').refresh();}else{webix.fullscreen.set($$(\'' . $this->id . '_Wnd\'));$$(\'' . $this->id . '_btn_resize\').define({icon:"mdi mdi-fullscreen-exit", tooltip:"Torna alla visualizzazione normale"});$$(\'' . $this->id . '_btn_resize\').refresh();}}catch(msg){console.error(msg);}';

        if (!is_array($bodyProps))
            $bodyProps = array("type" => "clean");
        if (!isset($bodyProps['type']))
            $bodyProps['type'] = "clean";

        $this->body = new AA_JSON_Template_Layout($this->id . "_Content_Box", $bodyProps);
        $this->head = new AA_JSON_Template_Generic($this->id . "_head", array(
            "css" => "AA_Wnd_header_box",
            "view" => "toolbar",
            "height" => "38",
            "elements" => array(
                array("id" => $this->id . "_Title", "css" => "AA_Wnd_title", "template" => $this->title),
                array("id" => $this->id . "_btn_resize", "view" => "icon", "icon" => "mdi mdi-fullscreen", "css" => "AA_Wnd_btn_fullscreen", "width" => 24, "height" => 24, "tooltip" => "Mostra la finestra a schermo intero", "click" => $script),
                array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "hotkey" => "esc", "css" => "AA_Wnd_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi la finestra", "click" => "try{if($$('" . $this->id . "_Wnd').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Wnd').close();}catch(msg){console.error(msg)}")
            )
        )
        );

        $this->wnd = new AA_JSON_Template_Generic($this->id . "_Wnd", array(
            "view" => "window",
            "height" => $this->height,
            "width" => $this->width,
            "position" => "center",
            "modal" => $this->modal,
            "move" => true,
            "resize" => true,
            "css" => "AA_Wnd"
        )
        );

        $this->wnd->SetProp("head", $this->head);
        $this->wnd->SetProp("body", $this->body);
    }

    protected function Update()
    {
        $this->wnd->setProp("height", $this->height);
        $this->wnd->setProp("width", $this->width);
        $this->wnd->setProp("modal", $this->modal);

        if($this->body->GetRowsCount()==0)
        {
            $this->body->AddRow(new AA_JSON_Template_Generic());
        }
    }

    protected $width = "1280";
    public function SetWidth($width = "1280")
    {
        if ($width > 0)
            $this->width = $width;
    }
    public function GetWidth()
    {
        return $this->width;
    }

    protected $height = "720";
    public function SetHeight($height = "720")
    {
        if ($height > 0)
            $this->height = $height;
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

        if ($view instanceof AA_JSON_Template_Generic)
            $this->body->AddRow($view);
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
