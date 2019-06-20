<?php
namespace BandejaBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecibirType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('post')
            ->add('recibir', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'label' => false,
                'attr' => ['class' => 'btn btn-success'],
            ]);
    }
}
