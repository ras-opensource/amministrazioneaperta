<?php
class AA_JSON_Template_Ckeditor5 extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "ckeditor5";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_CKEDITOR5_" . uniqid(time());

        parent::__construct($id, $props);
    }
}
