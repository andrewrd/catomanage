/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     14/10/2016 9:29:44 PM                        */
/*==============================================================*/

drop table if exists CGPRREL;

drop table if exists CGRYREL;

drop table if exists SPECIALS;

drop table if exists STOCK;

drop table if exists ATTRIBUTEVALUE;

drop table if exists ATTRIBUTE;

drop table if exists PRODPRICES;

drop table if exists SHOPPERGROUP;

drop table if exists PRODUCT;

drop table if exists CATEGORY;

/*==============================================================*/
/* Table: ATTRIBUTE                                             */
/*==============================================================*/
create table ATTRIBUTE
(
   ID                   int not null auto_increment,
   PRODUCT_PROD_ID      int not null,
   NAME                 varchar(45),
   primary key (ID)
);

/*==============================================================*/
/* Table: ATTRIBUTEVALUE                                        */
/*==============================================================*/
create table ATTRIBUTEVALUE
(
   ATTRVAL_ID           int not null auto_increment,
   ATTRVAL_PROD_ID      int not null,
   ATTRVAL_ATTR_ID      int not null,
   ATTRVAL_VALUE        varchar(45) not null,
   ATTRVAL_PRICE        decimal(10,2),
   primary key (ATTRVAL_ID)
);

/*==============================================================*/
/* Table: CATEGORY                                              */
/*==============================================================*/
create table CATEGORY
(
   CAT_ID               int not null auto_increment,
   CAT_NAME             varchar(40) not null,
   CAT_DESC             varchar(128),
   CAT_IMG_URL          varchar(128),
   CAT_DISP_CMD         varchar(128),
   primary key (CAT_ID)
);

/*==============================================================*/
/* Table: CGPRREL                                               */
/*==============================================================*/
create table CGPRREL
(
   CGPRREL_ID           int not null auto_increment,
   CGPR_CAT_ID          int not null,
   CGPR_PROD_ID         int not null,
   primary key (CGPRREL_ID)
);

/*==============================================================*/
/* Table: CGRYREL                                               */
/*==============================================================*/
create table CGRYREL
(
   CGRYREL_ID           int not null auto_increment,
   CGRYREL_ID_PARENT    int not null,
   CGRYREL_ID_CHILD     int not null,
   CGRYREL_SEQUENCE     int,
   primary key (CGRYREL_ID)
);

/*==============================================================*/
/* Table: PRODPRICES                                            */
/*==============================================================*/
create table PRODPRICES
(
   PRPR_ID              int not null auto_increment,
   PRPR_PROD_ID         int not null,
   PRPR_SHOPGRP         int not null,
   PRPR_PRICE           decimal(10,2),
   primary key (PRPR_ID)
);

/*==============================================================*/
/* Table: PRODUCT                                               */
/*==============================================================*/
create table PRODUCT
(
   PROD_ID              int not null auto_increment,
   PROD_NAME            varchar(40) not null,
   PROD_DESC            varchar(128),
   PROD_IMG_URL         varchar(128),
   PROD_LONG_DESC       varchar(256),
   PROD_SKU             char(16),
   PROD_DISP_CMD        varchar(128),
   PROD_WEIGHT          decimal(6,2),
   PROD_L               int,
   PROD_W               int,
   PROD_H               int,
   primary key (PROD_ID)
);

/*==============================================================*/
/* Table: SHOPPERGROUP                                          */
/*==============================================================*/
create table SHOPPERGROUP
(
   SHOPGRP_ID           int not null auto_increment,
   SHOPGRP_NAME         varchar(45) not null,
   SHOPGRP_DESCRIPTION  varchar(256),
   primary key (SHOPGRP_ID)
);

/*==============================================================*/
/* Table: SPECIALS                                              */
/*==============================================================*/
create table SPECIALS
(
   SPECIALS_ID          int not null auto_increment,
   SPECIAL_PRODUCT_ID   int not null,
   SPECIAL_PRODATTRVAL  int not null,
   SPECIAL_PRODPRICES_ID int,
   SPECIAL_START_DATE   date not null,
   SPECIAL_END_DATE     date not null,
   SPECIAL_COMMENT      varchar(255),
   primary key (SPECIALS_ID)
);

/*==============================================================*/
/* Table: STOCK                                                 */
/*==============================================================*/
create table STOCK
(
   STOCK_ID             int not null auto_increment,
   STOCK_PROD_ID        int not null,
   STOCK_PROD_ATTRVALUE_ID int not null,
   STOCK_QTY            int,
   STOCK_SKU            varchar(60),
   STOCK_LOCATION       varchar(60),
   primary key (STOCK_ID)
);

alter table ATTRIBUTE add constraint FK_PRODUCTS_POSSESS foreign key (PRODUCT_PROD_ID)
      references PRODUCT (PROD_ID) on delete restrict on update restrict;

alter table ATTRIBUTEVALUE add constraint FK_HAS foreign key (ATTRVAL_ATTR_ID)
      references ATTRIBUTE (ID) on delete restrict on update restrict;

alter table ATTRIBUTEVALUE add constraint FK_ITEMS_POSSESS foreign key (ATTRVAL_PROD_ID)
      references PRODUCT (PROD_ID) on delete restrict on update restrict;

alter table CGPRREL add constraint FK_CATEGORISED_BY foreign key (CGPR_PROD_ID)
      references PRODUCT (PROD_ID) on delete restrict on update restrict;

alter table CGPRREL add constraint FK_CATEGORISES_PRODUCTS_BY foreign key (CGPR_CAT_ID)
      references CATEGORY (CAT_ID) on delete restrict on update restrict;

alter table CGRYREL add constraint FK_CATEGORISES foreign key (CGRYREL_ID_PARENT)
      references CATEGORY (CAT_ID) on delete restrict on update restrict;

alter table CGRYREL add constraint FK_CATEGORIZED_BY foreign key (CGRYREL_ID_CHILD)
      references CATEGORY (CAT_ID) on delete restrict on update restrict;

alter table PRODPRICES add constraint FK_DETERMINED_BY foreign key (PRPR_SHOPGRP)
      references SHOPPERGROUP (SHOPGRP_ID) on delete restrict on update restrict;

alter table PRODPRICES add constraint FK_PRODUCT_HAS foreign key (PRPR_PROD_ID)
      references PRODUCT (PROD_ID) on delete restrict on update restrict;

alter table SPECIALS add constraint FK_ATTRVALUE_HAS foreign key (SPECIAL_PRODATTRVAL)
      references ATTRIBUTEVALUE (ATTRVAL_ID) on delete restrict on update restrict;

alter table SPECIALS add constraint FK_PROMOTED_AS foreign key (SPECIAL_PRODUCT_ID)
      references PRODUCT (PROD_ID) on delete restrict on update restrict;

alter table STOCK add constraint FK_ATTRIBUTEVALUE_HAS foreign key (STOCK_PROD_ATTRVALUE_ID)
      references ATTRIBUTEVALUE (ATTRVAL_ID) on delete restrict on update restrict;

alter table STOCK add constraint FK_PRODUCTHAS foreign key (STOCK_PROD_ID)
      references PRODUCT (PROD_ID) on delete restrict on update restrict;

/* Insert into Category */
insert into category values (1,'Store root category','Store root category',null,'DisplayCategory.php');
insert into category values (2,'Men''s Clothing','Clothing of all types - trousers, jackets, etc.',null,'DisplayCategory.php');
insert into category values (3,'Jeans','Jeans: Denim, cotton, etc.',null,'DisplayCategory.php');
insert into category values (4,'Denim Jeans','Denim jeans',null,'DisplayCategory.php');
insert into category values (5,'Boot cut jeans','Boot cut jeans',null,'DisplayProducts.php');
insert into category values (6,'Straight cut jeans','Straight cut jeans',null,'DisplayProducts.php');
insert into category values (7,'Shoes and boots','Shoes and boots','shoes.jpg','DisplayCategory.php');
insert into category values (8,'Computers','Computers','computers.jpg','DisplayCategory.php');
insert into category values (9,'Laptop computers','Laptop computers','laptop.jpg','DisplayProducts.php');
insert into category values (10,'Servers','Rackmount and tower servers','server.jpg','DisplayProducts.php');
insert into category values (11,'Shirts','Shirts of all kinds','shirts.jpg','DisplayCategory.php');
insert into category values (12,'Trousers','Trousers, jeans, shorts','trousers.jpg','DisplayCategory.php');
insert into category values (13,'Jackets','Sports jackets, blazers, etc.','jackets.jpg','DisplayCategory.php');
insert into category values (14,'Business shirts','Cotton and cotton blend business shirts','bshirt.jpg','DisplayCategory.php');
insert into category values (15,'Short-sleeved shirts','With collars and short sleeves','sshirt.jpg','DisplayCategory.php');
insert into category values (16,'T-shirts','Tees: plain and with designs','tshirt.jpg','DisplayCategory.php');

/* Insert into Product */
insert into PRODUCT values (1,'Null Product','Null product','null.jpg','Null product','NULL','DisplayProduct.php', 0, 0, 0, 0);
insert into PRODUCT values (2,'Levi 501','Levi 501 Classic Jeans','levi_501.jpg','You will look terrific in these classic blue denim jeans. Hard-wearing, but stylish, these durable pants always look smart, even when you''ve just gotten off your horse.','LEVI501','DisplayProduct.php', 0.12, 12, 36, 20);
insert into PRODUCT values (3,'Levi 504','Levi 504 Cord Jeans','levi_504.jpg','Light-weight corduroy is perfect for those hot summer days! Look cool and be cool. Available in blue, brown and black.','LEVI504','DisplayProduct.php', 0.13, 13, 37, 21);
insert into PRODUCT values (4,'Levi 502','Levi 502 Jeans','levi_502.jpg','The classic Levi look in black','LEVI502','DisplayProduct.php', 0.14, 14, 38, 22);
insert into PRODUCT values (5,'Wrangler CCOR','Wrangler Cowboy Cut Original Fit','wr_ccof.jpg','Available in blue and black, yadda, yadda, yadda','WRCCOF','DisplayProduct.php', 0.15, 15, 39, 23);
insert into PRODUCT values (6,'XKCD Stats Class Tee','The classic XKCD "Causality" t-shirt','xkcd-t.jpg','The classic XKCD cartoon now comes to a t-shirt near you! Available in blue only.','XKCDT','DisplayProduct.php', 0.16, 16, 40, 24);
insert into PRODUCT values (7,'IBM 306m','IBM X-Series 306m 1U Rack-mount server','306m.jpg','A 1U rack-mount server. Specify processor, RAM, storage, etc. below','IBM306M','DisplayProduct.php', 12.6, 70, 55, 40);

/* Insert into CgPrRel */
insert into CGPRREL values (1,5,2);
insert into CGPRREL values (2,5,4);
insert into CGPRREL values (3,6,3);
insert into CGPRREL values (4,5,5);
insert into CGPRREL values (5,16,6);
insert into CGPRREL values (6,10,7);

/* Insert into CgryRel */
insert into CGRYREL values (1,1,2,null);
insert into CGRYREL values (2,2,3,null);
insert into CGRYREL values (3,3,4,null);
insert into CGRYREL values (4,3,5,null);
insert into CGRYREL values (5,3,6,null);
insert into CGRYREL values (6,4,5,null);
insert into CGRYREL values (7,4,6,null);
insert into CGRYREL values (8,2,7,null);
insert into CGRYREL values (9,2,11,null);
insert into CGRYREL values (10,2,12,null);
insert into CGRYREL values (11,12,3,null);
insert into CGRYREL values (12,2,11,null);
insert into CGRYREL values (13,11,14,null);
insert into CGRYREL values (14,11,15,null);
insert into CGRYREL values (15,11,16,null);
insert into CGRYREL values (16,2,13,null);
insert into CGRYREL values (17,1,8,null);
insert into CGRYREL values (18,8,9,null);
insert into CGRYREL values (19,8,10,NULL);

/* Insert into Attribute */
insert into ATTRIBUTE values (DEFAULT, 2,'Colour');
insert into ATTRIBUTE values (DEFAULT, 2,'Waist');
insert into ATTRIBUTE values (DEFAULT, 7,'CPU Speed');
insert into ATTRIBUTE values (DEFAULT, 7, 'Bandwidth');
insert into ATTRIBUTE values (DEFAULT, 6, 'Size');
insert into ATTRIBUTE values (DEFAULT, 6, 'Colour');
insert into ATTRIBUTE values (DEFAULT, 5,'Transmission');
insert into ATTRIBUTE values (DEFAULT, 5,'Body Type');

/* ================================
Insert into AttributeValue
================================== */

/* Colours for Levi 501  */
insert into ATTRIBUTEVALUE values (DEFAULT, 2, 1, 'Blue', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 2, 1, 'Black', 5.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 2, 1, 'Grey', 5.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 2, 1, 'Burgundy', 5.00);

/* Waist Size for Levi 501 */
insert into ATTRIBUTEVALUE values (DEFAULT, 2, 2, '32', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 2, 2, '34', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 2, 2, '36', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 2, 2, '38', 1.99);

/* CPU speed for IBM Series Server */
insert into ATTRIBUTEVALUE values (DEFAULT, 7, 3, '1.8 GHz', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 7, 3, '2.4 GHz', 49.99);

/* Bandwidth for IBM Series Server */
insert into ATTRIBUTEVALUE values (DEFAULT, 7, 4, '3 GB', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 7, 4, '8.5 GB', 69.99);

/* Shirt size for XCSD tee */
insert into ATTRIBUTEVALUE values (DEFAULT, 6, 5, 'S', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 6, 5, 'M', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 6, 5, 'L', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 6, 5, 'XL', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 6, 5, 'XXL', 2.00);

/* Shirt colour for XCSD tee */
insert into ATTRIBUTEVALUE values (DEFAULT, 6, 6, 'Red', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 6, 6, 'Orange', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 6, 6, 'Yellow', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 6, 6, 'Green', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 6, 6, 'Blue', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 6, 6, 'Black', 0.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 6, 6, 'White', 0.00);

/* Transmission for Wrangler */
insert into ATTRIBUTEVALUE values (DEFAULT, 5, 7, 'Automatic', 300.00);
insert into ATTRIBUTEVALUE values (DEFAULT, 5, 7, 'Manual', 0.00);

/* Body type for Wrangler */
insert into ATTRIBUTEVALUE values (DEFAULT, 5, 8, 'Jeep', 0.00);

/* Insert into ShopperGroup */
insert into SHOPPERGROUP values (DEFAULT, 'Normal', 'This a normal shopper');
insert into SHOPPERGROUP values (DEFAULT, 'Australian', 'This is a shopper from Australia. Please consider GST');
insert into SHOPPERGROUP values (DEFAULT, 'Discounted', 'This is a shopper that gets a discount');

/* ProdPrices for Levi501 jeans */
insert into PRODPRICES values (DEFAULT, 2, 1, 35.99);
insert into PRODPRICES values (DEFAULT, 2, 2, 30.99);
insert into PRODPRICES values (DEFAULT, 2, 3, 25.99);

/* ProdPrices for IBM Server */
insert into PRODPRICES values (DEFAULT, 7, 1, 150.99);
insert into PRODPRICES values (DEFAULT, 7, 2, 140.99);
insert into PRODPRICES values (DEFAULT, 7, 3, 130.99);

/* ProdPrices for XCSD Tee */
insert into PRODPRICES values (DEFAULT, 6, 1, 24.99);
insert into PRODPRICES values (DEFAULT, 6, 2, 20.99);
insert into PRODPRICES values (DEFAULT, 6, 3, 15.99);

insert into PRODPRICES values (DEFAULT, 4, 1, 24.99);
insert into PRODPRICES values (DEFAULT, 4, 2, 20.99);
insert into PRODPRICES values (DEFAULT, 4, 3, 15.99);

insert into PRODPRICES values (DEFAULT, 5, 1, 25000.00);
insert into PRODPRICES values (DEFAULT, 5, 2, 24000.99);
insert into PRODPRICES values (DEFAULT, 5, 3, 22599.00);

insert into PRODPRICES values (DEFAULT, 3, 1, 29.99);
insert into PRODPRICES values (DEFAULT, 3, 2, 25.99);
insert into PRODPRICES values (DEFAULT, 3, 3, 19.99);
