<?php
namespace AccessBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UsuarioType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'required' => true
        ])->add('contrasena', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', [
            'required' => true
        ])->add('validar_contrasena', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', [
            'required' => true
        ]);
    }
}
