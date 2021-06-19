<?php


namespace App\Controller\Backoffice;


use App\Entity\Template;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TemplateController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Template::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("name", "Nom"),
            AssociationField::new("creation_type", "Type de création")
        ];
    }
}