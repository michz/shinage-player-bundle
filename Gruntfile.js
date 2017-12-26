module.exports = function(grunt) {
    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        resourcesPath: 'Resources/public',
        clean: {
            css: ['<%= resourcesPath %>/dist/css/*'],
            js: ['<%= resourcesPath %>/dist/js/*']
        },
        concat: {
            options: {
                stripBanners: true
            },
            css: {
                src: [
                    '<%= resourcesPath %>/css/reset.css',
                    '<%= resourcesPath %>/css/**'
                ],
                dest: '<%= resourcesPath %>/dist/css/shp.css'
            },
            js : {
                src : [
                    '<%= resourcesPath %>/js/*.js'
                ],
                dest: '<%= resourcesPath %>/dist/js/shp.js'
            }
        },
        cssmin : {
            "shp":{
                src: '<%= resourcesPath %>/dist/css/shp.css',
                dest: '<%= resourcesPath %>/dist/css/shp.min.css'
            }
        },
        uglify : {
            js: {
                files: {
                    '<%= resourcesPath %>/dist/js/shp.min.js': ['<%= resourcesPath %>/dist/js/shp.js']
                }
            }
        }
    });

    // Load the plugins.
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    // Default task(s).
    grunt.registerTask('default', ['clean', 'concat', 'cssmin', 'uglify']);
    //grunt.registerTask('default', ['clean', 'copy']);
};
