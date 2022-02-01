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
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
   * @Route("/api/customers/{id}/users", name="api_user_show", methods={"GET"})
   */
  public function getUsersCustomer(UsersRepository $userRepository, 
  Pagination $pagination, 
  SerializerInterface $serializer )
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
     * @Route("/api/customers/{id}/users/{user_id}", name="api_user_show_id" , methods={"GET"})
     * @Entity("user", expr="repository.find(user_id)")
     * @Entity("customer", expr="repository.find(id)")
     */
    public function getUserCustomerId(Users $user, Customers $customer, 
    SerializerInterface $serializer )
    {
  
      $data = $serializer->serialize($user, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }


    /**    
     * ajouter un nouvel utilisateur lié à un client ;
     * @Route("/api/customers/{id}/users", name="api_user_create", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function create(Request $request, 
    EntityManagerInterface $em, 
    SerializerInterface $serializer, 
    ValidatorInterface $validator) : JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $errors = $validator->validate($user);

        if(count($errors) > 0) {
            $data = $serializer->serialize($errors, 'json');
            return new JsonResponse($data, 400, [], true);
        }
dd($user);
        $user->setEmail($email)
             ->setFullname($Fullname);
        
        $em->persist($user);
        $em->flush();

        $data = $serializer->serialize($user, 'json', SerializationContext::create()->setGroups(array('create')));
        return new JsonResponse($data, Response::HTTP_CREATED, [], true);
    }

    /**
     * supprimer un utilisateur ajouté par un client.
     * @Route("/api/customers/{id}/users/{user_id}",name="delete", methods={"DELETE"})
     * @param Users $user
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function deleteUserCustomer(Users $user, 
    EntityManagerInterface $em) : JsonResponse
    {
        $em->remove($user);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);

    }
}
?>