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
    ExportType,
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
     *
     */
    public function testReflection()
    {
        (new \Rebelo\Test\CommnunTest())
            ->testReflection(AuditFile::class);
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testInstance()
    {
        $auditFile = new AuditFile();
        $this->assertInstanceOf(AuditFile::class, $auditFile);
        $this->assertSame(
            ExportType::C, $auditFile->getExportType()->get()
        );
        $auditFile->setExportType(new ExportType(ExportType::S));
        $this->assertSame(
            ExportType::S, $auditFile->getExportType()->get()
        );

        $auditFileSimple = new AuditFile(new ExportType(ExportType::S));
        $this->assertInstanceOf(AuditFile::class, $auditFileSimple);
        $this->assertSame(
            ExportType::S, $auditFileSimple->getExportType()->get()
        );


        $header = new Header();
        $auditFile->setHeader($header);
        $this->assertInstanceOf(Header::class, $auditFile->getHeader());

        $master = new MasterFiles();
        $auditFile->setMasterFiles($master);
        $this->assertInstanceOf(MasterFiles::class, $auditFile->getMasterFiles());

        $sourceDoc = new SourceDocuments();
        $auditFile->setSourceDocuments($sourceDoc);
        $this->assertInstanceOf(
            SourceDocuments::class, $auditFile->getSourceDocuments()
        );
    }

    /**
     * Reads all saft Demo SAFT in Test\Ressources
     * and parse then to AuditFile class, after that generate a xml from the
     * AuditFile class and test if the xml strings are equal
     */
    public function testCreateParseXml()
    {
        $saftDemoXml = \simplexml_load_file(SAFT_DEMO_PATH);

        $auditFile = new AuditFile();
        $auditFile->parseXmlNode($saftDemoXml);

        $xmlRootNode = $auditFile->createRootElement();
        $xml         = $auditFile->createXmlNode($xmlRootNode);

        try {
            $assertXml = $this->xmlIsEqual($saftDemoXml, $xml);
            $this->assertTrue($assertXml,
                \sprintf("Fail with error '%s'", $assertXml)
            );
        } catch (\Exception | \Error $e) {
            $this->fail(\sprintf("Fail with error '%s'", $e->getMessage()));
        }
    }
}