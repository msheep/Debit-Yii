/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : thor

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2013-09-10 10:03:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `p2p_admin_log_service`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_admin_log_service`;
CREATE TABLE `p2p_admin_log_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '日志类型（1:身份认证；2：户籍认证...）',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '日志主题',
  `msg` varchar(500) NOT NULL DEFAULT '' COMMENT '日志内容',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '插入时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_admin_log_service
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_block_money_detail`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_block_money_detail`;
CREATE TABLE `p2p_block_money_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0冻结1解除冻结',
  `money` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '支出金额',
  `debitId` int(11) NOT NULL COMMENT '还款类型下指代借款明细',
  `debitFinancingId` int(11) NOT NULL DEFAULT '0' COMMENT '借贷明细表id（）',
  `userId` int(10) NOT NULL DEFAULT '0' COMMENT '出账人',
  `startTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '冻结开始',
  `endTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '冻结结束',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_block_money_detail
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_debit`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_debit`;
CREATE TABLE `p2p_debit` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cat` tinyint(2) NOT NULL DEFAULT '0' COMMENT '借贷产品id',
  `productId` tinyint(11) NOT NULL DEFAULT '0' COMMENT '具体产品id',
  `borrowerId` int(11) NOT NULL DEFAULT '0' COMMENT '借款人id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '借款标题',
  `debitMoney` int(11) NOT NULL DEFAULT '0' COMMENT '借款金额',
  `debitRate` float(7,5) NOT NULL DEFAULT '0.00000' COMMENT '借款利率',
  `debitDeadline` tinyint(2) NOT NULL DEFAULT '0' COMMENT '借款期限',
  `invitDeadline` tinyint(2) NOT NULL DEFAULT '0' COMMENT '招标期限',
  `debitPurpose` varchar(300) NOT NULL DEFAULT '' COMMENT '贷款用途',
  `repayDate` tinyint(2) NOT NULL DEFAULT '0' COMMENT '每月还款日',
  `debitProgress` int(11) NOT NULL DEFAULT '0' COMMENT '已经收到的借款额度',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态（0:审核中；1：招标中；-1：招标失败；2：还款中；3：还款结束）',
  `verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核（0：否；1：是）',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '插入时间',
  `utime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `vtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '审核通过时间',
  `fee` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `operatorId` int(11) NOT NULL DEFAULT '0' COMMENT '操作人id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_debit
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_debit_financing_record`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_debit_financing_record`;
CREATE TABLE `p2p_debit_financing_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `debitId` int(11) NOT NULL DEFAULT '0' COMMENT '借贷id',
  `debitMoney` int(11) NOT NULL DEFAULT '0' COMMENT '借出的钱',
  `lenderId` int(11) NOT NULL DEFAULT '0' COMMENT '出借人id',
  `vertify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核（0：否；1：是）',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '插入时间',
  `operatorId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_debit_financing_record
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_debit_product_car`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_debit_product_car`;
CREATE TABLE `p2p_debit_product_car` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '抵押人id',
  `carId` varchar(30) NOT NULL DEFAULT '' COMMENT '汽车发动机号',
  `brand` varchar(20) NOT NULL DEFAULT '' COMMENT '品牌',
  `color` char(12) NOT NULL DEFAULT '' COMMENT '颜色',
  `year` int(4) NOT NULL DEFAULT '0' COMMENT '购买年份',
  `mileage` int(8) NOT NULL DEFAULT '0' COMMENT '行驶里程数',
  `isAuto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否自动档（0：自动；1：手动）',
  `drivingLicense` varchar(30) NOT NULL DEFAULT '' COMMENT '驾驶证号',
  `accident` tinyint(1) NOT NULL DEFAULT '0' COMMENT '事故情况（0：无重大事故，仅有小碰擦；1：无重大事故，无碰擦；2：中等事故；3：有重大事故）',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '插入时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_debit_product_car
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_debit_product_domain`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_debit_product_domain`;
CREATE TABLE `p2p_debit_product_domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '抵押人id',
  `domain` varchar(100) NOT NULL DEFAULT '' COMMENT '网址',
  `owner` varchar(50) NOT NULL DEFAULT '' COMMENT '所有人',
  `serviceProvider` varchar(50) NOT NULL DEFAULT '' COMMENT '服务提供商',
  `deadLine` date NOT NULL DEFAULT '0000-00-00' COMMENT '域名截止时间',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '插入时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_debit_product_domain
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_debit_product_property`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_debit_product_property`;
CREATE TABLE `p2p_debit_product_property` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '抵押人id',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '具体地址',
  `area` float(10,1) NOT NULL DEFAULT '0.0' COMMENT '房屋面积',
  `year` int(4) NOT NULL DEFAULT '0' COMMENT '年代',
  `propertyCertificateId` varchar(50) NOT NULL DEFAULT '' COMMENT '房产证号',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '插入时间',
  `areaId` char(4) NOT NULL DEFAULT '' COMMENT '地址id',
  `provinceId` char(4) NOT NULL DEFAULT '',
  `cityId` char(4) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_debit_product_property
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_debit_repay_record`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_debit_repay_record`;
CREATE TABLE `p2p_debit_repay_record` (
  `id` int(11) NOT NULL COMMENT '主键',
  `debitId` int(11) NOT NULL DEFAULT '0' COMMENT '借贷id',
  `repayMoney` int(11) NOT NULL DEFAULT '0' COMMENT '还款金额',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否还款（0：否；1：是）',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '插入时间',
  `utime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_debit_repay_record
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_education_auth`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_education_auth`;
CREATE TABLE `p2p_education_auth` (
  `userId` int(11) NOT NULL,
  `degree` tinyint(1) NOT NULL DEFAULT '0' COMMENT '学历：0自学，1小学，2初中，3中专，4高中，5大专，6本科，7硕士，8博士',
  `school` varchar(100) NOT NULL DEFAULT '' COMMENT '学校',
  `degreeNo` varchar(100) NOT NULL DEFAULT '' COMMENT '学位证书编号',
  `schoolStartTime` date NOT NULL DEFAULT '0000-00-00' COMMENT '入学时间',
  `schoolEndTime` date NOT NULL DEFAULT '0000-00-00' COMMENT '毕业时间',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '认证创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '认证状态：0未认证1已认证',
  `operatorId` int(11) NOT NULL DEFAULT '0' COMMENT '操作人id',
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_education_auth
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_file`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_file`;
CREATE TABLE `p2p_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host` varchar(20) NOT NULL DEFAULT 'f1' COMMENT '文件服务器',
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '文件创建人',
  `path` varchar(500) NOT NULL DEFAULT '',
  `category` tinyint(1) NOT NULL DEFAULT '0' COMMENT '文件类型：0身份认证图片1户籍认证图片2学历认证图片3视频认证。。。',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_file
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_identity_auth`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_identity_auth`;
CREATE TABLE `p2p_identity_auth` (
  `userId` int(11) NOT NULL,
  `realName` varchar(20) NOT NULL DEFAULT '',
  `identityNo` varchar(18) NOT NULL DEFAULT '',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '认证创建时间',
  `fileId` int(11) NOT NULL DEFAULT '0' COMMENT '文件id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '认证状态：0未认证1已认证',
  `operatorId` int(11) NOT NULL DEFAULT '0' COMMENT '操作人id',
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='身份认证表';

-- ----------------------------
-- Records of p2p_identity_auth
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_income_payout_detail`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_income_payout_detail`;
CREATE TABLE `p2p_income_payout_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收入类型：0充值，1借款方还款，2投标失败退回（流标）3融资成功收到钱,支出类型：4还款，5提现，6出借',
  `money` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '收入数额',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `debitId` int(11) DEFAULT NULL COMMENT '收入类型为借款方还款和流标时，关联对应的借款条目',
  `orderNo` varchar(20) DEFAULT NULL COMMENT '充值类型时指代充值的流水账号',
  `orderSource` varchar(20) DEFAULT NULL COMMENT '充值类型的资金来源（渠道），一般为银行名称',
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '记录关联用户id',
  `category` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收支类型：0收入1支出',
  `operatorId` int(11) NOT NULL DEFAULT '0' COMMENT '操作人id',
  `debitRepayId` int(11) DEFAULT NULL COMMENT '关联还款id',
  `debitFinancingId` int(11) DEFAULT NULL COMMENT '关联的出借明细id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收入支出表';

-- ----------------------------
-- Records of p2p_income_payout_detail
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_resident_auth`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_resident_auth`;
CREATE TABLE `p2p_resident_auth` (
  `userId` int(10) NOT NULL COMMENT '用户表id',
  `maritalStatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '婚姻状况：1已婚，0未婚',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '认证创建时间',
  `telphone` varchar(100) NOT NULL DEFAULT '' COMMENT '固定电话',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '常用邮箱',
  `hoursing` tinyint(1) NOT NULL DEFAULT '0' COMMENT '住房状况：0租房，1自购商品房',
  `cats` tinyint(1) NOT NULL DEFAULT '0' COMMENT '车辆数目状况',
  `monthIncome` tinyint(1) NOT NULL DEFAULT '0' COMMENT '月平均存款状况',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '认证状态：0未认证1已认证',
  `liveAreaId` char(4) NOT NULL DEFAULT '' COMMENT '居住地区域',
  `liveProvinceId` char(4) NOT NULL DEFAULT '' COMMENT '居住地省',
  `liveCityId` char(4) NOT NULL DEFAULT '' COMMENT '居住地城市',
  `liveAddress` varchar(100) NOT NULL DEFAULT '' COMMENT '居住地详细地址',
  `householdAreaId` char(4) NOT NULL DEFAULT '' COMMENT '户口所在地区域',
  `householdProvinceId` char(4) NOT NULL DEFAULT '' COMMENT '户口所在地省',
  `householdCityId` char(4) NOT NULL DEFAULT '' COMMENT '户口所在地市',
  `hourseholdAddress` varchar(100) NOT NULL DEFAULT '' COMMENT '户口所在地详细地址',
  `operatorId` int(11) NOT NULL DEFAULT '0' COMMENT '操作人id',
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_resident_auth
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_user`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_user`;
CREATE TABLE `p2p_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id，自增主键',
  `userName` varchar(100) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(100) NOT NULL DEFAULT '' COMMENT '密码',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态：1正常，0失效',
  `identityAuth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '身份认证：0未验证，1已验证',
  `residentAuth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '户籍认证：0未验证，1已验证',
  `educationAuth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '学历认证：0未验证，1已验证',
  `videoAuth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '视频认证：0未验证，1已验证',
  `blockMoney` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `cashMoney` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '现金账户余额（不包括冻结金额）',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of p2p_user
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_user_notify`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_user_notify`;
CREATE TABLE `p2p_user_notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(40) NOT NULL DEFAULT '' COMMENT '消息主题',
  `msg` varchar(300) NOT NULL DEFAULT '' COMMENT '消息内容',
  `isRead` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户是否阅读（0：否；1：是）',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '插入时间',
  `utime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_user_notify
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_video_auth`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_video_auth`;
CREATE TABLE `p2p_video_auth` (
  `userId` int(11) NOT NULL COMMENT '用户id',
  `video` varchar(100) NOT NULL DEFAULT '' COMMENT '视频文件路径',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '认证创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '认证状态：0未认证1已认证',
  `operatorId` int(11) NOT NULL DEFAULT '0' COMMENT '操作人id',
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p2p_video_auth
-- ----------------------------

-- ----------------------------
-- Table structure for `p2p_withdraw_apply`
-- ----------------------------
DROP TABLE IF EXISTS `p2p_withdraw_apply`;
CREATE TABLE `p2p_withdraw_apply` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '申请人提现人id',
  `money` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `fee` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `utime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `operatorId` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '申请状态0申请中，1申请成功，-1申请失败',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现申请表';

-- ----------------------------
-- Records of p2p_withdraw_apply
-- ----------------------------
