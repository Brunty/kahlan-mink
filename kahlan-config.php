<?php

\Brunty\Kahlan\Mink\Mink::register($this);
\Brunty\Kahlan\Mink\PhpServer::register($this);

\Kahlan\box('app.url', 'http://localhost:8888');
\Kahlan\box('brunty.kahlan-mink.base-url', 'http://localhost:8888');
