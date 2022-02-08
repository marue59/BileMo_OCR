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
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security as OASecurity;

/**
 * Class ProductController
 *
 * @package App\Controller
 * @OASecurity(name="Bearer")
 * @OA\Tag(name="Product")
 */
class ProductController extends AbstractController {
    
  /**
   * Products list
   * 
   * @Route("/api/products",  name="product_show", methods={"GET"})
   * 
   * @OA\Get(
   *      path="/api/products",
   *      description="Consulter la liste des produits BileMo"
   * )
   * 
   *      @OA\Response(
   *         response=200, description="Returns products list",
   *            @Model(type=Products::class, groups={"listProduct"})
   *        )),
   *      @OA\Response(
   *        response=401, description="UNAUTHORIZED - JWT Token not found | Expired JWT Token | Invalid JWT Token" 
   * )
   *      @OA\Response(
   *        response=404, description="Page Not found" 
   * )
   * 
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
   * Product detail
   * 
   * @Route("/api/products/{id}", name="product_detail", methods={"GET"})
   * 
   * @OA\Get(
   *      path="/api/products/{id}",
   *      description="Consulter les détails d’un produit BileMo ;"
   * )
   * 
   *      @OA\Response(
   *         response=200, description="Returns product detail",
   *            @Model(type=Products::class)
   *        )),
   *      @OA\Response(
   *        response=401, description="UNAUTHORIZED - JWT Token not found | Expired JWT Token | Invalid JWT Token" 
   * )
   *      @OA\Response(
   *        response=404, description="Page Not found" 
   * )
   * 
   * 
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