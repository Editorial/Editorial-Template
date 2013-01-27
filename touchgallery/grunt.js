module.exports = function(grunt) {

    grunt.initConfig({
        concat: {
            dist: {
                src  : ['lib/zepto.js', 'lib/utils.js', 'lib/*.js'],
                dest : 'build.js'
            }
        },
        min: {
            dist: {
                src  : ['build.js'],
                dest : 'build.min.js'
            }
        },
        watch: {
            files : '<config:concat.dist.src>',
            tasks : ['concat', 'min']
        }
    });

    grunt.registerTask('default', 'concat min');

};