TYPE=TRIGGERS
triggers='CREATE DEFINER=`root`@`localhost` TRIGGER after_donor_insert\nAFTER INSERT ON donors\nFOR EACH ROW\nBEGIN\n    INSERT INTO donation_reports (donor_id, donation_date)\n    VALUES (NEW.id, CURDATE());\nEND'
sql_modes=1098907648
definers='root@localhost'
client_cs_names='utf8mb4'
connection_cl_names='utf8mb4_unicode_ci'
db_cl_names='utf8mb4_general_ci'
created=1734548050133837
