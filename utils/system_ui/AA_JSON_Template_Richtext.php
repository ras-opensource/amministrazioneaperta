<?php
class AA_JSON_Template_Richtext extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "richtext";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_RICHTEXT_" . uniqid(time());
        if (!isset($props['clear']))
            $props['clear'] = true;

        parent::__construct($id, $props);
    }
}
