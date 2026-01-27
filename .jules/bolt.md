# Bolt's Journal

## 2025-05-21 - N+1 Insert Pattern
**Learning:** The raw PDO implementation in controllers tends to loop over input arrays and execute individual `INSERT` statements, leading to N+1 performance issues during write operations.
**Action:** Use batch inserts by constructing dynamic SQL with multiple `VALUES` clauses when handling array inputs.

## 2025-05-21 - Over-fetching in Controllers
**Learning:** Controllers often use `SELECT *` for list views, fetching unused columns like full addresses and banking details which are unnecessary for the index table.
**Action:** Replace `SELECT *` with specific column selection in list queries to reduce memory footprint.
