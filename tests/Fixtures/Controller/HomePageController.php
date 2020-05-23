<?php

declare(strict_types=1);

namespace PK\Tests\EagerResettableServicesBundle\Fixtures\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController
{
    /**
     * @Route("/")
     */
    public function homepage(): Response
    {
        return new Response();
    }
}
