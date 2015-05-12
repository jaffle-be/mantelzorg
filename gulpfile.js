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
    gulp.src('app/frontend/css/**/*.less', {base:'app/frontend/css'})
        .pipe(plumb())
        .pipe(less())
        .pipe(autoprefixer())
        .pipe(minify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('public/css'));
});

gulp.task('js', function()
{
    gulp.src([
        //other plugins
        'app/components/metisMenu/src/metisMenu.js',
        'app/components/moment/min/moment-with-locales.js',

        'app/frontend/js/plugins/boot.js',
        'app/frontend/js/plugins/viewport.js',
        'app/frontend/js/plugins/organisations.js',
        'app/frontend/js/plugins/locations.js',
        'app/frontend/js/plugins/questionnaires.js',
        'app/frontend/js/plugins/questionnaire-panel.js',
        'app/frontend/js/plugins/questionnaire-saver.js',
        'app/frontend/js/plugins/question-creator.js',
        'app/frontend/js/plugins/choise-creator.js',
        'app/frontend/js/plugins/question-saver.js',
        'app/frontend/js/plugins/bootstrap-datepicker.js',
        'app/frontend/js/plugins/start.js'
    ])
        .pipe(plumb())
        .pipe(concat('master.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/js'));

    gulp.src('app/frontend/js/*.js')
        .pipe(uglify())
        .pipe(rename({suffix:'.min'}))
        .pipe(gulp.dest('public/js'));
});


/**
 * jquery, twitter bootstrap, jquery-ui, modernizr
 */
gulp.task('libs', function()
{
    gulp.src('app/components/modernizr/modernizr.js')
        .pipe(plumb())
        .pipe(uglify())
        .pipe(rename('modernizr.min.js'))
        .pipe(gulp.dest('public/js'));

    gulp.src([
        'app/components/jquery/dist/jquery.min.js',
        'app/components/bootstrap/dist/js/bootstrap.min.js',
    ])
        .pipe(plumb())
        .pipe(gulp.dest('public/js'));

    gulp.src(
        'app/components/bootstrap/dist/fonts/*'
    )
        .pipe(plumb())
        .pipe(gulp.dest('public/fonts'));

    gulp.src([
        'app/components/jquery-ui/ui/core.js',
        'app/components/jquery-ui/ui/widget.js',
        'app/components/jquery-ui/ui/mouse.js',
        'app/components/jquery-ui/ui/position.js',
        'app/components/jquery-ui/ui/sortable.js'
    ])
        .pipe(plumb())
        .pipe(concat('jquery-ui.custom.min.js'))
        .pipe(gulp.dest('public/js'));

});

gulp.task('default', ['watch']);

gulp.task('watch', function()
{
    gulp.watch('app/frontend/css/*.less', ['master']);
    gulp.watch('app/frontend/less/**/*.less', ['master']);
    gulp.watch('app/frontend/js/**/*.js', ['js'])
});
