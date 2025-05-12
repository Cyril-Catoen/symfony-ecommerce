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


		if ($request->isMethod('POST')) {

			$email = $request->request->get('email');
			$password = $request->request->get(key: 'password');

			$user = new User();

			$passwordHashed = $userPasswordHasher->hashPassword($user, $password);


			dump($email);
			dump($passwordHashed); die;

		}


		return $this->render('/admin/user/create-user.html.twig');

	}


}