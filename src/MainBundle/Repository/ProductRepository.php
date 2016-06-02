<?php

namespace MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findSixOrderDesc()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM MainBundle:Product p ORDER BY p.id DESC'
            )
            ->setMaxResults(6)
            ->getResult();
    }
}
