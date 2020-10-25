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

namespace Rebelo\SaftPt\Sign;

use Rebelo\Date\Date as RDate;

/**
 * Create/verify the hash of signature
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Sign
{
    /**
     *
     * Private key to create the hash sign
     *
     * @var string
     * @since 1.0.0
     */
    private string $private;

    /**
     *
     * Public key to verify the hash sign
     *
     * @var string
     * @since 1.0.0
     */
    private string $public;

    /**
     *
     * @param string|null $privateKey
     * @param string|null $publicKey
     * @since 1.0.0
     */
    public function __construct(?string $privateKey = null,
                                ?string $publicKey = null)
    {
        if ($privateKey !== null) {
            $this->setPrivateKey($privateKey);
        }
        if ($publicKey !== null) {
            $this->setPublicKey($publicKey);
        }
    }

    /**
     * Set the private key to create the hash sign
     * @param string $privatekey
     * @return void
     * @since 1.0.0
     */
    public function setPrivateKey(string $privatekey): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->private = $privatekey;
    }

    /**
     * Set the public key to verify the hash sign
     * @param string $publicKey
     * @return void
     * @since 1.0.0
     */
    public function setPublicKey(string $publicKey): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->public = $publicKey;
    }

    /**
     * Set the private key file path to create the hash sign
     * @param string $path
     * @return void
     * @since 1.0.0
     */
    public function setPrivateKeyFilePath(string $path): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $key = \file_get_contents($path);
        if ($key === false) {
            $msg = \sprintf("Private key file path error: '%s'", $path);
            \Logger::getLogger(\get_class($this))
                ->error(
                    \sprintf(__METHOD__." '%s'", $msg)
                );
            throw new SignException($msg);
        }
        $this->private = $key;
    }

    /**
     * Set the public key file path to verify the hash sign
     * @param string $path
     * @return void
     * @since 1.0.0
     */
    public function setPublicKeyFilePath(string $path): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $key = \file_get_contents($path);
        if ($key === false) {
            $msg = \sprintf("Public key file path error: '%s'", $path);
            \Logger::getLogger(\get_class($this))
                ->error(
                    \sprintf(__METHOD__." '%s'", $msg)
                );
            throw new SignException($msg);
        }
        $this->public = $key;
    }

    /**
     * Create the string to be sign or verified
     * @param RDate $docDate The document date
     * @param RDate $systemEntryDate The System Entry Date of the document
     * @param string $doc The document identifier EX: FT FT/1
     * @param float $grossTotal The document gross total
     * @param string|null $lastHash The hash of the last document of the same serie, if this is the first document in the serie pass null or empty string
     * @return string
     * @since 1.0.0
     */
    protected function creatString2Sign(RDate $docDate, RDate $systemEntryDate,
                                        string $doc, float $grossTotal,
                                        ?string $lastHash = null): string
    {
        return \sprintf(
            "%s;%s;%s;%s;%s", $docDate->format(RDate::SQL_DATE),
            $systemEntryDate->format(RDate::DATE_T_TIME), $doc,
            \number_format($grossTotal, 2, ".", ""),
            $lastHash === null ? "" : $lastHash
        );
    }

    /**
     *
     * @param RDate $docDate The document date
     * @param RDate $systemEntryDate The System Entry Date of the document
     * @param string $doc The document identifier EX: FT FT/1
     * @param float $grossTotal The document gross total
     * @param string|null $lastHash The hash of the last document of the same serie, if this is the first document in the serie pass null or empty string
     * @return string
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @since 1.0.0
     */
    public function createSignature(RDate $docDate, RDate $systemEntryDate,
                                    string $doc, float $grossTotal,
                                    ?string $lastHash = null): string
    {

        if (isset($this->private) === false) {
            $msg = "Private key not setted";
            \Logger::getLogger(\get_class($this))
                ->error(
                    \sprintf(__METHOD__." '%s'", $msg)
                );
            throw new SignException($msg);
        }

        $str2sign = $this->creatString2Sign(
            $docDate, $systemEntryDate, $doc, $grossTotal, $lastHash
        );

        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(__METHOD__." create sign hash for '%s'", $str2sign)
            );

        $signature = "";
        $resPriKey = openssl_get_privatekey($this->private);

        if ($resPriKey === false) {
            throw new SignException("Error getting private key");
        }

        openssl_sign(
            $str2sign, $signature, $resPriKey, OPENSSL_ALGO_SHA1
        );
        return base64_encode($signature);
    }

    /**
     * Verify is the signature is valid
     * @param string $hash The hash signature to be verified
     * @param RDate $docDate The document date
     * @param RDate $systemEntryDate The System Entry Date of the document
     * @param string $doc The document identifier EX: FT FT/1
     * @param float $grossTotal The document gross total
     * @param string|null $lastHash The hash of the last document of the same serie, if this is the first document in the serie pass null or empty string
     * @return bool
     * @throws \Rebelo\SaftPt\Sign\SignException
     * @since 1.0.0
     */
    public function verifySignature(string $hash, RDate $docDate,
                                    RDate $systemEntryDate, string $doc,
                                    float $grossTotal, ?string $lastHash = null): bool
    {

        if (isset($this->public) === false) {
            $msg = "Public key not setted";
            \Logger::getLogger(\get_class($this))
                ->error(
                    \sprintf(__METHOD__." '%s'", $msg)
                );
            throw new SignException($msg);
        }

        $str2sign = $this->creatString2Sign(
            $docDate, $systemEntryDate, $doc, $grossTotal, $lastHash
        );

        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(__METHOD__." verify sign hash for '%s'", $str2sign)
            );

        $resPubKey = openssl_get_publickey($this->public);

        if ($resPubKey === false) {
            throw new SignException("Error getting public key");
        }

        return openssl_verify(
            $str2sign, base64_decode($hash), $resPubKey, OPENSSL_ALGO_SHA1
        ) === 1;
    }
}