<?php

namespace App\Controller\Backoffice;

use App\Entity\Subscription;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $fakeSubscription = Action::new('fakeSubscription', 'Simuler un abonnement', 'fa fa-file-invoice-dollar')
            ->linkToCrudAction('fakeSubscription');

        return $actions
            ->add(Crud::PAGE_DETAIL, $fakeSubscription)
            ->add(Crud::PAGE_EDIT, $fakeSubscription);
    }

    public function fakeSubscription(AdminContext $context){
        $user = $context->getEntity()->getInstance();

        $subscription = new Subscription();
        $defaultStartDate = new \DateTime();
        $defaultEndDate = date_add(new \DateTime(), new \DateInterval("P1M")); // add 1 month
        $subscription->setStartDate($defaultStartDate);
        $subscription->setEndDate($defaultEndDate);
        $subscription->setPrice(20.0);
        $subscription->setPaymentType(Subscription::$PAYMENT_TYPE["manual"]);
        // TODO: generate invoice
        $subscription->setInvoice("null");
        $subscription->setUser($user);

        $form = $this->createFormBuilder($subscription)
            ->add('startDate', DateType::class)
            ->add('endDate', DateType::class)
            ->add('price', MoneyType::class)
            ->add('add', SubmitType::class, ['label' => 'Générer'])
            ->getForm();

        $form->handleRequest($context->getRequest());
        if($form->isSubmitted() && $form->isValid()){
            $subscription = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $user->setIsActive(true);
            $entityManager->persist($subscription);
            $entityManager->flush();

            $this->addFlash('success', 'Abonnement créé pour ' . $user->getUsername());
            return $this->redirect(
                $this->get(CrudUrlGenerator::class)->build()
                    ->setAction(Action::EDIT)->setEntityId($user->getId())->generateUrl()
            );
        }

        return $this->render('backoffice/user/fake_subscription.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
