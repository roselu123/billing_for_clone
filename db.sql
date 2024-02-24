CREATE TABLE billing (
    id INT(10) AUTO_INCREMENT PRIMARY KEY,
    Date DATE NOT NULL,
    Patient_Name VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    Name_Gaurantor VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    Address VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    Contact VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    Amount FLOAT(10,2) NOT NULL,
    Due_Date DATE NOT NULL,
    Collateral_Given VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    Promissory_Note TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    Collateral_Image TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    OR_CR TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    Titles TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    Statement_of_Account TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    no_of_notifications INT(100) NOT NULL,
    notification_read TINYINT(4) NOT NULL
);

CREATE TABLE notif (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    Patient_Name VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    Amount FLOAT NOT NULL,
    Due_Date DATE NOT NULL,
    no_of_notifications INT(100) NOT NULL,
    notification_read INT(100) NOT NULL,
    paid INT(100) NOT NULL
);
