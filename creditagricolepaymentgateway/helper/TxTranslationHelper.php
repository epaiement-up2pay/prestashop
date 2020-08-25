<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 * @author Crédit Agricole
 * @copyright Copyright (c) 2020 Crédit Agricole, Einsteinring 35, 85609 Aschheim, Germany
 * @license MIT License
 */

namespace WirecardEE\Prestashop\Helper;

use Tools;
use WirecardEE\Prestashop\Classes\Constants\TxConstants as TxConst;

/**
 * Class TxTranslationHelper
 * @package WirecardEE\Prestashop\Helper
 */
class TxTranslationHelper
{
    use TranslationHelper;

    /** @var string */
    const TRANSLATION_FILE = "txconstants";

    /**
     * Translates the transaction types in the transaction table
     *
     * @param string $transactionType
     *
     * @return string
     * @since 2.10.0
     */
    public function translateTxType($transactionType)
    {
        $transactionType = TxConst::TX_TYPE . Tools::strtoupper(str_replace('-', '_', $transactionType));
        $translatedTxType = $this->getTranslatedString(TxConst::TX_TYPE_KEYS[$transactionType]);
        return $translatedTxType;
    }

    /**
     * Translates the transaction states in the transaction table
     *
     * @param string $transactionState
     *
     * @return string
     * @since 2.10.0
     */
    public function translateTxState($transactionState)
    {
        $transactionState = TxConst::TX_STATE. Tools::strtoupper($transactionState);
        $translatedTxState = $this->getTranslatedString(TxConst::TX_STATE_KEYS[$transactionState]);
        return $translatedTxState;
    }
}
