<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    public function findWithAllInfos($bookId)
    {
        $entityManager = $this->getEntityManager();
        
        $query = $entityManager->createQuery(
            'SELECT b, e, a
        FROM App\Entity\Book b
        INNER JOIN b.editor e
        INNER JOIN b.authors a
        WHERE b.id = :id'
            )->setParameter('id', $bookId);
            
        return $query->getOneOrNullResult();
    }
    
    public function findAllWithAllInfos()
    {
        $entityManager = $this->getEntityManager();
        
        $query = $entityManager->createQuery(
            'SELECT b, e, a
        FROM App\Entity\Book b
        INNER JOIN b.editor e
        INNER JOIN b.authors a'
            );
            
        return $query->getResult();
    }
    
    /**
     * Returns a list of Books objects whose authors' lastname match the specified term 
     * 
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
    
    /**
     * Returns list of books according to an offset and a agiven limit 
     * 
     * @param int $page             current page
     * @param int $nbMaxPerPage     number of books to get
     */
    public function findAllPagined($page, $nbMaxPerPage) {
        if (!is_numeric($page) || $page < 1 || !is_numeric($nbMaxPerPage)) {
            throw new NotFoundHttpException('La page demandée n\'existe pas');
        }
        
        $query = $this->getEntityManager()->createQuery(
            'SELECT b, e, a
             FROM App\Entity\Book b
             INNER JOIN b.editor e
             INNER JOIN b.authors a'
        );
        
        $offset = ($page - 1) * $nbMaxPerPage;
        $query->setFirstResult($offset)->setMaxResults($nbMaxPerPage);
        $paginator = new Paginator($query);
        
        if ( ($paginator->count() <= $offset) && $page != 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas.'); // page 404, sauf pour la première page
        }
        
        return $paginator;
    }
    
    
}
