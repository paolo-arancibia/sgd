<?php
namespace AccessBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', 'Symfony\Component\Form\Extension\Core\Type\PasswordType')
            ->add('newPassword', 'Symfony\Component\Form\Extension\Core\Type\RepeatedType', [
                'type' => 'password',
                'invalid_message' => 'Las contraseñas no coinciden',
                'required' => true,
                //'first_options' => array('label' => 'Contraseña'),
                //'second_options' => array('label' => 'Repita Contraseña')
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccessBundle\Form\Model\ChangePassword',
        ));
    }

    public function getName()
    {
        return 'change_passwd';
    }
}
