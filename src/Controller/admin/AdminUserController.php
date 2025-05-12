<?php


namespace App\Controller\admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController {

	#[Route('/admin/create-user', name: 'admin/create-user')]
	public function displayCreateUser(Request $request, UserPasswordHasherInterface $userPasswordHasher){
        // On utilise les composants de Symfony pour gérer le hash du Password et la création de l'utilisateur

		if ($request->isMethod('POST')) {

            // Si le formulaire est soumis, on récupère dans deux variables distinctes l'email (identifiant futur de l'user) et le hash du mot de passe généré
			$email = $request->request->get('email');
			$password = $request->request->get(key: 'password');

            // On créé un User avec la fonction préconstruite par Symfony
			$user = new User();

            // Le mot de passe est hashé par la fonction intégrée par Symfony
			$passwordHashed = $userPasswordHasher->hashPassword($user, $password);


			dump($email);
			dump($passwordHashed); die;

		}


		return $this->render('/admin/user/create-user.html.twig');

	}


}