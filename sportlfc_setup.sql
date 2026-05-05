DROP DATABASE IF EXISTS sportlfc;
CREATE DATABASE sportlfc;
USE sportlfc;

CREATE TABLE Users (
    USERID INT NOT NULL AUTO_INCREMENT,
    DOB DATE,
    Sex CHAR(1),
    Fname VARCHAR(15) NOT NULL,
    Lname VARCHAR(15) NOT NULL,
    Password VARCHAR(50) NOT NULL,
    CONSTRAINT UserPK PRIMARY KEY (USERID)
);

CREATE TABLE Admin (
    A_USERID INT NOT NULL,
    IS_Admin BOOLEAN NOT NULL DEFAULT TRUE,
    CONSTRAINT AdminPK PRIMARY KEY (A_USERID),
    CONSTRAINT AdminUserFK FOREIGN KEY (A_USERID) REFERENCES Users(USERID)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Coach (
    C_USERID INT NOT NULL,
    HireDate DATE NOT NULL,
    CONSTRAINT CoachPK PRIMARY KEY (C_USERID),
    CONSTRAINT CoachUserFK FOREIGN KEY (C_USERID) REFERENCES Users(USERID)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Player (
    P_USERID INT NOT NULL,
    Fee DECIMAL(10,2) DEFAULT 0.00,
    CONSTRAINT PlayerPK PRIMARY KEY (P_USERID),
    CONSTRAINT PlayerUserFK FOREIGN KEY (P_USERID) REFERENCES Users(USERID)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Sport (
    Name VARCHAR(50) NOT NULL,
    CONSTRAINT SportPK PRIMARY KEY (Name)
);

CREATE TABLE Equipment (
    EQID INT NOT NULL AUTO_INCREMENT,
    Name VARCHAR(50) NOT NULL,
    Size VARCHAR(20),
    Price DECIMAL(10,2) NOT NULL,
    CONSTRAINT EquipmentPK PRIMARY KEY (EQID)
);

CREATE TABLE Team (
    TeamID INT NOT NULL AUTO_INCREMENT,
    SprtName VARCHAR(50) NOT NULL,
    FrstPlayer INT,
    HeadCoach INT,
    Name VARCHAR(50) NOT NULL,
    Sex CHAR(1),
    Min_age INT,
    Max_age INT,
    CONSTRAINT TeamPK PRIMARY KEY (TeamID),
    CONSTRAINT TeamSportFK FOREIGN KEY (SprtName) REFERENCES Sport(Name)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT TeamPlayerFK FOREIGN KEY (FrstPlayer) REFERENCES Player(P_USERID)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT TeamCoachFK FOREIGN KEY (HeadCoach) REFERENCES Coach(C_USERID)
        ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE Plays_In (
    PlayerID INT NOT NULL,
    TeamID INT NOT NULL,
    Role VARCHAR(30),
    Uniform_No INT,
    CONSTRAINT PlaysInPK PRIMARY KEY (PlayerID, TeamID),
    CONSTRAINT PlaysInPlayerFK FOREIGN KEY (PlayerID) REFERENCES Player(P_USERID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT PlaysInTeamFK FOREIGN KEY (TeamID) REFERENCES Team(TeamID)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Plays (
    PlayerID INT NOT NULL,
    SportName VARCHAR(50) NOT NULL,
    CONSTRAINT PlaysPK PRIMARY KEY (PlayerID, SportName),
    CONSTRAINT PlaysPlayerFK FOREIGN KEY (PlayerID) REFERENCES Player(P_USERID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT PlaysSportFK FOREIGN KEY (SportName) REFERENCES Sport(Name)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Needs (
    Sport_name VARCHAR(50) NOT NULL,
    EQID INT NOT NULL,
    Min_num INT,
    CONSTRAINT NeedsPK PRIMARY KEY (EQID, Sport_name),
    CONSTRAINT NeedsSportFK FOREIGN KEY (Sport_name) REFERENCES Sport(Name)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT NeedsEqptFK FOREIGN KEY (EQID) REFERENCES Equipment(EQID)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Specialize_In (
    C_USERID INT NOT NULL,
    Sport_name VARCHAR(50) NOT NULL,
    CONSTRAINT SpecializePK PRIMARY KEY (C_USERID, Sport_name),
    CONSTRAINT SpecializeCoachFK FOREIGN KEY (C_USERID) REFERENCES Coach(C_USERID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT SpecializeSportFK FOREIGN KEY (Sport_name) REFERENCES Sport(Name)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Coaches (
    TeamID INT NOT NULL,
    C_USERID INT NOT NULL,
    Role VARCHAR(30),
    CONSTRAINT CoachesPK PRIMARY KEY (TeamID, C_USERID),
    CONSTRAINT CoachesTeamFK FOREIGN KEY (TeamID) REFERENCES Team(TeamID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT CoachesCoachFK FOREIGN KEY (C_USERID) REFERENCES Coach(C_USERID)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Buys (
    EQID INT NOT NULL,
    PlayerID INT NOT NULL,
    QTY INT NOT NULL DEFAULT 1,
    CONSTRAINT BuysPK PRIMARY KEY (EQID, PlayerID),
    CONSTRAINT BuysEqptFK FOREIGN KEY (EQID) REFERENCES Equipment(EQID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT BuysPlayerFK FOREIGN KEY (PlayerID) REFERENCES Player(P_USERID)
        ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO Users (USERID, DOB, Sex, Fname, Lname, Password) VALUES
(1,  '1980-03-15', 'M', 'James',   'Carter',   'admin123'),
(2,  '1975-07-22', 'F', 'Linda',   'Morris',   'admin456'),
(3,  '1985-01-10', 'M', 'David',   'Hughes',   'coach123'),
(4,  '1982-06-05', 'F', 'Sarah',   'Kim',      'coach456'),
(5,  '1979-11-20', 'M', 'Marcus',  'Brown',    'coach789'),
(6,  '1988-04-14', 'M', 'Tony',    'Rivera',   'coachabc'),
(7,  '1983-09-30', 'F', 'Elena',   'Sousa',    'coachxyz'),
(8,  '2000-02-18', 'M', 'Jake',    'Thompson', 'pass1'),
(9,  '2001-05-22', 'F', 'Amara',   'Diallo',   'pass2'),
(10, '1999-08-11', 'M', 'Chris',   'Nwosu',    'pass3'),
(11, '2002-03-04', 'F', 'Sofia',   'Lopez',    'pass4'),
(12, '2000-12-19', 'M', 'Kevin',   'Park',     'pass5'),
(13, '1998-07-07', 'M', 'Dante',   'Reyes',    'pass6'),
(14, '2003-01-25', 'F', 'Nia',     'Okafor',   'pass7'),
(15, '2001-10-13', 'M', 'Leo',     'Ferreira', 'pass8'),
(16, '1999-04-02', 'F', 'Zara',    'Ahmed',    'pass9'),
(17, '2002-06-28', 'M', 'Carlos',  'Mendez',   'pass10'),
(18, '2000-09-15', 'M', 'Kofi',    'Asante',   'pass11'),
(19, '2001-11-03', 'F', 'Priya',   'Sharma',   'pass12'),
(20, '1999-02-14', 'M', 'Ethan',   'Brooks',   'pass13'),
(21, '2003-07-09', 'F', 'Aaliya',  'Hassan',   'pass14'),
(22, '2000-05-21', 'M', 'Nathan',  'Cole',     'pass15');

INSERT INTO Admin (A_USERID, IS_Admin) VALUES (1, TRUE), (2, TRUE);

INSERT INTO Coach (C_USERID, HireDate) VALUES
(3, '2018-08-01'),(4, '2019-01-15'),(5, '2017-03-10'),(6, '2020-06-01'),(7, '2021-09-01');

INSERT INTO Player (P_USERID, Fee) VALUES
(8,150.00),(9,200.00),(10,150.00),(11,175.00),(12,200.00),(13,150.00),(14,125.00),
(15,175.00),(16,200.00),(17,150.00),(18,175.00),(19,125.00),(20,200.00),(21,150.00),(22,175.00);

INSERT INTO Sport (Name) VALUES ('Soccer'),('Basketball'),('Swimming'),('Tennis'),('Track');

INSERT INTO Equipment (EQID, Name, Size, Price) VALUES
(1,'Soccer Ball','Size 5',25.00),(2,'Shin Guards','Medium',15.00),(3,'Cleats','Size 10',80.00),
(4,'Basketball','Size 7',30.00),(5,'Basketball Shoes','Size 10',120.00),
(6,'Swim Cap','Standard',10.00),(7,'Goggles','Standard',20.00),(8,'Swimsuit','Medium',45.00),
(9,'Tennis Racket','Standard',95.00),(10,'Tennis Balls','Standard',8.00),
(11,'Running Shoes','Size 10',90.00),(12,'Track Spikes','Size 9',75.00),
(13,'Jersey','Large',40.00),(14,'Shorts','Medium',25.00),(15,'Water Bottle','Standard',12.00);

INSERT INTO Team (TeamID, SprtName, FrstPlayer, HeadCoach, Name, Sex, Min_age, Max_age) VALUES
(1,'Soccer',8,3,'Lake Forest Lions','M',18,30),
(2,'Soccer',9,4,'North Shore FC','F',16,28),
(3,'Basketball',10,5,'Midwest Ballers','M',18,32),
(4,'Basketball',11,4,'Chicago Flames','F',17,30),
(5,'Swimming',12,3,'Lakeview Dolphins','M',15,25),
(6,'Tennis',13,6,'Ace Squad','M',18,35),
(7,'Track',14,7,'Sprint Elite','F',16,28),
(8,'Soccer',15,5,'Forest City United','M',18,30),
(9,'Basketball',16,6,'Lake County Hoops','F',18,30),
(10,'Track',17,7,'North Track Club','M',16,26);

INSERT INTO Plays (PlayerID, SportName) VALUES
(8,'Soccer'),(9,'Soccer'),(10,'Basketball'),(11,'Basketball'),(12,'Swimming'),
(13,'Tennis'),(13,'Soccer'),(14,'Track'),(15,'Soccer'),(16,'Basketball'),
(17,'Track'),(18,'Soccer'),(18,'Basketball'),(19,'Swimming'),(20,'Basketball'),
(20,'Soccer'),(21,'Track'),(22,'Soccer');

INSERT INTO Plays_In (PlayerID, TeamID, Role, Uniform_No) VALUES
(8,1,'Forward',10),(9,2,'Midfielder',7),(10,3,'Point Guard',1),(11,4,'Guard',4),
(12,5,'Freestyle',3),(13,6,'Singles',2),(14,7,'Sprinter',8),(15,1,'Defender',14),
(15,8,'Midfielder',9),(16,4,'Forward',11),(16,9,'Guard',6),(17,10,'Distance',15),
(18,1,'Midfielder',5),(18,3,'Center',12),(19,5,'Backstroke',7),(20,3,'Shooting Guard',13),
(20,8,'Striker',17),(21,7,'Relay',20),(21,10,'Distance',18),(22,1,'Goalkeeper',1),(22,8,'Midfielder',8);

INSERT INTO Specialize_In (C_USERID, Sport_name) VALUES
(3,'Soccer'),(3,'Swimming'),(4,'Soccer'),(4,'Basketball'),(5,'Basketball'),
(5,'Swimming'),(6,'Tennis'),(6,'Basketball'),(7,'Track');

INSERT INTO Coaches (TeamID, C_USERID, Role) VALUES
(1,3,'Head Coach'),(2,4,'Head Coach'),(3,5,'Head Coach'),(4,4,'Head Coach'),
(5,3,'Head Coach'),(6,6,'Head Coach'),(7,7,'Head Coach'),(8,5,'Assistant Coach'),
(9,6,'Head Coach'),(10,7,'Head Coach'),(1,4,'Assistant Coach'),(3,6,'Assistant Coach');

INSERT INTO Needs (Sport_name, EQID, Min_num) VALUES
('Soccer',1,1),('Soccer',2,1),('Soccer',3,1),('Soccer',13,1),
('Basketball',4,1),('Basketball',5,1),('Basketball',13,1),
('Swimming',6,1),('Swimming',7,1),('Swimming',8,1),
('Tennis',9,1),('Tennis',10,3),
('Track',11,1),('Track',12,1),('Track',14,1);

INSERT INTO Buys (EQID, PlayerID, QTY) VALUES
(1,8,1),(2,8,1),(3,8,1),(4,10,1),(5,10,1),(6,12,1),(7,12,1),(9,13,1),(10,13,3),(11,14,1);

alter table Users
ADD COLUMN role VARCHAR(20) Default 'player';

update users set role = 'admin' where USERID in (select A_USERID from admin);
update users set role = 'coach' where USERID in (select C_USERID from coach);
