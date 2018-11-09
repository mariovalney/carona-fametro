## Creating table users

```
CREATE TABLE users (
    ID BIGINT NOT NULL AUTO_INCREMENT,
    googleId varchar(100) UNIQUE NOT NULL,
    email varchar(100) NOT NULL,
    firstName varchar(100) NOT NULL,
    lastName varchar(100) NOT NULL,
    displayName varchar(100) NOT NULL,
    avatar varchar(100) NOT NULL,
    bio LONGTEXT NOT NULL,
    phone varchar(20) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT users_PK PRIMARY KEY (ID)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci
COMMENT='List of users';
```