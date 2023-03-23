<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230323115341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE achievement (id BIGSERIAL NOT NULL, title VARCHAR(120) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE skill (id BIGSERIAL NOT NULL, name VARCHAR(120) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE skill_assessment (id BIGSERIAL NOT NULL, skill_id BIGINT DEFAULT NULL, task_assessment_id BIGINT DEFAULT NULL, skill_value SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE task (id BIGSERIAL NOT NULL, title VARCHAR(120) NOT NULL, content TEXT NOT NULL, type SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE parent_children (parent_id BIGINT NOT NULL, child_id BIGINT NOT NULL, PRIMARY KEY(parent_id, child_id))');
        $this->addSql('CREATE INDEX IDX_3E5A1D6B727ACA70 ON parent_children (parent_id)');
        $this->addSql('CREATE INDEX IDX_3E5A1D6BDD62C21B ON parent_children (child_id)');
        $this->addSql('CREATE TABLE task_assessment (id BIGSERIAL NOT NULL, user_id BIGINT DEFAULT NULL, task_id BIGINT DEFAULT NULL, assessment SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX task_assessment__user_id__ind ON task_assessment (user_id)');
        $this->addSql('CREATE INDEX task_assessment__task_id__ind ON task_assessment (task_id)');
        $this->addSql('CREATE TABLE task_setting (id BIGSERIAL NOT NULL, task_id BIGINT DEFAULT NULL, skill_id BIGINT DEFAULT NULL, value_percentage SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX task_setting__skill_id__ind ON task_setting (skill_id)');
        $this->addSql('CREATE INDEX task_setting__task_id__ind ON task_setting (task_id)');
        $this->addSql('CREATE TABLE "user" (id BIGSERIAL NOT NULL, surname VARCHAR(120) NOT NULL, name VARCHAR(120) NOT NULL, patronymic VARCHAR(120) NOT NULL, email VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_achievement (id BIGSERIAL NOT NULL, user_id BIGINT DEFAULT NULL, achievement_id BIGINT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX user_achievement__user_id__ind ON user_achievement (user_id)');
        $this->addSql('CREATE INDEX user_achievement__achievement_id__ind ON user_achievement (achievement_id)');
        $this->addSql('ALTER TABLE skill_assessment ADD CONSTRAINT skill_assessment__skill_id__fk FOREIGN KEY (skill_id) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE skill_assessment ADD CONSTRAINT skill_assessment__task_assessment_id__fk FOREIGN KEY (task_assessment_id) REFERENCES task_assessment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE parent_children ADD CONSTRAINT parent_children__parent_id__fk FOREIGN KEY (parent_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE parent_children ADD CONSTRAINT parent_children__child_id__fk FOREIGN KEY (child_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_assessment ADD CONSTRAINT task_assessment__user_id__fk FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_assessment ADD CONSTRAINT task_assessment__task_id__fk FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_setting ADD CONSTRAINT task_setting__task_id__fk FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_setting ADD CONSTRAINT task_setting__skill_id__fk FOREIGN KEY (skill_id) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_achievement ADD CONSTRAINT user_achievement__user_id__fk FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_achievement ADD CONSTRAINT user_achievement__achievement_id__fk FOREIGN KEY (achievement_id) REFERENCES achievement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE skill_assessment DROP CONSTRAINT skill_assessment__skill_id__fk');
        $this->addSql('ALTER TABLE skill_assessment DROP CONSTRAINT skill_assessment__task_assessment_id__fk');
        $this->addSql('ALTER TABLE parent_children DROP CONSTRAINT parent_children__parent_id__fk');
        $this->addSql('ALTER TABLE parent_children DROP CONSTRAINT parent_children__child_id__fk');
        $this->addSql('ALTER TABLE task_assessment DROP CONSTRAINT task_assessment__user_id__fk');
        $this->addSql('ALTER TABLE task_assessment DROP CONSTRAINT task_assessment__task_id__fk');
        $this->addSql('ALTER TABLE task_setting DROP CONSTRAINT task_setting__task_id__fk');
        $this->addSql('ALTER TABLE task_setting DROP CONSTRAINT task_setting__skill_id__fk');
        $this->addSql('ALTER TABLE user_achievement DROP CONSTRAINT user_achievement__user_id__fk');
        $this->addSql('ALTER TABLE user_achievement DROP CONSTRAINT user_achievement__achievement_id__fk');
        $this->addSql('DROP TABLE achievement');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE skill_assessment');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE parent_children');
        $this->addSql('DROP TABLE task_assessment');
        $this->addSql('DROP TABLE task_setting');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_achievement');
    }
}
