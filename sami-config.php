<?php

use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Sami;

return new Sami('./src', [
    'title' => 'Simple Query Builder',
    'build_dir' => __DIR__ . '/docs/api',
    'remote_repository' => new GitHubRemoteRepository(
        'juangamonal/simple-query-builder',
        __DIR__ . '/src'
    )
]);
