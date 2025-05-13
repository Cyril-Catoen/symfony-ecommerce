<?php


namespace App\Controller\guest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController {


	#[Route('/login', name: "login", methods: ['GET'])]
	public function displayLogin(AuthenticationUtils $authenticationUtils): Response {

		$error = $authenticationUtils->getLastAuthenticationError();

		return $this->render('guest/login.html.twig', [
			'error' => $error
		]);

	}

	#[Route('/logout', name: "logout", methods: ['GET'])]
	public function logout() {

	}


}

?>