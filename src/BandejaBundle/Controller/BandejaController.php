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

class BandejaController extends Controller
{
    public function indexAction()
    {
        return $this->redirectToRoute('recibidos_bandeja');
    }

    public function recibidosAction($page = 0)
    {
        $loginUser = $this->getUser();

        $expr = new Comparison('encargado', '=', 1);
        $criteria = new Criteria();
        $criteria->where( $expr );

        $dpto = $loginUser->getDepUsus()->matching($criteria)->get(0)->getFkDepto();

        $searchForm = $this->createForm(BuscarType::class);
        $derivarForm = $this->createForm(DerivarType::class);


        $results =  $this->getDoctrine()
                    ->getManager()
                    ->getRepository('BandejaBundle:Documentos')
                    ->findAllRecibidosByDepto( $dpto );

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
                'menu_op' => 'bandeja',
                'searchForm' => $searchForm->createView(),
                'derivarForm' => $derivarForm->createView(),
                'documentos' => $documentos,
                'derivaciones' => $derivaciones,
            )
        );
    }

    public function porrecibirAction($page = 0)
    {
        $searchForm = $this->createForm(BuscarType::class);

        return $this->render(
            'BandejaBundle:Bandeja:porrecibir.html.twig',
            array(
                'page' => $page,
                'menu_op' => 'porrecibir',
                'searchForm' => $searchForm->createView(),
            )
        );
    }

    public function despachadosAction($page = 0)
    {
        $searchForm = $this->createForm(BuscarType::class);

        return $this->render(
            'BandejaBundle:Bandeja:despachados.html.twig',
            array(
                'page' => $page,
                'menu_op' => 'despachados',
                'searchForm' => $searchForm->createView(),
            )
        );
    }

    public function verAction($id)
    {
        $loginUser = $this->getUser();
        $derivarForm = $this->createForm(DerivarType::class);

        $repo = $this->getDoctrine()->getRepository('BandejaBundle:Documentos');
        $query = $repo->createQueryBuilder('doc')
               ->where('doc.idDoc = :ID')
               ->setParameter('ID', $id)
               ->getQuery();
        $documento = $query->getResult()[0];

        return $this->render(
            'BandejaBundle:Bandeja:ver.html.twig',
            array(
                'id' => $id,
                'documento' => $documento,
                'derivarForm' => $derivarForm->createView(),
            )
        );
    }

    public function editarAction(Request $request, $id)
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
            $derivarData = $derivarForm->getData(); //originales:ArrayCollection, notas_original:String, copias:ArrayCollection, notas_copias:String
            $remitenteData = $remitenteForm->getData();
            $personaData = $personaForm->getData();

            if ($remitenteData['id_persona']) {
                $persona = $this->getDoctrine()
                         ->getRepository('BandejaBundle:Personas')
                         ->find( $remitenteData['id_persona'] );
            } elseif ($remitenteData['id_depto']) {
                $departamento = $this->getDoctrine()
                              ->getRepository('BandejaBundle:Departamentos')
                              ->find( $remitenteData['id_depto'] );

                $expr = new Comparison('encargado', '=', 1);

                $criteria = new Criteria();
                $criteria->where( $expr );

                $encargados = $departamento->getDepUsus()->matching( $criteria );

                $persona = $encargados->first()->getFkUsuario()->getFkPersona();

            } else {
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

                $em->persist($persona);
                $em->flush();
            }

            $loginUser = $this->getUser();

            $documento = new Documentos();
            $documento->setFkTipoDoc( $nuevoData['fkTipoDoc'] );
            $documento->setNroExpediente( $nuevoData['nroExpediente'] );
            $documento->setFechaDoc( $nuevoData['fechaDoc'] );
            $documento->setAnt( $nuevoData['ant'] );
            $documento->setMat( $nuevoData['mat'] );
            $documento->setExt( $nuevoData['ext'] );
            $documento->setEstado(1); // 1=NORMAL,2=ARCHIVADO
            $documento->setFkRutPersona( $persona );
            $documento->setFkUsuario( $loginUser );
            $documento->setFechaC( new \DateTime() );
            $documento->setFechaM( new \DateTime() );

            $expr = new Comparison('encargado', '=', 1);
            $criteria = new Criteria();
            $criteria->where( $expr );

            $em->persist($documento);
            $em->flush();

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
                    $em->flush();
                }
            }

            if ($request->get('guardar') === 'derivar') {
                foreach ($derivarData['originales'] as $depto) {
                    $derivacion = new Derivaciones();
                    $derivacion->setTipo(1);
                    $derivacion->setNota($derivarData['nota_original']);
                    $derivacion->setFkDoc($documento);
                    $derivacion->setFechaC(new \DateTime());
                    $derivacion->setFechaM(new \DateTime());

                    $derivacion->setFkRemitente($loginUser);
                    $derivacion->setFkDeptorem($loginUser->getDepUsus()->matching($criteria)->get(0)->getFkDepto());

                    $derivacion->setFkDestinatario($depto->getDepUsus()->matching($criteria)->get(0)->getFkUsuario());
                    $derivacion->setFkDeptodes($depto);

                    $em->persist($derivacion);
                    $em->flush();
                }

                foreach ($derivarData['copias'] as $depto) {
                    $derivacion = new Derivaciones();
                    $derivacion->setTipo(2);
                    $derivacion->setNota($derivarData['nota_copias']);
                    $derivacion->setFkDoc($documento);
                    $derivacion->setFechaC(new \DateTime());
                    $derivacion->setFechaM(new \DateTime());

                    $derivacion->setFkRemitente($loginUser);
                    $derivacion->setFkDeptorem($loginUser->getDepUsus()->matching($criteria)->get(0)->getFkDepto());

                    $derivacion->setFkDestinatario($depto->getDepUsus()->matching($criteria)->get(0)->getFkUsuario());
                    $derivacion->setFkDeptodes($depto);

                    $em->persist($derivacion);
                    $em->flush();
                }

                $this->addFlash('success', 'Documento derivado correctamente');
                return $this->redirectToRoute('recibidos_bandeja');

            } elseif ($request->get('guardar') === 'guardar') {
                foreach ($derivarData['originales'] as $depto) {
                    $derivacion = new Derivaciones();
                    $derivacion->setTipo(1);
                    $derivacion->setNota($derivarData['nota_original']);
                    $derivacion->setFkDoc($documento);
                    $derivacion->setFechaC(new \DateTime());
                    $derivacion->setFechaM(new \DateTime());

                    $derivacion->setFkRemitente($loginUser);
                    $derivacion->setFkDeptorem($loginUser->getDepUsus()->matching($criteria)->get(0)->getFkDepto());

                    $derivacion->setFkDestinatario($loginUser->getDepUsus()->matching($criteria)->get(0)->getFkUsuario());
                    $derivacion->setFkDeptodes($loginUser->getDepUsus()->matching($criteria)->get(0)->getFkDepto());

                    $em->persist($derivacion);
                    $em->flush();
                }

                $this->addFlash('success', 'Documento guardado correctamente');
                return $this->redirectToRoute('porrecibir_bandeja');
            }
        }

        return $this->render(
            'BandejaBundle:Bandeja:editar.html.twig',
            array(
                'id' => $id,
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
