<?php
class AA_JSON_Template_Layout extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "layout";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_LAYOUT" . uniqid(time());

        parent::__construct($id, $props);
    }
}
