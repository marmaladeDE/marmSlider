<?php

class marm_slider_start extends marm_slider_start_parent
{

    /**
     * ported from oxid >4.5 /views/start.php
     * Returns active banner list
     * @return objects
     */
    public function getBanners()
    {
        $oBannerList = null;

        if ( $this->getConfig()->getConfigParam( 'bl_perfLoadAktion' ) ) {
        $oBannerList = oxNew( 'oxActionList' );
        $oBannerList->loadBanners();
        }

        return $oBannerList;
    }

}