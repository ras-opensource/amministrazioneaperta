<?php
class AA_JSON_Template_Select extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "richselect";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_RICHSELECT" . uniqid(time());

        parent::__construct($id, $props);
    }
}
