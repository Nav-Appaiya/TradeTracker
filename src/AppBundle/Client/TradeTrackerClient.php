<?php
/**
 * Created by PhpStorm.
 * User: Nav
 * Date: 25-05-16
 * Time: 01:40
 */

namespace AppBundle\Client;


use AppBundle\Entity\Campaign;
use AppBundle\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\DependencyInjection\Container;
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
    public $siteId = '248946';


    /**
     * TradeTrackerClient constructor.
     * @param string $klantid
     * @param string $sleutel
     * @param ContainerInterface $container
     * @internal param $client
     */
    public function __construct($klantid = '', $sleutel = '', ContainerInterface $container)
    {
        $this->client = new \SoapClient(
            'http://ws.tradetracker.com/soap/affiliate?wsdl', array(
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP
            )
        );
        
        $this->client->authenticate($klantid, $sleutel, false, 'nl_NL', false);
        $this->container = $container;
    }


    /**
     *
     */
    public function fetchSitesAndSave()
    {
        $sites = $this->client->getAffiliateSites();
        $em = $this->container->get('doctrine.orm.entity_manager');
        $siteRepo = $em->getRepository('AppBundle:Site');
        $sites[] = array();

        foreach ($sites as $site) {
            if(isset($site->ID)){
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

    // Testing with naviation.me site at 248946
    /**
     * @return string
     */
    public function getSiteId() {
        return '248946';
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
        $camps = $this->client->getCampaigns($this->siteId, false);
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

    public function getTestingStuff()
    {
        return $this->client->__getFunctions();
    }
    
}