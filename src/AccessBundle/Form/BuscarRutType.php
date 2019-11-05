<?php
namespace AccessBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BuscarRutType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rut', 'Symfony\Component\Form\Extension\Core\Type\NumberType')
            ->add('dv', 'Symfony\Component\Form\Extension\Core\Type\TextType');
    }
}
