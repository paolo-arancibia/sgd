<?php
namespace AccessBundle\Form;


use AccessBundle\Entity\App;
use AccessBundle\Entity\Perfiles;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PermisosType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'class' => 'AccessBundle\Entity\Perfiles',
                'query_builder' => function(EntityRepository $repo) {
                    return $repo->createQueryBuilder('per')
                        ->orderBy('per.nombre', 'ASC');
                }, 'choice_value' => function (Perfiles $per = null) {
                    return $per ? $per->getIdPerfil() : '';
                }, 'label' => 'Perfiles',
                'choice_label' => 'nombre'
            ])->add('systems', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'class' => 'AccessBundle\Entity\App',
                'query_builder' => function(EntityRepository $repo) {
                    return $repo->createQueryBuilder('app')
                        ->orderBy('app.nombre', 'ASC');
                }, 'choice_value' => function (App $app = null) {
                    return $app ? $app->getIdApp() : '';
                }, 'label' => 'Sistemas',
                'choice_label' => 'nombre'
            ]);
    }
}
