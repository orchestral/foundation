var gulp = require('gulp'),
  gutil = require('gulp-util'),
  coffee = require('gulp-coffee'),
  csso = require('gulp-minify-css'),
  less = require('gulp-less'),
  rename = require('gulp-rename'),
  uglify = require('gulp-uglify'),
  underscore = require('underscore'),
  dir;

dir = {
  asset: 'resources/assets',
  bower: 'resources/components',
  web: 'resources/public'
};

// Less
gulp.task('css', function () {
  return gulp.src(dir.asset+'/less/orchestra.less')
    .pipe(less())
    .pipe(csso())
    .pipe(gulp.dest('resources/public/css'));
});

// Coffee
gulp.task('js', function () {
  return gulp.src(dir.asset+'/coffee/orchestra.coffee')
    .pipe(coffee().on('error', gutil.log))
    .pipe(gulp.dest(dir.web+'/js'));
});

// Minify JavaScript
gulp.task('uglify', function () {
  var options = {
    outSourceMaps: false,
    output: {
      max_line_len: 150
    }
  };

  return gulp.src(dir.web+'/js/orchestra.js')
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify(options))
    .pipe(gulp.dest(dir.web+'/js'))
});

gulp.task('copy', function () {
  var copy = [
    [dir.bower+'/bootstrap/dist/**/*', dir.web+'/vendor/bootstrap'],
    [dir.bower+'/perfect-scrollbar/js/*.min.js', dir.web+'/vendor/perfect-scrollbar'],
    [dir.bower+'/perfect-scrollbar/css/*.min.css', dir.web+'/vendor/perfect-scrollbar']
  ];

  underscore.each(copy, function (file) {
    gulp.src(file[0]).pipe(gulp.dest(file[1]));
  });
});

// Add file watch
gulp.task('watch', function () {
  gulp.watch(dir.asset+'/less/orchestra.less', ['css']);
  gulp.watch(dir.asset+'/coffee/orchestra.coffee', ['js']);
  gulp.watch(dir.web+'/css/orchestra.js', ['uglify']);
});

// Default task.
gulp.task('default', ['css', 'copy', 'js', 'uglify']);
