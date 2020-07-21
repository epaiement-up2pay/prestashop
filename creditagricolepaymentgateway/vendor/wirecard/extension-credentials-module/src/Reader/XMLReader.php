<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace Wirecard\Credentials\Reader;

use Wirecard\Credentials\PaymentMethodRegistry;
use Wirecard\Credentials\Exception\InvalidXMLFormatException;
use Exception;
use DOMDocument;
use DOMXPath;

/**
 * Class XMLReader
 * @package Credentials\Reader
 * @since 1.0.0
 */
class XMLReader implements ReaderInterface
{
    /**
     * @var string
     */
    const XML_SCHEMA_FILE_NAME = "schema.xsd";

    /**
     * @var string
     */
    private $rawXML;

    /**
     * XMLReader constructor.
     * @param string $data
     * @throws InvalidXMLFormatException
     * @since 1.0.0
     */
    public function __construct($data)
    {
        $this->rawXML = $data;
        try {
            if (!$this->validate()) {
                throw new InvalidXMLFormatException();
            }
        } catch (Exception $ex) {
            throw new InvalidXMLFormatException($ex->getMessage());
        }
    }

    /**
     * @return string
     * @since 1.0.0
     */
    private function getXMLSchemaPath()
    {
        return sprintf(
            "%s/%s",
            dirname(dirname(__DIR__)),
            self::XML_SCHEMA_FILE_NAME
        );
    }

    /**
     * Validate XML with XSD schema
     * @since 1.0.0
     */
    private function validate()
    {
        $dom = new DOMDocument();
        $dom->loadXML($this->rawXML);
        return $dom->schemaValidate($this->getXMLSchemaPath());
    }


    /**
     * @since 1.0.0
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function toArray()
    {
        $credentials = [];
        $domDocument = new DOMDocument();
        $domDocument->loadXML($this->rawXML);
        $xPath = new DOMXPath($domDocument);
        foreach (PaymentMethodRegistry::availablePaymentMethods() as $paymentMethod) {
            $paymentMethodXPath = $xPath->query(
                "/config/payment_methods/{$paymentMethod}"
            )->item(0);
            if (!$paymentMethodXPath) {
                continue;
            }
            /** @var \DOMNode $child */
            foreach ($paymentMethodXPath->childNodes as $child) {
                if ($child->nodeType != XML_ELEMENT_NODE) {
                    continue;
                }
                $credentials[$paymentMethod][$child->nodeName] = $child->nodeValue;
            }
        }

        return $credentials;
    }
}
