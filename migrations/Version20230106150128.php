<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230106150128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users_chats (
          chat_id UUID NOT NULL,
          user_id UUID NOT NULL,
          PRIMARY KEY(chat_id, user_id)
        )');
        $this->addSql('CREATE INDEX IDX_CA1FBC461A9A7125 ON users_chats (chat_id)');
        $this->addSql('CREATE INDEX IDX_CA1FBC46A76ED395 ON users_chats (user_id)');
        $this->addSql('COMMENT ON COLUMN users_chats.chat_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users_chats.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE messages (
          id UUID NOT NULL,
          chat_id UUID NOT NULL,
          sender_id UUID NOT NULL,
          body TEXT NOT NULL,
          published_at TIMESTAMP(0)
          WITH
            TIME ZONE NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_DB021E961A9A7125 ON messages (chat_id)');
        $this->addSql('CREATE INDEX IDX_DB021E96F624B39D ON messages (sender_id)');
        $this->addSql('COMMENT ON COLUMN messages.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN messages.chat_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN messages.sender_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN messages.published_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE
          users_chats
        ADD
          CONSTRAINT FK_CA1FBC461A9A7125 FOREIGN KEY (chat_id) REFERENCES chats (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          users_chats
        ADD
          CONSTRAINT FK_CA1FBC46A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          messages
        ADD
          CONSTRAINT FK_DB021E961A9A7125 FOREIGN KEY (chat_id) REFERENCES chats (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          messages
        ADD
          CONSTRAINT FK_DB021E96F624B39D FOREIGN KEY (sender_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users_chats DROP CONSTRAINT FK_CA1FBC461A9A7125');
        $this->addSql('ALTER TABLE users_chats DROP CONSTRAINT FK_CA1FBC46A76ED395');
        $this->addSql('ALTER TABLE messages DROP CONSTRAINT FK_DB021E961A9A7125');
        $this->addSql('ALTER TABLE messages DROP CONSTRAINT FK_DB021E96F624B39D');
        $this->addSql('DROP TABLE users_chats');
        $this->addSql('DROP TABLE messages');
    }
}
