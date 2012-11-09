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
# Dumping data for table 'Accounts'
#

LOCK TABLES Accounts WRITE;
INSERT INTO Accounts VALUES (14,1,'','','2001-02-14','0000-00-00','Open',8,'',NULL),(15,26,'jz','snuffoleupaguss','2001-02-13','0000-00-00','Open',4,NULL,NULL),(17,1,'g','phat','2001-03-14','0000-00-00','Open',7,'gphat.com',NULL);
UNLOCK TABLES;

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

#
# Dumping data for table 'Configuration'
#

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
# Dumping data for table 'Contacts'
#

LOCK TABLES Contacts WRITE;
INSERT INTO Contacts VALUES (2,1,'Cory','G','Watson','(555) 555-5555','(555) 555-5555','gphat@cafes.net','Yah!'),(3,26,'Cory','G','Watson','(615) 850-3001','N/A','N/A','Good guy');
UNLOCK TABLES;

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
# Dumping data for table 'Customers'
#

LOCK TABLES Customers WRITE;
INSERT INTO Customers VALUES (1,'Cory','G','Watson','123 Coolguy Drive','(931) 555-5535','','gphat@cafes.net','Tullahoma','TN','37388',122.76000214,'1234-1234-1234-4321','Cory G Watson','bleh','','04/03','',NULL),(26,'Joanna','M','Zeliadt','28 White Bridge Road','(615) 843-2920','(615) 843-3001','jzeliadt@inphact.com','Nashville','TN','37205',0.00000000,'','','bleh','InPhact','','USA',NULL),(6,'Albert','R','Testerman','918 Made Up Road','(931) 393-0421','(931) 393-4390','nobody@nowhere.com','Nowhereville','TN','37388',175.00000000,'N/A','N/A',NULL,NULL,NULL,NULL,NULL),(7,'Bob','Q','Jenkins','132 Niegh Drive','(555) 555-5555','','bob@nowhere.com','Westchester','NC','18181',0.00000000,'1234-1234-1234-1234','Bob Q Jenkins','none','SillyTech','03/03',NULL,NULL),(22,'Al','J','Invisible','65 Temp Drive','(931) 393-4390','','gphat@cafes.net','Tullahoma','TN','37388',0.00000000,'','','uno','','',NULL,NULL),(25,'Simon','Q','Public','40 Movie Drive','','','gphat@cafes.net','Tullahoma','TN','37388',10.00000000,'','','arse','','',NULL,NULL);
UNLOCK TABLES;

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
# Dumping data for table 'Invoices'
#

LOCK TABLES Invoices WRITE;
INSERT INTO Invoices VALUES (3,1,'Setup Fee','2000-08-22',5.00000000),(6,7,'Setup Fee','2001-03-04',20.00000000),(5,6,'Setup','2001-01-08',25.00000000),(7,26,'Setup Fee','2001-03-13',25.00000000);
UNLOCK TABLES;

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
# Dumping data for table 'News'
#

LOCK TABLES News WRITE;
INSERT INTO News VALUES (1,'News Feature','News can now be posted, edited and removed from this page through the Administration section!','2001-02-11','gphat');
UNLOCK TABLES;

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
# Dumping data for table 'Notes'
#

LOCK TABLES Notes WRITE;
INSERT INTO Notes VALUES (1,'Testing, 1, 2, 3','2001-02-18','gphat',1),(2,'Really bossy','2001-03-13','gphat',26);
UNLOCK TABLES;

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
# Dumping data for table 'Package'
#

LOCK TABLES Package WRITE;
INSERT INTO Package VALUES (8,4,2),(7,4,1),(9,5,3),(13,7,4),(12,7,5),(14,7,1),(15,8,1),(16,8,4),(17,8,5);
UNLOCK TABLES;

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
# Dumping data for table 'PackageGroup'
#

LOCK TABLES PackageGroup WRITE;
INSERT INTO PackageGroup VALUES (5,'Consulting','monthly'),(4,'Standard Dialup','monthly'),(7,'Standard Hosting','monthly'),(8,'Quarterly Hosting','quarterly');
UNLOCK TABLES;

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
# Dumping data for table 'Payments'
#

LOCK TABLES Payments WRITE;
INSERT INTO Payments VALUES (31,1,'2001-03-12','Cash','1',60.00000000),(28,25,'2001-03-03','Cash','0001',10.00000000),(26,6,'2001-02-12','Cash','0',150.00000000),(32,1,'2001-03-13','Cash','0001',10.00000000),(29,7,'2001-03-04','Cash','1018920',20.00000000),(24,6,'2001-01-08','Cash','100',50.00000000),(30,1,'2001-03-06','Cash','0001',27.76000023),(33,1,'2001-03-13','Cash','0001',10.00000000),(34,1,'2001-03-13','Cash','1',10.00000000),(36,26,'2001-03-13','Cash','000001',25.00000000),(39,0,NULL,'Cash\'',NULL,NULL),(40,1,'2001-03-25','Cash','100101',10.00000000);
UNLOCK TABLES;

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
# Dumping data for table 'Resources'
#

LOCK TABLES Resources WRITE;
INSERT INTO Resources VALUES (1,'Disk Space (1Mb)',1.00000000,0.0800),(2,'Dialup Service (10 Hours)',9.94999981,0.0800),(3,'Consulting Time (Hour)',45.00000000,0.0800),(4,'Email Accounts (10)',10.00000000,0.0800),(5,'Domain Hosting',10.00000000,0.0800);
UNLOCK TABLES;

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

#
# Dumping data for table 'TicketWork'
#

LOCK TABLES TicketWork WRITE;
UNLOCK TABLES;

#
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
# Dumping data for table 'Tickets'
#

LOCK TABLES Tickets WRITE;
INSERT INTO Tickets VALUES (8,1,'This should work','Open','2001-02-13','11:29:02','0000-00-00','00:00:00','Customer',NULL,NULL),(15,1,'Blah','Open','2001-05-15','09:12:17','0000-00-00','00:00:00','Customer',NULL,NULL),(13,26,'Computer evaporated','Closed','2001-03-13','10:05:40','2001-03-13','10:06:54','Cory Watson',NULL,NULL);
UNLOCK TABLES;

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

