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

//watch
gulp.task( 'watch', function() {
	livereload.listen();
	gulp.watch( './assets/material-icons/scss/**', ['css-icons'] );
} );
