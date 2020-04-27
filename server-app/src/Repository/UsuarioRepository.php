<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Usuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usuario[]    findAll()
 * @method Usuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    public function comprobarUsuario($nombre, $password)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.nombreusuario = :nombre')
            ->andWhere('u.password = :password')
            ->setParameter('nombre', $nombre)
            ->setParameter('password', md5($password))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllByIdTiendaAndNombreUsuario($idTienda, $nombre) {
        return $this->createQueryBuilder('u')
        ->andWhere('u.nombreusuario like :nombre')
        ->andWhere('u.idtiendaFk = :idTienda')
        ->setParameter('nombre', '%' . $nombre .'%')
        ->setParameter('idTienda', $idTienda)
        ->orderBy('u.idusuario', 'ASC')
        ->getQuery()
        ->getResult();
    }

    // /**
    //  * @return Usuario[] Returns an array of Usuario objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Usuario
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
