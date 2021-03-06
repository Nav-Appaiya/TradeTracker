<?php
/**
 * Created by PhpStorm.
 * User: Nav
 * Date: 30-05-16
 * Time: 21:07.
 */
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CronController.
 */
class CronController extends Controller
{
    /**
     * @Route(path="/cron")
     */
    public function indexAction()
    {
        $client = $this->get('tradetracker');
        header('Content-Type: text/plain');

        $sites = $client->getClient()->getAffiliateSites()[0];
        $payments = $client->getClient()->getPayments();
        $affilateSiteId = $sites->ID;

        print_r($client->getFunctions());

        $feedProducts = $client->getClient()->getFeedProducts($affilateSiteId, [
            'limit' => 10,
        ]);

        print_r($client->fetchProductsAndSave());



        exit;
    }
}
