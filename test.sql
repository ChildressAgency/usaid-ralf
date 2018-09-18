SELECT query
from wp_swp_log
group by query
order by count(query)

SELECT query
from wp_swp_log
group by query
order by count(query) DESC, hits DESC
LIMIT 3