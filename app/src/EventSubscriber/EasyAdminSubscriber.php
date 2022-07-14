<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Budget;
use App\Entity\LeftBudget;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{

    public function __construct()
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setUserLeftBudget'],
        ];
    }

    public function setUserLeftBudget(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();


        if (!($entity instanceof User)) {
            return;
        }

        foreach($entity->getPosition()->getBudgets() as $budget){
            $leftBudget = new LeftBudget();
            $leftBudget->setCategory($budget->getCategory());
            $leftBudget->setAmount($budget->getAmount());

            $entity->addLeftBudget($leftBudget);
        }
    }
}