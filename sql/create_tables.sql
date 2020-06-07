create table if not exists user (
    id bigint AUTO_INCREMENT  primary key,
    username VARCHAR(30) NOT NULL,
    passhash VARCHAR(30) NOT NULL,
    full_name VARCHAR(50) NOT NULL,
    ip varchar(45) NOT NULL DEFAULT '127.0.0.1',
    email varchar(100) NOT NULL,
    lastseen datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    access_level enum('god', 'admin', 'user') NOT NULL DEFAULT 'user',
    email_validated boolean 
);


create table if not exists chat (
    sender bigint,
    receiver bigint,
    message_timestamp datetime,
    message_string blob,
    primary key(sender, receiver),
    foreign key (sender) references user(id),
    foreign key (receiver) references user(id)
);

create table if not exists valid_queue (
    id bigint AUTO_INCREMENT primary key,
    username varchar(30) NOT NULL,
    link varchar(100) NOT NULL,
    sent_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table if not exists online_status (
    id bigint AUTO_INCREMENT primary key,
    username varchar(30) NOT NULL
);