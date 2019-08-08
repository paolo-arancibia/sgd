<?php
namespace BandejaBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemitenteType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id_depto', 'Symfony\Component\Form\Extension\Core\Type\HiddenType')
            ->add('id_persona', 'Symfony\Component\Form\Extension\Core\Type\HiddenType');
    }
}
