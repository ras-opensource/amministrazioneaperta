<?php
class AA_JSON_Template_Combo extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "combo";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_COMBO" . uniqid(time());

        parent::__construct($id, $props);
    }
}
