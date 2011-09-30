<?php

class marm_slider_oxactionlist extends marm_slider_oxactionlist_parent
{

    /**
     * ported from oxid >4.5 /core/oxactionlist.php
     * load active shop banner list
     *
     * @return null
     */
    public function loadBanners()
    {
        $oBaseObject = $this->getBaseObject();
        $oViewName = $oBaseObject->getViewName();
        $sQ = "select * from {$oViewName} where oxtype=3 and " . $oBaseObject->getSqlActiveSnippet()
            . " and oxshopid='" . $this->getConfig()->getShopId() . "' " . $this->_getUserGroupFilter()
            . " order by oxsort";
        $this->selectString( $sQ );
    }
}