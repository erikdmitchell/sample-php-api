// Project configuration
var buildInclude = [
        // include common file types
        '**/*.php',
        '**/*.html',
        '**/*.css',
        '**/*.js',
        '**/*.svg',
        '**/*.ttf',
        '**/*.otf',
        '**/*.eot',
        '**/*.woff',
        '**/*.woff2',
        '**/*.png',
        
        // include specific files and folders
        //'screenshot.png',
        //'readme.txt',

        // exclude files and folders
        '!./composer.json', 
        '!./composer.lock',
        '!./gulpfile.js',
        '!./{node_modules,node_modules/**/*}',
        '!./package.json',
        '!./phpcs.ruleset.xml',
        '!./{sass,sass/**/*}',
        '!./.stylelintrc',
        '!./{vendor,vendor/**/*}',
        '!svn/**'
    ];
    
var phpSrc = [
        '**/*.php', // Include all files    
        '!node_modules/**/*', // Exclude node_modules
        '!vendor/**', // Exclude vendor 
        //'!bootstrap.php', // Exclude bootstrap   
        //'!tests/**', // Exclude tests?        
    ];

var cssInclude = [
        // include css
        '**/*.css',

        // exclude files and folders
        '!**/*.min.css',
        '!node_modules/**/*',
        '!style.css',
        '!inc/css/*',
        '!vendor/**'
    ];
    
var jsInclude = [
        // include js
        '**/*.js',

        // exclude files and folders
        '!**/*.min.js',
        '!node_modules/**/*',
        '!vendor/**',
        '!**/gulpfile.js',
        '!inc/js/html5shiv.js',
        '!inc/js/respond.js', 
        '!webpack.config.js',
        '!assets/src/**',
        '!assets/build/**',        
    ]; 
    
var jsDest = './assets/js/';
var cssDest = './assets/css/';

// Load plugins
const gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'), // Autoprefixing magic
    babel = require('gulp-babel'),
    minifycss = require('gulp-uglifycss'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    runSequence = require('run-sequence'),
    gulpsass = require('gulp-sass'),
    plugins = require('gulp-load-plugins')({
        camelize: true
    }),
    ignore = require('gulp-ignore'), // Helps with ignoring files and directories in our run tasks
    plumber = require('gulp-plumber'), // Helps prevent stream crashing on errors
    sourcemaps = require('gulp-sourcemaps'),
    jshint = require('gulp-jshint'), // JSHint plugin
    stylish = require('jshint-stylish'), // JSHint Stylish plugin
    stylelint = require('gulp-stylelint'), // stylelint plugin
    gulpphpcs = require('gulp-phpcs'), // Gulp plugin for running PHP Code Sniffer.
    gphpcbf = require('gulp-phpcbf'), // PHP Code Beautifier
    gutil = require('gulp-util'), // gulp util
    gzip = require('gulp-zip');

/**
 * Styles
 */
 
// compile sass
function sass(done) {
    return (
    gulp.src('./assets/css/sass/*.scss')
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(gulpsass({
            errLogToConsole: true,
            outputStyle: 'expanded',
        }))
        .pipe(sourcemaps.write({
            includeContent: false
        }))
        .pipe(sourcemaps.init({
            loadMaps: true
        }))
        .pipe(autoprefixer('last 2 version', '> 1%', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
        .pipe(sourcemaps.write('.'))
        .pipe(plumber.stop())
        .pipe(gulp.dest(cssDest))
        
    );
    done();
}

// minify all css
function mincss(done) {
    return (
    gulp.src(cssInclude)
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sourcemaps.write({
            includeContent: false
        }))
        .pipe(sourcemaps.init({
            loadMaps: true
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(plumber.stop())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(minifycss({
            maxLineLen: 80
        }))
        .pipe(gulp.dest('./'))
        
    );
    done();
}

// css linting with Stylelint.
function lintcss(done) {
    return (
    gulp.src(cssInclude)
        .pipe(stylelint({
            reporters: [
            {formatter: 'string', console: true}
            ]
        }))
    );
    done();
}

/**
 * Scripts
 */

// min all js files
function scripts() {
    return (
        gulp.src(jsInclude)
        .pipe(babel({
            presets: [
                ['@babel/preset-env', {
                    modules: false
            }]
            ]
        }))    
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(uglify())
        .pipe(gulp.dest('./'))
    );
}

// js linting with JSHint.
function lintjs(done) {
    return (
    gulp.src(jsInclude)
        .pipe(jshint())
        .pipe(jshint.reporter(stylish))
    );
    done();
}

/*
 * Misc
*/

// Watch files
function watchFiles() {
    gulp.watch('./sass/**/*', sass);
    //gulp.watch('./js/**/*.js', js);
    // Watch js file and compile them if any codes change
    //watch(['js/*.js'], jsTask);  
}

// gulp zip
function zip(done) {
    return (
    gulp.src(buildInclude)
        .pipe(gzip('sample-api.zip'))
        .pipe(gulp.dest('./../'))
    );
    done();
}

// define complex tasks
const styles = gulp.series(sass, mincss); // Styles task
const js = gulp.series(scripts); // compile and minimize js
const build = gulp.series(styles, scripts, zip); // Package Distributable
const watch = gulp.parallel(styles, scripts, watchFiles); // Watch Task

// export tasks
exports.sass = sass;
exports.mincss = mincss;
exports.lintcss = lintcss;
exports.styles = styles;
exports.js = js;
exports.lintjs = lintjs;
exports.phpcs = phpcs;
exports.phpcbf = phpcbf;
exports.zip = zip;
exports.build = build;
exports.watch = watch;