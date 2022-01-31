<?php
    
namespace App\Controller;

use App\Entity\Products;
use App\Pagination\Pagination;
use App\Repository\ProductsRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController {
    
  /**
   * consulter la liste des produits BileMo ;
   * @Route("/api/products")
   * @Method({"GET"})
   */
  public function getProducts(ProductsRepository $productRepository, Pagination $pagination, SerializerInterface $serializer )
  {
      $query = $productRepository->createQueryBuilder('p')->getQuery();
      $data = $pagination->create($query);

      $data = $serializer->serialize($data, 'json', SerializationContext::create()->setGroups(array('listProduct')));
      
      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
  
  }

   /**
   * consulter les détails d’un produit BileMo ;
   * @Route("/api/products/{id}")
   * @Method({"GET"})
   */
    public function getProductId(Products $product, SerializerInterface $serializer): Response
    {
        $data = $serializer->serialize($product, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}

?>