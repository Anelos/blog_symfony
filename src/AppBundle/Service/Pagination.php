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
    private $nbPage;
    private $page;
    private $entityName;
    private $criteria = array();
    private $order = "DESC";

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        if ($page > $this->getNbPage()) {
            $this->page = $this->getNbPage();
        } elseif
        ($page < 1) {
            $this->page = 1;
        } else {
            $this->page = $page;
        }
    }

    public function getNbPage()
    {
        $this->nbPage = $this->em->getRepository($this->entityName)->getNumberOfPage($this->criteria);

        return $this->nbPage;
    }

    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * @param array $criteria
     */
    public function setCriteria(Array $criteria)
    {
        $this->criteria = $criteria;
    }


    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function getQueryResult(){

        return $this->em->getRepository($this->entityName)->getQuery($this->page, $this->criteria, $this->order);
    }

}