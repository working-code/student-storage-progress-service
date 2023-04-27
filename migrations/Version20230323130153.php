<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230323130153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create concurrently indices';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX CONCURRENTLY skill_assessment__skill_id__ind ON skill_assessment (skill_id)');
        $this->addSql('CREATE INDEX CONCURRENTLY skill_assessment__task_assessment_id__ind ON skill_assessment (task_assessment_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX skill_assessment__skill_id__ind');
        $this->addSql('DROP INDEX skill_assessment__task_assessment_id__ind');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
