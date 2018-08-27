<?php

namespace OneSpec;

use OneSpec\Architect\ClassBuilder;

$spec = Spec::class(__FILE__,\DateTime::class);

$spec->before(function (ClassBuilder $obj) {
    $obj->beConstructedWith("now", new \DateTimeZone("UTC"));
});

$spec->describe("aasd ad w f naskjd askn kfkl abf askna snaskd naskd lkajsdn kasnd kafk as nad nakfn alkf akls dnaksdn and kasjd kjaaksdn klasdb klasd kasbdj absdjab kajsbd jasuay dbuad uayvdauysdvquwqu vfuavsd vasd vasd uadv quydvsav kasdv uydwv uasvd hasvd uyv uasvd asvd vuwqdv aksdv uqv", function(Spec $spec) {
    $spec->it("asd", function ($expect, ClassBuilder $obj) {
        sleep(1);
        $expect($obj->getTimezone()->getName())->toBeEqualTo("Europe/London");
    });

    $spec->describe("qwe", function(Spec $spec) {
        $spec->it("b", function ($expect, ClassBuilder $obj) {
            $obj->beConstructedWith("now", new \DateTimeZone("Europe/London"));
            $expect($obj->getTimezone()->getName())->toNotBeEqualTo("Europe/London");
        });
    });

    $spec->it("should have a UTC timezone", function ($expect, ClassBuilder $obj) {
        $expect($obj->getTimezone()->getName())->BeEqualTo("UTC");
    });
});

$spec->it("d", function ($expect, ClassBuilder $obj) {
    $obj->setTimezone(new \DateTimeZone('ASD'));
    $expect($obj->getTimezone()->getName())->toBeEqualTo("ASD");
});
