<?php
namespace Wwwision\CrTest\Domain\Aggregate\NodeTree\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodeTreeWasPublishedTo implements EventInterface
{
    /**
     * Workspace id of the node that was published to
     *
     * @var string
     */
    private $workspaceId;

    /**
     * Workspace id of the node that was published from
     *
     * @var string
     */
    private $sourceWorkspaceId;

    public function __construct(string $workspaceId, string $sourceWorkspaceId)
    {
        $this->workspaceId = $workspaceId;
        $this->sourceWorkspaceId = $sourceWorkspaceId;
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