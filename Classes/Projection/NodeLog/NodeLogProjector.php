<?php
namespace Wwwision\CrTest\Projection\NodeLog;

use Neos\Cqrs\EventStore\RawEvent;
use Neos\Cqrs\Projection\Doctrine\AbstractAsynchronousDoctrineProjector;
use Neos\Flow\Annotations as Flow;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasMaterialized;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasCreated;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasDiscarded;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasPublishedFrom;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasPublishedTo;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasRenamed;

/**
 * @method NodeEvent get($identifier)
 */
class NodeLogProjector extends AbstractAsynchronousDoctrineProjector
{
    /**
     * @var string
     */
    protected $readModelClassName = NodeEvent::class;

    public function whenNodeWasCreated(NodeWasCreated $event, RawEvent $rawEvent)
    {
        $this->logEvent($event->getNodeId(), $event->getWorkspaceId(), $rawEvent);
    }

    public function whenNodeWasDiscarded(NodeWasDiscarded $event, RawEvent $rawEvent)
    {
        $this->logEvent($event->getNodeId(), $event->getWorkspaceId(), $rawEvent);
    }

//    public function whenNodeWasMaterialized(NodeWasMaterialized $event, RawEvent $rawEvent)
//    {
//        $this->logEvent($event->getNodeId(), $event->getWorkspaceId(), $rawEvent);
//    }

    public function whenNodeWasPublishedFrom(NodeWasPublishedFrom $event, RawEvent $rawEvent)
    {
        $this->logEvent($event->getNodeId(), $event->getWorkspaceId(), $rawEvent);
    }

    public function whenNodeWasPublishedTo(NodeWasPublishedTo $event, RawEvent $rawEvent)
    {
        $this->logEvent($event->getNodeId(), $event->getWorkspaceId(), $rawEvent);
    }

    public function whenNodeWasRenamed(NodeWasRenamed $event, RawEvent $rawEvent)
    {
        $this->logEvent($event->getNodeId(), $event->getWorkspaceId(), $rawEvent);
    }

    private function logEvent(string $nodeId, string $workspaceId, RawEvent $rawEvent)
    {
        $nodeEvent = new NodeEvent($rawEvent->getSequenceNumber(), $rawEvent->getRecordedAt(), $nodeId, $workspaceId, $rawEvent->getType(), $rawEvent->getPayload());
        $this->add($nodeEvent);
    }
}