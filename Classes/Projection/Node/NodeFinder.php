<?php
namespace Wwwision\CrTest\Projection\Node;

use Neos\Cqrs\Projection\Doctrine\AbstractDoctrineFinder;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class NodeFinder extends AbstractDoctrineFinder
{

    /**
     * @param string $contextId
     * @return Node
     */
    public function findOneByContextId(string $contextId)
    {
        $query = $this->createQuery();
        /** @var Node $node */
        $node = $query->matching(
            $query->equals('contextId', $contextId)
        )
            ->execute()
            ->getFirst();
        return $node;
    }

    /**
     * @param string $workspaceId
     * @return Node[] indexed by the node identifier
     */
    public function findByWorkspaceId(string $workspaceId): array
    {
        $query = $this->createQuery();
        $nodes = $query->matching(
            $query->equals('workspaceId', $workspaceId)
        )
            ->execute()
            ->toArray();
        return array_reduce($nodes, function (array $result, Node $node) {
            $result[$node->getId()] = $node;
            return $result;
        }, []);
    }

    /**
     * @param string $workspaceId
     * @return Node[]
     */
    public function findByWorkspaceIdWithFallbacks(string $workspaceId): array
    {
        $fallbackNodes = $this->findByWorkspaceId('live');
        if ($workspaceId === 'live') {
            return $fallbackNodes;
        }
        $workspaceNodes = $this->findByWorkspaceId($workspaceId);
        return array_merge($fallbackNodes, $workspaceNodes);
    }

}