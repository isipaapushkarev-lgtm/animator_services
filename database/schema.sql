CREATE DATABASE IF NOT EXISTS animator_services
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE animator_services;

SET FOREIGN_KEY_CHECKS = 0;
DROP VIEW IF EXISTS v_program_catalog, v_animator_programs, v_upcoming_orders,
  v_customer_order_history, v_program_ratings;
DROP TABLE IF EXISTS photos, reviews, orders, program_animators, animators, programs, users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL DEFAULT '$2y$10$dtZyWMV1vywl2hiWlmCMFuhU9smuFkDgMD6vwksAoaDRRnR41saja',
  role ENUM('client','admin') NOT NULL DEFAULT 'client',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE programs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL UNIQUE,
  summary VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  age_group VARCHAR(40) NOT NULL,
  duration_minutes SMALLINT UNSIGNED NOT NULL,
  heroes_count TINYINT UNSIGNED NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  accent ENUM('violet','pink','cyan','yellow') NOT NULL DEFAULT 'violet',
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE animators (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  character_name VARCHAR(80) NOT NULL,
  bio TEXT NOT NULL,
  experience_years TINYINT UNSIGNED NOT NULL,
  rating DECIMAL(2,1) NOT NULL DEFAULT 5.0,
  accent ENUM('violet','pink','cyan','yellow') NOT NULL DEFAULT 'yellow',
  photo_path VARCHAR(255) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE program_animators (
  program_id INT UNSIGNED NOT NULL,
  animator_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (program_id, animator_id),
  CONSTRAINT fk_pa_program FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE,
  CONSTRAINT fk_pa_animator FOREIGN KEY (animator_id) REFERENCES animators(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  program_id INT UNSIGNED NOT NULL,
  animator_id INT UNSIGNED NULL,
  customer_name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(190) NOT NULL,
  event_date DATE NOT NULL,
  event_time TIME NOT NULL,
  address VARCHAR(255) NOT NULL,
  children_age VARCHAR(40) NOT NULL,
  children_count TINYINT UNSIGNED NOT NULL,
  comment TEXT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  status ENUM('new','confirmed','completed','cancelled') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  CONSTRAINT fk_orders_program FOREIGN KEY (program_id) REFERENCES programs(id),
  CONSTRAINT fk_orders_animator FOREIGN KEY (animator_id) REFERENCES animators(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE reviews (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  order_id INT UNSIGNED NOT NULL UNIQUE,
  rating TINYINT UNSIGNED NOT NULL,
  title VARCHAR(120) NOT NULL,
  body TEXT NOT NULL,
  event_date DATE NOT NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_reviews_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT chk_review_rating CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB;

CREATE TABLE photos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  order_id INT UNSIGNED NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  mime_type VARCHAR(50) NOT NULL,
  uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_photos_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_photos_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (name,phone,email,password_hash,role) VALUES
('Администратор','+7 (900) 000-00-00','admin@yarko-night.ru','$2y$10$dtZyWMV1vywl2hiWlmCMFuhU9smuFkDgMD6vwksAoaDRRnR41saja','admin'),
('Анна Морозова','+7 (912) 111-22-33','anna@example.ru','$2y$10$dtZyWMV1vywl2hiWlmCMFuhU9smuFkDgMD6vwksAoaDRRnR41saja','client'),
('Михаил Соколов','+7 (916) 222-33-44','mikhail@example.ru','$2y$10$dtZyWMV1vywl2hiWlmCMFuhU9smuFkDgMD6vwksAoaDRRnR41saja','client');

INSERT INTO programs (name,slug,summary,description,age_group,duration_minutes,heroes_count,price,accent) VALUES
('Ночная команда','night-team','Сценарий с маскотами, музыкой и световым финалом','Знакомство с героями, игры, музыкальный блок и общее фото.','6–12 лет',60,1,6900,'pink'),
('Аркадный квест','arcade-quest','Командные испытания и безопасная ночная атмосфера','Квест с картой заданий, двумя героями и финальным призом.','7–12 лет',90,2,7900,'cyan'),
('Финальное шоу','final-show','Большая программа с тремя героями и фотографом','Расширенное шоу со световым реквизитом, музыкой и фотосессией.','8–14 лет',120,3,12900,'yellow');

INSERT INTO animators (name,character_name,bio,experience_years,rating,accent) VALUES
('Артём Волков','Фред','Ведущий шоу и командных игровых программ.',5,4.9,'yellow'),
('Мария Белова','Бонни','Музыкальный герой и мастер танцевальных игр.',4,4.9,'pink'),
('Илья Орлов','Чики','Мастер квестов, загадок и игровых испытаний.',5,4.9,'yellow'),
('София Миронова','Фокси','Капитан квеста и ведущая командных финалов.',6,4.9,'cyan');

INSERT INTO program_animators VALUES
(1,1),(1,2),(2,1),(2,3),(2,4),(3,1),(3,2),(3,3),(3,4);

INSERT INTO orders (user_id,program_id,animator_id,customer_name,phone,email,event_date,event_time,address,children_age,children_count,comment,total_price,status) VALUES
(2,2,1,'Анна Морозова','+7 (912) 111-22-33','anna@example.ru','2026-09-15','16:00:00','Москва, ул. Праздничная, 12','7–9 лет',10,'Имениннику исполняется 8 лет',7900,'confirmed'),
(3,1,2,'Михаил Соколов','+7 (916) 222-33-44','mikhail@example.ru','2026-08-10','14:30:00','Москва, ул. Световая, 8','6–8 лет',8,'Нужен спокойный сценарий',6900,'completed'),
(2,3,4,'Анна Морозова','+7 (912) 111-22-33','anna@example.ru','2026-08-15','17:00:00','Москва, пр-т Игровой, 4','9–11 лет',14,'Добавить фотосессию',12900,'completed');

INSERT INTO reviews (user_id,order_id,rating,title,body,event_date) VALUES
(3,2,5,'Удобно и честно','Выбрали пакет онлайн, стоимость не изменилась, всё прошло спокойно.','2026-08-10'),
(2,3,5,'Дети были в восторге!','Артисты приехали вовремя, программа была живой, но не пугающей.','2026-08-15');

INSERT INTO photos (user_id,order_id,file_name,original_name,mime_type) VALUES
(2,3,'demo-party-1.svg','Финальное фото.svg','image/svg+xml'),
(2,3,'demo-party-2.svg','Командное фото.svg','image/svg+xml'),
(3,2,'demo-party-3.svg','Аркадная сцена.svg','image/svg+xml');

CREATE VIEW v_program_catalog AS
SELECT p.id, p.name AS program_name, p.age_group, p.duration_minutes,
       p.heroes_count, p.price, COUNT(pa.animator_id) AS available_animators
FROM programs p LEFT JOIN program_animators pa ON pa.program_id=p.id
GROUP BY p.id, p.name, p.age_group, p.duration_minutes, p.heroes_count, p.price;

CREATE VIEW v_animator_programs AS
SELECT a.id, a.name AS animator_name, a.character_name, a.experience_years,
       a.rating, GROUP_CONCAT(p.name ORDER BY p.name SEPARATOR ', ') AS programs
FROM animators a LEFT JOIN program_animators pa ON pa.animator_id=a.id
LEFT JOIN programs p ON p.id=pa.program_id
GROUP BY a.id, a.name, a.character_name, a.experience_years, a.rating;

CREATE VIEW v_upcoming_orders AS
SELECT o.id AS order_id, o.event_date, o.event_time, o.customer_name,
       p.name AS program_name, a.character_name, o.total_price, o.status
FROM orders o JOIN programs p ON p.id=o.program_id
LEFT JOIN animators a ON a.id=o.animator_id
WHERE o.event_date >= CURRENT_DATE AND o.status IN ('new','confirmed');

CREATE VIEW v_customer_order_history AS
SELECT u.id AS user_id, u.name AS customer_name, u.email, COUNT(o.id) AS orders_count,
       COALESCE(SUM(o.total_price),0) AS total_spent,
       MAX(o.event_date) AS last_event_date
FROM users u LEFT JOIN orders o ON o.user_id=u.id
WHERE u.role='client'
GROUP BY u.id, u.name, u.email;

CREATE VIEW v_program_ratings AS
SELECT p.id, p.name AS program_name, COUNT(r.id) AS reviews_count,
       ROUND(COALESCE(AVG(r.rating),0),1) AS average_rating
FROM programs p LEFT JOIN orders o ON o.program_id=p.id
LEFT JOIN reviews r ON r.order_id=o.id AND r.is_published=1
GROUP BY p.id, p.name;
