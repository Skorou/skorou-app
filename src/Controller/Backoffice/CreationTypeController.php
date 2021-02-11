<?php


namespace App\Controller\Backoffice;


use App\Controller\Backoffice\utils\OrderableController;
use App\Entity\CreationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CreationTypeController extends OrderableController
{

    public static function getEntityFqcn(): string
    {
        return CreationType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular("Type de création")
            ->setEntityLabelInPlural("Types de création")
            ->setSearchFields(['name']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("name", "Nom")->setSortable(false),
            TextField::new("dimensionsLabel", "Dimensions")->onlyOnIndex(),
            IntegerField::new("width", "Largeur")->onlyOnForms(),
            IntegerField::new("height", "Hauteur")->onlyOnForms(),
        ];
    }
}