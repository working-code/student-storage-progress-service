<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230713075617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_course (id BIGSERIAL NOT NULL, user_id BIGINT NOT NULL, course_id BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX user_course__user_id__ind ON user_course (user_id)');
        $this->addSql('CREATE INDEX user_course__course_id__ind ON user_course (course_id)');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT user_course__user_id__fk FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT user_course__course_id__fk FOREIGN KEY (course_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_course DROP CONSTRAINT user_course__user_id__fk');
        $this->addSql('ALTER TABLE user_course DROP CONSTRAINT user_course__course_id__fk');
        $this->addSql('DROP TABLE user_course');
    }
}
