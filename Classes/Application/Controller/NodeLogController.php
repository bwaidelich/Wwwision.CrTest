<?php
namespace Wwwision\CrTest\Application\Controller;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use Wwwision\CrTest\Projection\NodeLog\NodeEventFinder;

class NodeLogController extends ActionController
{
    /**
     * @Flow\Inject
     * @var NodeEventFinder
     */
    protected $nodeEventFinder;

    /**
     * @param string $nodeId
     * @param string $workspaceId
     * @return void
     */
    public function indexAction($nodeId = null, $workspaceId = null)
    {
        $this->view->assignMultiple([
            'events' => $this->nodeEventFinder->findByNodeIdAndWorkspaceId($nodeId, $workspaceId),
            'nodeId' => $nodeId,
            'workspaceId' => $workspaceId
        ]);
    }

    /**
     * @return bool
     */
    protected function getErrorFlashMessage()
    {
        return false;
    }


}