<?php
namespace Wwwision\CrTest\Application\Controller;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\Controller\ActionController;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\DiscardNodeTree;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\PublishNodeTree;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\PublishNodeTreePartially;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\NodeTreeCommandHandler;
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
     * @Flow\Inject
     * @var NodeTreeCommandHandler
     */
    protected $nodeTreeCommandHandler;


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
     * @param PublishNodeTree $command
     * @return void
     */
    public function publishAction(PublishNodeTree $command)
    {
        $this->nodeTreeCommandHandler->handlePublish($command);
        $this->addFlashMessage('Published node tree "%s" to workspace "%s"', 'Success', Message::SEVERITY_OK, [$command->getWorkspaceId(), $command->getTargetWorkspaceId()]);
        $this->redirect('index', 'Node');
    }

    /**
     * @param PublishNodeTreePartially $command
     * @return void
     */
    public function publishPartiallyAction(PublishNodeTreePartially $command)
    {
        $this->nodeTreeCommandHandler->handlePublishPartially($command);
        $this->addFlashMessage('Published selected nodes from workspace "%s" to workspace "%s"', 'Success', Message::SEVERITY_OK, [$command->getWorkspaceId(), $command->getTargetWorkspaceId()]);
        $this->redirect('index', 'Node');
    }

    /**
     * @param DiscardNodeTree $command
     * @return void
     */
    public function discardAction(DiscardNodeTree $command)
    {
        $this->nodeTreeCommandHandler->handleDiscard($command);
        $this->addFlashMessage('Discarded all node changes in workspace "%s"', 'Success', Message::SEVERITY_OK, [$command->getWorkspaceId()]);
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