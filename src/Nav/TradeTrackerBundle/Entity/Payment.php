<?php
/**
 * Created by PhpStorm.
 * User: nav.appaiya
 * Date: 19-8-2016
 * Time: 13:42.
 */
namespace Nav\TradeTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment.
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity()
 */
class Payment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="invoiceNumber", type="string", length=255)
     */
    private $invoiceNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=255)
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="subTotal", type="decimal", scale=2)
     */
    private $subTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="VAT", type="decimal", scale=2)
     */
    private $vAT;

    /**
     * @var string
     *
     * @ORM\Column(name="endTotal" ,type="decimal", scale=2)
     */
    private $endTotal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="billDate", type="date")
     */
    private $billDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payDate", type="date")
     */
    private $payDate;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set invoiceNumber.
     *
     * @param string $invoiceNumber
     *
     * @return Payment
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * Get invoiceNumber.
     *
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * Set currency.
     *
     * @param string $currency
     *
     * @return Payment
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set subTotal.
     *
     * @param string $subTotal
     *
     * @return Payment
     */
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;

        return $this;
    }

    /**
     * Get subTotal.
     *
     * @return string
     */
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * Set vAT.
     *
     * @param string $vAT
     *
     * @return Payment
     */
    public function setVAT($vAT)
    {
        $this->vAT = $vAT;

        return $this;
    }

    /**
     * Get vAT.
     *
     * @return string
     */
    public function getVAT()
    {
        return $this->vAT;
    }

    /**
     * Set endTotal.
     *
     * @param string $endTotal
     *
     * @return Payment
     */
    public function setEndTotal($endTotal)
    {
        $this->endTotal = $endTotal;

        return $this;
    }

    /**
     * Get endTotal.
     *
     * @return string
     */
    public function getEndTotal()
    {
        return $this->endTotal;
    }

    /**
     * Set billDate.
     *
     * @param \DateTime $billDate
     *
     * @return Payment
     */
    public function setBillDate($billDate)
    {
        $this->billDate = $billDate;

        return $this;
    }

    /**
     * Get billDate.
     *
     * @return \DateTime
     */
    public function getBillDate()
    {
        return $this->billDate;
    }

    /**
     * Set payDate.
     *
     * @param \DateTime $payDate
     *
     * @return Payment
     */
    public function setPayDate($payDate)
    {
        $this->payDate = $payDate;

        return $this;
    }

    /**
     * Get payDate.
     *
     * @return \DateTime
     */
    public function getPayDate()
    {
        return $this->payDate;
    }
}
