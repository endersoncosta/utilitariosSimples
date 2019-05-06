
SELECT e.id, e.post_title, e.post_status, e.post_modified, e.post_type,
j.post_title as fileTitle, j.guid as fileLink,
u.meta_value as unidade,
t.meta_value as tipo 
FROM wp_posts e
INNER JOIN wp_posts j ON j.post_parent = e.ID
INNER JOIN wp_postmeta u ON u.post_id = e.ID
INNER JOIN wp_postmeta t ON t.post_id = e.ID
WHERE e.post_type = "documento" 
AND j.post_type = "attachment" 
AND e.post_status = "publish"
AND u.meta_key = "unidade"
AND t.meta_key = "tipo";