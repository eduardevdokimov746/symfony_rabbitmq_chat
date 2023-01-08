<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230105073509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create chat_types table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE chat_types (id UUID NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_584D64AB5E237E06 ON chat_types (name)');
        $this->addSql('COMMENT ON COLUMN chat_types.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE chat_types');
    }
}
