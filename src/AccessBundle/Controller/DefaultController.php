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

        $apps = $this->getDoctrine()->getManager('default')
              ->getRepository('AccessBundle:App')
              ->findByUser($this->getUser());

        return $this->render('AccessBundle:Default:index.html.twig', array(
            'apps' => $apps
        ));
    }

    public function passportAction(Request $request, $id = 0)
    {
        $app = $this->getDoctrine()->getManager('default')
             ->getRepository('AccessBundle\Entity\App')
             ->findOneBy(['idApp' => $id]);

        if (! $app) {
            $this->addFlash('danger', 'No existe la aplicación solicitada');
            return $this->redirectToRoute('access_apps');
        }

        $usuario = $this->getUser();

        $permisos = $this->getDoctrine()->getManager('default')
                  ->getRepository('AccessBundle:Permisos')
                  ->findBy([
                      'fkUsuario' => $usuario->getIdUsuario(),
                      'fkApp' => $app->getIdApp(),
                      'fechaE' => null
                  ]);

        // ccarino mapero de perfiles
        if ($app->getIdApp() == 3) {
            // ROL => rol_sistema_externo
            $mapping_perfil = array(
                'ROLE_ADMIN' => '0',    // admin,
                'ROLE_CCARINO_ASISTENTE' => '4',
                'ROLE_CCARINO_PSICOLOGO' => '1',
                'ROLE_CCARINO_NUTRICIONISTA' => '5',
                'ROLE_CCARINO_PSIQUIATRA' => '2',
            );

            // USUARIO => usuario_externo
            $mapping_usuario = array(
                '1' => '1',
            );

            $_SESSION['id_user'] = $mapping_usuario[ $usuario->getIdUsuario() ];
            $_SESSION['perfil'] = $mapping_perfil[$permisos[0]->getfkFkPerfil()->getNombre()];

            if ($_SESSION['perfil'] == '0')
                return $this->redirect('http://app.laflorida.cl/ccarino/consult.php');
            elseif ($_SESSION['perfil'] == '4')
                return $this->redirect('http://app.laflorida.cl/ccarino/consult.php');
            elseif ($_SESSION['perfil'] == '1')
                return $this->redirect('http://app.laflorida.cl/ccarino/consult.php');
            elseif ($_SESSION['perfil'] == '5')
                return $this->redirect('http://app.laflorida.cl/ccarino/agenda.php');
            elseif ($_SESSION['perfil'] == '2')
                return $this->redirect('http://app.laflorida.cl/ccarino/agenda.php');
            elseif ($_SESSION['perfil'] == '3')
                return $this->redirect('http://app.laflorida.cl/ccarino/mis-talleres.php');
        }

        $this->addFlash('warning', 'no se encontraron los permisos de la aplicación');
        return $this->redirectToRoute('access_apps');
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
