<?php

use Quanta\Exceptions\ArrayArgumentTypeErrorMessage;

describe('ArrayArgumentTypeErrorMessage', function () {

    describe('->__toString()', function () {

        it('should not fail', function () {

            $test = function () {
                strval(new ArrayArgumentTypeErrorMessage(1, 'callable', [1]));
            };

            expect($test)->not->toThrow();

        });

    });

});
