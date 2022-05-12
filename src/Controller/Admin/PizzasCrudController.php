<?php

namespace App\Controller\Admin;

use App\Entity\Pizzas;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PizzasCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pizzas::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('nom'),
            TextField::new('ingredients'),
            AssociationField::new('menu')
        ];
    }

}
