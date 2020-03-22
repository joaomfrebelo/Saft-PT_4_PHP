<?php

namespace Rebelo\SaftPt\SourceDocuments\PaymentsAType\PaymentAType\LineAType;

/**
 * Class representing SourceDocumentIDAType
 */
class SourceDocumentIDAType
{

    /**
     * @var string $originatingON
     */
    private $originatingON = null;

    /**
     * @var \DateTime $invoiceDate
     */
    private $invoiceDate = null;

    /**
     * @var string $description
     */
    private $description = null;

    /**
     * Gets as originatingON
     *
     * @return string
     */
    public function getOriginatingON()
    {
        return $this->originatingON;
    }

    /**
     * Sets a new originatingON
     *
     * @param string $originatingON
     * @return self
     */
    public function setOriginatingON($originatingON)
    {
        $this->originatingON = $originatingON;
        return $this;
    }

    /**
     * Gets as invoiceDate
     *
     * @return \DateTime
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    /**
     * Sets a new invoiceDate
     *
     * @param \DateTime $invoiceDate
     * @return self
     */
    public function setInvoiceDate(\DateTime $invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }

    /**
     * Gets as description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


}

