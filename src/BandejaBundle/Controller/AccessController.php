<?php
namespace BandejaBundle\Controller;


use BandejaBundle\Entity\Usuarios;
use BandejaBundle\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccessController extends Controller
{
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'BandejaBundle:Access:login.twig.html',
            array(
                'last_username' => $lastUsername,
                'error' => $error,
            )
        );
    }
}
