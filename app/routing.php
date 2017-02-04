<?php
$app->get('/', "app.controller:homeAction");

$app->post('/messages/add', "crud.controller:addAction");
$app->get('/messages/delete/{id}', "crud.controller:deleteAction");
$app->get('/messages/edit/{id}', "crud.controller:editAction");
$app->get('/messages/new', "crud.controller:newAction");
$app->post('/messages/update', "crud.controller:updateAction");
