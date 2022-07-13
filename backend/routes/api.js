var express = require('express');
var router = express.Router();

var ApiController = require('../Controllers/apiController');

router.get('/equipos/:grupo', ApiController.lista_equipos_grupo);


module.exports = router;
