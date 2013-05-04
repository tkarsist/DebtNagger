CREATE DATABASE DEBTNAG;

CREATE TABLE DEBTNAG.USER (
       ID INT NOT NULL AUTO_INCREMENT
     , NICK VARCHAR(100) NOT NULL
     , EMAIL VARCHAR(255) NOT NULL
     , PWD VARCHAR(500)
     , NAGINTERVAL INT
     , CREATED DATETIME
     , LASTLOGIN DATETIME
     , PRIMARY KEY (ID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE DEBTNAG.CONTACT (
       ID INT NOT NULL AUTO_INCREMENT
     , NICK VARCHAR(100) NOT NULL
     , EMAIL VARCHAR(255) NOT NULL
     , NAG INT
     , LASTNAG DATETIME
     , NEXTNAG DATETIME
     , FK_USER_ID INT NOT NULL
     , PRIMARY KEY (ID)
     , INDEX (FK_USER_ID)
     , CONSTRAINT FK_CONTACT_1 FOREIGN KEY (FK_USER_ID)
                  REFERENCES DEBTNAG.USER (ID) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE DEBTNAG.DEBT (
       ID INT NOT NULL AUTO_INCREMENT
     , DATE DATETIME NOT NULL
     , DESCRIPTION VARCHAR(255)
     , SUM DOUBLE NOT NULL
     , FK_USER_ID INT NOT NULL
     , FK_CONTACT_ID INT NOT NULL
     , PRIMARY KEY (ID)
     , INDEX (FK_USER_ID)
     , CONSTRAINT FK_DEBT_1 FOREIGN KEY (FK_USER_ID)
                  REFERENCES DEBTNAG.USER (ID)
     , INDEX (FK_CONTACT_ID)
     , CONSTRAINT FK_DEBT_2 FOREIGN KEY (FK_CONTACT_ID)
                  REFERENCES DEBTNAG.CONTACT (ID) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE DEBTNAG.CLAIMED_DEBT (
       ID INT NOT NULL AUTO_INCREMENT
     , DATE DATETIME NOT NULL
     , DESCRIPTION VARCHAR(255)
     , SUM DOUBLE NOT NULL
     , FK_CONTACT_ID INT NOT NULL
     , FK_USER_ID INT NOT NULL
     , PRIMARY KEY (ID)
     , INDEX (FK_CONTACT_ID)
     , CONSTRAINT FK_CLAIMED_DEBT_1 FOREIGN KEY (FK_CONTACT_ID)
                  REFERENCES DEBTNAG.CONTACT (ID) ON DELETE CASCADE
     , INDEX (FK_USER_ID)
     , CONSTRAINT FK_CLAIMED_DEBT_2 FOREIGN KEY (FK_USER_ID)
                  REFERENCES DEBTNAG.USER (ID) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE DEBTNAG.REGISTER (
       ID INT NOT NULL AUTO_INCREMENT
     , CREATED DATETIME
     , NICK VARCHAR(100)
     , EMAIL VARCHAR(255)
     , PWD VARCHAR(500)
     , PRIMARY KEY (ID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE DEBTNAG.MAILQUEUE (
       ID INT NOT NULL AUTO_INCREMENT
     , CREATED DATETIME
     , RECIPIENT VARCHAR(100)
     , SUBJECT VARCHAR(100)
     , MESSAGE TEXT
     , PRIMARY KEY (ID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS  `ci_sessions` (
	session_id varchar(40) DEFAULT '0' NOT NULL,
	ip_address varchar(16) DEFAULT '0' NOT NULL,
	user_agent varchar(120) NOT NULL,
	last_activity int(10) unsigned DEFAULT 0 NOT NULL,
	user_data text NOT NULL,
	PRIMARY KEY (session_id),
	KEY `last_activity_idx` (`last_activity`)
);
