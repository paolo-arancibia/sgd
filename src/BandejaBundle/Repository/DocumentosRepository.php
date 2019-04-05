<?php

namespace BandejaBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DocumentosRepository extends EntityRepository
{
    public function findAllByDepto($depto, $estado, $offset, $limit)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
               ->select('MAX(der.idDerivacion) as idDerivacion')
               ->addSelect('IDENTITY(der.fkDoc) as idDoc')
               ->from('BandejaBundle:Derivaciones', 'der')
               ->groupBy('der.fkDoc')
               ->getQuery();

        $derivaciones = $query->getResult();
        $arr = array();

        foreach ($derivaciones as $der) {
            $arr[] = $der['idDerivacion'];
        }

        $query = $this->getEntityManager()->createQueryBuilder();

        $query = $query->select('docs')
               ->addSelect('der')
               ->from('BandejaBundle:Documentos', 'docs')
               ->join('BandejaBundle:Derivaciones', 'der', 'with',
                      $query->expr()->andX(
                          $query->expr()->eq('docs.idDoc', 'der.fkDoc'),
                          $query->expr()->in('der.idDerivacion', $arr)
                      ))
               ->where('der.fkDeptodes = :DEPTO')
               ->andWhere('docs.estado = :ESTADO')
               ->setParameter('DEPTO', $depto)
               ->setParameter('ESTADO', $estado)
               ->setFirstResult( $offset ) // $offset
               ->setMaxResults( $limit ) // $limit
               ->getQuery();

        return $query->getResult();
    }
}
