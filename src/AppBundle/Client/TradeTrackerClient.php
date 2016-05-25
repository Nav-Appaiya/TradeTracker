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
     *  Ophalen van lijst met sites en persisten naar db.
     */
    public function fetchSitesAndSave()
    {
        $sites = $this->client->getAffiliateSites();
        $em = $this->container->get('doctrine.orm.entity_manager');
        $siteRepo = $em->getRepository('AppBundle:Site');

        foreach ($sites as $site) {

            $newSite = $siteRepo->findOneBySiteId($site->ID);

            if (!$newSite) {
                $newSite = new Site($site->ID);
            }
            $newSite->setInfo($site->info);
            $newSite->setName($site->name);
            $newSite->setUrl($site->URL);
            $em->persist($newSite);
            $em->flush();

            echo 'saved: ' . $newSite->getName() . "\n";
        }
    }

    // Testing with naviation.me site at 248946
    public function getSiteId()
    {
        return '248946';
    }


    public function fetchCampaignsAndSave($options = array())
    {
        $camps = $this->client->getCampaigns($this->siteId, false);
        $em = $this->container->get('doctrine')->getManager();
        $campRepo = $em->getRepository('AppBundle:Campaign');

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

        }
    }

}