<?php
class AA_JSON_Template_Datepicker extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "datepicker";
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_DATEPICKER" . uniqid(time());
        if (!isset($props['clear']))
            $props['clear'] = true;

        parent::__construct($id, $props);
    }
}
