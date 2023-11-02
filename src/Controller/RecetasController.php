<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Receta;
use App\Repository\RecetaRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RecetasController extends AbstractController
{
    #[Route('/recetas', name: 'listado_recetas',methods:["GET"])]
    #[Route('/', name: 'raiz',methods:["GET"])]
    public function listado_recetas(RecetaRepository $recetaRepository): Response
    {
        /*
        $recetas =  $recetaRepository->findAll();

        return $this->json($recetas);
       // return  new JsonResponse($recetas);
       */
        
        return $this->render('recetas/listado.html.twig', [
            'recetas' => $recetaRepository->findAll(),
        ]);
        
    }
    #[Route('/recetas', name: 'crear_recetas',methods:["POST"])]
    #[Route('/recetas/crear', name: 'crear_recetas_formulario',methods:["GET","POST"])]
    public function crear_recetas(EntityManagerInterface $manager,Request $request): Response
    {

        $receta = new Receta;
        $form =  $this->createFormBuilder($receta)
                    ->add("nombre" , TextType::class,[
                        "constraints"=>[
                            new Length(["min" => 10])
                        ]
                    ])
                    ->add("texto" , TextType::class)
                    ->setMethod("POST")
                    ->add("CREAR" , SubmitType::class)

                    ->getForm();

        $form-> handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            //utilizo los datos recibidos

            $receta = $form->getData();

            $manager->persist($receta);
            $manager->flush();
            
            return $this->redirectToRoute("listado_recetas");


        }else{
            //mostrar el formulario para que el usario lo rellene

             return $this->render('recetas/crear.html.twig', [
                        'formulario' => $form,
                    ]);
        }
        
        
        
       
        /*
        $receta ->setNombre("paella");
        $receta ->setTexto("la mejor paella de la vida");
        

        $manager->persist($receta); //quiero guardar/crear esta receta
        $manager->flush();//para que se guarden los cambios en la bd


        return  new Response("creada una nueva receta con id ".$receta->getId());
        //*/
       
    }
    #[Route('/recetas/{id}', name: 'detalle_recetas',methods:["GET"])]
    public function detalle_recetas(int $id,RecetaRepository $recetaRepository): Response
    {
        $receta =  $recetaRepository->find($id);

        return $this->render('recetas/detalle.html.twig', [
            'controller_name' => 'RecetasController',
            'receta'=>$receta
        ]);


      //  return  $this->json($receta);
       
    }
    #[Route('/recetas/{id}/{nombre}/{texto}', name: 'rec_recetas',methods:["GET"])]
    public function rec_recetas(int $id,string $nombre,string $texto): JsonResponse
    {
        return  new JsonResponse(["ID" => $id , "Nombre" => $nombre , "Texto" =>$texto]);
       
    }

    #[Route('/recetas/{id}', name: 'delete_recetas',methods:["DELETE"])]
    #[Route('/recetas/{id}/delete', name: 'delete_recetas_get',methods:["GET"])]

    public function detele_recetas(int $id,RecetaRepository $recetaRepository , EntityManagerInterface $manager): Response
    {
          //buscar receta por ID
       $receta = $recetaRepository->find($id);

       if($receta != null){

            $manager->remove($receta);

        
        //decirle al gestor que guarde los cambios

        $manager->flush();

        return $this->redirectToRoute("listado_recetas");
       }{
        throw $this->createNotFoundException("No existe receta con el id".$id);
       }
       //eleminar receta
       

       // return  new Response("eleminado de receta" . $id,Response::HTTP_NOT_FOUND);
       
    }

    #[Route('/recetas/{id}', name: 'update_recetas',methods:["PATCH"])]
    public function patch_recetas(int $id,RecetaRepository $recetaRepository , EntityManagerInterface $manager): Response
    {
        //buscar receta por ID
       $receta = $recetaRepository->find($id);

       //CAMBIAR LOS DATOS
       $receta ->setNombre("*".$receta->getNombre());

       //decirle al gestor que guarde los cambios

       $manager->flush();

       return new Response("cambiada la receta".$id);


    }
}
