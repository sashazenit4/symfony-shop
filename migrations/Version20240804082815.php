<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240804082815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE book_authors_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE books_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE book_authors (id INT NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, second_name VARCHAR(255) DEFAULT NULL, birth_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN book_authors.birth_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE books (id INT NOT NULL, title VARCHAR(255) NOT NULL, publish_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN books.publish_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE books_book_authors (books_id INT NOT NULL, book_authors_id INT NOT NULL, PRIMARY KEY(books_id, book_authors_id))');
        $this->addSql('CREATE INDEX IDX_C50FB1977DD8AC20 ON books_book_authors (books_id)');
        $this->addSql('CREATE INDEX IDX_C50FB197F24F4B89 ON books_book_authors (book_authors_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE books_book_authors ADD CONSTRAINT FK_C50FB1977DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE books_book_authors ADD CONSTRAINT FK_C50FB197F24F4B89 FOREIGN KEY (book_authors_id) REFERENCES book_authors (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE book_authors_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE books_id_seq CASCADE');
        $this->addSql('ALTER TABLE books_book_authors DROP CONSTRAINT FK_C50FB1977DD8AC20');
        $this->addSql('ALTER TABLE books_book_authors DROP CONSTRAINT FK_C50FB197F24F4B89');
        $this->addSql('DROP TABLE book_authors');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE books_book_authors');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
