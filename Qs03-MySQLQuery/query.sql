DROP TABLE IF EXISTS `orders`;

CREATE TABLE IF NOT EXISTS `orders` (
    `Order_ID` INT UNSIGNED NOT NULL,
    `Order_Date` TIMESTAMP,
    `Sales_Type` VARCHAR(20),
    PRIMARY KEY (`Order_ID`)
);

DROP TABLE IF EXISTS `order_products`;

CREATE TABLE IF NOT EXISTS `order_products` (
    `Order_Product_ID` INT UNSIGNED NOT NULL,
    `Order_ID` INT UNSIGNED NOT NULL,
    `Item_Name` VARCHAR(20),
    `Normal_Price` DECIMAL(10,2),
    `Promotion_Price` DECIMAL(10,2),
    PRIMARY KEY (`Order_Product_ID`)
);

INSERT INTO `orders` (Order_ID, Order_Date, Sales_Type) VALUES
(1001, "2007-05-01 12:10:10", "Normal"),
(1002, "2007-05-07 05:28:55", "Normal"),
(1003, "2007-05-19 17:17:00", "Promotion"),
(1004, "2007-05-22 22:47:16", "Promotion"),
(1005, "2007-05-27 08:15:07", "Promotion"),
(1006, "2007-06-01 06:35:59", "Normal");


INSERT INTO `order_products` ( Order_Product_ID, Order_ID, Item_Name, Normal_Price, Promotion_Price) VALUES
(2000, 1001, "Radio", 800.00 ,712.99),
(2001, 1002, "Portable Audio", 16.00 ,15.00),
(2002, 1002, "THE SIMS", 9.99 ,8.79),
(2003, 1003, "Radio", 800.00 ,712.99),
(2004, 1004, "Scanner", 124.00 ,120.00),
(2005, 1005, "Portable Audio", 16.00 ,15.00),
(2006, 1005, "Radio", 800.00 ,712.99),
(2007, 1006, "Camcorders", 359.00 ,303.00),
(2008, 1006, "Radio", 800.00 ,712.99);


SELECT 
	o.Order_ID AS Order_ID,
	COUNT(o.Order_ID) AS Number_Of_Order,
	CASE
	    WHEN o.Sales_Type = 'Normal' THEN SUM(p.Normal_Price)
	    ELSE SUM(p.Promotion_Price)
	END AS Total_Sales_Amount
FROM orders o
JOIN order_products p ON p.Order_ID = o.Order_ID
GROUP BY o.Order_ID;