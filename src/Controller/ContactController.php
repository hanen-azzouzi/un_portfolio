<?php


namespace App\Controller;

use App\Entity\Message;
use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ContactController extends AbstractController
{
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Créer une nouvelle instance de l'entité Message
            $message = new Message();
            
            // Récupérer les données du formulaire et les définir sur l'objet Message
            $name = $form->get('name')->getData();
            $email = $form->get('email')->getData();
            $messageText = $form->get('message')->getData();
            
            $message->setName($name);
            $message->setEmail($email);
            $message->setMessage($messageText);

            // Enregistrer l'objet Message dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            // Afficher un message de confirmation ou rediriger l'utilisateur
            $this->addFlash('success', 'Votre message a été envoyé avec succès !');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(),
        ]);
    }
}
