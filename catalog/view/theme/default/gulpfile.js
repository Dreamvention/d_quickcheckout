var gulp = require('gulp'),
	sass = require('gulp-sass'),
	browserSync = require('browser-sync'),
	concat = require("gulp-concat"),
	uglify = require("gulp-uglify"),
	cleanCSS = require('gulp-clean-css'),
	sourcemaps = require('gulp-sourcemaps'),
	autoprefixer = require('gulp-autoprefixer');
var default_theme_path = '../default/';
gulp.task('browser-sync', function () {
	browserSync({
		proxy: 'http://localhost/302/d_quickcheckout/',
		files: [
			'stylesheet/d_quickcheckout/*.css',
		]
	});
});
// will compille styles in dark and light folders
gulp.task('sass-core', function () {
	return gulp.src('stylesheet/d_quickcheckout/main.s*ss')
		.pipe(sass().on('error', sass.logError))
		.pipe(autoprefixer(['last 15 versions']))
		.pipe(gulp.dest('stylesheet/d_quickcheckout/'))
		.pipe(browserSync.stream({match: '**/*.css'}));

});
gulp.task('sass', function () {
	return gulp.src('stylesheet/d_quickcheckout/skin/journal2/journal2.s*ss')
		.pipe(sass().on('error', sass.logError))
		.pipe(autoprefixer({
			browsers: ["last 15 versions"]
		}))
		.pipe(gulp.dest('stylesheet/d_quickcheckout/skin/journal2'))
		.pipe(browserSync.stream({match: '**/*.css'}));
});
gulp.task("core-scripts", function () {
	return gulp.src([
		"javascript/core/checkout/checkout.js",
		"javascript/core/common/cart.js",
		"javascript/core/common/compare.js",
		"javascript/core/common/voucher.js",
		"javascript/core/common/wishlist.js",
		"javascript/core/partial/d_address_field.js",
		"javascript/core/partial/d_custom_field.js",
		"javascript/core/partial/d_product_sort.js",
		"javascript/core/product/product.js",
		"javascript/core/product/search.js",
		"javascript/core/total/coupon.js",
		"javascript/core/total/reward.js",
		"javascript/core/total/shipping.js",
		"javascript/core/total/voucher.js",
		"javascript/core/common/common.js",
	])
		.pipe(concat("d_visualize.js"))
		.pipe(gulp.dest('javascript/core'));
});

gulp.task('watch', ['browser-sync', 'sass', 'core-scripts'], function () {
	gulp.watch('stylesheet/d_quickcheckout/scss/**/*.s*ss', ['sass-core']);
	gulp.watch('stylesheet/d_quickcheckout/skin/**/**/*.s*ss', ['sass']);
	gulp.watch('template/**/*.**', browserSync.reload);
	gulp.watch('../../controller/**/*.**', browserSync.reload);
	gulp.watch('../../../controller/**/**/*.**', browserSync.reload);
});

gulp.task('default', ['watch']);