<?php
/**
 * Created by PhpStorm.
 * User: anelos
 * Date: 16/11/17
 * Time: 14:54
 */

namespace AppBundle\Service;


use AppBundle\Entity\Article;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class LikeManager
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setLike(Article $article, User $user){
        $article->addLike($user);
        $user->addLike($article);
        $this->em->persist($article);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function removeLike(Article $article, User $user)
    {
        $article->removeLike($user);
        $user->removeLike($article);
        $this->em->persist($article);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function manageLike(Article $article, User $user){
        if($article->getLikes()->contains($user)){
            $this->removeLike($article, $user);
        } else {
            $this->setLike($article, $user);
        }
        return $article;
    }

}