<?php

namespace App\DataFixtures;

use App\Entity\Departements;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DepartementsFixtures extends Fixture
{
    public function load(ObjectManager $manager)

    /* je crée un tableau avec mes jeux de test */
    {
        $departements_jeux_test =  [

            1 => [
                'name' => 'Direction',
                'email' => 'direction-mail@mail.fr'
            ],

            2 => [
                'name' => 'COM',
                'email' => 'com-mail@mail.fr'
            ],

            3 => [
                'name' => 'DEV',
                'email' => 'dev-mail@mail.fr'
            ],

            4 => [
                'name' => 'RH',
                'email' => 'rh-mail@mail.fr'
            ],

        ];

        /* je fais une boucle sur le tableau de jeux de test pour insérer les données dans la BDD */

        foreach($departements_jeux_test as $departement){

            $departements = new Departements();
            $departements->setName($departement['name']);
            $departements->setEmail($departement['email']);

            $manager->persist($departements);
        }

        $manager->flush();
    }
}
