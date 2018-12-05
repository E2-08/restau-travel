<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181114163313 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, flag VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language_restaurant (language_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_AB8C792282F1BAF4 (language_id), INDEX IDX_AB8C7922B1E7706E (restaurant_id), PRIMARY KEY(language_id, restaurant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE language_restaurant ADD CONSTRAINT FK_AB8C792282F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE language_restaurant ADD CONSTRAINT FK_AB8C7922B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE language_restaurant DROP FOREIGN KEY FK_AB8C792282F1BAF4');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE language_restaurant');
    }
}
