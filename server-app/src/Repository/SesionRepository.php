<?php

namespace App\Repository;

use App\Entity\Sesion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Clase que contiene los métodos para buscar en la tabla Sesion
 * @method Sesion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sesion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sesion[]    findAll()
 * @method Sesion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SesionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sesion::class);
    }

    
}
