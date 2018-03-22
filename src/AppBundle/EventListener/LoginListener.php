<?php
namespace AppBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\AuthToken;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class LoginListener implements EventSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onLogin',
            SecurityEvents::INTERACTIVE_LOGIN => 'onLogin',
        );
    }

    public function onLogin($event)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $email = $event->getAuthenticationToken()->getUser()->getEmail();
        $user = $em->getRepository('AppBundle:User')->findOneByEmail($email);
        $token = $em->getRepository('AppBundle:AuthToken')->findOneByUser($user);
        if($token == null){
          $token = new AuthToken();
          $token->setValue('test');
          $token->setCreatedAt(new \DateTime());
          $token->setUser($user);

          $em->persist($token);
          $em->flush();
        }
    }
}
