<?php declare(strict_types = 1);

namespace PHPStan\ExtensionInstaller;

/**
 * This class is generated by phpstan/extension-installer.
 * @internal
 */
final class GeneratedConfig
{

	public const EXTENSIONS = array (
  'composer/composer' => 
  array (
    'install_path' => '/Users/bkwaltz/platform_sites/projects/colby-site/web/wp-content/themes/colby-college-theme/lib/simplesamlphp/vendor/composer/composer',
    'relative_install_path' => '../../../composer/composer',
    'extra' => 
    array (
      'includes' => 
      array (
        0 => 'phpstan/rules.neon',
      ),
    ),
    'version' => '2.8.4',
    'phpstanVersionConstraint' => NULL,
  ),
  'composer/pcre' => 
  array (
    'install_path' => '/Users/bkwaltz/platform_sites/projects/colby-site/web/wp-content/themes/colby-college-theme/lib/simplesamlphp/vendor/composer/pcre',
    'relative_install_path' => '../../../composer/pcre',
    'extra' => 
    array (
      'includes' => 
      array (
        0 => 'extension.neon',
      ),
    ),
    'version' => '3.3.2',
    'phpstanVersionConstraint' => NULL,
  ),
  'phpstan/phpstan-mockery' => 
  array (
    'install_path' => '/Users/bkwaltz/platform_sites/projects/colby-site/web/wp-content/themes/colby-college-theme/lib/simplesamlphp/vendor/phpstan/phpstan-mockery',
    'relative_install_path' => '../../phpstan-mockery',
    'extra' => 
    array (
      'includes' => 
      array (
        0 => 'extension.neon',
      ),
    ),
    'version' => '1.1.3',
    'phpstanVersionConstraint' => '>=1.12.0.0-dev, <2.0.0.0-dev',
  ),
);

	public const NOT_INSTALLED = array (
);

	/** @var string|null */
	public const PHPSTAN_VERSION_CONSTRAINT = '>=1.12.0.0-dev, <2.0.0.0-dev';

	private function __construct()
	{
	}

}
