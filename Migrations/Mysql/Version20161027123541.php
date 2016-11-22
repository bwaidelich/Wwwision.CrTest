<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20161027123541 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Initial "Node" and "Workspace" read models';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('CREATE TABLE wwwision_crtest_workspace (id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wwwision_crtest_node (contextid VARCHAR(255) NOT NULL, id VARCHAR(255) NOT NULL, workspaceid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, publishedversion INT NOT NULL, PRIMARY KEY(contextid)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('DROP TABLE wwwision_crtest_node');
        $this->addSql('DROP TABLE wwwision_crtest_workspace');
    }
}