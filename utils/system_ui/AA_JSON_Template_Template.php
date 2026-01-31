<?php
class AA_JSON_Template_Template extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "template";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_TEMPLATE" . uniqid(time());

        parent::__construct($id, $props);
    }
}
