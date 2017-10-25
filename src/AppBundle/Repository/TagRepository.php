<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Tag;
use Doctrine\ORM\EntityRepository;

/**
 * Class TagRepository
 * @package AppBundle\Repository
 */
class TagRepository extends EntityRepository
{

    /**
     * @param string $tagSlug
     * @return object
     */
    public function findOneTagBySlug($tagSlug)
    {
        return $this->findOneBy(array("slug" => $tagSlug));
    }

    /**
     * @param Tag $tag
     * @return object
     */
    public function createNewTag(Tag $tag){
        $this->getEntityManager()->persist($tag);
        $this->getEntityManager()->flush();
        return $tag;
    }

}