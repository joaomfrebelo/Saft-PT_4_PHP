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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments;

use Rebelo\Enum\AEnum;
use Rebelo\Enum\EnumException;

/**
 * CurrencyCode
 *
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_AED()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_AFN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ALL()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_AMD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ANG()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_AOA()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ARS()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_AUD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_AWG()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_AZN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BAM()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BBD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BDT()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BGN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BHD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BIF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BMD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BND()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BOB()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BOV()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BRL()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BSD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BTN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BWP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BYN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BYR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_BZD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CAD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CDF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CHE()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CHF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CHW()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CLF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CLP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CNY()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_COP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_COU()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CRC()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CUC()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CUP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CVE()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_CZK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_DJF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_DKK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_DOP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_DZD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_EGP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ERN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ETB()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_FJD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_FKP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_GBP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_GEL()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_GHS()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_GIP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_GMD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_GNF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_GTQ()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_GYD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_HKD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_HNL()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_HRK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_HTG()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_HUF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_IDR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ILS()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_INR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_IQD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ISK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_JMD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_JOD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_JPY()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_KES()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_KGS()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_KHR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_KMF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_KPW()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_KRW()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_KWD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_KYD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_KZT()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_LAK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_LBP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_LKR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_LRD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_LSL()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_LTL()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_LVL()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_LYD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MAD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MDL()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MGA()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MKD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MMK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MNT()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MOP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MRO()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MRU()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MUR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MVR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MWK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MXN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MXV()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MYR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_MZN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_NAD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_NGN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_NIO()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_NOK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_NPR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_NZD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_OMR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_PAB()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_PEN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_PGK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_PHP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_PKR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_PLN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_PYG()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_QAR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_RON()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_RSD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_RUB()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_RWF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SAR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SBD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SCR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SDG()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SEK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SGD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SHP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SLL()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SOS()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SRD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SSP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_STD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_STN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SVC()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SYP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SZL()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_THB()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_TJS()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_TMT()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_TND()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_TOP()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_TRY()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_TTD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_TWD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_TZS()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_UAH()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_UGX()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_USD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_USN()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_USS()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_UYI()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_UYU()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_UZS()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_VEF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_VES()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_VND()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_VUV()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_WST()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XAF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XAG()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XAU()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XBA()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XBB()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XBC()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XBD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XCD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XDR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XFU()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XOF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XPD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XPF()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XPT()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XSU()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_XUA()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_YER()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ZAR()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ZMW()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ZWL()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_EEK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_SKK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_TMM()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ZMK()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ZWD()
 * @method static \Rebelo\SaftPt\AuditFile\SourceDocuments\CurrencyCode ISO_ZWR()
 *
 * @author João Rebelo
 */
class CurrencyCode extends AEnum
{
    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_AED = "AED";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_AFN = "AFN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ALL = "ALL";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_AMD = "AMD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ANG = "ANG";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_AOA = "AOA";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ARS = "ARS";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_AUD = "AUD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_AWG = "AWG";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_AZN = "AZN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BAM = "BAM";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BBD = "BBD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BDT = "BDT";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BGN = "BGN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BHD = "BHD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BIF = "BIF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BMD = "BMD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BND = "BND";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BOB = "BOB";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BOV = "BOV";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BRL = "BRL";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BSD = "BSD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BTN = "BTN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BWP = "BWP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BYN = "BYN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BYR = "BYR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_BZD = "BZD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CAD = "CAD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CDF = "CDF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CHE = "CHE";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CHF = "CHF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CHW = "CHW";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CLF = "CLF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CLP = "CLP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CNY = "CNY";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_COP = "COP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_COU = "COU";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CRC = "CRC";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CUC = "CUC";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CUP = "CUP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CVE = "CVE";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_CZK = "CZK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_DJF = "DJF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_DKK = "DKK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_DOP = "DOP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_DZD = "DZD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_EGP = "EGP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ERN = "ERN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ETB = "ETB";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_FJD = "FJD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_FKP = "FKP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_GBP = "GBP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_GEL = "GEL";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_GHS = "GHS";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_GIP = "GIP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_GMD = "GMD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_GNF = "GNF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_GTQ = "GTQ";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_GYD = "GYD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_HKD = "HKD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_HNL = "HNL";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_HRK = "HRK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_HTG = "HTG";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_HUF = "HUF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_IDR = "IDR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ILS = "ILS";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_INR = "INR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_IQD = "IQD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_IRR = "IRR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ISK = "ISK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_JMD = "JMD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_JOD = "JOD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_JPY = "JPY";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_KES = "KES";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_KGS = "KGS";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_KHR = "KHR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_KMF = "KMF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_KPW = "KPW";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_KRW = "KRW";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_KWD = "KWD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_KYD = "KYD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_KZT = "KZT";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_LAK = "LAK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_LBP = "LBP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_LKR = "LKR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_LRD = "LRD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_LSL = "LSL";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_LTL = "LTL";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_LVL = "LVL";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_LYD = "LYD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MAD = "MAD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MDL = "MDL";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MGA = "MGA";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MKD = "MKD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MMK = "MMK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MNT = "MNT";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MOP = "MOP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MRO = "MRO";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MRU = "MRU";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MUR = "MUR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MVR = "MVR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MWK = "MWK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MXN = "MXN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MXV = "MXV";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MYR = "MYR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_MZN = "MZN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_NAD = "NAD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_NGN = "NGN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_NIO = "NIO";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_NOK = "NOK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_NPR = "NPR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_NZD = "NZD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_OMR = "OMR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_PAB = "PAB";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_PEN = "PEN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_PGK = "PGK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_PHP = "PHP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_PKR = "PKR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_PLN = "PLN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_PYG = "PYG";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_QAR = "QAR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_RON = "RON";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_RSD = "RSD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_RUB = "RUB";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_RWF = "RWF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SAR = "SAR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SBD = "SBD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SCR = "SCR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SDG = "SDG";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SEK = "SEK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SGD = "SGD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SHP = "SHP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SLL = "SLL";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SOS = "SOS";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SRD = "SRD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SSP = "SSP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_STD = "STD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_STN = "STN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SVC = "SVC";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SYP = "SYP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SZL = "SZL";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_THB = "THB";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_TJS = "TJS";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_TMT = "TMT";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_TND = "TND";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_TOP = "TOP";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_TRY = "TRY";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_TTD = "TTD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_TWD = "TWD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_TZS = "TZS";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_UAH = "UAH";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_UGX = "UGX";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_USD = "USD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_USN = "USN";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_USS = "USS";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_UYI = "UYI";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_UYU = "UYU";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_UZS = "UZS";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_VEF = "VEF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_VES = "VES";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_VND = "VND";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_VUV = "VUV";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_WST = "WST";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XAF = "XAF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XAG = "XAG";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XAU = "XAU";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XBA = "XBA";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XBB = "XBB";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XBC = "XBC";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XBD = "XBD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XCD = "XCD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XDR = "XDR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XFU = "XFU";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XOF = "XOF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XPD = "XPD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XPF = "XPF";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XPT = "XPT";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XSU = "XSU";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_XUA = "XUA";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_YER = "YER";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ZAR = "ZAR";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ZMW = "ZMW";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ZWL = "ZWL";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_EEK = "EEK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_SKK = "SKK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_TMM = "TMM";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ZMK = "ZMK";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ZWD = "ZWD";

    /**
     * Iso code
     * @since 1.0.0
     */
    const ISO_ZWR = "ZWR";

    /**
     * CurrencyCode (ISO 4217)<br>
     * <pre>
     *   &lt;xs:element name="CurrencyCode"&gt;
     *       &lt;!-- Nao consta o EUR por nao existirem situacoes que requeiram este codigo de moeda --&gt;
     *       &lt;xs:simpleType&gt;
     *           &lt;xs:restriction base="xs:string"&gt;
     *               &lt;xs:pattern
     *                   value="AED|AFN|ALL|AMD|ANG|AOA|ARS|AUD|AWG|AZN|BAM|BBD|BDT|BGN|BHD|BIF|BMD|BND|BOB|BOV|BRL|BSD|BTN|BWP|BYN|BYR|BZD|CAD|CDF|CHE|CHF|CHW|CLF|CLP|CNY|COP|COU|CRC|CUC|CUP|CVE|CZK|DJF|DKK|DOP|DZD|EGP|ERN|ETB|FJD|FKP|GBP|GEL|GHS|GIP|GMD|GNF|GTQ|GYD|HKD|HNL|HRK|HTG|HUF|IDR|ILS|INR|IQD|IRR|ISK|JMD|JOD|JPY|KES|KGS|KHR|KMF|KPW|KRW|KWD|KYD|KZT|LAK|LBP|LKR|LRD|LSL|LTL|LVL|LYD|MAD|MDL|MGA|MKD|MMK|MNT|MOP|MRO|MRU|MUR|MVR|MWK|MXN|MXV|MYR|MZN|NAD|NGN|NIO|NOK|NPR|NZD|OMR|PAB|PEN|PGK|PHP|PKR|PLN|PYG|QAR|RON|RSD|RUB|RWF|SAR|SBD|SCR|SDG|SEK|SGD|SHP|SLL|SOS|SRD|SSP|STD|STN|SVC|SYP|SZL|THB|TJS|TMT|TND|TOP|TRY|TTD|TWD|TZS|UAH|UGX|USD|USN|USS|UYI|UYU|UZS|VEF|VES|VND|VUV|WST|XAF|XAG|XAU|XBA|XBB|XBC|XBD|XCD|XDR|XFU|XOF|XPD|XPF|XPT|XSU|XUA|YER|ZAR|ZMW|ZWL|EEK|SKK|TMM|ZMK|ZWD|ZWR"/&gt;
     *               &lt;xs:length value="3"/&gt;
     *           &lt;/xs:restriction&gt;
     *       &lt;/xs:simpleType&gt;
     *   &lt;/xs:element&gt;
     * </pre>
     * @param string $value
     * @throws EnumException
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * Get the value as string
     * @return string
     * @since 1.0.0
     */
    public function get(): string
    {
        return (string) parent::get();
    }
}
