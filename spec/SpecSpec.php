<?php

namespace spec;

use OneSpec\Spec;
use Xae3Oow5cahz9shahngu\Spec as Mock;

$spec = Spec::class(__FILE__,Mock::class);

$spec->describe("name hashing", function(Spec $spec) {
    $spec->it("should not throw exception if describe name is same in inner describe", function ($expect) {
        $expect(function () {
            $obj = Mock::class('file A', \DateTimeZone::class);
            $obj->describe('name hashing', function ($spec) {
                $spec->describe('name hashing', function ($spec) {});
            });
        })->toNotThrowException();
    });

    $spec->it('should not throw exception if test name is same in different describes', function ($expect) {
        $expect(function () {
            $obj = Mock::class('file B', \DateTimeZone::class);
            $obj->it('test name', function () {});
            $obj->describe('name hashing', function ($spec) {
                $spec->it('test name', function () {});
            });
        })->toNotThrowException();
    });
});
