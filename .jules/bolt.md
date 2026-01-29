# Bolt's Journal

## 2025-05-21 - N+1 Insert Pattern
**Learning:** The raw PDO implementation in controllers tends to loop over input arrays and execute individual `INSERT` statements, leading to N+1 performance issues during write operations.
**Action:** Use batch inserts by constructing dynamic SQL with multiple `VALUES` clauses when handling array inputs.

## 2025-05-21 - Over-fetching in Controllers
**Learning:** Controllers often use `SELECT *` which fetches unnecessary columns (like large text fields or sensitive data) that are not used in the views, increasing memory usage and data transfer.
**Action:** Explicitly select only the columns required by the view (e.g., `SELECT id, name, total...`).
