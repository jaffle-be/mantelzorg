/**
 * boostrapping
 */
var gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    minify = require('gulp-minify-css'),
    less = require('gulp-less'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename');

/**
 * Twitter Bootstrap related tasks
 */

gulp.task('master', function()
{
    //copy the assets


    var master = gulp.src('app/frontend/css/master.less')
        .pipe(less())
        .pipe(autoprefixer())
        .pipe(minify())
        .pipe(rename('master.min.css'))
        .pipe(gulp.dest('public/css'));

    var users = gulp.src('app/frontend/css/users.less')
        .pipe(less())
        .pipe(autoprefixer())
        .pipe(minify())
        .pipe(rename('users.min.css'))
        .pipe(gulp.dest('public/css'));

    var beta = gulp.src('app/frontend/css/beta.less')
        .pipe(less())
        .pipe(autoprefixer())
        .pipe(minify())
        .pipe(rename('beta.min.css'))
        .pipe(gulp.dest('public/css'));
});
