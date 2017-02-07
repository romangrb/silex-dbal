<?php

$app->get('/manga/all', "app.controller:getAll");

$app->get('/manga/{num}', "app.controller:getPage");

$app->post('/manga/add', "app.controller:addPerson");
