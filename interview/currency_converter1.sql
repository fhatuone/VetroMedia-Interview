DROP DATABASE IF EXISTS Cur_Converter;

create database Cur_Converter;

use Cur_Converter;

create	table countries(
	cntry_id	int not null auto_increment,
	cntry_name	varchar(50)	not	null,
	cntry_code	varchar(50)	not	null,
	primary key	(cntry_id));

INSERT INTO `countries`( `cntry_name`, `cntry_code`) VALUES ('United States Dollar','USD'),('South African Rand','ZAR'),('British Pound Sterling','GBP'),('Euro','EUR'),('Kenyan Shilling','KES');
	
Create table users(
	usr_id 			int not null auto_increment,
	usr_fname		varchar(50)  not null,
	usr_lname		varchar(50)  not null,
	usr_email		varchar(100) not null,
	usr_password	varchar(100) not null,
	usr_status		int	not	null DEFAULT 0,
	usr_actv_link 	varchar(255) not null,
	frgt_psswd_rndm_str varchar(255) not null,
	date_created	timestamp not null DEFAULT CURRENT_TIMESTAMP,
	date_updated	datetime	not null,
	cntry_id		int not null,
	foreign key(cntry_id) references countries(cntry_id) ON DELETE cascade,
	primary key	(usr_id),
	unique(usr_email));
	
	

create	table currencies(
	curr_id	int not null auto_increment,
	curr_amount	varchar(50)	not	null,
	curr_time	datetime	not	null,
	cntry_id	int not	null,
	primary key	(curr_id),
    foreign key(cntry_id) references countries(cntry_id) ON DELETE cascade); 
		
create table	account(
	acc_id		int	not	null	auto_increment,
	acc_type	varchar(50)		not	null,
	acc_balance	varchar(50)		not	null,
	acc_trans	varchar(50)		not	null,
	amnt_deposit	varchar(50)	not	null,
	amnt_withdraw	varchar(50)	not	null,
	date_created	datetime	not null,
	usr_id		int not null,
	primary	key	(acc_id),
	foreign key(usr_id) references users(usr_id) ON DELETE cascade
	);
	
	
create	table	orders(
	order_id		int	not	null	auto_increment,
	fr_curr_pchsd	varchar(50)	not	null,
	exch_rate		varchar(50)	not	null,
	surch_perc		varchar(50)	not	null,
	fr_amnt_pchsd	varchar(50)	not	null,	
	usd_amnt_paid		varchar(50)	not	null,
	surch_amnt			varchar(50)	not	null,
	order_time		datetime	not	null,
	acc_id			int	not null,
	primary	key	(order_id),
	foreign key(acc_id) references account(acc_id)ON DELETE cascade
	);
	
