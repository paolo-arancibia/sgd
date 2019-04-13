<?php

namespace BandejaBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DocumentosRepository extends EntityRepository
{
    public function findRecibidosByDepto($depto, $offset = 0, $limit = 0)
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query = $query->select('docs')
               ->addSelect('der')
               ->from('BandejaBundle:Documentos', 'docs')
               ->join('BandejaBundle:Derivaciones', 'der', 'with',
                      $query->expr()->andX(
                          $query->expr()->eq('docs.idDoc', 'der.fkDoc'),
                          $query->expr()->isNull('der.fechaE')
                      ))
               ->where('der.fkDeptodes = :DEPTO')
               ->andWhere('docs.estado IN (0,1)')
               ->setParameter('DEPTO', $depto)
               ->getQuery();

        if ($offset)
            $query->setFirstResult($offset); // $offset

        if ($limit)
            $query->setMaxResults($limit); // $limit

        return $query->getResult();
    }

    public function countRecibidosByDepto($depto)
    {
        $docs = $this->findRecibidosByDepto($depto);
        return (int) count($docs) / 2;
    }

    public function findPorrecibirByDepto($depto, $offset = 0, $limit = 0)
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query = $query->select('docs')
               ->addSelect('der')
               ->from('BandejaBundle:Documentos', 'docs')
               ->join('BandejaBundle:Derivaciones', 'der', 'with',
                      $query->expr()->andX(
                          $query->expr()->eq('docs.idDoc', 'der.fkDoc'),
                          $query->expr()->isNull('der.fechaE')
                      ))
               ->where('der.fkDeptodes = :DEPTO')
               ->andWhere('docs.estado = 2')
               ->setParameter('DEPTO', $depto)
               ->getQuery();

        if ($offset)
            $query->setFirstResult($offset); // $offset

        if ($limit)
            $query->setMaxResults($limit); // $limit

        return $query->getResult();
    }

    public function countPorrecibirByDepto($depto)
    {
        $docs = $this->findPorrecibirByDepto($depto);
        return (int) count($docs) / 2;
    }

    public function findDespachadosByUsuario($usuario, $offset = 0, $limit = 0)
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query = $query->select('docs')
               ->addSelect('der')
               ->from('BandejaBundle:Documentos', 'docs')
               ->join('BandejaBundle:Derivaciones', 'der', 'with',
                      $query->expr()->andX(
                          $query->expr()->eq('docs.idDoc', 'der.fkDoc'),
                          $query->expr()->isNull('der.fechaE')
                      ))
               ->where('der.fkRemitente = :USUARIO')
               ->andWhere('docs.estado in (1,0)')
               ->setParameter('USUARIO', $usuario)
               ->getQuery();

        if ($offset)
            $query->setFirstResult($offset);

        if ($limit)
            $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function countDespachadosByUsuario($usuario)
    {
        $docs = $this->findDespachadosByUsuario($usuario);
        return (int) count($docs) / 2;
    }
}
