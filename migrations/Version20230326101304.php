<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230326101304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE skill_assessment ALTER skill_id SET NOT NULL');
        $this->addSql('ALTER TABLE skill_assessment ALTER task_assessment_id SET NOT NULL');
        $this->addSql('ALTER TABLE task_assessment ALTER user_id SET NOT NULL');
        $this->addSql('ALTER TABLE task_assessment ALTER task_id SET NOT NULL');
        $this->addSql('ALTER TABLE task_setting ALTER task_id SET NOT NULL');
        $this->addSql('ALTER TABLE task_setting ALTER skill_id SET NOT NULL');
        $this->addSql('ALTER TABLE user_achievement ALTER user_id SET NOT NULL');
        $this->addSql('ALTER TABLE user_achievement ALTER achievement_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE skill_assessment ALTER skill_id DROP NOT NULL');
        $this->addSql('ALTER TABLE skill_assessment ALTER task_assessment_id DROP NOT NULL');
        $this->addSql('ALTER TABLE user_achievement ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE user_achievement ALTER achievement_id DROP NOT NULL');
        $this->addSql('ALTER TABLE task_setting ALTER task_id DROP NOT NULL');
        $this->addSql('ALTER TABLE task_setting ALTER skill_id DROP NOT NULL');
        $this->addSql('ALTER TABLE task_assessment ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE task_assessment ALTER task_id DROP NOT NULL');
    }
}
