<?php

$app->get('/person/all', "app.controller:getAll");

$app->get('/person/{num}', "app.controller:getPage");

$app->post('/person/add', "app.controller:addPerson");

$app->post('/person/upload_pictures', "fs.controller:addPictures");

$app->post('/person/download', "fs.controller:getPictures");

$app->post('/archives/upload', "fs.controller:saveAndUnpackFiles");

$app->post('/test', "fs.controller:test");