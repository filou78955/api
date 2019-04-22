<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\NationaliteRepository;

class ApiAuteurController extends AbstractController
{
    /**
     * @Route("/api/auteurs", name="api_auteurs", methods={"GET"})
     */
    public function list(AuteurRepository $repo, SerializerInterface $serializer)
    {
        $auteurs=$repo->findAll();
        $resultat=$serializer->serialize(
            $auteurs,
            'json',
            [
                'groups'=>['listAuteurFull']
            ]
        );
        return new JsonResponse($resultat,200,[],true);
    }
    /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_show", methods={"GET"})
     */
    public function show(Auteur $auteur, SerializerInterface $serializer)
    {
        
        $resultat=$serializer->serialize(
            $auteur,
            'json',
            [
                'groups'=>['listAuteurSimple']
            ]
        );
        return new JsonResponse($resultat,Response::HTTP_OK,[],true);
    }
    /**
     * @Route("/api/auteurs", name="api_auteurs_create", methods={"POST"})
     */
    public function create(Request $request, ObjectManager $manager, NationaliteRepository $repnation, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data=$request->getContent();
        $dataTab=$serializer->decode($data,'json');
        $auteur = new Auteur ();
       $nationalite=$repnation->find($dataTab['nationalite']['id']);
       $serializer->deserialize($data, Auteur::class,'json',['object_to_populate'=>$auteur]);
       $auteur->setNationalite($nationalite);

        //$serializer->deserialize($data, Auteur::class,'json',['object_to_populate'=>$auteur]);
         $errors=$validator->validate($auteur);
         if(count($errors)){
             $errorsJson=$serializer->serialize($errors,'json');
             return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST,[],true);
         }
         $manager->persist($auteur);
         $manager->flush();
        
        return new JsonResponse("Le auteur a bien été crée",Response::HTTP_CREATED,
        //["location"=>"api/auteurs/".$auteur->getId()]
        ["location"=>$this->generateUrl(
            'api_auteurs_show',
             ["id"=>$auteur->getId()],
              UrlGeneratorInterface::ABSOLUTE_URL)],
        true);
        
    }

    /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_update", methods={"PUT"})
     */
    public function edit(Auteur $auteur,Request $request, NationaliteRepository $repnation ,ObjectManager $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data=$request->getContent();
        $dataTab=$serializer->decode($data,'json');
        $nationalite=$repnation->find($dataTab['nationalite']['id']);
        $serializer->deserialize($data, Auteur::class,'json',['object_to_populate'=>$auteur]);
        $auteur->setNationalite($nationalite);
        $errors=$validator->validate($auteur);
        
         if(count($errors)){
             $errorsJson=$serializer->serialize($errors,'json');
             return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST,[],true);
         }
        $manager->persist($auteur);
        $manager->flush(); 
        return new JsonResponse("Le auteur a bien été modifié",Response::HTTP_OK,[],true);
        
    }

    /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_delete", methods={"DELETE"})
     */
    public function delete(Auteur $auteur, ObjectManager $manager)
    {
        
        $manager->remove($auteur);
        $manager->flush(); 
        return new JsonResponse("Le auteur a bien été supprimé",Response::HTTP_OK,[]);
        
    }
}
