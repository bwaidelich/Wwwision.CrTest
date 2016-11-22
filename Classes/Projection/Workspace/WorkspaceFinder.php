<?php
namespace Wwwision\CrTest\Projection\Workspace;

use Neos\Cqrs\Projection\Doctrine\AbstractDoctrineFinder;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\QueryInterface;

/**
 * @Flow\Scope("singleton")
 */
class WorkspaceFinder extends AbstractDoctrineFinder
{

    protected $defaultOrderings = [
        'id' => QueryInterface::ORDER_DESCENDING
    ];

}