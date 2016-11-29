<?php
namespace Wwwision\CrTest\Domain\Aggregate\NodeTree\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodesWerePublishedFrom implements EventInterface
{
    /**
     * @var string[]
     */
    private $nodeIds;

    /**
     * Workspace id of the nodes that were published from
     *
     * @var string
     */
    private $workspaceId;

    /**
     * Workspace id of the nodes that were published to
     *
     * @var string
     */
    private $targetWorkspaceId;


    /**
     * @param array<string> $nodeIds
     * @param string $workspaceId
     * @param string $targetWorkspaceId
     */
    public function __construct(array $nodeIds, string $workspaceId, string $targetWorkspaceId)
    {
        $this->nodeIds = $nodeIds;
        $this->workspaceId = $workspaceId;
        $this->targetWorkspaceId = $targetWorkspaceId;
    }

    /**
     * @return string[]
     */
    public function getNodeIds(): array
    {
        return $this->nodeIds;
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

    public function getTargetWorkspaceId(): string
    {
        return $this->targetWorkspaceId;
    }

}