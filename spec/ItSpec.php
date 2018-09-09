<?php

namespace spec;

use OneSpec\Spec;
use Xae3Oow5cahz9shahngu\It;

$spec = Spec::class(It::class);

$spec->describe("getters", function(Spec $spec) {
    $spec->it("should have a getter for the name", function ($expect) {
        $it = new It('some name', function () {});
        $expect($it->getName())->toBeEqualTo('some name');
    });

    $spec->it('should have a getter for the test closure', function ($expect) {
        $it = new It('some name', function () {});
        $expect(is_callable($it->getTest()))->toBeTrue();
    });
});
