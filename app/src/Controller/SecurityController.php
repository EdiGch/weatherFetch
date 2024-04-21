<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    /**
     * Endpoint used for user authentication. Handling is done by LexikJWTAuthenticationBundle.
     *
     * @phpstan-ignore-next-line
     */
    public function loginCheck()
    {
    }
}
