var gulp = require('gulp'),
    compass = require('gulp-compass'),
    concat = require('gulp-concat'),
    autoprefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    jshint = require('gulp-jshint'),
    clean = require('gulp-clean'),
    rename = require('gulp-rename'),
    cleancss = require('gulp-clean-css'),
    watch = require('gulp-watch'),
    paths = {
        js: './app/Resources/assets/js/*.js',
        sass: './app/Resources/assets/sass/*.scss'
    };

gulp.task('default', ['jshint', 'compress', 'compass']);
gulp.task('build', ['compress', 'compass']);

var concatConfig = require('./app/Resources/assets/js/concat.json');

gulp.task('compass', function() {
    return gulp.src('./app/Resources/assets/sass/layout.scss')
        .pipe(compass({
            css: 'web/Resources/css',
            sass: 'app/Resources/assets/sass',
            image: 'web/assets/images',
            fonts: 'web/fonts/bootstrap'
        }))
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 7', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
        .pipe(gulp.dest('web/assets/css'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(cleancss())
        .pipe(gulp.dest('web/assets/css'));
});

gulp.task('compress', function() {
    return gulp.src(concatConfig.dependencies)
        .pipe(concat(concatConfig.fileName))
        .pipe(gulp.dest('web/assets/js'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(uglify())
        .pipe(gulp.dest('web/assets/js'));
});

gulp.task('clean', function () {
    return gulp.src('web/assets', {read: false})
        .pipe(clean());
});

gulp.task('jshint', function() {
    return gulp.src(concatConfig.dependencies)
        .pipe(jshint())
        .pipe(jshint.reporter('jshint-stylish'))
});

gulp.task('watch', function () {
    gulp.watch([paths.sass, paths.js], ['jshint', 'compress', 'compass']);
});