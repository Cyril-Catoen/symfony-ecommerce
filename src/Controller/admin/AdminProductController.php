<?php 

namespace App\Controller\admin;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Entity\Category;
use Exception;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController {
    
    #[Route('/admin/list-product', name: 'admin/list-product', methods: ['GET'])]
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

        return $this->render('admin/product/list-product.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/admin/create-product', name: 'admin/create-product', methods: ['GET', 'POST'])]
    public function displayCreateProduct(Request $request, categoryRepository $categoryRepository, EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag): Response {
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
            $image = $request->files->get('image');

            	// si une image a bien été envoyée
			if ($image) {
				// je créé un nouveau nom unique pour l'image et je rajoute l'extension
				// originale de l'image (.jpg ou .png etc)
				$imageNewName = uniqid() . '.' . $image->guessExtension();
				// je déplace l'image dans le dossier /public/uploads (je récupère le chemin du dossier grâce à la classe parameterbag) 
				// et je la renomme avec le nouveau nom
				$image->move($parameterBag->get('kernel.project_dir').'/public/uploads', $imageNewName);
			}

            try {
             // On créé une instance de product
            $product = new Product($title, $description, $price, $isPublished, $category, $imageNewName);
            
            $entityManager->persist($product);
            $entityManager->flush();

             // Redirige vers la liste des produits avec un message de succès
             $this->addFlash('success', 'Produit ajouté avec succès !');

            } catch (\Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }

           
            return $this->redirectToRoute('admin/list-product');
        }
     
		return $this->render('admin/product/create-product.html.twig', ['categories' => $categories]); // on affiche la vue et lui communique les données récupérées dans le repository Category
    }

    #[Route('/admin/delete-product/{id}', name: "admin/delete-product", methods: ['GET'])]
	public function deleteProduct($id, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response {
			// On cible le produit à supprimer par son id unique.
			$product = $productRepository->find($id);
            
            if (!$product) {
                return $this->redirectToRoute('/admin/404');
            }

            try {
                // On utilise la méthode remove de la classe EntityManager 
                // On prend en paramètre le produit à supprimer
                $entityManager->remove($product);
                $entityManager->flush();
	
	    		// On ajoute un message flash pour notifier que le produit est supprimé
		    	$this->addFlash('success', 'The product has been deleted');
                return $this->redirectToRoute('admin/list-product');

            } catch(Exception $exception) {
                $this->addFlash('error', "Le produit n'a pas été supprimé.");
            }
            
			// On redirige vers la page de liste mis à jour
			return $this->redirectToRoute('admin/list-product');
		}

        #[Route('/admin/update-product/{id}', name: "admin/update-product", methods: ['GET', 'POST'])]
		public function updateproduct($id, Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag): Response {
			$product = $productRepository->find($id);
            $categories = $categoryRepository->findAll();

            if (!$product) {
				$this->addFlash('error', 'Produit non trouvé.');
				return $this->redirectToRoute('admin/list-product');
			}

            
		
		
			if ($request->isMethod("POST")) { // On récupère les nouvelles données si le formulaire est soumis.

                $title = $request->request->get('title');
                $description = $request->request->get('description');
                $price = $request->request->get('price');
                $isPublished = $request->request->get('isPublished') !== null;
                // On récupère l'id de la catégorie sélectionné par l'utilisateur
                $categoryId = $request->request->get('category');
                // On récupère la catégorie complète liée à l'id récupéré (grâce à la classe CategoryRepository)
                $category = $categoryRepository->find($categoryId);
                $image = $request->files->get('image');
                // si une image a bien été envoyée
                if ($image) {
                // je créé un nouveau nom unique pour l'image et je rajoute l'extension
                // originale de l'image (.jpg ou .png etc)
                $imageNewName = uniqid() . '.' . $image->guessExtension();
                // je déplace l'image dans le dossier /public/uploads (je récupère le chemin du dossier grâce à la classe parameterbag) 
                // et je la renomme avec le nouveau nom
                $image->move($parameterBag->get('kernel.project_dir').'/public/uploads', $imageNewName);
                }
    
    
           	 	$product->update($title, $description, $price, $isPublished, $category, $imageNewName);

            	$entityManager->persist($product); // Enregistre dans la base de données l'product créé
				$entityManager->flush();
		
				$this->addFlash('success', 'Produit mis à jour avec succès.');
		
				return $this->redirectToRoute('admin/list-product');
			}
		
			return $this->render('admin/product/update-product.html.twig', ['product' => $product, 'categories' => $categories]);
		}
}
?>