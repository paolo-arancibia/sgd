<?php
namespace BandejaBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltersType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('post')
            ->add('mostrar', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'choices' => array(
                    'Pendientes' => 'pendientes',
                    'Archivados' => 'archivados'
                ),
                'choices_as_values' => true,
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('limite', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'choices' => [
                    '<i class="fas fa-circle text-danger">&nbsp;</i>Vencidos' => 'vencidos',
                    '<i class="fas fa-circle text-warning">&nbsp;</i>&le; 5 días' => 'menos_5',
                    '<i class="fas fa-circle text-success">&nbsp;</i>&gt; 5 días' => 'mas_5',
                ],
                'choices_as_values' => true,
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
            ]);
    }
}
