var express = require('express');
var path = require('path');
var cookieParser = require('cookie-parser');
const session = require('express-session');
const MySQLStore = require('express-mysql-session')(session);
var logger = require('morgan');
var bodyParser = require('body-parser');
var apiRouter = require('./routes/api');

var options = {
    host: '127.0.0.1',
    user: 'root',
    password: '',
    database: 'ozeretski',
    createDatabaseTable: true,
    charset: 'utf8mb4_bin',
    schema: {
        tableName: 'sessions',
        columnNames: {
            session_id: 'session_id',
            expires: 'expires',
            data: 'data'
        }
    }
};
var sessionStore = new MySQLStore(options);

var app = express();

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, './views'));

app.use(logger('dev'));
app.use(express.json({limit: '50mb'}));
app.use(express.urlencoded({limit: '50mb'}));
app.use(cookieParser());
app.use(session({
  name: 'sid',
  resave: false,
  saveUninitialized: false,
  secret: 'ssh!quiet,it\'asecret!',
  store: sessionStore
}));
app.use(express.static(path.join(__dirname, 'public')));

app.use('/api/v1', apiRouter);




// catch 404 and forward to error handler
app.use(function(req, res, next) {
  //next(createError(404));
  res.render('es/error-404');
});


// error handler
app.use(function(err, req, res, next) {
  // render the error page
  res.status(err.status || 500);
  console.log(err);
  res.render('error');
});

app.use(bodyParser.json({
  limit: '50mb'
}));

app.use(bodyParser.urlencoded({
  limit: '50mb',
  parameterLimit: 100000,
  extended: true
}));
module.exports = app;
