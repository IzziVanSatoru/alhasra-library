CREATE DATABASE alhasra_library;

USE alhasra_library;

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    author VARCHAR(255),
    image VARCHAR(255)
);

CREATE TABLE book_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT,
    FOREIGN KEY (book_id) REFERENCES books(id)
);

INSERT INTO books (title, author, image) VALUES
('Book pelangi is gay           ', 'jauza',     'book1.jpg'),
('Book ayam',                    'Author jay    ', 'book2.jpg'),
('Book keramat',                        'lucifero',      'book3.jpg');
