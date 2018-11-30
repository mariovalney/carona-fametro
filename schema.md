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

## Creating table routes

```
CREATE TABLE routes (
    ID BIGINT NOT NULL AUTO_INCREMENT,
    userId BIGINT NOT NULL,
    startLat varchar(20) NOT NULL,
    startLng varchar(20) NOT NULL,
    returnLat varchar(20) NOT NULL,
    returnLng varchar(20) NOT NULL,
    startTime varchar(10) NOT NULL,
    returnTime varchar(10) NOT NULL,
    startPlace varchar(100) NOT NULL,
    returnPlace varchar(100) NOT NULL,
    campusName varchar(100) NOT NULL,
    isDriver integer(1) NOT NULL,
    dow integer(1) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT routes_PK PRIMARY KEY (ID),
    CONSTRAINT routes_users_FK FOREIGN KEY (userId) REFERENCES users(ID)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci
COMMENT='List of routes';
```

## Creating table invites

```
CREATE TABLE invites (
    ID BIGINT NOT NULL AUTO_INCREMENT,
    routeId BIGINT NOT NULL,
    rideId BIGINT NOT NULL,
    route LONGTEXT NOT NULL,
    ride LONGTEXT NOT NULL,
    type varchar(10) NOT NULL,
    sendedMails integer(5) NOT NULL,
    isAccepted integer(1) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT invites_PK PRIMARY KEY (ID),
    CONSTRAINT invites_routes_FK FOREIGN KEY (routeId) REFERENCES routes(ID),
    CONSTRAINT invites_rides_FK FOREIGN KEY (rideId) REFERENCES routes(ID)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci
COMMENT='Invites';
```

## Creating table cache

```
CREATE TABLE cache (
    ID BIGINT NOT NULL AUTO_INCREMENT,
    cache_key varchar(100) NOT NULL,
    cache_value LONGTEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT cache_PK PRIMARY KEY (ID)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci
COMMENT='Cache';
```