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

namespace Rebelo\SaftPt\AuditFile\SourceDocuments\MovementOfGoods;

/**
 * MovementType<br>
 * Shall be filled in with:<br>
 * "GR" - Delivery note;<br>
 * "GT" - Transport guide (include here the global transport documents);<br>
 * “GA” – Transport document for own fixed assets;<br>
 * “GC” - Consignment note;<br>
 * “GD” – Return note.<br>
 * @author João Rebelo
 * @since 3.0.0
 */
enum MovementType : string
{
    /**
     * "GR" - Delivery note
     * @since 3.0.0
     */
    case GR = "GR";

    /**
     * "GT" - Transport guide (include here the global transport documents)
     * @since 3.0.0
     */
    case GT = "GT";

    /**
     * “GA” – Transport document for own fixed assets
     * @since 3.0.0
     */
    case GA = "GA";

    /**
     * “GC” - Consignment note
     * @since 3.0.0
     */
    case GC = "GC";

    /**
     * “GD” – Return note
     * @since 3.0.0
     */
    case GD = "GD";

}
