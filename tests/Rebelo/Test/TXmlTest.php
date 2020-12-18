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

namespace Rebelo\Test;

/**
 *
 * @author João Rebelo
 */
trait TXmlTest
{

    /**
     *
     * @param \SimpleXMLElement $xml1
     * @param \SimpleXMLElement $xml2
     * @param bool $textStrict
     * @return boolean|string
     */
    public function xmlIsEqual(\SimpleXMLElement $xml1,
                                 \SimpleXMLElement $xml2,
                                 bool $textStrict = false)
    {
        // compare text content
        if ($textStrict) {
            if ("$xml1" != "$xml2") {
                return "mismatched text content (strict)";
            }
        } else {
            if (trim("$xml1") != trim("$xml2")) {
                return "mismatched text content";
            }
        }

        // check all attributes
        $search1 = array();
        $search2 = array();
        foreach ($xml1->attributes() as $a => $b) {
            $search1[$a] = "$b";  // force string conversion
        }
        foreach ($xml2->attributes() as $a => $b) {
            $search2[$a] = "$b";
        }
        if ($search1 != $search2) return "mismatched attributes";

        // check all namespaces
        $ns1 = array();
        $ns2 = array();
        foreach ($xml1->getNamespaces() as $a => $b) {
            $ns1[$a] = "$b";
        }
        foreach ($xml2->getNamespaces() as $a => $b) {
            $ns2[$a] = "$b";
        }
        if ($ns1 != $ns2) return "mismatched namespaces";

        // get all namespace attributes
        foreach ($ns1 as $ns) {   // don't need to cycle over ns2, since its identical to ns1
            $search1 = array();
            $search2 = array();
            foreach ($xml1->attributes($ns) as $a => $b) {
                $search1[$a] = "$b";
            }
            foreach ($xml2->attributes($ns) as $a => $b) {
                $search2[$a] = "$b";
            }
            if ($search1 != $search2) return "mismatched ns:$ns attributes";
        }

        // get all children
        $search1 = array();
        $search2 = array();
        foreach ($xml1->children() as $b) {
            if (!isset($search1[$b->getName()]))
                    $search1[$b->getName()]   = array();
            $search1[$b->getName()][] = $b;
        }
        foreach ($xml2->children() as $b) {
            if (!isset($search2[$b->getName()]))
                    $search2[$b->getName()]   = array();
            $search2[$b->getName()][] = $b;
        }
        // cycle over children
        if (count($search1) != count($search2))
                return "mismatched children count";  // xml2 has less or more children names (we don't have to search through xml2's children too)
        foreach ($search1 as $childName => $children) {
            if (!isset($search2[$childName]))
                    return "xml2 does not have child $childName";  // xml2 has none of this child
            if (count($search1[$childName]) != count($search2[$childName]))
                    return "mismatched $childName children count";  // xml2 has less or more children
            foreach ($children as $child) {
                // do any of search2 children match?
                $foundMatch = false;
                $reasons     = array();
                foreach ($search2[$childName] as $id => $secondChild) {
                    if (($r = $this->xmlIsEqual($child, $secondChild)) === true) {
                        // found a match: delete second
                        $foundMatch = true;
                        unset($search2[$childName][$id]);
                    } else {
                        $reasons[] = $r;
                    }
                }
                if (!$foundMatch)
                        return "xml2 does not have specific $childName child: ".implode(
                            "; ",
                            $reasons
                        );
            }
        }

        // finally, cycle over namespaced children
        foreach ($ns1 as $ns) {   // don't need to cycle over ns2, since its identical to ns1
            // get all children
            $search1 = array();
            $search2 = array();
            foreach ($xml1->children() as $b) {
                if (!isset($search1[$b->getName()]))
                        $search1[$b->getName()]   = array();
                $search1[$b->getName()][] = $b;
            }
            foreach ($xml2->children() as $b) {
                if (!isset($search2[$b->getName()]))
                        $search2[$b->getName()]   = array();
                $search2[$b->getName()][] = $b;
            }
            // cycle over children
            if (count($search1) != count($search2))
                    return "mismatched ns:$ns children count";  // xml2 has less or more children names (we don't have to search through xml2's children too)
            foreach ($search1 as $childName => $children) {
                if (!isset($search2[$childName]))
                        return "xml2 does not have ns:$ns child $childName";  // xml2 has none of this child
                if (count($search1[$childName]) != count($search2[$childName]))
                        return "mismatched ns:$ns $childName children count";  // xml2 has less or more children
                foreach ($children as $child) {
                    // do any of search2 children match?
                    $foundMatch = false;
                    foreach ($search2[$childName] as $id => $secondChild) {
                        if ($this->xmlIsEqual($child, $secondChild) === true) {
                            // found a match: delete second
                            $foundMatch = true;
                            unset($search2[$childName][$id]);
                        }
                    }
                    if (!$foundMatch)
                            return "xml2 does not have specific ns:$ns $childName child";
                }
            }
        }

        // if we've got through all of THIS, then we can say that xml1 has the same attributes and children as xml2.
        return true;
    }
}
