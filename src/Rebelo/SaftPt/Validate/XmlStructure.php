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
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace Rebelo\SaftPt\Validate;

use Rebelo\SaftPt\AuditFile\AuditFile;
use Rebelo\SaftPt\Validate\Schema\Schema;

/**
 * XmlStructure
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class XmlStructure extends AValidate
{

    /**
     * Validate the xml with the xsd schema file using libxml
     * @param \Rebelo\SaftPt\AuditFile\AuditFile $auditFile
     * @since 1.0.0
     */
    public function __construct(AuditFile $auditFile)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        parent::__construct($auditFile);
    }

    /**
     * Validate the xml with the xsd schema file using libxml
     * @param string $xml
     * @return bool
     * @since 1.0.0
     */
    public function validate(string &$xml): bool
    {
        try {

            \Logger::getLogger(\get_class($this))->debug(__METHOD__);
            \libxml_use_internal_errors(true);
            \libxml_clear_errors();

            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->loadXML($xml, LIBXML_PARSEHUGE | LIBXML_BIGLINES);

            $valide = $dom->schemaValidate(Schema::GLOBAL_XSD);

            if ($valide === false) {
                $errorStack = \libxml_get_errors();
                foreach ($errorStack as $error) {
                    $msg = \sprintf(
                        "'%s' on Line '%s' column '%s'",
                        $error->message, $error->line, $error->column
                    );
                    $this->auditFile->getErrorRegistor()->addLibXmlError($msg);
                    \Logger::getLogger(\get_class($this))
                        ->debug(
                            \sprintf(__METHOD__." validate error '%s'", $msg)
                        );
                }
            }
            return $valide;
        } catch (\Exception | \Error $e) {
            $this->auditFile->getErrorRegistor()
                ->addExceptionErrors($e->getMessage());
            \Logger::getLogger(\get_class($this))
                ->debug(
                    \sprintf(
                        __METHOD__." validate error '%s'", $e->getMessage()
                    )
                );
            return false;
        }
    }
}
