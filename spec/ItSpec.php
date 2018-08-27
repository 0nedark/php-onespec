<?php

namespace Stable;

use OneSpec\It;
use OneSpec\Stable\Architect\ClassBuilder;
use OneSpec\Stable\Spec;

$spec = Spec::class(__FILE__,It::class);

$spec->before(function (ClassBuilder $obj) {
    $obj->beConstructedWith('some name', function () {});
});

$spec->describe("getters", function(Spec $spec) {
    $spec->it("should have a getter for the name", function ($expect, ClassBuilder $obj) {
        $expect($obj->getName())->toBeEqualTo('some name');
    });

    $spec->it('should have a getter for the test closure', function ($expect, ClassBuilder $obj) {
        $expect(is_callable($obj->getTest()))->toBeTrue();
    });
});
