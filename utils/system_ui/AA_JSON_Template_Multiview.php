<?php
class AA_JSON_Template_Multiview extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "multiview";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_MULTIVIEW" . uniqid(time());

        parent::__construct($id, $props);
    }
}
