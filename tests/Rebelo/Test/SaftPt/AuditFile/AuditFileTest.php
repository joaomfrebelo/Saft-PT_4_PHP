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
    MasterFiles\MasterFiles,
    SourceDocuments\SourceDocuments
};

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
            return;
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($saftDemoXml);

        $xmlRootNode = $auditFile->createRootElement();
        $xml         = $auditFile->createXmlNode($xmlRootNode);

        try {
            $assertXml = $this->xmlIsEqual($saftDemoXml, $xml);
            $this->assertTrue(
                $assertXml,
                \sprintf("Fail with error '%s'", $assertXml)
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
            return;
        }

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($saftDemoXml);

        try {
            $assertXml = $this->xmlIsEqual(
                $saftDemoXml, new \SimpleXMLElement($auditFile->toXmlString())
            );

            $this->assertTrue(
                $assertXml,
                \sprintf("Fail with error '%s'", $assertXml)
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

        $this->assertNotEmpty(
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
            return;
        }

        try {
            $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);
            if ($saftDemoXml === false) {
                $this->fail(\sprintf("Fail load xml file '%s'", SAFT_DEMO_PATH));
                return;
            }

            $auditFile = new AuditFile();
            $auditFile->parseXmlNode($saftDemoXml);
            $auditFile->toFile($auditXmlFile);

            $simXmlToCompare = \simplexml_load_file($auditXmlFile);
            if ($simXmlToCompare === false) {
                $this->fail(
                    \sprintf("Fail loading temp file  '%s'", $auditXmlFile)
                );
                return;
            }

            $assertXml = $this->xmlIsEqual(
                $saftDemoXml, $simXmlToCompare
            );

            $this->assertTrue(
                $assertXml,
                \sprintf("Fail with error '%s'", $assertXml)
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
        $auditXmlFile = tempnam(sys_get_temp_dir(), 'saft');
        if ($auditXmlFile === false) {
            $this->fail("Fail to create temp file");
            return;
        }

        try {
            $auditFile = new AuditFile();
            $auditFile->toFile($auditXmlFile);
            $node      = \simplexml_load_file($auditXmlFile);
            if ($node === false) {
                $this->fail(
                    \sprintf("Fail loading temp file  '%s'", $auditXmlFile)
                );
                return;
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

            $this->assertNotEmpty(
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
}