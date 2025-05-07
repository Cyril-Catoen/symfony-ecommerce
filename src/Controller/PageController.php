<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController {

	#[Route('/', name: "home")]
	public function displayHome() {

		return $this->render('home.html.twig');
	}

	#[Route('/404', name: "404")]
	public function display404() {

		// La fonction render renvoie automatiquement une erreur 200.
		// On ne peut pas paramétrer render pour avoir une erreur 404.
		
		// On créé le HTML issu du twig
		$html = $this->renderView('404.html.twig');

		// On retourne une réponse 404 avec le HTML
		return new Response($html, 404);
	}

}

?>