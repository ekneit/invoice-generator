# Bolt's Journal

## 2025-05-21 - N+1 Insert Pattern
**Learning:** The raw PDO implementation in controllers tends to loop over input arrays and execute individual `INSERT` statements, leading to N+1 performance issues during write operations.
**Action:** Use batch inserts by constructing dynamic SQL with multiple `VALUES` clauses when handling array inputs.

## 2025-05-21 - Select Star Anti-Pattern
**Learning:** `SELECT *` is used frequently in controllers, fetching unnecessary columns like detailed address fields and bank info for simple list views.
**Action:** Replace `SELECT *` with specific column selection (e.g., `SELECT id, name, total...`) to reduce memory usage and database load, especially for list views. Verify used columns in the view file first.
