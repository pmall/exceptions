<?php

use function Quanta\Exceptions\areAllTypedAs;

describe('areAllTypedAs', function () {

    context('when the type is an interface name', function () {

        beforeAll(function () {
            interface AreAllTypedAsTestInterface {}
        });

        context('when all the values of the given array implement the interface with the given name', function () {

            it('should return true', function () {

                $test = areAllTypedAs(AreAllTypedAsTestInterface::class, [
                    new class implements AreAllTypedAsTestInterface {},
                    new class implements AreAllTypedAsTestInterface {},
                    new class implements AreAllTypedAsTestInterface {},
                ]);

                expect($test)->toBeTruthy();

            });

        });

        context('when a value of the given array does not implement the interface with the given name', function () {

            it('should return false', function () {

                $test = areAllTypedAs(AreAllTypedAsTestInterface::class, [
                    new class implements AreAllTypedAsTestInterface {},
                    new class {},
                    new class implements AreAllTypedAsTestInterface {},
                ]);

                expect($test)->toBeFalsy();

            });

        });

    });

    context('when the type is a class name', function () {

        beforeAll(function () {
            abstract class AreAllTypedAsTestClass {}
        });

        context('when all the values of the given array are instances of the class with the given name', function () {

            it('should return true', function () {

                $test = areAllTypedAs(AreAllTypedAsTestClass::class, [
                    new class extends AreAllTypedAsTestClass {},
                    new class extends AreAllTypedAsTestClass {},
                    new class extends AreAllTypedAsTestClass {},
                ]);

                expect($test)->toBeTruthy();

            });

        });

        context('when a value of the given array is not an instance of the class with the given name', function () {

            it('should return false', function () {

                $test = areAllTypedAs(AreAllTypedAsTestClass::class, [
                    new class extends AreAllTypedAsTestClass {},
                    new class {},
                    new class extends AreAllTypedAsTestClass {},
                ]);

                expect($test)->toBeFalsy();

            });

        });

    });

    context('when the type is callable', function () {

        context('when all the values of the given array are callable values', function () {

            it('should return true', function () {

                $test = areAllTypedAs('callable', [
                    function () {},
                    function () {},
                    function () {},
                ]);

                expect($test)->toBeTruthy();

            });

        });

        context('when a value of the given array is not a callable value', function () {

            it('should return false', function () {

                $test = areAllTypedAs('callable', [
                    function () {},
                    'value',
                    function () {},
                ]);

                expect($test)->toBeFalsy();

            });

        });

    });

    context('when the type is a regular type', function () {

        context('when all the values of the given array are typed as the given type', function () {

            it('should return true', function () {

                $map = [
                    'boolean' => [true, false, true],
                    'integer' => [1, 2, 3],
                    'double' => [1.1, 2.1, 3.1],
                    'string' => ['v1', 'v2', 'v3'],
                    'array' => [[1], [2], [3]],
                    'object' => [new class {}, new class {}, new class {}],
                    'resource' => [tmpfile(), tmpfile(), tmpfile()],
                    'null' => [null, null, null],
                ];

                foreach ($map as $type => $values) {

                    $test = areAllTypedAs($type, $values);

                    expect($test)->toBeTruthy();

                }

            });

        });

        context('when a value of the given array is not typed as the given type', function () {

            it('should return false', function () {

                $test = areAllTypedAs('integer', [1, [2], 3]);

                expect($test)->toBeFalsy();

            });

        });

    });

});
