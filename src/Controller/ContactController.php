<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request ,\Swift_Mailer $mailer)
    {
        $form=$this->createForm((ContactType::class));
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $contact=$form->getData();

$message=(new \Swift_Message('nouveau message'))
    ->setFrom($contact['mail'])
    ->setTo('medaladin92@gmail.com')
    ->setBody(
        $this->renderView('mail/mail.html.twig',compact('contact')
        ),'text/html'
    );

      $mailer->send($message);
      $this->addFlash('message','le message est bien ete envoyer');
      return $this->redirectToRoute('liste_nutritionniste_front');
        }
        return $this->render('contact/index.html.twig', [
            'contactform' => $form->createView()
        ]);
    }
}
