<?php
class AA_JSON_Template_Text extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "text";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_TEMPLATE" . uniqid(time());
        if (!isset($props['clear']))
            $props['clear'] = true;

        parent::__construct($id, $props);
    }
}
