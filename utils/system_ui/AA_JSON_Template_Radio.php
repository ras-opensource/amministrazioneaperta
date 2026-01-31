<?php
class AA_JSON_Template_Radio extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "radio";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_RADIO" . uniqid(time());

        parent::__construct($id, $props);
    }
}
