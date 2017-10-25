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

    public function getAllArticles($page, $order = "DESC")
    {
        return $this
            ->findBy(
                array(),
                array("created" => $order),
                self::ARTICLESPERPAGES,
                self::ARTICLESPERPAGES * ($page - 1));

    }

    public function getPublishedArticles($page, $published = true, $order = "DESC")
    {
        return $this
            ->findBy(
                array("published" => $published),
                array("created" => $order),
                self::ARTICLESPERPAGES,
                self::ARTICLESPERPAGES * ($page - 1));
    }

    public function getNumberOfPageForPublished($published = true)
    {
        return ceil(count($this->findBy(array("published" => $published))) / self::ARTICLESPERPAGES);
    }

}