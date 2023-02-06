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
 * TaxCountryRegion
 *
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AD()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AF()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AI()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AL()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AO()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AQ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AS()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AT()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AU()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AW()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AX()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_AZ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BA()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BB()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BD()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BF()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BH()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BI()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BJ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BL()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BN()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BO()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BQ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BS()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BT()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BV()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BW()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BY()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_BZ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CA()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CC()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CD()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CF()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CH()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CI()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CK()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CL()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CN()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CO()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CU()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CV()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CW()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CX()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CY()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_CZ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_DE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_DJ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_DK()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_DM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_DO()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_DZ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_EC()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_EE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_EG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_EH()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_ER()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_ES()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_ET()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_FI()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_FJ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_FK()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_FM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_FO()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_FR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GA()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GB()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GD()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GF()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GH()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GI()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GL()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GN()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GP()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GQ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GS()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GT()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GU()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GW()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_GY()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_HK()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_HM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_HN()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_HR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_HT()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_HU()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_ID()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_IE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_IL()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_IM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_IN()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_IO()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_IQ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_IR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_IS()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_IT()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_JE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_JM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_JO()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_JP()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_KE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_KG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_KH()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_KI()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_KM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_KN()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_KP()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_KR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_KW()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_KY()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_KZ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_LA()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_LB()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_LC()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_LI()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_LK()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_LR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_LS()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_LT()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_LU()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_LV()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_LY()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MA()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MC()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MD()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_ME()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MF()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MH()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MK()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_ML()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MN()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MO()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MP()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MQ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MS()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MT()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MU()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MV()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MW()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MX()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MY()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_MZ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_NA()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_NC()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_NE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_NF()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_NG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_NI()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_NL()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_NO()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_NP()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_NR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_NU()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_NZ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_OM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PA()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PF()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PH()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PK()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PL()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PN()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PS()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PT()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PW()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_PY()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_QA()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_RE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_RO()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_RS()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_RU()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_RW()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SA()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SB()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SC()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SD()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SH()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SI()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SJ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SK()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SL()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SN()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SO()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SS()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_ST()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SV()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SX()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SY()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_SZ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TC()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TD()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TF()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TH()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TJ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TK()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TL()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TN()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TO()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TR()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TT()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TV()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TW()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_TZ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_UA()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_UG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_UM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_US()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_UY()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_UZ()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_VA()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_VC()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_VE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_VG()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_VI()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_VN()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_VU()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_WF()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_WS()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_XK()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_YE()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_YT()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_ZA()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_ZM()
 * @method static \Rebelo\SaftPt\AuditFile\TaxCountryRegion ISO_ZW()
 * @author João Rebelo
 */
class TaxCountryRegion extends Country
{
    /**
     *
     * @since 1.0.0
     */
    const PT_AC = "PT-AC";

    /**
     * Alias of PT_AC
     * @since 1.0.0
     */
    const PT_MA     = "PT-MA";

    /**
     *
     * @since 1.0.0
     */
    const ISO_PT_AC = "PT-AC";

    /**
     * Alias of PT_MA
     * @since 1.0.0
     */
    const ISO_PT_MA = "PT-MA";

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
