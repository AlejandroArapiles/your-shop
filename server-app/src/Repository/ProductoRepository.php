<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Clase que contiene los métodos para buscar en la tabla Producto
 * @method Producto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Producto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Producto[]    findAll()
 * @method Producto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Producto::class);
    }

    /**
     * Filtra los productos en función de la tienda y un texto (nombre del producto)
     *
     * @param integer $idTienda
     * @param String $nombre
     * @return void
     */
    public function findAllByIdTiendaAndNombreProducto($idTienda, $nombre) {
        return $this->createQueryBuilder('p')
        ->andWhere('p.nombreproducto like :nombre')
        ->andWhere('p.idtiendaFk = :idTienda')
        ->setParameter('nombre', '%' . $nombre .'%')
        ->setParameter('idTienda', $idTienda)
        ->orderBy('p.idproducto', 'ASC')
        ->getQuery()
        ->getResult();
    }
}
