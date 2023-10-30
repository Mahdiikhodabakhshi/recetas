<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RecetasController extends AbstractController
{
    #[Route('/recetas', name: 'listado_recetas',methods:["GET"])]
    #[Route('/', name: 'listado_recetas',methods:["GET"])]
    public function listado_recetas(): Response
    {
        return  new Response("listado DE recetas");
        /*
        return $this->render('recetas/index.html.twig', [
            'controller_name' => 'RecetasController',
        ]);
        //*/
    }
    #[Route('/recetas', name: 'crear_recetas',methods:["POST"])]
    public function crear_recetas(): Response
    {
        return  new Response("FURMULARIO DE CREAR NUEVA RECETA");
       
    }
    #[Route('/recetas/{id}', name: 'detalle_recetas',methods:["GET"])]
    public function detalle_recetas(int $id): JsonResponse
    {
        return  new JsonResponse(["ID" => $id , "message" => "detalle" , "error" =>null]);
       
    }
    #[Route('/recetas/{id}/{nombre}/{texto}', name: 'rec_recetas',methods:["GET"])]
    public function rec_recetas(int $id,string $nombre,string $texto): JsonResponse
    {
        return  new JsonResponse(["ID" => $id , "Nombre" => $nombre , "Texto" =>$texto]);
       
    }

    #[Route('/recetas/{id}', name: 'delete_recetas',methods:["DELETE"])]
    public function detele_recetas(int $id,): Response
    {
        return  new Response("eleminado de receta" . $id,Response::HTTP_NOT_FOUND);
       
    }

    #[Route('/recetas/{id}', name: 'update_recetas',methods:["PATCH"])]
    public function patch_recetas(int $id): Response
    {
        return  new Response("actualizado de receta" . $id,Response::HTTP_NOT_FOUND);
       
    }
}
