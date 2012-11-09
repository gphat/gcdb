--INSTRUCTIONS:  *** NOTE THIS WILL ERASE/RECREATE TABLES ***
--1. Create your database, for example under the name: gcdb
--2. Install the tables using: psql gcdb < raw.pgsql
--
--Table structure for table 'Accounts'

DROP SEQUENCE account_seq;
CREATE SEQUENCE account_seq;

DROP TABLE Accounts;
CREATE TABLE Accounts (
  AccountID INTEGER NOT NULL DEFAULT nextval('account_seq'),
  CustomerID INTEGER DEFAULT NULL,
  Username VARCHAR(50) DEFAULT NULL,
  Password VARCHAR(50) DEFAULT NULL,
  DateOpened DATE DEFAULT NULL,
  DateClosed DATE DEFAULT NULL,
  Status VARCHAR(25) DEFAULT NULL,
  PackageGroupID INTEGER DEFAULT NULL,
  Domain VARCHAR(125) DEFAULT NULL,
  LastDateBilled DATE DEFAULT NULL,
  PRIMARY KEY  (AccountID),
  UNIQUE (AccountID)
);

--Table structure for table 'Configuration'

DROP TABLE Configuration;
CREATE TABLE Configuration (
  Version VARCHAR(50) DEFAULT NULL,
  Language VARCHAR(50) DEFAULT NULL,
  SearchBar VARCHAR(3) CHECK (SearchBar IN ('On','Off',NULL)) DEFAULT NULL,
  Name VARCHAR(100) DEFAULT NULL,
  TaxRate NUMERIC(6,2) DEFAULT NULL,
  BillFromAddress VARCHAR(100) DEFAULT NULL,
  BillReplyAddress VARCHAR(100) DEFAULT NULL,
  BillSubject VARCHAR(100) DEFAULT NULL,
  BillHeader TEXT,
  BillFooter TEXT,
  TicketNotifier VARCHAR(100) DEFAULT NULL,
  HotTicket VARCHAR(3) CHECK (HotTicket IN ('On','Off',NULL)) DEFAULT NULL,
  BillBcc VARCHAR(100) DEFAULT NULL,
  CurrencyAfter VARCHAR(3) CHECK (CurrencyAfter IN ('On','Off',NULL)) DEFAULT NULL
);

--
--Dumping data for table 'Configuration'
--

INSERT INTO Configuration VALUES ('2.0.0','english.php','On','gcdb',0.0800,'gphat@cafes.net','billsgphat@cafes.net','gcdb Invoice','The following is an invoice for your account.','Thank you for your business!','gphat@cafes.net','On','gphat@cafes.net','Off');

--
--Table structure for table 'Contacts'
--

DROP SEQUENCE contact_seq;
CREATE SEQUENCE contact_seq;

DROP TABLE Contacts;
CREATE TABLE Contacts (
  ContactID INTEGER NOT NULL DEFAULT nextval('contact_seq'),
  CustomerID INTEGER DEFAULT NULL,
  First VARCHAR(30) DEFAULT NULL,
  Mid CHAR(1) DEFAULT NULL,
  Last VARCHAR(40) DEFAULT NULL,
  Phone VARCHAR(16) DEFAULT NULL,
  Mobile VARCHAR(16) DEFAULT NULL,
  Email VARCHAR(100) DEFAULT NULL,
  Notes VARCHAR(80) DEFAULT NULL,
  PRIMARY KEY  (ContactID),
  UNIQUE (ContactID)
);

--
--Table structure for table 'Customers'
--

DROP SEQUENCE customer_seq;
CREATE SEQUENCE customer_seq;

DROP TABLE Customers;
CREATE TABLE Customers (
  CustomerID INTEGER NOT NULL DEFAULT nextval('customer_seq'),
  First VARCHAR(30) DEFAULT NULL,
  Mid CHAR(1) DEFAULT NULL,
  Last VARCHAR(40) DEFAULT NULL,
  Address VARCHAR(255) DEFAULT NULL,
  Telephone VARCHAR(16) DEFAULT NULL,
  Fax VARCHAR(16) DEFAULT NULL,
  Email VARCHAR(100) DEFAULT NULL,
  City VARCHAR(50) DEFAULT NULL,
  State VARCHAR(50) DEFAULT NULL,
  Zip VARCHAR(10) DEFAULT NULL,
  Balance NUMERIC(10,2) DEFAULT NULL,
  CCNumber VARCHAR(20) DEFAULT NULL,
  CCName VARCHAR(80) DEFAULT NULL,
  Password VARCHAR(50) DEFAULT NULL,
  Company VARCHAR(100) DEFAULT NULL,
  CCExpire VARCHAR(10) DEFAULT NULL,
  Country VARCHAR(100) DEFAULT NULL,
  Overdraft NUMERIC(10,2) DEFAULT NULL,
  PRIMARY KEY  (CustomerID),
  UNIQUE (CustomerID)
);

--
--Table structure for table 'Invoices'
--

DROP SEQUENCE invoice_seq;
CREATE SEQUENCE invoice_seq;

DROP TABLE Invoices;
CREATE TABLE Invoices (
  InvoiceID INTEGER NOT NULL DEFAULT nextval('invoice_seq'),
  CustomerID INTEGER NOT NULL DEFAULT '0',
  Description VARCHAR(100) DEFAULT NULL,
  DateBilled DATE DEFAULT NULL,
  Amount NUMERIC(10,2) DEFAULT NULL,
  PRIMARY KEY  (InvoiceID),
  UNIQUE (InvoiceID)
);

--
--Table structure for table 'News'
--

DROP SEQUENCE news_seq;
CREATE SEQUENCE news_seq;

DROP TABLE News;
CREATE TABLE News (
  NewsID INTEGER NOT NULL DEFAULT nextval('news_seq'),
  Title VARCHAR(255) DEFAULT NULL,
  Content TEXT,
  PostedDate DATE DEFAULT NULL,
  Poster VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY  (NewsID),
  UNIQUE (NewsID)
);

--
--Table structure for table 'Notes'
--

DROP SEQUENCE notes_seq;
CREATE SEQUENCE notes_seq;

DROP TABLE Notes;
CREATE TABLE Notes (
  NoteID INTEGER NOT NULL DEFAULT nextval('notes_seq'),
  Note VARCHAR(255) DEFAULT NULL,
  PostedDate DATE DEFAULT NULL,
  Poster VARCHAR(50) DEFAULT NULL,
  CustomerID INTEGER DEFAULT NULL,
  PRIMARY KEY  (NoteID),
  UNIQUE (NoteID)
);

--
--Table structure for table 'Package'
--

DROP SEQUENCE package_seq;
CREATE SEQUENCE package_seq;

DROP TABLE Package;
CREATE TABLE Package (
  PackageID INTEGER NOT NULL DEFAULT nextval('package_seq'),
  PackageGroupID INTEGER NOT NULL DEFAULT '0',
  ResourceID INTEGER NOT NULL DEFAULT '0',
  PRIMARY KEY  (PackageID),
  UNIQUE (PackageID)
);

--
--Table structure for table 'PackageGroup'
--

DROP SEQUENCE packagegroup_seq;
CREATE SEQUENCE packagegroup_seq;

DROP TABLE PackageGroup;
CREATE TABLE PackageGroup (
  PackageGroupID INTEGER NOT NULL DEFAULT nextval('packagegroup_seq'),
  Description VARCHAR(100) DEFAULT NULL,
  Charged VARCHAR(15) DEFAULT NULL,
  PRIMARY KEY  (PackageGroupID),
  UNIQUE (PackageGroupID)
);

--
--Table structure for table 'Payments'
--

DROP SEQUENCE payments_seq;
CREATE SEQUENCE payments_seq;

DROP TABLE Payments;
CREATE TABLE Payments (
  PaymentID INTEGER NOT NULL DEFAULT nextval('payments_seq'),
  CustomerID INTEGER NOT NULL DEFAULT '0',
  DatePaid DATE DEFAULT NULL,
  Type VARCHAR(10) DEFAULT NULL,
  Number VARCHAR(50) DEFAULT NULL,
  Amount NUMERIC(10,2) DEFAULT NULL,
  PRIMARY KEY  (PaymentID),
  UNIQUE (PaymentID)
);

--
--Table structure for table 'Resources'
--

DROP SEQUENCE resource_seq;
CREATE SEQUENCE resource_seq;

DROP TABLE Resources;
CREATE TABLE Resources (
  ResourceID INTEGER NOT NULL DEFAULT nextval('resource_seq'),
  Description VARCHAR(100) DEFAULT NULL,
  Price NUMERIC(10,2) DEFAULT NULL,
  TaxRate NUMERIC(6,2) DEFAULT NULL,
  PRIMARY KEY  (ResourceID),
  UNIQUE (ResourceID)
);

--
--Table structure for table 'TicketWork'
--

DROP SEQUENCE ticketwork_seq;
CREATE SEQUENCE ticketwork_seq;

DROP TABLE TicketWork;
CREATE TABLE TicketWork (
  TicketWorkID INTEGER NOT NULL DEFAULT nextval('ticketwork_seq'),
  TicketID INTEGER DEFAULT NULL,
  PRIMARY KEY  (TicketWorkID),
  UNIQUE (TicketWorkID)
);

--Table structure for table 'Tickets'
--

DROP SEQUENCE ticket_seq;
CREATE SEQUENCE ticket_seq;

DROP TABLE Tickets;
CREATE TABLE Tickets (
  TicketID INTEGER NOT NULL DEFAULT nextval('ticket_seq'),
  CustomerID INTEGER NOT NULL DEFAULT '0',
  Description TEXT,
  Status VARCHAR(6) CHECK (Status IN ('Open','Closed',NULL)) DEFAULT NULL,
  OpenDate DATE DEFAULT NULL,
  OpenTime TIME DEFAULT NULL,
  CloseDate DATE DEFAULT NULL,
  CloseTime TIME DEFAULT NULL,
  Opener VARCHAR(100) DEFAULT NULL,
  Billable VARCHAR(3) CHECK (Billable IN ('Yes','No',NULL)) DEFAULT NULL,
  Billed VARCHAR(3) CHECK (Billed IN ('Yes','No',NULL)) DEFAULT NULL,
  PRIMARY KEY  (TicketID),
  UNIQUE (TicketID)
);

--
--Table structure for table 'Users'
--

DROP SEQUENCE user_seq;
CREATE SEQUENCE user_seq;

DROP TABLE Users;
CREATE TABLE Users (
  UserID INTEGER NOT NULL DEFAULT nextval('user_seq'),
  Username VARCHAR(50) DEFAULT NULL,
  Password VARCHAR(50) DEFAULT NULL,
  RealName VARCHAR(100) DEFAULT NULL,
  Language VARCHAR(50) DEFAULT NULL,
  Admin VARCHAR(3) CHECK (Admin IN ('Yes','No',NULL)) DEFAULT NULL,
  PRIMARY KEY  (UserID),
  UNIQUE  (UserID)
);

--
--Dumping data for table 'Users'
---

INSERT INTO Users (Username, Password, RealName, Language, Admin) VALUES ('admin','admin','Administrator','english.php','Yes');
