<?php
class AA_JSON_Template_Checkbox extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "checkbox";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_CHECKBOX" . uniqid(time());

        parent::__construct($id, $props);
    }
}
