<?php
namespace AccessBundle\Controller;


use BandejaBundle\Entity\Usuarios;
use BandejaBundle\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller
{
    public function indexAction()
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        return $this->redirectToRoute('login_access');
    }

    public function appsAction()
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        /** @todo filter apps by ACL */
        $apps = $this->getDoctrine()->getManager('customer')
              ->getRepository('AccessBundle:App')
              ->findAll();

        return $this->render('AccessBundle:Default:index.html.twig', array(
            'apps' => $apps
        ));
    }

    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error)
            $this->addFlash('danger', $error->getMessage());

        return $this->render(
            'BandejaBundle:Access:login.twig.html', array(
                'last_username' => $lastUsername)
        );
    }

    public function logoutAction()
    {
    }

    private function setOnSession()
    {
        $session = $this->get('session');
        $loginUser = $this->getUser();

        // Check the login user data
        if (! $loginUser) {
            $this->addFlash('danger', 'Tiene que iniciar sesión para continuar.');

            return false;
        }

        $persona = $this->getDoctrine()
                 ->getRepository('BandejaBundle:Personas', 'customer')
                 ->find($loginUser->getFkPersona());

        if (! $persona) {
            $this->addFlash('danger', 'El usuario no tiene datos personales.');

            return false;
        }

        // Set session variables
        if ($session->get('departamento') === null)
            $session->set('departamento', $loginUser->getDepUsus()->get(0)->getFkDepto());

        if ($session->get('user_persona') === null)
            $session->set('user_persona', $persona);

        return true;
    }
}
