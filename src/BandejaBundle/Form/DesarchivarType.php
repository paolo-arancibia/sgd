<?php
namespace BandejaBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DesarchivarType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('post')
            ->add('razon', TextareaType::class, [
                'label' => 'Razón para desarchivar',
                'label_attr' => ['class' => 'form-label mt-2'],
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ]);
    }
}
