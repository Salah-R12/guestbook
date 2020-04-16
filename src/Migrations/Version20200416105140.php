<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200416105140 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE bb_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE f_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE kk_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ll_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE p_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE vc_id_seq CASCADE');
        $this->addSql('DROP TABLE bb');
        $this->addSql('DROP TABLE f');
        $this->addSql('DROP TABLE kk');
        $this->addSql('DROP TABLE ll');
        $this->addSql('DROP TABLE p');
        $this->addSql('DROP TABLE vc');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE bb_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE f_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE kk_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ll_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE p_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE vc_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bb (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE f (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE kk (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ll (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE p (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE vc (id INT NOT NULL, PRIMARY KEY(id))');
    }
}
