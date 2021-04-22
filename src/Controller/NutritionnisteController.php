<?php

namespace App\Controller;

use App\Entity\Nutritionniste;
use App\Form\NutritionnisteType;
use App\Repository\NutritionnisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NutritionnisteController extends AbstractController
{
    /**
     * @Route("/nutritionniste", name="nutritionniste")
     */
    public function index(): Response
    {
        return $this->render('nutritionniste/index.html.twig', [
            'controller_name' => 'NutritionnisteController',
        ]);
    }
    /**
     * @Route("/admin/nutritionniste", name="admin_nutritionniste")
     */
    public function nutritionnisteList(Request $request, EntityManagerInterface $manager,NutritionnisteRepository $repository): Response
    {
       // $nutritionniste = $this->getDoctrine()->getRepository(Nutritionniste::class)->findAll();
        $nutritionniste=$repository->findAll();
        $nutritionniste = new Nutritionniste();
        $form = $this->createForm(NutritionnisteType::class,$nutritionniste);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get("image")->getData();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('$uploads'),
                $fileName
            );
            $nutritionniste->setImage($fileName);
            $nut=$this->getDoctrine()->getManager();
            $nut->persist($nutritionniste);
            $nut->flush();
            return $this->redirectToRoute('admin_nutritionniste');
        }
       // return $this->render('nutritionniste/liste_nutritionniste.html.twig', ["form"=>$form->createView(), "nutritionniste"=>$nutritionniste]);
        $nutritionniste=$repository->findAll();
        return $this->render('nutritionniste/liste_nutritionniste.html.twig',["form"=>$form->createView(), 'nutritionniste'=>$nutritionniste]);

    }




    /**
     * @Route("admin/update-nutritionniste/{id}", name="update_nutritionniste")
     */
    public function update(Request$request, EntityManagerInterface $nut, $id): Response
    {
        if ($request->isMethod('post')) {
            // your code
            $nutritionniste = $this->getDoctrine()->getRepository(Nutritionniste::class)->find($id);
            $nutritionniste->setno($request->request->get('no'));
            $nutritionniste->setprenom($request->request->get('prenom'));
            $nutritionniste->setaddr($request->request->get('addr'));
            $nutritionniste->setmail( $request->request->get('mail'));
            $nutritionniste->setnum( $request->request->get('num'));
            $file = $request->files->get('image');
            if (!empty($file)){
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('$uploads'),
                    $fileName
                );
                $nutritionniste->setimage($fileName);
            }

            $nut->flush();
            return $this->redirectToRoute('admin_nutritionniste');
        }

    }

    /**
     * @Route("/admin/delete-nutritionniste/{id}", name="delete_nutritionniste")
     */
    public function delete($id, EntityManagerInterface $manager): Response
    {
        $nutritionniste = $this->getDoctrine()->getRepository(Nutritionniste::class)->find($id);
        $manager->remove($nutritionniste);
        $manager->flush();
        return $this->redirectToRoute('admin_nutritionniste');
    }

    /**
     * @Route("/front/liste-nutritionnistet-front", name="liste_nutritionniste_front")
     */
    public function listenutritionnisteFront(EntityManagerInterface $manager): Response
    {
        $nutritionniste = $this->getDoctrine()->getRepository(Nutritionniste::class)->findAll();
        return $this->render('nutritionniste/liste_nutritionniste_front.html.twig', ["nutritionniste"=>$nutritionniste]);
    }



    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }


}
