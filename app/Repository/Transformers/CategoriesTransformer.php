<?php

namespace App\Repository\Transformers;

use function App\checkImage;

class CategoriesTransformer extends Transformer{
    public function transform($category){
        return $category;
    }
}