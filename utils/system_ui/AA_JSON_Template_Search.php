<?php
class AA_JSON_Template_Search extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "search";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_SEARCH" . uniqid(time());

        parent::__construct($id, $props);
    }
}
