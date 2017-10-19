<?php

namespace AppBundle\Service;

use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Gedmo\Sluggable\Util\Urlizer;


/**
 * Class TagConverter
 * @package AppBundle\Service
 */
class TagConverter
{

    private $em;
    private $article;

    /**
     * TagConverter constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $tagString
     * @return array
     */
    public function tagStringToArray($tagString)
    {
        return array_map('trim', explode(";", $tagString));
    }

    /**
     * @param Article $article
     * @return Article
     */
    public function tagFindOrCreate(Article $article)
    {
        $this->article = $article;
        $tagArray = $this->tagStringToArray($this->article->getTempTags());
        foreach ($tagArray as $tagName) {

            $tag = $this->em->getRepository('AppBundle:Tag')->findOneTagBySlug(Urlizer::urlize($tagName));

            if (!$tag) {
                $tag = new Tag();
                $tag->setDesignation($tagName);
                $tag = $this->em->getRepository('AppBundle:Tag')->createNewTag($tag);
            }
            $this->article->addTag($tag);
        }

        return $this->article;
    }
}