<?php
    
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController {
    
    /**
     * @Route("/api/users")
     */
    public function getUsers()
    {
      return $this->json([]);
    }

  }

?>