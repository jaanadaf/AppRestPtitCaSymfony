<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119164503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Si "datedecreation" n'existe pas, ne la supprimez pas
        $this->addSql('ALTER TABLE restaurant ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE nombivtmaximum nombre_maximum INT NOT NULL');
    }
    
    
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant ADD datedecreation DATETIME NOT NULL, ADD datedemiseajour DATETIME NOT NULL, DROP created_at, DROP updated_at, CHANGE nombre_maximum nombivtmaximum INT NOT NULL');
    }
}
