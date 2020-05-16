1. When db says  #1071 - Specified key was too long; max key length is 767 bytes -
add in table creation: 'CREATE TABLE' sequence in last line 'ROW_FORMAT=DYNAMIC', eg: ') ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;'
