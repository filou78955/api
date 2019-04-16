<?php
namespace App\services;


use App\Entity\Pret;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PretSubscriber Implements EventSubscriberInterface
{
    private $token;
    public function __construct(TokenStorageInterface $token)
    {
        $this->token=$token;
    }
    public static function getSubscribedEvents()
    {
        return [
           KernelEvents::VIEW =>['getAuthenticatedUser', EventPriorities::PRE_WRITE]
        ];
    }

    public function getAuthenticatedUser(GetResponseForControllerResultEvent $event)
    {
        $entity= $event->getControllerResult();
        $method= $event->getRequest()->getMethod();
        $adherent= $this->token->getToken()->getUser();
        if ($entity instanceof Pret) {
           if ($method == Request::METHOD_POST) {
            $entity->setAdherent($adherent);
           }elseif($method == Request::METHOD_PUT){
            if ($entity->getDateRetourReelle() == null) {
                $entity->getLivre()->setDispo(false);
            }else{
                $entity->getLivre()->setDispo(true);
            }
        } elseif ($method == Request::METHOD_DELETE) {
            $entity->getLivre()->setDispo(true);
        }
        return;
    }

 }
}
?>