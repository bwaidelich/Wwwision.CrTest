<?php
namespace Wwwision\CrTest\Domain\Aggregate\Workspace\Event;

use Neos\Cqrs\Event\EventInterface;

final class WorkspaceWasCreated implements EventInterface
{
    /**
     * @var string
     */
    private $workspaceId;

    public function __construct(string $workspaceId)
    {
        $this->workspaceId = $workspaceId;
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

}