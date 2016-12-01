<?php
namespace Wwwision\CrTest\Domain\Aggregate\Workspace;

use Neos\Cqrs\EventStore\ExpectedVersion;
use Neos\Flow\Annotations as Flow;
use Wwwision\CrTest\Projection\Node\NodeFinder;

/**
 * @Flow\Scope("singleton")
 */
final class WorkspaceCommandHandler
{
    /**
     * @Flow\Inject
     * @var WorkspaceRepository
     */
    protected $workspaceRepository;

    /**
     * @Flow\Inject
     * @var NodeFinder
     */
    protected $nodeFinder;

    public function handleCreateWorkspace(Command\CreateWorkspace $command)
    {
        $workspace = Workspace::create($command->getWorkspaceId());
        $this->workspaceRepository->save($workspace, ExpectedVersion::NO_STREAM);
    }
}