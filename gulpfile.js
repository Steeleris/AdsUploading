var gulp = require('gulp'),
    compass = require('gulp-compass'),
    concat = require('gulp-concat'),
    autoprefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    jshint = require('gulp-jshint'),
    clean = require('gulp-clean'),
    rename = require('gulp-rename'),
    cleancss = require('gulp-clean-css'),
    copy = require('gulp-contrib-copy'),
    bower = require('gulp-bower'),
    watch = require('gulp-watch'),
    paths = {
        js: './app/Resources/assets/js/*.js',
        sass: './app/Resources/assets/sass/*.scss',
        img: './app/Resources/assets/images/*.*',
        bowerDir: './bower_components'
    };

gulp.task('default', ['jshint', 'compress', 'compass', 'copy', 'icons']);
gulp.task('build', ['compress', 'compass', 'copy', 'icons']);

var concatConfig = require('./app/Resources/assets/js/concat.json');

gulp.task('compass', function() {
    return gulp.src('./app/Resources/assets/sass/layout.scss')
        .pipe(compass({
            css: 'web/Resources/css',
            sass: 'app/Resources/assets/sass',
            image: 'web/assets/images'
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

gulp.task('copy', function() {
    gulp.src(paths.img)
        .pipe(copy())
        .pipe(gulp.dest('web/assets/images'));
});

gulp.task('bower', function() {
    return bower()
        .pipe(gulp.dest(paths.bowerDir))
});

gulp.task('icons', function() {
    return gulp.src(paths.bowerDir + '/font-awesome/fonts/**.*')
        .pipe(gulp.dest('web/assets/fonts'));
});

gulp.task('watch', function () {
    gulp.watch([paths.sass, paths.js], ['jshint', 'compress', 'compass']);
});