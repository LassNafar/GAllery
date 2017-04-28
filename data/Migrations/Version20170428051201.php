<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170428051201 extends AbstractMigration
{
    public function getDescription()
    {
        $description = 'This is the initial migration which creates blog tables.';
        return $description;
    }
    
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Создаем таблицу 'albom'
        $table = $schema->createTable('albom');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);        
        $table->addColumn('name', 'string', ['notnull'=>true, 'lenght'=>128]);
        $table->addColumn('image', 'string', ['notnull'=>true, 'lenght'=>512]);
        $table->addColumn('priority', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');
        
        // Создаем таблицу 'author'
        $table = $schema->createTable('author');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]); 
        $table->addColumn('name', 'string', ['notnull'=>true, 'lenght'=>128]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');
        
        // Создаем таблицу 'albom_author'
        $table = $schema->createTable('post_tag');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]); 
        $table->addColumn('albom_id', 'integer', ['notnull'=>true]);
        $table->addColumn('author_id', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');
        
        // Создаем таблицу 'users'
        $table = $schema->createTable('users');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]); 
        $table->addColumn('login', 'string', ['notnull'=>true, 'lenght'=>128]);
        $table->addColumn('name', 'string', ['notnull'=>true, 'lenght'=>512]);
        $table->addColumn('password', 'string', ['notnull'=>true, 'lenght'=>256]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('albom_author');
        $schema->dropTable('author');
        $schema->dropTable('albom');
        $schema->dropTable('users');
    }
}
