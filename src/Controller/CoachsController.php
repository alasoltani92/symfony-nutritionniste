<?php

namespace App\Controller;

use App\Entity\Coachs;
use App\Form\CoachsType;
use App\Repository\CoachsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoachsController extends Controller
{
    /**
     * @Route("/coachs", name="coachs")
     */
    public function index(): Response
    {
        return $this->render('coachs/index.html.twig', [
            'controller_name' => 'CoachsController',
        ]);
    }
    /**
     * @Route("/admin/Coachs", name="admin_Coachs")
     */
    public function coachsList(Request $request, EntityManagerInterface $manager,CoachsRepository $repository, \Swift_Mailer $mailer): Response
    {
        $coachs = $this->getDoctrine()->getRepository(Coachs::class)->findAll();
        $coachs=$repository->findAll();
        $coachs = new Coachs();
        $form = $this->createForm(CoachsType::class,$coachs);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get("photo")->getData();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('$uploads'),
                $fileName
            );
            $coachs->setphoto($fileName);
            $co=$this->getDoctrine()->getManager();
            $co->persist($coachs);
            $co->flush();

/////////////////////email
            $message = (new \Swift_Message('vous ete inscrit'))
                ->setFrom('mejrialoulou74@gmail.com')
                ->setTo('hedibaa97@gmail.com')
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'coachs/registration.html.twig',
                        ['name' => $form,
                            'coachs' => $coachs,]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);


            $this->addFlash('message','Vous aller recevoir un mail');
//////////////////////////////////

            return $this->redirectToRoute('admin_Coachs');

        }
        // return $this->render('coachs/liste_coachs.html.twig', ["form"=>$form->createView(), "coachs"=>$coachs]);
        $coachs=$repository->findAll();

       ///////////////////////// //pagination
        $allcoachs = $repository->findAll();
        $coachs = $this->get('knp_paginator')->paginate(
        // Doctrine Query, not results
            $allcoachs,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3
        );

/////////////////////////////////////////



        return $this->render('coachs/liste_coachs.html.twig',["form"=>$form->createView(), 'coachs'=>$coachs]);

    }




    /**
     * @Route("admin/update-coachs/{id}", name="update_coachs")
     */
    public function update(Request $request, EntityManagerInterface $co, $id): Response
    {
        if ($request->isMethod('post')) {
            // your code
            $coachs = $this->getDoctrine()->getRepository(Coachs::class)->find($id);
            $coachs->setnom($request->request->get('nom'));
            $coachs->setprenom($request->request->get('prenom'));
            $coachs->setaddr($request->request->get('addr'));
            $coachs->setmail( $request->request->get('mail'));
            $coachs->setnum( $request->request->get('num'));
            $file = $request->files->get('photo');
            if (!empty($file)){
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('$uploads'),
                    $fileName
                );
                $coachs->setphoto($fileName);
            }

            $co->flush();
            return $this->redirectToRoute('admin_Coachs');
        }

    }

    /**
     * @Route("/admin/delete-coachs/{id}", name="delete_coachs")
     */
    public function delete($id, EntityManagerInterface $manager): Response
    {
        $coachs = $this->getDoctrine()->getRepository(Coachs::class)->find($id);
        $manager->remove($coachs);
        $manager->flush();
        return $this->redirectToRoute('admin_Coachs');
    }

    /**
     * @Route("/front/liste-coachs-front", name="liste_coachs_front")
     */
    public function listecoachsFront(EntityManagerInterface $manager): Response
    {
        $coachs = $this->getDoctrine()->getRepository(Coachs::class)->findAll();
        return $this->render('coachs/liste_coachs_front.html.twig', ["coachs"=>$coachs]);
    }



    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

}
