<?php
namespace BandejaBundle\Controller;


use BandejaBundle\Entity\Departamentos;
use BandejaBundle\Entity\Documentos;
use BandejaBundle\Entity\TiposDocumentos;
// use BandejaBundle\Form\DerivarType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
// use Symfony\Component\HttpFoundation\Response;
    
class BandejaController extends Controller
{
    public function indexAction()
    {
        return $this->forward('BandejaBundle:Bandeja:recibidos');
    }

    public function recibidosAction($page = 0)
    {
        $searchForm = $this->createFormBuilder(null, array(
            'action' => '',
            'method' => 'GET'))
                    ->add('searchText', SearchType::class, [
                        'required' => false,
                        'attr' => ['placeholder' => 'Buscar...'],
                    ])
                    ->getForm();

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
        return $this->render(
            'BandejaBundle:Bandeja:porrecibir.html.twig',
            array(
                'page' => $page,
                'menu_op' => 'porrecibir'
            )
        );
    }

    public function despachadosAction($page = 0)
    {
        return $this->render(
            'BandejaBundle:Bandeja:despachados.html.twig',
            array(
                'page' => $page,
                'menu_op' => 'despachados'
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

    public function editarAction($id)
    {
        $tipos = $this->getTiposDocs();
        $derivarForm = $this->getDerivarForm();
        $nuevoForm = $this->getNuevoForm();

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

    private function getDeptos()
    {
        $repository = $this->getDoctrine()->getRepository('BandejaBundle:Departamentos');

        $query =  $repository->createQueryBuilder('depto')
               ->orderBy('depto.descripcion', 'ASC')
               ->getQuery();

        return $query->getResult();
    }


    private function getTiposDocs()
    {
        $repository = $this->getDoctrine()->getRepository('BandejaBundle:TiposDocumentos');

        $query =  $repository->createQueryBuilder('tipodoc')
               ->orderBy('tipodoc.abrev', 'ASC')
               ->getQuery();

        return $query->getResult();
    }

    private function getDerivarForm()
    {
        $deptos = $this->getDeptos();

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
                'required' => true,
            ])->add('ant', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'rows' => 4],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Antecedentes',
                'required' => true,
            ])->add('mat', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'rows' => 4],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Materia',
                'required' => true,
            ])->add('ext', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'rows' => 4],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Extracto',
                'required' => true,
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
