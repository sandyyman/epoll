CREATE TABLE `candidate` (
  `cid` int(11) NOT NULL, `cname` varchar(30) NOT NULL, `cphoto` varchar(200) NOT NULL, `party` varchar(30) NOT NULL, `const` varchar(30) NOT NULL, `body` varchar(500) NOT NULL, `count_vote` int(11) NOT NULL);

CREATE TABLE `admin` (
  `aid` int(11) NOT NULL,
  `aname` varchar(30) NOT NULL,
  `apwd` varchar(8) NOT NULL
);


CREATE TABLE `govt_db` ( `sr_no` int(11) NOT NULL, `voter_id` int(11) NOT NULL, `email` varchar(40) NOT NULL);

CREATE TABLE `otp_expiry` (`id` int(11) NOT NULL, `otp` varchar(10) NOT NULL, `is_expired` int(11) NOT NULL, `create_at` datetime NOT NULL);

CREATE TABLE `voter` (`name` varchar(30) NOT NULL,
`email` varchar(30) NOT NULL, 
`age` int(11) NOT NULL,
`uname` varchar(15) NOT NULL,
`voter_id` int(11) NOT NULL,
`password` varchar(50) NOT NULL,
`flag` tinyint(1) NOT NULL, 
`hash` varchar(32) NOT NULL, 
`active` int(1) NOT NULL, 
`voter_photo` varchar(255) NOT NULL); 

CREATE TABLE votes(vote_id int(11) not null AUTO_INCREMENT, voter_id int(11) NOT NULL, cid int(11) NOT NULL, primary key(vote_id),unique(voter_id,cid), foreign key(voter_id) references voter(voter_id) on delete cascade, foreign key(cid) references candidates references candidate(cid) on delete cascade);
