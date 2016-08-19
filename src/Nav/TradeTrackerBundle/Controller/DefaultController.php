<?php

namespace Nav\TradeTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $tradetracker = $this->get('tradetracker');

        return $this->render('NavTradeTrackerBundle:Default:index.html.twig');
    }

    private function _fetchingResourcesFromApi()
    {
        $tradetracker = $this->get('tradetracker');
        dump($tradetracker->fetchSitesAndSave());
        dump($tradetracker->fetchCampaignsAndSave());
        dump($tradetracker->fetchProductsAndSave());
        dump($tradetracker->fetchPaymentsAndSave());
    }
}
