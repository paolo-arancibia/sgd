<?php
namespace BandejaBundle\Form;


use BandejaBundle\Entity\TiposDocumentos;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NuevoDocumentoType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fkTipoDoc', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'class' => 'BandejaBundle:TiposDocumentos',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('tdoc')
                        ->orderBy('tdoc.descripcion', 'ASC');
                },
                'choice_label' => 'descripcion',
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'chosen-select'],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Tipo Documento',
                'choice_value' => function (TiposDocumentos $td = null) {
                    return $td ? $td->getIdTiposDoc() : '';
                },
            ])->add('nroDoc', 'Symfony\Component\Form\Extension\Core\Type\NumberType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Número Documento',
                'required' => false,
            ])->add('nroExpediente', 'Symfony\Component\Form\Extension\Core\Type\NumberType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Número Expediente',
                'required' => false,
            ])->add('fechaDoc', 'Symfony\Component\Form\Extension\Core\Type\DateType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Fecha Recepción',
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'required' => true,
            ])->add('ant', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
                'attr' => ['class' => 'form-control', 'rows' => 4],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Antecedentes',
                'required' => false,
            ])->add('mat', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
                'attr' => ['class' => 'form-control', 'rows' => 4],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Materia',
                'required' => false,
            ])->add('ext', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
                'attr' => ['class' => 'form-control', 'rows' => 4],
                'label_attr' => ['class' => 'mb-0'],
                'label' => 'Extracto',
                'required' => false,
            ])
        ;
    }
}
