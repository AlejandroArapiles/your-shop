<?php

namespace App\Controller;

use App\Entity\Tienda;
use App\Entity\Producto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductoController extends AbstractController
{

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var SerializerInterface $serializer */
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * inserta un producto en la tienda perteneciente al usuario logado.
     *
     * @param Request $request
     * @return void
     */
    public function insertProducto(Request $request) 
    {
        $producto = new Producto();
        $datos = json_decode($request->getContent());
        $producto->setNombreproducto($datos->nombreproducto);
        $producto->setDescripcion($datos->descripcion);
        $producto->setCantidad($datos->cantidad);
        $producto->setPrecio($datos->precio);
        $producto->setActivo($datos->activo);
        $producto->setIdtiendaFk($this->entityManager->getReference('App\Entity\Tienda', $datos->tienda));

        $this->entityManager->persist($producto);
        $this->entityManager->flush();

        return new JsonResponse(['mensaje' => 'Saved new product with id '.$producto->getIdproducto()]);
    }
    
    /**
     * saca la lista de productos pertenecientes a la tienda la cual el usuario está logado. (Se añadirá filtro opcional por nombre de producto)
     *
     * @return void
     */
    public function listarProductos(Request $request, $idTienda)
    {
        $nombre = $request->query->get("nombre");
        if (isset($nombre) && !empty($nombre)) {
            $productos = $this->getDoctrine()->getRepository(Producto::class)->findAllByIdTiendaAndNombreProducto($idTienda, $nombre);
        } else {
            $productos = $this->getDoctrine()->getRepository(Producto::class)->findBy(["idtiendaFk" => $idTienda]);
        }
        $productos = $this->serializer->normalize($productos, null, ["groups" => "public"]);
        return new JsonResponse(['data' => $productos]);
    }

    /** 
     * muestra los datos de un producto por id, comprobando que el producto pertenezca a la misma tienda que el usuario logado.
     * 
     * @return void
     */
    public function verProducto($idProducto) : JsonResponse
    {
        $producto = $this->getDoctrine()->getRepository(Producto::class)->findByIdproducto($idProducto);
        $producto = $this->serializer->normalize($producto, null, ["groups" => "public"]);
        return new JsonResponse(['data' => $producto]);
    }

    /**
     * de la lista de productos previamente utilizada añadiremos la posibilidad de seleccionar los productos individualmente y borrar si se desea. Este producto pertenece a la tienda que el usuario está logado.
     *
     * @return void
     */
    public function eliminarProducto($idProducto) {
        $producto = $this->getDoctrine()->getRepository(Producto::class)->findOneByIdproducto($idProducto);
        if (isset($producto)) {
            $this->entityManager->remove($producto);
            $this->entityManager->flush();
            
            return new JsonResponse(['result' => 'ok']);
        }
        
        return new JsonResponse(['result' => 'No existe']);
    }

    /**
     * de la lista de productos previamente utilizada añadiremos la posibilidad de seleccionar los productos individualmente y modificar algún campo si se desea. Este producto pertenece a la tienda que el usuario está logado.
     *
     * @return void
     */
    public function modificarProducto(Request $request, $idProducto) {
        $producto = $this->getDoctrine()->getRepository(Producto::class)->findOneByIdproducto($idProducto);
        if (isset($producto)) {
            $datos = json_decode($request->getContent());
            $producto->setNombreProducto($datos->nombreproducto);
            $producto->setDescripcion($datos->descripcion);
            $producto->setPrecio($datos->precio);
            $producto->setCantidad($datos->cantidad);
            $producto->setActivo($datos->activo);

            $this->entityManager->persist($producto);
            $this->entityManager->flush();
            
            return new JsonResponse(['result' => 'ok']);
        }
        
        return new JsonResponse(['result' => 'No existe']);
    }

}
