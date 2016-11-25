<?php
namespace Wwwision\CrTest\Domain\Aggregate\Workspace;

use Neos\Cqrs\EventStore\ExpectedVersion;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Notice;
use TYPO3\Flow\Error\Result;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\DiscardNode;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\PublishNode;
use Wwwision\CrTest\Domain\Aggregate\Node\NodeCommandHandler;
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

    /**
     * @Flow\Inject
     * @var NodeCommandHandler
     */
    protected $nodeCommandHandler;

    public function handleCreateWorkspace(Command\CreateWorkspace $command)
    {
        $workspace = Workspace::create($command->getWorkspaceId());
        $this->workspaceRepository->save($workspace, ExpectedVersion::NO_STREAM);
    }

    public function handlePublishWorkspace(Command\PublishWorkspace $command): Result
    {
        $result = new Result();
        // HACK This feels wrong
        $nodes = $this->nodeFinder->findByWorkspaceId($command->getWorkspaceId());
        $numberOfNodes = count($nodes);
        if ($numberOfNodes === 0) {
            $result->addNotice(new Notice('Workspace "%s" does not contain any unpublished changes.', 1480064043, [$command->getWorkspaceId()], 'No changes'));
            return $result;
        }
        foreach ($nodes as $node) {
            $publishCommand = new PublishNode($node->getId(), $command->getWorkspaceId(), $command->getTargetWorkspaceId());
            $this->nodeCommandHandler->handlePublishNode($publishCommand);
        }
        return $result;
    }

    public function handlePublishWorkspacePartially(Command\PublishWorkspacePartially $command): Result
    {
        $result = new Result();
        #$this->eventStore->startTransaction();
        $numberOfNodes = 0;
        foreach ($command->getNodes() as $node) {
            if (!$node['include']) {
                continue;
            }
            // HACK This feels wrong
            $nodeInstance = $this->nodeFinder->findOneByIdAndWorkspaceId($node['id'], $command->getWorkspaceId());
            if ($nodeInstance === null) {
                // TODO exception?
                continue;
            }
            $publishCommand = new PublishNode($node['id'], $command->getWorkspaceId(), $command->getTargetWorkspaceId(), $node['publishedVersion']);
            $this->nodeCommandHandler->handlePublishNode($publishCommand);
            $numberOfNodes ++;

        }
        #$this->eventStore->commitTransaction();
        if ($numberOfNodes === 0) {
            $result->addNotice(new Notice('No nodes were published.', 1480064430, [], 'No changes'));
        }
        return $result;
    }

    public function handleDiscardWorkspace(Command\DiscardWorkspace $command): Result
    {
        $result = new Result();
        // HACK This feels wrong
        $nodes = $this->nodeFinder->findByWorkspaceId($command->getWorkspaceId());
        $numberOfNodes = count($nodes);
        if ($numberOfNodes === 0) {
            $result->addNotice(new Notice('Workspace "%s" does not contain any unpublished changes.', 1480064917, [$command->getWorkspaceId()], 'No changes'));
            return $result;
        }
        foreach ($nodes as $node) {
            $discardCommand = new DiscardNode($node->getId(), $command->getWorkspaceId());
            $this->nodeCommandHandler->handleDiscardNode($discardCommand);
        }
        return $result;
    }
}