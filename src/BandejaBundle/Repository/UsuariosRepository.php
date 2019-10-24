<?php

namespace BandejaBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UsuariosRepository extends EntityRepository
{
    public function findAll($limit = 0, $offset = 0)
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query = $query->select('usu')
               ->from('BandejaBundle:Usuarios', 'usu')
               ->where( $query->expr()->isNull('usu.fechaE'));

        if ($offset)
            $query->setFirstResult($offset);

        if ($limit)
            $query->setMaxResults($limit);

        $query = $query->getQuery();

        return $query->getResult();
    }
}
