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
               ->where('1 = 1')
               ->orderBy('docs.fechaDoc', 'DESC');

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
            $query->setFirstResult($offset);

        if ($limit)
            $query->setMaxResults($limit);

        $query = $query->getQuery();

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
               ->where('docs.estado = 2')
               ->orderBy('docs.fechaDoc', 'DESC');

        if ($offset)
            $query->setFirstResult($offset); // $offset

        if ($limit)
            $query->setMaxResults($limit); // $limit

        $query = $query->getQuery();

        return $query->getResult();
    }

    public function countPorrecibirByDepto($depto)
    {
        $docs = $this->findPorrecibirByDepto($depto);
        return (int) count($docs) / 2;
    }

    public function findDespachadosByUsuario($usuario, $depto, $offset = 0, $limit = 0)
    {
        $derivaciones = $this->sentDerivaciones($depto);

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
               ->where('der.fkRemitente = :USUARIO')
               ->andWhere('der.fkDeptorem = :DEPTO')
               ->andWhere('docs.estado = 2')
               ->orderBy('docs.fechaDoc', 'DESC')
               ->setParameter('USUARIO', $usuario)
               ->setParameter('DEPTO', $depto)
               ->getQuery();

        if ($offset)
            $query->setFirstResult($offset);

        if ($limit)
            $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function countDespachadosByUsuario($usuario, $depto)
    {
        $docs = $this->findDespachadosByUsuario($usuario, $depto);
        return (int) count($docs) / 2;
    }

    private function lastDerivaciones($depto)
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $derivaciones = $query->select('MAX(der.idDerivacion) as idDer')
                      ->addSelect('IDENTITY(der.fkDoc) as fkDoc')
                      ->addSelect('IDENTITY(der.fkDeptodes) as fkDeptodes')
                      ->addSelect('IDENTITY(der.fkDeptorem) as fkDeptorem')
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

    private function sentDerivaciones($depto)
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $derivaciones = $query->select('MAX(der.idDerivacion) as idDer')
                      ->addSelect('IDENTITY(der.fkDoc) as fkDoc')
                      ->addSelect('IDENTITY(der.fkDeptodes) as fkDeptodes')
                      ->addSelect('IDENTITY(der.fkDeptorem) as fkDeptorem')
                      ->from('BandejaBundle:Derivaciones', 'der')
                      ->groupBy('der.fkDoc')
                      ->orderBy('der.fkDoc', 'DESC')
                      ->setMaxResults(9999)
                      ->getQuery()
                      ->getResult();

        $derivaciones = array_filter($derivaciones, function($var) use ($depto) {
            return $var['fkDeptorem'] == $depto->getIdDepartamento();
        });

        return $derivaciones;
    }

    public function findBuscarByQuery($usuario, $needle, $offset = 0, $limit = 0)
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $depUsus = $usuario->getDepUsus();

        if ($depUsus->count()) {
            foreach($depUsus as $d)
                $deptos[] = $d->getFkDepto()->getIdDepartamento();
        }

        $query = $query->select('docs')
               ->addSelect('der')
               ->from('BandejaBundle:Documentos', 'docs')
               ->join('BandejaBundle:Derivaciones', 'der', 'with',
                      $query->expr()->andX(
                          $query->expr()->eq('docs.idDoc', 'der.fkDoc'),
                          $query->expr()->in('der.fkDeptodes', $deptos),
                          $query->expr()->isNull('der.fechaE')
                      ))
               ->orderBy('docs.fechaDoc', 'DESC');

        if (is_numeric($needle)) {
            $query->where('docs.idDoc = :NEEDLE')
                ->orWhere('docs.nroExpediente = :NEEDLE')
                ->setParameter('NEEDLE', $needle);

        } else if (preg_match('/DESDE[\s]*:[\s]*([0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4})[\s]+HASTA[\s]*:[\s]*([0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4})/i',
                              $needle,
                              $matches)) {
            $desde = date_create_from_format('d/m/Y', $matches[1]);
            $hasta = date_create_from_format('d/m/Y', $matches[2]);

            $query->where(
                $query->expr()->andX(
                    $query->expr()->gte('docs.fechaDoc', ':DESDE'),
                    $query->expr()->lte('docs.fechaDoc', ':HASTA')
                ))
                ->setParameters(['DESDE' => $desde, 'HASTA' => $hasta]);

        } else if (preg_match('/([0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4})/i', $needle, $matches)) {
            $fecha = date_create_from_format('d/m/Y', $matches[1]);

            $query->where($query->expr()->andX($query->expr()->eq('docs.fechaDoc', ':YEAR')))
                ->setParameter('YEAR', $fecha->format('Y-m-d'));

        } else {
            $query->where(
                $query->expr()->orX(
                    $query->expr()->like('docs.ant', ':NEEDLE'),
                    $query->expr()->like('docs.mat', ':NEEDLE'),
                    $query->expr()->like('docs.ext', ':NEEDLE'),
                    $query->expr()->like('der.nota', ':NEEDLE')
                ))
                ->setParameter('NEEDLE', '%' . $needle . '%');
        }

        $query = $query->getQuery();

        if ($offset)
            $query->setFirstResult($offset);

        if ($limit)
            $query->setMaxResults($limit);

        //dump($deptos, , $query->getDql());die;

        return $query->getResult();
    }

    public function countBuscarByQuery($usuario, $needle)
    {
        $docs = $this->findBuscarByQuery($usuario, $needle);
        return (int) count($docs) / 2;
    }
}
