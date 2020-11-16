<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Stats{
    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    public function getStats(){
        
        $users = $this->getUsersCount();
        $focusPays = $this->getFocusPaysCount();
        $focusVille = $this->getFocusVilleCount();
        $focusLieu = $this->getFocusLieuCount();
        $comment = $this->getCommentCount();

        return compact('users', 'focusPays', 'focusVille', 'focusLieu', 'comment');
    }

    public function getUsersCount(){
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getFocusPaysCount(){
        return $this->manager->createQuery('SELECT count(p) FROM App\Entity\FocusPays p')->getSingleScalarResult();
    }

    public function getFocusVilleCount(){
        return $this->manager->createQuery('SELECT count(v) FROM App\Entity\FocusVille v')->getSingleScalarResult();
    }

    public function getFocusLieuCount(){
       return $this->manager->createQuery('SELECT count(l) FROM App\Entity\FocusLieu l')->getSingleScalarResult();
    }

    public function getCommentCount(){
        return $this->manager->createQuery('SELECT count(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    public function getFocusPaysStats($direction){
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, p.title, p.id, u.firstName, u.lastName, u.picture FROM App\Entity\Comment c JOIN c.focusPays p JOIN p.author u GROUP BY p ORDER BY note ' . $direction
        )->setMaxResults(5)->getResult();
    }

    public function getFocusVilleStats($direction){
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, p.title, p.id, u.firstName, u.lastName, u.picture FROM App\Entity\Comment c JOIN c.focusVille p JOIN p.author u GROUP BY p ORDER BY note ' . $direction
        )->setMaxResults(5)->getResult();
    }

    public function getFocusLieuStats($direction){
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, p.title, p.id, u.firstName, u.lastName, u.picture FROM App\Entity\Comment c JOIN c.focusVille p JOIN p.author u GROUP BY p ORDER BY note ' . $direction
        )->setMaxResults(5)->getResult();
    }

    // public function getBestFocusPays(){

    // }

    // public function getBadFocusPays(){
    //     return $this->manager->createQuery(
    //         'SELECT AVG(c.rating) as note, p.title, p.id, u.firstName, u.lastName, u.picture FROM App\Entity\Comment c JOIN c.focusPays p JOIN p.author u GROUP BY p ORDER BY note ASC'
    //     )->setMaxResults(5)->getResult();
    // }

    // public function getBestFocusVille(){
    //     return $this->manager->createQuery(
    //         'SELECT AVG(c.rating) as note, p.title, p.id, u.firstName, u.lastName, u.picture FROM App\Entity\Comment c JOIN c.focusVille p JOIN p.author u GROUP BY p ORDER BY note DESC'
    //     )->setMaxResults(5)->getResult();
    // }

    // public function getBadFocusVille(){
    //     return $this->manager->createQuery(
    //         'SELECT AVG(c.rating) as note, p.title, p.id, u.firstName, u.lastName, u.picture FROM App\Entity\Comment c JOIN c.focusVille p JOIN p.author u GROUP BY p ORDER BY note ASC'
    //     )->setMaxResults(5)->getResult();
    // }

    // public function getBestFocusLieu(){
    //     return $this->manager->createQuery(
    //         'SELECT AVG(c.rating) as note, p.title, p.id, u.firstName, u.lastName, u.picture FROM App\Entity\Comment c JOIN c.focusLieu p JOIN p.author u GROUP BY p ORDER BY note DESC'
    //     )->setMaxResults(5)->getResult();
    // }

    // public function getBadFocusLieu(){
    //     return $this->manager->createQuery(
    //         'SELECT AVG(c.rating) as note, p.title, p.id, u.firstName, u.lastName, u.picture FROM App\Entity\Comment c JOIN c.focusLieu p JOIN p.author u GROUP BY p ORDER BY note ASC'
    //     )->setMaxResults(5)->getResult();
    // }

}