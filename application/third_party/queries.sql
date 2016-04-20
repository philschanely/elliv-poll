CREATE OR REPLACE VIEW `Ballot_Item_Details` AS
SELECT Ballot_Item.*, 
       User.first_name, User.last_name, 
       Option.label AS `option_selected`, 
       q_id, Question.label AS `question_label`, Question.order, 
       Poll.poll_id, Poll.title AS `poll_title` 
FROM `Ballot_Item` 
JOIN `User` ON `Ballot_Item`.`user` = `User`.`user_id`
JOIN `Option` ON `Ballot_Item`.`option` = `Option`.`o_id` 
JOIN `Question` ON `Option`.`question` = `Question`.`q_id` 
JOIN `Poll` ON `Question`.`poll` = `Poll`.`poll_id`;

CREATE OR REPLACE VIEW Completed_Poll_Details AS
SELECT * FROM Completed_Poll 
JOIN User ON Completed_Poll.user = User.user_id
JOIN Poll ON Completed_Poll.poll = Poll.poll_id;

CREATE OR REPLACE VIEW `Option_Details` AS
SELECT `Option`.*, 
       q_id, Question.label AS `question_label`, Question.order as question_order, 
       Poll.poll_id, Poll.title AS `poll_title` 
FROM `Option` 
JOIN `Question` ON `Option`.`question` = `Question`.`q_id` 
JOIN `Poll` ON `Question`.`poll` = `Poll`.`poll_id`;