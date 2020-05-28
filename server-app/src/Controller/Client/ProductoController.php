<?php

namespace App\Controller\Client;

use App\Entity\Producto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProductoController extends AbstractController {

    /** @var EntityManagerInterface $entityManager */
    protected $entityManager;

    /** @var SessionInterface $sessionManager */
    protected $sessionManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $sessionManager) {
       $this->entityManager = $entityManager;
       $this->sessionManager = $sessionManager;
   }

    public function listarProductos($idTienda)
    {
        if ($this->sessionManager->get('user')['idTienda'] != $idTienda) {
            throw new AccessDeniedHttpException("Esta tienda no pertenece a este usuario");
        }
        $productos = $this->getDoctrine()->getRepository(Producto::class)->findByIdtiendaFk($idTienda);
        return $this->render('producto/listado.html.twig', [
            'productos' => $productos
        ]);
    }

    public function crearProducto(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $producto = new Producto();
            $producto->setNombreproducto($request->request->get('nombre'));
            $producto->setDescripcion($request->request->get('descripcion'));
            $producto->setPrecio($request->request->get('precio'));
            $producto->setActivo($request->request->get('activo'));
            $producto->setCantidad($request->request->get('cantidad'));
            $producto->setIdtiendaFk($this->entityManager->getReference('App\Entity\Tienda', 1));

            $this->entityManager->persist($producto);
            $this->entityManager->flush();

            return $this->redirectToRoute('clientViewProducto', ['idProducto' => $producto->getIdproducto()]);
        } else {
            return $this->render('producto/view.html.twig');
        }
        //$producto = $this->getDoctrine()->getRepository(Producto::class)->findByIdproducto($idProducto);
    }

    public function verProducto($idProducto)
    {
        $producto = $this->getDoctrine()->getRepository(Producto::class)->findOneByIdproducto($idProducto);

        //TODO comprobar idTienda  de session es la misma que la del producto
        return $this->render('producto/view.html.twig', [
            'producto' => $producto
        ]);
    }
}