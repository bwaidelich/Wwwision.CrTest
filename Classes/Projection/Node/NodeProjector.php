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

    public function whenNodeWasCreated(NodeWasCreated $event)
    {
        $newNode = new Node($event->getNodeId(), $event->getWorkspaceId(), $event->getName());
        // if the new node is created in live workspace, set its published version to 0 (default = -1)
        if ($event->getWorkspaceId() === 'live') {
            $newNode->_setPublishedVersion(0);
        }
        $this->add($newNode);
    }

    public function whenNodeWasPublishedTo(NodeWasPublishedTo $event, RawEvent $storedEvent)
    {
        $sourceNode = $this->getNodeFromDifferentWorkspace($event->getNodeId(), $event->getSourceWorkspaceId());
        $targetNode = $this->get(['id' => $event->getNodeId(), 'workspaceId' => $event->getWorkspaceId()]);
        if ($targetNode === null) {
            $targetNode = new Node($event->getNodeId(), $event->getWorkspaceId(), $sourceNode->getName());
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
        $node = $this->get(['id' => $event->getNodeId(), 'workspaceId' => $event->getWorkspaceId()]);
        if ($node === null) {
            $baseNode = $this->getNodeFromDifferentWorkspace($event->getNodeId(), 'live');
            $publishedVersion = $baseNode !== null ? $baseNode->getPublishedVersion() : ExpectedVersion::NO_STREAM;
            $node = new Node($event->getNodeId(), $event->getWorkspaceId(), $event->getNewName());
            $node->_setPublishedVersion($publishedVersion);
            $this->add($node);
        } else {
            $node->_setName($event->getNewName());
            $this->update($node);
        }
    }

    public function whenNodeWasDiscarded(NodeWasDiscarded $event)
    {
        $node = $this->get(['id' => $event->getNodeId(), 'workspaceId' => $event->getWorkspaceId()]);
        $this->remove($node);
    }

    /**
     * HACK possible race condition? can we instead fetch the published version from the write side?
     *
     * @param string $nodeId
     * @param string $workspaceId
     * @return Node
     */
    private function getNodeFromDifferentWorkspace(string $nodeId, string $workspaceId)
    {
        return $this->get(['id' => $nodeId, 'workspaceId' => $workspaceId]);
    }
}