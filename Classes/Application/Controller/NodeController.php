<?php
namespace Wwwision\CrTest\Application\Controller;

use Neos\Cqrs\EventStore\Exception\ConcurrencyException;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Annotations as Flow;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\CreateNode;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\DiscardNode;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\MoveNode;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\PublishNode;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\RenameNode;
use Wwwision\CrTest\Domain\Aggregate\Node\NodeCommandHandler;
use Wwwision\CrTest\Projection\Node\NodeFinder;
use Wwwision\CrTest\Projection\Workspace\WorkspaceFinder;

class NodeController extends ActionController
{

    /**
     * @Flow\Inject
     * @var WorkspaceFinder
     */
    protected $workspaceFinder;

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

    /**
     * @return void
     */
    public function indexAction()
    {
        $workspaces = $this->workspaceFinder->findAll();
        $workspacesAndNodes = [];
        foreach ($workspaces as $workspace) {
            $workspacesAndNodes[] = [
                'workspace' => $workspace,
                'nodes' => $this->nodeFinder->findByWorkspaceIdWithFallbacks($workspace->getId()),
            ];
        }
        $this->view->assignMultiple([
            'workspacesAndNodes' => $workspacesAndNodes
        ]);
    }

    /**
     * @param CreateNode $command
     * @return void
     */
    public function createAction(CreateNode $command)
    {
        $this->nodeCommandHandler->handleCreateNode($command);
        $this->addFlashMessage('Created node "%s" in workspace "%s"', 'Success', Message::SEVERITY_OK, [$command->getNodeId(), $command->getWorkspaceId()]);
        $this->redirect('index');
    }

    /**
     * @param RenameNode $command
     * @return void
     */
    public function renameAction(RenameNode $command)
    {
        $this->nodeCommandHandler->handleRenameNode($command);
        $this->addFlashMessage('Renamed node "%s@%s" to "%s"', 'Success', Message::SEVERITY_OK, [$command->getNodeId(), $command->getWorkspaceId(), $command->getNewName()]);
        $this->redirect('index');
    }

    /**
     * @param MoveNode $command
     * @return void
     */
    public function moveAction(MoveNode $command)
    {
        $this->nodeCommandHandler->handleMoveNode($command);
        $this->addFlashMessage('Moved node "%s" %s "%s"', 'Success', Message::SEVERITY_OK, [$command->getNodeId(), $command->getPosition(), $command->getReferenceNodeId()]);
        $this->redirect('index');
    }

    /**
     * @param PublishNode $command
     * @return void
     */
    public function publishAction(PublishNode $command)
    {
        try {
            $this->nodeCommandHandler->handlePublishNode($command);
        } catch (ConcurrencyException $exception) {
            $this->redirect('mergePreview', null, null, ['nodeId' => $command->getNodeId(), 'workspaceId' => $command->getSourceWorkspaceId()]);
        }
        $this->addFlashMessage('Published node "%s@%s" to workspace "%s"', 'Success', Message::SEVERITY_OK, [$command->getNodeId(), $command->getSourceWorkspaceId(), $command->getTargetWorkspaceId()]);
        $this->redirect('index');
    }



    /**
     * @param string $nodeId
     * @param string $workspaceId
     * @return void
     */
    public function mergePreviewAction(string $nodeId, string $workspaceId)
    {
        $this->view->assignMultiple([
            'baseNode' => $this->nodeFinder->findOneByIdAndWorkspaceId($nodeId, 'live'),
            'node' => $this->nodeFinder->findOneByIdAndWorkspaceId($nodeId, $workspaceId),
        ]);
    }

    /**
     * @param DiscardNode $command
     */
    public function discardAction(DiscardNode $command)
    {
        $this->nodeCommandHandler->handleDiscardNode($command);
        $this->addFlashMessage('Discarded local changes of node "%s@%s"', 'Notice', Message::SEVERITY_NOTICE, [$command->getNodeId(), $command->getWorkspaceId()]);
        $this->redirect('index');
    }

    /**
     * @return bool
     */
    protected function getErrorFlashMessage()
    {
        return false;
    }


}