<?php 

namespace App\Controller\admin;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController {
    
    #[Route('/admin/list-product', name: 'admin/list-product')]
    public function displayListProduct(ProductRepository $ProductRepository) {

        // Récupérer tous les produits
        $products = $ProductRepository->findBy(['isPublished' => true]);
        
        // Filtrer les produits publiés // Méthode pas optimale en cas de grosse BDD.
        // $publishedProducts = [];
        // foreach ($products as $product) {
        //     if ($product->isPublished() === true) {
        //         $publishedProducts[] = $product;
        //     }
        // }

        return $this->render('admin/product/list-product.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/admin/create-product', name: 'admin/create-product')]
    public function displayCreateProduct(Request $request, categoryRepository $categoryRepository, EntityManagerInterface $entityManager) {
        $categories = $categoryRepository->findAll(); // On récupère l'ensemble des catégories du repository

        if ($request->isMethod("POST")) {
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $price = $request->request->get('price');
            $isPublished = $request->request->get('isPublished') !== null;
    		// On récupère l'id de la catégorie sélectionné par l'utilisateur
			$categoryId = $request->request->get('category');
			// On récupère la catégorie complète liée à l'id récupéré (grâce à la classe CategoryRepository)
			$category = $categoryRepository->find($categoryId);

             // On créé une instance de product
            $product = new Product($title, $description, $price, $isPublished, $category);
            
            $entityManager->persist($product);
            $entityManager->flush();

            // Redirige vers la liste des produits avec un message de succès
            $this->addFlash('success', 'Produit ajouté avec succès !');
            return $this->redirectToRoute('admin/list-product');
        }
     
		return $this->render('admin/product/create-product.html.twig', ['categories' => $categories]); // on affiche la vue et lui communique les données récupérées dans le repository Category
    }
}
?>