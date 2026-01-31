<?php
class AA_XML_FEED
{
    //Identificativo del feed
    protected $id = "AA_GENERIC_XML_FEED";

    //versione
    protected $version = "1.0";

    //licenza
    protected $sLicense = "IODL";

    //url del feed
    protected $sUrl = "";
    public function SetURL($var = "")
    {
        $this->sUrl = $var;
    }
    public function GetURL()
    {
        return $this->sUrl;
    }

    //timestamp
    protected $sTimestamp = "";
    public function Timestamp()
    {
        return $this->sTimestamp;
    }

    //params
    protected $aParams = array();
    public function GetParams()
    {
        return $this->aParams;
    }
    public function SetParams($params = array())
    {
        if (is_array($params)) $this->aParams = $params;
    }

    //content
    protected $sContent = "";
    public function GetContent()
    {
        return $this->sContent;
    }
    public function SetContent($var)
    {
        $this->sContent = $var;
    }

    //Restituisce il feed in formato xml
    public function toXML()
    {
        $return = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $return .= "<aa_xml_feed id='" . $this->id . "' version='" . $this->version . "'><meta>";
        $return .= "<url>" . htmlspecialchars($this->sUrl, ENT_QUOTES) . "</url>";
        $return .= "<timestamp>" . $this->sTimestamp . "</timestamp>";
        $return .= "<license>" . $this->sLicense . "</license>";
        $return .= "<params>";
        foreach ($this->aParams as $key => $value) {
            $return .= "<param id='" . htmlspecialchars($key, ENT_QUOTES) . "'>" . htmlspecialchars($value, ENT_QUOTES) . "</param>";
        }
        $return .= "</params></meta><content>";
        $return .= $this->sContent;
        $return .= "</content></aa_xml_feed>";

        return $return;
    }

    public function __toString()
    {
        return $this->toXML();
    }
}
