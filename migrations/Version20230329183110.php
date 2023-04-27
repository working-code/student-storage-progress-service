<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Achievement;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230329183110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO achievement (id, title, description) VALUES (nextval(\'achievement_id_seq\'), :title, :description)', [
            'title' => Achievement::NAME_SILVER,
            'description' => 'Сдано домашние задание 9 баллов',
        ]);
        $this->addSql('INSERT INTO achievement (id, title, description) VALUES (nextval(\'achievement_id_seq\'), :title, :description)', [
            'title' => Achievement::NAME_GOLD,
            'description' => 'Сдано домашние задание на 10 баллов',
        ]);
        $this->addSql('INSERT INTO achievement (id, title, description) VALUES (nextval(\'achievement_id_seq\'), :title, :description)', [
            'title' => Achievement::NAME_SUPER_SILVER,
            'description' => 'Все домашние задания сданы на 9 баллов',
        ]);
        $this->addSql('INSERT INTO achievement (id, title, description) VALUES (nextval(\'achievement_id_seq\'), :title, :description)', [
            'title' => Achievement::NAME_SUPER_GOLD,
            'description' => 'Все домашние задания сданы на 10 баллов',
        ]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE achievement CASCADE');
    }
}
