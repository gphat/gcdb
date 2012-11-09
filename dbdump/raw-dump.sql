# MySQL dump 8.14
#
# Host: localhost    Database: gcdb
#--------------------------------------------------------
# Server version	3.23.38-log

#
# Table structure for table 'Accounts'
#

DROP TABLE IF EXISTS Accounts;
CREATE TABLE Accounts (
  AccountID int(10) unsigned NOT NULL auto_increment,
  CustomerID int(10) unsigned default NULL,
  Username varchar(50) default NULL,
  Password varchar(50) default NULL,
  DateOpened date default NULL,
  DateClosed date default NULL,
  Status varchar(25) default NULL,
  PackageGroupID int(10) unsigned default NULL,
  Domain varchar(125) default NULL,
  LastDateBilled date default NULL,
  PRIMARY KEY  (AccountID),
  UNIQUE KEY AccountID (AccountID)
) TYPE=MyISAM;

#
# Table structure for table 'Configuration'
#

DROP TABLE IF EXISTS Configuration;
CREATE TABLE Configuration (
  Version varchar(50) default NULL,
  Language varchar(50) default NULL,
  SearchBar enum('On','Off') default NULL,
  Name varchar(100) default NULL,
  TaxRate float(6,4) default NULL,
  BillFromAddress varchar(100) default NULL,
  BillReplyAddress varchar(100) default NULL,
  BillSubject varchar(100) default NULL,
  BillHeader text,
  BillFooter text,
  TicketNotifier varchar(100) default NULL,
  HotTicket enum('On','Off') default NULL,
  BillBcc varchar(100) default NULL,
  CurrencyAfter enum('On','Off') default NULL
) TYPE=MyISAM;

LOCK TABLES Configuration WRITE;
INSERT INTO Configuration VALUES ('2.0.0','english.php','On','gcdb',0.0800,'gphat@cafes.net','billsgphat@cafes.net','gcdb Invoice','The following is an invoice for your account.','Thank you for your business!','gphat@cafes.net','On','gphat@cafes.net','Off');
UNLOCK TABLES;

#
# Table structure for table 'Contacts'
#

DROP TABLE IF EXISTS Contacts;
CREATE TABLE Contacts (
  ContactID int(10) unsigned NOT NULL auto_increment,
  CustomerID int(10) unsigned default NULL,
  First varchar(30) default NULL,
  Mid char(1) default NULL,
  Last varchar(40) default NULL,
  Phone varchar(16) default NULL,
  Mobile varchar(16) default NULL,
  Email varchar(100) default NULL,
  Notes varchar(80) default NULL,
  PRIMARY KEY  (ContactID),
  UNIQUE KEY ContactID (ContactID)
) TYPE=MyISAM;

#
# Table structure for table 'Customers'
#

DROP TABLE IF EXISTS Customers;
CREATE TABLE Customers (
  CustomerID int(10) unsigned NOT NULL auto_increment,
  First varchar(30) default NULL,
  Mid char(1) default NULL,
  Last varchar(40) default NULL,
  Address varchar(255) default NULL,
  Telephone varchar(16) default NULL,
  Fax varchar(16) default NULL,
  Email varchar(100) default NULL,
  City varchar(50) default NULL,
  State varchar(50) default NULL,
  Zip varchar(10) default NULL,
  Balance float(10,8) default NULL,
  CCNumber varchar(20) default NULL,
  CCName varchar(80) default NULL,
  Password varchar(50) default NULL,
  Company varchar(100) default NULL,
  CCExpire varchar(10) default NULL,
  Country varchar(100) default NULL,
  Overdraft float(10,8) default NULL,
  PRIMARY KEY  (CustomerID),
  UNIQUE KEY id (CustomerID)
) TYPE=MyISAM;

#
# Table structure for table 'Invoices'
#

DROP TABLE IF EXISTS Invoices;
CREATE TABLE Invoices (
  InvoiceID int(10) unsigned NOT NULL auto_increment,
  CustomerID int(10) unsigned NOT NULL default '0',
  Description varchar(100) default NULL,
  DateBilled date default NULL,
  Amount float(10,8) default NULL,
  PRIMARY KEY  (InvoiceID),
  UNIQUE KEY InvoiceID (InvoiceID)
) TYPE=MyISAM;

#
# Table structure for table 'News'
#

DROP TABLE IF EXISTS News;
CREATE TABLE News (
  NewsID int(10) unsigned NOT NULL auto_increment,
  Title varchar(255) default NULL,
  Content text,
  PostedDate date default NULL,
  Poster varchar(50) default NULL,
  PRIMARY KEY  (NewsID),
  UNIQUE KEY NewsID (NewsID)
) TYPE=MyISAM;

#
# Table structure for table 'Notes'
#

DROP TABLE IF EXISTS Notes;
CREATE TABLE Notes (
  NoteID int(10) unsigned NOT NULL auto_increment,
  Note varchar(255) default NULL,
  PostedDate date default NULL,
  Poster varchar(50) default NULL,
  CustomerID int(10) unsigned default NULL,
  PRIMARY KEY  (NoteID),
  UNIQUE KEY NoteID (NoteID)
) TYPE=MyISAM;

#
# Table structure for table 'Package'
#

DROP TABLE IF EXISTS Package;
CREATE TABLE Package (
  PackageID int(10) unsigned NOT NULL auto_increment,
  PackageGroupID int(10) unsigned NOT NULL default '0',
  ResourceID int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (PackageID),
  UNIQUE KEY PackageID (PackageID)
) TYPE=MyISAM;

#
# Table structure for table 'PackageGroup'
#

DROP TABLE IF EXISTS PackageGroup;
CREATE TABLE PackageGroup (
  PackageGroupID int(10) unsigned NOT NULL auto_increment,
  Description varchar(100) default NULL,
  Charged varchar(15) default NULL,
  PRIMARY KEY  (PackageGroupID),
  UNIQUE KEY PackageGroupID (PackageGroupID)
) TYPE=MyISAM;

#
# Table structure for table 'Payments'
#

DROP TABLE IF EXISTS Payments;
CREATE TABLE Payments (
  PaymentID int(10) unsigned NOT NULL auto_increment,
  CustomerID int(10) unsigned NOT NULL default '0',
  DatePaid date default NULL,
  Type varchar(10) default NULL,
  Number varchar(50) default NULL,
  Amount float(10,8) default NULL,
  PRIMARY KEY  (PaymentID),
  UNIQUE KEY PaymentID (PaymentID)
) TYPE=MyISAM;

#
# Table structure for table 'Resources'
#

DROP TABLE IF EXISTS Resources;
CREATE TABLE Resources (
  ResourceID int(10) unsigned NOT NULL auto_increment,
  Description varchar(100) default NULL,
  Price float(10,8) default NULL,
  TaxRate float(6,4) default NULL,
  PRIMARY KEY  (ResourceID),
  UNIQUE KEY ResourceID (ResourceID)
) TYPE=MyISAM;

#
# Table structure for table 'TicketWork'
#

DROP TABLE IF EXISTS TicketWork;
CREATE TABLE TicketWork (
  TicketWorkID int(10) unsigned NOT NULL auto_increment,
  TicketID int(10) unsigned default NULL,
  PRIMARY KEY  (TicketWorkID),
  UNIQUE KEY TicketWorkID (TicketWorkID)
) TYPE=MyISAM;

# Table structure for table 'Tickets'
#

DROP TABLE IF EXISTS Tickets;
CREATE TABLE Tickets (
  TicketID int(10) unsigned NOT NULL auto_increment,
  CustomerID int(10) unsigned NOT NULL default '0',
  Description text,
  Status enum('Open','Closed') default NULL,
  OpenDate date default NULL,
  OpenTime time default NULL,
  CloseDate date default NULL,
  CloseTime time default NULL,
  Opener varchar(100) default NULL,
  Billable enum('Yes','No') default NULL,
  Billed enum('Yes','No') default NULL,
  PRIMARY KEY  (TicketID),
  UNIQUE KEY TicketID (TicketID)
) TYPE=MyISAM;

#
# Table structure for table 'Users'
#

DROP TABLE IF EXISTS Users;
CREATE TABLE Users (
  UserID int(10) unsigned NOT NULL auto_increment,
  Username varchar(50) default NULL,
  Password varchar(50) default NULL,
  RealName varchar(100) default NULL,
  Language varchar(50) default NULL,
  Admin enum('Yes','No') default NULL,
  PRIMARY KEY  (UserID)
) TYPE=MyISAM;

#
# Dumping data for table 'Users'
#

LOCK TABLES Users WRITE;
INSERT INTO Users VALUES (1,'admin','admin','Administrator','english.php','Yes');
UNLOCK TABLES;
