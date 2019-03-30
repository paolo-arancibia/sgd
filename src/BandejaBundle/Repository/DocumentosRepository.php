<?php

namespace BandejaBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DocumentosRepository extends EntityRepository
{

    public function findAllRecibidosByDepto($depto)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
               ->select('MAX(der.idDerivacion) as idDerivacion')
               ->addSelect('IDENTITY(der.fkDoc) as idDoc')
               ->from('BandejaBundle:Derivaciones', 'der')
               ->groupBy('der.fkDoc')
               //->setFirstResult( 0 ) // $offset
               //->setMaxResults( 25 ) // $limit
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
               ->setParameter('DEPTO', $depto)
               ->setFirstResult( 0 ) // $offset
               ->setMaxResults( 25 ) // $limit
               ->getQuery();

        return $query->getResult();
    }
}
