UPDATE journal_info 
SET details = REPLACE(details, 'src="../../uploads/', 'src="/uploads/') 
WHERE details LIKE '%../../uploads/%';
