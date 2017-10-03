<?php

use function Brunty\Kahlan\Mink\browser;
use function Brunty\Kahlan\Mink\element;
use function Brunty\Kahlan\Mink\url;
use function Kahlan\box;

describe('Mink instance', function () {
    it('browses the site booted via the PhpServer by specifying the URL', function () {
        browser()->visit(box('app.url'));
        expect(element('#content p')->getText())->toContain('Hello world!');
    });

    it('browses the site booted via the PhpServer by specifying the URL', function () {
        browser()->visit(url('/alternative-page.php'));
        expect(element('#content p')->getText())->toContain('Alternative page!');
    });
});
