'use strict';

import gulp from 'gulp';
import dartSass from 'sass';
import gulpSass from 'gulp-sass';
const sass = gulpSass( dartSass );
import autoprefixer from 'gulp-autoprefixer';
import sourcemaps from 'gulp-sourcemaps';
import sassGlob from 'gulp-sass-glob';
const concat = require('gulp-concat-util');
const browsersync = require('browser-sync').create();

const sassPaths = {
	frontendSrc: 'index-frontend.scss',
	editorSrc: 'index-editor.scss',
};

const sassOpts = { outputStyle: 'compressed', errLogToConsole: true };

gulp.task('frontendCss', () => {
	console.log(`Finished: Block and General CSS`);
	return compileFrontendSass();
});

gulp.task('editorCss', () => {
	console.log(`Finished: Block Editor CSS`);
	return compileEditorSass();
});

gulp.task('scripts', function(){
	return gulp.src([`../scripts/*.js`])
		.pipe(concat(`frontend.build.js`))
		.pipe(gulp.dest('./gulp-output'))
		.pipe(browsersync.reload({stream: true}));
});

gulp.task('watch', function () {
	browsersync.init({
		proxy: "localhost:8888/web",
    });
	
    gulp.watch(['../styles/*.scss', '../blocks-gutenberg/**/block.scss', '../blocks-acf/**/block.scss'], gulp.series('frontendCss'));
    gulp.watch(['../styles/editor-only.scss', '../blocks-gutenberg/**/editor.scss', '../blocks-acf/**/editor.scss', '../styles/*.scss', '../blocks-gutenberg/**/block.scss', '../blocks-acf/**/block.scss'], gulp.series('editorCss'));
    gulp.watch([`../scripts/*.js`], gulp.series('scripts'));
});

function compileFrontendSass() {
	return compileSass(sassPaths.frontendSrc, 'frontend.build.css');
}

function compileEditorSass() {
	return compileSass(sassPaths.editorSrc, 'editor.build.css');
}

function compileSass(srcFile, outputFilename) {
	return gulp.src(srcFile)
		.pipe(concat(outputFilename))
		.pipe(sassGlob())
		.on('error', function (err) {
			console.log(err.toString());
	
			this.emit('end');
		})
		.pipe(sass(sassOpts))
		.on('error', function (err) {
		console.log(err.toString())

		this.emit('end');
	})
    .pipe(sourcemaps.init())
    .pipe(autoprefixer())
	.pipe(sourcemaps.write('.'))
	.pipe(gulp.dest('./gulp-output'))
	.pipe(browsersync.reload({stream: true}));

}

gulp.task('build', gulp.series('frontendCss', 'editorCss', 'scripts'));
gulp.task('default', gulp.series('watch'));
