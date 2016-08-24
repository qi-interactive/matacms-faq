<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

use yii\db\Schema;
use yii\db\Migration;

class m160824_114850 extends Migration {

	public function safeUp() {
		$this->createTable('{{%matacms_faq_subject}}', [
			'Id'		=> Schema::TYPE_PK,
			'Subject'   => Schema::TYPE_STRING . '(255) NOT NULL',
			]);

		$this->createTable('{{%matacms_faq_question}}', [
			'Id'                 => Schema::TYPE_PK,
			'Question'           => Schema::TYPE_STRING . '(255) NOT NULL',
			'Answer'             => Schema::TYPE_TEXT . ' NOT NULL',
			'SubjectId'          => Schema::TYPE_INTEGER . ' NOT NULL',
			]);
	}

	public function safeDown() {
		$this->dropTable('{{%matacms_faq_subject}}');
		$this->dropTable('{{%matacms_faq_question}}');
	}
}
