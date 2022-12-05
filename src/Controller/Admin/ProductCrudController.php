<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
       return [
        TextField::new('name'),
        SlugField::new('slug')->setTargetFieldName('name'),
        ImageField::new('image')
            ->setBasePath('/uploads/products')
            ->setUploadDir('public/uploads/products')
            ->setUploadedFileNamePattern('product-[randomhash].[extension]')
            ->setRequired(false),
        MoneyField::new('price')->setCurrency('MAD'),
        NumberField::new('quantity'),
        AssociationField::new('category'),
        TextField::new('subtitle'),
        TextareaField::new('description'),
      

       ];
    }
    
}
