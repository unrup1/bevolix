<?php
namespace Deployer;


// Project name
set('application', 'Bevolix');

// Project repository
set('repository', 'git@gitlab.com:unrup1/bevolix.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys
add('shared_files', ['.env.local']);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);
set('allow_anonymous_stats', false);
set('writable_mode', 'chmod');

set('default_stage', 'dev');

// Hosts
host('test')
    ->hostname('dev.philun.de')
    ->stage('dev')
    ->user('root')
    ->become('www-data')
    ->set('deploy_path', '/var/www/abschussplanung.philun.de');

host('prod1.philun.de')
    ->stage('prod')
    ->user('root')
    ->become('www-data')
    ->set('branch', 'master')
    ->set('deploy_path', '/var/www/abschussplanung.landkreis-uelzen.de');

host('demo-future')
    ->hostname('dev.philun.de')
    ->stage('prod')
    ->user('root')
    ->become('www-data')
    ->set('branch', 'plan')
    ->set('deploy_path', '/var/www/demo.philun.de');


// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

// NPM
set('bin/npm', function () {
    return run('which npm');
});

desc('Install npm packages');
task('npm:install', function () {
    if (has('previous_release')) {
        if (test('[ -d {{previous_release}}/node_modules ]')) {
            run('cp -R {{previous_release}}/node_modules {{release_path}}');
        }
    }
    run("cd {{release_path}} && {{bin/npm}} install --ignore-scripts");
});

desc('Execute npm build');
task('npm:build', function() {
    run("cd {{release_path}} && {{bin/npm}} run build");
});

desc('Build email templates');
task('npm:emails', function() {
    run("cd {{release_path}} && {{bin/npm}} run emails:build");
});

after('deploy:update_code', 'npm:install');
after('npm:install', 'npm:build');
after('npm:build', 'npm:emails');

// PHP FPM
desc('Reload PHP-FPM Cache');
task('php-fpm:reload', function() {
    run("cachetool opcache:reset --fcgi=127.0.0.1:9000");
});
after('deploy:symlink', 'php-fpm:reload');

// Composer
// composer
desc('Optimize autoloader of composer');
task('deploy:optimize-autoload', function () {
    run('cd {{release_path}} && {{bin/composer}} dump-autoload --no-dev --classmap-authoritative');
});
after('deploy:vendors', 'deploy:optimize-autoload');
