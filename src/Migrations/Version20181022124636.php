<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181022124636 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE servicelanguage (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_6F17C254B1E7706E (restaurant_id), INDEX IDX_6F17C25482F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notice (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, user_id INT DEFAULT NULL, comment LONGTEXT NOT NULL, note INT NOT NULL, INDEX IDX_480D45C2B1E7706E (restaurant_id), INDEX IDX_480D45C2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, url VARCHAR(255) NOT NULL, caption VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6AB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, flag VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, timesolt VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, slug VARCHAR(255) NOT NULL, coverimages VARCHAR(255) NOT NULL, INDEX IDX_EB95123FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, booking_at DATETIME NOT NULL, bookingtime TIME NOT NULL, number INT NOT NULL, bookingstart TIME NOT NULL, bookingend TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking_user (booking_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9502F4073301C60 (booking_id), INDEX IDX_9502F407A76ED395 (user_id), PRIMARY KEY(booking_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking_restaurant (booking_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_720F714E3301C60 (booking_id), INDEX IDX_720F714EB1E7706E (restaurant_id), PRIMARY KEY(booking_id, restaurant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, referance VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, hash VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, subscriber_id INT NOT NULL, offer_id INT NOT NULL, created_at DATETIME NOT NULL, duration INT NOT NULL, subscription_start DATETIME NOT NULL, subscription_end DATETIME NOT NULL, UNIQUE INDEX UNIQ_A3C664D37808B1AD (subscriber_id), UNIQUE INDEX UNIQ_A3C664D353C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE servicelanguage ADD CONSTRAINT FK_6F17C254B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE servicelanguage ADD CONSTRAINT FK_6F17C25482F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE booking_user ADD CONSTRAINT FK_9502F4073301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE booking_user ADD CONSTRAINT FK_9502F407A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE booking_restaurant ADD CONSTRAINT FK_720F714E3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE booking_restaurant ADD CONSTRAINT FK_720F714EB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D37808B1AD FOREIGN KEY (subscriber_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D353C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE servicelanguage DROP FOREIGN KEY FK_6F17C25482F1BAF4');
        $this->addSql('ALTER TABLE servicelanguage DROP FOREIGN KEY FK_6F17C254B1E7706E');
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C2B1E7706E');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AB1E7706E');
        $this->addSql('ALTER TABLE booking_restaurant DROP FOREIGN KEY FK_720F714EB1E7706E');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D37808B1AD');
        $this->addSql('ALTER TABLE booking_user DROP FOREIGN KEY FK_9502F4073301C60');
        $this->addSql('ALTER TABLE booking_restaurant DROP FOREIGN KEY FK_720F714E3301C60');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D353C674EE');
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C2A76ED395');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123FF675F31B');
        $this->addSql('ALTER TABLE booking_user DROP FOREIGN KEY FK_9502F407A76ED395');
        $this->addSql('DROP TABLE servicelanguage');
        $this->addSql('DROP TABLE notice');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE booking_user');
        $this->addSql('DROP TABLE booking_restaurant');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE subscription');
    }
}
