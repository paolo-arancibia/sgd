<?php
namespace AccessBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AppRepository extends EntityRepository
{
    public function findByUser($usuario)
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query = $query->select('app')
               ->from('AccessBundle\Entity\App', 'app')
               ->join('AccessBundle\Entity\Permisos', 'per', 'with',
                      $query->expr()->andX(
                          $query->expr()->eq('app.idApp', 'per.fkApp'),
                          $query->expr()->eq('per.fkUsuario', ':usuario')))
               ->setParameter('usuario', $usuario);

        $query = $query->getQuery();

        return $query->getResult();
    }
}
