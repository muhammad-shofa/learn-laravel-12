import "./bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "admin-lte/dist/css/adminlte.min.css";
import "admin-lte/dist/js/adminlte.min.js";
import "@fortawesome/fontawesome-free/css/all.min.css";
import "./auth";

$(window).on("load", function () {
    $("#preloader").fadeOut(500);
});
