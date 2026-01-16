# Bolt's Journal ⚡

## 2024-05-23 - Database Prepared Statements
**Learning:** Found multiple instances of `PDO::prepare()` being called inside loops. This forces the database to re-parse the query plan for every iteration, which is inefficient for batch inserts.
**Action:** Always verify loops performing database operations. Move `prepare()` calls outside loops and execute them with varying parameters inside the loop.
