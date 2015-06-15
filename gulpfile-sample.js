var localurl	= '%%YOUR LOCAL URL HERE%%',
	gulp 		= require('gulp-param')( require('gulp'), process.argv ),
	fs 			= require('fs'),
	mkdirp		= require('mkdirp'),
	bower 		= require('gulp-bower'),
	sass 		= require('gulp-sass'),
	include 	= require('gulp-include'),
	uglify 		= require('gulp-uglify'),
	rename		= require('gulp-rename'),
	sourcemaps	= require('gulp-sourcemaps'),
	sync		= require('browser-sync');


// Default gulp task 'gulp'
// Runs the following tasks:
// 		bower
// 		sass
// 		scripts
gulp.task('default', ['bower','sass','scripts']);


// Install required bower components
// @see bower.json
gulp.task('bower', function(){
	return bower();
});


// Compile SASS files
gulp.task('scss', function(){
	gulp.src('scss/*.scss')
		.pipe( sourcemaps.init() )
			.pipe( sass.sync({ outputStyle : 'compressed' }).on('error', sass.logError) )
		.pipe( sourcemaps.write('../css') )
		.pipe( gulp.dest('css') )
		.pipe(sync.stream());
});


// Concatenate and minify scripts
gulp.task('scripts', function(){
	gulp.src('js/app.js')
		.pipe( include() )
		.pipe( uglify() )
		.pipe( rename('app-min.js') )
		.pipe( gulp.dest('js/min') )
		.pipe(sync.stream());
});


// Init browser-sync server, and watch for changes
gulp.task('server', ['scss','scripts'], function(){

	sync.init({
		files: ['./*.php'],
		proxy : localurl
	});

	gulp.watch('scss/**/*.scss', ['scss']);
	gulp.watch(['js/**/*.js','!js/min/**/*.js'], ['scripts']);
});



// Yay a template generator!
// 
// Run gulp tpl --n {prefix}-{name} to generate a template file, and matching template function.
// Folders and files will be created if they do not already exist.
// @see includes/generator_templates for the blank placeholder files from which content will be copied
gulp.task('tpl', function( n, p ){

	if( !n ) {
		console.error( 'Error: Name missing. Please specify a template name with parameter --n {prefix}-{name}.' );
		return;
	}

	var parts = n.split(/-(.+)?/,2);

	if( parts.length < 2 ){
		console.error( 'Error: Improper name. Templates must follow the naming convention {prefix}-{name}.' );
		return;
	}

	var	prefix 		= parts[0],
		name		= parts[1],
		directory	= 'tpl_' + prefix + 's',
		filename 	= n + '.php',
		filepath	= directory + '/' + filename,
		fn_filename = prefix + '_functions.php',
		fn_filepath = directory + '/' + fn_filename;

	if( p ){
		var params = p.split(',');
		console.log(params);
	}


	// Create the directory. If it already exists, it will still proceed as normal.
	mkdirp( directory, function( error ){

		if( error ){
			console.error( error );
		} else {

			// Generate template file
			fs.readFile('includes/generator_templates/tpl.txt', 'utf8', function( error, data ){

				if( error ){
					console.error( error );
				} else {

					// If the doesn't exist, generate it
					fs.open( filepath, 'r', function( error, fd ){

						if( error && error.code=='ENOENT' ){

							var displayName = name.replace(/\-/g,' ') + ' ' + prefix;
							var text = data
								.replace( /\{\{name\}\}/g, name )
								.replace( /\{\{prefix\}\}/g, prefix )
								.replace( /\{\{display\-name\}\}/g, displayName.charAt(0).toUpperCase() + displayName.slice(1) );

							
								

							fs.writeFile( filepath, text, function(){
								console.log('--File created: ' + filepath);
							});

						} else {

							console.error( 'Warning:  template file "' + filepath + '" aready exists.' );

						}
					});	

				}

			});


			// Generate functions file
			fs.readFile('includes/generator_templates/tpl_function.txt', 'utf8', function( error, data ){

				if( error ){
					console.error( error );
				} else {

					var text = data
						.replace( /\{\{name\}\}/g, name )
						.replace( /\{\{prefix\}\}/g, prefix );

					if( params ){
						var params_in 	= '',
							params_out 	= '',
							param_docs 	= '';

						for( var i = 0; i < params.length; i++ ){
							if( i > 0 ){
								params_in += ', ';
								params_out += '\n\t\t';
							}
							params_in += '$' + params[i];
							params_out += '\'' + params[i] + '\' => $' + params[i] + ',';
						}
						text = text
							.replace( /\{\{params_in\}\}/g, params_in )
							.replace( /\{\{params_out\}\}/g, params_out );
					}

					fs.open( fn_filepath, 'r', function( error, fd ){

						if( error && error.code=='ENOENT' ){

							console.log('functions file doesnt exist, creating it');

							text = '<?php' + text;


							fs.writeFile( fn_filepath, text, function(){
								console.log('--File created: ' + fn_filepath);
								console.log('---Function "tpl_' + prefix + '_' + name + '()" added');
							});

						} else {

							fs.appendFile( fn_filepath, text, function(){
								console.log('--Function "tpl_' + prefix + '_' + name + '()" added to file: ' + fn_filepath);
							});

						}

					});

				}

			});

		}
	});

});