DROP DATABASE IF EXISTS conferenceDB;
CREATE DATABASE conferenceDB;
USE conferenceDB;

CREATE TABLE Sub_committe (
    name VARCHAR(20) primary key
);

CREATE TABLE Member (
    ID INTEGER NOT NULL,
    Name VARCHAR(25) NOT NULL,
    fname VARCHAR(10),
    lname VARCHAR(15),
    PRIMARY KEY(ID)
);

CREATE TABLE Chair (
    chair_id INTEGER NOT NULL,
    sub_title VARCHAR(20) NOT NULL,
    PRIMARY KEY (chair_id, sub_title),
    FOREIGN KEY (chair_id) REFERENCES Member(ID),
    FOREIGN KEY (sub_title) REFERENCES Sub_committe(name)
);

CREATE TABLE Member_of (
    member_id INTEGER NOT NULL,
    sub_title VARCHAR(20) NOT NULL,
    PRIMARY KEY (member_id, sub_title),
    FOREIGN KEY (member_id) REFERENCES Member(ID),
    FOREIGN KEY (sub_title) REFERENCES Sub_committe(name)
);

CREATE TABLE Attendee (
    name VARCHAR(20) NOT NULL,
    fee INTEGER,
    ID INTEGER NOT NULL,
    fname VARCHAR(10),
    lname VARCHAR(15),
    PRIMARY KEY(ID)
);

CREATE TABLE Student(
    ID INTEGER NOT NULL,
    name VARCHAR(20) NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY (ID) REFERENCES Attendee (ID) ON DELETE CASCADE
);

CREATE TABLE Professional(
    ID INTEGER NOT NULL,
    name VARCHAR(20) NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY (ID) REFERENCES Attendee (ID) ON DELETE CASCADE
);

CREATE TABLE Sponsor(
    ID INTEGER NOT NULL,
    name VARCHAR(20) NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY (ID) REFERENCES Attendee (ID) ON DELETE CASCADE
);

CREATE TABLE Speaker(
    ID INTEGER NOT NULL,
    name VARCHAR(20) NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY (ID) REFERENCES Attendee (ID) ON DELETE CASCADE
);

CREATE TABLE Room (
    num INTEGER,
    num_beds INTEGER,
    PRIMARY KEY(num)
);

CREATE TABLE Assigned (
    SID INTEGER NOT NULL,
    roomNum INTEGER,
    PRIMARY KEY(SID, roomNum),
    FOREIGN KEY (SID) REFERENCES Student (ID) ON DELETE CASCADE,
    FOREIGN KEY (roomNum) REFERENCES Room (num) ON DELETE CASCADE
);

CREATE TABLE Company (
    name VARCHAR(25) NOT NULL,
    num_emails_sent INTEGER, 
    level VARCHAR(20) NOT NULL,
    PRIMARY KEY(name)
);

CREATE TABLE Represents (
    sponsorID INTEGER NOT NULL,
    Company_name VARCHAR(25) NOT NULL,
    PRIMARY KEY(sponsorID, Company_name),
    FOREIGN KEY (sponsorID) REFERENCES Sponsor (ID) ON DELETE CASCADE,
    FOREIGN KEY (Company_name) REFERENCES Company (name) ON DELETE CASCADE
);

CREATE TABLE JobAb(
    jobID INTEGER NOT NULL,
    Company_name VARCHAR(25) NOT NULL,
    title VARCHAR(25) NOT NULL,
    salary DECIMAL(10,2) NOT NULL,
    location VARCHAR(30) NOT NULL,
    PRIMARY KEY(jobID),
    FOREIGN KEY (Company_name) REFERENCES Company(name) ON DELETE CASCADE
);

CREATE TABLE Session(
    sessionID INTEGER,
    speakerID INTEGER NOT NULL,
    room_location VARCHAR(30) NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    PRIMARY KEY(sessionID),
    FOREIGN KEY (speakerID) REFERENCES Speaker(ID) ON DELETE CASCADE
);

INSERT INTO Sub_committe VALUES
('Logistics'),
('Sponsorship'),
('Workshops'),
('Security'),
('Cleaning'),
('Hospitality');

INSERT INTO Member VALUES
(1, 'Sydney Crosby', 'Sydney', 'Crosby'),
(2, 'Jason Smith', 'Jason', 'Smith'),
(3, 'Erik Karlsson', 'Erik', 'Karlsson'),
(4, 'J.T Miller', 'J.T', 'Miller'),
(5, 'Nathan Cook', 'Nathan', 'Cook'),
(6, 'Nicholas Hum', 'Nicholas', 'Hum'),
(7, 'Bassic MeMba', 'Bassic', 'MeMba');

INSERT INTO Chair VALUES
(1, 'Logistics'),
(2, 'Sponsorship'),
(3, 'Workshops'),
(4, 'Security'),
(5, 'Cleaning'),
(6, 'Hospitality')
;

INSERT INTO Member_of VALUES
(1, 'Logistics'),
(2, 'Sponsorship'),
(3, 'Workshops'),
(4, 'Security'),
(5, 'Cleaning'),
(6, 'Hospitality'),
(7, 'Cleaning')
;

INSERT INTO Attendee VALUES
('Connor McDavid', 50, 100, 'Connor', 'McDavid'),
('Leon Draisital', 50, 101, 'Leon', 'Draisital'),
('Jason Robertson', 50, 102, 'Jason', 'Robertson'),
('Nick Robertson', 50, 103, 'Nick', 'Robertson'),
('Zack Smith', 50, 104, 'Zack', 'Smith'),
('John Jones', 50, 105, 'John', 'Jones'),
('Fin Hill', 50, 106, 'Fin', 'Hill'),
('Mitch Marner', 100, 200, 'Mitch', 'Marner'),
('John Tavares', 100, 201, 'John', 'Tavares'),
('Mitchel Marn', 100, 202, 'Mitchel', 'Marn'),
('Tyler Blevins', 100, 203, 'Tyler', 'Blevins'),
('Mistika Roger', 100, 204, 'Mistika', 'Roger'),
('Sheldon Keefe', 100, 205, 'Sheldon', 'Keefe'),
('Ryan Miller', 0, 300, 'Ryan', 'Miller'),
('Catherine Parke', 0, 301, 'Catherine', 'Parke'),
('John Carlson', 0, 302, 'John', 'Carlson'),
('Tom Wilson', 0, 303, 'Tom', 'Wilson'),
('Jack Novak', 0, 304, 'Jack', 'Novak'),
('Zack Cook', 0, 305, 'Zack', 'Cook'),
('Dave Cook', 0, 306, 'Dave', 'Cook'),
('Andrew Diab', 0, 307, 'Andrew', 'Diab'),
('Tim Stuzle', 0, 500, 'Tim', 'Stuzle'),
('Jake Sanderson', 0, 501, 'Jake', 'Sanderson'),
('Mick Amadio', 0, 502, 'Mike', 'Amadio'),
('Josh Norris', 0, 503, 'Josh', 'Norris'),
('Drake Batherson', 0, 504, 'Drake', 'Batherson'),
('Anton Forsberg', 0, 505, 'Anton', 'Forsberg');

INSERT INTO Student VALUES
(100, 'Connor McDavid'),
(101, 'Leon Draisital'),
(102, 'Jason Robertson'),
(103, 'Nick Robertson'),
(104, 'Zack Smith'),
(105, 'John Jones'),
(106, 'Fin Hill');

INSERT INTO Professional VALUES
(200, 'Mitch Marner'),
(201, 'John Tavares'),
(202, 'Mitchel Marn'),
(203, 'Tyler Blevins'),
(204, 'Mistika Roger'),
(205, 'Sheldon Keefe');

INSERT INTO Sponsor VALUES
(300, 'Ryan Miller'),
(301, 'Catherine Parke'),
(302, 'John Carlson'),
(303, 'Tom Wilson'),
(304, 'Jack Novak'),
(305, 'Zack Cook'),
(306, 'Dave Cook'),
(307, 'Andrew Diab');

INSERT INTO Speaker VALUES
(500, 'Tim Stuzle'),
(501, 'Jake Sanderson'),
(502, 'Mike Amadio'),
(503, 'Josh Norris'),
(504, 'Drake Batherson'),
(505, 'Anton Forsberg');

INSERT INTO Room VALUES
(201, 2),
(202, 1),
(203, 1),
(301, 2),
(302, 2),
(303, 2);

INSERT INTO Assigned VALUES
(100, 201),
(101, 202),
(102, 203),
(103, 301),
(104, 302),
(105, 303),
(106, 201);

INSERT INTO Company VALUES
('Nivdia', 5, 'Platinum'),
('CIBC', 4, 'Gold'),
('Canada Gov', 5, 'Platinum'),
('Bell', 3, 'Silver'),
('Cissco', 3, 'Silver'),
('KPMG', 5, 'Platinum'),
('Queens University', 4, 'Silver');

INSERT INTO Represents VALUES
(300, 'Nivdia'),
(302, 'CIBC'),
(301, 'Canada Gov'),
(303, 'Bell'),
(304, 'Cissco'),
(305, 'KPMG'),
(306, 'Queens University'),
(307, 'Canada Gov');

INSERT INTO JobAb VALUES
(1010, 'Nivdia', 'Software Engineer', 85000.00, 'Toronto, ON'),
(1910, 'CIBC', 'Data Analyst', 70000.00, 'Ottawa, ON'),
(2130, 'Canada Gov', 'Web development', 70000.00, 'Vancouver, BC'),
(5645, 'Bell', 'Verilog Tech', 170000.00, 'Montreal, QC'),
(9600, 'Cissco', 'Full Stack Engineer', 100000.00, 'Toronto, ON'),
(8478, 'KPMG', 'Data Analyst', 78000.00, 'New York, NY'),
(4000, 'Queens University', 'Teaching Assistant', 55000.00, 'Kingston, ON');

INSERT INTO Session VALUES
(101, 500, 'Room 214', '2025-06-15', '09:00:00', '10:30:00'),
(202, 501, 'Room 314', '2025-06-16', '11:00:00', '12:30:00'),
(303, 502, 'Room 400', '2025-06-17', '010:00:00', '11:30:00'),
(404, 503, 'Room 410', '2025-06-17', '11:00:00', '12:30:00'),
(505, 504, 'Room 727', '2025-06-18', '09:00:00', '10:30:00'),
(606, 505, 'Room 900', '2025-06-18', '12:00:00', '13:30:00');
