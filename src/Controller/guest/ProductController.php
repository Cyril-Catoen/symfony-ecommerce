<?php 

namespace App\Controller\guest;

use App\Entity\Product;
// use App\Form\ProductForm;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController {
    
    #[Route('/list-product', name: 'list-product')]
    public function displayListProduct(ProductRepository $ProductRepository) {

        // Récupérer tous les produits
        $products = $ProductRepository->findAll();
        
        // Filtrer les produits publiés
        $publishedProducts = [];
        foreach ($products as $product) {
            if ($product->isPublished() === true) {
                $publishedProducts[] = $product;
            }
        }

        return $this->render('guest/list-product.html.twig', [
            'products' => $publishedProducts
        ]);
    }
}
?>