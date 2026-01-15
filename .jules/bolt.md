# Bolt's Journal

## 2025-05-18 - Prepare Statement Optimization
**Learning:** Found multiple instances of `PDO::prepare` being called inside `foreach` loops in `InvoiceController.php`.
**Action:** Always move `prepare` calls outside loops to prevent database driver from re-compiling the SQL for every iteration. This reduces overhead significantly for invoices with many items.
