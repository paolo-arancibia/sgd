<?php
namespace BandejaBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ])->add('fecha_nacimiento', DateType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'widget' => 'single_text',
                'label' => 'Fecha Nacimiento',
            ])->add('nombre_calle', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label col'],
                'label' => 'Calle donde vive',
            ])->add('numdirec', NumberType::class, [
                'attr' => ['class' => 'form-control text-right'],
                'label_attr' => ['class' => 'form-label sr-only'],
                'label' => 'Número de la propiedad donde vive',
            ])->add('referenciadir', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Referencia a la dirección',
            ])->add('nombre_comuna', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Comuna donde vive',
            ])->add('fono', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Teléfono de contacto',
            ])->add('fono_2', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Otro teléfono de contacto',
            ])->add('email', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Email',
            ])->add('sexo', ChoiceType::class, [
                'choices' => ['Masculino' => 'masculino', 'Femenino' => 'femenino'],
                'choices_as_values' => true,
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Sexo',
            ])
        ;
    }
}
