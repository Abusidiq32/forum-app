<?php

use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\get;

it('should return the correct component', function(){
    get(route('index.route'))
        ->asserInertia(fn (AssertableInertia $inertia) => $inertia
          ->component('Post/Index', true)  
        );
});


it('passes posts to view', function(){
    get(route('index.route'))
        ->asserInertia(fn (AssertableInertia $inertia) => $inertia
        ->has('posts')        
);
});