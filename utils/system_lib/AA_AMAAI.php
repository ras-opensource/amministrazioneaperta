<?php
class AA_AMAAI
{
    private static $oInstance = null;
    public static function GetInstance()
    {
        if (self::$oInstance == null) self::$oInstance = new AA_AMAAI;

        return self::$oInstance;
    }

    //Restituisce il template del layout della finestra
    public function TemplateLayout()
    {
        return new AA_GenericWindowTemplate("AA_AMAAI", "AMAAI - Navigazione Assistita");
    }

    //Restituisce la pagina iniziale della navigazione assistita
    public function TemplateStart()
    {
        $content = "<div style='display:flex; flex-direction: column; justify-content: space-between; align-items: center; width:100%; height:100%; font-size: larger'>";
        //$content.="<div style='display:flex; flex-direction: column; justify-content: flex-start; align-items: center; width:100%; height: 30%'>";
        //$content.="<p style='font-weight: 700'>Benvenuti!</p>";
        //$content.="<p style='border-bottom: 1px solid blue'>L'assistente digitale AMAAI vi assisterà nell'utilizzo delle funzionalità della piattaforma.</p>";
        //$content.="<br>";
        $content .= "<p style='font-weight: 700'>Come posso esserti d'aiuto?</p>";
        //$content.="</div>";
        $content .= "<div style='display:flex; flex-direction: column; justify-content: space-between; align-items: stretch; width:100%; height:50%; margin-bottom: 3em'>";
        $content .= "<div class='AA_AMAAI_QUEST'><a class='AA_AMAAI_QUEST_1'>Voglio effettuare una nuova pubblicazione...</a></div>";
        $content .= "<div class='AA_AMAAI_QUEST'><a class='AA_AMAAI_QUEST_2'>Voglio modificare una pubblicazione...</a></div>";
        $content .= "<div class='AA_AMAAI_QUEST'><a class='AA_AMAAI_QUEST_3'>Voglio cercare una pubblicazione...</a></div>";
        $content .= "<div class='AA_AMAAI_QUEST'><a class='AA_AMAAI_QUEST_4'>Voglio annullare una pubblicazione...</a></div>";
        $content .= "</div>";
        $content .= "</div>";
        return array(
            "id" => "AA_AMAAI_START",
            "view" => "template",
            "template" => $content
        );
    }
}
