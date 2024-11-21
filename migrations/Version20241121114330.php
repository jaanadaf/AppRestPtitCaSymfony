<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20241121114330 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // Code précédent...

        // Crée la table restaurant si elle n'existe pas déjà
        $this->addSql('CREATE TABLE IF NOT EXISTS restaurant (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            address VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        // Code pour supprimer la table si la migration est annulée
        $this->addSql('DROP TABLE IF EXISTS restaurant');
    }
}
