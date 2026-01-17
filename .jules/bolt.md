## 2024-05-22 - N+1 Query in Invoice Items
**Learning:** Found an N+1 query issue in `InvoiceController::store` and `update` where invoice items are inserted one by one in a loop.
**Action:** Always look for loops performing database operations. Batch these operations into a single query where possible (e.g., `INSERT INTO ... VALUES (...), (...)`).
