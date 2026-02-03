<?php
class AA_NewsTags
{
    private $aTags=array();

    private static $oInstance=null;

    protected static function Initialize()
    {
        static::$oInstance=new AA_NewsTags();
    }

    private function __construct()
    {
        $this->aTags[0]="esterna";
        $this->aTags[1]="interna";
    }

    public static function GetTags($params=null)
    {
        if(!static::$oInstance)
        {
            static::Initialize();
        }

        return static::$oInstance->aTags;
    }
}
