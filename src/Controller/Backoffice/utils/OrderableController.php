<?php


namespace App\Controller\Backoffice\utils;


use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class OrderableController extends AbstractCrudController
{
    private static function getOrderableFieldSetter(): string {
        return 'setOrderIndex';
    }

    private static function getOrderableField(): string {
        return 'order_index';
    }

    public function createEntity(string $entityFqcn)
    {

        $newEntity = parent::createEntity($entityFqcn);
        $maxOrderIndex = $this->getDoctrine()->getRepository($entityFqcn)
            ->createQueryBuilder('e')
            ->select('MAX(e.order_index)')
            ->getQuery()->getSingleResult()[1];
        $newEntity->{$this::getOrderableFieldSetter()}(is_null($maxOrderIndex) ? 0 : $maxOrderIndex);
        return $newEntity;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->overrideTemplate('crud/index', 'admin/orderable_index.html.twig')
            ->setPaginatorPageSize(PHP_INT_MAX) // All entities must be shown to order them
            ->setDefaultSort([$this::getOrderableField() => 'ASC']);
    }

    public function order(AdminContext $context) {
        $request = $context->getRequest();
        if (!$request->isXmlHttpRequest()){
            throw new HttpException(403, "Can't access this action directly");
        }
        $data = json_decode($request->getContent());
        $newOrder = json_decode($data->order);
        if (!$newOrder) {
            throw new HttpException(400, "Missing order parameter");
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entities = $this->getDoctrine()
            ->getRepository($this::getEntityFqcn())
            ->findBy(['id' => $newOrder]);

        foreach ($entities as $entity) {
            $newIndex = array_search($entity->getId(), $newOrder);
            if($newIndex !== false){
                $entity->{$this::getOrderableFieldSetter()}(
                    $newIndex
                );
            }
        }

        $entityManager->flush();

        return $this->json("ok");
    }
}