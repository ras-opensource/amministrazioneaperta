<?php
Class AA_GenericResources extends AA_GenericParsableDbObject
{
    public function __construct($params = null)
    {
        static::$dbDataTable="aa_resources";

        $this->aProps['timestamp']="";
        $this->aProps['module']="";
        $this->aProps['data']="";

        return parent::__construct($params);
    }
}
