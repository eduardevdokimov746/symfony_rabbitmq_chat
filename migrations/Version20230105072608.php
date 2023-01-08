<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230105072608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create users table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (
          id UUID NOT NULL,
          login VARCHAR(20) NOT NULL,
          password VARCHAR(255) NOT NULL,
          first_name VARCHAR(60) NOT NULL,
          last_name VARCHAR(60) DEFAULT NULL,
          avatar VARCHAR(60) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9AA08CB10 ON users (login)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
