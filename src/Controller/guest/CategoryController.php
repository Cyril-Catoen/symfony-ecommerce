<?php 

namespace App\Controller\guest;

use App\Entity\Category;
// use App\Form\CategoryForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController {
    
    #[Route('/list-category', name: 'list-category')]
	public function displayListCategories(CategoryRepository $categoryRepository) {

		// permet de faire une requête SQL SELECT * sur la table category
		$categories = $categoryRepository->findAll();

		return $this->render('guest\list-category.html.twig', [
			'categories' => $categories
		]);
		
	}
}
?>