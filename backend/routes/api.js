var express = require('express');
var router = express.Router();

var ApiController = require('../Controllers/apiController');

router.get('/hola1', ApiController.prueba);


module.exports = router;
