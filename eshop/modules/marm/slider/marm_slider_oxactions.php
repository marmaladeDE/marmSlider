<?php
class marm_slider_oxactions extends marm_slider_oxactions_parent
{

    /**
     * ported from oxid >4.5 /core/oxactions.php
     * return assigned banner article
     *
     * @return oxArticle
     */
    public function getBannerArticle()
    {
        $oDb = oxDb::getDb();
        $sArtId = $oDb->getOne(
            'select oxobjectid from oxobject2action '
          . 'where oxactionid='.$oDb->quote($this->getId())
          . ' and oxclass="oxarticle"'
        );

        if ( $sArtId ) {
            $oArticle = oxNew( 'oxarticle' );

            if ( $this->isAdmin() ) {
                $oArticle->setLanguage( oxLang::getInstance()->getEditLanguage() );
            }

            if ( $oArticle->load($sArtId) ) {
                return $oArticle;
            }
        }
        return null;
    }


    /**
     * ported from oxid >4.5 /core/oxactions.php
     * Returns assigned banner article picture url
     *
     * @return string
     */
    public function getBannerPictureUrl()
    {
        if ( isset( $this->oxactions__oxpic ) && $this->oxactions__oxpic->value ) {
            $sPromoDir = oxUtilsFile::getInstance()->normalizeDir( 'promo' );
            return $this->getConfig()->getPictureUrl( $sPromoDir.$this->oxactions__oxpic->value, false );
        }
    }

    /**
     * ported from oxid >4.5 /core/oxactions.php
     * Returns assigned banner link. If no link is defined and article is
     * assigned to banner, article link will be returned.
     *
     * @return string
     */
    public function getBannerLink()
    {
        if ( isset( $this->oxactions__oxlink ) && $this->oxactions__oxlink->value ) {
            return  oxUtilsUrl::getInstance()->processUrl( $this->oxactions__oxlink->value );
        } else {
            // if article is assinged to banner, getting article link
            if ( $oArticle = $this->getBannerArticle() ) {
                return $oArticle->getLink();
            }
        }
    }

}