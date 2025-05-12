<?php


namespace App\Controller\guest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController {


	#[Route('/login', name: "login")]
	public function displayLogin() {

		return $this->render('guest/login.html.twig');

	}

}

?>