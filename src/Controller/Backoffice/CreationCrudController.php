<?php

namespace App\Controller\Backoffice;

use App\Entity\Creation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CreationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Creation::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
