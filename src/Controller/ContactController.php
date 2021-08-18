<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DepartementsRepository;
/* Pour le formulaire */
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
/* pour le Mail */
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

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
        
            ->add('nom',TextType::class,[
                'label' => 'Nom',
                'required' => true,

            ])

            ->add('email',EmailType::class,[
                'label' => 'Email',
                'required' => true,

            ])

            ->add('prenom',TextType::class,[
                'label' => 'Prénom',
                'required' => true,

            ])

            ->add('departements', ChoiceType::class,[
                'label' =>'Département',
                'required' => true,
                'choices' => $data,
            ])

            ->add('objet',TextType::class,[
                'label' => 'Objet',
                'required' => true,

            ])

            ->add('mail',TextareaType::class,[
                'label' => 'Corps du mail' ,
                'required' => true,
                'attr' =>[ 'cols' => '60' , 'rows' => '10'] 
            ])

            ->add('submit',SubmitType::class,[
                'label' => "Envoyer"
            ])

            ->getForm()
        ;

        return $this->render("contact/formulaire.html.twig", [
            'contactFormulaire' => $formBuilder->createView(),
        ]);

    }


    public function envoiEmail(MailerInterface $mailer, Request $request)
    {
            /* je récupére les inputs du formulaire */
            $data = $request->request->all('form');

            /* je met en place un tableau qui va vérifier les inputs  */
            $verif_input = [

                'nom',
                'prenom',
                'email',
                'departements',
                'objet',
                'mail'

            ];

            /* je met en place une boucle for pour vérifier si les champs souhaité existe ou ne sont pas vide */
            for($i = 0 ; $i < count($verif_input); $i++){
                if(isset($data[$verif_input[$i]])){
                    if($data[$verif_input[$i]] == ""){
                        $this->addFlash('error', 'Formulaire incorrect');
                        return $this->redirect('/formulaire');
                    }
                }else{
                    $this->addFlash('error', 'Formulaire incorrect');
                    return $this->redirect('/formulaire');
                }
            }

            /* je génére le Email */
            $email = (new Email())

                ->from($data['email'])
                ->to($data['departements'])
                ->subject($data['objet']. ' De ' . $data['nom'] . ' ' . $data['prenom'])
                ->text($data['mail']);
    
            /* je l'envoie */
            $mailer->send($email);

            /* je met en place un message de confirmation */
            $this->addFlash('success', 'Votre e-mail a bien été envoyé');
            return $this->redirect('/formulaire');
    }

}