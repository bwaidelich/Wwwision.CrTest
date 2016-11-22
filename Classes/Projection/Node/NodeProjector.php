<?php
namespace Wwwision\CrTest\Projection\Node;

use Neos\Cqrs\EventStore\ExpectedVersion;
use Neos\Cqrs\EventStore\RawEvent;
use Neos\Cqrs\Projection\Doctrine\AbstractDoctrineProjector;
use TYPO3\Flow\Annotations as Flow;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasCreated;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasDiscarded;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasPublishedTo;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasRenamed;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\SiteNodeWasCreated;

/**
 * @method Node get($identifier)
 */
class NodeProjector extends AbstractDoctrineProjector
{
    public function whenSiteNodeWasCreated(SiteNodeWasCreated $event)
    {
        $nodeContextId = $event->getNodeId() . '@' . $event->getWorkspaceId();
        $node = new Node($nodeContextId, $event->getName());
        // site nodes are (currently) published implicitly
        $node->_setPublishedVersion(0);
        $this->add($node);
    }

    public function whenNodeWasCreated(NodeWasCreated $event)
    {
        $nodeContextId = $event->getNodeId() . '@' . $event->getWorkspaceId();
        $this->add(new Node($nodeContextId, $event->getName()));
    }

    public function whenNodeWasPublishedTo(NodeWasPublishedTo $event, RawEvent $storedEvent)
    {
        $sourceNode = $this->getNodeFromDifferentWorkspace($event->getNodeContextId(), $event->getSourceWorkspaceId());
        $targetNode = $this->get($event->getNodeContextId());
        if ($targetNode === null) {
            $targetNode = new Node($event->getNodeContextId(), $sourceNode->getName());
            $targetNode->_setPublishedVersion($storedEvent->getVersion());
            $this->add($targetNode);
        } else {
            $targetNode->_setName($sourceNode->getName());
            $targetNode->_setPublishedVersion($storedEvent->getVersion());
            $this->update($targetNode);
        }
        $this->remove($sourceNode);
    }

    public function whenNodeWasRenamed(NodeWasRenamed $event)
    {
        $node = $this->get($event->getNodeContextId());
        if ($node === null) {
            // possible race condition? can we instead fetch the published version from the write side?
            $baseNode = $this->getNodeFromDifferentWorkspace($event->getNodeContextId(), 'live');
            $publishedVersion = $baseNode !== null ? $baseNode->getPublishedVersion() : ExpectedVersion::NO_STREAM;
            $node = new Node($event->getNodeContextId(), $event->getNewName());
            $node->_setPublishedVersion($publishedVersion);
            $this->add($node);
        } else {
            $node->_setName($event->getNewName());
            $this->update($node);
        }
    }

    public function whenNodeWasDiscarded(NodeWasDiscarded $event)
    {
        $node = $this->get($event->getNodeContextId());
        $this->remove($node);
    }

    private function getNodeFromDifferentWorkspace(string $nodeContextId, string $workspaceId)
    {
        // HACK
        list($nodeId) = explode('@', $nodeContextId);
        return $this->get($nodeId . '@' . $workspaceId);
    }
}