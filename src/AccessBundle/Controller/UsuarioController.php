<?php
namespace AccessBundle\Controller;


use AccessBundle\Entity\Permisos;
use AccessBundle\Form\Model\ChangePassword;
use BandejaBundle\Entity\Personas;
use BandejaBundle\Entity\Usuarios;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UsuarioController extends Controller
{
    public function viewAction(Request $request)
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');


        $changePasswordModel = new ChangePassword();

        $usuarioForm = $this->createForm('AccessBundle\Form\UsuarioType', $changePasswordModel);
        $usuarioForm->handleRequest($request);

        $usuario = $this->getUser();

        if (! $usuario) {
            $this->addFlash('danger', 'El usuario no existe');
            return $this->redirectToRoute('access_apps');
        }

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

        return $this->render('AccessBundle:Usuario:view.html.twig', [
            'usuario' => $usuario,
            'usuarioForm' => $usuarioForm->createView(),
        ]);
    }

    public function listAction()
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        return $this->render('AccessBundle:Usuario:list.html.twig');
    }

    public function newAction(Request $request)
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        $buscarRutForm = $this->createForm('AccessBundle\Form\BuscarRutType');
        $personaForm = $this->createForm('BandejaBundle\Form\PersonaType');
        $usuarioForm = $this->createForm('AccessBundle\Form\UsuarioNuevoType');

        $buscarRutForm->handleRequest($request);
        $personaForm->handleRequest($request);
        $usuarioForm->handleRequest($request);

        if ($buscarRutForm->isValid() && $buscarRutForm->isSubmitted()) {
            $buscarRutData = $buscarRutForm->getData();

            $persona = $this->getDoctrine()->getManager('customer')
                     ->getRepository('BandejaBundle:Personas', 'pers')
                     ->findOneBy(['rut' => (string) $buscarRutData['rut']]);

            $newp = false;
            if (! $persona) {
                $persona = new Personas;
                $persona->setRut($buscarRutData['rut']);
                $persona->setVrut($buscarRutData['dv']);
                $persona->setNombreComuna('LA FLORIDA');

                $newp = true;
            }

            return $this->render('AccessBundle:Usuario:new2.html.twig', [
                'personaForm' => $personaForm->createView(),
                'usuarioForm' => $usuarioForm->createView(),
                'persona' => $persona,
                'newp' => $newp
            ]);
        }

        if ($personaForm->isValid() && $personaForm->isSubmitted()) {
            $personaData = $personaForm->getData();

            $persona = $this->getDoctrine()->getManager('customer')
                     ->getRepository('BandejaBundle:Personas', 'pers')
                     ->findOneBy(['rut' => (string) $personaData['rut']]);

            if (! $persona)
                $persona = new Personas;

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

            $this->addFlash('success', 'Persona rut ' . $personaData['rut'] . ' guardada');

            return $this->render('AccessBundle:Usuario:new2.html.twig', [
                'personaForm' => $personaForm->createView(),
                'usuarioForm' => $usuarioForm->createView(),
                'persona' => $persona,
                'newp' => false
            ]);
        }

        if ($usuarioForm->isValid() && $usuarioForm->isSubmitted()) {
            $usuarioData = $usuarioForm->getData();

            $usuario = new Usuarios;
            $usuario->setNombre($usuarioData['username']);
            $usuario->setFkPersona($usuarioData['rut']);
            $usuario->setFechaC(new \DateTime());
            $usuario->setFechaM(new \DateTime());

            $encoder = $this->container->get('security.password_encoder');

            $new_pwr_encoded = $encoder->encodePassword($usuario,$usuarioData['password']);
            $usuario->setContrasena($new_pwr_encoded);

            $em = $this->getDoctrine()->getEntityManager('default');
            $em->persist($usuario);
            $em->flush();

            $this->addFlash('success', 'Usuario ' . $usuario->getNombre() . ' guardado');
            return $this->redirectToRoute('usuario_list');
        }

        return $this->render('AccessBundle:Usuario:new1.html.twig', [
            'buscarRutForm' => $buscarRutForm->createView(),
        ]);
    }

    public function editAction(Request $request, $id = null)
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        $changePasswordModel = new ChangePassword();

        $usuarioForm = $this->createForm('AccessBundle\Form\UsuarioType', $changePasswordModel);
        $personaForm = $this->createForm('BandejaBundle\Form\PersonaType');
        $permisosForm = $this->createForm('AccessBundle\Form\PermisosType');

        $usuarioForm->handleRequest($request);
        $personaForm->handleRequest($request);
        $permisosForm->handleRequest($request);

        $usuario = $this->getDoctrine()->getManager('default')
                 ->getRepository('BandejaBundle:Usuarios')
                 ->findBy(['idUsuario' => $id]);

        $usuario = $usuario[0];

        if (empty($usuario)) {
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

        $permisos = $this->getDoctrine()->getManager('default')
                  ->getRepository('AccessBundle:Permisos')
                  ->findBy([
                      'fkUsuario' => $usuario->getIdUsuario(),
                      'fechaE' => null
                  ], ['fkApp' => 'ASC']);

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
            return $this->redirectToRoute('usuario_list');
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
            return $this->redirectToRoute('usuario_list');
        }

        if ($permisosForm->isSubmitted()) {
            $permisosData = $permisosForm->getData();

            /** duplicate key case */
            $permiso = $this->getDoctrine()->getManager('default')
                      ->getRepository('AccessBundle:Permisos')
                      ->findOneBy([
                          'fkUsuario' => $usuario->getIdUsuario(),
                          'fkFkPerfil' => $permisosData['roles']->getIdPerfil(),
                          'fkApp' => $permisosData['systems']->getIdApp()
                      ]);

            if(! $permiso) {
                $permiso = new Permisos();

                $permiso->setFkApp($permisosData['systems']);
                $permiso->setFkFkPerfil($permisosData['roles']);
                $permiso->setFkUsuario($usuario);
                $permiso->setFechaC(new \DateTime);
                $permiso->setFechaM(new \DateTime);
            } else {
                $permiso->setFechaE(null);
                $permiso->setFechaM(new \DateTime);
            }

            $em = $this->getDoctrine()->getEntityManager('default');
            $em->persist($permiso);
            $em->flush();

            return $this->redirectToRoute('usuario_edit', ['id' => $id]);
        }

        return $this->render('AccessBundle:Usuario:edit.html.twig', [
            'usuario' => $usuario,
            'persona' => $persona,
            'permisos' => $permisos,
            'usuarioForm' => $usuarioForm->createView(),
            'personaForm' => $personaForm->createView(),
            'permisosForm' => $permisosForm->createView()
        ]);
    }

    public function deletePermisoAction(Request $request, $id = null)
    {
        if (! $id) {
            $this->addFlash('danger', 'No existe el permiso');

            return $this->redirectToRoute('usuario_edit', [
                'id' => $permiso->getFkUsuario()->getIdUsuario()
            ]);
        }

        $permiso = $this->getDoctrine()->getManager('default')
                 ->getRepository('AccessBundle\Entity\Permisos')
                 ->findOneBy(['idPermiso' => $id]);

        if (! $permiso) {
            $this->addFlash('danger', 'No se encontró el permiso');

            return $this->redirectToRoute('usuario_edit', [
                'id' => $permiso->getFkUsuario()->getIdUsuario()
            ]);
        }

        $permiso->setFechaE(new \DateTime());

        $em = $this->getDoctrine()->getEntityManager('default');
        $em->persist($permiso);
        $em->flush();

        $this->addFlash('success', 'Se eliminó el permiso');
        return $this->redirectToRoute('usuario_edit', [
            'id' => $permiso->getFkUsuario()->getIdUsuario()
        ]);
    }

    public function usuariosAction()
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        $usuarios = $this->getDoctrine()->getManager('default')
                  ->getRepository('BandejaBundle:Usuarios')
                  ->findBy(['fechaE' => null], ['nombre' => 'ASC']);

        foreach ($usuarios as $u) {
            $usuArray['data'][] = array(
                'idUsuario' => $u->getIdUsuario(),
                'username' => $u->getNombre(),
                'rut' => $u->getFkPersona()
             );

            $perArray[$u->getFkPersona()] = $u->getFkPersona();
        }

        $query = $this->getDoctrine()->getManager('customer')
               ->createQueryBuilder();

        $personas = $query->select('pers')
                  ->from('BandejaBundle:Personas', 'pers', 'pers.rut')
                  ->where('pers.rut IN (:personas)')
                  ->setParameter('personas', $perArray)
                  ->getQuery()
                  ->getResult();

        foreach ($usuArray['data'] as $k => $u) {
            $usuArray['data'][$k]['persona'] = [
                'nombrecompleto' => $personas[$u['rut']]->getNombreCompleto(),
                'rutdv' => $personas[$u['rut']]->getRut() .'-'. $personas[$u['rut']]->getVrut()

            ];
        }

        $response = new JsonResponse($usuArray);

        return $response;
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
