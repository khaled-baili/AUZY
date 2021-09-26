<?php return array(
    'root' => array(
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'reference' => NULL,
        'name' => 'khaled/auzy-tests',
        'dev' => true,
    ),
    'versions' => array(
        'giacocorsiglia/wordpress-stubs' => array(
            'dev_requirement' => true,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'khaled/auzy-tests' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'reference' => NULL,
            'dev_requirement' => false,
        ),
        'php-stubs/wordpress-stubs' => array(
            'pretty_version' => 'v5.8.0',
            'version' => '5.8.0.0',
            'type' => 'library',
            'install_path' => __DIR__ . '/../php-stubs/wordpress-stubs',
            'aliases' => array(),
            'reference' => '794e6eedfd5f2a334d581214c007fc398be588fe',
            'dev_requirement' => true,
        ),
    ),
);
