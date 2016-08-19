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

        dump($tradetracker->fetchCampaignsAndSave());
        return $this->render('NavTradeTrackerBundle:Default:index.html.twig');
    }
}
