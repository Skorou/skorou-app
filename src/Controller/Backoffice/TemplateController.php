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
        $actions = parent::configureActions($actions);
        return $actions
            ->add(Crud::PAGE_EDIT, $generateTemplate)
            ->add(Crud::PAGE_DETAIL, $generateTemplate);
    }

    public function generateTemplate(AdminContext $context) {
        $obj = $context->getEntity()->getInstance();

        return $this->render('backoffice/template/generate_template.html.twig', [
            'obj' => $obj,
            'data' => json_encode($obj->getData())
        ]);
    }

    public function saveTemplate(AdminContext $context) {
        $request = $context->getRequest();
        if (!$request->isXmlHttpRequest()){
            throw new HttpException(403, "Can't access this action directly");
        }
        $data = json_decode($request->getContent());
        $templateData = json_decode($data->data);
        if (!$templateData) {
            throw new HttpException(400, "Missing order parameter");
        }

        $template = $context->getEntity()->getInstance();
        $entityManager = $this->getDoctrine()->getManager();
        $template->setData($templateData);

        $entityManager->persist($template);

        $entityManager->flush();

        return $this->json("ok");
    }
}