<?php

declare (strict_types=1);
namespace Sentry\Monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Sentry\Event;
use Sentry\EventHint;
use Sentry\Severity;
use Sentry\State\HubInterface;
use Sentry\State\Scope;
/**
 * This Monolog handler logs every message to a Sentry's server using the given
 * hub instance.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
final class Handler extends \Monolog\Handler\AbstractProcessingHandler
{
    private const CONTEXT_EXCEPTION_KEY = 'exception';
    /**
     * @var HubInterface
     */
    private $hub;
    /**
     * @var bool
     */
    private $fillExtraContext;
    /**
     * {@inheritdoc}
     *
     * @param HubInterface $hub The hub to which errors are reported
     */
    public function __construct(\Sentry\State\HubInterface $hub, $level = \Monolog\Logger::DEBUG, bool $bubble = \true, bool $fillExtraContext = \false)
    {
        parent::__construct($level, $bubble);
        $this->hub = $hub;
        $this->fillExtraContext = $fillExtraContext;
    }
    /**
     * {@inheritdoc}
     */
    protected function write(array $record) : void
    {
        $event = \Sentry\Event::createEvent();
        $event->setLevel(self::getSeverityFromLevel($record['level']));
        $event->setMessage($record['message']);
        $event->setLogger(\sprintf('monolog.%s', $record['channel']));
        $hint = new \Sentry\EventHint();
        if (isset($record['context']['exception']) && $record['context']['exception'] instanceof \Throwable) {
            $hint->exception = $record['context']['exception'];
        }
        $this->hub->withScope(function (\Sentry\State\Scope $scope) use($record, $event, $hint) : void {
            $scope->setExtra('monolog.channel', $record['channel']);
            $scope->setExtra('monolog.level', $record['level_name']);
            $monologContextData = $this->getMonologContextData($record['context']);
            if (!empty($monologContextData)) {
                $scope->setExtra('monolog.context', $monologContextData);
            }
            $this->hub->captureEvent($event, $hint);
        });
    }
    /**
     * Translates the Monolog level into the Sentry severity.
     *
     * @param int $level The Monolog log level
     */
    private static function getSeverityFromLevel(int $level) : \Sentry\Severity
    {
        switch ($level) {
            case \Monolog\Logger::DEBUG:
                return \Sentry\Severity::debug();
            case \Monolog\Logger::WARNING:
                return \Sentry\Severity::warning();
            case \Monolog\Logger::ERROR:
                return \Sentry\Severity::error();
            case \Monolog\Logger::CRITICAL:
            case \Monolog\Logger::ALERT:
            case \Monolog\Logger::EMERGENCY:
                return \Sentry\Severity::fatal();
            case \Monolog\Logger::INFO:
            case \Monolog\Logger::NOTICE:
            default:
                return \Sentry\Severity::info();
        }
    }
    /**
     * @param mixed[] $context
     *
     * @return mixed[]
     */
    private function getMonologContextData(array $context) : array
    {
        if (!$this->fillExtraContext) {
            return [];
        }
        $contextData = [];
        foreach ($context as $key => $value) {
            // We skip the `exception` field because it goes in its own context
            if (self::CONTEXT_EXCEPTION_KEY === $key) {
                continue;
            }
            $contextData[$key] = $value;
        }
        return $contextData;
    }
}