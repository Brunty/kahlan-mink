<?php

use function Brunty\Kahlan\Mink\browser;
use function Brunty\Kahlan\Mink\element;
use function Kahlan\box;

describe('Mink instance', function () {
    it('browses the site booted via the PhpServer', function () {
        browser()->visit(box('app.url'));
        expect(element('#content p')->getText())->toContain('Hello world!');
    });
});
