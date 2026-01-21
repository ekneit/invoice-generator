# Bolt's Journal

## 2025-05-21 - N+1 Insert Pattern
**Learning:** The raw PDO implementation in controllers tends to loop over input arrays and execute individual `INSERT` statements, leading to N+1 performance issues during write operations.
**Action:** Use batch inserts by constructing dynamic SQL with multiple `VALUES` clauses when handling array inputs.

## 2025-05-21 - Column Over-fetching
**Learning:** Controllers using `SELECT *` often fetch significantly more data than the view requires, increasing memory usage and database I/O.
**Action:** Replace `SELECT *` with explicit column selection (e.g., `SELECT id, name`) after verifying exactly which fields are used in the corresponding view.
