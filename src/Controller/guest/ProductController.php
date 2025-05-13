<?php 

namespace App\Controller\guest;

use App\Entity\Product;
// use App\Form\ProductForm;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController {
    
    #[Route('/list-product', name: 'list-product', methods: ['GET'])]
    public function displayListProduct(ProductRepository $ProductRepository): Response {

        // Récupérer tous les produits
        $products = $ProductRepository->findBy(['isPublished' => true]);
        
        // Filtrer les produits publiés // Méthode pas optimale en cas de grosse BDD.
        // $publishedProducts = [];
        // foreach ($products as $product) {
        //     if ($product->isPublished() === true) {
        //         $publishedProducts[] = $product;
        //     }
        // }

        if (!$products) {
			return $this->redirectToRoute('/guest/404');
        }

        return $this->render('guest/product/list-product.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/single-product/{id}', name: 'single-product', methods: ['GET'])]
	public function displaySinglecategories($id, productRepository $productRepository): Response {

		// permet de faire une requête SQL SELECT * sur la table product et de sélectionner un item par ID
		$product = $productRepository->find($id);

		// Si l'id demandé ne correspond à aucun product
		// Alors l'utilisateur est redirigé vers une page d'erreur 404.
		// Sinon l'product avec l'id correspond est affiché.
		if (!$product) {
			return $this->redirectToRoute('/guest/404');
		}
		return $this->render('guest\product\selected-product.html.twig', [
			'product' => $product
		]);
	}
}
?>