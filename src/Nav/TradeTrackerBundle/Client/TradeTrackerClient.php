<?php
/**
 * Created by PhpStorm.
 * User: nav.appaiya
 * Date: 19-8-2016
 * Time: 13:40
 */

namespace Nav\TradeTrackerBundle\Client;

use Nav\TradeTrackerBundle\Entity\Campaign;
use Nav\TradeTrackerBundle\Entity\Payment;
use Nav\TradeTrackerBundle\Entity\Product;
use Nav\TradeTrackerBundle\Entity\Site;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TradeTrackerClient
 * @package AppBundle\Client
 */
class TradeTrackerClient
{
    /**
     * @var \SoapClient
     */
    protected $client;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Hardcoded siteid for now
     */
    public $affilateSiteId;


    /**
     * TradeTrackerClient constructor.
     * @param string $klantid
     * @param string $sleutel
     * @param ContainerInterface $container
     * @internal param $client
     */
    public function __construct(
        $klantid = '',
        $sleutel = '',
        ContainerInterface $container
    ) {
        set_time_limit(0);
        $this->client = new \SoapClient(
            'http://ws.tradetracker.com/soap/affiliate?wsdl', array(
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP
            )
        );

        if ($klantid == 'tradetracker-klantid' || $sleutel == 'tradetracker-sleutel') {
            return new Exception("KlantID / Sleutel not set properly, please " .
                "check services.yml under Bundle/Resources/config/.");
        }

        $this->client->authenticate($klantid, $sleutel, false, 'nl_NL', false);
        $this->container = $container;
    }


    /**
     * Fetches all sites and saves them to the sites table.
     */
    public function fetchSitesAndSave()
    {
        $sites = $this->client->getAffiliateSites();
        $em = $this->container->get('doctrine.orm.entity_manager');
        $siteRepo = $em->getRepository('AppBundle:Site');
        $sites[] = array();

        foreach ($sites as $site) {
            if (isset($site->ID)) {
                $newSite = $siteRepo->findOneBySiteId($site->ID);

                if (!$newSite) {
                    $newSite = new Site($site->ID);
                    $em->persist($newSite);
                    echo 'new site adding: ' . $site->name . "\n";
                }
                $newSite->setInfo($site->info);
                $newSite->setName($site->name);
                $newSite->setUrl($site->URL);
                $em->persist($newSite);
                $em->flush();
                $sites[] = $newSite;
            }
        }

        return $sites;
    }

    /**
     * Fetchs products and fills database, will update products if already found.
     * @param array $options
     */
    public function fetchProductsAndSave($options = ['limit' => 100000])
    {
        // set_time_limit(100);
        $em = $this->container->get('doctrine.orm.entity_manager');
        $productRepo = $em->getRepository('AppBundle:Product');
        $productsFeed = $this->client->getFeedProducts($this->getSiteId(),
            $options);
        $products = [];

        foreach ($productsFeed as $item) {
            $newProduct = $productRepo->findOneByIdentifier($item->identifier);
            if (!$newProduct) {
                $newProduct = new Product();
                $newProduct->setIdentifier($item->identifier);
                echo $newProduct->getName();
            }
            $newProduct->setName($item->name);
            $newProduct->setProductCategoryName(isset($item->productCategoryName) ? $item->productCategoryName : "None");
            $newProduct->setDescription(isset($item->description) ?: 'No description');
            $newProduct->setImageUrl($item->imageURL);
            $newProduct->setProductUrl($item->productURL);
            $newProduct->setAdditional($item->additional);
            $newProduct->setPrice(isset($item->price) ? $item->price : '0.00');

            $em->persist($newProduct);
        }
        $em->flush();

    }

    /**
     * Fetch payments and save them.
     */
    public function fetchPaymentsAndSave()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $paymentsRepo = $em->getRepository('AppBundle:Payment');
        $payments = $this->client->getPayments();

        foreach ($payments as $payment) {
            $paymentEntity = $paymentsRepo->findOneByInvoiceNumber($payment->invoiceNumber);

            if (!$paymentEntity) {
                /**
                 * @var Payment $paymentEntity
                 */
                $paymentEntity = new Payment();
                $paymentEntity->setInvoiceNumber($payment->invoiceNumber);
            }
            $paymentEntity->setCurrency($payment->currency);
            $paymentEntity->setSubTotal($payment->subTotal);
            $paymentEntity->setVAT($payment->VAT);
            $paymentEntity->setEndTotal($payment->endTotal);
            $paymentEntity->setBillDate(new \DateTime($payment->billDate));
            $paymentEntity->setPayDate(new \DateTime($payment->payDate));
            $em->persist($paymentEntity);
        }

        $em->flush();
        die('Done imporing payments');
    }

    /**
     * @return string
     */
    public function getSiteId()
    {
        $affilateSites = $this->client->getAffiliateSites()[0];
        $this->affilateSiteId = $affilateSites->ID;

        return $this->affilateSiteId;
    }

    /**
     * @return \SoapClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param \SoapClient $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * Returns array with campaigns
     * Passing options by using filter
     *
     * @param array $options
     * @return array
     */
    public function fetchCampaignsAndSave($options = array())
    {
        $camps = $this->client->getCampaigns($this->getSiteId(), $options);
        $em = $this->container->get('doctrine')->getManager();
        $campRepo = $em->getRepository('AppBundle:Campaign');
        $campaigns = array();

        foreach ($camps as $camp) {
            $newCamp = $campRepo->findOneByCampaignId($camp->ID);
            if (!$newCamp) {
                $newCamp = new Campaign();
                $newCamp->setCampaignId($camp->ID);
                echo 'new capaign adding: ' . $camp->name . "\n";
            }
            $newCamp->setUrl($camp->URL);
            $newCamp->setInfo($camp->info);
            $newCamp->setName($camp->name);
            $em->persist($newCamp);
            $em->flush();

            $campaigns[] = $newCamp;
        }

        return $campaigns;
    }


    /**
     * @return mixed
     */
    public function getCampaignCategories()
    {
        return $this->client->getCampaignCategories();
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return $this->client->__getFunctions();
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->client->__getTypes();
    }

    /**
     * @return array
     */
    public function getTestingStuff()
    {
        return $this->client->__getFunctions();
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function getProductsFeeds($options = ['limit' => 10])
    {
        return $this->client->getFeedProducts($this->getSiteId(), $options);
    }

}