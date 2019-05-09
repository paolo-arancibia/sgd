<?php

namespace BandejaBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DocumentosRepository extends EntityRepository
{
    public function findRecibidosByDepto($depto, $mostrar = null, $limite = null, $offset = 0, $limit = 0)
    {
        $derivaciones = $this->lastDerivaciones($depto);

        $query = $this->getEntityManager()->createQueryBuilder();

        $query = $query->select('docs')
               ->addSelect('der')
               ->from('BandejaBundle:Documentos', 'docs')
               ->join('BandejaBundle:Derivaciones', 'der', 'with',
                      $query->expr()->andX(
                          $query->expr()->eq('docs.idDoc', 'der.fkDoc'),
                          $query->expr()->in('der.idDerivacion', array_column($derivaciones, 'idDer')),
                          $query->expr()->isNull('der.fechaE')
                      ))
               ->where('1 = 1');

        if (isset($mostrar))
            $query->andWhere($query->expr()->eq('docs.estado', $mostrar == 'archivados' ? 0 : 1));
        else
            $query->andWhere('docs.estado IN (0,1)');

        if (isset($limite)) {
            switch ($limite) {
                case 'vencidos':
                    $query->andWhere($query->expr()->lt('docs.fechaDoc', date('\'Y-m-d\'')));
                    break;
                case 'menos_5':
                    $query->andWhere($query->expr()->gte('docs.fechaDoc', date('\'Y-m-d\'')));
                    $query->andWhere($query->expr()->lt(
                        'docs.fechaDoc',
                        date('\'Y-m-d\'', time() + (5 * 24 * 60 * 60)
                        ))) ; // 5 dias
                    break;
                case 'mas_5':
                    $query->andWhere($query->expr()->gt(
                        'docs.fechaDoc',
                        date('\'Y-m-d\'', time() + (5 * 24 * 60 * 60)
                        ))) ; // 5 dias
                    break;
            }
        }

        if ($offset)
            $query->setFirstResult($offset); // $offset

        if ($limit)
            $query->setMaxResults($limit); // $limit

        $query = $query->getQuery();
        //dump($query->getDql()); die;
        return $query->getResult();
    }

    public function countRecibidosByDepto($depto, $mostrar = null, $limite = null)
    {
        $docs = $this->findRecibidosByDepto($depto, $mostrar, $limite);
        return (int) count($docs) / 2;
    }

    public function findPorrecibirByDepto($depto, $offset = 0, $limit = 0)
    {
        $derivaciones = $this->lastDerivaciones($depto);

        $query = $this->getEntityManager()->createQueryBuilder();

        $query = $query->select('docs')
               ->addSelect('der')
               ->from('BandejaBundle:Documentos', 'docs')
               ->join('BandejaBundle:Derivaciones', 'der', 'with',
                      $query->expr()->andX(
                          $query->expr()->eq('docs.idDoc', 'der.fkDoc'),
                          $query->expr()->in('der.idDerivacion', array_column($derivaciones, 'idDer')),
                          $query->expr()->isNull('der.fechaE')
                      ))
               ->where('docs.estado = 2');

        if ($offset)
            $query->setFirstResult($offset); // $offset

        if ($limit)
            $query->setMaxResults($limit); // $limit

        $query = $query->getQuery();

        return $query->getResult();

        /*$query = $this->getEntityManager()->createQueryBuilder();

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

            return $query->getResult();*/
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

    private function lastDerivaciones($depto)
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $derivaciones = $query->select('MAX(der.idDerivacion) as idDer')
                      ->addSelect('IDENTITY(der.fkDoc) as fkDoc')
                      ->addSelect('IDENTITY(der.fkDeptodes) as fkDeptodes')
                      ->from('BandejaBundle:Derivaciones', 'der')
                      ->groupBy('der.fkDoc')
                      ->orderBy('der.fkDoc', 'DESC')
                      ->setMaxResults(9999)
                      ->getQuery()
                      ->getResult();

        $derivaciones = array_filter($derivaciones, function($var) use ($depto) {
            return $var['fkDeptodes'] == $depto->getIdDepartamento();
        });

        return $derivaciones;
    }
}
