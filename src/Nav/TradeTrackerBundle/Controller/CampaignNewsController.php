<?php

namespace Nav\TradeTrackerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CampaignNewsController extends Controller
{

    /**
     * @Route(path="/blog")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $news = $this->get('tradetracker')->getAllCampaignNews();

        return $this->render('@NavTradeTracker/Tradetracker/campaign_news.html.twig', [
            'news' => $news
        ]);
    }
}
