<?php
class AA_JSON_Template_Toolbar extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "toolbar";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_TOOLBAR" . uniqid(time());

        parent::__construct($id, $props);
    }
}
