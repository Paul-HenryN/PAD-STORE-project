<?php

namespace App\Controller\Admin;

use App\Entity\Budget;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class BudgetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Budget::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('position')->setRequired(true),
            AssociationField::new('category')->setRequired(true),
            MoneyField::new('amount')->setCurrency('XAF')->setRequired(true)->setStoredAsCents(false),
        ];
    }
}
