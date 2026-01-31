<?php
class AA_GenericPopupTemplate
{
    protected $id = "AA_TemplateGenericPopup";
    public function SetId($id = "")
    {
        if ($id != "")
            $this->id = $id;
    }

    public function GetId()
    {
        return $this->id;
    }

    public function GetPopupId()
    {
        return $this->id . "_Popup";
    }

    protected $body = "";
    public function GetContent()
    {
        return $this->body;
    }

    protected $head = "";
    protected $wnd = "";

    protected $title = "";
    public function SetTitle($val = "")
    {
        $this->title = $val;
    }
    public function GetTitle()
    {
        return $this->title;
    }

    //content css
    protected $css = "";
    public function SetCss($val = "")
    {
        $this->css = $val;
    }

    protected $bClose = true;
    public function Enableclose($val = true)
    {
        if ($val)
            $this->bClose = true;
        else
            $this->bClose = false;
    }

    protected $closePosition = 2; //0 top-left, 1 top-center, 2: top-right (default), 3: bottom-left, 4: bottom-center, 5 bottom-right
    public function SetClosePosition($val = 2)
    {
        if (intval($val) >= 0 && intval($val) <= 5) {
            $this->closePosition = $val;
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
        if ($id != "")
            $this->id = $id;

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->module = $module;

        $this->body = new AA_JSON_Template_Layout($this->id . "_Content_Box", array("type" => "clean", "padding" => 5));

        $this->wnd = new AA_JSON_Template_Generic($this->id . "_Popup", array(
            "view" => "popup",
            "height" => $this->height,
            "width" => $this->width,
            "modal" => $this->modal,
            "position" => "center",
            "css" => "AA_Popup"
        )
        );
    }

    protected function Update()
    {
        $this->wnd->setProp("height", $this->height);
        $this->wnd->setProp("width", $this->width);
        $this->wnd->setProp("modal", $this->modal);

        if ($this->bClose && $this->closePosition < 3) {
            if ($this->closePosition == 0) {

                if ($this->title != "") {
                    $this->head = new AA_JSON_Template_Generic($this->id . "_head", array(
                        "css" => "AA_Popup_header_box",
                        "type" => "clean",
                        "view" => "toolbar",
                        "height" => "38",
                        "elements" => array(
                            array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                            array("view" => "label", "label" => $this->title, "align" => "center"),
                        )
                    )
                    );
                } else {
                    $this->head = new AA_JSON_Template_Generic($this->id . "_head", array(
                        "css" => "AA_Popup_header_box",
                        "type" => "clean",
                        "view" => "toolbar",
                        "height" => "38",
                        "elements" => array(
                            array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                            array("view" => "spacer"),
                        )
                    )
                    );
                }
            }
            if ($this->closePosition == 1) {
                if ($this->title != "")
                    $this->body->addRow(new AA_JSON_Template_Generic($this->id . "_Title", array("view" => "label", "label" => $this->title, "align" => "center")));
                $this->head = new AA_JSON_Template_Generic($this->id . "_head", array(
                    "css" => "AA_Popup_header_box",
                    "type" => "clean",
                    "view" => "toolbar",
                    "height" => "38",
                    "elements" => array(
                        array("view" => "spacer"),
                        array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                        array("view" => "spacer")
                    )
                )
                );
            }
            if ($this->closePosition == 2) {
                if ($this->title != "") {
                    $this->head = new AA_JSON_Template_Generic($this->id . "_head", array(
                        "css" => "AA_Popup_header_box",
                        "type" => "clean",
                        "view" => "toolbar",
                        "height" => "38",
                        "elements" => array(
                            array("view" => "label", "label" => $this->title, "align" => "center"),
                            array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                        )
                    )
                    );
                } else {
                    $this->head = new AA_JSON_Template_Generic($this->id . "_head", array(
                        "css" => "AA_Popup_header_box",
                        "type" => "clean",
                        "view" => "toolbar",
                        "height" => "38",
                        "elements" => array(
                            array("view" => "spacer"),
                            array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "hotkey" => "esc", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                        )
                    )
                    );
                }
            }

            if ($this->head instanceof AA_JSON_Template_Generic)
                $this->body->addRow($this->head);
        } else {
            if ($this->title != "")
                $this->body->addRow(new AA_JSON_Template_Generic($this->id . "_Title", array("view" => "label", "label" => $this->title, "align" => "center")));
        }

        //inserisce i figli
        foreach ($this->children as $curChild) {
            $this->body->addRow($curChild);
        }

        if ($this->bClose && $this->closePosition >= 3) {
            if ($this->closePosition == 3) {
                $this->head = new AA_JSON_Template_Generic($this->id . "_head", array(
                    "css" => "AA_Popup_header_box",
                    "type" => "clean",
                    "view" => "toolbar",
                    "height" => "38",
                    "elements" => array(
                        array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                        array("view" => "spacer")
                    )
                )
                );
            }
            if ($this->closePosition == 4) {
                $this->head = new AA_JSON_Template_Generic($this->id . "_head", array(
                    "css" => "AA_Popup_header_box",
                    "type" => "clean",
                    "view" => "toolbar",
                    "height" => "38",
                    "elements" => array(
                        array("view" => "spacer"),
                        array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}"),
                        array("view" => "spacer")
                    )
                )
                );
            }
            if ($this->closePosition == 5) {
                $this->head = new AA_JSON_Template_Generic($this->id . "_head", array(
                    "css" => "AA_Popup_header_box",
                    "type" => "clean",
                    "view" => "toolbar",
                    "height" => "38",
                    "elements" => array(
                        array("view" => "spacer"),
                        array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Popup_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi", "click" => "try{if($$('" . $this->id . "_Popup').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Popup').close();}catch(msg){console.error(msg)}")
                    )
                )
                );
            }

            if ($this->head instanceof AA_JSON_Template_Generic) {
                $this->body->addRow(new AA_JSON_Template_Generic());
                $this->body->addRow($this->head);
            }
        }

        if ($this->css != "")
            $this->body->SetProp("css", $this->css);

        //Aggiunge il body
        $this->wnd->SetProp("body", $this->body);
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
    protected $children = array();
    public function AddView($view)
    {
        if (is_array($view) && $view['id'] != "") {
            $this->children[] = new AA_JSON_Template_Generic($view['id'], $view);
            return;
        }

        if ($view instanceof AA_JSON_Template_Generic) {
            $this->children[] = $view;
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
