<?php

namespace App\Controller\Api;

use App\Entity\Tienda;
use App\Entity\Producto;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\AuthAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * API
 * Clase que interactúa con la tabla Producto en la base de datos
 */
class ProductoController extends AuthAbstractController
{

    /**
     * inserta un producto en la tienda perteneciente al usuario logado.
     * @param Request $request
     * @return void
     */
    public function insertProducto(Request $request) 
    {
        $producto = new Producto();
        $datos = json_decode($request->getContent());
        $idTienda = $datos->idtiendaFk->idtienda;
        $this->validarMd5($request, $idTienda);
        $producto->setNombreproducto($datos->nombreproducto);
        $producto->setDescripcion($datos->descripcion);
        $producto->setCantidad($datos->cantidad);
        $producto->setPrecio($datos->precio);
        $producto->setActivo($datos->activo);
        $producto->setIdtiendaFk($this->entityManager->getReference('App\Entity\Tienda', $idTienda));

        $this->entityManager->persist($producto);
        $this->entityManager->flush();

        $producto = $this->serializer->normalize($producto, null, ["groups" => "public"]);

        return new JsonResponse($producto);
    }
    
    /**
     * saca la lista de productos pertenecientes a la tienda la cual el usuario está logado.
     *
     * @return void
     */
    public function listarProductos(Request $request, $idTienda)
    {
        $this->validarMd5($request, $idTienda);
        $nombre = $request->query->get("nombre");
        if (isset($nombre) && !empty($nombre)) {
            $productos = $this->getDoctrine()->getRepository(Producto::class)->findAllByIdTiendaAndNombreProducto($idTienda, $nombre);
        } else {
            $productos = $this->getDoctrine()->getRepository(Producto::class)->findBy(["idtiendaFk" => $idTienda]);
        }
        $productos = $this->serializer->normalize($productos, null, ["groups" => "public"]);
        return new JsonResponse($productos);
    }

    /** 
     * muestra los datos de un producto por id, comprobando que el producto pertenezca a la misma tienda que el usuario logado.
     * 
     * @return void
     */
    public function verProducto($idProducto) : JsonResponse
    {
        $producto = $this->getDoctrine()->getRepository(Producto::class)->findByIdproducto($idProducto);
        $this->validarMd5($request, $producto->getIdtiendaFk()->getIdtienda());
        $producto = $this->serializer->normalize($producto, null, ["groups" => "public"]);
        return new JsonResponse($producto);
    }

    /**
     * de la lista de productos previamente utilizada añadiremos la posibilidad de seleccionar los productos individualmente y borrar si se desea. Este producto pertenece a la tienda que el usuario está logado.
     *
     * @return void
     */
    public function eliminarProducto($idProducto) {
        $producto = $this->getDoctrine()->getRepository(Producto::class)->findOneByIdproducto($idProducto);
        $this->validarMd5($request, $producto->getIdtiendaFk()->getIdtienda());
        if (isset($producto)) {
            $this->entityManager->remove($producto);
            $this->entityManager->flush();
            
            return new JsonResponse(null, Response::HTTP_OK);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * de la lista de productos previamente utilizada añadiremos la posibilidad de seleccionar los productos individualmente y modificar algún campo si se desea. Este producto pertenece a la tienda que el usuario está logado.
     *
     * @return void
     */
    public function modificarProducto(Request $request, $idProducto) {
        $producto = $this->getDoctrine()->getRepository(Producto::class)->findOneByIdproducto($idProducto);
        $this->validarMd5($request, $producto->getIdtiendaFk()->getIdtienda());
        if (isset($producto)) {
            $datos = json_decode($request->getContent());
            $producto->setNombreProducto($datos->nombreproducto);
            $producto->setDescripcion($datos->descripcion);
            $producto->setPrecio($datos->precio);
            $producto->setCantidad($datos->cantidad);
            $producto->setActivo($datos->activo);

            $this->entityManager->persist($producto);
            $this->entityManager->flush();
            
            $producto = $this->serializer->normalize($producto, null, ["groups" => "public"]);
            return new JsonResponse($producto, Response::HTTP_OK);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

}
