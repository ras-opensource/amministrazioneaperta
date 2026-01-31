<?php
class AA_JSON_Template_Form extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "form";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_FORM" . uniqid(time());

        parent::__construct($id, $props);
    }
}
