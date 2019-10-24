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
            ->add('rut', 'Symfony\Component\Form\Extension\Core\Type\NumberType', [
                'attr' => ['class' => 'form-control w-auto d-inline'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Rut',
            ])->add('dv', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'attr' => ['class' => 'form-control w-auto d-inline', 'size' => 1],
                'label_attr' => ['class' => 'form-label'],
                'label' => '',
            ])->add('nombres', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Nombres',
            ])->add('apellidopaterno', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Apellido Paterno',
            ])->add('apellidomaterno', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Apellido Materno',
                'required' => false,
            ])->add('fecha_nacimiento', 'Symfony\Component\Form\Extension\Core\Type\DateType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'widget' => 'single_text',
                'label' => 'Fecha Nacimiento',
                'required' => false,
            ])->add('nombre_calle', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Calle donde vive',
            ])->add('numdirec', 'Symfony\Component\Form\Extension\Core\Type\NumberType', [
                'attr' => ['class' => 'form-control text-right'],
                'label_attr' => ['class' => 'form-label sr-only'],
                'label' => 'Número de la propiedad donde vive',
            ])->add('referenciadir', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Referencia a la dirección',
                'required' => false,
            ])->add('nombre_comuna', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Comuna donde vive',
            ])->add('fono', 'Symfony\Component\Form\Extension\Core\Type\NumberType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Teléfono de contacto',
                'required' => false,
            ])->add('fono_2', 'Symfony\Component\Form\Extension\Core\Type\NumberType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Otro teléfono de contacto',
                'required' => false,
            ])->add('email', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Email',
                'required' => false,
            ])->add('sexo', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
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
