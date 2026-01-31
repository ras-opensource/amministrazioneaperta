<?php
class AA_JSON_Template_Carousel extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["navigation"] = array();
        $this->props["view"] = "carousel";
        $this->props["navigation"]["type"] = "side";
        $this->props["navigation"]["items"] = true;
        $this->props["scrollSpeed"] = "800ms";

        if ($id == "")
            $id = "AA_JSON_TEMPLATE_CAROUSEL" . uniqid(time());

        parent::__construct($id, $props);
    }

    protected $slides = array();
    public function AddSlide($slide = null)
    {
        if ($slide instanceof AA_JSON_Template_Generic) {
            $this->slides[] = $slide;
        }
    }

    protected $autoScroll = false;
    protected $autoScrollSlideTime = 5000;
    public function EnableAutoScroll($bVal = true)
    {
        $this->autoScroll = $bVal;
    }
    public function SetAutoScrollSlideTime($val = 5000)
    {
        if ($val > 1000) {
            $this->autoScrollSlideTime = $val;
        }
    }

    public function ShowNavigationButtons($bVal = true)
    {
        $this->props['navigation']['buttons'] = $bVal;
    }

    public function SetScrollSpeed($speed = 500)
    {
        if ($speed > 0)
            $this->props["scrollSpeed"] = $speed . "ms";
    }

    public function GetSlides()
    {
        return $this->slides;
    }

    public function SetTipe($newType = "side")
    {
        $this->props["navigation"]["type"] = $newType;
    }

    public function ShowItems($show = true)
    {
        $this->props["navigation"]["items"] = $show;
    }

    public function toArray()
    {
        foreach ($this->slides as $curSlide) {
            $this->AddCol($curSlide);
        }

        $this->props['autoScroll'] = $this->autoScroll;
        $this->props['autoScrollSlideTime'] = $this->autoScrollSlideTime;
        $this->props['slidesCount'] = sizeof($this->slides);

        return parent::toArray();
    }
}
