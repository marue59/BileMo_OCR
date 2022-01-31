<?php
    
namespace App\Controller;

use App\Entity\Users;
use App\Entity\Customers;
use App\Pagination\Pagination;
use App\Repository\UsersRepository;
use App\Repository\CustomersRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
   * @Route("/api/customers/{id}/users", name="api_users_index")
   * @Method({"GET"})
   */
  public function getUsersCustomer(UsersRepository $userRepository, Pagination $pagination, SerializerInterface $serializer )
  {
      $query = $userRepository->createQueryBuilder('u')->getQuery();
      $data = $pagination->create($query);

      $data = $serializer->serialize($data, 'json', SerializationContext::create()->setGroups(array('listUser')));

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
  }

    /**
     * consulter le détail d’un user inscrit lié à un customer 
     * @Route("/api/customers/{id}/users/{user_id}")
     * @Entity("user", expr="repository.find(user_id)")
     * @Method({"GET"})
     */
    public function getUserCustomerId(Users $user, SerializerInterface $serializer )
    {
  
      $data = $serializer->serialize($user, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }


    /**    
     * ajouter un nouvel utilisateur lié à un client ;
     * @Route("/api/customers/{id}/users", name="api_users_add", methods={"POST"})
     * 
     */
    public function addUserCustomer(Request $request, SerializerInterface $serializer
  )
    {
      $jsonRecu = $request->getContent();
      dd($jsonRecu);
      $user = $serializer->deserialize($jsonRecu, User::class,'json');

      
    
    }

}

?>