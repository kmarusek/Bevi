<?php

namespace WPSentry\ScopedVendor\Http\Discovery\Composer;

use WPSentry\ScopedVendor\Composer\Composer;
use WPSentry\ScopedVendor\Composer\DependencyResolver\Pool;
use WPSentry\ScopedVendor\Composer\EventDispatcher\EventSubscriberInterface;
use WPSentry\ScopedVendor\Composer\Factory;
use WPSentry\ScopedVendor\Composer\Installer;
use WPSentry\ScopedVendor\Composer\IO\IOInterface;
use WPSentry\ScopedVendor\Composer\Json\JsonFile;
use WPSentry\ScopedVendor\Composer\Json\JsonManipulator;
use WPSentry\ScopedVendor\Composer\Package\Locker;
use WPSentry\ScopedVendor\Composer\Package\Version\VersionParser;
use WPSentry\ScopedVendor\Composer\Package\Version\VersionSelector;
use WPSentry\ScopedVendor\Composer\Plugin\PluginInterface;
use WPSentry\ScopedVendor\Composer\Repository\InstalledRepositoryInterface;
use WPSentry\ScopedVendor\Composer\Repository\RepositorySet;
use WPSentry\ScopedVendor\Composer\Script\Event;
use WPSentry\ScopedVendor\Composer\Script\ScriptEvents;
use WPSentry\ScopedVendor\Http\Discovery\ClassDiscovery;
/**
 * Auto-installs missing implementations.
 *
 * When a dependency requires both this package and one of the supported `*-implementation`
 * virtual packages, this plugin will auto-install a well-known implementation if none is
 * found. The plugin will first look at already installed packages and figure out the
 * preferred implementation to install based on the below stickyness rules (or on the first
 * listed implementation if no rules match.)
 *
 * Don't miss updating src/Strategy/Common*Strategy.php when adding a new supported package.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
class Plugin implements \WPSentry\ScopedVendor\Composer\Plugin\PluginInterface, \WPSentry\ScopedVendor\Composer\EventDispatcher\EventSubscriberInterface
{
    /**
     * Describes, for every supported virtual implementation, which packages
     * provide said implementation and which extra dependencies each package
     * requires to provide the implementation.
     */
    private const PROVIDE_RULES = ['php-http/async-client-implementation' => ['symfony/http-client' => ['guzzlehttp/promises', 'php-http/message-factory', 'psr/http-factory-implementation'], 'php-http/guzzle7-adapter' => [], 'php-http/guzzle6-adapter' => [], 'php-http/curl-client' => [], 'php-http/react-adapter' => []], 'php-http/client-implementation' => ['symfony/http-client' => ['php-http/message-factory', 'psr/http-factory-implementation'], 'php-http/guzzle7-adapter' => [], 'php-http/guzzle6-adapter' => [], 'php-http/cakephp-adapter' => [], 'php-http/curl-client' => [], 'php-http/react-adapter' => [], 'php-http/buzz-adapter' => [], 'php-http/artax-adapter' => [], 'kriswallsmith/buzz:^1' => []], 'psr/http-client-implementation' => ['symfony/http-client' => ['psr/http-factory-implementation'], 'guzzlehttp/guzzle' => [], 'kriswallsmith/buzz:^1' => []], 'psr/http-message-implementation' => ['php-http/discovery' => ['psr/http-factory-implementation']], 'psr/http-factory-implementation' => ['nyholm/psr7' => [], 'guzzlehttp/psr7:>=2' => [], 'slim/psr7' => [], 'laminas/laminas-diactoros' => [], 'phalcon/cphalcon:^4' => [], 'zendframework/zend-diactoros:>=2' => [], 'http-interop/http-factory-guzzle' => [], 'http-interop/http-factory-diactoros' => [], 'http-interop/http-factory-slim' => []]];
    /**
     * Describes which package should be preferred on the left side
     * depending on which one is already installed on the right side.
     */
    private const STICKYNESS_RULES = ['symfony/http-client' => 'symfony/framework-bundle', 'php-http/guzzle7-adapter' => 'guzzlehttp/guzzle:^7', 'php-http/guzzle6-adapter' => 'guzzlehttp/guzzle:^6', 'php-http/guzzle5-adapter' => 'guzzlehttp/guzzle:^5', 'php-http/cakephp-adapter' => 'cakephp/cakephp', 'php-http/react-adapter' => 'react/event-loop', 'php-http/buzz-adapter' => 'kriswallsmith/buzz:^0.15.1', 'php-http/artax-adapter' => 'amphp/artax:^3', 'http-interop/http-factory-guzzle' => 'guzzlehttp/psr7:^1', 'http-interop/http-factory-diactoros' => 'zendframework/zend-diactoros:^1', 'http-interop/http-factory-slim' => 'slim/slim:^3'];
    public static function getSubscribedEvents() : array
    {
        return [\WPSentry\ScopedVendor\Composer\Script\ScriptEvents::POST_UPDATE_CMD => 'postUpdate'];
    }
    public function activate(\WPSentry\ScopedVendor\Composer\Composer $composer, \WPSentry\ScopedVendor\Composer\IO\IOInterface $io) : void
    {
    }
    public function deactivate(\WPSentry\ScopedVendor\Composer\Composer $composer, \WPSentry\ScopedVendor\Composer\IO\IOInterface $io)
    {
    }
    public function uninstall(\WPSentry\ScopedVendor\Composer\Composer $composer, \WPSentry\ScopedVendor\Composer\IO\IOInterface $io)
    {
    }
    public function postUpdate(\WPSentry\ScopedVendor\Composer\Script\Event $event)
    {
        $composer = $event->getComposer();
        $repo = $composer->getRepositoryManager()->getLocalRepository();
        $requires = [$composer->getPackage()->getRequires(), $composer->getPackage()->getDevRequires()];
        $missingRequires = $this->getMissingRequires($repo, $requires, 'project' === $composer->getPackage()->getType());
        $missingRequires = ['require' => \array_fill_keys(\array_merge([], ...\array_values($missingRequires[0])), '*'), 'require-dev' => \array_fill_keys(\array_merge([], ...\array_values($missingRequires[1])), '*'), 'remove' => \array_fill_keys(\array_merge([], ...\array_values($missingRequires[2])), '*')];
        if (!($missingRequires = \array_filter($missingRequires))) {
            return;
        }
        $composerJsonContents = \file_get_contents(\WPSentry\ScopedVendor\Composer\Factory::getComposerFile());
        $this->updateComposerJson($missingRequires, $composer->getConfig()->get('sort-packages'));
        $installer = null;
        // Find the composer installer, hack borrowed from symfony/flex
        foreach (\debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT) as $trace) {
            if (isset($trace['object']) && $trace['object'] instanceof \WPSentry\ScopedVendor\Composer\Installer) {
                $installer = $trace['object'];
                break;
            }
        }
        if (!$installer) {
            return;
        }
        $event->stopPropagation();
        $dispatcher = $composer->getEventDispatcher();
        $disableScripts = !\method_exists($dispatcher, 'setRunScripts') || !((array) $dispatcher)["\0*\0runScripts"];
        $composer = \WPSentry\ScopedVendor\Composer\Factory::create($event->getIO(), null, \false, $disableScripts);
        /** @var Installer $installer */
        $installer = clone $installer;
        if (\method_exists($installer, 'setAudit')) {
            $trace['object']->setAudit(\false);
        }
        // we need a clone of the installer to preserve its configuration state but with our own service objects
        $installer->__construct($event->getIO(), $composer->getConfig(), $composer->getPackage(), $composer->getDownloadManager(), $composer->getRepositoryManager(), $composer->getLocker(), $composer->getInstallationManager(), $composer->getEventDispatcher(), $composer->getAutoloadGenerator());
        if (\method_exists($installer, 'setPlatformRequirementFilter')) {
            $installer->setPlatformRequirementFilter(((array) $trace['object'])["\0*\0platformRequirementFilter"]);
        }
        if (0 !== $installer->run()) {
            \file_put_contents(\WPSentry\ScopedVendor\Composer\Factory::getComposerFile(), $composerJsonContents);
            return;
        }
        $versionSelector = new \WPSentry\ScopedVendor\Composer\Package\Version\VersionSelector(\WPSentry\ScopedVendor\Http\Discovery\ClassDiscovery::safeClassExists(\WPSentry\ScopedVendor\Composer\Repository\RepositorySet::class) ? new \WPSentry\ScopedVendor\Composer\Repository\RepositorySet() : new \WPSentry\ScopedVendor\Composer\DependencyResolver\Pool());
        $updateComposerJson = \false;
        foreach ($composer->getRepositoryManager()->getLocalRepository()->getPackages() as $package) {
            foreach (['require', 'require-dev'] as $key) {
                if (!isset($missingRequires[$key][$package->getName()])) {
                    continue;
                }
                $updateComposerJson = \true;
                $missingRequires[$key][$package->getName()] = $versionSelector->findRecommendedRequireVersion($package);
            }
        }
        if ($updateComposerJson) {
            $this->updateComposerJson($missingRequires, $composer->getConfig()->get('sort-packages'));
            $this->updateComposerLock($composer, $event->getIO());
        }
    }
    public function getMissingRequires(\WPSentry\ScopedVendor\Composer\Repository\InstalledRepositoryInterface $repo, array $requires, bool $isProject) : array
    {
        $allPackages = [];
        $devPackages = \method_exists($repo, 'getDevPackageNames') ? \array_fill_keys($repo->getDevPackageNames(), \true) : [];
        // One must require "php-http/discovery"
        // to opt-in for auto-installation of virtual package implementations
        if (!isset($requires[0]['php-http/discovery'])) {
            $requires = [[], []];
        }
        foreach ($repo->getPackages() as $package) {
            $allPackages[$package->getName()] = \true;
            if (1 < \count($names = $package->getNames(\false))) {
                $allPackages += \array_fill_keys($names, \false);
                if (isset($devPackages[$package->getName()])) {
                    $devPackages += $names;
                }
            }
            if (isset($package->getRequires()['php-http/discovery'])) {
                $requires[(int) isset($devPackages[$package->getName()])] += $package->getRequires();
            }
        }
        $missingRequires = [[], [], []];
        $versionParser = new \WPSentry\ScopedVendor\Composer\Package\Version\VersionParser();
        if (\WPSentry\ScopedVendor\Http\Discovery\ClassDiscovery::safeClassExists(\WPSentry\ScopedVendor\Phalcon\Http\Message\RequestFactory::class, \false)) {
            $missingRequires[0]['psr/http-factory-implementation'] = [];
            $missingRequires[1]['psr/http-factory-implementation'] = [];
        }
        foreach ($requires as $dev => $rules) {
            $abstractions = [];
            $rules = \array_intersect_key(self::PROVIDE_RULES, $rules);
            while ($rules) {
                $abstractions[] = $abstraction = \key($rules);
                foreach (\array_shift($rules) as $candidate => $deps) {
                    [$candidate, $version] = \explode(':', $candidate, 2) + [1 => null];
                    if (!isset($allPackages[$candidate])) {
                        continue;
                    }
                    if (null !== $version && !$repo->findPackage($candidate, $versionParser->parseConstraints($version))) {
                        continue;
                    }
                    if ($isProject && !$dev && isset($devPackages[$candidate])) {
                        $missingRequires[0][$abstraction] = [$candidate];
                        $missingRequires[2][$abstraction] = [$candidate];
                    } else {
                        $missingRequires[$dev][$abstraction] = [];
                    }
                    foreach ($deps as $dep) {
                        if (isset(self::PROVIDE_RULES[$dep])) {
                            $rules[$dep] = self::PROVIDE_RULES[$dep];
                        } elseif (!isset($allPackages[$dep])) {
                            $missingRequires[$dev][$abstraction][] = $dep;
                        } elseif ($isProject && !$dev && isset($devPackages[$dep])) {
                            $missingRequires[0][$abstraction][] = $dep;
                            $missingRequires[2][$abstraction][] = $dep;
                        }
                    }
                    break;
                }
            }
            while ($abstractions) {
                $abstraction = \array_shift($abstractions);
                if (isset($missingRequires[$dev][$abstraction])) {
                    continue;
                }
                $candidates = self::PROVIDE_RULES[$abstraction];
                foreach ($candidates as $candidate => $deps) {
                    [$candidate, $version] = \explode(':', $candidate, 2) + [1 => null];
                    if (null !== $version && !$repo->findPackage($candidate, $versionParser->parseConstraints($version))) {
                        continue;
                    }
                    if (isset($allPackages[$candidate]) && (!$isProject || $dev || !isset($devPackages[$candidate]))) {
                        continue 2;
                    }
                }
                foreach (\array_intersect_key(self::STICKYNESS_RULES, $candidates) as $candidate => $stickyRule) {
                    [$stickyName, $stickyVersion] = \explode(':', $stickyRule, 2) + [1 => null];
                    if (!isset($allPackages[$stickyName]) || $isProject && !$dev && isset($devPackages[$stickyName])) {
                        continue;
                    }
                    if (null !== $stickyVersion && !$repo->findPackage($stickyName, $versionParser->parseConstraints($stickyVersion))) {
                        continue;
                    }
                    $candidates = [$candidate => $candidates[$candidate]];
                    break;
                }
                $dep = \key($candidates);
                $missingRequires[$dev][$abstraction] = [$dep];
                if ($isProject && !$dev && isset($devPackages[$dep])) {
                    $missingRequires[2][$abstraction][] = $dep;
                }
                foreach (\current($candidates) as $dep) {
                    if (isset(self::PROVIDE_RULES[$dep])) {
                        $abstractions[] = $dep;
                    } elseif (!isset($allPackages[$dep])) {
                        $missingRequires[$dev][$abstraction][] = $dep;
                    } elseif ($isProject && !$dev && isset($devPackages[$dep])) {
                        $missingRequires[0][$abstraction][] = $dep;
                        $missingRequires[2][$abstraction][] = $dep;
                    }
                }
            }
        }
        $missingRequires[1] = \array_diff_key($missingRequires[1], $missingRequires[0]);
        return $missingRequires;
    }
    private function updateComposerJson(array $missingRequires, bool $sortPackages)
    {
        $file = \WPSentry\ScopedVendor\Composer\Factory::getComposerFile();
        $contents = \file_get_contents($file);
        $manipulator = new \WPSentry\ScopedVendor\Composer\Json\JsonManipulator($contents);
        foreach ($missingRequires as $key => $packages) {
            foreach ($packages as $package => $constraint) {
                if ('remove' === $key) {
                    $manipulator->removeSubNode('require-dev', $package);
                } else {
                    $manipulator->addLink($key, $package, $constraint, $sortPackages);
                }
            }
        }
        \file_put_contents($file, $manipulator->getContents());
    }
    private function updateComposerLock(\WPSentry\ScopedVendor\Composer\Composer $composer, \WPSentry\ScopedVendor\Composer\IO\IOInterface $io)
    {
        $lock = \substr(\WPSentry\ScopedVendor\Composer\Factory::getComposerFile(), 0, -4) . 'lock';
        $composerJson = \file_get_contents(\WPSentry\ScopedVendor\Composer\Factory::getComposerFile());
        $lockFile = new \WPSentry\ScopedVendor\Composer\Json\JsonFile($lock, null, $io);
        $locker = \WPSentry\ScopedVendor\Http\Discovery\ClassDiscovery::safeClassExists(\WPSentry\ScopedVendor\Composer\Repository\RepositorySet::class) ? new \WPSentry\ScopedVendor\Composer\Package\Locker($io, $lockFile, $composer->getInstallationManager(), $composerJson) : new \WPSentry\ScopedVendor\Composer\Package\Locker($io, $lockFile, $composer->getRepositoryManager(), $composer->getInstallationManager(), $composerJson);
        $lockData = $locker->getLockData();
        $lockData['content-hash'] = \WPSentry\ScopedVendor\Composer\Package\Locker::getContentHash($composerJson);
        $lockFile->write($lockData);
    }
}
