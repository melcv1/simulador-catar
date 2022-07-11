'use strict'
const dbConnection = require('../config/dbConnection');
const connection = dbConnection();
var moment = require('moment');
const fs=require('fs-extra');

class ApiController {


  async prueba(req,res){
    res.send("HOLA")

  }

}


var apiController = new ApiController();
module.exports = apiController
