<?php

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractMigration;

final class Version20250313000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create sylius_banner table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sylius_banner (
            id INT AUTO_INCREMENT NOT NULL,
            title VARCHAR(255) DEFAULT NULL,
            subtitle VARCHAR(500) DEFAULT NULL,
            link VARCHAR(500) DEFAULT NULL,
            image_path VARCHAR(500) DEFAULT NULL,
            position VARCHAR(50) DEFAULT \'hero\' NOT NULL,
            enabled TINYINT(1) DEFAULT 1 NOT NULL,
            sort_order INT DEFAULT 0 NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE sylius_banner');
    }
}
