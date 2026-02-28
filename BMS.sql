CREATE USER 'eagle'@'localhost' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON eagle_bms.* TO 'eagle'@'localhost';
FLUSH PRIVILEGES;

CREATE SCHEMA eagle_bms;
USE eagle_bms;
CREATE TABLE users (
    cnic VARCHAR(15) PRIMARY KEY,
    name VARCHAR(70) NOT NULL,
    pswd VARCHAR(255) NOT NULL,
    dob DATE NOT NULL,
    status ENUM('Active', 'On Leave', 'On Mission', 'KIA', 'Injured', 'Retired', 'Deceased') NOT NULL,
    salary INT NOT NULL,
    rank ENUM('Soldier', 'Captain', 'Major', 'Colonel', 'Brigadier', 'Major General', 'Lieutenant General', 'General') NOT NULL,
    position ENUM('Battalion', 'Brigade', 'Division', 'Corp', 'General Head Quarters') NOT NULL,
    CHECK (cnic REGEXP '^[0-9]{5}-[0-9]{7}-[0-9]$'),
    CHECK ((rank = 'General' AND position = 'General Head Quarters') 
        OR (rank = 'Lieutenant General' AND position IN ('General Head Quarters', 'Corp')) 
        OR (rank = 'Major General' AND position IN ('General Head Quarters', 'Corp', 'Division')) 
        OR (rank = 'Brigadier' AND position IN ('General Head Quarters', 'Corp', 'Division', 'Brigade')) 
        OR (rank = 'Colonel' AND position IN ('General Head Quarters', 'Corp', 'Division', 'Brigade', 'Battalion'))  
        OR (rank IN ('Major', 'Captain', 'Soldier') AND position IN ('General Head Quarters', 'Corp', 'Division', 'Brigade', 'Battalion')))
);

CREATE TABLE corps (
    corpID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) UNIQUE NOT NULL,
    location VARCHAR(50) UNIQUE NOT NULL,
    headedBy VARCHAR(15) UNIQUE NOT NULL,
    FOREIGN KEY (headedBy) REFERENCES users(cnic)
);

CREATE TABLE divisions (
    divID INT AUTO_INCREMENT PRIMARY KEY,
    corpID INT NOT NULL,
    name VARCHAR(100)  UNIQUE NOT NULL,
    type ENUM('Armoured','Infantry','Artillery') NOT NULL,
    location VARCHAR(100)  UNIQUE NOT NULL,
    headedBy VARCHAR(15)  UNIQUE NOT NULL,
    FOREIGN KEY (headedBy) REFERENCES users(cnic),
    FOREIGN KEY (corpID) REFERENCES corps(corpID)
);

CREATE TABLE brigades (
    brgID INT AUTO_INCREMENT PRIMARY KEY,
    divID INT NOT NULL,
    name VARCHAR(100) UNIQUE NOT NULL,
    location VARCHAR(100) UNIQUE NOT NULL,
    headedBy VARCHAR(15) UNIQUE NOT NULL,
    FOREIGN KEY (headedBy) REFERENCES users(cnic),
    FOREIGN KEY (divID) REFERENCES divisions(divID)
);

CREATE TABLE battalions (
    btlnID INT AUTO_INCREMENT PRIMARY KEY,
    brgID INT NOT NULL,
    name VARCHAR(100) UNIQUE NOT NULL,
    location VARCHAR(100) UNIQUE NOT NULL,
    headedBy VARCHAR(15) UNIQUE NOT NULL,
    FOREIGN KEY (headedBy) REFERENCES users(cnic),
    FOREIGN KEY (brgID) REFERENCES brigades(brgID)
);


CREATE TABLE btlnUsers (
    cnic VARCHAR(15) NOT NULL,
    btlnID INT NOT NULL,
    FOREIGN KEY (cnic) REFERENCES users(cnic),
    FOREIGN KEY (btlnID) REFERENCES battalions(btlnID)
);

CREATE TABLE brgUsers (
    cnic VARCHAR(15) NOT NULL,
    brgID INT NOT NULL,
    FOREIGN KEY (cnic) REFERENCES users(cnic),
    FOREIGN KEY (brgID) REFERENCES brigades(brgID)
);

CREATE TABLE divUsers (
    cnic VARCHAR(15) NOT NULL,
    divID INT NOT NULL,
    FOREIGN KEY (cnic) REFERENCES users(cnic),
    FOREIGN KEY (divID) REFERENCES divisions(divID)
);

CREATE TABLE corpUsers (
    cnic VARCHAR(15) NOT NULL,
    corpID INT NOT NULL,
    FOREIGN KEY (cnic) REFERENCES users(cnic),
    FOREIGN KEY (corpID) REFERENCES corps(corpID)
);

CREATE TABLE hierarchy (
    userID VARCHAR(15),
    superiorID VARCHAR(15),
    FOREIGN KEY (userID) REFERENCES users(cnic),
    FOREIGN KEY (superiorID) REFERENCES users(cnic),
    PRIMARY KEY (userID, superiorID)
);

CREATE TABLE resources (
    rID INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('Weapon','Vehicle','Electronics','Others') NOT NULL,
    details VARCHAR(800) NOT NULL,
    pssdBy VARCHAR(15),
    FOREIGN KEY (pssdBy) REFERENCES users(cnic)
);

CREATE TABLE missions (
    msnID INT AUTO_INCREMENT PRIMARY KEY,
    assgnBy VARCHAR(15),
    assgnTo VARCHAR(15),
    msnTitle VARCHAR(50) NOT NULL,
    msnDesc VARCHAR(5500) NOT NULL,
    msnStatus ENUM('Not Started', 'In Progress', 'Completed', 'On Hold', 'Failed') NOT NULL,
    FOREIGN KEY (assgnBy) REFERENCES users(cnic),
    FOREIGN KEY (assgnTo) REFERENCES users(cnic)
);

CREATE TABLE intel (
  intelID INT AUTO_INCREMENT PRIMARY KEY,
  intelTime TIMESTAMP,
  retrievedFrom VARCHAR(15),
  intendedFor VARCHAR(15),
  intelTitle VARCHAR (70) NOT NULL,
  intelDesc VARCHAR(2500) NOT NULL,
  FOREIGN KEY (retrievedFrom) REFERENCES users(cnic),
  FOREIGN KEY (intendedFor) REFERENCES users(cnic)
);

CREATE TABLE messages (
  msgId INT AUTO_INCREMENT PRIMARY KEY,
  msgTime TIMESTAMP,
  retrievedFrom VARCHAR(15),
  intendedFor VARCHAR(15),
  message VARCHAR(2500) NOT NULL,
  FOREIGN KEY (retrievedFrom) REFERENCES users(cnic),
  FOREIGN KEY (intendedFor) REFERENCES users(cnic)
);

CREATE VIEW userDetails AS
SELECT u.cnic, u.name, u.rank, u.position,  h.superiorID, c.corpID AS corpID, d.divID AS divisionID, b.brgID AS brigadeID, bt.btlnID AS battalionID
FROM users u
LEFT JOIN corpUsers cu ON u.cnic = cu.cnic
LEFT JOIN corps c ON cu.corpID = c.corpID
LEFT JOIN divUsers du ON u.cnic = du.cnic
LEFT JOIN divisions d ON du.divID = d.divID
LEFT JOIN brgUsers bru ON u.cnic = bru.cnic
LEFT JOIN brigades b ON bru.brgID = b.brgID
LEFT JOIN btlnUsers btu ON u.cnic = btu.cnic
LEFT JOIN battalions bt ON btu.btlnID = bt.btlnID
LEFT JOIN hierarchy h ON u.cnic = h.userID ;