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

/**
 * CurrencyCode<br>
 * <xs:simpleType><br>
 * <xs:restriction base="xs:string"><br>
 * <xs:pattern value="..."/><br>
 * <xs:length value="3"/><br>
 * </xs:restriction><br>
 * </xs:simpleType><br>
 * @author João Rebelo
 * @since 3.0.0
 */
enum CurrencyCode : string
{
    case  ISO_AED = "AED";
    case  ISO_AFN = "AFN";
    case  ISO_ALL = "ALL";
    case  ISO_AMD = "AMD";
    case  ISO_ANG = "ANG";
    case  ISO_AOA = "AOA";
    case  ISO_ARS = "ARS";
    case  ISO_AUD = "AUD";
    case  ISO_AWG = "AWG";
    case  ISO_AZN = "AZN";
    case  ISO_BAM = "BAM";
    case  ISO_BBD = "BBD";
    case  ISO_BDT = "BDT";
    case  ISO_BGN = "BGN";
    case  ISO_BHD = "BHD";
    case  ISO_BIF = "BIF";
    case  ISO_BMD = "BMD";
    case  ISO_BND = "BND";
    case  ISO_BOB = "BOB";
    case  ISO_BOV = "BOV";
    case  ISO_BRL = "BRL";
    case  ISO_BSD = "BSD";
    case  ISO_BTN = "BTN";
    case  ISO_BWP = "BWP";
    case  ISO_BYN = "BYN";
    case  ISO_BYR = "BYR";
    case  ISO_BZD = "BZD";
    case  ISO_CAD = "CAD";
    case  ISO_CDF = "CDF";
    case  ISO_CHE = "CHE";
    case  ISO_CHF = "CHF";
    case  ISO_CHW = "CHW";
    case  ISO_CLF = "CLF";
    case  ISO_CLP = "CLP";
    case  ISO_CNY = "CNY";
    case  ISO_COP = "COP";
    case  ISO_COU = "COU";
    case  ISO_CRC = "CRC";
    case  ISO_CUC = "CUC";
    case  ISO_CUP = "CUP";
    case  ISO_CVE = "CVE";
    case  ISO_CZK = "CZK";
    case  ISO_DJF = "DJF";
    case  ISO_DKK = "DKK";
    case  ISO_DOP = "DOP";
    case  ISO_DZD = "DZD";
    case  ISO_EGP = "EGP";
    case  ISO_ERN = "ERN";
    case  ISO_ETB = "ETB";
    case  ISO_FJD = "FJD";
    case  ISO_FKP = "FKP";
    case  ISO_GBP = "GBP";
    case  ISO_GEL = "GEL";
    case  ISO_GHS = "GHS";
    case  ISO_GIP = "GIP";
    case  ISO_GMD = "GMD";
    case  ISO_GNF = "GNF";
    case  ISO_GTQ = "GTQ";
    case  ISO_GYD = "GYD";
    case  ISO_HKD = "HKD";
    case  ISO_HNL = "HNL";
    case  ISO_HRK = "HRK";
    case  ISO_HTG = "HTG";
    case  ISO_HUF = "HUF";
    case  ISO_IDR = "IDR";
    case  ISO_ILS = "ILS";
    case  ISO_INR = "INR";
    case  ISO_IQD = "IQD";
    case  ISO_IRR = "IRR";
    case  ISO_ISK = "ISK";
    case  ISO_JMD = "JMD";
    case  ISO_JOD = "JOD";
    case  ISO_JPY = "JPY";
    case  ISO_KES = "KES";
    case  ISO_KGS = "KGS";
    case  ISO_KHR = "KHR";
    case  ISO_KMF = "KMF";
    case  ISO_KPW = "KPW";
    case  ISO_KRW = "KRW";
    case  ISO_KWD = "KWD";
    case  ISO_KYD = "KYD";
    case  ISO_KZT = "KZT";
    case  ISO_LAK = "LAK";
    case  ISO_LBP = "LBP";
    case  ISO_LKR = "LKR";
    case  ISO_LRD = "LRD";
    case  ISO_LSL = "LSL";
    case  ISO_LTL = "LTL";
    case  ISO_LVL = "LVL";
    case  ISO_LYD = "LYD";
    case  ISO_MAD = "MAD";
    case  ISO_MDL = "MDL";
    case  ISO_MGA = "MGA";
    case  ISO_MKD = "MKD";
    case  ISO_MMK = "MMK";
    case  ISO_MNT = "MNT";
    case  ISO_MOP = "MOP";
    case  ISO_MRO = "MRO";
    case  ISO_MRU = "MRU";
    case  ISO_MUR = "MUR";
    case  ISO_MVR = "MVR";
    case  ISO_MWK = "MWK";
    case  ISO_MXN = "MXN";
    case  ISO_MXV = "MXV";
    case  ISO_MYR = "MYR";
    case  ISO_MZN = "MZN";
    case  ISO_NAD = "NAD";
    case  ISO_NGN = "NGN";
    case  ISO_NIO = "NIO";
    case  ISO_NOK = "NOK";
    case  ISO_NPR = "NPR";
    case  ISO_NZD = "NZD";
    case  ISO_OMR = "OMR";
    case  ISO_PAB = "PAB";
    case  ISO_PEN = "PEN";
    case  ISO_PGK = "PGK";
    case  ISO_PHP = "PHP";
    case  ISO_PKR = "PKR";
    case  ISO_PLN = "PLN";
    case  ISO_PYG = "PYG";
    case  ISO_QAR = "QAR";
    case  ISO_RON = "RON";
    case  ISO_RSD = "RSD";
    case  ISO_RUB = "RUB";
    case  ISO_RWF = "RWF";
    case  ISO_SAR = "SAR";
    case  ISO_SBD = "SBD";
    case  ISO_SCR = "SCR";
    case  ISO_SDG = "SDG";
    case  ISO_SEK = "SEK";
    case  ISO_SGD = "SGD";
    case  ISO_SHP = "SHP";
    case  ISO_SLL = "SLL";
    case  ISO_SOS = "SOS";
    case  ISO_SRD = "SRD";
    case  ISO_SSP = "SSP";
    case  ISO_STD = "STD";
    case  ISO_STN = "STN";
    case  ISO_SVC = "SVC";
    case  ISO_SYP = "SYP";
    case  ISO_SZL = "SZL";
    case  ISO_THB = "THB";
    case  ISO_TJS = "TJS";
    case  ISO_TMT = "TMT";
    case  ISO_TND = "TND";
    case  ISO_TOP = "TOP";
    case  ISO_TRY = "TRY";
    case  ISO_TTD = "TTD";
    case  ISO_TWD = "TWD";
    case  ISO_TZS = "TZS";
    case  ISO_UAH = "UAH";
    case  ISO_UGX = "UGX";
    case  ISO_USD = "USD";
    case  ISO_USN = "USN";
    case  ISO_USS = "USS";
    case  ISO_UYI = "UYI";
    case  ISO_UYU = "UYU";
    case  ISO_UZS = "UZS";
    case  ISO_VEF = "VEF";
    case  ISO_VES = "VES";
    case  ISO_VND = "VND";
    case  ISO_VUV = "VUV";
    case  ISO_WST = "WST";
    case  ISO_XAF = "XAF";
    case  ISO_XAG = "XAG";
    case  ISO_XAU = "XAU";
    case  ISO_XBA = "XBA";
    case  ISO_XBB = "XBB";
    case  ISO_XBC = "XBC";
    case  ISO_XBD = "XBD";
    case  ISO_XCD = "XCD";
    case  ISO_XDR = "XDR";
    case  ISO_XFU = "XFU";
    case  ISO_XOF = "XOF";
    case  ISO_XPD = "XPD";
    case  ISO_XPF = "XPF";
    case  ISO_XPT = "XPT";
    case  ISO_XSU = "XSU";
    case  ISO_XUA = "XUA";
    case  ISO_YER = "YER";
    case  ISO_ZAR = "ZAR";
    case  ISO_ZMW = "ZMW";
    case  ISO_ZWL = "ZWL";
    case  ISO_EEK = "EEK";
    case  ISO_SKK = "SKK";
    case  ISO_TMM = "TMM";
    case  ISO_ZMK = "ZMK";
    case  ISO_ZWD = "ZWD";
    case  ISO_ZWR = "ZWR";

}
