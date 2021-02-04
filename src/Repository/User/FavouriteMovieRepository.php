<?php


namespace App\Repository\User;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class FavouriteMovieRepository extends EntityRepository
{
    /**
     * Get user favourites by imdbIDs.
     *
     * @param UserInterface $user
     * @param array $ids
     * @return array
     */
    public function getByImdbIds(UserInterface $user, array $ids): array
    {
        $qb = $this->createQueryBuilder('f', 'f.imdbMovieId');

        $qb
            ->where('f.user = :user')
            ->andWhere('f.imdbMovieId IN (:ids)')
            ->setParameter('user', $user)
            ->setParameter('ids', $ids)
            ->orderBy('f.createdAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();
    }
}
