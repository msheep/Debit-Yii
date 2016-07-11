<?php

/**
 * This is the model class for table "{{admin_log_service}}".
 *
 * The followings are the available columns in table '{{admin_log_service}}':
 * @property integer $id
 * @property integer $userId
 * @property integer $type
 * @property string $title
 * @property string $msg
 * @property string $ctime
 */
class AdminLogService extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AdminLogService the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{admin_log_service}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, type', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>50),
			array('msg', 'length', 'max'=>500),
			array('ctime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, type, title, msg, ctime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userId' => 'User',
			'type' => 'Type',
			'title' => 'Title',
			'msg' => 'Msg',
			'ctime' => 'Ctime',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('type',$this->type);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('msg',$this->msg,true);
		$criteria->compare('ctime',$this->ctime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}