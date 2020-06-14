<?php

namespace App\Controller\Client;

use App\Entity\Producto;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\AuthAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * CLIENT
 * Clase que interactúa con la tabla Producto en la base de datos
 */
class ProductoController extends AuthAbstractController {

    /** @var SessionInterface $sessionManager */
    protected $sessionManager;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer,
    AuthService $authService, SessionInterface $sessionManager) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
    }

    /**
     * Lista los productos de la tienda a la que pertenece el usuario
     *
     * @param integer $idTienda
     * @return void
     */
    public function listarProductos($idTienda)
    {
        if (!$this->authService->validateUserLogged()) {
            return $this->redirectToRoute('clientLogin');
        }
        $productos = $this->getDoctrine()->getRepository(Producto::class)->findByIdtiendaFk($idTienda);
        if ($this->sessionManager->get('user')['idTienda'] != $idTienda) {
            throw new AccessDeniedHttpException("Esta tienda no pertenece a este usuario");
        }
        
        return $this->render('producto/listado.html.twig', [
            'productos' => $productos
        ]);
    }

    /**
     * Crea un producto a la tienda que el usuario está logado.
     *
     * @param Request $request
     * @return void
     */
    public function crearProducto(Request $request)
    {
        if (!$this->authService->validateUserLogged()) {
            return $this->redirectToRoute('clientLogin');
        }
        if ($request->getMethod() == 'POST') {
            $producto = new Producto();
            $producto->setNombreproducto($request->request->get('nombre'));
            $producto->setDescripcion($request->request->get('descripcion'));
            $producto->setPrecio($request->request->get('precio'));
            $producto->setActivo($request->request->get('activo'));
            $producto->setCantidad($request->request->get('cantidad'));
            $producto->setIdtiendaFk($this->entityManager->getReference('App\Entity\Tienda', $this->sessionManager->get('user')['idTienda']));

            $this->entityManager->persist($producto);
            $this->entityManager->flush();

            return $this->redirectToRoute('clientListProductos', ['idTienda' => $producto->getIdTiendaFk()->getIdtienda()]);
        } else {
            return $this->render('producto/view.html.twig');
        }
    }

    /**
     * Lista la información de un producto recibiendo su id por parámetro
     *
     * @param Request $request
     * @param inte $idProducto
     * @return void
     */
    public function verProducto(Request $request, $idProducto)
    {
        if (!$this->authService->validateUserLogged()) {
            return $this->redirectToRoute('clientLogin');
        }
        $producto = $this->getDoctrine()->getRepository(Producto::class)->findOneByIdproducto($idProducto);
        if (!isset($producto) || $this->sessionManager->get('user')['idTienda'] != $producto->getIdTiendaFk()->getIdtienda()) {
            throw new AccessDeniedHttpException("Este producto no pertenece a esta tienda");
        }

        if ($request->getMethod() == 'POST') {
            $producto->setNombreproducto($request->request->get('nombre'));
            $producto->setDescripcion($request->request->get('descripcion'));
            $producto->setPrecio($request->request->get('precio'));
            $producto->setActivo($request->request->get('activo'));
            $producto->setCantidad($request->request->get('cantidad'));

            $this->entityManager->persist($producto);
            $this->entityManager->flush();
            return $this->redirectToRoute('clientListProductos', ['idTienda' => $producto->getIdTiendaFk()->getIdtienda()]);
        }
        return $this->render('producto/view.html.twig', [
            'producto' => $producto
        ]);
    }
}