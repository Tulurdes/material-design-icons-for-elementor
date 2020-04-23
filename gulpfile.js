'use strict';

var gulp         = require( 'gulp' ),
	rename       = require( 'gulp-rename' ),
	notify       = require( 'gulp-notify' ),
	autoprefixer = require( 'gulp-autoprefixer' ),
	sass         = require( 'gulp-sass' ),
	plumber      = require( 'gulp-plumber' );

//css
gulp.task( 'css-icons', function() {
	return gulp.src( './assets/material-icons/scss/material-icons.scss' )
		.pipe(
			plumber( {
				errorHandler: function( error ) {
					console.log( '=================ERROR=================' );
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( { outputStyle: 'compressed' } ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade:  false
		} ) )

		.pipe( rename( 'material-icons.css' ) )
		.pipe( gulp.dest( './assets/material-icons/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

gulp.task( 'css-icons-outlined', function() {
	return gulp.src( './assets/material-icons/scss/material-icons-outlined.scss' )
		.pipe(
			plumber( {
				errorHandler: function( error ) {
					console.log( '=================ERROR=================' );
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( { outputStyle: 'compressed' } ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade:  false
		} ) )

		.pipe( rename( 'material-icons-outlined.css' ) )
		.pipe( gulp.dest( './assets/material-icons/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

gulp.task( 'css-icons-codes', function() {
	return gulp.src( './assets/material-icons/scss/material-icons-codes.scss' )
		.pipe(
			plumber( {
				errorHandler: function( error ) {
					console.log( '=================ERROR=================' );
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( { outputStyle: 'compressed' } ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade:  false
		} ) )

		.pipe( rename( 'material-icons-codes.css' ) )
		.pipe( gulp.dest( './assets/material-icons/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) );
} );

//watch
gulp.task( 'watch', function() {
	gulp.watch( './assets/material-icons/scss/**', gulp.series( 'css-icons', 'css-icons-outlined', 'css-icons-codes' ) );
} );
