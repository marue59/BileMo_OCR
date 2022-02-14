<?php
    
namespace App\Controller;

use App\Entity\Users;
use App\Entity\Customers;
use App\Pagination\Pagination;
use OpenApi\Annotations as OA;
use App\Repository\UsersRepository;
use App\Repository\CustomersRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Nelmio\ApiDocBundle\Annotation\Security as OASecurity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class UserController
 *
 * @package App\Controller
 * @OASecurity(name="Bearer")
 * @OA\Tag(name="User")
 */
class UserController extends AbstractController {
    
  /** 
   * Users list
   * 
   * @Route("/api/customers/{id}/users", name="api_user_show", methods={"GET"})
   * 
   * @OA\Get(
   *      path="/api/customers/{id}/users",
   *      description="Consulter la liste des Users",
   *            @OA\Parameter(
   *              name="id", in="path", description="Id customer", required=true,
   *              @OA\Schema(type="integer")
   *            ),
   *            @OA\Response(
   *                response=200, description="Returns users list from customers",
   *                @Model(type=Users::class, groups={"listUser"}))
   *  ))
   *            @OA\Response(
   *                response=401, description="UNAUTHORIZED - JWT Token not found | Expired JWT Token | Invalid JWT Token" 
   * )
   *            @OA\Response(
   *                response=404, description="Page Not found" 
   * )
   * 
   */
  public function getUsersCustomer(UsersRepository $userRepository, 
  Pagination $pagination, 
  SerializerInterface $serializer, Customers $customer )
  {
      $query = $userRepository->createQueryBuilder('u')
                            ->where('u.customers=:customer')
                            ->setParameter('customer', $customer)
                            ->getQuery();
                            
      $data = $pagination->create($query);

      $data = $serializer->serialize($data, 'json', SerializationContext::create()->setGroups(array('listUser')));

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
  }

   /**
   * User detail
   * 
   * @Route("/api/customers/{id}/users/{user_id}", name="api_user_show_id" , methods={"GET"}, requirements={"user_id"="\d+"})
   * 
   * @OA\Get(
   *      path="/api/customers/{id}/users/{user_id}",
   *      description="Consulter le détail d’un user inscrit lié à un customer "
   * )
   *    @OA\Response(
   *        response=200, description="Returns users detail from customers",
   *        @Model(type=Users::class)
   *  ))
   *    @OA\Response(
   *        response=401, description="UNAUTHORIZED - JWT Token not found | Expired JWT Token | Invalid JWT Token" 
   * )
   *    @OA\Response(
   *        response=404, description="Page Not found" 
   * )
   *    @OA\Response(
   *        response=405, description="NOT ALLOWED" 
   * )
   * 
   * @Entity("user", expr="repository.find(user_id)")
   * @Entity("customer", expr="repository.find(id)")
   */
    public function getUserCustomerId(Users $user, 
    Customers $customer, 
    SerializerInterface $serializer )
    {
      if(!$customer->getUsers()->contains($user))
      {
        throw $this->createAccessDeniedException();
      }

      $data = $serializer->serialize($user, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }


    /**   
     * User add
     *  
     * @Route("/api/customers/{id}/users", name="api_user_create", methods={"POST"})
     * 
     * @OA\Post(
     *      path="/api/customers/{id}/users",
     *      description="Ajouter un nouvel utilisateur lié à un client",
     *      ),
     *      @OA\Response(
     *          response="201", description="OK - Success user is add"
     *      ),
     *      @OA\Response(
     *          response="401", description="UNAUTHORIZED - JWT Token not found | Expired JWT Token | Invalid JWT Token")
     * )
     *      @OA\Response(
     *          response=404, description="Page Not found" 
     * )
     * 
     * 
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param Customers $customer
     * @return Response
     */
    public function create(Request $request, 
    EntityManagerInterface $em, 
    SerializerInterface $serializer, 
    ValidatorInterface $validator, Customers $customer) : Response
    {
        $user = $serializer->deserialize($request->getContent(), Users::class, 'json');
        $errors = $validator->validate($user);

        if(count($errors) > 0) {
            $data = $serializer->serialize($errors, 'json');
            return new Response($data, 400, [], true);
        }
        
        $customer->addUser($user);

        $em->persist($user);
        $em->flush();

        $data = $serializer->serialize($user, 'json', SerializationContext::create()->setGroups(array('create')));

        $response = new Response($data, Response::HTTP_CREATED);
        $response->headers->set('Content-Type', 'application/json');
  
        return $response;
    }

    /**
     * User remove
     * 
     * @Route("/api/customers/{id}/users/{user_id}",name="delete", methods={"DELETE"})

     * @OA\Post(
     *      path="/api/customers/{id}/users/{user_id}",
     *      description="Supprimer un utilisateur ajouté par un client"
     *      ),
     *      @OA\Response(
     *          response="200", description="OK - Success user is remove"
     *      ),
     *      @OA\Response(
     *          response="401", description="UNAUTHORIZED - JWT Token not found | Expired JWT Token | Invalid JWT Token")
     * )
     *      @OA\Response(
     *          response=404, description="Page Not found" 
     * )
     * 
     * @Entity("user", expr="repository.find(user_id)")
     * @Entity("customer", expr="repository.find(id)")
     */
    public function deleteUserCustomer(Users $user, 
    EntityManagerInterface $em, 
    Customers $customer)
    {
        if(!$customer->getUsers()->contains($user))
        {
          throw $this->createAccessDeniedException();
        }
        $em->remove($user);
        $em->flush();
        
        return new Response('', 204);
        $response->headers->set('Content-Type', 'application/json');

        return $response; 

    }
}
?>