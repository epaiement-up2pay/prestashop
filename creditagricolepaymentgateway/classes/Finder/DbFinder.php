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

namespace WirecardEE\Prestashop\Classes\Finder;

use \Db;
use \DbQuery;

/**
 * Class DbFinder
 * @since 2.5.0
 * @package WirecardEE\Prestashop\Classes\Finder
 */
class DbFinder
{
    /**
     * @var array|Db
     */
    private $database;

    /**
     * DbFinder constructor.
     * @since 2.5.0
     */
    public function __construct()
    {
        $this->database = Db::getInstance();
    }

    /**
     * @return array|Db
     * @since 2.5.0
     */
    public function getDb()
    {
        return $this->database;
    }

    /**
     * @return DbQuery
     * @since 2.5.0
     */
    public function getQueryBuilder()
    {
        return new DbQuery();
    }
}
