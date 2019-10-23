<?php
namespace AccessBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioNuevoType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('password', 'Symfony\Component\Form\Extension\Core\Type\PasswordType')
            ->add('rut', 'Symfony\Component\Form\Extension\Core\Type\HiddenType');
    }
}
