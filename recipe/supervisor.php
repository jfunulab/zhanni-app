<?php
namespace Deployer;

set('restart_supervisor_command', 'echo "" | sudo -S /usr/sbin/service supervisor restart');

desc('Restart supervisor service');
task('restart:supervisor', function(){
    run('{{restart_supervisor_command}}');
});
