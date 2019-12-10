<?php
// Home
Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Teknoloji Haberleri', route('home'));
});

// Home > [Category]
Breadcrumbs::register('category', function ($breadcrumbs, $category) {
    $category = \Radkod\Posts\Models\Category::find($category);
    $breadcrumbs->parent('home');
    if ($category){
        foreach ($category->parent as $item){
            $parent = \Radkod\Posts\Models\Category::find($item->parent_id);
            if($parent != null){
                $breadcrumbs->push($parent->title, route('show_category', str_slug($parent->title) . '-' . $parent->id));
            }
            $breadcrumbs->push($item->title, route('show_category', str_slug($item->title) . '-' . $item->id));
        }
        $breadcrumbs->push($category->title, route('show_category', str_slug($category->title) . '-' . $category->id));
    }

});

// Home  > [Category] > [Post]
Breadcrumbs::register('post', function ($breadcrumbs, $post) {
    $post = \Radkod\Posts\Models\Posts::find($post);
    $breadcrumbs->parent('category', $post->category);
    $breadcrumbs->push($post->title, route('show_post', str_slug($post->title). '-' . $post->id));
});