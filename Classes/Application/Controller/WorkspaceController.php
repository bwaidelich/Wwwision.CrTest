<?php
namespace Wwwision\CrTest\Application\Controller;

use Neos\Cqrs\EventStore\Exception\ConcurrencyException;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\Controller\ActionController;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\PublishNode;
use Wwwision\CrTest\Domain\Aggregate\Workspace\Command\DiscardWorkspace;
use Wwwision\CrTest\Domain\Aggregate\Workspace\Command\PublishWorkspace;
use Wwwision\CrTest\Domain\Aggregate\Workspace\Command\PublishWorkspacePartially;
use Wwwision\CrTest\Domain\Aggregate\Workspace\WorkspaceCommandHandler;
use Wwwision\CrTest\Projection\Node\NodeFinder;

class WorkspaceController extends ActionController
{

    /**
     * @Flow\Inject
     * @var NodeFinder
     */
    protected $nodeFinder;

    /**
     * @Flow\Inject
     * @var WorkspaceCommandHandler
     */
    protected $workspaceCommandHandler;

    /**
     * @param string $workspaceId
     * @return void
     */
    public function publishPreviewAction($workspaceId)
    {
        $this->view->assignMultiple([
            'workspaceId' => $workspaceId,
            'nodes' => $this->nodeFinder->findByWorkspaceId($workspaceId)
        ]);
    }

    /**
     * @param PublishWorkspace $command
     * @return void
     */
    public function publishAction(PublishWorkspace $command)
    {
        $result = $this->workspaceCommandHandler->handlePublishWorkspace($command);
        if ($result->hasNotices()) {
            $this->flashMessageContainer->addMessage($result->getFirstNotice());
        } else {
            $this->addFlashMessage('Published all nodes from workspace "%s" to workspace "%s"', 'Success', Message::SEVERITY_OK, [$command->getWorkspaceId(), $command->getTargetWorkspaceId()]);
        }
        $this->redirect('index', 'Node');
    }

    /**
     * @param PublishWorkspacePartially $command
     * @return void
     */
    public function publishPartiallyAction(PublishWorkspacePartially $command)
    {
        $result = $this->workspaceCommandHandler->handlePublishWorkspacePartially($command);
        if ($result->hasNotices()) {
            $this->flashMessageContainer->addMessage($result->getFirstNotice());
        } else {
            $this->addFlashMessage('Published selected nodes from workspace "%s" to workspace "%s"', 'Success', Message::SEVERITY_OK, [$command->getWorkspaceId(), $command->getTargetWorkspaceId()]);
        }
        $this->redirect('index', 'Node');
    }

    /**
     * @param DiscardWorkspace $command
     * @return void
     */
    public function discardAction(DiscardWorkspace $command)
    {
        $result = $this->workspaceCommandHandler->handleDiscardWorkspace($command);
        if ($result->hasNotices()) {
            $this->flashMessageContainer->addMessage($result->getFirstNotice());
        } else {
            $this->addFlashMessage('Discarded all node changes in workspace "%s"', 'Success', Message::SEVERITY_OK, [$command->getWorkspaceId()]);
        }
        $this->redirect('index', 'Node');
    }

    /**
     * @return bool
     */
    protected function getErrorFlashMessage()
    {
        return false;
    }


}