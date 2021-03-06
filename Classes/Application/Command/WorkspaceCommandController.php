<?php
namespace Wwwision\CrTest\Application\Command;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Wwwision\CrTest\Domain\Aggregate\Workspace\Command\CreateWorkspace;
use Wwwision\CrTest\Domain\Aggregate\Workspace\WorkspaceCommandHandler;

class WorkspaceCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var WorkspaceCommandHandler
     */
    protected $workspaceCommandHandler;

    /**
     * @param string $workspaceId
     */
    public function createCommand(string $workspaceId)
    {
        $command = new CreateWorkspace($workspaceId);
        $this->workspaceCommandHandler->handleCreateWorkspace($command);
        $this->outputLine('Created workspace "%s"', [$workspaceId]);
    }
}