<?php
/**
 *    This file is part of OXID eShop Community Edition.
 *
 *    OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @package   admin
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: actions_main.php 33719 2011-03-10 08:40:42Z sarunas $
 */

/**
 * patched from oxid > 4.5
 */
class marm_slider_actions_main extends marm_slider_actions_main_parent
{

    /**
     * patched from /admin/oxAdminView oxid 4.5
     * Editable object id
     *
     * @var string
     */
    protected $_sEditObjectId = null;

    /**
     * Loads article actionss info, passes it to Smarty engine and
     * returns name of template file "actions_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        oxAdminDetails::render();

        // check if we right now saved a new entry
        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oAction = oxNew( "oxactions" );
            $oAction->loadInLang( $this->_iEditLang, $soxId);

            $oOtherLang = $oAction->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oAction->loadInLang( key($oOtherLang), $soxId );
            }

            $this->_aViewData["edit"] =  $oAction;

            // remove already created languages
            $aLang = array_diff ( oxLang::getInstance()->getLanguageNames(), $oOtherLang );

            if ( count( $aLang))
                $this->_aViewData["posslang"] = $aLang;

            foreach ( $oOtherLang as $id => $language) {
                $oLang= new oxStdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }
        }
        $aColumns = array();

        if ( oxConfig::getParameter("aoc") ) {
            // generating category tree for select list
            $sChosenArtCat = oxConfig::getParameter( "artcat");
            $sChosenArtCat = $this->_getCategoryTree( "artcattree", $sChosenArtCat, $soxId);

            include_once 'inc/actions_main.inc.php';
            $this->_aViewData['oxajax'] = $aColumns;

            return "popups/actions_main.tpl";
        }


        if ( ( $oPromotion = $this->getViewDataElement( "edit" ) ) ) {
            if ( ($oPromotion->oxactions__oxtype->value == 2) || ($oPromotion->oxactions__oxtype->value == 3) ) {
                if ( $iAoc = oxConfig::getParameter( "oxpromotionaoc" ) ) {
                    $sPopup = false;
                    switch( $iAoc ) {
                        case 'article':
                            // generating category tree for select list
                            $sChosenArtCat = oxConfig::getParameter( "artcat");
                            $sChosenArtCat = $this->_getCategoryTree( "artcattree", $sChosenArtCat, $soxId);

                            if ($oArticle = $oPromotion->getBannerArticle()) {
                                $this->_aViewData['actionarticle_artnum'] = $oArticle->oxarticles__oxartnum->value;
                                $this->_aViewData['actionarticle_title']  = $oArticle->oxarticles__oxtitle->value;
                            }

                            $sPopup = 'actions_article';
                            break;
                        case 'groups':
                            $sPopup = 'actions_groups';
                            break;
                    }

                    if ( $sPopup ) {
                        $aColumns = array();
                        include_once "inc/{$sPopup}.inc.php";
                        $this->_aViewData['oxajax'] = $aColumns;
                        return "popups/{$sPopup}.tpl";
                    }
                } else {
                    if ( $oPromotion->oxactions__oxtype->value == 2) {
                        $this->_aViewData["editor"] = $this->_generateTextEditor( "100%", 300, $oPromotion, "oxactions__oxlongdesc", "details.tpl.css" );
                    }
                }
            }
        }

        return "actions_main_45.tpl";
    }


    /**
     * Saves Promotions
     *
     * @return mixed
     */
    public function save()
    {
        $myConfig  = $this->getConfig();


        $soxId   = $this->getEditObjectId();
        $aParams = oxConfig::getParameter( "editval");

        $oPromotion = oxNew( "oxactions" );
        if ( $soxId != "-1" ) {
            $oPromotion->load( $soxId );

                oxUtilsPic::getInstance()->overwritePic( $oPromotion, 'oxactions', 'oxpic', 'PROMO', 'promo', $aParams, $myConfig->getPictureDir(false));

        } else {
            $aParams['oxactions__oxid']   = null;
        }

        if ( !$aParams['oxactions__oxactive'] ) {
            $aParams['oxactions__oxactive'] = 0;
        }

        $oPromotion->setLanguage( 0 );
        $oPromotion->assign( $aParams );
        $oPromotion->setLanguage( $this->_iEditLang );
        $oPromotion = oxUtilsFile::getInstance()->processFiles( $oPromotion );
        $oPromotion->save();

        // set oxid if inserted
        $this->setEditObjectId( $oPromotion->getId() );
    }
    /**
     * patched from /admin/oxAdminView oxid 4.5
     * Returns active/editable object id
     *
     * @return string
     */
    public function getEditObjectId()
    {
        if ( null === ( $sId = $this->_sEditObjectId ) ) {
            if ( null === ( $sId = oxConfig::getParameter( "oxid" ) ) ) {
                $sId = oxSession::getVar( "saved_oxid" );
            }
        }
        return $sId;
    }

    /**
     * Sets editable object id
     *
     * @param string $sId object id
     *
     * @return string
     */
    public function setEditObjectId( $sId )
    {
        $this->_sEditObjectId = $sId;
        $this->_aViewData["updatelist"] = 1;
    }

}
