<?php

namespace App\UClass;
use App\Entity\Category;

class Search{

    /**
     * @var string
     */
    public $searchTerm='';
    
    
    /**
     * @var Category[]
     */
    public $searchCategories = [];
}
?>