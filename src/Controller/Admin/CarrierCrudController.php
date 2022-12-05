<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

    
    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         TextField::new('Carrier Name'),
    //         TextEditorField::new('Description'),
    //         MoneyField::new('Price')->setCurrency('MAD'),
    //     ];
    // }
    
}
