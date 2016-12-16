var gulp = require('gulp'),
    less = require("gulp-less");

// task
gulp.task('less', function () {
    gulp.src([
        './public/less/style.less'
    ]) // path to your file
    .pipe(less())
    .pipe(gulp.dest('./public/css'));
});

gulp.task('watch', function () {
    gulp.watch(['./public/less/**/*.less'], ['less']);
});
