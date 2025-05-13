<?php 

namespace App\Controller\guest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController {

	#[Route('/', name: "home", methods: ['GET'])]
	public function displayHome(): Response {

		return $this->render('guest\home.html.twig');
	}

	#[Route('/guest/404', name: "/guest/404", methods: ['GET'])]
	public function display404(): Response {

		// La fonction render renvoie automatiquement une erreur 200.
		// On ne peut pas paramétrer render pour avoir une erreur 404.
		
		// On créé le HTML issu du twig
		$html = $this->renderView('guest/404.html.twig');

		// On retourne une réponse 404 avec le HTML
		return new Response($html, 404);
	}

}

?>