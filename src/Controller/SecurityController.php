<?php
    
namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController {
    
    /**
     * @Route("/api/login_check")
     */
    public function login()
    {
      return $this->json([]);
    }

  }
?>