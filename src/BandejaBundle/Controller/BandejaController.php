<?php
namespace BandejaBundle\Controller;


use BandejaBundle\Entity\Departamentos;
use BandejaBundle\Entity\DepUsu;
use BandejaBundle\Entity\Documentos;
use BandejaBundle\Entity\TiposDocumentos;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Criteria;
// formulario especial para derivar - por implementar
// use BandejaBundle\Form\DerivarType;
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
        return $this->forward('BandejaBundle:Bandeja:recibidos');
    }

    public function recibidosAction($page = 0)
    {
        $searchForm = $this->getSearchForm();

        $derivarForm = $this->getDerivarForm();

        return $this->render(
            'BandejaBundle:Bandeja:index.html.twig',
            array(
                'page' => $page,
                'menu_op' => 'bandeja',
                'searchForm' => $searchForm->createView(),
                'derivarForm' => $derivarForm->createView(),
            )
        );

    }

    public function porrecibirAction($page = 0)
    {
        $searchForm = $this->getSearchForm();

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
        $searchForm = $this->getSearchForm();

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
        $derivarForm = $this->getDerivarForm();
        //$derivarForm = $this->createForm(DerivarType::class);

        return $this->render(
            'BandejaBundle:Bandeja:ver.html.twig',
            array(
                'id' => $id,
                'derivarForm' => $derivarForm->createView()
            )
        );
    }

    public function editarAction(Request $request, $id)
    {
        $tipos = $this->getTiposDocs();
        $derivarForm = $this->getDerivarForm();
        $nuevoForm = $this->getNuevoForm();

        $derivarForm->handleRequest($request);
        $nuevoForm->handleRequest($request);

        if( $nuevoForm->isSubmitted() || $derivarForm->isSubmitted() )
        {
            $docNuevo = $nuevoForm->getData();
            $derivarA = $derivarForm->getData();

            print_r($docNuevo); exit();
            $em = $this->getDoctrine()->getManager();
            $em->persist($docNuevo);
            //$em->flush();
        }

        return $this->render(
            'BandejaBundle:Bandeja:editar.html.twig',
            array(
                'id' => $id,
                'tipos' => $tipos,
                'derivarForm' => $derivarForm->createView(),
                'nuevoForm' => $nuevoForm->createView()
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

    private function getSearchForm()
    {
        return $this->createFormBuilder(null, [
            'action' => '',
            'method' => 'GET'])
            ->add('searchText', SearchType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Buscar...'],
            ])
            ->getForm();
    }

    private function getDerivarForm()
    {
        $deptos = $this->getDeptos();
        $depUsu = new DepUsu();

        return $this->createFormBuilder()
            ->add('originales', ChoiceType::class, [
                'choices'=> $deptos,
                'choices_as_values' => true,
                'expanded' => false,
                'multiple' => true,
                'label_attr' => ['class' => 'form-label mt-1'],
                'attr' => ['class' => 'chosen-select'],
                'label' => 'Enviar Originales a',
                'choice_value' => function (Departamentos $depto = null) {
                    return $depto ? $depto->getIdDepartamento() : '';
                },
                'choice_label' => function (Departamentos $depto = null) {
                    return $depto ? $depto->getDescripcion() : '';
                }
            ])->add('nota-original', TextareaType::class, [
                'label' => 'Nota para originales',
                'label_attr' => ['class' => 'form-label mt-2'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('copias', ChoiceType::class, [
                'choices'=> $deptos,
                'choices_as_values' => true,
                'expanded' => false,
                'multiple' => true,
                         'label_attr' => ['class' => 'form-label mt-1'],
                'attr' => ['class' => 'chosen-select'],
                'label' => 'Enviar Copias a',
                'choice_value' => function (Departamentos $depto = null) {
                    return $depto ? $depto->getIdDepartamento() : '';
                },
                'choice_label' => function (Departamentos $depto = null) {
                    return $depto ? $depto->getDescripcion() : '';
                }
            ])->add('nota-copias', TextareaType::class, [
                'label' => 'Nota para copias',
                'label_attr' => ['class' => 'form-label mt-2'],
                'attr' => ['class' => 'form-control']
            ])->add('adjuntos', FileType::class, [
                'required' => false,
                'multiple' => true,
                'attr' => ['class' => 'adjuntos'],
                'label_attr' => ['class' => 'form-label mt-2'],
                'label' => 'Adjuntos',
            ])
            ->getForm();
    }

    private function getNuevoForm()
    {
        $tiposDocs = $this->getTiposDocs();

        $doc = new Documentos();

        return $this->createFormBuilder($doc)
            //->add('')
            ->add('fkTipoDoc', ChoiceType::class, [
                'choices'=> $tiposDocs,
                'choices_as_values' => true,
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'chosen-select'],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Tipo Documento',
                'choice_value' => function (TiposDocumentos $td = null) {
                    return $td ? $td->getIdTiposDoc() : '';
                },
                'choice_label' => function (TiposDocumentos $td = null) {
                    return $td ? $td->getAbrev() . ' - ' . $td->getDescripcion() : '';
                }
            ])->add('nroExpediente', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Número Expediente',
                'required' => false,
            ])->add('fechaDoc', DateType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Fecha Recepción',
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'required' => true,
            ])->add('ant', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'rows' => 4],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Antecedentes',
                'required' => false,
            ])->add('mat', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'rows' => 4],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Materia',
                'required' => false,
            ])->add('ext', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'rows' => 4],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Extracto',
                'required' => false,
            ])
            ->getForm();
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
