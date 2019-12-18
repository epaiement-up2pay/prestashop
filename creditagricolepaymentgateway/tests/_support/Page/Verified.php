<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace Page;

class Verified extends Base
{

    // include url of current page
    /**
     * @var string
     * @since 1.3.4
     */
    public $URL = '/bank';

    /**
     * @var array
     * @since 1.3.4
     */
    public $elements = array(
        'Password' => "//*[@id='password']",
        'Continue' => "//*[@name='authenticate']",
    );
}
