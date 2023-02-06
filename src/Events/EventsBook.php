<?php

namespace App\Events;

use App\Entity\Book;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventsBook implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['createBook', EventPriorities::PRE_WRITE],
        ];
    }

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }


    public function createBook( ViewEvent $event)
    {
        $book = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if($book instanceof Book && $method === "POST") {
            $title = $book->getTitle();
            $titleSlug = $this->slugger->slug($title);
            $book->setSlug($titleSlug);
            $book->setCreatedAt(new \Datetime);
            $book->setUpdatedAt(new \Datetime);
        }
    }


    public function editBook( ViewEvent $event)
    {
        $book = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if($method === "PUT") {
            $title = $book->getTitle();
            $titleSlug = $this->slugger->slug($title);
            $book->setSlug($titleSlug);
            $book->setUpdatedAt(new \Datetime);
        }
    }
}