<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230427103628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_skill (id BIGSERIAL NOT NULL, user_id BIGINT DEFAULT NULL, skill_id BIGINT DEFAULT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX user_skill__user_id__ind ON user_skill (user_id)');
        $this->addSql('CREATE INDEX user_skill__skill_id__ind ON user_skill (skill_id)');
        $this->addSql('CREATE UNIQUE INDEX user_skill__user_id_skill_id__un_ind ON user_skill (user_id, skill_id)');
        $this->addSql('ALTER TABLE user_skill ADD CONSTRAINT user_skill__user_id__fk FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_skill ADD CONSTRAINT user_skill__skill_id__fk FOREIGN KEY (skill_id) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_skill DROP CONSTRAINT user_skill__user_id__fk');
        $this->addSql('ALTER TABLE user_skill DROP CONSTRAINT user_skill__skill_id__fk');
        $this->addSql('DROP TABLE user_skill');
    }
}
