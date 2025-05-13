<?php 

namespace App\Controller\guest;

use App\Entity\Category;
// use App\Form\CategoryForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController {
    
    #[Route('/list-category', name: 'list-category')]
	public function displayListCategories(CategoryRepository $categoryRepository): Response {

		// permet de faire une requête SQL SELECT * sur la table category
		$categories = $categoryRepository->findAll();

		return $this->render('guest\category\list-category.html.twig', [
			'categories' => $categories
		]);
		
	}

    #[Route('/single-category/{id}', name: 'single-category')]
	public function displaySinglecategories($id, categoryRepository $categoryRepository): Response {

		// permet de faire une requête SQL SELECT * sur la table category et de sélectionner un item par ID
		$category = $categoryRepository->find($id);

		// Si l'id demandé ne correspond à aucun category
		// Alors l'utilisateur est redirigé vers une page d'erreur 404.
		// Sinon l'category avec l'id correspond est affiché.
		if (!$category) {
			return $this->redirectToRoute('/guest/404');
		}
		return $this->render('guest\category\selected-category.html.twig', [
			'category' => $category
		]);
	}
}
?>