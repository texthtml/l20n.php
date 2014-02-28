var gulp    = require('gulp');
var watch   = require('gulp-watch');
var notify  = require('gulp-notify');
var phpspec = require('gulp-phpspec');
var growl   = require('notify-send');

var notifier = notify.withReporter(function(options, callback) {
    growl.icon(options.icon).notify(options.title, options.message);
    callback();
});

gulp.task('default', function() {
    watch({glob:'{src,spec}/**/*', emitOnGlob: false, read: false})
        .pipe(watch({ emit: 'all' }, function(files) {
            var options = {
               notify: true
            };
            files
                .pipe(phpspec('', options))
                .on('error', notifier.onError({
                    title: 'l20n.php phpspec',
                    icon: 'face-sad',
                    message: '<font color="brown">Error(s)</font> occurred during test…'
                }))
                .pipe(notifier({
                    title: 'l20n.php phpspec',
                    icon: 'face-cool',
                    message: 'All tests have <font color="green">passed</font>'
                }));
        }));
});
