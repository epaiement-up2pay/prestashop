<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Helper;

trait TranslationHelper
{
    /**
     * Overwritten translation function, used in the module
     *
     * @param string $key translation key
     * @param string $iso_lang
     * @param string|bool $specific filename of the translation key
     *
     * @return string translation
     * @since 2.0.0
     *
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    protected function getTranslatedString($key, $iso_lang = 'en', $specific = false)
    {
        if (!$specific && defined("static::TRANSLATION_FILE")) {
            $specific = static::TRANSLATION_FILE;
        }

        if (!$specific) {
            $specific = \CreditAgricolePaymentGateway::NAME;
        }

        $translation = \Translate::getModuleTranslation(
            \CreditAgricolePaymentGateway::NAME,
            $key,
            $specific
        );

        if ($translation === $key) {
            $translation = \CreditAgricolePaymentGateway::getTranslationForLanguage($iso_lang, $key, $specific);
        }

        return $translation;
    }
}
