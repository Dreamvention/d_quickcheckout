/*jslint node: true */
"use strict";

var gulp = require("gulp");
var sass = require("gulp-sass");
var sourcemaps = require("gulp-sourcemaps");
var autoprefixer = require("gulp-autoprefixer");
var browserSync = require("browser-sync");
var path = require("path");
var fs = require('fs');

//script paths
var sassDest = "../../../../view/stylesheet/d_admin_style";
var style_folders = sassDest+'/themes/';
var baseDir = path.resolve(__dirname, "../../../../");

gulp.task("sass", function () {
	return gulp.src(sassDest + "/core/core.scss")
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
		.pipe(autoprefixer({
			browsers: ["last 15 versions"]
		}))
		.pipe(sourcemaps.write("./"))
		.pipe(gulp.dest(sassDest + '/core'))
		.pipe(browserSync.reload({stream: true}));
	;
});

function getFolders(dir) {
	return fs.readdirSync(dir)
		.filter(function (file) {
			return fs.statSync(path.join(dir, file)).isDirectory();
		});
}

gulp.task('sass_multi', function () {
	var folders = getFolders(style_folders);
	var tasks = folders.map(function (folder) {
		return gulp.src(path.join(style_folders, folder, folder + '.s*ss'))
			.pipe(sourcemaps.init())
			.pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
			.pipe(autoprefixer({
				browsers: ["last 15 versions"]
			}))
			.pipe(sourcemaps.write("./"))
			.pipe(gulp.dest(style_folders + folder))
			.pipe(browserSync.reload({stream: true}));
	});
	return tasks;
});
gulp.task('sass_welcome', function () {
	return gulp.src(sassDest+'/core/welcome.scss')
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
		.pipe(autoprefixer({
			browsers: ["last 15 versions"]
		}))
		.pipe(sourcemaps.write("./"))
		.pipe(gulp.dest(sassDest+'/core'))

})
gulp.task("sass:watch", function () {
	gulp.watch([sassDest + "/core/**/*.scss"], ["sass_multi"]);
	gulp.watch([sassDest + "/themes/light/**/*.scss"], ["sass_multi"]);
});

gulp.task("browser_sync_init", function () {
	browserSync({
		proxy: process.env.HOST
		// proxy: 'http://localhost/302/d_toolkit/',

	});
});

gulp.task("default", ["browser_sync_init"], function () {
	if (typeof process.env.HOST !== "undefined") {
		gulp.watch([
			baseDir + "/view/theme/default/template/extension/**/*.twig"
		], browserSync.reload);
	}
	gulp.start(["sass",'sass_multi', "sass:watch"]);
});