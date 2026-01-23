# Bolt's Journal

## 2025-05-21 - N+1 Insert Pattern
**Learning:** The raw PDO implementation in controllers tends to loop over input arrays and execute individual `INSERT` statements, leading to N+1 performance issues during write operations.
**Action:** Use batch inserts by constructing dynamic SQL with multiple `VALUES` clauses when handling array inputs.

## 2025-05-21 - Over-fetching Anti-pattern
**Learning:** The codebase frequently uses `SELECT *`, fetching unnecessary columns (including large text fields or sensitive data) for list views that only display a few fields.
**Action:** Explicitly select only the required columns in SQL queries to reduce memory usage and improve database performance.
