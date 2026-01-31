<?php
Class AA_GenericNews extends AA_GenericParsableDbObject
{
    static protected $dbDataTable="aa_news";
    static protected $objectClass=__CLASS__;
    public function __construct($params = null)
    {
        $this->aProps['timestamp']="";
        $this->aProps['tags']="";
        $this->aProps['oggetto']="";
        $this->aProps['descrizione']="";
        $this->aProps['allegati']="";
        $this->aProps['module']="";

        return parent::__construct($params);
    }
}
