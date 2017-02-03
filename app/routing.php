<?php
$app->get('/', "blog.controller:homeAction");

$app->post('/messages/add', "message.controller:addAction");
$app->get('/messages/delete/{id}', "message.controller:deleteAction");
$app->get('/messages/edit/{id}', "message.controller:editAction");
$app->get('/messages/new', "message.controller:newAction");
$app->post('/messages/update', "message.controller:updateAction");

$app->post('/users/add', "user.controller:addAction");
$app->get('/users/delete/{id}', "user.controller:deleteAction");
$app->get('/users/edit/{id}', "user.controller:editAction");
$app->get('/users/new', "user.controller:newAction");
$app->post('/users/update', "user.controller:updateAction");