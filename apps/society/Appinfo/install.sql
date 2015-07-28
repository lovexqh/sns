DROP TABLE IF EXISTS `ts_blog_link`;
CREATE TABLE `ts_blog_link` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uid`  int(11) NULL DEFAULT NULL ,
`blogid`  int(11) NULL DEFAULT NULL ,
`bjid`  int(11) NULL DEFAULT NULL ,
`zyid`  int(11) NULL DEFAULT NULL ,
`yxid`  int(11) NULL DEFAULT NULL ,
`nj`  int(11) NULL DEFAULT NULL ,
`depid`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `ts_photo_link`
-- ----------------------------
DROP TABLE IF EXISTS `ts_photo_link`;
CREATE TABLE `ts_photo_link` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uid`  int(11) NULL DEFAULT NULL ,
`photoid`  int(11) NULL DEFAULT NULL ,
`bjid`  int(11) NULL DEFAULT NULL ,
`zyid`  int(11) NULL DEFAULT NULL ,
`yxid`  int(11) NULL DEFAULT NULL ,
`nj`  int(11) NULL DEFAULT NULL ,
`depid`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `ts_society`
-- ----------------------------
DROP TABLE IF EXISTS `ts_society`;
CREATE TABLE `ts_society` (
`id`  int(11) NOT NULL AUTO_INCREMENT COMMENT '圈子id.' ,
`societyName`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '圈子名称.' ,
`sign`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '圈子签名.' ,
`tags`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '圈子标签' ,
`notice`  varchar(400) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '圈子公告.' ,
`icon`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`visitable`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '浏览权限.非成员是否浏览权限：0不可以，1可以' ,
`joinable`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '加入权限.成员加入时候需要审批：0不需要，1需要' ,
`downloadable`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '下载权限.共享是否让非成员下载：0不可以，1可以' ,
`typeid`  int(11) NULL DEFAULT 0 COMMENT '类型ID.' ,
`type`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '圈子类型.0自定义1专业2年级3专业4院系' ,
`cTime`  int(11) NULL DEFAULT 0 COMMENT '创建时间.创建时间' ,
`isDel`  tinyint(2) NULL DEFAULT 0 COMMENT '是否有效.是否有效，0有效，1无效' ,
`dTime`  int(11) NULL DEFAULT 0 COMMENT '失效日期.标记删除日期' ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='社交圈空间班级基础信息表, 该表存储了用户所在圈子的基础信息，包括班级签名，班级公告等基础信息'
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `ts_society_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ts_society_comment`;
CREATE TABLE `ts_society_comment` (
`id`  int(11) NOT NULL AUTO_INCREMENT COMMENT '编号.' ,
`societyId`  int(11) NULL DEFAULT NULL COMMENT '圈子ID.' ,
`messageId`  int(11) NULL DEFAULT NULL COMMENT '帖子id.' ,
`uid`  int(11) NULL DEFAULT NULL COMMENT '回复者id.' ,
`toId`  int(11) NULL DEFAULT NULL COMMENT '主回复帖id.主回复帖的id，0本帖即为主回复贴' ,
`toUid`  int(11) NULL DEFAULT NULL COMMENT '被回复者id.' ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '发布内容.' ,
`cTime`  int(11) NULL DEFAULT NULL COMMENT '创建时间.' ,
`isDel`  tinyint(2) NULL DEFAULT 0 COMMENT '是否删除.0：可用   1：删除' ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='社交圈动态回复, 回复评论回复'
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `ts_society_member`
-- ----------------------------
DROP TABLE IF EXISTS `ts_society_member`;
CREATE TABLE `ts_society_member` (
`id`  int(11) NOT NULL AUTO_INCREMENT COMMENT '编号.' ,
`societyId`  int(11) NOT NULL COMMENT '圈子ID.' ,
`uid`  int(11) NOT NULL COMMENT '用户id.' ,
`status`  tinyint(2) NULL DEFAULT 0 COMMENT '成员身份.0成员，1普通管理员，2圈主' ,
`cTime`  int(11) NULL DEFAULT NULL COMMENT '创建时间.创建时间' ,
`isDel`  tinyint(2) NULL DEFAULT 0 COMMENT '是否有效.0有效，1无效' ,
`dTime`  int(11) NULL DEFAULT NULL COMMENT '失效日期.标记删除日期' ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='社交圈成员表, 该表存储了用户所在圈子的基础信息，包括班级签名，班级公告等基础信息'
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `ts_society_message`
-- ----------------------------
DROP TABLE IF EXISTS `ts_society_message`;
CREATE TABLE `ts_society_message` (
`id`  int(11) NOT NULL AUTO_INCREMENT COMMENT '编号.' ,
`societyId`  int(11) NOT NULL COMMENT '圈子ID.' ,
`uid`  int(11) NOT NULL COMMENT '用户id.' ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题.' ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '发布内容.' ,
`cTime`  int(11) NULL DEFAULT NULL COMMENT '创建时间.' ,
`mTime`  int(11) NULL DEFAULT NULL COMMENT '最后修改时间.最后编辑（回复）时间，用于最新排序' ,
`isDel`  tinyint(2) NULL DEFAULT 0 COMMENT '是否删除.0：可用   1：删除' ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='圈子帖子, 圈子帖子'
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `ts_society_news`
-- ----------------------------
DROP TABLE IF EXISTS `ts_society_news`;
CREATE TABLE `ts_society_news` (
`newsId`  int(11) NOT NULL AUTO_INCREMENT COMMENT '编号.' ,
`societyId`  int(11) NULL DEFAULT NULL COMMENT '圈子ID.' ,
`fromUid`  int(11) NULL DEFAULT NULL COMMENT '发自用户id.' ,
`toUid`  int(11) NULL DEFAULT NULL COMMENT '接收用户id.' ,
`newsType`  varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '消息类型.1申请消息 2邀请消息 3退出消息' ,
`result`  varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '消息结果.1通过 0不通过' ,
`cTime`  int(11) NULL DEFAULT NULL COMMENT '创建时间.' ,
`isDel`  tinyint(2) NULL DEFAULT 0 COMMENT '是否有效.0有效  1无效' ,
PRIMARY KEY (`newsId`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='社交圈消息表, 存储圈子里面的加入、申请、离开等消息'
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `ts_society_share`
-- ----------------------------
DROP TABLE IF EXISTS `ts_society_share`;
CREATE TABLE `ts_society_share` (
`id`  int(11) NOT NULL AUTO_INCREMENT COMMENT '班级共享文档ID.' ,
`societyId`  int(11) NOT NULL COMMENT '圈子ID.' ,
`shareName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '文档名称.' ,
`size`  int(11) NULL DEFAULT 0 COMMENT '文档大小.' ,
`path`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '文档路径.' ,
`uid`  int(11) NULL DEFAULT NULL COMMENT '文档上传人.取值为登录用户ID' ,
`download`  int(11) NULL DEFAULT 0 COMMENT '下载次数.' ,
`cTime`  int(11) NULL DEFAULT NULL COMMENT '上传时间.' ,
`isDel`  tinyint(2) NULL DEFAULT 0 COMMENT '是否有效.0有效，1无效' ,
`format`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='社交圈空间共享文档表, 该表存储了圈子共享文档的基本信息'
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `ts_society_visitor`
-- ----------------------------
DROP TABLE IF EXISTS `ts_society_visitor`;
CREATE TABLE `ts_society_visitor` (
`sv_id`  int(11) NOT NULL AUTO_INCREMENT ,
`societyId`  int(11) NOT NULL ,
`uid`  int(11) NOT NULL ,
`status`  int(2) NOT NULL ,
`cTime`  int(11) NOT NULL ,
`times`  int(11) NOT NULL DEFAULT 0 COMMENT '访问次数' ,
`isDel`  int(2) NOT NULL ,
PRIMARY KEY (`sv_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `ts_society_vote`
-- ----------------------------
DROP TABLE IF EXISTS `ts_society_vote`;
CREATE TABLE `ts_society_vote` (
`vote_id`  int(11) NOT NULL AUTO_INCREMENT COMMENT '编号.' ,
`societyId`  int(11) NULL DEFAULT NULL COMMENT '圈子ID.' ,
`fromUid`  int(11) NULL DEFAULT NULL COMMENT '发自用户id.' ,
`toUid`  int(11) NULL DEFAULT NULL COMMENT '接收用户id.' ,
`cTime`  int(11) NULL DEFAULT NULL COMMENT '创建时间.' ,
`isDel`  tinyint(2) NULL DEFAULT 0 COMMENT '是否有效.0有效  1无效' ,
PRIMARY KEY (`vote_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='社交圈投票表, 存储圈子中校方圈子的投票信息'
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `ts_society_wall`
-- ----------------------------
DROP TABLE IF EXISTS `ts_society_wall`;
CREATE TABLE `ts_society_wall` (
`id`  int(11) NOT NULL AUTO_INCREMENT COMMENT '编号.' ,
`societyId`  int(11) NULL DEFAULT NULL COMMENT '圈子ID.' ,
`uid`  int(11) NULL DEFAULT NULL COMMENT '回复者id.' ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '发布内容.' ,
`cTime`  int(11) NULL DEFAULT NULL COMMENT '创建时间.' ,
`isDel`  tinyint(2) NULL DEFAULT NULL COMMENT '是否删除.0：可用   1：删除' ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='社交圈印象墙, '
AUTO_INCREMENT=1

;

-- ----------------------------
-- Auto increment value for `ts_blog_link`
-- ----------------------------
ALTER TABLE `ts_blog_link` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `ts_photo_link`
-- ----------------------------
ALTER TABLE `ts_photo_link` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `ts_society`
-- ----------------------------
ALTER TABLE `ts_society` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `ts_society_comment`
-- ----------------------------
ALTER TABLE `ts_society_comment` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `ts_society_member`
-- ----------------------------
ALTER TABLE `ts_society_member` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `ts_society_message`
-- ----------------------------
ALTER TABLE `ts_society_message` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `ts_society_news`
-- ----------------------------
ALTER TABLE `ts_society_news` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `ts_society_share`
-- ----------------------------
ALTER TABLE `ts_society_share` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `ts_society_visitor`
-- ----------------------------
ALTER TABLE `ts_society_visitor` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `ts_society_vote`
-- ----------------------------
ALTER TABLE `ts_society_vote` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `ts_society_wall`
-- ----------------------------
ALTER TABLE `ts_society_wall` AUTO_INCREMENT=1;


-- 在顶部添加进入社交圈入口
DELETE FROM `ts_dsk_navbar` WHERE `navname` = '社交圈';
INSERT INTO `ts_dsk_navbar` (`navname`, `navicon`, `navicon_hover`, `navurl`, `type`, `target`, `disp`, `avaliable`, `isdefault`,`allow`,`margintop`,`marginright`,`marginbottom`,`marginleft`,`iconview`,`iconposition`,`direction`,`dockshow`,`topbarshow`,`autolist`,`scale`,`backimg`,`inuser`,`icos`,`wins`,`iconpositions`,`mids`) 
VALUES ('社交圈', '', '', 'index.php?app=society&mod=Index&act=index', 'custom', '_self', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', '1', '0', '', '1', '', '', '', '');

