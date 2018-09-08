<?php

namespace spec;

use OneSpec\Spec;
use Prophecy\Prophet;
use Xae3Oow5cahz9shahngu\Printer;
use Xae3Oow5cahz9shahngu\Spec as SpecMock;

$spec = Spec::class(SpecMock::class);

$spec->describe("name hashing", function(Spec $spec) {
    $spec->it("should not throw exception if describe name is same in inner describe", function ($expect) {
        $expect(function () {
            $obj = SpecMock::class(\DateTimeZone::class);
            $obj->describe('name hashing', function ($spec) {
                $spec->describe('name hashing', function ($spec) {});
            });
        })->toNotThrowException();
    });

    $spec->it('should not throw exception if test name is same in different describes', function ($expect) {
        $expect(function () {
            $obj = SpecMock::class(\DateTime::class);
            $obj->it('test name', function () {});
            $obj->describe('name hashing', function ($spec) {
                $spec->it('test name', function () {});
            });
        })->toNotThrowException();
    });

});

$spec->describe('test running', function (Spec $spec) {
    $spec->it('should be possible to execute describes with the same name in different specs', function ($expect) {
        $expect(function () {
            $prophet = new Prophet();
            $printer = $prophet->prophesize(Printer::class);
            $obj = SpecMock::class(\Closure::class);
            $obj->describe('name hashing', function () {});
            $obj->runSpecInFile($printer->reveal());
        })->toNotThrowException();
    });

    $spec->it('should be possible to execute tests with the same name in different specs', function ($expect) {
        $expect(function () {
            $prophet = new Prophet();
            $printer = $prophet->prophesize(Printer::class);
            $obj = SpecMock::class(\DateInterval::class);
            $obj->it('test name', function () {});
            $obj->describe('name hashing', function ($spec) {
                $spec->it('test name', function () {});
            });
            $obj->runSpecInFile($printer->reveal());
        })->toNotThrowException();
    });
});
