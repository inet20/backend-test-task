<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240428190316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create table for product and coupon entities';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product (id INT NOT NULL, title VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');

        $this->addSql('CREATE TABLE coupon (id INT NOT NULL, code VARCHAR(255) NOT NULL, used BOOLEAN NOT NULL, type INT NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX coupon_index ON coupon (used, code)');
        $this->addSql('CREATE SEQUENCE coupon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP TABLE product');

        $this->addSql('DROP SEQUENCE coupon_id_seq CASCADE');
        $this->addSql('DROP INDEX coupon_index');
        $this->addSql('DROP TABLE coupon');
    }
}
