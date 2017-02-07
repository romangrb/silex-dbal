<?php

$app->get('/manga', "app.controller:getAll");

$app->get('/manga/top', "app.controller:getTop");
$app->get('/manga/page/{num}', "app.controller:getPage");

$app->post('/manga/add', "app.controller:addPerson");
// $app->get('/messages/delete/{id}', "crud.controller:deleteAction");
// $app->get('/messages/edit/{id}', "crud.controller:editAction");
// $app->get('/messages/new', "crud.controller:newAction");
// $app->post('/messages/update', "crud.controller:updateAction");
