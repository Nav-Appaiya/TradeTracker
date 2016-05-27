<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {
	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction(Request $request) {
		header('Content-Type: application/json');
		$tradeClient = $this->get('tradetracker');

		//	Opslaan sites - ok
		// $sites = $tradeClient->fetchSitesAndSave();
		// print_r($sites);

		//	Opslaan campaigns - ok
		// $campaigns = $tradeClient->fetchCampaignsAndSave();
		// print_r($campaigns);

		// Opslaan promotiemateriaal - todo
		$functions   = $tradeClient->getFunctions();
		$types       = $tradeClient->getTypes();
		$campaignCat = $tradeClient->getCampaignCategories();

		print_r($functions);

		exit;
	}

}
