<?php
class AA_JSON_Template_Tree extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "tree";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_TREE" . uniqid(time());

        parent::__construct($id, $props);
    }
}
