/*
 Navicat MySQL Data Transfer

 Source Server         : Intercionection
 Source Server Type    : MySQL
 Source Server Version : 50733
 Source Host           : localhost:3306
 Source Schema         : cafeteria

 Target Server Type    : MySQL
 Target Server Version : 50733
 File Encoding         : 65001

 Date: 12/10/2021 16:06:43
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bills
-- ----------------------------
DROP TABLE IF EXISTS `bills`;
CREATE TABLE `bills`  (
  `id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `quote` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `customer` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bills
-- ----------------------------

-- ----------------------------
-- Table structure for bills_details
-- ----------------------------
DROP TABLE IF EXISTS `bills_details`;
CREATE TABLE `bills_details`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `product_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `costs` decimal(22, 2) NOT NULL,
  `units` int(11) NOT NULL,
  `total` decimal(22, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bills_details
-- ----------------------------

-- ----------------------------
-- Table structure for prefix_id_autoincrement
-- ----------------------------
DROP TABLE IF EXISTS `prefix_id_autoincrement`;
CREATE TABLE `prefix_id_autoincrement`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `counter` int(11) NOT NULL,
  `tam` int(11) NOT NULL,
  `zero` enum('SI','NO') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'SI',
  `updated` datetime NULL DEFAULT NULL,
  `prefix` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` enum('ACTIVO','INACTIVO') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of prefix_id_autoincrement
-- ----------------------------
INSERT INTO `prefix_id_autoincrement` VALUES (1, 'products_category', 52, 6, 'SI', '2021-10-10 10:00:12', NULL, 'ACTIVO');
INSERT INTO `prefix_id_autoincrement` VALUES (2, 'products', 62, 6, 'SI', '2021-10-11 09:54:25', NULL, 'ACTIVO');
INSERT INTO `prefix_id_autoincrement` VALUES (3, 'providers', 9, 6, 'SI', '2021-10-10 22:26:48', NULL, 'ACTIVO');
INSERT INTO `prefix_id_autoincrement` VALUES (4, 'purchase_requests', 19, 6, 'SI', '2021-10-12 14:05:27', NULL, 'ACTIVO');
INSERT INTO `prefix_id_autoincrement` VALUES (5, 'purchase_orders', 1, 6, 'SI', '2021-10-12 13:15:24', NULL, 'ACTIVO');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `category` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `price` decimal(22, 2) NULL DEFAULT NULL,
  `img_portada` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `code_extern` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `unidad_para_compra` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `unidad_almacen` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `unidad_para_almacen` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `provider` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `updated_by` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `created_by` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` enum('ACTIVO','DELETE') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES ('000050', 'salchipapas act', '      ', '000043', 10.00, '6163a00af0f1b.png', '', 'DOCENA', '2', 'UND', '000008', '30vitin', '2021-10-11 08:29:22', '2021-10-10 13:10:19', '30vitin', 'ACTIVO');
INSERT INTO `products` VALUES ('000052', 'producto para borrar', '', '000050', 20.00, '', '', 'UND', '32', 'UND', 'PROVERR', '30vitin', '2021-10-10 21:28:29', '2021-10-10 21:28:21', '30vitin', 'DELETE');
INSERT INTO `products` VALUES ('000053', 'prouducto 2', ' ', '000044', 20.00, '', '', 'UND', '10', 'UND', '000008', '30vitin', '2021-10-11 14:08:46', '2021-10-11 09:44:01', '30vitin', 'ACTIVO');
INSERT INTO `products` VALUES ('000054', 'pan', '', '000045', 20.00, '', '', 'UND', '23', 'UND', '000009', NULL, NULL, '2021-10-11 09:50:34', '30vitin', 'ACTIVO');
INSERT INTO `products` VALUES ('000055', 'pan de molde', '', '000043', 220.00, '', '', 'UND', '233', 'UND', '000009', NULL, NULL, '2021-10-11 09:50:48', '30vitin', 'ACTIVO');
INSERT INTO `products` VALUES ('000056', 'jugo de naranja', '', '000045', 0.00, '', '', 'UND', '323', 'UND', '000008', NULL, NULL, '2021-10-11 09:52:26', '30vitin', 'ACTIVO');
INSERT INTO `products` VALUES ('000057', 'PH Marquis Tower', '', '000044', 20.00, '', '', 'UND', '32', 'UND', '000008', NULL, NULL, '2021-10-11 09:52:39', '30vitin', 'ACTIVO');
INSERT INTO `products` VALUES ('000058', 'producto 1', '', '000042', 20.00, '', '', 'UND', '33', 'UND', '000008', NULL, NULL, '2021-10-11 09:53:18', '30vitin', 'ACTIVO');
INSERT INTO `products` VALUES ('000059', 'producto 2', '', '000050', 30.00, '', '', 'UND', '43', 'UND', '000009', NULL, NULL, '2021-10-11 09:53:33', '30vitin', 'ACTIVO');
INSERT INTO `products` VALUES ('000060', 'producto 3', '', '', 2.00, '', '', 'UND', '3', 'UND', '000009', NULL, NULL, '2021-10-11 09:53:51', '30vitin', 'ACTIVO');
INSERT INTO `products` VALUES ('000061', 'producto 4', '', '', 30.00, '', '', 'UND', '55', 'UND', '000008', NULL, NULL, '2021-10-11 09:54:05', '30vitin', 'ACTIVO');
INSERT INTO `products` VALUES ('000062', 'producto 6', '', '000044', 40.00, '', '', 'UND', '3376', 'UND', '000008', NULL, NULL, '2021-10-11 09:54:25', '30vitin', 'ACTIVO');

-- ----------------------------
-- Table structure for products_category
-- ----------------------------
DROP TABLE IF EXISTS `products_category`;
CREATE TABLE `products_category`  (
  `id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_by` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `updated_by` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `status` enum('ACTIVO','DELETE') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of products_category
-- ----------------------------
INSERT INTO `products_category` VALUES ('000038', 'nueva sd', '30vitin', '30vitin', '2021-10-10 00:05:46', NULL, 'DELETE');
INSERT INTO `products_category` VALUES ('000039', 'otr aca', '30vitin', '30vitin', '2021-10-10 00:06:34', NULL, 'DELETE');
INSERT INTO `products_category` VALUES ('000040', 'otr cat', '30vitin', '30vitin', '2021-10-10 00:06:39', NULL, 'DELETE');
INSERT INTO `products_category` VALUES ('000041', 'nueva cat', '30vitin', '30vitin', '2021-10-10 08:30:21', NULL, 'DELETE');
INSERT INTO `products_category` VALUES ('000042', 'otr categoria', '30vitin', '30vitin', '2021-10-10 08:30:31', NULL, 'ACTIVO');
INSERT INTO `products_category` VALUES ('000043', 'Promo de navidad', '30vitin', NULL, '2021-10-10 09:34:25', NULL, 'ACTIVO');
INSERT INTO `products_category` VALUES ('000044', 'embutido', '30vitin', '30vitin', '2021-10-10 09:36:26', NULL, 'ACTIVO');
INSERT INTO `products_category` VALUES ('000045', 'Ceviche de camaron', '30vitin', NULL, '2021-10-10 09:37:03', NULL, 'ACTIVO');
INSERT INTO `products_category` VALUES ('000046', 'ewe', '30vitin', NULL, '2021-10-10 09:50:54', NULL, 'ACTIVO');
INSERT INTO `products_category` VALUES ('000047', 'actualizando cat', '30vitin', '30vitin', '2021-10-10 09:53:53', NULL, 'ACTIVO');
INSERT INTO `products_category` VALUES ('000048', 'Promo de navidad', '30vitin', NULL, '2021-10-10 09:55:39', NULL, 'ACTIVO');
INSERT INTO `products_category` VALUES ('000049', 'victor jose escobar', '30vitin', NULL, '2021-10-10 09:55:45', NULL, 'ACTIVO');
INSERT INTO `products_category` VALUES ('000050', 'salchichas', '30vitin', NULL, '2021-10-10 09:56:22', NULL, 'ACTIVO');
INSERT INTO `products_category` VALUES ('000051', 'enbutido', '30vitin', NULL, '2021-10-10 09:56:27', NULL, 'ACTIVO');
INSERT INTO `products_category` VALUES ('000052', 'victor jose escobar', '30vitin', '30vitin', '2021-10-10 10:00:12', NULL, 'DELETE');

-- ----------------------------
-- Table structure for providers
-- ----------------------------
DROP TABLE IF EXISTS `providers`;
CREATE TABLE `providers`  (
  `id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telephone1` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `telephone2` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fax` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `account` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `created_by` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `updated_by` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` enum('ACTIVO','DELETE') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of providers
-- ----------------------------
INSERT INTO `providers` VALUES ('000007', 'victor jose escobar', 'vitin3093@gmail.com', '67904509', '', '', '', 'barriada san martin veraguas,panama', '2021-10-10 22:25:44', '30vitin', '2021-10-11 21:56:18', '30vitin', 'DELETE');
INSERT INTO `providers` VALUES ('000008', 'victor jose escobar  h', 'vitin3093@gmail.com', '67904509', '', '', '', 'barriada san martin veraguas,panama', '2021-10-10 22:26:23', '30vitin', '2021-10-10 22:50:41', '30vitin', 'ACTIVO');
INSERT INTO `providers` VALUES ('000009', 'victor escobar', '', '69565519', '', '', '', 'barriada san martin veraguas,panama', '2021-10-10 22:26:48', '30vitin', NULL, NULL, 'ACTIVO');

-- ----------------------------
-- Table structure for purchase_orders
-- ----------------------------
DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders`  (
  `id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `date` date NOT NULL,
  `provider` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `purchase_request` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `status` enum('ACTIVO','CERRADO','DELETE') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'ACTIVO',
  `updated_by` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_by` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_orders
-- ----------------------------
INSERT INTO `purchase_orders` VALUES ('000016', '2021-10-30', '000009', '000009', 'comentario req 9', '2021-10-12 13:34:38', NULL, 'ACTIVO', NULL, '30vitin');
INSERT INTO `purchase_orders` VALUES ('000017', '2021-10-15', '000009', '000003', 'comentairo', '2021-10-12 13:38:15', NULL, 'ACTIVO', NULL, '30vitin');
INSERT INTO `purchase_orders` VALUES ('000018', '2021-11-01', '000009', '000009', 'comentario', '2021-10-12 13:39:39', NULL, 'ACTIVO', NULL, '30vitin');
INSERT INTO `purchase_orders` VALUES ('000019', '2021-10-15', '000009', '000003', 'comentairo', '2021-10-12 14:05:27', NULL, 'ACTIVO', NULL, '30vitin');

-- ----------------------------
-- Table structure for purchase_orders_details
-- ----------------------------
DROP TABLE IF EXISTS `purchase_orders_details`;
CREATE TABLE `purchase_orders_details`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `product_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `costs` decimal(22, 2) NOT NULL,
  `units` int(11) NOT NULL,
  `total` decimal(22, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_orders_details
-- ----------------------------
INSERT INTO `purchase_orders_details` VALUES (5, '000016', '000055', 220.00, 1, 220.00);
INSERT INTO `purchase_orders_details` VALUES (6, '000017', '000057', 20.00, 2, 40.00);
INSERT INTO `purchase_orders_details` VALUES (7, '000017', '000053', 20.00, 10, 200.00);
INSERT INTO `purchase_orders_details` VALUES (8, '000017', '000050', 10.00, 20, 200.00);
INSERT INTO `purchase_orders_details` VALUES (9, '000018', '000055', 220.00, 1, 220.00);
INSERT INTO `purchase_orders_details` VALUES (10, '000019', '000057', 20.00, 2, 40.00);
INSERT INTO `purchase_orders_details` VALUES (11, '000019', '000059', 37.00, 3, 111.00);

-- ----------------------------
-- Table structure for purchase_requests
-- ----------------------------
DROP TABLE IF EXISTS `purchase_requests`;
CREATE TABLE `purchase_requests`  (
  `id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `date` datetime NOT NULL,
  `provider` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `comment` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `created_by` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `updated_by` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` enum('ACTIVO','CERRADO','DELETE') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_requests
-- ----------------------------
INSERT INTO `purchase_requests` VALUES ('000003', '2021-10-15 00:00:00', '000009', 'comentairo', '2021-10-11 19:28:00', '2021-10-11 21:40:44', '30vitin', '30vitin', 'ACTIVO');
INSERT INTO `purchase_requests` VALUES ('000007', '2021-10-11 00:00:00', '000008', 'comentarios', '2021-10-11 19:35:46', '2021-10-11 22:03:08', '30vitin', '30vitin', 'CERRADO');
INSERT INTO `purchase_requests` VALUES ('000008', '2021-10-15 00:00:00', '000008', '', '2021-10-11 21:44:16', '2021-10-11 22:21:51', '30vitin', '30vitin', 'CERRADO');
INSERT INTO `purchase_requests` VALUES ('000009', '2021-10-30 00:00:00', '000009', '', '2021-10-11 21:45:24', '2021-10-11 22:20:51', '30vitin', '30vitin', 'ACTIVO');

-- ----------------------------
-- Table structure for purchase_requests_details
-- ----------------------------
DROP TABLE IF EXISTS `purchase_requests_details`;
CREATE TABLE `purchase_requests_details`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_request` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `product_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `costs` decimal(22, 2) NOT NULL,
  `units` int(11) NOT NULL,
  `total` decimal(22, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 39 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_requests_details
-- ----------------------------
INSERT INTO `purchase_requests_details` VALUES (31, '000003', '000057', 20.00, 2, 40.00);
INSERT INTO `purchase_requests_details` VALUES (32, '000003', '000059', 37.00, 3, 111.00);
INSERT INTO `purchase_requests_details` VALUES (34, '000009', '000055', 220.00, 1, 220.00);
INSERT INTO `purchase_requests_details` VALUES (35, '000007', '000056', 1.00, 20, 20.00);
INSERT INTO `purchase_requests_details` VALUES (36, '000007', '000055', 220.00, 10, 2200.00);
INSERT INTO `purchase_requests_details` VALUES (38, '000008', '000058', 20.00, 16, 320.00);

-- ----------------------------
-- Table structure for quotes
-- ----------------------------
DROP TABLE IF EXISTS `quotes`;
CREATE TABLE `quotes`  (
  `id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `purchase_order` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `customer` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of quotes
-- ----------------------------

-- ----------------------------
-- Table structure for quotes_details
-- ----------------------------
DROP TABLE IF EXISTS `quotes_details`;
CREATE TABLE `quotes_details`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `product_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `costs` decimal(22, 2) NOT NULL,
  `units` int(11) NOT NULL,
  `total` decimal(22, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of quotes_details
-- ----------------------------

-- ----------------------------
-- Table structure for users_access
-- ----------------------------
DROP TABLE IF EXISTS `users_access`;
CREATE TABLE `users_access`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ACTIVE',
  `password` blob NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_access_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of users_access
-- ----------------------------
INSERT INTO `users_access` VALUES (18, '30vitin', NULL, NULL, 'ACTIVE', 0xA3CE76EE0CCB576A03405356021E569C, NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
