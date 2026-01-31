# Bolt's Journal

## 2025-05-21 - N+1 Insert Pattern
**Learning:** The raw PDO implementation in controllers tends to loop over input arrays and execute individual `INSERT` statements, leading to N+1 performance issues during write operations.
**Action:** Use batch inserts by constructing dynamic SQL with multiple `VALUES` clauses when handling array inputs.

## 2025-01-31 - Over-fetching in List Views
**Learning:** Controller methods often perform `SELECT *` for list views that only display a few columns, wasting resources (memory/network) on unused data.
**Action:** Explicitly select only the necessary columns in SQL queries for list views, verifying against the View template to ensure no required fields are missing.
