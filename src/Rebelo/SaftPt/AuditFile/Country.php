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
 * Country
 *
 * @author João Rebelo
 */
enum Country : string
{
    case  ISO_AD = "AD";
    case  ISO_AE = "AE";
    case  ISO_AF = "AF";
    case  ISO_AG = "AG";
    case  ISO_AI = "AI";
    case  ISO_AL = "AL";
    case  ISO_AM = "AM";
    case  ISO_AO = "AO";
    case  ISO_AQ = "AQ";
    case  ISO_AR = "AR";
    case  ISO_AS = "AS";
    case  ISO_AT = "AT";
    case  ISO_AU = "AU";
    case  ISO_AW = "AW";
    case  ISO_AX = "AX";
    case  ISO_AZ = "AZ";
    case  ISO_BA = "BA";
    case  ISO_BB = "BB";
    case  ISO_BD = "BD";
    case  ISO_BE = "BE";
    case  ISO_BF = "BF";
    case  ISO_BG = "BG";
    case  ISO_BH = "BH";
    case  ISO_BI = "BI";
    case  ISO_BJ = "BJ";
    case  ISO_BL = "BL";
    case  ISO_BM = "BM";
    case  ISO_BN = "BN";
    case  ISO_BO = "BO";
    case  ISO_BQ = "BQ";
    case  ISO_BR = "BR";
    case  ISO_BS = "BS";
    case  ISO_BT = "BT";
    case  ISO_BV = "BV";
    case  ISO_BW = "BW";
    case  ISO_BY = "BY";
    case  ISO_BZ = "BZ";
    case  ISO_CA = "CA";
    case  ISO_CC = "CC";
    case  ISO_CD = "CD";
    case  ISO_CF = "CF";
    case  ISO_CG = "CG";
    case  ISO_CH = "CH";
    case  ISO_CI = "CI";
    case  ISO_CK = "CK";
    case  ISO_CL = "CL";
    case  ISO_CM = "CM";
    case  ISO_CN = "CN";
    case  ISO_CO = "CO";
    case  ISO_CR = "CR";
    case  ISO_CU = "CU";
    case  ISO_CV = "CV";
    case  ISO_CW = "CW";
    case  ISO_CX = "CX";
    case  ISO_CY = "CY";
    case  ISO_CZ = "CZ";
    case  ISO_DE = "DE";
    case  ISO_DJ = "DJ";
    case  ISO_DK = "DK";
    case  ISO_DM = "DM";
    case  ISO_DO = "DO";
    case  ISO_DZ = "DZ";
    case  ISO_EC = "EC";
    case  ISO_EE = "EE";
    case  ISO_EG = "EG";
    case  ISO_EH = "EH";
    case  ISO_ER = "ER";
    case  ISO_ES = "ES";
    case  ISO_ET = "ET";
    case  ISO_FI = "FI";
    case  ISO_FJ = "FJ";
    case  ISO_FK = "FK";
    case  ISO_FM = "FM";
    case  ISO_FO = "FO";
    case  ISO_FR = "FR";
    case  ISO_GA = "GA";
    case  ISO_GB = "GB";
    case  ISO_GD = "GD";
    case  ISO_GE = "GE";
    case  ISO_GF = "GF";
    case  ISO_GG = "GG";
    case  ISO_GH = "GH";
    case  ISO_GI = "GI";
    case  ISO_GL = "GL";
    case  ISO_GM = "GM";
    case  ISO_GN = "GN";
    case  ISO_GP = "GP";
    case  ISO_GQ = "GQ";
    case  ISO_GR = "GR";
    case  ISO_GS = "GS";
    case  ISO_GT = "GT";
    case  ISO_GU = "GU";
    case  ISO_GW = "GW";
    case  ISO_GY = "GY";
    case  ISO_HK = "HK";
    case  ISO_HM = "HM";
    case  ISO_HN = "HN";
    case  ISO_HR = "HR";
    case  ISO_HT = "HT";
    case  ISO_HU = "HU";
    case  ISO_ID = "ID";
    case  ISO_IE = "IE";
    case  ISO_IL = "IL";
    case  ISO_IM = "IM";
    case  ISO_IN = "IN";
    case  ISO_IO = "IO";
    case  ISO_IQ = "IQ";
    case  ISO_IR = "IR";
    case  ISO_IS = "IS";
    case  ISO_IT = "IT";
    case  ISO_JE = "JE";
    case  ISO_JM = "JM";
    case  ISO_JO = "JO";
    case  ISO_JP = "JP";
    case  ISO_KE = "KE";
    case  ISO_KG = "KG";
    case  ISO_KH = "KH";
    case  ISO_KI = "KI";
    case  ISO_KM = "KM";
    case  ISO_KN = "KN";
    case  ISO_KP = "KP";
    case  ISO_KR = "KR";
    case  ISO_KW = "KW";
    case  ISO_KY = "KY";
    case  ISO_KZ = "KZ";
    case  ISO_LA = "LA";
    case  ISO_LB = "LB";
    case  ISO_LC = "LC";
    case  ISO_LI = "LI";
    case  ISO_LK = "LK";
    case  ISO_LR = "LR";
    case  ISO_LS = "LS";
    case  ISO_LT = "LT";
    case  ISO_LU = "LU";
    case  ISO_LV = "LV";
    case  ISO_LY = "LY";
    case  ISO_MA = "MA";
    case  ISO_MC = "MC";
    case  ISO_MD = "MD";
    case  ISO_ME = "ME";
    case  ISO_MF = "MF";
    case  ISO_MG = "MG";
    case  ISO_MH = "MH";
    case  ISO_MK = "MK";
    case  ISO_ML = "ML";
    case  ISO_MM = "MM";
    case  ISO_MN = "MN";
    case  ISO_MO = "MO";
    case  ISO_MP = "MP";
    case  ISO_MQ = "MQ";
    case  ISO_MR = "MR";
    case  ISO_MS = "MS";
    case  ISO_MT = "MT";
    case  ISO_MU = "MU";
    case  ISO_MV = "MV";
    case  ISO_MW = "MW";
    case  ISO_MX = "MX";
    case  ISO_MY = "MY";
    case  ISO_MZ = "MZ";
    case  ISO_NA = "NA";
    case  ISO_NC = "NC";
    case  ISO_NE = "NE";
    case  ISO_NF = "NF";
    case  ISO_NG = "NG";
    case  ISO_NI = "NI";
    case  ISO_NL = "NL";
    case  ISO_NO = "NO";
    case  ISO_NP = "NP";
    case  ISO_NR = "NR";
    case  ISO_NU = "NU";
    case  ISO_NZ = "NZ";
    case  ISO_OM = "OM";
    case  ISO_PA = "PA";
    case  ISO_PE = "PE";
    case  ISO_PF = "PF";
    case  ISO_PG = "PG";
    case  ISO_PH = "PH";
    case  ISO_PK = "PK";
    case  ISO_PL = "PL";
    case  ISO_PM = "PM";
    case  ISO_PN = "PN";
    case  ISO_PR = "PR";
    case  ISO_PS = "PS";
    case  ISO_PT = "PT";
    case  ISO_PW = "PW";
    case  ISO_PY = "PY";
    case  ISO_QA = "QA";
    case  ISO_RE = "RE";
    case  ISO_RO = "RO";
    case  ISO_RS = "RS";
    case  ISO_RU = "RU";
    case  ISO_RW = "RW";
    case  ISO_SA = "SA";
    case  ISO_SB = "SB";
    case  ISO_SC = "SC";
    case  ISO_SD = "SD";
    case  ISO_SE = "SE";
    case  ISO_SG = "SG";
    case  ISO_SH = "SH";
    case  ISO_SI = "SI";
    case  ISO_SJ = "SJ";
    case  ISO_SK = "SK";
    case  ISO_SL = "SL";
    case  ISO_SM = "SM";
    case  ISO_SN = "SN";
    case  ISO_SO = "SO";
    case  ISO_SR = "SR";
    case  ISO_SS = "SS";
    case  ISO_ST = "ST";
    case  ISO_SV = "SV";
    case  ISO_SX = "SX";
    case  ISO_SY = "SY";
    case  ISO_SZ = "SZ";
    case  ISO_TC = "TC";
    case  ISO_TD = "TD";
    case  ISO_TF = "TF";
    case  ISO_TG = "TG";
    case  ISO_TH = "TH";
    case  ISO_TJ = "TJ";
    case  ISO_TK = "TK";
    case  ISO_TL = "TL";
    case  ISO_TM = "TM";
    case  ISO_TN = "TN";
    case  ISO_TO = "TO";
    case  ISO_TR = "TR";
    case  ISO_TT = "TT";
    case  ISO_TV = "TV";
    case  ISO_TW = "TW";
    case  ISO_TZ = "TZ";
    case  ISO_UA = "UA";
    case  ISO_UG = "UG";
    case  ISO_UM = "UM";
    case  ISO_US = "US";
    case  ISO_UY = "UY";
    case  ISO_UZ = "UZ";
    case  ISO_VA = "VA";
    case  ISO_VC = "VC";
    case  ISO_VE = "VE";
    case  ISO_VG = "VG";
    case  ISO_VI = "VI";
    case  ISO_VN = "VN";
    case  ISO_VU = "VU";
    case  ISO_WF = "WF";
    case  ISO_WS = "WS";
    case  ISO_XK = "XK";
    case  ISO_YE = "YE";
    case  ISO_YT = "YT";
    case  ISO_ZA = "ZA";
    case  ISO_ZM = "ZM";
    case  ISO_ZW = "ZW";
    case DESCONHECIDO = "Desconhecido";

}
