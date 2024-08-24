<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240824075532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE courses_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE enrollments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE students_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE courses (id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN courses.start_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN courses.end_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE enrollments (id INT NOT NULL, course_id_id INT NOT NULL, enrollment_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, completion_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, grade NUMERIC(5, 2) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CCD8C13296EF99BF ON enrollments (course_id_id)');
        $this->addSql('COMMENT ON COLUMN enrollments.enrollment_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN enrollments.completion_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE enrollments_students (enrollments_id INT NOT NULL, students_id INT NOT NULL, PRIMARY KEY(enrollments_id, students_id))');
        $this->addSql('CREATE INDEX IDX_C264F4C21B4748EB ON enrollments_students (enrollments_id)');
        $this->addSql('CREATE INDEX IDX_C264F4C21AD8D010 ON enrollments_students (students_id)');
        $this->addSql('CREATE TABLE students (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON students (email)');
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
        $this->addSql('ALTER TABLE enrollments ADD CONSTRAINT FK_CCD8C13296EF99BF FOREIGN KEY (course_id_id) REFERENCES courses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE enrollments_students ADD CONSTRAINT FK_C264F4C21B4748EB FOREIGN KEY (enrollments_id) REFERENCES enrollments (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE enrollments_students ADD CONSTRAINT FK_C264F4C21AD8D010 FOREIGN KEY (students_id) REFERENCES students (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE courses_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE enrollments_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE students_id_seq CASCADE');
        $this->addSql('ALTER TABLE enrollments DROP CONSTRAINT FK_CCD8C13296EF99BF');
        $this->addSql('ALTER TABLE enrollments_students DROP CONSTRAINT FK_C264F4C21B4748EB');
        $this->addSql('ALTER TABLE enrollments_students DROP CONSTRAINT FK_C264F4C21AD8D010');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE enrollments');
        $this->addSql('DROP TABLE enrollments_students');
        $this->addSql('DROP TABLE students');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
