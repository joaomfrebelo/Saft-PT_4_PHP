<?php
/*
 * The MIT License
 *
 * Copyright 2020 João Rebelo.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace Rebelo\Test\SaftPt\AuditFile;

use PHPUnit\Framework\TestCase;
use Rebelo\SaftPt\AuditFile\{
    AuditFile,
    Header,
    ErrorRegister,
    AuditFileException,
    MasterFiles\MasterFiles,
    SourceDocuments\SourceDocuments
};
use Rebelo\SaftPt\Validate\ValidationConfig;
use Rebelo\Date\Date as RDate;

/**
 * Class AuditFileTest
 *
 * @author João Rebelo
 */
class AuditFileTest extends TestCase
{

    use \Rebelo\Test\TXmlTest;

    /**
     * @author João Rebelo
     * @test
     */
    public function testReflection(): void
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(AuditFile::class);
        $this->assertTrue(true);
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testInstance(): void
    {
        $auditFile = new AuditFile();
        $this->assertInstanceOf(AuditFile::class, $auditFile);

        $this->assertInstanceOf(
            Header::class, $auditFile->getHeader()
        );

        $this->assertInstanceOf(
            MasterFiles::class, $auditFile->getMasterFiles()
        );

        $this->assertNull($auditFile->getSourceDocuments(false));

        $this->assertInstanceOf(
            SourceDocuments::class, $auditFile->getSourceDocuments()
        );

        $error      = new ErrorRegister();
        $error->addOnCreateXmlNode("Some error");
        $auditError = new AuditFile($error);
        $this->assertSame(
            $error, $auditError->getErrorRegistor()
        );
    }

    /**
     * Reads all saft Demo SAFT in Test\Ressources
     * and parse then to AuditFile class, after that generate a xml from the
     * AuditFile class and test if the xml strings are equal
     * @author João Rebelo
     * @test
     */
    public function testCreateParseXml(): void
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);
        if ($saftDemoXml === false) {
            $this->fail(\sprintf("Fail load xml file '%s'", SAFT_DEMO_PATH));
            /** @phpstan-ignore-next-line */
            return;
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($saftDemoXml);

        $xmlRootNode = $auditFile->createRootElement();
        $xml         = $auditFile->createXmlNode($xmlRootNode);

        try {
            $assertXml = $this->xmlIsEqual($saftDemoXml, $xml);
            $this->assertTrue(
                $assertXml, \sprintf("Fail with error '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testCreateXmlNodeWithoutSet(): void
    {
        $auditFile = new AuditFile();
        $xml       = $auditFile->createXmlNode(
            $auditFile->createRootElement()
        )->asXML();
        if ($xml === false) {
            $this->fail("Fail to get as xml string");
            /** @phpstan-ignore-next-line */
            return;
        }

        $this->assertInstanceOf(
            \SimpleXMLElement::class, new \SimpleXMLElement($xml)
        );

        $this->assertNotEmpty($auditFile->getErrorRegistor()->getOnCreateXmlNode());
        $this->assertEmpty($auditFile->getErrorRegistor()->getOnSetValue());
        $this->assertEmpty($auditFile->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testToXmlString(): void
    {

        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);
        if ($saftDemoXml === false) {
            $this->fail(\sprintf("Fail load xml file '%s'", SAFT_DEMO_PATH));
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($saftDemoXml);

        try {
            $assertXml = $this->xmlIsEqual(
                $saftDemoXml, new \SimpleXMLElement($auditFile->toXmlString())
            );

            $this->assertTrue(
                $assertXml, \sprintf("Fail with error '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        }

        $this->assertFalse(
            $auditFile->getErrorRegistor()->hasErrors(),
            "The AuditFile should not have errors"
        );

        $this->assertEmpty(
            $auditFile->getErrorRegistor()->getWarnings(),
            "The AuditFile should not have warnings"
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testToXmlStringWithError(): void
    {

        $auditFile = new AuditFile();

        try {

            $xml  = $auditFile->toXmlString();
            $node = new \SimpleXMLElement($xml);
            $this->assertSame(AuditFile::N_AUDITFILE, $node->getName());
        } catch (\Exception | \Error $e) {
            $this->fail(
                \sprintf(
                    "Shoul not throw error. Fail with error '%s'",
                    $e->getMessage()
                )
            );
        }

        $this->assertTrue(
            $auditFile->getErrorRegistor()->hasErrors(),
            "The AuditFile should have errors"
        );

        $this->assertNotEmpty(
            $auditFile->getErrorRegistor()->getOnCreateXmlNode(),
            "The AuditFile ErrorRegistor::OnCreateXmlNode should have errors"
        );

        // Should not have errors because the validate methoed was nor call
        $this->assertEmpty(
            $auditFile->getErrorRegistor()->getLibXmlError(),
            "The AuditFile ErrorRegistor::getLibXmlError should have errors"
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testToFile(): void
    {
        $auditXmlFile = tempnam(sys_get_temp_dir(), 'saft');
        if ($auditXmlFile === false) {
            $this->fail("Fail to create temp file");
        }

        try {
            $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);
            if ($saftDemoXml === false) {
                $this->fail(\sprintf("Fail load xml file '%s'", SAFT_DEMO_PATH));
            }

            $auditFile = new AuditFile();
            $auditFile->parseXmlNode($saftDemoXml);
            $auditFile->toFile($auditXmlFile);

            $loadFile = \file_get_contents($auditXmlFile);
            if ($loadFile === false) {
                $this->fail(
                    \sprintf("Fail loading temp file  '%s'", $auditXmlFile)
                );
            }

            $loadXml = \mb_convert_encoding($loadFile, "UTF-8", "Windows-1252");
            unset($loadFile);

            $strConv = \str_replace(
                '<?xml version="1.0" encoding="Windows-1252"?>',
                '<?xml version="1.0"?>', $loadXml
            );
            unset($loadXml);

            $simXmlToCompare = \simplexml_load_string($strConv);
            if ($simXmlToCompare === false) {
                $this->fail(
                    \sprintf("Fail converting temp '%s'", $auditXmlFile)
                );
            }

            $assertXml = $this->xmlIsEqual(
                $saftDemoXml, $simXmlToCompare
            );

            $this->assertTrue(
                $assertXml, \sprintf("Fail with error '%s'", $assertXml)
            );

            $this->assertFalse(
                $auditFile->getErrorRegistor()->hasErrors(),
                "The AuditFile should not have errors"
            );

            $this->assertEmpty(
                $auditFile->getErrorRegistor()->getWarnings(),
                "The AuditFile should not have warnings"
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        } finally {
            if (is_file($auditXmlFile)) {
                unlink($auditXmlFile);
            }
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testToFileWithError(): void
    {
        // Should not have errors because the validate methoed was nor call
        $auditXmlFile = tempnam(sys_get_temp_dir(), 'saft');
        if ($auditXmlFile === false) {
            $this->fail("Fail to create temp file");
        }

        try {
            $auditFile = new AuditFile();
            $auditFile->toFile($auditXmlFile);
            $loadFile  = \file_get_contents($auditXmlFile);
            if ($loadFile === false) {
                $this->fail(
                    \sprintf("Fail loading temp file  '%s'", $auditXmlFile)
                );
            }

            $loadXml = \mb_convert_encoding($loadFile, "UTF-8", "Windows-1252");
            unset($loadFile);

            $strConv = \str_replace(
                '<?xml version="1.0" encoding="Windows-1252"?>',
                '<?xml version="1.0"?>', $loadXml
            );
            unset($loadXml);

            $node = \simplexml_load_string($strConv);
            unset($strConv);

            if ($node === false) {
                $this->fail(
                    \sprintf(
                        "Fail creating xml node from temp file  '%s'",
                        $auditXmlFile
                    )
                );
            }

            $this->assertSame(AuditFile::N_AUDITFILE, $node->getName());

            $this->assertTrue(
                $auditFile->getErrorRegistor()->hasErrors(),
                "The AuditFile should have errors"
            );

            $this->assertNotEmpty(
                $auditFile->getErrorRegistor()->getOnCreateXmlNode(),
                "The AuditFile ErrorRegistor::OnCreateXmlNode should have errors"
            );

            $this->assertEmpty(
                $auditFile->getErrorRegistor()->getLibXmlError(),
                "The AuditFile ErrorRegistor::getLibXmlError should have errors"
            );
        } catch (\Exception | \Error $e) {
            $this->fail(
                \sprintf(
                    "Shoul not throw error. Fail with error '%s'",
                    $e->getMessage()
                )
            );
        } finally {
            if (is_file($auditXmlFile)) {
                unlink($auditXmlFile);
            }
        }
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testIssetMagikMethod(): void
    {
        $auditFile = new AuditFile();
        $this->assertFalse($auditFile->issetHeader());
        // The propertie name
        $header    = "header";
        $this->assertSame(
            isset($auditFile->{$header}), $auditFile->issetHeader()
        );

        $auditFile->getHeader();
        $this->assertTrue($auditFile->issetHeader());
        $this->assertSame(
            isset($auditFile->{$header}), $auditFile->issetHeader()
        );
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testLoadFile(): void
    {
        $audit = AuditFile::loadFile(SAFT_DEMO_PATH);
        $this->assertFalse($audit->getErrorRegistor()->hasErrors());
        $this->assertTrue($audit->issetHeader());
        $this->assertTrue($audit->issetMasterFiles());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testValidate(): void
    {
        $audit = AuditFile::loadFile(SAFT_DEMO_PATH);
        $this->assertTrue($audit->validate(PUBLIC_KEY_PATH));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testValidateWrongScheme(): void
    {
        $audit = AuditFile::loadFile(SAFT_WRONG_SCHEME_PATH);
        $this->assertFalse($audit->validate(PUBLIC_KEY_PATH));
        $this->assertNotEmpty($audit->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testValidateWrongSchemeWithConfigSetToNoSchemaValidation(): void
    {
        $config = new ValidationConfig();
        $config->setSchemaValidate(false);
        $audit  = AuditFile::loadFile(SAFT_WRONG_SCHEME_PATH);
        $audit->getErrorRegistor()->clearAllErrors();
        $this->assertTrue($audit->validate(PUBLIC_KEY_PATH, $config));
        $this->assertEmpty($audit->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testValidateWrongSchemeWithConfigSetToSchemaValidation(): void
    {
        $config = new ValidationConfig();
        $config->setSchemaValidate(true);
        $audit  = AuditFile::loadFile(SAFT_WRONG_SCHEME_PATH);
        $audit->getErrorRegistor()->clearAllErrors();
        $this->assertFalse($audit->validate(PUBLIC_KEY_PATH, $config));
        $this->assertNotEmpty($audit->getErrorRegistor()->getLibXmlError());
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testValidateDataNoPubKeyPathConfigYes(): void
    {
        $this->expectException(AuditFileException::class);
        $audit  = AuditFile::loadFile(SAFT_DEMO_PATH);
        $config = new ValidationConfig();
        $config->setSignValidation(true);
        $this->assertTrue($audit->validate(null, $config));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testValidateInvoiceWronSign(): void
    {
        $this->expectException(AuditFileException::class);
        $audit  = AuditFile::loadFile(SAFT_WRONG_INVOICE_SIGNATURE_PATH);
        $config = new ValidationConfig();
        $config->setSignValidation(true);
        $this->assertFalse($audit->validate(null, $config));

        $config->setSignValidation(false);
        $this->assertTrue($audit->validate(null, $config));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testValidateMovementOfGoodsWronSign(): void
    {
        $this->expectException(AuditFileException::class);
        $audit  = AuditFile::loadFile(SAFT_WRONG_MOV_GOODS_SIGNATURE_PATH);
        $config = new ValidationConfig();
        $config->setSignValidation(true);
        $this->assertFalse($audit->validate(null, $config));

        $config->setSignValidation(false);
        $this->assertTrue($audit->validate(null, $config));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testvalidateWorkingDocumentsWronSign(): void
    {
        $this->expectException(AuditFileException::class);
        $audit  = AuditFile::loadFile(SAFT_WRONG_WORKDOC_SIGNATURE_PATH);
        $config = new ValidationConfig();
        $config->setSignValidation(true);
        $this->assertFalse($audit->validate(null, $config));

        $config->setSignValidation(false);
        $this->assertTrue($audit->validate(null, $config));
    }

    /**
     * @author João Rebelo
     * @test
     */
    public function testvalidateRepeatedInternalCode(): void
    {
        $files = [
            SAFT_REPEATED_INVOICE_INTERNAL_CODE,
            SAFT_REPEATED_STOCK_MOVEMENT_INTERNAL_CODE,
            SAFT_REPEATED_WORK_DOCUMENT_INTERNAL_CODE,
            SAFT_REPEATED_PAYMENT_INTERNAL_CODE
        ];

        foreach ($files as $path) {
            $audit = AuditFile::loadFile($path);
            $this->assertFalse($audit->getErrorRegistor()->hasErrors());
            $audit->validate();
            $this->assertTrue($audit->getErrorRegistor()->hasErrors());
        }
    }

    /**
     * 
     * @param int $period
     * @param int $fiscalYearStartMonth
     * @param RDate $docDate
     * @return void
     * @author João Rebelo
     * @test
     * @dataProvider calperiodProvider
     */
    public function testCalperiod(int $period, int $fiscalYearStartMonth,
                                  RDate $docDate): void
    {
        $this->assertSame(
            /* @phpstan-ignore-next-line */
            $period, AuditFile::calcPeriod($fiscalYearStartMonth, $docDate)
        );
    }

    /**
     * 
     * @author João Rebelo
     * @return array
     */
    public function calperiodProvider(): array
    {
        return [
            [1, 1, RDate::parse(RDate::SQL_DATE, "2020-01-01")],
            [1, 1, RDate::parse(RDate::SQL_DATE, "2020-01-31")],
            [12, 1, RDate::parse(RDate::SQL_DATE, "2020-12-01")],
            [12, 1, RDate::parse(RDate::SQL_DATE, "2020-12-31")],
            [9, 1, RDate::parse(RDate::SQL_DATE, "2020-09-01")],
            [9, 1, RDate::parse(RDate::SQL_DATE, "2020-09-30")],
            [1, 10, RDate::parse(RDate::SQL_DATE, "2020-10-01")],
            [1, 10, RDate::parse(RDate::SQL_DATE, "2020-10-31")],
            [3, 10, RDate::parse(RDate::SQL_DATE, "2020-12-01")],
            [3, 10, RDate::parse(RDate::SQL_DATE, "2020-12-31")],
            [4, 10, RDate::parse(RDate::SQL_DATE, "2020-01-01")],
            [4, 10, RDate::parse(RDate::SQL_DATE, "2020-01-31")],
            [2, 12, RDate::parse(RDate::SQL_DATE, "2020-01-01")],
            [2, 12, RDate::parse(RDate::SQL_DATE, "2020-01-31")],
            [1, 12, RDate::parse(RDate::SQL_DATE, "2020-12-01")],
            [1, 12, RDate::parse(RDate::SQL_DATE, "2020-12-31")]
        ];
    }

    /**
     * 
     * @return void
     * @author João Rebelo
     * @test
     */
    public function testCalperiodException(): void
    {
        try {
            /* @phpstan-ignore-next-line */
            AuditFile::calcPeriod(0, new RDate());
            $this->fail(
                "Set fiscal year start month less then 1 shoul throw "
                ." CalcPeriodException"
            );
        } catch (\Rebelo\SaftPt\AuditFile\CalcPeriodException $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\CalcPeriodException::class, $e
            );
        }

        try {
            /* @phpstan-ignore-next-line */
            AuditFile::calcPeriod(-1, new RDate());
            $this->fail(
                "Set fiscal year start month less then 1 shoul throw "
                ." CalcPeriodException"
            );
        } catch (\Rebelo\SaftPt\AuditFile\CalcPeriodException $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\CalcPeriodException::class, $e
            );
        }

        try {
            /* @phpstan-ignore-next-line */
            AuditFile::calcPeriod(13, new RDate());
            $this->fail(
                "Set fiscal year start month greater then 12 shoul throw "
                ." CalcPeriodException"
            );
        } catch (\Rebelo\SaftPt\AuditFile\CalcPeriodException $e) {
            $this->assertInstanceOf(
                \Rebelo\SaftPt\AuditFile\CalcPeriodException::class, $e
            );
        }
    }
}
