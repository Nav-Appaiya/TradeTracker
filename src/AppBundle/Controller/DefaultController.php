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
		header('Content-Type: application/json');

		$tradeClient = $this->get('tradetracker');
		$klantId = $this->getParameter('tradetracker.klantid');
var_dump($klantId);exit;
		//	Opslaan sites - ok
		// $sites = $tradeClient->fetchSitesAndSave();
		// print_r($sites);

		//	Opslaan campaigns - ok
		// $campaigns = $tradeClient->fetchCampaignsAndSave();
		// print_r($campaigns);

		//	Opslaan Products - ok
		// $campaigns = $tradeClient->fetchProductsAndSave();
		// print_r($campaigns);

		// Opslaan promotiemateriaal - todo
		$functions   = $tradeClient->getFunctions();
		$types       = $tradeClient->getTypes();
		$campaignCat = $tradeClient->getCampaignCategories();
		$client      = $tradeClient->getClient();

		print_r($tradeClient->fetchProductsAndSave());

		exit;
	}

}
