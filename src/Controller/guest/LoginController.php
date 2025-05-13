<?php


namespace App\Controller\guest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController {


	#[Route('/login', name: "login")]
	public function displayLogin(AuthenticationUtils $authenticationUtils): Response {

		$error = $authenticationUtils->getLastAuthenticationError();

		return $this->render('guest/login.html.twig', [
			'error' => $error
		]);

	}

	#[Route('/logout', name: "logout")]
	public function logout() {

	}


}

?>