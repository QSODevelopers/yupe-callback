<?php

class m150417_101106_new_table_callback extends yupe\components\DbMigration
{

	public function safeUp()
	{
		$this->createTable('{{callback}}', [
			'id'               => 'pk',
			'name'             => 'varchar(50) NOT NULL',
			'description'      => 'string NOT NULL',
			'code'             => 'varchar(50) NOT NULL',
			'type'             => 'varchar(25) NOT NULL',
			'title'            => 'text NOT NULL',
			'template'         => 'string NOT NULL',
			'button_options'   => 'text NOT NULL',
			'modal_options'    => 'text NOT NULL',
			'form_options'     => 'text NOT NULL',
			'template_options' => 'text NOT NULL',
			'mail_options'     => 'text NOT NULL',
			'status'           => 'enum("0","1") NOT NULL DEFAULT "1"',
			'success_message'  => 'text NOT NULL',
			'error_message'    => 'text NOT NULL',
		], $this->getOptions());
	}

	public function safeDown()
	{
		$this->dropTable('{{callback}}');
	}
}