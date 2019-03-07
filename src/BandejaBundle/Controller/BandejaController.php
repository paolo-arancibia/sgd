<?php
namespace BandejaBundle\Controller;


use BandejaBundle\Entity\Departamentos;
use BandejaBundle\Entity\DepUsu;
use BandejaBundle\Entity\Documentos;
use BandejaBundle\Entity\TiposDocumentos;
use BandejaBundle\Form\BuscarType;
use BandejaBundle\Form\DerivarType;
use BandejaBundle\Form\NuevoDocumentoType;
use BandejaBundle\Form\PersonaType;
use BandejaBundle\Form\RemitenteType;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Criteria;
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
        $searchForm = $this->createForm(BuscarType::class);

        $derivarForm = $this->createForm(DerivarType::class);

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
        $derivarForm = $this->createForm(DerivarType::class);

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

        $derivarForm = $this->createForm(DerivarType::class);
        $nuevoForm = $this->createForm(NuevoDocumentoType::class);
        $personaForm = $this->createForm(PersonaType::class);
        $remitenteForm = $this->createForm(RemitenteType::class);

        $derivarForm->handleRequest($request);
        $nuevoForm->handleRequest($request);
        $personaForm->handleRequest($request);
        $remitenteForm->handleRequest($request);

        if( $nuevoForm->isSubmitted() || $derivarForm->isSubmitted() )
        {
            $docNuevoData = $nuevoForm->getData();
            $derivarData = $derivarForm->getData();

            //$em = $this->getDoctrine()->getManager();
            //$em->persist($docNuevo);
            //$em->flush();
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
