<?php
class AA_JSON_Template_Ckeditor5_field extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "ckeditor5_field";
        $props["type"] = "clean";
        $props["borderless"] = true;

        if ($id == "")
            $id = "AA_JSON_TEMPLATE_CKEDITOR5_" . uniqid(time());

        parent::__construct($id, $props);
    }
}
