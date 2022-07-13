'use strict'
const dbConnection = require('../config/dbConnection');
const connection = dbConnection();
var moment = require('moment');
const fs=require('fs-extra');

class ApiController {


  async lista_equipos_grupo(req, res) {
    const {grupo} = req.params;

    connection.query('SELECT a.NOM_EQUIPO_CORTO, a.NOM_EQUIPO_LARGO, a.ESCUDO_EQUIPO, b.GRUPO FROM equipotorneo as b INNER JOIN equipo as a ON a.id_equipo=b.ID_EQUIPO WHERE GRUPO=?', [grupo], (err, result) => {
      if (err) res.send(err);
      if (result.length <= 0) {
        res.send(result);
        console.log("este es el resultado"+result);
      } else {
        res.send(result);
      }
    });
  }


}


var apiController = new ApiController();
module.exports = apiController
