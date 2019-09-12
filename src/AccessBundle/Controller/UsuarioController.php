<?php
namespace AccessBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UsuarioController extends Controller
{
    public function viewAction(Request $request, $id)
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        $usuarioForm = $this->createForm('AccessBundle\Form\UsuarioType');
        $personaForm = $this->createForm('BandejaBundle\Form\PersonaType');

        $usuarioForm->handleRequest($request);
        $personaForm->handleRequest($request);

        $usuario = $this->getDoctrine()->getManager('default')
                 ->getRepository('BandejaBundle:Usuarios')
                 ->findBy(['idUsuario' => $id]);

        if (! $usuario) {
            $this->addFlash('danger', 'El usuario no existe');
            return $this->redirectToRoute('access_apps');
        }

        $usuario = $usuario[0];

        $persona = $this->getDoctrine()->getManager('customer')
                 ->getRepository('BandejaBundle:Personas')
                 ->findBy(['rut' => $usuario->getFkPersona()]);

        if (! $persona) {
            $this->addFlash('danger', 'El usuario no tiene datos personales');
            return $this->redirectToRoute('access_apps');
        }

        if ($usuarioForm->isSubmitted() && $usuarioForm->isValid()) {
            $usuarioData = $usuarioForm->getData();

            if( $usuarioData['contrasena'] !== $usuarioData['validar_contrasena'] ) {
                $this->addFlash('danger', 'la contraseña es distinta a validar contraseña');

                return $this->render('AccessBundle:Usuario:view.html.twig', [
                    'usuario' => $usuario,
                    'usuarioForm' => $usuarioForm->createView(),
                    'personaForm' => $personaForm->createView()
                ]);
            }

            $usuario->setNombre($usuarioData['nombre']);
            $usuario->setContrasena($usuarioData['contrasena']);
            dump($usuarioData['contrasena'], $usuario->getContrasena());die;

        }

        return $this->render('AccessBundle:Usuario:view.html.twig', [
            'usuario' => $usuario,
            'usuarioForm' => $usuarioForm->createView(),
            'personaForm' => $personaForm->createView()
        ]);
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

        return true;
    }
}
