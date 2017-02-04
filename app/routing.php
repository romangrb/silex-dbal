<?php
$app->get('/', "blog.controller:homeAction");

$app->post('/messages/add', "message.controller:addAction");
$app->get('/messages/delete/{id}', "message.controller:deleteAction");
$app->get('/messages/edit/{id}', "message.controller:editAction");
$app->get('/messages/new', "message.controller:newAction");
$app->post('/messages/update', "message.controller:updateAction");
