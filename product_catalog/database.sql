CREATE DATABASE IF NOT EXISTS product_catalog CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE product_catalog;

DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255) DEFAULT 'https://via.placeholder.com/200'
);

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255) DEFAULT 'https://via.placeholder.com/200',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============ ADMIN USERS (NEW) ============
USE product_catalog;

CREATE TABLE IF NOT EXISTS admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============ CATEGORIES (with images) ============
INSERT INTO categories (category_name, description, image_url) VALUES
('Dairy & Breakfast', 'Milk, bread, eggs, butter', 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=300'),
('Snacks & Munchies', 'Chips, biscuits, namkeen', 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=300'),
('Cold Drinks & Juices', 'Soft drinks, juices, water', 'https://images.unsplash.com/photo-1554866585-cd94860890b7?w=300'),
('Fruits & Vegetables', 'Fresh produce', 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=300'),
('Bakery & Biscuits', 'Cakes, cookies, rusks', 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300'),
('Instant & Frozen Food', 'Noodles, fries, ready meals', 'https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=300'),
('Tea, Coffee & Health Drinks', 'Tea, coffee, malt drinks', 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=300'),
('Chocolates & Sweets', 'Chocolates, candies, desserts', 'https://images.unsplash.com/photo-1511381939415-e44015466834?w=300'),
('Personal Care', 'Soaps, toothpaste, shampoo', 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=300'),
('Home & Cleaning', 'Detergents, cleaners', 'https://images.unsplash.com/photo-1563453392212-326f5e854473?w=300');
-- ============ PRODUCTS ============
INSERT INTO products (category_id, product_name, description, price, image_url) VALUES
(1,'Amul Toned Milk 1L','Fresh toned milk, rich in calcium.',68.00,'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=300'),
(1,'Britannia Brown Bread','Soft whole wheat bread.',45.00,'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300'),
(1,'Farm Fresh Eggs (6 pcs)','Protein rich farm eggs.',55.00,'https://images.unsplash.com/photo-1518569656558-1f25e69d93d7?w=300'),
(1,'Amul Butter 500g','Creamy salted butter.',275.00,'https://images.unsplash.com/photo-1589985270826-4b7bb135bc9d?w=300'),
(2,'Lays Classic Salted','Crispy potato chips.',20.00,'https://m.media-amazon.com/images/I/61FXwBMClcL._SL1500_.jpg?w=300'),
(2,'Oreo Biscuits','Chocolate cream biscuits.',30.00,'	https://m.media-amazon.com/images/I/61mD+sZv6fL._SX679_.jpg?w=300'),
(2,'Doritos','The bold flavor of salsa verde so every crunch is tangy.',55.00,'https://images.unsplash.com/photo-1600952841320-db92ec4047ca?w=300'),
(2,'Chips Masala Munch','Crunchy masala snacks.',20.00,'https://images.unsplash.com/photo-1599490659213-e2b9527bd087?w=300'),
(3,'Coca-Cola 750ml','Chilled soft drink.',40.00,'https://images.unsplash.com/photo-1554866585-cd94860890b7?w=300'),
(3,'Tropicana Orange Juice 1L','100% pure orange juice.',110.00,'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=300'),
(3,'Sprite 750ml','Lemon lime refreshment.',40.00,'https://images.unsplash.com/photo-1625772299848-391b6a87d7b3?w=300'),
(3,'Bisleri Water 1L','Packaged drinking water.',20.00,'https://images.unsplash.com/photo-1523362628745-0c100150b504?w=300'),
(4,'Banana (6 pcs)','Fresh ripe bananas.',50.00,'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=300'),
(4,'Tomato (500g)','Farm fresh tomatoes.',25.00,'https://images.unsplash.com/photo-1546094096-0df4bcaaa337?w=300'),
(4,'Potato (1 kg)','Fresh onions.',35.00,'https://images.unsplash.com/photo-1518977676601-b53f82aba655?w=300'),
(4,'Apple (4 pcs)','Crisp and sweet apples.',120.00,'https://images.unsplash.com/photo-1568702846914-96b305d2aaeb?w=300'),
(5,'Chocolate Cookies 200g','Butter cookies.',40.00,'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=300'),
(5,'Chocolate Chip Cookies','Classic glucose biscuits.',10.00,'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=300'),
(5,'Chocolate Truffle Pastry','Rich chocolate pastry.',90.00,'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=300'),
(6,'Maggi Noodles (4 pack)','2-minute masala noodles.',60.00,'https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=300'),
(6,'McCain French Fries 420g','Crispy frozen fries.',130.00,'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=300'),
(6,'Classic Soup','Instant tomato soup mix.',55.00,'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=300'),
(7,'Tata Tea 250g','Premium Assam tea.',145.00,'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=300'),
(7,'Nescafe Classic 50g','Instant coffee powder.',175.00,'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=300'),
(7,'TGL Co.Tea 500g','Chocolate health drink.',250.00,'https://images.unsplash.com/photo-1517487881594-2787fef5ebf7?w=300'),
(8,'Dark Chocolate','Smooth milk chocolate.',75.00,'https://images.unsplash.com/photo-1511381939415-e44015466834?w=300'),
(8,'White Chocolate','Hazelnut chocolate balls.',199.00,'https://images.unsplash.com/photo-1548907040-4baa42d10919?w=300'),
(9,'Dove Soap','Moisturising beauty soap.',165.00,'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=300'),
(9,'Colgate Strong Teeth','Cavity protection toothpaste.',95.00,'https://pxmshare.colgatepalmolive.com/PNG_2000/QlnnsEpwJqPnBl4bw-kQu.png?w=300'),
(10,'Surf Excel','Tough stain removal powder.',210.00,'https://m.media-amazon.com/images/I/61NfDZ97F3S._SX679_.jpg?w=300'),
(10,'Harpic Toilet Cleaner','Kills 99.9% germs.',95.00,'https://banner2.cleanpng.com/20180623/pfu/aazqs2lrr.webp?w=300');