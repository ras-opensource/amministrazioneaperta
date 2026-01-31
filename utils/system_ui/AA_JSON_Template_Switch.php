<?php
class AA_JSON_Template_Switch extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "switch";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_SWITCH" . uniqid(time());

        parent::__construct($id, $props);
    }
}
