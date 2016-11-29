<?php
namespace Wwwision\CrTest\Domain\Aggregate\NodeTree\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodesWerePublishedTo implements EventInterface
{
    /**
     * @var string[]
     */
    private $nodeIds;

    /**
     * Workspace id of the nodes that were published to
     *
     * @var string
     */
    private $workspaceId;

    /**
     * Workspace id of the nodes that were published from
     *
     * @var string
     */
    private $sourceWorkspaceId;


    /**
     * @param array<string> $nodeIds
     * @param string $workspaceId
     * @param string $sourceWorkspaceId
     */
    public function __construct(array $nodeIds, string $workspaceId, string $sourceWorkspaceId)
    {
        $this->nodeIds = $nodeIds;
        $this->workspaceId = $workspaceId;
        $this->sourceWorkspaceId = $sourceWorkspaceId;
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

    public function getSourceWorkspaceId(): string
    {
        return $this->sourceWorkspaceId;
    }

}