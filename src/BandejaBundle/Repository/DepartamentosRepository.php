<?php

namespace BandejaBundle\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityRepository;

class DepartamentosRepository extends EntityRepository
{
    public function findEncargado($id_depto)
    {
        $departamento = $this->find($id_depto);

        $expr = new Comparison('encargado', '=', 1);

        $criteria = new Criteria();
        $criteria->where($expr);

        $encargados = $departamento->getDepUsus()->matching( $criteria );

        return $encargados->first()->getFkUsuario()->getFkPersona();
    }
}
