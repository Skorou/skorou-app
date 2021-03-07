<?php


namespace App\Controller\Backoffice;


use App\Controller\Backoffice\utils\OrderableController;
use App\Entity\Template;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TemplateController extends OrderableController
{

    public static function getEntityFqcn(): string
    {
        return Template::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular("Template")
            ->setEntityLabelInSingular("Templates")
            ->setSearchFields(["name"]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("name", "Nom"),
            AssociationField::new("creation_type", "Catégorie")
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $generateTemplate = Action::new("generateTemplate", "Éditer le template", "fa fa-pencil")
            ->linkToCrudAction('generateTemplate');

        return $actions
            ->add(Crud::PAGE_EDIT, $generateTemplate)
            ->add(Crud::PAGE_DETAIL, $generateTemplate);
    }

    public function generateTemplate(AdminContext $context) {
        $obj = $context->getEntity()->getInstance();

        return $this->render('backoffice/template/generate_template.html.twig', [
            'obj' => $obj
        ]);
    }
}