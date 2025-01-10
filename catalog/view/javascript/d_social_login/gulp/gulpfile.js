var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var browserSync = require("browser-sync");
var path = require("path");
if (typeof process.env.HOST === "undefined") {
	process.env.HOST = 'localhost';
}

var codename = 'd_social_login';
var baseDir = path.resolve(__dirname, "../../../../../");
var themeDir = baseDir + '/catalog/view/theme/default';
var sassDest = themeDir + '/stylesheet/' + codename;
gulp.task('sass', function () {
	return gulp.src(sassDest + '/styles.scss')
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
		.pipe(autoprefixer({
			browsers: ['last 15 versions']
		}))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest(sassDest))
		.pipe(browserSync.reload({stream: true}));
});

gulp.task('sass:watch', function () {
	gulp.watch([sassDest + '*.scss', sassDest + '**/*.scss'], ['sass']);
});
gulp.task("browser_sync_init", function () {
	browserSync({
		proxy: process.env.HOST
	});
});
gulp.task('default', ["browser_sync_init"], function () {
	gulp.watch([
		baseDir + "/controller/extension/**/**/*.php",
		themeDir + "/template/extension/**/**/*.vue",
		themeDir + "/template/extension/**/**/*.twig"
	], browserSync.reload);
	gulp.start(["sass", "sass:watch"]);
});