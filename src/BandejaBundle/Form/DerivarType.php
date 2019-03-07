<?php
namespace BandejaBundle\Form;


use BandejaBundle\Entity\Departamentos;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DerivarType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('originales', EntityType::class, [
                'class' => 'BandejaBundle:Departamentos',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('depto')
                        ->orderBy('depto.descripcion', 'ASC');
                },
                'choice_label' => 'descripcion',
                'expanded' => false,
                'multiple' => true,
                'label_attr' => ['class' => 'form-label mt-1'],
                'attr' => ['class' => 'chosen-select'],
                'label' => 'Enviar Originales a',
                'choice_value' => function (Departamentos $depto = null) {
                    return $depto ? $depto->getIdDepartamento() : '';
                }
            ])->add('nota-original', TextareaType::class, [
                'label' => 'Nota para originales',
                'label_attr' => ['class' => 'form-label mt-2'],
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('copias', EntityType::class, [
                'class' => 'BandejaBundle:Departamentos',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('depto')
                        ->orderBy('depto.descripcion', 'ASC');
                },
                'choice_label' => 'descripcion',
                'expanded' => false,
                'multiple' => true,
                'label_attr' => ['class' => 'form-label mt-1'],
                'attr' => ['class' => 'chosen-select'],
                'label' => 'Enviar Copias a',
                'choice_value' => function (Departamentos $depto = null) {
                    return $depto ? $depto->getIdDepartamento() : '';
                },
                'required' => false,
            ])->add('nota-copias', TextareaType::class, [
                'label' => 'Nota para copias',
                'label_attr' => ['class' => 'form-label mt-2'],
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])->add('adjuntos', FileType::class, [
                'required' => false,
                'multiple' => true,
                'attr' => ['class' => 'adjuntos'],
                'label_attr' => ['class' => 'form-label mt-2'],
                'label' => 'Adjuntos',
            ])

        ;
    }
}
