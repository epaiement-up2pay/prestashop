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

namespace WirecardEE\Prestashop\Classes;

/**
 * @since 2.5.0
 */
class ProcessType
{
    /** @var string */
    const PROCESS_RESPONSE = 'process_response';

    /** @var string */
    const PROCESS_BACKEND = 'process_backend';

    /** @var string  */
    const PROCESS_INITIAL_RETURN = "return"; // TODO: delete and pull constants from OS Module.
    const PROCESS_INITIAL_NOTIFICATION = "notification"; // TODO: delete and pull constants from OS Module.
    const PROCESS_POST_PROCESSING_RETURN = "initial_return"; // TODO: delete and pull constants from OS Module.
    const PROCESS_POST_PROCESSING_NOTIFICATION = "initial_return"; // TODO: delete and pull constants from OS Module.
}
