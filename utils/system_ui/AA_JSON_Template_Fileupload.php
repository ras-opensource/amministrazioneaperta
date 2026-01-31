<?php
class AA_JSON_Template_Fileupload extends AA_JSON_Template_Layout
{
    public function __construct($id = "", $props = null, $sessionFileName = "AA_SessionFileUploader")
    {
        if ($id == "")
            $id = "AA_JSON_TEMPLATE_FILEUPLOAD" . uniqid(time());
        $props['name'] = "AA_FileUploader";

        if (!isset($props['value']))
            $props['value'] = "Sfoglia...";
        $props['autosend'] = false;
        if ($props['multiple'] == "")
            $props['multiple'] = false;
        $props['view'] = "uploader";
        $props['link'] = $id . "_FileUpload_List";
        $props['layout_id'] = $id . "_FileUpload_Layout";
        $props['formData'] = array("file_id" => $sessionFileName);

        parent::__construct($id . "_FileUpload_Layout", array("type" => "clean", "borderless" => true, "autoheight" => true));

        $this->AddRow(new AA_JSON_Template_Generic($id . "_FileUpload_Field", $props));
        $this->AddRow(new AA_JSON_Template_Generic($id . "_FileUpload_List", array(
            "view" => "list",
            "scroll" => false,
            "autoheight" => true,
            "minHeight" => 32,
            "type" => "uploader",
            "css" => array("background" => "transparent")
        )
        ));

        if ($props['bottomLabel']) {
            $this->AddRow(new AA_JSON_Template_Template($id . "_FileUpload_BottomLabel", array(
                "autoheight" => true,
                "template" => "<span style='font-size: smaller; font-style:italic'>" . $props['bottomLabel'] . "</span>",
                "css" => array("background" => "transparent")
            )
            ));
        }
    }
}
