<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ConferenceController extends AbstractController
{
    /**
     * @Route("/hello/{name}", name="conference")
     */
    public function index(string $name='' )
    {   $greet='';

        $greet =sprintf('<h1>Hello %s!</h1>',htmlspecialchars($name));
        return new Response('<html>
            <body>
                $greet
                <img src="/images/under-construction.gif"/>
            </body>
        </html>');
    }
}
