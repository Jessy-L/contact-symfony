<?php

namespace App\Controller;

use App\Repository\DepartementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    
    public function index(): Response
    {
        /* Envoir sur la view dans le dossier template/contact avec comme nom index.html.twig */
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    public function affichageForm(DepartementsRepository $departements){


        /* Je récupére toute les données dans la table département */
        $info = $departements->findAll();
        
        /* Je met en place un tableau vide  */
        $data = [];


        /* je remplie $data avec les info de la base de données */
        foreach($info as $departement){
            $data += [$departement->getName() => $departement->getEmail() ] ;
        }
        
        /* ici j'initialise les options du formulaire  */

        $option = [
            'action' => '/mail',
            'method' =>'POST'
        ];


        /* j'ajoute les champs voulu pour le formulaire de contact */

        $formBuilder = $this->createFormBuilder($data, $option)
        
            ->add('nom',TextType::class,['label' => 'Nom'])
            ->add('prenom',TextType::class,['label' => 'Prénom'])
            ->add('email',EmailType::class,['label' => 'Email'])
            ->add('departements', ChoiceType::class,['label' =>'Département', 'choices' => $data ])
            ->add('objet',TextType::class,['label' => 'Objet'])
            ->add('mail',TextType::class,['label' => 'Corps du mail'])
            ->add('submit',SubmitType::class,['label' => "Envoyer"])
            ->getForm()

        ;

        return $this->render("contact/formulaire.html.twig", [
            'contactFormulaire' => $formBuilder->createView(),
        ]);

    }


    public function envoiMail(){

        dump('MAIL ENVOYER');

    }

}
