<?php


namespace App\Controller\admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController {

	#[Route('/admin/create-user', name: 'admin/create-user')]
	public function displayCreateUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager){
        // On utilise les composants de Symfony pour gérer le hash du Password et la création de l'utilisateur

		if ($request->isMethod('POST')) {

            // Si le formulaire est soumis, on récupère dans deux variables distinctes l'email (identifiant futur de l'user) et le hash du mot de passe généré
			$email = $request->request->get('email');
			$password = $request->request->get(key: 'password');

            // On créé un User avec la fonction préconstruite par Symfony
			$user = new User();

            // Le mot de passe est hashé par la fonction intégrée par Symfony
			$passwordHashed = $userPasswordHasher->hashPassword($user, $password);

            // méthode "set"
			//$user->setPassword($passwordHashed);
			//$user->setEmail($email);
			// $user->setRoles(['ROLE_ADMIN']);

			// méthode retenue
			$user->createAdmin($email, $passwordHashed);

            try {
			$entityManager->persist($user);
			$entityManager->flush();

			$this->addFlash('success','Admin créé'); 
            // On redirige vers la page de liste mis à jour
			return $this->redirectToRoute('admin/list-user');

            } catch(Exception $exception) {

            $this->addFlash('error', 'Impossible de créer l\'admin');

                // Si le code erreur est 1062 (clé d'unicité), le message est complété par l'information relative à cette contrainte non respectée.
                if ($exception->getCode() === 1062) {
                    $this->addFlash('error',  'Email déjà pris.');
                }
            }
			// dump($email);
			// dump($passwordHashed); die;
		
        }
		return $this->render('/admin/user/create-user.html.twig');

	}

    #[Route('/admin/list-user', name: 'admin/list-user')]
	public function displayListUser(UserRepository $UserRepository){
        // On utilise les composants de Symfony pour gérer le hash du Password et la création de l'utilisateur

            // Récupérer tous les users
			$users = $UserRepository->findAll('user');
			
			// dump($email);
			// dump($passwordHashed); die;
		
		return $this->render('/admin/user/list-user.html.twig', [ 'users' => $users]);

	}
    }
