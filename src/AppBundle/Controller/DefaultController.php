<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\AuthToken;

class DefaultController extends Controller
{
  /**
   * @Rest\View
   * @Rest\Get("/webservice")
   */
  public function indexAction(Request $request)
  {
    $data = ['id' => 1];
    return $data;
  }

  /**
   * @Rest\View(statusCode=Response::HTTP_CREATED)
   * @Rest\Post("/auth")
   */
  public function authAction(Request $request)
  {
    $username = $request->request->get('username');
    $pass = $request->request->get('password');
    $em = $this->get('doctrine.orm.entity_manager');
    $user = $em->getRepository('AppBundle:User')->findOneByUsername($username);

    if(!$user){
      return 0;
    }

    $encoder = $this->get('security.password_encoder');
    $isPasswordValid = $encoder->isPasswordValid($user, $pass);

    if($isPasswordValid){
      $authToken = new AuthToken();
      $authToken->setValue(base64_encode(random_bytes(50)));
      $authToken->setCreatedAt(new \DateTime('now'));
      $authToken->setUser($user);

      $em->persist($authToken);
      $em->flush();
      var_dump($authToken->getValue());

      return $authToken;
    }

    return 1;
  }

}
