<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller {

    /**
	 * @Route("/", name="homepage")
	 */
	public function indexAction(Request $request) {

	    return $this->render('@NavTradeTracker/Default/index.html.twig');

		header('Content-Type: application/json');
        $tradeClient = $this->get('tradetracker');

		//	Opslaan sites - ok
		// $sites = $tradeClient->fetchSitesAndSave();
		// print_r($sites); exit;

		//	Opslaan campaigns - ok
        // $campaigns = $tradeClient->fetchCampaignsAndSave(['assignmentStatus' => 'accepted']);
        // print_r($campaigns); exit;

		//	Opslaan Products - ok
		// $products = $tradeClient->fetchProductsAndSave(['limit' => 150, 'offset'=> 1500]);
        // print_r($products); exit;

        // Opslaan van Payments - ok
        $payments = $tradeClient->fetchPaymentsAndSave([]);
        print_r($payments); exit;

        print_r($client->getPayments());exit;
		// Opslaan promotiemateriaal - todo
		$functions   = $tradeClient->getFunctions();
		$types       = $tradeClient->getTypes();
		$campaignCat = $tradeClient->getCampaignCategories();
		$client      = $tradeClient->getClient();

		print_r($tradeClient->fetchProductsAndSave());

		exit;
	}

}
