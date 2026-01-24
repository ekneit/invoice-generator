# Bolt's Journal

## 2025-05-21 - N+1 Insert Pattern
**Learning:** The raw PDO implementation in controllers tends to loop over input arrays and execute individual `INSERT` statements, leading to N+1 performance issues during write operations.
**Action:** Use batch inserts by constructing dynamic SQL with multiple `VALUES` clauses when handling array inputs.

## 2026-01-24 - Column Over-fetching
**Learning:** List views using `SELECT *` fetch unused columns (e.g., seller details, item descriptions), increasing memory usage and data transfer.
**Action:** Explicitly select only the columns required by the view (Column Projection) to improve query efficiency.
