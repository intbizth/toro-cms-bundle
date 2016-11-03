var gulp = require('gulp');
var gulpif = require('gulp-if');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var debug = require('gulp-debug');
var order = require('gulp-order');
var merge = require('merge-stream');
var gutil = require('gulp-util');

var env = gutil.env.env;
var stage = gutil.env.stage;

var rootPath = '../../../web/assets/cms/';
var nodePath = '../../../node_modules/';

if ('dev' === stage) {
    rootPath = "../" + rootPath;
    nodePath = "../" + nodePath;
}

var paths = {
    js: [
        nodePath + 'codemirror/lib/codemirror.js',
        nodePath + 'codemirror/mode/twig/twig.js',
        nodePath + 'codemirror/mode/xml/xml.js',
        nodePath + 'codemirror/mode/yaml/yaml.js',
        nodePath + 'codemirror/mode/css/css.js',
        nodePath + 'codemirror/mode/javascript/javascript.js',
        'Resources/private/js/partial/**',
        'Resources/private/js/app.js'
    ],
    sass: [
        'Resources/private/sass/**'
    ],
    css: [
        nodePath + 'codemirror/lib/codemirror.css'
    ],
    copy: [
        ['images', 'Resources/private/images/**']
    ]
};

gulp.task('script', function () {
    return gulp.src(paths.js)
        .pipe(concat('app.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(rootPath + 'js/'));
});

gulp.task('style', function () {
    var cssStream = gulp.src(paths.css)
            .pipe(concat('css-files.css'))
        ;

    var sassStream = gulp.src(paths.sass)
            .pipe(sass())
            .pipe(concat('sass-files.scss'))
        ;

    return merge(cssStream, sassStream)
        .pipe(order(['css-files.css', 'sass-files.scss']))
        .pipe(concat('style.css'))
        .pipe(gulpif(env === 'prod', uglifycss()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(rootPath + 'css/'))
        ;
});

gulp.task('copy', function () {
    for (var i = 0; i < paths.copy.length; i++) {
        var copy = paths.copy[i];
        gulp.src(copy[1]).pipe(gulp.dest(rootPath + copy[0]));
    }
});

gulp.task('watch', function () {
    gulp.watch(paths.js, ['script']);
    gulp.watch(paths.sass, ['style']);
    gulp.watch(paths.css, ['style']);
});

gulp.task('default', ['script', 'style', 'copy']);
