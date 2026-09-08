const gulp         = require('gulp');
const sass         = require('gulp-sass')(require('sass'));
const postcss      = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const rtlcss       = require('gulp-rtlcss');
const rename       = require('gulp-rename');
const terser       = require('gulp-terser');
const sort         = require('gulp-sort');
const wppot        = require('gulp-wp-pot');

const sassOptions = {
	style: 'compressed',
	loadPaths: ['assets/css/scss']
};

const potOptions = {
	domain: 'lsx-business-directory',
	package: 'lsx-business-directory',
	bugReport: 'https://github.com/lightspeedwp/lsx-business-directory/issues',
	team: 'LightSpeed <webmaster@lsdev.biz>'
};

// Sourcemaps come from gulp 5's built-in support rather than gulp-sourcemaps,
// which is unmaintained.
function styles() {
	return gulp.src('assets/css/scss/*.scss', { sourcemaps: true })
		.pipe(sass.sync(sassOptions))
		.pipe(postcss([autoprefixer()]))
		.pipe(gulp.dest('assets/css', { sourcemaps: 'maps' }));
}

function stylesRtl() {
	return gulp.src('assets/css/scss/*.scss')
		.pipe(sass.sync(sassOptions))
		.pipe(postcss([autoprefixer()]))
		.pipe(rtlcss())
		.pipe(rename({ suffix: '-rtl' }))
		.pipe(gulp.dest('assets/css'));
}

function js() {
	return gulp.src('assets/js/src/**/*.js')
		.pipe(terser())
		.pipe(rename({ suffix: '.min' }))
		.pipe(gulp.dest('assets/js'));
}

function wordpressPot() {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot(potOptions))
		.pipe(gulp.dest('languages/lsx-business-directory.pot'));
}

function wordpressPo() {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot(potOptions))
		.pipe(gulp.dest('languages/lsx-business-directory-en_EN.po'));
}

const compileCss = gulp.parallel(styles, stylesRtl);
const build = gulp.parallel(compileCss, js);

function watchFiles() {
	gulp.watch('assets/css/**/*.scss', compileCss);
	gulp.watch('assets/js/src/**/*.js', js);
}

function help(cb) {
	console.log('Use the following commands');
	console.log('--------------------------');
	console.log('gulp compile-css    to compile the scss to css');
	console.log('gulp compile-js     to compile the js to min.js');
	console.log('gulp build          to compile both');
	console.log('gulp watch          to keep watching the files for changes');
	console.log('gulp wordpress-pot  to regenerate the .pot');
	console.log('');
	console.log('The .po -> .mo step is `npm run build:mo` (WP-CLI), not gulp.');
	cb();
}

exports.styles = styles;
exports['styles-rtl'] = stylesRtl;
exports['compile-css'] = compileCss;
exports.js = js;
exports['compile-js'] = js;
exports.build = build;
exports.watch = watchFiles;
exports['wordpress-pot'] = wordpressPot;
exports['wordpress-po'] = wordpressPo;
exports.default = help;
