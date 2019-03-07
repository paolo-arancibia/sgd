<?php
namespace BandejaBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonaType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rut', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Rut',
            ])->add('nombres', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Nombres',
            ])->add('apellidopaterno', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Apellido Paterno',
            ])->add('apellidomaterno', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Apellido Materno',
            ])->add('direccion', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Dirección Particular',
            ])->add('fono', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Teléfono de contacto',
            ])->add('email', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Email',
            ])
        ;
    }
}
