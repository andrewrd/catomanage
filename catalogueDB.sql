/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     14/10/2016 9:29:44 PM                        */
/*==============================================================*/


drop table if exists ATTRIBUTE;

drop table if exists ATTRIBUTEVALUE;

drop table if exists CATEGORY;

drop table if exists CGPRREL;

drop table if exists CGRYREL;

drop table if exists PRODPRICES;

drop table if exists PRODUCT;

drop table if exists SHOPPERGROUP;

drop table if exists SPECIALS;

drop table if exists STOCK;

/*==============================================================*/
/* Table: ATTRIBUTE                                             */
/*==============================================================*/
create table ATTRIBUTE
(
   ID                   int not null,
   PRODUCT_PROD_ID      int not null,
   NAME                 varchar(45),
   primary key (ID)
);

/*==============================================================*/
/* Table: ATTRIBUTEVALUE                                        */
/*==============================================================*/
create table ATTRIBUTEVALUE
(
   ATTRVAL_ID           int not null,
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
   CAT_ID               int not null,
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
   CGPRREL_ID           int not null,
   CGPR_CAT_ID          int not null,
   CGPR_PROD_ID         int not null,
   primary key (CGPRREL_ID)
);

/*==============================================================*/
/* Table: CGRYREL                                               */
/*==============================================================*/
create table CGRYREL
(
   CGRYREL_ID           int not null,
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
   PRPR_ID              int not null,
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
   PROD_ID              int not null,
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
   SHOPGRP_ID           int not null,
   SHOPGRP_NAME         varchar(45) not null,
   SHOPGRP_DESCRIPTION  varchar(256),
   primary key (SHOPGRP_ID)
);

/*==============================================================*/
/* Table: SPECIALS                                              */
/*==============================================================*/
create table SPECIALS
(
   SPECIALS_ID          int not null,
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
   STOCK_ID             int not null,
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

