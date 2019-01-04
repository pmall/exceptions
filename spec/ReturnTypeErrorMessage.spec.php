<?php

use Quanta\Exceptions\ReturnTypeErrorMessage;

describe('ReturnTypeErrorMessage', function () {

    describe('->__toString()', function () {

        it('should not fail', function () {

            $test = function () {
                strval(new ReturnTypeErrorMessage('test', 'callable', 1));
            };

            expect($test)->not->toThrow();

        });

    });

});
