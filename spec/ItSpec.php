<?php

namespace spec;

use OneSpec\Architect\ClassBuilder;
use OneSpec\Spec;
use Xae3Oow5cahz9shahngu\It;

$spec = Spec::class(It::class);

$spec->before(function (ClassBuilder $obj) {
    $obj->beConstructed('some name', function () {});
});

$spec->describe("getters", function(Spec $spec) {
    $spec->it("should have a getter for the name", function ($expect, ClassBuilder $obj) {
        $expect($obj->getName())->toBeEqualTo('some name');
    });

    $spec->it('should have a getter for the test closure', function ($expect, ClassBuilder $obj) {
        $expect(is_callable($obj->getTest()))->toBeTrue();
    });
});
