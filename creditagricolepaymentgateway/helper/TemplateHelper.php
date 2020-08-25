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

/**
 * Class TemplateHelper
 *
 * @package WirecardEE\Prestashop\Helper
 * @since 2.4.0
 */
class TemplateHelper
{
    const VIEWS_DIRECTORY = 'views';
    const TEMPLATES_DIRECTORY = 'templates';
    const FRONTEND_DIRECTORY = 'front';

    const TEMPLATE_EXT = '.tpl';

    /**
     * Gets the path for a frontend template
     *
     * @param string $template
     * @return string
     * @since 2.4.0
     */
    public static function getFrontendTemplatePath($template)
    {
        return implode(
            DIRECTORY_SEPARATOR,
            [
                _PS_MODULE_DIR_,
                \CreditAgricolePaymentGateway::NAME,
                self::VIEWS_DIRECTORY,
                self::TEMPLATES_DIRECTORY,
                self::FRONTEND_DIRECTORY,
                $template . self::TEMPLATE_EXT
            ]
        );
    }
}
