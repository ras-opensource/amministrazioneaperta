<?php
class AA_GenericModuleSection
{
    protected $id = "AA_GENERIC_MODULE_SECTION";
    public function GetId()
    {
        return $this->id;
    }

    protected $name = "section name";
    public function GetName()
    {
        return $this->name;
    }
    public function SetName($val = "new name")
    {
        $this->name = $val;
    }

    protected $icon = "";
    public function GetIcon()
    {
        return $this->icon;
    }
    public function SetIcon($val = "")
    {
        $this->icon = $val;
    }

    //indica se deve esserci il riferimento sulla navbar
    protected $navbar = false;
    public function IsVisibleInNavbar()
    {
        return $this->navbar;
    }

    protected $view_id = "AA_Section_Content_Box";
    public function GetViewId()
    {
        return $this->view_id;
    }

    protected $module_id = "AA_GENERIC_MODULE";
    public function GetModuleId()
    {
        return $this->module_id;
    }

    protected $valid = false;
    public function isValid()
    {
        return $this->valid;
    }

    protected $default = false;
    public function IsDefault()
    {
        return $this->default;
    }

    protected $detail = false;
    public function IsDetail()
    {
        return $this->detail;
    }
    public function SetDetail($bVal = true)
    {
        $this->detail = $bVal;
    }

    protected $template = "";
    public function SetTemplate($val="")
    {
        $this->template=$val;
    }
    public function GetTemplate()
    {
        return $this->template;
    }

    protected $navbar_template = "{}";
    public function SetNavbarTemplate($template = "{}")
    {
        $this->navbar_template = $template;
    }

    protected $refresh_view = true;
    public function EnableRefreshView($bVal = true)
    {
        $this->refresh_view = $bVal;
    }

    public function toArray()
    {
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "navbar" => $this->navbar,
            "view_id" => $this->view_id,
            "module_id" => $this->module_id,
            "default" => $this->default,
            "valid" => $this->valid,
            "navbar_template" => $this->navbar_template,
            "refresh_view" => $this->refresh_view,
            "detail" => $this->detail
        );
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function toBase64()
    {
        return base64_encode($this->toString());
    }

    public function __construct($id = "AA_GENERIC_MODULE_SECTION", $name = "section name", $navbar = false, $view_id = "AA_Section_Content_Box", $module_id = "AA_GENERIC_MODULE", $default = false, $refresh_view = true, $detail = false, $valid = false, $icon="",$template="")
    {
        $this->name = $name;
        $this->id = $id;
        $this->navbar = $navbar;
        $this->view_id = $view_id;
        $this->module_id = $module_id;
        $this->default = $default;
        $this->valid = $valid;
        $this->refresh_view = $refresh_view;
        $this->detail = $detail;
        $this->icon = $icon;
        $this->template=$template;
    }

    public function TemplateActionMenu()
    {
        return $this->TemplateGenericActionMenu();
    }

    protected function TemplateGenericActionMenu()
    {
        $menu = new AA_JSON_Template_Generic(
            "AA_ActionMenu_".uniqid(),
            array(
                "view" => "contextmenu",
                "data" => array(array(
                    "id" => "refresh_".$this->GetId(),
                    "value" => "Aggiorna",
                    "icon" => "mdi mdi-reload",
                    "module_id" => $this->GetModuleId(),
                    "handler" => "refreshUiObject",
                    "handler_params" => array($this->GetViewId(), true)
                ))
            )
        );

        return $menu;
    }
}
