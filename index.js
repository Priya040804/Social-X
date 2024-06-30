var mysql = require("mysql");
var express = require("express");
var bodyParser = require("body-parser");

var con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "project"
});

var app = express();

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.set('view engine', 'ejs');

app.get("/login.html", function (req, res) {
    res.sendFile(__dirname + "/login.html");
});

app.post("/login.html", function (req, res) {
    console.log(req.body);

    var user_id = req.body.user_id;
    var first_name = req.body.first_name;
    // ... Other fields

    con.connect(function (error) {
        if (error) throw error;

        var sql = "INSERT INTO users(user_id, first_name, /... other columns.../) VALUES(?, /... other placeholders .../)";
        con.query(sql, [user_id, first_name /... other variables.../], function (error, result) {
            if (error) throw error;
            res.redirect('/users');
        });
    });
});

app.get('/users', function (req, res) {
    con.connect(function (error) {
        if (error) console.log(error);

        var sql = "SELECT * FROM users";

        con.query(sql, function (error, result) {
            if (error) console.log(error);
            res.render(__dirname + "/users.ejs", { users: result }); // Assuming users.ejs exists
        });
    });
});

app.listen(5501);