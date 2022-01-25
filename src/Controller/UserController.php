<?php
    
namespace App\Controller;

use App\Entity\Users;
use App\Entity\Customers;
use App\Pagination\Pagination;
use App\Repository\CustomersRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends AbstractController {
    
    /**
     * @Route("/api/users")
     */
    public function getUsers()
    {
      return $this->json([]);
    }

  /** liste des users inscrits liés à un customer
   * @Route("/api/customers/{id}/users")
   * @Method({"GET"})
   */
  public function getUserCustomer(CustomersRepository $customerRepository, Pagination $pagination, SerializerInterface $serializer )
  {
      $query = $customerRepository->createQueryBuilder('p')->getQuery();
      $data = $pagination->create($query);

      $data = $serializer->serialize($data, 'json', SerializationContext::create()->setGroups(array('detailUser')));

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
  }


    /**consulter le détail d’un user inscrit lié à un customer 
     * @Route("/api/customers/{id}/users")
     * @Method({"POST"})
     */
    public function postUserCustomer(Customers $customer )
    {
      $data = $this->serialize($customer, 'json');
      
      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
  }

}

?>