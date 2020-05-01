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

namespace Rebelo\SaftPt\AuditFile;

/**
 * ACountry
 *
 * @author João Rebelo
 */
abstract class ACountry extends \Rebelo\Enum\AEnum
{
    const ISO_AD = "AD";
    const ISO_AE = "AE";
    const ISO_AF = "AF";
    const ISO_AG = "AG";
    const ISO_AI = "AI";
    const ISO_AL = "AL";
    const ISO_AM = "AM";
    const ISO_AO = "AO";
    const ISO_AQ = "AQ";
    const ISO_AR = "AR";
    const ISO_AS = "AS";
    const ISO_AT = "AT";
    const ISO_AU = "AU";
    const ISO_AW = "AW";
    const ISO_AX = "AX";
    const ISO_AZ = "AZ";
    const ISO_BA = "BA";
    const ISO_BB = "BB";
    const ISO_BD = "BD";
    const ISO_BE = "BE";
    const ISO_BF = "BF";
    const ISO_BG = "BG";
    const ISO_BH = "BH";
    const ISO_BI = "BI";
    const ISO_BJ = "BJ";
    const ISO_BL = "BL";
    const ISO_BM = "BM";
    const ISO_BN = "BN";
    const ISO_BO = "BO";
    const ISO_BQ = "BQ";
    const ISO_BR = "BR";
    const ISO_BS = "BS";
    const ISO_BT = "BT";
    const ISO_BV = "BV";
    const ISO_BW = "BW";
    const ISO_BY = "BY";
    const ISO_BZ = "BZ";
    const ISO_CA = "CA";
    const ISO_CC = "CC";
    const ISO_CD = "CD";
    const ISO_CF = "CF";
    const ISO_CG = "CG";
    const ISO_CH = "CH";
    const ISO_CI = "CI";
    const ISO_CK = "CK";
    const ISO_CL = "CL";
    const ISO_CM = "CM";
    const ISO_CN = "CN";
    const ISO_CO = "CO";
    const ISO_CR = "CR";
    const ISO_CU = "CU";
    const ISO_CV = "CV";
    const ISO_CW = "CW";
    const ISO_CX = "CX";
    const ISO_CY = "CY";
    const ISO_CZ = "CZ";
    const ISO_DE = "DE";
    const ISO_DJ = "DJ";
    const ISO_DK = "DK";
    const ISO_DM = "DM";
    const ISO_DO = "DO";
    const ISO_DZ = "DZ";
    const ISO_EC = "EC";
    const ISO_EE = "EE";
    const ISO_EG = "EG";
    const ISO_EH = "EH";
    const ISO_ER = "ER";
    const ISO_ES = "ES";
    const ISO_ET = "ET";
    const ISO_FI = "FI";
    const ISO_FJ = "FJ";
    const ISO_FK = "FK";
    const ISO_FM = "FM";
    const ISO_FO = "FO";
    const ISO_FR = "FR";
    const ISO_GA = "GA";
    const ISO_GB = "GB";
    const ISO_GD = "GD";
    const ISO_GE = "GE";
    const ISO_GF = "GF";
    const ISO_GG = "GG";
    const ISO_GH = "GH";
    const ISO_GI = "GI";
    const ISO_GL = "GL";
    const ISO_GM = "GM";
    const ISO_GN = "GN";
    const ISO_GP = "GP";
    const ISO_GQ = "GQ";
    const ISO_GR = "GR";
    const ISO_GS = "GS";
    const ISO_GT = "GT";
    const ISO_GU = "GU";
    const ISO_GW = "GW";
    const ISO_GY = "GY";
    const ISO_HK = "HK";
    const ISO_HM = "HM";
    const ISO_HN = "HN";
    const ISO_HR = "HR";
    const ISO_HT = "HT";
    const ISO_HU = "HU";
    const ISO_ID = "ID";
    const ISO_IE = "IE";
    const ISO_IL = "IL";
    const ISO_IM = "IM";
    const ISO_IN = "IN";
    const ISO_IO = "IO";
    const ISO_IQ = "IQ";
    const ISO_IR = "IR";
    const ISO_IS = "IS";
    const ISO_IT = "IT";
    const ISO_JE = "JE";
    const ISO_JM = "JM";
    const ISO_JO = "JO";
    const ISO_JP = "JP";
    const ISO_KE = "KE";
    const ISO_KG = "KG";
    const ISO_KH = "KH";
    const ISO_KI = "KI";
    const ISO_KM = "KM";
    const ISO_KN = "KN";
    const ISO_KP = "KP";
    const ISO_KR = "KR";
    const ISO_KW = "KW";
    const ISO_KY = "KY";
    const ISO_KZ = "KZ";
    const ISO_LA = "LA";
    const ISO_LB = "LB";
    const ISO_LC = "LC";
    const ISO_LI = "LI";
    const ISO_LK = "LK";
    const ISO_LR = "LR";
    const ISO_LS = "LS";
    const ISO_LT = "LT";
    const ISO_LU = "LU";
    const ISO_LV = "LV";
    const ISO_LY = "LY";
    const ISO_MA = "MA";
    const ISO_MC = "MC";
    const ISO_MD = "MD";
    const ISO_ME = "ME";
    const ISO_MF = "MF";
    const ISO_MG = "MG";
    const ISO_MH = "MH";
    const ISO_MK = "MK";
    const ISO_ML = "ML";
    const ISO_MM = "MM";
    const ISO_MN = "MN";
    const ISO_MO = "MO";
    const ISO_MP = "MP";
    const ISO_MQ = "MQ";
    const ISO_MR = "MR";
    const ISO_MS = "MS";
    const ISO_MT = "MT";
    const ISO_MU = "MU";
    const ISO_MV = "MV";
    const ISO_MW = "MW";
    const ISO_MX = "MX";
    const ISO_MY = "MY";
    const ISO_MZ = "MZ";
    const ISO_NA = "NA";
    const ISO_NC = "NC";
    const ISO_NE = "NE";
    const ISO_NF = "NF";
    const ISO_NG = "NG";
    const ISO_NI = "NI";
    const ISO_NL = "NL";
    const ISO_NO = "NO";
    const ISO_NP = "NP";
    const ISO_NR = "NR";
    const ISO_NU = "NU";
    const ISO_NZ = "NZ";
    const ISO_OM = "OM";
    const ISO_PA = "PA";
    const ISO_PE = "PE";
    const ISO_PF = "PF";
    const ISO_PG = "PG";
    const ISO_PH = "PH";
    const ISO_PK = "PK";
    const ISO_PL = "PL";
    const ISO_PM = "PM";
    const ISO_PN = "PN";
    const ISO_PR = "PR";
    const ISO_PS = "PS";
    const ISO_PT = "PT";
    const ISO_PW = "PW";
    const ISO_PY = "PY";
    const ISO_QA = "QA";
    const ISO_RE = "RE";
    const ISO_RO = "RO";
    const ISO_RS = "RS";
    const ISO_RU = "RU";
    const ISO_RW = "RW";
    const ISO_SA = "SA";
    const ISO_SB = "SB";
    const ISO_SC = "SC";
    const ISO_SD = "SD";
    const ISO_SE = "SE";
    const ISO_SG = "SG";
    const ISO_SH = "SH";
    const ISO_SI = "SI";
    const ISO_SJ = "SJ";
    const ISO_SK = "SK";
    const ISO_SL = "SL";
    const ISO_SM = "SM";
    const ISO_SN = "SN";
    const ISO_SO = "SO";
    const ISO_SR = "SR";
    const ISO_SS = "SS";
    const ISO_ST = "ST";
    const ISO_SV = "SV";
    const ISO_SX = "SX";
    const ISO_SY = "SY";
    const ISO_SZ = "SZ";
    const ISO_TC = "TC";
    const ISO_TD = "TD";
    const ISO_TF = "TF";
    const ISO_TG = "TG";
    const ISO_TH = "TH";
    const ISO_TJ = "TJ";
    const ISO_TK = "TK";
    const ISO_TL = "TL";
    const ISO_TM = "TM";
    const ISO_TN = "TN";
    const ISO_TO = "TO";
    const ISO_TR = "TR";
    const ISO_TT = "TT";
    const ISO_TV = "TV";
    const ISO_TW = "TW";
    const ISO_TZ = "TZ";
    const ISO_UA = "UA";
    const ISO_UG = "UG";
    const ISO_UM = "UM";
    const ISO_US = "US";
    const ISO_UY = "UY";
    const ISO_UZ = "UZ";
    const ISO_VA = "VA";
    const ISO_VC = "VC";
    const ISO_VE = "VE";
    const ISO_VG = "VG";
    const ISO_VI = "VI";
    const ISO_VN = "VN";
    const ISO_VU = "VU";
    const ISO_WF = "WF";
    const ISO_WS = "WS";
    const ISO_XK = "XK";
    const ISO_YE = "YE";
    const ISO_YT = "YT";
    const ISO_ZA = "ZA";
    const ISO_ZM = "ZM";
    const ISO_ZW = "ZW";

    /**
     *
     * @param string $value
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}