<?php
    
namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Annotations as OA;

class SecurityController extends AbstractController {
    
    /**
     * Authentification
     * 
     * @OA\Post(
     *      path="/api/login_check",
     *      description="Ask for an authentication token",
     *      tags={"Login"},
     *      security={},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"username", "password"},
     *              @OA\Property(type="string", property="username", example="customers0@email.com"),
     *              @OA\Property(type="string", property="password", example="password")
     *          )
     *      ),
     *      @OA\Response(
     *          response="200", description="OK - Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="token",
     *                  type="string",
     *                  example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MzI4NDI2NTgsImV4cCI6MTYzMjg0NjI1OCwicm9sZXMiOlsiUk9MRV9DVVNUT01FUiJdLCJ1c2VybmFtZSI6IlBzYUZyYW5jZSJ9.lpgfnOpZQ13Q-HtOCqtRdD8ChcI3NlWpTsiK9Ff6LlXiC7ih89tvxyPgj5xKeYFQQZlLS7838ukT_QnWdjbyEmG8NvZD5mD-jmMKBTGaEGMiVs8c0J7OcrFb_duKe8-9cmho3j1DvODzVXOqDTYL3J-C-2Qg0bW4RzCT1rVxgvAcFGHG5MbpFyvwft96V-ZablsmTE_7a3rkFnc8mvcWW4ivVr2UDKxcZKa9dQWXO72KMKF7-7eXEFmJSSXfRc9kUo-rXA9IWnJ9upzXWQeDP-0Ccgmzl6ukMzowUS2NsYwaHq7cmhB4wCTqTA8ubgpjYWbZiTxts0rbOjY5KW_0plB7VPrIarBSmi4rmjK0RJ68-MDrofpyMGy32S_TnBM0xN4cNqozxL0Rx9OxvyYnaxxSZ8NFOFQfABAevulCCG68jhXcEA6qknJ6OXVD3zESbFNYbkvt8iO48CuUXoRVb7Xv-mokrJW9CQpA7Aiz2M7vXYbEAIfux012Y_ZnTfDPseikYYT8xKFEGOFZ-oeejx15y7GbF5VLhV-IEisYC7Z7rTdsaa6OXt7sfJ1Ux9Y98bksWqncgOBddUwbGC9-r2cWF2V0C8-zcjHsfNfP5jttHgpbsEMguguX_al6QARYRnUu3_CrGqgQD2mh8EO4AX3EaswfuV-6IldyL7z3A7A"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="401", description="UNAUTHORIZED - JWT Token not found | Expired JWT Token | Invalid JWT Token")
     * )
     * @Route("/api/login_check", methods={"POST"})
     */
    public function login()
    {
      return $this->json([]);
    }

  }
?>