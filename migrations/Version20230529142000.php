<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230529142000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE api_token (id BIGSERIAL NOT NULL, user_id BIGINT DEFAULT NULL, token VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX api_token__token_unq ON api_token (token)');
        $this->addSql('CREATE UNIQUE INDEX api_token__user_id_unq ON api_token (user_id)');
        $this->addSql('ALTER TABLE api_token ADD CONSTRAINT api_token__user_id_fk FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE api_token DROP CONSTRAINT api_token__user_id_fk');
        $this->addSql('DROP TABLE api_token');
    }
}
