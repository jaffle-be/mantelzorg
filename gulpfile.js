/**
 * boostrapping
 */
var gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    minify = require('gulp-minify-css'),
    less = require('gulp-less'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    plumb = require('gulp-plumber');

/**
 * Master css file
 */

gulp.task('master', function()
{
    gulp.src('resources/assets/css/**/*.less', {base:'resources/assets/css'})
        .pipe(plumb())
        .pipe(less())
        //.pipe(autoprefixer())
        .pipe(minify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('public/css'));
});

gulp.task('js', function()
{
    gulp.src([
        //other plugins
        'resources/assets/plugins/metisMenu/dist/metisMenu.js',
        'resources/assets/plugins/moment/min/moment-with-locales.js',

        'resources/assets/js/plugins/boot.js',
        'resources/assets/js/plugins/viewport.js',
        'resources/assets/js/plugins/organisations.js',
        'resources/assets/js/plugins/locations.js',
        'resources/assets/js/plugins/questionnaires.js',
        'resources/assets/js/plugins/questionnaire-panel.js',
        'resources/assets/js/plugins/questionnaire-saver.js',
        'resources/assets/js/plugins/question-creator.js',
        'resources/assets/js/plugins/choise-creator.js',
        'resources/assets/js/plugins/question-saver.js',
        'resources/assets/js/plugins/bootstrap-datepicker.js',
        'resources/assets/js/plugins/start.js'
    ])
        .pipe(plumb())
        .pipe(concat('master.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/js'));

    gulp.src('resources/assets/js/*.js')
        .pipe(uglify())
        .pipe(rename({suffix:'.min'}))
        .pipe(gulp.dest('public/js'));
});


/**
 * jquery, twitter bootstrap, jquery-ui, modernizr
 */
gulp.task('libs', function()
{
    gulp.src('resources/assets/plugins/modernizr/modernizr.js')
        .pipe(plumb())
        .pipe(uglify())
        .pipe(rename('modernizr.min.js'))
        .pipe(gulp.dest('public/js'));

    gulp.src([
        'resources/assets/plugins/jquery/dist/jquery.min.js',
        'resources/assets/plugins/bootstrap/dist/js/bootstrap.min.js',
        'resources/assets/plugins/bootstrap-material-design/dist/js/material.min.js',
        'resources/assets/plugins/bootstrap-material-design/dist/js/ripples.min.js',
    ])
        .pipe(plumb())
        .pipe(gulp.dest('public/js'));

    gulp.src([
        'resources/assets/plugins/bootstrap/dist/fonts/*',
        'resources/assets/plugins/bootstrap-material-design/dist/fonts/*'
    ])
        .pipe(plumb())
        .pipe(gulp.dest('public/fonts'));

    gulp.src([
        'resources/assets/plugins/jquery-ui/ui/core.js',
        'resources/assets/plugins/jquery-ui/ui/widget.js',
        'resources/assets/plugins/jquery-ui/ui/mouse.js',
        'resources/assets/plugins/jquery-ui/ui/position.js',
        'resources/assets/plugins/jquery-ui/ui/sortable.js'
    ])
        .pipe(plumb())
        .pipe(concat('jquery-ui.custom.min.js'))
        .pipe(gulp.dest('public/js'));

});

gulp.task('default', ['watch']);
gulp.task('compile', ['master', 'js', 'libs']);

gulp.task('watch', function()
{
    gulp.watch('resources/assets/css/*.less', ['master']);
    gulp.watch('resources/assets/less/**/*.less', ['master']);
    gulp.watch('resources/assets/js/**/*.js', ['js'])
});
