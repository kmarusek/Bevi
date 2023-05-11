<?php

declare (strict_types=1);
namespace WPSentry\ScopedVendor\PackageVersions;

use WPSentry\ScopedVendor\Composer\InstalledVersions;
use OutOfBoundsException;
\class_exists(\WPSentry\ScopedVendor\Composer\InstalledVersions::class);
/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = 'stayallive/wp-sentry';
    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS = array('clue/stream-filter' => 'v1.6.0@d6169430c7731d8509da7aecd0af756a5747b78e', 'composer/installers' => 'v2.2.0@c29dc4b93137acb82734f672c37e029dfbd95b35', 'composer/package-versions-deprecated' => '1.11.99.5@b4f54f74ef3453349c24a845d22392cd31e65f1d', 'guzzlehttp/promises' => '1.5.2@b94b2807d85443f9719887892882d0329d1e2598', 'guzzlehttp/psr7' => '1.9.1@e4490cabc77465aaee90b20cfc9a770f8c04be6b', 'http-interop/http-factory-guzzle' => '1.1.1@6e1efa1e020bf1c47cf0f13654e8ef9efb1463b3', 'jean85/pretty-package-versions' => '1.6.0@1e0104b46f045868f11942aea058cd7186d6c303', 'php-http/client-common' => '2.6.1@665bfc381bb910385f70391ed3eeefd0b7bbdd0d', 'php-http/curl-client' => '2.3.0@f7352c0796549949900d28fe991e19c90572386a', 'php-http/discovery' => '1.18.0@29ae6fae35f4116bbfe4c8b96ccc3f687eb07cd9', 'php-http/httplug' => '2.4.0@625ad742c360c8ac580fcc647a1541d29e257f67', 'php-http/message' => '1.15.0@2a1fbaa00cf5ffc82f379adf47388663bce8190d', 'php-http/message-factory' => '1.1.0@4d8778e1c7d405cbb471574821c1ff5b68cc8f57', 'php-http/promise' => '1.1.0@4c4c1f9b7289a2ec57cde7f1e9762a5789506f88', 'psr/http-client' => '1.0.2@0955afe48220520692d2d09f7ab7e0f93ffd6a31', 'psr/http-factory' => '1.0.2@e616d01114759c4c489f93b099585439f795fe35', 'psr/http-message' => '1.1@cb6ce4845ce34a8ad9e68117c10ee90a29919eba', 'psr/log' => '1.1.4@d49695b909c3b7628b6289db5479a1c204601f11', 'ralouphie/getallheaders' => '3.0.3@120b605dfeb996808c31b6477290a714d356e822', 'sentry/sentry' => '3.18.0@2041f2ed3f82a55eaca31079e106c8b17c89dbda', 'symfony/options-resolver' => 'v4.4.44@583f56160f716dd435f1cd721fd14b548f4bb510', 'symfony/polyfill-php80' => 'v1.27.0@7a6ff3f1959bb01aefccb463a0f2cd3d3d2fd936', 'stayallive/wp-sentry' => 'v6.14.0@0d58a3994f4e486d4a2d3e4c17b22cfbfe3e0fd2');
    private function __construct()
    {
    }
    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!self::composer2ApiUsable()) {
            return self::ROOT_PACKAGE_NAME;
        }
        return \WPSentry\ScopedVendor\Composer\InstalledVersions::getRootPackage()['name'];
    }
    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName) : string
    {
        if (self::composer2ApiUsable()) {
            return \WPSentry\ScopedVendor\Composer\InstalledVersions::getPrettyVersion($packageName) . '@' . \WPSentry\ScopedVendor\Composer\InstalledVersions::getReference($packageName);
        }
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }
        throw new \OutOfBoundsException('Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files');
    }
    private static function composer2ApiUsable() : bool
    {
        if (!\class_exists(\WPSentry\ScopedVendor\Composer\InstalledVersions::class, \false)) {
            return \false;
        }
        if (\method_exists(\WPSentry\ScopedVendor\Composer\InstalledVersions::class, 'getAllRawData')) {
            $rawData = \WPSentry\ScopedVendor\Composer\InstalledVersions::getAllRawData();
            if (\count($rawData) === 1 && \count($rawData[0]) === 0) {
                return \false;
            }
        } else {
            $rawData = \WPSentry\ScopedVendor\Composer\InstalledVersions::getRawData();
            if ($rawData === null || $rawData === []) {
                return \false;
            }
        }
        return \true;
    }
}
