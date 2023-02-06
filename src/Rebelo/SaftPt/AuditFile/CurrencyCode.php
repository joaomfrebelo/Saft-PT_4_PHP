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

namespace Rebelo\SaftPt\AuditFile;

use Rebelo\Enum\EnumException;

/**
 * CurrencyCode<br>
 * <xs:simpleType><br>
 * <xs:restriction base="xs:string"><br>
 * <xs:pattern value="..."/><br>
 * <xs:length value="3"/><br>
 * </xs:restriction><br>
 * </xs:simpleType><br>
 * @author João Rebelo
 * @since 1.0.0
 */
class CurrencyCode extends \Rebelo\Enum\AEnum
{
    const ISO_AED = "AED";
    const ISO_AFN = "AFN";
    const ISO_ALL = "ALL";
    const ISO_AMD = "AMD";
    const ISO_ANG = "ANG";
    const ISO_AOA = "AOA";
    const ISO_ARS = "ARS";
    const ISO_AUD = "AUD";
    const ISO_AWG = "AWG";
    const ISO_AZN = "AZN";
    const ISO_BAM = "BAM";
    const ISO_BBD = "BBD";
    const ISO_BDT = "BDT";
    const ISO_BGN = "BGN";
    const ISO_BHD = "BHD";
    const ISO_BIF = "BIF";
    const ISO_BMD = "BMD";
    const ISO_BND = "BND";
    const ISO_BOB = "BOB";
    const ISO_BOV = "BOV";
    const ISO_BRL = "BRL";
    const ISO_BSD = "BSD";
    const ISO_BTN = "BTN";
    const ISO_BWP = "BWP";
    const ISO_BYN = "BYN";
    const ISO_BYR = "BYR";
    const ISO_BZD = "BZD";
    const ISO_CAD = "CAD";
    const ISO_CDF = "CDF";
    const ISO_CHE = "CHE";
    const ISO_CHF = "CHF";
    const ISO_CHW = "CHW";
    const ISO_CLF = "CLF";
    const ISO_CLP = "CLP";
    const ISO_CNY = "CNY";
    const ISO_COP = "COP";
    const ISO_COU = "COU";
    const ISO_CRC = "CRC";
    const ISO_CUC = "CUC";
    const ISO_CUP = "CUP";
    const ISO_CVE = "CVE";
    const ISO_CZK = "CZK";
    const ISO_DJF = "DJF";
    const ISO_DKK = "DKK";
    const ISO_DOP = "DOP";
    const ISO_DZD = "DZD";
    const ISO_EGP = "EGP";
    const ISO_ERN = "ERN";
    const ISO_ETB = "ETB";
    const ISO_FJD = "FJD";
    const ISO_FKP = "FKP";
    const ISO_GBP = "GBP";
    const ISO_GEL = "GEL";
    const ISO_GHS = "GHS";
    const ISO_GIP = "GIP";
    const ISO_GMD = "GMD";
    const ISO_GNF = "GNF";
    const ISO_GTQ = "GTQ";
    const ISO_GYD = "GYD";
    const ISO_HKD = "HKD";
    const ISO_HNL = "HNL";
    const ISO_HRK = "HRK";
    const ISO_HTG = "HTG";
    const ISO_HUF = "HUF";
    const ISO_IDR = "IDR";
    const ISO_ILS = "ILS";
    const ISO_INR = "INR";
    const ISO_IQD = "IQD";
    const ISO_IRR = "IRR";
    const ISO_ISK = "ISK";
    const ISO_JMD = "JMD";
    const ISO_JOD = "JOD";
    const ISO_JPY = "JPY";
    const ISO_KES = "KES";
    const ISO_KGS = "KGS";
    const ISO_KHR = "KHR";
    const ISO_KMF = "KMF";
    const ISO_KPW = "KPW";
    const ISO_KRW = "KRW";
    const ISO_KWD = "KWD";
    const ISO_KYD = "KYD";
    const ISO_KZT = "KZT";
    const ISO_LAK = "LAK";
    const ISO_LBP = "LBP";
    const ISO_LKR = "LKR";
    const ISO_LRD = "LRD";
    const ISO_LSL = "LSL";
    const ISO_LTL = "LTL";
    const ISO_LVL = "LVL";
    const ISO_LYD = "LYD";
    const ISO_MAD = "MAD";
    const ISO_MDL = "MDL";
    const ISO_MGA = "MGA";
    const ISO_MKD = "MKD";
    const ISO_MMK = "MMK";
    const ISO_MNT = "MNT";
    const ISO_MOP = "MOP";
    const ISO_MRO = "MRO";
    const ISO_MRU = "MRU";
    const ISO_MUR = "MUR";
    const ISO_MVR = "MVR";
    const ISO_MWK = "MWK";
    const ISO_MXN = "MXN";
    const ISO_MXV = "MXV";
    const ISO_MYR = "MYR";
    const ISO_MZN = "MZN";
    const ISO_NAD = "NAD";
    const ISO_NGN = "NGN";
    const ISO_NIO = "NIO";
    const ISO_NOK = "NOK";
    const ISO_NPR = "NPR";
    const ISO_NZD = "NZD";
    const ISO_OMR = "OMR";
    const ISO_PAB = "PAB";
    const ISO_PEN = "PEN";
    const ISO_PGK = "PGK";
    const ISO_PHP = "PHP";
    const ISO_PKR = "PKR";
    const ISO_PLN = "PLN";
    const ISO_PYG = "PYG";
    const ISO_QAR = "QAR";
    const ISO_RON = "RON";
    const ISO_RSD = "RSD";
    const ISO_RUB = "RUB";
    const ISO_RWF = "RWF";
    const ISO_SAR = "SAR";
    const ISO_SBD = "SBD";
    const ISO_SCR = "SCR";
    const ISO_SDG = "SDG";
    const ISO_SEK = "SEK";
    const ISO_SGD = "SGD";
    const ISO_SHP = "SHP";
    const ISO_SLL = "SLL";
    const ISO_SOS = "SOS";
    const ISO_SRD = "SRD";
    const ISO_SSP = "SSP";
    const ISO_STD = "STD";
    const ISO_STN = "STN";
    const ISO_SVC = "SVC";
    const ISO_SYP = "SYP";
    const ISO_SZL = "SZL";
    const ISO_THB = "THB";
    const ISO_TJS = "TJS";
    const ISO_TMT = "TMT";
    const ISO_TND = "TND";
    const ISO_TOP = "TOP";
    const ISO_TRY = "TRY";
    const ISO_TTD = "TTD";
    const ISO_TWD = "TWD";
    const ISO_TZS = "TZS";
    const ISO_UAH = "UAH";
    const ISO_UGX = "UGX";
    const ISO_USD = "USD";
    const ISO_USN = "USN";
    const ISO_USS = "USS";
    const ISO_UYI = "UYI";
    const ISO_UYU = "UYU";
    const ISO_UZS = "UZS";
    const ISO_VEF = "VEF";
    const ISO_VES = "VES";
    const ISO_VND = "VND";
    const ISO_VUV = "VUV";
    const ISO_WST = "WST";
    const ISO_XAF = "XAF";
    const ISO_XAG = "XAG";
    const ISO_XAU = "XAU";
    const ISO_XBA = "XBA";
    const ISO_XBB = "XBB";
    const ISO_XBC = "XBC";
    const ISO_XBD = "XBD";
    const ISO_XCD = "XCD";
    const ISO_XDR = "XDR";
    const ISO_XFU = "XFU";
    const ISO_XOF = "XOF";
    const ISO_XPD = "XPD";
    const ISO_XPF = "XPF";
    const ISO_XPT = "XPT";
    const ISO_XSU = "XSU";
    const ISO_XUA = "XUA";
    const ISO_YER = "YER";
    const ISO_ZAR = "ZAR";
    const ISO_ZMW = "ZMW";
    const ISO_ZWL = "ZWL";
    const ISO_EEK = "EEK";
    const ISO_SKK = "SKK";
    const ISO_TMM = "TMM";
    const ISO_ZMK = "ZMK";
    const ISO_ZWD = "ZWD";
    const ISO_ZWR = "ZWR";

	/**
	 *
	 * @param string $value
	 * @throws EnumException
	 * @since 1.0.0
	 */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}
