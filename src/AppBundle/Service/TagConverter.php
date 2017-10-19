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
        $tagArray = $this->tagStringToArray($article->getTempTags());
        $repository = $this->em->getRepository('AppBundle:Tag');
        foreach ($tagArray as $tagName) {

            $tag = $repository->findOneTagBySlug(Urlizer::urlize($tagName));

            if (!$tag) {
                $tag = new Tag();
                $tag->setDesignation($tagName);
                $tag = $repository->createNewTag($tag);
            }
            if (!$article->getTags()->contains($tag)) {
                $article->addTag($tag);
            }
            $tagName = $tag;

        }
        $article = $this->removeUndesirableTags($article, $tagArray);

        return $article;
    }

    public function removeUndesirableTags(Article $article, $tags)
    {
        $repository = $this->em->getRepository('AppBundle:Tag');
        if ($article->getTags()->toArray() != $tags) {
            $articleTag = $article->getTags()->toArray();
            foreach ($articleTag as $tag) {
                if (!in_array($tag, $tags)) {
                    $article->removeTag($repository->findOneTagBySlug(Urlizer::urlize($tag)));
                }
            }
        }
        return $article;

    }
}