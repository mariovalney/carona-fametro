var gulp = require('gulp');

// Plugins
var addSrc = require('gulp-add-src');
var autoprefixer = require('gulp-autoprefixer');
var browserSync = require('browser-sync').create();
var concat = require('gulp-concat');
var iife = require('gulp-iife');
var minifyCSS = require('gulp-minify-css');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var uglifyEs = require('gulp-uglify-es').default;
var watch = require('gulp-watch');

// Directories
var theme_dir = 'themes/app',
    theme_source_dir = theme_dir + '/source/',
    theme_dist_dir = theme_dir + '/dist/';

/**
 * TASK: styles
 *
 * Proccess LESS to MAIN CSS
 * Prepare for DIST all CSS needed
 */

function scss_to_css() {
    return gulp.src(theme_source_dir + 'scss/styles.scss')
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(rename('scss.css'))
    .pipe(autoprefixer())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(theme_dist_dir + 'css'))
    .pipe(browserSync.stream({stream: true}));
}

function css_to_dist() {
    return gulp.src([
        theme_source_dir + 'css/vendor/bootstrap.css',
        theme_source_dir + 'css/vendor/slick.css',
        theme_source_dir + 'css/vendor/slick-theme.css',
        theme_source_dir + 'css/vendor/helpers.css',
        theme_source_dir + 'css/vendor/style.css',
        theme_source_dir + 'css/vendor/landing-2.css',
        theme_dist_dir + 'css/scss.css',
    ])
    .pipe(concat('styles.css'))
    .pipe(gulp.dest(theme_dist_dir + 'css'))
    .pipe(browserSync.stream({stream: true}));
}

gulp.task('styles', gulp.series(scss_to_css, css_to_dist));

/**
 * TASK: scripts
 *
 * Proccess LESS to MAIN JS
 * Prepare for DIST all JS needed
 */

var js_vendor_files = [
    'node_modules/jquery/dist/jquery.js',
    'node_modules/jquery-match-height/dist/jquery.matchHeight.js',
    'node_modules/sweetalert/dist/sweetalert.min.js',
    'node_modules/jquery-mask-plugin/dist/jquery.mask.js',
    'node_modules/jquery-validation/dist/jquery.validate.min.js',
    'node_modules/underscore/underscore.js',
    theme_source_dir + 'js/vendor/popper.min.js',
    theme_source_dir + 'js/vendor/bootstrap.min.js',
    theme_source_dir + 'js/vendor/*.js',
    theme_source_dir + 'js/landing.js',
];

var js_files = [
    theme_source_dir + 'js/**/*.js',
    '!' + theme_source_dir + 'js/vendor/*.js',
    '!' + theme_source_dir + 'js/ready.js',
    theme_source_dir + 'js/ready.js',
];

function js_to_dist() {
    return gulp.src(js_files)
        .pipe(concat('scripts.js'))
        .pipe(iife({
            prependSemicolon: false,
            params: ['window', 'document', '$'],
            args: ['window', 'document', '$']
        }))
        .pipe(addSrc.prepend(js_vendor_files))
        .pipe(concat('scripts.js'))
        .pipe(gulp.dest(theme_dist_dir + 'js'))
        .pipe(browserSync.reload({stream: true}));
}

gulp.task('scripts', js_to_dist);

/**
 * TASK: watch
 *
 * Keep watching for changes in directories to automate tasks
 */

function watch_changes() {
    browserSync.init({
        open: false,
        port: 8080,
        host: 'dev.caronafametro.mariovalney.com',
        proxy: 'dev.caronafametro.mariovalney.com',
        notify: false,
    });

    gulp.watch(theme_source_dir + 'css/**/*.css', gulp.series('styles'));
    gulp.watch(theme_source_dir + 'scss/**/*.scss', gulp.series('styles'));
    gulp.watch(theme_source_dir + 'js/**/*.js', gulp.series('scripts'));
}

gulp.task('watch', watch_changes);

/**
 * TASK: default
 * Create all things to distribute and deploy
 */

function styles_to_deploy() {
    return gulp.src(theme_dist_dir + 'css/styles.css')
        .pipe(rename('styles.min.css'))
        .pipe(minifyCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest(theme_dist_dir + 'css'));
}

function scripts_to_deploy() {
    return gulp.src(theme_dist_dir + 'js/scripts.js')
        .pipe(rename('scripts.min.js'))
        .pipe(uglifyEs())
        .pipe(gulp.dest(theme_dist_dir + 'js'));
}

gulp.task('default', gulp.parallel(
    gulp.series('styles', styles_to_deploy),
    gulp.series('scripts', scripts_to_deploy)
) );