<?php
namespace BandejaBundle\Controller;


use BandejaBundle\Entity\Usuarios;
use BandejaBundle\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccessController extends Controller
{
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            //'Access/login.html.twig',
            'BandejaBundle:Access:login.twig.html',
            array(
                'last_username' => $lastUsername,
                'error' => $error,
            )
        );
    }

    public function logoutAction()
    {
        return;
    }

    public function changeDeptoAction(Request $request, $idDepto)
    {
        $d = $this->getDoctrine()
           ->getEntityManager()
           ->getRepository('BandejaBundle:Departamentos')
           ->find($idDepto);

        $this->get('session')->set('departamento', $d);

        return $this->redirect($request->headers->get('referer'));
    }
}
