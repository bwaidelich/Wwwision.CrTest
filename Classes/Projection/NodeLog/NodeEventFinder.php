<?php
namespace Wwwision\CrTest\Projection\NodeLog;

use Neos\Cqrs\Projection\Doctrine\AbstractDoctrineFinder;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Persistence\QueryResultInterface;

/**
 * @Flow\Scope("singleton")
 */
class NodeEventFinder extends AbstractDoctrineFinder
{

    protected $defaultOrderings = [
        'sequenceNumber' => QueryInterface::ORDER_DESCENDING
    ];

    /**
     * @param string|null $nodeId
     * @param string|null $workspaceId
     * @return QueryResultInterface|NodeEvent[]
     */
    public function findByNodeIdAndWorkspaceId(string $nodeId = null, string $workspaceId = null)
    {
        $query = $this->createQuery();
        $constraints = [];
        if (!empty($nodeId)) {
            $constraints[] = $query->equals('nodeId', $nodeId);
        }
        if (!empty($workspaceId)) {
            $constraints[] = $query->equals('workspaceId', $workspaceId);
        }
        if ($constraints === []) {
            return $query->execute();
        }

        return $query->matching(
            $query->logicalAnd(
                $constraints
            )
        )
        ->execute();
    }

}