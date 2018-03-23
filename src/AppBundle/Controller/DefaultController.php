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
      return new Response(0); //User doesn't exists
    }

    $encoder = $this->get('security.password_encoder');
    $isPasswordValid = $encoder->isPasswordValid($user, $pass);

    if($isPasswordValid){
      $tokens = $em->getRepository('AppBundle:AuthToken')->findByUser($user);

      //Remove old tokens
      if($tokens != null){
        foreach($tokens as $token){
          $em->remove($token);
        }
      }

      $authToken = new AuthToken();
      $authToken->setValue(base64_encode(random_bytes(50)));
      $authToken->setCreatedAt(new \DateTime('now'));
      $authToken->setUser($user);
      $em->persist($authToken);

      $user->setLastLogin(new \DateTime('now'));
      $em->persist($user);

      $em->flush();

      return $authToken; //Your X-Auth-Token
    }

    return new Response(1); //Invalid password
  }

  /**
   * @Rest\Get("/auth-break")
   */
  public function authBreakAction(Request $request)
  {
    $token = $request->headers->get('X-Auth-Token');
    if($token != null){
      $em = $this->get('doctrine.orm.entity_manager');
      $auth = $em->getRepository('AppBundle:AuthToken')->findOneByValue($token);
      if($auth != null){
        $em->remove($auth);
        $em->flush();

        return new Response(1); //Token removed
      }
      else{
        return new Response(2); //Token not found
      }
    }

    return new Response(0); //Token doesn't exists
  }
}
