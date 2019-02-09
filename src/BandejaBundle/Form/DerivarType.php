<?php
namespace BandejaBundle\Form;


use BandejaBundle\Entity\Departamentos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class DerivarType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        
        $repository = $this->getDoctrine()->getRepository('BandejaBundle:Departamentos');

        $query =  $repository->createQueryBuilder('depto')
               ->orderBy('depto.descripcion', 'ASC')
               ->getQuery();

        $deptos = $query->getResults();

        $builder
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // TODO: crear clase para BUSQUEDA
        $resolver->setDefaults(array(
            'data_class' => Departamentos::class,
        ));
    }
}
