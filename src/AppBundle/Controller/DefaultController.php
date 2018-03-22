<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class DefaultController extends Controller
{
  /**
   * @Route("/")
   */
   public function homepageAction(){
     return new Response('homepage');
   }
  /**
   * @Rest\Get("/api")
   */
  public function indexAction(Request $request)
  {
    $data = ['id' => 1];
    return new JsonResponse($data);
  }
}
