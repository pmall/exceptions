<?php

use Quanta\Exceptions\ArrayReturnTypeErrorMessage;

describe('ArrayReturnTypeErrorMessage', function () {

    describe('->__toString()', function () {

        it('should not fail', function () {

            $test = function () {
                strval(new ArrayReturnTypeErrorMessage('test', 'callable', [1]));
            };

            expect($test)->not->toThrow();

        });

    });

});
