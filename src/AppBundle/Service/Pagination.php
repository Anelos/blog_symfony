<?php
/**
 * Created by PhpStorm.
 * User: anelos
 * Date: 31/10/17
 * Time: 16:01
 */

namespace AppBundle\Service;


use Doctrine\ORM\EntityManager;

class Pagination
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getPage($entityName, $page, $all = false)
    {

        $nbPage = $this->em->getRepository($entityName)->getNumberOfPage();
        if ($all) {
            $nbPage += $this->em->getRepository($entityName)->getNumberOfPage(false);
        }
        if ($page > $nbPage) {
            return $nbPage;
        } elseif
        ($page < 1) {
            return 1;
        }
        return $page;
    }
}