var gulp   = require('gulp');
var $      = require('gulp-load-plugins')();
var argv = require('yargs').argv;

// Check for --production flag
var isProduction = !!(argv.production);

// Define base paths for Sass and Javascript.
var sassPaths = [
    'scss/',
    'bower_components/foundation/scss',
    // 'bower_components/motion-ui/src',
    // 'bower_components/slicknav/scss',
    // 'bower_components/highlightjs/styles'
];

var javascriptFiles = [
    'bower_components/jquery/dist/jquery.js'
];

// Set up 'sass' task.
gulp.task('sass', function() {

  var minifycss = $.if(isProduction, $.minifyCss());

  return gulp.src('scss/bolt.scss')
    .pipe($.sass({
      includePaths: sassPaths,
      outputStyle: 'nested' // 'compressed' or 'nested'
    })
      .on('error', $.sass.logError))
    .pipe($.autoprefixer({
      browsers: ['last 2 versions', 'ie >= 9']
    }))
    .pipe(minifycss)
    .pipe($.if(!isProduction, $.sourcemaps.write()))
    .pipe(gulp.dest('../styles'));
});


// Set up 'compress' task.
gulp.task('compress', function() {

  var uglifyjs = $.if(isProduction, $.uglify());

  return gulp.src('js/*.js')
    .pipe($.concat('site.js'))
    .pipe(uglifyjs)
    .pipe(gulp.dest('../js'));
});


gulp.task('copyjavascript', function() {
   gulp.src(javascriptFiles)
   .pipe($.uglify())
   .pipe(gulp.dest('../js'));
});

// Build the "dist" folder by running all of the above tasks
gulp.task('build', ['sass', 'copyjavascript', 'compress']);

// Set up 'default' task, with watches.
gulp.task('default', ['sass', 'copyjavascript', 'compress'], function() {
  gulp.watch(['scss/**/*.scss'], ['sass']);
  gulp.watch(['javascript/**/*.js'], ['compress']);
});