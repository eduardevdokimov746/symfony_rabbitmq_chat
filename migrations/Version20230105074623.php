<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230105074623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create chats table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE chats (id UUID NOT NULL, chat_type_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D68180F894240FA ON chats (chat_type_id)');
        $this->addSql('COMMENT ON COLUMN chats.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN chats.chat_type_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE
          chats
        ADD
          CONSTRAINT FK_2D68180F894240FA FOREIGN KEY (chat_type_id) REFERENCES chat_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE chats DROP CONSTRAINT FK_2D68180F894240FA');
        $this->addSql('DROP TABLE chats');
    }
}
