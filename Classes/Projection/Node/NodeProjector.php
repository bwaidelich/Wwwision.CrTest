<?php
namespace Wwwision\CrTest\Projection\Node;

use Doctrine\Common\Persistence\ObjectManager as DoctrineObjectManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Neos\Cqrs\EventStore\ExpectedVersion;
use Neos\Cqrs\EventStore\RawEvent;
use Neos\Cqrs\Projection\Doctrine\AbstractDoctrineProjector;
use Neos\Flow\Annotations as Flow;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodesWerePublishedTo;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeTreeWasDiscarded;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeTreeWasPublishedFrom;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeTreeWasPublishedTo;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasCreated;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasDiscarded;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasPublishedTo;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasRenamed;

/**
 * @method Node get($identifier)
 */
class NodeProjector extends AbstractDoctrineProjector
{

    /**
     * @var DoctrineEntityManager
     */
    private $entityManager;

    /**
     * @param DoctrineObjectManager $entityManager
     * @return void
     */
    public function injectEntityManager(DoctrineObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function whenNodeWasCreated(NodeWasCreated $event)
    {
        $newNode = new Node($event->getNodeId(), $event->getWorkspaceId(), $event->getName());
        // if the new node is created in live workspace, set its published version to 0 (default = -1)
        if ($event->getWorkspaceId() === 'live') {
            $newNode->_setPublishedVersion(0);
        }
        $this->add($newNode);
    }

    public function whenNodeWasPublishedTo(NodeWasPublishedTo $event, RawEvent $rawEvent)
    {
        $sourceNode = $this->getNodeFromDifferentWorkspace($event->getNodeId(), $event->getSourceWorkspaceId());
        $targetNode = $this->get(['id' => $event->getNodeId(), 'workspaceId' => $event->getWorkspaceId()]);
        if ($targetNode === null) {
            $targetNode = new Node($event->getNodeId(), $event->getWorkspaceId(), $sourceNode->getName());
            $targetNode->_setPublishedVersion($rawEvent->getVersion());
            $this->add($targetNode);
        } else {
            $targetNode->_setName($sourceNode->getName());
            $targetNode->_setPublishedVersion($rawEvent->getVersion());
            $this->update($targetNode);
        }
        $this->remove($sourceNode);
    }

    public function whenNodesWerePublishedTo(NodesWerePublishedTo $event, RawEvent $rawEvent)
    {
        // HACK
        $this->entityManager->flush();
        $this->entityManager->getConnection()->executeUpdate('UPDATE wwwision_crtest_node tn JOIN wwwision_crtest_node sn ON (sn.id = tn.id) SET tn.name = sn.name, tn.publishedversion = :version WHERE sn.workspaceid = :sourceWorkspaceId AND tn.workspaceid = :targetWorkspaceId AND sn.id IN (:nodeIds)', ['version' => $rawEvent->getVersion(), 'sourceWorkspaceId' => $event->getSourceWorkspaceId(), 'targetWorkspaceId' => $event->getWorkspaceId(), 'nodeIds' => $event->getNodeIds()], ['nodeIds' =>  Connection::PARAM_STR_ARRAY]);
        $this->entityManager->getConnection()->executeQuery('DELETE FROM wwwision_crtest_node WHERE workspaceid = :workspaceId AND id IN (:nodeIds)', ['workspaceId' => $event->getSourceWorkspaceId(), 'nodeIds' => $event->getNodeIds()], ['nodeIds' =>  Connection::PARAM_STR_ARRAY]);
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    public function whenNodeTreeWasPublishedTo(NodeTreeWasPublishedTo $event, RawEvent $rawEvent)
    {
        // HACK
        $this->entityManager->flush();
        $this->entityManager->getConnection()->executeUpdate('UPDATE wwwision_crtest_node tn JOIN wwwision_crtest_node sn ON (sn.id = tn.id) SET tn.name = sn.name, tn.publishedversion = :version WHERE sn.workspaceid = :sourceWorkspaceId AND tn.workspaceid = :targetWorkspaceId', ['version' => $rawEvent->getVersion(), 'sourceWorkspaceId' => $event->getSourceWorkspaceId(), 'targetWorkspaceId' => $event->getWorkspaceId()]);
        $this->entityManager->getConnection()->executeQuery('DELETE FROM wwwision_crtest_node WHERE workspaceid = :workspaceId', ['workspaceId' => $event->getSourceWorkspaceId()]);
        $this->entityManager->flush();
        $this->entityManager->clear();
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

    public function whenNodeTreeWasDiscarded(NodeTreeWasDiscarded $event, RawEvent $rawEvent)
    {
        // HACK
        $this->entityManager->flush();
        $query = $this->entityManager->createQuery('DELETE FROM ' . $this->readModelClassName . ' n WHERE n.workspaceId = :workspaceId');
        $query->execute(['workspaceId' => 'user2']);
        $this->entityManager->flush();
        $this->entityManager->clear();
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