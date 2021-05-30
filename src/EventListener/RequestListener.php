<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;
use Psr\Log\LoggerInterface;
use App\Entity\User;

class RequestListener
{
    private $logger;
    private $user;

    public function __construct(LoggerInterface $logger, Security $security) {
        $this->logger = $logger;
        $this->user = $security->getUser();
    }

    public function onKernelResponse(ResponseEvent $event)
    {       
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        if ($this->user && $this->user instanceof User) {
            $this->logger->info('User : ' . $this->user->getName() . ' / Id : ' . $this->user->getId());
        }        

        // Request
        $request = $event->getRequest();
        
        $response = $event->getResponse();
        
        // Set multiple headers simultaneously
        $response->headers->add([
            'Header-Name1' => 'value',
            'Header-Name2' => 'ExampleValue'
        ]);
        
        // Or set a single header
        $response->headers->set("Example-Header", "ExampleValue");
    }
}