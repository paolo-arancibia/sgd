<?php
namespace AccessBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AccessBundle\Form\Model\ChangePassword;

class UsuarioController extends Controller
{
    public function viewAction(Request $request)
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');


        $changePasswordModel = new ChangePassword();

        $usuarioForm = $this->createForm('AccessBundle\Form\UsuarioType', $changePasswordModel);
        $personaForm = $this->createForm('BandejaBundle\Form\PersonaType');

        $usuarioForm->handleRequest($request);
        $personaForm->handleRequest($request);

        $usuario = $this->getUser();

        if (! $usuario) {
            $this->addFlash('danger', 'El usuario no existe');
            return $this->redirectToRoute('access_apps');
        }

        $persona = $this->getDoctrine()->getManager('customer')
                 ->getRepository('BandejaBundle:Personas')
                 ->findBy(['rut' => $usuario->getFkPersona()]);

        if (empty($persona)) {
            $this->addFlash('danger', 'El usuario no tiene datos personales');
            return $this->redirectToRoute('access_apps');
        } else
            $persona = $persona[0];

        if ($usuarioForm->isSubmitted() && $usuarioForm->isValid()) {
            $usuarioData = $usuarioForm->getData();

            $encoder = $this->container->get('security.password_encoder');

            /** user credential validation */
            if (! $encoder->isPasswordValid($usuario, $usuarioData->getOldPassword())) {
                dump($usuarioData->getOldPassword());
                dump('old pass invalid!'); die;
            }

            $new_pwr_encoded = $encoder->encodePassword($usuario,$usuarioData->getNewPassword());
            $usuario->setContrasena($new_pwr_encoded);

            $em = $this->getDoctrine()->getEntityManager('default');
            $em->persist($usuario);
            $em->flush();

            $this->addFlash('success', 'Contraseña actualizada');
            return $this->redirectToRoute('access_apps');
        }

        if ($personaForm->isSubmitted() && $personaForm->isValid()) {
            $personaData = $personaForm->getData();

            $persona->setRut( $personaData['rut'] );
            $persona->setVrut( $personaData['dv'] );
            $persona->setNombres( $personaData['nombres'] );
            $persona->setApellidoPaterno( $personaData['apellidopaterno']);
            $persona->setApellidoMaterno( $personaData['apellidomaterno']
                                          ? $personaData['apellidomaterno']
                                          : '');

            $persona->setNombreCalle( $personaData['nombre_calle'] );
            $persona->setNumdirec( $personaData['numdirec'] );
            $persona->setReferenciadir( $personaData['referenciadir']
                                        ? $personaData['referenciadir']
                                        : '' );
            $persona->setNombreComuna( $personaData['nombre_comuna'] );

            $persona->setFono( $personaData['fono'] ? $personaData['fono'] : '' );
            $persona->setFono2( $personaData['fono_2'] ? $personaData['fono_2'] : '' );
            $persona->setEmail( $personaData['email'] ? $personaData['email'] : '' );

            $persona->setFechaNacimiento( $personaData['fecha_nacimiento']
                                          ? $personaData['fecha_nacimiento']
                                          : '0000-00-00' );
            $persona->setSexo( $personaData['sexo'] );

            $persona->setUnidadV('');
            $persona->setFechaReg( new \DateTime() );

            $em2 = $this->getDoctrine()->getEntityManager('customer');
            $em2->persist($persona);
            $em2->flush();

            $this->addFlash('success', 'Datos personales actualizado');
            return $this->redirectToRoute('access_apps');
        }

        return $this->render('AccessBundle:Usuario:view.html.twig', [
            'usuario' => $usuario,
            'persona' => $persona,
            'usuarioForm' => $usuarioForm->createView(),
            'personaForm' => $personaForm->createView()
        ]);
    }

    private function listAcction()
    {
        return $this->render('AccessBundle:Usuario:list.html.twig');

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
