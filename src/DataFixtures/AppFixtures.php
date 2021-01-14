<?php

namespace App\DataFixtures;

use App\Entity\CategorieCharge as CategorieChargeEntity;
use App\Entity\CategorieAlimentation as CategorieAlimentationEntity;
use App\Entity\Charges as ChargesEntity;
use App\Entity\Alimentation as AlimentationEntity;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Migrations\Configuration\EntityManager\EntityManagerLoader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    protected const CATEGORIES_CHARGES = [
        [
            'label' => 'Charges fixes',
            'couleur' => '#FAE288',
            'icone' => 'fa fa-edit'
        ],
        [
            'label' => 'Charges variables',
            'couleur' => '#98D4E0',
            'icone' => 'fa fa-calculator'
        ]
    ];

    protected const CATEGORIES_ALIMENTATION = [
        [
            'label' => 'E-leclerc',
            'couleur' => '#C5AEF0',
            'icone' => 'fa fa-shopping-cart'
        ],
        [
            'label' => 'Boucher',
            'couleur' => '#F7927C',
            'icone' => 'fa fa-utensils'
        ],
        [
            'label' => 'Poissonnier',
            'couleur' => '#AAFBFF',
            'icone' => 'fa fa-fish'
        ]
    ];

    protected const CHARGES = [
        [
            'categorie_id' => 1,
            'libelle' => 'Abonnement SFR',
            'montant' => 52.78
        ],
        [
            'categorie_id' => 1,
            'libelle' => 'Loyer',
            'montant' => 682.50
        ],
        [
            'categorie_id' => 1,
            'libelle' => 'Assurance habitation',
            'montant' => 12.24
        ],
        [
            'categorie_id' => 2,
            'libelle' => 'Vidange B.M.W',
            'montant' => 98.71
        ],
        [
            'categorie_id' => 2,
            'libelle' => 'Cadeaux noêl',
            'montant' => 161.68
        ]
    ];

    protected const ALIMENTATION = [
        [
            'categorie_id' => 1,
            'libelle' => 'Courses',
            'montant' => 46.09
        ],
        [
            'categorie_id' => 1,
            'libelle' => 'Courses',
            'montant' => 84.98
        ],
        [
            'categorie_id' => 2,
            'libelle' => 'World Market',
            'montant' => 30.28
        ],
        [
            'categorie_id' => 3,
            'libelle' => 'Sainte-luce-sur-loire',
            'montant' => 37.30
        ]
    ];

    public function load(ObjectManager $manager)
    {
        
        for ($i = 0; $i < count(self::CATEGORIES_CHARGES); $i++) {
            $categorieCharge = new CategorieChargeEntity();
            $categorieCharge->setLabel(self::CATEGORIES_CHARGES[$i]['label']);
            $categorieCharge->setCouleur(self::CATEGORIES_CHARGES[$i]['couleur']);
            $categorieCharge->setIcone(self::CATEGORIES_CHARGES[$i]['icone']);
            $categorieCharge->setCreatedAt(new \Datetime());
            $categorieCharge->setUpdatedAt(new \Datetime());

            $manager->persist($categorieCharge);
            $manager->flush();

            $this->setReference('categorie-charge-'.$categorieCharge->getId(), $categorieCharge);            
        }

        for ($j = 0; $j < count(self::CHARGES); $j++) {
            $categorie_id = self::CHARGES[$j]['categorie_id'];
            $categorie = $this->getReference('categorie-charge-'.$categorie_id);
        
            $charges = new ChargesEntity();
            $charges->setLibelle(self::CHARGES[$j]['libelle']);
            $charges->setMontant(self::CHARGES[$j]['montant']);
            $charges->setCategorie($categorie);
            $charges->setCreatedAt(new \Datetime());
            $charges->setUpdatedAt(new \Datetime());
            
            $manager->persist($charges);
            $manager->flush();            
        }

        for ($i = 0; $i < count(self::CATEGORIES_ALIMENTATION); $i++) {
            $categorieAlimentation = new CategorieAlimentationEntity();
            $categorieAlimentation->setLabel(self::CATEGORIES_ALIMENTATION[$i]['label']);
            $categorieAlimentation->setCouleur(self::CATEGORIES_ALIMENTATION[$i]['couleur']);
            $categorieAlimentation->setIcone(self::CATEGORIES_ALIMENTATION[$i]['icone']);
            $categorieAlimentation->setCreatedAt(new \Datetime());
            $categorieAlimentation->setUpdatedAt(new \Datetime());

            $manager->persist($categorieAlimentation);            
            $manager->flush();

            $this->setReference('categorie-alimentation-'.$categorieAlimentation->getId(), $categorieAlimentation);
        }

        for ($j = 0; $j < count(self::ALIMENTATION); $j++) {
            $categorie_id = self::ALIMENTATION[$j]['categorie_id'];
            $categorie = $this->getReference('categorie-alimentation-'.$categorie_id);
            
            $alimentation = new AlimentationEntity();
            $alimentation->setLibelle(self::ALIMENTATION[$j]['libelle']);
            $alimentation->setMontant(self::ALIMENTATION[$j]['montant']);                    
            $alimentation->setCategorie($categorie);
            $alimentation->setCreatedAt(new \Datetime());
            $alimentation->setUpdatedAt(new \Datetime());

            $manager->persist($alimentation);
            $manager->flush();           
        }
    }
}