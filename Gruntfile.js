module.exports = function(grunt){

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		sass: {
			dist: {
				files: {
					'dev/css/styles.css' : 'dev/sass/bootstrap.scss'
				}
			}
		},

		autoprefixer: {
			options: {
				browsers: ['last 2 version', 'ie 9']
			},
			styles: {
				src: 'dev/css/styles.css'
			}
		},

		cssmin: {
			combine: {
				files: {
					'public/assets/css/styles.css': ['dev/css/*.css']
				}
			}
		},

		uglify: {
			options: {
				mangle: true
			},
			dist: {
				files: {
					'public/assets/js/scripts.js': ['dev/js/*.js']
				}
			}
		},

		watch: {
			scripts: {
				files: ['dev/js/*.js'],
				tasks: ['uglify']
			},
			css: {
				files: ['dev/sass/*.scss'],
				tasks: ['sass', 'autoprefixer', 'cssmin']
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-autoprefixer');
	
	grunt.registerTask('default', ['sass', 'autoprefixer', 'cssmin', 'uglify']);

}