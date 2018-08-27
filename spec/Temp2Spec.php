<?php

namespace OneSpec;

use OneSpec\Architect\ClassBuilder;

$spec = Spec::class(__FILE__,\DateTime::class);

$spec->before(function (ClassBuilder $obj) {
    $obj->beConstructedWith("now", new \DateTimeZone("UTC"));
});

$spec->describe("some A", function(Spec $spec) {
    $spec->it("aasd ad w f naskjd askn kfkl abf askna snaskd naskd lkajsdn kasnd kafk as nad nakfn alkf akls dnaksdn and kasjd kjaaksdn klasdb klasd kasbdj absdjab kajsbd jasuay dbuad uayvdauysdvquwqu vfuavsd vasd vasd uadv quydvsav kasdv uydwv uasvd hasvd uyv uasvd asvd vuwqdv aksdv uqv", function ($expect, ClassBuilder $obj) {
        $expect(true)->toBeFalse();
    });
});
