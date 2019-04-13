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
            ->setMethod('POST')
            ->add('mostrar', ChoiceType::class, [
                'choices' => array(
                    'Pendientes' => 'pendientes',
                    'Archivados' => 'archivados'
                ),
                'choices_as_values' => true,
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
            ])
            ->add('limite', ChoiceType::class, [
                'choices' => [
                    'Vencidos' => 'vencidos',
                    '&le;5 días' => 'menos_5',
                    '>5 días' => 'mas_5',
                ],
                'choices_as_values' => true,
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
            ]);
    }
}
