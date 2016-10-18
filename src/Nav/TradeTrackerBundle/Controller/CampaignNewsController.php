<?php

namespace Nav\TradeTrackerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CampaignNewsController extends Controller
{
    /**
     * @Route(path="/blog")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $options = null;

        if ($request->get('id')) {
            $options['campaignCategoryID'] = $request->get('id');
        }

        dump($options);
        $news = $this->get('tradetracker')->getAllCampaignNews($options);

        return $this->render('@NavTradeTracker/Tradetracker/campaign_news.html.twig', [
            'news' => $news,
        ]);
    }
}
