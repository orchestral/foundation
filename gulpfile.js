var gulp = require('gulp'),
    gutil = require('gulp-util'),
    coffee = require('gulp-coffee'),
    csso = require('gulp-minify-css'),
    less = require('gulp-less'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify');

// Less
gulp.task('css', function () {
    return gulp.src('resources/assets/less/orchestra.less')
        .pipe(less())
        .pipe(csso())
        .pipe(gulp.dest('public/css'));
});

// Coffee
gulp.task('js', function () {
    return gulp.src('resources/assets/coffee/orchestra.coffee')
        .pipe(coffee().on('error', gutil.log))
        .pipe(gulp.dest('public/js'));
});

// Minify JavaScript
gulp.task('uglify', function () {
    var options = {
        outSourceMaps: false,
        output: {
            max_line_len: 150
        }
    };

    return gulp.src('public/js/orchestra.js')
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify(options))
        .pipe(gulp.dest('public/js'))
});

// Add file watch
gulp.task('watch', function () {
    gulp.watch('resources/assets/less/orchestra.less', ['css']);
    gulp.watch('resources/assets/coffee/orchestra.coffee', ['js']);
    gulp.watch('public/css/orchestra.js', ['uglify']);
});

// Default task.
gulp.task('default', ['css', 'js', 'uglify', 'watch']);

// Run default without watch.
gulp.task('run', ['css', 'js', 'uglify']);
