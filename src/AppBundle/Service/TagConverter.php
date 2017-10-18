<?php
/**
 * Created by PhpStorm.
 * User: anelos
 * Date: 11/10/17
 * Time: 20:51
 */

namespace AppBundle\Service;


use AppBundle\Entity\Tag;
use AppBundle\Repository\TagRepository;
use Doctrine\ORM\EntityManager;
use Gedmo\Sluggable\Util\Urlizer;


/**
 * Class TagConverter
 * @package AppBundle\Service
 */
class TagConverter
{

    /**
     * @var EntityManager
     */
    private $repository;
    private $em;

    /**
     * TagConverter constructor.
     * @param TagRepository $repository
     * @param EntityManager $em
     */
    public function __construct(TagRepository $repository, EntityManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @param $tagString
     * @return array
     */
    public function tagStringToArray($tagString)
    {
        return array_map('trim',explode(";", $tagString));
    }

    /**
     * @param $tagSlug
     * @return bool|object
     */
    public function isTagExist($tagSlug)
    {
        $tag = $this->repository->findOneTagBySlug($tagSlug);

        if ($tag) {
            return $tag;
        } else {
            return false;
        }
    }

    /**
     * @param $tags
     * @return array
     */
    public function tagFindOrCreate($tags)
    {
        $tagArray = $this->tagStringToArray($tags);
        foreach ($tagArray as $tagName) {

            $tag = $this->isTagExist(Urlizer::urlize($tagName));

            if (!$tag) {
                $tag = new Tag();
                $tag->setDesignation($tagName);
                $tagName = $this->repository->createNewTag($tag);
            } else {
                $tagName = $tag->getId();
            }
        }

        return $tagArray;
    }
}