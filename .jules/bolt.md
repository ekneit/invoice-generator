# Bolt's Journal

## 2025-05-21 - N+1 Insert Pattern
**Learning:** The raw PDO implementation in controllers tends to loop over input arrays and execute individual `INSERT` statements, leading to N+1 performance issues during write operations.
**Action:** Use batch inserts by constructing dynamic SQL with multiple `VALUES` clauses when handling array inputs.

## 2025-05-21 - Select Star Anti-pattern
**Learning:** Controllers frequently use `SELECT *` which fetches all columns, including potentially large text fields or unused data, increasing memory usage and database load.
**Action:** Explicitly select only the columns required by the view or logic consuming the data.
