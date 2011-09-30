<?php

class marm_slider_oxutilsfile extends marm_slider_oxutilsfile_parent
{
    /**
     * adds promo dir
     */
    public function __construct()
    {
        $this->_aTypeToPath['PROMO'] = 'promo';
        return parent::__construct();
    }

    /**
     * EXTENDED: for PROM type, just copy file, no processing is needed.
     * for other, returns parent functionality
     * 
     * Prepares (resizes anc copies) images according to its type.
     * Returns preparation status
     *
     * @param string $sType   image type
     * @param string $sSource image location
     * @param string $sTarget image copy location
     *
     * @return array
     */
    protected function _prepareImage( $sType, $sSource, $sTarget)
    {
        if ($sType =='PROMO') {
            $this->_copyFile($sSource, $sTarget);
            return;
        }
        return parent::_prepareImage( $sType, $sSource, $sTarget);
    }

}