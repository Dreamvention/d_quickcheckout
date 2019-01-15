/**
 * This Gulp File is for development purposes. 
 * NEEDS REFACTORING
 * Dreamvention
 * 15.01.2019
 */

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    browserSync = require('browser-sync'),
    concat = require("gulp-concat"),
    uglify = require("gulp-uglify"),
    cleanCSS = require('gulp-clean-css'),
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('gulp-autoprefixer');
var default_theme_path = '../default/';
var fs = require('fs');
var path = require('path');
var baseDir = path.resolve(__dirname, '../../../');
var themeDir = path.join(baseDir, 'theme/default');
gulp.task('browser-sync', function() {
    browserSync({
        proxy: 'http://localhost/opencart/302/d_quickcheckout/',
        files: [
            'stylesheet/d_quickcheckout/*.css',
        ]
    });
});
gulp.task('sass-core', function() {
    return gulp.src(path.join(themeDir, 'stylesheet/d_quickcheckout/main.s*ss'))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer(['last 15 versions']))
        .pipe(gulp.dest(path.join(themeDir, 'stylesheet/d_quickcheckout/')))
        .pipe(browserSync.stream({ match: '**/*.css' }));

});
var list_tasks;

var style_folders = path.join(themeDir, 'stylesheet/d_quickcheckout/skin');

function getFolders(dir) {
    return fs.readdirSync(dir)
        .filter(function(file) {
            return fs.statSync(path.join(dir, file)).isDirectory();
        });
}
gulp.task('sass-rtl', function() {
    return gulp.src(path.join(themeDir, 'stylesheet/d_quickcheckout/rtl.s*ss'))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer(['last 15 versions']))
        .pipe(gulp.dest(path.join(themeDir, 'stylesheet/d_quickcheckout/')))
        .pipe(browserSync.stream({ match: '**/*.css' }));

});

gulp.task('sass_multi', function() {
    var folders = getFolders(style_folders);
    var tasks = folders.map(function(folder) {
        console.log(folder);
        return gulp.src(path.join(style_folders, folder, folder + '.s*ss'))
            .pipe(sourcemaps.init())
            .pipe(sass().on('error', sass.logError))
            .pipe(autoprefixer(['last 15 versions']))
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest(path.join(style_folders, folder)))
            .pipe(browserSync.stream({ match: '**/*.css' }));

    });
    return tasks;
});
gulp.task('sass', function() {
    folder = 'default';
    console.log(path.join(style_folders, folder));
    return gulp.src(path.join(style_folders, folder, folder + '.s*ss'))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ["last 15 versions"]
        }))
        .pipe(gulp.dest(path.join(style_folders, folder)))
        .pipe(browserSync.stream({ match: '**/*.css' }));
});
gulp.task('sass-default', function() {
    return gulp.src('stylesheet\\d_quickcheckout\\skin\\default\\default.s*ss')
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ["last 15 versions"]
        }))
        .pipe(gulp.dest('stylesheet\\d_quickcheckout\\skin\\default'))
        .pipe(browserSync.stream({ match: '**/*.css' }));
});
gulp.task("core-scripts", function() {
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

gulp.task('watch', ['browser-sync', 'sass-core', 'sass-rtl', 'sass_multi', 'core-scripts'], function() {
    gulp.watch(path.join(themeDir, 'stylesheet/d_quickcheckout/scss/**/*.s*ss'), ['sass-core']);
    gulp.watch(path.join(themeDir, 'stylesheet/d_quickcheckout/*.s*ss'), ['sass-rtl']);
    gulp.watch(path.join(themeDir, 'stylesheet/d_quickcheckout/skin/**/**/*.s*ss'), ['sass_multi']);
    gulp.watch(path.join(themeDir, 'template/**/*.**'), browserSync.reload);
    gulp.watch('../../controller/**/*.**', browserSync.reload);
    gulp.watch('../../../controller/**/**/*.**', browserSync.reload);
});

gulp.task('default', ['watch']);