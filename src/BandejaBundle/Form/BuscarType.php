<?php
namespace BandejaBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuscarType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('searchText', SearchType::class, [
                'required' => false,
                'attr' => ['placeholdel' => 'Buscar...'],
            ]);

    }
}
