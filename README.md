# First, You will need to take this page to htdocs uipaste hapo ndio uwashe xampp after that utacreate the database and ucreate the tablses so after creating all those, uitest sasa by typing in your browser "localhost/name_of_the_folder/landing.html" ndio run hivo ndio all pages need to look like


# Create the database 
 CREATE DATABASE ecopower;
# After creating insert this information inside the sql kwa php.myadmin

# users database sql query

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    status ENUM('active', 'suspended', 'banned') NOT NULL DEFAULT 'active',
    suspension_end DATE DEFAULT NULL
);

# Users feedback sql query

CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    feedback_type ENUM('suggestion', 'complaint', 'praise', 'other') NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


# users testimonials sql query

CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text TEXT NOT NULL,
    author VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

# insert into testimonials
INSERT INTO testimonials (text, author) VALUES 
('Eco Power Solutions made it easy for me to contribute to clean energy projects. I love seeing the impact of my donations!', 'Jacob Juma'),
('The transparency in how funds are used is impressive. It\'s great to see exactly where my money is going.', 'Alex Akok'),
('I\'ve been donating monthly for a year now, and I\'m amazed at the progress we\'ve made together!', 'Abu Hanifa');


# users frequent asked questions 

CREATE TABLE faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

# insert into faqs

INSERT INTO faqs (question, answer) VALUES 
('How can I track my donations?', 'You can track your donations by logging into your account and visiting the "Track Donations" page.'),
('Are my donations tax-deductible?', 'Yes, all donations to Eco Power Solutions are tax-deductible. We\'ll provide you with a receipt for your records.'),
('Can I set up recurring donations?', 'Absolutely! When making a donation, you can choose to make it a monthly or annual recurring donation.');





# Make sure to deleting everything here after you are done 