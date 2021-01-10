/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : taoxiaobei

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 05/01/2021 19:38:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '手机号',
  `headimg` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `sex` int(11) NOT NULL DEFAULT 1 COMMENT '性别 1 男 2女',
  `manage_school` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '管理的市场id | 隔开',
  `operate_auth` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '操作权限',
  `status` int(4) NOT NULL COMMENT '状态 1正常 2禁用 3删除',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `login_time` int(10) NULL DEFAULT NULL COMMENT '上一次登录时间',
  `login_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '上一次登录ip',
  `type` tinyint(4) NOT NULL DEFAULT 2 COMMENT '类型 1 超级管理员 2 普通管理员',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'infinite', '63a9f0ea7bb98050796b649e85481845', '18383086596', NULL, 1, '0', '1', 1, 1597651301, 1609839182, '::1', 1);
INSERT INTO `admin` VALUES (2, '犁生', '202cb962ac59075b964b07152d234b70', '18380000011', NULL, 1, '1', '', 1, 1597651301, 1598004458, '::1', 2);
INSERT INTO `admin` VALUES (3, 'dahei', 'e10adc3949ba59abbe56e057f20f883e', '18383086596', NULL, 1, '2', '', 1, 1598009136, 1609750975, '192.168.43.39', 2);
INSERT INTO `admin` VALUES (4, 'sjhp', '202cb962ac59075b964b07152d234b70', '18383086595', NULL, 2, '4', '', 3, 1598009235, NULL, NULL, 2);
INSERT INTO `admin` VALUES (5, 'mo', '81dc9bdb52d04dc20036dbd8313ed055', '18386956333', NULL, 1, '1', NULL, 1, 1598018480, NULL, NULL, 2);
INSERT INTO `admin` VALUES (6, 'testest', '63a9f0ea7bb98050796b649e85481845', '18383086598', NULL, 1, '4', NULL, 1, 1602837803, NULL, NULL, 2);
INSERT INTO `admin` VALUES (7, 'test', '63a9f0ea7bb98050796b649e85481845', '18383086596', NULL, 2, '1', NULL, 3, 1602837855, NULL, NULL, 2);

-- ----------------------------
-- Table structure for category_01
-- ----------------------------
DROP TABLE IF EXISTS `category_01`;
CREATE TABLE `category_01`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '一级目录id',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '图标',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '详细描述',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '一级商品目录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of category_01
-- ----------------------------
INSERT INTO `category_01` VALUES (1, '美妆个护', '/images/tab1.png', '一级商品');
INSERT INTO `category_01` VALUES (2, '数码电子', '/images/tab2.png', '一级商品');
INSERT INTO `category_01` VALUES (3, '生活用品', '/images/tab3.png', '一级商品');
INSERT INTO `category_01` VALUES (4, '服装配饰', '/images/tab4.png', '一级商品');
INSERT INTO `category_01` VALUES (5, '二手书籍', '/images/tab5.png', '一级商品');
INSERT INTO `category_01` VALUES (6, '交通工具', '/images/tab6.png', '一级商品');
INSERT INTO `category_01` VALUES (7, '票务卡券', '/images/tab7.png', '一级商品');
INSERT INTO `category_01` VALUES (8, '其他类别', '/images/tab8.png', '一级商品');

-- ----------------------------
-- Table structure for category_02
-- ----------------------------
DROP TABLE IF EXISTS `category_02`;
CREATE TABLE `category_02`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '二级目录id',
  `pre_id` int(11) NOT NULL COMMENT '上级目录',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标题',
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '图标',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '二级商品目录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of category_02
-- ----------------------------

-- ----------------------------
-- Table structure for chat
-- ----------------------------
DROP TABLE IF EXISTS `chat`;
CREATE TABLE `chat`  (
  `chat_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '聊天id',
  `seller_user_id` int(11) NOT NULL COMMENT '卖家id',
  `buyer_user_id` int(11) NOT NULL COMMENT '买家id',
  `add_time` int(10) NOT NULL COMMENT '创建时间',
  `trade_id` int(11) NOT NULL COMMENT '订单id',
  `status` int(11) NOT NULL COMMENT '聊天状态 0 结束 1 正常',
  PRIMARY KEY (`chat_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '聊天表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of chat
-- ----------------------------

-- ----------------------------
-- Table structure for chatrecord
-- ----------------------------
DROP TABLE IF EXISTS `chatrecord`;
CREATE TABLE `chatrecord`  (
  `chatrecord_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '聊天记录id',
  `chat_id` int(11) NOT NULL COMMENT '聊天id',
  `chat_text` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '聊天文本',
  `receive_status` int(11) NOT NULL DEFAULT 0 COMMENT '接收状态 0 未接收  1 已接收',
  `send_time` int(10) NOT NULL COMMENT '发送时间',
  `from_user_id` int(11) NOT NULL COMMENT '发送方',
  `to_user_id` int(11) NOT NULL COMMENT '接收方',
  PRIMARY KEY (`chatrecord_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '聊天记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chatrecord
-- ----------------------------

-- ----------------------------
-- Table structure for funds
-- ----------------------------
DROP TABLE IF EXISTS `funds`;
CREATE TABLE `funds`  (
  `fund_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资金记录id',
  `grade_id` int(11) NOT NULL COMMENT '订单id',
  `grade_status` int(11) NOT NULL COMMENT '交易状态 0 未完成 1 已确认',
  `re_money` float(11, 0) NOT NULL COMMENT '入账金额',
  `re_time` int(10) NOT NULL COMMENT '入账时间',
  `ex_money` float(11, 0) NULL DEFAULT NULL,
  `ex_time` int(10) NULL DEFAULT NULL,
  `status` int(4) NOT NULL DEFAULT 0 COMMENT '状态 0 未出账 1 已出账',
  PRIMARY KEY (`fund_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '资金记录' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of funds
-- ----------------------------

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods`  (
  `goods_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `goods_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标题',
  `goods_num` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品编号 SP20181008xxxx',
  `user_id` int(11) NOT NULL COMMENT '卖家id',
  `category_id` int(11) NULL DEFAULT NULL COMMENT '商品类别',
  `price` float(10, 2) NOT NULL COMMENT '价格',
  `new_old_index` int(11) NOT NULL COMMENT '新旧程度',
  `click_num` int(11) NOT NULL DEFAULT 0 COMMENT '点击数',
  `follow_num` int(11) NOT NULL DEFAULT 0 COMMENT '收藏数',
  `school_id` int(11) NOT NULL COMMENT '所属学校id,与卖家所属学校id一致',
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图片',
  `goods_intro` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品描述',
  `key_words` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '关键词 | 分隔',
  `add_time` int(10) NOT NULL COMMENT '上传时间',
  `check_time` datetime NULL DEFAULT NULL COMMENT '审核时间/封禁时间',
  `goods_status` int(11) NOT NULL DEFAULT 0 COMMENT '商品状态 0 未审核  1 正常  2 已下单 3 已支付  4 已完成  -1 商品异常',
  PRIMARY KEY (`goods_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of goods
-- ----------------------------
INSERT INTO `goods` VALUES (3, '商品1', 'sp1201452235654', 1, 1, 25.68, 1, 0, 0, 1, '/test/xxx.img', '简单介绍一下', '商品', 1885522232, NULL, 1);
INSERT INTO `goods` VALUES (5, '吹风机', 'SP201911207814', 1, 2, 20.00, 8, 0, 0, 111, 'xxxxxxx', '松下水离子小功率宿舍用吹风机', '吹风机|宿舍|电器', 1574249308, NULL, 0);

-- ----------------------------
-- Table structure for new_old_grade
-- ----------------------------
DROP TABLE IF EXISTS `new_old_grade`;
CREATE TABLE `new_old_grade`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '新旧指数id',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '等级名称，如七五新',
  `standard` float NOT NULL COMMENT '新旧比例，取值（0，1]',
  `mark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '详细描述',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '新旧程度等级' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of new_old_grade
-- ----------------------------

-- ----------------------------
-- Table structure for operate_category
-- ----------------------------
DROP TABLE IF EXISTS `operate_category`;
CREATE TABLE `operate_category`  (
  `id` int(11) NULL DEFAULT NULL,
  `operate` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '操作描述'
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '操作类型表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of operate_category
-- ----------------------------

-- ----------------------------
-- Table structure for operate_log
-- ----------------------------
DROP TABLE IF EXISTS `operate_log`;
CREATE TABLE `operate_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `admin_id` int(11) NOT NULL COMMENT '操作人',
  `operated_user_id` int(11) NOT NULL COMMENT '操作对象id',
  `operate_category` int(11) NOT NULL COMMENT '操作类型',
  `add_time` int(10) NOT NULL COMMENT '操作时间',
  `status` int(11) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '操作日志' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of operate_log
-- ----------------------------

-- ----------------------------
-- Table structure for posts
-- ----------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts`  (
  `posts_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '楼id',
  `user_id` int(11) NOT NULL COMMENT '楼主id',
  `school_id` int(11) NOT NULL DEFAULT 0 COMMENT '所属学校id',
  `posts_title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '帖子主题',
  `posts_text` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '帖子正文',
  `add_time` int(10) NOT NULL COMMENT '发贴时间',
  `like_num` int(11) NOT NULL DEFAULT 0 COMMENT '点赞数',
  `dislike_num` int(11) NOT NULL DEFAULT 0 COMMENT '踩数',
  `share_num` int(11) NOT NULL DEFAULT 0 COMMENT '分享数',
  `post_category` int(11) NOT NULL DEFAULT 0 COMMENT '贴子类型 0 闲聊  1 求助  2 咨询',
  `storey_sum` int(255) NULL DEFAULT 0 COMMENT '层回复总数',
  `status` int(4) NOT NULL DEFAULT 0 COMMENT '楼状态',
  PRIMARY KEY (`posts_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '楼' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of posts
-- ----------------------------
INSERT INTO `posts` VALUES (1, 1, 111, '测试', '这是一段测试文本', 1574250928, 0, 0, 0, 0, 0, 0);

-- ----------------------------
-- Table structure for replay
-- ----------------------------
DROP TABLE IF EXISTS `replay`;
CREATE TABLE `replay`  (
  `replay_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '回复id',
  `storey_id` int(11) NOT NULL COMMENT '层id',
  `replayer_id` int(11) NOT NULL COMMENT '回复人id',
  `to_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '回复对象id',
  `add_time` int(10) NOT NULL COMMENT '回复时间',
  `like_num` int(11) NOT NULL DEFAULT 0 COMMENT '点赞数',
  `dislike_num` int(11) NOT NULL DEFAULT 0 COMMENT '踩数',
  `share_num` int(11) NOT NULL DEFAULT 0 COMMENT '分享数',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '回复内容',
  `status` int(4) NOT NULL DEFAULT 0 COMMENT '楼状态',
  PRIMARY KEY (`replay_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '回复' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of replay
-- ----------------------------

-- ----------------------------
-- Table structure for school
-- ----------------------------
DROP TABLE IF EXISTS `school`;
CREATE TABLE `school`  (
  `school_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '学校市场代号',
  `school_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学校名称',
  `school_badge` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '校徽',
  `background_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '背景图片',
  `goods_num` int(255) NOT NULL DEFAULT 0 COMMENT '商品总数',
  `posts_num` int(11) NOT NULL DEFAULT 0 COMMENT '贴子总数',
  `goods_status` int(4) NOT NULL DEFAULT 1 COMMENT '市场开放状态 0 关闭  1 开放',
  `posts_status` int(4) NOT NULL DEFAULT 1 COMMENT '校园通开放状态 0 关闭 1 开放',
  PRIMARY KEY (`school_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '学校表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of school
-- ----------------------------
INSERT INTO `school` VALUES (0, '全部区域', '', '', 0, 0, 0, 0);
INSERT INTO `school` VALUES (4, '成都理工大学', '/test/badge.img', '/test/back_ground_img', 0, 0, 1, 1);
INSERT INTO `school` VALUES (1, '电子科技大学成都学院', '/test/badge.img', '/test/back_ground_img', 0, 0, 1, 1);
INSERT INTO `school` VALUES (2, '西华大学', '/test/badge.img', '/test/back_ground_img', 0, 0, 1, 1);

-- ----------------------------
-- Table structure for storey
-- ----------------------------
DROP TABLE IF EXISTS `storey`;
CREATE TABLE `storey`  (
  `storey_id` int(11) NOT NULL AUTO_INCREMENT,
  `posts_id` int(11) NOT NULL COMMENT '楼id',
  `storeyer_id` int(11) NOT NULL COMMENT '层主id',
  `add_time` int(10) NOT NULL COMMENT '回复时间',
  `like_num` int(11) NOT NULL DEFAULT 0 COMMENT '点赞数',
  `dislike_num` int(11) NOT NULL DEFAULT 0 COMMENT '踩数',
  `share_num` int(11) NOT NULL DEFAULT 0 COMMENT '分享数',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '回复内容',
  `replay_sum` int(11) NOT NULL DEFAULT 0 COMMENT '回复总数',
  `status` int(4) NOT NULL DEFAULT 0 COMMENT '楼状态',
  PRIMARY KEY (`storey_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '层' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of storey
-- ----------------------------

-- ----------------------------
-- Table structure for system_log
-- ----------------------------
DROP TABLE IF EXISTS `system_log`;
CREATE TABLE `system_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `admin_id` int(11) NOT NULL COMMENT '管理员id',
  `operate` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '操作',
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `time` int(10) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 82 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_log
-- ----------------------------
INSERT INTO `system_log` VALUES (1, 2, '登录系统', '::1', 1597747405);
INSERT INTO `system_log` VALUES (2, 2, '登录系统', '::1', 1597747498);
INSERT INTO `system_log` VALUES (3, 2, '退出登录', '::1', 1597747505);
INSERT INTO `system_log` VALUES (4, 1, '登录系统', '::1', 1597747537);
INSERT INTO `system_log` VALUES (5, 1, '退出登录', '::1', 1597750286);
INSERT INTO `system_log` VALUES (6, 1, '登录系统', '::1', 1597750301);
INSERT INTO `system_log` VALUES (7, 1, '退出登录', '::1', 1597750970);
INSERT INTO `system_log` VALUES (8, 2, '登录系统', '::1', 1597750982);
INSERT INTO `system_log` VALUES (9, 2, '退出登录', '::1', 1597751459);
INSERT INTO `system_log` VALUES (10, 2, '登录系统', '::1', 1597751477);
INSERT INTO `system_log` VALUES (11, 2, '退出登录', '::1', 1597754972);
INSERT INTO `system_log` VALUES (12, 1, '登录系统', '::1', 1597754991);
INSERT INTO `system_log` VALUES (13, 1, '登录系统', '::1', 1597758940);
INSERT INTO `system_log` VALUES (14, 2, '登录系统', '::1', 1597761986);
INSERT INTO `system_log` VALUES (15, 2, '登录系统', '::1', 1597840549);
INSERT INTO `system_log` VALUES (16, 2, '退出登录', '::1', 1597840563);
INSERT INTO `system_log` VALUES (17, 2, '登录系统', '::1', 1597840603);
INSERT INTO `system_log` VALUES (18, 2, '退出登录', '::1', 1597847079);
INSERT INTO `system_log` VALUES (19, 1, '登录系统', '::1', 1597847092);
INSERT INTO `system_log` VALUES (20, 1, '退出登录', '::1', 1597850841);
INSERT INTO `system_log` VALUES (21, 2, '登录系统', '::1', 1597850853);
INSERT INTO `system_log` VALUES (22, 2, '退出登录', '::1', 1597850975);
INSERT INTO `system_log` VALUES (23, 1, '登录系统', '::1', 1597850999);
INSERT INTO `system_log` VALUES (24, 1, '操作了【2 | 犁生】管理员', '::1', 1597853555);
INSERT INTO `system_log` VALUES (25, 1, '退出登录', '::1', 1597853583);
INSERT INTO `system_log` VALUES (26, 2, '登录系统', '::1', 1597853616);
INSERT INTO `system_log` VALUES (27, 2, '操作了【2 | 犁生】管理员', '::1', 1597853784);
INSERT INTO `system_log` VALUES (28, 2, '退出登录', '::1', 1597853799);
INSERT INTO `system_log` VALUES (29, 1, '登录系统', '::1', 1597853814);
INSERT INTO `system_log` VALUES (30, 1, '操作了【1 | root】管理员', '::1', 1597853902);
INSERT INTO `system_log` VALUES (32, 1, '操作了【1 | infinite】管理员', '::1', 1597854725);
INSERT INTO `system_log` VALUES (31, 1, '操作了【2 | 犁生】管理员', '::1', 1597854536);
INSERT INTO `system_log` VALUES (33, 1, '退出登录', '::1', 1597854870);
INSERT INTO `system_log` VALUES (34, 2, '登录系统', '::1', 1597854898);
INSERT INTO `system_log` VALUES (35, 2, '操作了【2 | 犁生】管理员', '::1', 1597854927);
INSERT INTO `system_log` VALUES (36, 2, '操作了【2 | 犁生】管理员', '::1', 1597854949);
INSERT INTO `system_log` VALUES (37, 2, '退出登录', '::1', 1597854987);
INSERT INTO `system_log` VALUES (38, 2, '登录系统', '::1', 1597855032);
INSERT INTO `system_log` VALUES (39, 2, '退出登录', '::1', 1597855066);
INSERT INTO `system_log` VALUES (40, 1, '登录系统', '::1', 1597855087);
INSERT INTO `system_log` VALUES (41, 1, '操作了【2 | 生如夏花】管理员', '::1', 1597855276);
INSERT INTO `system_log` VALUES (42, 1, '操作了【2 | 生如夏花】管理员', '::1', 1597855290);
INSERT INTO `system_log` VALUES (43, 1, '操作了【2 | 生如夏花】管理员', '::1', 1597855302);
INSERT INTO `system_log` VALUES (44, 1, '退出登录', '::1', 1597855345);
INSERT INTO `system_log` VALUES (45, 1, '登录系统', '::1', 1597855760);
INSERT INTO `system_log` VALUES (46, 1, '操作了【2 | 生如夏花】管理员', '::1', 1597855781);
INSERT INTO `system_log` VALUES (47, 1, '登录系统', '::1', 1597920225);
INSERT INTO `system_log` VALUES (48, 1, '操作了【1 | infinite】管理员', '::1', 1597920282);
INSERT INTO `system_log` VALUES (49, 1, '退出登录', '::1', 1597930962);
INSERT INTO `system_log` VALUES (50, 2, '登录系统', '::1', 1597930977);
INSERT INTO `system_log` VALUES (51, 2, '退出登录', '::1', 1597931335);
INSERT INTO `system_log` VALUES (52, 2, '登录系统', '::1', 1597931351);
INSERT INTO `system_log` VALUES (53, 2, '退出登录', '::1', 1597931365);
INSERT INTO `system_log` VALUES (54, 1, '登录系统', '::1', 1597931377);
INSERT INTO `system_log` VALUES (55, 1, '登录系统', '::1', 1598003708);
INSERT INTO `system_log` VALUES (56, 1, '退出登录', '::1', 1598004356);
INSERT INTO `system_log` VALUES (57, 2, '登录系统', '::1', 1598004387);
INSERT INTO `system_log` VALUES (58, 2, '操作了【2 | 生如夏花】管理员', '::1', 1598004415);
INSERT INTO `system_log` VALUES (59, 2, '退出登录', '::1', 1598004427);
INSERT INTO `system_log` VALUES (60, 2, '登录系统', '::1', 1598004458);
INSERT INTO `system_log` VALUES (61, 2, '退出登录', '::1', 1598004472);
INSERT INTO `system_log` VALUES (62, 1, '登录系统', '::1', 1598004486);
INSERT INTO `system_log` VALUES (63, 1, '操作了【2 | 犁生】管理员', '::1', 1598009654);
INSERT INTO `system_log` VALUES (64, 1, '删除管理员：【4 | sjhp】', '::1', 1598016480);
INSERT INTO `system_log` VALUES (65, 1, '操作了【2 | 犁生】管理员', '::1', 1598016563);
INSERT INTO `system_log` VALUES (66, 1, '新增管理员：mo', '::1', 1598018480);
INSERT INTO `system_log` VALUES (67, 1, '登录系统', '::1', 1602837372);
INSERT INTO `system_log` VALUES (68, 1, '新增管理员：testest', '::1', 1602837803);
INSERT INTO `system_log` VALUES (69, 1, '新增管理员：test', '::1', 1602837855);
INSERT INTO `system_log` VALUES (70, 1, '删除管理员：【7 | test】', '::1', 1602837867);
INSERT INTO `system_log` VALUES (71, 1, '登录系统', '::1', 1607430380);
INSERT INTO `system_log` VALUES (72, 1, '登录系统', '::1', 1609664036);
INSERT INTO `system_log` VALUES (73, 1, '操作了【3 | dabai】管理员', '::1', 1609664083);
INSERT INTO `system_log` VALUES (74, 1, '操作了【3 | dabai】管理员', '::1', 1609664093);
INSERT INTO `system_log` VALUES (75, 1, '登录系统', '::1', 1609749635);
INSERT INTO `system_log` VALUES (76, 1, '操作了【3 | dabai】管理员', '::1', 1609750943);
INSERT INTO `system_log` VALUES (77, 3, '登录系统', '192.168.43.39', 1609750975);
INSERT INTO `system_log` VALUES (78, 3, '操作了【3 | dabai】管理员', '192.168.43.39', 1609751261);
INSERT INTO `system_log` VALUES (79, 1, '操作了【3 | dahei】管理员', '::1', 1609751359);
INSERT INTO `system_log` VALUES (80, 1, '操作了【3 | dahei】管理员', '::1', 1609751371);
INSERT INTO `system_log` VALUES (81, 1, '登录系统', '::1', 1609839182);

-- ----------------------------
-- Table structure for trade
-- ----------------------------
DROP TABLE IF EXISTS `trade`;
CREATE TABLE `trade`  (
  `trade_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `trade_num` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '订单编号 DD20180325xxxx',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `add_time` int(10) NOT NULL COMMENT '创建时间,买家下单即创建',
  `user_id` int(11) NOT NULL COMMENT '买家的用户id',
  `take_goods_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '收货人姓名',
  `trade_time` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '交易时间',
  `trade_phone` int(11) NOT NULL COMMENT '联系电话',
  `trade_place` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '交易地址',
  `trade_mark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `trade_money` float NOT NULL COMMENT '交易金额',
  `pay_time` int(10) NULL DEFAULT NULL COMMENT '付款时间',
  `finish_time` int(10) NULL DEFAULT NULL COMMENT '完成时间',
  `trade_status` tinyint(255) NOT NULL DEFAULT 0 COMMENT '订单状态 0 正常  1 已支付 2 已完成 3 已删除',
  PRIMARY KEY (`trade_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '订单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of trade
-- ----------------------------
INSERT INTO `trade` VALUES (2, 'DD201911207708', 5, 1574263756, 1, '何弟弟', '午夜时分', 2147483647, '操场小树林', '卖屁股', 21, NULL, NULL, 0);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_num` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户的平台编号 TXB20081004xxxx',
  `open_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '微信用户唯一标识',
  `session_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'code換回的session_key',
  `thr_session` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '自行生成的session_key',
  `nick_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户昵称，不重复',
  `user_sex` tinyint(4) NULL DEFAULT 0 COMMENT '性别 男1 女2 未知0',
  `user_birth` int(10) NULL DEFAULT NULL COMMENT '用户生日,10位时间戳',
  `user_headimg` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户头像',
  `user_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '联系电话',
  `user_intro` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '简介，可空',
  `school_id` int(11) NULL DEFAULT 0 COMMENT '所属学校id',
  `post_status` int(4) NOT NULL DEFAULT 0 COMMENT '发贴权限 0 未激活 1 正常  2 冻结',
  `trade_status` int(4) NOT NULL DEFAULT 0 COMMENT '交易权限，0 未激活  1 正常  2 封禁',
  `follow_goods` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '关注商品id,用 | 隔开',
  `follow_posts` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '关注贴子，| 标识隔开',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `fill` int(2) NOT NULL DEFAULT 0 COMMENT '是否完善信息',
  PRIMARY KEY (`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (3, 'TXB20191116185702431', 'oi2ha5IVK77lzWkmKafKBWrtHUdg', 'czED6OIxZKoYbVaMRQrSWQ==', '1f6e91343d7d51911670646b24c49256', '@', 1, NULL, 'xxxxx', '18383086596', '在下测试君', 1, 0, 1, NULL, NULL, 1573901822, 1);
INSERT INTO `user` VALUES (4, '', '', '', '', 'p', 0, NULL, '', NULL, NULL, 0, 0, 0, NULL, NULL, 0, 0);

SET FOREIGN_KEY_CHECKS = 1;
