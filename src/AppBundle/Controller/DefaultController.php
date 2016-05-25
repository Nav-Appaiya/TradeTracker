<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
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

		//	Opslaan sites - ok
		//	$sites = $tradeClient->fetchSitesAndSave();
		//	print_r($sites);exit;

		$campains = $tradeClient->fetchCampaignsAndSave();

		foreach ($campains as $campain) {
			print_r($campain);exit;
		}
		exit;
	}
	
	
}
