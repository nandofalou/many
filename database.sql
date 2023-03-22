/*
 Criação do banco manyminds
*/

-- CREATE DATABASE `manyminds`;

-- USE `manyminds`;

/*Estrutura da tabela `user` */

CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pass` text DEFAULT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `type_person` enum('COLABORADOR','FORNECEDOR') NOT NULL DEFAULT 'COLABORADOR',
  `hash` varchar(128) DEFAULT NULL,
  `hash_validate` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_email` (`email`),
  KEY `idx_name` (`name`),
  KEY `idx_type_person` (`type_person`),
  KEY `idx_hash` (`hash`)
) ENGINE=InnoDB;


/*Estrutura da tabela `log_auth` */

CREATE TABLE `log_auth` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `ip` varchar(40) DEFAULT NULL,
  `success` tinyint(1) unsigned NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ip` (`ip`),
  KEY `idx_creates_at` (`created_at`),
  KEY `idx_id_user` (`id_user`),
  KEY `idx_search` (`id_user`,`ip`,`success`,`created_at`)
) ENGINE=MyISAM;

/*Estrutura da tabela `permission` */

CREATE TABLE `permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

/*Estrutura da tabela `permission_user` */

CREATE TABLE `permission_user` (
  `id_permission` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  KEY `idx_permission` (`id_permission`),
  KEY `idx_user` (`id_user`),
  KEY `uq_permission_user` (`id_permission`,`id_user`),
  CONSTRAINT `fk_permission` 
	FOREIGN KEY (`id_permission`)
	REFERENCES `permission` (`id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION,
  CONSTRAINT `fk_user`
	FOREIGN KEY (`id_user`)
	REFERENCES `user` (`id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
) ENGINE=InnoDB;


/*  Endereços  */

/*Estrutura da tabela `state` */

CREATE TABLE `state` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `abbr` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_abbr` (`abbr`)
) ENGINE=InnoDB;

/*Estrutura da tabela `city` */

CREATE TABLE `city` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_state` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_city_name` (`name`,`id_state`) USING BTREE,
  KEY `idx_id_state` (`id_state`),
  KEY `idx_name` (`name`),
  KEY `idx_code` (`code`),
  CONSTRAINT `fk_id_city_state`
	FOREIGN KEY (`id_state`)
	REFERENCES `state` (`id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
) ENGINE=InnoDB;

/*Estrutura da tabela `zipcode` */

CREATE TABLE `zipcode` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_city` int(11) unsigned NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `street` varchar(200) DEFAULT NULL,
  `complement` varchar(200) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_zipcode` (`zipcode`),
  KEY `idx_street` (`street`),
  KEY `idx_district` (`district`),
  KEY `idx_id_city` (`id_city`),
  CONSTRAINT `fk_zipcode_id_city`
	FOREIGN KEY (`id_city`)
	REFERENCES `city` (`id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
) ENGINE=InnoDB;

/*Estrutura da tabela `user_address` */

CREATE TABLE `user_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `zipcode` varchar(20) NOT NULL,
  `number` varchar(20) NOT NULL,
  `complement` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_id_user` (`id_user`),
  KEY `idx_zipcode` (`zipcode`),
  KEY `idx_deleted_at` (`deleted_at`),
  CONSTRAINT `fk_user_address_user`
	FOREIGN KEY (`id_user`)
	REFERENCES `user` (`id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
) ENGINE=InnoDB;

/* fim dos endereços */

/* produtos e pedidos */

/*Estrutura da tabela `product` */

CREATE TABLE `product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `price` double NOT NULL DEFAULT 0,
  `stock` int(10) unsigned NOT NULL DEFAULT 0,
  `created_by` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_active` (`active`),
  KEY `idx_price` (`price`),
  KEY `idx_stock` (`stock`),
  KEY `idx_created_by` (`created_by`),
  KEY `idx_deleted_at` (`deleted_at`),
  CONSTRAINT `fk_product_user`
	FOREIGN KEY (`created_by`)
	REFERENCES `user` (`id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
) ENGINE=InnoDB;


/*Estrutura da tabela `order_status` */

CREATE TABLE `order_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB;

/*Estrutura da tabela `order` */

CREATE TABLE `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_status_order` int(10) unsigned NOT NULL,
  `obs` text DEFAULT NULL,
	`created_by` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_id_user` (`id_user`),
  KEY `idx_created_by` (`created_by`),
  KEY `idx_id_status` (`id_status_order`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_deleted_at` (`deleted_at`),
  CONSTRAINT `fk_order_status`
	FOREIGN KEY (`id_status_order`)
	REFERENCES `order_status` (`id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_user`
	FOREIGN KEY (`id_user`)
	REFERENCES `user` (`id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION,
	CONSTRAINT `fk_order_created_by`
	FOREIGN KEY (`created_by`)
	REFERENCES `user` (`id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
) ENGINE=InnoDB;


/*Estrutura da tabela `order_item` */

CREATE TABLE `order_item` (
  `id_order` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  UNIQUE KEY `uq_order_irem` (`id_order`,`id_product`),
  KEY `idx_orderitem_product` (`id_product`),
  CONSTRAINT `fk_orderitem_product`
	FOREIGN KEY (`id_product`)
	REFERENCES `product` (`id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION,
  CONSTRAINT `fk_orderitem_user`
	FOREIGN KEY (`id_order`)
	REFERENCES `order` (`id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
) ENGINE=InnoDB;

/*Estrutura da tabela `log` */

CREATE TABLE `log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `process` varchar(100) NOT NULL,
  `old_data` text DEFAULT NULL,
  `new_data` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_process` (`process`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_id_user` (`id_user`)
) ENGINE=MyISAM;

/* fim da estrutura */

/* iniciando algumas tabelas */

/*
 inserindo status na tabela order_status
*/

insert into `order_status` (`id`, `name`) values('1','ATIVO');
insert into `order_status` (`id`, `name`) values('2','FINALIZADO');

/*
 inserindo estados (UF) na tabela state
*/

insert into `state` (`id`, `name`, `abbr`) values('1','Acre','AC');
insert into `state` (`id`, `name`, `abbr`) values('2','Alagoas','AL');
insert into `state` (`id`, `name`, `abbr`) values('3','Amapá','AP');
insert into `state` (`id`, `name`, `abbr`) values('4','Amazonas','AM');
insert into `state` (`id`, `name`, `abbr`) values('5','Bahia','BA');
insert into `state` (`id`, `name`, `abbr`) values('6','Ceará','CE');
insert into `state` (`id`, `name`, `abbr`) values('7','Distrito Federal','DF');
insert into `state` (`id`, `name`, `abbr`) values('8','Espírito Santo','ES');
insert into `state` (`id`, `name`, `abbr`) values('9','Goiás','GO');
insert into `state` (`id`, `name`, `abbr`) values('10','Maranhão','MA');
insert into `state` (`id`, `name`, `abbr`) values('11','Mato Grosso','MT');
insert into `state` (`id`, `name`, `abbr`) values('12','Mato Grosso do Sul','MS');
insert into `state` (`id`, `name`, `abbr`) values('13','Minas Gerais','MG');
insert into `state` (`id`, `name`, `abbr`) values('14','Pará','PA');
insert into `state` (`id`, `name`, `abbr`) values('15','Paraíba','PB');
insert into `state` (`id`, `name`, `abbr`) values('16','Paraná','PR');
insert into `state` (`id`, `name`, `abbr`) values('17','Pernambuco','PE');
insert into `state` (`id`, `name`, `abbr`) values('18','Piauí','PI');
insert into `state` (`id`, `name`, `abbr`) values('19','Rio de Janeiro','RJ');
insert into `state` (`id`, `name`, `abbr`) values('20','Rio Grande do Norte','RN');
insert into `state` (`id`, `name`, `abbr`) values('21','Rio Grande do Sul','RS');
insert into `state` (`id`, `name`, `abbr`) values('22','Rondônia','RO');
insert into `state` (`id`, `name`, `abbr`) values('23','Roraima','RR');
insert into `state` (`id`, `name`, `abbr`) values('24','Santa Catarina','SC');
insert into `state` (`id`, `name`, `abbr`) values('25','São Paulo','SP');
insert into `state` (`id`, `name`, `abbr`) values('26','Sergipe','SE');
insert into `state` (`id`, `name`, `abbr`) values('27','Tocantins','TO');

/*
 inserindo permissões
*/

INSERT INTO `permission` (`name`,`description`,`active`) VALUES
	 ('colaborador','Cadastro de Colaboradores',1),
	 ('fornecedor','Cadastro de Fornecedores',1),
	 ('produto','Cadastro de Produtos',1),
	 ('loja','Venda de Produtos',1);
