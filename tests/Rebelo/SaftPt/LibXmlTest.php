<?php

namespace Rebelo\SaftPt;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\Validate\Schema\Schema;

/**
 *
 *
 * @author João Rebelo
 */
class LibXmlTest extends TestCase
{

    /**
     *
     */
    public function createLibXmlErrorMsg(): string
    {
        $msg        = "";
        $errorStack = \libxml_get_errors();
        foreach ($errorStack as $erro) {
            $msg .= \sprintf(
                "'%s' on Line '%s' column '%s'\r\n", $erro->message,
                $erro->line, $erro->column
            );
        }
        return $msg;
    }

    /**
     * Erros gerado pelo teste:
     * 'Element '{http://www.w3.org/2001/XMLSchema}complexType': The content is not valid. Expected is (annotation?, (simpleContent | complexContent | ((group | all | choice | sequence)?, ((attribute | attributeGroup)*, anyAttribute?)))).
     * ' on Line '196' column '0'
     * 'Element '{http://www.w3.org/2001/XMLSchema}element': Invalid value for maxOccurs (must be 0 or 1).
     * ' on Line '315' column '0'
     * 'Element '{http://www.w3.org/2001/XMLSchema}element': Invalid value for maxOccurs (must be 0 or 1).
     * ' on Line '330' column '0'
     * 'Element '{http://www.w3.org/2001/XMLSchema}complexType': The content is not valid. Expected is (annotation?, (simpleContent | complexContent | ((group | all | choice | sequence)?, ((attribute | attributeGroup)*, anyAttribute?)))).
     * ' on Line '436' column '0'
     * 'Element '{http://www.w3.org/2001/XMLSchema}complexType': The content is not valid. Expected is (annotation?, (simpleContent | complexContent | ((group | all | choice | sequence)?, ((attribute | attributeGroup)*, anyAttribute?)))).
     * ' on Line '564' column '0'
     * 'Element '{http://www.w3.org/2001/XMLSchema}complexType': The content is not valid. Expected is (annotation?, (simpleContent | complexContent | ((group | all | choice | sequence)?, ((attribute | attributeGroup)*, anyAttribute?)))).
     * ' on Line '659' column '0'
     * 'Element '{http://www.w3.org/2001/XMLSchema}complexType': The content is not valid. Expected is (annotation?, (simpleContent | complexContent | ((group | all | choice | sequence)?, ((attribute | attributeGroup)*, anyAttribute?)))).
     * ' on Line '767' column '0'
     * 'Element '{http://www.w3.org/2001/XMLSchema}complexType': The content is not valid. Expected is (annotation?, (simpleContent | complexContent | ((group | all | choice | sequence)?, ((attribute | attributeGroup)*, anyAttribute?)))).
     * ' on Line '817' column '0'
     * @test
     */
    public function testValidateOnXsdVersion1Dot1(): void
    {

        try {
            $xsd = Schema::ORIGINAL_AT_XSD;

            if (\is_file($xsd) === false) {
                $this->fail("File not found: ".$xsd);
            }

            \libxml_use_internal_errors(true);
            \libxml_clear_errors();
            //\libxml_disable_entity_loader(false);
            $dom      = new \DOMDocument("1.1", "ISO-8859-1");
            $dom->load(SAFT_DEMO_PATH);
            $validate = $dom->schemaValidate($xsd);

            if ($validate === false) {
                $this->fail($this->createLibXmlErrorMsg());
            }
            $this->assertTrue($validate);
            $this->fail("XML validation already uses xml 1.1, change your code");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Exception::class, $e);
        }
    }

    /**
     *
     * Aparentemente o XSD da está mal concebido, não está segundo as norma e depois
     * dá erro de validação com o libxml (Campos de contabilidade), com as mensagens em baixo.
     *
     * @link https://www.w3schools.com/xml/el_all.asp definição do elemento "all"
     * @link https://www.w3schools.com/xml/schema_complex_indicators.asp
     * Segundo a definição:
     * Note: When using the <all> indicator you can set the <minOccurs> indicator to 0 or 1
     * and the <maxOccurs> indicator can only be set to 1 (the <minOccurs> and <maxOccurs> are described later).
     *
     * @link https://stackoverflow.com/questions/2362365/xsd-doesnt-allow-me-to-have-unbounded-inside-all-indicator
     * @link https://stackoverflow.com/questions/3827572/xml-schema-to-match-the-following-all-with-unbounded-maxoccurs/3827606#3827606
     *
     * Parece que o correcto é em vez da tag ALL é <xs:choice maxOccurs="unbounded">
     *
     * Erro do libxml2 ou da AT ????????????
     *
     * **************************************************************************************************
     * No teste anterior 'testValidateOnXsd_1_1' os erros da linha 315 e 330
     * atestam também que o xsd está mal concebido
     * --------------------------------------------------------------------------------------------------
     * 'Element '{http://www.w3.org/2001/XMLSchema}element': Invalid value for maxOccurs (must be 0 or 1).
     * ' on Line '315' column '0'
     * 'Element '{http://www.w3.org/2001/XMLSchema}element': Invalid value for maxOccurs (must be 0 or 1).
     * ' on Line '330' column '0'
     * ---------------------------------------------------------------------------------------------------
     * ***************************************************************************************************
     *
     *
     * Erros gerados pelo teste: Caso se tire as tags assert
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '1726' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '1767' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}CreditLine': This element is not expected.
     * ' on Line '1822' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '1870' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '1939' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '1973' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '2007' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}CreditLine': This element is not expected.
     * ' on Line '2041' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '2090' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}CreditLine': This element is not expected.
     * ' on Line '2194' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '2222' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '2271' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '2319' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '2367' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '2422' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}CreditLine': This element is not expected.
     * ' on Line '2456' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}CreditLine': This element is not expected.
     * ' on Line '2511' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}CreditLine': This element is not expected.
     * ' on Line '2545' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}CreditLine': This element is not expected.
     * ' on Line '2718' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}DebitLine': This element is not expected.
     * ' on Line '2782' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}CreditLine': This element is not expected.
     * ' on Line '2861' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}CreditLine': This element is not expected.
     * ' on Line '2906' column '0'
     * 'Element '{urn:OECD:StandardAuditFile-Tax:PT_1.04_01}CreditLine': This element is not expected.
     * ' on Line '2940' column '0'
     *
     * @test
     * @
     */
    public function testValidateOnXsdVersion1Dot0(): void
    {
        try {
            $xsd = Schema::XSD_V_1_0;

            if (\is_file($xsd) === false) {
                $this->fail("File not found: ".$xsd);
            }

            \libxml_use_internal_errors(true);
            \libxml_clear_errors();
            //\libxml_disable_entity_loader(false);
            $dom      = new \DOMDocument("1.0", "ISO-8859-1");
            $dom->load(SAFT_DEMO_PATH);
            $validate = $dom->schemaValidate($xsd);

            if ($validate === false) {
                $errorStack = \libxml_get_errors();
                $msg        = "";
                foreach ($errorStack as $erro) {
                    $msg .= sprintf(
                        "'%s' on Line '%s' column '%s'\r\n", $erro->message,
                        $erro->line, $erro->column
                    );
                }
                $this->fail($msg);
            }

            $this->assertTrue($validate);
            $this->fail("XML validation already uses xml 1.1, change your code");
        } catch (\Exception | \Error $e) {
            $this->assertInstanceOf(\Exception::class, $e);
        }
    }

    /**
     * Changed xsd to xml v 1.0 baecause of libxml only works with xml 1.0
     * and correct the error of tag all
     * @test
     */
    public function testValidateOnXsdVersion1Dot0MyXsd(): void
    {
        $xsd = Schema::GLOBAL_XSD;

        if (\is_file($xsd) === false) {
            $this->fail("File not found: ".$xsd);
        }

        \libxml_use_internal_errors(true);
        \libxml_clear_errors();
        $dom      = new \DOMDocument("1.0", "ISO-8859-1");
        $dom->load(SAFT_DEMO_PATH);
        $validate = $dom->schemaValidate($xsd);

        if ($validate === false) {
            $errorStack = \libxml_get_errors();
            $msg        = "";
            foreach ($errorStack as $erro) {
                $msg .= sprintf(
                    "'%s' on Line '%s' column '%s'\r\n", $erro->message,
                    $erro->line, $erro->column
                );
            }
            $this->fail($msg);
        }

        $this->assertTrue($validate);
    }

    /**
     * Validate permissive xsd
     *
     * @test
     */
    public function testValidatePermissive(): void
    {
        $xsd = Schema::PERMISSIVE_XSD;

        if (\is_file($xsd) === false) {
            $this->fail("File not found: ".$xsd);
        }

        \libxml_use_internal_errors(true);
        \libxml_clear_errors();
        $dom      = new \DOMDocument("1.0", "ISO-8859-1");
        $dom->load(SAFT_DEMO_PATH);
        $validate = $dom->schemaValidate($xsd);

        if ($validate === false) {
            $errorStack = \libxml_get_errors();
            $msg        = "";
            foreach ($errorStack as $erro) {
                $msg .= sprintf(
                    "'%s' on Line '%s' column '%s'\r\n", $erro->message,
                    $erro->line, $erro->column
                );
            }
            $this->fail($msg);
        }

        $this->assertTrue($validate);
    }
}
