<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class ArticleRepository
 * @package AppBundle\Repository
 */
class ArticleRepository extends EntityRepository
{
    const ARTICLESPERPAGES = 2;


    public function getQuery($page, $criteria = array(), $order = "DESC")
    {
        return $this
            ->findBy(
                $criteria,
                array("created" => $order),
                self::ARTICLESPERPAGES,
                self::ARTICLESPERPAGES * ($page - 1));
    }

    public function getNumberOfPage($criteria = array())
    {
        return ceil(count($this->findBy($criteria)) / self::ARTICLESPERPAGES);
    }


}