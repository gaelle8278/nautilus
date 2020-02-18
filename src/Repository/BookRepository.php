<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findWithEditor($bookId)
    {
        $entityManager = $this->getEntityManager();
        
        $query = $entityManager->createQuery(
            'SELECT b, e
        FROM App\Entity\Book b
        INNER JOIN b.editor e
        WHERE b.id = :id'
            )->setParameter('id', $bookId);
            
        return $query->getOneOrNullResult();
    }
    
    /**
     * Returns a list of Books objects whose authors' lastname match the specified term 
     * @param string $lastname
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function findBookByLikeAuhtorLastname(string $lastname) : array {
        $entityManager = $this->getEntityManager();
        
        $query = $entityManager->createQuery('
            SELECT b, a 
            FROM App\Entity\Book b
            INNER join b.authors a
            WHERE a.lastname LIKE :lastname
       ')->setParameter('lastname', '%'.$lastname.'%');
        
        
        return $query->getResult();
    }
    
    
}
