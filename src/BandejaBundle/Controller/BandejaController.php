<?php
namespace BandejaBundle\Controller;


use BandejaBundle\Entity\Adjuntos;
use BandejaBundle\Entity\Departamentos;
use BandejaBundle\Entity\DepUsu;
use BandejaBundle\Entity\Derivaciones;
use BandejaBundle\Entity\Documentos;
use BandejaBundle\Entity\Personas;
use BandejaBundle\Entity\TiposDocumentos;
use BandejaBundle\Form\BuscarType;
use BandejaBundle\Form\DerivarType;
use BandejaBundle\Form\NuevoDocumentoType;
use BandejaBundle\Form\PersonaType;
use BandejaBundle\Form\RemitenteType;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class BandejaController extends Controller
{
    public function indexAction()
    {
        return $this->redirectToRoute('recibidos_bandeja');
    }

    public function recibidosAction($page = 1)
    {
        $this->setOnSession();

        $searchForm = $this->createForm(BuscarType::class);
        $derivarForm = $this->createForm(DerivarType::class);

        $docsByPage = 25; // documentos por página

        $results = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('BandejaBundle:Documentos')
                 ->findAllByDepto($this->get('session')->get('departamento'), 1, ($page - 1), $docsByPage);

        $documentos = array_filter($results, function($var) {
            return $var instanceof Documentos;
        });

        $derivaciones = array_filter($results, function($var) {
            return $var instanceof Derivaciones;
        });

        return $this->render(
            'BandejaBundle:Bandeja:index.html.twig',
            array(
                'page' => $page,
                'menu_op' => 'recibidos',
                'searchForm' => $searchForm->createView(),
                'derivarForm' => $derivarForm->createView(),
                'documentos' => $documentos,
                'derivaciones' => $derivaciones,
            )
        );
    }

    public function porrecibirAction($page = 0)
    {
        $this->setOnSession();

        $searchForm = $this->createForm(BuscarType::class);
        $results = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('BandejaBundle:Documentos')
                 ->findAllByDepto($this->get('session')->get('departamento'), 2, ($page - 1), 25);

        $documentos = array_filter($results, function($var) {
            return $var instanceof Documentos;
        });

        $derivaciones = array_filter($results, function($var) {
            return $var instanceof Derivaciones;
        });

        return $this->render(
            'BandejaBundle:Bandeja:porrecibir.html.twig',
            array(
                'documentos' => $documentos,
                'derivaciones' => $derivaciones,
                'page' => $page,
                'menu_op' => 'porrecibir',
                'searchForm' => $searchForm->createView(),
            )
        );
    }

    public function despachadosAction($page = 0)
    {
        $this->setOnSession();

        $searchForm = $this->createForm(BuscarType::class);
        $results = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('BandejaBundle:Documentos')
                 ->findAllByDepto($this->get('session')->get('departamento'), 2, ($page - 1), 25);

        $documentos = array_filter($results, function($var) {
            return $var instanceof Documentos;
        });

        $derivaciones = array_filter($results, function($var) {
            return $var instanceof Derivaciones;
        });

        return $this->render(
            'BandejaBundle:Bandeja:despachados.html.twig',
            array(
                'documentos' => $documentos,
                'derivaciones' => $derivaciones,
                'page' => $page,
                'menu_op' => 'despachados',
                'searchForm' => $searchForm->createView(),
            )
        );
    }

    public function verAction($id)
    {
        $derivarForm = $this->createForm(DerivarType::class);

        $repo = $this->getDoctrine()->getRepository('BandejaBundle:Documentos');
        $query = $repo->createQueryBuilder('doc')
               ->where('doc.idDoc = :ID')
               ->andWhere('doc.fechaE is NULL')
               ->setParameter('ID', $id)
               ->getQuery();
        $documento = $query->getResult();

        if (!isset($documento) || empty($documento)) {
            $this->addFlash('warning', 'No existe en docuemnto IDDOC ' . $id);

            return $this->redirect($this->getRequest()->headers->get('referer'));
        }

        $urlArray = explode( '/', $this->getRequest()->headers->get('referer'));

        return $this->render(
            'BandejaBundle:Bandeja:ver.html.twig',
            array(
                'id' => $id,
                'menu_op' => end($urlArray),
                'documento' => $documento[0],
                'derivarForm' => $derivarForm->createView(),
            )
        );
    }

    public function nuevoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $tipos = $this->getTiposDocs();

        $derivarForm = $this->createForm(DerivarType::class);
        $nuevoForm = $this->createForm(NuevoDocumentoType::class);
        $personaForm = $this->createForm(PersonaType::class);
        $remitenteForm = $this->createForm(RemitenteType::class);

        // $nuevoForm es el principal
        $derivarForm->handleRequest($request);
        $nuevoForm->handleRequest($request);
        $personaForm->handleRequest($request);
        $remitenteForm->handleRequest($request);

        if ($nuevoForm->isSubmitted() && $nuevoForm->isValid()) {
            $nuevoData = $nuevoForm->getData();
            $derivarData = $derivarForm->getData();
            $remitenteData = $remitenteForm->getData();
            $personaData = $personaForm->getData();

            if ($remitenteData['id_persona']) {
                $persona = $this->getDoctrine()
                         ->getRepository('BandejaBundle:Personas')
                         ->find( $remitenteData['id_persona'] );
            } elseif ($remitenteData['id_depto']) {
                $persona = $this->getDoctrine()
                         ->getRepository('BandejaBundle:Departamentos')
                         ->findEncargado($remitenteData['id_depto']);
            } else {
                $persona = $this->createNewPersona($personaData);

                $em->persist($persona);
            }

            $loginUser = $this->getUser();
            $nuevoData['persona'] = $persona;

            $documento = $this->createNewDocumento($nuevoData, 2);

            $em->persist($documento);

            // guardar archivos adjuntos
            $files = $derivarData['adjuntos'];

            if (count($files)) {
                foreach( $files as $file ) {
                    $adjunto = new Adjuntos();
                    $adjunto->setFile( $file );
                    $adjunto->setUrl( $file->getClientOriginalName() );
                    $adjunto->setTipo(1);
                    $adjunto->setFkDoc($documento);
                    $adjunto->setFechaC(new \DateTime());
                    $adjunto->setFechaM(new \DateTime());
                    $adjunto->setFkUsuario($loginUser);

                    $adjunto->upload();

                    $em->persist($adjunto);
                }
            }

            $expr = new Comparison('encargado', '=', 1);
            $encargadoCriteria = new Criteria();
            $encargadoCriteria->where( $expr );

            if ($request->get('guardar') === 'derivar') {
                foreach ($derivarData['originales'] as $depto) {
                    $derivacion = $this->createNewDerivacion(
                        array('tipo' => 1, 'nota' => $derivarData['nota_original']),
                        $documento,
                        $loginUser, $loginUser->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkDepto(),
                        $depto->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkUsuario(), $depto);

                    $em->persist($derivacion);
                }

                foreach ($derivarData['copias'] as $depto) {
                    $derivacion = $this->createNewDerivacion(
                        array('tipo' => 2, 'nota' => $derivarData['nota_copias']),
                        $documento,
                        $loginUser, $loginUser->getDepUsus()->matching($criteria)->get(0)->getFkDepto(),
                        $depto->getDepUsus()->matching($criteria)->get(0)->getFkUsuario(), $depto);

                    $em->persist($derivacion);
                }

                $em->flush();
                $this->addFlash('success', 'Documento derivado');
                return $this->redirectToRoute('recibidos_bandeja');

            } elseif ($request->get('guardar') === 'guardar') {
                $derivacion = $this->createNewDerivacion(
                    array('tipo' => 1, 'nota' => $derivarData['nota_original']),
                    $documento,
                    $loginUser, $loginUser->getDepUsus()->matching($criteria)->get(0)->getFkDepto(),
                    $loginUser, $loginUser->getDepUsus()->matching($criteria)->get(0)->getFkDepto());

                $em->persist($derivacion);
                $em->flush();

                $this->addFlash('success', 'Documento guardado');
                return $this->redirectToRoute('porrecibir_bandeja');
            }
        }

        return $this->render(
            'BandejaBundle:Bandeja:editar.html.twig',
            array(
                'menu_op' => '',
                'tipos' => $tipos,
                'derivarForm' => $derivarForm->createView(),
                'nuevoForm' => $nuevoForm->createView(),
                'personaForm' => $personaForm->createView(),
                'remitenteForm' => $remitenteForm->createView(),
            )
        );
    }

    public function personasAction($str = "")
    {
        $personas = $this->getPersonas($str);
        $persArray = [];

        foreach($personas as $p) {
            $persArray[ $p->getRut() ] = array(
                'rut' => number_format($p->getRut(), 0, ',', '.') . '-' . $p->getVRut(),
                'id' => $p->getRut(),
                'nombre' => $p->getNombreCompleto(),
                'tipo' => 'pers',
            );
        }

        $response = new JsonResponse($persArray);

        return $response;
    }

    public function departamentosAction($str = "")
    {
        $deptos = $this->getDeptos($str);
        $deptosArray = [];

        foreach($deptos as $d) {
            $expr = new Comparison('encargado', '=', 1);

            $criteria = new Criteria();
            $criteria->where( $expr );

            $encargados = $d->getDepUsus()->matching( $criteria );

            $deptosArray[ $d->getIdDepartamento() ] = array(
                'idDepartamento' => $d->getIdDepartamento(),
                'descripcion' => $d->getDescripcion(),
                'encargado' => $encargados->isEmpty() ? null : $encargados->first()->getFkUsuario()->getFkPersona()->getNombreCompleto(),
                'idEncargado' => $encargados->isEmpty() ? null : $encargados->first()->getFkUsuario()->getIdUsuario(),
                'tipo' => 'depto',
            );
        }

        $response = new JsonResponse($deptosArray);

        return $response;
    }

    public function derivarAction(Request $request)
    {
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    private function getDeptos($str = "")
    {
        $repository = $this->getDoctrine()->getRepository('BandejaBundle:Departamentos');

        $query = $repository->createQueryBuilder('depto')
               ->where('depto.descripcion like :STR')
               ->orderBy('depto.descripcion', 'ASC')
               ->setParameter(':STR', '%'.$str.'%')
               ->getQuery();

        return $query->getResult();
    }

    private function getTiposDocs()
    {
        $repository = $this->getDoctrine()->getRepository('BandejaBundle:TiposDocumentos');

        $query = $repository->createQueryBuilder('tipodoc')
               ->orderBy('tipodoc.abrev', 'ASC')
               ->getQuery();

        return $query->getResult();
    }

    private function getPersonas($str)
    {
        $repository = $this->getDoctrine()->getRepository('BandejaBundle:Personas');

        $query = $repository->createQueryBuilder('pers')
               ->where('pers.nombres like :STR')
               ->orWhere('pers.apellidopaterno like :STR')
               ->orWhere('pers.apellidomaterno like :STR')
               ->orWhere('CONCAT(pers.rut,\'-\',pers.vrut) like :STR')
               ->setParameter(':STR', '%'.$str.'%')
               ->orderBy('pers.apellidopaterno, pers.apellidomaterno, pers.nombres', 'ASC')
               ->getQuery();

        return $query->getResult();
    }

    private function createNewPersona($personaData)
    {
        $persona = new Personas();

        list($rut, $vrut) = explode('-', $personaData['rut']);

        $persona->setRut( $rut );
        $persona->setVrut( $vrut );
        $persona->setNombres( $personaData['nombres'] );
        $persona->setApellidoPaterno( $personaData['apellidopaterno'] );
        $persona->setApellidoMaterno( $personaData['apellidomaterno'] );

        $persona->setNombreCalle( $personaData['nombre_calle'] );
        $persona->setNumdirec( $personaData['numdirec'] );
        $persona->setReferenciadir( $personaData['referenciadir'] );
        $persona->setNombreComuna( $personaData['nombre_comuna'] );

        $persona->setFono( $personaData['fono'] );
        $persona->setFono2( $personaData['fono_2'] );
        $persona->setEmail( $personaData['email'] );

        $persona->setFechaNacimiento( $personaData['fecha_nacimiento'] );
        $persona->setSexo( $personaData['sexo'] );

        $persona->setUnidadV('');
        $persona->setFechaReg( new \DateTime() );

        return $persona;
    }

    private function createNewDocumento($nuevoData, $tipo = 2)
    {
        $documento = new Documentos();
        $documento->setFkTipoDoc($nuevoData['fkTipoDoc']);
        $documento->setNroExpediente($nuevoData['nroExpediente']);
        $documento->setFechaDoc( $nuevoData['fechaDoc'] );
        $documento->setAnt($nuevoData['ant']);
        $documento->setMat($nuevoData['mat']);
        $documento->setExt($nuevoData['ext']);
        $documento->setEstado($tipo); // 0=ARCHIVADO,1=NORMAL,2=PORRECIBIR
        $documento->setFkRutPersona($nuevoData['persona']);
        $documento->setFkUsuario($this->getUser());
        $documento->setFechaC( new \DateTime() );
        $documento->setFechaM( new \DateTime() );

        return $documento;
    }

    private function createNewDerivacion($derivarData, $documento, $remitente, $deptorem, $destinatario, $deptodes)
    {
        $derivacion = new Derivaciones();

        $derivacion->setTipo($derivarData['tipo']);
        $derivacion->setNota($derivarData['nota']);
        $derivacion->setFkDoc($documento);
        $derivacion->setFechaC(new \DateTime());
        $derivacion->setFechaM(new \DateTime());

        $derivacion->setFkRemitente($remitente);
        $derivacion->setFkDeptorem($deptorem);

        $derivacion->setFkDestinatario($destinatario);
        $derivacion->setFkDeptodes($deptodes);

        return $derivacion;
    }

    private function setOnSession()
    {
        $session = $this->get('session');

        $loginUser = $this->getUser();

        $expr = new Comparison('encargado', '=', 1);
        $encargado = new Criteria();
        $encargado->where( $expr );

        if ($session->get('departamento') === null)
            $session->set('departamento', $loginUser->getDepUsus()->matching($encargado)->get(0)->getFkDepto());
    }


    /*
      $filtersForm = $this->createFormBuilder()

      ->add('clases', ChoiceType::class, [
      'choices' => [
      'Pendientes' => 'pendientes',
      'Archivados' => 'archivados',
      ],
      'choices_as_values' => true,
      'expanded' => true,
      'multiple' => false,
      'label_attr' => ['class' => 'form-check-label px-2 py-1'],
      ])
      ->getForm();*/
}
