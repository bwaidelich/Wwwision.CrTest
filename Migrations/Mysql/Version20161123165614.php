<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20161123165614 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Initial "NodeEvent" read model';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('CREATE TABLE wwwision_crtest_nodeevent (sequencenumber INTEGER(11) NOT NULL, timestamp DATETIME NOT NULL, nodeid VARCHAR(255) NOT NULL, workspaceid VARCHAR(255) NOT NULL, eventtype VARCHAR(255) NOT NULL, data LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', PRIMARY KEY(sequencenumber)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('DROP TABLE wwwision_crtest_nodeevent');
    }
}