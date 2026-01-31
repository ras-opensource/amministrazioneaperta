<?php
class AA_GenericPdfPreviewDlg extends AA_GenericWindowTemplate
{
    public function __construct($id = "", $title = "Pdf Viewer", $module = "")
    {
        if ($id == "") $id = "PdfPreviewDlg_" . time();
        parent::__construct($id, $title, $module);

        $this->SetWidth("720");
        $this->SetHeight("576");

        //riquadro di visualizzazione preview pdf
        $this->body->AddRow(new AA_JSON_Template_Template($this->id . "_Pdf_Preview_Box", array("type" => "clean", "template" => "<div id='pdf_preview_box' style='width: 100%; height: 100%'><span style='mdi mdi-spin'></span><span> Caricamento in corso</span></div>")));
    }

    protected function Update()
    {
        parent::Update();
    }
}
