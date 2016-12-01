<?php
namespace Wwwision\CrTest\Projection\Workspace;

use Neos\Cqrs\Projection\Doctrine\AbstractDoctrineFinder;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;

/**
 * @Flow\Scope("singleton")
 */
class WorkspaceFinder extends AbstractDoctrineFinder
{

    protected $defaultOrderings = [
        'id' => QueryInterface::ORDER_DESCENDING
    ];

}