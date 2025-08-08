<?php

use App\Models\Product;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('my.products', function (BreadcrumbTrail $trail) {
    $trail->push('Мои товары', route('my.products'));
});

Breadcrumbs::for('my.products.create', function (BreadcrumbTrail $trail) {
    $trail->parent('my.products');
    $trail->push('Новый товар');
});

Breadcrumbs::for('my.products.show', function (BreadcrumbTrail $trail, Product $product) {
    $trail->parent('my.products');
    $trail->push($product->name, route('my.products.show', $product->id));
});

Breadcrumbs::for('my.products.edit', function (BreadcrumbTrail $trail, Product $product) {
    $trail->parent('my.products.show', $product);
    $trail->push('Редактирование товара №' . $product->id);
});
