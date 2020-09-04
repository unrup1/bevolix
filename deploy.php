<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'Bevolix');

// Project repository
set('repository', 'git@github.com:unrup1/bevolix.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys
add('shared_files', ['.env']);
add('shared_dirs', ['storage/app']);

// Writable dirs by web server
add('writable_dirs', ['storage/app']);
set('allow_anonymous_stats', false);
set('writable_mode', 'chmod');

set('default_stage', 'dev');

// Hosts
host('test')
    ->hostname('app.bevolix.de')
    ->stage('prod')
    ->user('root')
    ->become('www-data')
    ->set('deploy_path', '/var/www/app.bevolix.de');

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'npm:install',
    'npm:prod',
    'deploy:shared',
    'deploy:vendors',
    'deploy:optimize-autoload',
    'deploy:writable',
    'artisan:config',
    'artisan:route',
    'artisan:cache',
    'artisan:migrate',
    'deploy:symlink',
    'php-fpm:reload',
    'deploy:unlock',
    'cleanup',
]);

// Success response
after('deploy', 'success');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

/**
 * Laravel
 * Migrate database before symlink new release.
 */
set('bin/artisan', 'cd {{release_path}} && {{bin/php}} artisan');

task('artisan:config', function() {
    run("{{bin/artisan}} config:cache");
});

task('artisan:route', function() {
    run("{{bin/artisan}} route:cache");
});

task('artisan:cache', function() {
    run("{{bin/artisan}} view:cache");
});

task('artisan:migrate', function() {
    run("{{bin/artisan}} migrate --force");
});

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
task('npm:prod', function() {
    run("cd {{release_path}} && {{bin/npm}} run prod");
});

// PHP FPM
desc('Reload PHP-FPM Cache');
task('php-fpm:reload', function() {
    run("cachetool opcache:reset --fcgi=127.0.0.1:9000");
});

// Composer
// composer
desc('Optimize autoloader of composer');
task('deploy:optimize-autoload', function () {
    run('cd {{release_path}} && {{bin/composer}} dump-autoload --no-dev --classmap-authoritative');
});
