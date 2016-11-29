<?php
namespace Wwwision\CrTest\Domain\Aggregate\NodeTree\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodeTreeWasPublishedFrom implements EventInterface
{

    /**
     * Workspace id of the node that was published from
     *
     * @var string
     */
    private $workspaceId;

    /**
     * Workspace id of the node that was published to
     *
     * @var string
     */
    private $targetWorkspaceId;


    public function __construct(string $workspaceId, string $targetWorkspaceId)
    {
        $this->workspaceId = $workspaceId;
        $this->targetWorkspaceId = $targetWorkspaceId;
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