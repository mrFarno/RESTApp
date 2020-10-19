<?php

$renderer->header()
            ->open_body()
            ->coucou($USER)
            ->close_body()
            ->footer()
            ->render();